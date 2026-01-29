<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Auto-Load Modules' Routes
$modulesPath = APPPATH . 'Modules/';
if (is_dir($modulesPath)) {
    foreach (scandir($modulesPath) as $module) {
        if ($module === '.' || $module === '..') {
            continue;
        }
        $routesPath = $modulesPath . $module . '/Config/Routes.php';
        if (file_exists($routesPath)) {
            include $routesPath;
        }
    }
}
