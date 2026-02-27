<?php

namespace Modules\Messaging\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Messaging\Models\ConversationModel;
use Modules\Messaging\Models\MessageModel;

class MessagingApiController extends ResourceController
{
    protected $conversationModel;
    protected $messageModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->conversationModel = new ConversationModel();
        $this->messageModel = new MessageModel();
    }

    /**
     * Get user's conversations
     * GET /api/messages/conversations
     */
    public function conversations()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $conversations = $this->conversationModel->getUserConversations($user->id);

        return $this->respond([
            'status' => 'success',
            'data' => $conversations
        ]);
    }

    /**
     * Get single conversation with messages
     * GET /api/messages/conversations/{id}
     */
    public function conversation($id = null)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $conversation = $this->conversationModel->find($id);

        if (!$conversation) {
            return $this->failNotFound('Conversation not found');
        }

        // Check if user is participant
        if (!$this->messageModel->isParticipant($id, $user->id)) {
            return $this->failForbidden('You are not a participant in this conversation');
        }

        // Get messages
        $limit = $this->request->getGet('limit') ?? 50;
        $offset = $this->request->getGet('offset') ?? 0;
        $messages = $this->messageModel->getConversationMessages($id, $limit, $offset);

        // Mark as read
        $this->messageModel->markConversationAsRead($id, $user->id);

        // Get participants
        $participants = $this->conversationModel->getParticipants($id);

        return $this->respond([
            'status' => 'success',
            'data' => [
                'conversation' => $conversation,
                'participants' => $participants,
                'messages' => $messages
            ]
        ]);
    }

    /**
     * Create new conversation
     * POST /api/messages/conversations
     */
    public function createConversation()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $data = $this->request->getJSON(true);

        // Validate required fields
        if (empty($data['type'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Conversation type is required',
                'errors' => ['type' => 'Type is required (private or group)']
            ], 422);
        }

        $type = $data['type'];
        $title = $data['title'] ?? null;
        $participantIds = $data['participant_ids'] ?? [];

        // For private conversations, only one other participant
        if ($type === 'private' && count($participantIds) > 1) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Private conversations can only have one other participant'
            ], 422);
        }

        try {
            $conversationId = $this->conversationModel->createConversation(
                $type,
                $user->id,
                $title,
                $participantIds
            );

            $conversation = $this->conversationModel->find($conversationId);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Conversation created successfully',
                'data' => $conversation
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to create conversation',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Send message to conversation
     * POST /api/messages/{conversation_id}
     */
    public function sendMessage($conversationId = null)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        // Check if conversation exists
        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation) {
            return $this->failNotFound('Conversation not found');
        }

        // Check if user is participant
        if (!$this->messageModel->isParticipant($conversationId, $user->id)) {
            return $this->failForbidden('You are not a participant in this conversation');
        }

        $data = $this->request->getJSON(true);

        // At least one of message_text or attachment is required
        if (empty($data['message_text']) && empty($data['attachment'])) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Message text or attachment is required',
                'errors' => ['message' => 'Message content is required']
            ], 422);
        }

        try {
            $messageId = $this->messageModel->sendMessage(
                $conversationId,
                $user->id,
                $data['message_text'] ?? null,
                $data['attachment'] ?? null
            );

            $message = $this->messageModel->find($messageId);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to send message',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Mark conversation as read
     * POST /api/messages/{conversation_id}/read
     */
    public function markAsRead($conversationId = null)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation) {
            return $this->failNotFound('Conversation not found');
        }

        if (!$this->messageModel->isParticipant($conversationId, $user->id)) {
            return $this->failForbidden('You are not a participant in this conversation');
        }

        $this->messageModel->markConversationAsRead($conversationId, $user->id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Conversation marked as read'
        ]);
    }

    /**
     * Get unread count
     * GET /api/messages/unread
     */
    public function unreadCount()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $count = $this->conversationModel->getUnreadCount($user->id);

        return $this->respond([
            'status' => 'success',
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    /**
     * Add participant to conversation
     * POST /api/messages/conversations/{id}/participants
     */
    public function addParticipant($id = null)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $conversation = $this->conversationModel->find($id);
        if (!$conversation) {
            return $this->failNotFound('Conversation not found');
        }

        // Only creator can add participants (for now)
        if ($conversation['created_by'] != $user->id) {
            return $this->failForbidden('Only conversation creator can add participants');
        }

        $data = $this->request->getJSON(true);
        $participantId = $data['user_id'] ?? null;

        if (!$participantId) {
            return $this->fail([
                'status' => 'error',
                'message' => 'User ID is required',
                'errors' => ['user_id' => 'User ID is required']
            ], 422);
        }

        try {
            $this->conversationModel->addParticipant($id, $participantId);

            return $this->respond([
                'status' => 'success',
                'message' => 'Participant added successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'error',
                'message' => 'Failed to add participant',
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }
}
