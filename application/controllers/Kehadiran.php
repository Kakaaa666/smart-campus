<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kehadiran extends CI_Controller {
    public function index() {
        $data['title'] = 'Kehadiran Kuliah - Smart Campus';
        $data['page_title'] = 'Presensi & Kehadiran Kuliah';
        $data['page_desc'] = 'Rekapitulasi kehadiran kuliah per mata kuliah';
        $data['card_subtitle'] = 'Persentase & Riwayat Kehadiran';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
