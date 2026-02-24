<?php

namespace Modules\Program\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Program\Models\ProgramModel;

class ProgramApiController extends ResourceController
{
    protected $modelName = 'Modules\Program\Models\ProgramModel';
    protected $format = 'json';
    
    /**
     * GET /api/programs
     * List all programs with pagination
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        $language = $this->request->getGet('language');
        $languageLevel = $this->request->getGet('language_level');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'title';
        $order = $this->request->getGet('order') ?? 'asc';
        
        // Validate sort field (whitelist allowed columns)
        $allowedSortFields = ['title', 'category', 'sub_category', 'language', 'language_level', 'duration', 'registration_fee', 'tuition_fee', 'discount', 'status', 'created_at'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'title';
        }
        
        // Validate order direction
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';
        
        // Build query
        $builder = $this->model;
        
        // Apply search filter
        if ($search) {
            $builder = $builder->groupStart()
                ->like('title', $search)
                ->orLike('category', $search)
                ->orLike('sub_category', $search)
                ->orLike('language', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }
        
        // Apply status filter
        if ($status) {
            $builder = $builder->where('status', $status);
        }
        
        // Apply category filter
        if ($category) {
            $builder = $builder->like('category', $category);
        }
        
        // Apply language filter
        if ($language) {
            $builder = $builder->where('language', $language);
        }
        
        // Apply language level filter
        if ($languageLevel) {
            $builder = $builder->where('language_level', $languageLevel);
        }
        
        // Apply sorting
        $builder = $builder->orderBy($sort, $order);
        
        // Get total count before pagination
        $total = $builder->countAllResults(false);
        
        // Get paginated results
        $programs = $builder->paginate($perPage, 'default', $page);
        
        return $this->respond([
            'status' => 'success',
            'data' => $programs,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }
    
    /**
     * GET /api/programs/{id}
     * Get single program details
     * 
     * @param string|null $id Program ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function show($id = null)
    {
        $program = $this->model->find($id);
        
        if (!$program) {
            return $this->failNotFound('Program not found');
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $program
        ]);
    }
    
    /**
     * POST /api/programs
     * Create new program
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        
        // Set default status if not provided
        $data['status'] = $data['status'] ?? 'active';
        
        if (!$this->model->save($data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $id = $this->model->getInsertID();
        $program = $this->model->find($id);
        
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Program created successfully',
            'data' => $program
        ]);
    }
    
    /**
     * PUT /api/programs/{id}
     * Update program
     * 
     * @param string|null $id Program ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function update($id = null)
    {
        $program = $this->model->find($id);
        
        if (!$program) {
            return $this->failNotFound('Program not found');
        }
        
        $data = $this->request->getJSON(true);
        
        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $updated = $this->model->find($id);
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Program updated successfully',
            'data' => $updated
        ]);
    }
    
    /**
     * DELETE /api/programs/{id}
     * Delete program (soft delete)
     * 
     * @param string|null $id Program ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function delete($id = null)
    {
        $program = $this->model->find($id);
        
        if (!$program) {
            return $this->failNotFound('Program not found');
        }
        
        $this->model->delete($id);
        
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Program deleted successfully'
        ]);
    }
    
    /**
     * GET /api/programs/search?q={keyword}
     * Search programs
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->fail('Search keyword is required');
        }
        
        $results = $this->model->searchPrograms($keyword);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/programs/filter?status={status}
     * Filter programs by status
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function filterByStatus()
    {
        $status = $this->request->getGet('status');
        
        if (!$status) {
            return $this->fail('Status parameter is required');
        }
        
        if (!in_array($status, ['active', 'inactive'])) {
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
     * GET /api/programs/filter/category?category={category}
     * Filter programs by category
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function filterByCategory()
    {
        $category = $this->request->getGet('category');
        
        if (!$category) {
            return $this->fail('Category parameter is required');
        }
        
        $results = $this->model->filterByCategory($category);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/programs/active
     * Get only active programs
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function active()
    {
        $results = $this->model->getActivePrograms();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/programs/categories
     * Get programs grouped by category
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function categories()
    {
        $results = $this->model->getProgramsByCategory();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results
        ]);
    }
    
    /**
     * GET /api/programs/filter/language?language={language}
     * Filter programs by language
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function filterByLanguage()
    {
        $language = $this->request->getGet('language');
        
        if (!$language) {
            return $this->fail('Language parameter is required');
        }
        
        $results = $this->model->filterByLanguage($language);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/programs/filter/language-level?level={level}
     * Filter programs by language level
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function filterByLanguageLevel()
    {
        $level = $this->request->getGet('level');
        
        if (!$level) {
            return $this->fail('Level parameter is required');
        }
        
        $results = $this->model->filterByLanguageLevel($level);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/programs/languages
     * Get available languages
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function languages()
    {
        $results = $this->model->getAvailableLanguages();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results
        ]);
    }
    
    /**
     * GET /api/programs/language-levels
     * Get available language levels
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function languageLevels()
    {
        $results = $this->model->getAvailableLanguageLevels();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results
        ]);
    }
    
    /**
     * GET /api/programs/by-language
     * Get programs grouped by language
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function byLanguage()
    {
        $results = $this->model->getProgramsByLanguage();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results
        ]);
    }
    
    /**
     * PUT /api/programs/{id}/sort-order
     * Update program sort order (for superadmin ajax)
     * 
     * @param string|null $id Program ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function updateSortOrder($id = null)
    {
        // Check if user is superadmin
        $user = auth()->user();
        if (!$user || !$user->inGroup('superadmin')) {
            return $this->failForbidden('Only superadmin can update sort order');
        }
        
        $program = $this->model->find($id);
        
        if (!$program) {
            return $this->failNotFound('Program not found');
        }
        
        $data = $this->request->getJSON(true);
        
        if (!isset($data['sort_order'])) {
            return $this->fail('sort_order field is required');
        }
        
        $sortOrder = (int) $data['sort_order'];
        
        if ($sortOrder < 1 || $sortOrder > 20) {
            return $this->fail('sort_order must be between 1 and 20');
        }
        
        if (!$this->model->update($id, ['sort_order' => $sortOrder])) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Sort order updated successfully',
            'data' => ['sort_order' => $sortOrder]
        ]);
    }
}
