<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('tools', ['namespace' => 'Modules\Tools\Controllers'], function($routes) {
    $routes->get('/', 'ToolsController::index');
    $routes->get('api-tester', 'ToolsController::apiTester');
});
