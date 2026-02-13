<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstallmentsTable extends Migration
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
            'total_contract_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'total_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'remaining_balance' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['unpaid', 'partial', 'paid'],
                'default' => 'unpaid',
            ],
            'due_date' => [
                'type' => 'DATE',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('registration_number');
        $this->forge->addForeignKey('registration_number', 'admissions', 'registration_number', 'CASCADE', 'CASCADE');
        $this->forge->createTable('installments');
    }

    public function down()
    {
        $this->forge->dropTable('installments');
    }
}
