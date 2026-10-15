<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Skpi extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_kemahasiswaan_superadmin', 'M_kemahasiswaan');
    }

    public function index()
    {
        redirect('admin/skpi');
    }
}
