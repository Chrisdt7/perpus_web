<?php
namespace App\Controllers;

use App\Models\ModelBuku;
use App\Models\ModelUser;
use App\Models\ModelBooking;
use CodeIgniter\Controller;
use Config\Services;

class Home extends Controller
{
    public function index()
    {
        $session = session();
        
        $modelBuku = new ModelBuku();
        $modelUser = new ModelUser();
        $modelBooking = new ModelBooking();

        $data = [
            'judul' => "Katalog Buku",
            'buku' => $modelBuku->getBuku(),
            'modelBooking' => $modelBooking,
        ];

        if (session()->get('email')) {
            $user = $modelUser->cekData(['email' => session()->get('email')])->getRowArray();
            $data['user'] = $user['nama'];
        } else {
            $data['user'] = 'Pengunjung';
        }

        $data['numRows'] = $modelBooking->getDataWhere('temp', ['email_user' => session('email')])->getNumRows();

        echo view('templates/templates-user/header', $data);
        echo view('buku/daftarbuku', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer', $data);
    }

    public function detailBuku($id)
    {
        $modelBuku = new ModelBuku();

        $buku = $modelBuku->joinKategoriBuku(['buku.id' => $id]);
        $data['user'] = "Pengunjung";
        $data['title'] = "Detail Buku";
        
        foreach ($buku as $fields) {
            if (property_exists($fields, 'judul_buku')) {
                $data['judul'] = $fields->judul_buku;
                $data['pengarang'] = $fields->pengarang;
                $data['penerbit'] = $fields->penerbit;
                $data['kategori'] = $fields->nama_kategori;
                $data['tahun'] = $fields->tahun_terbit;
                $data['isbn'] = $fields->isbn;
                $data['gambar'] = $fields->image;
                $data['dipinjam'] = $fields->dipinjam;
                $data['dibooking'] = $fields->dibooking;
                $data['stok'] = $fields->stok;
                $data['id'] = $id;
            }
        }

        echo view('templates/templates-user/header', $data);
        echo view('buku/detail-buku', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');
    }
}
