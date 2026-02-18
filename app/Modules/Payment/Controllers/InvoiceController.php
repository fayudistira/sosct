<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Models\InvoiceModel;
use Modules\Admission\Models\AdmissionModel;
use Modules\Payment\Libraries\PdfGenerator;

class InvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Display list of invoices
     */
    public function index()
    {
        // Auto-expire invoices that are past due
        $this->invoiceModel->processExpiredInvoices();

        $perPage = 10;
        $keyword = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $type = $this->request->getGet('type');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Build filters
        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        if ($type) {
            $filters['type'] = $type;
        }
        if ($startDate) {
            $filters['start_date'] = $startDate;
        }
        if ($endDate) {
            $filters['end_date'] = $endDate;
        }

        // Get invoices
        if ($keyword) {
            $invoices = $this->invoiceModel->searchInvoices($keyword);
        } elseif (!empty($filters)) {
            $invoices = $this->invoiceModel->filterInvoices($filters);
        } else {
            $invoices = $this->invoiceModel->paginate($perPage);
        }

        // Enrich with student details
        foreach ($invoices as &$invoice) {
            $student = $this->admissionModel->getByRegistrationNumber((string)$invoice['registration_number']);
            $invoice['student'] = $student;
        }

        $pager = $this->invoiceModel->pager;

        return view('Modules\Payment\Views\invoices\index', [
            'title' => 'Invoices',
            'invoices' => $invoices,
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display invoice details
     */
    public function view($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithPayments($id);

        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }

        // Get student details with profile data
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$invoice['registration_number'])
            ->first();

        $invoice['student'] = $student;

        // Get parent invoice (if this is an extending invoice)
        $parentInvoice = null;
        if (!empty($invoice['parent_invoice_id'])) {
            $parentInvoice = $this->invoiceModel->find($invoice['parent_invoice_id']);
        }

        // Get child invoice (if this invoice was extended)
        $childInvoice = $this->invoiceModel->getChildInvoice($id);

        return view('Modules\Payment\Views\invoices\view', [
            'title' => 'Invoice Details',
            'invoice' => $invoice,
            'parentInvoice' => $parentInvoice,
            'childInvoice' => $childInvoice,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get all approved admissions with student details for dropdown
        $students = $this->admissionModel->getAllWithDetails();

        // Filter only approved admissions
        $students = array_filter($students, function ($student) {
            return $student['status'] === 'approved';
        });

        return view('Modules\Payment\Views\invoices\create', [
            'title' => 'Create Invoice',
            'students' => $students,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Show extend invoice form
     */
    public function extend()
    {
        // Get all approved admissions with student details for dropdown
        $students = $this->admissionModel->getAllWithDetails();

        // Filter only approved admissions
        $students = array_filter($students, function ($student) {
            return $student['status'] === 'approved';
        });

        return view('Modules\Payment\Views\invoices\extend', [
            'title' => 'Extend Invoice',
            'students' => $students,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Get unpaid/partially_paid invoices for a student (AJAX endpoint)
     */
    public function getStudentInvoices()
    {
        $registrationNumber = $this->request->getGet('registration_number');

        if (!$registrationNumber) {
            return $this->response->setJSON(['error' => 'Registration number is required']);
        }

        $invoices = $this->invoiceModel->getExtendableInvoices($registrationNumber);

        return $this->response->setJSON(['invoices' => $invoices]);
    }

    /**
     * Get extended invoice summary (AJAX endpoint)
     */
    public function getInvoiceSummary()
    {
        $invoiceId = $this->request->getGet('invoice_id');

        if (!$invoiceId) {
            return $this->response->setJSON(['error' => 'Invoice ID is required']);
        }

        $summary = $this->invoiceModel->getExtendedInvoiceSummary($invoiceId);

        if (!$summary) {
            return $this->response->setJSON(['error' => 'Invoice not found or no program data available']);
        }

        return $this->response->setJSON(['summary' => $summary]);
    }

    /**
     * Store new invoice or extend existing invoice
     */
    public function store()
    {
        $action = $this->request->getPost('action'); // 'new' or 'extend'

        // Default to 'new' if action is not set (e.g., when JavaScript is disabled)
        if (empty($action)) {
            $action = 'new';
        }

        $items = $this->request->getPost('items');

        // Log for debugging
        log_message('debug', 'Invoice store action: ' . $action);
        log_message('debug', 'Items data: ' . print_r($items, true));

        // Validate that items exist
        if (!$items || !is_array($items) || empty($items)) {
            log_message('error', 'Invoice store failed: No items submitted');
            return redirect()->back()->withInput()->with('error', 'Silakan tambahkan minimal satu item faktur.');
        }

        // Filter and validate items, calculate total
        $validItems = [];
        $totalAmount = 0;

        foreach ($items as $key => $item) {
            if (!isset($item['description']) || !isset($item['amount'])) {
                continue;
            }
            if (!empty(trim($item['description'])) && !empty($item['amount'])) {
                $amount = (float) $item['amount'];
                if ($amount > 0) {
                    $validItems[] = [
                        'description' => trim($item['description']),
                        'amount' => $amount
                    ];
                    $totalAmount += $amount;
                }
            }
        }

        // Validate total amount
        if (empty($validItems) || $totalAmount <= 0) {
            log_message('error', 'Invoice store failed: No valid items with amount > 0');
            return redirect()->back()->withInput()->with('error', 'Silakan tambahkan minimal satu item dengan jumlah yang valid.');
        }

        if ($action === 'extend') {
            // EXTEND EXISTING INVOICE
            $invoiceId = $this->request->getPost('invoice_id');
            $dueDate = $this->request->getPost('due_date');

            if (!$invoiceId) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih faktur untuk diperpanjang.');
            }

            // Extend the invoice (creates a new invoice with link to original)
            $newInvoiceId = $this->invoiceModel->extendInvoice($invoiceId, $validItems, $dueDate);

            if ($newInvoiceId) {
                return redirect()->to('/invoice/view/' . $newInvoiceId)
                    ->with('success', 'Faktur berhasil diperpanjang. Faktur baru #' . $this->invoiceModel->find($newInvoiceId)['invoice_number'] . ' telah dibuat.');
            }

            return redirect()->back()->withInput()->with('error', 'Gagal memperpanjang faktur. Faktur mungkin tidak dapat diperpanjang.');
        } else {
            // CREATE NEW INVOICE
            $registrationNumber = $this->request->getPost('registration_number');
            $invoiceType = $this->request->getPost('invoice_type');
            $dueDate = $this->request->getPost('due_date');

            // Validate required fields for new invoice
            if (empty($registrationNumber)) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih siswa.');
            }

            if (empty($invoiceType)) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih jenis faktur.');
            }

            if (empty($dueDate)) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih tanggal jatuh tempo.');
            }

            $invoiceData = [
                'registration_number' => $registrationNumber,
                'description' => 'Invoice with ' . count($validItems) . ' item(s) - ' . $validItems[0]['description'],
                'amount' => $totalAmount,
                'due_date' => $dueDate,
                'invoice_type' => $invoiceType,
                'items' => $this->invoiceModel->encodeItems($validItems)
            ];

            log_message('debug', 'Creating invoice with data: ' . print_r($invoiceData, true));

            // Create invoice with items
            $invoiceId = $this->invoiceModel->createInvoice($invoiceData);

            if ($invoiceId) {
                log_message('info', 'Invoice created successfully with ID: ' . $invoiceId);
                return redirect()->to('/invoice')->with('success', 'Faktur berhasil dibuat.');
            }

            // Get validation errors
            $errors = $this->invoiceModel->errors();
            log_message('error', 'Invoice creation failed with errors: ' . print_r($errors, true));

            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    /**
     * Show edit form (Disabled)
     */
    public function edit($id)
    {
        return redirect()->to('/invoice')->with('error', 'Invoices cannot be edited once issued. Please cancel and recreate if needed.');
    }

    /**
     * Update invoice (Disabled)
     */
    public function update($id)
    {
        return redirect()->to('/invoice')->with('error', 'Invoices cannot be edited manually. Status and amounts are updated automatically via the Payment module.');
    }

    /**
     * Cancel an invoice
     */
    public function cancel($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }

        if ($invoice['status'] !== 'unpaid') {
            return redirect()->to('/invoice')->with('error', 'Only unpaid invoices can be cancelled.');
        }

        if ($this->invoiceModel->updateInvoiceStatus($id, 'cancelled')) {
            return redirect()->to('/invoice')->with('success', 'Invoice #' . $invoice['invoice_number'] . ' has been cancelled.');
        }

        return redirect()->to('/invoice')->with('error', 'Failed to cancel invoice.');
    }

    /**
     * Display printable invoice (can be saved as PDF from browser)
     */
    public function downloadPdf($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithItems($id);

        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }

        // Get student details with profile data
        $student = $this->admissionModel->select('
                admissions.registration_number,
                admissions.program_id,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category,
                programs.tuition_fee,
                programs.registration_fee as program_registration_fee
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$invoice['registration_number'])
            ->first();

        // Get installment/contract data for tuition_fee invoices
        $installment = null;
        $invoiceHistory = null;
        $totalPaid = 0;

        if ($invoice['invoice_type'] === 'tuition_fee') {
            // Get installment data
            $installmentModel = new \Modules\Payment\Models\InstallmentModel();
            $installment = $installmentModel->where('registration_number', $invoice['registration_number'])->first();

            // Get total paid from all payments for this student
            $db = \Config\Database::connect();
            $totalPaidResult = $db->table('payments')
                ->selectSum('amount', 'total')
                ->where('registration_number', $invoice['registration_number'])
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();
            $totalPaid = (float) ($totalPaidResult['total'] ?? 0);

            // Get invoice history (parent and child invoices)
            $invoiceHistory = $this->invoiceModel->getInvoiceHistory($id);
        }

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student,
            'installment' => $installment,
            'invoiceHistory' => $invoiceHistory,
            'totalPaid' => $totalPaid
        ]);
    }

    /**
     * Generate QR code for invoice
     */
    public function generateQr($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            return $this->response->setStatusCode(404)->setBody('Invoice not found');
        }

        // Get student email for token generation
        $student = $this->admissionModel->select('profiles.email')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->where('admissions.registration_number', (string)$invoice['registration_number'])
            ->first();

        if (!$student || empty($student['email'])) {
            // Fallback to public URL if no email available
            $publicUrl = base_url('invoice/public/' . $id);
        } else {
            // Generate secure token for invoice
            $emailService = new \App\Services\EmailService();
            $token = $emailService->generateInvoiceToken($id, $student['email']);
            $publicUrl = base_url('invoice/secure/' . $token);
        }

        // Create QR code using Builder (v6.0 API)
        $builder = new \Endroid\QrCode\Builder\Builder(
            data: $publicUrl,
            size: 300,
            margin: 10
        );
        $result = $builder->build();

        // Return QR code image
        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setBody($result->getString());
    }

    /**
     * Public invoice view (no authentication required)
     */
    public function publicView($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithItems($id);

        if (!$invoice) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invoice not found');
        }

        // Get student details with profile data
        $student = $this->admissionModel->select('
                admissions.registration_number,
                admissions.program_id,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category,
                programs.tuition_fee,
                programs.registration_fee as program_registration_fee
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$invoice['registration_number'])
            ->first();

        // Get installment/contract data for tuition_fee invoices
        $installment = null;
        $invoiceHistory = null;
        $totalPaid = 0;

        if ($invoice['invoice_type'] === 'tuition_fee') {
            // Get installment data
            $installmentModel = new \Modules\Payment\Models\InstallmentModel();
            $installment = $installmentModel->where('registration_number', $invoice['registration_number'])->first();

            // Get total paid from all payments for this student
            $db = \Config\Database::connect();
            $totalPaidResult = $db->table('payments')
                ->selectSum('amount', 'total')
                ->where('registration_number', $invoice['registration_number'])
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();
            $totalPaid = (float) ($totalPaidResult['total'] ?? 0);

            // Get invoice history (parent and child invoices)
            $invoiceHistory = $this->invoiceModel->getInvoiceHistory($id);
        }

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student,
            'installment' => $installment,
            'invoiceHistory' => $invoiceHistory,
            'totalPaid' => $totalPaid
        ]);
    }

    /**
     * Secure invoice view using encrypted token (no authentication required)
     */
    public function secureView($token)
    {
        // Load EmailService for token verification
        $emailService = new \App\Services\EmailService();
        $tokenData = $emailService->verifyInvoiceToken($token);

        if (!$tokenData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid or expired invoice link');
        }

        // Get invoice using the decrypted invoice ID
        $invoice = $this->invoiceModel->getInvoiceWithItems($tokenData['invoice_id']);

        if (!$invoice) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invoice not found');
        }

        // Verify the email matches the invoice registration
        $student = $this->admissionModel->select('
                admissions.registration_number,
                admissions.program_id,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category,
                programs.tuition_fee,
                programs.registration_fee as program_registration_fee
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$invoice['registration_number'])
            ->first();

        // Verify the email matches the token email
        if ($student['email'] !== $tokenData['email']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Unauthorized access to invoice');
        }

        // Get installment/contract data for tuition_fee invoices
        $installment = null;
        $invoiceHistory = null;
        $totalPaid = 0;

        if ($invoice['invoice_type'] === 'tuition_fee') {
            // Get installment data
            $installmentModel = new \Modules\Payment\Models\InstallmentModel();
            $installment = $installmentModel->where('registration_number', $invoice['registration_number'])->first();

            // Get total paid from all payments for this student
            $db = \Config\Database::connect();
            $totalPaidResult = $db->table('payments')
                ->selectSum('amount', 'total')
                ->where('registration_number', $invoice['registration_number'])
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();
            $totalPaid = (float) ($totalPaidResult['total'] ?? 0);

            // Get invoice history (parent and child invoices)
            $invoiceHistory = $this->invoiceModel->getInvoiceHistory($invoice['id']);
        }

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student,
            'installment' => $installment,
            'invoiceHistory' => $invoiceHistory,
            'totalPaid' => $totalPaid
        ]);
    }

    /**
     * Re-issue invoice for remaining balance
     * Creates a new invoice with remaining amount and marks old invoice as extended
     */
    public function reissue($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }

        // Only partially_paid or expired invoices can be re-issued
        if (!in_array($invoice['status'], ['partially_paid', 'expired'])) {
            return redirect()->to('/invoice/view/' . $id)->with('error', 'Only partially paid or expired invoices can be re-issued.');
        }

        // Get installment details
        $installmentModel = new \Modules\Payment\Models\InstallmentModel();
        $installment = null;

        if (!empty($invoice['installment_id'])) {
            $installment = $installmentModel->find($invoice['installment_id']);
        }

        // Calculate remaining balance
        if ($installment) {
            $remainingBalance = (float) $installment['remaining_balance'];
        } else {
            // Fallback: calculate from invoice amount and payments
            $paymentModel = new \Modules\Payment\Models\PaymentModel();
            $payments = $paymentModel->where('invoice_id', $id)
                ->where('status', 'paid')
                ->findAll();
            $totalPaid = array_sum(array_column($payments, 'amount'));
            $remainingBalance = (float) $invoice['amount'] - $totalPaid;
        }

        if ($remainingBalance <= 0) {
            return redirect()->to('/invoice/view/' . $id)->with('error', 'Invoice is already fully paid. Cannot re-issue.');
        }

        // Show confirmation form
        return view('Modules\Payment\Views\invoices\reissue', [
            'title' => 'Re-issue Invoice',
            'invoice' => $invoice,
            'installment' => $installment,
            'remainingBalance' => $remainingBalance,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Process re-issue invoice
     */
    public function processReissue()
    {
        $id = $this->request->getPost('invoice_id');
        $newDueDate = $this->request->getPost('due_date');
        $notes = $this->request->getPost('notes');

        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }

        // Get installment details
        $installmentModel = new \Modules\Payment\Models\InstallmentModel();
        $installment = null;

        if (!empty($invoice['installment_id'])) {
            $installment = $installmentModel->find($invoice['installment_id']);
        }

        // Calculate remaining balance
        if ($installment) {
            $remainingBalance = (float) $installment['remaining_balance'];
        } else {
            $paymentModel = new \Modules\Payment\Models\PaymentModel();
            $payments = $paymentModel->where('invoice_id', $id)
                ->where('status', 'paid')
                ->findAll();
            $totalPaid = array_sum(array_column($payments, 'amount'));
            $remainingBalance = (float) $invoice['amount'] - $totalPaid;
        }

        if ($remainingBalance <= 0) {
            return redirect()->to('/invoice/view/' . $id)->with('error', 'Invoice is already fully paid. Cannot re-issue.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Update old invoice status to 'extended'
            $this->invoiceModel->update($id, [
                'status' => 'extended',
                'description' => $invoice['description'] . ' (Extended - Replaced by new invoice)'
            ]);

            // 2. Create new invoice for remaining balance
            $newInvoiceData = [
                'registration_number' => $invoice['registration_number'],
                'contract_number' => $invoice['contract_number'] ?? $invoice['registration_number'],
                'installment_id' => $invoice['installment_id'],
                'parent_invoice_id' => $id,
                'description' => 'Remaining Balance - ' . ($invoice['description'] ?? 'Invoice'),
                'amount' => $remainingBalance,
                'due_date' => $newDueDate,
                'invoice_type' => $invoice['invoice_type'],
                'status' => 'unpaid',
                'items' => json_encode([
                    [
                        'description' => 'Remaining Balance from Invoice ' . $invoice['invoice_number'],
                        'amount' => $remainingBalance
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ];

            $newInvoiceId = $this->invoiceModel->createInvoice($newInvoiceData);

            if (!$newInvoiceId) {
                throw new \Exception('Failed to create new invoice');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return redirect()->to('/invoice/view/' . $newInvoiceId)->with('success', 'Invoice re-issued successfully. Old invoice marked as extended.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/invoice/view/' . $id)->with('error', 'Failed to re-issue invoice: ' . $e->getMessage());
        }
    }
}
