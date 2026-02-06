<?php
require 'vendor/autoload.php';

$config = new \Config\Database();
$db = \Config\Database::connect();

echo "=== Students Table ===\n";
$results = $db->table('students')->get()->getResultArray();
echo "Total student records: " . count($results) . "\n";
foreach ($results as $student) {
    echo json_encode($student, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== Students with Joins ===\n";
$query = $db->table('students s')
    ->select('s.*, p.full_name, p.email as profile_email, prog.title as program_title, u.username')
    ->join('profiles p', 'p.id = s.profile_id', 'left')
    ->join('programs prog', 'prog.id = s.program_id', 'left')
    ->join('users u', 'u.id = p.user_id', 'left')
    ->get();
$results = $query->getResultArray();
echo "Query result count: " . count($results) . "\n";
foreach ($results as $student) {
    echo json_encode($student, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== Model Query ===\n";
$studentModel = new \Modules\Student\Models\StudentModel();
$results = $studentModel->getAllWithDetails();
echo "Model result count: " . count($results) . "\n";
foreach ($results as $student) {
    echo json_encode($student, JSON_PRETTY_PRINT) . "\n";
}
