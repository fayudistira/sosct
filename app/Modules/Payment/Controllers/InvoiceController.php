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
            $student = $this->admissionModel->getByRegistrationNumber($invoice['registration_number']);
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
            ->where('admissions.registration_number', $invoice['registration_number'])
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
     * Store new invoice
     */
    public function store()
    {
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

        // Create invoice with total amount
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

        if ($invoice['status'] !== 'outstanding') {
            return redirect()->to('/invoice')->with('error', 'Only outstanding invoices can be cancelled.');
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
            ->where('admissions.registration_number', $invoice['registration_number'])
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

        // Generate public URL for invoice
        $publicUrl = base_url('invoice/public/' . $id);

        // Create QR code using Builder
        $result = \Endroid\QrCode\Builder\Builder::create()
            ->data($publicUrl)
            ->size(300)
            ->margin(10)
            ->build();

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
            ->where('admissions.registration_number', $invoice['registration_number'])
            ->first();

        return view('Modules\Payment\Views\invoices\print', [
            'invoice' => $invoice,
            'student' => $student
        ]);
    }
}
