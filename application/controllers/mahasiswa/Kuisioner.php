<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Kuisioner extends MY_Role_Controller {
    protected $allowed_roles = [3];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_kemahasiswaan_mahasiswa', 'M_kemahasiswaan');
    }
    public function index() {
        $data['title'] = 'Kuisioner - Smart Campus';
        $data['page_title'] = 'Kuisioner & Evaluasi';
        $data['page_desc'] = 'Pengisian kuisioner evaluasi pembelajaran dan dosen';
        $data['card_subtitle'] = 'Daftar Kuisioner Aktif';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
