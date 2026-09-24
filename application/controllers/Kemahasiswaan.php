<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kemahasiswaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }

        if ((int)$this->session->userdata('role') !== 1) {
            show_error('Halaman ini hanya dapat diakses oleh Super Admin.', 403);
        }
    }

    public function index()
    {
        $data = [
            'title'       => 'Biro Kemahasiswaan - Smart Campus',
            'page_title'  => 'Biro Kemahasiswaan',
            'page_desc'   => 'Pusat inspeksi dan integrasi layanan kemahasiswaan.',
            'card_subtitle' => 'Modul operasional kemahasiswaan tersedia melalui menu layanan terkait.'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}