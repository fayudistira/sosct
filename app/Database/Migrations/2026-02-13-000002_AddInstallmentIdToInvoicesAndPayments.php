<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInstallmentIdToInvoicesAndPayments extends Migration
{
    public function up()
    {
        // Add installment_id to invoices table
        $this->forge->addColumn('invoices', [
            'installment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'registration_number',
            ],
            'contract_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'installment_id',
            ],
        ]);

        // Add foreign key for installment_id in invoices
        $this->forge->addForeignKey('installment_id', 'installments', 'id', 'SET NULL', 'CASCADE', 'invoices_installment_id_foreign');

        // Add installment_id to payments table
        $this->forge->addColumn('payments', [
            'installment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'registration_number',
            ],
        ]);

        // Add foreign key for installment_id in payments
        $this->forge->addForeignKey('installment_id', 'installments', 'id', 'SET NULL', 'CASCADE', 'payments_installment_id_foreign');

        // Update invoice status enum to include 'extended'
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'cancelled', 'expired', 'partially_paid', 'extended') DEFAULT 'unpaid'");
    }

    public function down()
    {
        // Remove foreign keys first
        $this->forge->dropForeignKey('invoices', 'invoices_installment_id_foreign');
        $this->forge->dropForeignKey('payments', 'payments_installment_id_foreign');

        // Remove columns one by one
        $this->forge->dropColumn('invoices', 'contract_number');
        $this->forge->dropColumn('invoices', 'installment_id');
        $this->forge->dropColumn('payments', 'installment_id');

        // Revert status enum
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'cancelled', 'expired', 'partially_paid') DEFAULT 'unpaid'");
    }
}
