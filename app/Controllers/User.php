<?php 

namespace App\Controllers;

use App\Models\ModelUser;
use CodeIgniter\Controller;

class User extends BaseController
{
    public function __construct()
    {
        helper(['pustaka_helper']); 
        \App\Helpers\cek_login();
    }

    public function index()
    {
        $data['judul'] = 'Profil Saya';
        $modelUser = model('App\Models\ModelUser');
        $data['user'] = $modelUser->cekData(['email' => session()->get('email')])->getRowArray();

        return view('templates/header', $data) .
            view('templates/sidebar', $data) .
            view('templates/topbar', $data) .
            view('user/index', $data) .
            view('templates/footer');
    }

    public function anggota()
    {
        $data['judul'] = 'Data Anggota';
        $modelUser = model('App\Models\ModelUser');
        $db = db_connect();

        // Get user data
        $data['user'] = $modelUser->cekData(['email' => session()->get('email')])->getRowArray();

        // Create query to get all users with role_id = 1
        $builder = $db->table('user')->where('role_id', 1);
        $data['anggota'] = $builder->get()->getResultArray();

        // Load the views with the data
        return view('templates/header', $data) .
            view('templates/sidebar', $data) .
            view('templates/topbar', $data) .
            view('user/anggota', $data) .
            view('templates/footer');
    }

    public function ubahProfil()
    {
        $data['judul'] = 'Ubah Profil';
        $modelUser = model('App\Models\ModelUser');
        $data['user'] = $modelUser->cekData(['email' => session()->get('email')])->getRowArray();
        $validation = \Config\Services::validation();
        helper('form');

        $validation->setRules([
            'nama' => 'required|trim',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('templates/header', $data) .
                view('templates/sidebar', $data) .
                view('templates/topbar', $data) .
                view('user/ubah-profile', $data) .
                view('templates/footer');
        } else {
            $nama = $this->request->getPost('nama');
            $email = $this->request->getPost('email');

            // Handle image upload (You may need to configure this)
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['upload_path'] = './assets/img/profile/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '3000';
                $config['max_width'] = '1024';
                $config['max_height'] = '1000';
                $config['file_name'] = 'pro' . time();
        
                $modelUser = model('App\Models\ModelUser');
        
                $file = $this->request->getFile('image');
        
                if ($file->isValid() && !$file->hasMoved()) {
                    if ($file->move($config['upload_path'], $config['file_name'])) {
                        $gambar_lama = $data['user']['image'];
                        if ($gambar_lama != 'default.jpg') {
                            unlink(FCPATH . 'assets/img/profile/' . $gambar_lama);
                        }
                        $gambar_baru = $file->getName();
                        $modelUser->set('image', $gambar_baru);
                    } else {
                        // Handle the upload error here if needed
                        $errors = $file->getErrorString();
                        // Flash message or handle errors accordingly
                    }
                }
            }
            $modelUser->set('nama', $nama);
            $modelUser->where('email', $email)->update();

            // Flash message
            session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Profil Berhasil diubah </div>');
            return redirect()->to(site_url('user/index'));
        }
    }
}
