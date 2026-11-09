<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller
{
    protected $allowed_roles = [1, 2, 3, 4];
    protected $allowed_bureaus = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('admin/M_akademik_admin', 'M_akademik');
    }

    public function index()
    {
        $period = $this->M_akademik->get_active_period();

        // Data statistik untuk Dashboard Biro Akademik
        $total_mahasiswa = $this->db->where('deleted_at IS NULL', null, false)->count_all_results('akun_mahasiswa');
        $total_dosen = $this->db->where('role', 4)->where('deleted_at IS NULL', null, false)->count_all_results('akun_biro');
        $offerings = $this->M_akademik->get_admin_offerings();
        $total_penawaran = count($offerings);

        $this->load->model('mahasiswa/M_skpi_mahasiswa', 'M_skpi');
        $skpi_waiting = 0;
        try {
            $skpi_summary = $this->M_skpi->get_admin_summary();
            $skpi_waiting = (int)($skpi_summary['waiting'] ?? 0);
        } catch (\Throwable $e) {}

        // Statistik KRS
        $krs_counts = ['MENUNGGU' => 0, 'DISETUJUI' => 0, 'DITOLAK' => 0];
        try {
            $krs_rows = $this->db->select('status, COUNT(*) as total')
                ->where('tahun_akademik', $period['tahun_akademik'])
                ->where('semester_akademik', $period['semester_akademik'])
                ->group_by('status')
                ->get('krs')
                ->result();
            foreach ($krs_rows as $kr) {
                if (isset($krs_counts[$kr->status])) {
                    $krs_counts[$kr->status] = (int)$kr->total;
                }
            }
        } catch (\Throwable $e) {}

        $data = [
            'title' => 'Dashboard Akademik - Smart Campus',
            'page_title' => 'Dashboard Akademik',
            'page_desc' => 'Pusat informasi dan kendali operasional Biro Akademik.',
            'card_subtitle' => 'Ringkasan Eksekutif Akademik',
            'active_period' => $period,
            'total_mahasiswa' => $total_mahasiswa,
            'total_dosen' => $total_dosen,
            'total_penawaran' => $total_penawaran,
            'skpi_waiting' => $skpi_waiting,
            'krs_counts' => $krs_counts,
            'recent_offerings' => array_slice($offerings, 0, 6),
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/dashboard', $data);
        $this->load->view('templates/footer', $data);
    }

    public function dashboard()
    {
        $this->index();
    }

    public function operasional()
    {
        $period = $this->M_akademik->get_active_period();
        $data = [
            'title' => 'Operasional Akademik - Smart Campus',
            'page_title' => 'Operasional Akademik',
            'page_desc' => 'Kelola penawaran mata kuliah dan penetapan Dosen Wali.',
            'card_subtitle' => 'Biro Akademik',
            'active_period' => $period,
            'offerings' => $this->M_akademik->get_admin_offerings(),
            'students' => $this->M_akademik->get_students(),
            'lecturers' => $this->M_akademik->get_lecturers(),
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/admin', $data);
        $this->load->view('templates/footer', $data);
    }

    public function khs()
    {
        redirect('akademik/khs' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }

    public function nilai()
    {
        redirect('akademik/nilai' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }

    public function kehadiran()
    {
        redirect('kehadiran' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }

    public function save_offering()
    {
        if (!$this->require_post()) return;

        $this->form_validation->set_rules('kode_mk', 'Kode Mata Kuliah', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('nama_mk', 'Nama Mata Kuliah', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('prodi', 'Program Studi', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('semester', 'Semester Mahasiswa', 'required|integer|greater_than[0]|less_than_equal_to[14]');
        $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'trim|required|max_length[9]');
        $this->form_validation->set_rules('semester_akademik', 'Semester Akademik', 'required|in_list[Ganjil,Genap]');
        $this->form_validation->set_rules('sks', 'SKS', 'required|integer|greater_than[0]|less_than_equal_to[24]');
        $this->form_validation->set_rules('kelas', 'Kelas', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('hari', 'Hari', 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('ruangan', 'Ruangan', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('dosen_id', 'Dosen Pengampu', 'required|integer|greater_than[0]');

        $year = (string)$this->input->post('tahun_akademik', true);
        $start = (string)$this->input->post('waktu_mulai', true);
        $end = (string)$this->input->post('waktu_selesai', true);
        if ($this->form_validation->run() === false
            || !preg_match('/^\d{4}\/\d{4}$/', $year)
            || !$this->valid_time($start)
            || !$this->valid_time($end)
            || $start >= $end) {
            $this->session->set_flashdata('error', validation_errors('', '') ?: 'Periksa format periode dan rentang waktu perkuliahan.');
            redirect('admin/akademik/operasional');
            return;
        }

        $offering = [
            'kode_mk' => strtoupper(trim($this->input->post('kode_mk', true))),
            'nama_mk' => trim($this->input->post('nama_mk', true)),
            'prodi' => trim($this->input->post('prodi', true)),
            'semester' => (int)$this->input->post('semester', true),
            'tahun_akademik' => $year,
            'semester_akademik' => $this->input->post('semester_akademik', true),
            'sks' => (int)$this->input->post('sks', true),
            'kelas' => strtoupper(trim($this->input->post('kelas', true))),
            'hari' => $this->input->post('hari', true),
            'waktu_mulai' => $start . ':00',
            'waktu_selesai' => $end . ':00',
            'ruangan' => trim($this->input->post('ruangan', true)),
            'dosen_id' => (int)$this->input->post('dosen_id', true),
            'aktif' => 1,
        ];

        if (!$this->M_akademik->create_offering($offering)) {
            $this->session->set_flashdata('error', 'Penawaran gagal disimpan. Periksa akun dosen, data duplikat, dan benturan jadwal dosen atau ruangan.');
            redirect('admin/akademik/operasional');
            return;
        }

        $this->session->set_flashdata('success', 'Penawaran mata kuliah berhasil ditambahkan.');
        redirect('admin/akademik/operasional');
    }

    public function toggle_offering()
    {
        if (!$this->require_post()) return;

        $offering_id = (int)$this->input->post('penawaran_id', true);
        $active = $this->input->post('aktif', true) === '1';
        if ($offering_id < 1 || !$this->M_akademik->set_offering_active($offering_id, $active)) {
            $this->session->set_flashdata('error', 'Status penawaran mata kuliah tidak dapat diperbarui.');
        } else {
            $this->session->set_flashdata('success', $active ? 'Penawaran mata kuliah diaktifkan.' : 'Penawaran mata kuliah dinonaktifkan.');
        }
        redirect('admin/akademik/operasional');
    }

    public function assign_advisor()
    {
        if (!$this->require_post()) return;

        $student_id = (int)$this->input->post('mahasiswa_id', true);
        $lecturer_id = (int)$this->input->post('dosen_id', true);
        if ($student_id < 1 || $lecturer_id < 1
            || !$this->M_akademik->assign_advisor($student_id, $lecturer_id, (int)$this->session->userdata('id'))) {
            $this->session->set_flashdata('error', 'Penetapan Dosen Wali gagal. Pastikan akun mahasiswa dan dosen aktif.');
        } else {
            $this->session->set_flashdata('success', 'Dosen Wali berhasil ditetapkan.');
        }
        redirect('admin/akademik/operasional');
    }

    private function require_post()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            return true;
        }
        show_error('Metode permintaan tidak diizinkan.', 405);
        return false;
    }

    private function valid_time($value)
    {
        $time = DateTime::createFromFormat('!H:i', $value);
        return $time && $time->format('H:i') === $value;
    }
}
