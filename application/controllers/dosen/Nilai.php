<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Nilai extends MY_Role_Controller
{
    protected $allowed_roles = [1, 4]; // Dosen (4) & Super Admin (1)

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shared/M_akademik');
        $this->load->model('M_obe');
        $this->load->helper(['form', 'url']);
    }

    /**
     * Daftar Kelas Perkuliahan yang Diampu Dosen atau Program Studi (Kaprodi)
     */
    public function index()
    {
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

        // Fallback cerdas: Jika dosen reguler belum memiliki kelas yang di-mapping khusus,
        // ambilkan kelas pada prodi dosen tersebut agar tidak muncul layar kosong 0 kelas
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

    /**
     * Setup CPMK & Komponen Asesmen Perkuliahan (Wajib 100%)
     */
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

        // Ambil CPL prodi yang bersangkutan untuk dipilih saat setup CPMK
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

    /**
     * Simpan CPMK baru atau perbarui
     */
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

    /**
     * Hapus CPMK
     */
    public function delete_cpmk($cpmk_id, $penawaran_id)
    {
        $this->M_obe->delete_cpmk_item((int)$cpmk_id, (int)$penawaran_id);
        $this->session->set_flashdata('success', 'CPMK beserta komponen terkait berhasil dihapus.');
        redirect('dosen/nilai/setup_cpmk/' . $penawaran_id);
    }

    /**
     * Simpan Distribusi Komponen Penilaian (Bobot Wajib Tepat 100%)
     */
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
        $total_bobot = 0.0;

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
                $total_bobot += $bbt;
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

    /**
     * Matriks Input Nilai Mahasiswa Terintegrasi dengan Komponen Asesmen & Sinkronisasi KHS
     */
    public function matriks($penawaran_id)
    {
        $is_superadmin = ((int)$this->session->userdata('role') === 1);
        $is_kaprodi = ((int)$this->session->userdata('is_kaprodi') === 1);
        $dosen_id = ($is_superadmin || $is_kaprodi) ? 0 : (int)$this->session->userdata('id');
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

    /**
     * Simpan Massal Nilai dari Matriks Grid
     */
    public function save_matriks()
    {
        $penawaran_id = (int)$this->input->post('penawaran_id');
        $grades = $this->input->post('grades'); // [krs_detail_id][komponen_id] = nilai

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
}
