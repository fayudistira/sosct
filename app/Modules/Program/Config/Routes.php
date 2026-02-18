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
    
    // Bulk upload routes
    $routes->get('download-template', 'ProgramController::downloadTemplate');
    $routes->post('bulk-upload', 'ProgramController::bulkUpload', ['filter' => 'permission:program.manage']);
});

// Program API Routes
$routes->group('api/programs', ['namespace' => 'Modules\Program\Controllers\Api', 'filter' => 'session'], function($routes) {
    // CRUD operations
    $routes->get('/', 'ProgramApiController::index');
    $routes->get('(:segment)', 'ProgramApiController::show/$1');
    $routes->post('/', 'ProgramApiController::create');
    $routes->put('(:segment)', 'ProgramApiController::update/$1');
    $routes->delete('(:segment)', 'ProgramApiController::delete/$1');
    
    // Search and filter
    $routes->get('search', 'ProgramApiController::search');
    $routes->get('filter', 'ProgramApiController::filterByStatus');
    $routes->get('filter/category', 'ProgramApiController::filterByCategory');
    
    // Special endpoints
    $routes->get('active', 'ProgramApiController::active');
    $routes->get('categories', 'ProgramApiController::categories');
});