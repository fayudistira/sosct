<?php

namespace Modules\Payment\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Payment\Models\InvoiceModel;
use Modules\Admission\Models\AdmissionModel;

class InvoiceApiController extends ResourceController
{
    protected $modelName = 'Modules\Payment\Models\InvoiceModel';
    protected $format = 'json';

    /**
     * List all invoices with pagination
     * GET /api/invoices
     */
    public function index()
    {
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        // Apply filters if provided
        $filters = [];
        if ($status = $this->request->getGet('status')) {
            $filters['status'] = $status;
        }
        if ($type = $this->request->getGet('type')) {
            $filters['type'] = $type;
        }
        if ($startDate = $this->request->getGet('start_date')) {
            $filters['start_date'] = $startDate;
        }
        if ($endDate = $this->request->getGet('end_date')) {
            $filters['end_date'] = $endDate;
        }
        
        // Get invoices
        if (!empty($filters)) {
            $invoices = $invoiceModel->filterInvoices($filters);
            $total = count($invoices);
            $invoices = array_slice($invoices, ($page - 1) * $perPage, $perPage);
        } else {
            $invoices = $invoiceModel->paginate($perPage, 'default', $page);
            $total = $invoiceModel->countAllResults(false);
        }
        
        // Enrich with student details
        foreach ($invoices as &$invoice) {
            $student = $admissionModel->getByRegistrationNumber($invoice['registration_number']);
            $invoice['student'] = $student;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single invoice details
     * GET /api/invoices/{id}
     */
    public function show($id = null)
    {
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        $invoice = $invoiceModel->getInvoiceWithPayments($id);
        
        if (!$invoice) {
            return $this->failNotFound('Invoice not found');
        }
        
        // Get student details
        $student = $admissionModel->where('registration_number', $invoice['registration_number'])->first();
        $invoice['student'] = $student;
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    /**
     * Create new invoice
     * POST /api/invoices
     */
    public function create()
    {
        $invoiceModel = new InvoiceModel();
        $data = $this->request->getJSON(true);
        
        // Use createInvoice to auto-generate invoice number
        $id = $invoiceModel->createInvoice($data);
        
        if ($id) {
            $invoice = $invoiceModel->find($id);
            
            return $this->respondCreated([
                'status' => 'success',
                'data' => $invoice,
                'message' => 'Invoice created successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to create invoice',
            'errors' => $invoiceModel->errors()
        ], 422);
    }

    /**
     * Update invoice
     * PUT /api/invoices/{id}
     */
    public function update($id = null)
    {
        $invoiceModel = new InvoiceModel();
        $data = $this->request->getJSON(true);
        
        if (!$invoiceModel->find($id)) {
            return $this->failNotFound('Invoice not found');
        }
        
        if ($invoiceModel->update($id, $data)) {
            $invoice = $invoiceModel->find($id);
            
            return $this->respond([
                'status' => 'success',
                'data' => $invoice,
                'message' => 'Invoice updated successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to update invoice',
            'errors' => $invoiceModel->errors()
        ], 422);
    }

    /**
     * Delete invoice (soft delete)
     * DELETE /api/invoices/{id}
     */
    public function delete($id = null)
    {
        $invoiceModel = new InvoiceModel();
        
        if (!$invoiceModel->find($id)) {
            return $this->failNotFound('Invoice not found');
        }
        
        if ($invoiceModel->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to delete invoice'
        ], 422);
    }

    /**
     * Search invoices
     * GET /api/invoices/search?q=keyword
     */
    public function search()
    {
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Search keyword is required'
            ], 422);
        }
        
        $invoices = $invoiceModel->searchInvoices($keyword);
        
        // Enrich with student details
        foreach ($invoices as &$invoice) {
            $student = $admissionModel->getByRegistrationNumber($invoice['registration_number']);
            $invoice['student'] = $student;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    /**
     * Filter invoices by status
     * GET /api/invoices/filter/status?status=unpaid
     */
    public function filterByStatus()
    {
        $invoiceModel = new InvoiceModel();
        $status = $this->request->getGet('status');
        
        if (!$status) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Status parameter is required'
            ], 422);
        }
        
        $invoices = $invoiceModel->filterInvoices(['status' => $status]);
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    /**
     * Filter invoices by type
     * GET /api/invoices/filter/type?type=tuition_fee
     */
    public function filterByType()
    {
        $invoiceModel = new InvoiceModel();
        $type = $this->request->getGet('type');
        
        if (!$type) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Type parameter is required'
            ], 422);
        }
        
        $invoices = $invoiceModel->filterInvoices(['type' => $type]);
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    /**
     * Get invoices by student
     * GET /api/invoices/student/{registrationNumber}
     */
    public function getByStudent($registrationNumber = null)
    {
        log_message('debug', 'Fetching invoices for student: ' . $registrationNumber);
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        if (!$registrationNumber) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Registration number is required'
            ], 422);
        }
        
        // Verify student exists
        $student = $admissionModel->getByRegistrationNumber($registrationNumber);
        if (!$student) {
            log_message('error', 'Student not found: ' . $registrationNumber);
            return $this->failNotFound('Student not found');
        }
        
        $invoices = $invoiceModel->getInvoicesByStudent($registrationNumber);
        log_message('debug', 'Found ' . count($invoices) . ' invoices for student ' . $registrationNumber);
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices,
            'student' => $student
        ]);
    }

    /**
     * Get overdue invoices
     * GET /api/invoices/overdue
     */
    public function getOverdue()
    {
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        $invoices = $invoiceModel->getOverdueInvoices();
        
        // Calculate days overdue and enrich with student details
        foreach ($invoices as &$invoice) {
            $dueDate = new \DateTime($invoice['due_date']);
            $today = new \DateTime();
            $interval = $today->diff($dueDate);
            $invoice['days_overdue'] = $interval->days;
            
            $student = $admissionModel->getByRegistrationNumber($invoice['registration_number']);
            $invoice['student'] = $student;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    /**
     * Generate invoice PDF
     * GET /api/invoices/{id}/pdf
     */
    public function generatePdf($id = null)
    {
        $invoiceModel = new InvoiceModel();
        $admissionModel = new AdmissionModel();
        
        $invoice = $invoiceModel->find($id);
        
        if (!$invoice) {
            return $this->failNotFound('Invoice not found');
        }
        
        // Get student details
        $student = $admissionModel->where('registration_number', $invoice['registration_number'])->first();
        
        // Prepare invoice data for PDF
        $invoiceData = $invoice;
        $invoiceData['student_name'] = $student['full_name'] ?? 'N/A';
        
        // Generate PDF
        $pdfGenerator = new \Modules\Payment\Libraries\PdfGenerator();
        $filePath = $pdfGenerator->generateInvoicePdf($invoiceData);
        
        if (!$filePath) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to generate PDF'
            ], 500);
        }
        
        // Return PDF file
        $fullPath = WRITEPATH . 'uploads/' . $filePath;
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($fullPath));
    }

    /**
     * Cancel invoice
     * PUT /api/invoices/{id}/cancel
     */
    public function cancel($id = null)
    {
        $invoiceModel = new InvoiceModel();
        
        $invoice = $invoiceModel->find($id);
        
        if (!$invoice) {
            return $this->failNotFound('Invoice not found');
        }
        
        // Update status to cancelled
        if ($invoiceModel->updateInvoiceStatus($id, 'cancelled')) {
            $invoice = $invoiceModel->find($id);
            
            return $this->respond([
                'status' => 'success',
                'data' => $invoice,
                'message' => 'Invoice cancelled successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to cancel invoice'
        ], 422);
    }
}
