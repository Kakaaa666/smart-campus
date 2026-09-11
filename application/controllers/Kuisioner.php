<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuisioner extends CI_Controller {
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
