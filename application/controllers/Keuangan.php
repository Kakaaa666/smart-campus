<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

    private function current_role()
    {
        return (int)$this->session->userdata('role');
    }

    /**
     * Memastikan endpoint hanya dapat dipakai role yang ditentukan.
     * Guard ini dipakai di controller, bukan di sidebar atau view.
     */
    private function require_roles(array $allowed_roles, $redirect_to = 'keuangan')
    {
        if (in_array($this->current_role(), $allowed_roles, true)) {
            return true;
        }

        $message = 'Anda tidak memiliki akses ke fitur keuangan ini.';
        if ($this->input->is_ajax_request()) {
            $this->output->set_status_header(403)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => $message]));
            return false;
        }

        $this->session->set_flashdata('error', $message);
        redirect($redirect_to);
        return false;
    }

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
     * =====================================================
     * HALAMAN UTAMA MAHASISWA - Informasi Keuangan
     * =====================================================
     * Role 2 (Admin Keuangan) diarahkan ke dashboard admin keuangan.
     * Role 3 (Mahasiswa) melihat tagihan & pembayaran sendiri.
     * Role 1 (Super Admin) dapat pratinjau data mahasiswa terpilih.
     */
    public function index()
    {
        $user_role = (int)$this->session->userdata('role');

        // Admin Keuangan (role 2) langsung diarahkan ke dashboard admin keuangan
        if ($user_role === 2) {
            redirect('keuangan/admin');
            return;
        }

        if (!$this->require_roles([1, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');

        // Super Admin (role 1) bisa pratinjau data mahasiswa
        if ($user_role === 1) {
            $target_akun_id = (int)$this->input->get('mahasiswa_id');
            if ($target_akun_id <= 0) {
                $target_akun_id = 3; // Default: Muhammad Eka
            }
            $target_mhs = $this->db->where('id', $target_akun_id)
                                   ->where('role', 3)
                                   ->where('deleted_at IS NULL', null, false)
                                   ->get('akun')->row();
            if (!$target_mhs) {
                $target_mhs = $this->db->where('role', 3)
                                       ->where('deleted_at IS NULL', null, false)
                                       ->order_by('id', 'ASC')
                                       ->get('akun')->row();
                $target_akun_id = $target_mhs ? (int)$target_mhs->id : 0;
            }
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

        $this->M_keuangan->ensure_tagihan_semester_aktif($akun_id);
        $data['ambil_semester_pendek'] = $target_mhs ? (bool)$target_mhs->ambil_semester_pendek : false;

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

        // Cek apakah mahasiswa berada di semester akhir (>= 5 untuk D3, >= 7 untuk S1)
        $is_semester_akhir = ($data['mahasiswa_info']['semester'] >= 5);
        $data['is_semester_akhir'] = $is_semester_akhir;

        if ($is_semester_akhir) {
            $this->M_keuangan->sinkronkan_tagihan_kelulusan($akun_id);
        }

        // Ambil Pengaturan Akses Pembayaran Tugas Akhir Spesifik Mahasiswa Ini
        $akses_ta_mahasiswa = $this->M_keuangan->get_status_akses_ta_mahasiswa($akun_id);
        $data['akses_ta_mahasiswa'] = $akses_ta_mahasiswa;
        $data['akses_tugas_akhir']  = $akses_ta_mahasiswa;

        if ($akses_ta_mahasiswa) {
            $this->M_keuangan->sinkronkan_tagihan_ta($akun_id, true);
        }

        // Ambil Data Tagihan & Pembayaran Milik Mahasiswa Terpilih (Otomatis filter TA jika akses ditutup)
        $daftar_tagihan      = $this->M_keuangan->get_tagihan_by_akun($akun_id);
        $tagihan_pilihan     = $this->M_keuangan->get_tagihan_belum_lunas($akun_id);
        $riwayat_pembayaran  = $this->M_keuangan->get_riwayat_pembayaran($akun_id);
        $status_krs          = $this->M_keuangan->cek_status_krs($akun_id);
        $komponen_biaya      = $this->M_keuangan->get_informasi_komponen_semester();
        $biaya_tambahan      = $this->M_keuangan->get_informasi_biaya_tambahan();

        // Hitung Otomatis Total Pengeluaran Mahasiswa dari Riwayat Pembayaran (Status LUNAS)
        $total_pengeluaran   = $this->M_keuangan->get_total_pengeluaran_mahasiswa($akun_id);

        // Hitung Ringkasan Statistik Finansial Mahasiswa
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
        $data['page_desc']          = 'Tagihan, pembayaran SPP, dan riwayat transaksi perkuliahan';
        $data['daftar_tagihan']     = $daftar_tagihan;
        $data['tagihan_pilihan']    = $tagihan_pilihan;
        $data['riwayat_pembayaran'] = $riwayat_pembayaran;
        $data['status_krs']         = $status_krs;
        $data['komponen_biaya']     = $komponen_biaya;
        $data['biaya_tambahan']     = $biaya_tambahan;
        $data['akun_id_aktif']      = $akun_id;
        $data['total_pengeluaran']  = $total_pengeluaran;
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
        $view = $user_role === 1 ? 'keuangan/superadmin/mahasiswa' : 'keuangan/mahasiswa/index';
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }
    /**
     * =====================================================
     * 1. MENU ADMIN: DASHBOARD KEUANGAN EKSEKUTIF
     * =====================================================
     * Monitoring kemajuan keuangan kampus, statistik per fakultas/prodi,
     * dan ringkasan eksekutif secara komprehensif.
     */
    public function admin()
    {
        $user_role = $this->current_role();
        if (!$this->require_roles([1, 2])) return;

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

        // Filter Fakultas & Prodi
        $filter_fakultas = $this->input->get('fakultas', true);
        $filter_prodi    = $this->input->get('prodi', true);

        $data['filter_fakultas'] = $filter_fakultas;
        $data['filter_prodi']    = $filter_prodi;

        $daftar_fp = $this->M_keuangan->get_daftar_fakultas_prodi();
        $data['daftar_fakultas'] = $daftar_fp['fakultas'];
        $data['daftar_prodi']    = $daftar_fp['prodi'];

        // Hitung ringkasan nominal
        $q_nominal = $this->db->select("
            SUM(CASE WHEN status = 'LUNAS' THEN nominal ELSE 0 END) as nominal_lunas,
            SUM(CASE WHEN status = 'PENDING' THEN nominal ELSE 0 END) as nominal_pending,
            SUM(CASE WHEN status IN ('BELUM_BAYAR','DITOLAK') THEN nominal ELSE 0 END) as nominal_belum,
            SUM(nominal) as total_nominal
        ")->get('tagihan')->row();

        $data['nominal_lunas']   = $q_nominal ? (float)$q_nominal->nominal_lunas : 0;
        $data['nominal_pending'] = $q_nominal ? (float)$q_nominal->nominal_pending : 0;
        $data['nominal_belum']   = $q_nominal ? (float)$q_nominal->nominal_belum : 0;
        $data['nominal_total']   = $q_nominal ? (float)$q_nominal->total_nominal : 0;

        // Statistik jumlah
        $data['stat_pending']   = $this->M_keuangan->count_pembayaran_by_status('PENDING');
        $data['stat_lunas']     = $this->M_keuangan->count_pembayaran_by_status('LUNAS');
        $data['stat_ditolak']   = $this->M_keuangan->count_pembayaran_by_status('DITOLAK');
        $data['stat_mahasiswa'] = $this->M_keuangan->count_mahasiswa_aktif();

        // Daftar mahasiswa dengan status tagihan
        $data['mahasiswa_belum_lunas'] = $this->M_keuangan->get_mahasiswa_belum_lunas($filter_fakultas, $filter_prodi);

        $data['title']      = 'Dashboard Keuangan - Smart Campus';
        $data['page_title'] = 'Dashboard Manajemen Keuangan';
        $data['page_desc']  = 'Monitoring eksekutif penerimaan biaya kuliah, rasio pelunasan, dan progress mahasiswa';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $view = $user_role === 1 ? 'keuangan/superadmin/dashboard' : 'keuangan/admin/dashboard';
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * =====================================================
     * 2. MENU ADMIN: VERIFIKASI PEMBAYARAN (MENU TERSENDIRI)
     * =====================================================
     * Halaman khusus untuk memeriksa bukti transfer dan menyetujui /
     * menolak pembayaran mahasiswa.
     */
    public function verifikasi()
    {
        $user_role = $this->current_role();
        if (!$this->require_roles([1, 2])) return;

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

        $filter_fakultas = $this->input->get('fakultas', true);
        $filter_prodi    = $this->input->get('prodi', true);

        $data['filter_fakultas'] = $filter_fakultas;
        $data['filter_prodi']    = $filter_prodi;

        $daftar_fp = $this->M_keuangan->get_daftar_fakultas_prodi();
        $data['daftar_fakultas'] = $daftar_fp['fakultas'];
        $data['daftar_prodi']    = $daftar_fp['prodi'];

        // Pembayaran yang menunggu verifikasi (PENDING)
        $data['pembayaran_pending'] = $this->M_keuangan->get_semua_pembayaran('PENDING', $filter_fakultas, $filter_prodi);

        // Riwayat pembayaran yang telah diverifikasi (LUNAS atau DITOLAK)
        $data['pembayaran_selesai'] = $this->M_keuangan->get_semua_pembayaran_diverifikasi($filter_fakultas, $filter_prodi);

        $data['title']      = 'Verifikasi Pembayaran - Smart Campus';
        $data['page_title'] = 'Verifikasi Pembayaran Mahasiswa';
        $data['page_desc']  = 'Pemeriksaan bukti transfer, persetujuan pembayaran LUNAS, dan penolakan transaksi';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $view = $user_role === 1 ? 'keuangan/superadmin/verifikasi' : 'keuangan/admin/verifikasi';
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * =====================================================
     * 3. MENU ADMIN: KONTROL AKSES TUGAS AKHIR (PER-MAHASISWA)
     * =====================================================
     * Buka / tutup akses pembayaran tugas akhir/semester akhir secara
     * individual sesuai kondisi akademik tiap mahasiswa.
     */
    public function kontrol_ta()
    {
        $user_role = $this->current_role();
        if (!$this->require_roles([1, 2])) return;

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

        $filter_fakultas = $this->input->get('fakultas', true);
        $filter_prodi    = $this->input->get('prodi', true);
        $keyword         = $this->input->get('q', true);

        $data['filter_fakultas'] = $filter_fakultas;
        $data['filter_prodi']    = $filter_prodi;
        $data['keyword']         = $keyword;

        $daftar_fp = $this->M_keuangan->get_daftar_fakultas_prodi();
        $data['daftar_fakultas'] = $daftar_fp['fakultas'];
        $data['daftar_prodi']    = $daftar_fp['prodi'];

        // Ambil daftar mahasiswa beserta status akses TA masing-masing
        $data['daftar_mahasiswa_ta'] = $this->M_keuangan->get_daftar_mahasiswa_ta($filter_fakultas, $filter_prodi, $keyword);

        $data['title']      = 'Kontrol Akses Tagihan - Smart Campus';
        $data['page_title'] = 'Kontrol Akses Tagihan Mahasiswa';
        $data['page_desc']  = 'Pengelolaan akses tagihan Semester Akhir dan Semester Pendek secara per mahasiswa';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $view = $user_role === 1 ? 'keuangan/superadmin/kontrol_ta' : 'keuangan/admin/kontrol_ta';
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * AJAX/POST: Toggle Buka/Tutup Akses Tugas Akhir untuk SATU MAHASISWA
     */
    public function toggle_akses_ta_mhs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/kontrol_ta');
            return;
        }

        if (!$this->require_roles([1, 2])) return;

        $akun_id = (int)$this->input->post('akun_id', true);
        $target  = $this->db->get_where('akun', ['id' => $akun_id, 'role' => 3])->row();

        if (!$target) {
            $this->output->set_status_header(404)->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Mahasiswa tidak ditemukan']));
            return;
        }

        // Tentukan status baru (jika tidak dikirim, balik status yang ada)
        $req_status = $this->input->post('status');
        if ($req_status === null) {
            $new_status = ((int)$target->akses_ta === 1) ? 0 : 1;
        } else {
            $new_status = (int)$req_status ? 1 : 0;
        }

        $updated = $this->M_keuangan->set_status_akses_ta_mahasiswa($akun_id, $new_status);

        if (!$updated) {
            $message = 'Akses Tugas Akhir hanya dapat dibuka untuk mahasiswa yang sudah berada di semester akhir.';
            $this->output->set_status_header(422)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => $message]));
            return;
        }

        $label = $new_status ? 'DIBUKA' : 'DITUTUP';
        $msg = "Akses Pembayaran Tugas Akhir untuk {$target->nama_lengkap} (NIM: {$target->nim}) berhasil {$label}.";

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success'    => true,
                             'status'     => (bool)$new_status,
                             'status_int' => $new_status,
                             'nama'       => $target->nama_lengkap,
                             'message'    => $msg
                         ]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/kontrol_ta');
    }

    public function toggle_semester_pendek_mhs()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/kontrol_ta');
            return;
        }

        if (!$this->require_roles([1, 2])) return;

        $akun_id = (int)$this->input->post('akun_id', true);
        $status = (int)$this->input->post('status', true) ? 1 : 0;
        $target = $this->db->get_where('akun', ['id' => $akun_id, 'role' => 3])->row();
        if (!$target || !$this->M_keuangan->set_status_semester_pendek($akun_id, $status)) {
            $this->session->set_flashdata('error', 'Status Semester Pendek tidak dapat diperbarui.');
            redirect('keuangan/kontrol_ta');
            return;
        }

        $label = $status ? 'diaktifkan' : 'dinonaktifkan';
        $this->session->set_flashdata('success', "Semester Pendek untuk {$target->nama_lengkap} berhasil {$label}.");
        redirect('keuangan/kontrol_ta');
    }

    /**
     * AJAX: Ambil rinci tagihan + pembayaran SATU mahasiswa (untuk modal di dashboard admin).
     * Selalu kembalikan JSON.
     */
    public function detail_tagihan_mahasiswa()
    {
        if (!$this->require_roles([1, 2])) return;

        $akun_id = (int)$this->input->get('mahasiswa_id');
        if ($akun_id <= 0) {
            $this->output->set_status_header(400)->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'mahasiswa_id diperlukan']));
            return;
        }
        $mhs = $this->db->where('id', $akun_id)
                ->where('role', 3)
                ->where('deleted_at IS NULL', null, false)
                ->get('akun')
                ->row();
        if (!$mhs) {
            $this->output->set_status_header(404)->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Mahasiswa tidak ditemukan']));
            return;
        }

        $tagihan = $this->M_keuangan->get_tagihan_by_akun($akun_id, false, true);
        $riwayat = $this->M_keuangan->get_riwayat_pembayaran($akun_id);
        $total_tagihan = 0; $total_lunas = 0; $total_pending = 0; $total_belum = 0;
        $count_lunas = 0; $count_pending = 0; $count_belum = 0;
        foreach ($tagihan as $t) {
            $total_tagihan += (float)$t->nominal;
            if ($t->status === 'LUNAS') { $total_lunas += (float)$t->nominal; $count_lunas++; }
            elseif ($t->status === 'PENDING') { $total_pending += (float)$t->nominal; $count_pending++; }
            else { $total_belum += (float)$t->nominal; $count_belum++; }
        }
        $total_pengeluaran = $this->M_keuangan->get_total_pengeluaran_mahasiswa($akun_id);
        $detail_by_tagihan = [];
        foreach ($tagihan as $t) {
            $detail_by_tagihan[$t->id] = [
                'id_tagihan'   => (int)$t->id,
                'status_tagihan' => $t->status,
                'nominal'      => (float)$t->nominal,
                'jenis'        => $t->jenis_tagihan,
                'tahun'        => $t->tahun_akademik,
                'semester'     => $t->semester,
                'jatuh_tempo'  => $t->jatuh_tempo,
                'pembayaran'   => null,
            ];
        }
        foreach ($riwayat as $r) {
            $tid = (int)$r->tagihan_id;
            if (isset($detail_by_tagihan[$tid])) {
                $detail_by_tagihan[$tid]['pembayaran'] = [
                    'id' => (int)$r->id, 'status' => $r->status,
                    'tanggal' => date('d M Y', strtotime($r->tanggal_pembayaran)),
                    'nominal' => (float)$r->nominal_pembayaran, 'metode' => $r->metode_pembayaran,
                    'referensi' => $r->nomor_referensi, 'bukti' => $r->bukti_pembayaran,
                    'verifikator' => $r->nama_verifikator, 'diverifikasi_at' => $r->diverifikasi_at,
                    'alasan_penolakan' => $r->alasan_penolakan,
                ];
            }
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'mahasiswa' => [
                             'id' => (int)$mhs->id, 'nim' => $mhs->nim, 'nama' => $mhs->nama_lengkap,
                             'fakultas' => $mhs->fakultas, 'prodi' => $mhs->prodi,
                             'semester' => (int)$mhs->semester, 'email' => $mhs->email,
                             'akses_ta' => (bool)($mhs->akses_ta ?: 1), 'foto' => $mhs->foto,
                         ],
                         'ringkasan' => [
                             'total_tagihan' => $total_tagihan, 'total_lunas' => $total_lunas,
                             'total_pending' => $total_pending, 'total_belum' => $total_belum,
                             'count_lunas' => $count_lunas, 'count_pending' => $count_pending,
                             'count_belum' => $count_belum, 'total_pengeluaran' => (float)$total_pengeluaran,
                         ],
                         'tagihan' => array_values($detail_by_tagihan),
                     ]));
    }
    public function toggle_akses_ta_bulk()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/kontrol_ta');
            return;
        }

        if (!$this->require_roles([1, 2])) return;

        $status = (int)$this->input->post('status') ? 1 : 0;
        $semester_min = (int)$this->input->post('semester_min') ?: 5;

        // Ambil mahasiswa dengan semester >= minimum
        $mhs_list = $this->db->where('role', 3)
                             ->where('semester >=', $semester_min)
                             ->where('deleted_at IS NULL')
                             ->get('akun')->result();

        $count = 0;
        foreach ($mhs_list as $m) {
            $this->M_keuangan->set_status_akses_ta_mahasiswa($m->id, $status);
            $count++;
        }

        $label = $status ? 'DIBUKA' : 'DITUTUP';
        $msg = "Akses Tugas Akhir untuk {$count} mahasiswa tingkat akhir (semester {$semester_min}+) berhasil {$label}.";

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'count' => $count, 'message' => $msg]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/kontrol_ta');
    }

    /**
     * =====================================================
     * AKSI: VERIFIKASI (APPROVE) PEMBAYARAN OLEH ADMIN
     * =====================================================
     * Memperbaiki tombol "Setujui" yang sebelumnya tidak jalan
     */
    public function verifikasi_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/verifikasi');
            return;
        }

        if (!$this->require_roles([1, 2])) return;

        $pembayaran_id = (int)$this->input->post('pembayaran_id', true);
        $admin_id      = (int)$this->session->userdata('id');

        // Ambil data pembayaran
        $pembayaran = $this->db->select('pembayaran.*, tagihan.akun_id as tagihan_akun_id')
                       ->from('pembayaran')
                       ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id', 'inner')
                       ->where('pembayaran.id', $pembayaran_id)
                       ->get()->row();
        if (!$pembayaran) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.']));
                return;
            }
            $this->session->set_flashdata('error', 'Data pembayaran tidak ditemukan.');
            redirect('keuangan/verifikasi');
            return;
        }

        if ($pembayaran->status !== 'PENDING' || (int)$pembayaran->akun_id !== (int)$pembayaran->tagihan_akun_id) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Pembayaran sudah diproses sebelumnya (status: ' . $pembayaran->status . ').']));
                return;
            }
            $this->session->set_flashdata('error', 'Pembayaran ini sudah diproses sebelumnya (status: ' . $pembayaran->status . ').');
            redirect('keuangan/verifikasi');
            return;
        }

        // Update pembayaran menjadi LUNAS
        $this->db->where('id', $pembayaran_id)
             ->where('status', 'PENDING')
             ->update('pembayaran', [
            'status'            => 'LUNAS',
            'alasan_penolakan'  => null,
            'diverifikasi_oleh' => $admin_id,
            'diverifikasi_at'   => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() !== 1) {
            $this->session->set_flashdata('error', 'Pembayaran sudah diproses oleh Admin lain.');
            redirect('keuangan/verifikasi');
            return;
        }

        // Update tagihan terkait menjadi LUNAS
        $this->M_keuangan->update_status_tagihan($pembayaran->tagihan_id, 'LUNAS');

        $msg = 'Pembayaran berhasil diverifikasi dan dinyatakan LUNAS. Tagihan dan akses mahasiswa ter-update seketika.';

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success'       => true, 
                             'message'       => $msg,
                             'pembayaran_id' => $pembayaran_id,
                             'tagihan_id'    => $pembayaran->tagihan_id
                         ]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/verifikasi');
    }

    /**
     * =====================================================
     * AKSI: TOLAK (REJECT) PEMBAYARAN OLEH ADMIN
     * =====================================================
     */
    public function tolak_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan/verifikasi');
            return;
        }

        if (!$this->require_roles([1, 2])) return;

        $pembayaran_id = (int)$this->input->post('pembayaran_id', true);
        $alasan        = $this->input->post('alasan_penolakan', true);
        $admin_id      = (int)$this->session->userdata('id');

        if (empty(trim($alasan))) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Alasan penolakan wajib diisi.']));
                return;
            }
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            redirect('keuangan/verifikasi');
            return;
        }

        $pembayaran = $this->db->select('pembayaran.*, tagihan.akun_id as tagihan_akun_id')
                       ->from('pembayaran')
                       ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id', 'inner')
                       ->where('pembayaran.id', $pembayaran_id)
                       ->get()->row();
        if (!$pembayaran || $pembayaran->status !== 'PENDING' || (int)$pembayaran->akun_id !== (int)$pembayaran->tagihan_akun_id) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Pembayaran tidak ditemukan atau sudah diproses.']));
                return;
            }
            $this->session->set_flashdata('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
            redirect('keuangan/verifikasi');
            return;
        }

        // Update pembayaran menjadi DITOLAK
        $this->db->where('id', $pembayaran_id)
             ->where('status', 'PENDING')
             ->update('pembayaran', [
            'status'            => 'DITOLAK',
            'alasan_penolakan'  => trim($alasan),
            'diverifikasi_oleh' => $admin_id,
            'diverifikasi_at'   => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() !== 1) {
            $this->session->set_flashdata('error', 'Pembayaran sudah diproses oleh Admin lain.');
            redirect('keuangan/verifikasi');
            return;
        }

        // Kembalikan tagihan menjadi DITOLAK agar mahasiswa dapat bayar/upload ulang
        $this->M_keuangan->update_status_tagihan($pembayaran->tagihan_id, 'DITOLAK');

        $msg = 'Pembayaran telah ditolak. Mahasiswa dapat melihat alasan penolakan dan mengunggah ulang bukti pembayaran.';

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success'       => true, 
                             'message'       => $msg,
                             'pembayaran_id' => $pembayaran_id
                         ]));
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('keuangan/verifikasi');
    }

    /**
     * =====================================================
     * 4. MENU ADMIN: LAPORAN KEUANGAN & CETAK
     * =====================================================
     */
    public function laporan()
    {
        $user_role = $this->current_role();
        if (!$this->require_roles([1, 2])) return;

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
        $view = $user_role === 1 ? 'keuangan/superadmin/laporan' : 'keuangan/admin/laporan';
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * =====================================================
     * EKSPOR LAPORAN KE FORMAT MICROSOFT WORD (.DOC)
     * =====================================================
     * Sesuai revisi: menghasilkan file Word otomatis dari data website
     */
    public function export_word()
    {
        if (!$this->require_roles([1, 2])) return;

        $tahun_akademik = $this->input->get('tahun_akademik') ?: '2026/2027';
        $semester       = $this->input->get('semester') ?: 'Ganjil';
        $fakultas       = $this->input->get('fakultas') ?: null;
        $prodi          = $this->input->get('prodi') ?: null;

        $rekap      = $this->M_keuangan->get_rekap_laporan($tahun_akademik, $semester, $fakultas, $prodi);
        $transaksi  = $this->M_keuangan->get_semua_pembayaran_laporan($tahun_akademik, $semester, $fakultas, $prodi);
        $admin_nama = $this->session->userdata('nama_lengkap') ?: 'Admin Keuangan';

        $total_nominal_semua = 0;
        $total_lunas_semua   = 0;
        $total_pending_semua = 0;
        $total_belum_semua   = 0;

        foreach ($rekap as $rk) {
            $total_nominal_semua += (float)$rk->total_nominal;
            $total_lunas_semua   += (float)$rk->nominal_lunas;
            $total_pending_semua += (float)$rk->nominal_pending;
            $total_belum_semua   += (float)$rk->nominal_belum;
        }

        $filename = "Laporan_Keuangan_SmartCampus_" . preg_replace('/[^A-Za-z0-9]/', '_', $tahun_akademik) . "_" . $semester . "_" . date('Ymd_His') . ".doc";

        header("Content-Type: application/vnd.ms-word; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Cache-Control: private, max-age=0, must-revalidate");
        header("Pragma: public");

        ?>
        <html xmlns:o='urn:schemas-microsoft-com:office:office' 
              xmlns:w='urn:schemas-microsoft-com:office:word' 
              xmlns='http://www.w3.org/TR/REC-html40'>
        <head>
            <meta charset="utf-8">
            <title>Laporan Keuangan Smart Campus</title>
            <style>
                body {
                    font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
                    font-size: 11pt;
                    color: #1e293b;
                    margin: 20px;
                }
                .kop-surat {
                    text-align: center;
                    border-bottom: 3px double #0f172a;
                    padding-bottom: 12px;
                    margin-bottom: 20px;
                }
                .kop-surat h2 {
                    margin: 0;
                    font-size: 18pt;
                    color: #1e3a8a;
                    letter-spacing: 0.5px;
                }
                .kop-surat h3 {
                    margin: 4px 0;
                    font-size: 14pt;
                    color: #334155;
                }
                .kop-surat p {
                    margin: 2px 0;
                    font-size: 9pt;
                    color: #64748b;
                }
                .judul-laporan {
                    text-align: center;
                    margin-bottom: 24px;
                }
                .judul-laporan h4 {
                    margin: 0;
                    font-size: 14pt;
                    color: #0f172a;
                    text-decoration: underline;
                }
                .meta-table {
                    width: 100%;
                    margin-bottom: 18px;
                    font-size: 10pt;
                }
                .meta-table td {
                    padding: 4px 6px;
                }
                table.data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 24px;
                    font-size: 9.5pt;
                }
                table.data-table th {
                    background-color: #f1f5f9;
                    border: 1px solid #94a3b8;
                    padding: 8px 10px;
                    text-align: left;
                    font-weight: bold;
                    color: #0f172a;
                }
                table.data-table td {
                    border: 1px solid #cbd5e1;
                    padding: 7px 10px;
                    vertical-align: middle;
                }
                table.data-table tr.total-row td {
                    background-color: #e2e8f0;
                    font-weight: bold;
                    color: #0f172a;
                }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .badge-lunas { color: #047857; font-weight: bold; }
                .badge-pending { color: #b45309; font-weight: bold; }
                .badge-ditolak { color: #dc2626; font-weight: bold; }
                .tanda-tangan {
                    width: 100%;
                    margin-top: 40px;
                }
                .tanda-tangan td {
                    vertical-align: top;
                    padding: 10px;
                }
            </style>
        </head>
        <body>
            <div class="kop-surat">
                <h2>UNIVERSITAS SMART CAMPUS</h2>
                <h3>BIRO KEUANGAN DAN ADMINISTRASI AKADEMIK</h3>
                <p>Jl. Kampus Terpadu No. 123, Gd. Rektorat Lt. 2 | Telp: (021) 789-0123 | Email: keuangan@smartcampus.ac.id</p>
            </div>

            <div class="judul-laporan">
                <h4>LAPORAN EKSEKUTIF PENERIMAAN KEUANGAN KAMPUS</h4>
                <p style="margin: 4px 0; font-size: 10pt; color: #475569;">
                    Periode: Tahun Akademik <?= htmlspecialchars($tahun_akademik) ?> &bull; Semester <?= htmlspecialchars($semester) ?>
                </p>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 18%;"><strong>Fakultas</strong></td>
                    <td style="width: 32%;">: <?= $fakultas ? htmlspecialchars($fakultas) : 'Semua Fakultas' ?></td>
                    <td style="width: 18%;"><strong>Tanggal Cetak</strong></td>
                    <td style="width: 32%;">: <?= date('d F Y, H:i') ?> WIB</td>
                </tr>
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td>: <?= $prodi ? htmlspecialchars($prodi) : 'Semua Program Studi' ?></td>
                    <td><strong>Petugas Export</strong></td>
                    <td>: <?= htmlspecialchars($admin_nama) ?></td>
                </tr>
            </table>

            <h5 style="font-size: 11pt; margin-bottom: 8px; color: #1e3a8a;">I. REKAPITULASI BERDASARKAN KOMPONEN TAGIHAN</h5>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th>Komponen Tagihan</th>
                        <th class="text-center">Jml Mahasiswa</th>
                        <th class="text-right">Total Kewajiban (Rp)</th>
                        <th class="text-right">Realisasi Lunas (Rp)</th>
                        <th class="text-right">Menunggu Verifikasi (Rp)</th>
                        <th class="text-right">Tunggakan (Rp)</th>
                        <th class="text-center">Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekap)): ?>
                        <tr><td colspan="8" class="text-center">Tidak ada data rekapitulasi pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($rekap as $r): 
                            $pct = ($r->total_nominal > 0) ? round(($r->nominal_lunas / $r->total_nominal) * 100, 1) : 0;
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($r->jenis_tagihan) ?></strong></td>
                                <td class="text-center"><?= $r->jumlah_tagihan ?></td>
                                <td class="text-right"><?= number_format($r->total_nominal, 0, ',', '.') ?></td>
                                <td class="text-right" style="color:#047857; font-weight:bold;"><?= number_format($r->nominal_lunas, 0, ',', '.') ?></td>
                                <td class="text-right" style="color:#b45309;"><?= number_format($r->nominal_pending, 0, ',', '.') ?></td>
                                <td class="text-right" style="color:#dc2626;"><?= number_format($r->nominal_belum, 0, ',', '.') ?></td>
                                <td class="text-center"><strong><?= $pct ?>%</strong></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                            <td class="text-right"><?= number_format($total_nominal_semua, 0, ',', '.') ?></td>
                            <td class="text-right" style="color:#047857;"><?= number_format($total_lunas_semua, 0, ',', '.') ?></td>
                            <td class="text-right" style="color:#b45309;"><?= number_format($total_pending_semua, 0, ',', '.') ?></td>
                            <td class="text-right" style="color:#dc2626;"><?= number_format($total_belum_semua, 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?= ($total_nominal_semua > 0) ? round(($total_lunas_semua / $total_nominal_semua) * 100, 1) : 0 ?>%
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h5 style="font-size: 11pt; margin-top: 24px; margin-bottom: 8px; color: #1e3a8a;">II. DAFTAR RINCIAN TRANSAKSI PEMBAYARAN MAHASISWA</h5>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 25px;">No</th>
                        <th>Waktu Transaksi</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Kewajiban Tagihan</th>
                        <th>Metode Bayar</th>
                        <th class="text-right">Nominal (Rp)</th>
                        <th class="text-center">Status</th>
                        <th>Verifikator</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transaksi)): ?>
                        <tr><td colspan="10" class="text-center">Belum ada transaksi pembayaran pada filter ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($transaksi as $t): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($t->created_at)) ?></td>
                                <td><?= htmlspecialchars($t->nim_mahasiswa) ?></td>
                                <td><strong><?= htmlspecialchars($t->nama_mahasiswa) ?></strong></td>
                                <td><?= htmlspecialchars($t->prodi_mahasiswa ?: '-') ?></td>
                                <td><?= htmlspecialchars($t->jenis_tagihan) ?></td>
                                <td><?= htmlspecialchars($t->metode_pembayaran) ?></td>
                                <td class="text-right"><?= number_format($t->nominal_pembayaran, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge-<?= strtolower($t->status) ?>"><?= $t->status ?></span>
                                </td>
                                <td><?= htmlspecialchars($t->nama_verifikator ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <table class="tanda-tangan">
                <tr>
                    <td style="width: 60%;">
                        Mengetahui,<br>
                        <strong>Wakil Rektor II Bidang Keuangan &amp; Sarana</strong><br><br><br><br>
                        <u>Dr. Ir. Hendra Gunawan, S.Kom., M.T.</u><br>
                        NIP. 19780512 200501 1 002
                    </td>
                    <td style="width: 40%; text-align: right;">
                        Jakarta, <?= date('d F Y') ?><br>
                        <strong>Kepala Bagian Keuangan</strong><br><br><br><br>
                        <u><?= htmlspecialchars($admin_nama) ?></u><br>
                        NIP. 19851120 201001 2 004
                    </td>
                </tr>
            </table>
        </body>
        </html>
        <?php
        exit;
    }

    /**
     * AJAX Endpoint: Polling Status Real-time untuk Desktop Mahasiswa
     */
    public function status_realtime()
    {
        if (!$this->require_roles([1, 3])) return;

        $user_role       = $this->current_role();
        $current_user_id = (int)$this->session->userdata('id');

        if ($user_role === 1) {
            $akun_id = (int)$this->input->get('mahasiswa_id') ?: 3;
        } else {
            $akun_id = $current_user_id;
        }

        $target = $this->db->where('id', $akun_id)
                           ->where('role', 3)
                           ->where('deleted_at IS NULL', null, false)
                           ->get('akun')->row();
        if (!$target) {
            $this->output->set_status_header(404)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.']));
            return;
        }

        $realtime = $this->M_keuangan->get_status_realtime_mahasiswa($akun_id);
        $realtime['akses_tugas_akhir'] = $this->M_keuangan->get_status_akses_ta_mahasiswa($akun_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data'    => $realtime
            ]));
    }

    /**
     * AJAX Endpoint: Ambil Detail Pembayaran untuk Modal Edit Mahasiswa
     */
    public function get_detail_pembayaran_edit($pembayaran_id)
    {
        if (!$this->require_roles([1, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = $this->current_role();

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
     * Edit Pembayaran PENDING oleh Mahasiswa
     */
    public function edit_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
            return;
        }

        if (!$this->require_roles([1, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = $this->current_role();
        $pembayaran_id   = (int)$this->input->post('pembayaran_id', true);

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

        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'trim|required', [
            'required' => 'Metode pembayaran wajib dipilih.'
        ]);
        $this->form_validation->set_rules('tanggal_pembayaran', 'Tanggal Pembayaran', 'trim|required', [
            'required' => 'Tanggal pembayaran wajib diisi.'
        ]);
        $this->form_validation->set_rules('nomor_rekening', 'Nomor Rekening', 'trim|required|max_length[100]', [
            'required' => 'Nomor rekening wajib diisi.',
            'max_length' => 'Nomor rekening maksimal 100 karakter.'
        ]);
        $this->form_validation->set_rules('nama_rekening', 'Nama Pemilik Rekening', 'trim|required|max_length[150]', [
            'required' => 'Nama pemilik rekening wajib diisi.',
            'max_length' => 'Nama pemilik rekening maksimal 150 karakter.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>• ', '</div>'));
            redirect('keuangan');
            return;
        }

        $tanggal_pembayaran = $this->input->post('tanggal_pembayaran', true);
        $tanggal_valid = DateTime::createFromFormat('Y-m-d', $tanggal_pembayaran);
        if (!$tanggal_valid || $tanggal_valid->format('Y-m-d') !== $tanggal_pembayaran || $tanggal_pembayaran > date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal pembayaran tidak valid atau melebihi tanggal hari ini.');
            redirect('keuangan');
            return;
        }

        $update_data = [
            'metode_pembayaran'  => $this->input->post('metode_pembayaran', true),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran', true),
            'nomor_rekening'     => $this->input->post('nomor_rekening', true),
            'nama_rekening'      => $this->input->post('nama_rekening', true)
        ];

        // Cek berkas bukti baru
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
                @mkdir($upload_dir, 0755, true);
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
     */
    public function batalkan_pembayaran()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
            return;
        }

        if (!$this->require_roles([1, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = $this->current_role();
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
     * AJAX: Ambil detail pembayaran untuk modal admin
     */
    public function detail_pembayaran($pembayaran_id)
    {
        if (!$this->require_roles([1, 2])) return;

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
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('keuangan');
            return;
        }

        if (!$this->require_roles([1, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = $this->current_role();
        $tagihan_id      = (int)$this->input->post('tagihan_id', true);

        $this->form_validation->set_rules('tagihan_id', 'Pilihan Tagihan', 'required|numeric', [
            'required' => 'Silakan pilih tagihan yang akan dibayarkan.'
        ]);
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'trim|required', [
            'required' => 'Metode pembayaran wajib dipilih.'
        ]);
        $this->form_validation->set_rules('tanggal_pembayaran', 'Tanggal Pembayaran', 'trim|required', [
            'required' => 'Tanggal pembayaran wajib diisi.'
        ]);
        $this->form_validation->set_rules('nomor_rekening', 'Nomor Rekening', 'trim|required|max_length[100]', [
            'required' => 'Nomor rekening wajib diisi.',
            'max_length' => 'Nomor rekening maksimal 100 karakter.'
        ]);
        $this->form_validation->set_rules('nama_rekening', 'Nama Pemilik Rekening', 'trim|required|max_length[150]', [
            'required' => 'Nama pemilik rekening wajib diisi.',
            'max_length' => 'Nama pemilik rekening maksimal 150 karakter.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>• ', '</div>'));
            redirect('keuangan');
            return;
        }

        $tanggal_pembayaran = $this->input->post('tanggal_pembayaran', true);
        $tanggal_valid = DateTime::createFromFormat('Y-m-d', $tanggal_pembayaran);
        if (!$tanggal_valid || $tanggal_valid->format('Y-m-d') !== $tanggal_pembayaran || $tanggal_pembayaran > date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal pembayaran tidak valid atau melebihi tanggal hari ini.');
            redirect('keuangan');
            return;
        }

        if ($user_role !== 3) {
            $tagihan = $this->db->get_where('tagihan', ['id' => $tagihan_id])->row();
            $akun_id = $tagihan ? (int)$tagihan->akun_id : 0;
        } else {
            $akun_id = $current_user_id;
            $tagihan = $this->M_keuangan->get_tagihan_by_id_and_akun($tagihan_id, $akun_id);
        }

        if (!$tagihan) {
            $this->session->set_flashdata('error', 'Akses ditolak: Tagihan tidak ditemukan atau Anda tidak memiliki hak akses.');
            redirect('keuangan');
            return;
        }

        if ($tagihan->status === 'PENDING') {
            $this->session->set_flashdata('error', 'Tagihan ini sedang menunggu verifikasi Admin Keuangan.');
            redirect('keuangan');
            return;
        }

        if ($tagihan->jenis_tagihan === 'Semester Pendek' && !$this->M_keuangan->get_status_semester_pendek($akun_id)) {
            $this->session->set_flashdata('error', 'Mahasiswa tersebut belum ditandai mengambil Semester Pendek.');
            redirect('keuangan');
            return;
        }

        // Cek jika tagihan adalah jenis Tugas Akhir, pastikan akses TA mahasiswa ini dibuka
        if ((int)$tagihan->is_semester_akhir === 1 || stripos($tagihan->jenis_tagihan, 'Tugas Akhir') !== false || stripos($tagihan->jenis_tagihan, 'Skripsi') !== false) {
            $akses_ta = $this->M_keuangan->get_status_akses_ta_mahasiswa($akun_id);
            if (!$akses_ta) {
                $this->session->set_flashdata('error', 'Pembayaran Tugas Akhir untuk akun Anda saat ini sedang ditutup oleh Admin Keuangan.');
                redirect('keuangan');
                return;
            }
        }

        if ($tagihan->status === 'LUNAS') {
            $this->session->set_flashdata('error', 'Tagihan "' . $tagihan->jenis_tagihan . '" sudah berstatus LUNAS.');
            redirect('keuangan');
            return;
        }

        if (empty($_FILES['bukti_pembayaran']['name'])) {
            $this->session->set_flashdata('error', 'Berkas bukti pembayaran wajib dilampirkan.');
            redirect('keuangan');
            return;
        }

        $ext = strtolower(pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($ext, $allowed_extensions)) {
            $this->session->set_flashdata('error', 'Format berkas tidak diizinkan. Hanya berkas JPG, JPEG, PNG, dan PDF yang diperbolehkan.');
            redirect('keuangan');
            return;
        }

        $upload_dir = FCPATH . 'uploads/bukti_pembayaran/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0755, true);
        }

        $config['upload_path']      = './uploads/bukti_pembayaran/';
        $config['allowed_types']    = 'jpg|jpeg|png|pdf';
        $config['max_size']         = 3072;
        $config['file_name']        = 'bukti_' . $akun_id . '_' . $tagihan->id . '_' . time();
        $config['file_ext_tolower'] = TRUE;
        $config['overwrite']        = FALSE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('bukti_pembayaran')) {
            $upload_err = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal mengunggah bukti transfer: ' . $upload_err);
            redirect('keuangan');
            return;
        }

        $upload_res = $this->upload->data();
        $file_saved = $upload_res['file_name'];

        $data_pembayaran = [
            'tagihan_id'         => $tagihan->id,
            'akun_id'            => $akun_id,
            'metode_pembayaran'  => $this->input->post('metode_pembayaran', true),
            'tanggal_pembayaran' => $tanggal_pembayaran,
            'nominal_pembayaran' => $tagihan->nominal,
            'nomor_rekening'     => $this->input->post('nomor_rekening', true),
            'nama_rekening'      => $this->input->post('nama_rekening', true),
            'bukti_pembayaran'   => $file_saved,
            'status'             => 'PENDING',
            'alasan_penolakan'   => null,
            'diverifikasi_oleh'  => null,
            'diverifikasi_at'    => null
        ];

        $this->M_keuangan->insert_pembayaran($data_pembayaran);
        $this->M_keuangan->update_status_tagihan($tagihan->id, 'PENDING');

        $this->session->set_flashdata('success', 'Konfirmasi pembayaran berhasil dikirim dan sedang menunggu verifikasi Admin Keuangan.');
        redirect('keuangan');
    }

    /**
     * Tampilkan atau Unduh Bukti Pembayaran dengan Proteksi Akun
     */
    public function lihat_bukti($pembayaran_id)
    {
        if (!$this->require_roles([1, 2, 3])) return;

        $current_user_id = (int)$this->session->userdata('id');
        $user_role       = $this->current_role();

        if ($user_role !== 3) {
            $pembayaran = $this->db->get_where('pembayaran', ['id' => (int)$pembayaran_id])->row();
        } else {
            $pembayaran = $this->M_keuangan->get_pembayaran_by_id_and_akun($pembayaran_id, $current_user_id);
        }

        if (!$pembayaran) {
            show_404();
            return;
        }

        $upload_dir = realpath(FCPATH . 'uploads/bukti_pembayaran');
        $file_name = basename((string)$pembayaran->bukti_pembayaran);
        $file_path = $upload_dir ? realpath($upload_dir . DIRECTORY_SEPARATOR . $file_name) : false;

        if (!$upload_dir || !$file_path || strpos($file_path, $upload_dir . DIRECTORY_SEPARATOR) !== 0 || !is_file($file_path)) {
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
        header('Content-Disposition: inline; filename="' . $file_name . '"');
        readfile($file_path);
        exit;
    }
}
