<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'item_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'barcode' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'location_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'program_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'piece',
            ],
            'purchase_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'selling_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'current_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'minimum_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'maximum_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'discontinued'],
                'default'    => 'active',
            ],
            'specifications' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'thumbnail' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'supplier_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'supplier_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->addForeignKey('category_id', 'inventory_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'inventory_locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventory_items');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_items');
    }
}
