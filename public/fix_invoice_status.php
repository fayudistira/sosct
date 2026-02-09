<?php

/**
 * Database Fix Script - Update invoice status ENUM
 * 
 * This script updates the invoices table to use 'unpaid' instead of 'outstanding'.
 * Run from browser: http://localhost/feecs/fix_invoice_status.php
 */

// Load CodeIgniter
require_once dirname(__DIR__) . '/preload.php';

$db = \Config\Database::connect();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Invoice Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Fix Invoice Status ENUM</h2>
        <?php

        try {
            // Check current ENUM values
            $query = $db->query("SHOW COLUMNS FROM invoices LIKE 'status'");
            $current = $query->getRowArray();

            echo '<div class="card mb-4">
        <div class="card-header">Current Status Column</div>
        <div class="card-body">
            <pre>' . htmlspecialchars($current['Type']) . '</pre>
        </div>
    </div>';

            // Update existing records from 'outstanding' to 'unpaid'
            $updateResult = $db->query("UPDATE invoices SET status = 'unpaid' WHERE status = 'outstanding'");
            $affected = $db->affectedRows();

            echo '<div class="alert alert-info">
        <strong>Updated Records:</strong> ' . $affected . ' rows updated from "outstanding" to "unpaid"
    </div>';

            // Try to alter the ENUM
            try {
                $db->query("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'cancelled', 'expired', 'partially_paid') DEFAULT 'unpaid'");

                echo '<div class="alert alert-success">
            <strong>Success!</strong> ENUM constraint updated successfully.
        </div>';

                // Verify the change
                $query = $db->query("SHOW COLUMNS FROM invoices LIKE 'status'");
                $updated = $query->getRowArray();

                echo '<div class="card mt-4">
            <div class="card-header">Updated Status Column</div>
            <div class="card-body">
                <pre>' . htmlspecialchars($updated['Type']) . '</pre>
            </div>
        </div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-warning">
            <strong>Note:</strong> Could not update ENUM constraint automatically.<br>
            Error: ' . $e->getMessage() . '<br><br>
            <strong>Manual fix required:</strong><br>
            Run this SQL command in your database:<br>
            <code>ALTER TABLE invoices MODIFY COLUMN status ENUM(\'unpaid\', \'paid\', \'cancelled\', \'expired\', \'partially_paid\') DEFAULT \'unpaid\';</code>
        </div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">
        <strong>Error:</strong> ' . $e->getMessage() . '
    </div>';
        }

        ?>
        <div class="mt-4">
            <a href="<?= base_url('/') ?>" class="btn btn-primary">Go Home</a>
        </div>
    </div>
</body>

</html>