<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryMovementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'item_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'movement_type' => [
                'type'       => 'ENUM',
                'constraint' => ['purchase', 'return', 'sale', 'adjustment', 'transfer', 'damage', 'expired', 'initial'],
                'default'    => 'purchase',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'quantity_before' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'quantity_after' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'reference_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'performed_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'location_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'movement_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('item_id', 'inventory_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'inventory_locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventory_movements');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_movements');
    }
}
