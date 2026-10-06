<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Akademik extends MY_Role_Controller
{
    protected $allowed_roles = [2];
    protected $allowed_bureaus = ['akademik'];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('admin/M_akademik_admin', 'M_akademik');
    }

    public function index()
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
            redirect('admin/akademik');
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
            redirect('admin/akademik');
            return;
        }

        $this->session->set_flashdata('success', 'Penawaran mata kuliah berhasil ditambahkan.');
        redirect('admin/akademik');
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
        redirect('admin/akademik');
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
        redirect('admin/akademik');
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
