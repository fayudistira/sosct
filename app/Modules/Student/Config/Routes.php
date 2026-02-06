<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('student', ['namespace' => 'Modules\Student\Controllers', 'filter' => 'permission:student.manage'], function ($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('promote', 'StudentController::promoteForm');
    $routes->post('do-promote', 'StudentController::doPromote');
    $routes->get('view/(:num)', 'StudentController::show/$1');
    $routes->get('edit/(:num)', 'StudentController::edit/$1');
    $routes->post('update/(:num)', 'StudentController::update/$1');
});
