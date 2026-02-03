<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Web Routes (Staff Interface) - Protected by permission filter
$routes->group('admission', ['namespace' => 'Modules\Admission\Controllers', 'filter' => 'permission:admission.manage'], function($routes) {
    $routes->get('/', 'AdmissionController::index');
    $routes->get('view/(:num)', 'AdmissionController::view/$1');
    $routes->get('download/(:num)/(:any)', 'AdmissionController::downloadDocument/$1/$2');
    $routes->get('create', 'AdmissionController::create');
    $routes->post('store', 'AdmissionController::store');
    $routes->get('edit/(:num)', 'AdmissionController::edit/$1');
    $routes->post('update/(:num)', 'AdmissionController::update/$1');
    $routes->post('update-status', 'AdmissionController::updateStatus'); // AJAX endpoint
    $routes->delete('delete/(:num)', 'AdmissionController::delete/$1');
    $routes->get('search', 'AdmissionController::search');
});

// API Routes (RESTful) - Protected by tokens filter
$routes->group('api', ['namespace' => 'Modules\Admission\Controllers\Api', 'filter' => 'tokens'], function($routes) {
    $routes->resource('admissions', [
        'controller' => 'AdmissionApiController',
        'only' => ['index', 'show', 'create', 'update', 'delete']
    ]);
    
    $routes->get('admissions/search', 'AdmissionApiController::search');
    $routes->get('admissions/filter', 'AdmissionApiController::filter');
});
