<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOutstandingToUnpaid extends Migration
{
    public function up()
    {
        // Update all invoices with 'outstanding' status to 'unpaid'
        $this->db->query("UPDATE invoices SET status = 'unpaid' WHERE status = 'outstanding'");
    }

    public function down()
    {
        // Revert back to 'outstanding'
        $this->db->query("UPDATE invoices SET status = 'outstanding' WHERE status = 'unpaid'");
    }
}
