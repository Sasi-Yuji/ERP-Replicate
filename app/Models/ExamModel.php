<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table         = 'exam_schedule';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['course_id', 'exam_date', 'exam_type'];
}
