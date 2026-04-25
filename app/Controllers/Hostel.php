<?php

namespace App\Controllers;

use App\Models\HostelModel;
use App\Models\HostelAllocationModel;

class Hostel extends BaseController
{
    public function index()
    {
        $db       = \Config\Database::connect();
        $model    = new HostelModel();
        $role     = session('user_role');

        $data = [
            'title'          => 'Hostel Management',
            'total_rooms'    => $model->countAll(),
            'occupied_rooms' => $model->where('occupied >', 0)->countAll(),
            'requests'       => [],
            'my_allocation'  => null,
            'rooms'          => [],
        ];

        // RBAC: Admin/Faculty see pending requests, active allocations, and all rooms
        if (in_array($role, ['admin', 'faculty'])) {
            $data['requests'] = $db->table('hostel_allocation')
                ->select('hostel_allocation.*, students.name as student_name')
                ->join('students', 'students.id = hostel_allocation.student_id')
                ->where('hostel_allocation.status', 'Pending')
                ->get()->getResultArray();

            $data['active_allocations'] = $db->table('hostel_allocation')
                ->select('hostel_allocation.*, students.name as student_name, hostel_rooms.room_number, hostel_rooms.block')
                ->join('students', 'students.id = hostel_allocation.student_id')
                ->join('hostel_rooms', 'hostel_rooms.id = hostel_allocation.room_id')
                ->where('hostel_allocation.status', 'Active')
                ->get()->getResultArray();

            $data['rooms'] = $model->findAll();
        }

        // Fetch available rooms for Student to apply
        if ($role === 'student') {
            $data['available_rooms'] = $model->where('occupied < capacity')->findAll();
            $data['my_allocation'] = $db->table('hostel_allocation')
                ->select('hostel_allocation.*, hostel_rooms.room_number, hostel_rooms.block')
                ->join('hostel_rooms', 'hostel_rooms.id = hostel_allocation.room_id', 'left')
                ->where('hostel_allocation.student_id', session('student_id'))
                ->orderBy('hostel_allocation.id', 'DESC')
                ->get()->getRowArray();
        }

        return view('hostel/index', $data);
    }

    /**
     * Student applies for a hostel room.
     * student_id is always taken from session — not from POST — to prevent impersonation.
     */
    public function apply()
    {
        // RBAC Guard: Only students can apply
        if (session('user_role') !== 'student') {
            return redirect()->to('hostel')->with('error', 'Only students can submit hostel requests.');
        }

        $db   = \Config\Database::connect();
        $data = [
            'student_id'      => session('student_id'),  // SECURE: always use session profile ID
            'room_id'         => $this->request->getPost('room_id'),
            'allocation_date' => date('Y-m-d'),
            'status'          => 'Pending',
        ];

        // Prevent duplicate active/pending request
        $existing = $db->table('hostel_allocation')
            ->where('student_id', session('student_id'))
            ->whereIn('status', ['Pending', 'Active'])
            ->get()->getRowArray();
        if ($existing) {
            return redirect()->to('hostel')->with('error', 'You already have a pending or active room allocation.');
        }

        if ($db->table('hostel_allocation')->insert($data)) {
            return redirect()->to('hostel')->with('success', 'Room request submitted! Awaiting warden approval.');
        }
        return redirect()->back()->with('error', 'Request failed.');
    }

    /**
     * Faculty/Admin approves a hostel request.
     */
    public function approve($id)
    {
        if (!in_array(session('user_role'), ['admin', 'faculty'])) {
            return redirect()->to('hostel')->with('error', 'Unauthorized.');
        }

        $db = \Config\Database::connect();
        $allocation = $db->table('hostel_allocation')->where('id', $id)->get()->getRowArray();
        if (!$allocation) return redirect()->to('hostel')->with('error', 'Request not found.');

        // Check capacity
        $room = $db->table('hostel_rooms')->where('id', $allocation['room_id'])->get()->getRowArray();
        if ($room['occupied'] >= $room['capacity']) {
            return redirect()->to('hostel')->with('error', 'Room is already full.');
        }

        $db->table('hostel_allocation')->where('id', $id)->update(['status' => 'Active']);
        $db->table('hostel_rooms')->where('id', $allocation['room_id'])->set('occupied', 'occupied + 1', false)->update();

        return redirect()->to('hostel')->with('success', 'Allocation approved!');
    }

    public function reject($id)
    {
        if (!in_array(session('user_role'), ['admin', 'faculty'])) {
            return redirect()->to('hostel')->with('error', 'Unauthorized.');
        }
        $db = \Config\Database::connect();
        $db->table('hostel_allocation')->where('id', $id)->update(['status' => 'Rejected']);
        return redirect()->to('hostel')->with('success', 'Request rejected.');
    }

    public function updateFee($id)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->to('hostel')->with('error', 'Only Admin can update fees.');
        }
        $db = \Config\Database::connect();
        $feeStatus = $this->request->getPost('fee_status');
        $db->table('hostel_allocation')->where('id', $id)->update(['fee_status' => $feeStatus]);
        return redirect()->back()->with('success', 'Fee status updated!');
    }

    public function vacate($id)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->to('hostel')->with('error', 'Only Admin can vacate rooms.');
        }
        $db = \Config\Database::connect();
        $allocation = $db->table('hostel_allocation')->where('id', $id)->get()->getRowArray();
        
        if ($allocation['status'] === 'Active') {
            $db->table('hostel_allocation')->where('id', $id)->update(['status' => 'Vacated']);
            $db->table('hostel_rooms')->where('id', $allocation['room_id'])->set('occupied', 'occupied - 1', false)->update();
        }

        return redirect()->back()->with('success', 'Student vacated and room updated.');
    }

    /**
     * View all hostel rooms — Admin/Faculty only.
     */
    public function rooms()
    {
        if (!in_array(session('user_role'), ['admin', 'faculty'])) {
            return redirect()->to('hostel')->with('error', 'Unauthorized.');
        }
        $model = new HostelModel();
        return view('hostel/rooms', [
            'title' => 'Hostel Rooms',
            'rooms' => $model->findAll(),
        ]);
    }

    /**
     * View all allocations — Admin/Faculty see all; student sees own.
     */
    public function allocation()
    {
        $db   = \Config\Database::connect();
        $role = session('user_role');

        $query = $db->table('hostel_allocation')
            ->select('hostel_allocation.*, students.name as student_name, hostel_rooms.room_number, hostel_rooms.block')
            ->join('students', 'students.id = hostel_allocation.student_id', 'left')
            ->join('hostel_rooms', 'hostel_rooms.id = hostel_allocation.room_id', 'left');

        if ($role === 'student') {
            $query->where('hostel_allocation.student_id', session('student_id'));
        }

        $allocations = $query->orderBy('hostel_allocation.id', 'DESC')->get()->getResultArray();

        return view('hostel/allocation', [
            'title'       => 'Room Allocations',
            'allocations' => $allocations,
        ]);
    }
}
