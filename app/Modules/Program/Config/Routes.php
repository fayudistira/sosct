<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('program', ['namespace' => 'Modules\Program\Controllers', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'ProgramController::index', ['filter' => 'permission:program.view,program.manage']);
    $routes->get('view/(:segment)', 'ProgramController::view/$1', ['filter' => 'permission:program.view,program.manage']);
    $routes->get('create', 'ProgramController::create', ['filter' => 'permission:program.manage']);
    $routes->post('store', 'ProgramController::store', ['filter' => 'permission:program.manage']);
    $routes->get('edit/(:segment)', 'ProgramController::edit/$1', ['filter' => 'permission:program.manage']);
    $routes->post('update/(:segment)', 'ProgramController::update/$1', ['filter' => 'permission:program.manage']);
    $routes->get('delete/(:segment)', 'ProgramController::delete/$1', ['filter' => 'permission:program.manage']);
    
    // Bulk upload routes
    $routes->get('download-template', 'ProgramController::downloadTemplate');
    $routes->post('bulk-upload', 'ProgramController::bulkUpload', ['filter' => 'permission:program.manage']);
});

// Program API Routes - Protected with token authentication
$routes->group('api/programs', ['filter' => 'tokens', 'namespace' => 'Modules\Program\Controllers\Api'], function($routes) {
    // CRUD operations
    $routes->get('/', 'ProgramApiController::index');
    $routes->get('(:segment)', 'ProgramApiController::show/$1');
    $routes->post('/', 'ProgramApiController::create');
    $routes->put('(:segment)', 'ProgramApiController::update/$1');
    $routes->delete('(:segment)', 'ProgramApiController::delete/$1');
    
    // Sort order update (superadmin only)
    $routes->put('(:segment)/sort-order', 'ProgramApiController::updateSortOrder/$1');
    
    // Search and filter
    $routes->get('search', 'ProgramApiController::search');
    $routes->get('filter', 'ProgramApiController::filterByStatus');
    $routes->get('filter/category', 'ProgramApiController::filterByCategory');
    
    // Special endpoints
    $routes->get('active', 'ProgramApiController::active');
    $routes->get('categories', 'ProgramApiController::categories');
    $routes->get('languages', 'ProgramApiController::languages');
    $routes->get('language-levels', 'ProgramApiController::languageLevels');
    $routes->get('by-language', 'ProgramApiController::byLanguage');
    $routes->get('filter/language', 'ProgramApiController::filterByLanguage');
    $routes->get('filter/language-level', 'ProgramApiController::filterByLanguageLevel');
});