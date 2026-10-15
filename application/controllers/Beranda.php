<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('M_auth');
        $this->load->model('shared/M_akademik', 'M_akademik');
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan');
        $this->load->helper(['url', 'form']);

        // Cek apakah user sudah login, jika belum redirect ke halaman login
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            redirect('auth');
        }
    }

    public function index() {
        $role = (int)$this->session->userdata('role');
        if ($role === 2) {
            $biro = M_auth::normalize_biro($this->session->userdata('biro'));
            $routes = [
                'keuangan' => 'keuangan/admin',
                'akademik' => 'admin/akademik',
                'kemahasiswaan' => 'admin/kemahasiswaan',
                'perpustakaan' => 'admin/perpustakaan',
                'sarana_prasarana' => 'admin/sarana-prasarana',
                'penjaminan_mutu' => 'admin/penjaminan-mutu',
            ];
            redirect($routes[$biro] ?? 'keuangan/admin');
            return;
        }
        if ($role === 4) {
            redirect('dosen');
            return;
        }

        $akun_id = (int)$this->session->userdata('id');

        $data['title'] = 'Beranda - Smart Campus';
        $data['user'] = [
            'id'           => $akun_id,
            'nim'          => $this->session->userdata('nim'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'role'         => $role,
            'role_name'    => $this->session->userdata('role_name'),
            'foto'         => $this->session->userdata('foto')
        ];

        // Jika Mahasiswa (Role 3): Muat Data Profil Lengkap & Informasi Perkuliahan
        if ($role === 3) {
            // Ambil Profil Mahasiswa beserta Dosen Wali
            $student = $this->db->select('akun_mahasiswa.*, dosen.id AS dosen_id, dosen.nama_lengkap AS nama_dosen_wali, dosen.nim AS nidn_dosen_wali, dosen.email AS email_dosen_wali, dosen.telepon AS telepon_dosen_wali, dosen.foto AS foto_dosen_wali')
                                ->from('akun_mahasiswa')
                                ->join('dosen_wali', 'dosen_wali.mahasiswa_id = akun_mahasiswa.id', 'left')
                                ->join('akun_biro AS dosen', 'dosen.id = dosen_wali.dosen_id', 'left')
                                ->where('akun_mahasiswa.id', $akun_id)
                                ->get()->row();
            if ($student) {
                $student->role = 3;
            }

            $academic_period = $this->M_akademik->get_active_period();
            $krs = $this->M_akademik->get_krs($akun_id, $academic_period);
            $advisor = $this->M_akademik->get_advisor_for_student($akun_id);
            $status_keuangan = $this->M_keuangan->cek_status_krs($akun_id);
            $all_lecturers = $this->M_akademik->get_lecturers();

            // Mapping jadwal kuliah hari ini
            $hari_indonesia = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $today = $hari_indonesia[date('l')] ?? 'Senin';

            $courses = (!empty($krs) && !empty($krs->mata_kuliah)) ? $krs->mata_kuliah : [];
            $today_courses = [];
            foreach ($courses as $c) {
                if (strcasecmp($c->hari ?? '', $today) === 0) {
                    $today_courses[] = $c;
                }
            }

            $data['student'] = $student;
            $data['academic_period'] = $academic_period;
            $data['krs'] = $krs;
            $data['advisor'] = $advisor;
            $data['status_keuangan'] = $status_keuangan;
            $data['all_lecturers'] = $all_lecturers;
            $data['today'] = $today;
            $data['today_courses'] = $today_courses;
            $data['courses'] = $courses;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('beranda/beranda', $data);
        $this->load->view('templates/footer', $data);
    }
}
