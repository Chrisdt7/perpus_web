<?php

namespace App\Helpers;

use CodeIgniter\Session\Session;
use CodeIgniter\HTTP\RedirectResponse;

function cek_login(): ?RedirectResponse
{
    $session = session();
    if (!$session->has('email')) {
        $session->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Akses ditolak. Anda belum login!!</div>');
        if ($session->has('role_id') && $session->get('role_id') == 1) {
            return redirect()->to('autentifikasi');
        } else {
            return redirect()->to('home');
        }
    } else {
        return null;
    }
}

function cek_user(): ?RedirectResponse
{
    $session = session();
    $role_id = $session->get('role_id');
    if ($role_id != 1) {
        $session->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Akses tidak diizinkan</div>');
        return redirect()->to('home');
    }
    return null;
}