<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Dosen extends MY_Role_Controller
{
    protected $allowed_roles = [1, 4];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->model('shared/M_akademik', 'M_akademik');
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan');
    }

    public function index()
    {
        $lecturer_id = (int)$this->session->userdata('id');
        $is_admin = in_array((int)$this->session->userdata('role'), [1, 2], true);

        // Jika Dosen (bukan admin), statistik KRS hanya untuk mahasiswa bimbingannya sendiri
        $target_id = $is_admin ? 0 : $lecturer_id;
        $stats = $this->M_akademik->get_krs_validation_stats($target_id);

        $data = [
            'title' => 'Dashboard Dosen - Smart Campus',
            'page_title' => 'Dashboard Dosen',
            'page_desc' => 'Pusat pengelolaan rencana studi mahasiswa bimbingan (KRS), perkuliahan, dan penilaian akademik.',
            'card_subtitle' => 'Dosen Wali',
            'stats' => $stats,
            'pending_krs_count' => $stats['pending'],
            'academic_period' => $this->M_akademik->get_active_period(),
            'is_admin' => $is_admin,
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

    public function nilai()
    {
        redirect('akademik/nilai' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }

    public function khs()
    {
        $user_id = (int)$this->session->userdata('id');
        $is_admin = in_array((int)$this->session->userdata('role'), [1, 2], true);
        $advisor_students = $this->M_akademik->get_advisor_students($is_admin ? 0 : $user_id);

        $student_id = (int)$this->input->get('student_id');
        if (!$student_id) {
            $student_id = !empty($advisor_students) ? (int)$advisor_students[0]->id : 0;
        }

        $selected_period = $this->input->get('periode', true);
        $jenis = $this->input->get('jenis', true) ?: 'khs';

        $khs_data = $this->M_akademik->get_student_khs_data($student_id, $selected_period, $jenis);

        $data = [
            'title' => 'Kartu Hasil Studi Mahasiswa - Smart Campus',
            'page_title' => 'Kartu Hasil Studi (KHS)',
            'page_desc' => 'Daftar hasil studi evaluasi belajar mahasiswa bimbingan perwalian.',
            'card_subtitle' => 'Kartu Hasil Studi',
            'khs_data' => $khs_data,
            'all_students' => $advisor_students,
            'selected_student_id' => $student_id,
            'is_admin' => $is_admin,
            'is_dosen' => true,
            'current_role' => 'dosen'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/khs', $data);
        $this->load->view('templates/footer', $data);
    }

    public function mahasiswa_bimbingan()
    {
        $user_id = (int)$this->session->userdata('id');
        $is_admin = in_array((int)$this->session->userdata('role'), [1, 2], true);
        $filter_dosen = $this->input->get('dosen_id');
        $target_id = $filter_dosen !== null ? (int)$filter_dosen : $user_id;

        $data = [
            'title' => 'Mahasiswa Bimbingan - Smart Campus',
            'page_title' => 'Mahasiswa Bimbingan',
            'page_desc' => 'Daftar mahasiswa bimbingan akademik dan perwalian.',
            'card_subtitle' => 'Dosen Wali',
            'students' => $this->M_akademik->get_advisor_students($target_id),
            'all_lecturers' => $this->M_akademik->get_lecturers(),
            'selected_dosen' => $target_id,
            'is_admin' => $is_admin,
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/dosen_bimbingan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function persetujuan_krs()
    {
        $user_id = (int)$this->session->userdata('id');
        $user_role = (int)$this->session->userdata('role');
        $is_admin = in_array($user_role, [1, 2], true);

        // Filter dari query URL
        $status = $this->input->get('status', true) ?: 'menunggu';
        $prodi = $this->input->get('prodi', true) ?: 'all';
        $keyword = $this->input->get('q', true) ?: '';

        // HANYA MAHASISWA YANG MEMILIH DOSEN WALI TERSEBUT:
        // Jika dosen yang login (role 4), target_lecturer_id PASTI $user_id (dosen bersangkutan).
        // Mahasiswa bimbingan dosen lain tidak akan muncul.
        // Jika admin/superadmin, dapat memilih filter dosen tertentu atau semua.
        if ($is_admin) {
            $filter_dosen = $this->input->get('dosen_id');
            $target_lecturer_id = ($filter_dosen !== null && $filter_dosen !== '') ? (int)$filter_dosen : 0;
        } else {
            $target_lecturer_id = $user_id;
        }

        $krs_list = $this->M_akademik->get_advisor_krs_list($target_lecturer_id, $status, $prodi, $keyword);
        $stats = $this->M_akademik->get_krs_validation_stats($target_lecturer_id);
        $all_lecturers = $is_admin ? $this->M_akademik->get_lecturers() : [];

        $data = [
            'title' => 'Validasi & Persetujuan KRS - Smart Campus',
            'page_title' => 'Persetujuan KRS Mahasiswa Bimbingan',
            'page_desc' => 'Tinjau beban studi, jadwal mingguan, kelayakan SPP, dan berikan persetujuan rencana studi mahasiswa bimbingan Anda.',
            'card_subtitle' => 'Dosen Wali',
            'krs_list' => $krs_list,
            'stats' => $stats,
            'active_status' => $status,
            'active_prodi' => $prodi,
            'keyword' => $keyword,
            'selected_dosen' => $target_lecturer_id,
            'current_lecturer_id' => $user_id,
            'all_lecturers' => $all_lecturers,
            'is_admin' => $is_admin,
            'academic_period' => $this->M_akademik->get_active_period(),
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
        $action = $this->input->post('aksi', true); // 'setujui', 'tolak', 'batalkan'
        $note = trim((string)$this->input->post('catatan', true));

        $valid_actions = ['setujui', 'tolak', 'batalkan'];
        if ($krs_id < 1 || !in_array($action, $valid_actions, true)) {
            $this->session->set_flashdata('error', 'Aksi keputusan tidak valid.');
            redirect('dosen/persetujuan-krs');
            return;
        }

        if ($action === 'tolak' && $note === '') {
            $this->session->set_flashdata('error', 'Catatan revisi wajib diisi saat menolak/mengembalikan pengajuan KRS.');
            redirect('dosen/persetujuan-krs?status=menunggu');
            return;
        }

        $user_id = (int)$this->session->userdata('id');
        $user_role = (int)$this->session->userdata('role');
        $is_admin = in_array($user_role, [1, 2], true);

        // Eksekusi keputusan di model
        $result = $this->M_akademik->decide_krs($krs_id, $user_id, $action, $note, $is_admin);

        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        $redirect_status = $action === 'setujui' ? 'disetujui' : ($action === 'tolak' ? 'ditolak' : 'menunggu');
        redirect('dosen/persetujuan-krs?status=' . $redirect_status);
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
