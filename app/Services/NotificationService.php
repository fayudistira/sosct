<?php

namespace App\Services;

use Modules\Notification\Models\NotificationModel;
use Modules\Notification\Models\NotificationReadModel;

class NotificationService
{
    protected NotificationModel $notificationModel;
    protected NotificationReadModel $readModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->readModel = new NotificationReadModel();
    }

    /**
     * Create notification for a specific role
     * 
     * @param string $role Target role
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return int|false Insert ID or false on failure
     */
    public function createForRole(
        string $role,
        string $type,
        string $title,
        string $message,
        array $data = []
    ) {
        return $this->notificationModel->createNotification(
            $role,
            $type,
            $title,
            $message,
            $data
        );
    }

    /**
     * Create notification for multiple roles
     * 
     * @param array $roles Array of target roles
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return void
     */
    public function createForRoles(
        array $roles,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): void {
        $this->notificationModel->createForRoles(
            $roles,
            $type,
            $title,
            $message,
            $data
        );
    }

    /**
     * Notify all admins (superadmin + admin)
     * 
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return void
     */
    public function notifyAdmins(
        string $type,
        string $title,
        string $message,
        array $data = []
    ): void {
        $this->createForRoles(['superadmin', 'admin'], $type, $title, $message, $data);
    }

    /**
     * Notify about new admission
     * 
     * @param array $admissionData Admission data containing:
     *   - registration_number: string
     *   - admission_id: int (optional)
     *   - program_title: string
     *   - applicant_name: string
     * @return void
     */
    public function notifyNewAdmission(array $admissionData): void
    {
        $this->notifyAdmins(
            'new_admission',
            'New Admission Submitted',
            "New applicant: {$admissionData['applicant_name']} for {$admissionData['program_title']}",
            [
                'admission_id' => $admissionData['admission_id'] ?? null,
                'registration_number' => $admissionData['registration_number'],
                'program_title' => $admissionData['program_title'],
                'applicant_name' => $admissionData['applicant_name'],
            ]
        );
    }

    /**
     * Get unread notifications for a user
     * 
     * @param int $userId User ID
     * @param string|array $userRoles User's role(s) - can be single role or array of roles
     * @param int $limit Maximum number to return
     * @return array
     */
    public function getUnreadForUser(int $userId, $userRoles, int $limit = 20): array
    {
        return $this->notificationModel->getUnreadForUser($userId, $userRoles, $limit);
    }

    /**
     * Get unread notification count for a user
     * 
     * @param int $userId User ID
     * @param string|array $userRoles User's role(s) - can be single role or array of roles
     * @return int
     */
    public function getUnreadCountForUser(int $userId, $userRoles): int
    {
        return $this->notificationModel->getUnreadCountForUser($userId, $userRoles);
    }

    /**
     * Mark a notification as read for a user
     * 
     * @param int $notificationId Notification ID
     * @param int $userId User ID
     * @return bool Success status
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        return $this->readModel->markAsRead($notificationId, $userId);
    }

    /**
     * Mark all notifications as read for a user
     * 
     * @param int $userId User ID
     * @param string|array $userRoles User's role(s) - can be single role or array of roles
     * @return bool Success status
     */
    public function markAllAsRead(int $userId, $userRoles): bool
    {
        $notifications = $this->getUnreadForUser($userId, $userRoles);
        $ids = array_column($notifications, 'id');

        if (empty($ids)) {
            return true;
        }

        return $this->readModel->markAllAsReadForUser($userId, $ids);
    }

    /**
     * Get notification by ID
     * 
     * @param int $id Notification ID
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        return $this->notificationModel->getById($id);
    }
}