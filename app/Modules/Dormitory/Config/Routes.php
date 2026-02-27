<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Admin Dormitory Routes (Protected)
$routes->group('dormitory', ['namespace' => 'Modules\Dormitory\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'DormitoryController::index', ['filter' => 'permission:dormitory.view,dormitory.manage']);
    $routes->get('create', 'DormitoryController::create', ['filter' => 'permission:dormitory.manage']);
    $routes->post('store', 'DormitoryController::store', ['filter' => 'permission:dormitory.manage']);
    $routes->get('show/(:segment)', 'DormitoryController::show/$1', ['filter' => 'permission:dormitory.view,dormitory.manage']);
    $routes->get('edit/(:segment)', 'DormitoryController::edit/$1', ['filter' => 'permission:dormitory.manage']);
    $routes->post('update/(:segment)', 'DormitoryController::update/$1', ['filter' => 'permission:dormitory.manage']);
    $routes->post('delete/(:segment)', 'DormitoryController::delete/$1', ['filter' => 'permission:dormitory.manage']);
    
    // Bulk upload routes
    $routes->get('download-template', 'DormitoryController::downloadTemplate');
    $routes->post('bulk-upload', 'DormitoryController::bulkUpload', ['filter' => 'permission:dormitory.manage']);
    
    // Assignment routes
    $routes->get('assignments/(:segment)', 'DormitoryController::assignments/$1', ['filter' => 'permission:dormitory.assign']);
    $routes->post('assign/(:segment)', 'DormitoryController::assign/$1', ['filter' => 'permission:dormitory.assign']);
    $routes->post('unassign/(:num)', 'DormitoryController::unassign/$1', ['filter' => 'permission:dormitory.assign']);
    
    // Student search routes
    $routes->get('search', 'DormitoryController::searchStudent', ['filter' => 'permission:dormitory.view,dormitory.manage']);
    $routes->get('student/(:num)', 'DormitoryController::studentAssignment/$1', ['filter' => 'permission:dormitory.view,dormitory.manage']);
});

// Public Dormitory Routes (Frontend)
$routes->group('', ['namespace' => 'Modules\Dormitory\Controllers'], function ($routes) {
    $routes->get('dormitories', 'DormitoryFrontendController::index');
    $routes->get('dormitories/(:segment)', 'DormitoryFrontendController::show/$1');
});

// API Routes - Protected with token authentication
$routes->group('api/dormitories', ['filter' => 'tokens', 'namespace' => 'Modules\Dormitory\Controllers\Api'], function ($routes) {
    $routes->get('/', 'DormitoryApiController::index');
    $routes->get('available', 'DormitoryApiController::available');
    $routes->get('(:segment)', 'DormitoryApiController::show/$1');
    $routes->post('/', 'DormitoryApiController::create');
    $routes->put('(:segment)', 'DormitoryApiController::update/$1');
    $routes->delete('(:segment)', 'DormitoryApiController::delete/$1');
    $routes->post('(:segment)/assign', 'DormitoryApiController::assign/$1');
    $routes->post('(:segment)/unassign', 'DormitoryApiController::unassign/$1');
});
