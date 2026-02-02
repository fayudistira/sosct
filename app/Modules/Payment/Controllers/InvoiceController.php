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
            $student = $this->admissionModel->where('registration_number', $invoice['registration_number'])->first();
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
        
        // Get student details
        $student = $this->admissionModel->where('registration_number', $invoice['registration_number'])->first();
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
        // Get all students for dropdown
        $students = $this->admissionModel->findAll();
        
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
        $data = [
            'registration_number' => $this->request->getPost('registration_number'),
            'description' => $this->request->getPost('description'),
            'amount' => $this->request->getPost('amount'),
            'due_date' => $this->request->getPost('due_date'),
            'invoice_type' => $this->request->getPost('invoice_type')
        ];
        
        // Use createInvoice to auto-generate invoice number
        $id = $this->invoiceModel->createInvoice($data);
        
        if ($id) {
            return redirect()->to('/invoice')->with('success', 'Invoice created successfully.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->invoiceModel->errors());
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }
        
        // Get all students for dropdown
        $students = $this->admissionModel->findAll();
        
        return view('Modules\Payment\Views\invoices\edit', [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'students' => $students,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Update invoice
     */
    public function update($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }
        
        $data = [
            'id' => $id,
            'registration_number' => $this->request->getPost('registration_number'),
            'description' => $this->request->getPost('description'),
            'amount' => $this->request->getPost('amount'),
            'due_date' => $this->request->getPost('due_date'),
            'invoice_type' => $this->request->getPost('invoice_type'),
            'status' => $this->request->getPost('status')
        ];
        
        if ($this->invoiceModel->save($data)) {
            return redirect()->to('/invoice')->with('success', 'Invoice updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->invoiceModel->errors());
    }
    
    /**
     * Generate and download invoice PDF
     */
    public function downloadPdf($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/invoice')->with('error', 'Invoice not found.');
        }
        
        // Get student details
        $student = $this->admissionModel->where('registration_number', $invoice['registration_number'])->first();
        
        // Prepare invoice data for PDF
        $invoiceData = $invoice;
        $invoiceData['student_name'] = $student['full_name'] ?? 'N/A';
        
        // Generate PDF
        $pdfGenerator = new PdfGenerator();
        $filePath = $pdfGenerator->generateInvoicePdf($invoiceData);
        
        if (!$filePath) {
            return redirect()->back()->with('error', 'Failed to generate PDF.');
        }
        
        // Download PDF
        $fullPath = WRITEPATH . 'uploads/' . $filePath;
        return $this->response->download($fullPath, null)->setFileName('invoice_' . $invoice['invoice_number'] . '.pdf');
    }
}
