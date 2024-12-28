<?php

namespace App\Controllers;

class Matakuliah extends BaseController
{
    public function index()
        {
            
            $validation = \Config\Services::validation();
            
            return view('view-form-matakuliah', ['validation' => $validation]);

        }
    public function cetak()
        {
            $rules = [
                'kode' => 'required|min_length[3]',
                'nama' => 'required|min_length[3]'
            ];

            $message = [
                'kode' => [
                    'required'   => 'Kode Matakuliah Harus Diisi',
                    'min_length' => 'Kode Matakuliah Terlalu Pendek'
                ],
                'nama' => [
                    'required'   => 'Nama Matakuliah Harus Diisi',
                    'min_length' => 'Nama Matakuliah Terlalu Pendek'
                ]
            ];

            $validation = \Config\Services::validation();

            $validation->setRules($rules, $message);

            if (!$validation->withRequest($this->request)->run()) {
                return view('view-form-matakuliah', [
                    'validation' => $validation
                ]);
            }
            else {
                $data = [
                    'kode' => $this->request->getPost('kode'), // Use getPost to retrieve POST data
                    'nama' => $this->request->getPost('nama'),
                    'sks' => $this->request->getPost('sks')
                ];
                return view('view-data-matakuliah', $data);
            }
        }        
}