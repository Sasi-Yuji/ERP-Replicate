<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TriggerTestSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // 1. Ensure we have at least one student
        $student = $db->table('students')->get()->getRowArray();
        if (!$student) {
            $studentId = $db->table('students')->insert([
                'name'  => 'John Doe',
                'email' => 'john@example.com',
                'cgpa'  => 0.00
            ]);
        } else {
            $studentId = $student['id'];
        }

        // 2. Add Attendance (Trigger 1 test)
        $db->table('attendance')->insert([
            'student_id' => $studentId,
            'percentage' => 80.00
        ]);

        // 3. Add Fees (Trigger 2 test)
        $db->table('fees')->insert([
            'student_id'  => $studentId,
            'total_fee'   => 50000.00,
            'paid_amount' => 10000.00,
            'due_amount'  => 40000.00,
            'risk_status' => 'Normal'
        ]);

        // 4. Add Marks (Trigger 3 & 7 test)
        $db->table('marks')->insert([
            'student_id' => $studentId,
            'subject_id' => 101,
            'marks'      => 85
        ]);
    }
}
