<?php

$routes->group('account', ['filter' => 'session', 'namespace' => 'Modules\Account\Controllers'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('create', 'ProfileController::create');
    $routes->post('store', 'ProfileController::store');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('update', 'ProfileController::update');
});
