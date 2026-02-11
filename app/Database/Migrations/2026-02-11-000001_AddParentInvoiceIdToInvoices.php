<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParentInvoiceIdToInvoices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('invoices', [
            'parent_invoice_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'invoice_type'
            ]
        ]);

        $this->forge->addKey('parent_invoice_id');
        $this->forge->addForeignKey('parent_invoice_id', 'invoices', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('invoices', 'invoices_parent_invoice_id_foreign');
        $this->forge->dropColumn('invoices', 'parent_invoice_id');
    }
}
