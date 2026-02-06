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
        'description',
        'amount',
        'due_date',
        'invoice_type',
        'status',
        'items'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'registration_number' => 'required|max_length[20]',
        'description' => 'required|min_length[10]',
        'amount' => 'required|decimal|greater_than[0]',
        'due_date' => 'required|valid_date',
        'invoice_type' => 'required|in_list[registration_fee,tuition_fee,miscellaneous_fee]',
        'status' => 'permit_empty|in_list[outstanding,paid,cancelled,expired,partially_paid]'
    ];

    /**
     * Update all unpaid invoices that are past their due date to 'expired'
     * 
     * @return int Number of invoices updated
     */
    public function processExpiredInvoices(): int
    {
        return $this->where('status', 'outstanding')
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
     * Format: INV-YYYY-NNNN (e.g., INV-2026-0001)
     * 
     * @return string
     */
    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        // Get the last invoice number for current year
        $lastRecord = $this->like('invoice_number', $prefix)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastRecord) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRecord['invoice_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First invoice of the year
            $newNumber = 1;
        }

        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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

        // Set default status to outstanding
        if (!isset($data['status'])) {
            $data['status'] = 'outstanding';
        }

        return $this->insert($data);
    }

    /**
     * Get invoices by student registration number
     * 
     * @param string $registrationNumber Student registration number
     * @return array
     */
    public function getInvoicesByStudent(string $registrationNumber): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getOverdueInvoices(): array
    {
        return $this->where('status', 'outstanding')
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
        if (!$invoice) return 'outstanding';

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

        $newStatus = 'outstanding';
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
        // For 'paid' or 'outstanding' requests, we recalculate to be safe
        if ($status === 'paid' || $status === 'outstanding') {
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
}
