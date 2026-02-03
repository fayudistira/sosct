<?php
// Simple test to check profiles table columns
$mysqli = new mysqli('localhost', 'root', '', 'feecs');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW COLUMNS FROM profiles");

echo "Profiles table columns:\n";
echo "======================\n\n";

while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']} ({$row['Type']}) ";
    if ($row['Null'] == 'NO') echo "[NOT NULL] ";
    if ($row['Key'] == 'PRI') echo "[PRIMARY KEY] ";
    if ($row['Key'] == 'UNI') echo "[UNIQUE] ";
    echo "\n";
}

$mysqli->close();
