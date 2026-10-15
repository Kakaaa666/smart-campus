<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/shared/Keuangan_core.php';

class Keuangan extends Keuangan_core
{
    public function __construct()
    {
        parent::__construct();
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($this->session->userdata('role') === null);
        if (!$is_mahasiswa) {
            show_error('Halaman ini hanya dapat diakses oleh Mahasiswa.', 403);
        }
    }
}
