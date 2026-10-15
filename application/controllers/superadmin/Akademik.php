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
    public function nilai() { $this->index(); }
    public function khs() {
        $all_students = $this->M_akademik->get_all_students_for_khs();
        $student_id = (int)$this->input->get('student_id');
        if (!$student_id) {
            $arthur = $this->db->where('nim', '241221009')->get('akun')->row();
            $student_id = $arthur ? (int)$arthur->id : (!empty($all_students) ? (int)$all_students[0]->id : 0);
        }

        $selected_period = $this->input->get('periode', true);
        $jenis = $this->input->get('jenis', true) ?: 'khs';

        $khs_data = $this->M_akademik->get_student_khs_data($student_id, $selected_period, $jenis);

        $data = [
            'title' => 'Kartu Hasil Studi (KHS) - Smart Campus',
            'page_title' => 'Kartu Hasil Studi',
            'page_desc' => 'Pemeriksaan Kartu Hasil Studi (KHS) mahasiswa per semester.',
            'card_subtitle' => 'Kartu Hasil Studi Mahasiswa',
            'khs_data' => $khs_data,
            'all_students' => $all_students,
            'selected_student_id' => $student_id,
            'is_admin' => true,
            'is_dosen' => false,
            'current_role' => 'superadmin'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/khs', $data);
        $this->load->view('templates/footer', $data);
    }

    public function transkrip() {
        $this->khs();
    }
    public function kurikulum() { $this->index(); }
    public function matakuliah() { $this->index(); }
    public function kalender() { $this->index(); }
}
