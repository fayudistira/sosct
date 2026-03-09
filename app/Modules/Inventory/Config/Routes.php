<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// =============================================
// Web Routes (Session-based authentication)
// =============================================

// Inventory Dashboard
$routes->group('inventory', ['namespace' => 'Modules\Inventory\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'InventoryController::index');
    
    // Items
    $routes->get('items', 'ItemController::index');
    $routes->get('items/create', 'ItemController::create');
    $routes->post('items/store', 'ItemController::store');
    $routes->get('items/edit/(:segment)', 'ItemController::edit/$1');
    $routes->post('items/update/(:segment)', 'ItemController::update/$1');
    $routes->get('items/view/(:segment)', 'ItemController::view/$1');
    $routes->get('items/barcode/(:segment)', 'ItemController::barcode/$1');
    $routes->post('items/delete/(:segment)', 'ItemController::delete/$1');
    $routes->get('items/search', 'ItemController::search');
    
    // Categories
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/create', 'CategoryController::create');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->get('categories/edit/(:segment)', 'CategoryController::edit/$1');
    $routes->post('categories/update/(:segment)', 'CategoryController::update/$1');
    $routes->post('categories/delete/(:segment)', 'CategoryController::delete/$1');
    
    // Locations
    $routes->get('locations', 'LocationController::index');
    $routes->get('locations/create', 'LocationController::create');
    $routes->post('locations/store', 'LocationController::store');
    $routes->get('locations/edit/(:segment)', 'LocationController::edit/$1');
    $routes->post('locations/update/(:segment)', 'LocationController::update/$1');
    $routes->post('locations/delete/(:segment)', 'LocationController::delete/$1');
    
    // Movements
    $routes->get('movements', 'MovementController::index');
    $routes->get('movements/create', 'MovementController::create');
    $routes->post('movements/store', 'MovementController::store');
    $routes->get('movements/item/(:segment)', 'MovementController::itemMovements/$1');
    $routes->get('movements/report', 'MovementController::report');
    
    // Stock Opname
    $routes->get('stock-opname', 'StockOpnameController::index');
    $routes->get('stock-opname/create', 'StockOpnameController::create');
    $routes->post('stock-opname/store', 'StockOpnameController::store');
    $routes->get('stock-opname/detail/(:segment)', 'StockOpnameController::detail/$1');
    $routes->post('stock-opname/update-detail/(:segment)', 'StockOpnameController::updateDetail/$1');
    $routes->post('stock-opname/complete/(:segment)', 'StockOpnameController::complete/$1');
    $routes->post('stock-opname/cancel/(:segment)', 'StockOpnameController::cancel/$1');
    
    // Alerts
    $routes->get('alerts', 'AlertController::index');
    $routes->post('alerts/resolve/(:segment)', 'AlertController::resolve/$1');
    
    // Reports
    $routes->get('reports/summary', 'ReportController::summary');
    $routes->get('reports/valuation', 'ReportController::valuation');
    $routes->get('reports/movement', 'ReportController::movement');
});

// =============================================
// API Routes (Token-based authentication)
// =============================================

$routes->group('api/inventory', ['filter' => 'tokens', 'namespace' => 'Modules\Inventory\Controllers\Api'], function ($routes) {
    
    // Items API
    $routes->get('items', 'ItemApiController::index');
    $routes->post('items', 'ItemApiController::create');
    $routes->get('items/(:segment)', 'ItemApiController::show/$1');
    $routes->put('items/(:segment)', 'ItemApiController::update/$1');
    $routes->delete('items/(:segment)', 'ItemApiController::delete/$1');
    $routes->get('items/search', 'ItemApiController::search');
    $routes->get('items/barcode/(:segment)', 'ItemApiController::barcode/$1');
    $routes->get('items/low-stock', 'ItemApiController::getLowStock');
    
    // Categories API
    $routes->get('categories', 'CategoryApiController::index');
    $routes->post('categories', 'CategoryApiController::create');
    $routes->get('categories/(:segment)', 'CategoryApiController::show/$1');
    $routes->put('categories/(:segment)', 'CategoryApiController::update/$1');
    $routes->delete('categories/(:segment)', 'CategoryApiController::delete/$1');
    $routes->get('categories/tree', 'CategoryApiController::tree');
    
    // Locations API
    $routes->get('locations', 'LocationApiController::index');
    $routes->post('locations', 'LocationApiController::create');
    $routes->get('locations/(:segment)', 'LocationApiController::show/$1');
    $routes->put('locations/(:segment)', 'LocationApiController::update/$1');
    $routes->delete('locations/(:segment)', 'LocationApiController::delete/$1');
    
    // Movements API
    $routes->get('movements', 'MovementApiController::index');
    $routes->post('movements', 'MovementApiController::create');
    $routes->get('movements/(:segment)', 'MovementApiController::show/$1');
    $routes->get('movements/item/(:segment)', 'MovementApiController::byItem/$1');
    $routes->get('movements/summary', 'MovementApiController::summary');
    
    // Stock Opname API
    $routes->get('stock-opname', 'StockOpnameApiController::index');
    $routes->post('stock-opname', 'StockOpnameApiController::create');
    $routes->get('stock-opname/(:segment)', 'StockOpnameApiController::show/$1');
    $routes->put('stock-opname/(:segment)', 'StockOpnameApiController::update/$1');
    $routes->post('stock-opname/(:segment)/complete', 'StockOpnameApiController::complete/$1');
    $routes->post('stock-opname/(:segment)/details', 'StockOpnameApiController::addDetail/$1');
    $routes->put('stock-opname/(:segment)/details/(:segment)', 'StockOpnameApiController::updateDetail/$1/$2');
    
    // Alerts API
    $routes->get('alerts', 'AlertApiController::index');
    $routes->get('alerts/active', 'AlertApiController::active');
    $routes->put('alerts/(:segment)/resolve', 'AlertApiController::resolve/$1');
    
    // Reports API
    $routes->get('reports/summary', 'ReportApiController::summary');
    $routes->get('reports/valuation', 'ReportApiController::valuation');
    $routes->get('reports/movement', 'ReportApiController::movement');
});
