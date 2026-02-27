<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Messaging Web Routes
$routes->group('messages', ['namespace' => 'Modules\Messaging\Controllers', 'filter' => 'session'], function($routes) {
    // Pages
    $routes->get('/', 'MessagingController::index');
    $routes->get('conversation/(:segment)', 'MessagingController::conversation/$1');
    $routes->post('create', 'MessagingController::createConversation');
    $routes->post('send', 'MessagingController::sendMessage');
    
    // API Endpoints for AJAX
    $routes->get('api/conversations', 'MessagingController::apiGetConversations');
    $routes->get('api/messages/(:segment)', 'MessagingController::apiGetMessages/$1');
    $routes->post('api/mark-read', 'MessagingController::apiMarkRead');
    $routes->get('api/users/search', 'MessagingController::apiSearchUsers');
    $routes->get('api/unread-count', 'MessagingController::apiGetUnreadCount');
});

// Messaging API Routes - Protected with token authentication
$routes->group('api/messages', ['filter' => 'tokens', 'namespace' => 'Modules\Messaging\Controllers\Api'], function($routes) {
    // Conversations
    $routes->get('conversations', 'MessagingApiController::conversations');
    $routes->post('conversations', 'MessagingApiController::createConversation');
    $routes->get('conversations/(:segment)', 'MessagingApiController::conversation/$1');
    $routes->post('conversations/(:segment)/participants', 'MessagingApiController::addParticipant/$1');
    
    // Messages
    $routes->post('(:segment)', 'MessagingApiController::sendMessage/$1');
    $routes->post('(:segment)/read', 'MessagingApiController::markAsRead/$1');
    
    // Unread
    $routes->get('unread', 'MessagingApiController::unreadCount');
});

// Notification Page Routes
$routes->group('notifications', ['namespace' => 'Modules\Notification\Controllers'], function($routes) {
    // View all notifications page
    $routes->get('/', 'NotificationController::index');
});

// Notification API Routes - Protected with token authentication
$routes->group('notifications/api', ['filter' => 'tokens', 'namespace' => 'Modules\Notification\Controllers\Api'], function($routes) {
    // Get unread notification count
    $routes->get('unread-count', 'NotificationApiController::unreadCount');
    
    // Get notification list
    $routes->get('list', 'NotificationApiController::list');
    
    // Mark a notification as read
    $routes->post('mark-read/(:num)', 'NotificationApiController::markRead/$1');
    
    // Mark all notifications as read
    $routes->post('mark-all-read', 'NotificationApiController::markAllRead');
});
