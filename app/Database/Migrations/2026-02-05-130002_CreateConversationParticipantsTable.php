<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConversationParticipantsTable extends Migration
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
            'conversation_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'joined_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('conversation_id', 'conversations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        // Ensure unique participation per conversation
        $this->forge->addUniqueKey(['conversation_id', 'user_id']);
        
        $this->forge->createTable('conversation_participants');
    }

    public function down()
    {
        $this->forge->dropTable('conversation_participants');
    }
}
