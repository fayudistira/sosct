<?php

namespace Modules\Users\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * List all users with pagination
     * GET /api/users
     */
    public function index()
    {
        $userModel = new UserModel();
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $role = $this->request->getGet('role');
        
        // Sorting parameters
        $sort = $this->request->getGet('sort') ?? 'id';
        $order = $this->request->getGet('order') ?? 'asc';
        
        // Validate sort field (whitelist allowed columns)
        $allowedSortFields = ['id', 'username', 'active', 'last_active', 'created_at'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'id';
        }
        
        // Validate order direction
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        // Build query
        $builder = $userModel->builder();

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('username', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status !== null && $status !== '') {
            $builder->where('active', $status === 'active' ? 1 : 0);
        }
        
        // Apply sorting
        $builder->orderBy($sort, $order);

        // Get total count before pagination
        $total = $builder->countAllResults(false);

        // Get paginated results
        $users = $userModel->paginate($perPage, 'default', $page);

        // Enrich user data
        $usersData = [];
        foreach ($users as $user) {
            $emailIdentity = $user->getEmailIdentity();
            $usersData[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $emailIdentity->secret ?? '-',
                'active' => $user->active,
                'last_active' => $user->last_active,
                'created_at' => $user->created_at,
                'groups' => $user->getGroups()
            ];
        }

        // Apply role filter after fetching (since it's in a separate table)
        if ($role) {
            $usersData = array_filter($usersData, function($user) use ($role) {
                return in_array($role, $user['groups']);
            });
            $usersData = array_values($usersData);
        }

        return $this->respond([
            'status' => 'success',
            'data' => $usersData,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Get single user details
     * GET /api/users/{id}
     */
    public function show($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $emailIdentity = $user->getEmailIdentity();
        
        // Get profile if exists
        $profile = null;
        $profileModel = new \Modules\Account\Models\ProfileModel();
        $profileData = $profileModel->where('user_id', $user->id)->first();
        if ($profileData) {
            $profile = $profileData;
        }

        return $this->respond([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $emailIdentity->secret ?? '-',
                'active' => $user->active,
                'last_active' => $user->last_active,
                'created_at' => $user->created_at,
                'groups' => $user->getGroups(),
                'permissions' => array_keys($user->getPermissions()),
                'profile' => $profile
            ]
        ]);
    }

    /**
     * Create new user
     * POST /api/users
     */
    public function create()
    {
        $userModel = new UserModel();
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Validate required fields
        if (empty($data['email'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Email is required',
                'errors' => ['email' => 'Email is required']
            ], 422);
        }

        if (empty($data['password'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Password is required',
                'errors' => ['password' => 'Password is required']
            ], 422);
        }

        // Check if email already exists
        $existingUser = $userModel->where('email', $data['email'])->first();
        if ($existingUser) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Email already registered',
                'errors' => ['email' => 'This email is already registered']
            ], 422);
        }

        // Check if username already exists
        if (!empty($data['username'])) {
            $existingUsername = $userModel->where('username', $data['username'])->first();
            if ($existingUsername) {
                return $this->fail([
                    'status' => 'error',
                    'message' => 'Username already taken',
                    'errors' => ['username' => 'This username is already taken']
                ], 422);
            }
        }

        // Create user entity
        $userData = [
            'email'    => $data['email'],
            'username' => $data['username'] ?? $data['email'],
            'password' => $data['password'],
        ];

        $user = new User($userData);

        try {
            $userModel->save($user);
            $userId = $userModel->getInsertID();
            $newUser = $userModel->findById($userId);

            // Add to groups if specified
            if (!empty($data['groups'])) {
                $groups = is_array($data['groups']) ? $data['groups'] : [$data['groups']];
                foreach ($groups as $group) {
                    $newUser->addGroup($group);
                }
            } else {
                // Add to default group
                $newUser->addGroup('user');
            }

            // Activate user if specified
            if (isset($data['active']) && $data['active']) {
                $newUser->activate();
                $userModel->save($newUser);
            }

            $emailIdentity = $newUser->getEmailIdentity();

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    'id' => $newUser->id,
                    'username' => $newUser->username,
                    'email' => $emailIdentity->secret ?? '-',
                    'active' => $newUser->active,
                    'groups' => $newUser->getGroups()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to create user',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Update user
     * PUT /api/users/{id}
     */
    public function update($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Update user fields
        if (!empty($data['email'])) {
            // Check if email is already taken by another user
            $existingEmail = $userModel->where('email', $data['email'])->where('id !=', $id)->first();
            if ($existingEmail) {
                return $this->fail([
                    'status' => 'error',
                    'message' => 'Email already in use',
                    'errors' => ['email' => 'This email is already registered to another user']
                ], 422);
            }
            $user->email = $data['email'];
        }

        if (!empty($data['username'])) {
            // Check if username is already taken
            $existingUsername = $userModel->where('username', $data['username'])->where('id !=', $id)->first();
            if ($existingUsername) {
                return $this->fail([
                    'status' => 'error',
                    'message' => 'Username already taken',
                    'errors' => ['username' => 'This username is already taken']
                ], 422);
            }
            $user->username = $data['username'];
        }

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        if (isset($data['active'])) {
            if ($data['active']) {
                $user->activate();
            } else {
                $user->deactivate();
            }
        }

        try {
            $userModel->save($user);
            $updatedUser = $userModel->findById($id);
            $emailIdentity = $updatedUser->getEmailIdentity();

            // Update groups if specified
            if (isset($data['groups'])) {
                $groups = is_array($data['groups']) ? $data['groups'] : [$data['groups']];
                // Remove from all groups
                foreach ($updatedUser->getGroups() as $group) {
                    $updatedUser->removeGroup($group);
                }
                // Add to new groups
                foreach ($groups as $group) {
                    $updatedUser->addGroup($group);
                }
                $userModel->save($updatedUser);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'id' => $updatedUser->id,
                    'username' => $updatedUser->username,
                    'email' => $emailIdentity->secret ?? '-',
                    'active' => $updatedUser->active,
                    'groups' => $updatedUser->getGroups()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to update user',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Delete user
     * DELETE /api/users/{id}
     */
    public function delete($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        // Prevent deleting yourself
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id == $id) {
            return $this->fail([
                'status' => 'error',
                'message' => 'You cannot delete your own account'
            ], 422);
        }

        try {
            $userModel->delete($id);
            return $this->respondDeleted([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to delete user',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Activate user
     * PUT /api/users/{id}/activate
     */
    public function activate($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $user->activate();

        try {
            $userModel->save($user);
            return $this->respond([
                'status' => 'success',
                'message' => 'User activated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to activate user',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Deactivate user
     * PUT /api/users/{id}/deactivate
     */
    public function deactivate($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        // Prevent deactivating yourself
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id == $id) {
            return $this->fail([
                'status' => 'error',
                'message' => 'You cannot deactivate your own account'
            ], 422);
        }

        $user->deactivate();

        try {
            $userModel->save($user);
            return $this->respond([
                'status' => 'success',
                'message' => 'User deactivated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to deactivate user',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Assign user to group
     * POST /api/users/{id}/assign-group
     */
    public function assignGroup($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $group = $data['group'] ?? null;

        if (!$group) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Group is required',
                'errors' => ['group' => 'Group is required']
            ], 422);
        }

        try {
            $user->addGroup($group);
            $userModel->save($user);

            return $this->respond([
                'status' => 'success',
                'message' => 'User assigned to group successfully',
                'data' => [
                    'groups' => $user->getGroups()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to assign group',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Remove user from group
     * POST /api/users/{id}/remove-group
     */
    public function removeGroup($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $group = $data['group'] ?? null;

        if (!$group) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Group is required',
                'errors' => ['group' => 'Group is required']
            ], 422);
        }

        try {
            $user->removeGroup($group);
            $userModel->save($user);

            return $this->respond([
                'status' => 'success',
                'message' => 'User removed from group successfully',
                'data' => [
                    'groups' => $user->getGroups()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to remove group',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Get user statistics
     * GET /api/users/statistics
     */
    public function statistics()
    {
        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Get total users
        $totalUsers = $userModel->countAll();

        // Get active users
        $activeUsers = $userModel->where('active', 1)->countAllResults();

        // Get users by group
        $groups = $db->table('auth_groups_users')
            ->select('group, COUNT(*) as count')
            ->groupBy('group')
            ->get()
            ->getResultArray();

        $groupCounts = [];
        foreach ($groups as $row) {
            $groupCounts[$row['group']] = (int) $row['count'];
        }

        // Get new users this month
        $monthStart = date('Y-m-01');
        $newUsers = $userModel->where('created_at >=', $monthStart)->countAllResults();

        return $this->respond([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'inactive_users' => $totalUsers - $activeUsers,
                'by_group' => $groupCounts,
                'new_this_month' => $newUsers
            ]
        ]);
    }
}
