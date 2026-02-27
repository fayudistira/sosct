<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Dashboard Web Routes
$routes->group('dashboard', ['namespace' => 'Modules\Dashboard\Controllers', 'filter' => 'session'], function($routes) {
    $routes->get('/', 'DashboardController::index');
});

// Dashboard API Routes - Protected with token authentication
$routes->group('api/dashboard', ['filter' => 'tokens', 'namespace' => 'Modules\Dashboard\Controllers\Api'], function($routes) {
    $routes->get('stats', 'DashboardApiController::stats');
    $routes->get('overview', 'DashboardApiController::overview');
    $routes->get('recent-admissions', 'DashboardApiController::recentAdmissions');
    $routes->get('recent-payments', 'DashboardApiController::recentPayments');
    $routes->get('overdue-invoices', 'DashboardApiController::overdueInvoices');
    $routes->get('revenue-chart', 'DashboardApiController::revenueChart');
    $routes->get('admissions-chart', 'DashboardApiController::admissionsChart');
});
