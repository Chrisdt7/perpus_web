<?php

namespace App\Controllers;

class Latihan2 extends BaseController
{
    public function index()
    {
        echo "Selamat Datang.. selamat belajar Web Programming";
    }
    public function penjumlahan($n1, $n2)
    {
        $model = new \App\Models\Model_Latihan2();
        $hasil = $model->penjumlahan($n1, $n2);
        echo "<div class='welcome'>";
        echo "Hasil Penjumlahan dari ". $n1 ." + ". $n2 ." = ".$hasil;
        echo "</div>";
        $data = [
            'nilai1' => $n1,
            'nilai2' => $n2,
            'hasil' => $hasil,
        ];
        return view('View-Latihan2', $data);
    }
    public function pengurangan($n1, $n2)
    {
        $model = new \App\Models\Model_Latihan2();
        $hasil = $model->pengurangan($n1, $n2);
        echo "<div class='welcome'>";
        echo "Hasil Pengurangan dari ". $n1 ." - ". $n2 ." = ".$hasil;
        echo "</div>";
    }
    public function perkalian($n1, $n2)
    {
        $model = new \App\Models\Model_Latihan2();
        $hasil = $model->perkalian($n1, $n2);
        echo "<div class='welcome'>";
        echo "Hasil Perkalian dari ". $n1 ." * ". $n2 ." = ".$hasil;
        echo "</div>";
    }
    public function pembagian($n1, $n2)
    {
        $model = new \App\Models\Model_Latihan2();
        $hasil = $model->pembagian($n1, $n2);
        echo "<div class='welcome'>";
        echo "Hasil Pembagian dari ". $n1 ." / ". $n2 ." = ".$hasil;
        echo "</div>";
    }
}