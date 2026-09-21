<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form', 'file', 'html']);
        $this->load->model('M_keuangan');

        // Wajib login untuk mengakses seluruh fitur keuangan
        if (!$this->session->userdata('is_logged_in')) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(401)->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Sesi Anda telah berakhir. Silakan login kembali.']));
                exit;
            }
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses menu keuangan.');
            redirect('auth');
        }
    }

    /**
     * Halaman Utama Modul Keuangan
     * Role 2 (Admin Keuangan) diarahkan ke dashboard admin keuangan,
     * Role 3 (Mahasiswa) melihat tagihan & pembayaran sendiri,
     * Role 1 (Super Admin) dapat preview data mahasiswa
     */
    public function index()
    {
        $user_role = (int)$this->session->userdata('role');

        // Admin Keuangan (role 2) langsung diarahkan ke dashboard admin keuangan
        if ($user_role === 2) {
            redirect('keuangan/admin');
            return;
        }

        $current_user_id = (int)$this->session->userdata('id');

        // Super Admin (role 1) bisa preview data mahasiswa
        if ($user_role === 1) {
            $target_akun_id = (int)$this->input->get('mahasiswa_id');
            if ($target_akun_id <= 0) {
                $target_akun_id = 3; // Default: Muhammad Eka
            }
            $target_mhs = $this->db->get_where('akun', ['id' => $target_akun_id])->row();
            $data['is_admin_preview'] = true;
            $data['target_mahasiswa'] = $target_mhs;
            $data['daftar_mahasiswa'] = $this->db->get_where('akun', ['role' => 3, 'deleted_at' => null])->result();
            $akun_id = $target_akun_id;
        } else {
            // Mahasiswa biasa hanya melihat datanya sendiri
            $target_mhs = $this->db->get_where('akun', ['id' => $current_user_id])->row();
            $data['is_admin_preview'] = false;
            $data['target_mahasiswa'] = $target_mhs;
            $data['daftar_mahasiswa'] = [];
            $akun_id = $current_user_id;
        }

        // Data Akun Pengguna yang Sedang Login
        $data['user'] = [
            'id'           => $current_user_id,
            'nim'          => $this->session->userdata('nim'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'email'        => $this->session->userdata('email'),
            'role'         => $user_role,
            'role_name'    => $this->session->userdata('role_name'),
            'foto'         => $this->session->userdata('foto')
        ];

        // Profil Akademik Mahasiswa Terpilih
        $data['mahasiswa_info'] = [
            'id'           => $target_mhs ? $target_mhs->id : $current_user_id,
            'nim'          => $target_mhs ? $target_mhs->nim : $this->session->userdata('nim'),
            'nama_lengkap' => $target_mhs ? $target_mhs->nama_lengkap : $this->session->userdata('nama_lengkap'),
            'fakultas'     => ($target_mhs && !empty($target_mhs->fakultas)) ? $target_mhs->fakultas : 'Fakultas Ilmu Komputer',
            'prodi'        => ($target_mhs && !empty($target_mhs->prodi)) ? $target_mhs->prodi : 'D3 Sistem Informasi',
            'semester'     => ($target_mhs && !empty($target_mhs->semester)) ? (int)$target_mhs->semester : 5,
        ];

        // Cek apakah mahasiswa sudah berada di semester akhir (semester >= 5 untuk D3 / semester >= 7 untuk S1)
        $is_semester_akhir = ($data['mahasiswa_info']['semester'] >= 5);
        $data['is_semester_akhir'] = $is_semester_akhir;

        // Ambil Pengaturan Akses Pembayaran Tugas Akhir dari Admin
        $data['akses_tugas_akhir'] = $this->M_keuangan->get_setting_akses_ta();

        // Ambil Data Tagihan & Pembayaran Milik Mahasiswa Terpilih
        $daftar_tagihan      = $this->M_keuangan->get_tagihan_by_akun($akun_id);
        $tagihan_pilihan     = $this->M_keuangan->get_tagihan_belum_lunas($akun_id);
        $riwayat_pembayaran  = $this->M_keuangan->get_riwayat_pembayaran($akun_id);
        $status_krs          = $this->M_keuangan->cek_status_krs($akun_id);
        $komponen_biaya      = $this->M_keuangan->get_informasi_komponen_semester();
        $biaya_tambahan      = $this->M_keuangan->get_informasi_biaya_tambahan();

        // Hitung Ringkasan Statistik Finansial Mahasiswa (Kewajiban Pokok Semester)
        $total_tagihan     = 0;
        $total_terbayar    = 0;
        $total_belum_bayar = 0;
        $count_lunas       = 0;
        $count_pending     = 0;
        $count_belum_bayar = 0;

        foreach ($daftar_tagihan as $t) {
            $total_tagihan += (float)$t->nominal;
            if ($t->status === 'LUNAS') {
                $total_terbayar += (float)$t->nominal;
                $count_lunas++;
            } elseif ($t->status === 'PENDING') {
                $count_pending++;
            } else {
                $total_belum_bayar += (float)$t->nominal;
                $count_belum_bayar++;
            }
        }

        $data['title']              = 'Keuangan Mahasiswa - Smart Campus';
        $data['page_title']         = 'Informasi Keuangan Mahasiswa';
        $data['page_desc']          = 'Tagihan, pembayaran SPP, dan riwayat transaksi';
        $data['daftar_tagihan']     = $daftar_tagihan;
        $data['tagihan_pilihan']    = $tagihan_pilihan;
        $data['riwayat_pembayaran'] = $riwayat_pembayaran;
        $data['status_krs']         = $status_krs;
        $data['komponen_biaya']     = $komponen_biaya;
        $data['biaya_tambahan']     = $biaya_tambahan;
        $data['akun_id_aktif']      = $akun_id;
        $data['ringkasan']          = [
            'total_tagihan'     => $total_tagihan,
            'total_terbayar'    => $total_terbayar,
            'total_belum_bayar' => $total_belum_bayar,
            'count_lunas'       => $count_lunas,
            'count_pending'     => $count_pending,
            'count_belum_bayar' => $count_belum_bayar
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('keuangan/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * =====================================================
     * HALAMAN ADMIN KEUANGAN - Dashboard Verifikasi
     * Dilengkapi pembeda Fakultas & Prodi, Filter, dan Live Search
     * =====================================================
     */
    public function admin()
    {
        $user_role = (int)$this->session->userdata('role');

        // Hanya Super Admin (1) dan Admin Keuangan (2) yang boleh akses
        if ($user_role === 3) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Admin Keuangan.');
            redirect('keuangan');
            return;
        }

        $current_user_id = (int)$this->session->userdata('id');

        $data['user'] = [
            'id'           => $current_user_id,
            'nim'          => $this->session->userdata('nim'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'email'        => $this->session->userdata('email'),
            'role'         => $user_role,
            'role_name'    => $this->session->userdata('role_name'),
            'foto'         => $this->session->userdata('foto')
        ];

        // Filter dari URL GET (Fakultas & Prodi)
        $filter_fakultas = $this->input->get('fakultas', true);
        $filter_prodi    = $this->input->get('prodi', true);

        $data['filter_fakultas'] = $filter_fakultas;
        $data['filter_prodi']    = $filter_prodi;

        // Ambil daftar master Fakultas dan Prodi unik untuk dropdown filter
        $daftar_fp = $this->M_keuangan->get_daftar_fakultas_prodi();
        $data['daftar_fakultas'] = $daftar_fp['fakultas'];
        $data['daftar_prodi']    = $daftar_fp['prodi'];

        // Pengaturan Buka / Tutup Akses Pembayaran Tugas Akhir
        $data['akses_tugas_akhir'] = $this->M_keuangan->get_setting_akses_ta();

        // Statistik ringkasan untuk dashboard admin
        $data['stat_pending']   = $this->M_keuangan->count_pembayaran_by_status('PENDING');
        $data['stat_lunas']     = $this->M_keuangan->count_pembayaran_by_status('LUNAS');
        $data['stat_ditolak']   = $this->M_keuangan->count_pembayaran_by_status('DITOLAK');
        $data['stat_mahasiswa'] = $this->M_keuangan->count_mahasiswa_aktif();

        // Daftar pembayaran pending untuk diverifikasi (dengan filter fakultas & prodi)
        $data['pembayaran_pending'] = $this->M_keuangan->get_semua_pembayaran('PENDING', $filter_fakultas, $filter_prodi);

        // Daftar mahasiswa dengan tagihan (dengan filter fakultas & prodi)
        $data['mahasiswa_belum_lunas'] = $this->M_keuangan->get_mahasiswa_belum_lunas($filter_fakultas, $filter_prodi);

        $data['title']      = 'Admin Keuangan - Smart Campus';
        $data['page_title'] = 'Dashboard Admin Keuangan';
        $data['page_desc']  = 'Verifikasi pembayaran mahasiswa, pembeda fakultas/prodi, dan kontrol keuangan kampus';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('keuangan/admin', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * AJAX Endpoint: Polling Status Real-time untuk Desktop Mahasiswa
     */
    public function status_realtime()
    {
        $user_role       = (int)$this->session->userdata('role');
        $current_user_id = (int)$this->session->userdata('id');

        if ($user_role === 1) {
            $akun_id = (int)$this->input->get('mahasiswa_id') ?: 3;
        } else {
            $akun_id = $current_user_id;
        }

        $realtime = $this->M_keuangan->get_status_realtime_mahasiswa($akun_id);
        $realtime['akses_tugas_akhir'] = $this->M_keuangan->get_setting_akses_ta();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data'    => $realtime
            ]));
    }

    /**
     * Toggle Buka / Tutup Akses Pembayaran Tugas Akhir (Admin Role 1 & 2)
     */
    public function toggle_akses_ta()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/admin');
            return;
        }

        $user_role = (int)$this->session->userdata('role');
        if ($user_role === 3) {
            show_error('Akses ditolak', 403);
            return;
        }

        $new_status = $this->input->post('status');
        if ($new_status === null) {
            $current = $this->M_keuangan->get_setting_akses_ta();
            $new_status = !$current;
        } else {
            $new_status = (bool)$new_status;
        }

        $admin_name = $this->session->userdata('nama_lengkap') ?: 'Admin Keuangan';
        $this->M_keuangan->set_setting_akses_ta($new_status, $admin_name);

        $status_label = $new_status ? 'DIBUKA' : 'DITUTUP';
        $msg = "Akses Pembayaran Tugas Akhir berhasil {$status_label}.";

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true,
                             'status'  => $new_status,
                             'message' => $msg
                         ]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/admin');
    }

    /**
     * Edit Pembayaran PENDING oleh Mahasiswa
     * Mahasiswa bisa memperbaiki data atau ganti bukti transfer sebelum diverifikasi/ditolak admin
     */
    public function edit_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
            return;
        }

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');
        $pembayaran_id   = (int)$this->input->post('pembayaran_id', true);

        // Ambil data pembayaran
        if ($user_role !== 3) {
            $pembayaran = $this->db->get_where('pembayaran', ['id' => $pembayaran_id])->row();
        } else {
            $pembayaran = $this->M_keuangan->get_pembayaran_by_id_and_akun($pembayaran_id, $current_user_id);
        }

        if (!$pembayaran) {
            $this->session->set_flashdata('error', 'Data pembayaran tidak ditemukan atau Anda tidak memiliki akses.');
            redirect('keuangan');
            return;
        }

        if ($pembayaran->status !== 'PENDING') {
            $this->session->set_flashdata('error', 'Pembayaran tidak dapat diedit karena sudah diproses oleh Admin (status: ' . $pembayaran->status . ').');
            redirect('keuangan');
            return;
        }

        // Validasi Form Edit
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'trim|required', [
            'required' => 'Metode pembayaran wajib dipilih.'
        ]);
        $this->form_validation->set_rules('tanggal_pembayaran', 'Tanggal Pembayaran', 'trim|required', [
            'required' => 'Tanggal pembayaran wajib diisi.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>• ', '</div>'));
            redirect('keuangan');
            return;
        }

        $update_data = [
            'metode_pembayaran'  => $this->input->post('metode_pembayaran', true),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran', true),
            'nomor_referensi'    => $this->input->post('nomor_referensi', true)
        ];

        // Cek jika mahasiswa mengunggah berkas bukti baru
        if (!empty($_FILES['bukti_pembayaran']['name'])) {
            $ext = strtolower(pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($ext, $allowed_extensions)) {
                $this->session->set_flashdata('error', 'Format berkas bukti baru tidak valid. Hanya JPG, JPEG, PNG, dan PDF yang diperbolehkan.');
                redirect('keuangan');
                return;
            }

            $upload_dir = FCPATH . 'uploads/bukti_pembayaran/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $config['upload_path']      = './uploads/bukti_pembayaran/';
            $config['allowed_types']    = 'jpg|jpeg|png|pdf';
            $config['max_size']         = 3072;
            $config['file_name']        = 'bukti_edit_' . $pembayaran->akun_id . '_' . $pembayaran->tagihan_id . '_' . time();
            $config['file_ext_tolower'] = TRUE;
            $config['overwrite']        = FALSE;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('bukti_pembayaran')) {
                $upload_err = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Gagal mengunggah berkas bukti baru: ' . $upload_err);
                redirect('keuangan');
                return;
            }

            $upload_res = $this->upload->data();
            $file_new   = $upload_res['file_name'];

            // Hapus berkas lama jika ada
            if (!empty($pembayaran->bukti_pembayaran)) {
                $old_path = $upload_dir . $pembayaran->bukti_pembayaran;
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }

            $update_data['bukti_pembayaran'] = $file_new;
        }

        $akun_target = ($user_role !== 3) ? $pembayaran->akun_id : $current_user_id;
        $this->M_keuangan->update_pembayaran_pending($pembayaran_id, $akun_target, $update_data);

        $this->session->set_flashdata('success', 'Data pembayaran berhasil diperbarui. Admin Keuangan akan memverifikasi perubahan Anda.');
        redirect('keuangan');
    }

    /**
     * Batalkan Pembayaran PENDING oleh Mahasiswa
     * Mahasiswa tidak perlu menunggu ditolak admin jika ingin membatalkan
     */
    public function batalkan_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
            return;
        }

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');
        $pembayaran_id   = (int)$this->input->post('pembayaran_id', true);

        if ($user_role !== 3) {
            $pembayaran = $this->db->get_where('pembayaran', ['id' => $pembayaran_id])->row();
            $akun_target = $pembayaran ? (int)$pembayaran->akun_id : 0;
        } else {
            $akun_target = $current_user_id;
        }

        $res = $this->M_keuangan->batalkan_pembayaran($pembayaran_id, $akun_target);

        if ($res) {
            $msg = 'Pengajuan pembayaran berhasil dibatalkan. Status tagihan telah dikembalikan ke BELUM BAYAR.';
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'message' => $msg]));
                return;
            }
            $this->session->set_flashdata('success', $msg);
        } else {
            $msg = 'Gagal membatalkan pembayaran. Pembayaran tidak ditemukan atau sudah diproses oleh Admin.';
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => $msg]));
                return;
            }
            $this->session->set_flashdata('error', $msg);
        }

        redirect('keuangan');
    }

    /**
     * AJAX Endpoint: Ambil Detail Pembayaran untuk Modal Edit Mahasiswa
     */
    public function get_detail_pembayaran_edit($pembayaran_id)
    {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');

        if ($user_role !== 3) {
            $pembayaran = $this->db->select('pembayaran.*, tagihan.jenis_tagihan, tagihan.nominal as nominal_tagihan, tagihan.semester, tagihan.tahun_akademik')
                                   ->from('pembayaran')
                                   ->join('tagihan', 'pembayaran.tagihan_id = tagihan.id', 'inner')
                                   ->where('pembayaran.id', (int)$pembayaran_id)
                                   ->get()->row();
        } else {
            $pembayaran = $this->M_keuangan->get_pembayaran_by_id_and_akun($pembayaran_id, $current_user_id);
        }

        if (!$pembayaran) {
            $this->output->set_status_header(404)->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Data tidak ditemukan']));
            return;
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'data' => $pembayaran]));
    }

    /**
     * Proses Verifikasi (APPROVE) Pembayaran oleh Admin Keuangan
     */
    public function verifikasi_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/admin');
            return;
        }

        $user_role = (int)$this->session->userdata('role');
        if ($user_role === 3) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('keuangan');
            return;
        }

        $pembayaran_id = (int)$this->input->post('pembayaran_id', true);
        $admin_id      = (int)$this->session->userdata('id');

        // Ambil data pembayaran
        $pembayaran = $this->db->get_where('pembayaran', ['id' => $pembayaran_id])->row();
        if (!$pembayaran) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.']));
                return;
            }
            $this->session->set_flashdata('error', 'Data pembayaran tidak ditemukan.');
            redirect('keuangan/admin');
            return;
        }

        if ($pembayaran->status !== 'PENDING') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Pembayaran sudah diproses sebelumnya (status: ' . $pembayaran->status . ').']));
                return;
            }
            $this->session->set_flashdata('error', 'Pembayaran ini sudah diproses sebelumnya (status: ' . $pembayaran->status . ').');
            redirect('keuangan/admin');
            return;
        }

        // Update pembayaran menjadi LUNAS
        $this->db->where('id', $pembayaran_id)->update('pembayaran', [
            'status'            => 'LUNAS',
            'alasan_penolakan'  => null,
            'diverifikasi_oleh' => $admin_id,
            'diverifikasi_at'   => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        // Update tagihan terkait menjadi LUNAS
        $this->M_keuangan->update_status_tagihan($pembayaran->tagihan_id, 'LUNAS');

        $msg = 'Pembayaran berhasil diverifikasi dan dinyatakan LUNAS. Akses mahasiswa diperbarui secara real-time.';

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => $msg]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/admin');
    }

    /**
     * Proses Penolakan (REJECT) Pembayaran oleh Admin Keuangan
     */
    public function tolak_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/admin');
            return;
        }

        $user_role = (int)$this->session->userdata('role');
        if ($user_role === 3) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('keuangan');
            return;
        }

        $pembayaran_id   = (int)$this->input->post('pembayaran_id', true);
        $alasan          = $this->input->post('alasan_penolakan', true);
        $admin_id        = (int)$this->session->userdata('id');

        if (empty(trim($alasan))) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Alasan penolakan wajib diisi.']));
                return;
            }
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            redirect('keuangan/admin');
            return;
        }

        $pembayaran = $this->db->get_where('pembayaran', ['id' => $pembayaran_id])->row();
        if (!$pembayaran || $pembayaran->status !== 'PENDING') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Pembayaran tidak ditemukan atau sudah diproses.']));
                return;
            }
            $this->session->set_flashdata('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
            redirect('keuangan/admin');
            return;
        }

        // Update pembayaran menjadi DITOLAK
        $this->db->where('id', $pembayaran_id)->update('pembayaran', [
            'status'            => 'DITOLAK',
            'alasan_penolakan'  => $alasan,
            'diverifikasi_oleh' => $admin_id,
            'diverifikasi_at'   => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        // Kembalikan tagihan menjadi DITOLAK (agar mahasiswa bisa bayar ulang)
        $this->M_keuangan->update_status_tagihan($pembayaran->tagihan_id, 'DITOLAK');

        $msg = 'Pembayaran telah ditolak. Mahasiswa akan mendapatkan notifikasi real-time untuk memperbaiki pembayaran.';

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => $msg]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/admin');
    }

    /**
     * Halaman Laporan Keuangan untuk Rektorat
     * Filter Periode, Fakultas, Prodi, dan Rekapitulasi Eksekutif
     */
    public function laporan()
    {
        $user_role = (int)$this->session->userdata('role');
        if ($user_role === 3) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Laporan Keuangan.');
            redirect('keuangan');
            return;
        }

        $current_user_id = (int)$this->session->userdata('id');

        $data['user'] = [
            'id'           => $current_user_id,
            'nim'          => $this->session->userdata('nim'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'email'        => $this->session->userdata('email'),
            'role'         => $user_role,
            'role_name'    => $this->session->userdata('role_name'),
            'foto'         => $this->session->userdata('foto')
        ];

        // Filter periode & fakultas & prodi dari GET params
        $tahun_akademik = $this->input->get('tahun_akademik') ?: '2026/2027';
        $semester       = $this->input->get('semester') ?: 'Ganjil';
        $fakultas       = $this->input->get('fakultas') ?: null;
        $prodi          = $this->input->get('prodi') ?: null;

        $daftar_fp = $this->M_keuangan->get_daftar_fakultas_prodi();
        $data['daftar_fakultas'] = $daftar_fp['fakultas'];
        $data['daftar_prodi']    = $daftar_fp['prodi'];

        $data['laporan_rekap']     = $this->M_keuangan->get_rekap_laporan($tahun_akademik, $semester, $fakultas, $prodi);
        $data['semua_pembayaran']  = $this->M_keuangan->get_semua_pembayaran_laporan($tahun_akademik, $semester, $fakultas, $prodi);
        $data['tahun_akademik']    = $tahun_akademik;
        $data['semester']          = $semester;
        $data['filter_fakultas']   = $fakultas;
        $data['filter_prodi']      = $prodi;
        $data['daftar_tahun']      = $this->M_keuangan->get_daftar_tahun_akademik();

        $data['title']      = 'Laporan Keuangan - Smart Campus';
        $data['page_title'] = 'Laporan Keuangan ke Rektorat';
        $data['page_desc']  = 'Monitoring eksekutif rekapitulasi penerimaan keuangan kampus per periode';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('keuangan/laporan', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * AJAX: Ambil detail pembayaran untuk ditampilkan di modal verifikasi
     */
    public function detail_pembayaran($pembayaran_id)
    {
        $user_role = (int)$this->session->userdata('role');
        if ($user_role === 3) {
            show_404();
            return;
        }

        $pembayaran = $this->M_keuangan->get_detail_pembayaran_admin($pembayaran_id);
        if (!$pembayaran) {
            show_404();
            return;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'data' => $pembayaran]));
    }

    /**
     * Proses Kirim Konfirmasi Pembayaran & Upload Bukti Transfer (Mahasiswa)
     */
    public function konfirmasi_pembayaran()
    {
        // Tolak jika bukan request POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
        }

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');
        $tagihan_id      = (int)$this->input->post('tagihan_id', true);

        // Validasi Aturan Form
        $this->form_validation->set_rules('tagihan_id', 'Pilihan Tagihan', 'required|numeric', [
            'required' => 'Silakan pilih tagihan yang akan dibayarkan.'
        ]);
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'trim|required', [
            'required' => 'Metode pembayaran wajib dipilih.'
        ]);
        $this->form_validation->set_rules('tanggal_pembayaran', 'Tanggal Pembayaran', 'trim|required', [
            'required' => 'Tanggal pembayaran wajib diisi.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>• ', '</div>'));
            redirect('keuangan');
        }

        // Jika Super Admin / Admin, izinkan memproses tagihan mahasiswa terpilih untuk uji coba
        if ($user_role !== 3) {
            $tagihan = $this->db->get_where('tagihan', ['id' => $tagihan_id])->row();
            $akun_id = $tagihan ? (int)$tagihan->akun_id : 0;
        } else {
            $akun_id = $current_user_id;
            // Keamanan Anti-IDOR: Pastikan tagihan tersebut benar milik akun mahasiswa yang sedang login
            $tagihan = $this->M_keuangan->get_tagihan_by_id_and_akun($tagihan_id, $akun_id);
        }

        if (!$tagihan) {
            $this->session->set_flashdata('error', 'Akses ditolak: Tagihan tidak ditemukan atau Anda tidak memiliki hak akses.');
            redirect('keuangan');
        }

        // Cek jika tagihan adalah jenis Tugas Akhir, pastikan akses TA sedang dibuka
        if (stripos($tagihan->jenis_tagihan, 'Tugas Akhir') !== false || stripos($tagihan->jenis_tagihan, 'Skripsi') !== false) {
            $akses_ta = $this->M_keuangan->get_setting_akses_ta();
            if (!$akses_ta) {
                $this->session->set_flashdata('error', 'Pembayaran Tugas Akhir saat ini sedang ditutup oleh Admin Keuangan.');
                redirect('keuangan');
                return;
            }
        }

        // Jangan izinkan pembayaran jika tagihan sudah LUNAS
        if ($tagihan->status === 'LUNAS') {
            $this->session->set_flashdata('error', 'Tagihan "' . $tagihan->jenis_tagihan . '" sudah berstatus LUNAS.');
            redirect('keuangan');
        }

        // Cek berkas upload
        if (empty($_FILES['bukti_pembayaran']['name'])) {
            $this->session->set_flashdata('error', 'Berkas bukti pembayaran wajib dilampirkan.');
            redirect('keuangan');
        }

        // Validasi ekstensi berkas secara manual sebelum upload library
        $ext = strtolower(pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($ext, $allowed_extensions)) {
            $this->session->set_flashdata('error', 'Format berkas tidak diizinkan. Hanya berkas JPG, JPEG, PNG, dan PDF yang diperbolehkan.');
            redirect('keuangan');
        }

        // Buat folder upload jika belum ada
        $upload_dir = FCPATH . 'uploads/bukti_pembayaran/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Konfigurasi Upload CI
        $config['upload_path']      = './uploads/bukti_pembayaran/';
        $config['allowed_types']    = 'jpg|jpeg|png|pdf';
        $config['max_size']         = 3072; // Maks 3MB
        $config['file_name']        = 'bukti_' . $akun_id . '_' . $tagihan->id . '_' . time();
        $config['file_ext_tolower'] = TRUE;
        $config['overwrite']        = FALSE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('bukti_pembayaran')) {
            $upload_err = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal mengunggah bukti transfer: ' . $upload_err);
            redirect('keuangan');
        }

        $upload_res = $this->upload->data();
        $file_saved = $upload_res['file_name'];

        // Simpan Record Pembayaran Baru (Nominal diambil langsung dari database server, bukan input user)
        $data_pembayaran = [
            'tagihan_id'         => $tagihan->id,
            'akun_id'            => $akun_id,
            'metode_pembayaran'  => $this->input->post('metode_pembayaran', true),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran', true),
            'nominal_pembayaran' => $tagihan->nominal,
            'nomor_referensi'    => $this->input->post('nomor_referensi', true),
            'bukti_pembayaran'   => $file_saved,
            'status'             => 'PENDING',
            'alasan_penolakan'   => null,
            'diverifikasi_oleh'  => null,
            'diverifikasi_at'    => null
        ];

        $this->M_keuangan->insert_pembayaran($data_pembayaran);

        // Status tagihan mengikuti status pembayaran menjadi PENDING
        $this->M_keuangan->update_status_tagihan($tagihan->id, 'PENDING');

        // Pesan Sukses
        $this->session->set_flashdata('success', 'Konfirmasi pembayaran berhasil dikirim dan sedang menunggu verifikasi Admin Keuangan.');
        redirect('keuangan');
    }

    /**
     * Tampilkan atau Unduh Bukti Pembayaran dengan Proteksi Akun
     */
    public function lihat_bukti($pembayaran_id)
    {
        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = (int)$this->session->userdata('role');

        if ($user_role !== 3) {
            $pembayaran = $this->db->get_where('pembayaran', ['id' => (int)$pembayaran_id])->row();
        } else {
            $pembayaran = $this->M_keuangan->get_pembayaran_by_id_and_akun($pembayaran_id, $current_user_id);
        }

        if (!$pembayaran) {
            show_404();
            return;
        }

        $file_path = FCPATH . 'uploads/bukti_pembayaran/' . $pembayaran->bukti_pembayaran;

        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'Berkas bukti pembayaran tidak ditemukan di server.');
            redirect('keuangan');
            return;
        }

        $mime_type = get_mime_by_extension($file_path);
        if (!$mime_type) {
            $mime_type = 'application/octet-stream';
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Length: ' . filesize($file_path));
        header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
        readfile($file_path);
        exit;
    }
}
