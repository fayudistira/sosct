<?php

namespace Modules\Payment\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Payment\Models\PaymentModel;
use Modules\Admission\Models\AdmissionModel;

class PaymentApiController extends ResourceController
{
    protected $modelName = 'Modules\Payment\Models\PaymentModel';
    protected $format = 'json';

    /**
     * List all payments with pagination
     * GET /api/payments
     */
    public function index()
    {
        $paymentModel = new PaymentModel();
        $admissionModel = new AdmissionModel();
        
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        // Apply filters if provided
        $filters = [];
        if ($status = $this->request->getGet('status')) {
            $filters['status'] = $status;
        }
        if ($method = $this->request->getGet('method')) {
            $filters['method'] = $method;
        }
        if ($startDate = $this->request->getGet('start_date')) {
            $filters['start_date'] = $startDate;
        }
        if ($endDate = $this->request->getGet('end_date')) {
            $filters['end_date'] = $endDate;
        }
        
        // Get payments
        if (!empty($filters)) {
            $payments = $paymentModel->filterPayments($filters);
            $total = count($payments);
            $payments = array_slice($payments, ($page - 1) * $perPage, $perPage);
        } else {
            $payments = $paymentModel->paginate($perPage, 'default', $page);
            $total = $paymentModel->countAllResults(false);
        }
        
        // Enrich with student details
        foreach ($payments as &$payment) {
            $student = $admissionModel->where('registration_number', $payment['registration_number'])->first();
            $payment['student'] = $student;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single payment details
     * GET /api/payments/{id}
     */
    public function show($id = null)
    {
        $paymentModel = new PaymentModel();
        $admissionModel = new AdmissionModel();
        
        $payment = $paymentModel->find($id);
        
        if (!$payment) {
            return $this->failNotFound('Payment not found');
        }
        
        // Get student details
        $student = $admissionModel->where('registration_number', $payment['registration_number'])->first();
        $payment['student'] = $student;
        
        // Get invoice details if linked
        if ($payment['invoice_id']) {
            $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
            $invoice = $invoiceModel->find($payment['invoice_id']);
            $payment['invoice'] = $invoice;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $payment
        ]);
    }

    /**
     * Create new payment record
     * POST /api/payments
     */
    public function create()
    {
        $paymentModel = new PaymentModel();
        $data = $this->request->getJSON(true);
        
        if ($paymentModel->insert($data)) {
            $id = $paymentModel->getInsertID();
            $payment = $paymentModel->find($id);
            
            return $this->respondCreated([
                'status' => 'success',
                'data' => $payment,
                'message' => 'Payment created successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to create payment',
            'errors' => $paymentModel->errors()
        ], 422);
    }

    /**
     * Update payment record
     * PUT /api/payments/{id}
     */
    public function update($id = null)
    {
        $paymentModel = new PaymentModel();
        $data = $this->request->getJSON(true);
        
        if (!$paymentModel->find($id)) {
            return $this->failNotFound('Payment not found');
        }
        
        if ($paymentModel->update($id, $data)) {
            $payment = $paymentModel->find($id);
            
            return $this->respond([
                'status' => 'success',
                'data' => $payment,
                'message' => 'Payment updated successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to update payment',
            'errors' => $paymentModel->errors()
        ], 422);
    }

    /**
     * Update payment status
     * PUT /api/payments/{id}/status
     */
    public function updateStatus($id = null)
    {
        $paymentModel = new PaymentModel();
        $data = $this->request->getJSON(true);
        
        if (!$paymentModel->find($id)) {
            return $this->failNotFound('Payment not found');
        }
        
        $status = $data['status'] ?? null;
        if (!$status) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Status is required'
            ], 422);
        }
        
        $additionalData = [];
        if (isset($data['failure_reason'])) {
            $additionalData['failure_reason'] = $data['failure_reason'];
        }
        if (isset($data['refund_date'])) {
            $additionalData['refund_date'] = $data['refund_date'];
        }
        if (isset($data['refund_reason'])) {
            $additionalData['refund_reason'] = $data['refund_reason'];
        }
        
        if ($paymentModel->updatePaymentStatus($id, $status, $additionalData)) {
            $payment = $paymentModel->find($id);
            
            return $this->respond([
                'status' => 'success',
                'data' => $payment,
                'message' => 'Payment status updated successfully'
            ]);
        }
        
        return $this->fail([
            'status' => 'error',
            'message' => 'Failed to update payment status'
        ], 422);
    }

    /**
     * Search payments
     * GET /api/payments/search?q=keyword
     */
    public function search()
    {
        $paymentModel = new PaymentModel();
        $admissionModel = new AdmissionModel();
        
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Search keyword is required'
            ], 422);
        }
        
        $payments = $paymentModel->searchPayments($keyword);
        
        // Enrich with student details
        foreach ($payments as &$payment) {
            $student = $admissionModel->where('registration_number', $payment['registration_number'])->first();
            $payment['student'] = $student;
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Filter payments by status
     * GET /api/payments/filter/status?status=paid
     */
    public function filterByStatus()
    {
        $paymentModel = new PaymentModel();
        $status = $this->request->getGet('status');
        
        if (!$status) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Status parameter is required'
            ], 422);
        }
        
        $payments = $paymentModel->filterPayments(['status' => $status]);
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Filter payments by method
     * GET /api/payments/filter/method?method=cash
     */
    public function filterByMethod()
    {
        $paymentModel = new PaymentModel();
        $method = $this->request->getGet('method');
        
        if (!$method) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Method parameter is required'
            ], 422);
        }
        
        $payments = $paymentModel->filterPayments(['method' => $method]);
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Filter payments by date range
     * GET /api/payments/filter/daterange?start_date=2026-01-01&end_date=2026-12-31
     */
    public function filterByDateRange()
    {
        $paymentModel = new PaymentModel();
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        if (!$startDate || !$endDate) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Both start_date and end_date are required'
            ], 422);
        }
        
        $payments = $paymentModel->filterPayments([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Get payments by student
     * GET /api/payments/student/{registrationNumber}
     */
    public function getByStudent($registrationNumber = null)
    {
        $paymentModel = new PaymentModel();
        $admissionModel = new AdmissionModel();
        
        if (!$registrationNumber) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Registration number is required'
            ], 422);
        }
        
        // Verify student exists
        $student = $admissionModel->where('registration_number', $registrationNumber)->first();
        if (!$student) {
            return $this->failNotFound('Student not found');
        }
        
        $payments = $paymentModel->getPaymentsByStudent($registrationNumber);
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments,
            'student' => $student
        ]);
    }

    /**
     * Get payment statistics
     * GET /api/payments/statistics?start_date=2026-01-01&end_date=2026-12-31
     */
    public function statistics()
    {
        $paymentModel = new PaymentModel();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        // Get dashboard statistics
        $stats = $paymentModel->getDashboardStatistics($startDate, $endDate);
        
        // Get revenue breakdowns
        $stats['revenue_by_method'] = $paymentModel->getRevenueByMethod();
        $stats['revenue_by_type'] = $paymentModel->getRevenueByType();
        
        // Get monthly trend for current year
        $stats['monthly_trend'] = $paymentModel->getMonthlyRevenueTrend(date('Y'));
        
        return $this->respond([
            'status' => 'success',
            'data' => $stats,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Upload receipt file
     * POST /api/payments/{id}/receipt
     */
    public function uploadReceipt($id = null)
    {
        $paymentModel = new PaymentModel();
        
        // Check if payment exists
        $payment = $paymentModel->find($id);
        if (!$payment) {
            return $this->failNotFound('Payment not found');
        }
        
        // Check if file was uploaded
        $file = $this->request->getFile('receipt_file');
        if (!$file) {
            return $this->fail([
                'status' => 'error',
                'message' => 'No file uploaded',
                'errors' => [
                    'receipt_file' => 'Receipt file is required'
                ]
            ], 422);
        }
        
        // Upload file
        $filePath = $paymentModel->uploadReceiptFile($file);
        
        if (!$filePath) {
            return $this->fail([
                'status' => 'error',
                'message' => 'File upload failed',
                'errors' => [
                    'receipt_file' => 'Invalid file format or size. Allowed: PDF, JPG, PNG (max 2MB)'
                ]
            ], 422);
        }
        
        // Update payment record with file path
        $paymentModel->update($id, ['receipt_file' => $filePath]);
        
        // Get updated payment
        $payment = $paymentModel->find($id);
        
        return $this->respond([
            'status' => 'success',
            'data' => $payment,
            'message' => 'Receipt file uploaded successfully'
        ]);
    }
}
