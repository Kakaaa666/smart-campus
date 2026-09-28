<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Verifikasi extends MY_Role_Controller
{
    protected $allowed_roles = [3];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mahasiswa/M_verifikasi_mahasiswa', 'M_verifikasi');
    }

    public function index()
    {
        $this->render_module('Verifikasi Ijazah', 'Ajukan dan pantau proses verifikasi dokumen akademik.', 'Akademik');
    }
}
