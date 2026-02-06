<?php

use Modules\Admission\Models\AdmissionModel;
use Modules\Payment\Models\InvoiceModel;

require '../vendor/autoload.php';
require '../preload.php';

$admissionModel = new AdmissionModel();
$invoiceModel = new InvoiceModel();

echo "<h2>Recent Admissions:</h2>";
$admissions = $admissionModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
echo "<table border='1' style='padding:10px;'>";
echo "<tr><th>ID</th><th>Registration #</th><th>Status</th><th>Created</th></tr>";
foreach ($admissions as $a) {
    echo "<tr><td>{$a['id']}</td><td>{$a['registration_number']}</td><td>{$a['status']}</td><td>{$a['created_at']}</td></tr>";
}
echo "</table>";

echo "<h2>Recent Invoices:</h2>";
$invoices = $invoiceModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
echo "<table border='1' style='padding:10px;'>";
echo "<tr><th>ID</th><th>Registration #</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
if (count($invoices) > 0) {
    foreach ($invoices as $inv) {
        echo "<tr><td>{$inv['id']}</td><td>{$inv['registration_number']}</td><td>{$inv['amount']}</td><td>{$inv['status']}</td><td>{$inv['created_at']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='5'>No invoices found</td></tr>";
}
echo "</table>";
