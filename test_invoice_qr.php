<?php
// Simple test file to check invoice QR and public view
// Access this file at: http://localhost/test_invoice_qr.php

echo "<h1>Invoice QR Code and Public View Test</h1>";

$invoiceId = 1; // Test with invoice ID 1
$baseUrl = "http://localhost/feecs"; // Adjust this to your base URL

echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='{$baseUrl}/invoice/public/{$invoiceId}' target='_blank'>Public Invoice View (ID: {$invoiceId})</a></li>";
echo "<li><a href='{$baseUrl}/invoice/qr/{$invoiceId}' target='_blank'>QR Code Image (ID: {$invoiceId})</a></li>";
echo "</ul>";

echo "<h2>QR Code Preview:</h2>";
echo "<img src='{$baseUrl}/invoice/qr/{$invoiceId}' alt='QR Code' style='border: 1px solid #ccc; padding: 10px;'>";

echo "<h2>Expected Behavior:</h2>";
echo "<ul>";
echo "<li>Public Invoice View should show invoice details without requiring login</li>";
echo "<li>QR Code should display a scannable QR code image</li>";
echo "<li>Scanning the QR code should open the public invoice view</li>";
echo "</ul>";
?>
