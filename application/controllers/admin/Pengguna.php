<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Pengguna extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['akademik'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_pengguna_admin', 'M_pengguna');
    }

    public function index()
    {
        $this->render_module('Data Mahasiswa', 'Kelola data operasional mahasiswa sesuai kewenangan biro.', 'Akademik');
    }

    public function mahasiswa() { $this->index(); }
}
