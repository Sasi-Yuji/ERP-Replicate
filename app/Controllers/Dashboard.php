<?php

namespace App\Controllers;

use App\Models\StudentsModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new StudentsModel();
        $role  = session('user_role');

        // RBAC: Student sees only their own record, Faculty sees their mentees
        if ($role === 'student') {
            $student = $model->find(session('student_id'));
            $students = $student ? [$student] : [];
        } elseif ($role === 'faculty') {
            $facultyId = session('user_id');
            $students = $model->select('students.*')
                ->join('mentor_allocation', 'mentor_allocation.student_id = students.id', 'left')
                ->where('mentor_allocation.mentor_id', $facultyId)
                ->findAll();
        } else {
            $students = $model->findAll();
        }

        $db = \Config\Database::connect();
        
        // Stats based on Role
        if ($role === 'admin') {
            $libStats = [
                'total_books'  => $db->table('books')->countAllResults(),
                'issued_books' => $db->table('book_issue')->where('status', 'Issued')->countAllResults(),
            ];
            $examStats = [
                'pending_results' => $db->table('exam_results')->where('is_published', 0)->countAllResults(),
            ];
            $hostelStats = [
                'total_occupied'   => $db->table('hostel_rooms')->selectSum('occupied')->get()->getRowArray()['occupied'] ?? 0,
                'pending_requests' => $db->table('hostel_allocation')->where('status', 'Pending')->countAllResults(),
            ];
        } elseif ($role === 'faculty') {
            $facultyId = session('user_id');
            $menteeIds = !empty($students) ? array_column($students, 'id') : [0];
            
            $libStats = [
                'total_books'  => $db->table('books')->countAllResults(),
                'issued_books' => $db->table('book_issue')->whereIn('student_id', $menteeIds)->where('status', 'Issued')->countAllResults(),
            ];
            $examStats = [
                'pending_papers' => $db->table('exam_question_papers')->countAllResults(), 
            ];
            $hostelStats = [
                'pending_requests' => $db->table('hostel_allocation')->whereIn('student_id', $menteeIds)->where('status', 'Pending')->countAllResults(),
            ];
        } else {
            // Student Stats
            $studentId = session('student_id');
            $libStats = [
                'total_books'  => $db->table('books')->countAllResults(),
                'my_books'     => $db->table('book_issue')->where('student_id', $studentId)->where('status', 'Issued')->countAllResults(),
            ];
            $examStats = [
                'upcoming' => $db->table('exam_schedule')->countAllResults(),
            ];
            $hostel = $db->table('hostel_allocation')
                ->where('student_id', $studentId)
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            $hostelStats = [
                'my_status' => $hostel['status'] ?? 'Not Applied',
            ];
            
            $studentStats = [
                'attendance' => 85, // Static for now as table is missing
                'att_status' => 'Normal',
                'fee_status' => $hostel['fee_status'] ?? 'N/A'
            ];
        }

        $title = 'Dashboard';
        if ($role === 'admin') $title = 'Admin Dashboard';
        if ($role === 'faculty') $title = 'Faculty Dashboard';
        if ($role === 'student') $title = 'Student Dashboard';

        return view('dashboard', [
            'title'          => $title,
            'students'       => $students,
            'total_students' => count($students),
            'libStats'       => $libStats,
            'examStats'      => $examStats,
            'hostelStats'    => $hostelStats,
            'studentStats'   => $studentStats ?? [],
            'role'           => $role
        ]);
    }

    public function create()
    {
        // RBAC Guard: Students cannot add users
        if (session('user_role') === 'student') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized: Students cannot add users.');
        }

        $db    = \Config\Database::connect();
        $model = new StudentsModel();
        
        $name  = $this->request->getPost('name');
        $email = $this->request->getPost('email');

        $data  = [
            'name'  => $name,
            'email' => $email,
            'cgpa'  => 0.00,
        ];

        if ($model->insert($data)) {
            $studentId = $model->insertID();

            // Auto-create login credentials for the student
            $db->table('users')->insert([
                'username'   => $email,
                'password'   => 'student123', // Default password
                'role'       => 'student',
                'student_id' => $studentId
            ]);

            // RBAC: If faculty is adding a student, auto-assign as mentor
            if (session('user_role') === 'faculty') {
                $db->table('mentor_allocation')->insert([
                    'student_id' => $studentId,
                    'mentor_id'  => session('user_id')
                ]);
            } else {
                // Admin adds: default to admin or some default faculty
                $db->table('mentor_allocation')->insert([
                    'student_id' => $studentId,
                    'mentor_id'  => 1 // Admin
                ]);
            }

            return redirect()->to('dashboard')->with('success', 'Student created! Login: ' . $email . ' | Pass: student123');
        }
        return redirect()->back()->with('error', 'Failed to add student.');
    }

    public function delete($id)
    {
        // RBAC Guard: Students cannot delete users
        if (session('user_role') === 'student') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized: Students cannot delete users.');
        }

        $model = new StudentsModel();
        if ($model->delete($id)) {
            return redirect()->to('dashboard')->with('success', 'User deleted successfully!');
        }
        return redirect()->back()->with('error', 'Failed to delete user.');

    }
}
