<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\BookIssueModel;

class Library extends BaseController
{
    public function index()
    {
        $db        = \Config\Database::connect();
        $model      = new BooksModel();
        $studentModel = new \App\Models\StudentsModel();
        $issueModel = new BookIssueModel();
        $role      = session('user_role');

        $issueLog = [];
        if ($role === 'student') {
            $issueLog = $db->table('book_issue')
                ->select('book_issue.*, books.title, books.author, books.isbn, books.category')
                ->join('books', 'books.id = book_issue.book_id')
                ->where('book_issue.student_id', session('student_id'))
                ->orderBy('book_issue.id', 'DESC')
                ->get()->getResultArray();
        } else {
            $issueLog = $db->table('book_issue')
                ->select('book_issue.*, books.title, books.author, books.isbn, books.category, students.name as student_name')
                ->join('books', 'books.id = book_issue.book_id')
                ->join('students', 'students.id = book_issue.student_id')
                ->orderBy('book_issue.id', 'DESC')
                ->get()->getResultArray();
        }

        // Attach issue history to each book
        $books = $model->findAll();
        foreach ($books as &$book) {
            $book['issues'] = array_filter($issueLog, function($issue) use ($book) {
                return $issue['book_id'] == $book['id'];
            });
        }

        return view('library/index', [
            'title'           => 'Library Management',
            'books'           => $books,
            'total_students'  => $studentModel->countAllResults(),
            'total_books'     => $model->countAllResults(),
            'reference_books' => $model->like('category', 'Reference')->countAllResults(),
            'available_books' => $model->where('copies_available >', 0)->countAllResults(),
        ]);
    }

    public function create()
    {
        // RBAC Guard: Only Admin and Faculty can add books
        if (session('user_role') === 'student') {
            return redirect()->to('library')->with('error', 'Unauthorized: Students cannot add books.');
        }

        $model = new BooksModel();
        $data  = [
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'isbn'             => $this->request->getPost('isbn'),
            'category'         => $this->request->getPost('category'),
            'copies_available' => $this->request->getPost('copies_available'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('library')->with('success', 'Book added successfully!');
        }
        return redirect()->back()->with('error', 'Failed to add book.');
    }

    public function update($id)
    {
        if (session('user_role') === 'student') {
            return redirect()->to('library')->with('error', 'Unauthorized.');
        }

        $model = new BooksModel();
        $data  = [
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'isbn'             => $this->request->getPost('isbn'),
            'category'         => $this->request->getPost('category'),
            'copies_available' => $this->request->getPost('copies_available'),
        ];

        if ($model->update($id, $data)) {
            return redirect()->to('library')->with('success', 'Book updated successfully!');
        }
        return redirect()->back()->with('error', 'Failed to update book.');
    }

    public function delete($id)
    {
        // RBAC Guard: Only Admin and Faculty can delete books
        if (session('user_role') === 'student') {
            return redirect()->to('library')->with('error', 'Unauthorized: Students cannot delete books.');
        }

        $model = new BooksModel();
        if ($model->delete($id)) {
            return redirect()->to('library')->with('success', 'Book deleted successfully!');
        }
        return redirect()->back()->with('error', 'Failed to delete book.');
    }

    /**
     * Student borrows a book.
     * Decrements copies_available and creates a book_issue record.
     */
    public function issue()
    {
        // RBAC Guard: Only students can borrow books
        if (session('user_role') !== 'student') {
            return redirect()->to('library')->with('error', 'Only students can borrow books.');
        }

        $db         = \Config\Database::connect();
        $booksModel = new BooksModel();
        $book_id    = $this->request->getPost('book_id');
        $student_id = session('student_id');

        // Fallback: If session student_id is missing, try to fetch it from DB
        if (!$student_id) {
            $user = $db->table('users')->where('id', session('user_id'))->get()->getRowArray();
            $student_id = $user['student_id'] ?? null;
            if ($student_id) {
                session()->set('student_id', $student_id);
            }
        }

        if (!$student_id) {
            return redirect()->to('library')->with('error', 'Your student profile is not linked. Please contact admin.');
        }

        // Check if book is available
        $book = $booksModel->find($book_id);
        if (!$book || $book['copies_available'] < 1) {
            return redirect()->to('library')->with('error', 'Sorry, this book is currently out of stock.');
        }

        // Check if student already has this book issued
        $existing = $db->table('book_issue')
            ->where('student_id', $student_id)
            ->where('book_id', $book_id)
            ->where('status', 'Issued')
            ->get()->getRowArray();
        if ($existing) {
            return redirect()->to('library')->with('error', 'You have already borrowed this book. Please return it first.');
        }

        // Insert book_issue record
        $db->table('book_issue')->insert([
            'student_id'  => $student_id,
            'book_id'     => $book_id,
            'issue_date'  => date('Y-m-d'),
            'return_date' => date('Y-m-d', strtotime('+14 days')),
            'status'      => 'Issued',
        ]);

        // Decrement copies_available
        $booksModel->update($book_id, [
            'copies_available' => $book['copies_available'] - 1,
        ]);

        return redirect()->to('library')->with('success', 'Book "' . $book['title'] . '" borrowed successfully! Return by ' . date('d M Y', strtotime('+14 days')) . '.');
    }

    /**
     * Student returns a borrowed book.
     */
    public function returnBook($issue_id)
    {
        // RBAC Guard: Only students can return books
        if (session('user_role') !== 'student') {
            return redirect()->to('library')->with('error', 'Unauthorized.');
        }

        $db         = \Config\Database::connect();
        $booksModel = new BooksModel();
        $student_id = session('student_id');

        // Verify this issue record belongs to this student
        $issue = $db->table('book_issue')
            ->where('id', $issue_id)
            ->where('student_id', $student_id)
            ->where('status', 'Issued')
            ->get()->getRowArray();

        if (!$issue) {
            return redirect()->to('library')->with('error', 'Invalid return request.');
        }

        // Mark as returned
        $db->table('book_issue')->where('id', $issue_id)->update([
            'status'      => 'Returned',
            'return_date' => date('Y-m-d'),
        ]);

        // Increment copies_available
        $book = $booksModel->find($issue['book_id']);
        $booksModel->update($issue['book_id'], [
            'copies_available' => $book['copies_available'] + 1,
        ]);

        return redirect()->to('library')->with('success', 'Book returned successfully!');
    }

    /**
     * Faculty recommends a book as a reference material.
     * Toggles category to "Reference 📚".
     */
    public function recommend($id)
    {
        // RBAC Guard: Only Admin and Faculty can recommend
        if (session('user_role') === 'student') {
            return redirect()->to('library')->with('error', 'Unauthorized.');
        }

        $model = new BooksModel();
        $book  = $model->find($id);

        if (!$book) {
            return redirect()->to('library')->with('error', 'Book not found.');
        }

        $newCategory = (strpos($book['category'], 'Reference') !== false) 
            ? 'General 📚' // Revert to General
            : 'Reference 📚'; // Mark as Reference

        $model->update($id, ['category' => $newCategory]);

        $msg = (strpos($newCategory, 'Reference') !== false) 
            ? 'Book marked as Reference material. Students cannot borrow this.' 
            : 'Book category updated.';

        return redirect()->to('library')->with('success', $msg);
    }
}
