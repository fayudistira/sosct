<?php

use CodeIgniter\Config\Services;

$routes = Services::routes();

// Public Invoice Routes (MUST be before grouped routes to avoid conflicts)
$routes->get('invoice/public/(:segment)', '\Modules\Payment\Controllers\InvoiceController::publicView/$1');
$routes->get('invoice/secure/(:any)', '\Modules\Payment\Controllers\InvoiceController::secureView/$1');
$routes->get('invoice/qr/(:segment)', '\Modules\Payment\Controllers\InvoiceController::generateQr/$1');

// Public Payment Routes (MUST be before grouped routes to avoid conflicts)
$routes->get('payment/public/(:segment)', '\Modules\Payment\Controllers\PaymentController::publicReceipt/$1');
$routes->get('payment/secure/(:any)', '\Modules\Payment\Controllers\PaymentController::secureReceipt/$1');
$routes->get('payment/qr/(:segment)', '\Modules\Payment\Controllers\PaymentController::generateQr/$1');

// Payment Web UI Routes
$routes->group('payment', ['namespace' => 'Modules\Payment\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'PaymentController::index');
    $routes->get('view/(:segment)', 'PaymentController::view/$1');
    $routes->get('create', 'PaymentController::create');
    $routes->post('store', 'PaymentController::store');
    $routes->get('edit/(:segment)', 'PaymentController::edit/$1');
    $routes->post('update/(:segment)', 'PaymentController::update/$1');
    $routes->get('receipt/(:segment)', 'PaymentController::receipt/$1');
});

// Payment Reports Routes
$routes->group('payment/reports', ['namespace' => 'Modules\Payment\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('revenue', 'PaymentController::revenueReport');
    $routes->get('overdue', 'PaymentController::overdueReport');
    $routes->get('export', 'PaymentController::exportCsv');
});

// Invoice Web UI Routes
$routes->group('invoice', ['namespace' => 'Modules\Payment\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'InvoiceController::index');
    $routes->get('view/(:segment)', 'InvoiceController::view/$1');
    $routes->get('create', 'InvoiceController::create');
    $routes->get('extend', 'InvoiceController::extend');
    $routes->get('bulk-extend', 'InvoiceController::bulkExtend');
    $routes->post('bulk-extend-store', 'InvoiceController::bulkExtendStore');
    $routes->post('store', 'InvoiceController::store');
    $routes->get('edit/(:segment)', 'InvoiceController::edit/$1');
    $routes->post('update/(:segment)', 'InvoiceController::update/$1');
    $routes->get('cancel/(:segment)', 'InvoiceController::cancel/$1');
    $routes->get('pdf/(:segment)', 'InvoiceController::downloadPdf/$1');
    $routes->get('reissue/(:segment)', 'InvoiceController::reissue/$1');
    $routes->post('process-reissue', 'InvoiceController::processReissue');
    $routes->get('student-invoices', 'InvoiceController::getStudentInvoices');
    $routes->get('invoice-summary', 'InvoiceController::getInvoiceSummary');
});

// Payment API Routes
$routes->group('api/payments', ['namespace' => 'Modules\Payment\Controllers\Api', 'filter' => 'session'], function ($routes) {
    // CRUD operations
    $routes->get('/', 'PaymentApiController::index');
    $routes->get('(:segment)', 'PaymentApiController::show/$1');
    $routes->post('/', 'PaymentApiController::create');
    $routes->put('(:segment)', 'PaymentApiController::update/$1');

    // Status update
    $routes->put('(:segment)/status', 'PaymentApiController::updateStatus/$1');

    // Search and filter
    $routes->get('search', 'PaymentApiController::search');
    $routes->get('filter/status', 'PaymentApiController::filterByStatus');
    $routes->get('filter/method', 'PaymentApiController::filterByMethod');
    $routes->get('filter/daterange', 'PaymentApiController::filterByDateRange');

    // Student payments
    $routes->get('student/(:segment)', 'PaymentApiController::getByStudent/$1');

    // Statistics
    $routes->get('statistics', 'PaymentApiController::statistics');

    // Receipt upload
    $routes->post('(:segment)/receipt', 'PaymentApiController::uploadReceipt/$1');

    // Add this line
    $routes->get('(:segment)/receipt', 'PaymentApiController::getReceipt/$1');
});

// Invoice API Routes
$routes->group('api/invoices', ['namespace' => 'Modules\Payment\Controllers\Api', 'filter' => 'session'], function ($routes) {
    // Search and filter (MUST be before CRUD routes with wildcards)
    $routes->get('search', 'InvoiceApiController::search');
    $routes->get('search-admissions', 'InvoiceApiController::searchAdmissions');
    $routes->get('filter/status', 'InvoiceApiController::filterByStatus');
    $routes->get('filter/type', 'InvoiceApiController::filterByType');

    // Student invoices
    $routes->get('student/(:segment)', 'InvoiceApiController::getByStudent/$1');

    // Overdue invoices
    $routes->get('overdue', 'InvoiceApiController::getOverdue');

    // CRUD operations (wildcard routes MUST be last)
    $routes->get('/', 'InvoiceApiController::index');
    $routes->post('/', 'InvoiceApiController::create');
    $routes->get('(:segment)', 'InvoiceApiController::show/$1');
    $routes->put('(:segment)', 'InvoiceApiController::update/$1');
    $routes->delete('(:segment)', 'InvoiceApiController::delete/$1');

    // PDF generation
    $routes->get('(:segment)/pdf', 'InvoiceApiController::generatePdf/$1');

    // Cancel invoice
    $routes->put('(:segment)/cancel', 'InvoiceApiController::cancel/$1');
});

// Student Payment Routes (requires authentication and student role)
$routes->group('my', ['namespace' => 'Modules\Payment\Controllers', 'filter' => 'session'], function ($routes) {
    // Invoices
    $routes->get('invoices', 'StudentPaymentController::myInvoices');
    $routes->get('invoices/(:num)', 'StudentPaymentController::myInvoiceDetail/$1');

    // Payments
    $routes->get('payments', 'StudentPaymentController::myPayments');
    $routes->get('payments/(:num)', 'StudentPaymentController::myPaymentDetail/$1');

    // API endpoints for dashboard widget
    $routes->get('invoice-summary', 'StudentPaymentController::myInvoiceSummary');
});

// Contract Routes
$routes->group('contract', ['namespace' => 'Modules\Payment\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('/', 'ContractController::index');
    $routes->get('view/(:segment)', 'ContractController::view/$1');
    $routes->get('print/(:segment)', 'ContractController::print/$1');
});
