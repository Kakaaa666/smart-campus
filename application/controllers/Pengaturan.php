<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

    private function _render($page_title, $page_desc, $card_subtitle = '') {
        $data['title'] = $page_title . ' - Smart Campus';
        $data['page_title'] = $page_title;
        $data['page_desc'] = $page_desc;
        $data['card_subtitle'] = $card_subtitle;
        $data['breadcrumb_parent'] = 'Pengaturan';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }

    public function index() {
        $this->profil();
    }

    public function profil() {
        $this->_render('Profil Pengguna', 'Kelola informasi biodata dan akun mahasiswa', 'Data Diri Mahasiswa');
    }

    public function ubah_password() {
        $this->_render('Ubah Password', 'Pembaruan kata sandi akun portal Smart Campus', 'Formulir Kata Sandi Baru');
    }
}
