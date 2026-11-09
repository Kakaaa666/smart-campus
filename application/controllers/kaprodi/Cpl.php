<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Cpl extends MY_Role_Controller
{
    protected $allowed_roles = [1, 4]; // Dosen (4) atau Super Admin (1)

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_obe');
        $this->load->helper(['form', 'url']);

        // Proteksi spesifik Kaprodi: Dosen dengan session is_kaprodi == 1 (atau Super Admin)
        $is_kaprodi = (int)$this->session->userdata('is_kaprodi') === 1;
        $is_superadmin = (int)$this->session->userdata('role') === 1;

        if (!$is_kaprodi && !$is_superadmin) {
            $uid = (int)$this->session->userdata('id');
            if ($uid > 0) {
                $uRow = $this->db->select('is_kaprodi, prodi, nama_lengkap')->where('id', $uid)->get('akun_biro')->row();
                if ($uRow && ((int)$uRow->is_kaprodi === 1 || stripos($uRow->nama_lengkap, 'kaprodi') !== false)) {
                    $is_kaprodi = true;
                    $this->session->set_userdata('is_kaprodi', 1);
                    if (!empty($uRow->prodi)) {
                        $this->session->set_userdata('prodi', $uRow->prodi);
                    }
                }
            }
        }

        if (!$is_kaprodi && !$is_superadmin) {
            show_error('Akses ditolak. Modul Master CPL & Pemetaan Kurikulum ini khusus untuk Ketua Program Studi (Kaprodi).', 403);
        }
    }

    /**
     * Halaman Utama: Daftar Master CPL & Matriks Pemetaan Kurikulum
     */
    public function index()
    {
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;

        // Jika super admin, boleh beralih prodi via query parameter
        if ((int)$this->session->userdata('role') === 1 && $this->input->get('prodi_id')) {
            $prodi_id = (int)$this->input->get('prodi_id');
        }

        $kategori_filter = $this->input->get('kategori', TRUE);
        $kategori = !empty($kategori_filter) ? trim($kategori_filter) : null;

        $prodi = $this->M_obe->get_prodi($prodi_id);
        $all_prodi = $this->M_obe->get_all_prodi();
        $cpls = $this->M_obe->get_cpls_by_prodi($prodi_id, $kategori);
        $matrix_data = $this->M_obe->get_curriculum_matrix($prodi_id);

        $data = [
            'title'           => 'Master CPL & Matriks Kurikulum - Smart Campus',
            'page_title'      => 'Master CPL & Pemetaan Kurikulum',
            'page_desc'       => 'Pengelolaan Capaian Pembelajaran Lulusan (CPL) dan matriks korelasi mata kuliah kurikulum OBE.',
            'card_subtitle'   => 'Program Studi ' . ($prodi ? $prodi->nama_prodi : 'Teknik Informatika'),
            'prodi'           => $prodi,
            'all_prodi'       => $all_prodi,
            'cpls'            => $cpls,
            'matrix_data'     => $matrix_data,
            'kategori_filter' => $kategori,
            'prodi_id'        => $prodi_id,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kaprodi/cpl/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Simpan / Perbarui CPL
     */
    public function save()
    {
        $id = $this->input->post('id');
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;
        if ((int)$this->session->userdata('role') === 1 && $this->input->post('prodi_id')) {
            $prodi_id = (int)$this->input->post('prodi_id');
        }

        $kode_cpl  = trim((string)$this->input->post('kode_cpl', TRUE));
        $kategori  = trim((string)$this->input->post('kategori', TRUE));
        $deskripsi = trim((string)$this->input->post('deskripsi', TRUE));

        if (empty($kode_cpl) || empty($kategori) || empty($deskripsi)) {
            $this->session->set_flashdata('error', 'Semua field (Kode CPL, Kategori, Deskripsi) wajib diisi.');
            redirect('kaprodi/cpl' . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : ''));
            return;
        }

        $data = [
            'prodi_id'  => $prodi_id,
            'kode_cpl'  => $kode_cpl,
            'kategori'  => $kategori,
            'deskripsi' => $deskripsi
        ];

        if (!empty($id)) {
            $this->M_obe->save_cpl($data, $id);
            $this->session->set_flashdata('success', 'Data CPL ' . $kode_cpl . ' berhasil diperbarui.');
        } else {
            $this->M_obe->save_cpl($data);
            $this->session->set_flashdata('success', 'Data CPL ' . $kode_cpl . ' baru berhasil ditambahkan.');
        }

        redirect('kaprodi/cpl' . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : ''));
    }

    /**
     * Hapus CPL
     */
    public function delete($id)
    {
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;
        if ((int)$this->session->userdata('role') === 1 && $this->input->get('prodi_id')) {
            $prodi_id = (int)$this->input->get('prodi_id');
        }

        $success = $this->M_obe->delete_cpl((int)$id, $prodi_id);
        if ($success) {
            $this->session->set_flashdata('success', 'CPL berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus CPL atau CPL tidak ditemukan.');
        }

        redirect('kaprodi/cpl' . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : ''));
    }

    /**
     * Mengambil data CPL tunggal format JSON untuk modal edit
     */
    public function get_json($id)
    {
        $cpl = $this->M_obe->get_cpl_by_id((int)$id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($cpl ?: []));
    }
}
