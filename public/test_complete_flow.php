<?php

/**
 * End-to-end test of admission submission with invoice creation
 */

use Modules\Account\Models\ProfileModel;
use Modules\Admission\Models\AdmissionModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;

require '../vendor/autoload.php';
require '../preload.php';

echo "<h2>Complete Admission Flow Test</h2>";
echo "<hr>";

try {
    $profileModel = new ProfileModel();
    $admissionModel = new AdmissionModel();
    $programModel = new ProgramModel();
    $invoiceModel = new InvoiceModel();

    // Get a program with fees
    $program = $programModel->where('registration_fee >', 0)->first();
    if (!$program) {
        echo "<p style='color:red;'>No programs found</p>";
        exit;
    }

    echo "<h3>1. Creating Profile</h3>";

    // Create profile
    $profileData = [
        'profile_number' => $profileModel->generateProfileNumber(),
        'full_name' => 'Test User ' . uniqid(),
        'email' => 'test' . uniqid() . '@test.com',
        'phone' => '08123456789',
        'gender' => 'Male',
        'date_of_birth' => '2000-01-01',
        'place_of_birth' => 'Test City',
        'religion' => 'Islam',
        'street_address' => 'Test Address',
        'district' => 'Test District',
        'regency' => 'Test Regency',
        'province' => 'Test Province',
        'emergency_contact_name' => 'Test Emergency',
        'emergency_contact_phone' => '08123456789',
        'emergency_contact_relation' => 'Parent',
        'father_name' => 'Test Father',
        'mother_name' => 'Test Mother',
    ];

    $profileId = $profileModel->insert($profileData);
    echo "✓ Profile created: ID <strong>" . $profileId . "</strong><br>";
    echo "  Profile #: " . $profileData['profile_number'] . "<br>";

    echo "<h3>2. Creating Admission (PENDING status)</h3>";

    // Create admission with PENDING status
    $admissionData = [
        'registration_number' => $admissionModel->generateRegistrationNumber(),
        'profile_id' => $profileId,
        'program_id' => $program['id'],
        'status' => 'pending',  // <-- PENDING, not approved
        'application_date' => date('Y-m-d'),
    ];

    $admissionId = $admissionModel->insert($admissionData);
    echo "✓ Admission created: ID <strong>" . $admissionId . "</strong><br>";
    echo "  Registration #: " . $admissionData['registration_number'] . "<br>";
    echo "  Status: <span style='color:orange;'>" . $admissionData['status'] . "</span><br>";
    echo "  Program: " . $program['title'] . "<br>";

    echo "<h3>3. Calculating Fees</h3>";

    $regFee = (float)($program['registration_fee'] ?? 0);
    $tuitionFee = (float)($program['tuition_fee'] ?? 0);
    $discount = (float)($program['discount'] ?? 0);
    $finalTuition = $tuitionFee * (1 - $discount / 100);
    $totalAmount = $regFee + $finalTuition;

    echo "<table border='1' cellpadding='10'>";
    echo "<tr><td>Registration Fee:</td><td>Rp " . number_format($regFee, 0, ',', '.') . "</td></tr>";
    echo "<tr><td>Tuition Fee:</td><td>Rp " . number_format($tuitionFee, 0, ',', '.') . "</td></tr>";
    echo "<tr><td>Discount:</td><td>" . $discount . "%</td></tr>";
    echo "<tr><td><strong>Total Amount:</strong></td><td><strong>Rp " . number_format($totalAmount, 0, ',', '.') . "</strong></td></tr>";
    echo "</table>";

    echo "<h3>4. Creating Invoice (with OUTSTANDING status)</h3>";

    if ($totalAmount > 0) {
        $invoiceData = [
            'registration_number' => $admissionData['registration_number'],
            'description' => 'Initial Fees: Registration and Tuition for ' . $program['title'],
            'amount' => $totalAmount,
            'due_date' => date('Y-m-d', strtotime('+3 days')),
            'invoice_type' => 'tuition_fee',
            'status' => 'outstanding'  // <-- CORRECTED STATUS
        ];

        $invoiceId = $invoiceModel->createInvoice($invoiceData);

        if ($invoiceId) {
            echo "✓ Invoice created successfully!<br>";
            echo "  Invoice ID: <strong>" . $invoiceId . "</strong><br>";

            // Verify
            $invoice = $invoiceModel->find($invoiceId);
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><td>Invoice #:</td><td>" . $invoice['invoice_number'] . "</td></tr>";
            echo "<tr><td>Registration #:</td><td>" . $invoice['registration_number'] . "</td></tr>";
            echo "<tr><td>Amount:</td><td>Rp " . number_format($invoice['amount'], 0, ',', '.') . "</td></tr>";
            echo "<tr><td>Status:</td><td><span style='color:green;'>" . $invoice['status'] . "</span></td></tr>";
            echo "<tr><td>Due Date:</td><td>" . $invoice['due_date'] . "</td></tr>";
            echo "</table>";

            echo "<h3 style='color:green;'>✓ COMPLETE SUCCESS!</h3>";
            echo "<p>Admission created with status <strong>PENDING</strong></p>";
            echo "<p>Invoice created with status <strong>OUTSTANDING</strong></p>";
            echo "<p><strong>This proves invoices can now be created for pending admissions!</strong></p>";
        } else {
            echo "✗ Invoice creation FAILED<br>";
            echo "<pre>" . json_encode($invoiceModel->errors(), JSON_PRETTY_PRINT) . "</pre>";
        }
    } else {
        echo "✗ Total amount is 0 - no invoice created<br>";
    }
} catch (Exception $e) {
    echo "<h3 style='color:red;'>ERROR:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
