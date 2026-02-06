<?php
// Test file to simulate form submission and check invoice creation
use Modules\Frontend\Controllers\PageController;
use Modules\Admission\Models\AdmissionModel;
use Modules\Account\Models\ProfileModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;

require '../vendor/autoload.php';
require '../preload.php';

echo "<h2>Invoice Creation Test</h2>";

try {
    // Get a program with fees
    $programModel = new ProgramModel();
    $program = $programModel->where('registration_fee >', 0)->first();

    if (!$program) {
        echo "No programs found with registration_fee > 0";
        exit;
    }

    echo "<h3>Selected Program:</h3>";
    echo "<pre>";
    echo "Title: " . $program['title'] . "\n";
    echo "RegFee: " . $program['registration_fee'] . "\n";
    echo "TuitionFee: " . $program['tuition_fee'] . "\n";
    echo "Discount: " . $program['discount'] . "\n";
    echo "</pre>";

    // Create-profile
    $profileModel = new ProfileModel();
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
    echo "<h3>Profile Created:</h3>";
    echo "Profile ID: " . $profileId . "\n";

    // Create admission
    $admissionModel = new AdmissionModel();
    $admissionData = [
        'registration_number' => $admissionModel->generateRegistrationNumber(),
        'profile_id' => $profileId,
        'program_id' => $program['id'],
        'status' => 'pending',
        'application_date' => date('Y-m-d'),
    ];

    $admissionId = $admissionModel->insert($admissionData);
    echo "<h3>Admission Created:</h3>";
    echo "Admission ID: " . $admissionId . "\n";
    echo "Registration Number: " . $admissionData['registration_number'] . "\n";

    // Attempt invoice creation
    echo "<h3>Attempting Invoice Creation:</h3>";

    $regFee = $program['registration_fee'] ?? 0;
    $tuitionFee = $program['tuition_fee'] ?? 0;
    $discount = $program['discount'] ?? 0;
    $finalTuition = $tuitionFee * (1 - $discount / 100);
    $totalAmount = $regFee + $finalTuition;

    echo "RegFee: " . $regFee . "\n";
    echo "TuitionFee: " . $tuitionFee . "\n";
    echo "Discount: " . $discount . "\n";
    echo "FinalTuition: " . $finalTuition . "\n";
    echo "TotalAmount: " . $totalAmount . "\n";

    if ($totalAmount > 0) {
        $invoiceModel = new InvoiceModel();
        $invoiceData = [
            'registration_number' => $admissionData['registration_number'],
            'description' => 'Initial Fees: Registration and Tuition for ' . $program['title'],
            'amount' => $totalAmount,
            'due_date' => date('Y-m-d', strtotime('+3 days')),
            'invoice_type' => 'tuition_fee',
            'status' => 'outstanding'
        ];

        echo "\nInvoice Data:\n";
        echo "<pre>" . json_encode($invoiceData, JSON_PRETTY_PRINT) . "</pre>";

        $invoiceId = $invoiceModel->createInvoice($invoiceData);

        if ($invoiceId) {
            echo "<h3 style='color:green;'>✓ Invoice Created Successfully!</h3>";
            echo "Invoice ID: " . $invoiceId . "\n";

            // Verify invoice was inserted
            $invoice = $invoiceModel->find($invoiceId);
            echo "\nVerification - Retrieved Invoice:\n";
            echo "<pre>" . json_encode($invoice, JSON_PRETTY_PRINT) . "</pre>";
        } else {
            echo "<h3 style='color:red;'>✗ Invoice Creation FAILED!</h3>";
            echo "Model Errors:\n";
            echo "<pre>" . json_encode($invoiceModel->errors(), JSON_PRETTY_PRINT) . "</pre>";
        }
    }
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Error: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
