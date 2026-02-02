<?php
/**
 * Upload Directory Diagnostic Tool
 * Access this file directly: https://yourdomain.com/check_uploads.php
 * DELETE THIS FILE after troubleshooting!
 */

// Security: Only allow in development or with a secret key
$secret = $_GET['key'] ?? '';
if ($secret !== 'debug123') {
    die('Access denied. Add ?key=debug123 to URL');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Directory Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Upload Directory Diagnostic</h1>
    <p><strong>⚠️ DELETE THIS FILE AFTER TROUBLESHOOTING!</strong></p>
    
    <?php
    $uploadsPath = '../writable/uploads/';
    $programsPath = $uploadsPath . 'programs/thumbs/';
    
    echo "<h2>1. Directory Existence</h2>";
    echo "<table>";
    echo "<tr><th>Path</th><th>Exists</th><th>Readable</th><th>Writable</th></tr>";
    
    $paths = [
        'writable/uploads' => $uploadsPath,
        'programs/thumbs' => $programsPath,
    ];
    
    foreach ($paths as $label => $path) {
        $exists = is_dir($path);
        $readable = is_readable($path);
        $writable = is_writable($path);
        
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td class='" . ($exists ? 'success' : 'error') . "'>" . ($exists ? 'Yes' : 'No') . "</td>";
        echo "<td class='" . ($readable ? 'success' : 'error') . "'>" . ($readable ? 'Yes' : 'No') . "</td>";
        echo "<td class='" . ($writable ? 'success' : 'error') . "'>" . ($writable ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>2. File Permissions</h2>";
    if (is_dir($programsPath)) {
        $files = scandir($programsPath);
        $files = array_diff($files, ['.', '..']);
        
        if (empty($files)) {
            echo "<p class='warning'>No files found in programs/thumbs/</p>";
        } else {
            echo "<table>";
            echo "<tr><th>File</th><th>Permissions</th><th>Owner</th><th>Size</th><th>Readable</th></tr>";
            
            foreach (array_slice($files, 0, 10) as $file) {
                $filePath = $programsPath . $file;
                $perms = substr(sprintf('%o', fileperms($filePath)), -4);
                $owner = function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($filePath))['name'] : 'N/A';
                $size = filesize($filePath);
                $readable = is_readable($filePath);
                
                echo "<tr>";
                echo "<td>$file</td>";
                echo "<td>$perms</td>";
                echo "<td>$owner</td>";
                echo "<td>" . number_format($size) . " bytes</td>";
                echo "<td class='" . ($readable ? 'success' : 'error') . "'>" . ($readable ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            if (count($files) > 10) {
                echo "<p>Showing first 10 of " . count($files) . " files</p>";
            }
        }
    } else {
        echo "<p class='error'>Directory does not exist!</p>";
    }
    
    echo "<h2>3. PHP Configuration</h2>";
    echo "<table>";
    echo "<tr><th>Setting</th><th>Value</th></tr>";
    echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
    echo "<tr><td>Current User</td><td>" . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : exec('whoami')) . "</td></tr>";
    echo "<tr><td>Upload Max Filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
    echo "<tr><td>Post Max Size</td><td>" . ini_get('post_max_size') . "</td></tr>";
    echo "<tr><td>Memory Limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
    echo "</table>";
    
    echo "<h2>4. Test Image Display</h2>";
    if (is_dir($programsPath)) {
        $files = scandir($programsPath);
        $files = array_diff($files, ['.', '..']);
        
        if (!empty($files)) {
            $testFile = reset($files);
            echo "<p>Testing file: <strong>$testFile</strong></p>";
            
            // Test direct access
            echo "<h3>Direct File Access (should fail in production):</h3>";
            echo "<img src='../writable/uploads/programs/thumbs/$testFile' alt='Direct' style='max-width: 200px; border: 2px solid red;'>";
            echo "<p class='error'>If you see the image above, your writable directory is publicly accessible (security risk!)</p>";
            
            // Test via route
            echo "<h3>Via FileController Route (should work):</h3>";
            echo "<img src='/writable/uploads/programs/thumbs/$testFile' alt='Via Route' style='max-width: 200px; border: 2px solid green;'>";
            echo "<p class='success'>If you see the image above, the FileController route is working correctly!</p>";
        } else {
            echo "<p class='warning'>No test files available</p>";
        }
    }
    
    echo "<h2>5. Server Information</h2>";
    echo "<table>";
    echo "<tr><th>Setting</th><th>Value</th></tr>";
    echo "<tr><td>Server Software</td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
    echo "<tr><td>Document Root</td><td>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</td></tr>";
    echo "<tr><td>Script Filename</td><td>" . __FILE__ . "</td></tr>";
    echo "</table>";
    
    echo "<h2>6. Recommendations</h2>";
    echo "<ul>";
    
    if (!is_dir($programsPath)) {
        echo "<li class='error'>Create the directory: mkdir -p writable/uploads/programs/thumbs</li>";
    }
    
    if (is_dir($programsPath) && !is_writable($programsPath)) {
        echo "<li class='error'>Fix permissions: chmod -R 755 writable/uploads</li>";
        echo "<li class='error'>Fix ownership: chown -R www-data:www-data writable/uploads</li>";
    }
    
    if (function_exists('posix_getpwuid')) {
        $phpUser = posix_getpwuid(posix_geteuid())['name'];
        echo "<li>PHP is running as: <strong>$phpUser</strong></li>";
        echo "<li>Make sure uploaded files are owned by this user</li>";
    }
    
    echo "</ul>";
    
    echo "<hr>";
    echo "<p><strong>⚠️ IMPORTANT: DELETE THIS FILE (check_uploads.php) AFTER TROUBLESHOOTING!</strong></p>";
    ?>
</body>
</html>
