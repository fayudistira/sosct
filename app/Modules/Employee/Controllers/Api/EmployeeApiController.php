<?php

namespace Modules\Employee\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Employee\Models\StaffModel;

class EmployeeApiController extends ResourceController
{
    protected $modelName = 'Modules\Employee\Models\StaffModel';
    protected $format = 'json';

    /**
     * List all employees with pagination
     * GET /api/employees
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $department = $this->request->getGet('department');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'staff_number';
        $order = $this->request->getGet('order') ?? 'asc';
        
        // Validate sort field (whitelist allowed columns)
        $allowedSortFields = ['staff_number', 'full_name', 'position', 'department', 'status', 'hire_date'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'staff_number';
        }
        
        // Validate order direction
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';
        
        // Map sort fields to actual column names (handle joined tables)
        $sortMapping = [
            'staff_number' => 'staff.staff_number',
            'full_name' => 'profiles.full_name',
            'position' => 'staff.position',
            'department' => 'staff.department',
            'status' => 'staff.status',
            'hire_date' => 'staff.hire_date'
        ];
        $sortColumn = $sortMapping[$sort] ?? 'staff.staff_number';

        // Build query
        $builder = $this->model->select('staff.*, profiles.full_name, profiles.email, profiles.phone')
            ->join('profiles', 'profiles.id = staff.profile_id');

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('profiles.full_name', $search)
                ->orLike('profiles.email', $search)
                ->orLike('staff.staff_number', $search)
                ->orLike('staff.position', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status) {
            $builder->where('staff.status', $status);
        }

        // Apply department filter
        if ($department) {
            $builder->like('staff.department', $department);
        }
        
        // Apply sorting
        $builder->orderBy($sortColumn, $order);

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $employees = $builder->paginate($perPage, 'default', $page);

        return $this->respond([
            'status' => 'success',
            'data' => $employees,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single employee details
     * GET /api/employees/{id}
     */
    public function show($id = null)
    {
        $employee = $this->model->select('staff.*, profiles.full_name, profiles.email, profiles.phone')
            ->join('profiles', 'profiles.id = staff.profile_id')
            ->find($id);

        if (!$employee) {
            return $this->failNotFound('Employee not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $employee
        ]);
    }
}
