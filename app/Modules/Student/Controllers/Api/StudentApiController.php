<?php

namespace Modules\Student\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Student\Models\StudentModel;

class StudentApiController extends ResourceController
{
    protected $modelName = 'Modules\Student\Models\StudentModel';
    protected $format = 'json';

    /**
     * List all students with pagination
     * GET /api/students
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $programId = $this->request->getGet('program_id');
        $batch = $this->request->getGet('batch');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'desc';
        
        // Validate sort field (whitelist allowed columns)
        $allowedSortFields = ['student_number', 'full_name', 'status', 'enrollment_date', 'batch', 'created_at'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'created_at';
        }
        
        // Validate order direction
        $order = strtolower($order) === 'asc' ? 'asc' : 'desc';

        // Build query with joins
        $builder = $this->model->select('
                students.*,
                profiles.full_name,
                profiles.email as profile_email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = students.profile_id')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->where('students.deleted_at IS NULL');

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('students.student_number', $search)
                ->orLike('profiles.full_name', $search)
                ->orLike('profiles.email', $search)
                ->orLike('profiles.phone', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status) {
            $builder->where('students.status', $status);
        }

        // Apply program filter
        if ($programId) {
            $builder->where('students.program_id', $programId);
        }

        // Apply batch filter
        if ($batch) {
            $builder->where('students.batch', $batch);
        }
        
        // Apply sorting
        if ($sort === 'full_name') {
            $builder->orderBy('profiles.full_name', $order);
        } else {
            $builder->orderBy('students.' . $sort, $order);
        }

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $students = $builder->paginate($perPage, 'default', $page);

        return $this->respond([
            'status' => 'success',
            'data' => $students,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single student details
     * GET /api/students/{id}
     */
    public function show($id = null)
    {
        $student = $this->model->getStudentWithDetails($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $student
        ]);
    }

    /**
     * Get student by student number
     * GET /api/students/number/{student_number}
     */
    public function showByNumber($studentNumber = null)
    {
        if (!$studentNumber) {
            return $this->fail('Student number is required');
        }

        $student = $this->model->where('student_number', $studentNumber)->first();

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $studentDetails = $this->model->getStudentWithDetails($student['id']);

        return $this->respond([
            'status' => 'success',
            'data' => $studentDetails
        ]);
    }

    /**
     * Update student
     * PUT /api/students/{id}
     */
    public function update($id = null)
    {
        $student = $this->model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $data = $this->request->getJSON(true);

        // Allow updating only specific fields
        $allowedFields = ['status', 'gpa', 'total_credits', 'graduation_date', 'graduation_gpa', 'program_id'];
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

        $updated = $this->model->getStudentWithDetails($id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * Delete student (soft delete)
     * DELETE /api/students/{id}
     */
    public function delete($id = null)
    {
        $student = $this->model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ]);
    }

    /**
     * Get student payments
     * GET /api/students/{id}/payments
     */
    public function payments($id = null)
    {
        $student = $this->model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $paymentModel = new \Modules\Payment\Models\PaymentModel();
        $payments = $paymentModel->getPaymentsByStudent($student['student_number']);

        return $this->respond([
            'status' => 'success',
            'data' => $payments,
            'student' => [
                'id' => $student['id'],
                'student_number' => $student['student_number']
            ]
        ]);
    }

    /**
     * Get student invoices
     * GET /api/students/{id}/invoices
     */
    public function invoices($id = null)
    {
        $student = $this->model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
        
        // Build query
        $builder = $invoiceModel->select('invoices.*')
            ->where('invoices.registration_number', $student['student_number'])
            ->orderBy('invoices.due_date', 'DESC');

        $invoices = $builder->findAll();

        return $this->respond([
            'status' => 'success',
            'data' => $invoices,
            'student' => [
                'id' => $student['id'],
                'student_number' => $student['student_number']
            ]
        ]);
    }

    /**
     * Get student installments
     * GET /api/students/{id}/installments
     */
    public function installments($id = null)
    {
        $student = $this->model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $installmentModel = new \Modules\Payment\Models\InstallmentModel();
        $installments = $installmentModel->getByRegistrationNumber($student['student_number']);

        return $this->respond([
            'status' => 'success',
            'data' => $installments,
            'student' => [
                'id' => $student['id'],
                'student_number' => $student['student_number']
            ]
        ]);
    }

    /**
     * Search students
     * GET /api/students/search?q={keyword}
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return $this->fail('Search keyword is required');
        }

        $students = $this->model->select('
                students.*,
                profiles.full_name,
                profiles.email as profile_email,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = students.profile_id')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->where('students.deleted_at IS NULL')
            ->groupStart()
            ->like('students.student_number', $keyword)
            ->orLike('profiles.full_name', $keyword)
            ->orLike('profiles.email', $keyword)
            ->orLike('profiles.phone', $keyword)
            ->groupEnd()
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'data' => $students,
            'count' => count($students)
        ]);
    }

    /**
     * Get student statistics
     * GET /api/students/statistics
     */
    public function statistics()
    {
        $db = \Config\Database::connect();

        // Get counts by status
        $statusCounts = $db->table('students')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = [
            'active' => 0,
            'inactive' => 0,
            'graduated' => 0,
            'dropped' => 0,
            'suspended' => 0,
            'total' => 0
        ];

        foreach ($statusCounts as $row) {
            $stats[$row['status']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }

        // Get new students this month
        $monthStart = date('Y-m-01');
        $newStudents = $db->table('students')
            ->where('created_at >=', $monthStart)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        $stats['new_this_month'] = $newStudents;

        // Get batches
        $batches = $db->table('students')
            ->select('batch, COUNT(*) as count')
            ->where('deleted_at IS NULL')
            ->groupBy('batch')
            ->orderBy('batch', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $stats['by_batch'] = $batches;

        return $this->respond([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Get current user's student profile
     * GET /api/students/me
     */
    public function me()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Find profile for this user
        $profileModel = new \Modules\Account\Models\ProfileModel();
        $profile = $profileModel->where('user_id', $user->id)->first();

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        // Find student for this profile
        $student = $this->model->where('profile_id', $profile['id'])->first();

        if (!$student) {
            return $this->failNotFound('Student record not found');
        }

        $studentDetails = $this->model->getStudentWithDetails($student['id']);

        return $this->respond([
            'status' => 'success',
            'data' => $studentDetails
        ]);
    }
}
