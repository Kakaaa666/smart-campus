<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller
{
    protected $allowed_roles = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_akademik_superadmin', 'M_akademik');
    }

    public function index()
    {
        $this->render_module('Pusat Kendali Akademik', 'Pantau dan kelola seluruh layanan akademik perguruan tinggi.', 'Akademik');
    }

    public function jadwal() { $this->index(); }
    public function nilai() { $this->index(); }
    public function khs() { $this->index(); }
    public function transkrip() { $this->index(); }
    public function kurikulum() { $this->index(); }
    public function matakuliah() { $this->index(); }
    public function kalender() { $this->index(); }
}
