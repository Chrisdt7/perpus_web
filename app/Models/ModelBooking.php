<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBooking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    protected $allowedFields = ['*'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getData($table)
    {
        return $this->db->table($table)->get()->getRow();
    }

    public function getDataWhere($table, $where)
    {
        return $this->db->table($table)->getWhere($where);
    }

    public function getOrderByLimit($table, $order, $limit)
    {
        return $this->db->table($table)->orderBy($order, 'desc')->limit($limit)->get();
    }

    public function joinOrder($where)
    {
        return $this->table('booking')
            ->join('booking_detail d', 'd.id_booking=booking.id_booking')
            ->join('buku bu', 'bu.id=d.id_buku')
            ->where('booking.id_user', $where)
            ->get();

        if ($query->resultID) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function simpanDetail($where = null)
    {
        $sql = "INSERT INTO booking_detail (id_booking, id_buku) SELECT booking.id_booking, temp.id_buku FROM booking, temp WHERE temp.id_user = booking.id_user AND booking.id_user='$where'";
        $this->db->query($sql);
    }

    public function insertData($table, $data)
    {
        $builder = $this->db->table($table);
        $builder->insert($data);
    }

    public function updateData($table, $data, $where)
    {
        $builder = $this->db->table($table);
        $builder->update($data, $where);
    }

    public function deleteData($where, $table)
    {
        $builder = $this->db->table($table);
        $builder->where($where);
        $builder->delete();
    }

    public function find($id = null, $column = '*')
    {
        if ($id === null) {
            return $this->findAll($column);
        }

        return $this->asArray()
                    ->where('id', $id)
                    ->first($column);
    }

    public function kosongkanData($table)
    {
        return $this->db->table($table)->truncate();
    }

    public function createTemp()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS temp(id_booking varchar(12), tgl_booking DATETIME, email_user varchar(128), id_buku int)');
    }

    public function selectJoin()
    {
        return $this->db->table('booking')
            ->join('booking_detail', 'booking_detail.id_booking=booking.id_booking')
            ->join('buku', 'booking_detail.id_buku=buku.id')
            ->get();
    }

    public function showtemp($where)
    {
        return $this->db->table('temp')->getWhere($where);
    }

    public function kodeOtomatis($tabel, $key)
    {
        $kodejadi = '';

        // Attempt to generate a unique code up to 10 times
        for ($i = 0; $i < 10; $i++) {
            $kodejadi = date('dmY') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

            // Check if the generated code already exists in the database
            $query = $this->db->table($tabel)
                ->where($key, $kodejadi)
                ->countAllResults();

            if ($query == 0) {
                // If the code doesn't exist, break the loop
                break;
            }
        }

        return $kodejadi;
    }
}
