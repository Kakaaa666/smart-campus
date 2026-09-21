<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('M_keuangan');

        // Wajib login untuk mengakses menu perwalian
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses menu perwalian.');
            redirect('auth');
        }
    }

    private function _render($page_title, $page_desc, $card_subtitle = '') {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');

        // Tentukan akun mahasiswa yang dicek
        if ($user_role !== 3) {
            $target_akun_id = (int)$this->input->get('mahasiswa_id');
            if ($target_akun_id <= 0) {
                $target_akun_id = 3; // Akun Mahasiswa Muhammad Eka untuk simulasi Super Admin
            }
            $target_mhs = $this->db->get_where('akun', ['id' => $target_akun_id])->row();
            $data['is_admin_preview'] = true;
            $data['target_mahasiswa'] = $target_mhs;
            $akun_id = $target_akun_id;
        } else {
            $data['is_admin_preview'] = false;
            $data['target_mahasiswa'] = null;
            $akun_id = $current_user_id;
        }

        // Cek Status Pembayaran Semester Mahasiswa untuk Akses KRS
        $status_krs = $this->M_keuangan->cek_status_krs($akun_id);

        $data['title']             = $page_title . ' - Smart Campus';
        $data['page_title']        = $page_title;
        $data['page_desc']         = $page_desc;
        $data['card_subtitle']     = $card_subtitle;
        $data['breadcrumb_parent'] = 'Perwalian';
        $data['status_krs']        = $status_krs;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);

        // Jika syarat pembayaran belum terpenuhi (belum LUNAS), tampilkan tampilan KRS Terkunci
        if (!$status_krs['buka_krs']) {
            $this->load->view('perwalian/krs_terkunci', $data);
        } else {
            $this->load->view('templates/content_page', $data);
        }

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
