<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProfilesTableAddMissingFields extends Migration
{
    public function up()
    {
        // Add missing fields to profiles table
        $fields = [
            'profile_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'after' => 'id',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'after' => 'phone',
            ],
        ];
        
        $this->forge->addColumn('profiles', $fields);
        
        // Add unique keys using raw SQL (more reliable)
        $this->db->query('ALTER TABLE profiles ADD UNIQUE KEY profiles_profile_number_unique (profile_number)');
        $this->db->query('ALTER TABLE profiles ADD UNIQUE KEY profiles_email_unique (email)');
        
        // Make user_id nullable (not everyone needs login)
        $this->db->query('ALTER TABLE profiles MODIFY user_id INT(11) UNSIGNED NULL');
    }

    public function down()
    {
        // Drop unique keys first
        $this->forge->dropKey('profiles', 'profiles_profile_number_unique');
        $this->forge->dropKey('profiles', 'profiles_email_unique');
        
        // Drop columns
        $this->forge->dropColumn('profiles', ['profile_number', 'email']);
    }
}
