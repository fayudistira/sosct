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

        return view('Modules\Payment\Views\invoices\view', [
            'title' => 'Invoice Details',
            'invoice' => $invoice,
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
        $items = $this->request->getPost('items');

        // Validate that items exist
        if (!$items || !is_array($items)) {
            return redirect()->back()->withInput()->with('error', 'Please add at least one line item.');
        }

        // Filter and validate items, calculate total
        $validItems = [];
        $totalAmount = 0;

        foreach ($items as $item) {
            if (!empty($item['description']) && !empty($item['amount'])) {
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
            return redirect()->back()->withInput()->with('error', 'Please add at least one valid line item with amount greater than zero.');
        }

        if ($action === 'extend') {
            // EXTEND EXISTING INVOICE
            $invoiceId = $this->request->getPost('invoice_id');

            if (!$invoiceId) {
                return redirect()->back()->withInput()->with('error', 'Please select an invoice to extend.');
            }

            // Extend the invoice (updates the existing record)
            if ($this->invoiceModel->extendInvoice($invoiceId, $validItems)) {
                return redirect()->to('/invoice/view/' . $invoiceId)
                    ->with('success', 'Invoice extended successfully.');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to extend invoice. The invoice may not be extendable.');
        } else {
            // CREATE NEW INVOICE
            $invoiceData = [
                'registration_number' => $this->request->getPost('registration_number'),
                'description' => 'Invoice with ' . count($validItems) . ' item(s)',
                'amount' => $totalAmount,
                'due_date' => $this->request->getPost('due_date'),
                'invoice_type' => $this->request->getPost('invoice_type'),
                'items' => $this->invoiceModel->encodeItems($validItems)
            ];

            // Create invoice with items
            $invoiceId = $this->invoiceModel->createInvoice($invoiceData);

            if ($invoiceId) {
                return redirect()->to('/invoice')->with('success', 'Invoice created successfully.');
            }

            return redirect()->back()->withInput()->with('errors', $this->invoiceModel->errors());
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

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student
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

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student
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

        // Verify the email matches the token email
        if ($student['email'] !== $tokenData['email']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Unauthorized access to invoice');
        }

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student
        ]);
    }
}
