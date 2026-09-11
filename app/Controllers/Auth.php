<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registerProcess()
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));
        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email sudah terdaftar');
        }

        $this->userModel->insert([
            'nama' => trim((string) $this->request->getPost('nama')),
            'email' => $email,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'user',
            'is_verified' => 0
        ]);

        return redirect()->to(base_url('login'))
            ->with('success', 'Pendaftaran berhasil, silakan login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
            ->with('error', 'Email atau password salah');
        }

        session()->set([
            'logged_in' => true,
            'user_id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user',
            'is_verified' => (int) ($user['is_verified'] ?? 0)
        ]);

        session()->regenerate(true);
        return redirect()->to(base_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}