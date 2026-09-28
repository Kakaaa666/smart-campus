<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Merdeka_belajar extends MY_Role_Controller {
    protected $allowed_roles = [3];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_kemahasiswaan_mahasiswa', 'M_kemahasiswaan');
    }
    public function index() {
        $data['title'] = 'Merdeka Belajar - Smart Campus';
        $data['page_title'] = 'Program Merdeka Belajar (MBKM)';
        $data['page_desc'] = 'Pendaftaran, pertukaran mahasiswa, magang, dan konversi SKS';
        $data['card_subtitle'] = 'Status Partisipasi Program MBKM';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
