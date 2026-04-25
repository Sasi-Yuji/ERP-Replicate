<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $table      = 'exam_results';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'course_id',
        'marks',
        'grade',
    ];
}
