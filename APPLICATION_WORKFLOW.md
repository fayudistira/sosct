# Application Workflow - Prospect to Student

## 🎯 Complete Logical Flow

This document explains the complete journey from when a prospect sees a program to becoming an enrolled student.

---

## 📊 Visual Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    PROSPECT JOURNEY                              │
└─────────────────────────────────────────────────────────────────┘

1. DISCOVERY PHASE
   ┌──────────────┐
   │   Prospect   │ → Visits website
   └──────┬───────┘
          ↓
   ┌──────────────┐
   │   Programs   │ → Browses program catalog
   │     Page     │   - Filters by category
   └──────┬───────┘   - Searches programs
          ↓           - Views program cards
   ┌──────────────┐
   │   Program    │ → Views program details
   │    Detail    │   - Description, features
   └──────┬───────┘   - Fees, duration
          ↓           - Facilities
   [Clicks "Apply"]
          ↓

2. APPLICATION PHASE
   ┌──────────────┐
   │ Application  │ → Fills application form
   │     Form     │   - Personal information
   └──────┬───────┘   - Contact details
          ↓           - Address
          │           - Emergency contact
          │           - Parents info
          │           - Upload photo
          │           - Upload documents
          │           - Motivation notes
          ↓
   [Submit Application]
          ↓
   ┌──────────────────────────────────────┐
   │  BACKEND PROCESSING                  │
   │                                      │
   │  1. Create Profile                   │
   │     - Generate profile_number        │
   │     - Save personal data             │
   │     - Save files                     │
   │                                      │
   │  2. Create Admission                 │
   │     - Link profile_id                │
   │     - Link program_id                │
   │     - Generate registration_number   │
   │     - Set status = 'pending'         │
   │     - Save application_date          │
   │     - Save applicant_notes           │
   └──────────────┬───────────────────────┘
          ↓
   ┌──────────────┐
   │   Success    │ → Shows confirmation
   │     Page     │   - Registration number
   └──────────────┘   - Next steps
          ↓
   [Email Confirmation Sent]

3. REVIEW PHASE (Admin Side)
   ┌──────────────┐
   │    Admin     │ → Logs into dashboard
   └──────┬───────┘
          ↓
   ┌──────────────┐
   │  Admissions  │ → Views pending applications
   │     List     │   - Filters by status
   └──────┬───────┘   - Searches by name/reg number
          ↓
   ┌──────────────┐
   │  Admission   │ → Reviews application
   │    Detail    │   - Views profile data
   └──────┬───────┘   - Views program applied
          ↓           - Views documents
          │           - Reads motivation
   [Decision Time]
          ↓
    ┌────┴────┐
    │         │
 Approve   Reject
    │         │
    ↓         ↓
   ┌──────────────────────────────────────┐
   │  UPDATE ADMISSION                    │
   │  - Set status = 'approved'/'rejected'│
   │  - Set reviewed_date = today         │
   │  - Set reviewed_by = admin_user_id   │
   │  - Add notes (optional)              │
   └──────────────┬───────────────────────┘
          ↓
   [Email Notification Sent to Applicant]

4. ENROLLMENT PHASE (If Approved)
   ┌──────────────┐
   │    Admin     │ → Opens approved admission
   └──────┬───────┘
          ↓
   [Clicks "Create Student Record"]
          ↓
   ┌──────────────────────────────────────┐
   │  CREATE STUDENT                      │
   │  - Generate student_number           │
   │  - Link profile_id                   │
   │  - Link admission_id                 │
   │  - Link program_id                   │
   │  - Set enrollment_date = today       │
   │  - Set status = 'active'             │
   │  - Set batch (e.g., "2026-A")        │
   └──────────────┬───────────────────────┘
          ↓
   ┌──────────────┐
   │   Student    │ → Student record created
   │   Created    │   - Has student_number
   └──────┬───────┘   - Linked to profile
          ↓           - Linked to admission
          │           - Linked to program
          ↓
   [Student can now be enrolled in classes]

5. CLASS ENROLLMENT PHASE
   ┌──────────────┐
   │    Admin     │ → Opens class management
   └──────┬───────┘
          ↓
   ┌──────────────┐
   │    Class     │ → Views class details
   │    Detail    │
   └──────┬───────┘
          ↓
   [Clicks "Assign Students"]
          ↓
   ┌──────────────┐
   │   Student    │ → Selects students to enroll
   │  Assignment  │   - Shows active students
   └──────┬───────┘   - Checks capacity
          ↓           - Prevents duplicates
   [Clicks "Enroll"]
          ↓
   ┌──────────────────────────────────────┐
   │  CREATE CLASS_MEMBER                 │
   │  - Link class_id                     │
   │  - Link student_id                   │
   │  - Set enrollment_date = today       │
   │  - Set status = 'active'             │
   └──────────────┬───────────────────────┘
          ↓
   ┌──────────────┐
   │   Student    │ → Student enrolled in class
   │   Enrolled   │   - Can attend classes
   └──────────────┘   - Tracked in system
```

---

## 🔍 Detailed Step-by-Step Flow

### Step 1: Discovery Phase

#### 1.1 Prospect Visits Programs Page
**URL**: `/programs`

**What Happens**:
```php
// Frontend/Controllers/PageController.php
public function programs()
{
    $programModel = new ProgramModel();
    
    // Get all active programs
    $programs = $programModel->where('status', 'active')
                             ->where('deleted_at', null)
                             ->findAll();
    
    // Group by category for tabs
    $categories = array_unique(array_column($programs, 'category'));
    
    return view('Frontend/Views/Programs/index', [
        'programs' => $programs,
        'categories' => $categories
    ]);
}
```

**User Sees**:
- Program cards with thumbnails
- Category tabs (Undergraduate, Graduate, etc.)
- Search bar
- Filter options
- Each card shows:
  - Program title
  - Category
  - Duration
  - Fees
  - "View Details" button

---

#### 1.2 Prospect Clicks Program Card
**URL**: `/programs/detail/{program_id}`

**What Happens**:
```php
// Frontend/Controllers/PageController.php
public function programDetail($id)
{
    $programModel = new ProgramModel();
    
    // Get program details
    $program = $programModel->find($id);
    
    if (!$program) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
    
    return view('Frontend/Views/Programs/detail', [
        'program' => $program
    ]);
}
```

**User Sees**:
- Full program description
- Features list
- Facilities list
- Fees breakdown
- Duration
- Mode & Curriculum
- **"Apply Now" button** ← Important!

---

### Step 2: Application Phase

#### 2.1 Prospect Clicks "Apply Now"
**URL**: `/apply?program={program_id}`

**What Happens**:
```php
// Frontend/Controllers/PageController.php
public function apply()
{
    $programId = $this->request->getGet('program');
    
    $programModel = new ProgramModel();
    $program = $programModel->find($programId);
    
    return view('Frontend/Views/apply', [
        'program' => $program
    ]);
}
```

**User Sees**:
- Multi-step application form
- Program name at top (what they're applying to)
- Form sections:
  1. Personal Information
  2. Contact Information
  3. Address
  4. Emergency Contact
  5. Parents Information
  6. Documents Upload
  7. Motivation/Notes

---

#### 2.2 Prospect Fills and Submits Form
**URL**: `POST /apply`

**What Happens** (NEW FLOW):
```php
// Frontend/Controllers/PageController.php
public function submitApplication()
{
    $validation = $this->validate([
        'full_name' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[profiles.email]',
        'phone' => 'required',
        'program_id' => 'required',
        // ... other validations
    ]);
    
    if (!$validation) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    $db = \Config\Database::connect();
    $db->transStart(); // Start transaction
    
    try {
        // STEP 1: Create Profile
        $profileModel = new ProfileModel();
        
        // Generate profile number
        $profileNumber = $profileModel->generateProfileNumber();
        
        // Handle file uploads
        $photoPath = $this->handlePhotoUpload();
        $documents = $this->handleDocumentUploads();
        
        // Create profile
        $profileData = [
            'profile_number' => $profileNumber,
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
            'documents' => json_encode($documents),
        ];
        
        $profileId = $profileModel->insert($profileData);
        
        // STEP 2: Create Admission
        $admissionModel = new AdmissionModel();
        
        // Generate registration number
        $registrationNumber = $admissionModel->generateRegistrationNumber();
        
        $admissionData = [
            'registration_number' => $registrationNumber,
            'profile_id' => $profileId,
            'program_id' => $this->request->getPost('program_id'),
            'status' => 'pending',
            'application_date' => date('Y-m-d'),
            'applicant_notes' => $this->request->getPost('motivation'),
            'notes' => null, // Admin notes (empty for now)
        ];
        
        $admissionId = $admissionModel->insert($admissionData);
        
        $db->transComplete(); // Commit transaction
        
        if ($db->transStatus() === false) {
            throw new \Exception('Failed to create application');
        }
        
        // Send confirmation email (optional)
        // $this->sendConfirmationEmail($profileData['email'], $registrationNumber);
        
        // Redirect to success page
        return redirect()->to('/apply/success')
                        ->with('registration_number', $registrationNumber)
                        ->with('success', 'Application submitted successfully!');
        
    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', 'Application submission failed: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Failed to submit application. Please try again.');
    }
}
```

**Database State After Submission**:
```sql
-- profiles table
INSERT INTO profiles (
    profile_number, email, full_name, phone, ...
) VALUES (
    'PROF-2026-0001', 'john@example.com', 'John Doe', '08123456789', ...
);
-- Returns profile_id = 1

-- admissions table
INSERT INTO admissions (
    registration_number, profile_id, program_id, status, application_date
) VALUES (
    'REG-2026-0001', 1, 'uuid-of-program', 'pending', '2026-02-03'
);
-- Returns admission_id = 1
```

**User Sees**:
- Success page with:
  - Registration number (REG-2026-0001)
  - Confirmation message
  - Next steps instructions
  - Contact information

---

### Step 3: Review Phase (Admin Side)

#### 3.1 Admin Views Pending Applications
**URL**: `/admission/admissions`

**What Happens**:
```php
// Admission/Controllers/AdmissionController.php
public function index()
{
    $admissionModel = new AdmissionModel();
    
    // Get admissions with profile and program data
    $admissions = $admissionModel
        ->select('admissions.*, profiles.full_name, profiles.email, profiles.phone, programs.title as program_name')
        ->join('profiles', 'profiles.id = admissions.profile_id')
        ->join('programs', 'programs.id = admissions.program_id')
        ->where('admissions.deleted_at', null)
        ->orderBy('admissions.created_at', 'DESC')
        ->findAll();
    
    return view('Admission/Views/index', [
        'admissions' => $admissions
    ]);
}
```

**Admin Sees**:
- Table with columns:
  - Registration Number
  - Full Name (from profiles)
  - Email (from profiles)
  - Program (from programs)
  - Status (pending/approved/rejected)
  - Application Date
  - Actions (View, Approve, Reject)

---

#### 3.2 Admin Views Application Detail
**URL**: `/admission/admissions/view/{admission_id}`

**What Happens**:
```php
// Admission/Controllers/AdmissionController.php
public function view($id)
{
    $admissionModel = new AdmissionModel();
    
    // Get admission with related data
    $admission = $admissionModel
        ->select('admissions.*, profiles.*, programs.title as program_name, programs.tuition_fee')
        ->join('profiles', 'profiles.id = admissions.profile_id')
        ->join('programs', 'programs.id = admissions.program_id')
        ->where('admissions.id', $id)
        ->first();
    
    if (!$admission) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
    
    return view('Admission/Views/view', [
        'admission' => $admission
    ]);
}
```

**Admin Sees**:
- Complete profile information
- Program applied to
- Application date
- Applicant's motivation notes
- Uploaded documents (photo, certificates)
- Status badges
- Action buttons:
  - **Approve** (if pending)
  - **Reject** (if pending)
  - **Create Student** (if approved)

---

#### 3.3 Admin Approves Application
**URL**: `POST /admission/admissions/approve/{admission_id}`

**What Happens**:
```php
// Admission/Controllers/AdmissionController.php
public function approve($id)
{
    $admissionModel = new AdmissionModel();
    
    $data = [
        'status' => 'approved',
        'reviewed_date' => date('Y-m-d'),
        'reviewed_by' => auth()->user()->id,
        'notes' => $this->request->getPost('notes'), // Optional admin notes
    ];
    
    if ($admissionModel->update($id, $data)) {
        // Send approval email (optional)
        // $this->sendApprovalEmail($admission);
        
        return redirect()->to('/admission/admissions/view/' . $id)
                        ->with('success', 'Application approved successfully!');
    }
    
    return redirect()->back()->with('error', 'Failed to approve application.');
}
```

**Database State After Approval**:
```sql
UPDATE admissions 
SET status = 'approved',
    reviewed_date = '2026-02-03',
    reviewed_by = 1,
    notes = 'Approved - meets requirements'
WHERE id = 1;
```

---

### Step 4: Enrollment Phase

#### 4.1 Admin Creates Student Record
**URL**: `POST /admission/admissions/create-student/{admission_id}`

**What Happens**:
```php
// Admission/Controllers/AdmissionController.php
public function createStudent($admissionId)
{
    $admissionModel = new AdmissionModel();
    $studentModel = new StudentModel();
    
    // Get admission with profile
    $admission = $admissionModel
        ->select('admissions.*, profiles.id as profile_id')
        ->join('profiles', 'profiles.id = admissions.profile_id')
        ->where('admissions.id', $admissionId)
        ->first();
    
    // Validate admission is approved
    if ($admission['status'] !== 'approved') {
        return redirect()->back()->with('error', 'Only approved applications can be enrolled.');
    }
    
    // Check if student already exists
    if ($studentModel->where('profile_id', $admission['profile_id'])->first()) {
        return redirect()->back()->with('error', 'Student record already exists for this profile.');
    }
    
    // Generate student number
    $studentNumber = $studentModel->generateStudentNumber();
    
    // Create student record
    $studentData = [
        'student_number' => $studentNumber,
        'profile_id' => $admission['profile_id'],
        'admission_id' => $admissionId,
        'enrollment_date' => date('Y-m-d'),
        'status' => 'active',
        'program_id' => $admission['program_id'],
        'batch' => $this->request->getPost('batch') ?? date('Y') . '-A',
        'gpa' => 0.00,
        'total_credits' => 0,
    ];
    
    if ($studentModel->insert($studentData)) {
        // Send enrollment email (optional)
        // $this->sendEnrollmentEmail($admission, $studentNumber);
        
        return redirect()->to('/admission/admissions/view/' . $admissionId)
                        ->with('success', 'Student record created successfully! Student Number: ' . $studentNumber);
    }
    
    return redirect()->back()->with('error', 'Failed to create student record.');
}
```

**Database State After Student Creation**:
```sql
INSERT INTO students (
    student_number, profile_id, admission_id, enrollment_date, 
    status, program_id, batch, gpa, total_credits
) VALUES (
    'STU-2026-0001', 1, 1, '2026-02-03',
    'active', 'uuid-of-program', '2026-A', 0.00, 0
);
-- Returns student_id = 1
```

**Now the data relationships are**:
```
profile (id=1) ← admission (id=1) ← student (id=1)
     ↓                ↓                    ↓
Personal Data    Application         Student Role
```

---

### Step 5: Class Enrollment Phase

#### 5.1 Admin Assigns Student to Class
**URL**: `/academic/classes/assign/{class_id}`

**What Happens**:
```php
// Academic/Controllers/ClassController.php
public function assignStudents($classId)
{
    $studentModel = new StudentModel();
    $classMemberModel = new ClassMemberModel();
    
    // Get active students not already in this class
    $students = $studentModel
        ->select('students.*, profiles.full_name, profiles.email, programs.title as program_name')
        ->join('profiles', 'profiles.id = students.profile_id')
        ->join('programs', 'programs.id = students.program_id', 'left')
        ->where('students.status', 'active')
        ->whereNotIn('students.id', function($builder) use ($classId) {
            return $builder->select('student_id')
                          ->from('class_members')
                          ->where('class_id', $classId)
                          ->where('deleted_at', null);
        })
        ->findAll();
    
    return view('Academic/Views/classes/assign_students', [
        'class_id' => $classId,
        'students' => $students
    ]);
}

public function enrollStudents($classId)
{
    $studentIds = $this->request->getPost('student_ids'); // Array of student IDs
    $classMemberModel = new ClassMemberModel();
    
    $enrolled = 0;
    $errors = [];
    
    foreach ($studentIds as $studentId) {
        // Check if already enrolled
        if ($classMemberModel->isEnrolled($classId, $studentId)) {
            $errors[] = "Student ID {$studentId} is already enrolled";
            continue;
        }
        
        // Create class member
        $data = [
            'class_id' => $classId,
            'student_id' => $studentId,
            'enrollment_date' => date('Y-m-d'),
            'status' => 'active',
        ];
        
        if ($classMemberModel->insert($data)) {
            $enrolled++;
        } else {
            $errors[] = "Failed to enroll student ID {$studentId}";
        }
    }
    
    $message = "{$enrolled} student(s) enrolled successfully.";
    if (!empty($errors)) {
        $message .= " Errors: " . implode(', ', $errors);
    }
    
    return redirect()->to('/academic/classes/view/' . $classId)
                    ->with('success', $message);
}
```

**Database State After Class Enrollment**:
```sql
INSERT INTO class_members (
    class_id, student_id, enrollment_date, status
) VALUES (
    'uuid-of-class', 1, '2026-02-03', 'active'
);
```

---

## 📊 Complete Data Relationships

After the entire flow, here's how the data is connected:

```
┌─────────────┐
│  profiles   │ PROF-2026-0001
│   (id=1)    │ John Doe
└──────┬──────┘ john@example.com
       │
       ├─────────────────┐
       │                 │
       ↓                 ↓
┌─────────────┐   ┌─────────────┐
│ admissions  │   │  students   │
│   (id=1)    │   │   (id=1)    │
│ REG-2026-001│   │ STU-2026-001│
│ status:     │   │ status:     │
│ approved    │   │ active      │
└──────┬──────┘   └──────┬──────┘
       │                 │
       ↓                 ↓
┌─────────────┐   ┌─────────────┐
│  programs   │   │class_members│
│ Web Dev     │   │   (id=1)    │
│ Program     │   │ status:     │
└─────────────┘   │ active      │
                  └──────┬──────┘
                         ↓
                  ┌─────────────┐
                  │   classes   │
                  │ Web Dev 101 │
                  └─────────────┘
```

---

## 🎯 Summary

1. **Prospect** browses programs → clicks program → clicks "Apply Now"
2. **System** creates **profile** (personal data) + **admission** (application record)
3. **Admin** reviews admission → approves
4. **Admin** creates **student** record from approved admission
5. **Admin** assigns student to **classes** via class_members

**Key Points**:
- Profile created ONCE, reused for everything
- Admission tracks application history
- Student is a role assignment
- One person can have multiple admissions (different programs)
- One student can be in multiple classes

---

**Document Version**: 1.0  
**Date**: 2026-02-03  
**Status**: ✅ Complete Workflow Documentation
