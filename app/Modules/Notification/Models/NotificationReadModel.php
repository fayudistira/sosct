<?php

namespace Modules\Notification\Models;

use CodeIgniter\Model;

class NotificationReadModel extends Model
{
    protected $table = 'notification_reads';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'notification_id',
        'user_id',
        'read_at',
    ];
    protected $useTimestamps = false;

    /**
     * Mark a notification as read for a user
     * 
     * @param int $notificationId Notification ID
     * @param int $userId User ID
     * @return bool Success status
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        // Check if already marked as read
        if ($this->isRead($notificationId, $userId)) {
            return true;
        }

        return $this->insert([
            'notification_id' => $notificationId,
            'user_id' => $userId,
            'read_at' => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    /**
     * Check if a notification has been read by a user
     * 
     * @param int $notificationId Notification ID
     * @param int $userId User ID
     * @return bool
     */
    public function isRead(int $notificationId, int $userId): bool
    {
        return $this->where([
            'notification_id' => $notificationId,
            'user_id' => $userId,
        ])->first() !== null;
    }

    /**
     * Mark multiple notifications as read for a user
     * 
     * @param int $userId User ID
     * @param array $notificationIds Array of notification IDs
     * @return bool Success status
     */
    public function markAllAsReadForUser(int $userId, array $notificationIds): bool
    {
        if (empty($notificationIds)) {
            return true;
        }

        $data = [];
        $readAt = date('Y-m-d H:i:s');

        foreach ($notificationIds as $notificationId) {
            // Skip if already read
            if (!$this->isRead($notificationId, $userId)) {
                $data[] = [
                    'notification_id' => $notificationId,
                    'user_id' => $userId,
                    'read_at' => $readAt,
                ];
            }
        }

        if (empty($data)) {
            return true;
        }

        // Batch insert
        return $this->insertBatch($data) !== false;
    }

    /**
     * Get all read notification IDs for a user
     * 
     * @param int $userId User ID
     * @return array Array of notification IDs
     */
    public function getReadNotificationIds(int $userId): array
    {
        $results = $this->where('user_id', $userId)
            ->findAll();

        return array_column($results, 'notification_id');
    }
}