<?php

require 'vendor/autoload.php';

$db = \Config\Database::connect();

echo "Profiles table structure:\n";
echo "========================\n\n";

$fields = $db->getFieldData('profiles');

foreach ($fields as $field) {
    echo "- {$field->name} ({$field->type}";
    if ($field->max_length) {
        echo ", max_length: {$field->max_length}";
    }
    echo ")\n";
}

echo "\n";
