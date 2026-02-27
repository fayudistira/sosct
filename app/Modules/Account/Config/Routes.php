<?php

$routes->group('account', ['filter' => 'session', 'namespace' => 'Modules\Account\Controllers'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('create', 'ProfileController::create');
    $routes->post('store', 'ProfileController::store');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('update', 'ProfileController::update');
});

// Profile API Routes - Protected with token authentication
$routes->group('api/profiles', ['filter' => 'tokens', 'namespace' => 'Modules\Account\Controllers\Api'], function($routes) {
    // CRUD operations
    $routes->get('/', 'ProfileApiController::index');
    $routes->post('/', 'ProfileApiController::create');
    $routes->get('search', 'ProfileApiController::search');
    $routes->get('(:segment)', 'ProfileApiController::show/$1');
    $routes->put('(:segment)', 'ProfileApiController::update/$1');
    $routes->delete('(:segment)', 'ProfileApiController::delete/$1');
    
    // Current user profile
    $routes->get('me', 'ProfileApiController::me');
    $routes->put('me', 'ProfileApiController::updateMe');
});
