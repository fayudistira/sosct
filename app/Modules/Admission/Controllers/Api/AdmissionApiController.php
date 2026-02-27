<?php

namespace Modules\Admission\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Admission\Models\AdmissionModel;

class AdmissionApiController extends ResourceController
{
    protected $modelName = 'Modules\Admission\Models\AdmissionModel';
    protected $format = 'json';
    
    /**
     * GET /api/admissions
     * List all admissions with pagination
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'application_date';
        $order = $this->request->getGet('order') ?? 'desc';
        
        // Validate sort order
        $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';
        
        // Allowed sort fields
        $allowedSortFields = [
            'registration_number' => 'admissions.registration_number',
            'full_name' => 'profiles.full_name',
            'email' => 'profiles.email',
            'phone' => 'profiles.phone',
            'program_title' => 'programs.title',
            'status' => 'admissions.status',
            'application_date' => 'admissions.application_date'
        ];
        
        // Validate sort field
        $sortField = $allowedSortFields[$sort] ?? 'admissions.application_date';
        
        // Build query
        $builder = $this->model->select('admissions.*, profiles.full_name, profiles.email, profiles.phone, programs.title as program_title')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id', 'left');
        
        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('profiles.full_name', $search)
                ->orLike('profiles.email', $search)
                ->orLike('admissions.registration_number', $search)
                ->orLike('profiles.phone', $search)
                ->groupEnd();
        }
        
        // Apply status filter
        if ($status) {
            $builder->where('admissions.status', $status);
        }
        
        // Apply sorting
        $builder->orderBy($sortField, $order);
        
        // Get total count before pagination
        $total = $builder->countAllResults(false);
        
        // Get paginated results
        $admissions = $builder->paginate($perPage, 'default', $page);
        
        return $this->respond([
            'status' => 'success',
            'data' => $admissions,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }
    
    /**
     * GET /api/admissions/{id}
     * Get single admission details
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function show($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        // Decode documents JSON
        if (!empty($admission['documents'])) {
            $admission['documents'] = json_decode($admission['documents'], true);
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $admission
        ]);
    }
    
    /**
     * POST /api/admissions
     * Create new admission
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        
        // Generate registration number
        $data['registration_number'] = $this->model->generateRegistrationNumber();
        $data['status'] = $data['status'] ?? 'pending';
        $data['application_date'] = $data['application_date'] ?? date('Y-m-d');
        
        if (!$this->model->save($data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $id = $this->model->getInsertID();
        $admission = $this->model->find($id);
        
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Admission created successfully',
            'data' => $admission
        ]);
    }
    
    /**
     * PUT /api/admissions/{id}
     * Update admission
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function update($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        $data = $this->request->getJSON(true);
        
        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $updated = $this->model->find($id);
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Admission updated successfully',
            'data' => $updated
        ]);
    }
    
    /**
     * DELETE /api/admissions/{id}
     * Delete admission (soft delete)
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function delete($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        $this->model->delete($id);
        
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Admission deleted successfully'
        ]);
    }
    
    /**
     * GET /api/admissions/search?q={keyword}
     * Search admissions
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->fail('Search keyword is required');
        }
        
        $results = $this->model->searchAdmissions($keyword);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/admissions/filter?status={status}
     * Filter admissions by status
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function filter()
    {
        $status = $this->request->getGet('status');
        
        if (!$status) {
            return $this->fail('Status parameter is required');
        }
        
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            return $this->fail('Invalid status value');
        }
        
        $results = $this->model->filterByStatus($status);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * POST /api/admissions/{id}/approve
     * Approve an admission
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function approve($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        if ($admission['status'] === 'approved') {
            return $this->fail('Admission is already approved');
        }
        
        $data = $this->request->getJSON(true) ?? [];
        $notes = $data['notes'] ?? 'Approved via API';
        
        $updateData = [
            'status' => 'approved',
            'reviewed_date' => date('Y-m-d H:i:s'),
            'notes' => $admission['notes'] ? $admission['notes'] . "\n" . $notes : $notes
        ];
        
        if ($this->model->update($id, $updateData)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Admission approved successfully',
                'data' => $this->model->find($id)
            ]);
        }
        
        return $this->fail('Failed to approve admission');
    }

    /**
     * POST /api/admissions/{id}/reject
     * Reject an admission
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function reject($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        if ($admission['status'] === 'rejected') {
            return $this->fail('Admission is already rejected');
        }
        
        $data = $this->request->getJSON(true) ?? [];
        $notes = $data['notes'] ?? 'Rejected via API';
        
        $updateData = [
            'status' => 'rejected',
            'reviewed_date' => date('Y-m-d H:i:s'),
            'notes' => $admission['notes'] ? $admission['notes'] . "\n" . $notes : $notes
        ];
        
        if ($this->model->update($id, $updateData)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Admission rejected successfully',
                'data' => $this->model->find($id)
            ]);
        }
        
        return $this->fail('Failed to reject admission');
    }

    /**
     * POST /api/admissions/{id}/promote
     * Promote an admission to student
     * 
     * @param int|null $id Admission ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function promote($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        if ($admission['status'] !== 'approved') {
            return $this->fail('Only approved admissions can be promoted to student');
        }
        
        // Check if already promoted
        $db = \Config\Database::connect();
        $existingStudent = $db->table('students')->where('admission_id', $id)->first();
        
        if ($existingStudent) {
            return $this->fail('This admission has already been promoted to student');
        }
        
        // Get profile data
        $profile = $db->table('profiles')->find($admission['profile_id']);
        
        if (!$profile) {
            return $this->failNotFound('Profile not found for this admission');
        }
        
        $db->transBegin();
        
        try {
            // Create user account
            $userModel = new \CodeIgniter\Shield\Models\UserModel();
            $username = $profile['citizen_id'] ?? $profile['phone'];
            $password = $profile['phone'] ?? rand(100000, 999999);
            
            $userEntity = new \CodeIgniter\Shield\Entities\User([
                'username' => $username,
                'email'    => $profile['email'],
                'password' => $password,
            ]);
            
            $userModel->save($userEntity);
            $userId = $userModel->getInsertID();
            $user = $userModel->findById($userId);
            $user->activate();
            $user->addGroup('student');
            $userModel->save($user);
            
            // Update profile with user_id
            $db->table('profiles')->update(['user_id' => $userId], ['id' => $admission['profile_id']]);
            
            // Create student record
            $studentNumber = 'STU-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $db->table('students')->insert([
                'student_number' => $studentNumber,
                'profile_id' => $admission['profile_id'],
                'admission_id' => $id,
                'enrollment_date' => date('Y-m-d'),
                'status' => 'active',
                'program_id' => $admission['program_id'],
                'batch' => date('Y'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null
            ]);
            
            $db->transCommit();
            
            return $this->respond([
                'status' => 'success',
                'message' => 'Admission promoted to student successfully',
                'data' => [
                    'student_number' => $studentNumber,
                    'username' => $username,
                    'password' => $password // In production, send this via email instead
                ]
            ]);
            
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->fail('Failed to promote admission: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/admissions/statistics
     * Get admission statistics
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function statistics()
    {
        $db = \Config\Database::connect();
        
        // Get counts by status
        $statusCounts = $db->table('admissions')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $stats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total' => 0
        ];
        
        foreach ($statusCounts as $row) {
            $stats[$row['status']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }
        
        // Get today's admissions
        $today = date('Y-m-d');
        $todayCount = $db->table('admissions')
            ->where('DATE(created_at)', $today)
            ->countAllResults();
        
        $stats['today'] = $todayCount;
        
        // Get this month's admissions
        $monthStart = date('Y-m-01');
        $monthCount = $db->table('admissions')
            ->where('DATE(created_at) >=', $monthStart)
            ->countAllResults();
        
        $stats['this_month'] = $monthCount;
        
        return $this->respond([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
