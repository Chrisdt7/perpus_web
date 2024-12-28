<?php

namespace App\Controllers;

use Config\Services;

class Autentifikasi extends BaseController
{
    public function index()
    {
        $session = session();

        // If the user is already logged in, redirect to the user page
        if ($session->get('email')) {
            return redirect()->to('user');
        }

        // Load validation service
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ]);

        // Validate user input
        if (!$validation->withRequest($this->request)->run()) {
            // If validation fails, show the login view again
            $data = [
                'judul' => 'Login',
                'user'  => '',
            ];

            echo view('templates/aute_header', $data);
            echo view('autentifikasi/login');
            echo view('templates/aute_footer');
        } else {
            // Proceed to login
            return $this->_login($this->request->getPost('email'), $this->request->getPost('password'), $session);
        }
    }

    private function _login($email, $password, $session)
    {
        // Load the user model
        $modelUser = new \App\Models\ModelUser();

        // Find the user by email
        $user = $modelUser->where('email', $email)->first();

        // Check if the user exists
        if ($user) {
            // Check if the account is active
            if ($user['is_active'] == 1) {
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Set user data in the session
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                    ];
                    $session->set($data);

                    // Redirect based on user role
                    if ($user['role_id'] == 1) {
                        return redirect()->to('user');
                    } else {
                        // Check if the user has the default profile image
                        if ($user['image'] == 'default.jpg') {
                            $session->setFlashdata('pesan', 'Silahkan Ubah Profil Anda untuk Ubah Photo Profil');
                        } else {
                            $session->setFlashdata('pesan', 'Selamat Datang ' . $user['nama']);
                        }

                        return redirect()->to('user');
                    }
                } else {
                    // Incorrect password
                    $session->setFlashdata('pesan', 'Password salah!!');
                    return redirect()->to('login');
                }
            } else {
                // Account not active
                $session->setFlashdata('pesan', 'User belum diaktifasi!!');
                return redirect()->to('login');
            }
        } else {
            // Email not registered
            $session->setFlashdata('pesan', 'Email tidak terdaftar!!');
            return redirect()->to('login');
        }
    }

    public function blok()
    {
        return view('autentifikasi/blok');
    }
    
    public function gagal()
    {
        return view('autentifikasi/gagal');
    }

    public function registrasi()
    {
        $session = session();
    
        if ($session->get('email')) {
            return redirect()->to('user');
        }
    
        // Create validation rules for the registration form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password1' => 'required|min_length[3]|matches[password2]',
            'password2' => 'required|matches[password1]',
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'judul' => 'Registrasi Member',
                'validation' => $validation, // Pass the validation object to the view
            ];
    
            $headerView = view('templates/aute_header', $data);
            $bodyView = view('autentifikasi/registrasi');
            $footerView = view('templates/aute_footer');
    
            return $headerView . $bodyView . $footerView;
        } else {
            $email = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
            $data = [
                'nama' => $this->request->getPost('nama'),
                'email' => $email,
                'image' => 'default.jpg',
                'password' => password_hash($this->request->getPost('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'tanggal_input' => time(),
            ];
    
            $modelUser = new \App\Models\ModelUser();
            $modelUser->insert($data);
    
            $session->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun member anda sudah dibuat. Silahkan Aktivasi Akun anda</div>');
    
            return redirect()->to('autentifikasi');
        }
    }    

    public function logout()
    {
        $session = session();
        session()->destroy();
        return redirect()->to('autentifikasi');
    }
}