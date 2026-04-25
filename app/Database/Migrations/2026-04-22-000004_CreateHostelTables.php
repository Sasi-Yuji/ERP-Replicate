<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHostelTables extends Migration
{
    public function up()
    {
        // hostel_rooms
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'room_number' => ['type' => 'VARCHAR', 'constraint' => 20],
            'block' => ['type' => 'VARCHAR', 'constraint' => 20],
            'capacity' => ['type' => 'INT', 'constraint' => 11],
            'occupied' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('hostel_rooms');

        // hostel_allocation
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'room_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'allocation_date' => ['type' => 'DATE'],
            'status' => ['type' => 'ENUM', 'constraint' => ['Pending', 'Active', 'Vacated', 'Rejected'], 'default' => 'Pending'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('room_id', 'hostel_rooms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hostel_allocation');
    }

    public function down()
    {
        $this->forge->dropTable('hostel_allocation');
        $this->forge->dropTable('hostel_rooms');
    }
}
