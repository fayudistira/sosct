<?php

/**
 * Direct invoice creation test
 * Checks if invoices are being created properly with validation
 */

use Modules\Payment\Models\InvoiceModel;

require '../vendor/autoload.php';
require '../preload.php';

echo "<h2>Direct Invoice Creation Test</h2>";
echo "<hr>";

try {
    $invoiceModel = new InvoiceModel();

    // Test data with all required fields
    $testInvoiceData = [
        'registration_number' => 'REG-TEST-' . uniqid(),
        'description' => 'Test Invoice - This is a test description with proper length for testing invoice creation functionality',
        'amount' => 500000.00,
        'due_date' => date('Y-m-d', strtotime('+3 days')),
        'invoice_type' => 'tuition_fee',
        'status' => 'outstanding',
        'items' => json_encode([
            ['description' => 'Registration Fee', 'amount' => 500000.00]
        ])
    ];

    echo "<h3>Test Invoice Data:</h3>";
    echo "<pre>";
    foreach ($testInvoiceData as $key => $value) {
        if (in_array($key, ['amount'])) {
            echo $key . ": " . number_format($value, 2) . "\n";
        } else {
            echo $key . ": " . $value . "\n";
        }
    }
    echo "</pre>";

    // Attempt to create invoice
    echo "<h3>Creating Invoice...</h3>";
    $invoiceId = $invoiceModel->createInvoice($testInvoiceData);

    if ($invoiceId) {
        echo "<h3 style='color:green;'>✓ SUCCESS!</h3>";
        echo "<p>Invoice created with ID: <strong>" . $invoiceId . "</strong></p>";

        // Retrieve and display the created invoice
        $invoice = $invoiceModel->find($invoiceId);
        if ($invoice) {
            echo "<h4>Created Invoice Details:</h4>";
            echo "<table border='1' cellpadding='10'>";
            foreach ($invoice as $key => $value) {
                echo "<tr><td><strong>" . $key . "</strong>:</td><td>" . esc($value) . "</td></tr>";
            }
            echo "</table>";

            // Verify soft deletes are handled correctly
            echo "<h4>Soft Delete Status:</h4>";
            echo "deleted_at is NULL: " . (empty($invoice['deleted_at']) ? "✓ YES" : "✗ NO (deleted_at: " . $invoice['deleted_at'] . ")") . "<br>";
        }
    } else {
        echo "<h3 style='color:red;'>✗ FAILED!</h3>";
        echo "<p>Invoice creation failed.</p>";

        $errors = $invoiceModel->errors();
        if (!empty($errors)) {
            echo "<h4>Validation Errors:</h4>";
            echo "<pre>";
            print_r($errors);
            echo "</pre>";
        }

        // Check if model has validation rules issues
        echo "<h4>Model Validation Rules:</h4>";
        echo "<pre>";
        var_dump($invoiceModel->validationRules);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<h3 style='color:red;'>EXCEPTION:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
