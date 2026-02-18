<?php

namespace Modules\Users\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Models\UserModel;

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
}
