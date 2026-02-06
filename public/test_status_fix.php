<?php

/**
 * Test invoice creation after status fix
 */

use Modules\Payment\Models\InvoiceModel;
use Modules\Program\Models\ProgramModel;

require '../vendor/autoload.php';
require '../preload.php';

echo "<h2>Invoice Status Validation Test</h2>";
echo "<hr>";

try {
    $invoiceModel = new InvoiceModel();
    $programModel = new ProgramModel();

    // Get a program with fees
    $program = $programModel->where('registration_fee >', 0)->first();

    if (!$program) {
        echo "<p style='color:red;'>No programs found with registration_fee > 0</p>";
        exit;
    }

    echo "<h3>Step 1: Check Program Fees</h3>";
    echo "<p>Program: <strong>" . esc($program['title']) . "</strong></p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><td>Registration Fee:</td><td>" . number_format($program['registration_fee'] ?? 0, 2) . "</td></tr>";
    echo "<tr><td>Tuition Fee:</td><td>" . number_format($program['tuition_fee'] ?? 0, 2) . "</td></tr>";
    echo "<tr><td>Discount:</td><td>" . ($program['discount'] ?? 0) . "%</td></tr>";

    $discount = $program['discount'] ?? 0;
    $finalTuition = ($program['tuition_fee'] ?? 0) * (1 - $discount / 100);
    $totalAmount = ($program['registration_fee'] ?? 0) + $finalTuition;

    echo "<tr><td><strong>Total Amount:</strong></td><td><strong>" . number_format($totalAmount, 2) . "</strong></td></tr>";
    echo "</table>";

    echo "<h3>Step 2: Create Test Invoice</h3>";

    // Create test invoice with OUTSTANDING status (correct value)
    $invoiceData = [
        'registration_number' => 'REG-TESTFIX-' . uniqid(),
        'description' => 'Test Invoice - Status Fix Validation for ' . $program['title'],
        'amount' => 500000.00,
        'due_date' => date('Y-m-d', strtotime('+3 days')),
        'invoice_type' => 'tuition_fee',
        'status' => 'outstanding'  // CORRECT VALUE
    ];

    echo "<p>Creating invoice with status = '<strong>outstanding</strong>'...</p>";

    $invoiceId = $invoiceModel->createInvoice($invoiceData);

    if ($invoiceId) {
        echo "<h3 style='color:green;'>✓ SUCCESS!</h3>";
        echo "<p>Invoice created with ID: <strong>" . $invoiceId . "</strong></p>";

        // Verify
        $invoice = $invoiceModel->find($invoiceId);
        echo "<h4>Verification:</h4>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><td>Invoice ID:</td><td>" . $invoice['id'] . "</td></tr>";
        echo "<tr><td>Invoice Number:</td><td>" . $invoice['invoice_number'] . "</td></tr>";
        echo "<tr><td>Registration #:</td><td>" . $invoice['registration_number'] . "</td></tr>";
        echo "<tr><td>Amount:</td><td>" . number_format($invoice['amount'], 2) . "</td></tr>";
        echo "<tr><td>Status:</td><td><span style='color:green;'>" . $invoice['status'] . "</span></td></tr>";
        echo "<tr><td>Due Date:</td><td>" . $invoice['due_date'] . "</td></tr>";
        echo "</table>";

        echo "<h3 style='color:green;'>✓ INVOICE STATUS NOW WORKS!</h3>";
        echo "<p>Invoices will now be created successfully when admissions are submitted.</p>";
    } else {
        echo "<h3 style='color:red;'>✗ FAILED!</h3>";
        echo "<p>Error: " . json_encode($invoiceModel->errors()) . "</p>";
    }
} catch (Exception $e) {
    echo "<h3 style='color:red;'>EXCEPTION:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
