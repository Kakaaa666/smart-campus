<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Dosen extends MY_Role_Controller
{
    protected $allowed_roles = [4];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('shared/M_akademik', 'M_akademik');
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan');
    }

    public function index()
    {
        $pending = $this->M_akademik->get_pending_advisor_krs((int)$this->session->userdata('id'));
        $data = [
            'title' => 'Dashboard Dosen - Smart Campus',
            'page_title' => 'Dashboard Dosen',
            'page_desc' => 'Ringkasan aktivitas akademik dan persetujuan rencana studi.',
            'card_subtitle' => 'Biro Akademik',
            'pending_krs_count' => count($pending),
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/dosen_dashboard', $data);
        $this->load->view('templates/footer', $data);
    }

    public function isi_nilai()
    {
        $this->render_module('Isi Nilai', 'Kelola nilai mahasiswa pada mata kuliah yang diampu.', 'Akademik');
    }

    public function revisi_nilai()
    {
        $this->render_module('Revisi Nilai', 'Ajukan dan pantau revisi nilai mahasiswa.', 'Akademik');
    }

    public function mahasiswa_bimbingan()
    {
        $data = [
            'title' => 'Mahasiswa Bimbingan - Smart Campus',
            'page_title' => 'Mahasiswa Bimbingan',
            'page_desc' => 'Daftar mahasiswa yang ditetapkan kepada Anda sebagai Dosen Wali.',
            'card_subtitle' => 'Biro Akademik',
            'students' => $this->M_akademik->get_advisor_students((int)$this->session->userdata('id')),
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/dosen_bimbingan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function persetujuan_krs()
    {
        $pending = $this->M_akademik->get_pending_advisor_krs((int)$this->session->userdata('id'));
        foreach ($pending as $request) {
            $period = [
                'tahun_akademik' => $request->tahun_akademik,
                'semester_akademik' => $request->semester_akademik,
            ];
            $request->krs_detail = $this->M_akademik->get_krs($request->akun_id, $period);
        }

        $data = [
            'title' => 'Persetujuan KRS - Smart Campus',
            'page_title' => 'Persetujuan KRS',
            'page_desc' => 'Periksa dan putuskan pengajuan KRS mahasiswa bimbingan Anda.',
            'card_subtitle' => 'Biro Akademik',
            'pending_krs' => $pending,
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/dosen_persetujuan_krs', $data);
        $this->load->view('templates/footer', $data);
    }

    public function proses_krs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }

        $krs_id = (int)$this->input->post('krs_id', true);
        $action = $this->input->post('aksi', true);
        $note = trim((string)$this->input->post('catatan', true));
        if ($krs_id < 1 || !in_array($action, ['setujui', 'tolak'], true)
            || ($action === 'tolak' && $note === '') || strlen($note) > 500) {
            $this->session->set_flashdata('error', 'Keputusan tidak valid. Catatan penolakan wajib diisi dan maksimal 500 karakter.');
            redirect('dosen/persetujuan-krs');
            return;
        }

        $lecturer_id = (int)$this->session->userdata('id');
        $requests = $this->M_akademik->get_pending_advisor_krs($lecturer_id);
        $request = null;
        foreach ($requests as $candidate) {
            if ((int)$candidate->id === $krs_id) {
                $request = $candidate;
                break;
            }
        }
        if (!$request) {
            $this->session->set_flashdata('error', 'Pengajuan tidak ditemukan atau bukan kewenangan Anda.');
            redirect('dosen/persetujuan-krs');
            return;
        }

        $approve = $action === 'setujui';
        if ($approve && !$this->M_keuangan->cek_status_krs((int)$request->akun_id)['buka_krs']) {
            $this->session->set_flashdata('error', 'KRS tidak dapat disetujui karena status pembayaran semester mahasiswa tidak lagi memenuhi syarat.');
            redirect('dosen/persetujuan-krs');
            return;
        }

        if (!$this->M_akademik->decide_krs($krs_id, $lecturer_id, $approve, $note)) {
            $this->session->set_flashdata('error', 'Keputusan gagal disimpan. Pengajuan mungkin sudah diproses.');
        } else {
            $this->session->set_flashdata('success', $approve ? 'KRS berhasil disetujui.' : 'KRS ditolak dan dikembalikan kepada mahasiswa.');
        }
        redirect('dosen/persetujuan-krs');
    }

    public function akademik()
    {
        $this->render_module('Akademik', 'Akses informasi akademik dosen.', 'Akademik');
    }

    public function perkuliahan()
    {
        $this->render_module('Perkuliahan', 'Lihat jadwal dan aktivitas perkuliahan.', 'Akademik');
    }

    public function profil()
    {
        redirect('profil');
    }
}
