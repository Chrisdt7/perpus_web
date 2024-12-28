<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_Latihan2 extends Model
{   
    //membuat variable untuk menampung nilai
    public $nilai1, $nilai2, $hasil;
    //method penjumlahan
    public function penjumlahan($n1 = null, $n2 = null)
    {
        $this->nilai1 = $n1;
        $this->nilai2 = $n2;
        $this->hasil  = $this->nilai1 + $this->nilai2;
        return $this->hasil;
    }
    //method pengurangan
    public function pengurangan($n1 = null, $n2 = null)
    {
        $this->nilai1 = $n1;
        $this->nilai2 = $n2;
        $this->hasil  = $this->nilai1 - $this->nilai2;
        return $this->hasil;
    }
    public function perkalian($n1 = null, $n2 = null)
    {
        $this->nilai1 = $n1;
        $this->nilai2 = $n2;
        $this->hasil  = $this->nilai1 * $this->nilai2;
        return $this->hasil;
    }
    public function pembagian($n1 = null, $n2 = null)
    {
        $this->nilai1 = $n1;
        $this->nilai2 = $n2;
        $this->hasil  = $this->nilai1 / $this->nilai2;
        return $this->hasil;
    }
}