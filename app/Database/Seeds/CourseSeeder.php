<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Computer Science & Engineering'],
            ['name' => 'Information Technology'],
            ['name' => 'Electronics & Communication'],
            ['name' => 'Mechanical Engineering'],
            ['name' => 'Civil Engineering'],
            ['name' => 'MBA (Business Admin)'],
        ];

        // Using simple insert ignore to avoid duplicates
        foreach ($data as $course) {
            $this->db->table('courses')->insert($course);
        }
    }
}
