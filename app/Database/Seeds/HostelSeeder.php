<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class HostelSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            ['room_number' => 'A-101', 'block' => 'Boys Block A', 'capacity' => 4, 'occupied' => 0],
            ['room_number' => 'A-102', 'block' => 'Boys Block A', 'capacity' => 4, 'occupied' => 0],
            ['room_number' => 'A-103', 'block' => 'Boys Block A', 'capacity' => 2, 'occupied' => 0],
            ['room_number' => 'B-201', 'block' => 'Girls Block B', 'capacity' => 3, 'occupied' => 0],
            ['room_number' => 'B-202', 'block' => 'Girls Block B', 'capacity' => 3, 'occupied' => 0],
            ['room_number' => 'C-301', 'block' => 'Elite Block C', 'capacity' => 1, 'occupied' => 0],
        ];

        foreach ($rooms as $room) {
            $this->db->table('hostel_rooms')->insert($room);
        }
    }
}
