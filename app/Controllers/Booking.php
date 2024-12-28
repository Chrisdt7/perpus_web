<?php

namespace App\Controllers;

use App\Models\ModelBooking;
use App\Models\ModelUser;
use App\Helpers;
use CodeIgniter\Database\ConnectionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


class Booking extends BaseController
{
    protected $db;

    public function __construct(ConnectionInterface $db = null)
    {
        helper(['uri', 'session', 'pustaka_helper']);
        $this->modelBooking = new ModelBooking();
        $this->modelUser = new ModelUser();
        $this->db = db_connect();
    }

    public function index()
    {
        \App\Helpers\cek_login();
        $id = ['booking.id_user' => $this->request->uri->getSegment(1)];
        $id_user = session('id_user');
        
        $data['booking'] = $this->modelBooking->joinOrder($id_user)->getResult();
        
        $user = $this->modelUser->cekData(['email' => session('email')])->getRowArray();

        $data['user'] = [
            'image' => $user['image'],
            'user' => $user['nama'],
            'email' => $user['email'],
            'tanggal_input' => $user['tanggal_input']
        ];

        $dtb = $this->modelBooking->showtemp(['id_user' => $id_user])->getNumRows();

        if ($dtb < 1) {
            session()->setFlashdata('pesan', '<div class="alert alert-massege alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            return redirect()->to(base_url());
        } else {
            $data['temp'] = $this->db->query("select image, judul_buku, penulis, penerbit, tahun_terbit,id_buku from temp where id_user='$id_user'")->getResultArray();
        }

        $data['judul'] = "Data Booking";
        $data['numRows'] = $this->modelBooking->getDataWhere('temp', ['email_user' => session('email')])->getNumRows();

        echo view('templates/templates-user/header', $data);
        echo view('booking/data-booking', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');
    }

    public function tambahBooking()
    {
        $id_buku = $this->request->uri->getSegment(3);

        $d = $this->db->query("Select * FROM buku WHERE id='$id_buku'")->getRow();
        $isi = [
            'id_buku' => $id_buku,
            'judul_buku' => $d->judul_buku,
            'id_user' => session()->get('id_user'),
            'email_user' => session()->get('email'),
            'tgl_booking' => date('Y-m-d H:i:s'),
            'image' => $d->image,
            'penulis' => $d->pengarang,
            'penerbit' => $d->penerbit,
            'tahun_terbit' => $d->tahun_terbit
        ];

        $temp = $this->modelBooking->getDataWhere('temp', ['id_buku' => $id_buku])->getNumRows();
        $userid = session()->get('id_user');
        $tempuser = $this->db->query("select*from temp where id_user ='$userid'")->getNumRows();
        $databooking = $this->db->query("select*from booking where id_user='$userid'")->getNumRows();

        if ($databooking > 0) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Masih Ada booking buku sebelumnya yang belum diambil.<br> Ambil Buku yang dibooking atau tunggu 1x24 Jam untuk bisa booking kembali </div>');
            return redirect()->to(base_url());
        }

        if ($temp > 0) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Buku ini Sudah anda booking </div>');
            return redirect()->to(base_url() . 'home');
        }

        if ($tempuser == 3) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Booking Buku Tidak Boleh Lebih dari 3</div>');
            return redirect()->to(base_url() . 'home');
        }

        $this->modelBooking->createTemp();
        $this->modelBooking->insertData('temp', $isi);

        session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Buku berhasil ditambahkan ke keranjang </div>');
        return redirect()->to(base_url() . 'home');
    }

    public function hapusbooking($id_buku)
    {
        $id_buku = $this->request->uri->getSegment(3);
        $id_user = session()->get('id_user');
        $this->modelBooking->deleteData(['id_buku' => $id_buku], 'temp');

        $kosong = $this->db->query("select*from temp where id_user='$id_user'")->getNumRows();

        if ($kosong < 1) {
            session()->setFlashdata('pesan', '<div class="alert alert-massege alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            return redirect()->to(base_url());
        } else {
            return redirect()->to(base_url() . 'booking');
        }
    }

    public function bookingSelesai($where)
    {
        // Update stock and dibooking in the buku table during booking process
        $this->db->query("UPDATE buku, temp SET buku.dibooking=buku.dibooking+1, buku.stok=buku.stok-1 WHERE buku.id=temp.id_buku");

        $tglsekarang = date('Y-m-d');
        $isibooking = [
            'id_booking' => $this->modelBooking->kodeOtomatis('booking', 'id_booking'),
            'tgl_booking' => date('Y-m-d H:i:s'),
            'batas_ambil' => date('Y-m-d', strtotime('+2 days', strtotime($tglsekarang))),
            'id_user' => $where
        ];

        // Save to booking and booking_detail tables, and empty the temporary table
        $this->modelBooking->insertData('booking', $isibooking);
        $this->modelBooking->simpanDetail($where);
        $this->modelBooking->kosongkanData('temp');

        // Redirect to the booking info page
        return redirect()->to(base_url('booking/info'));
    }

    public function info()
    {
        $where = session('id_user');
        $data['user'] = session('nama');
        $data['judul'] = "Selesai Booking";
        $data['useraktif'] = $this->modelUser->cekData(['id' => session('id_user')])->getResult();
        
        $builder = $this->db->table('booking bo');
        $builder->select('*');
        $builder->join('booking_detail d', 'd.id_booking=bo.id_booking');
        $builder->join('buku bu', 'd.id_buku=bu.id');
        $builder->where('bo.id_user', $where);
        $query = $builder->get();
        $data['items'] = $query->getResultArray();
        $data['session'] = session();
        $data['numRows'] = $this->modelBooking->getDataWhere('temp', ['email_user' => session('email')])->getNumRows();

        echo view('templates/templates-user/header', $data);
        echo view('booking/info-booking', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');
    }

    public function exportToPdf()
    {
        $id_user = session('id_user');
        $data['user'] = session('nama');
        $data['judul'] = "Cetak Bukti Booking";
        $data['useraktif'] = $this->modelUser->cekData(['id' => session('id_user')])->getResult();
        $data['items'] = $this->db->query("select * from booking bo, booking_detail d, buku bu where d.id_booking=bo.id_booking and d.id_buku=bu.id and bo.id_user='$id_user'")->getResultArray();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);

        $html = view('booking/bukti-pdf', $data); // Load view into HTML variable
        
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape'); // Set paper size and orientation

        $dompdf->render(); // Render PDF

        $dompdf->stream("bukti-booking-$id_user.pdf", array('Attachment' => 0));
    }
}
