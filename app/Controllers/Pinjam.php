<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelUser;
use App\Models\ModelPinjam;
use App\Models\ModelBooking;
use App\Helpers;

class Pinjam extends Controller
{
	protected $db;

    public function __construct()
    {
        helper(['form','uri', 'session', 'pustaka_helper']);
        \App\Helpers\cek_login();
        \App\Helpers\cek_user();
		$this->modelUser = new ModelUser();
		$this->db = db_connect();
    }

    public function index()
	{
		$this->modelPinjam = new ModelPinjam();
		$data['judul'] = "Data Pinjam";
		$data['user'] = $this->modelUser->cekData(['email' => session()->get('email')])->getRowArray();

		$data['pinjam'] = $this->modelPinjam->joinData();

		echo view('templates/header', $data);
		echo view('templates/sidebar', $data);
		echo view('templates/topbar', $data);
		echo view('pinjam/data-pinjam', $data);
		echo view('templates/footer');
	}

    public function daftarBooking()
    {
		$validation = \Config\Services::validation();
        $data['judul'] = "Daftar Booking";
        $data['user'] = $this->modelUser->cekData(['email' => session()->get('email')])->getRowArray();

        $data['pinjam'] = $this->db->query("SELECT * FROM booking")->getResultArray();

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('booking/daftar-booking', $data);
        echo view('templates/footer');
    }

	public function bookingDetail($id_booking)
	{
		$data['judul'] = "Booking Detail";
		$data['user'] = $this->modelUser->cekData(['email' => session()->get('email')])->getRowArray();

		$builder = $this->db->table('booking b');
		$builder->select('*');
		$builder->join('user u', 'b.id_user = u.id');
		$builder->where('b.id_booking', $id_booking);
		$query = $builder->get();
		$data['agt_booking'] = $query->getResultArray();

		$builder = $this->db->table('booking_detail d');
		$builder->select('d.id_buku, b.judul_buku, b.pengarang, b.penerbit, b.tahun_terbit');
		$builder->join('buku b', 'd.id_buku = b.id');
		$builder->where('d.id_booking', $id_booking);
		$query = $builder->get();
		$data['detail'] = $query->getResultArray();

		echo view('templates/header', $data);
		echo view('templates/sidebar', $data);
		echo view('templates/topbar', $data);
		echo view('booking/booking-detail', $data);
		echo view('templates/footer');
	}

	public function pinjamAct()
	{
		try {
			$this->modelBooking = new ModelBooking();
			$this->modelPinjam = new ModelPinjam();
			
			$id_booking = $this->request->uri->getSegment(3);
			$lama = $this->request->getPost('lama');
			$bo = $this->db->table('booking')->where('id_booking', $id_booking)->get()->getRow();
			$tglsekarang = date('Y-m-d');
			$no_pinjam = $this->modelBooking->kodeOtomatis('pinjam', 'no_pinjam');
			$databooking = [
				'no_pinjam' => $no_pinjam,
				'id_booking' => $id_booking,
				'tgl_pinjam' => $tglsekarang,
				'id_user' => $bo->id_user,
				'tgl_kembali' => date('Y-m-d', strtotime('+' . $lama . ' days', strtotime($tglsekarang))),
				'tgl_pengembalian' => '0000-00-00',
				'status' => 'Pinjam',
				'total_denda' => 0
			];

			// Example of throwing an exception if data is empty
			if (empty($databooking)) {
				throw new \Exception('Data array is empty.');
			}

			$this->modelPinjam->simpanPinjam($databooking);
			$this->modelPinjam->simpanDetail($id_booking, $no_pinjam);

			$denda = $this->request->getPost('denda');
			
			$this->db->query("update detail_pinjam set denda='$denda'");

			// Delete Data booking whose books are taken for borrowing
			$this->db->table('booking_detail')->where('id_booking', $id_booking)->delete();
			$this->db->table('booking')->where('id_booking', $id_booking)->delete();
			
			// Update dibooking and dipinjam in the buku table when the booked book is taken for borrowing
			$this->db->query("UPDATE buku, detail_pinjam SET buku.dipinjam=buku.dipinjam+1, buku.dibooking=buku.dibooking-1 WHERE buku.id=detail_pinjam.id_buku AND detail_pinjam.id_booking=$id_booking");

			session()->setFlashdata('pesan', '<div class="alert alert-message alert-success" role="alert">Data Peminjaman Berhasil Disimpan</div>');
			return redirect()->to(base_url('pinjam'));
		} catch (\Exception $e) {
			// Handle the exception
			$errorMessage = $e->getMessage();
			// You can log the error, display a user-friendly message, or take other actions here
			echo "Error: $errorMessage";
		}
	}

	public function ubahStatus()
	{
		$this->session = \Config\Services::session();
		$id_buku = $this->request->uri->getSegment(3);
		$no_pinjam = $this->request->uri->getSegment(4);
		$tgl = date('Y-m-d');
		$status = 'Kembali';

		// Update status and return date when the book is returned
		$this->db->query("UPDATE pinjam, detail_pinjam SET pinjam.status='$status', pinjam.tgl_pengembalian='$tgl' WHERE detail_pinjam.id_buku='$id_buku' AND pinjam.no_pinjam='$no_pinjam'");

		// Update stock and borrowed count in the book table
		$this->db->query("UPDATE buku, detail_pinjam SET buku.dipinjam=buku.dipinjam-1, buku.stok=buku.stok+1 WHERE buku.id=detail_pinjam.id_buku");

		// Set flash message
		$this->session->setFlashdata('pesan', '<div class="alert alert-message alert-success" role="alert"></div>');

		// Redirect to the pinjam page
		return redirect()->to(base_url('pinjam'));
	}
}