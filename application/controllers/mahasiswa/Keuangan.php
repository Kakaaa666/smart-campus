<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/shared/Keuangan_core.php';

class Keuangan extends Keuangan_core
{
    public function __construct()
    {
        parent::__construct();
        if ((int)$this->session->userdata('role') !== 3) {
            show_error('Halaman ini hanya dapat diakses oleh Mahasiswa.', 403);
        }
    }
}
