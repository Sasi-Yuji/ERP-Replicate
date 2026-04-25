<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLibraryTables extends Migration
{
    public function up()
    {
        // books
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'author' => ['type' => 'VARCHAR', 'constraint' => 100],
            'isbn' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 50],
            'copies_available' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('books');

        // book_issue
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'book_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'issue_date' => ['type' => 'DATE'],
            'return_date' => ['type' => 'DATE', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Issued', 'Returned'], 'default' => 'Issued'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('book_issue');
    }

    public function down()
    {
        $this->forge->dropTable('book_issue');
        $this->forge->dropTable('books');
    }
}
