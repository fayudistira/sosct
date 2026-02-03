# Data Model Refactoring - Complete ✅

## 🎯 Objective Achieved

Successfully refactored the database to eliminate data duplication and establish a clean relational structure.

---

## ✅ What Was Done

### 1. Updated Profiles Table
**Migration**: `2026-02-03-020943_UpdateProfilesTableAddMissingFields.php`

**Changes**:
- ✅ Added `profile_number` field (VARCHAR 20, UNIQUE)
- ✅ Added `email` field (VARCHAR 100, UNIQUE)
- ✅ Made `user_id` nullable (not everyone needs login)
- ✅ Added unique constraints

**Result**: Profiles table is now the **single source of truth** for all people.

---

### 2. Refactored Admissions Table
**Migration**: `2026-02-03-020753_AlterAdmissionsToRelationalTable.php`

**Removed Fields** (now in profiles):
- ❌ full_name, nickname, gender, place_of_birth, date_of_birth
- ❌ religion, citizen_id, phone, email
- ❌ street_address, district, regency, province, postal_code
- ❌ emergency_contact_name, emergency_contact_phone, emergency_contact_relation
- ❌ father_name, mother_name
- ❌ photo, documents
- ❌ course (replaced with program_id)

**Added Fields**:
- ✅ `profile_id` (INT, FK to profiles) - WHO is applying
- ✅ `program_id` (VARCHAR 36, FK to programs) - WHAT they're applying to
- ✅ `reviewed_date` (DATE) - When reviewed
- ✅ `reviewed_by` (INT, FK to users) - Who reviewed
- ✅ `applicant_notes` (TEXT) - Motivation letter

**Updated**:
- ✅ Status enum: added 'withdrawn' option
- ✅ Foreign keys with proper constraints
- ✅ Unique constraint: (profile_id, program_id, deleted_at)

**Result**: Admissions is now a clean **relational/junction table** connecting profiles to programs.

---

### 3. Created Students Table
**Migration**: `2026-02-03-020815_CreateStudentsTable.php`

**Purpose**: Role assignment for enrolled students

**Fields**:
- `id` - Primary key
- `student_number` - Unique identifier (STU-YYYY-NNNN)
- `profile_id` - FK to profiles (UNIQUE)
- `admission_id` - FK to admissions (which admission led to enrollment)
- `enrollment_date` - When enrolled
- `status` - active, inactive, graduated, dropped, suspended
- `program_id` - Current/main program
- `batch` - Batch identifier
- `gpa` - Grade point average
- `total_credits` - Total credits earned
- `graduation_date` - When graduated
- `graduation_gpa` - Final GPA
- Timestamps + soft delete

**Result**: Clean separation of student role data.

---

### 4. Created Staff Table
**Migration**: `2026-02-03-020850_CreateStaffTable.php`

**Purpose**: Role assignment for staff members

**Fields**:
- `id` - Primary key
- `staff_number` - Unique identifier (STF-YYYY-NNNN)
- `profile_id` - FK to profiles (UNIQUE)
- `position` - Job position
- `department` - Department
- `hire_date` - When hired
- `status` - active, inactive, resigned, terminated
- `employment_type` - full-time, part-time, contract
- `salary` - Salary amount
- `termination_date` - When terminated
- `termination_reason` - Why terminated
- Timestamps + soft delete

**Result**: Clean separation of staff role data.

---

### 5. Created Instructors Table
**Migration**: `2026-02-03-020916_CreateInstructorsTable.php`

**Purpose**: Role assignment for instructors

**Fields**:
- `id` - Primary key
- `instructor_number` - Unique identifier (INS-YYYY-NNNN)
- `profile_id` - FK to profiles (UNIQUE)
- `specialization` - Area of expertise
- `qualification` - Educational qualification
- `hire_date` - When hired
- `status` - active, inactive, resigned
- `max_classes` - Maximum classes per semester
- `hourly_rate` - Hourly teaching rate
- Timestamps + soft delete

**Result**: Clean separation of instructor role data.

---

## 📊 New Data Architecture

```
┌─────────────┐
│   users     │  ← Authentication (optional)
│  (Shield)   │
└──────┬──────┘
       │ 0..1:1
       ↓
┌─────────────┐
│  profiles   │  ← MASTER: Single source of truth
│             │     (profile_number, personal data)
└──────┬──────┘
       │
       ├─ 1:N → ┌──────────────┐
       │         │  admissions  │  ← RELATIONAL: Profile → Program
       │         │              │     (registration_number, status)
       │         └──────┬───────┘
       │                │ N:1
       │                ↓
       │         ┌──────────────┐
       │         │  programs    │  ← Programs/Courses
       │         └──────────────┘
       │
       ├─ 0..1:1 → ┌──────────────┐
       │            │  students    │  ← ROLE: Student data
       │            │              │     (student_number, GPA, etc.)
       │            └──────────────┘
       │
       ├─ 0..1:1 → ┌──────────────┐
       │            │  staff       │  ← ROLE: Staff data
       │            │              │     (staff_number, position, etc.)
       │            └──────────────┘
       │
       └─ 0..1:1 → ┌──────────────┐
                    │  instructors │  ← ROLE: Instructor data
                    │              │     (instructor_number, etc.)
                    └──────────────┘
```

---

## 🔄 Workflow Examples

### Example 1: New Applicant → Student
```
1. Person applies through website
   → Create profile (personal data)
   → Create admission (profile_id + program_id)
   → Status: pending

2. Admin reviews
   → Update admission.status = 'approved'
   → Set reviewed_date and reviewed_by

3. Admin creates student record
   → Create student (profile_id + admission_id)
   → Student gets student_number
   → Status: active

4. Enroll in classes
   → class_members references student_id
```

### Example 2: Person Applies to Multiple Programs
```
1. Person has profile_id = 1
2. Applies to Program A
   → admission (profile_id=1, program_id=A, reg_number=REG-2026-0001)
3. Applies to Program B
   → admission (profile_id=1, program_id=B, reg_number=REG-2026-0002)
4. Both applications tracked separately
5. Can be approved for one or both
```

### Example 3: Student Becomes Instructor
```
1. Person has:
   - profile_id = 1
   - student record (student_number = STU-2026-0001)
   
2. Hired as instructor:
   → Create instructor (profile_id=1, instructor_number=INS-2026-0001)
   
3. Same person now has:
   - 1 profile (personal data)
   - 1 student record (student role)
   - 1 instructor record (instructor role)
   - Multiple admissions (application history)
```

---

## ✅ Benefits Achieved

### 1. Single Source of Truth
- ✅ Personal data stored once in profiles
- ✅ Update once, reflects everywhere
- ✅ No data inconsistency

### 2. Clean Relationships
- ✅ Clear foreign keys
- ✅ Proper relational structure
- ✅ Easy to query and join

### 3. Flexibility
- ✅ One person can have multiple roles
- ✅ Track complete application history
- ✅ Reuse profile data

### 4. Data Integrity
- ✅ Foreign key constraints
- ✅ Unique constraints prevent duplicates
- ✅ Cascading deletes maintain consistency

### 5. Scalability
- ✅ Easy to add new role types
- ✅ Easy to add new relationships
- ✅ Supports complex queries

---

## 📝 Next Steps

### Immediate (Required)
1. [ ] Update ProfileModel
   - Add profile_number generation
   - Add email field to allowedFields
   - Update validation rules

2. [ ] Update AdmissionModel
   - Remove personal data fields
   - Add profile_id, program_id
   - Update validation rules
   - Update relationships

3. [ ] Update AdmissionController
   - Modify create() to create profile first
   - Update store() to save profile + admission
   - Update views to show profile data
   - Update approval workflow

4. [ ] Create StudentModel
   - Implement student_number generation
   - Add CRUD methods
   - Add query methods

5. [ ] Create StaffModel
   - Implement staff_number generation
   - Add CRUD methods

6. [ ] Create InstructorModel
   - Implement instructor_number generation
   - Add CRUD methods

### Short Term
7. [ ] Update Frontend application form
   - Create profile + admission
   - Link to program

8. [ ] Update Payment module
   - Change invoices to reference student_id
   - Update invoice generation

9. [ ] Update all views
   - Show profile data via relationships
   - Update forms

10. [ ] Create seeders
    - ProfileSeeder
    - Updated AdmissionSeeder
    - StudentSeeder
    - StaffSeeder
    - InstructorSeeder

### Testing
11. [ ] Test profile creation
12. [ ] Test admission creation
13. [ ] Test student creation
14. [ ] Test multiple roles per person
15. [ ] Test all relationships
16. [ ] Test data integrity

---

## 🗄️ Database Tables Summary

| Table | Purpose | Key Fields | Relationships |
|-------|---------|------------|---------------|
| **profiles** | Master identity | profile_number, email, personal data | → users (0..1:1) |
| **admissions** | Applications | registration_number, profile_id, program_id, status | → profiles (N:1), → programs (N:1) |
| **students** | Student role | student_number, profile_id, admission_id | → profiles (1:1), → admissions (N:1) |
| **staff** | Staff role | staff_number, profile_id, position | → profiles (1:1) |
| **instructors** | Instructor role | instructor_number, profile_id, specialization | → profiles (1:1) |
| **programs** | Courses | id (UUID), title, category | ← admissions (1:N) |

---

## 🎯 Success Criteria

- [x] Schema designed
- [x] Migrations created
- [x] Migrations executed successfully
- [x] All tables created
- [x] Foreign keys established
- [x] Unique constraints added
- [ ] Models updated
- [ ] Controllers updated
- [ ] Views updated
- [ ] Seeders created
- [ ] Testing complete
- [ ] Documentation updated

---

## 📅 Timeline

**Completed**: 2026-02-03
**Duration**: 1 day (schema design + migrations)
**Next Phase**: Update application code (estimated 1-2 weeks)

---

## ⚠️ Important Notes

1. **No Data Loss**: Since all records were deleted, no data migration was needed
2. **Clean Start**: Fresh schema with proper relationships
3. **Backward Compatibility**: Old code will break - must update all modules
4. **Foreign Keys**: All relationships enforced at database level
5. **Soft Deletes**: All tables support soft delete for audit trail

---

## 🔧 Migration Files Created

1. `2026-02-03-020943_UpdateProfilesTableAddMissingFields.php`
2. `2026-02-03-020753_AlterAdmissionsToRelationalTable.php`
3. `2026-02-03-020815_CreateStudentsTable.php`
4. `2026-02-03-020850_CreateStaffTable.php`
5. `2026-02-03-020916_CreateInstructorsTable.php`

All migrations executed successfully! ✅

---

**Document Version**: 1.0  
**Date**: 2026-02-03  
**Status**: ✅ Database Refactoring Complete  
**Next**: Update Application Code
