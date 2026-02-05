<?php

namespace Modules\Employee\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use Modules\Account\Models\ProfileModel;
use Modules\Employee\Models\StaffModel;

class EmployeeController extends BaseController
{
    protected $staffModel;
    protected $profileModel;
    protected $userModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
        $this->profileModel = new ProfileModel();
        $this->userModel = new UserModel();
    }

    /**
     * List all employees
     */
    public function index()
    {
        $data = [
            'title' => 'Employee Management',
            'employees' => $this->staffModel->getStaffWithDetails(),
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Employee\Views\index', $data);
    }

    /**
     * Show detailed employee profile
     */
    public function show($id)
    {
        $staff = $this->staffModel->getStaffWithDetails($id);
        
        if (!$staff) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Employee not found');
        }

        // Decode documents JSON if it exists
        if (!empty($staff['documents'])) {
            $decoded = json_decode($staff['documents'], true);
            if (is_array($decoded)) {
                $staff['documents'] = $decoded;
            }
        }

        $data = [
            'title' => 'Employee Details - ' . $staff['full_name'],
            'staff' => $staff,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Employee\Views\view', $data);
    }

    /**
     * Display create employee form
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Employee',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'statusOptions' => ['active', 'inactive', 'resigned', 'terminated'],
            'employmentTypes' => ['full-time', 'part-time', 'contract']
        ];

        return view('Modules\Employee\Views\form', $data);
    }

    /**
     * Store new employee (User -> Profile -> Staff)
     */
    public function store()
    {
        $rules = array_merge(
            $this->profileModel->getValidationRules(),
            $this->staffModel->getValidationRules(),
            [
                'username' => 'required|is_unique[users.username]',
                'password' => 'required|min_length[8]',
            ]
        );

        // Adjust some rules for unified form
        unset($rules['user_id']);
        unset($rules['profile_id']);
        unset($rules['staff_number']); // Generated in controller
        unset($rules['id']); // Remove ID rule for store

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Create Shield User
            $userEntity = new User([
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
            ]);
            
            if (!$this->userModel->save($userEntity)) {
                throw new \Exception('Failed to create user account.');
            }
            
            $userId = $this->userModel->getInsertID();
            $userEntity = $this->userModel->find($userId);
            $userEntity->activate();
            $userEntity->addGroup('staff');

            // 2. Create Profile
            $profileData = $this->request->getPost();
            $profileData['user_id'] = $userId;
            $profileData['profile_number'] = $this->profileModel->generateProfileNumber();

            // Handle Photo
            $photoFile = $this->request->getFile('photo');
            if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
                $profileData['photo'] = $this->profileModel->uploadPhoto($photoFile);
            }

            // Handle Documents
            $docFiles = $this->request->getFileMultiple('documents');
            if ($docFiles) {
                $docPaths = [];
                foreach ($docFiles as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $docPaths[] = $this->profileModel->uploadDocument($file);
                    }
                }
                if (!empty($docPaths)) {
                    $profileData['documents'] = json_encode($docPaths);
                }
            }

            if (!$this->profileModel->insert($profileData)) {
                throw new \Exception('Failed to create profile.');
            }
            
            $profileId = $this->profileModel->getInsertID();

            // 3. Create Staff Record
            $staffData = $this->request->getPost();
            $staffData['profile_id'] = $profileId;
            $staffData['staff_number'] = $this->staffModel->generateStaffNumber();

            if (!$this->staffModel->insert($staffData)) {
                throw new \Exception('Failed to create employment record.');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed.');
            }

            return redirect()->to('admin/employee')
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display edit employee form
     */
    public function edit($id)
    {
        $staff = $this->staffModel->getStaffWithDetails($id);
        
        if (!$staff) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Employee not found');
        }

        $data = [
            'title' => 'Edit Employee: ' . $staff['full_name'],
            'staff' => $staff,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'statusOptions' => ['active', 'inactive', 'resigned', 'terminated'],
            'employmentTypes' => ['full-time', 'part-time', 'contract']
        ];

        return view('Modules\Employee\Views\form', $data);
    }

    /**
     * Update employee records
     */
    public function update($id)
    {
        $existingStaff = $this->staffModel->find($id);
        if (!$existingStaff) {
             throw new \CodeIgniter\Exceptions\PageNotFoundException('Employee not found');
        }

        $profileId = $existingStaff['profile_id'];
        $existingProfile = $this->profileModel->find($profileId);
        $userId = $existingProfile['user_id'];

        // Validation Rules (Subset for Update)
        $rules = array_merge(
            $this->profileModel->getValidationRules(),
            $this->staffModel->getValidationRules()
        );
        
        // Dynamic validation context for unique checks
        $rules['email'] = "required|valid_email|is_unique[profiles.email,id,{$profileId}]";
        
        unset($rules['user_id']);
        unset($rules['profile_id']);
        unset($rules['staff_number']); // Staff number is usually ready-only or already exists

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update email in Shield if changed
            $newEmail = $this->request->getPost('email');
            if ($newEmail !== $existingProfile['email']) {
                $user = $this->userModel->find($userId);
                $user->email = $newEmail;
                $this->userModel->save($user);
            }

            // Update Profile
            $profileData = $this->request->getPost();
            
            // Handle Photo Update
            $photoFile = $this->request->getFile('photo');
            if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
                $profileData['photo'] = $this->profileModel->uploadPhoto($photoFile);
                // Optional: Delete old photo
            }

            $this->profileModel->update($profileId, $profileData);

            // Update Staff
            $this->staffModel->update($id, $this->request->getPost());

            $db->transComplete();

            if ($db->transStatus() === false) {
                 throw new \Exception('Database transaction failed.');
            }

            return redirect()->to('admin/employee/view/' . $id)
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete employee (Soft delete staff)
     */
    public function delete($id)
    {
        if ($this->staffModel->delete($id)) {
            return redirect()->to('admin/employee')
                ->with('success', 'Employee deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete employee.');
    }
}
