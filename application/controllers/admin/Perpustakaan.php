<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Perpustakaan extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['perpustakaan'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_perpustakaan_admin', 'M_perpustakaan');
    }

    public function index()
    {
        $this->render_module('Operasional Perpustakaan', 'Kelola katalog, sirkulasi, dan layanan perpustakaan kampus.', 'Perpustakaan');
    }

    public function katalog() { $this->index(); }
    public function sirkulasi() { $this->index(); }
}
