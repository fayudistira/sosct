<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

$routes->group('', ['namespace' => 'Modules\Frontend\Controllers'], function ($routes) {
    $routes->get('/', 'PageController::index');
    $routes->get('about', 'PageController::about');
    $routes->get('contact', 'PageController::contact');

    // Programs routes
    $routes->get('programs', 'PageController::programs');
    $routes->get('programs/(:segment)', 'PageController::programDetail/$1');

    // Landing pages for language courses
    $routes->get('mandarin', 'PageController::mandarin');
    $routes->get('japanese', 'PageController::japanese');
    $routes->get('korean', 'PageController::korean');
    $routes->get('german', 'PageController::german');
    $routes->get('english', 'PageController::english');

    // Apply routes - IMPORTANT: Specific routes must come before wildcard routes
    $routes->get('apply', 'PageController::apply');
    $routes->post('apply/submit', 'PageController::submitApplication');
    $routes->get('apply/success', 'PageController::applySuccess');
    $routes->get('apply/(:segment)', 'PageController::applyWithProgram/$1'); // Must be last
});
