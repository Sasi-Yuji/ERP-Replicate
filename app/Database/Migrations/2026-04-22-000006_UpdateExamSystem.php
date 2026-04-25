<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateExamSystem extends Migration
{
    public function up()
    {
        // 1. Add question papers table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'course_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'file_path'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'exam_type'   => ['type' => 'ENUM', 'constraint' => ['Midterm', 'Final'], 'default' => 'Final'],
            'uploaded_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_question_papers');

        // 2. Add is_published column to exam_results
        $fields = [
            'is_published' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'grade'],
        ];
        $this->forge->addColumn('exam_results', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('exam_results', 'is_published');
        $this->forge->dropTable('exam_question_papers');
    }
}
