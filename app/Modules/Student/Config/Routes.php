<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('student', ['namespace' => 'Modules\Student\Controllers', 'filter' => 'permission:student.manage'], function($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('view/(:num)', 'StudentController::show/$1');
});
