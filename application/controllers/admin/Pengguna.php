<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Pengguna extends MY_Role_Controller
{
    protected $allowed_roles = [1, 2];
    protected $allowed_bureaus = ['akademik'];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->model('admin/M_pengguna_admin', 'M_pengguna');
        $this->load->model('admin/M_keuangan_admin', 'M_keuangan');
    }

    public function index()
    {
        // Tangkap parameter filter dari GET query
        $filter = [
            'prodi'    => $this->input->get('prodi', true) ?: '',
            'semester' => $this->input->get('semester', true) ?: '',
            'status'   => $this->input->get('status', true) ?: '',
            'angkatan' => $this->input->get('angkatan', true) ?: '',
            'search'   => $this->input->get('q', true) ?: '',
        ];

        $students      = $this->M_pengguna->get_mahasiswa_list($filter);
        $stats         = $this->M_pengguna->get_summary_stats();
        $prodi_list    = $this->M_pengguna->get_daftar_prodi();
        $semester_list = $this->M_pengguna->get_daftar_semester();
        $angkatan_list = $this->M_pengguna->get_daftar_angkatan();

        $data = [
            'title'         => 'Data Mahasiswa - Biro Akademik',
            'page_title'    => 'Data Mahasiswa',
            'page_desc'     => 'Kelola direktori mahasiswa, program studi, status semester, dan penugasan akademik.',
            'card_subtitle' => 'Biro Akademik',
            'students'      => $students,
            'stats'         => $stats,
            'prodi_list'    => $prodi_list,
            'semester_list' => $semester_list,
            'angkatan_list' => $angkatan_list,
            'filter'        => $filter,
        ];

        $data['short_semester_students'] = $this->M_pengguna->get_students_for_short_semester_approval();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/data_mahasiswa', $data);
        $this->load->view('templates/footer', $data);
    }

    public function proses_semester_pendek()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }

        $mahasiswa_id = (int)$this->input->post('mahasiswa_id', true);
        $keputusan = $this->input->post('keputusan', true);
        if ($mahasiswa_id < 1 || !in_array($keputusan, ['DISETUJUI', 'DITOLAK'], true)) {
            $this->session->set_flashdata('error', 'Keputusan Semester Pendek tidak valid.');
            redirect('admin/pengguna');
            return;
        }

        if (!$this->M_keuangan->putuskan_semester_pendek($mahasiswa_id, $keputusan, (int)$this->session->userdata('id'))) {
            $this->session->set_flashdata('error', 'Keputusan gagal disimpan. Mahasiswa mungkin sudah disetujui atau tagihan gagal dibuat.');
            redirect('admin/pengguna');
            return;
        }

        $this->session->set_flashdata(
            'success',
            $keputusan === 'DISETUJUI'
                ? 'Semester Pendek disetujui dan tagihan telah diteruskan ke menu Keuangan mahasiswa.'
                : 'Pengajuan Semester Pendek ditolak.'
        );
        redirect('admin/pengguna');
    }

    public function mahasiswa()
    {
        $this->index();
    }

    /**
     * AJAX Endpoint untuk melihat detail lengkap profil mahasiswa
     */
    public function ajax_detail($id = null)
    {
        if (!$id) {
            $id = $this->input->get('id', true);
        }

        $student = $this->M_pengguna->get_detail_mahasiswa((int)$id);
        if (!$student) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.']));
            return;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data'    => $student
            ]));
    }

    /**
     * Update data akademik singkat mahasiswa (Status, Semester, Prodi)
     */
    public function update_akademik()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('admin/pengguna');
            return;
        }

        $id = (int)$this->input->post('id', true);
        if (!$id) {
            $this->session->set_flashdata('error', 'ID Mahasiswa tidak valid.');
            redirect('admin/pengguna');
            return;
        }

        $update_data = [];

        if ($this->input->post('prodi') !== null) {
            $update_data['prodi'] = trim($this->input->post('prodi', true));
        }

        if ($this->input->post('semester') !== null) {
            $update_data['semester'] = (int)$this->input->post('semester', true);
        }

        if ($this->input->post('status_akademik') !== null) {
            $update_data['status_akademik'] = trim($this->input->post('status_akademik', true));
        }

        if ($this->input->post('fakultas') !== null) {
            $update_data['fakultas'] = trim($this->input->post('fakultas', true));
        }

        if ($this->input->post('angkatan') !== null) {
            $update_data['angkatan'] = trim($this->input->post('angkatan', true));
        }

        if (!empty($update_data)) {
            $this->M_pengguna->update_mahasiswa($id, $update_data);
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil diperbarui.');
        }

        // Kembalikan ke halaman dengan query filter jika ada
        $redirect_url = 'admin/pengguna';
        $query_str = $this->input->post('redirect_query', true);
        if (!empty($query_str)) {
            $redirect_url .= '?' . ltrim($query_str, '?');
        }

        redirect($redirect_url);
    }
}
