<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Sarana_prasarana extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['sarana_prasarana'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_sarana_prasarana_admin', 'M_sarana_prasarana');
    }

    public function index()
    {
        $this->render_module('Dashboard Sarana Prasarana', 'Kelola fasilitas, inventaris, ruang, dan pemeliharaan kampus.', 'Sarana Prasarana');
    }

    public function inventaris() { $this->index(); }
    public function pemeliharaan() { $this->index(); }
}
