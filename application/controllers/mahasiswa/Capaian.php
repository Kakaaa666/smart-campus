<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Capaian extends MY_Role_Controller
{
    // Memperbolehkan Mahasiswa (3), Superadmin (1), Admin (2), dan Dosen (4)
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_obe');
        $this->load->helper(['url', 'html']);
    }

    /**
     * Halaman Transkrip Portofolio Capaian CPL Mahasiswa (OBE)
     */
    public function index()
    {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');
        $is_admin        = in_array($user_role, [1, 2], true);
        $is_dosen        = ($user_role === 4);

        $selected_student_id = $current_user_id;
        $all_students = [];

        if ($is_admin || $is_dosen) {
            $all_students = $this->db->select('id, nim, nama_lengkap, prodi')
                                     ->where('deleted_at IS NULL', null, false)
                                     ->order_by('nama_lengkap', 'ASC')
                                     ->get('akun_mahasiswa')
                                     ->result();

            $req_student_id = (int)$this->input->get('student_id');
            if ($req_student_id > 0) {
                $selected_student_id = $req_student_id;
            } elseif (!empty($all_students)) {
                $selected_student_id = (int)$all_students[0]->id;
            }
        }

        $semester_filter = $this->input->get('semester', TRUE);
        $filter_semester = (!empty($semester_filter) && $semester_filter !== 'all') ? (int)$semester_filter : 'all';

        $obe_data = $this->M_obe->get_student_obe_attainment($selected_student_id, $filter_semester);

        if (!$obe_data || empty($obe_data['student'])) {
            show_error('Data ketercapaian OBE mahasiswa tidak ditemukan.', 404);
            return;
        }

        // Ambil kalkulasi CPL Bottom-Up murni dari nilai_evaluasi (Fase 3 & Fase 5)
        $cpl_bottomup = $this->M_obe->get_capaian_cpl_mahasiswa($selected_student_id);

        $data = [
            'title'               => 'Portofolio CPL & SKPI - ' . $obe_data['student']->nama_lengkap,
            'page_title'          => 'Portofolio Capaian Pembelajaran Lulusan (CPL)',
            'page_desc'           => 'Evaluasi komprehensif profil kompetensi mahasiswa berbasis Outcome-Based Education (OBE) dan Surat Keterangan Pendamping Ijazah (SKPI).',
            'card_subtitle'       => $obe_data['student']->nim . ' - ' . $obe_data['student']->nama_lengkap,
            'student'             => $obe_data['student'],
            'prodi'               => $obe_data['prodi'],
            'courses'             => $obe_data['courses'],
            'cpls'                => $obe_data['cpls'],
            'cpl_bottomup'        => $cpl_bottomup,
            'chart_labels'        => !empty($cpl_bottomup['labels']) ? $cpl_bottomup['labels'] : $obe_data['chart_labels'],
            'chart_values'        => !empty($cpl_bottomup['scores']) ? $cpl_bottomup['scores'] : $obe_data['chart_values'],
            'available_semesters' => $obe_data['available_semesters'],
            'filter_semester'     => $filter_semester,
            'is_semester_ongoing' => $obe_data['is_semester_ongoing'],
            'total_sks_lulus'     => $obe_data['total_sks_lulus'],
            'total_sks_ongoing'   => $obe_data['total_sks_ongoing'],
            'is_admin'            => $is_admin,
            'is_dosen'            => $is_dosen,
            'selected_student_id' => $selected_student_id,
            'all_students'        => $all_students,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('mahasiswa/capaian', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Halaman Siap Cetak: Portofolio Kompetensi CPL & Dokumen SKPI Resmi (Fase 5)
     */
    public function cetak($student_id = null)
    {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');
        $is_admin        = in_array($user_role, [1, 2], true);
        $is_dosen        = ($user_role === 4);

        $selected_student_id = $current_user_id;
        $req_id = (int)($student_id ?: $this->input->get('student_id'));

        if (($is_admin || $is_dosen) && $req_id > 0) {
            $selected_student_id = $req_id;
        }

        $student = $this->db->get_where('akun_mahasiswa', ['id' => $selected_student_id])->row();
        if (!$student) {
            $student = $this->db->get_where('akun', ['id' => $selected_student_id])->row();
        }

        if (!$student) {
            show_error('Data mahasiswa tidak ditemukan.', 404);
            return;
        }

        // Tentukan Program Studi
        $prodi = null;
        if (!empty($student->prodi)) {
            $prodi = $this->db->like('nama_prodi', $student->prodi)->get('prodi')->row();
        }
        if (!$prodi) {
            $prodi = $this->db->where('id', 1)->get('prodi')->row();
        }

        // Dapatkan data capaian CPL secara Bottom-Up
        $cpl_data = $this->M_obe->get_capaian_cpl_mahasiswa($selected_student_id);

        // Ambil data Kaprodi
        $kaprodi = $this->db->select('nama_lengkap, nip, nim')
                            ->where('is_kaprodi', 1)
                            ->get('akun_biro')
                            ->row();
        if (!$kaprodi) {
            $kaprodi = (object)[
                'nama_lengkap' => 'Sugeng Widodo, S.Kom., M.Kom.',
                'nip' => '198203152008121003'
            ];
        }

        // Generate Nomor Registrasi SKPI Resmi
        $nomor_skpi = 'SKPI/' . date('Y') . '/' . ($prodi->kode_prodi ?? 'TI') . '/' . str_pad($student->id, 5, '0', STR_PAD_LEFT);

        // Format tanggal Indonesia
        $bulan_indo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $tgl_indo = date('j') . ' ' . ($bulan_indo[(int)date('n')] ?? date('F')) . ' ' . date('Y');

        $data = [
            'title'         => 'Cetak Portofolio SKPI - ' . $student->nama_lengkap,
            'student'       => $student,
            'prodi'         => $prodi,
            'cpl_data'      => $cpl_data,
            'kaprodi'       => $kaprodi,
            'nomor_skpi'    => $nomor_skpi,
            'tanggal_cetak' => $tgl_indo
        ];

        $this->load->view('mahasiswa/cetak_skpi', $data);
    }
}
