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
     * Halaman Utama: Profil Lulusan, Master CPL & Matriks Kurikulum (CPL-MK)
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
        $profil_lulusan = $this->M_obe->get_profil_lulusan($prodi_id);
        $profil_cpl_data = $this->M_obe->get_profil_cpl_matrix($prodi_id);
        $cpls = $this->M_obe->get_cpl_list($prodi_id, $kategori);
        $cpl_mk_data = $this->M_obe->get_cpl_mk_matrix_data($prodi_id);
        $matrix_data = $this->M_obe->get_curriculum_matrix($prodi_id);

        $data = [
            'title'             => 'Kurikulum OBE: Profil Lulusan, CPL & Matriks MK - Smart Campus',
            'page_title'        => 'Perancangan Kurikulum OBE & CPL',
            'page_desc'         => 'Pengelolaan Profil Lulusan, Capaian Pembelajaran Lulusan (CPL), dan Matriks Korelasi Mata Kuliah (CPL-MK).',
            'card_subtitle'     => 'Program Studi ' . ($prodi ? $prodi->nama_prodi : 'Teknik Informatika'),
            'prodi'             => $prodi,
            'all_prodi'         => $all_prodi,
            'profil_lulusan'    => $profil_lulusan,
            'profil_cpl_data'   => $profil_cpl_data,
            'cpls'              => $cpls,
            'cpl_mk_data'       => $cpl_mk_data,
            'matrix_data'       => $matrix_data,
            'kategori_filter'   => $kategori,
            'prodi_id'          => $prodi_id,
            'active_tab'        => $this->input->get('tab', TRUE) ?: 'profil',
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kaprodi/cpl/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Dashboard Evaluasi Kurikulum & Analisis Ketercapaian CPL Program Studi (Fase 5)
     */
    public function evaluasi()
    {
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;

        // Jika super admin, boleh beralih prodi via query parameter
        if ((int)$this->session->userdata('role') === 1 && $this->input->get('prodi_id')) {
            $prodi_id = (int)$this->input->get('prodi_id');
        }

        $angkatan = $this->input->get('angkatan', TRUE) ?: 'all';

        $eval_data = $this->M_obe->get_kaprodi_cpl_curriculum_evaluation($prodi_id, $angkatan);
        $all_prodi = $this->M_obe->get_all_prodi();

        $data = [
            'title'             => 'Dashboard Evaluasi Kurikulum & CPL - Smart Campus',
            'page_title'        => 'Dashboard Evaluasi Kurikulum & CPL',
            'page_desc'         => 'Monitoring dan evaluasi ketercapaian Capaian Pembelajaran Lulusan (CPL) program studi untuk penjaminan mutu kurikulum OBE.',
            'card_subtitle'     => 'Program Studi ' . ($eval_data['prodi']->nama_prodi ?? 'Teknik Informatika'),
            'eval_data'         => $eval_data,
            'all_prodi'         => $all_prodi,
            'prodi_id'          => $prodi_id,
            'selected_angkatan' => $angkatan,
            'is_superadmin'     => ((int)$this->session->userdata('role') === 1),
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kaprodi/cpl/evaluasi', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Simpan / Perbarui Profil Lulusan
     */
    public function save_profil()
    {
        $id = $this->input->post('id');
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;
        if ((int)$this->session->userdata('role') === 1 && $this->input->post('prodi_id')) {
            $prodi_id = (int)$this->input->post('prodi_id');
        }

        $kode_profil = trim((string)$this->input->post('kode_profil', TRUE));
        $nama_profil = trim((string)$this->input->post('nama_profil', TRUE));
        $deskripsi   = trim((string)$this->input->post('deskripsi', TRUE));

        if (empty($kode_profil) || empty($nama_profil)) {
            $this->session->set_flashdata('error', 'Kode Profil dan Nama Profil wajib diisi.');
            redirect('kaprodi/cpl?tab=profil' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
            return;
        }

        $data = [
            'prodi_id'    => $prodi_id,
            'kode_profil' => $kode_profil,
            'nama_profil' => $nama_profil,
            'deskripsi'   => $deskripsi
        ];

        $this->M_obe->save_profil_lulusan($data, $id ?: null);
        $this->session->set_flashdata('success', 'Profil Lulusan (' . $kode_profil . ') berhasil disimpan.');
        redirect('kaprodi/cpl?tab=profil' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
    }

    /**
     * Hapus Profil Lulusan
     */
    public function delete_profil($id)
    {
        $session_prodi = (int)$this->session->userdata('prodi_id');
        $prodi_id = $session_prodi > 0 ? $session_prodi : 1;
        if ((int)$this->session->userdata('role') === 1 && $this->input->get('prodi_id')) {
            $prodi_id = (int)$this->input->get('prodi_id');
        }

        $this->M_obe->delete_profil_lulusan((int)$id, $prodi_id);
        $this->session->set_flashdata('success', 'Profil Lulusan dan relasi CPL terkait berhasil dihapus.');
        redirect('kaprodi/cpl?tab=profil' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
    }

    /**
     * Simpan Relasi Pivot Profil Lulusan <-> CPL
     */
    public function save_profil_cpl()
    {
        $profil_id = (int)$this->input->post('profil_lulusan_id');
        $cpl_ids   = (array)$this->input->post('cpl_ids');
        $prodi_id  = (int)$this->input->post('prodi_id') ?: 1;

        if ($profil_id <= 0) {
            $this->session->set_flashdata('error', 'Profil Lulusan tidak valid.');
            redirect('kaprodi/cpl?tab=profil' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
            return;
        }

        $this->M_obe->save_profil_cpl_mapping($profil_id, $cpl_ids);
        $this->session->set_flashdata('success', 'Pemetaan Profil Lulusan ke CPL berhasil diperbarui.');
        redirect('kaprodi/cpl?tab=profil' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
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
            redirect('kaprodi/cpl?tab=cpl' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
            return;
        }

        $data = [
            'prodi_id'  => $prodi_id,
            'kode_cpl'  => $kode_cpl,
            'kategori'  => $kategori,
            'deskripsi' => $deskripsi
        ];

        $this->M_obe->save_cpl_obe($data, $id ?: null);
        $this->session->set_flashdata('success', 'Data CPL ' . $kode_cpl . ' berhasil disimpan.');
        redirect('kaprodi/cpl?tab=cpl' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
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

        $success = $this->M_obe->delete_cpl_obe((int)$id, $prodi_id);
        if ($success) {
            $this->session->set_flashdata('success', 'CPL berhasil dihapus beserta pemetaannya.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus CPL atau CPL tidak ditemukan.');
        }

        redirect('kaprodi/cpl?tab=cpl' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
    }

    /**
     * Simpan Matriks CPL-MK dan Validasi Total Bobot Tepat 100%
     */
    public function save_cpl_mk()
    {
        $cpl_id = (int)$this->input->post('cpl_id');
        $prodi_id = (int)$this->input->post('prodi_id') ?: 1;
        $mk_weights = (array)$this->input->post('mk_weights'); // [mata_kuliah_id => bobot]

        if ($cpl_id <= 0) {
            $this->session->set_flashdata('error', 'CPL yang dipilih tidak valid.');
            redirect('kaprodi/cpl?tab=cpl_mk' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
            return;
        }

        $result = $this->M_obe->save_cpl_mk_matrix($cpl_id, $mk_weights);

        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('kaprodi/cpl?tab=cpl_mk' . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : ''));
    }

    /**
     * Mengambil data CPL tunggal format JSON untuk modal edit
     */
    public function get_json($id)
    {
        $cpl = $this->M_obe->get_cpl_by_id_obe((int)$id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($cpl ?: []));
    }

    /**
     * Mengambil data Profil Lulusan format JSON untuk modal edit
     */
    public function get_profil_json($id)
    {
        $pl = $this->M_obe->get_profil_lulusan_by_id((int)$id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($pl ?: []));
    }

    /**
     * Mengambil data bobot CPL-MK untuk modal setting bobot
     */
    public function get_cpl_mk_json($cpl_id)
    {
        $cpl = $this->M_obe->get_cpl_by_id_obe((int)$cpl_id);
        $rows = $this->db->where('cpl_id', (int)$cpl_id)->get('cpl_mk')->result();
        $weights = [];
        foreach ($rows as $r) {
            $weights[$r->mata_kuliah_id] = (float)$r->bobot_cpl;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'cpl' => $cpl,
                 'weights' => $weights
             ]));
    }
}

