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
});

// API Routes (RESTful) - Protected by session filter for AJAX calls
$routes->group('api/admissions', ['namespace' => 'Modules\Admission\Controllers\Api', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'AdmissionApiController::index');
    $routes->get('(:segment)', 'AdmissionApiController::show/$1');
    $routes->get('search', 'AdmissionApiController::search');
    $routes->get('filter', 'AdmissionApiController::filter');
});
