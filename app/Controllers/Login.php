<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session()->set('captcha_result', $num1 + $num2);
        
        return view('auth/login', [
            'num1' => $num1,
            'num2' => $num2
        ]);
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $captcha  = $this->request->getPost('captcha');

        if ($captcha != session('captcha_result')) {
            return redirect()->back()->with('error', 'Invalid Captcha!');
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user && $password === $user['password']) { // Using plain text for current logic
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'student_id' => $user['student_id'], // Store student profile ID
                'user_role'  => $user['role'],
                'username'   => $user['username']
            ]);
            return redirect()->to('dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
