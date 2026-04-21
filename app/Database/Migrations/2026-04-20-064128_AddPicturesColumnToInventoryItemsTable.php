<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPicturesColumnToInventoryItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('inventory_items', [
            'pictures' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'thumbnail'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_items', 'pictures');
    }
}
