<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan');
        $this->load->model('shared/M_akademik', 'M_akademik');

        // Wajib login untuk mengakses menu perwalian
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses menu perwalian.');
            redirect('auth');
            return;
        }

        if (!in_array((int)$this->session->userdata('role'), [1, 3], true)) {
            show_error('Menu perwalian hanya dapat diakses mahasiswa dan Superadmin.', 403);
            return;
        }
    }

    private function _render($page_title, $page_desc, $card_subtitle = '', $view_name = 'ambil_matakuliah') {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');

        // Tentukan akun mahasiswa yang dicek
        if ($user_role === 1) {
            $target_akun_id = (int)$this->input->get('mahasiswa_id');
            $target_mhs = $target_akun_id > 0
                ? $this->db->where('id', $target_akun_id)->where('role', 3)->where('deleted_at IS NULL', null, false)->get('akun')->row()
                : null;
            if (!$target_mhs) {
                $target_mhs = $this->db->where('role', 3)
                                       ->where('deleted_at IS NULL', null, false)
                                       ->order_by('id', 'ASC')
                                       ->get('akun')->row();
            }
            if (!$target_mhs) {
                show_error('Belum ada data mahasiswa aktif untuk pratinjau perwalian.', 404);
                return;
            }
            $target_akun_id = (int)$target_mhs->id;
            $data['is_admin_preview'] = true;
            $data['target_mahasiswa'] = $target_mhs;
            $akun_id = $target_akun_id;
        } else {
            $target_mhs = $this->M_akademik->get_account($current_user_id);
            if (!$target_mhs) {
                show_error('Data mahasiswa aktif tidak ditemukan.', 404);
                return;
            }
            $data['is_admin_preview'] = false;
            $data['target_mahasiswa'] = $target_mhs;
            $akun_id = $current_user_id;
        }

        // Cek Status Pembayaran Semester Mahasiswa untuk Akses KRS
        $status_krs = $this->M_keuangan->cek_status_krs($akun_id);

        // Simulasi status hanya untuk preview admin, bukan mahasiswa.
        if ($user_role !== 3) {
            $simulasi = $this->input->get('status', true) ?: $this->input->get('simulasi', true);
            if ($simulasi === 'terkunci') {
                $status_krs['buka_krs'] = false;
                $status_krs['status']   = 'BELUM_BAYAR';
            } elseif ($simulasi === 'terbuka' || $simulasi === 'lunas') {
                $status_krs['buka_krs'] = true;
                $status_krs['status']   = 'LUNAS';
            }
        }

        $data['title']             = $page_title . ' - Smart Campus';
        $data['page_title']        = $page_title;
        $data['page_desc']         = $page_desc;
        $data['card_subtitle']     = $card_subtitle;
        $data['breadcrumb_parent'] = 'Perwalian';
        $data['status_krs']        = $status_krs;
        $data['view_name']         = $view_name;
        $data['academic_period']   = $this->M_akademik->get_active_period();
        $data['krs']               = $this->M_akademik->get_krs($akun_id, $data['academic_period']);
        $data['advisor']           = $this->M_akademik->get_advisor_for_student($akun_id);
        $data['offerings']         = $this->M_akademik->get_offerings(
            $target_mhs->prodi,
            $target_mhs->semester,
            $data['academic_period']
        );
        $data['max_sks']           = M_akademik::MAX_SKS_PER_SEMESTER;
        $data['min_sks']           = M_akademik::MIN_SKS_PER_SEMESTER;
        $data['can_edit_krs']      = !$data['is_admin_preview']
            && $status_krs['buka_krs']
            && (!$data['krs'] || in_array($data['krs']->status, ['DRAFT', 'DITOLAK'], true));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);

        // Jika syarat pembayaran belum terpenuhi (belum LUNAS), tampilkan tampilan KRS Terkunci
        if (!$status_krs['buka_krs']) {
            $this->load->view('perwalian/krs_terkunci', $data);
        } else {
            $target_view = ($view_name === 'frs') ? 'perwalian/frs' : 'perwalian/ambil_matakuliah';
            $this->load->view($target_view, $data);
        }

        $this->load->view('templates/footer', $data);
    }

    public function index() {
        $this->ambil_matakuliah();
    }

    public function ambil_matakuliah() {
        $this->_render('Ambil Mata Kuliah', 'Pemilihan rencana studi dan kartu rencana studi semester baru', 'Formulir pemilihan mata kuliah', 'ambil_matakuliah');
    }

    public function frs() {
        $this->_render('Formulir Rencana Studi (FRS)', 'Persetujuan dan cetak formulir rencana studi dari dosen wali', 'Status verifikasi dan validasi FRS', 'frs');
    }

    public function simpan_krs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('perwalian');
            return;
        }
        if ((int)$this->session->userdata('role') !== 3) {
            show_error('Hanya mahasiswa yang dapat mengubah KRS.', 403);
            return;
        }

        $akun_id = (int)$this->session->userdata('id');
        $status_krs = $this->M_keuangan->cek_status_krs($akun_id);
        if (!$status_krs['buka_krs']) {
            $this->session->set_flashdata('error', $status_krs['pesan']);
            redirect('perwalian');
            return;
        }

        $action = $this->input->post('aksi', true);
        if (!in_array($action, ['draft', 'ajukan'], true)) {
            $this->session->set_flashdata('error', 'Aksi KRS tidak valid.');
            redirect('perwalian');
            return;
        }

        $offering_ids = $this->input->post('penawaran', true);
        if ($offering_ids === null) {
            $offering_ids = [];
        }
        $result = $this->M_akademik->save_student_krs($akun_id, $offering_ids, $action === 'ajukan');
        $this->session->set_flashdata($result['success'] ? 'success' : 'error', $result['message']);
        redirect($result['success'] && $action === 'ajukan' ? 'perwalian/frs' : 'perwalian');
    }
}
