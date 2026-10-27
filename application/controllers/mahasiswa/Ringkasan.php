<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Ringkasan extends MY_Role_Controller {
    // Memperbolehkan Mahasiswa (3), Superadmin (1), Admin (2), dan Dosen (4) untuk mengakses
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa/M_akademik_mahasiswa', 'M_akademik');
    }

    public function index() {
        if (!$this->require_student_financial_clearance('Ringkasan Aktivitas')) {
            return;
        }

        $raw_role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($raw_role === null) || (int)$raw_role === 3;
        $user_role = $is_mahasiswa ? 3 : (int)$raw_role;
        $is_admin = in_array($user_role, [1, 2], true);
        $is_dosen = ($user_role === 4);

        $akun_id = (int)$this->session->userdata('id');

        // Jika diakses oleh Admin atau Dosen, izinkan memilih mahasiswa.
        $all_students = [];
        if ($is_admin || $is_dosen) {
            $all_students = $this->M_akademik->get_all_students_for_khs();
            $req_student_id = (int)$this->input->get('student_id');
            if ($req_student_id > 0) {
                $akun_id = $req_student_id;
            } else {
                $akun_id = !empty($all_students) ? (int)$all_students[0]->id : $akun_id;
            }
        }

        $ringkasan = $this->M_akademik->get_student_ringkasan_data($akun_id);
        if (!$ringkasan || empty($ringkasan['student'])) {
            show_error('Data profil dan akademis mahasiswa tidak ditemukan.', 404);
            return;
        }

        $data = [
            'title'               => 'Ringkasan Akademis - Smart Campus',
            'page_title'          => 'Ringkasan Aktivitas',
            'page_desc'           => 'Ringkasan capaian, IPK, dan progres perkuliahan',
            'card_subtitle'       => 'Statistik & Ringkasan Perkuliahan Mahasiswa',
            'breadcrumb_parent'   => 'Akademik',
            'ringkasan'           => $ringkasan,
            'student'             => $ringkasan['student'],
            'is_admin'            => $is_admin,
            'is_dosen'            => $is_dosen,
            'all_students'        => $all_students,
            'selected_student_id' => $akun_id
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/mahasiswa_ringkasan', $data);
        $this->load->view('templates/footer', $data);
    }
}
