<?php

/**
 * Notification Module Routes
 * 
 * API routes for the notification system
 */

// Notification Page Routes
$routes->group('notifications', ['namespace' => 'Modules\Notification\Controllers'], function($routes) {
    // View all notifications page
    $routes->get('/', 'NotificationController::index');
});

// Notification API Routes
$routes->group('notifications/api', ['namespace' => 'Modules\Notification\Controllers\Api'], function($routes) {
    // Get unread notification count
    $routes->get('unread-count', 'NotificationApiController::unreadCount');
    
    // Get notification list
    $routes->get('list', 'NotificationApiController::list');
    
    // Mark a notification as read
    $routes->post('mark-read/(:num)', 'NotificationApiController::markRead/$1');
    
    // Mark all notifications as read
    $routes->post('mark-all-read', 'NotificationApiController::markAllRead');
});