<?php

// Load CodeIgniter framework
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../system/Test/bootstrap.php';

$db = \Config\Database::connect();

$students = $db->table('students')->get()->getResultArray();

foreach ($students as $student) {
    // Check if user already exists
    $exists = $db->table('users')->where('student_id', $student['id'])->get()->getRowArray();
    
    if (!$exists) {
        $db->table('users')->insert([
            'username'   => $student['email'],
            'password'   => 'student123',
            'role'       => 'student',
            'student_id' => $student['id']
        ]);
        echo "Created login for: " . $student['name'] . " (User: " . $student['email'] . ")\n";
    } else {
        echo "Login already exists for: " . $student['name'] . "\n";
    }
}
