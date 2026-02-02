<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
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
            'invoice_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'registration_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'due_date' => [
                'type' => 'DATE',
            ],
            'invoice_type' => [
                'type' => 'ENUM',
                'constraint' => ['registration_fee', 'tuition_fee', 'miscellaneous_fee'],
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['unpaid', 'paid', 'cancelled'],
                'default' => 'unpaid',
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
        $this->forge->addUniqueKey('invoice_number');
        $this->forge->addKey('registration_number');
        $this->forge->addKey('status');
        $this->forge->addKey('due_date');
        $this->forge->addForeignKey('registration_number', 'admissions', 'registration_number', 'CASCADE', 'CASCADE');
        $this->forge->createTable('invoices');
    }

    public function down()
    {
        $this->forge->dropTable('invoices');
    }
}
