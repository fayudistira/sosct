<?php

namespace Modules\Payment\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Payment\Models\InstallmentModel;

class InstallmentApiController extends ResourceController
{
    protected $modelName = 'Modules\Payment\Models\InstallmentModel';
    protected $format = 'json';

    /**
     * List all installments with pagination
     * GET /api/installments
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $status = $this->request->getGet('status');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'desc';
        
        // Validate order direction
        $order = strtolower($order) === 'asc' ? 'asc' : 'desc';

        // Build query
        $builder = $this->model;

        // Apply status filter
        if ($status) {
            $builder = $builder->where('status', $status);
        }
        
        // Apply sorting
        $builder = $builder->orderBy($sort, $order);

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $installments = $builder->paginate($perPage, 'default', $page);

        // Enrich with student details
        $admissionModel = new \Modules\Admission\Models\AdmissionModel();
        foreach ($installments as &$installment) {
            $student = $admissionModel->getByRegistrationNumber($installment['registration_number']);
            $installment['student_name'] = $student['full_name'] ?? 'N/A';
        }

        return $this->respond([
            'status' => 'success',
            'data' => $installments,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single installment details
     * GET /api/installments/{id}
     */
    public function show($id = null)
    {
        $installment = $this->model->find($id);

        if (!$installment) {
            return $this->failNotFound('Installment not found');
        }

        // Get student details
        $admissionModel = new \Modules\Admission\Models\AdmissionModel();
        $student = $admissionModel->getByRegistrationNumber($installment['registration_number']);
        $installment['student'] = $student;

        // Get associated invoices
        $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
        $invoices = $invoiceModel->where('installment_id', $id)->findAll();
        $installment['invoices'] = $invoices;

        // Calculate totals
        $totalAmount = $installment['total_amount'];
        $paidAmount = $installment['paid_amount'];
        $installment['remaining_amount'] = $totalAmount - $paidAmount;

        return $this->respond([
            'status' => 'success',
            'data' => $installment
        ]);
    }

    /**
     * Update installment
     * PUT /api/installments/{id}
     */
    public function update($id = null)
    {
        $installment = $this->model->find($id);

        if (!$installment) {
            return $this->failNotFound('Installment not found');
        }

        $data = $this->request->getJSON(true);

        // Allow updating only specific fields
        $allowedFields = ['status', 'total_amount', 'notes'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            return $this->fail('No valid fields to update');
        }

        if (!$this->model->update($id, $updateData)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $updated = $this->model->find($id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Installment updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * Get installments by student registration number
     * GET /api/installments/student/{registration_number}
     */
    public function getByStudent($registrationNumber = null)
    {
        if (!$registrationNumber) {
            return $this->fail('Registration number is required');
        }

        // Verify student exists
        $admissionModel = new \Modules\Admission\Models\AdmissionModel();
        $student = $admissionModel->getByRegistrationNumber($registrationNumber);
        
        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $installments = $this->model->getByRegistrationNumber($registrationNumber);

        return $this->respond([
            'status' => 'success',
            'data' => $installments,
            'student' => [
                'registration_number' => $registrationNumber,
                'full_name' => $student['full_name']
            ]
        ]);
    }

    /**
     * Get installment statistics
     * GET /api/installments/statistics
     */
    public function statistics()
    {
        $db = \Config\Database::connect();

        // Get counts by status
        $statusCounts = $db->table('installments')
            ->select('status, COUNT(*) as count, SUM(total_amount) as total_amount, SUM(paid_amount) as paid_amount')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = [
            'by_status' => [],
            'total_amount' => 0,
            'paid_amount' => 0,
            'remaining_amount' => 0
        ];

        foreach ($statusCounts as $row) {
            $stats['by_status'][$row['status']] = [
                'count' => (int) $row['count'],
                'total_amount' => (float) ($row['total_amount'] ?? 0),
                'paid_amount' => (float) ($row['paid_amount'] ?? 0)
            ];
            $stats['total_amount'] += (float) ($row['total_amount'] ?? 0);
            $stats['paid_amount'] += (float) ($row['paid_amount'] ?? 0);
        }

        $stats['remaining_amount'] = $stats['total_amount'] - $stats['paid_amount'];

        // Get overdue installments
        $today = date('Y-m-d');
        $overdueCount = $db->table('installments')
            ->where('status', 'active')
            ->where('end_date <', $today)
            ->countAllResults();

        $stats['overdue_count'] = $overdueCount;

        return $this->respond([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
