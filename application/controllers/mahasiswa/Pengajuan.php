<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Pengajuan extends MY_Role_Controller
{
    protected $allowed_roles = [3];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mahasiswa/M_pengajuan_mahasiswa', 'M_pengajuan');
    }

    public function index()
    {
        $this->render_module('Pengajuan Layanan', 'Pusat pengajuan layanan akademik dan kemahasiswaan.', 'Akademik');
    }
}
