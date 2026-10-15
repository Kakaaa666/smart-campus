<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Penjaminan_mutu extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['penjaminan_mutu'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_penjaminan_mutu_admin', 'M_penjaminan_mutu');
    }

    public function index()
    {
        $this->render_module('Dashboard Penjaminan Mutu', 'Kelola standar, audit mutu, evaluasi, dan tindak lanjut peningkatan mutu.', 'Penjaminan Mutu');
    }

    public function standar() { $this->index(); }
    public function audit() { $this->index(); }
}
