<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateInvoicesStatusEnum extends Migration
{
    public function up()
    {
        // Modify status enum to include new values
        $this->db->query("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'cancelled', 'expired', 'partially_paid') DEFAULT 'unpaid'");
    }

    public function down()
    {
        // Revert to old status enum
        $this->db->query("ALTER TABLE invoices MODIFY COLUMN status ENUM('outstanding', 'paid', 'cancelled') DEFAULT 'outstanding'");
    }
}
