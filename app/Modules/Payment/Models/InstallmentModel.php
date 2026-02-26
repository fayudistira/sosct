<?php

namespace Modules\Payment\Models;

use CodeIgniter\Model;

class InstallmentModel extends Model
{
    protected $table = 'installments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $usePagination = true;

    protected $allowedFields = [
        'registration_number',
        'total_contract_amount',
        'total_paid',
        'remaining_balance',
        'status',
        'due_date',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'registration_number' => 'required|max_length[20]',
        'total_contract_amount' => 'required|decimal|greater_than_equal_to[0]',
        'total_paid' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'remaining_balance' => 'required|decimal',
        'status' => 'permit_empty|in_list[unpaid,partial,paid,switched]'
    ];

    protected $validationMessages = [
        'registration_number' => [
            'required' => 'Registration number is required'
        ],
        'total_contract_amount' => [
            'required' => 'Total contract amount is required',
            'decimal' => 'Total contract amount must be a valid decimal number'
        ]
    ];

    /**
     * Create installment record with calculated balance
     *
     * @param array $data
     * @return int|false
     */
    public function createInstallment(array $data)
    {
        // Calculate remaining balance
        $totalContract = (float) ($data['total_contract_amount'] ?? 0);
        $totalPaid = (float) ($data['total_paid'] ?? 0);
        $data['remaining_balance'] = $totalContract - $totalPaid;

        // Set default status
        if (!isset($data['status'])) {
            if ($data['remaining_balance'] <= 0) {
                $data['status'] = 'paid';
            } elseif ($totalPaid > 0) {
                $data['status'] = 'partial';
            } else {
                $data['status'] = 'unpaid';
            }
        }

        return $this->insert($data) ? $this->insertID() : false;
    }

    /**
     * Get installment by registration number
     *
     * @param string $registrationNumber
     * @return array|null
     */
    public function getByRegistrationNumber(string $registrationNumber): ?array
    {
        return $this->where('registration_number', $registrationNumber)->first();
    }

    /**
     * Get LATEST installment by registration number (most recent)
     * This is useful when there are multiple installments (e.g., after program switch)
     *
     * @param string $registrationNumber
     * @return array|null
     */
    public function getLatestByRegistrationNumber(string $registrationNumber): ?array
    {
        return $this->where('registration_number', $registrationNumber)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Update payment total and recalculate balance
     *
     * @param int $installmentId
     * @return bool
     */
    public function updatePaymentTotal(int $installmentId): bool
    {
        $installment = $this->find($installmentId);
        if (!$installment) return false;

        // Get total paid from payments table
        $db = \Config\Database::connect();
        $paidResult = $db->table('payments')
            ->selectSum('amount')
            ->where('installment_id', $installmentId)
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        $totalPaid = (float) ($paidResult['amount'] ?? 0);
        $totalContract = (float) $installment['total_contract_amount'];
        $remainingBalance = $totalContract - $totalPaid;

        // Determine status
        $status = 'unpaid';
        if ($remainingBalance <= 0) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partial';
        }

        return $this->update($installmentId, [
            'total_paid' => $totalPaid,
            'remaining_balance' => $remainingBalance,
            'status' => $status
        ]);
    }

    /**
     * Get remaining balance for an installment
     *
     * @param int $installmentId
     * @return float
     */
    public function getBalance(int $installmentId): float
    {
        $installment = $this->find($installmentId);
        return $installment ? (float) $installment['remaining_balance'] : 0;
    }

    /**
     * Mark installment as fully paid
     *
     * @param int $installmentId
     * @return bool
     */
    public function markAsPaid(int $installmentId): bool
    {
        $installment = $this->find($installmentId);
        if (!$installment) return false;

        return $this->update($installmentId, [
            'total_paid' => $installment['total_contract_amount'],
            'remaining_balance' => 0,
            'status' => 'paid'
        ]);
    }

    /**
     * Get installment with admission details
     *
     * @param string $registrationNumber
     * @return array|null
     */
    public function getWithDetails(string $registrationNumber): ?array
    {
        $db = \Config\Database::connect();
        $installment = $db->table($this->table)
            ->select('installments.*, profiles.full_name, profiles.email, profiles.phone, programs.title as program_title, programs.category')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('installments.registration_number', $registrationNumber)
            ->orderBy('installments.id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $installment;
    }

    /**
     * Get all installments with pagination
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getWithPagination(int $perPage = 10, int $page = 1)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table)
            ->select('installments.*, profiles.full_name, programs.title as program_title')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->orderBy('installments.created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;

        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Search installments by keyword
     *
     * @param string $keyword
     * @return array
     */
    public function search(string $keyword): array
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('installments.*, profiles.full_name, profiles.email, programs.title as program_title')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->groupStart()
            ->like('installments.registration_number', $keyword)
            ->orLike('profiles.full_name', $keyword)
            ->orLike('profiles.email', $keyword)
            ->groupEnd()
            ->orderBy('installments.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Filter installments by status
     *
     * @param string $status
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function filterByStatus(string $status, int $perPage = 10, int $page = 1): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table)
            ->select('installments.*, profiles.full_name, programs.title as program_title')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('installments.status', $status)
            ->orderBy('installments.created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;

        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get installment statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $db = \Config\Database::connect();

        $totalContract = $db->table($this->table)
            ->selectSum('total_contract_amount')
            ->get()
            ->getRowArray()['total_contract_amount'] ?? 0;

        $totalPaid = $db->table($this->table)
            ->selectSum('total_paid')
            ->get()
            ->getRowArray()['total_paid'] ?? 0;

        $totalBalance = $db->table($this->table)
            ->selectSum('remaining_balance')
            ->get()
            ->getRowArray()['remaining_balance'] ?? 0;

        $unpaidCount = $this->where('status', 'unpaid')->countAllResults();
        $partialCount = $this->where('status', 'partial')->countAllResults();
        $paidCount = $this->where('status', 'paid')->countAllResults();

        return [
            'total_contract_amount' => (float) $totalContract,
            'total_paid' => (float) $totalPaid,
            'total_remaining' => (float) $totalBalance,
            'unpaid_count' => $unpaidCount,
            'partial_count' => $partialCount,
            'paid_count' => $paidCount
        ];
    }

    /**
     * Check if installment has remaining balance
     *
     * @param int $installmentId
     * @return bool
     */
    public function hasBalance(int $installmentId): bool
    {
        $installment = $this->find($installmentId);
        return $installment && (float) $installment['remaining_balance'] > 0;
    }

    /**
     * Get payments for an installment
     *
     * @param int $installmentId
     * @return array
     */
    public function getPayments(int $installmentId): array
    {
        $paymentModel = new PaymentModel();
        return $paymentModel->where('installment_id', $installmentId)
            ->where('deleted_at', null)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }
}
