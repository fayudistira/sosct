# Data Migration Scripts

## Phase 3: Data Migration

### Step 3.1: Migrate Admissions → Profiles + New Admissions

Create migration file:

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateDataToNewStructure extends Migration
{
    public function up()
    {
        // Step 1: Migrate admissions_old → profiles
        $this->migrateAdmissionsToProfiles();
        
        // Step 2: Create new admission records
        $this->createNewAdmissionRecords();
        
        // Step 3: Create student records from approved admissions
        $this->createStudentRecords();
        
        // Step 