<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Web Routes (HR Management) - Protected by permission filter
$routes->group('admin/employee', ['namespace' => 'Modules\Employee\Controllers', 'filter' => 'group:admin,superadmin,frontline'], function($routes) {
    $routes->get('/', 'EmployeeController::index');
    $routes->get('view/(:num)', 'EmployeeController::show/$1');
    $routes->get('create', 'EmployeeController::create');
    $routes->post('store', 'EmployeeController::store');
    $routes->get('edit/(:num)', 'EmployeeController::edit/$1');
    $routes->post('update/(:num)', 'EmployeeController::update/$1');
    $routes->delete('delete/(:num)', 'EmployeeController::delete/$1');
});

// Employee API Routes
$routes->group('api/employees', ['namespace' => 'Modules\Employee\Controllers\Api', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'EmployeeApiController::index');
    $routes->get('(:segment)', 'EmployeeApiController::show/$1');
});
