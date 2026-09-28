<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Pengguna extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_pengguna_superadmin', 'M_pengguna');
    }

    public function index()
    {
        $this->render_module('Manajemen Pengguna', 'Kelola akun, role, status aktif, dan akses seluruh pengguna sistem.', 'Rektorat');
    }

    public function mahasiswa() { $this->index(); }
    public function admin() { $this->index(); }
}
