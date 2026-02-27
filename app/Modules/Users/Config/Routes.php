<?php

$routes->group('users', ['filter' => 'session', 'namespace' => 'Modules\Users\Controllers'], function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('toggle-status/(:num)', 'UserController::toggleStatus/$1');
});

// User API Routes - Protected with token authentication
$routes->group('api/users', ['filter' => 'tokens', 'namespace' => 'Modules\Users\Controllers\Api'], function($routes) {
    // CRUD operations
    $routes->get('/', 'UserApiController::index');
    $routes->post('/', 'UserApiController::create');
    $routes->get('statistics', 'UserApiController::statistics');
    $routes->get('(:segment)', 'UserApiController::show/$1');
    $routes->put('(:segment)', 'UserApiController::update/$1');
    $routes->delete('(:segment)', 'UserApiController::delete/$1');
    
    // User management
    $routes->put('(:segment)/activate', 'UserApiController::activate/$1');
    $routes->put('(:segment)/deactivate', 'UserApiController::deactivate/$1');
    $routes->post('(:segment)/assign-group', 'UserApiController::assignGroup/$1');
    $routes->post('(:segment)/remove-group', 'UserApiController::removeGroup/$1');
});
