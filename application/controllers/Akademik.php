<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akademik extends CI_Controller {

    private function _render($page_title, $page_desc, $card_subtitle = '') {
        $data['title'] = $page_title . ' - Smart Campus';
        $data['page_title'] = $page_title;
        $data['page_desc'] = $page_desc;
        $data['card_subtitle'] = $card_subtitle;
        $data['breadcrumb_parent'] = 'Akademik';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }

    public function index() {
        $this->jadwal();
    }

    public function jadwal() {
        $this->_render('Jadwal Kuliah', 'Informasi jadwal perkuliahan semester aktif', 'Daftar jadwal mata kuliah dan ruangan');
    }

    public function nilai() {
        $this->_render('Nilai Perkuliahan', 'Daftar nilai evaluasi hasil belajar', 'Transkrip nilai semester aktif');
    }

    public function khs() {
        $this->_render('Kartu Hasil Studi (KHS)', 'Informasi KHS per semester mahasiswa', 'Cetak dan unduh kartu hasil studi');
    }

    public function transkrip() {
        $this->_render('Transkrip Akademik', 'Daftar rekapitulasi seluruh nilai mahasiswa', 'Transkrip nilai kumulatif');
    }

    public function kurikulum() {
        $this->_render('Kurikulum Program Studi', 'Daftar kurikulum dan sebaran mata kuliah', 'Struktur kurikulum semester');
    }

    public function matakuliah() {
        $this->_render('Daftar Mata Kuliah', 'Katalog mata kuliah program studi', 'Informasi SKS dan dosen pengampu');
    }

    public function kalender() {
        $this->_render('Kalender Akademik', 'Agenda dan jadwal penting kegiatan akademik kampus', 'Agenda tahun akademik berjalan');
    }
}
