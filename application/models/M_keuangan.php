<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan extends CI_Model {

    protected $table_tagihan    = 'tagihan';
    protected $table_pembayaran = 'pembayaran';
    protected $table_akun       = 'akun';
    protected $settings_file;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->settings_file = APPPATH . 'config/keuangan_settings.json';
        $this->ensure_schema();
    }

    /** Pastikan struktur tabel tersedia tanpa membuat data simulasi. */
    public function ensure_schema()
    {
        if (!$this->db->table_exists($this->table_tagihan)) {
            $this->db->query("CREATE TABLE `{$this->table_tagihan}` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `akun_id` INT(11) NOT NULL,
                `jenis_tagihan` VARCHAR(150) NOT NULL,
                `tahun_akademik` VARCHAR(20) NOT NULL DEFAULT '2026/2027',
                `semester` VARCHAR(20) NOT NULL DEFAULT 'Ganjil',
                `nominal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `jatuh_tempo` DATE NOT NULL,
                `status` ENUM('BELUM_BAYAR','PENDING','LUNAS','DITOLAK') NOT NULL DEFAULT 'BELUM_BAYAR',
                `is_semester_akhir` TINYINT(1) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), KEY `idx_tagihan_akun` (`akun_id`), KEY `idx_tagihan_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        if (!$this->db->table_exists($this->table_pembayaran)) {
            $this->db->query("CREATE TABLE `{$this->table_pembayaran}` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `tagihan_id` INT(11) NOT NULL,
                `akun_id` INT(11) NOT NULL,
                `metode_pembayaran` VARCHAR(100) NOT NULL,
                `tanggal_pembayaran` DATE NOT NULL,
                `nominal_pembayaran` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `nomor_referensi` VARCHAR(100) NULL,
                `nomor_rekening` VARCHAR(100) NULL,
                `nama_rekening` VARCHAR(150) NULL,
                `bukti_pembayaran` VARCHAR(255) NULL,
                `status` ENUM('PENDING','LUNAS','DITOLAK') NOT NULL DEFAULT 'PENDING',
                `alasan_penolakan` TEXT NULL,
                `diverifikasi_oleh` INT(11) NULL,
                `diverifikasi_at` DATETIME NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), KEY `idx_pay_tagihan` (`tagihan_id`), KEY `idx_pay_akun` (`akun_id`), KEY `idx_pay_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        if ($this->db->table_exists($this->table_akun)) {
            $fields = $this->db->list_fields($this->table_akun);
            if (!in_array('fakultas', $fields)) $this->db->query("ALTER TABLE `{$this->table_akun}` ADD COLUMN `fakultas` VARCHAR(100) NULL DEFAULT 'Fakultas Ilmu Komputer' AFTER `foto`");
            if (!in_array('prodi', $fields)) $this->db->query("ALTER TABLE `{$this->table_akun}` ADD COLUMN `prodi` VARCHAR(100) NULL DEFAULT 'D3 Sistem Informasi' AFTER `fakultas`");
            if (!in_array('semester', $fields)) $this->db->query("ALTER TABLE `{$this->table_akun}` ADD COLUMN `semester` INT(11) NULL DEFAULT 5 AFTER `prodi`");
            if (!in_array('akses_ta', $fields)) $this->db->query("ALTER TABLE `{$this->table_akun}` ADD COLUMN `akses_ta` TINYINT(1) NOT NULL DEFAULT 0 AFTER `semester`");
            if (!in_array('ambil_semester_pendek', $fields)) $this->db->query("ALTER TABLE `{$this->table_akun}` ADD COLUMN `ambil_semester_pendek` TINYINT(1) NOT NULL DEFAULT 0 AFTER `akses_ta`");
        }

        if ($this->db->table_exists($this->table_tagihan)) {
            $fields = $this->db->list_fields($this->table_tagihan);
            if (!in_array('is_semester_akhir', $fields)) $this->db->query("ALTER TABLE `{$this->table_tagihan}` ADD COLUMN `is_semester_akhir` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`");
        }

        if ($this->db->table_exists($this->table_pembayaran)) {
            $fields = $this->db->list_fields($this->table_pembayaran);
            if (!in_array('nomor_rekening', $fields)) $this->db->query("ALTER TABLE `{$this->table_pembayaran}` ADD COLUMN `nomor_rekening` VARCHAR(100) NULL AFTER `nomor_referensi`");
            if (!in_array('nama_rekening', $fields)) $this->db->query("ALTER TABLE `{$this->table_pembayaran}` ADD COLUMN `nama_rekening` VARCHAR(150) NULL AFTER `nomor_rekening`");
            $this->db->query("UPDATE `{$this->table_pembayaran}` SET `nomor_rekening` = `nomor_referensi` WHERE (`nomor_rekening` IS NULL OR `nomor_rekening` = '') AND `nomor_referensi` IS NOT NULL AND `nomor_referensi` <> ''");
        }

        $upload_dir = FCPATH . 'uploads/bukti_pembayaran/';
        if (!is_dir($upload_dir)) @mkdir($upload_dir, 0755, true);
    }

    public function ensure_tagihan_semester_aktif($akun_id)
    {
        $akun = $this->db->select('semester, prodi')
                         ->where('id', (int)$akun_id)
                         ->where('role', 3)
                         ->where('deleted_at IS NULL', null, false)
                         ->get($this->table_akun)
                         ->row();

        if (!$akun) {
            return false;
        }

        $bulan = (int)date('n');
        $tahun = (int)date('Y');
        $semester = ($bulan >= 7 && $bulan <= 12) ? 'Ganjil' : 'Genap';
        $tahun_akademik = $semester === 'Ganjil'
            ? $tahun . '/' . ($tahun + 1)
            : ($tahun - 1) . '/' . $tahun;
        $jatuh_tempo = $semester === 'Ganjil'
            ? $tahun . '-10-15'
            : $tahun . '-03-15';

        foreach ($this->get_informasi_komponen_semester() as $komponen) {
            $exists = $this->db->where('akun_id', (int)$akun_id)
                               ->where('jenis_tagihan', $komponen['komponen'])
                               ->where('tahun_akademik', $tahun_akademik)
                               ->where('semester', $semester)
                               ->count_all_results($this->table_tagihan);

            if ($exists === 0) {
                $this->db->insert($this->table_tagihan, [
                    'akun_id'           => (int)$akun_id,
                    'jenis_tagihan'     => $komponen['komponen'],
                    'tahun_akademik'    => $tahun_akademik,
                    'semester'          => $semester,
                    'nominal'           => $komponen['nominal'],
                    'jatuh_tempo'       => $jatuh_tempo,
                    'status'            => 'BELUM_BAYAR',
                    'is_semester_akhir' => 0,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s')
                ]);
            }
        }

        return true;
    }

    private function mahasiswa_semester_akhir($akun)
    {
        $prodi = strtoupper((string)$akun->prodi);
        $batas = strpos($prodi, 'D3') !== false ? 5 : 7;
        return (int)$akun->semester >= $batas;
    }

/* Legacy seed block removed; the application must start with empty transactions.

        // Data tagihan dan pembayaran tidak di-seed otomatis.
        // Akun tetap tersedia, sedangkan transaksi dibuat melalui alur aplikasi.
    }

    /**
     * Inisialisasi data seed tagihan & pembayaran awal untuk simulasi interaktif
     */
    protected function seed_initial_data_disabled()
    {
        return;

        $now = date('Y-m-d H:i:s');
        $tempo = date('Y-m-d', strtotime('+30 days'));

        // Tagihan untuk Muhammad Eka (Akun ID 3)
        $tagihan_eka = [
            [
                'akun_id'           => 3,
                'jenis_tagihan'     => 'SPP / UKT',
                'tahun_akademik'    => '2026/2027',
                'semester'          => 'Ganjil',
                'nominal'           => 3500000,
                'jatuh_tempo'       => '2026-10-15',
                'status'            => 'LUNAS',
                'is_semester_akhir' => 0,
                'created_at'        => $now,
                'updated_at'        => $now
            ],
            [
                'akun_id'           => 3,
                'jenis_tagihan'     => 'Praktikum Komputer',
                'tahun_akademik'    => '2026/2027',
                'semester'          => 'Ganjil',
                'nominal'           => 500000,
                'jatuh_tempo'       => '2026-10-20',
                'status'            => 'PENDING',
                'is_semester_akhir' => 0,
                'created_at'        => $now,
                'updated_at'        => $now
            ],
            [
                'akun_id'           => 3,
                'jenis_tagihan'     => 'Fasilitas Akademik',
                'tahun_akademik'    => '2026/2027',
                'semester'          => 'Ganjil',
                'nominal'           => 300000,
                'jatuh_tempo'       => '2026-11-01',
                'status'            => 'BELUM_BAYAR',
                'is_semester_akhir' => 0,
                'created_at'        => $now,
                'updated_at'        => $now
            ],
            [
                'akun_id'           => 3,
                'jenis_tagihan'     => 'Sistem Informasi & Administrasi',
                'tahun_akademik'    => '2026/2027',
                'semester'          => 'Ganjil',
                'nominal'           => 200000,
                'jatuh_tempo'       => '2026-11-10',
                'status'            => 'BELUM_BAYAR',
                'is_semester_akhir' => 0,
                'created_at'        => $now,
                'updated_at'        => $now
            ]
        ];

        foreach ($tagihan_eka as $t) {
            $this->db->insert($this->table_tagihan, $t);
            $t_id = $this->db->insert_id();

            // Insert pembayaran untuk yang LUNAS
            if ($t['status'] === 'LUNAS') {
                $this->db->insert($this->table_pembayaran, [
                    'tagihan_id'         => $t_id,
                    'akun_id'            => 3,
                    'metode_pembayaran'  => 'Transfer Bank Mandiri',
                    'tanggal_pembayaran' => '2026-09-10',
                    'nominal_pembayaran' => $t['nominal'],
                    'nomor_referensi'    => 'MDR-20260910-98214',
                    'bukti_pembayaran'   => 'bukti_sample_lunas.png',
                    'status'             => 'LUNAS',
                    'alasan_penolakan'   => null,
                    'diverifikasi_oleh'  => 1,
                    'diverifikasi_at'    => '2026-09-11 10:00:00',
                    'created_at'         => '2026-09-10 14:20:00',
                    'updated_at'         => '2026-09-11 10:00:00'
                ]);
            }

            // Insert pembayaran untuk yang PENDING (siap diverifikasi di menu admin)
            if ($t['status'] === 'PENDING') {
                $this->db->insert($this->table_pembayaran, [
                    'tagihan_id'         => $t_id,
                    'akun_id'            => 3,
                    'metode_pembayaran'  => 'Transfer Bank BCA',
                    'tanggal_pembayaran' => date('Y-m-d'),
                    'nominal_pembayaran' => $t['nominal'],
                    'nomor_referensi'    => 'BCA-REF-' . rand(100000, 999999),
                    'bukti_pembayaran'   => 'bukti_sample_pending.png',
                    'status'             => 'PENDING',
                    'alasan_penolakan'   => null,
                    'diverifikasi_oleh'  => null,
                    'diverifikasi_at'    => null,
                    'created_at'         => $now,
                    'updated_at'         => $now
                ]);
            }
        }

    }


    // ======================================================
    // SHARED: KONTROL AKSES TUGAS AKHIR PER MAHASISWA
    // Dipanggil controller admin/superadmin, hasilnya dipakai view mahasiswa.
    // ======================================================

    /**
     * Ambil status buka/tutup akses pembayaran Tugas Akhir (Pengaturan Global / Fallback)
     */
    public function get_setting_akses_ta()
    {
        if (file_exists($this->settings_file)) {
            $content = @file_get_contents($this->settings_file);
            $json = json_decode($content, true);
            if (isset($json['akses_tugas_akhir'])) {
                return (bool)$json['akses_tugas_akhir'];
            }
        }
        return true;
    }

    /**
     * Simpan status buka/tutup akses pembayaran Tugas Akhir (Pengaturan Global / Fallback)
     */
    public function set_setting_akses_ta($status, $user_name = 'Admin')
    {
        $data = [
            'akses_tugas_akhir' => (bool)$status,
            'updated_at'        => date('Y-m-d H:i:s'),
            'updated_by'        => $user_name
        ];
        return (bool)@file_put_contents($this->settings_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Ambil status buka/tutup akses pembayaran Tugas Akhir untuk mahasiswa tertentu
     */
    public function get_status_akses_ta_mahasiswa($akun_id)
    {
        $mhs = $this->db->select('akses_ta')->where('id', (int)$akun_id)->get($this->table_akun)->row();
        return $mhs ? (bool)$mhs->akses_ta : false;
    }

    /**
     * Ubah status buka/tutup akses pembayaran Tugas Akhir untuk mahasiswa tertentu
     * Jika dibuka (status = 1), otomatis sinkronkan tagihan semester akhir mahasiswa.
     */
    public function set_status_akses_ta_mahasiswa($akun_id, $status)
    {
        $status_int = $status ? 1 : 0;
        $akun = $this->db->select('semester, prodi')
                         ->where('id', (int)$akun_id)
                         ->where('role', 3)
                         ->where('deleted_at IS NULL', null, false)
                         ->get($this->table_akun)
                         ->row();

        if (!$akun || ($status_int === 1 && !$this->mahasiswa_semester_akhir($akun))) {
            return false;
        }

        $this->db->where('id', (int)$akun_id)->update($this->table_akun, [
            'akses_ta'   => $status_int,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Sinkronkan hanya tagihan Bimbingan & Ujian Tugas Akhir.
        $this->sinkronkan_tagihan_ta($akun_id, $status_int);
        if ($status_int) {
            $this->sinkronkan_tagihan_kelulusan($akun_id);
        }

        return true;
    }

    /** Sinkronisasi tagihan Bimbingan & Ujian Tugas Akhir. */
    public function sinkronkan_tagihan_ta($akun_id, $status_buka)
    {
        $akun_id = (int)$akun_id;

        if ($status_buka) {
            $this->buat_tagihan_tambahan($akun_id, 'Bimbingan & Ujian Tugas Akhir', 1250000, 1);
        }
    }

    public function sinkronkan_tagihan_kelulusan($akun_id)
    {
        $this->buat_tagihan_tambahan($akun_id, 'Cetak & Administrasi Dokumen Akademik', 100000, 1);
        $this->buat_tagihan_tambahan($akun_id, 'Wisuda', 1500000, 1);
    }

    public function set_status_semester_pendek($akun_id, $status)
    {
        $akun_id = (int)$akun_id;
        $akun = $this->db->where('id', $akun_id)
                         ->where('role', 3)
                         ->where('deleted_at IS NULL', null, false)
                         ->get($this->table_akun)
                         ->row();
        if (!$akun) {
            return false;
        }

        $status_int = $status ? 1 : 0;
        $this->db->where('id', $akun_id)->update($this->table_akun, [
            'ambil_semester_pendek' => $status_int,
            'updated_at'            => date('Y-m-d H:i:s')
        ]);

        if ($status_int) {
            $this->buat_tagihan_tambahan($akun_id, 'Semester Pendek', 750000, 0);
        }

        return true;
    }

    public function get_status_semester_pendek($akun_id)
    {
        $akun = $this->db->select('ambil_semester_pendek')
                         ->where('id', (int)$akun_id)
                         ->get($this->table_akun)
                         ->row();
        return $akun ? (bool)$akun->ambil_semester_pendek : false;
    }

    private function buat_tagihan_tambahan($akun_id, $jenis, $nominal, $is_semester_akhir)
    {
        $existing = $this->db->where('akun_id', (int)$akun_id)
                             ->where('jenis_tagihan', $jenis)
                             ->get($this->table_tagihan)
                             ->row();
        if ($existing) {
            return;
        }

        $this->db->insert($this->table_tagihan, [
            'akun_id'           => (int)$akun_id,
            'jenis_tagihan'     => $jenis,
            'tahun_akademik'    => '2026/2027',
            'semester'          => 'Ganjil',
            'nominal'           => $nominal,
            'jatuh_tempo'       => date('Y-m-d', strtotime('+30 days')),
            'status'            => 'BELUM_BAYAR',
            'is_semester_akhir' => (int)$is_semester_akhir,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ambil daftar seluruh mahasiswa untuk halaman Kontrol Akses Tugas Akhir
     */
    public function get_daftar_mahasiswa_ta($fakultas = null, $prodi = null, $keyword = null)
    {
        $this->db->select('akun.*, 
                           tagihan_ta.id as ta_tagihan_id, 
                           tagihan_ta.status as ta_tagihan_status, 
                           tagihan_ta.nominal as ta_tagihan_nominal');
        $this->db->from($this->table_akun . ' as akun');
        $this->db->join($this->table_tagihan . ' as tagihan_ta',
                        "akun.id = tagihan_ta.akun_id AND tagihan_ta.jenis_tagihan = 'Bimbingan & Ujian Tugas Akhir'",
                        'left');
        $this->db->where('akun.role', 3);
        $this->db->where('akun.deleted_at IS NULL');

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('akun.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('akun.prodi', $prodi);
        }
        if ($keyword !== null && $keyword !== '') {
            $this->db->group_start();
            $this->db->like('akun.nama_lengkap', $keyword);
            $this->db->or_like('akun.nim', $keyword);
            $this->db->or_like('akun.email', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('akun.semester', 'DESC');
        $this->db->order_by('akun.nama_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    // ======================================================
    // MAHASISWA: QUERY TAGIHAN & PEMBAYARAN MILIK AKUN
    // ======================================================

    /**
     * Ambil seluruh tagihan milik akun mahasiswa tertentu.
     * Sesuai Revisi: Jika akses pembayaran semester akhir ditutup,
    * maka seluruh tagihan biaya semester akhir tidak ditampilkan pada daftar tagihan.
     *
     * CATATAN: get_status_akses_ta_mahasiswa() dipanggil SEBELUM db->where() agar
     * Active Record CI3 tidak mewarisi kondisi WHERE yang tertinggal dari sub-query.
     */
    public function get_tagihan_by_akun($akun_id, $ignore_ta_filter = false, $include_optional = false)
    {
        $akun_id = (int)$akun_id;

        // Tentukan status akses TA SEBELUM memanggil query builder apa pun
        $akses_ta = false;
        if (!$ignore_ta_filter) {
            $akses_ta = $this->get_status_akses_ta_mahasiswa($akun_id);
        }
        $ambil_semester_pendek = $this->get_status_semester_pendek($akun_id);

        // Baru bangun kondisi Active Record
        $this->db->where('akun_id', $akun_id);

        if (!$include_optional) {
            $tagihan_semester_akhir = [
                'Bimbingan & Ujian Tugas Akhir',
                'Cetak & Administrasi Dokumen Akademik',
                'Wisuda'
            ];
            $this->db->group_start();
            $this->db->where_not_in('jenis_tagihan', array_merge($tagihan_semester_akhir, ['Semester Pendek']));
            if (!$ignore_ta_filter && $akses_ta) {
                $this->db->or_where_in('jenis_tagihan', $tagihan_semester_akhir);
            }
            if (!$ignore_ta_filter && $ambil_semester_pendek) {
                $this->db->or_where('jenis_tagihan', 'Semester Pendek');
            }
            $this->db->group_end();
        }

        $this->db->order_by('jatuh_tempo', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table_tagihan)->result();
    }

    /**
     * Ambil tagihan yang belum lunas (BELUM_BAYAR atau DITOLAK)
     * untuk pilihan modal konfirmasi pembayaran.
     * Jika akses TA ditutup, biaya semester akhir tidak dimunculkan.
     *
     * CATATAN: get_status_akses_ta_mahasiswa() dipanggil SEBELUM db->where() agar
     * Active Record CI3 tidak mewarisi kondisi WHERE yang tertinggal dari sub-query.
     */
    public function get_tagihan_belum_lunas($akun_id)
    {
        $akun_id = (int)$akun_id;

        // Tentukan status akses TA SEBELUM memanggil query builder apa pun
        $akses_ta = $this->get_status_akses_ta_mahasiswa($akun_id);
        $ambil_semester_pendek = $this->get_status_semester_pendek($akun_id);

        // Baru bangun kondisi Active Record
        $this->db->where('akun_id', $akun_id);
        $this->db->where_in('status', ['BELUM_BAYAR', 'DITOLAK']);

        $tagihan_semester_akhir = [
            'Bimbingan & Ujian Tugas Akhir',
            'Cetak & Administrasi Dokumen Akademik',
            'Wisuda'
        ];
        $this->db->group_start();
        $this->db->where_not_in('jenis_tagihan', array_merge($tagihan_semester_akhir, ['Semester Pendek']));
        if ($akses_ta) {
            $this->db->or_where_in('jenis_tagihan', $tagihan_semester_akhir);
        }
        if ($ambil_semester_pendek) {
            $this->db->or_where('jenis_tagihan', 'Semester Pendek');
        }
        $this->db->group_end();

        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table_tagihan)->result();
    }

    /**
     * Ambil tagihan semester aktif utama (misalnya SPP / UKT)
     */
    public function get_tagihan_aktif($akun_id)
    {
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table_tagihan)->row();
    }

    /**
     * Ambil detail tagihan spesifik berdasarkan ID dan Akun ID
     * Proteksi keamanan anti-IDOR: mahasiswa hanya bisa mengakses tagihannya sendiri
     */
    public function get_tagihan_by_id_and_akun($tagihan_id, $akun_id)
    {
        return $this->db->get_where($this->table_tagihan, [
            'id'      => (int)$tagihan_id,
            'akun_id' => (int)$akun_id
        ])->row();
    }

    /**
     * Ambil seluruh riwayat pembayaran milik mahasiswa beserta detail tagihannya
     */
    public function get_riwayat_pembayaran($akun_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan, 
                           tagihan.jatuh_tempo, 
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('pembayaran.akun_id', (int)$akun_id);
        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Hitung total pengeluaran resmi mahasiswa (hanya status LUNAS)
     * Sesuai revisi: otomatis menghitung total pengeluaran riwayat pembayaran
     */
    public function get_total_pengeluaran_mahasiswa($akun_id)
    {
        $this->db->select_sum('nominal_pembayaran', 'total_pengeluaran');
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->where('status', 'LUNAS');
        $res = $this->db->get($this->table_pembayaran)->row();
        return $res && $res->total_pengeluaran ? (float)$res->total_pengeluaran : 0.0;
    }

    /**
     * Ambil detail satu pembayaran berdasarkan ID dan Akun ID
     */
    public function get_pembayaran_by_id_and_akun($pembayaran_id, $akun_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan, 
                           tagihan.jatuh_tempo');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->where('pembayaran.id', (int)$pembayaran_id);
        $this->db->where('pembayaran.akun_id', (int)$akun_id);
        return $this->db->get()->row();
    }

    /**
     * Simpan record konfirmasi pembayaran baru
     */
    public function insert_pembayaran($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_pembayaran, $data);
        return $this->db->insert_id();
    }

    /**
     * Update pembayaran yang berstatus PENDING oleh mahasiswa (Edit Data / Ganti Bukti)
     */
    public function update_pembayaran_pending($pembayaran_id, $akun_id, $data)
    {
        $this->db->where('id', (int)$pembayaran_id);
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->where('status', 'PENDING');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table_pembayaran, $data);
    }

    /**
     * Batalkan pembayaran PENDING oleh mahasiswa
     */
    public function batalkan_pembayaran($pembayaran_id, $akun_id)
    {
        $pembayaran = $this->db->get_where($this->table_pembayaran, [
            'id'      => (int)$pembayaran_id,
            'akun_id' => (int)$akun_id,
            'status'  => 'PENDING'
        ])->row();

        if (!$pembayaran) {
            return false;
        }

        // Kembalikan status tagihan ke BELUM_BAYAR
        $this->update_status_tagihan($pembayaran->tagihan_id, 'BELUM_BAYAR');

        // Hapus file bukti transfer lama jika ada
        if (!empty($pembayaran->bukti_pembayaran)) {
            $file_path = FCPATH . 'uploads/bukti_pembayaran/' . $pembayaran->bukti_pembayaran;
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }

        // Hapus record pembayaran
        $this->db->where('id', (int)$pembayaran_id);
        return $this->db->delete($this->table_pembayaran);
    }

    /**
     * Update status pada tabel tagihan
     */
    public function update_status_tagihan($tagihan_id, $status)
    {
        $this->db->where('id', (int)$tagihan_id);
        return $this->db->update($this->table_tagihan, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Cek status akses KRS mahasiswa berdasarkan status pembayaran SPP semester aktif
     */
    public function cek_status_krs($akun_id)
    {
        $bulan = (int)date('n');
        $tahun = (int)date('Y');
        $semester_aktif = ($bulan >= 7 && $bulan <= 12) ? 'Ganjil' : 'Genap';
        $tahun_akademik_aktif = $semester_aktif === 'Ganjil'
            ? $tahun . '/' . ($tahun + 1)
            : ($tahun - 1) . '/' . $tahun;

        $this->db->where('akun_id', (int)$akun_id)
                 ->like('jenis_tagihan', 'SPP', 'after')
                 ->where('tahun_akademik', $tahun_akademik_aktif)
                 ->where('semester', $semester_aktif)
                 ->order_by('id', 'DESC');
        $tagihan_spp = $this->db->get($this->table_tagihan)->row();

        if (!$tagihan_spp) {
            return [
                'status'    => 'BELUM_BAYAR',
                'pesan'     => 'KRS belum dapat diakses. Silakan selesaikan pembayaran semester terlebih dahulu.',
                'buka_krs'  => false,
                'tagihan'   => null
            ];
        }

        switch ($tagihan_spp->status) {
            case 'LUNAS':
                return [
                    'status'    => 'LUNAS',
                    'pesan'     => 'Pembayaran telah diverifikasi. Akses KRS terbuka penuh.',
                    'buka_krs'  => true,
                    'tagihan'   => $tagihan_spp
                ];
            case 'PENDING':
                return [
                    'status'    => 'PENDING',
                    'pesan'     => 'KRS belum dapat diakses. Pembayaran sedang menunggu verifikasi Admin Keuangan.',
                    'buka_krs'  => false,
                    'tagihan'   => $tagihan_spp
                ];
            case 'DITOLAK':
                $last_reject = $this->db->where([
                    'tagihan_id' => $tagihan_spp->id,
                    'status'     => 'DITOLAK'
                ])->order_by('id', 'DESC')->get($this->table_pembayaran)->row();

                return [
                    'status'           => 'DITOLAK',
                    'pesan'            => 'KRS belum dapat diakses. Pembayaran ditolak. Silakan perbaiki pembayaran.',
                    'alasan_penolakan' => $last_reject ? $last_reject->alasan_penolakan : 'Bukti pembayaran tidak sesuai atau tidak terbaca.',
                    'buka_krs'         => false,
                    'tagihan'          => $tagihan_spp
                ];
            case 'BELUM_BAYAR':
            default:
                return [
                    'status'    => 'BELUM_BAYAR',
                    'pesan'     => 'KRS belum dapat diakses. Silakan selesaikan pembayaran semester terlebih dahulu.',
                    'buka_krs'  => false,
                    'tagihan'   => $tagihan_spp
                ];
        }
    }

    /**
     * Mengambil status realtime mahasiswa untuk polling AJAX desktop
     */
    public function get_status_realtime_mahasiswa($akun_id)
    {
        $status_krs = $this->cek_status_krs($akun_id);

        $tagihan = $this->get_tagihan_by_akun($akun_id);

        $count_pending = 0;
        $count_lunas   = 0;
        $count_belum   = 0;
        $hash_string   = '';

        foreach ($tagihan as $t) {
            if ($t->status === 'LUNAS') $count_lunas++;
            elseif ($t->status === 'PENDING') $count_pending++;
            else $count_belum++;
            $hash_string .= "{$t->id}:{$t->status}:{$t->updated_at}|";
        }

        $this->db->where('akun_id', (int)$akun_id);
        $this->db->order_by('updated_at', 'DESC');
        $last_bayar = $this->db->get($this->table_pembayaran)->row();

        if ($last_bayar) {
            $hash_string .= "pay:{$last_bayar->id}:{$last_bayar->status}:{$last_bayar->updated_at}";
        }

        return [
            'status_krs'         => $status_krs['status'],
            'pesan_krs'          => $status_krs['pesan'],
            'buka_krs'           => $status_krs['buka_krs'],
            'count_pending'      => $count_pending,
            'count_lunas'        => $count_lunas,
            'count_belum'        => $count_belum,
            'last_status_bayar'  => $last_bayar ? $last_bayar->status : null,
            'alasan_penolakan'   => ($last_bayar && $last_bayar->status === 'DITOLAK') ? $last_bayar->alasan_penolakan : null,
            'hash'               => md5($hash_string),
            'timestamp'          => date('Y-m-d H:i:s')
        ];
    }

    // ======================================================
    // METHODS KHUSUS ADMIN DAN SUPERADMIN
    // ======================================================

    /**
     * Hitung jumlah pembayaran berdasarkan status
     */
    public function count_pembayaran_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results($this->table_pembayaran);
    }

    /**
     * Hitung jumlah mahasiswa aktif (role = 3, deleted_at IS NULL)
     */
    public function count_mahasiswa_aktif()
    {
        return $this->db->where('role', 3)
                        ->where('deleted_at IS NULL')
                        ->count_all_results($this->table_akun);
    }

    /**
     * Ambil daftar unik Fakultas dan Program Studi yang ada di sistem
     */
    public function get_daftar_fakultas_prodi()
    {
        $fakultas = $this->db->distinct()
                             ->select('fakultas')
                             ->where('role', 3)
                             ->where('deleted_at IS NULL')
                             ->where('fakultas IS NOT NULL')
                             ->get($this->table_akun)
                             ->result_array();

        $prodi = $this->db->distinct()
                          ->select('prodi, fakultas')
                          ->where('role', 3)
                          ->where('deleted_at IS NULL')
                          ->where('prodi IS NOT NULL')
                          ->get($this->table_akun)
                          ->result_array();

        return [
            'fakultas' => array_filter(array_column($fakultas, 'fakultas')),
            'prodi'    => array_filter(array_column($prodi, 'prodi'))
        ];
    }

    /**
     * Ambil semua data pembayaran berdasarkan status, fakultas, dan prodi
     */
    public function get_semua_pembayaran($status = null, $fakultas = null, $prodi = null)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan,
                           tagihan.jatuh_tempo,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           mhs.semester as semester_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');

        if ($status !== null && $status !== '') {
            $this->db->where('pembayaran.status', $status);
        }
        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil riwayat pembayaran yang sudah diverifikasi (LUNAS atau DITOLAK)
     */
    public function get_semua_pembayaran_diverifikasi($fakultas = null, $prodi = null)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan,
                           tagihan.jatuh_tempo,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where_in('pembayaran.status', ['LUNAS', 'DITOLAK']);

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->order_by('pembayaran.updated_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil detail satu pembayaran untuk modal admin
     */
    public function get_detail_pembayaran_admin($pembayaran_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan,
                           tagihan.jatuh_tempo,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.email as email_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           mhs.semester as semester_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('pembayaran.id', (int)$pembayaran_id);
        return $this->db->get()->row();
    }

    /**
     * Daftar mahasiswa yang memiliki tagihan dengan filter fakultas dan prodi
     */
    public function get_mahasiswa_belum_lunas($fakultas = null, $prodi = null)
    {
        $this->db->select('akun.id, akun.nim, akun.nama_lengkap, akun.email, 
                           akun.fakultas, akun.prodi, akun.semester as semester_mhs, akun.akses_ta,
                           COUNT(tagihan.id) as total_tagihan,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN 1 ELSE 0 END) as tagihan_lunas,
                           SUM(CASE WHEN tagihan.status = "PENDING" THEN 1 ELSE 0 END) as tagihan_pending,
                           SUM(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN 1 ELSE 0 END) as tagihan_belum,
                           SUM(tagihan.nominal) as total_nominal,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN tagihan.nominal ELSE 0 END) as nominal_lunas');
        $this->db->from($this->table_akun . ' as akun');
        $this->db->join($this->table_tagihan . ' as tagihan', 'akun.id = tagihan.akun_id', 'left');
        $this->db->where('akun.role', 3);
        $this->db->where('akun.deleted_at IS NULL');

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('akun.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('akun.prodi', $prodi);
        }

        $this->db->group_by('akun.id');
        $this->db->order_by('tagihan_belum', 'DESC');
        $this->db->order_by('akun.prodi', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Rekap laporan keuangan berdasarkan tahun akademik & semester
     */
    public function get_rekap_laporan($tahun_akademik, $semester, $fakultas = null, $prodi = null)
    {
        $this->db->select('tagihan.jenis_tagihan,
                           COUNT(tagihan.id) as jumlah_tagihan,
                           SUM(tagihan.nominal) as total_nominal,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN tagihan.nominal ELSE 0 END) as nominal_lunas,
                           SUM(CASE WHEN tagihan.status = "PENDING" THEN tagihan.nominal ELSE 0 END) as nominal_pending,
                           SUM(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN tagihan.nominal ELSE 0 END) as nominal_belum,
                           COUNT(CASE WHEN tagihan.status = "LUNAS" THEN 1 END) as jumlah_lunas,
                           COUNT(CASE WHEN tagihan.status = "PENDING" THEN 1 END) as jumlah_pending,
                           COUNT(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN 1 END) as jumlah_belum');
        $this->db->from($this->table_tagihan . ' as tagihan');
        $this->db->join($this->table_akun . ' as mhs', 'tagihan.akun_id = mhs.id', 'inner');
        $this->db->where('tagihan.tahun_akademik', $tahun_akademik);
        $this->db->where('tagihan.semester', $semester);

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->group_by('tagihan.jenis_tagihan');
        return $this->db->get()->result();
    }

    /**
     * Ambil semua pembayaran untuk laporan rektorat (dengan filter)
     */
    public function get_semua_pembayaran_laporan($tahun_akademik, $semester, $fakultas = null, $prodi = null)
    {
        $this->db->select('pembayaran.*,
                           tagihan.jenis_tagihan,
                           tagihan.tahun_akademik,
                           tagihan.semester,
                           tagihan.nominal as nominal_tagihan,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran . ' as pembayaran');
        $this->db->join($this->table_tagihan . ' as tagihan', 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('tagihan.tahun_akademik', $tahun_akademik);
        $this->db->where('tagihan.semester', $semester);

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil daftar tahun akademik yang tersedia di database
     */
    public function get_daftar_tahun_akademik()
    {
        $res = $this->db->distinct()
                        ->select('tahun_akademik, semester')
                        ->order_by('tahun_akademik', 'DESC')
                        ->get($this->table_tagihan)
                        ->result();
        if (empty($res)) {
            return [(object)['tahun_akademik' => '2026/2027', 'semester' => 'Ganjil']];
        }
        return $res;
    }

    /**
     * Informasi komponen tarif biaya satu semester sebagai panduan dan transparansi mahasiswa
     */
    public function get_informasi_komponen_semester()
    {
        return [
            [
                'no'          => 1,
                'kategori'    => 'Kewajiban Pokok',
                'komponen'    => 'SPP / UKT',
                'nominal'     => 3500000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Biaya kuliah semester pokok, bimbingan akademik dosen wali, dan perkuliahan reguler. Syarat mutlak pembukaan akses KRS.',
                'syarat_krs'  => true
            ],
            [
                'no'          => 2,
                'kategori'    => 'Akademik & Lab',
                'komponen'    => 'Praktikum',
                'nominal'     => 500000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Praktikum dan penggunaan laboratorium komputer/sains, modul praktikum, dan lisensi perangkat lunak praktikum.',
                'syarat_krs'  => false
            ],
            [
                'no'          => 3,
                'kategori'    => 'Sarana Kampus',
                'komponen'    => 'Fasilitas Akademik',
                'nominal'     => 300000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Penunjang kegiatan akademik, akses perpustakaan digital, sarana e-learning, dan fasilitas pendukung kampus.',
                'syarat_krs'  => false
            ],
            [
                'no'          => 4,
                'kategori'    => 'Layanan Kampus',
                'komponen'    => 'Sistem Informasi & Administrasi',
                'nominal'     => 200000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Layanan akademik, portal Smart Campus, administrasi kesiswaan, dan pemeliharaan server perkuliahan.',
                'syarat_krs'  => false
            ]
        ];
    }

    /**
     * Rincian Biaya Tambahan (Di Luar SPP/UKT Semester Reguler)
     */
    public function get_informasi_biaya_tambahan()
    {
        return [
            [
                'no'          => 1,
                'jenis_biaya' => 'Bimbingan & Ujian Tugas Akhir',
                'nominal'     => 1250000,
                'keterangan'  => 'Honorarium bimbingan intensif laporan tugas akhir/skripsi dan administrasi pelaksanaan sidang.',
                'peruntukan'  => 'Mahasiswa Semester Akhir (Sesuai Izin Admin)'
            ],
            [
                'no'          => 2,
                'jenis_biaya' => 'Cetak & Administrasi Dokumen Akademik',
                'nominal'     => 100000,
                'keterangan'  => 'Pencetakan transkrip nilai resmi, legalisir ijazah, dan administrasi berkas kelulusan.',
                'peruntukan'  => 'Sesuai Kebutuhan'
            ],
            [
                'no'          => 3,
                'jenis_biaya' => 'Wisuda',
                'nominal'     => 1500000,
                'keterangan'  => 'Prosesi wisuda sarjana/diploma, toga wisuda, ijazah digital & cetak berhologram, serta dokumentasi.',
                'peruntukan'  => 'Calon Wisudawan'
            ],
            [
                'no'          => 4,
                'jenis_biaya' => 'Semester Pendek',
                'nominal'     => 750000,
                'keterangan'  => 'Biaya pengambilan mata kuliah pada semester pendek sesuai ketentuan akademik kampus.',
                'peruntukan'  => 'Mahasiswa yang Mengambil Semester Pendek'
            ]
        ];
    }
}
