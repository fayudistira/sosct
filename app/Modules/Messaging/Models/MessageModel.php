<?php

namespace Modules\Messaging\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['conversation_id', 'sender_id', 'message_text', 'attachment_path', 'is_read'];
    protected $useTimestamps = true;

    /**
     * Get messages for a conversation
     */
    public function getConversationMessages(string $conversationId, int $limit = 50, int $offset = 0)
    {
        return $this->select('messages.*, users.username as sender_name')
                    ->join('users', 'users.id = messages.sender_id')
                    ->where('conversation_id', $conversationId)
                    ->orderBy('created_at', 'ASC') // Oldest first for chat history? Or DESC for fetch? Usually DB fetch DESC, then reverse in UI or code. ASC is easier for full history.
                    // Let's go with DESC for pagination efficiency, then UI reverses.
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->find();
    }

    /**
     * Send a message
     */
    public function sendMessage(string $conversationId, int $senderId, ?string $text, ?string $attachment = null)
    {
        return $this->insert([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'message_text' => $text,
            'attachment_path' => $attachment,
            'is_read' => 0
        ]);
    }

    /**
     * Mark all messages in a conversation as read for a user
     */
    public function markConversationAsRead(string $conversationId, int $userId)
    {
        $db = \Config\Database::connect();
        // Update participant's last read timestamp
        $db->table('conversation_participants')
           ->where('conversation_id', $conversationId)
           ->where('user_id', $userId)
           ->update(['last_read_at' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Check if user is participant
     */
    public function isParticipant(string $conversationId, int $userId)
    {
        $db = \Config\Database::connect();
        return $db->table('conversation_participants')
                  ->where('conversation_id', $conversationId)
                  ->where('user_id', $userId)
                  ->countAllResults() > 0;
    }
}
