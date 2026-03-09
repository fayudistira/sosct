<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryAlertsTable extends Migration
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
            'alert_type' => [
                'type'       => 'ENUM',
                'constraint' => ['low_stock', 'overstock', 'expiring', 'expired'],
                'default'    => 'low_stock',
            ],
            'current_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'threshold' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'resolved'],
                'default'    => 'active',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'resolved_at' => [
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
        $this->forge->createTable('inventory_alerts');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_alerts');
    }
}
