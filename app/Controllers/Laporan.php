<?php

namespace App\Controllers;

use App\Models\ModelBuku;
use App\Models\ModelUser;
use Dompdf\dompdf;

class Laporan extends BaseController
{
	public function __construct()
    {
        helper(['uri', 'session', 'pustaka_helper']);
        $this->modelBuku = new ModelBuku();
        $this->modelUser = new ModelUser();
		$this->session = \Config\Services::session();
        $this->db = db_connect();
        $dompdf = new Dompdf();
    }

    public function laporan_buku()
    {
        $data = [];
        $data['judul'] = 'Laporan Data Buku';
        $data['user'] = $this->modelUser->cekData(['email' => $this->session->get('email')])->getRowArray();
        $data['buku'] = $this->modelBuku->getBuku();
        $data['kategori'] = $this->modelBuku->getKategori();
		$data['session'] = $this->session;

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('buku/laporan_buku', $data);
        echo view('templates/footer');
    }

	public function cetak_laporan_buku()
	{
		$data['buku'] = $this->modelBuku->getBuku();
		$data['kategori'] = $this->modelBuku->getKategori();

		echo view('buku/laporan_print_buku', $data);
	}

    public function laporan_buku_pdf()
    {
        $data['buku'] = $this->modelBuku->getBuku();
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/perpus_web/app/ThirdParty/dompdf/autoload.inc.php";
        $dompdf = new dompdf();
        $html = view('buku/laporan_pdf_buku', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan_data_buku.pdf", ['Attachment' => 0]);
    }

    public function export_excel()
    {
        $data = [
            'title' => 'Laporan Buku',
            'buku' => $this->modelBuku->getBuku()
        ];

        return view('buku/export_excel_buku', $data);
    }

    public function laporan_pinjam()
    {
        $data['judul'] = 'Laporan Data Peminjaman';
        $data['user'] = $this->modelUser->where('email', $this->session->get('email'))->first();
        $data['laporan'] = $this->db->query("SELECT * FROM pinjam p, detail_pinjam d, buku b, user u WHERE d.id_buku=b.id AND p.id_user=u.id AND p.no_pinjam=d.no_pinjam")->getResultArray();

        echo view('templates/header', $data);
        echo view('templates/sidebar');
        echo view('templates/topbar', $data);
        echo view('pinjam/laporan-pinjam', $data);
        echo view('templates/footer');
    }

    public function cetak_laporan_pinjam()
    {
        $laporan = $this->db->query("SELECT * FROM pinjam p, detail_pinjam d, buku b, user u WHERE d.id_buku=b.id AND p.id_user=u.id AND p.no_pinjam=d.no_pinjam")->getResultArray();
        
        $data = [
            'laporan' => $laporan
        ];

        return view('pinjam/laporan-print-pinjam', $data);
    }

    public function laporan_pinjam_pdf()
    {
        $laporan = $this->db->query("SELECT * FROM pinjam p, detail_pinjam d, buku b, user u WHERE d.id_buku=b.id AND p.id_user=u.id AND p.no_pinjam=d.no_pinjam")->getResultArray();

        $data = [
            'laporan' => $laporan
        ];

        $root = $_SERVER['DOCUMENT_ROOT'];
        include $root . "/perpus_web/app/ThirdParty/dompdf/autoload.inc.php";
        $dompdf = new dompdf();
        $html = view('pinjam/laporan-pdf-pinjam', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan data peminjaman.pdf", ['Attachment' => 0]);
    }    

    public function export_excel_pinjam()
    {
        $laporan = $this->db->query("select * from pinjam p, detail_pinjam d, buku b, user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->getResultArray();

        $data = [
            'title'     => 'Laporan Buku',
            'laporan'   => $laporan
        ];

        return view('pinjam/export-excel-pinjam', $data);
    }
}