<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\LoginModel;
use CodeIgniter\Shield\Entities\Login;

/**
 * API Authentication Controller
 * Handles login, logout, token management for API access
 */
class AuthApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * POST /api/auth/login
     * Login with credentials and get access token
     */
    public function login()
    {
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        // Validate input
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Email and password are required',
                'errors' => [
                    'email' => 'Email is required',
                    'password' => 'Password is required'
                ]
            ], 422);
        }

        // Get the authenticator
        $authenticator = auth('session')->attempt($credentials);

        if (!$authenticator->isOK()) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Invalid login credentials',
                'errors' => [
                    'credentials' => 'Invalid email or password'
                ]
            ], 401);
        }

        $user = $authenticator->getUser();

        // Check if user is active
        if (!$user->isActive()) {
            auth()->logout();
            return $this->fail([
                'status' => 'error',
                'message' => 'Account is inactive'
            ], 403);
        }

        // Generate access token for API access
        $token = $this->generateAccessToken($user);

        // Get user groups/roles
        $groups = $user->getGroups();
        $permissions = $user->getPermissions();

        return $this->respond([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'username' => $user->username,
                    'groups' => $groups,
                    'permissions' => array_keys($permissions)
                ],
                'access_token' => $token->raw_token,
                'token_type' => 'Bearer',
                'token_expiry' => date('Y-m-d H:i:s', time() + (config('AuthToken')->unusedTokenLifetime ?? 31536000))
            ]
        ], 200);
    }

    /**
     * POST /api/auth/register
     * Register new user and get access token
     */
    public function register()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Validate required fields
        $required = ['email', 'password', 'password_confirm'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->fail([
                    'status' => 'error',
                    'message' => "{$field} is required",
                    'errors' => [$field => "{$field} is required"]
                ], 422);
            }
        }

        // Check password confirmation
        if ($data['password'] !== $data['password_confirm']) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Passwords do not match',
                'errors' => ['password_confirm' => 'Passwords do not match']
            ], 422);
        }

        // Get registration fields from config
        $config = config('Auth');
        $requireEmailVerification = $config->requireEmailVerification ?? false;

        // Prepare user data
        $userData = [
            'email'    => $data['email'],
            'username' => $data['username'] ?? $data['email'],
            'password' => $data['password'],
        ];

        // Get UserModel and save user
        $userModel = new UserModel();

        // Check if email already exists
        $existingUser = $userModel->where('email', $data['email'])->first();
        if ($existingUser) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Email already registered',
                'errors' => ['email' => 'This email is already registered']
            ], 422);
        }

        // Create user entity
        $user = new User($userData);

        try {
            $userModel->save($user);

            // Get the created user
            $newUser = $userModel->findById($userModel->getInsertID());

            // Add to default group (usually 'user')
            $newUser->addGroup('user');

            // Handle email verification if required
            if ($requireEmailVerification) {
                // Send verification email (implementation depends on setup)
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Registration successful. Please verify your email.',
                    'data' => [
                        'user_id' => $newUser->id,
                        'email' => $newUser->email
                    ]
                ], 201);
            }

            // Activate user and generate token
            $newUser->activate();
            $userModel->save($newUser);

            // Generate access token
            $token = $this->generateAccessToken($newUser);

            // Get user groups
            $groups = $newUser->getGroups();

            return $this->respond([
                'status' => 'success',
                'message' => 'Registration successful',
                'data' => [
                    'user' => [
                        'id' => $newUser->id,
                        'email' => $newUser->email,
                        'username' => $newUser->username,
                        'groups' => $groups
                    ],
                    'access_token' => $token->raw_token,
                    'token_type' => 'Bearer'
                ]
            ], 201);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Registration failed',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * POST /api/auth/logout
     * Logout and invalidate token
     */
    public function logout()
    {
        $user = auth()->user();

        if ($user) {
            // Get the token used for this request
            $token = $this->request->getBearerToken();

            if ($token) {
                // Delete the specific token
                $tokenModel = new \CodeIgniter\Shield\Authentication\Entities\AccessToken();
                $db = \Config\Database::connect();
                $db->table('auth_tokens')
                    ->where('raw_token', hash('sha256', $token))
                    ->delete();
            }

            // Logout from session
            auth()->logout();
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }

    /**
     * GET /api/auth/me
     * Get current authenticated user info
     */
    public function me()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Get profile if exists
        $profile = null;
        $profileModel = new \Modules\Account\Models\ProfileModel();
        $profileData = $profileModel->where('user_id', $user->id)->first();
        if ($profileData) {
            $profile = $profileData;
        }

        // Get user groups and permissions
        $groups = $user->getGroups();
        $permissions = $user->getPermissions();

        return $this->respond([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'username' => $user->username,
                    'active' => $user->active,
                    'groups' => $groups,
                    'permissions' => array_keys($permissions),
                    'created_at' => $user->created_at,
                    'last_active' => $user->last_active
                ],
                'profile' => $profile
            ]
        ]);
    }

    /**
     * POST /api/auth/refresh
     * Refresh access token
     */
    public function refresh()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Get the current token and delete it
        $token = $this->request->getBearerToken();
        if ($token) {
            $db = \Config\Database::connect();
            $db->table('auth_tokens')
                ->where('raw_token', hash('sha256', $token))
                ->delete();
        }

        // Generate new token
        $newToken = $this->generateAccessToken($user);

        return $this->respond([
            'status' => 'success',
            'message' => 'Token refreshed successfully',
            'data' => [
                'access_token' => $newToken->raw_token,
                'token_type' => 'Bearer',
                'token_expiry' => date('Y-m-d H:i:s', time() + (config('AuthToken')->unusedTokenLifetime ?? 31536000))
            ]
        ]);
    }

    /**
     * POST /api/auth/change-password
     * Change user password
     */
    public function changePassword()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Validate required fields
        if (empty($data['current_password']) || empty($data['new_password']) || empty($data['new_password_confirm'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'All password fields are required',
                'errors' => [
                    'current_password' => 'Current password is required',
                    'new_password' => 'New password is required',
                    'new_password_confirm' => 'Password confirmation is required'
                ]
            ], 422);
        }

        // Check password confirmation
        if ($data['new_password'] !== $data['new_password_confirm']) {
            return $this->fail([
                'status' => 'error',
                'message' => 'New passwords do not match',
                'errors' => ['new_password_confirm' => 'Passwords do not match']
            ], 422);
        }

        // Verify current password
        $credentials = [
            'email'    => $user->email,
            'password' => $data['current_password'],
        ];

        $authenticator = auth('session')->attempt($credentials);

        if (!$authenticator->isOK()) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Current password is incorrect',
                'errors' => ['current_password' => 'Current password is incorrect']
            ], 422);
        }

        // Update password
        $userModel = new UserModel();
        $user->password = $data['new_password'];
        
        try {
            $userModel->save($user);
            return $this->respond([
                'status' => 'success',
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to change password',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Generate access token for user
     */
    protected function generateAccessToken(User $user): \CodeIgniter\Shield\Entities\AccessToken
    {
        // Create a descriptive name for the token
        $tokenName = 'API Token - ' . date('Y-m-d H:i:s');
        
        // Generate the token
        $token = $user->generateAccessToken($tokenName);
        
        return $token;
    }
}
