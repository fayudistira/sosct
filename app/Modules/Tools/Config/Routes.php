<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('tools', ['namespace' => 'Modules\Tools\Controllers', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'ToolsController::index');
    $routes->get('qrgen', 'ToolsController::qrgen');
    $routes->get('imager', 'ToolsController::imager');
    
    // Hanzi Flashcard Routes
    $routes->group('hanzi', ['namespace' => 'Modules\Tools\Hanzi\Controllers'], function($routes) {
        $routes->get('/', 'HanziController::index');
        $routes->get('create', 'HanziController::create');
        $routes->post('store', 'HanziController::store');
        $routes->get('edit/(:num)', 'HanziController::edit/$1');
        $routes->post('update/(:num)', 'HanziController::update/$1');
        $routes->get('delete/(:num)', 'HanziController::delete/$1');
        $routes->get('bulk-upload', 'HanziController::bulkUpload');
        $routes->post('bulk-upload/process', 'HanziController::processBulkUpload');
        $routes->get('flashcards', 'HanziController::flashcards');
        
        // API Routes
        $routes->get('api', 'HanziController::apiIndex');
        $routes->get('api/flashcards', 'HanziController::apiGetFlashcards');
        $routes->delete('api/(:num)', 'HanziController::apiDelete/$1');
    });
});