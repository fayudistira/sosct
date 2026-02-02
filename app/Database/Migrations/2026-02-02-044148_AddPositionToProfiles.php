<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPositionToProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'mother_name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('profiles', 'position');
    }
}
