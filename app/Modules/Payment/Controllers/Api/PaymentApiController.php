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
        $invoiceModel = new \Modules\Payment\Models\InvoiceModel();

        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $sort = $this->request->getGet('sort') ?? 'payment_date';
        $order = $this->request->getGet('order') ?? 'desc';

        // Validate sort order
        $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';

        // Allowed sort fields
        $allowedSortFields = [
            'payment_date' => 'payment_date',
            'invoice_number' => 'invoice_id',
            'student_name' => 'registration_number',
            'amount' => 'amount',
            'payment_method' => 'payment_method',
            'status' => 'status'
        ];

        // Validate sort field
        $sortField = $allowedSortFields[$sort] ?? 'payment_date';

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

        // Get payments - search takes priority
        if ($search) {
            $payments = $paymentModel->searchPayments($search);
            $total = count($payments);
            // Apply sorting to search results
            usort($payments, function($a, $b) use ($sortField, $order) {
                $aVal = $a[$sortField] ?? '';
                $bVal = $b[$sortField] ?? '';
                $result = strcmp($aVal, $bVal);
                return $order === 'ASC' ? $result : -$result;
            });
            $payments = array_slice($payments, ($page - 1) * $perPage, $perPage);
        } elseif (!empty($filters)) {
            $payments = $paymentModel->filterPayments($filters);
            $total = count($payments);
            // Apply sorting to filter results
            usort($payments, function($a, $b) use ($sortField, $order) {
                $aVal = $a[$sortField] ?? '';
                $bVal = $b[$sortField] ?? '';
                $result = strcmp($aVal, $bVal);
                return $order === 'ASC' ? $result : -$result;
            });
            $payments = array_slice($payments, ($page - 1) * $perPage, $perPage);
        } else {
            $paymentModel->orderBy($sortField, $order);
            $payments = $paymentModel->paginate($perPage, 'default', $page);
            $total = $paymentModel->countAllResults(false);
        }

        // Enrich with student and invoice details
        foreach ($payments as &$payment) {
            $student = $admissionModel->getByRegistrationNumber($payment['registration_number']);
            $payment['student'] = $student;
            $payment['student_name'] = $student['full_name'] ?? 'N/A';
            
            // Get invoice details if linked
            if ($payment['invoice_id']) {
                $invoice = $invoiceModel->find($payment['invoice_id']);
                $payment['invoice_number'] = $invoice['invoice_number'] ?? 'N/A';
            } else {
                $payment['invoice_number'] = 'N/A';
            }
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
        $student = $admissionModel->getByRegistrationNumber((string)$payment['registration_number']);
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

        // Handle installment linking - don't link miscellaneous_fee invoices to contract
        $installmentId = null;
        if (isset($data['invoice_id']) && $data['invoice_id']) {
            $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
            $invoice = $invoiceModel->find($data['invoice_id']);
            // Only link to installment if invoice is NOT a miscellaneous_fee
            if ($invoice && !empty($invoice['installment_id']) && $invoice['invoice_type'] !== 'miscellaneous_fee') {
                $installmentId = $invoice['installment_id'];
            }
        }

        // If no installment from invoice, try to find by registration_number (for direct payments)
        if (!$installmentId && isset($data['registration_number'])) {
            $installmentModel = new \Modules\Payment\Models\InstallmentModel();
            $installment = $installmentModel->getByRegistrationNumber($data['registration_number']);
            if ($installment) {
                $installmentId = $installment['id'];
            }
        }

        // Set the installment_id (will be null for miscellaneous_fee invoices)
        $data['installment_id'] = $installmentId;

        if ($paymentModel->insert($data)) {
            $id = $paymentModel->getInsertID();
            $payment = $paymentModel->find($id);

            // Update installment totals if linked
            if ($installmentId && isset($data['status']) && $data['status'] === 'paid') {
                $installmentModel = new \Modules\Payment\Models\InstallmentModel();
                $installmentModel->updatePaymentTotal($installmentId);
            }

            // Auto-approve admission if payment is paid
            if (isset($data['status']) && $data['status'] === 'paid' && isset($data['registration_number'])) {
                $admissionModel = new \Modules\Admission\Models\AdmissionModel();
                $admission = $admissionModel->where('registration_number', $data['registration_number'])->first();
                if ($admission && $admission['status'] === 'pending') {
                    $admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ? $admission['notes'] . "\n" : "") . "Automatically approved via API payment."
                    ]);
                }
            }

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

            // Auto-approve admission if payment is paid
            if (isset($data['status']) && $data['status'] === 'paid') {
                $admissionModel = new \Modules\Admission\Models\AdmissionModel();
                $regNumber = $data['registration_number'] ?? $payment['registration_number'];
                $admission = $admissionModel->where('registration_number', $regNumber)->first();
                if ($admission && $admission['status'] === 'pending') {
                    $admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ? $admission['notes'] . "\n" : "") . "Automatically approved via API payment update."
                    ]);
                }
            }

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

            // Auto-approve admission if payment is paid
            if ($status === 'paid') {
                $admissionModel = new \Modules\Admission\Models\AdmissionModel();
                $admission = $admissionModel->where('registration_number', $payment['registration_number'])->first();
                if ($admission && $admission['status'] === 'pending') {
                    $admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ? $admission['notes'] . "\n" : "") . "Automatically approved via API status update."
                    ]);
                }
            }

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
            $student = $admissionModel->getByRegistrationNumber($payment['registration_number']);
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
        $student = $admissionModel->getByRegistrationNumber((string)$registrationNumber);
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

    /**
     * Get payment receipt details
     * GET /api/payments/{id}/receipt
     */
    public function getReceipt($id = null)
    {
        $paymentModel = new PaymentModel();
        $admissionModel = new AdmissionModel();

        $payment = $paymentModel->find($id);

        if (!$payment) {
            return $this->failNotFound('Payment not found');
        }

        // Get student details
        $student = $admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$payment['registration_number'])
            ->first();

        // Get invoice details if linked
        $invoice = null;
        if ($payment['invoice_id']) {
            $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
            $invoice = $invoiceModel->find($payment['invoice_id']);
        }

        // Company information
        $company = [
            'address' => 'Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212',
            'email' => 'admin@kursusbahasa.org',
            'phone' => '+62 858 1031 0950'
        ];

        // Generate payment number if not exists
        if (!isset($payment['payment_number'])) {
            $payment['payment_number'] = 'PAY-' . date('Y') . '-' . str_pad((string)$payment['id'], 4, '0', STR_PAD_LEFT);
        }

        // Return HTML content for receipt
        $html = view('Modules\Payment\Views\payments\receipt', [
            'payment' => $payment,
            'student' => $student,
            'invoice' => $invoice,
            'company' => $company
        ]);

        return $this->response->setBody($html);
    }
}
