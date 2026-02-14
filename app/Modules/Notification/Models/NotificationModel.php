<?php

namespace Modules\Notification\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'target_role',
        'type',
        'title',
        'message',
        'data',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get unread notifications for a user based on their role(s)
     * 
     * @param int $userId User ID
     * @param string|array $userRoles User's role(s) - can be single role or array of roles
     * @param int $limit Maximum number of notifications to return
     * @return array
     */
    public function getUnreadForUser(int $userId, $userRoles, int $limit = 20): array
    {
        $db = $this->db;

        // Convert single role to array
        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Build placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($userRoles), '?'));

        // Get notifications for user's roles that haven't been read by this user
        $sql = "
            SELECT n.* 
            FROM {$this->table} n
            LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_id = ?
            WHERE n.target_role IN ({$placeholders})
            AND nr.id IS NULL
            ORDER BY n.created_at DESC
            LIMIT ?
        ";

        $params = array_merge([$userId], $userRoles, [$limit]);
        $result = $db->query($sql, $params);
        $notifications = $result->getResultArray();

        // Decode JSON data for each notification
        foreach ($notifications as &$notification) {
            if (!empty($notification['data'])) {
                $notification['data'] = json_decode($notification['data'], true);
            }
        }

        return $notifications;
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
        $db = $this->db;

        // Convert single role to array
        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Build placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($userRoles), '?'));

        $sql = "
            SELECT COUNT(*) as count
            FROM {$this->table} n
            LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_id = ?
            WHERE n.target_role IN ({$placeholders})
            AND nr.id IS NULL
        ";

        $params = array_merge([$userId], $userRoles);
        $result = $db->query($sql, $params);
        $row = $result->getRowArray();

        return (int)($row['count'] ?? 0);
    }

    /**
     * Create a new notification for a specific role
     * 
     * @param string $targetRole Target role (admin, superadmin, etc.)
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return int|false Insert ID or false on failure
     */
    public function createNotification(
        string $targetRole,
        string $type,
        string $title,
        string $message,
        array $data = []
    ) {
        return $this->insert([
            'target_role' => $targetRole,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => !empty($data) ? json_encode($data) : null,
        ]);
    }

    /**
     * Create notifications for multiple roles
     * 
     * @param array $roles Array of role names
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
        foreach ($roles as $role) {
            $this->createNotification($role, $type, $title, $message, $data);
        }
    }

    /**
     * Get notification by ID with decoded data
     * 
     * @param int $id Notification ID
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $notification = $this->find($id);

        if ($notification && !empty($notification['data'])) {
            $notification['data'] = json_decode($notification['data'], true);
        }

        return $notification;
    }
}