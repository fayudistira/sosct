<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('settings', ['namespace' => 'Modules\Settings\Controllers'], function ($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->get('cleanup', 'SettingsController::cleanup');
    $routes->post('cleanup', 'SettingsController::doCleanup');
    $routes->get('test-data', 'SettingsController::testData');
    $routes->post('generate-test-data', 'SettingsController::generateTestData');
});
