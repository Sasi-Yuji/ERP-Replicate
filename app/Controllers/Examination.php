<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamResultModel;

class Examination extends BaseController
{
    public function index()
    {
        $model = new ExamModel();
        $db    = \Config\Database::connect();
        $courses = $db->table('courses')->get()->getResultArray();
        $students = $db->table('students')->get()->getResultArray();
        
        // Fetch data for Dashboard Lists
        $recent_schedules = $db->table('exam_schedule')
            ->select('exam_schedule.*, courses.name as course_name')
            ->join('courses', 'courses.id = exam_schedule.course_id', 'left')
            ->orderBy('exam_date', 'ASC')
            ->limit(5)
            ->get()->getResultArray();

        $results_query = $db->table('exam_results')
            ->select('exam_results.*, students.name as student_name, courses.name as course_name')
            ->join('students', 'students.id = exam_results.student_id', 'left')
            ->join('courses', 'courses.id = exam_results.course_id', 'left');
        
        if (session('user_role') === 'student') {
            $results_query->where('exam_results.student_id', session('student_id'))->where('is_published', 1);
        }
        $recent_results = $results_query->orderBy('exam_results.id', 'DESC')->limit(5)->get()->getResultArray();

        $today = date('Y-m-d');

        return view('examination/index', [
            'title'           => 'Examination Management',
            'total_exams'     => $db->table('exam_schedule')->where('exam_date >=', $today)->countAllResults(),
            'pending_results' => $db->table('exam_results')->where('is_published', 0)->countAllResults(),
            'total_papers'    => $db->table('exam_question_papers')->countAllResults(),
            'courses'         => $courses,
            'students'        => $students,
            'recent_schedules' => $recent_schedules,
            'recent_results'   => $recent_results,
        ]);
    }

    /**
     * Faculty/Admin upload marks into exam_results.
     */
    public function uploadMarks()
    {
        if (session('user_role') === 'student') {
            return redirect()->to('exam')->with('error', 'Unauthorized.');
        }

        $db    = \Config\Database::connect();
        $marks = (int)$this->request->getPost('marks');
        
        $grade = 'F';
        if ($marks >= 90) $grade = 'A+';
        elseif ($marks >= 80) $grade = 'A';
        elseif ($marks >= 70) $grade = 'B';
        elseif ($marks >= 60) $grade = 'C';
        elseif ($marks >= 50) $grade = 'D';

        $studentId = $this->request->getPost('student_id');
        $courseId  = $this->request->getPost('course_id');

        // Manual Verification
        $studentExists = $db->table('students')->where('id', $studentId)->countAllResults() > 0;
        $courseExists  = $db->table('courses')->where('id', $courseId)->countAllResults() > 0;

        if (!$studentExists) return redirect()->back()->with('error', "Invalid Student ID: $studentId. This student does not exist.");
        if (!$courseExists)  return redirect()->back()->with('error', "Invalid Course ID: $courseId. This course does not exist.");

        $data = [
            'student_id'   => $studentId,
            'course_id'    => $courseId,
            'marks'        => $marks,
            'grade'        => $grade,
            'is_published' => 0
        ];

        try {
            if ($db->table('exam_results')->insert($data)) {
                return redirect()->back()->with('success', 'Marks uploaded! Awaiting Admin publication.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());
        }
        return redirect()->back()->with('error', 'Failed to upload marks.');
    }

    /**
     * Admin schedules a new exam.
     */
    public function scheduleExam()
    {
        if (session('user_role') !== 'admin') {
            return redirect()->to('exam')->with('error', 'Only Admin can schedule exams.');
        }

        $db   = \Config\Database::connect();
        $courseId = $this->request->getPost('course_id');
        $examDate = $this->request->getPost('exam_date');
        $examType = $this->request->getPost('exam_type');

        $db = \Config\Database::connect();

        // Rule 1: Only one exam allowed per date institution-wide
        $dateExists = $db->table('exam_schedule')->where('exam_date', $examDate)->countAllResults() > 0;
        if ($dateExists) {
            return redirect()->back()->with('error', "Cannot schedule: An exam is already scheduled for $examDate.");
        }

        // Rule 2: A course cannot have multiple exams of the same type
        $typeExists = $db->table('exam_schedule')
            ->where('course_id', $courseId)
            ->where('exam_type', $examType)
            ->countAllResults() > 0;
        if ($typeExists) {
            return redirect()->back()->with('error', "Cannot schedule: This course already has a $examType scheduled.");
        }

        $data = [
            'course_id' => $courseId,
            'exam_date' => $examDate,
            'exam_type' => $examType,
        ];

        try {
            if ($db->table('exam_schedule')->insert($data)) {
                return redirect()->to('exam/schedule')->with('success', 'Exam scheduled successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage() . ' (Course ID: ' . $data['course_id'] . ')');
        }
        return redirect()->back()->with('error', 'Failed to schedule exam.');
    }

    /**
     * Faculty uploads a question paper.
     */
    public function uploadPaper()
    {
        if (session('user_role') === 'student') {
            return redirect()->to('exam')->with('error', 'Unauthorized.');
        }

        $db   = \Config\Database::connect();
        $courseId = $this->request->getPost('course_id');
        $courseExists = $db->table('courses')->where('id', $courseId)->countAllResults() > 0;
        if (!$courseExists) return redirect()->back()->with('error', "Invalid Course selected (ID: $courseId).");

        $data = [
            'course_id' => $courseId,
            'title'     => $this->request->getPost('title'),
            'exam_type' => $this->request->getPost('exam_type'),
            'file_path' => 'uploads/papers/paper_' . time() . '.pdf',
            'uploaded_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($db->table('exam_question_papers')->insert($data)) {
                return redirect()->back()->with('success', 'Question paper uploaded successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());
        }
        return redirect()->back()->with('error', 'Failed to upload paper.');
    }

    /**
     * Admin publishes all pending results.
     */
    public function publish()
    {
        if (session('user_role') !== 'admin') {
            return redirect()->to('exam')->with('error', 'Only Admin can publish results.');
        }

        $db = \Config\Database::connect();
        $db->table('exam_results')->where('is_published', 0)->update(['is_published' => 1]);

        return redirect()->to('exam/results')->with('success', 'All pending results have been published!');
    }


    /**
     * View exam schedule — accessible to all roles.
     */
    public function schedule()
    {
        $db       = \Config\Database::connect();
        $schedules = $db->table('exam_schedule')
            ->select('exam_schedule.*, courses.name as course_name')
            ->join('courses', 'courses.id = exam_schedule.course_id', 'left')
            ->orderBy('exam_date', 'ASC')
            ->get()->getResultArray();

        return view('examination/schedule', [
            'title'     => 'Exam Schedule',
            'schedules' => $schedules,
        ]);
    }

    /**
     * View exam results.
     * Students see only their own results.
     * Admin/Faculty see all results.
     */
    public function results()
    {
        $db   = \Config\Database::connect();
        $role = session('user_role');
        $courses = $db->table('courses')->get()->getResultArray();

        $query = $db->table('exam_results')
            ->select('exam_results.*, students.name as student_name, courses.name as course_name')
            ->join('students', 'students.id = exam_results.student_id', 'left')
            ->join('courses', 'courses.id = exam_results.course_id', 'left');

        if ($role === 'student') {
            $query->where('exam_results.student_id', session('student_id'));
            $query->where('exam_results.is_published', 1); // Students only see published results
        }

        $results = $query->orderBy('exam_results.id', 'DESC')->get()->getResultArray();

        return view('examination/results', [
            'title'   => 'Examination Results',
            'results' => $results,
            'courses' => $courses,
        ]);
    }
}
