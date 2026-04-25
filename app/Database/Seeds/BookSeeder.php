<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title'            => 'Clean Code',
                'author'           => 'Robert C. Martin',
                'isbn'             => '978-0132350884',
                'category'         => 'Programming 💻',
                'copies_available' => 5,
            ],
            [
                'title'            => 'The Pragmatic Programmer',
                'author'           => 'Andrew Hunt',
                'isbn'             => '978-0201616224',
                'category'         => 'Programming 💻',
                'copies_available' => 3,
            ],
            [
                'title'            => 'Introduction to Algorithms',
                'author'           => 'Thomas H. Cormen',
                'isbn'             => '978-0262033848',
                'category'         => 'Programming 💻',
                'copies_available' => 2,
            ],
            [
                'title'            => 'The Great Gatsby',
                'author'           => 'F. Scott Fitzgerald',
                'isbn'             => '978-0743273565',
                'category'         => 'Fiction 📖',
                'copies_available' => 10,
            ],
            [
                'title'            => 'A Brief History of Time',
                'author'           => 'Stephen Hawking',
                'isbn'             => '978-0553380163',
                'category'         => 'Science 🔬',
                'copies_available' => 4,
            ],
        ];

        // Using Query Builder
        $this->db->table('books')->insertBatch($data);
    }
}
