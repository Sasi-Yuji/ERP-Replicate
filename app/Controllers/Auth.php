<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Note: In a real app, use a model and password_verify. 
        // For this demo/clone, I'll check against the roles we expect.
        if ($username === 'admin' && $password === 'admin123') {
            $this->setUserSession(['id' => 1, 'username' => 'admin', 'role' => 'admin']);
            return redirect()->to('/dashboard');
        } elseif ($username === 'student' && $password === 'student123') {
            $this->setUserSession(['id' => 2, 'username' => 'student', 'role' => 'student']);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
