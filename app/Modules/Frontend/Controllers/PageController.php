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
            'selectedProgram' => null,
            'user' => auth()->user()
        ]);
    }

    /**
     * Display programs listing page with category tabs
     */
    public function programs(): string
    {
        $programModel = new \Modules\Program\Models\ProgramModel();

        // Get selected category and sub-category from query string
        $selectedCategory = $this->request->getGet('category');
        $selectedSubCategory = $this->request->getGet('sub_category');

        // Get all active programs
        $allPrograms = $programModel->where('status', 'active')
            ->orderBy('category', 'ASC')
            ->orderBy('sub_category', 'ASC')
            ->orderBy('title', 'ASC')
            ->findAll();

        // Group programs by category then by sub_category
        $programsByCategory = [];
        $categories = [];

        foreach ($allPrograms as $program) {
            $category = $program['category'] ?? 'Uncategorized';
            $subCategory = $program['sub_category'] ?? 'General';

            if (!isset($programsByCategory[$category])) {
                $programsByCategory[$category] = [
                    'sub_categories' => [],
                    'total_programs' => 0
                ];
                $categories[] = $category;
            }

            if (!isset($programsByCategory[$category]['sub_categories'][$subCategory])) {
                $programsByCategory[$category]['sub_categories'][$subCategory] = [];
            }

            $programsByCategory[$category]['sub_categories'][$subCategory][] = $program;
            $programsByCategory[$category]['total_programs']++;
        }

        // If no category selected, select the first one
        if (empty($selectedCategory) && !empty($categories)) {
            $selectedCategory = $categories[0];
        }

        // If no sub-category selected, select the first one of the selected category
        if (empty($selectedSubCategory) && !empty($selectedCategory) && !empty($programsByCategory[$selectedCategory]['sub_categories'])) {
            $subCategories = array_keys($programsByCategory[$selectedCategory]['sub_categories']);
            $selectedSubCategory = $subCategories[0];
        }

        return view('Modules\Frontend\Views\Programs\index', [
            'title' => 'Our Programs',
            'programsByCategory' => $programsByCategory,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'selectedSubCategory' => $selectedSubCategory,
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
            'selectedProgram' => $program,
            'user' => auth()->user()
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
            $program = null;
            $programModel = new \Modules\Program\Models\ProgramModel();

            if ($programId) {
                $program = $programModel->find($programId);
            } else {
                // Fallback: Look up program by ID from 'course' field (which now contains ID)
                $courseValue = $this->request->getPost('course');

                if ($courseValue) {
                    // Check if it's a UUID (program ID) or a title
                    if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $courseValue)) {
                        // It's a UUID
                        $programId = $courseValue;
                        $program = $programModel->find($programId);
                    } else {
                        // It's a title, look it up
                        $program = $programModel->where('title', $courseValue)->first();
                        if ($program) {
                            $programId = $program['id'];
                        }
                    }
                }
            }

            if (!$program) {
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

            // STEP 3: Auto-generate Invoice
            $invoiceId = null;
            $regFee = $program['registration_fee'] ?? 0;
            $tuitionFee = $program['tuition_fee'] ?? 0;
            $discount = $program['discount'] ?? 0;
            $finalTuition = $tuitionFee * (1 - $discount / 100);
            $totalAmount = $regFee + $finalTuition;

            log_message('error', '[Frontend Apply] Program: ' . $program['title'] . ' | RegFee: ' . $regFee . ' | TuitionFee: ' . $tuitionFee . ' | Discount: ' . $discount . ' | TotalAmount: ' . $totalAmount);

            if ($totalAmount > 0) {
                $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
                $invoiceData = [
                    'registration_number' => $admissionData['registration_number'],
                    'description' => 'Initial Fees: Registration and Tuition for ' . $program['title'],
                    'amount' => $totalAmount,
                    'due_date' => date('Y-m-d', strtotime('+3 days')), // Due in 3 days
                    'invoice_type' => 'tuition_fee',
                    'status' => 'outstanding'
                ];

                log_message('error', '[Frontend Apply] Creating invoice with data: ' . json_encode($invoiceData));

                $invoiceId = $invoiceModel->createInvoice($invoiceData);

                if (!$invoiceId) {
                    log_message('error', '[Frontend Apply] Invoice creation FAILED. Errors: ' . json_encode($invoiceModel->errors()));
                } else {
                    log_message('error', '[Frontend Apply] Invoice created successfully with ID: ' . $invoiceId);
                }
            } else {
                log_message('error', '[Frontend Apply] Skipping invoice creation - totalAmount is 0');
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Success - redirect to public invoice if it exists, otherwise success page
            if (isset($invoiceId) && $invoiceId) {
                return redirect()->to('invoice/public/' . $invoiceId)
                    ->with('success', 'Your application has been submitted successfully!')
                    ->with('registration_number', $admissionData['registration_number']);
            }

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
        $registrationNumber = session('registration_number');
        $admission = null;
        $invoices = [];

        if ($registrationNumber) {
            $admissionModel = new \Modules\Admission\Models\AdmissionModel();
            $admission = $admissionModel->getByRegistrationNumber($registrationNumber);

            $invoiceModel = new \Modules\Payment\Models\InvoiceModel();
            // Get all invoices for this registration, including paid ones
            $invoices = $invoiceModel->where('registration_number', $registrationNumber)
                ->where('deleted_at IS NULL', null, false)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        return view('Modules\Frontend\Views\apply_success', [
            'title' => 'Application Submitted',
            'registrationNumber' => $registrationNumber,
            'admission' => $admission,
            'invoices' => $invoices
        ]);
    }
}
