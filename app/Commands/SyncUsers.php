<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncUsers extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'users:sync';
    protected $description = 'Syncs students with users table to ensure everyone has a login.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $students = $db->table('students')->get()->getResultArray();

        foreach ($students as $student) {
            $exists = $db->table('users')->where('student_id', $student['id'])->get()->getRowArray();
            if (!$exists) {
                $db->table('users')->insert([
                    'username'   => $student['email'],
                    'password'   => 'student123',
                    'role'       => 'student',
                    'student_id' => $student['id']
                ]);
                CLI::write("Created login for: " . $student['name'] . " (" . $student['email'] . ")", 'green');
            }
        }
        CLI::write("Sync complete!", 'cyan');
    }
}
