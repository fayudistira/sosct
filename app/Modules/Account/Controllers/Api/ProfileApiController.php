<?php

namespace Modules\Account\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Account\Models\ProfileModel;

class ProfileApiController extends ResourceController
{
    protected $modelName = 'Modules\Account\Models\ProfileModel';
    protected $format = 'json';

    /**
     * List all profiles with pagination
     * GET /api/profiles
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'desc';
        
        // Validate sort field (whitelist allowed columns)
        $allowedSortFields = ['full_name', 'email', 'phone', 'created_at', 'citizen_id'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'created_at';
        }
        
        // Validate order direction
        $order = strtolower($order) === 'asc' ? 'asc' : 'desc';

        // Build query
        $builder = $this->model;

        // Apply search filter
        if ($search) {
            $builder = $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('citizen_id', $search)
                ->groupEnd();
        }
        
        // Apply sorting
        $builder = $builder->orderBy($sort, $order);

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $profiles = $builder->paginate($perPage, 'default', $page);

        return $this->respond([
            'status' => 'success',
            'data' => $profiles,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single profile details
     * GET /api/profiles/{id}
     */
    public function show($id = null)
    {
        $profile = $this->model->find($id);

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        // Get user info if linked
        if ($profile['user_id']) {
            $userModel = new \CodeIgniter\Shield\Models\UserModel();
            $user = $userModel->find($profile['user_id']);
            if ($user) {
                $profile['user'] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'groups' => $user->getGroups()
                ];
            }
        }

        return $this->respond([
            'status' => 'success',
            'data' => $profile
        ]);
    }

    /**
     * Create new profile
     * POST /api/profiles
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        // Required fields validation
        $requiredFields = ['full_name', 'email'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->fail([
                    'status' => 'error',
                    'message' => "{$field} is required",
                    'errors' => [$field => "{$field} is required"]
                ], 422);
            }
        }

        // Check for duplicate email
        $existingEmail = $this->model->where('email', $data['email'])->first();
        if ($existingEmail) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Email already exists',
                'errors' => ['email' => 'This email is already registered']
            ], 422);
        }

        if (!$this->model->save($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $id = $this->model->getInsertID();
        $profile = $this->model->find($id);

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Profile created successfully',
            'data' => $profile
        ]);
    }

    /**
     * Update profile
     * PUT /api/profiles/{id}
     */
    public function update($id = null)
    {
        $profile = $this->model->find($id);

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        $data = $this->request->getJSON(true);

        // Check for duplicate email (excluding current profile)
        if (!empty($data['email'])) {
            $existingEmail = $this->model->where('email', $data['email'])->where('id !=', $id)->first();
            if ($existingEmail) {
                return $this->fail([
                    'status' => 'error',
                    'message' => 'Email already in use',
                    'errors' => ['email' => 'This email is already registered to another profile']
                ], 422);
            }
        }

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $updated = $this->model->find($id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * Delete profile (soft delete)
     * DELETE /api/profiles/{id}
     */
    public function delete($id = null)
    {
        $profile = $this->model->find($id);

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Profile deleted successfully'
        ]);
    }

    /**
     * Search profiles
     * GET /api/profiles/search?q={keyword}
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return $this->fail('Search keyword is required');
        }

        $profiles = $this->model->groupStart()
            ->like('full_name', $keyword)
            ->orLike('email', $keyword)
            ->orLike('phone', $keyword)
            ->orLike('citizen_id', $keyword)
            ->groupEnd()
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'data' => $profiles,
            'count' => count($profiles)
        ]);
    }

    /**
     * Get current user's profile
     * GET /api/profiles/me
     */
    public function me()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Find profile for this user
        $profile = $this->model->where('user_id', $user->id)->first();

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        // Add user info
        $profile['user'] = [
            'id' => $user->id,
            'username' => $user->username,
            'groups' => $user->getGroups()
        ];

        return $this->respond([
            'status' => 'success',
            'data' => $profile
        ]);
    }

    /**
     * Update current user's profile
     * PUT /api/profiles/me
     */
    public function updateMe()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Find profile for this user
        $profile = $this->model->where('user_id', $user->id)->first();

        if (!$profile) {
            return $this->failNotFound('Profile not found');
        }

        $data = $this->request->getJSON(true);

        // Don't allow updating user_id
        unset($data['user_id']);

        // Check for duplicate email
        if (!empty($data['email'])) {
            $existingEmail = $this->model->where('email', $data['email'])->where('id !=', $profile['id'])->first();
            if ($existingEmail) {
                return $this->fail([
                    'status' => 'error',
                    'message' => 'Email already in use',
                    'errors' => ['email' => 'This email is already registered to another profile']
                ], 422);
            }
        }

        if (!$this->model->update($profile['id'], $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $updated = $this->model->find($profile['id']);

        return $this->respond([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $updated
        ]);
    }
}
