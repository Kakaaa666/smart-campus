<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Skpi extends MY_Role_Controller
{
    // Hanya Super Admin (role 1) dan Admin Akademik (role 2 dengan biro akademik) yang diperbolehkan
    protected $allowed_roles = [1, 2];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library(['session']);
        $this->load->model('mahasiswa/M_skpi_mahasiswa', 'M_skpi');

        $role = (int)$this->session->userdata('role');
        $biro = strtolower($this->session->userdata('biro') ?? '');

        // Jika Admin Biro (role 2), wajib Biro Akademik
        if ($role === 2 && $biro !== 'akademik') {
            show_error('Akses Ditolak: Modul Validasi SKPI hanya dapat diakses oleh Biro Akademik dan Super Administrator.', 403, '403 Akses Ditolak');
            exit;
        }
    }

    /**
     * Halaman Rekap Mahasiswa Pengaju SKPI (Daftar Mahasiswa & Jumlah Tervalidasi / Belum)
     */
    public function index()
    {
        $filter = [
            'keyword' => trim($this->input->get('keyword', true) ?: ''),
            'status'  => trim($this->input->get('status', true) ?: ''),
            'prodi'   => trim($this->input->get('prodi', true) ?: ''),
        ];

        $summary        = $this->M_skpi->get_admin_summary();
        $mahasiswa_list = $this->M_skpi->get_mahasiswa_rekap($filter);

        $data = [
            'title'          => 'Validasi SKPI Mahasiswa - Biro Akademik',
            'page_title'     => 'Validasi Pengajuan SKPI',
            'page_desc'      => 'Daftar mahasiswa yang mengajukan SKPI beserta jumlah kegiatan yang telah tervalidasi dan belum tervalidasi.',
            'card_subtitle'  => 'Rekap Pengajuan SKPI Mahasiswa',
            'summary'        => $summary,
            'mahasiswa_list' => $mahasiswa_list,
            'filter'         => $filter,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('skpi/admin_skpi', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Halaman Rincian Pengajuan SKPI Mahasiswa Berdasarkan 10 Kategori
     */
    public function detail($mahasiswa_id)
    {
        $mahasiswa_id = (int)$mahasiswa_id;
        $mahasiswa = $this->M_skpi->get_mahasiswa_info($mahasiswa_id);

        if (!$mahasiswa) {
            $this->session->set_flashdata('error', 'Data mahasiswa tidak ditemukan.');
            redirect('admin/skpi');
        }

        $filter = [
            'nama'   => trim($this->input->get('nama', true) ?: ''),
            'status' => trim($this->input->get('status', true) ?: '')
        ];

        $summary       = $this->M_skpi->get_summary($mahasiswa_id);
        $kategori_data = $this->M_skpi->get_grouped_by_kategori($mahasiswa_id, $filter);
        $kategori_list = $this->M_skpi->get_kategori_list();

        $data = [
            'title'         => 'Rincian SKPI - ' . $mahasiswa->nama_lengkap . ' (' . $mahasiswa->nim . ')',
            'page_title'    => 'Validasi SKPI Mahasiswa',
            'page_desc'     => 'Periksa portofolio dan ubah status validasi berkas kegiatan mahasiswa sesuai 10 kategori SKPI.',
            'card_subtitle' => $mahasiswa->nama_lengkap . ' - ' . $mahasiswa->nim,
            'mahasiswa'     => $mahasiswa,
            'summary'       => $summary,
            'kategori_data' => $kategori_data,
            'kategori_list' => $kategori_list,
            'filter'        => $filter,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('skpi/admin_skpi_detail', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Ubah status validasi pengajuan SKPI (Valid / Tidak Valid / Waiting)
     */
    public function update_status()
    {
        $id               = (int)$this->input->post('id');
        $status           = trim($this->input->post('status', true));
        $catatan_validasi = trim($this->input->post('catatan_validasi', true) ?: '');
        $admin_id         = (int)$this->session->userdata('id');

        if (!$id || !in_array($status, ['WAITING', 'YES', 'NO'], true)) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Data atau status tidak valid!']));
            }
            $this->session->set_flashdata('error', 'Status atau ID pengajuan tidak valid.');
            redirect('admin/skpi');
        }

        $updated = $this->M_skpi->update_status($id, $status, $catatan_validasi, $admin_id);

        $status_label = [
            'YES'     => 'disetujui (Valid)',
            'NO'      => 'ditolak (Tidak Valid)',
            'WAITING' => 'dikembalikan ke status Menunggu Validasi'
        ];
        $msg = 'Status kegiatan SKPI berhasil diubah menjadi ' . ($status_label[$status] ?? $status) . '.';

        if ($this->input->is_ajax_request()) {
            $item = $this->M_skpi->get_detail($id);
            $m_id = $item ? (int)$item->mahasiswa_id : null;
            $mhs_summary = $m_id ? $this->M_skpi->get_summary($m_id) : null;
            $admin_summary = $this->M_skpi->get_admin_summary();
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'           => true, 
                    'message'          => $msg, 
                    'catatan_validasi' => $catatan_validasi,
                    'summary'          => $mhs_summary ?: $admin_summary,
                    'admin_summary'    => $admin_summary
                ]));
        }

        $this->session->set_flashdata('success', $msg);
        redirect('admin/skpi');
    }

    /**
     * Dapatkan detail pengajuan dalam bentuk JSON
     */
    public function get_detail($id)
    {
        $item = $this->M_skpi->get_detail($id);
        if (!$item) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']));
        }

        // Ambil data mahasiswa & verifikator
        $mhs = $this->db->select('nim, nama_lengkap, prodi, semester')->where('id', $item->mahasiswa_id)->get('akun_mahasiswa')->row();
        $verif = !empty($item->divalidasi_oleh) ? $this->db->select('nama_lengkap')->where('id', $item->divalidasi_oleh)->get('akun_biro')->row() : null;

        $item->nim = $mhs->nim ?? '-';
        $item->nama_mahasiswa = $mhs->nama_lengkap ?? '-';
        $item->prodi = $mhs->prodi ?? '-';
        $item->semester = $mhs->semester ?? '-';
        $item->nama_verifikator = $verif->nama_lengkap ?? '-';
        $item->file_url = !empty($item->file_bukti) ? base_url('uploads/skpi/' . $item->file_bukti) : null;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'data' => $item]));
    }
}
