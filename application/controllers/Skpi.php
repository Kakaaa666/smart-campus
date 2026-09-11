<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skpi extends CI_Controller {
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
