<?php

namespace Modules\Messaging\Controllers;

use App\Controllers\BaseController;
use Modules\Messaging\Models\ConversationModel;
use Modules\Messaging\Models\MessageModel;
use CodeIgniter\Shield\Models\UserModel;

class MessagingController extends BaseController
{
    protected $conversationModel;
    protected $messageModel;
    protected $userModel;

    public function __construct()
    {
        $this->conversationModel = new ConversationModel();
        $this->messageModel = new MessageModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Messages',
            'user' => auth()->user()
        ];
        return view('Modules\Messaging\Views\index', $data);
    }

    public function conversation($id)
    {
        if (!$this->messageModel->isParticipant($id, auth()->id())) {
            return redirect()->to('/messages')->with('error', 'You do not have permission to view this conversation.');
        }

        // Mark as read
        $this->messageModel->markConversationAsRead($id, auth()->id());
        
        $conversation = $this->conversationModel->find($id);
        $participants = $this->conversationModel->getParticipants($id);
        
        // Determine title for display
        $displayTitle = $conversation['title'];
        if ($conversation['type'] === 'private') {
            foreach ($participants as $p) {
                if ($p['user_id'] != auth()->id()) {
                    $displayTitle = $p['username'];
                    break;
                }
            }
        }

        $data = [
            'title' => $displayTitle,
            'conversation' => $conversation,
            'participants' => $participants,
            'user' => auth()->user()
        ];
        
        return view('Modules\Messaging\Views\conversation', $data);
    }

    public function createConversation()
    {
        // For processing the create form
        $type = $this->request->getPost('type') ?? 'private';
        $title = $this->request->getPost('title');
        $participants = $this->request->getPost('participants'); // Array of user IDs
        
        if (empty($participants)) {
            return redirect()->back()->with('error', 'Please select at least one participant.');
        }

        $id = $this->conversationModel->createConversation($type, auth()->id(), $title, $participants);
        
        return redirect()->to("/messages/conversation/$id"); 
    }

    public function sendMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $rules = [
            'conversation_id' => 'required',
            'message' => 'permit_empty',
            // 'attachment' => 'uploaded[attachment]|max_size[attachment,2048]' // logic handled manually below
        ];
        
        // Basic validation
        if (!$this->validate($rules)) {
             return $this->response->setStatusCode(400)->setJSON(['error' => $this->validator->getErrors()]);
        }
        
        $conversationId = $this->request->getPost('conversation_id');
        $messageText = $this->request->getPost('message');
        
        // Permissions check
        if (!$this->messageModel->isParticipant($conversationId, auth()->id())) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $attachmentPath = null;
        $file = $this->request->getFile('attachment');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/messages', $newName);
            $attachmentPath = 'uploads/messages/' . $newName;
        }
        
        if (empty($messageText) && empty($attachmentPath)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Message or attachment required']);
        }

        $this->messageModel->sendMessage($conversationId, auth()->id(), $messageText, $attachmentPath);
        
        return $this->response->setJSON(['status' => 'success']);
    }

    // API: Get Conversations List
    public function apiGetConversations()
    {
        $conversations = $this->conversationModel->getUserConversations(auth()->id());
        
        // Format relative time helper could be useful here or in JS
        return $this->response->setJSON(['conversations' => $conversations]);
    }

    // API: Get Messages for a Conversation
    public function apiGetMessages($conversationId)
    {
        if (!$this->messageModel->isParticipant($conversationId, auth()->id())) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        // Handling pagination/cursors could be added here
        $messages = $this->messageModel->getConversationMessages($conversationId);
        
        // Reverse for chat order (oldest top) if we fetched DESC
        $messages = array_reverse($messages);
        
        return $this->response->setJSON(['messages' => $messages, 'current_user_id' => auth()->id()]);
    }
    
    // API: Mark Read
    public function apiMarkRead()
    {
        $conversationId = $this->request->getPost('conversation_id');
        if ($conversationId && $this->messageModel->isParticipant($conversationId, auth()->id())) {
            $this->messageModel->markConversationAsRead($conversationId, auth()->id());
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setStatusCode(400);
    }

    // API: Search users for new conversation
    public function apiSearchUsers()
    {
        $term = $this->request->getGet('term');
        
        if (empty($term)) {
             return $this->response->setJSON([]);
        }
        
        $db = \Config\Database::connect();
        $users = $db->table('users')
                    ->like('username', $term)
                    // ->orLike('email', $term) // if email is accessible
                    ->where('id !=', auth()->id())
                    ->where('active', 1)
                    ->limit(20)
                    ->get()
                    ->getResultArray();
                    
        $results = [];
        foreach ($users as $u) {
            $results[] = [
                'id' => $u['id'],
                'text' => $u['username']
            ];
        }
        
        return $this->response->setJSON(['results' => $results]);
    }

    // API: Get Total Unread Count
    public function apiGetUnreadCount()
    {
        $count = $this->conversationModel->getUnreadCount(auth()->id());
        return $this->response->setJSON(['count' => $count]);
    }
}
