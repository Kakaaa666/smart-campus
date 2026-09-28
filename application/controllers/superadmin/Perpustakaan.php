<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Perpustakaan extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_perpustakaan_superadmin', 'M_perpustakaan');
    }

    public function index()
    {
        $this->render_module('Pusat Kendali Perpustakaan', 'Pantau integrasi katalog, sirkulasi, dan layanan perpustakaan.', 'Perpustakaan');
    }

    public function katalog() { $this->index(); }
    public function sirkulasi() { $this->index(); }
}