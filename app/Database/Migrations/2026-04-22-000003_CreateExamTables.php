<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamTables extends Migration
{
    public function up()
    {
        // exam_schedule
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'course_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'exam_date' => ['type' => 'DATE'],
            'exam_type' => ['type' => 'ENUM', 'constraint' => ['Midterm', 'Final'], 'default' => 'Final'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_schedule');

        // exam_results
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'course_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'marks' => ['type' => 'INT', 'constraint' => 3],
            'grade' => ['type' => 'VARCHAR', 'constraint' => 5],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_results');
    }

    public function down()
    {
        $this->forge->dropTable('exam_results');
        $this->forge->dropTable('exam_schedule');
    }
}
