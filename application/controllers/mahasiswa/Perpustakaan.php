<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Perpustakaan extends MY_Role_Controller
{
    protected $allowed_roles = [3];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mahasiswa/M_perpustakaan_mahasiswa', 'M_perpustakaan');
    }

    public function index()
    {
        $this->render_module('Perpustakaan', 'Cari koleksi, lihat status peminjaman, dan akses layanan perpustakaan.', 'Perpustakaan');
    }

    public function katalog() { $this->index(); }
    public function pinjaman() { $this->index(); }
}
