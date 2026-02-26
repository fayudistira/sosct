<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSwitchedStatusToInstallments extends Migration
{
    public function up()
    {
        // Check if we're using ENUM and modify it
        // For MySQL, we need to modify the ENUM column
        $db = \Config\Database::connect();
        
        // Get current column type
        $query = $db->query("SHOW COLUMNS FROM installments LIKE 'status'");
        $column = $query->getRow();
        
        if ($column && strpos($column->Type, 'enum') !== false) {
            // Modify the ENUM to include 'switched'
            $db->query("ALTER TABLE installments MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'switched') DEFAULT 'unpaid'");
        }
    }

    public function down()
    {
        // Revert to original ENUM
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM installments LIKE 'status'");
        $column = $query->getRow();
        
        if ($column && strpos($column->Type, 'enum') !== false) {
            $db->query("ALTER TABLE installments MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid'");
        }
    }
}
