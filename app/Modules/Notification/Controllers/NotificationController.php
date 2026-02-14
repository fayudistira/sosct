<?php

namespace Modules\Notification\Controllers;

use App\Controllers\BaseController;
use App\Services\NotificationService;

class NotificationController extends BaseController
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Display all notifications for the current user
     * 
     * @return string
     */
    public function index(): string
    {
        $user = auth()->user();
        $userRoles = $user->getGroups();

        // Get all notifications (read and unread) for the user
        $notifications = $this->notificationService->getUnreadForUser($user->id, $userRoles, 50);

        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'user' => $user,
            'menuItems' => $this->loadModuleMenus(),
        ];

        return view('Modules\Notification\Views\index', $data);
    }

    /**
     * Load module menus for the dashboard
     * 
     * @return array
     */
    protected function loadModuleMenus(): array
    {
        $menuPath = APPPATH . 'Modules/Notification/Config/Menu.php';
        if (file_exists($menuPath)) {
            return include $menuPath;
        }
        return [];
    }
}