<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddItemsJsonToInvoices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('invoices', [
            'items' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Invoice line items stored as JSON array',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('invoices', 'items');
    }
}
