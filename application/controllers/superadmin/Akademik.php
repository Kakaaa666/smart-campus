<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller
{
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin/M_akademik_superadmin', 'M_akademik');
    }

    public function index()
    {
        $period = $this->M_akademik->get_active_period();
        $data = [
            'title' => 'Pusat Kendali Akademik - Smart Campus',
            'page_title' => 'Pusat Kendali Akademik',
            'page_desc' => 'Pantau penawaran mata kuliah dan status KRS pada periode aktif.',
            'card_subtitle' => 'Akademik',
            'active_period' => $period,
            'overview' => $this->M_akademik->get_period_overview($period),
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/superadmin_overview', $data);
        $this->load->view('templates/footer', $data);
    }

    public function jadwal() { $this->index(); }
    public function nilai() { redirect('akademik/nilai' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')); }
    public function khs() {
        $student_id = (int)$this->input->get('student_id');
        $all_students = $this->M_akademik->get_all_students_for_khs();
        $prodi_options = $this->M_akademik->get_khs_prodi_options();
        $angkatan_options = $this->M_akademik->get_khs_angkatan_options();
        $filters = [
            'prodi'    => $this->input->get('prodi', true) ?: '',
            'angkatan' => $this->input->get('angkatan', true) ?: '',
            'semester' => $this->input->get('semester', true) ?: '',
            'status'   => $this->input->get('status', true) ?: '',
            'q'        => $this->input->get('q', true) ?: '',
        ];

        $students_list = [];
        $khs_data = null;
        if ($student_id > 0) {
            $selected_period = $this->input->get('periode', true);
            $jenis = $this->input->get('jenis', true) ?: 'khs';
            $khs_data = $this->M_akademik->get_student_khs_data($student_id, $selected_period, $jenis);
        } else {
            $students_list = $this->M_akademik->get_filtered_students_for_khs($filters);
        }

        $data = [
            'title' => 'Kartu Hasil Studi (KHS) - Smart Campus',
            'page_title' => 'Kartu Hasil Studi',
            'page_desc' => 'Pemeriksaan Kartu Hasil Studi (KHS) mahasiswa per semester.',
            'card_subtitle' => 'Kartu Hasil Studi Mahasiswa',
            'khs_data' => $khs_data,
            'students_list' => $students_list,
            'total_students' => count($students_list),
            'prodi_options' => $prodi_options,
            'angkatan_options' => $angkatan_options,
            'filters' => $filters,
            'all_students' => $all_students,
            'selected_student_id' => $student_id,
            'is_admin' => true,
            'is_dosen' => false,
            'selected_jenis' => $this->input->get('jenis', true) ?: 'khs',
            'current_role' => 'superadmin'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/khs', $data);
        $this->load->view('templates/footer', $data);
    }

    public function transkrip() {
        redirect('akademik/transkrip' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }
    public function kurikulum() { $this->index(); }
    public function matakuliah() { $this->index(); }
    public function kalender() { $this->index(); }
}
