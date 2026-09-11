<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi extends CI_Controller {
    public function index() {
        $data['title'] = 'Verifikasi Ijazah - Smart Campus';
        $data['page_title'] = 'Verifikasi Keaslian Ijazah & Transkrip';
        $data['page_desc'] = 'Layanan cek keabsahan dokumen kelulusan dan nomor PIN';
        $data['card_subtitle'] = 'Formulir Validasi Dokumen';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
