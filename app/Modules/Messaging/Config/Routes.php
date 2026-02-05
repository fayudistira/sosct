<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
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
