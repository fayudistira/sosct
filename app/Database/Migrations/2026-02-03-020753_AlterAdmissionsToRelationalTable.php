<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterAdmissionsToRelationalTable extends Migration
{
    public function up()
    {
        // Drop old unique keys first
        $this->forge->dropKey('admissions', 'email');
        
        // Drop all personal data columns (now in profiles)
        $columnsToRemove = [
            'full_name', 'nickname', 'gender', 'place_of_birth', 'date_of_birth',
            'religion', 'citizen_id', 'phone', 'email', 'street_address',
            'district', 'regency', 'province', 'postal_code',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
            'father_name', 'mother_name', 'photo', 'documents', 'course'
        ];
        
        $this->forge->dropColumn('admissions', $columnsToRemove);
        
        // Add new relational fields
        $newFields = [
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'after' => 'registration_number',
            ],
            'program_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false,
                'after' => 'profile_id',
            ],
            'reviewed_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'application_date',
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'reviewed_date',
            ],
            'applicant_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'notes',
            ],
        ];
        
        $this->forge->addColumn('admissions', $newFields);
        
        // Update status enum to include 'withdrawn'
        $this->db->query("ALTER TABLE admissions MODIFY status ENUM('pending', 'approved', 'rejected', 'withdrawn') DEFAULT 'pending'");
        
        // Add foreign keys
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE', 'admissions_profile_fk');
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'RESTRICT', 'CASCADE', 'admissions_program_fk');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'CASCADE', 'admissions_reviewer_fk');
        
        // Add indexes
        $this->forge->addKey('profile_id', false, false, 'admissions_profile_idx');
        $this->forge->addKey('program_id', false, false, 'admissions_program_idx');
        
        // Add unique constraint: one person can apply to same program only once (unless deleted)
        $this->db->query('ALTER TABLE admissions ADD UNIQUE KEY unique_application (profile_id, program_id, deleted_at)');
    }

    public function down()
    {
        // Drop foreign keys
        $this->forge->dropForeignKey('admissions', 'admissions_profile_fk');
        $this->forge->dropForeignKey('admissions', 'admissions_program_fk');
        $this->forge->dropForeignKey('admissions', 'admissions_reviewer_fk');
        
        // Drop unique constraint
        $this->forge->dropKey('admissions', 'unique_application');
        
        // Drop new columns
        $this->forge->dropColumn('admissions', ['profile_id', 'program_id', 'reviewed_date', 'reviewed_by', 'applicant_notes']);
        
        // Add back old columns (for rollback)
        $oldFields = [
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nickname' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'gender' => ['type' => 'ENUM', 'constraint' => ['Male', 'Female']],
            'place_of_birth' => ['type' => 'VARCHAR', 'constraint' => 100],
            'date_of_birth' => ['type' => 'DATE'],
            'religion' => ['type' => 'VARCHAR', 'constraint' => 50],
            'citizen_id' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 15],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'street_address' => ['type' => 'TEXT'],
            'district' => ['type' => 'VARCHAR', 'constraint' => 100],
            'regency' => ['type' => 'VARCHAR', 'constraint' => 100],
            'province' => ['type' => 'VARCHAR', 'constraint' => 100],
            'postal_code' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'emergency_contact_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'emergency_contact_phone' => ['type' => 'VARCHAR', 'constraint' => 15],
            'emergency_contact_relation' => ['type' => 'VARCHAR', 'constraint' => 50],
            'father_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'mother_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'course' => ['type' => 'VARCHAR', 'constraint' => 100],
            'photo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'documents' => ['type' => 'TEXT', 'null' => true],
        ];
        
        $this->forge->addColumn('admissions', $oldFields);
        $this->forge->addUniqueKey('email');
    }
}
