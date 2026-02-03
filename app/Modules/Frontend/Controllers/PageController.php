<?php

namespace Modules\Frontend\Controllers;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    protected $admissionModel;
    
    public function __construct()
    {
        // Will be loaded after Admission module is created
        // $this->admissionModel = new \Modules\Admission\Models\AdmissionModel();
    }
    
    public function home(): string
    {
        return view('Modules\Frontend\Views\home', [
            'title' => 'Home'
        ]);
    }
    
    public function about(): string
    {
        return view('Modules\Frontend\Views\about', [
            'title' => 'About Us'
        ]);
    }
    
    public function contact(): string
    {
        return view('Modules\Frontend\Views\contact', [
            'title' => 'Contact Us'
        ]);
    }
    
    public function apply(): string
    {
        // Fetch programs from API
        $programs = $this->fetchProgramsFromAPI();
        
        return view('Modules\Frontend\Views\apply', [
            'title' => 'Apply for Admission',
            'programs' => $programs,
            'selectedProgram' => null
        ]);
    }
    
    /**
     * Display programs listing page with category tabs
     */
    public function programs(): string
    {
        $programModel = new \Modules\Program\Models\ProgramModel();
        
        // Get selected category from query string
        $selectedCategory = $this->request->getGet('category');
        
        // Get all active programs
        $allPrograms = $programModel->where('status', 'active')
                                    ->orderBy('category', 'ASC')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();
        
        // Group programs by category
        $programsByCategory = [];
        $categories = [];
        
        foreach ($allPrograms as $program) {
            $category = $program['category'] ?? 'Uncategorized';
            
            if (!isset($programsByCategory[$category])) {
                $programsByCategory[$category] = [];
                $categories[] = $category;
            }
            
            $programsByCategory[$category][] = $program;
        }
        
        // If no category selected, select the first one
        if (empty($selectedCategory) && !empty($categories)) {
            $selectedCategory = $categories[0];
        }
        
        return view('Modules\Frontend\Views\Programs\index', [
            'title' => 'Our Programs',
            'programsByCategory' => $programsByCategory,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'totalPrograms' => count($allPrograms)
        ]);
    }
    
    /**
     * Display program detail page
     */
    public function programDetail($id)
    {
        // Fetch single program from API
        $program = $this->fetchProgramFromAPI($id);
        
        if (!$program || $program['status'] !== 'active') {
            return redirect()->to('/programs')
                ->with('error', 'Program not found or not available.');
        }
        
        // Calculate final price with discount
        $finalPrice = $program['tuition_fee'] * (1 - $program['discount'] / 100);
        
        return view('Modules\Frontend\Views\Programs\detail', [
            'title' => $program['title'],
            'program' => $program,
            'finalPrice' => $finalPrice
        ]);
    }
    
    /**
     * Display apply form with pre-selected program
     */
    public function applyWithProgram($programId)
    {
        // Fetch single program from API
        $program = $this->fetchProgramFromAPI($programId);
        
        // Debug logging
        if (!$program) {
            log_message('error', 'Program not found with ID: ' . $programId);
            return redirect()->to('/programs')
                ->with('error', 'Program not found.');
        }
        
        if (!isset($program['status'])) {
            log_message('error', 'Program status not set for ID: ' . $programId);
            return redirect()->to('/programs')
                ->with('error', 'Program status is not configured.');
        }
        
        if ($program['status'] !== 'active') {
            log_message('error', 'Program is not active. ID: ' . $programId . ', Status: ' . $program['status']);
            return redirect()->to('/programs')
                ->with('error', 'This program is currently not available for enrollment.');
        }
        
        // Fetch all programs for dropdown
        $programs = $this->fetchProgramsFromAPI();
        
        return view('Modules\Frontend\Views\apply', [
            'title' => 'Apply for ' . $program['title'],
            'programs' => $programs,
            'selectedProgram' => $program
        ]);
    }
    
    /**
     * Fetch programs from API (optimized - direct model access)
     */
    private function fetchProgramsFromAPI(): array
    {
        try {
            // Direct model access instead of HTTP call for better performance
            $programModel = new \Modules\Program\Models\ProgramModel();
            return $programModel->getActivePrograms();
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch programs: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Fetch single program from API (optimized - direct model access)
     */
    private function fetchProgramFromAPI($id): ?array
    {
        try {
            // Direct model access instead of HTTP call for better performance
            $programModel = new \Modules\Program\Models\ProgramModel();
            $program = $programModel->find($id);
            return $program ?: null;
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch program: ' . $e->getMessage());
            return null;
        }
    }
    
    public function submitApplication()
    {
        // Load models
        $profileModel = new \Modules\Account\Models\ProfileModel();
        $admissionModel = new \Modules\Admission\Models\AdmissionModel();
        
        // Validate input
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'gender' => 'required|in_list[Male,Female]',
            'place_of_birth' => 'required|min_length[3]|max_length[100]',
            'date_of_birth' => 'required|valid_date',
            'religion' => 'required|min_length[3]|max_length[50]',
            'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
            'email' => 'required|valid_email|is_unique[profiles.email]',
            'street_address' => 'required|min_length[5]',
            'district' => 'required|min_length[3]',
            'regency' => 'required|min_length[3]',
            'province' => 'required|min_length[3]',
            'emergency_contact_name' => 'required|min_length[3]|max_length[100]',
            'emergency_contact_phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
            'emergency_contact_relation' => 'required|min_length[3]|max_length[50]',
            'father_name' => 'required|min_length[3]|max_length[100]',
            'mother_name' => 'required|min_length[3]|max_length[100]',
            'course' => 'permit_empty|min_length[3]', // Optional - for backward compatibility
            'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
            'documents.*' => 'max_size[documents,5120]|ext_in[documents,pdf,doc,docx]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Handle photo upload
            $photo = $this->request->getFile('photo');
            $photoPath = null;
            
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                $photoPath = $photo->getRandomName();
                $photo->move(FCPATH . 'uploads/profiles/photos', $photoPath);
                $photoPath = 'profiles/photos/' . $photoPath;
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
            }
            
            // STEP 1: Create Profile
            $profileData = [
                'profile_number' => $profileModel->generateProfileNumber(),
                'user_id' => null, // No user account yet
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
                'photo' => $photoPath,
                'documents' => !empty($documentPaths) ? json_encode($documentPaths) : null,
            ];
            
            $profileId = $profileModel->insert($profileData);
            
            if (!$profileId) {
                throw new \Exception('Failed to create profile');
            }
            
            // STEP 2: Create Admission
            // Get program_id from form (either directly or by looking up course name)
            $programId = $this->request->getPost('program_id');
            
            if (!$programId) {
                // Fallback: Look up program by ID from 'course' field (which now contains ID)
                $courseValue = $this->request->getPost('course');
                
                if ($courseValue) {
                    // Check if it's a UUID (program ID) or a title
                    if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $courseValue)) {
                        // It's a UUID
                        $programId = $courseValue;
                    } else {
                        // It's a title, look it up
                        $programModel = new \Modules\Program\Models\ProgramModel();
                        $program = $programModel->where('title', $courseValue)->first();
                        
                        if (!$program) {
                            throw new \Exception('Program not found');
                        }
                        
                        $programId = $program['id'];
                    }
                }
            }
            
            if (!$programId) {
                throw new \Exception('Program selection is required');
            }
            
            $admissionData = [
                'registration_number' => $admissionModel->generateRegistrationNumber(),
                'profile_id' => $profileId,
                'program_id' => $programId,
                'status' => 'pending',
                'application_date' => date('Y-m-d'),
                'applicant_notes' => $this->request->getPost('notes'),
            ];
            
            $admissionId = $admissionModel->insert($admissionData);
            
            if (!$admissionId) {
                throw new \Exception('Failed to create admission');
            }
            
            // Commit transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            // Success - redirect with registration number
            return redirect()->to('/apply/success')
                ->with('success', 'Your application has been submitted successfully!')
                ->with('registration_number', $admissionData['registration_number']);
                
        } catch (\Exception $e) {
            // Rollback transaction
            $db->transRollback();
            log_message('error', 'Application submission failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit application. Please try again.');
        }
    }
    
    public function applySuccess(): string
    {
        return view('Modules\Frontend\Views\apply_success', [
            'title' => 'Application Submitted'
        ]);
    }
}
