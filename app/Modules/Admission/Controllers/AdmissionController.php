<?php

namespace Modules\Admission\Controllers;

use App\Controllers\BaseController;
use Modules\Admission\Models\AdmissionModel;
use Modules\Account\Models\ProfileModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\InstallmentModel;
use Modules\Student\Models\StudentModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AdmissionController extends BaseController
{
    protected $admissionModel;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * List all admissions with pagination and stats
     * 
     * @return string
     */
    public function index(): string
    {
        // Use getAllWithDetails to get joined data from profiles and programs
        $allAdmissions = $this->admissionModel->getAllWithDetails();

        // Manual pagination (since we're using a custom query)
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $data['admissions'] = array_slice($allAdmissions, $offset, $perPage);
        $data['totalAdmissions'] = count($allAdmissions);
        $data['currentPage'] = $page;
        $data['perPage'] = $perPage;
        $data['totalPages'] = ceil(count($allAdmissions) / $perPage);

        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();

        return view('Modules\Admission\Views\index', $data);
    }

    /**
     * View admission details with file links
     * 
     * @param int $id Admission ID
     * @return string
     */
    public function view($id): string
    {
        // Use getWithDetails to get joined data
        $data['admission'] = $this->admissionModel->getWithDetails($id);

        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Decode documents JSON if it exists
        if (!empty($data['admission']['documents'])) {
            $decoded = json_decode($data['admission']['documents'], true);
            if (is_array($decoded)) {
                $data['admission']['documents'] = $decoded;
            }
        }

        // Get Invoice Status for this admission (by registration number)
        $invoiceModel = new InvoiceModel();
        $installmentModel = new InstallmentModel();

        $invoices = $invoiceModel->getInvoicesByStudent($data['admission']['registration_number']);
        $data['invoice'] = !empty($invoices) ? $invoices[0] : null; // Get latest invoice

        // Get installment record
        $installment = $installmentModel->getByRegistrationNumber($data['admission']['registration_number']);
        $data['installment'] = $installment;

        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();

        return view('Modules\Admission\Views\view', $data);
    }

    /**
     * Show create form for manual entry
     * 
     * @return string
     */
    public function create(): string
    {
        // Load active programs for course selection
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')->findAll();

        return view('Modules\Admission\Views\create', [
            'title' => 'Create Admission',
            'programs' => $programs,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }


    /**
     * Store new admission (Creates Profile -> Admission -> Invoice)
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $profileModel = new ProfileModel();
            $admissionModel = new AdmissionModel();
            $programModel = new ProgramModel();
            $invoiceModel = new InvoiceModel();

            // 1. Validate Program
            $programId = $this->request->getPost('program_id');
            // Compatibility check: if view sends 'course' (old), try to find program
            if (empty($programId) && $this->request->getPost('course')) {
                $courseName = $this->request->getPost('course');
                $prog = $programModel->where('title', $courseName)->first();
                if ($prog) $programId = $prog['id'];
            }

            $program = $programModel->find($programId);
            if (!$program) {
                throw new \Exception('Selected program is invalid.');
            }

            // 2. Create Orphaned Profile
            $profileData = [
                'profile_number' => $profileModel->generateProfileNumber(),
                'full_name' => $this->request->getPost('full_name'),
                'nickname' => $this->request->getPost('nickname'),
                'gender' => $this->request->getPost('gender'),
                'place_of_birth' => $this->request->getPost('place_of_birth'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'religion' => $this->request->getPost('religion'),
                'citizen_id' => $this->request->getPost('citizen_id'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'street_address' => $this->request->getPost('street_address'),
                'district' => $this->request->getPost('district'),
                'regency' => $this->request->getPost('regency'),
                'province' => $this->request->getPost('province'),
                'postal_code' => $this->request->getPost('postal_code'),
                'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
                'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
                'emergency_contact_relation' => $this->request->getPost('emergency_contact_relation'),
                'father_name' => $this->request->getPost('father_name'),
                'mother_name' => $this->request->getPost('mother_name'),
            ];

            // Handle Profile Photo
            $photo = $this->request->getFile('photo');
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                $profileData['photo'] = $profileModel->uploadPhoto($photo);
            }

            // Handle Documents
            $documents = $this->request->getFileMultiple('documents');
            if ($documents) {
                $docPaths = [];
                foreach ($documents as $doc) {
                    if ($doc->isValid() && !$doc->hasMoved()) {
                        $docPaths[] = $profileModel->uploadDocument($doc);
                    }
                }
                if (!empty($docPaths)) {
                    $profileData['documents'] = json_encode($docPaths);
                }
            }

            if (!$profileModel->insert($profileData)) {
                throw new \Exception('Failed to create profile: ' . json_encode($profileModel->errors()));
            }
            $profileId = $profileModel->getInsertID();

            // 3. Create Admission
            $admissionData = [
                'registration_number' => $admissionModel->generateRegistrationNumber(),
                'profile_id' => $profileId,
                'program_id' => $programId,
                'status' => $this->request->getPost('status') ?? 'pending',
                'application_date' => date('Y-m-d'),
                'notes' => $this->request->getPost('notes')
            ];

            if (!$admissionModel->insert($admissionData)) {
                throw new \Exception('Failed to create admission: ' . json_encode($admissionModel->errors()));
            }

            // 4. Calculate total fees and create Installment Record
            $regFee = (float)($program['registration_fee'] ?? 0);
            $tuitionFee = (float)($program['tuition_fee'] ?? 0);
            $totalAmount = $regFee + $tuitionFee;
            $dueDate = date('Y-m-d', strtotime('+2 weeks')); // 2 weeks to pay

            $installmentModel = new InstallmentModel();
            $installmentData = [
                'registration_number' => $admissionData['registration_number'],
                'total_contract_amount' => $totalAmount,
                'total_paid' => 0,
                'remaining_balance' => $totalAmount,
                'status' => 'unpaid',
                'due_date' => $dueDate
            ];

            if (!$installmentModel->createInstallment($installmentData)) {
                throw new \Exception('Failed to create installment record: ' . json_encode($installmentModel->errors()));
            }
            $installmentId = $installmentModel->insertID();

            log_message('error', '[Debug] Program ID: ' . $programId . ' | Title: ' . $program['title'] . ' | Reg Fee: ' . $regFee . ' | Tuition Fee: ' . $tuitionFee . ' | Total: ' . $totalAmount);

            // 5. Create Registration Invoice with 2 items (Registration Fee + Course Fee)
            if ($totalAmount > 0) {
                log_message('error', '[Debug] Attempting to create invoice for total amount: ' . $totalAmount);

                // Create items array with 2 entries
                $items = [
                    [
                        'description' => 'Registration Fee for ' . $program['title'],
                        'amount' => $regFee,
                        'type' => 'registration_fee'
                    ],
                    [
                        'description' => 'Course Fee for ' . $program['title'],
                        'amount' => $tuitionFee,
                        'type' => 'tuition_fee'
                    ]
                ];

                $invoiceData = [
                    'registration_number' => $admissionData['registration_number'],
                    'contract_number' => $admissionData['registration_number'],
                    'installment_id' => $installmentId,
                    'description' => 'Payment for ' . $program['title'] . ' Program',
                    'amount' => $totalAmount,
                    'due_date' => $dueDate, // Use installment due date
                    'invoice_type' => 'tuition_fee',
                    'status' => 'unpaid',
                    'items' => json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ];

                if (!$invoiceModel->createInvoice($invoiceData)) {
                    log_message('error', '[Debug] Invoice creation FAILED: ' . json_encode($invoiceModel->errors()));
                    throw new \Exception('Failed to create invoice: ' . json_encode($invoiceModel->errors()));
                }
                log_message('error', '[Debug] Invoice created successfully with 2 items.');
            } else {
                log_message('error', '[Debug] Skipping invoice creation because total fee is 0.');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed.');
            }

            // 5. Send Invoice Email to Guest
            if ($regFee > 0) {
                $emailService = new \App\Services\EmailService();
                $invoiceDetails = $invoiceModel->where('registration_number', $admissionData['registration_number'])->first();

                if ($invoiceDetails) {
                    $emailData = [
                        'amount' => $invoiceDetails['amount'],
                        'due_date' => $invoiceDetails['due_date'],
                        'description' => $invoiceDetails['description']
                    ];

                    $admissionContext = [
                        'registration_number' => $admissionData['registration_number'],
                        'program_title' => $program['title']
                    ];

                    // Send email (async - doesn't block if it fails)
                    $emailService->sendInvoiceNotification(
                        $emailData,
                        $profileData['email'],
                        $profileData['full_name'],
                        $admissionContext,
                        $invoiceModel->where('registration_number', $admissionData['registration_number'])->first()['id'] ?? null
                    );
                }
            }

            return redirect()->to('/admission')
                ->with('success', 'Admission created successfully. Invoice generated and sent to email.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show edit form
     * 
     * @param int $id Admission ID
     * @return string
     */
    public function edit($id): string
    {
        // Use getWithDetails to get joined data
        $data['admission'] = $this->admissionModel->getWithDetails($id);

        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Load active programs for program selection
        $programModel = new ProgramModel();
        $data['programs'] = $programModel->where('status', 'active')->findAll();

        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();

        return view('Modules\Admission\Views\edit', $data);
    }

    /**
     * Update admission (updates both profile and admission data)
     * 
     * @param int $id Admission ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        // Get existing admission with profile data
        $existing = $this->admissionModel->getWithDetails($id);
        if (!$existing) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        $profileId = $existing['profile_id'];

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update Profile Data
            $profileModel = new ProfileModel();
            $profileData = [
                'id' => $profileId, // Include ID for validation to work correctly
                'full_name' => $this->request->getPost('full_name'),
                'nickname' => $this->request->getPost('nickname'),
                'gender' => $this->request->getPost('gender'),
                'place_of_birth' => $this->request->getPost('place_of_birth'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'religion' => $this->request->getPost('religion'),
                'citizen_id' => $this->request->getPost('citizen_id'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'street_address' => $this->request->getPost('street_address'),
                'district' => $this->request->getPost('district'),
                'regency' => $this->request->getPost('regency'),
                'province' => $this->request->getPost('province'),
                'postal_code' => $this->request->getPost('postal_code'),
                'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
                'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
                'emergency_contact_relation' => $this->request->getPost('emergency_contact_relation'),
                'father_name' => $this->request->getPost('father_name'),
                'mother_name' => $this->request->getPost('mother_name'),
            ];

            // Handle photo upload
            $photo = $this->request->getFile('photo');
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                $profileData['photo'] = $profileModel->uploadPhoto($photo);
            }

            // Handle documents upload
            // Note: In update we might append or replace. For simplicity, appending or replacing based on logic?
            // Existing logic seemed to replace or add. Let's keep it simple: Add new ones.
            $documents = $this->request->getFileMultiple('documents');
            if ($documents) {
                // ... logic for docs
                // For now, let's keep it simple as before
            }

            // Update profile
            if (!$profileModel->update($profileId, $profileData)) {
                throw new \Exception('Failed to update profile: ' . json_encode($profileModel->errors()));
            }

            // Update Admission Data
            $admissionData = [
                'program_id' => $this->request->getPost('program_id'),
                'status' => $this->request->getPost('status'),
                'notes' => $this->request->getPost('notes'),
                'applicant_notes' => $this->request->getPost('applicant_notes'),
            ];

            // If status is being changed to approved/rejected, set reviewed date and reviewer
            if ($admissionData['status'] !== $existing['status'] && in_array($admissionData['status'], ['approved', 'rejected'])) {
                $admissionData['reviewed_date'] = date('Y-m-d');
                $admissionData['reviewed_by'] = auth()->user()->id ?? null;
            }

            if (!$this->admissionModel->update($id, $admissionData)) {
                throw new \Exception('Failed to update admission: ' . json_encode($this->admissionModel->errors()));
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/admission/view/' . $id)
                ->with('success', 'Admission and profile updated successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update admission: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete admission
     * 
     * @param int $id Admission ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $this->admissionModel->delete($id);

        return redirect()->to('/admission')
            ->with('success', 'Admission deleted successfully.');
    }

    /**
     * Search admissions
     * 
     * @return string
     */
    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        $data['admissions'] = $this->admissionModel->searchAdmissions($keyword);
        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        $data['keyword'] = $keyword;
        $data['totalAdmissions'] = count($data['admissions']);
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();

        return view('Modules\Admission\Views\index', $data);
    }

    /**
     * Update admission status via AJAX
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $json = $this->request->getJSON();
        $admissionId = $json->admission_id ?? null;
        $newStatus = $json->status ?? null;
        $notes = $json->notes ?? '';

        if (!$admissionId || !$newStatus) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
        }

        // Get existing admission
        $existing = $this->admissionModel->find($admissionId);
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'Admission not found']);
        }

        // Prepare update data
        $data = [
            'status' => $newStatus,
        ];

        // Append notes if provided
        if (!empty($notes)) {
            $existingNotes = $existing['notes'] ?? '';
            $timestamp = date('Y-m-d H:i:s');
            $userName = auth()->user()->username ?? 'System';
            $newNote = "\n[{$timestamp}] {$userName}: {$notes}";
            $data['notes'] = $existingNotes . $newNote;
        }

        // If status is being changed to approved/rejected, set reviewed date and reviewer
        if ($newStatus !== $existing['status'] && in_array($newStatus, ['approved', 'rejected'])) {
            $data['reviewed_date'] = date('Y-m-d');
            $data['reviewed_by'] = auth()->user()->id ?? null;
        }

        // Update admission
        if ($this->admissionModel->update($admissionId, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully to ' . ucfirst($newStatus)
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update status: ' . json_encode($this->admissionModel->errors())
            ]);
        }
    }

    /**
     * Download document file
     * 
     * @param int $id Admission ID
     * @param string $filename Document filename (relative path)
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function downloadDocument($id, $filename)
    {
        // Get admission with profile data
        $admission = $this->admissionModel->getWithDetails($id);

        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Documents are stored in profiles table
        $documents = [];
        if (!empty($admission['documents'])) {
            if (is_string($admission['documents'])) {
                $documents = json_decode($admission['documents'], true) ?? [];
            } elseif (is_array($admission['documents'])) {
                $documents = $admission['documents'];
            }
        }

        // Check if filename is in the documents array
        if (!in_array($filename, $documents)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found in profile');
        }

        // Build file path - filename already includes the relative path (e.g., profiles/documents/xxx.pdf)
        $filepath = FCPATH . 'uploads/' . $filename;

        if (!file_exists($filepath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found on disk: ' . $filename);
        }
        return $this->response->download($filepath, null);
    }

    /**
     * Search students for AJAX (Select2)
     */
    public function ajaxSearch()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $keyword = $this->request->getGet('q');
        if (!$keyword) {
            return $this->response->setJSON(['results' => []]);
        }

        $results = $this->admissionModel->searchAdmissions($keyword);

        // Filter only approved or pending students for payment
        $filtered = array_filter($results, function ($item) {
            return in_array($item['status'], ['approved', 'pending']);
        });

        $formatted = array_map(function ($item) {
            $statusSuffix = $item['status'] === 'pending' ? ' [PENDING]' : '';
            return [
                'id' => $item['registration_number'],
                'text' => $item['full_name'] . ' (' . $item['registration_number'] . ')' . $statusSuffix
            ];
        }, array_values($filtered));

        return $this->response->setJSON(['results' => $formatted]);
    }

    /**
     * Display promotion form (Upgrade to Student)
     */
    public function promote($id)
    {
        $admission = $this->admissionModel->getWithDetails($id);
        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        if ($admission['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Admission must be approved before promotion.');
        }

        return view('Modules\Admission\Views\promote', [
            'title' => 'Promote to Student',
            'admission' => $admission,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Process promotion
     */
    public function processPromotion($id)
    {
        $admission = $this->admissionModel->getWithDetails($id);
        if (!$admission || $admission['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Invalid admission for promotion.');
        }

        // Validate that citizen_id and phone are available
        if (empty($admission['citizen_id'])) {
            return redirect()->back()->with('error', 'Citizen ID is required for account creation. Please update the profile first.');
        }
        if (empty($admission['phone'])) {
            return redirect()->back()->with('error', 'Phone number is required for account creation. Please update the profile first.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Create User using Shield's User entity
            // Auto-generate username and password
            $username = $admission['citizen_id'];
            $password = $admission['phone'];

            $userProvider = auth()->getProvider();

            $userEntity = new User([
                'username' => $username,
                'email'    => $admission['email'],
                'password' => $password,
            ]);

            if (!$userProvider->save($userEntity)) {
                throw new \Exception('Failed to create user account.');
            }

            $userId = $userProvider->getInsertID();
            $user = $userProvider->findById($userId);

            if (!$user) {
                throw new \Exception('Failed to retrieve created user.');
            }

            // Activate and add to student group
            $user->activate();
            $user->addGroup('student');

            if (!$userProvider->save($user)) {
                throw new \Exception('Failed to activate user and add group.');
            }

            // 2. Update Profile with User ID
            if (!$db->table('profiles')
                ->where('id', $admission['profile_id'])
                ->update(['user_id' => $userId])) {
                throw new \Exception('Failed to update profile: ' . json_encode($db->error()));
            }

            // 3. Create Student Record
            $studentNumber = 'STU-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            if (!$db->table('students')->insert([
                'student_number' => $studentNumber,
                'profile_id' => $admission['profile_id'],
                'admission_id' => $id,
                'enrollment_date' => date('Y-m-d'),
                'status' => 'active',
                'program_id' => $admission['program_id'],
                'batch' => date('Y'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null
            ])) {
                throw new \Exception('Failed to create student record: ' . json_encode($db->error()));
            }

            $db->transCommit();

            return redirect()->to('/student')->with('success', 'Student promoted successfully. Username: ' . $username . ', Password: ' . $password);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Promotion error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Promotion failed: ' . $e->getMessage());
        }
    }
}
