<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller {
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_akademik_mahasiswa', 'M_akademik');
    }

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
        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null);
        $user_role = $is_mahasiswa ? null : (int)$raw_role;
        $akun_id = (int)$this->session->userdata('id');

        // Jika diakses oleh Admin atau Dosen, tampilkan data mahasiswa (default Arthur atau mahasiswa pertama)
        if (!$is_mahasiswa) {
            $mhs = $this->db->where('nim', '241221009')->get('akun_mahasiswa')->row();
            if (!$mhs) {
                $mhs = $this->db->get('akun_mahasiswa')->row();
            }
            if ($mhs) {
                $akun_id = (int)$mhs->id;
            }
        }

        $period = $this->M_akademik->get_active_period();
        $account = $this->M_akademik->get_account($akun_id);
        if (!$account) {
            show_error('Data mahasiswa aktif tidak ditemukan.', 404);
            return;
        }
        $data = [
            'title' => 'Jadwal Kuliah - Smart Campus',
            'page_title' => 'Jadwal Kuliah',
            'page_desc' => 'Jadwal mata kuliah sesuai KRS yang disetujui pada periode aktif.',
            'card_subtitle' => 'Daftar jadwal mata kuliah dan ruangan',
            'active_period' => $period,
            'student' => $account,
            'krs' => $this->M_akademik->get_krs($akun_id, $period),
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/mahasiswa_jadwal', $data);
        $this->load->view('templates/footer', $data);
    }

    public function nilai() {
        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null);
        $user_role = $is_mahasiswa ? null : (int)$raw_role;
        $akun_id = (int)$this->session->userdata('id');
        $is_admin = in_array($user_role, [1, 2], true);
        $is_dosen = ($user_role === 4);

        $all_students = [];
        if ($is_admin || $is_dosen) {
            $all_students = $this->M_akademik->get_all_students_for_khs();
            $req_student_id = (int)$this->input->get('student_id');
            if ($req_student_id) {
                $akun_id = $req_student_id;
            } else {
                $arthur = $this->db->where('nim', '241221009')->get('akun_mahasiswa')->row();
                $akun_id = $arthur ? (int)$arthur->id : (!empty($all_students) ? (int)$all_students[0]->id : $akun_id);
            }
        }

        $selected_period = $this->input->get('periode', true);

        $nilai_data = $this->M_akademik->get_student_nilai_data($akun_id, $selected_period);
        if (!$nilai_data) {
            show_error('Data mahasiswa tidak ditemukan.', 404);
            return;
        }

        $data = [
            'title' => 'Nilai - Smart Campus',
            'page_title' => 'Nilai Perkuliahan',
            'page_desc' => 'Daftar nilai evaluasi hasil belajar mata kuliah yang telah selesai ditempuh.',
            'card_subtitle' => 'Daftar Nilai Mahasiswa',
            'nilai_data' => $nilai_data,
            'all_students' => $all_students,
            'selected_student_id' => $akun_id,
            'is_admin' => $is_admin,
            'is_dosen' => $is_dosen,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/nilai', $data);
        $this->load->view('templates/footer', $data);
    }

    public function khs() {
        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null);
        $user_role = $is_mahasiswa ? null : (int)$raw_role;
        $akun_id = (int)$this->session->userdata('id');
        $is_admin = in_array($user_role, [1, 2], true);
        $is_dosen = ($user_role === 4);

        $all_students = [];
        if ($is_admin || $is_dosen) {
            $all_students = $this->M_akademik->get_all_students_for_khs();
            $req_student_id = (int)$this->input->get('student_id');
            if ($req_student_id) {
                $akun_id = $req_student_id;
            } else {
                $arthur = $this->db->where('nim', '241221009')->get('akun_mahasiswa')->row();
                $akun_id = $arthur ? (int)$arthur->id : (!empty($all_students) ? (int)$all_students[0]->id : $akun_id);
            }
        }

        $selected_period = $this->input->get('periode', true);
        $jenis = $this->input->get('jenis', true) ?: 'khs';

        $khs_data = $this->M_akademik->get_student_khs_data($akun_id, $selected_period, $jenis);
        if (!$khs_data) {
            show_error('Data mahasiswa tidak ditemukan.', 404);
            return;
        }

        $data = [
            'title' => 'Kartu Hasil Studi (KHS) - Smart Campus',
            'page_title' => 'Kartu Hasil Studi',
            'page_desc' => 'Informasi KHS per semester mahasiswa dan pencetakan hasil studi resmi.',
            'card_subtitle' => 'Kartu Hasil Studi',
            'khs_data' => $khs_data,
            'all_students' => $all_students,
            'selected_student_id' => $akun_id,
            'is_admin' => $is_admin,
            'is_dosen' => $is_dosen,
            'current_role' => ($user_role === 1 ? 'superadmin' : ($user_role === 2 ? 'admin' : ($user_role === 4 ? 'dosen' : 'mahasiswa')))
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/khs', $data);
        $this->load->view('templates/footer', $data);
    }

    public function transkrip() {
        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null);
        $user_role = $is_mahasiswa ? null : (int)$raw_role;
        $akun_id = (int)$this->session->userdata('id');
        $is_admin = in_array($user_role, [1, 2], true);
        $is_dosen = ($user_role === 4);

        $all_students = [];
        if ($is_admin || $is_dosen) {
            $all_students = $this->M_akademik->get_all_students_for_khs();
            $req_student_id = (int)$this->input->get('student_id');
            if ($req_student_id) {
                $akun_id = $req_student_id;
            } else {
                $arthur = $this->db->where('nim', '241221009')->get('akun_mahasiswa')->row();
                $akun_id = $arthur ? (int)$arthur->id : (!empty($all_students) ? (int)$all_students[0]->id : $akun_id);
            }
        }

        $khs_data = $this->M_akademik->get_student_khs_data($akun_id, null, 'transkrip');
        $data = [
            'title' => 'Transkrip Akademik - Smart Campus',
            'page_title' => 'Transkrip Akademik',
            'page_desc' => 'Daftar rekapitulasi seluruh nilai mahasiswa kumulatif.',
            'card_subtitle' => 'Transkrip Nilai Kumulatif',
            'khs_data' => $khs_data,
            'all_students' => $all_students,
            'selected_student_id' => $akun_id,
            'is_admin' => $is_admin,
            'is_dosen' => $is_dosen,
            'current_role' => ($user_role === 1 ? 'superadmin' : ($user_role === 2 ? 'admin' : ($user_role === 4 ? 'dosen' : 'mahasiswa')))
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/khs', $data);
        $this->load->view('templates/footer', $data);
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
