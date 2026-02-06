<?php
require '../vendor/autoload.php';
require '../preload.php';

$config = new \Config\Database();
$db = $config->connect();

// Check invoices table structure
echo "=== Invoices Table Structure ===\n";
$result = $db->query('SHOW CREATE TABLE invoices')->getRow();
echo $result->{'Create Table'} . "\n\n";

// Check admissions table structure
echo "=== Admissions Table Structure ===\n";
$result = $db->query('SHOW CREATE TABLE admissions')->getRow();
echo $result->{'Create Table'} . "\n\n";

// Check if there are any foreign key constraints
echo "=== Foreign Key Constraints ===\n";
$result = $db->query("
    SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'feecs' AND (TABLE_NAME = 'invoices' OR TABLE_NAME = 'admissions')
    AND REFERENCED_TABLE_NAME IS NOT NULL
")->getResult();

foreach ($result as $row) {
    echo "{$row->TABLE_NAME}.{$row->COLUMN_NAME} -> {$row->REFERENCED_TABLE_NAME}.{$row->REFERENCED_COLUMN_NAME}\n";
}
