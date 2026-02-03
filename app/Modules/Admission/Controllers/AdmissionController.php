<?php

namespace Modules\Admission\Controllers;

use App\Controllers\BaseController;
use Modules\Admission\Models\AdmissionModel;

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
        $programModel = new \Modules\Program\Models\ProgramModel();
        $programs = $programModel->where('status', 'active')->findAll();
        
        return view('Modules\Admission\Views\create', [
            'title' => 'Create Admission',
            'programs' => $programs,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    
    /**
     * Store new admission with file uploads
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate registration number if not provided
        if (empty($data['registration_number'])) {
            $data['registration_number'] = $this->admissionModel->generateRegistrationNumber();
        }
        
        // Set default values
        $data['application_date'] = $data['application_date'] ?? date('Y-m-d');
        $data['status'] = $data['status'] ?? 'pending';
        
        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/admissions/photos', $photoName);
            $data['photo'] = $photoName;
        }
        
        // Handle documents upload
        $documents = $this->request->getFileMultiple('documents');
        $documentNames = [];
        
        if ($documents) {
            foreach ($documents as $doc) {
                if ($doc->isValid() && !$doc->hasMoved()) {
                    $docName = $doc->getRandomName();
                    $doc->move(FCPATH . 'uploads/admissions/documents', $docName);
                    $documentNames[] = $docName;
                }
            }
        }
        
        if (!empty($documentNames)) {
            $data['documents'] = json_encode($documentNames);
        }
        
        if (!$this->admissionModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->admissionModel->errors());
        }
        
        return redirect()->to('/admission')
            ->with('success', 'Admission created successfully.');
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
        $programModel = new \Modules\Program\Models\ProgramModel();
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
            $profileModel = new \Modules\Account\Models\ProfileModel();
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
                $photoPath = $photo->getRandomName();
                $photo->move(FCPATH . 'uploads/profiles/photos', $photoPath);
                $profileData['photo'] = 'profiles/photos/' . $photoPath;
                
                // Delete old photo if exists
                if (!empty($existing['photo'])) {
                    $oldPhotoPath = FCPATH . 'uploads/' . $existing['photo'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
            }
            
            // Handle documents upload
            $documents = $this->request->getFileMultiple('documents');
            $documentPaths = [];
            
            if ($documents) {
                foreach ($documents as $doc) {
                    if ($doc->isValid() && !$doc->hasMoved()) {
                        $docPath = $doc->getRandomName();
                        $doc->move(FCPATH . 'uploads/profiles/documents', $docPath);
                        $documentPaths[] = 'profiles/documents/' . $docPath;
                    }
                }
                
                if (!empty($documentPaths)) {
                    $profileData['documents'] = json_encode($documentPaths);
                }
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
            log_message('error', 'Admission update failed: ' . $e->getMessage());
            
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
}
