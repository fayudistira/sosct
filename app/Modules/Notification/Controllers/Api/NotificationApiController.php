<?php

namespace Modules\Notification\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\NotificationService;

class NotificationApiController extends ResourceController
{
    protected NotificationService $notificationService;
    protected $format = 'json';

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Get unread notification count for current user
     * 
     * GET /notifications/api/unread-count
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function unreadCount()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $userRoles = $this->getUserRoles($user);
        $count = $this->notificationService->getUnreadCountForUser($user->id, $userRoles);

        return $this->respond(['count' => $count]);
    }

    /**
     * Get notification list for current user
     * 
     * GET /notifications/api/list
     * Query params: limit (default: 10)
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function list()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $limit = (int)($this->request->getGet('limit') ?? 10);
        $userRoles = $this->getUserRoles($user);

        $notifications = $this->notificationService->getUnreadForUser($user->id, $userRoles, $limit);

        return $this->respond([
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     * 
     * POST /notifications/api/mark-read/{id}
     * 
     * @param int|null $id Notification ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function markRead($id = null)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        if (!$id) {
            return $this->failValidationError('Notification ID required');
        }

        // Verify notification exists and is for user's role
        $notification = $this->notificationService->getById((int)$id);
        if (!$notification) {
            return $this->failNotFound('Notification not found');
        }

        $userRoles = $this->getUserRoles($user);
        if (!in_array($notification['target_role'], $userRoles)) {
            return $this->failForbidden('Access denied');
        }

        $result = $this->notificationService->markAsRead((int)$id, $user->id);

        return $this->respond(['success' => $result]);
    }

    /**
     * Mark all notifications as read for current user
     * 
     * POST /notifications/api/mark-all-read
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function markAllRead()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        $userRoles = $this->getUserRoles($user);
        $result = $this->notificationService->markAllAsRead($user->id, $userRoles);

        return $this->respond(['success' => $result]);
    }

    /**
     * Get user's primary role
     * 
     * @param \CodeIgniter\Shield\Entities\User $user
     * @return string
     */
    protected function getUserRole($user): string
    {
        $groups = $user->getGroups();
        return $groups[0] ?? 'student';
    }

    /**
     * Get all user's roles
     * 
     * @param \CodeIgniter\Shield\Entities\User $user
     * @return array
     */
    protected function getUserRoles($user): array
    {
        return $user->getGroups();
    }
}