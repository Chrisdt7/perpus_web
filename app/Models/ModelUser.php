<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['nama','email','password', 'image'];

    public function simpanData($data = null)
    {
        return $this->insert($data);
    }
    
    public function cekData($where = null)
    {
        return $this->select('*')
                    ->where($where)
                    ->get();
    }

    public function getUserWhere($email = null)
    {
        return $this->where('email', $email)->get();
    }

    public function cekUserAccess($where = null)
    {
        return $this->select('*')
            ->from('access_menu')
            ->where($where)
            ->get();
    }

    public function getUserLimit($limit = 10, $offset = 0)
    {
        return $this->limit($limit, $offset)->get();
    }
}
