<?php

/**
 * Cleanup Script for Testing
 * 
 * This script clears all admission-related tables for testing purposes.
 * Run from browser: http://localhost/feecs/cleanup_test_data.php
 * 
 * @WARNING: This will DELETE all data from the specified tables!
 */

// Prevent direct access without confirmation
if (!isset($_POST['confirm'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cleanup Test Data</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Warning: This will DELETE data!</h4>
                        </div>
                        <div class="card-body">
                            <p class="lead">This script will <strong>DELETE ALL DATA</strong> from the following tables:</p>
                            <ul class="list-group mb-4">
                                <li class="list-group-item">profiles</li>
                                <li class="list-group-item">admissions</li>
                                <li class="list-group-item">invoices</li>
                                <li class="list-group-item">payments</li>
                                <li class="list-group-item">students</li>
                                <li class="list-group-item">conversations</li>
                                <li class="list-group-item">messages</li>
                            </ul>
                            <p class="text-muted small mb-4">
                                Note: Users table (for login accounts) and programs table will NOT be affected.
                            </p>
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Type "DELETE" to confirm:</label>
                                    <input type="text" name="confirm" class="form-control" placeholder="DELETE">
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-2"></i>Delete All Test Data
                                </button>
                            </form>
                            <div class="mt-3 text-center">
                                <a href="<?= base_url('/') ?>" class="text-muted">Cancel and go back home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
    exit;
}

// Verify confirmation
if ($_POST['confirm'] !== 'DELETE') {
    echo '<div class="alert alert-danger">You must type "DELETE" exactly to confirm.</div>';
    echo '<a href="cleanup_test_data.php">Try again</a>';
    exit;
}

// Load CodeIgniter
require_once dirname(__DIR__) . '/preload.php';

$db = \Config\Database::connect();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleanup Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4"><i class="bi bi-database me-2"></i>Cleanup Results</h2>
        <?php

        try {
            // Disable foreign key checks temporarily
            $db->query('SET FOREIGN_KEY_CHECKS = 0');

            // Tables to clean (in order - child tables first)
            $tables = [
                'messages',
                'conversation_participants',
                'payments',
                'invoices',
                'students',
                'admissions',
                'profiles',
                'conversations',
            ];

            echo '<div class="card">
        <div class="card-body">
            <h5 class="card-title">Tables being cleaned:</h5>
            <ul class="list-group list-group-flush">';

            foreach ($tables as $table) {
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                echo '<span><i class="bi bi-table me-2"></i>' . $table . '</span>';

                try {
                    // Check if table exists
                    $db->query("SHOW TABLES LIKE '{$table}'");
                    if ($db->affectedRows() > 0) {
                        $count = $db->table($table)->countAllResults();
                        $db->table($table)->truncate(); // This also resets auto-increment
                        echo '<span class="badge bg-success">✓ Cleared (' . $count . ' rows deleted)</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Table not found</span>';
                    }
                } catch (\Exception $e) {
                    echo '<span class="badge bg-danger">Error: ' . $e->getMessage() . '</span>';
                }

                echo '</li>';
            }

            echo '</ul>
        </div>
    </div>';

            // Re-enable foreign key checks
            $db->query('SET FOREIGN_KEY_CHECKS = 1');

            // Also delete uploaded files
            $uploadDirs = [
                FCPATH . 'uploads/profiles/photos',
                FCPATH . 'uploads/profiles/documents',
            ];

            echo '<div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-folder me-2"></i>Uploaded Files</h5>';

            foreach ($uploadDirs as $dir) {
                if (is_dir($dir)) {
                    $files = glob($dir . '/*');
                    $count = count($files);
                    echo '<p>' . basename($dir) . ': ';
                    if ($count > 0) {
                        // Delete files but keep directory
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                        echo '<span class="badge bg-success">✓ Cleared (' . $count . ' files deleted)</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Empty or not found</span>';
                    }
                    echo '</p>';
                } else {
                    echo '<p>' . basename($dir) . ': <span class="badge bg-secondary">Directory not found</span></p>';
                }
            }

            echo '</div>
    </div>';

            echo '<div class="alert alert-success mt-4">
        <h5><i class="bi bi-check-circle me-2"></i>Cleanup Complete!</h5>
        <p class="mb-0">All admission-related data has been cleared. You can now run fresh tests.</p>
    </div>';
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            $db->query('SET FOREIGN_KEY_CHECKS = 1');

            echo '<div class="alert alert-danger">
        <h5><i class="bi bi-x-circle me-2"></i>Error!</h5>
        <p>' . $e->getMessage() . '</p>
    </div>';
        }

        ?>
        <div class="mt-4">
            <a href="cleanup_test_data.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-repeat me-1"></i>Run Again
            </a>
            <a href="<?= base_url('/') ?>" class="btn btn-primary">
                <i class="bi bi-house me-1"></i>Go Home
            </a>
        </div>
    </div>
</body>

</html>