<?php

namespace App\Controllers;

use App\Models\ModelUser;
use App\Models\ModelBooking;
use CodeIgniter\Controller;
use service\Config;

class Member extends Controller
{
    public function __construct()
    {
        helper('form');
        helper('url');
        $this->modelBooking = new ModelBooking();
    }

    public function index()
    {
        return $this->_login();
    }

    private function _login()
    {
        $session = session();
        $request = $this->request;
        $modelUser = new ModelUser();

        $email = htmlspecialchars($request->getPost('email'));
        $password = $request->getPost('password');
        $user = $modelUser->where('email', $email)->first();

        if ($user) {
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'id_user' => $user['id'],
                        'nama' => $user['nama'],
                    ];

                    $session->set($data);
                    return redirect()->to('home');
                } else {
                    $session->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                    return redirect()->to('home');
                }
            } else {
                $session->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                return redirect()->to('home');
            }
        } else {
            $session->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
            return redirect()->to('home');
        }
    }

    public function daftar()
    {
        $session = session();
        $request = $this->request;
        $modelUser = new ModelUser();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password1' => 'required|min_length[3]|matches[password2]',
            'password2' => 'required|matches[password1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $session->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">' . $validation->getErrors() . '</div>');
            return redirect()->to(base_url());
        }

        $data = [
            'nama' => htmlspecialchars($request->getPost('nama')),
            'alamat' => $request->getPost('alamat'),
            'email' => htmlspecialchars($request->getPost('email')),
            'image' => 'default.jpg',
            'password' => password_hash($request->getPost('password1'), PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 1,
            'tanggal_input' => time()
        ];

        $modelUser->insert($data);
        $session->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun anggota anda sudah dibuat.</div>');
        return redirect()->to(base_url());
    }

    public function myProfil()
    {
        $session = session();
        $modelUser = new ModelUser();

        $data['judul'] = 'Profil Saya';
        $user = $modelUser->where('email', $session->get('email'))->first();

        $data['image'] = $user['image'];
        $data['user'] = $user['nama'];
        $data['email'] = $user['email'];
        $data['tanggal_input'] = $user['tanggal_input'];
        $data['modelBooking'] = $this->modelBooking;
        $data['numRows'] = $this->modelBooking->getDataWhere('temp', ['email_user' => session('email')])->getNumRows();

        echo view('templates/templates-user/header', $data);
        echo view('member/index', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer', $data);
    }

	public function ubahProfil()
    {
        $session = session();
        $modelUser = new ModelUser();
        $data['judul'] = 'Profil Saya';
        $user = $modelUser->cekData(['email' => $session->get('email')])->getRowArray();

        $data['image'] = $user['image'];
        $data['user'] = $user['nama'];
        $data['email'] = $user['email'];
        $data['tanggal_input'] = $user['tanggal_input'];

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|trim',
            // Add validation rules for other fields if needed
        ]);

        $data['validation'] = $validation;

        if (!$validation->withRequest($this->request)->run()) {
            echo view('templates/templates-user/header', $data);
            echo view('member/ubah-anggota', $data);
            echo view('templates/templates-user/modal');
            echo view('templates/templates-user/footer', $data);
        } else {
            $nama = $this->request->getPost('nama');
            $email = $this->request->getPost('email');

            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = 'pro' . time() . '.' . $image->getClientExtension();
                $image->move('./assets/img/profile/', $newName);

                $oldImage = $user['image'];
                if ($oldImage != 'default.jpg') {
                    unlink(FCPATH . 'assets/img/profile/' . $oldImage);
                }

                $modelUser->update($user['id'], [
                    'nama' => $nama,
                    'image' => $newName,
                ]);
            } else {
                $modelUser->update($user['id'], ['nama' => $nama]);
            }

            $session->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Profil Berhasil diubah </div>');
            return redirect()->to('member/myprofil');
        }
    }

    public function logout()
    {
        $session = session();

        $session->remove(['email', 'role_id']);
        $session->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Anda telah logout!!</div>');
        
        return redirect()->to('home');
    }
}