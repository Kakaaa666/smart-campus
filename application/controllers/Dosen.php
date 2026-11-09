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
        $this->load->model('M_obe');
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
        $this->nilai();
    }

    public function revisi_nilai()
    {
        $this->render_module('Revisi Nilai', 'Ajukan dan pantau revisi nilai mahasiswa.', 'Akademik');
    }

    /**
     * Integrasi Modul Outcome-Based Education (OBE) Dosen
     */
    public function nilai($action = 'index', $param1 = null, $param2 = null)
    {
        if ($action === 'setup_cpmk') {
            return $this->setup_cpmk($param1);
        } elseif ($action === 'save_cpmk') {
            return $this->save_cpmk();
        } elseif ($action === 'delete_cpmk') {
            return $this->delete_cpmk($param1, $param2);
        } elseif ($action === 'save_komponen') {
            return $this->save_komponen();
        } elseif ($action === 'matriks') {
            return $this->matriks($param1);
        } elseif ($action === 'save_matriks') {
            return $this->save_matriks();
        }

        $user_id = (int)$this->session->userdata('id');
        $user_role = (int)$this->session->userdata('role');
        $is_superadmin = ($user_role === 1);
        $is_kaprodi = ((int)$this->session->userdata('is_kaprodi') === 1);

        // Pastikan deteksi kaprodi akurat jika session belum ter-refresh
        if (!$is_kaprodi && $user_role === 4 && $user_id > 0) {
            $user_row = $this->db->select('is_kaprodi, prodi, nama_lengkap')->where('id', $user_id)->get('akun_biro')->row();
            if ($user_row && ((int)$user_row->is_kaprodi === 1 || stripos($user_row->nama_lengkap, 'kaprodi') !== false)) {
                $is_kaprodi = true;
                $this->session->set_userdata('is_kaprodi', 1);
                if (!empty($user_row->prodi)) {
                    $this->session->set_userdata('prodi', $user_row->prodi);
                }
            }
        }
        $user_prodi = $this->session->userdata('prodi') ?: 'Teknik Informatika';

        $req_dosen_id = $this->input->get('dosen_id');
        $tahun = $this->input->get('tahun', TRUE);
        $semester = $this->input->get('semester', TRUE);

        if ($is_superadmin) {
            $dosen_id = ($req_dosen_id !== null && $req_dosen_id !== '' && $req_dosen_id !== '0') ? (int)$req_dosen_id : 0;
            $filter_prodi = $this->input->get('prodi', TRUE) ?: null;
            $all_lecturers = $this->db->where('role', 4)->order_by('nama_lengkap', 'ASC')->get('akun_biro')->result();
        } elseif ($is_kaprodi) {
            // Kaprodi: Sebagai Ketua Program Studi, default adalah mengawasi seluruh kelas Program Studi
            if ($req_dosen_id !== null && $req_dosen_id !== '' && (int)$req_dosen_id > 0) {
                $dosen_id = (int)$req_dosen_id;
                $filter_prodi = null;
            } else {
                $dosen_id = 0; // Seluruh kelas prodi
                $filter_prodi = $user_prodi;
            }
            $all_lecturers = $this->db->where('role', 4)
                                      ->group_start()
                                          ->like('prodi', $user_prodi)
                                          ->or_where('fakultas', 'Fakultas Teknologi Informasi')
                                          ->or_where('is_kaprodi', 1)
                                      ->group_end()
                                      ->order_by('nama_lengkap', 'ASC')
                                      ->get('akun_biro')->result();
        } else {
            // Dosen pengampu reguler (hanya bertanggung jawab atas kelas yang diampu sendiri)
            $dosen_id = $user_id;
            $filter_prodi = null;
            $all_lecturers = [];
        }

        $classes = $this->M_obe->get_dosen_classes($dosen_id, $tahun, $semester, $filter_prodi);

        if (empty($classes) && $dosen_id > 0 && !$is_superadmin && !$is_kaprodi) {
            $classes = $this->M_obe->get_dosen_classes(0, $tahun, $semester, $user_prodi);
        }

        $data = [
            'title'          => ($is_kaprodi ? 'Monitoring & ' : '') . 'Penilaian Akademik & OBE - Smart Campus',
            'page_title'     => $is_kaprodi ? 'Monitoring & Penilaian OBE Program Studi' : 'Penilaian Akademik & OBE',
            'page_desc'      => $is_kaprodi 
                                ? 'Pengawasan instrumen asesmen CPMK, konfigurasi bobot 100%, dan penilaian mahasiswa di Program Studi ' . $user_prodi . '.' 
                                : 'Pengelolaan instrumen asesmen CPMK berbasis Outcome-Based Education dan penilaian mahasiswa yang diampu.',
            'card_subtitle'  => $is_kaprodi ? 'Ketua Program Studi ' . $user_prodi : 'Dosen Pengampu',
            'classes'        => $classes,
            'dosen_id'       => $dosen_id,
            'user_id'        => $user_id,
            'is_superadmin'  => $is_superadmin,
            'is_kaprodi'     => $is_kaprodi,
            'user_prodi'     => $user_prodi,
            'all_lecturers'  => $all_lecturers,
            'filter_tahun'   => $tahun,
            'filter_semester'=> $semester,
            'filter_prodi'   => $filter_prodi,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dosen/nilai/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function setup_cpmk($penawaran_id)
    {
        $is_superadmin = ((int)$this->session->userdata('role') === 1);
        $is_kaprodi = ((int)$this->session->userdata('is_kaprodi') === 1);
        $dosen_id = ($is_superadmin || $is_kaprodi) ? 0 : (int)$this->session->userdata('id');
        $course = $this->M_obe->get_class_detail((int)$penawaran_id, $dosen_id);

        if (!$course) {
            show_error('Kelas perkuliahan tidak ditemukan atau Anda tidak memiliki hak akses ke kelas ini.', 404);
            return;
        }

        $cpmks = $this->M_obe->get_cpmks_by_penawaran($course->id);
        $komponens = $this->M_obe->get_komponen_penilaian($course->id);

        $prodi = $this->db->like('nama_prodi', $course->prodi)->get('prodi')->row();
        $prodi_id = $prodi ? $prodi->id : 1;
        $available_cpls = $this->M_obe->get_cpls_by_prodi($prodi_id);

        $total_bobot = 0.0;
        foreach ($komponens as $k) {
            $total_bobot += (float)$k->bobot_persen;
        }

        $data = [
            'title'          => 'Setup CPMK & Asesmen - ' . $course->nama_mk,
            'page_title'     => 'Setup CPMK & Instrumen Asesmen',
            'page_desc'      => 'Pengaturan Capaian Pembelajaran Mata Kuliah dan distribusi bobot asesmen (wajib total 100%).',
            'card_subtitle'  => $course->kode_mk . ' - ' . $course->nama_mk . ' (Kelas ' . $course->kelas . ')',
            'course'         => $course,
            'cpmks'          => $cpmks,
            'komponens'      => $komponens,
            'available_cpls' => $available_cpls,
            'total_bobot'    => $total_bobot,
            'is_valid_100'   => (abs($total_bobot - 100.00) <= 0.01)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dosen/nilai/setup_cpmk', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save_cpmk()
    {
        $penawaran_id = (int)$this->input->post('penawaran_id');
        $cpmk_id      = $this->input->post('cpmk_id');
        $kode_cpmk    = $this->input->post('kode_cpmk', TRUE);
        $deskripsi    = $this->input->post('deskripsi', TRUE);
        $cpl_ids      = $this->input->post('cpl_ids');

        if (empty($kode_cpmk) || empty($deskripsi)) {
            $this->session->set_flashdata('error', 'Kode CPMK dan Deskripsi wajib diisi.');
            redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
            return;
        }

        $this->M_obe->save_cpmk_item($penawaran_id, $kode_cpmk, $deskripsi, (array)$cpl_ids, $cpmk_id);
        $this->session->set_flashdata('success', 'CPMK berhasil disimpan.');
        redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
    }

    public function delete_cpmk($cpmk_id, $penawaran_id)
    {
        $this->M_obe->delete_cpmk_item((int)$cpmk_id, (int)$penawaran_id);
        $this->session->set_flashdata('success', 'CPMK beserta komponen terkait berhasil dihapus.');
        redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
    }

    public function save_komponen()
    {
        $penawaran_id = (int)$this->input->post('penawaran_id');
        $names  = $this->input->post('nama_asesmen');
        $cpmks  = $this->input->post('cpmk_id');
        $bobots = $this->input->post('bobot_persen');
        $ids    = $this->input->post('komponen_id');

        if (empty($names) || !is_array($names)) {
            $this->session->set_flashdata('error', 'Minimal harus ada 1 komponen penilaian.');
            redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
            return;
        }

        $components = [];
        for ($i = 0; $i < count($names); $i++) {
            $nama = trim($names[$i] ?? '');
            $cpmk = (int)($cpmks[$i] ?? 0);
            $bbt  = (float)($bobots[$i] ?? 0);
            $id   = !empty($ids[$i]) ? (int)$ids[$i] : null;

            if ($nama !== '' && $cpmk > 0) {
                $components[] = [
                    'id'           => $id,
                    'cpmk_id'      => $cpmk,
                    'nama_asesmen' => $nama,
                    'bobot_persen' => $bbt
                ];
            }
        }

        $res = $this->M_obe->save_assessment_components($penawaran_id, $components);

        if ($res['status']) {
            $this->session->set_flashdata('success', $res['message']);
        } else {
            $this->session->set_flashdata('error', $res['message']);
        }

        redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
    }

    public function matriks($penawaran_id)
    {
        $matrix_data = $this->M_obe->get_grading_matrix_data((int)$penawaran_id);

        if (!$matrix_data) {
            show_error('Kelas perkuliahan tidak ditemukan.', 404);
            return;
        }

        $course = $matrix_data['course'];

        $data = [
            'title'         => 'Matriks Nilai Mahasiswa - ' . $course->nama_mk,
            'page_title'    => 'Matriks Penilaian Mahasiswa',
            'page_desc'     => 'Form grid nilai mahasiswa per komponen asesmen dengan kalkulasi otomatis nilai akhir dan sinkronisasi KHS.',
            'card_subtitle' => $course->kode_mk . ' - ' . $course->nama_mk . ' (Kelas ' . $course->kelas . ' / ' . $course->tahun_akademik . ')',
            'course'        => $course,
            'komponens'     => $matrix_data['komponens'],
            'total_bobot'   => $matrix_data['total_bobot'],
            'is_obe'        => $matrix_data['is_obe_configured'],
            'students'      => $matrix_data['students'],
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dosen/nilai/matriks', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save_matriks()
    {
        $penawaran_id = (int)$this->input->post('penawaran_id');
        $grades = $this->input->post('grades');

        if (empty($grades) || !is_array($grades)) {
            $this->session->set_flashdata('error', 'Tidak ada nilai yang dikirimkan.');
            redirect('dosen/nilai/matriks/' . $penawaran_id);
            return;
        }

        $res = $this->M_obe->save_grading_matrix($penawaran_id, $grades);

        if ($res['status']) {
            $this->session->set_flashdata('success', $res['message']);
        } else {
            $this->session->set_flashdata('error', $res['message']);
        }

        redirect('dosen/nilai/matriks/' . $penawaran_id);
    }

    public function khs()
    {
        $user_id = (int)$this->session->userdata('id');
        $is_admin = in_array((int)$this->session->userdata('role'), [1, 2], true);
        $student_id = (int)$this->input->get('student_id');

        $filters = [
            'prodi'    => $this->input->get('prodi', true) ?: '',
            'angkatan' => $this->input->get('angkatan', true) ?: '',
            'semester' => $this->input->get('semester', true) ?: '',
            'status'   => $this->input->get('status', true) ?: '',
            'q'        => $this->input->get('q', true) ?: '',
        ];
        if (!$is_admin) {
            $filters['dosen_id'] = $user_id;
        }

        $advisor_students = $this->M_akademik->get_advisor_students($is_admin ? 0 : $user_id);
        $prodi_options = $this->M_akademik->get_khs_prodi_options();
        $angkatan_options = $this->M_akademik->get_khs_angkatan_options();

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
            'title' => 'Kartu Hasil Studi Mahasiswa - Smart Campus',
            'page_title' => 'Kartu Hasil Studi (KHS)',
            'page_desc' => 'Daftar hasil studi evaluasi belajar mahasiswa bimbingan perwalian.',
            'card_subtitle' => 'Kartu Hasil Studi',
            'khs_data' => $khs_data,
            'students_list' => $students_list,
            'total_students' => count($students_list),
            'prodi_options' => $prodi_options,
            'angkatan_options' => $angkatan_options,
            'filters' => $filters,
            'all_students' => $advisor_students,
            'selected_student_id' => $student_id,
            'is_admin' => $is_admin,
            'is_dosen' => true,
            'selected_jenis' => $this->input->get('jenis', true) ?: 'khs',
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

    /**
     * AJAX endpoint: Mengambil data katalog penawaran mata kuliah dan pilihan saat ini untuk modal Dosen Wali
     */
    public function get_krs_offerings_ajax()
    {
        $user_role = (int)$this->session->userdata('role');
        $user_id   = (int)$this->session->userdata('id');
        $is_admin  = in_array($user_role, [1, 2], true);

        $krs_id = (int)$this->input->get('krs_id');
        if ($krs_id < 1) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => 'Parameter KRS ID tidak valid.']));
        }

        $qb = $this->db->select('krs.*, mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.prodi, mahasiswa.semester')
                       ->from('krs')
                       ->join('akun_mahasiswa AS mahasiswa', 'mahasiswa.id = krs.akun_id', 'inner')
                       ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'left')
                       ->where('krs.id', $krs_id)
                       ->where('mahasiswa.deleted_at IS NULL', null, false);

        if (!$is_admin && $user_id > 0) {
            $qb->where('dosen_wali.dosen_id', $user_id);
        }

        $krs = $qb->get()->row();
        if (!$krs) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => 'Data KRS mahasiswa tidak ditemukan atau bukan bimbingan Anda.']));
        }

        $active_period = [
            'tahun_akademik' => $krs->tahun_akademik,
            'semester_akademik' => $krs->semester_akademik,
        ];

        // Ambil semua katalog penawaran mata kuliah yang relevan
        $offerings = $this->M_akademik->get_offerings(
            $krs->prodi,
            $krs->semester,
            $active_period
        );

        // Ambil ID penawaran yang sedang terpilih di KRS saat ini
        $current_details = $this->db->where('krs_id', $krs_id)->get('krs_detail')->result();
        $current_ids = array_map('intval', array_column($current_details, 'penawaran_id'));

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode([
                                'success' => true,
                                'krs' => $krs,
                                'current_ids' => $current_ids,
                                'offerings' => $offerings,
                                'max_sks' => M_akademik::MAX_SKS_PER_SEMESTER,
                                'min_sks' => M_akademik::MIN_SKS_PER_SEMESTER,
                            ]));
    }

    /**
     * Menyimpan penyesuaian/pemilihan/penggantian mata kuliah mahasiswa oleh Dosen Wali
     */
    public function simpan_krs_mahasiswa()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => 'Metode permintaan tidak diizinkan.']));
        }

        $user_id   = (int)$this->session->userdata('id');
        $user_role = (int)$this->session->userdata('role');
        $is_admin  = in_array($user_role, [1, 2], true);

        $krs_id       = (int)$this->input->post('krs_id');
        $offering_ids = $this->input->post('penawaran');
        $action       = $this->input->post('aksi') ?: 'simpan'; // 'simpan' atau 'setujui'
        $note         = trim((string)$this->input->post('catatan'));

        if ($offering_ids === null) {
            $offering_ids = [];
        }

        $result = $this->M_akademik->advisor_update_krs_courses(
            $krs_id,
            $offering_ids,
            $user_id,
            $action,
            $note,
            $is_admin
        );

        // Respon AJAX jika diminta melalui XHR / JSON
        if ($this->input->is_ajax_request() || $this->input->get('ajax') === '1') {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode($result));
        }

        // Respon form standard
        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        $redirect_status = ($action === 'setujui') ? 'disetujui' : 'menunggu';
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
