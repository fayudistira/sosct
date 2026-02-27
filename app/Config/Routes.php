<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Default route is handled by Frontend module
// $routes->get('/', 'Home::index');

// Register Shield routes except login and register (we'll override those)
// Wrap in try-catch to prevent errors if Shield service isn't ready
try {
    if (function_exists('service')) {
        service('auth')->routes($routes, ['except' => ['login', 'register']]);
    }
} catch (\Throwable $e) {
    // Shield routes will be loaded by auto-discovery instead
}

// Custom login and register routes with session handling
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView', ['as' => 'login']);
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView', ['as' => 'register']);
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');

// API Routes - Public (no authentication required)
$routes->group('api/auth', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('login', 'AuthApiController::login');
    $routes->post('register', 'AuthApiController::register');
});

// API Routes - Protected (authentication required)
$routes->group('api/auth', ['filter' => 'tokens', 'namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('logout', 'AuthApiController::logout');
    $routes->get('me', 'AuthApiController::me');
    $routes->post('refresh', 'AuthApiController::refresh');
    $routes->post('change-password', 'AuthApiController::changePassword');
});

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

// Route to serve uploaded files - DISABLED (using junction/symlink instead for better performance)
// Files are now accessible via public/uploads which is a junction to writable/uploads
// $routes->get('writable/uploads/(:segment)/(:segment)/(:any)', 'FileController::serve/$1/$2/$3');
// $routes->get('writable/uploads/(:segment)/(:any)', 'FileController::serve/$1/$2');
// $routes->get('writable/uploads/(:any)', 'FileController::serve/$1');
