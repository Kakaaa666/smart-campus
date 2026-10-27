<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Kehadiran extends MY_Role_Controller {
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_akademik_mahasiswa', 'M_akademik');
    }

    public function index() {
        if (!$this->require_student_financial_clearance('Kehadiran Kuliah')) {
            return;
        }

        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null) || (int)$raw_role === 3;
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
                $akun_id = !empty($all_students) ? (int)$all_students[0]->id : $akun_id;
            }
        }

        $selected_period = $this->input->get('periode', true);

        $kehadiran_data = $this->M_akademik->get_student_kehadiran_data($akun_id, $selected_period);
        if (!$kehadiran_data) {
            show_error('Data mahasiswa tidak ditemukan.', 404);
            return;
        }

        $data = [
            'title' => 'Kehadiran Kuliah - Smart Campus',
            'page_title' => 'Kehadiran Kuliah',
            'page_desc' => 'Rekapitulasi dan riwayat kehadiran perkuliahan mahasiswa.',
            'card_subtitle' => 'Rekapitulasi Kehadiran',
            'kehadiran_data' => $kehadiran_data,
            'all_students' => $all_students,
            'selected_student_id' => $akun_id,
            'is_admin' => $is_admin,
            'is_dosen' => $is_dosen,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/kehadiran', $data);
        $this->load->view('templates/footer', $data);
    }
}
