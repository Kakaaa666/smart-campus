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
        $period = $this->M_akademik->get_active_period();
        $data = [
            'title' => 'Pusat Kendali Akademik - Smart Campus',
            'page_title' => 'Pusat Kendali Akademik',
            'page_desc' => 'Pantau penawaran mata kuliah dan status KRS pada periode aktif.',
            'card_subtitle' => 'Akademik',
            'active_period' => $period,
            'overview' => $this->M_akademik->get_period_overview($period),
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/superadmin_overview', $data);
        $this->load->view('templates/footer', $data);
    }

    public function jadwal() { $this->index(); }
    public function nilai() { $this->index(); }
    public function khs() { $this->index(); }
    public function transkrip() { $this->index(); }
    public function kurikulum() { $this->index(); }
    public function matakuliah() { $this->index(); }
    public function kalender() { $this->index(); }
}
