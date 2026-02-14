<?php

namespace Modules\Frontend\Controllers;

use App\Controllers\BaseController;
use Modules\Account\Models\ProfileModel;
use Modules\Admission\Models\AdmissionModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;

class PageController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Display homepage
     */
    public function index(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')->orderBy('created_at', 'DESC')->findAll();

        return view('Modules\Frontend\Views\home', [
            'title' => 'Welcome',
            'programs' => $programs
        ]);
    }

    /**
     * Display contact page
     */
    public function contact(): string
    {
        return view('Modules\Frontend\Views\contact', [
            'title' => 'Contact Us'
        ]);
    }

    /**
     * Display about page
     */
    public function about(): string
    {
        return view('Modules\Frontend\Views\about', [
            'title' => 'About Us'
        ]);
    }

    /**
     * Handle contact form submission
     */
    public function submitContact()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[3]',
            'message' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Here you could send email, save to database, etc.
        // For now, just show success message
        return redirect()->to('/contact')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Display apply form
     */
    public function apply(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')->findAll();

        return view('Modules\Frontend\Views\apply', [
            'title' => 'Apply for Admission',
            'programs' => $programs,
            'user' => auth()->user()
        ]);
    }

    /**
     * Display apply form with pre-selected program
     */
    public function applyWithProgram($programId)
    {
        $programModel = new ProgramModel();
        $program = $programModel->find($programId);

        if (!$program) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Program not found');
        }

        $programs = $programModel->where('status', 'active')->findAll();

        return view('Modules\Frontend\Views\apply', [
            'title' => 'Apply for ' . $program['title'],
            'programs' => $programs,
            'selectedProgram' => $program,
            'user' => auth()->user()
        ]);
    }

    /**
     * Handle application form submission
     */
    public function submitApplication()
    {
        log_message('error', '[Frontend Apply] Starting application submission');

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
            'documents.*' => 'max_size[documents,5120]|ext_in[documents,pdf,doc,docx,jpg,jpeg,png,gif]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', '[Frontend Apply] Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        log_message('error', '[Frontend Apply] Validation passed');

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
            log_message('error', '[Frontend Apply] Profile created with ID: ' . $profileId);

            if (!$profileId) {
                throw new \Exception('Failed to create profile: ' . json_encode($profileModel->errors()));
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

            log_message('error', '[Frontend Apply] Selected program: ' . $program['title']);

            $admissionData = [
                'registration_number' => $admissionModel->generateRegistrationNumber(),
                'profile_id' => $profileId,
                'program_id' => $programId,
                'status' => 'pending',
                'application_date' => date('Y-m-d'),
                'applicant_notes' => $this->request->getPost('notes'),
            ];

            $admissionId = $admissionModel->insert($admissionData);
            log_message('error', '[Frontend Apply] Admission created with ID: ' . $admissionId);

            if (!$admissionId) {
                throw new \Exception('Failed to create admission: ' . json_encode($admissionModel->errors()));
            }

            // STEP 3: Create Installment Record
            $installmentModel = new \Modules\Payment\Models\InstallmentModel();
            $dueDate = date('Y-m-d', strtotime('+2 weeks')); // 2 weeks to pay

            $regFee = $program['registration_fee'] ?? 0;
            $tuitionFee = $program['tuition_fee'] ?? 0;
            $discount = $program['discount'] ?? 0;
            $finalTuition = $tuitionFee * (1 - $discount / 100);
            $totalAmount = $regFee + $finalTuition;

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
            log_message('error', '[Frontend Apply] Installment created with ID: ' . $installmentId);

            // STEP 4: Auto-generate Invoice
            $invoiceId = null;

            log_message('error', '[Frontend Apply] Program: ' . $program['title'] . ' | RegFee: ' . $regFee . ' | TuitionFee: ' . $tuitionFee . ' | Discount: ' . $discount . ' | TotalAmount: ' . $totalAmount);

            if ($totalAmount > 0) {
                $invoiceModel = new \Modules\Payment\Models\InvoiceModel();

                // Create items array with 2 entries (Registration Fee + Course Fee)
                $items = [
                    [
                        'description' => 'Registration Fee for ' . $program['title'],
                        'amount' => (float)$regFee,
                        'type' => 'registration_fee'
                    ],
                    [
                        'description' => 'Course Fee for ' . $program['title'],
                        'amount' => (float)$finalTuition,
                        'type' => 'tuition_fee'
                    ]
                ];

                $invoiceData = [
                    'registration_number' => $admissionData['registration_number'],
                    'contract_number' => $admissionData['registration_number'],
                    'installment_id' => $installmentId,
                    'description' => 'Initial Fees: Registration and Tuition for ' . $program['title'],
                    'amount' => $totalAmount,
                    'due_date' => $dueDate, // Use installment due date
                    'invoice_type' => 'tuition_fee',
                    'status' => 'unpaid',
                    'items' => json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ];

                log_message('error', '[Frontend Apply] Invoice Data: ' . json_encode($invoiceData, JSON_UNESCAPED_UNICODE));
                $invoiceId = $invoiceModel->createInvoice($invoiceData);
                log_message('error', '[Frontend Apply] Invoice created with ID: ' . $invoiceId);

                if (!$invoiceId) {
                    log_message('error', '[Frontend Apply] Invoice creation FAILED: ' . json_encode($invoiceModel->errors()));
                }
            } else {
                log_message('error', '[Frontend Apply] Skipping invoice creation - totalAmount is 0');
            }

            // Commit transaction
            $db->transComplete();

            log_message('error', '[Frontend Apply] Transaction status: ' . ($db->transStatus() ? 'SUCCESS' : 'FAILED'));

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed - database error');
            }

            // Create notification for admins about new admission
            try {
                $notificationService = new \App\Services\NotificationService();
                $notificationService->notifyNewAdmission([
                    'registration_number' => $admissionData['registration_number'],
                    'admission_id' => $admissionId,
                    'program_title' => $program['title'],
                    'applicant_name' => $this->request->getPost('full_name'),
                ]);
                log_message('error', '[Frontend Apply] Notification sent to admins');
            } catch (\Throwable $notifEx) {
                log_message('error', '[Frontend Apply] Notification failed: ' . $notifEx->getMessage());
                // Continue even if notification fails
            }

            // Send confirmation email with invoice link using EmailService
            log_message('error', '[Frontend Apply] Preparing to send email to: ' . $this->request->getPost('email'));

            try {
                $emailService = new \App\Services\EmailService();

                $invoiceData = [
                    'amount' => $totalAmount,
                    'due_date' => date('Y-m-d', strtotime('+3 days')),
                    'description' => $program['title'] ?? 'Program Registration'
                ];

                $emailAdmissionData = [
                    'registration_number' => $admissionData['registration_number'],
                    'program_title' => $program['title'] ?? 'N/A'
                ];

                $emailSent = $emailService->sendInvoiceNotification(
                    $invoiceData,
                    $this->request->getPost('email'),
                    $this->request->getPost('full_name'),
                    $emailAdmissionData,
                    $invoiceId
                );

                log_message('error', '[Frontend Apply] Email send result: ' . ($emailSent ? 'SUCCESS' : 'FAILED'));
            } catch (\Throwable $emailEx) {
                log_message('error', '[Frontend Apply] Email exception: ' . $emailEx->getMessage());
                // Continue even if email fails - don't let it break the flow
            }

            // Success - redirect to public invoice if it exists, otherwise success page
            if (!empty($invoiceId)) {
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
            log_message('error', '[Frontend Apply] Exception: ' . $e->getMessage());
            log_message('error', '[Frontend Apply] Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit application. Please try again.');
        }
    }

    /**
     * Display application success page
     */
    public function applySuccess(): string
    {
        $registrationNumber = session('registration_number');

        return view('Modules\Frontend\Views\apply_success', [
            'title' => 'Application Submitted',
            'registrationNumber' => $registrationNumber
        ]);
    }

    /**
     * Programs listing
     */
    public function programs(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')->orderBy('created_at', 'DESC')->findAll();

        // Build data structure expected by the view
        $programsByCategory = [];
        $categories = [];
        $totalPrograms = count($programs);

        foreach ($programs as $program) {
            $category = !empty($program['category']) ? $program['category'] : 'General';
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';

            // Add category to list if not exists
            if (!in_array($category, $categories)) {
                $categories[] = $category;
                $programsByCategory[$category] = [
                    'total_programs' => 0,
                    'sub_categories' => []
                ];
            }

            // Add sub-category to list if not exists
            if (!isset($programsByCategory[$category]['sub_categories'][$subCategory])) {
                $programsByCategory[$category]['sub_categories'][$subCategory] = [];
            }

            // Add program to sub-category
            $programsByCategory[$category]['sub_categories'][$subCategory][] = $program;
            $programsByCategory[$category]['total_programs']++;
        }

        // Sort categories and sub-categories
        sort($categories);
        foreach ($categories as $category) {
            ksort($programsByCategory[$category]['sub_categories']);
        }

        // Default selected category (first one or 'All')
        $selectedCategory = !empty($categories) ? $categories[0] : 'General';

        return view('Modules\Frontend\Views\Programs\index', [
            'title' => 'Our Programs',
            'programs' => $programs,
            'totalPrograms' => $totalPrograms,
            'categories' => $categories,
            'programsByCategory' => $programsByCategory,
            'selectedCategory' => $selectedCategory
        ]);
    }

    /**
     * Program details
     */
    public function programDetail($id): string
    {
        $programModel = new ProgramModel();
        $program = $programModel->find($id);

        if (!$program) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Program not found');
        }

        return view('Modules\Frontend\Views\Programs\detail', [
            'title' => $program['title'],
            'program' => $program
        ]);
    }

    /**
     * Mandarin Chinese landing page
     */
    public function mandarin(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')
            ->where('category', 'Mandarin')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Organize programs by subcategory
        $programsBySubCategory = [];
        $subCategories = [];
        foreach ($programs as $program) {
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';
            if (!in_array($subCategory, $subCategories)) {
                $subCategories[] = $subCategory;
            }
            if (!isset($programsBySubCategory[$subCategory])) {
                $programsBySubCategory[$subCategory] = [];
            }
            $programsBySubCategory[$subCategory][] = $program;
        }

        return view('Modules\Frontend\Views\landings\mandarin', [
            'title' => 'Kursus Bahasa Mandarin - SOS Course and Training',
            'programs' => $programs,
            'programsBySubCategory' => $programsBySubCategory,
            'subCategories' => $subCategories
        ]);
    }

    /**
     * Japanese landing page
     */
    public function japanese(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')
            ->where('category', 'Japanese')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Organize programs by subcategory
        $programsBySubCategory = [];
        $subCategories = [];
        foreach ($programs as $program) {
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';
            if (!in_array($subCategory, $subCategories)) {
                $subCategories[] = $subCategory;
            }
            if (!isset($programsBySubCategory[$subCategory])) {
                $programsBySubCategory[$subCategory] = [];
            }
            $programsBySubCategory[$subCategory][] = $program;
        }

        return view('Modules\Frontend\Views\landings\japanese', [
            'title' => 'Kursus Bahasa Jepang - SOS Course and Training',
            'programs' => $programs,
            'programsBySubCategory' => $programsBySubCategory,
            'subCategories' => $subCategories
        ]);
    }

    /**
     * Korean landing page
     */
    public function korean(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')
            ->where('category', 'Korean')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Organize programs by subcategory
        $programsBySubCategory = [];
        $subCategories = [];
        foreach ($programs as $program) {
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';
            if (!in_array($subCategory, $subCategories)) {
                $subCategories[] = $subCategory;
            }
            if (!isset($programsBySubCategory[$subCategory])) {
                $programsBySubCategory[$subCategory] = [];
            }
            $programsBySubCategory[$subCategory][] = $program;
        }

        return view('Modules\Frontend\Views\landings\korean', [
            'title' => 'Kursus Bahasa Korea - SOS Course and Training',
            'programs' => $programs,
            'programsBySubCategory' => $programsBySubCategory,
            'subCategories' => $subCategories
        ]);
    }

    /**
     * German landing page
     */
    public function german(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')
            ->where('category', 'German')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Organize programs by subcategory
        $programsBySubCategory = [];
        $subCategories = [];
        foreach ($programs as $program) {
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';
            if (!in_array($subCategory, $subCategories)) {
                $subCategories[] = $subCategory;
            }
            if (!isset($programsBySubCategory[$subCategory])) {
                $programsBySubCategory[$subCategory] = [];
            }
            $programsBySubCategory[$subCategory][] = $program;
        }

        return view('Modules\Frontend\Views\landings\german', [
            'title' => 'Kursus Bahasa Jerman - SOS Course and Training',
            'programs' => $programs,
            'programsBySubCategory' => $programsBySubCategory,
            'subCategories' => $subCategories
        ]);
    }

    /**
     * English landing page
     */
    public function english(): string
    {
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')
            ->where('category', 'English')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Organize programs by subcategory
        $programsBySubCategory = [];
        $subCategories = [];
        foreach ($programs as $program) {
            $subCategory = !empty($program['sub_category']) ? $program['sub_category'] : 'Standard';
            if (!in_array($subCategory, $subCategories)) {
                $subCategories[] = $subCategory;
            }
            if (!isset($programsBySubCategory[$subCategory])) {
                $programsBySubCategory[$subCategory] = [];
            }
            $programsBySubCategory[$subCategory][] = $program;
        }

        return view('Modules\Frontend\Views\landings\english', [
            'title' => 'Kursus Bahasa Inggris - SOS Course and Training',
            'programs' => $programs,
            'programsBySubCategory' => $programsBySubCategory,
            'subCategories' => $subCategories
        ]);
    }
}
