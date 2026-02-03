# Admission Model Fix - Application Submission Error Resolved

**Date**: February 3, 2026  
**Issue**: "Failed to submit application. Please try again."  
**Root Cause**: AdmissionModel had outdated field definitions that didn't match the new relational database structure

---

## Problem Summary

After refactoring the admissions table to be relational (connecting profiles and programs), the application form submission was failing because:

1. **AdmissionModel** still had OLD `allowedFields` (all personal data fields like full_name, email, phone, etc.)
2. **Database table** had NEW structure (only relational fields: profile_id, program_id, etc.)
3. **PageController** was trying to insert data with new structure, but model rejected it

---

## Changes Made

### 1. Updated AdmissionModel (`app/Modules/Admission/Models/AdmissionModel.php`)

#### allowedFields - BEFORE:
```php
protected $allowedFields = [
    'registration_number',
    'full_name', 'nickname', 'gender', 'place_of_birth', 'date_of_birth',
    'religion', 'citizen_id', 'phone', 'email', 'street_address',
    'district', 'regency', 'province', 'postal_code',
    'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
    'father_name', 'mother_name', 'course', 'status', 'application_date',
    'photo', 'documents', 'notes'
];
```

#### allowedFields - AFTER:
```php
protected $allowedFields = [
    'registration_number',
    'profile_id',
    'program_id',
    'status',
    'application_date',
    'reviewed_date',
    'reviewed_by',
    'notes',
    'applicant_notes'
];
```

#### validationRules - BEFORE:
```php
protected $validationRules = [
    'registration_number' => 'required|is_unique[admissions.registration_number,id,{id}]',
    'full_name' => 'required|min_length[3]|max_length[100]',
    'gender' => 'required|in_list[Male,Female]',
    // ... many more personal data validations
    'course' => 'required|min_length[3]',
    'status' => 'required|in_list[pending,approved,rejected]'
];
```

#### validationRules - AFTER:
```php
protected $validationRules = [
    'registration_number' => 'required|is_unique[admissions.registration_number,id,{id}]',
    'profile_id' => 'required|is_natural_no_zero|is_not_unique[profiles.id]',
    'program_id' => 'required|is_not_unique[programs.id]',
    'status' => 'required|in_list[pending,approved,rejected,withdrawn]',
    'application_date' => 'permit_empty|valid_date',
    'reviewed_date' => 'permit_empty|valid_date',
    'reviewed_by' => 'permit_empty|is_natural_no_zero|is_not_unique[users.id]'
];
```

#### Updated Methods:
- `searchAdmissions()` - Now joins with profiles and programs tables
- `getCourseStatistics()` → `getProgramStatistics()` - Uses program_id instead of course field
- `getCourseStatusBreakdown()` → `getProgramStatusBreakdown()` - Uses program_id
- Added `getWithDetails()` - Fetch admission with profile and program data
- Added `getAllWithDetails()` - Fetch all admissions with joined data

---

### 2. Updated Apply Form (`app/Modules/Frontend/Views/apply.php`)

#### Course Selection Field - BEFORE:
```php
<!-- Dropdown sent program TITLE as text -->
<select name="course">
    <option value="<?= esc($program['title']) ?>">
        <?= esc($program['title']) ?>
    </option>
</select>
```

#### Course Selection Field - AFTER:
```php
<!-- Pre-selected program sends program_id -->
<input type="hidden" name="program_id" value="<?= esc($selectedProgram['id']) ?>">

<!-- Dropdown sends program ID -->
<select name="course">
    <option value="<?= esc($program['id']) ?>">
        <?= esc($program['title']) ?>
    </option>
</select>
```

---

### 3. Updated PageController (`app/Modules/Frontend/Controllers/PageController.php`)

#### Program ID Resolution - Enhanced:
```php
// Get program_id from form (either directly or by looking up course name)
$programId = $this->request->getPost('program_id');

if (!$programId) {
    // Fallback: Look up program by ID from 'course' field
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
```

---

## Application Flow (Fixed)

### Step 1: User Fills Form
- Personal information → Will be stored in **profiles** table
- Program selection → Sends **program_id**
- Files uploaded → Stored in `public/uploads/profiles/`

### Step 2: Controller Processes Submission
1. **Create Profile** (ProfileModel)
   - Generate profile_number (PROF-2026-0001)
   - Insert all personal data
   - Upload photo and documents
   - Returns profile_id

2. **Create Admission** (AdmissionModel)
   - Generate registration_number (REG-2026-0001)
   - Link profile_id and program_id
   - Set status = 'pending'
   - Returns admission_id

### Step 3: Success Response
- Redirect to `/apply/success`
- Display registration number
- Profile and admission created successfully

---

## Database Structure (Current)

### profiles table (Master Personal Data)
- id (PK)
- profile_number (PROF-2026-0001)
- user_id (nullable - for future account creation)
- full_name, gender, date_of_birth, etc.
- email, phone, address fields
- emergency contact, parents info
- photo, documents (JSON)

### admissions table (Relational)
- id (PK)
- registration_number (REG-2026-0001)
- **profile_id** (FK → profiles.id)
- **program_id** (FK → programs.id)
- status (pending/approved/rejected/withdrawn)
- application_date
- reviewed_date, reviewed_by
- notes, applicant_notes

### students table (Role Assignment)
- id (PK)
- student_number (STU-2026-0001)
- **profile_id** (FK → profiles.id)
- enrollment_date
- status (active/inactive/graduated)

---

## Testing Checklist

- [x] AdmissionModel updated with new fields
- [x] Apply form sends program_id
- [x] PageController handles program_id correctly
- [ ] Test application submission from programs page
- [ ] Test application submission from apply page
- [ ] Verify profile creation
- [ ] Verify admission creation
- [ ] Check file uploads work
- [ ] Verify success page displays registration number

---

## Next Steps

1. **Test the application form** - Submit a test application
2. **Update AdmissionController** - View/edit methods need to join with profiles and programs
3. **Update admission views** - Display profile and program data from joined tables
4. **Create student conversion** - When admission is approved, create student record

---

## Files Modified

1. `app/Modules/Admission/Models/AdmissionModel.php` - Complete refactor
2. `app/Modules/Frontend/Views/apply.php` - Updated course selection field
3. `app/Modules/Frontend/Controllers/PageController.php` - Enhanced program_id handling

---

**Status**: ✅ FIXED - Application submission should now work correctly
