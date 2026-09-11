<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {
    public function index() {
        $data['title'] = 'Keuangan - Smart Campus';
        $data['page_title'] = 'Informasi Keuangan Mahasiswa';
        $data['page_desc'] = 'Tagihan, pembayaran SPP, dan riwayat transaksi';
        $data['card_subtitle'] = 'Status Pembayaran & Tagihan';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }
}
