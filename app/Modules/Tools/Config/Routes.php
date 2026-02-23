<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('tools', ['namespace' => 'Modules\Tools\Controllers', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'ToolsController::index');
    $routes->get('qrgen', 'ToolsController::qrgen');
});