<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Models\PaymentModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Admission\Models\AdmissionModel;

class PaymentController extends BaseController
{
    protected $paymentModel;
    protected $invoiceModel;
    protected $admissionModel;
    
    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->admissionModel = new AdmissionModel();
    }
    
    /**
     * Display list of payments
     */
    public function index()
    {
        $perPage = 10;
        $keyword = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $method = $this->request->getGet('method');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Build filters
        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        if ($method) {
            $filters['method'] = $method;
        }
        if ($startDate) {
            $filters['start_date'] = $startDate;
        }
        if ($endDate) {
            $filters['end_date'] = $endDate;
        }
        
        // Get payments
        if ($keyword) {
            $payments = $this->paymentModel->searchPayments($keyword);
        } elseif (!empty($filters)) {
            $payments = $this->paymentModel->filterPayments($filters);
        } else {
            $payments = $this->paymentModel->paginate($perPage);
        }
        
        // Enrich with student details
        foreach ($payments as &$payment) {
            $student = $this->admissionModel->getByRegistrationNumber($payment['registration_number']);
            $payment['student'] = $student;
        }
        
        $pager = $this->paymentModel->pager;
        
        return view('Modules\Payment\Views\payments\index', [
            'title' => 'Payments',
            'payments' => $payments,
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'method' => $method,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Display payment details
     */
    public function view($id)
    {
        $payment = $this->paymentModel->find($id);
        
        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }
        
        // Get student details
        $student = $this->admissionModel->getByRegistrationNumber($payment['registration_number']);
        $payment['student'] = $student;
        
        // Get invoice if linked
        if ($payment['invoice_id']) {
            $invoice = $this->invoiceModel->find($payment['invoice_id']);
            $payment['invoice'] = $invoice;
        }
        
        return view('Modules\Payment\Views\payments\view', [
            'title' => 'Payment Details',
            'payment' => $payment,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        return view('Modules\Payment\Views\payments\create', [
            'title' => 'Create Payment',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Store new payment
     */
    public function store()
    {
        $data = [
            'registration_number' => $this->request->getPost('registration_number'),
            'invoice_id' => $this->request->getPost('invoice_id') ?: null,
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'document_number' => $this->request->getPost('document_number'),
            'payment_date' => $this->request->getPost('payment_date'),
            'status' => $this->request->getPost('status') ?: 'pending',
            'notes' => $this->request->getPost('notes')
        ];
        
        // Handle receipt file upload
        $receiptFile = $this->request->getFile('receipt_file');
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            $filePath = $this->paymentModel->uploadReceiptFile($receiptFile);
            if ($filePath) {
                $data['receipt_file'] = $filePath;
            }
        }
        
        if ($this->paymentModel->insert($data)) {
            // If linked to invoice and status is paid, update invoice status
            if ($data['invoice_id'] && $data['status'] === 'paid') {
                $this->invoiceModel->updateInvoiceStatus($data['invoice_id'], 'paid');
            }
            
            return redirect()->to('/payment')->with('success', 'Payment created successfully.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->paymentModel->errors());
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $payment = $this->paymentModel->find($id);
        
        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }
        
        // Get all approved admissions with student details for dropdown
        $students = $this->admissionModel->getAllWithDetails();
        
        // Filter only approved admissions
        $students = array_filter($students, function($student) {
            return $student['status'] === 'approved';
        });
        
        // Get unpaid invoices for dropdown
        $invoices = $this->invoiceModel->where('status', 'unpaid')->findAll();
        
        return view('Modules\Payment\Views\payments\edit', [
            'title' => 'Edit Payment',
            'payment' => $payment,
            'students' => $students,
            'invoices' => $invoices,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Update payment
     */
    public function update($id)
    {
        $payment = $this->paymentModel->find($id);
        
        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }
        
        $data = [
            'id' => $id,
            'registration_number' => $this->request->getPost('registration_number'),
            'invoice_id' => $this->request->getPost('invoice_id') ?: null,
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'document_number' => $this->request->getPost('document_number'),
            'payment_date' => $this->request->getPost('payment_date'),
            'status' => $this->request->getPost('status'),
            'notes' => $this->request->getPost('notes')
        ];
        
        // Handle receipt file upload
        $receiptFile = $this->request->getFile('receipt_file');
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            $filePath = $this->paymentModel->uploadReceiptFile($receiptFile);
            if ($filePath) {
                $data['receipt_file'] = $filePath;
            }
        }
        
        if ($this->paymentModel->save($data)) {
            // If linked to invoice and status is paid, update invoice status
            if ($data['invoice_id'] && $data['status'] === 'paid') {
                $this->invoiceModel->updateInvoiceStatus($data['invoice_id'], 'paid');
            }
            
            return redirect()->to('/payment')->with('success', 'Payment updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->paymentModel->errors());
    }

    /**
     * Revenue report
     */
    public function revenueReport()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        // Get statistics
        $stats = $this->paymentModel->getDashboardStatistics($startDate, $endDate);
        $revenueByMethod = $this->paymentModel->getRevenueByMethod();
        $revenueByType = $this->paymentModel->getRevenueByType();
        $monthlyTrend = $this->paymentModel->getMonthlyRevenueTrend(date('Y'));
        
        // Get detailed payments for export
        $payments = $this->paymentModel->filterPayments([
            'status' => 'paid',
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        return view('Modules\Payment\Views\reports\revenue', [
            'title' => 'Revenue Report',
            'stats' => $stats,
            'revenueByMethod' => $revenueByMethod,
            'revenueByType' => $revenueByType,
            'monthlyTrend' => $monthlyTrend,
            'payments' => $payments,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Overdue invoices report
     */
    public function overdueReport()
    {
        $overdueInvoices = $this->paymentModel->getOverduePayments();
        
        return view('Modules\Payment\Views\reports\overdue', [
            'title' => 'Overdue Invoices Report',
            'invoices' => $overdueInvoices,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }
    
    /**
     * Export report to CSV
     */
    public function exportCsv()
    {
        $type = $this->request->getGet('type');
        $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        if ($type === 'revenue') {
            $data = $this->paymentModel->filterPayments([
                'status' => 'paid',
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            $filename = 'revenue_report_' . date('Y-m-d') . '.csv';
        } elseif ($type === 'overdue') {
            $data = $this->paymentModel->getOverduePayments();
            $filename = 'overdue_report_' . date('Y-m-d') . '.csv';
        } else {
            return redirect()->back()->with('error', 'Invalid report type');
        }
        
        $csv = $this->paymentModel->exportToCSV($data);
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }
}
