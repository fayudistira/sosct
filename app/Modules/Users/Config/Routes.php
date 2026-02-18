<?php

$routes->group('users', ['filter' => 'session', 'namespace' => 'Modules\Users\Controllers'], function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('toggle-status/(:num)', 'UserController::toggleStatus/$1');
});

// User API Routes
$routes->group('api/users', ['filter' => 'session', 'namespace' => 'Modules\Users\Controllers\Api'], function($routes) {
    $routes->get('/', 'UserApiController::index');
});
