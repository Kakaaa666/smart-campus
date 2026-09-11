<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merdeka_belajar extends CI_Controller {
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
