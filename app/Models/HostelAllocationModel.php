<?php

namespace App\Models;

use CodeIgniter\Model;

class HostelAllocationModel extends Model
{
    protected $table      = 'hostel_allocation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'room_id',
        'allocation_date',
        'status',
    ];
}
