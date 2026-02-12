<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('classroom', ['namespace' => 'Modules\Classroom\Controllers'], function ($routes) {
    $routes->get('/', 'ClassroomController::index');
    $routes->get('create', 'ClassroomController::create');
    $routes->post('store', 'ClassroomController::store');
    $routes->get('show/(:num)', 'ClassroomController::show/$1');
    $routes->get('edit/(:num)', 'ClassroomController::edit/$1');
    $routes->post('update/(:num)', 'ClassroomController::update/$1');
    $routes->post('delete/(:num)', 'ClassroomController::delete/$1');
});

// Student Classroom Routes (requires authentication and student role)
$routes->group('my/class', ['namespace' => 'Modules\Classroom\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'StudentClassroomController::myClass');
    $routes->get('summary', 'StudentClassroomController::myClassSummary');
});
