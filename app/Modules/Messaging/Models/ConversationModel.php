<?php

namespace Modules\Messaging\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table = 'conversations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'title', 'type', 'created_by'];
    protected $useTimestamps = true;

    /**
     * Get all conversations for a specific user
     */
    public function getUserConversations(int $userId)
    {
        $db = \Config\Database::connect();
        
        // Get conversation IDs where user is a participant
        $builder = $db->table('conversation_participants');
        $builder->select('conversation_id, last_read_at');
        $builder->where('user_id', $userId);
        $participations = $builder->get()->getResultArray();
        
        if (empty($participations)) {
            return [];
        }
        
        $conversations = [];
        $messageModel = new MessageModel();
        
        foreach ($participations as $participation) {
            $convId = $participation['conversation_id'];
            $conv = $this->find($convId);
            
            if (!$conv) continue;
            
            // Get last message
            $lastMessage = $messageModel->where('conversation_id', $convId)
                                      ->orderBy('created_at', 'DESC')
                                      ->first();
                                      
            // Get unread count
            $unreadCount = $messageModel->where('conversation_id', $convId)
                                      ->where('created_at >', $participation['last_read_at'] ?? '1970-01-01')
                                      ->countAllResults();
            
            // Get participants info
            $participants = $this->getParticipants($convId);
            
            // Format title for private chats (use other person's name)
            if ($conv['type'] === 'private') {
                foreach ($participants as $p) {
                    if ($p['user_id'] != $userId) {
                        $conv['title'] = $p['username']; // Simplified, assumes username exists
                        $conv['avatar'] = $p['avatar'] ?? null; // potential placeholder
                        break;
                    }
                }
            }

            $conv['last_message'] = $lastMessage;
            $conv['unread_count'] = $unreadCount;
            $conv['participants'] = $participants;
            
            $conversations[] = $conv;
        }
        
        // Sort by last message date
        usort($conversations, function($a, $b) {
            $dateA = $a['last_message']['created_at'] ?? $a['created_at'];
            $dateB = $b['last_message']['created_at'] ?? $b['created_at'];
            return strtotime($dateB) - strtotime($dateA);
        });
        
        return $conversations;
    }

    /**
     * Create a new conversation
     */
    public function createConversation(string $type, int $createdBy, ?string $title = null, array $participantIds = [])
    {
        $id = $this->generateUUID();
        
        $data = [
            'id' => $id,
            'type' => $type,
            'created_by' => $createdBy,
            'title' => $title
        ];
        
        $this->insert($data);
        
        // Add creator as participant
        $this->addParticipant($id, $createdBy);
        
        // Add other participants
        foreach ($participantIds as $uid) {
            $this->addParticipant($id, $uid);
        }
        
        return $id;
    }

    /**
     * Add user to conversation
     */
    public function addParticipant(string $conversationId, int $userId)
    {
        $db = \Config\Database::connect();
        $db->table('conversation_participants')->ignore(true)->insert([
            'conversation_id' => $conversationId,
            'user_id' => $userId,
            'joined_at' => date('Y-m-d H:i:s'),
            'last_read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get participants of a conversation
     */
    public function getParticipants(string $conversationId)
    {
        $db = \Config\Database::connect();
        return $db->table('conversation_participants')
                 ->select('users.id as user_id, users.username, users.active') // Adjust fields based on Users table
                 ->join('users', 'users.id = conversation_participants.user_id')
                 ->where('conversation_id', $conversationId)
                 ->get()
                 ->getResultArray();
    }
    

    /**
     * Get total unread count for user across all conversations
     */
    public function getUnreadCount(int $userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('conversation_participants');
        $builder->select('conversation_id, last_read_at');
        $builder->where('user_id', $userId);
        $participations = $builder->get()->getResultArray();
        
        $totalUnread = 0;
        $messageModel = new MessageModel();
        
        foreach ($participations as $p) {
            $count = $messageModel->where('conversation_id', $p['conversation_id'])
                                ->where('created_at >', $p['last_read_at'] ?? '1970-01-01')
                                ->countAllResults();
            $totalUnread += $count;
        }
        
        return $totalUnread;
    }

    /**
     * Helper to generate UUID

     */
    protected function generateUUID() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
