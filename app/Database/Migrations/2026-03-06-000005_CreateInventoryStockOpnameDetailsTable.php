<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryStockOpnameDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'opname_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'item_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'system_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'physical_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'difference' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['matched', 'discrepancy', 'pending'],
                'default'    => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'counted_at' => [
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
        $this->forge->addForeignKey('opname_id', 'inventory_stock_opnames', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'inventory_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventory_stock_opname_details');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_stock_opname_details');
    }
}
