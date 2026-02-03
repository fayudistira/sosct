# Admission Module Updated for Relational Structure

**Date**: February 3, 2026  
**Status**: ✅ Complete

---

## Issues Fixed

### 1. Route Order Issue
**Problem**: `/apply/success` was being caught by `apply/(:segment)` route  
**Solution**: Moved specific routes before wildcard routes in Routes.php

### 2. Admission Index - Undefined Key Errors
**Problem**: View trying to access `full_name`, `email`, `phone`, `course` directly from admissions table  
**Solution**: Updated controller to use `getAllWithDetails()` which joins profiles and programs tables

### 3. Admission View - Undefined Key 'course'
**Problem**: View trying to access old field names that no longer exist in admissions table  
**Solution**: Updated view to use `program_title`, `category` from joined data

### 4. Admission Edit - Cannot Edit Profile Data
**Problem**: Edit form trying to edit personal data that's now in profiles table  
**Solution**: Simplified edit to only update admission-specific fields (program_id, status, notes)

---

## Files Modified

### 1. Routes Configuration
**File**: `app/Modules/Frontend/Config/Routes.php`

```php
// BEFORE - Wrong order
$routes->get('apply', 'PageController::apply');
$routes->get('apply/(:segment)', 'PageController::applyWithProgram/$1');
$routes->post('apply/submit', 'PageController::submitApplication');
$routes->get('apply/success', 'PageController::applySuccess');

// AFTER - Correct order
$routes->get('apply', 'PageController::apply');
$routes->post('apply/submit', 'PageController::submitApplication');
$routes->get('apply/success', 'PageController::applySuccess');
$routes->get('apply/(:segment)', 'PageController::applyWithProgram/$1'); // Must be last
```

### 2. AdmissionController
**File**: `app/Modules/Admission/Controllers/AdmissionController.php`

**Changes**:
- `index()` - Now uses `getAllWithDetails()` instead of `getWithPagination()`
- `view()` - Now uses `getWithDetails($id)` instead of `find($id)`
- `edit()` - Now uses `getWithDetails($id)` to get joined data
- `update()` - Simplified to only update admission fields (program_id, status, notes, reviewed_date, reviewed_by)
- `search()` - Already uses `searchAdmissions()` which joins tables

### 3. Admission Index View
**File**: `app/Modules/Admission/Views/index.php`

**Changes**:
- Changed table header from "Course" to "Program"
- Changed `$admission['course']` to `$admission['program_title']`
- Updated pagination to use manual pagination variables

### 4. Admission View Page
**File**: `app/Modules/Admission/Views/view.php`

**Changes**:
- Changed "Course" to "Program" in section header
- Changed `$admission['course']` to `$admission['program_title']`
- Added display of program category
- Split notes into `applicant_notes` and `notes` (admin notes)
- Updated photo path from `uploads/admissions/photos/` to `uploads/profiles/photos/`

### 5. Admission Edit Page
**File**: `app/Modules/Admission/Views/edit.php`

**Changes**:
- Changed course dropdown to program_id dropdown
- Removed all personal data fields (they're in profiles table)
- Added admin notes field
- Added info alert explaining that profile data must be edited in Profile module
- Added "withdrawn" status option

---

## Current Admission Module Capabilities

### What You CAN Do:
✅ View all admissions with applicant details (joined from profiles)  
✅ View single admission with full details  
✅ Search admissions by name, email, phone, registration number, program  
✅ Change admission status (pending → approved/rejected/withdrawn)  
✅ Change assigned program  
✅ Add admin notes  
✅ Track who reviewed and when (reviewed_by, reviewed_date)  
✅ Delete admissions (soft delete)

### What You CANNOT Do (By Design):
❌ Edit applicant's personal information (name, email, address, etc.)  
❌ Upload/change profile photo from admission edit  
❌ Upload/change documents from admission edit

**Why?** Personal data is now in the profiles table. To edit profile information, use the Profile module at `/dashboard/profiles`.

---

## Data Flow

### Application Submission (Frontend)
1. User fills form at `/apply` or `/apply/{program_id}`
2. Form submits to `/apply/submit`
3. Controller creates:
   - Profile record (with all personal data)
   - Admission record (linking profile_id and program_id)
4. Redirects to `/apply/success` with registration number

### Admission Management (Dashboard)
1. **List**: `/admission` - Shows all admissions with joined profile and program data
2. **View**: `/admission/view/{id}` - Shows complete details with joined data
3. **Edit**: `/admission/edit/{id}` - Only edit admission-specific fields
4. **Search**: `/admission/search?keyword=...` - Search across profiles and programs

---

## Database Relationships

```
profiles (Master Personal Data)
├── id (PK)
├── profile_number (PROF-2026-0001)
├── full_name, email, phone, address, etc.
└── photo, documents

admissions (Relational)
├── id (PK)
├── registration_number (REG-2026-0001)
├── profile_id (FK → profiles.id)
├── program_id (FK → programs.id)
├── status (pending/approved/rejected/withdrawn)
├── reviewed_date, reviewed_by
└── notes, applicant_notes

programs
├── id (PK - UUID)
├── title, category, sub_category
└── tuition_fee, discount, etc.
```

---

## Next Steps

### 1. Profile Module Enhancement
- Add profile edit functionality
- Add photo/document upload/update
- Link to admissions from profile view

### 2. Student Conversion
- When admission is approved, create student record
- Generate student_number (STU-2026-0001)
- Link student to profile_id

### 3. Admission Workflow
- Add email notifications on status change
- Add approval workflow (multi-level approval)
- Add bulk actions (approve/reject multiple)

### 4. Reporting
- Admission statistics by program
- Conversion rate (applications → students)
- Timeline reports

---

## Testing Checklist

- [x] Application form submission works
- [x] Redirects to success page correctly
- [x] Admission index displays all records
- [x] Admission view shows complete details
- [x] Admission edit updates status and program
- [x] Search works across profiles and programs
- [ ] Profile edit functionality (to be implemented)
- [ ] Student conversion on approval (to be implemented)

---

**Status**: Admission module is now fully functional with the new relational structure! 🎉
