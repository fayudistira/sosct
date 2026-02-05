<?php
require 'public/index.php';
$db = \Config\Database::connect();
$query = $db->query('SELECT title, category FROM programs');
foreach ($query->getResult() as $row) {
    echo $row->title . " (" . $row->category . ")\n";
}
