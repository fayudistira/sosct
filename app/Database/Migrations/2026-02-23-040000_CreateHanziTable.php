<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHanziTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'hanzi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'pinyin' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'OTHER'],
                'default' => 'OTHER',
                'null' => false,
            ],
            'translation' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON format: {"en": "hello", "id": "halo"}',
            ],
            'example' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON format: {"en": "Hello friend", "id": "Halo teman"}',
            ],
            'stroke_count' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'frequency' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Frequency ranking',
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
        $this->forge->addUniqueKey('hanzi');
        $this->forge->addKey('category');
        $this->forge->createTable('hanzi', true);
    }

    public function down()
    {
        $this->forge->dropTable('hanzi', true);
    }
}
