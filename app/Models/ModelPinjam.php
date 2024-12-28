<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPinjam extends Model
{
    protected $table = 'pinjam'; 
    protected $primaryKey = 'no_pinjam';
    protected $allowedFields = ['no_pinjam', 'tgl_pinjam', 'id_booking', 'id_user', 'tgl_kembali', 'tgl_pengembalian', 'status', 'total_denda'];
    protected $db;

    public function simpanPinjam($databooking)
    {
        try {
            if (empty($databooking)) {
                throw new \Exception('Data array is empty.');
            }

            // Debugging: Print the data before insertion
            print_r($databooking);

            // Insert the data into the database
            $result = $this->insert($databooking);

            if (!$result) {
                throw new \Exception('Failed to insert data.');
            }
        } catch (\Exception $e) {
            // Handle the exception
            $errorMessage = $e->getMessage();
            // Log the error or display a user-friendly message
            echo "Error: $errorMessage";
        }
    }

    public function selectData($where)
    {
        return $this->where($where)->get()->getResultArray();
    }

    public function updateData($data, $where)
    {
        $this->update($data, $where);
    }

    public function deleteData($table, $where)
    {
        $this->db->table($table)->where($where)->delete();
    }

    public function joinData()
    {
        return $this->select('*')
            ->join('detail_pinjam', 'detail_pinjam.no_pinjam=pinjam.no_pinjam', 'right')
            ->get()
            ->getResultArray();
    }

    public function simpanDetail($idbooking)
    {
        $sql = "INSERT INTO detail_pinjam (no_pinjam, id_buku) 
                SELECT DISTINCT pinjam.no_pinjam, booking_detail.id_buku 
                FROM pinjam 
                JOIN booking_detail ON pinjam.id_booking = booking_detail.id_booking
                WHERE booking_detail.id_booking = $idbooking";

        $this->query($sql);
    }
}