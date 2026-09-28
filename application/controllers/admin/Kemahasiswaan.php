<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Kemahasiswaan extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['kemahasiswaan'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_kemahasiswaan_admin', 'M_kemahasiswaan');
    }

    public function index()
    {
        $this->render_module('Layanan Kemahasiswaan', 'Kelola layanan kuisioner, Merdeka Belajar, dan dokumen kemahasiswaan.', 'Kemahasiswaan');
    }

    public function kuisioner() { $this->index(); }
    public function merdeka_belajar() { $this->index(); }
    public function skpi() { $this->index(); }
}
