<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryStockOpnamesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'opname_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'location_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'draft',
            ],
            'performed_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'start_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('location_id', 'inventory_locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventory_stock_opnames');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_stock_opnames');
    }
}
