<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Skpi extends MY_Role_Controller
{
    protected $allowed_roles = [3];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'file']);
        $this->load->library('session');
        $this->load->model('mahasiswa/M_skpi_mahasiswa', 'M_skpi');
    }

    /**
     * Halaman Utama Pengajuan SKPI Mahasiswa
     */
    public function index()
    {
        $mahasiswa_id = (int)$this->session->userdata('id');

        $filter = [
            'nama'   => trim($this->input->get('nama', true) ?: ''),
            'status' => trim($this->input->get('status', true) ?: '')
        ];

        $summary       = $this->M_skpi->get_summary($mahasiswa_id);
        $kategori_data = $this->M_skpi->get_grouped_by_kategori($mahasiswa_id, $filter);
        $kategori_list = $this->M_skpi->get_kategori_list();

        $data = [
            'title'         => 'Pengajuan SKPI - Smart Campus',
            'page_title'    => 'Pengajuan SKPI',
            'page_desc'     => 'Surat Keterangan Pendamping Ijazah (SKPI) - Rekam jejak prestasi dan kegiatan mahasiswa',
            'card_subtitle' => 'Daftar Portofolio & Pengajuan SKPI',
            'summary'       => $summary,
            'kategori_data' => $kategori_data,
            'kategori_list' => $kategori_list,
            'filter'        => $filter,
            'current_user'  => [
                'id'            => $mahasiswa_id,
                'nim'           => $this->session->userdata('nim'),
                'nama'          => $this->session->userdata('nama'),
                'prodi'         => $this->session->userdata('prodi')
            ]
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('skpi/mahasiswa_skpi', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Simpan Pengajuan Baru atau Update
     */
    public function simpan()
    {
        $mahasiswa_id = (int)$this->session->userdata('id');
        $id           = (int)$this->input->post('id');
        $kategori_id  = (int)$this->input->post('kategori_id');
        $nama_kegiatan = trim($this->input->post('nama_kegiatan', true));

        if (empty($nama_kegiatan) || $kategori_id < 1 || $kategori_id > 10) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Nama kegiatan dan kategori wajib diisi!']));
            }
            $this->session->set_flashdata('error', 'Nama kegiatan dan kategori wajib diisi!');
            redirect('skpi');
        }

        $tanggal_kegiatan = $this->input->post('tanggal_kegiatan', true) ?: null;
        $tahun = !empty($tanggal_kegiatan) ? (int)date('Y', strtotime($tanggal_kegiatan)) : (int)date('Y');

        $data_save = [
            'mahasiswa_id'     => $mahasiswa_id,
            'kategori_id'      => $kategori_id,
            'nama_kegiatan'    => $nama_kegiatan,
            'penyelenggara'    => trim($this->input->post('penyelenggara', true) ?: ''),
            'tingkat'          => $this->input->post('tingkat', true) ?: 'Lokal / Kampus',
            'peran'            => trim($this->input->post('peran', true) ?: ''),
            'tanggal_kegiatan' => $tanggal_kegiatan,
            'tahun'            => $tahun,
            'no_sertifikat'    => trim($this->input->post('no_sertifikat', true) ?: ''),
        ];

        // Handle File Upload jika ada
        if (!empty($_FILES['file_bukti']['name'])) {
            $upload_path = FCPATH . 'uploads/skpi/';
            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'skpi_' . $mahasiswa_id . '_' . time() . '_' . rand(100, 999);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file_bukti')) {
                $upload_data = $this->upload->data();
                $data_save['file_bukti'] = $upload_data['file_name'];

                // Hapus file lama jika proses edit
                if ($id) {
                    $old = $this->M_skpi->get_detail($id, $mahasiswa_id);
                    if ($old && !empty($old->file_bukti)) {
                        @unlink($upload_path . $old->file_bukti);
                    }
                }
            } else {
                $err = $this->upload->display_errors('', '');
                if ($this->input->is_ajax_request()) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => false, 'message' => 'Gagal upload file: ' . $err]));
                }
                $this->session->set_flashdata('error', 'Gagal upload file: ' . $err);
                redirect('skpi');
            }
        }

        if ($id) {
            // Cek apakah data milik mahasiswa bersangkutan
            $existing = $this->M_skpi->get_detail($id, $mahasiswa_id);
            if (!$existing) {
                if ($this->input->is_ajax_request()) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']));
                }
                $this->session->set_flashdata('error', 'Data tidak ditemukan!');
                redirect('skpi');
            }

            // Jika sebelumnya ditolak/diubah, status kembali ke WAITING untuk divalidasi ulang
            $data_save['status'] = 'WAITING';
            $this->M_skpi->simpan($data_save, $id);
            $msg = 'Pengajuan SKPI berhasil diperbarui dan menunggu validasi.';
        } else {
            $this->M_skpi->simpan($data_save);
            $msg = 'Pengajuan SKPI baru berhasil ditambahkan dan menunggu validasi.';
        }

        if ($this->input->is_ajax_request()) {
            $summary = $this->M_skpi->get_summary($mahasiswa_id);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => $msg, 'summary' => $summary]));
        }

        $this->session->set_flashdata('success', $msg);
        redirect('skpi');
    }

    /**
     * Dapatkan data detail dalam bentuk JSON untuk modal edit/detail
     */
    public function get_detail($id)
    {
        $mahasiswa_id = (int)$this->session->userdata('id');
        $item = $this->M_skpi->get_detail($id, $mahasiswa_id);

        if (!$item) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']));
        }

        $item->file_url = !empty($item->file_bukti) ? base_url('uploads/skpi/' . $item->file_bukti) : null;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'data' => $item]));
    }

    /**
     * Hapus Pengajuan SKPI
     */
    public function hapus($id)
    {
        $mahasiswa_id = (int)$this->session->userdata('id');
        $deleted = $this->M_skpi->hapus($id, $mahasiswa_id);

        if ($this->input->is_ajax_request()) {
            if ($deleted) {
                $summary = $this->M_skpi->get_summary($mahasiswa_id);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => true, 'message' => 'Data kegiatan berhasil dihapus.', 'summary' => $summary]));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Data gagal dihapus atau tidak ditemukan.']));
            }
        }

        if ($deleted) {
            $this->session->set_flashdata('success', 'Data kegiatan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data gagal dihapus atau tidak ditemukan.');
        }
        redirect('skpi');
    }
}
