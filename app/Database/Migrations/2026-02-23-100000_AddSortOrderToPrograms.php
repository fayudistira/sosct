<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSortOrderToPrograms extends Migration
{
    public function up()
    {
        $fields = [
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'curriculum'
            ]
        ];

        $this->forge->addColumn('programs', $fields);

        // Set default sort_order based on created_at for existing records
        $this->db->query('UPDATE programs SET sort_order = id WHERE sort_order = 0');
    }

    public function down()
    {
        $this->forge->dropColumn('programs', 'sort_order');
    }
}
