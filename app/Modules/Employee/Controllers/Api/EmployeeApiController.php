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
