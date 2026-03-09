<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryLocationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['warehouse', 'room', 'storage'],
                'default'    => 'storage',
            ],
            'is_default' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
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
        $this->forge->createTable('inventory_locations');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_locations');
    }
}
