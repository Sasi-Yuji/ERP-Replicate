<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Create a Student record first
        $studentId = $this->db->table('students')->insert([
            'name'  => 'Test Student',
            'email' => 'student@example.com',
            'cgpa'  => 3.50
        ]);

        $data = [
            [
                'username'   => 'admin',
                'password'   => 'admin123',
                'role'       => 'admin',
                'student_id' => null,
            ],
            [
                'username'   => 'faculty',
                'password'   => 'faculty123',
                'role'       => 'faculty',
                'student_id' => null,
            ],
            [
                'username'   => 'student',
                'password'   => 'student123',
                'role'       => 'student',
                'student_id' => $studentId, // Link to student record
            ],
        ];

        // Insert users
        $this->db->table('users')->insertBatch($data);
    }
}
