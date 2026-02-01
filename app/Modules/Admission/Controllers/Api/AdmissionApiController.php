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
        
        $admissions = $this->model->paginate($perPage);
        $pager = $this->model->pager;
        
        return $this->respond([
            'status' => 'success',
            'data' => $admissions,
            'pagination' => [
                'current_page' => $pager->getCurrentPage(),
                'total_pages' => $pager->getPageCount(),
                'per_page' => $perPage,
                'total' => $pager->getTotal()
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
}
