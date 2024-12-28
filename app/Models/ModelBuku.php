<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBuku extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['*'];

    public function getBuku()
    {
        return $this->findAll();
    }

    public function bukuWhere($where)
    {
        return $this->where($where)->findAll();
    }

    public function simpanBuku($data = null)
    {
        return $this->insert($data);
    }

    public function updateBuku($data = null, $where = null)
    {
        return $this->update($where, $data);
    }

    public function hapusBuku($where = null)
    {
        return $this->delete($where);
    }

    public function total($field, $where)
    {
        $builder = $this->selectSum($field)->from('buku');
        if (!empty($where) && count($where) > 0)
        {
            $builder->$where($where);
        }
        return $builder->get()->getRow()->$field;
    }

    // manajemen kategori
    public function getKategori()
    {
        return $this->db->table('kategori')->get()->getResultArray();
    }

    public function kategoriWhere($where)
    {
        return $this->db->table('kategori')->getWhere($where)->getResultArray();
    }

    public function simpanKategori($data = null)
    {
        return $this->db->table('kategori')->insert($data);
    }

    public function hapusKategori($where = null)
    {
        return $this->db->table('kategori')->delete($where);
    }

    public function updateKategori($where = null, $data = null)
    {
        return $this->db->table('kategori')->update($data, $where);
    }

    public function joinKategoriBuku($where)
    {
        return $this->select('buku.*, kategori.*')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori')
            ->where($where)
            ->get()
            ->getResult();
    }
    
    public function getLimitBuku()
    {
        $this->db->limit(5);
        return $this->db->get('buku');
    }
}
