<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Student Web Routes
$routes->group('student', ['namespace' => 'Modules\Student\Controllers', 'filter' => 'permission:student.manage'], function ($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('promote', 'StudentController::promoteForm');
    $routes->post('do-promote', 'StudentController::doPromote');
    $routes->get('view/(:num)', 'StudentController::show/$1');
    $routes->get('edit/(:num)', 'StudentController::edit/$1');
    $routes->post('update/(:num)', 'StudentController::update/$1');
});

// Student API Routes - Protected with token authentication
$routes->group('api/students', ['filter' => 'tokens', 'namespace' => 'Modules\Student\Controllers\Api'], function ($routes) {
    // CRUD operations
    $routes->get('/', 'StudentApiController::index');
    $routes->get('me', 'StudentApiController::me');
    $routes->get('search', 'StudentApiController::search');
    $routes->get('statistics', 'StudentApiController::statistics');
    $routes->get('(:segment)', 'StudentApiController::show/$1');
    $routes->get('number/(:segment)', 'StudentApiController::showByNumber/$1');
    $routes->put('(:segment)', 'StudentApiController::update/$1');
    $routes->delete('(:segment)', 'StudentApiController::delete/$1');
    
    // Student relationships
    $routes->get('(:segment)/payments', 'StudentApiController::payments/$1');
    $routes->get('(:segment)/invoices', 'StudentApiController::invoices/$1');
    $routes->get('(:segment)/installments', 'StudentApiController::installments/$1');
});
