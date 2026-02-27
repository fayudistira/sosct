<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('tools/api-tester', 'Tools\ToolsController::apiTester');
$routes->get('tools', 'Tools\ToolsController::index');
