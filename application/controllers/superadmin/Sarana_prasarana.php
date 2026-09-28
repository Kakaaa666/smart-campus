<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Sarana_prasarana extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_sarana_prasarana_superadmin', 'M_sarana_prasarana');
    }

    public function index()
    {
        $this->render_module('Pusat Kendali Sarana Prasarana', 'Pantau fasilitas, inventaris, ruang, dan pemeliharaan seluruh kampus.', 'Sarana Prasarana');
    }
}
