<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProgramSwitchFields extends Migration
{
    public function up()
    {
        // Add fields to installments table to track program switches
        $this->forge->addColumn('installments', [
            'parent_installment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Reference to parent installment when program is switched'
            ],
            'switch_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Date when program switch occurred'
            ],
            'switch_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for program switch'
            ]
        ]);

        // Add fields to admissions table to track program changes
        $this->forge->addColumn('admissions', [
            'previous_program_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Previous program ID before switch'
            ],
            'program_switch_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Number of times program has been switched'
            ]
        ]);

        // Add index for parent_installment_id
        $this->forge->addKey('parent_installment_id', false, false, 'installments');
    }

    public function down()
    {
        // Drop columns from admissions
        $this->forge->dropColumn('admissions', 'previous_program_id');
        $this->forge->dropColumn('admissions', 'program_switch_count');

        // Drop columns from installments
        $this->forge->dropColumn('installments', 'parent_installment_id');
        $this->forge->dropColumn('installments', 'switch_date');
        $this->forge->dropColumn('installments', 'switch_reason');
    }
}
