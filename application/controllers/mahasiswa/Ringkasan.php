<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Ringkasan extends MY_Role_Controller {
    protected $allowed_roles = [3];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_akademik_mahasiswa', 'M_akademik');
    }
    public function index() {
        $data['title'] = 'Ringkasan - Smart Campus';
        $data['page_title'] = 'Ringkasan Aktivitas';
        $data['page_desc'] = 'Ringkasan capaian, IPK, dan progres perkuliahan';
        $data['card_subtitle'] = 'Statistik & Ringkasan Perkuliahan';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
