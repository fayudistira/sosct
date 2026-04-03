<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveEmailUniqueConstraintFromProfiles extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE profiles DROP INDEX profiles_email_unique');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE profiles ADD UNIQUE KEY profiles_email_unique (email)');
    }
}
