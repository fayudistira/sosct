<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'registration_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'invoice_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'bank_transfer'],
            ],
            'document_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'payment_date' => [
                'type' => 'DATE',
            ],
            'receipt_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed', 'refunded'],
                'default' => 'pending',
            ],
            'failure_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'refund_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'refund_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('registration_number');
        $this->forge->addKey('payment_date');
        $this->forge->addKey('status');
        $this->forge->addKey('payment_method');
        $this->forge->addForeignKey('registration_number', 'admissions', 'registration_number', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('invoice_id', 'invoices', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
