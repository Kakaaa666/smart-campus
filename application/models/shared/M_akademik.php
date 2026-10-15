<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akademik extends CI_Model
{
    const MAX_SKS_PER_SEMESTER = 24;
    const MIN_SKS_PER_SEMESTER = 12;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema();
    }

    public function module_name()
    {
        return 'Akademik';
    }

    public function ensure_schema()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `penawaran_matakuliah` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `kode_mk` VARCHAR(32) NOT NULL,
            `nama_mk` VARCHAR(150) NOT NULL,
            `prodi` VARCHAR(100) NOT NULL,
            `semester` TINYINT(2) NOT NULL,
            `tahun_akademik` VARCHAR(9) NOT NULL,
            `semester_akademik` ENUM('Ganjil','Genap') NOT NULL,
            `sks` TINYINT(2) NOT NULL,
            `kelas` VARCHAR(20) NOT NULL,
            `hari` ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
            `waktu_mulai` TIME NOT NULL,
            `waktu_selesai` TIME NOT NULL,
            `ruangan` VARCHAR(100) NOT NULL,
            `dosen_id` INT(11) NOT NULL,
            `jenis_mk` ENUM('Wajib','Jurusan','Pilihan','Luar Jurusan','Umum') NOT NULL DEFAULT 'Wajib',
            `kuota` INT(11) NOT NULL DEFAULT 40,
            `prasyarat` VARCHAR(150) NULL DEFAULT NULL,
            `aktif` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_penawaran_periode` (`tahun_akademik`,`semester_akademik`,`prodi`,`semester`,`aktif`),
            KEY `idx_penawaran_kode` (`kode_mk`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Periksa dan tambahkan kolom jenis_mk, kuota, prasyarat jika belum ada
        if ($this->db->table_exists('penawaran_matakuliah')) {
            $fields = $this->db->list_fields('penawaran_matakuliah');
            if (!in_array('jenis_mk', $fields, true)) {
                $this->db->query("ALTER TABLE `penawaran_matakuliah` ADD COLUMN `jenis_mk` ENUM('Wajib','Jurusan','Pilihan','Luar Jurusan','Umum') NOT NULL DEFAULT 'Wajib' AFTER `dosen_id`");
            }
            if (!in_array('kuota', $fields, true)) {
                $this->db->query("ALTER TABLE `penawaran_matakuliah` ADD COLUMN `kuota` INT(11) NOT NULL DEFAULT 40 AFTER `jenis_mk`");
            }
            if (!in_array('prasyarat', $fields, true)) {
                $this->db->query("ALTER TABLE `penawaran_matakuliah` ADD COLUMN `prasyarat` VARCHAR(150) NULL DEFAULT NULL AFTER `kuota`");
            }
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS `dosen_wali` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `mahasiswa_id` INT(11) NOT NULL,
            `dosen_id` INT(11) NOT NULL,
            `ditetapkan_oleh` INT(11) NOT NULL,
            `ditetapkan_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_dosen_wali_mahasiswa` (`mahasiswa_id`),
            KEY `idx_dosen_wali_dosen` (`dosen_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `krs` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `akun_id` INT(11) NOT NULL,
            `tahun_akademik` VARCHAR(9) NOT NULL,
            `semester_akademik` ENUM('Ganjil','Genap') NOT NULL,
            `status` ENUM('DRAFT','MENUNGGU','DISETUJUI','DITOLAK') NOT NULL DEFAULT 'DRAFT',
            `total_sks` TINYINT(3) NOT NULL DEFAULT 0,
            `catatan` TEXT NULL,
            `diajukan_at` DATETIME NULL,
            `diproses_oleh` INT(11) NULL,
            `diproses_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_krs_mahasiswa_periode` (`akun_id`,`tahun_akademik`,`semester_akademik`),
            KEY `idx_krs_status` (`status`,`tahun_akademik`,`semester_akademik`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `krs_detail` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `krs_id` INT(11) NOT NULL,
            `penawaran_id` INT(11) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_krs_detail_offering` (`krs_id`,`penawaran_id`),
            KEY `idx_krs_detail_penawaran` (`penawaran_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `khs_nilai` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `akun_id` INT(11) NOT NULL,
            `tahun_akademik` VARCHAR(20) NOT NULL,
            `semester_akademik` ENUM('Ganjil','Genap') NOT NULL,
            `semester_mhs` TINYINT(2) NOT NULL DEFAULT 1,
            `kode_mk` VARCHAR(32) NOT NULL,
            `nama_mk` VARCHAR(150) NOT NULL,
            `sks` TINYINT(2) NOT NULL,
            `nilai_huruf` VARCHAR(5) NOT NULL,
            `nilai_indeks` DECIMAL(3,2) NOT NULL,
            `kategori` VARCHAR(50) NOT NULL,
            `nxk` DECIMAL(5,2) NOT NULL,
            `keterangan` VARCHAR(20) NOT NULL DEFAULT '1',
            `is_paket` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_khs_mahasiswa_periode` (`akun_id`,`tahun_akademik`,`semester_akademik`),
            KEY `idx_khs_semester_mhs` (`akun_id`,`semester_mhs`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Seeder otomatis katalog mata kuliah multi-jurusan
        $this->seed_comprehensive_offerings();

        // Seeder otomatis akun dosen dan penugasan dosen wali mahasiswa
        $this->ensure_default_lecturers_and_advisors();

        // Seeder otomatis KHS mahasiswa
        $this->ensure_default_khs_data();
    }

    /**
     * Menentukan kesamaan rumpun program studi (case-insensitive & toleran terhadap prefix S1/D3/Teknik)
     */
    public static function is_same_major($prodi_a, $prodi_b)
    {
        $a = strtolower(trim((string)$prodi_a));
        $b = strtolower(trim((string)$prodi_b));
        if ($a === '' || $b === '') {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        
        // Bersihkan prefix jenjang umum (s1, d3, d4, s2, prodi, jurusan, fakultas, teknik)
        $clean_a = trim(preg_replace('/^(s1|d3|d4|s2|prodi|jurusan|fakultas|teknik)\s+/i', '', $a));
        $clean_b = trim(preg_replace('/^(s1|d3|d4|s2|prodi|jurusan|fakultas|teknik)\s+/i', '', $b));
        if ($clean_a !== '' && $clean_a === $clean_b) {
            return true;
        }

        // Rumpun disiplin ilmu kata kunci
        $disciplines = [
            'informatika',
            'akuntansi',
            'manajemen',
            'sistem informasi',
            'ilmu hukum',
            'hukum',
            'teknik elektro',
            'teknik sipil'
        ];

        foreach ($disciplines as $disc) {
            if (strpos($a, $disc) !== false && strpos($b, $disc) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolusi Kategori Mata Kuliah Secara Dinamis & Kontekstual Berdasarkan Prodi Mahasiswa:
     * - MK Umum: Tetap 'Umum' untuk seluruh prodi (Pancasila, Bahasa Indonesia, dsb)
     * - MK dari Prodi Mahasiswa: 'Wajib' atau 'Jurusan' (sesuai kurikulum prodinya)
     * - MK dari Prodi Lain: 'Luar Jurusan' (Lintas Prodi / MBKM), dengan badge nama prodi asalnya
     */
    public static function resolve_course_category($offering, $student_prodi)
    {
        $item_prodi = trim((string)($offering->prodi ?? ''));
        $item_jenis = trim((string)($offering->jenis_mk ?? 'Wajib'));
        $item_kode  = trim((string)($offering->kode_mk ?? ''));

        // 1. Mata Kuliah Umum (MKU / Universitas)
        if ($item_jenis === 'Umum' || stripos($item_kode, 'MKU') === 0 || strcasecmp($item_prodi, 'Umum') === 0) {
            return [
                'kategori'       => 'Umum',
                'kategori_label' => 'MK Umum',
                'badge_class'    => 'badge-mk-umum',
                'is_own_major'   => true,
                'prodi_asal'     => 'Umum'
            ];
        }

        // 2. Cek apakah matakuliah berasal dari program studi mahasiswa yang bersangkutan
        if (self::is_same_major($item_prodi, $student_prodi)) {
            if ($item_jenis === 'Jurusan') {
                return [
                    'kategori'       => 'Jurusan',
                    'kategori_label' => 'MK Jurusan',
                    'badge_class'    => 'badge-mk-jurusan',
                    'is_own_major'   => true,
                    'prodi_asal'     => $item_prodi
                ];
            } else {
                return [
                    'kategori'       => 'Wajib',
                    'kategori_label' => 'MK Wajib Prodi',
                    'badge_class'    => 'badge-mk-wajib',
                    'is_own_major'   => true,
                    'prodi_asal'     => $item_prodi
                ];
            }
        }

        // 3. Matakuliah dari Luar Jurusan / Lintas Prodi
        $label_prodi = $item_prodi ?: 'Lainnya';
        return [
            'kategori'       => 'Luar Jurusan',
            'kategori_label' => 'Luar Jurusan (' . $label_prodi . ')',
            'badge_class'    => 'badge-mk-luar',
            'is_own_major'   => false,
            'prodi_asal'     => $item_prodi
        ];
    }

    /**
     * Seeder otomatis penawaran mata kuliah multi-jurusan:
     * Teknik Informatika, Akuntansi, Manajemen, Sistem Informasi, Hukum, & MKU
     */
    private function seed_comprehensive_offerings()
    {
        // Cari dosen aktif atau akun admin sebagai pengampu
        $dosen = $this->db->where('role', 4)->where('deleted_at IS NULL', null, false)->get('akun')->row();
        $dosen_id = $dosen ? (int)$dosen->id : 0;
        if (!$dosen_id) {
            $admin = $this->db->where_in('role', [1, 2])->where('deleted_at IS NULL', null, false)->get('akun')->row();
            $dosen_id = $admin ? (int)$admin->id : 1;
        }

        $period = $this->get_active_period();
        $thn = $period['tahun_akademik'];

        // Normalisasi data matkul eksisting (misal MKU prodi 'Umum' dan jenis 'Umum')
        $this->db->where("kode_mk LIKE 'MKU%'")->update('penawaran_matakuliah', [
            'prodi' => 'Umum',
            'jenis_mk' => 'Umum'
        ]);
        $this->db->where('kode_mk', 'MN-305')->update('penawaran_matakuliah', [
            'prodi' => 'Manajemen',
            'jenis_mk' => 'Jurusan'
        ]);
        $this->db->where('kode_mk', 'SI-405')->update('penawaran_matakuliah', [
            'prodi' => 'Sistem Informasi',
            'jenis_mk' => 'Jurusan'
        ]);
        $this->db->where('kode_mk', 'HK-504')->update('penawaran_matakuliah', [
            'prodi' => 'Ilmu Hukum',
            'jenis_mk' => 'Jurusan'
        ]);

        $sample_courses = [
            // ==========================================
            // 1. TEKNIK INFORMATIKA (GANJIL)
            // ==========================================
            [
                'kode_mk' => 'TI-101', 'nama_mk' => 'Algoritma & Pemrograman I', 'prodi' => 'Teknik Informatika',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-1A',
                'hari' => 'Senin', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Lab Komputer 1',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'TI-102', 'nama_mk' => 'Matematika Diskrit', 'prodi' => 'Teknik Informatika',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-1A',
                'hari' => 'Selasa', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Ruang Teori RK-201',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 45, 'prasyarat' => 'Tidak Ada'
            ],
            [
                // Jadwal Senin 08:00 (bentrok dengan TI-101 jika dipilih bersamaan)
                'kode_mk' => 'TI-301', 'nama_mk' => 'Struktur Data & Algoritma', 'prodi' => 'Teknik Informatika',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-3A',
                'hari' => 'Senin', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Ruang Teori RK-202',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Lulus Algoritma I'
            ],
            [
                'kode_mk' => 'TI-302', 'nama_mk' => 'Sistem Basis Data', 'prodi' => 'Teknik Informatika',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-3A',
                'hari' => 'Rabu', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Lab Basis Data',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'TI-501', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'prodi' => 'Teknik Informatika',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-5A',
                'hari' => 'Kamis', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Ruang Teori RK-301',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Lulus PBO'
            ],
            [
                'kode_mk' => 'TI-502', 'nama_mk' => 'Jaringan Komputer & Cyber Security', 'prodi' => 'Teknik Informatika',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-5A',
                'hari' => 'Jumat', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Lab Jaringan',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'TI-511', 'nama_mk' => 'Kecerdasan Buatan & Machine Learning', 'prodi' => 'Teknik Informatika',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-5B',
                'hari' => 'Selasa', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Lab Riset AI',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 30, 'prasyarat' => 'Lulus Matematika Diskrit'
            ],
            [
                'kode_mk' => 'TI-512', 'nama_mk' => 'Pengembangan Aplikasi Mobile Terapan', 'prodi' => 'Teknik Informatika',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-5B',
                'hari' => 'Rabu', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '16:00:00', 'ruangan' => 'Lab Mobile Dev',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 35, 'prasyarat' => 'Lulus Algoritma I'
            ],
            [
                'kode_mk' => 'TI-711', 'nama_mk' => 'Cloud Computing & DevOps Architecture', 'prodi' => 'Teknik Informatika',
                'semester' => 7, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'TI-7A',
                'hari' => 'Kamis', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Lab Cloud Server',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 30, 'prasyarat' => 'Lulus Jaringan Komputer'
            ],

            // ==========================================
            // 2. AKUNTANSI (GANJIL)
            // ==========================================
            [
                'kode_mk' => 'AK-101', 'nama_mk' => 'Pengantar Akuntansi I', 'prodi' => 'Akuntansi',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-1A',
                'hari' => 'Senin', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Gedung Akuntansi AK-101',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 45, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'AK-301', 'nama_mk' => 'Akuntansi Keuangan Menengah I', 'prodi' => 'Akuntansi',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-3A',
                'hari' => 'Selasa', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Gedung Akuntansi AK-201',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Lulus Pengantar Akuntansi I'
            ],
            [
                'kode_mk' => 'AK-302', 'nama_mk' => 'Akuntansi Biaya & Manajemen', 'prodi' => 'Akuntansi',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-3A',
                'hari' => 'Rabu', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Gedung Akuntansi AK-202',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'AK-501', 'nama_mk' => 'Sistem Informasi Akuntansi (SIA)', 'prodi' => 'Akuntansi',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-5A',
                'hari' => 'Kamis', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Lab Komputer Akuntansi',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Lulus Pengantar Akuntansi II'
            ],
            [
                'kode_mk' => 'AK-511', 'nama_mk' => 'Auditing & Pemeriksaan Akuntansi Forensik', 'prodi' => 'Akuntansi',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-5B',
                'hari' => 'Jumat', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '16:00:00', 'ruangan' => 'Gedung Akuntansi AK-301',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 35, 'prasyarat' => 'Lulus Akuntansi Keuangan'
            ],
            [
                'kode_mk' => 'AK-711', 'nama_mk' => 'Analisis Laporan Keuangan Digital & Pajak', 'prodi' => 'Akuntansi',
                'semester' => 7, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'AK-7A',
                'hari' => 'Kamis', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Gedung Bisnis B-202',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 30, 'prasyarat' => 'Lulus Auditing'
            ],

            // ==========================================
            // 3. MANAJEMEN & SISTEM INFORMASI & HUKUM (GANJIL)
            // ==========================================
            [
                'kode_mk' => 'MN-101', 'nama_mk' => 'Pengantar Manajemen & Bisnis Global', 'prodi' => 'Manajemen',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'MN-1A',
                'hari' => 'Rabu', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Gedung Bisnis B-102',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 45, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'MN-305', 'nama_mk' => 'Manajemen Bisnis Digital & Startup', 'prodi' => 'Manajemen',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'MN-3X',
                'hari' => 'Senin', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Gedung Bisnis B-101',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 50, 'prasyarat' => 'Terbuka'
            ],
            [
                'kode_mk' => 'MN-501', 'nama_mk' => 'Manajemen Pemasaran Digital & E-Commerce', 'prodi' => 'Manajemen',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'MN-5A',
                'hari' => 'Jumat', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Gedung Bisnis B-201',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 40, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'SI-101', 'nama_mk' => 'Konsep Sistem Informasi & Proses Bisnis', 'prodi' => 'Sistem Informasi',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'SI-1A',
                'hari' => 'Selasa', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Ruang Teori SI-101',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'SI-301', 'nama_mk' => 'Analisis & Desain Sistem Informasi (ADSI)', 'prodi' => 'Sistem Informasi',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'SI-3A',
                'hari' => 'Kamis', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Lab Rekayasa SI',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Lulus Konsep SI'
            ],
            [
                'kode_mk' => 'SI-405', 'nama_mk' => 'Desain Antarmuka Pengguna (UI/UX Design)', 'prodi' => 'Sistem Informasi',
                'semester' => 3, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 3, 'kelas' => 'SI-4X',
                'hari' => 'Selasa', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Lab Multimedia SI',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 40, 'prasyarat' => 'Terbuka'
            ],
            [
                'kode_mk' => 'HK-504', 'nama_mk' => 'Hukum Siber, Etika Digital & HAKI', 'prodi' => 'Ilmu Hukum',
                'semester' => 5, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 2, 'kelas' => 'HK-5X',
                'hari' => 'Kamis', 'waktu_mulai' => '15:30:00', 'waktu_selesai' => '17:10:00', 'ruangan' => 'Gedung Hukum H-201',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 45, 'prasyarat' => 'Terbuka'
            ],

            // ==========================================
            // 4. MATA KULIAH UMUM (MKU - GANJIL)
            // ==========================================
            [
                'kode_mk' => 'MKU-101', 'nama_mk' => 'Pendidikan Pancasila & Kewarganegaraan', 'prodi' => 'Umum',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 2, 'kelas' => 'MKU-A',
                'hari' => 'Jumat', 'waktu_mulai' => '09:00:00', 'waktu_selesai' => '10:40:00', 'ruangan' => 'Auditorium Utama Lt. 3',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Umum', 'kuota' => 80, 'prasyarat' => 'Wajib Universitas'
            ],
            [
                'kode_mk' => 'MKU-102', 'nama_mk' => 'Bahasa Indonesia & Penulisan Karya Ilmiah', 'prodi' => 'Umum',
                'semester' => 1, 'tahun_akademik' => $thn, 'semester_akademik' => 'Ganjil', 'sks' => 2, 'kelas' => 'MKU-B',
                'hari' => 'Sabtu', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:10:00', 'ruangan' => 'Auditorium Utama Lt. 2',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Umum', 'kuota' => 80, 'prasyarat' => 'Wajib Universitas'
            ],

            // ==========================================
            // 5. SEMESTER GENAP: INFORMATIKA & AKUNTANSI & MKU
            // ==========================================
            [
                'kode_mk' => 'TI-201', 'nama_mk' => 'Pemrograman Berorientasi Objek (PBO)', 'prodi' => 'Teknik Informatika',
                'semester' => 2, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'TI-2A',
                'hari' => 'Senin', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Lab Komputer 2',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Lulus Algoritma I'
            ],
            [
                'kode_mk' => 'TI-202', 'nama_mk' => 'Sistem Operasi & Arsitektur Komputer', 'prodi' => 'Teknik Informatika',
                'semester' => 2, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'TI-2A',
                'hari' => 'Selasa', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '15:30:00', 'ruangan' => 'Ruang Teori RK-203',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'TI-401', 'nama_mk' => 'Pemrograman Web Lanjut (Fullstack JS/PHP)', 'prodi' => 'Teknik Informatika',
                'semester' => 4, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'TI-4A',
                'hari' => 'Rabu', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Lab Web & Desain',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Lulus Basis Data'
            ],
            [
                'kode_mk' => 'TI-601', 'nama_mk' => 'Data Warehouse & Business Intelligence', 'prodi' => 'Teknik Informatika',
                'semester' => 6, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'TI-6A',
                'hari' => 'Kamis', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Lab Riset Data',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 35, 'prasyarat' => 'Lulus Basis Data'
            ],
            [
                'kode_mk' => 'AK-201', 'nama_mk' => 'Pengantar Akuntansi II', 'prodi' => 'Akuntansi',
                'semester' => 2, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'AK-2A',
                'hari' => 'Senin', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Gedung Akuntansi AK-101',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 40, 'prasyarat' => 'Lulus Pengantar Akuntansi I'
            ],
            [
                'kode_mk' => 'AK-401', 'nama_mk' => 'Perpajakan Terapan & e-Filing', 'prodi' => 'Akuntansi',
                'semester' => 4, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'AK-4A',
                'hari' => 'Rabu', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '10:30:00', 'ruangan' => 'Lab Perpajakan & Audit',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Wajib', 'kuota' => 35, 'prasyarat' => 'Tidak Ada'
            ],
            [
                'kode_mk' => 'AK-601', 'nama_mk' => 'Akuntansi Sektor Publik & Forensik', 'prodi' => 'Akuntansi',
                'semester' => 6, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 3, 'kelas' => 'AK-6A',
                'hari' => 'Kamis', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '13:00:00', 'ruangan' => 'Gedung Akuntansi AK-302',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Jurusan', 'kuota' => 35, 'prasyarat' => 'Lulus Akuntansi Biaya'
            ],
            [
                'kode_mk' => 'MKU-201', 'nama_mk' => 'Etika Profesi & Komunikasi Kepemimpinan', 'prodi' => 'Umum',
                'semester' => 2, 'tahun_akademik' => $thn, 'semester_akademik' => 'Genap', 'sks' => 2, 'kelas' => 'MKU-C',
                'hari' => 'Jumat', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:10:00', 'ruangan' => 'Auditorium Utama Lt. 2',
                'dosen_id' => $dosen_id, 'jenis_mk' => 'Umum', 'kuota' => 75, 'prasyarat' => 'Wajib Universitas'
            ]
        ];

        foreach ($sample_courses as $item) {
            $exists = $this->db->where('kode_mk', $item['kode_mk'])
                               ->where('kelas', $item['kelas'])
                               ->where('tahun_akademik', $item['tahun_akademik'])
                               ->where('semester_akademik', $item['semester_akademik'])
                               ->count_all_results('penawaran_matakuliah');
            if ($exists === 0) {
                $item['aktif'] = 1;
                $this->db->insert('penawaran_matakuliah', $item);
            }
        }
    }

    public function get_active_period()
    {
        $month = (int)date('n');
        $year = (int)date('Y');
        $semester = ($month >= 7 && $month <= 12) ? 'Ganjil' : 'Genap';
        $academic_year = $semester === 'Ganjil'
            ? $year . '/' . ($year + 1)
            : ($year - 1) . '/' . $year;

        return [
            'tahun_akademik' => $academic_year,
            'semester_akademik' => $semester,
        ];
    }

    /**
     * Ambil penawaran mata kuliah lengkap untuk KRS:
     * Menyesuaikan kategori (Wajib, Jurusan, Luar Jurusan, Umum) secara dinamis & kontekstual
     * berdasarkan program studi mahasiswa yang aktif.
     */
    public function get_offerings($prodi, $semester, $period)
    {
        $offerings = $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                              ->from('penawaran_matakuliah')
                              ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                              ->where('penawaran_matakuliah.tahun_akademik', $period['tahun_akademik'])
                              ->where('penawaran_matakuliah.aktif', 1)
                              ->order_by("FIELD(penawaran_matakuliah.semester_akademik, '{$period['semester_akademik']}')", 'DESC', false)
                              ->order_by('penawaran_matakuliah.semester', 'ASC')
                              ->order_by("FIELD(penawaran_matakuliah.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')", '', false)
                              ->order_by('penawaran_matakuliah.waktu_mulai', 'ASC')
                              ->get()
                              ->result();

        // Sematkan resolusi kategori kontekstual terhadap prodi mahasiswa
        foreach ($offerings as $item) {
            $cat = self::resolve_course_category($item, $prodi);
            $item->kategori_kontekstual = $cat['kategori'];
            $item->kategori_label       = $cat['kategori_label'];
            $item->kategori_badge_class = $cat['badge_class'];
            $item->is_own_major         = $cat['is_own_major'];
            $item->prodi_asal           = $cat['prodi_asal'];
        }

        // Urutkan cerdas:
        // 1. Semester aktif periode saat ini didahulukan
        // 2. Mata kuliah prodi sendiri (Wajib, Jurusan), lalu Umum, lalu Luar Jurusan
        usort($offerings, function($a, $b) use ($period) {
            // Prioritas 1: Periode akademik yang cocok
            $a_match_period = ($a->semester_akademik === $period['semester_akademik']) ? 0 : 1;
            $b_match_period = ($b->semester_akademik === $period['semester_akademik']) ? 0 : 1;
            if ($a_match_period !== $b_match_period) {
                return $a_match_period - $b_match_period;
            }

            // Prioritas 2: Urutan Kategori Kontekstual (Wajib -> Jurusan -> Umum -> Luar Jurusan)
            $rank = ['Wajib' => 1, 'Jurusan' => 2, 'Umum' => 3, 'Luar Jurusan' => 4];
            $rank_a = $rank[$a->kategori_kontekstual] ?? 5;
            $rank_b = $rank[$b->kategori_kontekstual] ?? 5;
            if ($rank_a !== $rank_b) {
                return $rank_a - $rank_b;
            }

            // Prioritas 3: Jenjang Semester matakuliah
            if ((int)$a->semester !== (int)$b->semester) {
                return (int)$a->semester - (int)$b->semester;
            }

            // Prioritas 4: Hari kuliah
            $day_rank = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
            $d_a = $day_rank[$a->hari] ?? 7;
            $d_b = $day_rank[$b->hari] ?? 7;
            if ($d_a !== $d_b) {
                return $d_a - $d_b;
            }

            return strcmp($a->waktu_mulai, $b->waktu_mulai);
        });

        return $offerings;
    }

    public function get_krs($akun_id, $period)
    {
        $krs = $this->db->select('krs.*, dosen.nama_lengkap AS nama_dosen_wali')
                        ->from('krs')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'left')
                        ->join('akun AS dosen', 'dosen.id = dosen_wali.dosen_id', 'left')
                        ->where('krs.akun_id', (int)$akun_id)
                        ->where('krs.tahun_akademik', $period['tahun_akademik'])
                        ->where('krs.semester_akademik', $period['semester_akademik'])
                        ->get()
                        ->row();

        if (!$krs) {
            return null;
        }

        $krs->mata_kuliah = $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                                     ->from('krs_detail')
                                     ->join('penawaran_matakuliah', 'penawaran_matakuliah.id = krs_detail.penawaran_id', 'inner')
                                     ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                                     ->where('krs_detail.krs_id', (int)$krs->id)
                                     ->order_by("FIELD(penawaran_matakuliah.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')", '', false)
                                     ->order_by('penawaran_matakuliah.waktu_mulai', 'ASC')
                                     ->get()
                                     ->result();

        $mhs = $this->get_account($akun_id);
        $student_prodi = $mhs ? $mhs->prodi : '';
        foreach ($krs->mata_kuliah as $mk) {
            $cat = self::resolve_course_category($mk, $student_prodi);
            $mk->kategori_kontekstual = $cat['kategori'];
            $mk->kategori_label       = $cat['kategori_label'];
            $mk->kategori_badge_class = $cat['badge_class'];
            $mk->is_own_major         = $cat['is_own_major'];
            $mk->prodi_asal           = $cat['prodi_asal'];
        }

        return $krs;
    }

    public function get_all_students_for_preview()
    {
        return $this->db->select('id, nim, nama_lengkap, prodi, fakultas, semester')
                        ->where('role', 3)
                        ->where('deleted_at IS NULL', null, false)
                        ->order_by('prodi', 'ASC')
                        ->order_by('nama_lengkap', 'ASC')
                        ->get('akun')
                        ->result();
    }

    public function get_account($akun_id)
    {
        return $this->db->select('id, nim, nama_lengkap, fakultas, prodi, jenjang, semester, role')
                        ->where('id', (int)$akun_id)
                        ->where('role', 3)
                        ->where('deleted_at IS NULL', null, false)
                        ->get('akun')
                        ->row();
    }

    public function get_advisor_for_student($akun_id)
    {
        return $this->db->select('akun.id, akun.nim, akun.nama_lengkap')
                        ->from('dosen_wali')
                        ->join('akun', 'akun.id = dosen_wali.dosen_id', 'inner')
                        ->where('dosen_wali.mahasiswa_id', (int)$akun_id)
                        ->where('akun.role', 4)
                        ->where('akun.deleted_at IS NULL', null, false)
                        ->get()
                        ->row();
    }

    public function get_admin_offerings()
    {
        return $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                        ->from('penawaran_matakuliah')
                        ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                        ->order_by('penawaran_matakuliah.tahun_akademik', 'DESC')
                        ->order_by('penawaran_matakuliah.semester_akademik', 'ASC')
                        ->order_by('penawaran_matakuliah.prodi', 'ASC')
                        ->order_by('penawaran_matakuliah.semester', 'ASC')
                        ->order_by('penawaran_matakuliah.kode_mk', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_period_overview($period)
    {
        $offerings = $this->db->where('tahun_akademik', $period['tahun_akademik'])
                              ->where('semester_akademik', $period['semester_akademik'])
                              ->where('aktif', 1)
                              ->count_all_results('penawaran_matakuliah');
        $krs_status = $this->db->select('status, COUNT(*) AS jumlah')
                               ->where('tahun_akademik', $period['tahun_akademik'])
                               ->where('semester_akademik', $period['semester_akademik'])
                               ->group_by('status')
                               ->get('krs')
                               ->result();
        $counts = ['DRAFT' => 0, 'MENUNGGU' => 0, 'DISETUJUI' => 0, 'DITOLAK' => 0];
        foreach ($krs_status as $status) {
            $counts[$status->status] = (int)$status->jumlah;
        }

        return [
            'penawaran_aktif' => (int)$offerings,
            'krs' => $counts,
        ];
    }

    public function get_students()
    {
        return $this->db->select('akun.id, akun.nim, akun.nama_lengkap, akun.prodi, akun.semester, dosen_wali.dosen_id')
                        ->from('akun')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = akun.id', 'left')
                        ->where('akun.role', 3)
                        ->where('akun.deleted_at IS NULL', null, false)
                        ->order_by('akun.prodi', 'ASC')
                        ->order_by('akun.nim', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_lecturers()
    {
        return $this->db->select('id, nim, nama_lengkap')
                        ->where('role', 4)
                        ->where('deleted_at IS NULL', null, false)
                        ->order_by('nama_lengkap', 'ASC')
                        ->get('akun')
                        ->result();
    }

    public function create_offering(array $offering)
    {
        $lecturer = $this->db->where('id', (int)$offering['dosen_id'])
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->count_all_results('akun');
        $duplicate = $this->db->where('kode_mk', $offering['kode_mk'])
                              ->where('kelas', $offering['kelas'])
                              ->where('prodi', $offering['prodi'])
                              ->where('semester', (int)$offering['semester'])
                              ->where('tahun_akademik', $offering['tahun_akademik'])
                              ->where('semester_akademik', $offering['semester_akademik'])
                              ->count_all_results('penawaran_matakuliah');
        if ($lecturer !== 1 || $duplicate > 0) {
            return false;
        }

        $schedule_conflict = $this->db->where('tahun_akademik', $offering['tahun_akademik'])
                                      ->where('semester_akademik', $offering['semester_akademik'])
                                      ->where('hari', $offering['hari'])
                                      ->where('aktif', 1)
                                      ->group_start()
                                          ->where('dosen_id', (int)$offering['dosen_id'])
                                          ->or_where('ruangan', $offering['ruangan'])
                                      ->group_end()
                                      ->group_start()
                                          ->where('waktu_mulai <', $offering['waktu_selesai'])
                                          ->where('waktu_selesai >', $offering['waktu_mulai'])
                                      ->group_end()
                                      ->count_all_results('penawaran_matakuliah');
        if ($schedule_conflict > 0) {
            return false;
        }

        return $this->db->insert('penawaran_matakuliah', $offering);
    }

    public function set_offering_active($offering_id, $active)
    {
        $exists = $this->db->where('id', (int)$offering_id)->count_all_results('penawaran_matakuliah');
        if ($exists !== 1) {
            return false;
        }
        return $this->db->where('id', (int)$offering_id)
                        ->update('penawaran_matakuliah', ['aktif' => $active ? 1 : 0]);
    }

    public function assign_advisor($student_id, $lecturer_id, $admin_id)
    {
        $student = $this->db->where('id', (int)$student_id)
                            ->where('role', 3)
                            ->where('deleted_at IS NULL', null, false)
                            ->count_all_results('akun');
        $lecturer = $this->db->where('id', (int)$lecturer_id)
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->count_all_results('akun');
        if ($student !== 1 || $lecturer !== 1) {
            return false;
        }

        $assignment = $this->db->get_where('dosen_wali', ['mahasiswa_id' => (int)$student_id])->row();
        $data = [
            'mahasiswa_id' => (int)$student_id,
            'dosen_id' => (int)$lecturer_id,
            'ditetapkan_oleh' => (int)$admin_id,
        ];

        if ($assignment) {
            return $this->db->where('id', (int)$assignment->id)->update('dosen_wali', $data);
        }

        return $this->db->insert('dosen_wali', $data);
    }

    public function save_student_krs($akun_id, $offering_ids, $submit = false)
    {
        if (!is_array($offering_ids)) {
            return ['success' => false, 'message' => 'Daftar mata kuliah tidak valid.'];
        }

        $ids = [];
        foreach ($offering_ids as $offering_id) {
            if (!is_scalar($offering_id) || !ctype_digit((string)$offering_id) || (int)$offering_id < 1) {
                return ['success' => false, 'message' => 'Pilihan mata kuliah tidak valid.'];
            }
            $ids[] = (int)$offering_id;
        }
        $ids = array_values(array_unique($ids));

        $student = $this->get_account($akun_id);
        if (!$student) {
            return ['success' => false, 'message' => 'Data mahasiswa aktif tidak ditemukan.'];
        }

        $period = $this->get_active_period();
        $offerings = [];
        if ($ids) {
            $offerings = $this->db->where_in('id', $ids)
                                  ->where('aktif', 1)
                                  ->get('penawaran_matakuliah')
                                  ->result();
            if (count($offerings) !== count($ids)) {
                return ['success' => false, 'message' => 'Satu atau lebih mata kuliah sudah tidak tersedia. Muat ulang halaman dan periksa pilihan Anda.'];
            }
        }

        $total_sks = 0;
        $schedule = [];
        foreach ($offerings as $offering) {
            $total_sks += (int)$offering->sks;
            foreach ($schedule as $scheduled) {
                if ($offering->hari === $scheduled->hari
                    && $offering->waktu_mulai < $scheduled->waktu_selesai
                    && $offering->waktu_selesai > $scheduled->waktu_mulai) {
                    return ['success' => false, 'message' => 'Jadwal ' . $offering->kode_mk . ' (' . $offering->nama_mk . ') bertabrakan dengan ' . $scheduled->kode_mk . ' (' . $scheduled->nama_mk . ') pada hari ' . $offering->hari . ' (' . substr($offering->waktu_mulai, 0, 5) . '-' . substr($offering->waktu_selesai, 0, 5) . ' vs ' . substr($scheduled->waktu_mulai, 0, 5) . '-' . substr($scheduled->waktu_selesai, 0, 5) . ').'];
                }
            }
            $schedule[] = $offering;
        }

        if ($total_sks > self::MAX_SKS_PER_SEMESTER) {
            return ['success' => false, 'message' => 'Total beban studi melebihi batas maksimal ' . self::MAX_SKS_PER_SEMESTER . ' SKS.'];
        }
        if ($submit && !$ids) {
            return ['success' => false, 'message' => 'Pilih minimal satu mata kuliah sebelum mengajukan KRS.'];
        }
        if ($submit && $total_sks < self::MIN_SKS_PER_SEMESTER) {
            return ['success' => false, 'message' => 'Total beban studi minimal ' . self::MIN_SKS_PER_SEMESTER . ' SKS untuk mengajukan KRS.'];
        }
        if ($submit) {
            $advisor = $this->get_advisor_for_student($akun_id);
            if (!$advisor) {
                return ['success' => false, 'message' => 'Pengajuan belum dapat dikirim karena Dosen Wali belum ditetapkan oleh Admin Akademik.'];
            }
        }

        $this->db->trans_begin();
        $krs = $this->db->where('akun_id', (int)$akun_id)
                        ->where('tahun_akademik', $period['tahun_akademik'])
                        ->where('semester_akademik', $period['semester_akademik'])
                        ->get('krs')
                        ->row();

        if ($krs && !in_array($krs->status, ['DRAFT', 'DITOLAK'], true)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'KRS berstatus ' . $krs->status . ' dan tidak dapat diubah saat ini.'];
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            'akun_id' => (int)$akun_id,
            'tahun_akademik' => $period['tahun_akademik'],
            'semester_akademik' => $period['semester_akademik'],
            'status' => $submit ? 'MENUNGGU' : 'DRAFT',
            'total_sks' => $total_sks,
            'catatan' => null,
            'diajukan_at' => $submit ? $now : null,
            'diproses_oleh' => null,
            'diproses_at' => null,
        ];

        if ($krs) {
            $this->db->where('id', (int)$krs->id)->update('krs', $data);
            $krs_id = (int)$krs->id;
            $this->db->where('krs_id', $krs_id)->delete('krs_detail');
        } else {
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $this->db->insert('krs', $data);
            $krs_id = (int)$this->db->insert_id();
        }

        foreach ($ids as $offering_id) {
            $this->db->insert('krs_detail', [
                'krs_id' => $krs_id,
                'penawaran_id' => $offering_id,
            ]);
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'KRS gagal disimpan karena terjadi kesalahan basis data.'];
        }

        $this->db->trans_commit();
        return [
            'success' => true,
            'message' => $submit
                ? 'KRS berhasil diajukan kepada Dosen Wali.'
                : 'Pilihan mata kuliah berhasil disimpan sebagai draf.',
        ];
    }

    /**
     * Memastikan akun Dosen dan penugasan Dosen Wali mahasiswa tersedia di sistem
     */
    public function ensure_default_lecturers_and_advisors()
    {
        $lecturers = [
            [
                'nim' => 'sugeng',
                'nama_lengkap' => 'Sugeng Widodo, S.Kom., M.Kom',
                'email' => 'sugeng@staff.campus.ac.id',
                'role' => 4,
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'password' => password_hash('dosen123', PASSWORD_DEFAULT),
                'biro' => 'dosen',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'dosen',
                'nama_lengkap' => 'Dr. Ir. Hendra Setiawan, M.T.',
                'email' => 'dosen@staff.campus.ac.id',
                'role' => 4,
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'password' => password_hash('dosen123', PASSWORD_DEFAULT),
                'biro' => 'dosen',
                'foto' => 'avatar-4.png'
            ],
            [
                'nim' => 'maya',
                'nama_lengkap' => 'Dr. Maya Sartika, S.E., M.Ak.',
                'email' => 'maya.sartika@smartcampus.ac.id',
                'role' => 4,
                'prodi' => 'Akuntansi',
                'fakultas' => 'Fakultas Ekonomi & Bisnis',
                'password' => password_hash('dosen123', PASSWORD_DEFAULT),
                'biro' => 'dosen',
                'foto' => 'avatar-2.png'
            ]
        ];

        foreach ($lecturers as $lec) {
            $existing_lec = $this->db->where('nim', $lec['nim'])->or_where('email', $lec['email'])->get('akun')->row();
            if (!$existing_lec) {
                $this->db->insert('akun', $lec);
            } else {
                // Pastikan role dan biro murni Dosen
                $this->db->where('id', (int)$existing_lec->id)->update('akun', [
                    'role' => 4,
                    'biro' => 'dosen',
                    'nama_lengkap' => $lec['nama_lengkap'],
                    'deleted_at' => null
                ]);
            }
        }

        // Ambil ID dosen Pak Sugeng, Pak Hendra, dan Bu Maya
        $dosen_sugeng = $this->db->where('role', 4)->group_start()->where('nim', 'sugeng')->or_like('nama_lengkap', 'Sugeng')->group_end()->get('akun')->row();
        $dosen_hendra = $this->db->where('role', 4)->group_start()->where('nim', 'dosen')->or_like('nama_lengkap', 'Hendra')->group_end()->get('akun')->row();
        $dosen_maya   = $this->db->where('role', 4)->group_start()->where('nim', 'maya')->or_like('nama_lengkap', 'Maya')->group_end()->get('akun')->row();

        // 1. Khusus Nabila (NIM 2004) memilih Pak Sugeng sebagai Dosen Wali
        $mhs_nabila = $this->db->where('role', 3)->group_start()->where('nim', '2004')->or_like('nama_lengkap', 'Nabila')->group_end()->get('akun')->row();
        if ($mhs_nabila && $dosen_sugeng) {
            $check_dw = $this->db->where('mahasiswa_id', (int)$mhs_nabila->id)->get('dosen_wali')->row();
            if ($check_dw) {
                $this->db->where('id', (int)$check_dw->id)->update('dosen_wali', ['dosen_id' => (int)$dosen_sugeng->id]);
            } else {
                $this->db->insert('dosen_wali', [
                    'mahasiswa_id' => (int)$mhs_nabila->id,
                    'dosen_id' => (int)$dosen_sugeng->id,
                    'ditetapkan_oleh' => (int)$mhs_nabila->id
                ]);
            }

            // Pastikan KRS Nabila berstatus MENUNGGU agar muncul di antrean persetujuan Pak Sugeng
            $period = $this->get_active_period();
            $krs_nabila = $this->db->where('akun_id', (int)$mhs_nabila->id)
                                   ->where('tahun_akademik', $period['tahun_akademik'])
                                   ->where('semester_akademik', $period['semester_akademik'])
                                   ->get('krs')->row();
            if ($krs_nabila) {
                if ($krs_nabila->status === 'DRAFT' || empty($krs_nabila->status)) {
                    $this->db->where('id', (int)$krs_nabila->id)->update('krs', [
                        'status' => 'MENUNGGU',
                        'diajukan_at' => date('Y-m-d H:i:s')
                    ]);
                }
            } else {
                // Buat draf & submit KRS demo untuk Nabila jika belum ada
                $offering_ids = $this->db->select('id')->limit(4)->get('penawaran_matakuliah')->result();
                $ids = array_map(function($o){ return (int)$o->id; }, $offering_ids);
                $this->save_student_krs((int)$mhs_nabila->id, $ids, true);
            }
        }

        // 1b. Khusus Arthur Tampi (241221009) memilih Pak Sugeng sebagai Dosen Wali
        $mhs_arthur = $this->db->where('role', 3)->group_start()->where('nim', '241221009')->or_like('nama_lengkap', 'Arthur')->group_end()->get('akun')->row();
        if ($mhs_arthur && $dosen_sugeng) {
            $check_dw_art = $this->db->where('mahasiswa_id', (int)$mhs_arthur->id)->get('dosen_wali')->row();
            if ($check_dw_art) {
                $this->db->where('id', (int)$check_dw_art->id)->update('dosen_wali', ['dosen_id' => (int)$dosen_sugeng->id]);
            } else {
                $this->db->insert('dosen_wali', [
                    'mahasiswa_id' => (int)$mhs_arthur->id,
                    'dosen_id' => (int)$dosen_sugeng->id,
                    'ditetapkan_oleh' => (int)$mhs_arthur->id
                ]);
            }
        }

        // 2. Hubungkan mahasiswa lain ke Pak Hendra atau Bu Maya
        $other_students = $this->db->select('id, nim')
                                   ->where('role', 3)
                                   ->where('deleted_at IS NULL', null, false)
                                   ->get('akun')->result();

        foreach ($other_students as $stu) {
            if ($mhs_nabila && (int)$stu->id === (int)$mhs_nabila->id) {
                continue; // Nabila khusus Pak Sugeng
            }
            if ($mhs_arthur && (int)$stu->id === (int)$mhs_arthur->id) {
                continue; // Arthur khusus Pak Sugeng
            }
            $target_dosen_id = ($dosen_hendra) ? (int)$dosen_hendra->id : ($dosen_sugeng ? (int)$dosen_sugeng->id : 1);
            if ($stu->nim === '2003' && $dosen_maya) {
                $target_dosen_id = (int)$dosen_maya->id;
            }

            $dw = $this->db->where('mahasiswa_id', (int)$stu->id)->get('dosen_wali')->row();
            if (!$dw) {
                $this->db->insert('dosen_wali', [
                    'mahasiswa_id' => (int)$stu->id,
                    'dosen_id' => $target_dosen_id,
                    'ditetapkan_oleh' => 1
                ]);
            }
        }
    }

    /**
     * Deteksi benturan jadwal kuliah untuk daftar mata kuliah tertentu
     */
    public static function detect_schedule_conflicts(array $courses)
    {
        $conflicts = [];
        $n = count($courses);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $c1 = $courses[$i];
                $c2 = $courses[$j];
                $hari1 = $c1->hari ?? ($c1['hari'] ?? '');
                $hari2 = $c2->hari ?? ($c2['hari'] ?? '');
                if ($hari1 !== '' && $hari1 === $hari2) {
                    $m1 = substr($c1->waktu_mulai ?? ($c1['waktu_mulai'] ?? '00:00:00'), 0, 5);
                    $s1 = substr($c1->waktu_selesai ?? ($c1['waktu_selesai'] ?? '00:00:00'), 0, 5);
                    $m2 = substr($c2->waktu_mulai ?? ($c2['waktu_mulai'] ?? '00:00:00'), 0, 5);
                    $s2 = substr($c2->waktu_selesai ?? ($c2['waktu_selesai'] ?? '00:00:00'), 0, 5);

                    if (max($m1, $m2) < min($s1, $s2)) {
                        $conflicts[] = [
                            'c1' => $c1,
                            'c2' => $c2,
                            'hari' => $hari1,
                            'waktu1' => $m1 . ' - ' . $s1,
                            'waktu2' => $m2 . ' - ' . $s2,
                            'pesan' => ($c1->kode_mk ?? '') . ' (' . ($c1->nama_mk ?? '') . ') bentrok dengan ' . ($c2->kode_mk ?? '') . ' (' . ($c2->nama_mk ?? '') . ') pada hari ' . $hari1 . ' (' . $m1 . '-' . $s1 . ' vs ' . $m2 . '-' . $s2 . ')'
                        ];
                    }
                }
            }
        }
        return $conflicts;
    }

    public function get_pending_advisor_krs($lecturer_id = 0)
    {
        return $this->get_advisor_krs_list($lecturer_id, 'MENUNGGU');
    }

    /**
     * Mengambil daftar pengajuan KRS lengkap untuk Dosen Wali & Superadmin:
     * Disertai filter status, prodi, pencarian, dan analisa komprehensif (benturan jadwal, breakdown SKS, status SPP)
     */
    public function get_advisor_krs_list($lecturer_id = 0, $status = null, $prodi = null, $keyword = null)
    {
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan_checker');

        $qb = $this->db->select('krs.*, mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.prodi, mahasiswa.semester, mahasiswa.foto, dosen.nama_lengkap AS nama_dosen_wali, validator.nama_lengkap AS nama_validator')
                       ->from('krs')
                       ->join('akun AS mahasiswa', 'mahasiswa.id = krs.akun_id', 'inner')
                       ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'left')
                       ->join('akun AS dosen', 'dosen.id = dosen_wali.dosen_id', 'left')
                       ->join('akun AS validator', 'validator.id = krs.diproses_oleh', 'left')
                       ->where('mahasiswa.role', 3)
                       ->where('mahasiswa.deleted_at IS NULL', null, false);

        if ($lecturer_id > 0) {
            $qb->where('dosen_wali.dosen_id', (int)$lecturer_id);
        }

        if ($status && $status !== 'semua') {
            $qb->where('krs.status', strtoupper($status));
        }

        if ($prodi && $prodi !== 'all') {
            $qb->like('mahasiswa.prodi', $prodi);
        }

        if ($keyword && trim($keyword) !== '') {
            $kw = trim($keyword);
            $qb->group_start()
               ->like('mahasiswa.nama_lengkap', $kw)
               ->or_like('mahasiswa.nim', $kw)
               ->group_end();
        }

        $qb->order_by("FIELD(krs.status, 'MENUNGGU', 'DITOLAK', 'DISETUJUI', 'DRAFT')", '', false);
        $qb->order_by('krs.diajukan_at', 'DESC');
        $qb->order_by('krs.updated_at', 'DESC');

        $list = $qb->get()->result();

        // Rincian matakuliah, benturan jadwal, dan breakdown SKS per mahasiswa
        foreach ($list as $item) {
            $period = [
                'tahun_akademik' => $item->tahun_akademik,
                'semester_akademik' => $item->semester_akademik,
            ];
            $item->krs_detail = $this->get_krs($item->akun_id, $period);

            $courses = (!empty($item->krs_detail) && !empty($item->krs_detail->mata_kuliah))
                ? $item->krs_detail->mata_kuliah
                : [];

            // Analisis benturan jadwal
            $item->conflicts = self::detect_schedule_conflicts($courses);
            $item->has_conflict = !empty($item->conflicts);

            // Analisis komposisi beban SKS
            $sks_wajib = 0;
            $sks_jurusan = 0;
            $sks_luar = 0;
            $sks_umum = 0;
            foreach ($courses as $c) {
                $kat = $c->kategori_kontekstual ?? ($c->jenis_mk ?? 'Wajib');
                $sks = (int)($c->sks ?? 0);
                if ($kat === 'Wajib') $sks_wajib += $sks;
                elseif ($kat === 'Jurusan') $sks_jurusan += $sks;
                elseif ($kat === 'Luar Jurusan') $sks_luar += $sks;
                elseif ($kat === 'Umum') $sks_umum += $sks;
                else $sks_wajib += $sks;
            }
            $item->sks_wajib = $sks_wajib;
            $item->sks_jurusan = $sks_jurusan;
            $item->sks_luar = $sks_luar;
            $item->sks_umum = $sks_umum;

            // Status kelayakan pembayaran semester mahasiswa
            $item->status_keuangan = $this->M_keuangan_checker->cek_status_krs($item->akun_id);
        }

        return $list;
    }

    /**
     * Statistik validasi KRS untuk Dashboard & Tabs Dosen Wali
     */
    public function get_krs_validation_stats($lecturer_id = 0)
    {
        $lecturer_id = (int)$lecturer_id;
        $where_krs_lec = '';
        if ($lecturer_id > 0) {
            $where_krs_lec = ' AND dosen_wali.dosen_id = ' . $lecturer_id;
        }

        $sql_krs = "SELECT 
            COALESCE(SUM(CASE WHEN krs.status = 'MENUNGGU' THEN 1 ELSE 0 END), 0) AS pending,
            COALESCE(SUM(CASE WHEN krs.status = 'DISETUJUI' THEN 1 ELSE 0 END), 0) AS approved,
            COALESCE(SUM(CASE WHEN krs.status = 'DITOLAK' THEN 1 ELSE 0 END), 0) AS rejected
        FROM krs
        INNER JOIN akun AS mahasiswa ON mahasiswa.id = krs.akun_id
        LEFT JOIN dosen_wali ON dosen_wali.mahasiswa_id = krs.akun_id
        WHERE mahasiswa.role = 3 
          AND mahasiswa.deleted_at IS NULL" . $where_krs_lec;

        $res_krs = $this->db->query($sql_krs)->row();

        $where_stu_lec = '';
        if ($lecturer_id > 0) {
            $where_stu_lec = ' AND dosen_wali.dosen_id = ' . $lecturer_id;
        }

        $sql_students = "SELECT COUNT(DISTINCT dosen_wali.mahasiswa_id) AS total_students
        FROM dosen_wali
        INNER JOIN akun AS mahasiswa ON mahasiswa.id = dosen_wali.mahasiswa_id
        WHERE mahasiswa.role = 3 
          AND mahasiswa.deleted_at IS NULL" . $where_stu_lec;

        $res_students = $this->db->query($sql_students)->row();

        return [
            'pending' => (int)($res_krs->pending ?? 0),
            'approved' => (int)($res_krs->approved ?? 0),
            'rejected' => (int)($res_krs->rejected ?? 0),
            'total_students' => (int)($res_students->total_students ?? 0),
        ];
    }

    public function get_advisor_students($lecturer_id)
    {
        $period = $this->get_active_period();
        $qb = $this->db->select('mahasiswa.id, mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.prodi, mahasiswa.semester, krs.status AS status_krs, krs.total_sks, krs.tahun_akademik, krs.semester_akademik')
                       ->from('dosen_wali')
                       ->join('akun AS mahasiswa', 'mahasiswa.id = dosen_wali.mahasiswa_id', 'inner')
                       ->join(
                           'krs',
                           'krs.akun_id = mahasiswa.id'
                               . ' AND krs.tahun_akademik = ' . $this->db->escape($period['tahun_akademik'])
                               . ' AND krs.semester_akademik = ' . $this->db->escape($period['semester_akademik']),
                           'left',
                           false
                       )
                       ->where('mahasiswa.role', 3)
                       ->where('mahasiswa.deleted_at IS NULL', null, false);

        if ($lecturer_id > 0) {
            $qb->where('dosen_wali.dosen_id', (int)$lecturer_id);
        }

        return $qb->order_by('mahasiswa.nama_lengkap', 'ASC')->get()->result();
    }

    /**
     * Memproses keputusan validasi KRS oleh Dosen Wali / Administrator:
     * - setujui: mengubah status menjadi DISETUJUI
     * - tolak: mengubah status menjadi DITOLAK dengan catatan revisi
     * - batalkan: mengembalikan status dari disetujui/ditolak kembali ke antrean
     */
    public function decide_krs($krs_id, $lecturer_id, $action, $note = null, $is_admin = false)
    {
        $this->db->trans_begin();
        $qb = $this->db->select('krs.id, krs.akun_id, krs.status, krs.total_sks')
                       ->from('krs')
                       ->join('akun AS mahasiswa', 'mahasiswa.id = krs.akun_id', 'inner')
                       ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'left')
                       ->where('krs.id', (int)$krs_id)
                       ->where('mahasiswa.role', 3)
                       ->where('mahasiswa.deleted_at IS NULL', null, false);

        // Dosen hanya berwenang menyetujui mahasiswa bimbingan perwaliannya sendiri
        if (!$is_admin && $lecturer_id > 0) {
            $qb->where('dosen_wali.dosen_id', (int)$lecturer_id);
        }

        $krs = $qb->get()->row();

        if (!$krs) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Pengajuan KRS tidak ditemukan atau bukan kewenangan mahasiswa bimbingan Anda.'];
        }

        $has_courses = $this->db->where('krs_id', (int)$krs_id)->count_all_results('krs_detail');
        if ($has_courses < 1 && $action === 'setujui') {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'KRS tidak dapat disetujui karena belum memiliki mata kuliah terpilih.'];
        }

        $new_status = 'MENUNGGU';
        $catatan_final = null;

        if ($action === 'setujui') {
            $new_status = 'DISETUJUI';
            $catatan_final = trim((string)$note) ?: 'KRS telah diperiksa dan disetujui oleh Dosen Wali.';
        } elseif ($action === 'tolak') {
            $new_status = 'DITOLAK';
            $catatan_final = trim((string)$note);
            if ($catatan_final === '') {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Catatan revisi wajib diisi saat menolak/mengembalikan pengajuan KRS.'];
            }
        } elseif ($action === 'batalkan') {
            $new_status = 'MENUNGGU';
            $catatan_final = 'Status persetujuan dibatalkan dan dikembalikan untuk peninjauan ulang.';
        } else {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tindakan validasi KRS tidak dikenali.'];
        }

        $this->db->where('id', (int)$krs_id)
                 ->update('krs', [
                     'status' => $new_status,
                     'catatan' => $catatan_final,
                     'diproses_oleh' => (int)$lecturer_id,
                     'diproses_at' => date('Y-m-d H:i:s'),
                 ]);

        if (!$this->db->trans_status() || $this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Gagal memperbarui status KRS.'];
        }

        $this->db->trans_commit();

        $msg = 'Status KRS berhasil diperbarui.';
        if ($action === 'setujui') {
            $msg = 'KRS mahasiswa berhasil disetujui (divalidasi). Mahasiswa kini dapat mencetak FRS.';
        } elseif ($action === 'tolak') {
            $msg = 'KRS berhasil ditolak dan dikembalikan kepada mahasiswa dengan catatan revisi.';
        } elseif ($action === 'batalkan') {
            $msg = 'Persetujuan KRS berhasil dibatalkan dan dikembalikan ke antrean peninjauan.';
        }

        return ['success' => true, 'message' => $msg];
    }

    /**
     * =========================================================================
     * MODUL KARTU HASIL STUDI (KHS) & TRANSKRIP AKADEMIK
     * =========================================================================
     */

    /**
     * Seeder data KHS mahasiswa (Semester 1 paket, semester 2, 3, dst.)
     * Termasuk data presisi MOCHAMMAD ARTHUR ARIESTA TAMPI (241221009)
     */
    public function ensure_default_khs_data()
    {
        // 1. Sinkronisasi Arthur Tampi (241221009) berada di Semester 4 (karena KHS Semester 3 dari foto resmi sudah selesai)
        $this->db->where('nim', '241221009')->update('akun', ['semester' => 4]);

        // 2. Bersihkan SELURUH nilai KHS pada semester yang sedang berjalan (semester_mhs >= semester_aktif mahasiswa)
        // Mahasiswa di Semester S baru saja KRS-an / sedang kuliah, sehingga KHS HANYA ADA untuk Semester 1 s/d (S - 1)
        $all_students_db = $this->db->where('role', 3)->get('akun')->result();
        foreach ($all_students_db as $st_db) {
            $cur_active_smt = max(1, (int)($st_db->semester ?: 1));
            // Hapus rekaman nilai KHS untuk semester berjalan ke atas
            $this->db->where('akun_id', (int)$st_db->id)->where('semester_mhs >=', $cur_active_smt)->delete('khs_nilai');
        }

        // 3. Arthur Tampi (241221009) - D3 Sistem Informasi (Aktif Semester 4)
        // Nilai KHS selesai: Semester 1, 2, dan 3 (Total SKS ditempuh = 63 SKS)
        $arthur = $this->db->where('role', 3)->group_start()->where('nim', '241221009')->or_like('nama_lengkap', 'Arthur')->group_end()->get('akun')->row();
        if ($arthur) {
            $count = $this->db->where('akun_id', (int)$arthur->id)->count_all_results('khs_nilai');
            if ($count < 20) {
                $this->db->where('akun_id', (int)$arthur->id)->delete('khs_nilai');
                
                // Semester 3: 2025 / Genap (PERSIS SESUAI FOTO KHS USER - 23 SKS)
                $smt3_courses = [
                    ['kode_mk' => 'IF24KK42', 'nama_mk' => 'KECERDASAN BUATAN', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB41', 'nama_mk' => 'ANALISA DAN PERANCANGAN SISTEM INFORMASI', 'sks' => 4, 'nilai_huruf' => 'C+', 'nilai_indeks' => 2.5, 'kategori' => 'Cukup Baik', 'nxk' => 10.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB42', 'nama_mk' => 'PEMROGRAMAN PERANGKAT BERGERAK', 'sks' => 4, 'nilai_huruf' => 'C', 'nilai_indeks' => 2.0, 'kategori' => 'Cukup', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB43', 'nama_mk' => 'INTERAKSI MANUSIA & KOMPUTER', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB44', 'nama_mk' => 'KOMPUTER AKUNTANSI', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB45', 'nama_mk' => 'PEMROGRAMAN WEB LANJUT', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MU24PB41', 'nama_mk' => 'KEWIRAUSAHAAN II', 'sks' => 2, 'nilai_huruf' => 'C', 'nilai_indeks' => 2.0, 'kategori' => 'Cukup', 'nxk' => 4.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($smt3_courses as $c) {
                    $c['akun_id'] = (int)$arthur->id;
                    $c['tahun_akademik'] = '2025';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 3;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 2: 2024 / Genap (20 SKS)
                $smt2_courses = [
                    ['kode_mk' => 'MI20KB21', 'nama_mk' => 'STRUKTUR DATA & ALGORITMA', 'sks' => 4, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 14.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB22', 'nama_mk' => 'SISTEM BASIS DATA', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB23', 'nama_mk' => 'DESAIN GRAFIS & MULTIMEDIA', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB24', 'nama_mk' => 'PEMROGRAMAN WEB DASAR', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MI20KB25', 'nama_mk' => 'PENGANTAR SISTEM INFORMASI BISNIS', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'MU24PB21', 'nama_mk' => 'KEWIRAUSAHAAN I', 'sks' => 2, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 7.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($smt2_courses as $c) {
                    $c['akun_id'] = (int)$arthur->id;
                    $c['tahun_akademik'] = '2024';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 2;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 1: 2024 / Ganjil (Paket Semester 1 - 20 SKS)
                $smt1_courses = [
                    ['kode_mk' => 'MI20KB11', 'nama_mk' => 'ALGORITMA & PEMROGRAMAN DASAR', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MI20KB12', 'nama_mk' => 'PENGANTAR TEKNOLOGI INFORMASI', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MI20KB13', 'nama_mk' => 'MATEMATIKA DISKRIT', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MU24PB11', 'nama_mk' => 'PENDIDIKAN PANCASILA & KEWARGANEGARAAN', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MU24PB12', 'nama_mk' => 'BAHASA INDONESIA', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MU24PB13', 'nama_mk' => 'BAHASA INGGRIS TERAPAN', 'sks' => 2, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 6.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MU24PB14', 'nama_mk' => 'PENDIDIKAN AGAMA', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'MI20KB14', 'nama_mk' => 'APLIKASI PERKANTORAN & BISNIS', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                ];
                foreach ($smt1_courses as $c) {
                    $c['akun_id'] = (int)$arthur->id;
                    $c['tahun_akademik'] = '2024';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 1;
                    $this->db->insert('khs_nilai', $c);
                }
            }
        }

        // 4. Nabila Ayu Lestari (NIM 2004) - Semester 4 Aktif Teknik Informatika
        // Karena Nabila ada di Semester 4 (baru saja KRS-an), KHS yang ada HANYA Semester 1, 2, dan 3!
        // Total SKS ditempuh dari Semester 1 s/d 3 = 63 SKS. Semester 4 belum ada nilai di KHS.
        $nabila = $this->db->where('role', 3)->group_start()->where('nim', '2004')->or_like('nama_lengkap', 'Nabila')->group_end()->get('akun')->row();
        if ($nabila) {
            $cnt = $this->db->where('akun_id', (int)$nabila->id)->count_all_results('khs_nilai');
            if ($cnt < 22) {
                $this->db->where('akun_id', (int)$nabila->id)->delete('khs_nilai');

                // Semester 1: 2023 / Ganjil (Paket Semester 1 - 20 SKS)
                $n_smt1 = [
                    ['kode_mk' => 'TI-101', 'nama_mk' => 'Algoritma dan Pemrograman I', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-102', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-103', 'nama_mk' => 'Pengantar Teknologi Informasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-104', 'nama_mk' => 'Arsitektur Komputer & Logika Digital', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-101', 'nama_mk' => 'Pendidikan Pancasila & Kewarganegaraan', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-102', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-103', 'nama_mk' => 'Bahasa Inggris Teknik', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-104', 'nama_mk' => 'Pendidikan Agama Islam', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                ];
                foreach ($n_smt1 as $c) {
                    $c['akun_id'] = (int)$nabila->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 1;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 2: 2023 / Genap (21 SKS)
                $n_smt2 = [
                    ['kode_mk' => 'TI-201', 'nama_mk' => 'Algoritma dan Pemrograman II', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-202', 'nama_mk' => 'Struktur Data & Algoritma', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-203', 'nama_mk' => 'Sistem Basis Data', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-204', 'nama_mk' => 'Pemrograman Web Dasar', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-205', 'nama_mk' => 'Aljabar Linier & Matriks', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-206', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'KU-201', 'nama_mk' => 'Komunikasi Interpersonal & Etika Profesi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($n_smt2 as $c) {
                    $c['akun_id'] = (int)$nabila->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 2;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 3: 2024 / Ganjil (22 SKS) -> Total SKS s/d Smt 3 = 63 SKS!
                $n_smt3 = [
                    ['kode_mk' => 'TI-301', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-302', 'nama_mk' => 'Jaringan Komputer & Komunikasi Data', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-303', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-304', 'nama_mk' => 'Pemrograman Web Lanjut', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-305', 'nama_mk' => 'Analisis & Desain Sistem', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-306', 'nama_mk' => 'Interaksi Manusia & Komputer', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-307', 'nama_mk' => 'Statistika & Probabilitas Terapan', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($n_smt3 as $c) {
                    $c['akun_id'] = (int)$nabila->id;
                    $c['tahun_akademik'] = '2024';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 3;
                    $this->db->insert('khs_nilai', $c);
                }
            }
        }

        // 5. Salsabila Putri Ramadhani (NIM 2002) - Semester 2 Aktif Teknik Informatika
        // Karena Salsabila di Semester 2 (baru saja KRS-an), KHS yang ada HANYA Semester 1 (Paket 20 SKS)
        $salsabila = $this->db->where('role', 3)->group_start()->where('nim', '2002')->or_like('nama_lengkap', 'Salsabila')->group_end()->get('akun')->row();
        if ($salsabila) {
            $cnt = $this->db->where('akun_id', (int)$salsabila->id)->count_all_results('khs_nilai');
            if ($cnt < 8) {
                $this->db->where('akun_id', (int)$salsabila->id)->delete('khs_nilai');
                
                // Semester 1: Paket 20 SKS
                $s_smt1 = [
                    ['kode_mk' => 'TI-101', 'nama_mk' => 'Algoritma dan Pemrograman I', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-102', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-103', 'nama_mk' => 'Pengantar Teknologi Informasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-104', 'nama_mk' => 'Arsitektur Komputer & Logika Digital', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-101', 'nama_mk' => 'Pendidikan Pancasila & Kewarganegaraan', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-102', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-103', 'nama_mk' => 'Bahasa Inggris Teknik', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-104', 'nama_mk' => 'Pendidikan Agama Islam', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                ];
                foreach ($s_smt1 as $c) {
                    $c['akun_id'] = (int)$salsabila->id;
                    $c['tahun_akademik'] = '2024';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 1;
                    $this->db->insert('khs_nilai', $c);
                }
            }
        }

        // 6. Rizky Aditya Saputra (NIM 2003) - Semester 3 Aktif Sistem Informasi
        // Karena Rizky di Semester 3 (baru saja KRS-an), KHS yang ada HANYA Semester 1 dan 2!
        // Total SKS ditempuh dari Semester 1 s/d 2 = 41 SKS. Semester 3 belum ada nilai di KHS.
        $rizky = $this->db->where('role', 3)->group_start()->where('nim', '2003')->or_like('nama_lengkap', 'Rizky')->group_end()->get('akun')->row();
        if ($rizky) {
            $cnt = $this->db->where('akun_id', (int)$rizky->id)->count_all_results('khs_nilai');
            if ($cnt < 13) {
                $this->db->where('akun_id', (int)$rizky->id)->delete('khs_nilai');
                
                // Semester 1: Paket (20 SKS)
                $r_smt1 = [
                    ['kode_mk' => 'SI-101', 'nama_mk' => 'Pengantar Sistem Informasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'SI-102', 'nama_mk' => 'Dasar Pemrograman & Logika', 'sks' => 4, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 14.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-101', 'nama_mk' => 'Pendidikan Pancasila & Kewarganegaraan', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-102', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'SI-103', 'nama_mk' => 'Matematika Bisnis', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'SI-104', 'nama_mk' => 'Manajemen & Organisasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-104', 'nama_mk' => 'Pendidikan Agama Islam', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                ];
                foreach ($r_smt1 as $c) {
                    $c['akun_id'] = (int)$rizky->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 1;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 2: 21 SKS (Total Smt 1-2 = 41 SKS)
                $r_smt2 = [
                    ['kode_mk' => 'SI-201', 'nama_mk' => 'Basis Data & SQL Lanjutan', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'SI-202', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 4, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 14.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'SI-203', 'nama_mk' => 'Statistika Bisnis Terapan', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'SI-204', 'nama_mk' => 'Desain Antarmuka Pengguna (UI/UX)', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'SI-205', 'nama_mk' => 'Komunikasi Bisnis & Interpersonal', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'KU-202', 'nama_mk' => 'Kewirausahaan I', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($r_smt2 as $c) {
                    $c['akun_id'] = (int)$rizky->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 2;
                    $this->db->insert('khs_nilai', $c);
                }
            }
        }

        // 7. Daffa Maulana Firmansyah (NIM 2005) - Semester 5 Aktif Teknik Informatika
        // Karena Daffa di Semester 5 (baru saja KRS-an), KHS yang ada HANYA Semester 1, 2, 3, dan 4!
        // Total SKS ditempuh dari Semester 1 s/d 4 = 85 SKS. Semester 5 belum ada nilai di KHS.
        $daffa = $this->db->where('role', 3)->group_start()->where('nim', '2005')->or_like('nama_lengkap', 'Daffa')->group_end()->get('akun')->row();
        if ($daffa) {
            $cnt = $this->db->where('akun_id', (int)$daffa->id)->count_all_results('khs_nilai');
            if ($cnt < 28) {
                $this->db->where('akun_id', (int)$daffa->id)->delete('khs_nilai');

                // Semester 1 (2022/Ganjil - Paket, 20 SKS)
                $d_smt1 = [
                    ['kode_mk' => 'TI-101', 'nama_mk' => 'Algoritma dan Pemrograman I', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-102', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-103', 'nama_mk' => 'Pengantar Teknologi Informasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'TI-104', 'nama_mk' => 'Arsitektur Komputer', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-101', 'nama_mk' => 'Pendidikan Pancasila', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-102', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-103', 'nama_mk' => 'Bahasa Inggris Teknik', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                    ['kode_mk' => 'KU-104', 'nama_mk' => 'Pendidikan Agama Islam', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                ];
                foreach ($d_smt1 as $c) {
                    $c['akun_id'] = (int)$daffa->id;
                    $c['tahun_akademik'] = '2022';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 1;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 2 (2022/Genap, 21 SKS)
                $d_smt2 = [
                    ['kode_mk' => 'TI-201', 'nama_mk' => 'Algoritma dan Pemrograman II', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-202', 'nama_mk' => 'Struktur Data & Algoritma', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-203', 'nama_mk' => 'Sistem Basis Data', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-204', 'nama_mk' => 'Pemrograman Web Dasar', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-205', 'nama_mk' => 'Aljabar Linier & Matriks', 'sks' => 3, 'nilai_huruf' => 'B', 'nilai_indeks' => 3.0, 'kategori' => 'Baik', 'nxk' => 9.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-206', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'KU-201', 'nama_mk' => 'Komunikasi Interpersonal', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($d_smt2 as $c) {
                    $c['akun_id'] = (int)$daffa->id;
                    $c['tahun_akademik'] = '2022';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 2;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 3 (2023/Ganjil, 22 SKS)
                $d_smt3 = [
                    ['kode_mk' => 'TI-301', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-302', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-303', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-304', 'nama_mk' => 'Pemrograman Web Lanjut', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-305', 'nama_mk' => 'Analisis & Desain Sistem', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-306', 'nama_mk' => 'Interaksi Manusia Komputer', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-307', 'nama_mk' => 'Statistika & Probabilitas', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($d_smt3 as $c) {
                    $c['akun_id'] = (int)$daffa->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Ganjil';
                    $c['semester_mhs'] = 3;
                    $this->db->insert('khs_nilai', $c);
                }

                // Semester 4 (2023/Genap, 22 SKS)
                $d_smt4 = [
                    ['kode_mk' => 'TI-401', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-402', 'nama_mk' => 'Pemrograman Mobile', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-403', 'nama_mk' => 'Keamanan Informasi', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-404', 'nama_mk' => 'Data Mining Terapan', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-405', 'nama_mk' => 'Cloud Computing', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'TI-406', 'nama_mk' => 'Manajemen Proyek TI', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                    ['kode_mk' => 'KU-401', 'nama_mk' => 'Kewirausahaan Berbasis Teknologi', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                ];
                foreach ($d_smt4 as $c) {
                    $c['akun_id'] = (int)$daffa->id;
                    $c['tahun_akademik'] = '2023';
                    $c['semester_akademik'] = 'Genap';
                    $c['semester_mhs'] = 4;
                    $this->db->insert('khs_nilai', $c);
                }
            }
        }

        // 8. Loop Otomatis untuk Seluruh Mahasiswa Aktif Lainnya (Role = 3)
        // Hanya membuat KHS untuk semester yang telah selesai ditempuh (s < semester_aktif)
        $all_students = $this->db->where('role', 3)->get('akun')->result();
        foreach ($all_students as $st) {
            $student_id = (int)$st->id;
            $active_smt = max(1, (int)($st->semester ?: 1));

            // Jika mahasiswa di semester 1, belum ada semester sebelumnya yang selesai
            if ($active_smt <= 1) {
                continue;
            }

            // Generate riwayat KHS HANYA untuk semester yang sudah selesai (s = 1 s/d active_smt - 1)
            for ($s = 1; $s < $active_smt; $s++) {
                $has_data = $this->db->where('akun_id', $student_id)->where('semester_mhs', $s)->count_all_results('khs_nilai');
                if ($has_data < 4) {
                    $thn = 2024 - (int)ceil(($active_smt - $s) / 2);
                    $sem_akademik = ($s % 2 === 1) ? 'Ganjil' : 'Genap';

                    $dummy_courses = [];
                    if ($s === 1) {
                        $dummy_courses = [
                            ['kode_mk' => 'MK-101', 'nama_mk' => 'Algoritma & Pemrograman Dasar', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'MK-102', 'nama_mk' => 'Matematika Komputasi', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'MK-103', 'nama_mk' => 'Pengantar Ilmu Komputer & TI', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'KU-101', 'nama_mk' => 'Pancasila & Kewarganegaraan', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'KU-102', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'KU-103', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'KU-104', 'nama_mk' => 'Pendidikan Agama', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                            ['kode_mk' => 'MK-104', 'nama_mk' => 'Logika & Keterampilan Komputer', 'sks' => 2, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 8.0, 'keterangan' => '1', 'is_paket' => 1],
                        ];
                    } elseif ($s === 2) {
                        $dummy_courses = [
                            ['kode_mk' => 'MK-201', 'nama_mk' => 'Struktur Data & Kompleksitas Algoritma', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-202', 'nama_mk' => 'Sistem Manajemen Basis Data', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-203', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 4, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 14.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-204', 'nama_mk' => 'Desain Antarmuka & UX', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-205', 'nama_mk' => 'Sistem Operasi Komputer', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'KU-201', 'nama_mk' => 'Kewirausahaan Dasar', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                        ];
                    } elseif ($s === 3) {
                        $dummy_courses = [
                            ['kode_mk' => 'MK-301', 'nama_mk' => 'Pemrograman Aplikasi Berbasis Web', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-302', 'nama_mk' => 'Jaringan Komputer & Protokol', 'sks' => 4, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 14.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-303', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-304', 'nama_mk' => 'Analisis Kebutuhan Sistem Informasi', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-305', 'nama_mk' => 'Statistika Terapan & Probabilitas', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-306', 'nama_mk' => 'Etika Profesi & Hukum Siber', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                        ];
                    } elseif ($s === 4) {
                        $dummy_courses = [
                            ['kode_mk' => 'MK-401', 'nama_mk' => 'Kecerdasan Komputasi & AI', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-402', 'nama_mk' => 'Pengembangan Aplikasi Mobile', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-403', 'nama_mk' => 'Keamanan Sistem & Siber', 'sks' => 3, 'nilai_huruf' => 'B+', 'nilai_indeks' => 3.5, 'kategori' => 'Sangat Baik', 'nxk' => 10.5, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-404', 'nama_mk' => 'Cloud Architecture & Service', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'MK-405', 'nama_mk' => 'Manajemen Proyek TI Modern', 'sks' => 4, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 16.0, 'keterangan' => '1', 'is_paket' => 0],
                            ['kode_mk' => 'KU-401', 'nama_mk' => 'Technopreneurship Lanjut', 'sks' => 3, 'nilai_huruf' => 'A', 'nilai_indeks' => 4.0, 'kategori' => 'Memuaskan', 'nxk' => 12.0, 'keterangan' => '1', 'is_paket' => 0],
                        ];
                    }

                    foreach ($dummy_courses as $c) {
                        $c['akun_id'] = $student_id;
                        $c['tahun_akademik'] = (string)$thn;
                        $c['semester_akademik'] = $sem_akademik;
                        $c['semester_mhs'] = $s;
                        $this->db->insert('khs_nilai', $c);
                    }
                }
            }
        }
    }

    /**
     * Mengambil daftar semester KHS yang tersedia untuk seorang mahasiswa
     */
    public function get_student_khs_semesters($akun_id)
    {
        $rows = $this->db->select('tahun_akademik, semester_akademik, semester_mhs, MAX(is_paket) as is_paket')
                         ->where('akun_id', (int)$akun_id)
                         ->group_by(['semester_mhs', 'tahun_akademik', 'semester_akademik'])
                         ->order_by('semester_mhs', 'DESC')
                         ->get('khs_nilai')
                         ->result();

        $periods = [];
        foreach ($rows as $r) {
            $key = 'smt_' . $r->semester_mhs;
            $label = 'Semester ' . $r->semester_mhs . ' (' . $r->tahun_akademik . ' / ' . $r->semester_akademik . ')';
            $periods[] = [
                'key' => $key,
                'label' => $label,
                'tahun_akademik' => $r->tahun_akademik,
                'semester_akademik' => $r->semester_akademik,
                'semester_mhs' => (int)$r->semester_mhs,
                'is_paket' => (bool)$r->is_paket
            ];
        }
        return $periods;
    }

    /**
     * Mengambil data lengkap KHS mahasiswa (detail matkul, nilai, indeks, IP, beban max, & statistik kumulatif)
     */
    public function get_student_khs_data($akun_id, $selected_period = null, $jenis = 'khs')
    {
        $account = $this->get_account($akun_id);
        if (!$account) {
            return null;
        }

        // Ambil data dosen wali
        $advisor = $this->get_advisor_for_student($akun_id);
        $account->nama_dosen_wali = $advisor ? $advisor->nama_lengkap : 'Sugeng Widodo, S.Kom., M.Kom';
        $account->nip_dosen_wali  = $advisor ? $advisor->nim : '-';

        // Normalisasi Jenjang
        if (empty($account->jenjang)) {
            $account->jenjang = ($account->prodi === 'Sistem Informasi' && ($account->nim === '241221009' || strpos($account->nama_lengkap, 'ARTHUR') !== false)) ? 'Diploma 3' : 'Strata 1';
        }

        // Ambil daftar semester/periode yang tersedia
        $available_periods = $this->get_student_khs_semesters($akun_id);
        if (empty($available_periods)) {
            return [
                'student' => $account,
                'available_periods' => [],
                'selected_period' => null,
                'courses' => [],
                'summary' => [
                    'jumlah_bobot' => 0,
                    'jumlah_sks' => 0,
                    'ip' => 0.00,
                    'beban_max' => 24,
                    'is_paket_semester' => false,
                    'sks_kumulatif_sd' => 0,
                    'ipk_kumulatif_sd' => 0.00,
                    'total_seluruh_sks' => 0,
                    'grand_ipk' => 0.00
                ]
            ];
        }

        // Tentukan periode yang dipilih
        $chosen_period = null;
        if (!empty($selected_period)) {
            foreach ($available_periods as $p) {
                if ($p['key'] === $selected_period || 
                    $p['label'] === $selected_period || 
                    (string)$p['semester_mhs'] === (string)$selected_period ||
                    ($p['tahun_akademik'] . '_' . strtolower($p['semester_akademik'])) === $selected_period) {
                    $chosen_period = $p;
                    break;
                }
            }
        }
        if (!$chosen_period) {
            $chosen_period = $available_periods[0]; // Periode semester terbaru (default)
        }

        // Query matkul KHS untuk periode tersebut (atau seluruh matkul jika mode transkrip)
        $this->db->where('akun_id', (int)$akun_id);
        if ($jenis === 'khs') {
            $this->db->where('semester_mhs', (int)$chosen_period['semester_mhs']);
        }
        $courses = $this->db->order_by('id', 'ASC')->get('khs_nilai')->result();

        // Hitung statistik untuk semester yang dipilih (atau seluruhnya jika transkrip)
        $jumlah_sks = 0;
        $jumlah_bobot = 0;
        $all_paket = count($courses) > 0;
        foreach ($courses as $c) {
            $jumlah_sks += (int)$c->sks;
            $jumlah_bobot += (float)$c->nxk;
            if (empty($c->is_paket) || (int)$c->is_paket !== 1) {
                $all_paket = false;
            }
        }
        $ip = $jumlah_sks > 0 ? round($jumlah_bobot / $jumlah_sks, 2) : 0.00;
        $beban_max = ($ip >= 3.00) ? 24 : (($ip >= 2.50) ? 22 : (($ip >= 2.00) ? 20 : 18));

        // Hitung kumulatif SAMPAI DENGAN semester yang dipilih (e.g. Smt 1 s/d 3 untuk Nabila = 63 SKS!)
        $target_smt = (int)$chosen_period['semester_mhs'];
        $kumulatif_sd_smt = $this->db->select('SUM(sks) AS tot_sks, SUM(nxk) AS tot_bobot')
                                     ->where('akun_id', (int)$akun_id)
                                     ->where('semester_mhs <=', $target_smt)
                                     ->get('khs_nilai')->row();
        $sks_kumulatif_sd = (int)($kumulatif_sd_smt->tot_sks ?? 0);
        $bobot_kumulatif_sd = (float)($kumulatif_sd_smt->tot_bobot ?? 0);
        $ipk_kumulatif_sd = $sks_kumulatif_sd > 0 ? round($bobot_kumulatif_sd / $sks_kumulatif_sd, 2) : $ip;

        // Hitung juga grand total seluruh semester yang tercatat
        $grand_res = $this->db->select('SUM(sks) AS tot_sks, SUM(nxk) AS tot_bobot')
                              ->where('akun_id', (int)$akun_id)
                              ->get('khs_nilai')->row();
        $grand_sks = (int)($grand_res->tot_sks ?? 0);
        $grand_bobot = (float)($grand_res->tot_bobot ?? 0);
        $grand_ipk = $grand_sks > 0 ? round($grand_bobot / $grand_sks, 2) : $ip;

        return [
            'student' => $account,
            'available_periods' => $available_periods,
            'selected_period' => $chosen_period,
            'courses' => $courses,
            'summary' => [
                'jumlah_bobot' => $jumlah_bobot,
                'jumlah_sks' => $jumlah_sks,
                'ip' => $ip,
                'beban_max' => $beban_max,
                'is_paket_semester' => $all_paket,
                'sks_kumulatif_sd' => $sks_kumulatif_sd,
                'ipk_kumulatif_sd' => $ipk_kumulatif_sd,
                'total_seluruh_sks' => $grand_sks,
                'grand_ipk' => $grand_ipk
            ]
        ];
    }

    /**
     * Mengambil seluruh mahasiswa untuk pemilih KHS (Superadmin / Dosen)
     */
    public function get_all_students_for_khs()
    {
        return $this->db->select('id, nim, nama_lengkap, prodi, jenjang, semester')
                        ->where('role', 3)
                        ->where('deleted_at IS NULL', null, false)
                        ->order_by('nama_lengkap', 'ASC')
                        ->get('akun')
                        ->result();
    }
}
