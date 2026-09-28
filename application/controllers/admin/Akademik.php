<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['akademik'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_akademik_admin', 'M_akademik');
    }

    public function index()
    {
        $this->render_module('Operasional Akademik', 'Kelola jadwal, nilai, KHS, kurikulum, mata kuliah, dan kalender akademik.', 'Akademik');
    }

    public function jadwal() { $this->index(); }
    public function nilai() { $this->index(); }
    public function khs() { $this->index(); }
    public function kurikulum() { $this->index(); }
    public function matakuliah() { $this->index(); }
    public function kalender() { $this->index(); }
}
