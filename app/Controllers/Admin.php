<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelUser;
use App\Models\ModelBuku;
use App\Models\ModelBooking;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['uri', 'session', 'pustaka_helper']);
        \App\Helpers\cek_login();
        \App\Helpers\cek_user();
        $modelUser = new ModelUser();
        $modelBuku = new ModelBuku();
        $modelBooking = new ModelBooking();
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';
        $data['user'] = $modelUser->cekData(['email' => session()->get('email')])->getRowArray();
        $data['anggota'] = $modelUser->getUserLimit()->getResultArray();
        $data['buku'] = $modelBuku->getLimitBuku()->getResultArray();

        // Updating stock and booking in the book table
        $detail = $this->db->query("SELECT * FROM booking, booking_detail WHERE DAY(curdate()) < DAY(batas_ambil) AND booking.id_booking=booking_detail.id_booking")->getResultArray();
        foreach ($detail as $key) {
            $id_buku = $key['id_buku'];
            $batas = $key['tgl_booking'];
            $tglawal = date_create($batas);
            $tglskrg = date_create();
            $beda = date_diff($tglawal, $tglskrg);
            if ($beda->days > 2) {
                $this->db->query("UPDATE buku SET stok=stok+1, dibooking=dibooking-1 WHERE id='$id_buku'");
            }
        }

        // Automatically deleting booking data that is over 2 days old
        $bookings = $modelBooking->getData('booking');
        if (!empty($bookings)) {
            foreach ($bookings as $bo) {
                $id_booking = $bo->id_booking;
                $tglbooking = $bo->tgl_booking;
                $tglawal = date_create($tglbooking);
                $tglskrg = date_create();
                $beda = date_diff($tglawal, $tglskrg);
                if ($beda->days > 2) {
                    $this->db->query("DELETE FROM booking WHERE id_booking='$id_booking'");
                    $this->db->query("DELETE FROM booking_detail WHERE id_booking='$id_booking'");
                }
            }
        }

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('admin/index', $data);
        echo view('templates/footer');
    }
}
