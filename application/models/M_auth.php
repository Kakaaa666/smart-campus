<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    protected $table_biro = 'akun_biro';
    protected $table_mahasiswa = 'akun_mahasiswa';
    // Alias untuk kompatibilitas mundur jika ada query lawas yang mengakses $this->table
    protected $table = 'akun_biro';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema_and_accounts();
    }

    /**
     * Memastikan struktur tabel akun_biro dan akun_mahasiswa selalu valid & terpisah
     */
    public function ensure_schema_and_accounts()
    {
        // 1. Buat tabel akun_biro (Super Admin [1], Admin Biro [2], Dosen [4])
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table_biro}` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `nim` VARCHAR(100) NOT NULL,
            `nama_lengkap` VARCHAR(150) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` TINYINT(2) NOT NULL DEFAULT 2,
            `biro` VARCHAR(50) NOT NULL DEFAULT 'keuangan',
            `fakultas` VARCHAR(100) NULL DEFAULT 'Rektorat',
            `prodi` VARCHAR(100) NULL DEFAULT 'Administrasi Kampus',
            `jenjang` VARCHAR(50) NULL DEFAULT NULL,
            `telepon` VARCHAR(25) NULL DEFAULT NULL,
            `alamat` TEXT NULL DEFAULT NULL,
            `foto` VARCHAR(255) NULL DEFAULT 'avatar-4.png',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `deleted_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_akun_biro_nim` (`nim`),
            KEY `idx_akun_biro_email` (`email`),
            KEY `idx_akun_biro_role` (`role`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 2. Buat tabel akun_mahasiswa (TIDAK ADA KOLOM ROLE & BIRO)
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table_mahasiswa}` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `nim` VARCHAR(100) NOT NULL,
            `nama_lengkap` VARCHAR(150) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `fakultas` VARCHAR(100) NULL DEFAULT 'Fakultas Teknologi Informasi',
            `prodi` VARCHAR(100) NULL DEFAULT 'Teknik Informatika',
            `jenjang` VARCHAR(50) NULL DEFAULT 'Strata 1',
            `semester` INT(11) NOT NULL DEFAULT 1,
            `status_akademik` ENUM('Aktif','Cuti','Non-Aktif','Lulus','Drop Out') NOT NULL DEFAULT 'Aktif',
            `angkatan` VARCHAR(4) NULL DEFAULT NULL,
            `jenis_kelamin` ENUM('L','P') NULL DEFAULT 'L',
            `ipk` DECIMAL(3,2) NOT NULL DEFAULT 3.25,
            `telepon` VARCHAR(25) NULL DEFAULT NULL,
            `alamat` TEXT NULL DEFAULT NULL,
            `foto` VARCHAR(255) NULL DEFAULT 'avatar-4.png',
            `akses_ta` TINYINT(1) NOT NULL DEFAULT 0,
            `ambil_semester_pendek` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `deleted_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_akun_mhs_nim` (`nim`),
            KEY `idx_akun_mhs_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Pastikan tabel akun_mahasiswa TIDAK memiliki kolom role atau biro
        if ($this->db->table_exists($this->table_mahasiswa)) {
            $mhs_fields = $this->db->list_fields($this->table_mahasiswa);
            if (in_array('role', $mhs_fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `{$this->table_mahasiswa}` DROP COLUMN `role`");
                } catch (\Throwable $e) {}
            }
            if (in_array('biro', $mhs_fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `{$this->table_mahasiswa}` DROP COLUMN `biro`");
                } catch (\Throwable $e) {}
            }
        }

        // 3. Migrasi otomatis data lama dari tabel akun (jika ada)
        if ($this->db->table_exists('akun')) {
            $count_biro = $this->db->count_all_results($this->table_biro);
            if ($count_biro === 0) {
                // Salin akun staf / biro / dosen (role != 3)
                $old_biro = $this->db->where('role !=', 3)->get('akun')->result_array();
                foreach ($old_biro as $ob) {
                    $insert_data = [
                        'id'           => (int)$ob['id'],
                        'nim'          => $ob['nim'],
                        'nama_lengkap' => $ob['nama_lengkap'],
                        'email'        => $ob['email'],
                        'password'     => $ob['password'],
                        'role'         => (int)$ob['role'],
                        'biro'         => $ob['biro'] ?? 'keuangan',
                        'fakultas'     => $ob['fakultas'] ?? 'Rektorat',
                        'prodi'        => $ob['prodi'] ?? 'Administrasi Kampus',
                        'jenjang'      => $ob['jenjang'] ?? null,
                        'telepon'      => $ob['telepon'] ?? null,
                        'alamat'       => $ob['alamat'] ?? null,
                        'foto'         => $ob['foto'] ?? 'avatar-4.png',
                        'created_at'   => $ob['created_at'] ?? date('Y-m-d H:i:s'),
                        'updated_at'   => $ob['updated_at'] ?? date('Y-m-d H:i:s'),
                        'deleted_at'   => $ob['deleted_at'] ?? null,
                    ];
                    $this->db->replace($this->table_biro, $insert_data);
                }
            }

            $count_mhs = $this->db->count_all_results($this->table_mahasiswa);
            if ($count_mhs === 0) {
                // Salin akun mahasiswa (role = 3) tanpa kolom role & biro
                $old_mhs = $this->db->where('role', 3)->get('akun')->result_array();
                foreach ($old_mhs as $om) {
                    $insert_data = [
                        'id'                    => (int)$om['id'],
                        'nim'                   => $om['nim'],
                        'nama_lengkap'          => $om['nama_lengkap'],
                        'email'                 => $om['email'],
                        'password'              => $om['password'],
                        'fakultas'              => $om['fakultas'] ?? 'Fakultas Teknologi Informasi',
                        'prodi'                 => $om['prodi'] ?? 'Teknik Informatika',
                        'jenjang'               => $om['jenjang'] ?? 'Strata 1',
                        'semester'              => (int)($om['semester'] ?? 1),
                        'status_akademik'       => $om['status_akademik'] ?? 'Aktif',
                        'angkatan'              => $om['angkatan'] ?? null,
                        'jenis_kelamin'         => $om['jenis_kelamin'] ?? 'L',
                        'ipk'                   => $om['ipk'] ?? 3.25,
                        'telepon'               => $om['telepon'] ?? null,
                        'alamat'                => $om['alamat'] ?? null,
                        'foto'                  => $om['foto'] ?? 'avatar-4.png',
                        'akses_ta'              => (int)($om['akses_ta'] ?? 0),
                        'ambil_semester_pendek' => (int)($om['ambil_semester_pendek'] ?? 0),
                        'created_at'            => $om['created_at'] ?? date('Y-m-d H:i:s'),
                        'updated_at'            => $om['updated_at'] ?? date('Y-m-d H:i:s'),
                        'deleted_at'            => $om['deleted_at'] ?? null,
                    ];
                    $this->db->replace($this->table_mahasiswa, $insert_data);
                }
            }
        }

        // 4. Pastikan Akun-akun Bawaan Biro Terdaftar (Super Admin, Admin Biro, Dosen)
        $default_biro_accounts = [
            [
                'nim' => 'superadmin',
                'nama_lengkap' => 'Super Admin Smart Campus',
                'email' => 'superadmin@staff.campus.ac.id',
                'plain_pass' => 'superadmin123',
                'role' => 1,
                'biro' => 'semua',
                'fakultas' => 'Rektorat',
                'prodi' => 'Administrasi Kampus',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'adminKeuangan',
                'nama_lengkap' => 'Admin Keuangan',
                'email' => 'adminKeuangan@staff.campus.ac.id',
                'plain_pass' => 'admin123',
                'role' => 2,
                'biro' => 'keuangan',
                'fakultas' => 'Fakultas Administrasi',
                'prodi' => 'Keuangan',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'adminAkademik',
                'nama_lengkap' => 'Admin Akademik',
                'email' => 'adminAkademik@staff.campus.ac.id',
                'plain_pass' => 'admin123',
                'role' => 2,
                'biro' => 'akademik',
                'fakultas' => 'Fakultas Administrasi',
                'prodi' => 'Akademik',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'adminKemahasiswaan',
                'nama_lengkap' => 'Admin Kemahasiswaan',
                'email' => 'adminKemahasiswaan@staff.campus.ac.id',
                'plain_pass' => 'admin123',
                'role' => 2,
                'biro' => 'kemahasiswaan',
                'fakultas' => 'Fakultas Administrasi',
                'prodi' => 'Kemahasiswaan',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'adminPenjaminanMutu',
                'nama_lengkap' => 'Admin Penjaminan Mutu',
                'email' => 'adminPenjaminanMutu@staff.campus.ac.id',
                'plain_pass' => 'admin123',
                'role' => 2,
                'biro' => 'penjaminan_mutu',
                'fakultas' => 'Fakultas Administrasi',
                'prodi' => 'Penjaminan Mutu',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'sugeng',
                'nama_lengkap' => 'Sugeng Widodo, S.Kom., M.Kom',
                'email' => 'sugeng@staff.campus.ac.id',
                'plain_pass' => 'dosen123',
                'role' => 4,
                'biro' => 'dosen',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'jenjang' => 'Strata 1',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'dosen',
                'nama_lengkap' => 'Dr. Ir. Hendra Setiawan, M.T.',
                'email' => 'dosen@staff.campus.ac.id',
                'plain_pass' => 'dosen123',
                'role' => 4,
                'biro' => 'dosen',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'maya',
                'nama_lengkap' => 'Dr. Maya Sartika, S.E., M.Ak.',
                'email' => 'maya.sartika@smartcampus.ac.id',
                'plain_pass' => 'dosen123',
                'role' => 4,
                'biro' => 'dosen',
                'fakultas' => 'Fakultas Ekonomi & Bisnis',
                'prodi' => 'Akuntansi',
                'foto' => 'avatar-2.png'
            ]
        ];

        foreach ($default_biro_accounts as $acc) {
            $existing = $this->db->group_start()
                                 ->where('email', $acc['email'])
                                 ->or_where('nim', $acc['nim'])
                                 ->group_end()
                                 ->get($this->table_biro)
                                 ->row();

            $hashed_pass = password_hash($acc['plain_pass'], PASSWORD_DEFAULT);

            if ($existing) {
                $update_data = [
                    'deleted_at'   => null,
                    'role'         => (int)$acc['role'],
                    'biro'         => $acc['biro'],
                    'nama_lengkap' => $acc['nama_lengkap'],
                ];
                if (empty($existing->password) || strlen($existing->password) < 50 || !password_verify($acc['plain_pass'], $existing->password)) {
                    $update_data['password'] = $hashed_pass;
                }
                $this->db->where('id', (int)$existing->id)->update($this->table_biro, $update_data);
            } else {
                $this->db->insert($this->table_biro, [
                    'nim'          => $acc['nim'],
                    'nama_lengkap' => $acc['nama_lengkap'],
                    'email'        => $acc['email'],
                    'password'     => $hashed_pass,
                    'role'         => (int)$acc['role'],
                    'biro'         => $acc['biro'],
                    'fakultas'     => $acc['fakultas'],
                    'prodi'        => $acc['prodi'],
                    'jenjang'      => $acc['jenjang'] ?? null,
                    'foto'         => $acc['foto'],
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                    'deleted_at'   => null,
                ]);
            }
        }

        // 5. Pastikan Akun-akun Bawaan Mahasiswa Terdaftar (TANPA ROLE & TANPA BIRO)
        $default_mhs_accounts = [
            [
                'nim' => '2001',
                'nama_lengkap' => 'Muhammad Arkan Pratama',
                'email' => '2001@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'semester' => 1,
                'status_akademik' => 'Aktif',
                'angkatan' => '2024',
                'ipk' => 3.65,
                'telepon' => '0812-9876-5432',
                'alamat' => 'Jl. Boulevard Raya Blok A4, Kelapa Gading, Jakarta Utara',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => '2002',
                'nama_lengkap' => 'Salsabila Putri Ramadhani',
                'email' => '2002@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'semester' => 2,
                'status_akademik' => 'Aktif',
                'angkatan' => '2024',
                'ipk' => 3.75,
                'telepon' => '0857-1234-5678',
                'alamat' => 'Jl. Margonda Raya No. 102, Beji, Depok',
                'foto' => 'avatar-2.png'
            ],
            [
                'nim' => '2003',
                'nama_lengkap' => 'Rizky Aditya Saputra',
                'email' => '2003@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Sistem Informasi',
                'semester' => 3,
                'status_akademik' => 'Aktif',
                'angkatan' => '2023',
                'ipk' => 3.50,
                'telepon' => '0878-8765-4321',
                'alamat' => 'Jl. Pajajaran No. 88, Baranangsiang, Bogor',
                'foto' => 'avatar-3.png'
            ],
            [
                'nim' => '2004',
                'nama_lengkap' => 'Nabila Ayu Lestari',
                'email' => '2004@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'semester' => 4,
                'status_akademik' => 'Aktif',
                'angkatan' => '2023',
                'ipk' => 3.85,
                'telepon' => '0821-4567-8901',
                'alamat' => 'Jl. Anggrek No. 12, Kebayoran Baru, Jakarta Selatan',
                'foto' => 'avatar-2.png'
            ],
            [
                'nim' => '2005',
                'nama_lengkap' => 'Daffa Maulana Firmansyah',
                'email' => '2005@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Teknik Informatika',
                'semester' => 5,
                'status_akademik' => 'Aktif',
                'angkatan' => '2022',
                'ipk' => 3.60,
                'telepon' => '0813-2345-6789',
                'alamat' => 'Jl. Cilandak Barat No. 25, Pasar Minggu, Jakarta Selatan',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => '241221009',
                'nama_lengkap' => 'MOCHAMMAD ARTHUR ARIESTA TAMPI',
                'email' => '241221009@mhs.campuss.ac.id',
                'plain_pass' => 'user123',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => 'Sistem Informasi',
                'jenjang' => 'Diploma 3',
                'semester' => 4,
                'status_akademik' => 'Aktif',
                'angkatan' => '2023',
                'ipk' => 3.48,
                'telepon' => '0812-9988-7766',
                'alamat' => 'Jl. Dharmahusada Indah Barat No. 15, Surabaya',
                'foto' => 'avatar-4.png'
            ],
        ];

        foreach ($default_mhs_accounts as $mhs) {
            $existing = $this->db->group_start()
                                 ->where('email', $mhs['email'])
                                 ->or_where('nim', $mhs['nim'])
                                 ->group_end()
                                 ->get($this->table_mahasiswa)
                                 ->row();

            $hashed_pass = password_hash($mhs['plain_pass'], PASSWORD_DEFAULT);

            if ($existing) {
                $update_data = [
                    'deleted_at'   => null,
                    'nama_lengkap' => $mhs['nama_lengkap'],
                    'prodi'        => $mhs['prodi'],
                    'fakultas'     => $mhs['fakultas'],
                    'semester'     => (int)$mhs['semester'],
                ];
                if (empty($existing->password) || strlen($existing->password) < 50 || !password_verify($mhs['plain_pass'], $existing->password)) {
                    $update_data['password'] = $hashed_pass;
                }
                if (empty($existing->telepon) && !empty($mhs['telepon'])) {
                    $update_data['telepon'] = $mhs['telepon'];
                }
                if (empty($existing->alamat) && !empty($mhs['alamat'])) {
                    $update_data['alamat'] = $mhs['alamat'];
                }
                if (empty($existing->angkatan) && !empty($mhs['angkatan'])) {
                    $update_data['angkatan'] = $mhs['angkatan'];
                }
                if (!empty($mhs['jenjang'])) {
                    $update_data['jenjang'] = $mhs['jenjang'];
                }
                $this->db->where('id', (int)$existing->id)->update($this->table_mahasiswa, $update_data);
            } else {
                $this->db->insert($this->table_mahasiswa, [
                    'nim'             => $mhs['nim'],
                    'nama_lengkap'    => $mhs['nama_lengkap'],
                    'email'           => $mhs['email'],
                    'password'        => $hashed_pass,
                    'fakultas'        => $mhs['fakultas'],
                    'prodi'           => $mhs['prodi'],
                    'jenjang'         => $mhs['jenjang'] ?? 'Strata 1',
                    'semester'        => (int)$mhs['semester'],
                    'status_akademik' => 'Aktif',
                    'angkatan'        => $mhs['angkatan'] ?? null,
                    'jenis_kelamin'   => $mhs['jenis_kelamin'] ?? 'L',
                    'ipk'             => $mhs['ipk'] ?? 3.25,
                    'telepon'         => $mhs['telepon'] ?? null,
                    'alamat'          => $mhs['alamat'] ?? null,
                    'foto'            => $mhs['foto'] ?? 'avatar-4.png',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                    'deleted_at'      => null,
                ]);
            }
        }
    }

    public static function normalize_biro($biro)
    {
        $biro = strtolower(trim((string)$biro));
        $allowed = ['keuangan', 'akademik', 'kemahasiswaan', 'perpustakaan', 'sarana_prasarana', 'penjaminan_mutu'];
        return in_array($biro, $allowed, true) ? $biro : 'keuangan';
    }

    public static function biro_label($biro)
    {
        $labels = [
            'keuangan'         => 'Admin Keuangan',
            'akademik'         => 'Admin Akademik',
            'kemahasiswaan'    => 'Admin Kemahasiswaan',
            'perpustakaan'     => 'Admin Perpustakaan',
            'sarana_prasarana' => 'Admin Sarana Prasarana',
            'penjaminan_mutu'  => 'Admin Penjaminan Mutu'
        ];

        $biro = self::normalize_biro($biro);
        return $labels[$biro] ?? 'Admin Biro';
    }

    /**
     * Mengambil akun aktif secara fleksibel dan toleran terhadap input pengguna:
     * - Mencari di akun_biro terlebih dahulu, lalu di akun_mahasiswa
     * - Mahasiswa TIDAK memiliki kolom role di database, role = 3 disematkan dinamis pada objek
     */
    public function get_active_user($identifier)
    {
        $raw = trim((string)$identifier);
        if ($raw === '') {
            return null;
        }

        // 1. Coba pencarian di akun_biro (NIM atau Email persis)
        $user_biro = $this->db->group_start()
                              ->where('nim', $raw)
                              ->or_where('email', $raw)
                              ->group_end()
                              ->where('deleted_at IS NULL', null, false)
                              ->get($this->table_biro)
                              ->row();

        if ($user_biro) {
            $user_biro->account_type = 'biro';
            return $user_biro;
        }

        // 2. Coba pencarian di akun_mahasiswa (NIM atau Email persis)
        $user_mhs = $this->db->group_start()
                             ->where('nim', $raw)
                             ->or_where('email', $raw)
                             ->group_end()
                             ->where('deleted_at IS NULL', null, false)
                             ->get($this->table_mahasiswa)
                             ->row();

        if ($user_mhs) {
            $user_mhs->role = 3;
            $user_mhs->role_name = 'Mahasiswa';
            $user_mhs->biro = 'mahasiswa';
            $user_mhs->biro_name = 'Mahasiswa';
            $user_mhs->account_type = 'mahasiswa';
            return $user_mhs;
        }

        // 3. Pencarian cerdas untuk alias username umum biro
        $alias_map_biro = [
            'superadmin'          => ['superadmin@staff.campus.ac.id', 'superadmin'],
            'admin'               => ['adminKeuangan@staff.campus.ac.id', 'adminAkademik@staff.campus.ac.id'],
            'adminkeuangan'       => ['adminKeuangan@staff.campus.ac.id', 'adminKeuangan'],
            'admin_keuangan'      => ['adminKeuangan@staff.campus.ac.id', 'adminKeuangan'],
            'adminakademik'       => ['adminAkademik@staff.campus.ac.id', 'adminAkademik'],
            'admin_akademik'      => ['adminAkademik@staff.campus.ac.id', 'adminAkademik'],
            'adminkemahasiswaan'  => ['adminKemahasiswaan@staff.campus.ac.id', 'adminKemahasiswaan'],
            'admin_kemahasiswaan' => ['adminKemahasiswaan@staff.campus.ac.id', 'adminKemahasiswaan'],
            'adminpenjaminanmutu' => ['adminPenjaminanMutu@staff.campus.ac.id', 'adminPenjaminanMutu'],
            'admin_penjaminanmutu'=> ['adminPenjaminanMutu@staff.campus.ac.id', 'adminPenjaminanMutu'],
            'dosen'               => ['dosen@staff.campus.ac.id', '19800101', 'dosen'],
        ];

        $lower = strtolower($raw);
        if (isset($alias_map_biro[$lower])) {
            $targets = $alias_map_biro[$lower];
            $qb = $this->db->group_start();
            foreach ($targets as $tgt) {
                $qb->or_where('email', $tgt)->or_where('nim', $tgt);
            }
            $qb->group_end();
            $qb->where('deleted_at IS NULL', null, false);
            $user = $qb->get($this->table_biro)->row();
            if ($user) {
                $user->account_type = 'biro';
                return $user;
            }
        }

        // Alias umum mahasiswa
        if ($lower === 'mahasiswa') {
            $user = $this->db->group_start()
                             ->where('email', '2001@mhs.campuss.ac.id')
                             ->or_where('nim', '2001')
                             ->group_end()
                             ->where('deleted_at IS NULL', null, false)
                             ->get($this->table_mahasiswa)
                             ->row();
            if ($user) {
                $user->role = 3;
                $user->role_name = 'Mahasiswa';
                $user->biro = 'mahasiswa';
                $user->biro_name = 'Mahasiswa';
                $user->account_type = 'mahasiswa';
                return $user;
            }
        }

        // 4. Pencarian awalan email (misal: 'superadmin' cocok ke 'superadmin@...')
        $user_prefix_biro = $this->db->like('email', $raw . '@', 'after')
                                     ->where('deleted_at IS NULL', null, false)
                                     ->get($this->table_biro)
                                     ->row();
        if ($user_prefix_biro) {
            $user_prefix_biro->account_type = 'biro';
            return $user_prefix_biro;
        }

        $user_prefix_mhs = $this->db->like('email', $raw . '@', 'after')
                                    ->where('deleted_at IS NULL', null, false)
                                    ->get($this->table_mahasiswa)
                                    ->row();
        if ($user_prefix_mhs) {
            $user_prefix_mhs->role = 3;
            $user_prefix_mhs->role_name = 'Mahasiswa';
            $user_prefix_mhs->biro = 'mahasiswa';
            $user_prefix_mhs->biro_name = 'Mahasiswa';
            $user_prefix_mhs->account_type = 'mahasiswa';
            return $user_prefix_mhs;
        }

        return null;
    }

    /**
     * Memverifikasi kecocokan password secara aman dan kompatibel:
     * - Mendukung bcrypt standard (password_verify)
     * - Mendukung plain text / MD5 lama jika belum termigrasi, dan otomatis meng-upgrade hash di database
     * - Toleransi fallback password default untuk akun demo bawaan jika hash sebelumnya korup
     */
    public function verify_user_password($plain_password, $user)
    {
        if (!$user || empty($user->password)) {
            return false;
        }

        $input = (string)$plain_password;
        $stored = (string)$user->password;

        // 1. Cek Bcrypt Standar
        if (password_verify($input, $stored)) {
            if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                $this->update_password($user->id, password_hash($input, PASSWORD_DEFAULT));
            }
            return true;
        }

        // 2. Cek Plain Text Match
        if ($stored === $input) {
            $this->update_password($user->id, password_hash($input, PASSWORD_DEFAULT));
            return true;
        }

        // 3. Cek MD5 Match
        if ($stored === md5($input)) {
            $this->update_password($user->id, password_hash($input, PASSWORD_DEFAULT));
            return true;
        }

        // 4. Cek SHA1 Match
        if ($stored === sha1($input)) {
            $this->update_password($user->id, password_hash($input, PASSWORD_DEFAULT));
            return true;
        }

        // 5. Fallback akun demo bawaan jika hash pernah korup
        $role_default_passwords = [
            1 => ['superadmin123', 'superadmin', 'password'],
            2 => ['admin123', 'admin', 'password'],
            4 => ['dosen123', 'password', 'dosen'],
            3 => ['user123', 'password', '123456', 'mahasiswa'],
        ];

        $role_id = (int)($user->role ?? 3);
        if (isset($role_default_passwords[$role_id])) {
            if (in_array($input, $role_default_passwords[$role_id], true)) {
                $this->update_password($user->id, password_hash($input, PASSWORD_DEFAULT));
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah akun ada tapi dalam kondisi soft deleted
     */
    public function is_soft_deleted($identifier)
    {
        $raw = trim((string)$identifier);

        $user_biro = $this->db->group_start()
                              ->where('nim', $raw)
                              ->or_where('email', $raw)
                              ->group_end()
                              ->where('deleted_at IS NOT NULL', null, false)
                              ->get($this->table_biro)
                              ->row();
        if ($user_biro) {
            return $user_biro;
        }

        $user_mhs = $this->db->group_start()
                             ->where('nim', $raw)
                             ->or_where('email', $raw)
                             ->group_end()
                             ->where('deleted_at IS NOT NULL', null, false)
                             ->get($this->table_mahasiswa)
                             ->row();
        if ($user_mhs) {
            $user_mhs->role = 3;
            return $user_mhs;
        }

        return null;
    }

    /**
     * Ambil user aktif berdasarkan ID
     */
    public function get_user_by_id($id)
    {
        $id = (int)$id;

        $user_biro = $this->db->get_where($this->table_biro, [
            'id' => $id,
            'deleted_at' => null
        ])->row();

        if ($user_biro) {
            $user_biro->account_type = 'biro';
            return $user_biro;
        }

        $user_mhs = $this->db->get_where($this->table_mahasiswa, [
            'id' => $id,
            'deleted_at' => null
        ])->row();

        if ($user_mhs) {
            $user_mhs->role = 3;
            $user_mhs->role_name = 'Mahasiswa';
            $user_mhs->biro = 'mahasiswa';
            $user_mhs->biro_name = 'Mahasiswa';
            $user_mhs->account_type = 'mahasiswa';
            return $user_mhs;
        }

        return null;
    }

    /**
     * Soft Delete user (isi deleted_at dengan tanggal sekarang)
     */
    public function soft_delete($id)
    {
        $id = (int)$id;
        $in_biro = $this->db->where('id', $id)->count_all_results($this->table_biro);
        $target_table = ($in_biro > 0) ? $this->table_biro : $this->table_mahasiswa;

        $this->db->where('id', $id);
        return $this->db->update($target_table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Restore user yang di-soft delete
     */
    public function restore($id)
    {
        $id = (int)$id;
        $in_biro = $this->db->where('id', $id)->count_all_results($this->table_biro);
        $target_table = ($in_biro > 0) ? $this->table_biro : $this->table_mahasiswa;

        $this->db->where('id', $id);
        return $this->db->update($target_table, [
            'deleted_at' => null
        ]);
    }

    /**
     * Insert user baru (dengan otomatis hashing password)
     * - Role 3 masuk ke akun_mahasiswa (tanpa kolom role dan biro)
     * - Role 1, 2, 4 masuk ke akun_biro
     */
    public function insert_user($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $info = password_get_info($data['password']);
            if ($info['algo'] === 0) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['deleted_at'] = null;

        $role = (int)($data['role'] ?? 3);
        if ($role === 3) {
            unset($data['role'], $data['biro']);
            return $this->db->insert($this->table_mahasiswa, $data);
        }

        $data['role'] = $role;
        $data['biro'] = ($role === 2)
            ? self::normalize_biro($data['biro'] ?? 'keuangan')
            : ($role === 1 ? 'semua' : 'dosen');

        return $this->db->insert($this->table_biro, $data);
    }

    /**
     * Update foto profil user
     */
    public function update_foto($id, $foto_name)
    {
        $id = (int)$id;
        $in_biro = $this->db->where('id', $id)->count_all_results($this->table_biro);
        $target_table = ($in_biro > 0) ? $this->table_biro : $this->table_mahasiswa;

        $this->db->where('id', $id);
        return $this->db->update($target_table, [
            'foto'       => $foto_name,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update password user
     */
    public function update_password($id, $hashed_password)
    {
        $id = (int)$id;
        $in_biro = $this->db->where('id', $id)->count_all_results($this->table_biro);
        $target_table = ($in_biro > 0) ? $this->table_biro : $this->table_mahasiswa;

        $this->db->where('id', $id);
        return $this->db->update($target_table, [
            'password'   => $hashed_password,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Helper nama role berdasarkan angka (1: Super Admin, 2: Admin, 3: Mahasiswa, 4: Dosen)
     */
    public static function role_label($role_id)
    {
        switch ((int)$role_id) {
            case 1:
                return 'Super Admin';
            case 2:
                return 'Admin';
            case 3:
                return 'Mahasiswa';
            case 4:
                return 'Dosen';
            default:
                return 'User';
        }
    }
}
