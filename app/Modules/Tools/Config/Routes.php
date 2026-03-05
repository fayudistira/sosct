<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('tools', ['namespace' => 'Modules\Tools\Controllers'], function($routes) {
    $routes->get('/', 'ToolsController::index');
    $routes->get('api-tester', 'ToolsController::apiTester');
    $routes->get('qrgen', 'ToolsController::qrgen');
    $routes->get('imager', 'ToolsController::imager');
    // $routes->get('hanzi', 'ToolsController::hanzi');
});

// Hanzi submodule routes
$routes->group('tools/hanzi', ['namespace' => 'Modules\Tools\Hanzi\Controllers'], function($routes) {
    $routes->get('/', 'HanziController::index');
    $routes->get('create', 'HanziController::create');
    $routes->post('create', 'HanziController::create');
    $routes->get('edit/(:num)', 'HanziController::edit/$1');
    $routes->post('edit/(:num)', 'HanziController::edit/$1');
    $routes->get('flashcards', 'HanziController::flashcards');
    $routes->get('bulk-upload', 'HanziController::bulkUpload');
    $routes->post('bulk-upload', 'HanziController::bulkUpload');
    $routes->delete('(:num)', 'HanziController::delete/$1');
});
