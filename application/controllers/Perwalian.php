<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian extends CI_Controller {

    private function _render($page_title, $page_desc, $card_subtitle = '') {
        $data['title'] = $page_title . ' - Smart Campus';
        $data['page_title'] = $page_title;
        $data['page_desc'] = $page_desc;
        $data['card_subtitle'] = $card_subtitle;
        $data['breadcrumb_parent'] = 'Perwalian';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }

    public function index() {
        $this->ambil_matakuliah();
    }

    public function ambil_matakuliah() {
        $this->_render('Ambil Mata Kuliah', 'Pemilihan rencana studi dan kartu rencana studi semester baru', 'Formulir pemilihan mata kuliah');
    }

    public function frs() {
        $this->_render('Formulir Rencana Studi (FRS)', 'Persetujuan dan cetak formulir rencana studi dari dosen wali', 'Status verifikasi dan validasi FRS');
    }
}
