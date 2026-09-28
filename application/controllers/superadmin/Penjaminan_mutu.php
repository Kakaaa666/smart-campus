<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Penjaminan_mutu extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_penjaminan_mutu_superadmin', 'M_penjaminan_mutu');
    }

    public function index()
    {
        $this->render_module('Pusat Kendali Penjaminan Mutu', 'Pantau standar, audit, evaluasi, dan tindak lanjut mutu seluruh biro.', 'Penjaminan Mutu');
    }
}
