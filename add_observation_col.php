<?php
// Script to add 'observation' column to 'behavior_log' table
// Save as add_observation_col.php in root

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Config\Database;

$db = Database::connect();
$forge = \Config\Database::forge();

echo "Checking 'behavior_log' table...\n";

if ($db->fieldExists('observation', 'behavior_log')) {
    echo "Column 'observation' already exists.\n";
} else {
    echo "Adding 'observation' column...\n";
    $fields = [
        'observation' => [
            'type' => 'TEXT',
            'null' => true,
            'default' => null,
            'after' => 'created_at'
        ],
    ];
    $forge->addColumn('behavior_log', $fields);
    echo "Column added successfully.\n";
}

echo "Done.\n";
