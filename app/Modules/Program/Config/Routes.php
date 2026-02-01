<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('program', ['namespace' => 'Modules\Program\Controllers', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'ProgramController::index', ['filter' => 'permission:program.view,program.manage']);
    $routes->get('view/(:segment)', 'ProgramController::view/$1', ['filter' => 'permission:program.view,program.manage']);
    $routes->get('create', 'ProgramController::create', ['filter' => 'permission:program.manage']);
    $routes->post('store', 'ProgramController::store', ['filter' => 'permission:program.manage']);
    $routes->get('edit/(:segment)', 'ProgramController::edit/$1', ['filter' => 'permission:program.manage']);
    $routes->post('update/(:segment)', 'ProgramController::update/$1', ['filter' => 'permission:program.manage']);
    $routes->get('delete/(:segment)', 'ProgramController::delete/$1', ['filter' => 'permission:program.manage']);
});