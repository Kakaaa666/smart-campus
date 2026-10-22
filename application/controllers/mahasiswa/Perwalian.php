<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan');
        $this->load->model('shared/M_akademik', 'M_akademik');

        // Wajib login untuk mengakses menu perwalian
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses menu perwalian.');
            redirect('auth');
            return;
        }

        $role = $this->session->userdata('role');
        $is_mahasiswa = ($this->session->userdata('account_type') === 'mahasiswa') || ($role === null);

        if (!$is_mahasiswa && (int)$role !== 1) {
            show_error('Menu perwalian hanya dapat diakses mahasiswa dan Superadmin.', 403);
            return;
        }
    }

    private function is_mahasiswa()
    {
        return ($this->session->userdata('account_type') === 'mahasiswa') || ($this->session->userdata('role') === null);
    }

    private function _render($page_title, $page_desc, $card_subtitle = '', $view_name = 'ambil_matakuliah') {
        $current_user_id = (int)$this->session->userdata('id');
        $is_mahasiswa    = $this->is_mahasiswa();
        $user_role       = $is_mahasiswa ? null : (int)$this->session->userdata('role');

        // Tentukan akun mahasiswa yang dicek
        if ($user_role === 1) {
            $target_akun_id = (int)$this->input->get('mahasiswa_id');
            $target_mhs = $target_akun_id > 0
                ? $this->db->where('id', $target_akun_id)->where('deleted_at IS NULL', null, false)->get('akun_mahasiswa')->row()
                : null;
            if (!$target_mhs) {
                $target_mhs = $this->db->where('deleted_at IS NULL', null, false)
                                       ->order_by('id', 'ASC')
                                       ->get('akun_mahasiswa')->row();
            }
            if (!$target_mhs) {
                show_error('Belum ada data mahasiswa aktif untuk pratinjau perwalian.', 404);
                return;
            }
            $target_akun_id = (int)$target_mhs->id;
            $data['is_admin_preview'] = true;
            $data['target_mahasiswa'] = $target_mhs;
            $data['all_students'] = $this->M_akademik->get_all_students_for_preview();
            $akun_id = $target_akun_id;
        } else {
            $target_mhs = $this->M_akademik->get_account($current_user_id);
            if (!$target_mhs) {
                show_error('Data mahasiswa aktif tidak ditemukan.', 404);
                return;
            }
            $data['is_admin_preview'] = false;
            $data['target_mahasiswa'] = $target_mhs;
            $akun_id = $current_user_id;
        }

        // Cek Status Pembayaran Semester Mahasiswa untuk Akses KRS
        $status_krs = $this->M_keuangan->cek_status_krs($akun_id);

        // Simulasi status hanya untuk preview admin, bukan mahasiswa.
        if (!$is_mahasiswa) {
            $simulasi = $this->input->get('status', true) ?: $this->input->get('simulasi', true);
            if ($simulasi === 'terkunci') {
                $status_krs['buka_krs'] = false;
                $status_krs['status']   = 'BELUM_BAYAR';
            } elseif ($simulasi === 'terbuka' || $simulasi === 'lunas') {
                $status_krs['buka_krs'] = true;
                $status_krs['status']   = 'LUNAS';
            }
        }

        $available_periods = $this->M_akademik->get_student_available_periods($akun_id);
        $active_student_period = !empty($available_periods) ? $available_periods[0] : null;

        $selected_periode = $this->input->get('periode', true);
        if (!empty($selected_periode)) {
            $cleaned = str_replace(' - ', '-', $selected_periode);
            $parts = explode('-', $cleaned);
            $academic_period = [
                'tahun_akademik' => trim($parts[0]),
                'semester_akademik' => ucfirst(strtolower(trim($parts[1] ?? 'Genap')))
            ];
        } elseif ($active_student_period) {
            $academic_period = [
                'tahun_akademik' => $active_student_period['tahun_akademik'],
                'semester_akademik' => $active_student_period['semester_akademik']
            ];
            $selected_periode = $active_student_period['label'];
        } else {
            $academic_period  = $this->M_akademik->get_active_period();
            $selected_periode = explode('/', $academic_period['tahun_akademik'])[0] . ' - ' . $academic_period['semester_akademik'];
        }

        $data['title']             = $page_title . ' - Smart Campus';
        $data['page_title']        = $page_title;
        $data['page_desc']         = $page_desc;
        $data['card_subtitle']     = $card_subtitle;
        $data['breadcrumb_parent'] = 'KRS dan Perwalian';
        $data['status_krs']        = $status_krs;
        $data['view_name']         = $view_name;
        $data['available_periods'] = $available_periods;
        $data['academic_period']   = $academic_period;
        $data['selected_periode']  = $selected_periode;
        $data['krs']               = $this->M_akademik->get_krs($akun_id, $academic_period);
        $data['advisor']           = $this->M_akademik->get_advisor_for_student($akun_id);
        $data['all_lecturers']     = $this->M_akademik->get_lecturers();
        $data['offerings']         = $this->M_akademik->get_offerings(
            $target_mhs->prodi,
            $target_mhs->semester,
            $academic_period
        );
        $data['mata_kuliah_wajib'] = $this->M_akademik->get_compulsory_courses($target_mhs->prodi, (int)$target_mhs->id);
        $data['diskusi_list']      = $this->M_akademik->get_frs_discussions($akun_id);
        $data['current_user_avatar']= $this->session->userdata('foto') ?: 'avatar-4.png';
        $data['max_sks']           = M_akademik::MAX_SKS_PER_SEMESTER;
        $data['min_sks']           = M_akademik::MIN_SKS_PER_SEMESTER;

        $krs_is_locked_initially = ($data['krs'] && in_array(strtoupper($data['krs']->status ?? ''), ['MENUNGGU', 'DISETUJUI'], true));
        $is_edit_mode = ($this->input->get('edit') === '1') || !$krs_is_locked_initially;

        $data['krs_is_locked_initially'] = $krs_is_locked_initially;
        $data['is_edit_mode'] = $is_edit_mode;
        $data['can_edit_krs'] = !empty($status_krs['buka_krs']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);

        // Jika syarat pembayaran belum terpenuhi (belum LUNAS), tampilkan tampilan KRS Terkunci
        if (!$status_krs['buka_krs']) {
            $this->load->view('perwalian/krs_terkunci', $data);
        } else {
            $target_view = ($view_name === 'frs') ? 'perwalian/frs' : 'perwalian/ambil_matakuliah';
            $this->load->view($target_view, $data);
        }

        $this->load->view('templates/footer', $data);
    }

    public function index() {
        $this->ambil_matakuliah();
    }

    public function ambil_matakuliah() {
        $this->_render('Ambil Mata Kuliah', 'Pemilihan rencana studi dan kartu rencana studi semester baru', 'Formulir pemilihan mata kuliah', 'ambil_matakuliah');
    }

    public function frs() {
        $this->_render('Formulir Rencana Studi (FRS)', 'Persetujuan dan cetak formulir rencana studi dari dosen wali', 'Status verifikasi dan validasi FRS', 'frs');
    }

    public function simpan_krs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('perwalian');
            return;
        }

        $is_mahasiswa = $this->is_mahasiswa();
        $user_role = (int)$this->session->userdata('role');

        if (!$is_mahasiswa && $user_role !== 1) {
            show_error('Hanya mahasiswa dan Superadmin yang dapat mengubah KRS.', 403);
            return;
        }

        if ($is_mahasiswa) {
            $akun_id = (int)$this->session->userdata('id');
        } else {
            $target_mhs_id = (int)$this->input->post('mahasiswa_id');
            $akun_id = $target_mhs_id > 0 ? $target_mhs_id : (int)$this->session->userdata('id');
        }

        $status_krs = $this->M_keuangan->cek_status_krs($akun_id);
        if (!$status_krs['buka_krs'] && $is_mahasiswa) {
            $this->session->set_flashdata('error', $status_krs['pesan']);
            redirect('perwalian');
            return;
        }

        $action = $this->input->post('aksi', true);
        if (!in_array($action, ['draft', 'ajukan'], true)) {
            $this->session->set_flashdata('error', 'Aksi KRS tidak valid.');
            redirect('perwalian' . (!$is_mahasiswa ? '?mahasiswa_id=' . $akun_id : ''));
            return;
        }

        $offering_ids = $this->input->post('penawaran', true);
        if ($offering_ids === null) {
            $offering_ids = [];
        }
        $result = $this->M_akademik->save_student_krs($akun_id, $offering_ids, $action === 'ajukan');
        $this->session->set_flashdata($result['success'] ? 'success' : 'error', $result['message']);

        $redirect_url = ($result['success'] && $action === 'ajukan') ? 'perwalian/frs' : 'perwalian';
        if (!$is_mahasiswa) {
            $redirect_url .= '?mahasiswa_id=' . $akun_id;
        }
        redirect($redirect_url);
    }

    public function pilih_dosen_wali()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('perwalian');
            return;
        }

        $akun_id = (int)$this->session->userdata('id');

        if (!$this->is_mahasiswa()) {
            $this->session->set_flashdata('error', 'Hanya mahasiswa yang dapat memilih dosen wali.');
            redirect('perwalian');
            return;
        }

        $dosen_id = (int)$this->input->post('dosen_id', true);
        if ($dosen_id < 1) {
            $this->session->set_flashdata('error', 'Silakan pilih Dosen Wali yang valid.');
            redirect('perwalian');
            return;
        }

        $success = $this->M_akademik->assign_advisor($akun_id, $dosen_id, $akun_id);
        if ($success) {
            $dosen = $this->db->get_where('akun_biro', ['id' => $dosen_id])->row();
            $nama_dosen = $dosen ? $dosen->nama_lengkap : 'Dosen Wali';
            $this->session->set_flashdata('success', 'Berhasil memilih ' . $nama_dosen . ' sebagai Dosen Wali Anda.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memilih dosen wali. Pastikan dosen berstatus aktif.');
        }

        redirect('perwalian');
    }

    /**
     * Menyimpan pesan diskusi/komentar pada halaman FRS
     */
    public function kirim_komentar_frs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('perwalian/frs');
            return;
        }

        $is_mahasiswa = $this->is_mahasiswa();
        $current_user_id = (int)$this->session->userdata('id');
        $target_mhs_id = (int)$this->input->post('mahasiswa_id');
        $mahasiswa_id = ($is_mahasiswa || $target_mhs_id < 1) ? $current_user_id : $target_mhs_id;

        $pesan = trim((string)$this->input->post('pesan', true));
        if ($pesan === '') {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Pesan komentar tidak boleh kosong.']);
                return;
            }
            $this->session->set_flashdata('error', 'Pesan komentar tidak boleh kosong.');
            redirect('perwalian/frs' . (!$is_mahasiswa && $mahasiswa_id ? '?mahasiswa_id=' . $mahasiswa_id : ''));
            return;
        }

        $sender_name = $this->session->userdata('nama_lengkap') ?: 'Pengguna';
        $sender_role = $this->session->userdata('role');
        $sender_type = $is_mahasiswa ? 'mahasiswa' : ((int)$sender_role === 4 ? 'dosen' : 'superadmin');
        $sender_photo = $this->session->userdata('foto') ?: 'avatar-4.png';

        $insert_data = [
            'mahasiswa_id'      => $mahasiswa_id,
            'krs_id'            => (int)$this->input->post('krs_id') ?: null,
            'tahun_akademik'    => $this->input->post('tahun_akademik', true) ?: null,
            'semester_akademik' => $this->input->post('semester_akademik', true) ?: null,
            'pengirim_id'       => $current_user_id,
            'pengirim_tipe'     => $sender_type,
            'pengirim_nama'     => $sender_name,
            'pengirim_foto'     => $sender_photo,
            'pesan'             => $pesan,
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $this->M_akademik->save_frs_discussion($insert_data);

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Komentar berhasil diposting.',
                'data'    => [
                    'nama'  => $sender_name,
                    'foto'  => $sender_photo,
                    'pesan' => nl2br(html_escape($pesan)),
                    'waktu' => date('d M Y H:i')
                ]
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Komentar berhasil dikirim.');
        $redirect_url = 'perwalian/frs' . (!$is_mahasiswa && $mahasiswa_id ? '?mahasiswa_id=' . $mahasiswa_id : '');
        redirect($redirect_url);
    }
}
