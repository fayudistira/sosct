<?php

namespace Modules\Account\Controllers;

use App\Controllers\BaseController;
use Modules\Account\Models\ProfileModel;

class ProfileController extends BaseController
{
    protected $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    /**
     * Display user profile or create button
     */
    public function index()
    {
        $user = auth()->user();
        $profile = $this->profileModel->getByUserId($user->id);
        
        // Get user's role from auth_groups_users
        $role = $this->getUserRole($user->id);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'profile' => $profile,
            'role' => $role
        ];

        return view('Modules\Account\Views\index', $data);
    }

    /**
     * Get user's role from auth_groups_users table
     */
    private function getUserRole(int $userId): ?string
    {
        $db = \Config\Database::connect();
        $result = $db->table('auth_groups_users')
                    ->select('group')
                    ->where('user_id', $userId)
                    ->get()
                    ->getRowArray();
        
        return $result['group'] ?? null;
    }

    /**
     * Show create profile form
     */
    public function create()
    {
        $user = auth()->user();
        
        // Check if profile already exists
        if ($this->profileModel->hasProfile($user->id)) {
            return redirect()->to('/account')->with('error', 'Profile already exists');
        }
        
        // Get user's role
        $role = $this->getUserRole($user->id);

        $data = [
            'title' => 'Create Profile',
            'user' => $user,
            'role' => $role,
            'validation' => \Config\Services::validation()
        ];

        return view('Modules\Account\Views\create', $data);
    }

    /**
     * Store new profile
     */
    public function store()
    {
        $user = auth()->user();
        
        // Check if profile already exists
        if ($this->profileModel->hasProfile($user->id)) {
            return redirect()->to('/account')->with('error', 'Profile already exists');
        }

        $data = $this->request->getPost();
        $data['user_id'] = $user->id;

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $photoPath = $this->profileModel->uploadPhoto($photo);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        // Handle document uploads
        $documents = $this->request->getFileMultiple('documents');
        $uploadedDocs = [];
        if ($documents) {
            foreach ($documents as $doc) {
                if ($doc->isValid()) {
                    $docPath = $this->profileModel->uploadDocument($doc);
                    if ($docPath) {
                        $uploadedDocs[] = $docPath;
                    }
                }
            }
        }
        if (!empty($uploadedDocs)) {
            $data['documents'] = json_encode($uploadedDocs);
        }

        if ($this->profileModel->insert($data)) {
            return redirect()->to('/account')->with('success', 'Profile created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->profileModel->errors());
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $this->profileModel->getByUserId($user->id);

        if (!$profile) {
            return redirect()->to('/account/create')->with('error', 'Please create your profile first');
        }
        
        // Get user's role
        $role = $this->getUserRole($user->id);

        $data = [
            'title' => 'Edit Profile',
            'user' => $user,
            'profile' => $profile,
            'role' => $role,
            'validation' => \Config\Services::validation()
        ];

        return view('Modules\Account\Views\edit', $data);
    }

    /**
     * Update profile
     */
    public function update()
    {
        $user = auth()->user();
        $profile = $this->profileModel->getByUserId($user->id);

        if (!$profile) {
            return redirect()->to('/account/create')->with('error', 'Please create your profile first');
        }

        $data = $this->request->getPost();
        $data['user_id'] = $user->id;

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $photoPath = $this->profileModel->uploadPhoto($photo);
            if ($photoPath) {
                // Delete old photo if exists
                if ($profile['photo']) {
                    $oldPhotoPath = WRITEPATH . 'uploads/' . $profile['photo'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $data['photo'] = $photoPath;
            }
        }

        // Handle document uploads
        $documents = $this->request->getFileMultiple('documents');
        if ($documents && $documents[0]->isValid()) {
            $existingDocs = $profile['documents'] ? json_decode($profile['documents'], true) : [];
            $uploadedDocs = $existingDocs;
            
            foreach ($documents as $doc) {
                if ($doc->isValid()) {
                    $docPath = $this->profileModel->uploadDocument($doc);
                    if ($docPath) {
                        $uploadedDocs[] = $docPath;
                    }
                }
            }
            $data['documents'] = json_encode($uploadedDocs);
        }

        if ($this->profileModel->update($profile['id'], $data)) {
            return redirect()->to('/account')->with('success', 'Profile updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->profileModel->errors());
    }
}
