<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Rps extends MY_Role_Controller
{
    protected $allowed_roles = [1, 4]; // Dosen (4) dan Super Admin (1)

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_obe');
        $this->load->helper(['form', 'url']);
    }

    /**
     * Halaman Utama: Daftar Mata Kuliah & Status RPS OBE
     * Dosen hanya melihat mata kuliah yang diampunya (dengan fallback prodi)
     */
    public function index()
    {
        $user_id = (int)$this->session->userdata('id');
        $user_role = (int)$this->session->userdata('role');
        $is_superadmin = ($user_role === 1);
        $is_kaprodi = ((int)$this->session->userdata('is_kaprodi') === 1);
        $user_prodi = $this->session->userdata('prodi') ?: 'Teknik Informatika';

        // Ambil daftar mata kuliah berdasarkan hak akses dosen pengampu
        $courses = $this->M_obe->get_dosen_rps_courses($user_id, $user_prodi, $is_superadmin, $is_kaprodi);

        $published_count = count(array_filter($courses, function($c) { return isset($c->status) && $c->status === 'PUBLISHED'; }));
        $draft_count = count($courses) - $published_count;

        $data = [
            'title'           => 'Desain Pembelajaran & RPS OBE - Smart Campus',
            'page_title'      => 'Desain Pembelajaran & RPS OBE',
            'page_desc'       => 'Pengelolaan Rencana Pembelajaran Semester (RPS), turunan CPMK & Sub-CPMK berbasis Outcome-Based Education.',
            'card_subtitle'   => $is_kaprodi ? 'Ketua Program Studi ' . $user_prodi : 'Dosen Pengampu',
            'courses'         => $courses,
            'total_mk'        => count($courses),
            'published_count' => $published_count,
            'draft_count'     => $draft_count,
            'is_superadmin'   => $is_superadmin,
            'is_kaprodi'      => $is_kaprodi,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dosen/rps/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Editor RPS untuk Mata Kuliah Spesifik
     */
    public function detail($mata_kuliah_id)
    {
        $mata_kuliah_id = (int)$mata_kuliah_id;
        $rps_data = $this->M_obe->get_rps_mk($mata_kuliah_id);

        if (!$rps_data || !$rps_data['mata_kuliah']) {
            show_error('Mata kuliah tidak ditemukan.', 404);
            return;
        }

        $mk = $rps_data['mata_kuliah'];

        $data = [
            'title'             => 'RPS: ' . $mk->nama . ' - Smart Campus',
            'page_title'        => 'Rencana Pembelajaran Semester (RPS)',
            'page_desc'         => 'Desain pembelajaran dan pemetaan CPL -> CPMK -> Sub-CPMK (Total Bobot Wajib 100%).',
            'card_subtitle'     => $mk->kode . ' - ' . $mk->nama,
            'mk'                => $mk,
            'cpls'              => $rps_data['cpls'],
            'cpmks'             => $rps_data['cpmks'],
            'sub_cpmks'         => $rps_data['sub_cpmks'],
            'sub_flat'          => $rps_data['sub_flat'],
            'matriks_16'        => $rps_data['matriks_16'],
            'total_bobot_nilai' => $rps_data['total_bobot_nilai'],
            'is_valid_100'      => $rps_data['is_valid_100'],
            'rps_status'        => $rps_data['rps_status'],
            'status_publikasi'  => $rps_data['status_publikasi'],
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dosen/rps/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Simpan / Edit CPMK
     * Wajib terikat ke salah satu cpl_mk_id yang tersedia di MK tersebut
     */
    public function save_cpmk()
    {
        $mk_id      = (int)($this->input->post('mata_kuliah_id') ?: $this->input->post('mk_id'));
        $cpmk_id    = $this->input->post('id');
        $cpl_mk_id  = (int)$this->input->post('cpl_mk_id');
        $kode_cpmk  = trim((string)$this->input->post('kode_cpmk', TRUE));
        $deskripsi  = trim((string)$this->input->post('deskripsi', TRUE));

        if ($mk_id <= 0 || empty($kode_cpmk) || empty($deskripsi) || $cpl_mk_id <= 0) {
            $this->session->set_flashdata('error', 'Semua field CPMK wajib diisi dan harus terhubung ke salah satu CPL yang dititipkan.');
            redirect('dosen/rps/detail/' . $mk_id);
            return;
        }

        $data = [
            'cpl_mk_id'      => $cpl_mk_id,
            'mata_kuliah_id' => $mk_id,
            'mk_id'          => $mk_id,
            'kode_cpmk'      => $kode_cpmk,
            'deskripsi'      => $deskripsi
        ];

        $this->M_obe->save_cpmk_obe($data, $cpmk_id ?: null);
        $this->session->set_flashdata('success', 'CPMK (' . $kode_cpmk . ') berhasil disimpan.');
        redirect('dosen/rps/detail/' . $mk_id);
    }

    /**
     * Hapus CPMK
     */
    public function delete_cpmk($cpmk_id, $mk_id)
    {
        $this->M_obe->delete_cpmk_obe((int)$cpmk_id);
        $this->M_obe->sync_rps_status_bobot((int)$mk_id);
        $this->session->set_flashdata('success', 'CPMK beserta seluruh Sub-CPMK turunannya berhasil dihapus.');
        redirect('dosen/rps/detail/' . (int)$mk_id);
    }

    /**
     * Simpan / Edit Sub-CPMK (dengan Pertemuan 1-16 & Instrumen Evaluasi)
     */
    public function save_sub_cpmk()
    {
        $mk_id              = (int)($this->input->post('mata_kuliah_id') ?: $this->input->post('mk_id'));
        $cpmk_id            = (int)$this->input->post('cpmk_id');
        $sub_id             = $this->input->post('id');
        $kode_sub           = trim((string)($this->input->post('kode_sub', TRUE) ?: $this->input->post('kode_sub_cpmk', TRUE)));
        $deskripsi          = trim((string)$this->input->post('deskripsi', TRUE));
        $pertemuan_ke       = trim((string)($this->input->post('pertemuan_ke', TRUE) ?: $this->input->post('minggu_pertemuan', TRUE)));
        $instrumen_evaluasi = trim((string)($this->input->post('instrumen_evaluasi', TRUE) ?: $this->input->post('bentuk_evaluasi', TRUE)));
        $bobot_nilai        = (float)$this->input->post('bobot_nilai');

        if ($cpmk_id <= 0 || empty($kode_sub) || empty($deskripsi) || empty($pertemuan_ke) || $bobot_nilai <= 0) {
            $this->session->set_flashdata('error', 'Semua field Sub-CPMK wajib diisi dengan bobot nilai lebih dari 0%.');
            redirect('dosen/rps/detail/' . $mk_id);
            return;
        }

        $data = [
            'cpmk_id'            => $cpmk_id,
            'kode_sub'           => $kode_sub,
            'kode_sub_cpmk'      => $kode_sub,
            'deskripsi'          => $deskripsi,
            'pertemuan_ke'       => $pertemuan_ke,
            'minggu_pertemuan'   => $pertemuan_ke,
            'instrumen_evaluasi' => $instrumen_evaluasi,
            'bentuk_evaluasi'    => $instrumen_evaluasi,
            'bobot_nilai'        => $bobot_nilai
        ];

        $this->M_obe->save_sub_cpmk_obe($data, $sub_id ?: null);
        $this->session->set_flashdata('success', 'Sub-CPMK (' . $kode_sub . ') berhasil disimpan.');
        redirect('dosen/rps/detail/' . $mk_id);
    }

    /**
     * Hapus Sub-CPMK
     */
    public function delete_sub_cpmk($sub_id, $mk_id)
    {
        $this->M_obe->delete_sub_cpmk_obe((int)$sub_id);
        $this->session->set_flashdata('success', 'Sub-CPMK berhasil dihapus.');
        redirect('dosen/rps/detail/' . (int)$mk_id);
    }

    /**
     * Publikasikan RPS (Wajib Total Bobot Tepat 100%)
     * Memblokir jika total bobot != 100% dengan pesan error yang jelas
     */
    public function publish($mata_kuliah_id)
    {
        $user_id = (int)$this->session->userdata('id');
        $result = $this->M_obe->publish_rps_obe((int)$mata_kuliah_id, $user_id);

        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('dosen/rps/detail/' . (int)$mata_kuliah_id);
    }
}

