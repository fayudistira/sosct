<?php

require_once 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = new Config\Paths();
$app = new CodeIgniter\CodeIgniter($pathsConfig);
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

// Check programs table
echo "=== CHECKING PROGRAMS TABLE ===\n\n";

$query = $db->query("SELECT COUNT(*) as total FROM programs WHERE deleted_at IS NULL");
$result = $query->getRow();
echo "Total programs: " . $result->total . "\n\n";

if ($result->total > 0) {
    echo "=== PROGRAMS LIST ===\n";
    $query = $db->query("SELECT id, title, category, status FROM programs WHERE deleted_at IS NULL ORDER BY category, title");
    $programs = $query->getResultArray();
    
    foreach ($programs as $program) {
        echo sprintf(
            "- [%s] %s (%s) - Status: %s\n",
            substr($program['id'], 0, 8),
            $program['title'],
            $program['category'],
            $program['status']
        );
    }
} else {
    echo "❌ NO PROGRAMS FOUND!\n";
    echo "\nRun the seeder:\n";
    echo "php spark db:seed LanguageProgramSeeder\n";
}

echo "\n=== DONE ===\n";
