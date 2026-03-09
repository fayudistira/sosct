<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddToLocationToInventoryMovementsTable extends Migration
{
    public function up()
    {
        // Add to_location_id column for transfer tracking
        $fields = [
            'to_location_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
                'after'      => 'location_id'
            ],
            'source_location_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
                'after'      => 'to_location_id'
            ]
        ];
        
        $this->forge->addColumn('inventory_movements', $fields);
        
        // Add foreign key for to_location_id
        $this->forge->addForeignKey('to_location_id', 'inventory_locations', 'id', 'SET NULL', 'CASCADE', 'inventory_movements_to_location_foreign');
        $this->forge->addForeignKey('source_location_id', 'inventory_locations', 'id', 'SET NULL', 'CASCADE', 'inventory_movements_source_location_foreign');
        
        // Update ENUM to include 'distributed'
        $this->db->query("ALTER TABLE inventory_movements MODIFY movement_type ENUM('purchase', 'return', 'sale', 'distributed', 'adjustment', 'transfer', 'damage', 'expired', 'initial') DEFAULT 'purchase'");
    }

    public function down()
    {
        $this->forge->dropForeignKey('inventory_movements', 'inventory_movements_to_location_foreign');
        $this->forge->dropForeignKey('inventory_movements', 'inventory_movements_source_location_foreign');
        $this->forge->dropColumn('inventory_movements', ['to_location_id', 'source_location_id']);
        
        // Revert ENUM
        $this->db->query("ALTER TABLE inventory_movements MODIFY movement_type ENUM('purchase', 'return', 'sale', 'distributed', 'adjustment', 'transfer', 'damage', 'expired', 'initial') DEFAULT 'purchase'");
    }
}
