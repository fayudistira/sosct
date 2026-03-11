<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePageviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'page_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'page_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'view_count' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'default'    => 1,
            ],
            'last_viewed_at' => [
                'type' => 'DATETIME',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['page_url']);
        $this->forge->createTable('pageviews');
    }

    public function down()
    {
        $this->forge->dropTable('pageviews');
    }
}
