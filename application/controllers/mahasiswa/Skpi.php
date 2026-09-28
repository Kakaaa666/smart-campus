<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Skpi extends MY_Role_Controller {
    protected $allowed_roles = [3];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_kemahasiswaan_mahasiswa', 'M_kemahasiswaan');
    }
    public function index() {
        $data['title'] = 'Pengajuan SKPI - Smart Campus';
        $data['page_title'] = 'Surat Keterangan Pendamping Ijazah (SKPI)';
        $data['page_desc'] = 'Pengajuan sertifikat, prestasi, dan kegiatan pendamping ijazah';
        $data['card_subtitle'] = 'Daftar Portofolio & Pengajuan SKPI';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
