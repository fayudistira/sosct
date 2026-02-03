# Data Model Refactoring - Step-by-Step Implementation Guide

## 📋 Overview

This guide provides detailed instructions for refactoring the FEECS data model from a duplicated structure to a clean relational architecture with profiles as the single source of truth.

**Estimated Time**: 2-3 weeks  
**Difficulty**: Medium-High  
**Risk Level**: Medium (requires careful data migration)

---

## ⚠️ Pre-Implementation Checklist

Before starting, ensure:

- [ ] Full database backup created
- [ ] Development environment ready
- [ ] All team members notified
- [ ] Testing environment prepared
- [ ] Rollback plan documented
- [ ] Stakeholder approval obtained

---

## 📦 Phase 1: Preparation (Day 1)

### Step 1.1: Backup Current Database

```bash
# Create backup
mysqldump -u root -p feecs_db > backup_before_refactoring_$(date +%Y%m%d).sql

# Verify backup
ls -lh backup_before_refactoring_*.sql

# Test restore on separate database (optional but recommended)
mysql -u root -p -e "CREATE DATABASE feecs_test;"
mysql -u root -p feecs_test < backup_before_refactoring_*.sql
```

### Step 1.2: Create Feature Branch

```bash
git checkout -b feature/data-model-refactoring
git push -u origin feature/data-model-refactoring
```

### Step 1.3: Document Current State

Create a snapshot of current data:

```sql
-- Count records in each table
SELECT 'admissions' as table_name, COUNT(*) as count FROM admissions
UNION ALL
SELECT 'profiles', COUNT(*) FROM profiles
UNION ALL
SELECT 'invoices', COUNT(*) FROM invoices
UNION ALL
SELECT 'payments', COUNT(*) FROM payments;

-- Export to file for reference
-- Save this output for verification later
```

---

## 📦 Phase 2: Create New Table Structures (Day 1-2)

### Step 2.1: Rename Old Tables

First, rename existing tables to keep them as backup:

```bash
php spark make:migration RenameOldTables
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_RenameOldTables.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameOldTables extends Migration
{
    public function up()
    {
        // Rename old tables to keep as backup
        $this->forge->renameTable('admissions', 'admissions_old');
        $this->forge->renameTable('profiles', 'profiles_old');
    }

    public function down()
    {
        // Restore original names
        $this->forge->renameTable('admissions_old', 'admissions');
        $this->forge->renameTable('profiles_old', 'profiles');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.2: Create New Profiles Table

```bash
php spark make:migration CreateNewProfilesTable
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateNewProfilesTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'profile_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nickname' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'place_of_birth' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'citizen_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'street_address' => [
                'type' => 'TEXT',
            ],
            'district' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'regency' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'emergency_contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'emergency_contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'emergency_contact_relation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'father_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mother_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'documents' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of document filenames',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('phone');
        $this->forge->addKey('profile_number');
        $this->forge->addKey('deleted_at');
        
        // Add foreign key to users table
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('profiles');
    }

    public function down()
    {
        $this->forge->dropTable('profiles');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.3: Create New Admissions Table

```bash
php spark make:migration CreateNewAdmissionsTable
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateNewAdmissionsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewAdmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'registration_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'program_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'withdrawn'],
                'default' => 'pending',
            ],
            'application_date' => [
                'type' => 'DATE',
            ],
            'reviewed_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Admin notes',
            ],
            'applicant_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Applicant motivation/notes',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('profile_id');
        $this->forge->addKey('program_id');
        $this->forge->addKey('status');
        $this->forge->addKey('registration_number');
        $this->forge->addKey('deleted_at');
        
        // Add foreign keys
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        
        // Unique constraint: one person can apply to same program only once
        $this->db->query('ALTER TABLE admissions ADD UNIQUE KEY unique_application (profile_id, program_id, deleted_at)');
        
        $this->forge->createTable('admissions');
    }

    public function down()
    {
        $this->forge->dropTable('admissions');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.4: Create Students Table

```bash
php spark make:migration CreateStudentsTable
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateStudentsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'unique' => true,
            ],
            'admission_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'enrollment_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'graduated', 'dropped', 'suspended'],
                'default' => 'active',
            ],
            'program_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'batch' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'gpa' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'default' => 0.00,
            ],
            'total_credits' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'graduation_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'graduation_gpa' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('profile_id');
        $this->forge->addKey('admission_id');
        $this->forge->addKey('status');
        $this->forge->addKey('student_number');
        $this->forge->addKey('deleted_at');
        
        // Add foreign keys
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admission_id', 'admissions', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.5: Create Staff Table

```bash
php spark make:migration CreateStaffTable
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateStaffTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'staff_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'unique' => true,
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'hire_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'resigned', 'terminated'],
                'default' => 'active',
            ],
            'employment_type' => [
                'type' => 'ENUM',
                'constraint' => ['full-time', 'part-time', 'contract'],
                'default' => 'full-time',
            ],
            'salary' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'termination_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'termination_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('profile_id');
        $this->forge->addKey('status');
        $this->forge->addKey('staff_number');
        $this->forge->addKey('deleted_at');
        
        // Add foreign key
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('staff');
    }

    public function down()
    {
        $this->forge->dropTable('staff');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.6: Create Instructors Table

```bash
php spark make:migration CreateInstructorsTable
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateInstructorsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstructorsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'instructor_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'unique' => true,
            ],
            'specialization' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'qualification' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'hire_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'resigned'],
                'default' => 'active',
            ],
            'max_classes' => [
                'type' => 'INT',
                'default' => 5,
            ],
            'hourly_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('profile_id');
        $this->forge->addKey('status');
        $this->forge->addKey('instructor_number');
        $this->forge->addKey('deleted_at');
        
        // Add foreign key
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('instructors');
    }

    public function down()
    {
        $this->forge->dropTable('instructors');
    }
}
```

Run migration:
```bash
php spark migrate
```

---

### Step 2.7: Verify New Tables

```sql
-- Check all tables created
SHOW TABLES;

-- Check profiles structure
DESCRIBE profiles;

-- Check admissions structure
DESCRIBE admissions;

-- Check students structure
DESCRIBE students;

-- Check staff structure
DESCRIBE staff;

-- Check instructors structure
DESCRIBE instructors;
```

---

## 📦 Phase 3: Data Migration (Day 3-4)

### Step 3.1: Create Data Migration Script

```bash
php spark make:migration MigrateDataToNewStructure
```

**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_MigrateDataToNewStructure.php`

