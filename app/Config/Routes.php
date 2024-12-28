<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'home::index');

$routes->get('home', 'home::index');

$routes->get('web', 'Web::index');

$routes->get('web/about', 'Web::about');

$routes->get('latihan1', 'Latihan1::index');

$routes->get('latihan2', 'Latihan2::index');

$routes->get('latihan2/penjumlahan/(:num)/(:num)', 'Latihan2::penjumlahan/$1/$2');

$routes->get('latihan2/pengurangan/(:num)/(:num)', 'Latihan2::pengurangan/$1/$2');

$routes->get('latihan2/perkalian/(:num)/(:num)', 'Latihan2::perkalian/$1/$2');

$routes->get('latihan2/pembagian/(:num)/(:num)', 'Latihan2::pembagian/$1/$2');

$routes->get('matakuliah', 'Matakuliah::index');

$routes->post('matakuliah/cetak', 'Matakuliah::cetak');

// Perpus-Web
$routes->add('autentifikasi', 'Autentifikasi::index');
$routes->add('autentifikasi/lupaPassword', 'Autentifikasi::lupaPassword');
$routes->add('autentifikasi/registrasi', 'Autentifikasi::registrasi');
$routes->add('autentifikasi/login', 'Autentifikasi::login');
$routes->add('autentifikasi/logout', 'Autentifikasi::logout');

$routes->add('user', 'User::index');
$routes->get('user/index', 'User::index');
$routes->get('user/anggota', 'User::anggota');
$routes->match(['get', 'post'], 'user/ubahprofil', 'User::ubahProfil');

$routes->add('buku', 'Buku::kategori');
$routes->get('home/detailBuku/(:any)', 'Home::detailBuku/$1');

$routes->add('member', 'Member::index');
$routes->get('member/myprofil', 'Member::myProfil');
$routes->match(['get', 'post'], 'member/ubahprofil', 'Member::ubahProfil');
$routes->get('member/logout', 'Member::logout');

$routes->get('booking', 'Booking::index');
$routes->get('booking/tambahBooking/(:segment)', 'Booking::tambahBooking/$1');
$routes->get('booking/hapusbooking/(:num)', 'Booking::hapusbooking/$1');
$routes->get('booking/bookingSelesai/(:num)', 'Booking::bookingSelesai/$1');
$routes->get('booking/info', 'Booking::info');
$routes->get('booking/exportToPdf/(:num)', 'Booking::exportToPdf/$1');

$routes->get('pinjam/daftarBooking', 'Pinjam::daftarBooking');
$routes->get('pinjam/bookingDetail/(:segment)', 'Pinjam::bookingDetail/$1');
$routes->match(['get', 'post'], 'pinjam/pinjamAct/(:segment)', 'Pinjam::pinjamAct/$1');
$routes->get('pinjam/', 'Pinjam::index');
$routes->get('pinjam/ubahStatus/(:segment)/(:segment)', 'Pinjam::ubahStatus/$1/$2');

$routes->get('laporan/laporan_buku', 'Laporan::laporan_buku');
$routes->get('laporan/cetak_laporan_buku', 'Laporan::cetak_laporan_buku');
$routes->get('laporan/laporan_buku_pdf', 'Laporan::laporan_buku_pdf');
$routes->get('laporan/export_excel', 'Laporan::export_excel');
$routes->get('laporan/laporan_pinjam', 'Laporan::laporan_pinjam');
$routes->get('laporan/cetak_laporan_pinjam', 'Laporan::cetak_laporan_pinjam');
$routes->get('laporan/laporan_pinjam_pdf', 'Laporan::laporan_pinjam_pdf');
$routes->get('laporan/export_excel_pinjam', 'Laporan::export_excel_pinjam');