<?php

namespace Modules\Classroom\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Classroom\Models\ClassroomModel;

class ClassroomApiController extends ResourceController
{
    protected $modelName = 'Modules\Classroom\Models\ClassroomModel';
    protected $format = 'json';

    /**
     * List all classrooms with pagination
     * GET /api/classrooms
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'start_date';
        $order = $this->request->getGet('order') ?? 'desc';

        // Validate sort order
        $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';

        // Allowed sort fields
        $allowedSortFields = [
            'title' => 'title',
            'batch' => 'batch',
            'program' => 'program',
            'status' => 'status',
            'start_date' => 'start_date'
        ];

        // Validate sort field
        $sortField = $allowedSortFields[$sort] ?? 'start_date';

        // Build query
        $builder = $this->model;

        // Apply search filter
        if ($search) {
            $builder = $builder->groupStart()
                ->like('title', $search)
                ->orLike('batch', $search)
                ->orLike('program', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status) {
            $builder = $builder->where('status', $status);
        }

        // Apply sorting
        $builder = $builder->orderBy($sortField, $order);

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $classrooms = $builder->paginate($perPage, 'default', $page);

        return $this->respond([
            'status' => 'success',
            'data' => $classrooms,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single classroom details
     * GET /api/classrooms/{id}
     */
    public function show($id = null)
    {
        $classroom = $this->model->find($id);

        if (!$classroom) {
            return $this->failNotFound('Classroom not found');
        }

        // Decode JSON fields
        $classroom['schedule'] = json_decode($classroom['schedule'] ?? '[]', true);
        $classroom['members'] = json_decode($classroom['members'] ?? '[]', true);

        return $this->respond([
            'status' => 'success',
            'data' => $classroom
        ]);
    }
}
