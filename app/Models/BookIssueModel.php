<?php

namespace App\Models;

use CodeIgniter\Model;

class BookIssueModel extends Model
{
    protected $table      = 'book_issue';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'book_id',
        'issue_date',
        'return_date',
        'status',
    ];
}
