<?php

namespace Modules\Payment\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'registration_number',
        'installment_id',
        'invoice_id',
        'amount',
        'payment_method',
        'document_number',
        'payment_date',
        'receipt_file',
        'status',
        'failure_reason',
        'refund_date',
        'refund_reason',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'registration_number' => 'required|max_length[20]',
        'invoice_id' => 'required|is_natural_no_zero',
        'amount' => 'required|decimal|greater_than[0]',
        'payment_method' => 'required|in_list[cash,bank_transfer,mobile_banking,credit_card]',
        'document_number' => 'required|max_length[100]',
        'payment_date' => 'required|valid_date',
        'receipt_file' => 'permit_empty|max_length[255]',
        'status' => 'permit_empty|in_list[pending,paid,failed,refunded]'
    ];

    protected $validationMessages = [
        'registration_number' => [
            'required' => 'Registration number is required'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'payment_method' => [
            'required' => 'Payment method is required',
            'in_list' => 'Payment method must be cash or bank_transfer'
        ],
        'document_number' => [
            'required' => 'Document number is required',
            'max_length' => 'Document number cannot exceed 100 characters'
        ],
        'payment_date' => [
            'required' => 'Payment date is required',
            'valid_date' => 'Payment date must be a valid date'
        ]
    ];

    /**
     * Create payment record
     * 
     * @param array $data Payment data
     * @return int|false Payment ID or false on failure
     */
    public function createPayment(array $data)
    {
        // Set default status to pending if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        // Auto-link installment based on registration_number if not provided
        if (!isset($data['installment_id']) && isset($data['registration_number'])) {
            $installmentModel = new InstallmentModel();
            $installment = $installmentModel->getByRegistrationNumber($data['registration_number']);
            if ($installment) {
                $data['installment_id'] = $installment['id'];
            }
        }

        $result = $this->insert($data);

        if ($result) {
            $paymentId = $this->insertID();

            // If payment is immediately marked as paid, update installment
            if ($data['status'] === 'paid' && !empty($data['installment_id'])) {
                $installmentModel = new InstallmentModel();
                $installmentModel->updatePaymentTotal($data['installment_id']);
            }

            return $paymentId;
        }

        return false;
    }

    /**
     * Get payments by student registration number
     * 
     * @param string $registrationNumber Student registration number
     * @return array
     */
    public function getPaymentsByStudent(string $registrationNumber): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get payments by date range
     * 
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @return array
     */
    public function getPaymentsByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('payment_date >=', $startDate)
            ->where('payment_date <=', $endDate)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Search payments by keyword
     * Search by student name, registration_number, or document_number
     * 
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchPayments(string $keyword): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        return $builder->select('payments.*, profiles.full_name as student_name, invoices.invoice_number')
            ->join('admissions', 'admissions.registration_number = payments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
            ->where('payments.deleted_at', null)
            ->groupStart()
            ->like('profiles.full_name', $keyword)
            ->orLike('payments.registration_number', $keyword)
            ->orLike('payments.document_number', $keyword)
            ->groupEnd()
            ->orderBy('payments.payment_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Filter payments by multiple criteria
     * 
     * @param array $filters Filter criteria (status, method, start_date, end_date)
     * @return array
     */
    public function filterPayments(array $filters): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $builder->select('payments.*, profiles.full_name as student_name, invoices.invoice_number')
            ->join('admissions', 'admissions.registration_number = payments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
            ->where('payments.deleted_at', null);

        if (isset($filters['status'])) {
            $builder->where('payments.status', $filters['status']);
        }

        if (isset($filters['method'])) {
            $builder->where('payments.payment_method', $filters['method']);
        }

        if (isset($filters['start_date'])) {
            $builder->where('payments.payment_date >=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $builder->where('payments.payment_date <=', $filters['end_date']);
        }

        return $builder->orderBy('payments.payment_date', 'DESC')->get()->getResultArray();
    }

    /**
     * Update payment status
     * 
     * @param int $id Payment ID
     * @param string $status New status
     * @param array $additionalData Additional data (failure_reason, refund_date, refund_reason)
     * @return bool
     */
    public function updatePaymentStatus(int $id, string $status, array $additionalData = []): bool
    {
        // Get current payment
        $payment = $this->find($id);

        if (!$payment) {
            return false;
        }

        // Validate status transitions - prevent refunded → pending
        if ($payment['status'] === 'refunded' && $status === 'pending') {
            return false;
        }

        $updateData = ['status' => $status];

        // Handle failure_reason for status='failed'
        if ($status === 'failed' && isset($additionalData['failure_reason'])) {
            $updateData['failure_reason'] = $additionalData['failure_reason'];
        }

        // Handle refund_date and refund_reason for status='refunded'
        if ($status === 'refunded') {
            if (isset($additionalData['refund_date'])) {
                $updateData['refund_date'] = $additionalData['refund_date'];
            }
            if (isset($additionalData['refund_reason'])) {
                $updateData['refund_reason'] = $additionalData['refund_reason'];
            }
        }

        $result = $this->update($id, $updateData);

        // Update installment if payment is linked and status changed to/from paid
        if ($result && !empty($payment['installment_id'])) {
            $installmentModel = new InstallmentModel();
            $statusesAffectingInstallment = ['paid', 'refunded', 'failed'];

            if (in_array($status, $statusesAffectingInstallment) || in_array($payment['status'], $statusesAffectingInstallment)) {
                $installmentModel->updatePaymentTotal($payment['installment_id']);
            }
        }

        return $result;
    }

    /**
     * Upload receipt file
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file Uploaded file
     * @return string|false File path or false on failure
     */
    public function uploadReceiptFile($file)
    {
        // Validate file
        if (!$file->isValid()) {
            return false;
        }

        // Validate file format (PDF, JPG, JPEG, PNG)
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2048 * 1024) {
            return false;
        }

        // Store files in public/uploads/receipts/ directory
        $uploadPath = FCPATH . 'uploads/receipts/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $newName = $file->getRandomName();

        // Move file
        if ($file->move($uploadPath, $newName)) {
            // Return relative path for database storage
            return 'receipts/' . $newName;
        }

        return false;
    }

    /**
     * Get dashboard statistics
     * 
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @return array
     */
    public function getDashboardStatistics(string $startDate, string $endDate): array
    {
        $db = \Config\Database::connect();

        // Calculate total revenue (sum of paid payments in date range)
        $revenueResult = $db->table($this->table)
            ->selectSum('amount', 'total')
            ->where('status', 'paid')
            ->where('payment_date >=', $startDate)
            ->where('payment_date <=', $endDate)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        $totalRevenue = $revenueResult['total'] ?? 0;

        // Count pending payments
        $pendingCount = $db->table($this->table)
            ->where('status', 'pending')
            ->where('deleted_at', null)
            ->countAllResults();

        // Count completed payments in date range
        $completedCount = $db->table($this->table)
            ->where('status', 'paid')
            ->where('payment_date >=', $startDate)
            ->where('payment_date <=', $endDate)
            ->where('deleted_at', null)
            ->countAllResults();

        // Count overdue invoices
        $overdueCount = $db->table('invoices')
            ->where('status', 'unpaid')
            ->where('due_date <', date('Y-m-d'))
            ->where('deleted_at', null)
            ->countAllResults();

        return [
            'total_revenue' => (float) $totalRevenue,
            'pending_count' => $pendingCount,
            'completed_count' => $completedCount,
            'overdue_count' => $overdueCount
        ];
    }

    /**
     * Get revenue breakdown by payment method
     * 
     * @return array
     */
    public function getRevenueByMethod(): array
    {
        $db = \Config\Database::connect();

        $results = $db->table($this->table)
            ->select('payment_method, SUM(amount) as total')
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->groupBy('payment_method')
            ->get()
            ->getResultArray();

        $breakdown = [];
        foreach ($results as $row) {
            $breakdown[$row['payment_method']] = (float) $row['total'];
        }

        return $breakdown;
    }

    /**
     * Get revenue breakdown by invoice type
     * 
     * @return array
     */
    public function getRevenueByType(): array
    {
        $db = \Config\Database::connect();

        $results = $db->table($this->table)
            ->select('invoices.invoice_type, SUM(payments.amount) as total')
            ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
            ->where('payments.status', 'paid')
            ->where('payments.deleted_at', null)
            ->where('invoices.invoice_type IS NOT', null)
            ->groupBy('invoices.invoice_type')
            ->get()
            ->getResultArray();

        $breakdown = [];
        foreach ($results as $row) {
            $breakdown[$row['invoice_type']] = (float) $row['total'];
        }

        return $breakdown;
    }

    /**
     * Get monthly revenue trend for a year
     * 
     * @param int $year Year
     * @return array
     */
    public function getMonthlyRevenueTrend(int $year): array
    {
        $db = \Config\Database::connect();

        $results = $db->table($this->table)
            ->select('MONTH(payment_date) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->where('YEAR(payment_date)', $year)
            ->where('deleted_at', null)
            ->groupBy('MONTH(payment_date)')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $trend = [];
        foreach ($results as $row) {
            $monthNum = (int) $row['month'];
            $trend[] = [
                'month' => $months[$monthNum],
                'revenue' => (float) $row['total']
            ];
        }

        return $trend;
    }

    /**
     * Get overdue payments
     * 
     * @return array
     */
    public function getOverduePayments(): array
    {
        $db = \Config\Database::connect();

        return $db->table('invoices')
            ->select('invoices.*, profiles.full_name, profiles.email')
            ->join('admissions', 'admissions.registration_number = invoices.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->where('invoices.status', 'unpaid')
            ->where('invoices.due_date <', date('Y-m-d'))
            ->where('invoices.deleted_at', null)
            ->orderBy('invoices.due_date', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get refunded payments
     * 
     * @return array
     */
    public function getRefundedPayments(): array
    {
        return $this->where('status', 'refunded')
            ->orderBy('refund_date', 'DESC')
            ->findAll();
    }

    /**
     * Export data to CSV format
     * 
     * @param array $data Data to export
     * @return string CSV content
     */
    public function exportToCSV(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $csv = '';

        // Add headers
        $headers = array_keys($data[0]);
        $csv .= implode(',', $headers) . "\n";

        // Add data rows
        foreach ($data as $row) {
            $values = array_map(function ($value) {
                // Escape values containing commas or quotes
                if (strpos($value, ',') !== false || strpos($value, '"') !== false) {
                    return '"' . str_replace('"', '""', $value) . '"';
                }
                return $value;
            }, array_values($row));

            $csv .= implode(',', $values) . "\n";
        }

        return $csv;
    }

    /**
     * Get paginated payments for a specific student
     *
     * @param string $registrationNumber Student registration number
     * @param int $perPage Number of records per page
     * @return array
     */
    public function getStudentPayments(string $registrationNumber, int $perPage = 10): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->orderBy('payment_date', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Search payments for a specific student
     *
     * @param string $registrationNumber Student registration number
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchStudentPayments(string $registrationNumber, string $keyword): array
    {
        return $this->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->groupStart()
            ->like('document_number', $keyword)
            ->orLike('payment_method', $keyword)
            ->groupEnd()
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get payment summary for a student
     *
     * @param string $registrationNumber Student registration number
     * @return array
     */
    public function getStudentPaymentSummary(string $registrationNumber): array
    {
        $db = \Config\Database::connect();

        $result = $db->table($this->table)
            ->select("
                COUNT(*) as total_payments,
                SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_paid,
                SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = 'refunded' THEN amount ELSE 0 END) as total_refunded
            ")
            ->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        return [
            'total_payments' => $result['total_payments'] ?? 0,
            'total_paid' => (float) ($result['total_paid'] ?? 0),
            'total_pending' => (float) ($result['total_pending'] ?? 0),
            'total_refunded' => (float) ($result['total_refunded'] ?? 0)
        ];
    }
}
