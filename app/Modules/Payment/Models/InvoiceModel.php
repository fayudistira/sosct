<?php

namespace Modules\Payment\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'invoice_number',
        'registration_number',
        'contract_number',
        'installment_id',
        'description',
        'amount',
        'due_date',
        'invoice_type',
        'status',
        'items',
        'parent_invoice_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'registration_number' => 'required|max_length[20]',
        'contract_number' => 'permit_empty|max_length[20]',
        'installment_id' => 'permit_empty|is_natural',
        'description' => 'required|min_length[3]',
        'amount' => 'required|decimal|greater_than[0]',
        'due_date' => 'required|valid_date',
        'invoice_type' => 'required|in_list[registration_fee,tuition_fee,miscellaneous_fee]',
        'status' => 'permit_empty|in_list[unpaid,paid,cancelled,expired,partially_paid,extended]'
    ];

    /**
     * Update all unpaid invoices that are past their due date to 'expired'
     * 
     * @return int Number of invoices updated
     */
    public function processExpiredInvoices(): int
    {
        return $this->where('status', 'unpaid')
            ->where('due_date <', date('Y-m-d'))
            ->set(['status' => 'expired'])
            ->update();
    }

    protected $validationMessages = [
        'registration_number' => [
            'required' => 'Registration number is required'
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 10 characters'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'due_date' => [
            'required' => 'Due date is required',
            'valid_date' => 'Due date must be a valid date'
        ],
        'invoice_type' => [
            'required' => 'Invoice type is required',
            'in_list' => 'Invoice type must be registration_fee, tuition_fee, or miscellaneous_fee'
        ]
    ];

    /**
     * Generate unique invoice number
     * Format: INV-YYYYMMDD-HHMMSS-ID (e.g., INV-20260211-150901-123)
     *
     * @return string
     */
    public function generateInvoiceNumber(): string
    {
        $now = new \DateTime();
        $datePart = $now->format('Ymd');
        $timePart = $now->format('His');

        // Get the next invoice ID
        $db = \Config\Database::connect();
        $nextId = $db->table($this->table)
            ->selectMax('id')
            ->get()
            ->getRowArray();

        $invoiceId = ($nextId['id'] ?? 0) + 1;

        return "INV-{$datePart}-{$timePart}-{$invoiceId}";
    }

    /**
     * Create invoice with auto-generated invoice number
     * 
     * @param array $data Invoice data
     * @return int|false Invoice ID or false on failure
     */
    public function createInvoice(array $data)
    {
        // Generate invoice number if not provided
        if (!isset($data['invoice_number'])) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
        }

        // Set default status to unpaid
        if (!isset($data['status'])) {
            $data['status'] = 'unpaid';
        }

        // Run validation before insert
        if (!$this->validate($data)) {
            log_message('error', 'Invoice validation failed: ' . print_r($this->errors(), true));
            return false;
        }

        // Attempt to insert
        $result = $this->insert($data);

        if (!$result) {
            log_message('error', 'Invoice insert failed: ' . print_r($this->errors(), true));
            return false;
        }

        return (int) $this->insertID();
    }

    /**
     * Get invoices by student registration number
     *
     * @param string $registrationNumber Student registration number
     * @return array
     */
    public function getInvoicesByStudent(string $registrationNumber): array
    {
        log_message('debug', '=== getInvoicesByStudent START ===');
        log_message('debug', 'Querying invoices for registration_number: ' . $registrationNumber);

        $invoices = $this->where('registration_number', $registrationNumber)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        log_message('debug', 'Query returned ' . count($invoices) . ' invoices');

        // Log the last query for debugging
        $db = \Config\Database::connect();
        $lastQuery = $db->getLastQuery();
        log_message('debug', 'Last Query: ' . $lastQuery->getQuery());

        log_message('debug', '=== getInvoicesByStudent END ===');

        return $invoices;
    }

    public function getOverdueInvoices(): array
    {
        return $this->where('status', 'unpaid')
            ->where('due_date <', date('Y-m-d'))
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }

    /**
     * Search invoices by keyword
     * Search by invoice_number or student name
     * 
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchInvoices(string $keyword): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        return $builder->select('invoices.*')
            ->join('admissions', 'admissions.registration_number = invoices.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->where('invoices.deleted_at', null)
            ->groupStart()
            ->like('invoices.invoice_number', $keyword)
            ->orLike('profiles.full_name', $keyword)
            ->groupEnd()
            ->orderBy('invoices.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Filter invoices by multiple criteria
     * 
     * @param array $filters Filter criteria (status, type, start_date, end_date)
     * @return array
     */
    public function filterInvoices(array $filters): array
    {
        $builder = $this->builder();

        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $builder->where('invoice_type', $filters['type']);
        }

        if (isset($filters['start_date'])) {
            $builder->where('due_date >=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $builder->where('due_date <=', $filters['end_date']);
        }

        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Recalculate and update the status of an invoice based on its payments
     * 
     * @param int $invoiceId Invoice ID
     * @return string The new status
     */
    public function recalculateInvoiceStatus(int $invoiceId): string
    {
        $invoice = $this->find($invoiceId);
        if (!$invoice) return 'unpaid';

        // Get sum of all 'paid' payments for this invoice
        $db = \Config\Database::connect();
        $paidAmount = $db->table('payments')
            ->where('invoice_id', $invoiceId)
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->selectSum('amount')
            ->get()
            ->getRowArray();

        $totalPaid = (float) ($paidAmount['amount'] ?? 0);
        $invoiceAmount = (float) $invoice['amount'];

        $newStatus = 'unpaid';
        if ($totalPaid >= $invoiceAmount) {
            $newStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $newStatus = 'partially_paid';
        }

        // If it was cancelled or expired, and now has payments, we update it
        // but if it's currently paid we don't downgrade it unless payments were deleted/changed
        $this->update($invoiceId, ['status' => $newStatus]);

        return $newStatus;
    }

    /**
     * Update invoice status
     * 
     * @param int $id Invoice ID
     * @param string $status New status
     * @return bool
     */
    public function updateInvoiceStatus(int $id, string $status): bool
    {
        // For 'paid' or 'unpaid' requests, we recalculate to be safe
        if ($status === 'paid' || $status === 'unpaid') {
            $this->recalculateInvoiceStatus($id);
            return true;
        }
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Get invoice with associated payments
     *
     * @param int $id Invoice ID
     * @return array|null
     */
    public function getInvoiceWithPayments(int $id): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $invoice = $builder->select('invoices.*')
            ->where('invoices.id', $id)
            ->where('invoices.deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$invoice) {
            return null;
        }

        // Get associated payments
        $paymentsBuilder = $db->table('payments');
        $payments = $paymentsBuilder->where('invoice_id', $id)
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();

        $invoice['payments'] = $payments;

        // Calculate total paid from associated payments
        $totalPaid = 0;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'paid') {
                $totalPaid += (float) $payment['amount'];
            }
        }
        $invoice['total_paid'] = $totalPaid;

        return $invoice;
    }

    /**
     * Get invoice with associated items
     *
     * @param int $id Invoice ID
     * @return array|null
     */
    public function getInvoiceWithItems(int $id): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $invoice = $builder->select('invoices.*')
            ->where('invoices.id', $id)
            ->where('invoices.deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$invoice) {
            return null;
        }

        // Get associated items from JSON
        $items = !empty($invoice['items']) ? json_decode($invoice['items'], true) : [];
        $invoice['items'] = $items;

        // Get associated payments and calculate total paid
        $paymentsBuilder = $db->table('payments');
        $payments = $paymentsBuilder->where('invoice_id', $id)
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();

        $totalPaid = 0;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'paid') {
                $totalPaid += (float) $payment['amount'];
            }
        }
        $invoice['total_paid'] = $totalPaid;

        return $invoice;
    }

    /**
     * Encode items array to JSON
     */
    public function encodeItems(array $items): string
    {
        return json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Decode items JSON to array
     */
    public function decodeItems(?string $itemsJson): array
    {
        if (empty($itemsJson)) {
            return [];
        }

        $decoded = json_decode($itemsJson, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get items from an invoice
     */
    public function getItems(int $invoiceId): array
    {
        $invoice = $this->find($invoiceId);
        if (!$invoice) {
            return [];
        }

        return $this->decodeItems($invoice['items'] ?? null);
    }

    /**
     * Save items for an invoice
     */
    public function saveItems(int $invoiceId, array $items): bool
    {
        $encoded = $this->encodeItems($items);
        return $this->update($invoiceId, ['items' => $encoded]);
    }

    /**
     * Get unpaid or partially_paid invoices for a student that can be extended
     *
     * @param string $registrationNumber Student registration number
     * @return array
     */
    public function getExtendableInvoices(string $registrationNumber): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Extend an existing invoice by adding new items (informational only, does not change the invoice amount)
     *
     * @param int $invoiceId Invoice ID to extend
     * @param array $newItems New items to add (for informational purposes only)
     * @param string|null $newDescription Optional new description
     * @return bool
     */
    public function extendInvoice(int $invoiceId, array $newItems, ?string $newDescription = null): bool
    {
        $invoice = $this->find($invoiceId);

        if (!$invoice) {
            return false;
        }

        // Check if invoice can be extended
        if (!in_array($invoice['status'], ['unpaid', 'partially_paid'])) {
            return false;
        }

        // Get existing items
        $existingItems = $this->decodeItems($invoice['items'] ?? null);

        // Merge existing and new items (for tracking/informational purposes)
        $allItems = array_merge($existingItems, $newItems);

        // Prepare update data - NOTE: We do NOT update the amount
        // The amount remains the same as the original invoice amount
        $updateData = [
            'items' => $this->encodeItems($allItems)
        ];

        // Update description if provided
        if ($newDescription) {
            $updateData['description'] = $newDescription;
        }

        return $this->update($invoiceId, $updateData);
    }

    /**
     * Get the extension history for an invoice
     *
     * @param int $invoiceId Invoice ID
     * @return array
     */
    public function getInvoiceExtensionHistory(int $invoiceId): array
    {
        $history = [];
        $currentId = $invoiceId;

        while ($currentId) {
            $invoice = $this->find($currentId);
            if (!$invoice) {
                break;
            }

            $history[] = [
                'id' => $invoice['id'],
                'invoice_number' => $invoice['invoice_number'],
                'amount' => $invoice['amount'],
                'status' => $invoice['status'],
                'created_at' => $invoice['created_at']
            ];

            $currentId = $invoice['parent_invoice_id'] ?? null;
        }

        return $history;
    }

    /**
     * Get extended invoice summary for display when extending an invoice
     *
     * @param int $invoiceId Invoice ID to extend
     * @return array|null Summary data or null if invoice not found
     */
    public function getExtendedInvoiceSummary(int $invoiceId): ?array
    {
        $invoice = $this->find($invoiceId);
        if (!$invoice) {
            return null;
        }

        $db = \Config\Database::connect();

        // Get admission details with program information
        $admission = $db->table('admissions')
            ->select('
                admissions.program_id,
                programs.tuition_fee,
                programs.registration_fee
            ')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', $invoice['registration_number'])
            ->first();

        if (!$admission) {
            return null;
        }

        // Get sum of all paid payments for this student
        $totalPaidResult = $db->table('payments')
            ->selectSum('amount', 'total')
            ->where('registration_number', $invoice['registration_number'])
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        $totalPaid = (float) ($totalPaidResult['total'] ?? 0);

        // Calculate values
        $initialProgramAmount = (float) ($admission['tuition_fee'] ?? 0);
        $registrationFee = (float) ($admission['registration_fee'] ?? 0);
        $totalInitialAmount = $initialProgramAmount + $registrationFee;
        $outstandingBalance = $totalInitialAmount - $totalPaid;

        // Get current invoice items
        $currentItems = $this->decodeItems($invoice['items'] ?? null);

        return [
            'invoice_id' => $invoiceId,
            'invoice_number' => $invoice['invoice_number'],
            'registration_number' => $invoice['registration_number'],
            'initial_program_amount' => $initialProgramAmount,
            'registration_fee' => $registrationFee,
            'total_initial_amount' => $totalInitialAmount,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $outstandingBalance,
            'current_invoice_amount' => (float) $invoice['amount'],
            'current_items' => $currentItems,
            'invoice_status' => $invoice['status']
        ];
    }

    /**
     * Get paginated invoices for a specific student
     *
     * @param string $registrationNumber Student registration number
     * @param int $perPage Number of records per page
     * @return array
     */
    public function getStudentInvoices(string $registrationNumber, int $perPage = 10): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Search invoices for a specific student
     *
     * @param string $registrationNumber Student registration number
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchStudentInvoices(string $registrationNumber, string $keyword): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->groupStart()
            ->like('invoice_number', $keyword)
            ->orLike('description', $keyword)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get invoice with installment details
     *
     * @param int $id Invoice ID
     * @return array|null
     */
    public function getInvoiceWithInstallment(int $id): ?array
    {
        $invoice = $this->find($id);
        if (!$invoice) {
            return null;
        }

        // Get installment details if linked
        if (!empty($invoice['installment_id'])) {
            $installmentModel = new InstallmentModel();
            $installment = $installmentModel->find($invoice['installment_id']);
            $invoice['installment'] = $installment;
        } else {
            $invoice['installment'] = null;
        }

        return $invoice;
    }

    /**
     * Get extended invoice history (parent and child invoices)
     *
     * @param int $invoiceId Invoice ID
     * @return array
     */
    public function getInvoiceHistory(int $invoiceId): array
    {
        $history = [];
        $currentId = $invoiceId;

        // Get root invoice (follow parent chain up)
        while ($currentId) {
            $invoice = $this->find($currentId);
            if (!$invoice) {
                break;
            }

            if (empty($invoice['parent_invoice_id'])) {
                // This is the root, add it first
                array_unshift($history, [
                    'id' => $invoice['id'],
                    'invoice_number' => $invoice['invoice_number'],
                    'amount' => $invoice['amount'],
                    'status' => $invoice['status'],
                    'created_at' => $invoice['created_at'],
                    'is_parent' => true
                ]);
                break;
            }
            $currentId = $invoice['parent_invoice_id'];
        }

        // Get all child invoices
        $children = $this->where('parent_invoice_id', $invoiceId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        foreach ($children as $child) {
            $history[] = [
                'id' => $child['id'],
                'invoice_number' => $child['invoice_number'],
                'amount' => $child['amount'],
                'status' => $child['status'],
                'created_at' => $child['created_at'],
                'is_parent' => false
            ];
        }

        return $history;
    }
}
