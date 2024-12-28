<?php

namespace App\Controllers;

use App\Models\ModelBuku;
use App\Models\ModelUser;
use CodeIgniter\Controller;

class Buku extends BaseController
{
    public function __construct()
    {
        helper(['pustaka_helper']);
        \App\Helpers\cek_login();
        $this->modelUser = new ModelUser();
    }

    public function kategori()
    {
        $data['judul'] = 'Kategori Buku';
        $modelUser = model('App\Models\ModelUser');
        $modelBuku = model('App\Models\ModelBuku');
        $db = db_connect();
        // Load session service
        $this->session = \Config\Services::session();

        $userData = $modelUser->cekData(['email' => $this->session->get('email')])->getRow();
        $data['user'] = $userData ? (array)$userData : [];
        $data['kategori'] = $modelBuku->getKategori();

        if (!is_array($data['kategori'])) {
            $data['kategori'] = [];
        }

        $validation = \Config\Services::validation();

        $validation->setRules([
            'kategori' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('templates/header', $data) .
                view('templates/sidebar', $data) .
                view('templates/topbar', $data) .
                view('buku/kategori', $data) .
                view('templates/footer');
        } else {
            $data = [
                'kategori' => $this->request->getPost('kategori')
            ];
            $modelBuku->simpanKategori($data);
            return redirect()->to('/buku/kategori');
        }
    }

    public function hapusKategori($id)
    {
        $modelBuku = new ModelBuku(); // Correct instantiation
        $modelBuku->hapusKategori(['id_kategori' => $id]);
        return redirect()->to('/buku/kategori');
    }
}
