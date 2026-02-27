<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Web Routes (Staff Interface) - Protected by permission filter
$routes->group('admission', ['namespace' => 'Modules\Admission\Controllers', 'filter' => 'permission:admission.manage'], function($routes) {
    $routes->get('/', 'AdmissionController::index');
    $routes->get('view/(:num)', 'AdmissionController::view/$1');
    $routes->get('download/(:num)/(.+)', 'AdmissionController::downloadDocument/$1/$2');
    $routes->get('create', 'AdmissionController::create');
    $routes->post('store', 'AdmissionController::store');
    $routes->get('edit/(:num)', 'AdmissionController::edit/$1');
    $routes->post('update/(:num)', 'AdmissionController::update/$1');
    $routes->get('promote/(:num)', 'AdmissionController::promote/$1');
    $routes->post('process_promotion/(:num)', 'AdmissionController::processPromotion/$1');
    $routes->post('update-status', 'AdmissionController::updateStatus'); // AJAX endpoint
    $routes->delete('delete/(:num)', 'AdmissionController::delete/$1');
    $routes->get('search', 'AdmissionController::search');
    $routes->get('ajax-search', 'AdmissionController::ajaxSearch');
    
    // Program Switch Routes
    $routes->get('switch/(:num)', 'SwitchProgramController::index/$1');
    $routes->post('switch/(:num)', 'SwitchProgramController::switch/$1');
    $routes->get('switch-history/(:num)', 'SwitchProgramController::getHistory/$1');
});

// API Routes (RESTful) - Protected by session filter for AJAX calls
$routes->group('api/admissions', ['namespace' => 'Modules\Admission\Controllers\Api', 'filter' => 'tokens'], function($routes) {
    // CRUD operations (MUST be after specific routes)
    $routes->get('/', 'AdmissionApiController::index');
    $routes->post('/', 'AdmissionApiController::create');
    $routes->get('search', 'AdmissionApiController::search');
    $routes->get('filter', 'AdmissionApiController::filter');
    $routes->get('statistics', 'AdmissionApiController::statistics');
    $routes->get('(:segment)', 'AdmissionApiController::show/$1');
    $routes->put('(:segment)', 'AdmissionApiController::update/$1');
    $routes->delete('(:segment)', 'AdmissionApiController::delete/$1');
    
    // Action endpoints
    $routes->post('(:segment)/approve', 'AdmissionApiController::approve/$1');
    $routes->post('(:segment)/reject', 'AdmissionApiController::reject/$1');
    $routes->post('(:segment)/promote', 'AdmissionApiController::promote/$1');
});
