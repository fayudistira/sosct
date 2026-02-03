# Data Model Refactoring Plan

## 🎯 Objective

Refactor the data model to eliminate duplication and establish a clean relational structure where:
- **profiles** = Single source of truth for ALL people (students, staff, instructors)
- **admissions** = Relational table connecting profiles to programs (application records)
- **students** = Role assignment for enrolled students
- **staff** = Role assignment for staff members
- **instructors** = Role assignment for instructors

---

## 📊 Current Problems

### 1. Data Duplication
```
admissions table contains:
- full_name, gender, date_of_birth, phone, email
- address fields
- emergency contact
- parents info
- photo, documents

profiles table contains:
- SAME fields as admissions!
- Used only for staff currently
```

**Problem**: Same person data stored in multiple places = inconsistency risk

### 2. Unclear Relationships
```
admissions → ??? → students
profiles → staff only
No clear path from application to enrollment
```

### 3. Limited Flexibility
- Can't track a person across multiple roles
- Can't track application history properly
- Can't reuse profile data

---

## ✅ Proposed Solution

### New Data Architecture

```
┌─────────────┐
│   users     │  ← Authentication (optional)
│  (Shield)   │     Only if they need login
└──────┬──────┘
       │ 0..1:1
       ↓
┌─────────────┐
│  profiles   │  ← MASTER: All people (students, staff, instructors)
│             │     Single source of truth for personal data
└──────┬──────┘
       │
       ├─ 1:N → ┌──────────────┐
       │         │  admissions  │  ← RELATIONAL: Profile applies to Program
       │         │              │     (registration_number, status, notes)
       │         └──────┬───────┘
       │                │ N:1
       │                ↓
       │         ┌──────────────┐
       │         │  programs    │  ← Programs/Courses
       │         └──────────────┘
       │
       ├─ 0..1:1 → ┌──────────────┐
       │            │  students    │  ← ROLE: Student-specific data
       │            └──────────────┘
       │
       ├─ 0..1:1 → ┌──────────────┐
       │            │  staff       │  ← ROLE: Staff-specific data
       │            └──────────────┘
       │
       └─ 0..1:1 → ┌──────────────┐
                    │  instructors │  ← ROLE: Instructor-specific data
                    └──────────────┘
```

---

## 🗄️ New Database Schema

### 1. profiles (Master Identity Table)

**Purpose**: Single source of truth for ALL people

```sql
CREATE TABLE profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    profile_number VARCHAR(20) UNIQUE NOT NULL,  -- PROF-2026-0001
    user_id INT NULL,  -- Optional: only if they have login
    
    -- Personal Information
    full_name VARCHAR(100) NOT NULL,
    nickname VARCHAR(50),
    gender ENUM('Male', 'Female') NOT NULL,
    place_of_birth VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    religion VARCHAR(50) NOT NULL,
    citizen_id VARCHAR(20),
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    
    -- Address
    street_address TEXT NOT NULL,
    district VARCHAR(100) NOT NULL,
    regency VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10),
    
    -- Emergency Contact
    emergency_contact_name VARCHAR(100) NOT NULL,
    emergency_contact_phone VARCHAR(15) NOT NULL,
    emergency_contact_relation VARCHAR(50) NOT NULL,
    
    -- Parents/Family
    father_name VARCHAR(100) NOT NULL,
    mother_name VARCHAR(100) NOT NULL,
    
    -- Files
    photo VARCHAR(255),
    documents TEXT,  -- JSON array
    
    -- Metadata
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_profile_number (profile_number),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 2. admissions (Relational/Junction Table)

**Purpose**: Connect profiles to programs (application records)

```sql
CREATE TABLE admissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    registration_number VARCHAR(20) UNIQUE NOT NULL,  -- REG-2026-0001
    
    -- Relationships
    profile_id INT NOT NULL,   -- Who is applying
    program_id CHAR(36) NOT NULL,  -- What program they're applying to
    
    -- Application Specific Data
    status ENUM('pending', 'approved', 'rejected', 'withdrawn') DEFAULT 'pending',
    application_date DATE NOT NULL,
    reviewed_date DATE,
    reviewed_by INT,  -- user_id of reviewer
    
    -- Additional Info
    notes TEXT,  -- Admin notes
    applicant_notes TEXT,  -- Applicant's motivation/notes
    
    -- Metadata
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME,
    
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE RESTRICT,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_profile (profile_id),
    INDEX idx_program (program_id),
    INDEX idx_status (status),
    INDEX idx_registration (registration_number),
    INDEX idx_deleted (deleted_at),
    
    -- One person can apply to same program only once (unless previous is deleted)
    UNIQUE KEY unique_application (profile_id, program_id, deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Key Changes**:
- ❌ Remove all personal data fields (now in profiles)
- ✅ Add `profile_id` foreign key
- ✅ Add `program_id` foreign key
- ✅ Keep only application-specific data
- ✅ Add `reviewed_date` and `reviewed_by`
- ✅ Add `applicant_notes` for motivation letter
- ✅ Add unique constraint to prevent duplicate applications

---

### 3. students (Role Assignment Table)

**Purpose**: Student-specific data and role assignment

```sql
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_number VARCHAR(20) UNIQUE NOT NULL,  -- STU-2026-0001
    profile_id INT NOT NULL UNIQUE,  -- One profile = one student record
    
    -- Reference to original admission
    admission_id INT,  -- Which admission led to enrollment
    
    -- Student Specific Data
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'graduated', 'dropped', 'suspended') DEFAULT 'active',
    program_id CHAR(36),  -- Current/main program
    batch VARCHAR(50),  -- e.g., "2026-A", "Batch 5"
    
    -- Academic Data
    gpa DECIMAL(3,2) DEFAULT 0.00,
    total_credits INT DEFAULT 0,
    
    -- Graduation
    graduation_date DATE,
    graduation_gpa DECIMAL(3,2),
    
    -- Metadata
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME,
    
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL,
    
    INDEX idx_profile (profile_id),
    INDEX idx_admission (admission_id),
    INDEX idx_status (status),
    INDEX idx_student_number (student_number),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 4. staff (Role Assignment Table)

**Purpose**: Staff-specific data and role assignment

```sql
CREATE TABLE staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_number VARCHAR(20) UNIQUE NOT NULL,  -- STF-2026-0001
    profile_id INT NOT NULL UNIQUE,
    
    -- Staff Specific Data
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    hire_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'resigned', 'terminated') DEFAULT 'active',
    
    -- Employment
    employment_type ENUM('full-time', 'part-time', 'contract') DEFAULT 'full-time',
    salary DECIMAL(15,2),
    
    -- Termination
    termination_date DATE,
    termination_reason TEXT,
    
    -- Metadata
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME,
    
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    
    INDEX idx_profile (profile_id),
    INDEX idx_status (status),
    INDEX idx_staff_number (staff_number),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 5. instructors (Role Assignment Table)

**Purpose**: Instructor-specific data and role assignment

```sql
CREATE TABLE instructors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    instructor_number VARCHAR(20) UNIQUE NOT NULL,  -- INS-2026-0001
    profile_id INT NOT NULL UNIQUE,
    
    -- Instructor Specific Data
    specialization VARCHAR(100),
    qualification VARCHAR(100),  -- e.g., "PhD in Computer Science"
    hire_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'resigned') DEFAULT 'active',
    
    -- Teaching
    max_classes INT DEFAULT 5,  -- Maximum classes per semester
    hourly_rate DECIMAL(10,2),
    
    -- Metadata
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME,
    
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    
    INDEX idx_profile (profile_id),
    INDEX idx_status (status),
    INDEX idx_instructor_number (instructor_number),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 6. Updated Related Tables

#### invoices (Update foreign key)
```sql
ALTER TABLE invoices
    DROP FOREIGN KEY invoices_ibfk_1,  -- Remove old FK
    DROP COLUMN registration_number,
    ADD COLUMN student_id INT NOT NULL AFTER invoice_number,
    ADD FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE RESTRICT,
    ADD INDEX idx_student (student_id);
```

#### class_members (Update foreign key)
```sql
-- Already planned correctly in academic module spec
-- References students table, not admissions
```

---

## 🔄 Migration Strategy

### Phase 1: Create New Tables
1. Create new `profiles` table structure
2. Create new `students` table
3. Create new `staff` table
4. Create new `instructors` table
5. Keep old tables temporarily

### Phase 2: Migrate Data

#### Step 1: Migrate Admissions → Profiles + New Admissions
```sql
-- For each admission record:
-- 1. Create profile with personal data
-- 2. Create new admission record with profile_id and program_id
-- 3. Keep registration_number

INSERT INTO profiles (
    profile_number, full_name, nickname, gender, place_of_birth, 
    date_of_birth, religion, citizen_id, phone, email,
    street_address, district, regency, province, postal_code,
    emergency_contact_name, emergency_contact_phone, emergency_contact_relation,
    father_name, mother_name, photo, documents, created_at, updated_at
)
SELECT 
    CONCAT('PROF-', YEAR(created_at), '-', LPAD(id, 4, '0')),
    full_name, nickname, gender, place_of_birth,
    date_of_birth, religion, citizen_id, phone, email,
    street_address, district, regency, province, postal_code,
    emergency_contact_name, emergency_contact_phone, emergency_contact_relation,
    father_name, mother_name, photo, documents, created_at, updated_at
FROM admissions_old;

-- Then create new admission records
INSERT INTO admissions (
    registration_number, profile_id, program_id, status, 
    application_date, notes, created_at, updated_at
)
SELECT 
    a.registration_number,
    p.id,
    (SELECT id FROM programs WHERE program_name = a.course LIMIT 1),
    a.status,
    a.application_date,
    a.notes,
    a.created_at,
    a.updated_at
FROM admissions_old a
JOIN profiles p ON p.email = a.email;
```

#### Step 2: Create Students from Approved Admissions
```sql
INSERT INTO students (
    student_number, profile_id, admission_id, enrollment_date, 
    status, program_id, created_at, updated_at
)
SELECT 
    CONCAT('STU-', YEAR(a.application_date), '-', LPAD(a.id, 4, '0')),
    a.profile_id,
    a.id,
    a.application_date,
    'active',
    a.program_id,
    a.created_at,
    a.updated_at
FROM admissions a
WHERE a.status = 'approved';
```

#### Step 3: Migrate Old Profiles → Staff
```sql
INSERT INTO staff (
    staff_number, profile_id, position, hire_date, 
    status, created_at, updated_at
)
SELECT 
    CONCAT('STF-', YEAR(created_at), '-', LPAD(id, 4, '0')),
    id,
    position,
    created_at,
    'active',
    created_at,
    updated_at
FROM profiles_old
WHERE user_id IS NOT NULL;  -- Assuming staff have user accounts
```

### Phase 3: Update References
1. Update `invoices` table to reference `students` instead of `registration_number`
2. Update `class_members` to reference `students`
3. Update all queries in controllers/models

### Phase 4: Update Application Code
1. Update AdmissionModel
2. Update AdmissionController
3. Update all views
4. Update PaymentModule
5. Update all other modules

### Phase 5: Testing
1. Test data integrity
2. Test all CRUD operations
3. Test all relationships
4. Test all workflows

### Phase 6: Cleanup
1. Drop old tables
2. Remove old code
3. Update documentation

---

## 📝 Workflow Examples

### Workflow 1: New Applicant
```
1. Person visits website
2. Fills application form
   → Creates profile (personal data)
   → Creates admission (links profile to program)
   → Status: pending

3. Admin reviews application
   → Updates admission status to 'approved'

4. Admin creates student record
   → Creates student (links to profile and admission)
   → Student gets student_number

5. Student can now be enrolled in classes
   → class_members references student_id
```

### Workflow 2: Existing Person Applies Again
```
1. Person already has profile (from previous application)
2. Applies to different program
   → Reuses existing profile
   → Creates new admission (different program_id)
   → New registration_number

3. Can track all applications for same person
   → SELECT * FROM admissions WHERE profile_id = X
```

### Workflow 3: Student Becomes Instructor
```
1. Person has profile + student record
2. Hired as instructor
   → Creates instructor record (links to same profile)
   → Instructor gets instructor_number

3. Same person now has:
   - 1 profile (personal data)
   - 1 student record (student role)
   - 1 instructor record (instructor role)
   - Multiple admissions (application history)
```

---

## ✅ Benefits

### 1. Single Source of Truth
- Personal data stored once in `profiles`
- Update once, reflects everywhere
- No data inconsistency

### 2. Clean Relationships
- Clear foreign keys
- Proper relational structure
- Easy to query and join

### 3. Flexibility
- One person can have multiple roles
- Track complete application history
- Reuse profile data

### 4. Data Integrity
- Foreign key constraints
- Unique constraints prevent duplicates
- Cascading deletes maintain consistency

### 5. Scalability
- Easy to add new role types
- Easy to add new relationships
- Supports complex queries

### 6. Audit Trail
- Track all applications per person
- Track role changes
- Track status transitions

---

## 🎯 Success Criteria

- [x] Schema designed
- [ ] Migrations created
- [ ] Data migration scripts tested
- [ ] All modules updated
- [ ] All tests passing
- [ ] No data loss
- [ ] Performance acceptable
- [ ] Documentation updated

---

## 📅 Timeline

**Estimated Duration**: 2-3 weeks

- Week 1: Schema design, migrations, data migration scripts
- Week 2: Update application code, testing
- Week 3: Final testing, deployment, documentation

---

## ⚠️ Risks & Mitigation

### Risk 1: Data Loss During Migration
**Mitigation**: 
- Backup database before migration
- Test migration on copy first
- Keep old tables until verified

### Risk 2: Application Downtime
**Mitigation**:
- Perform migration during off-hours
- Use blue-green deployment
- Have rollback plan ready

### Risk 3: Code Breaking Changes
**Mitigation**:
- Update code incrementally
- Test each module after update
- Use feature flags if needed

---

**Document Version**: 1.0  
**Created**: 2026-02-03  
**Status**: ✅ Ready for Review & Approval
