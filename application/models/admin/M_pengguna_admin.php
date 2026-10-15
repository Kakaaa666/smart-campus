<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models/shared/M_pengguna.php';

class M_pengguna_admin extends M_pengguna
{
    protected $table = 'akun_mahasiswa';
    protected $table_dosen = 'akun_biro';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_columns();
    }

    /**
     * Memastikan kolom-kolom penunjang data mahasiswa ada di tabel akun_mahasiswa (tanpa kolom role)
     */
    public function ensure_columns()
    {
        if (!$this->db->table_exists($this->table)) {
            return;
        }

        $fields = $this->db->list_fields($this->table);

        // Jika kolom role tidak sengaja ada, hapus
        if (in_array('role', $fields, true)) {
            try {
                $this->db->query("ALTER TABLE `{$this->table}` DROP COLUMN `role`");
            } catch (\Throwable $e) {}
        }

        $columns_to_add = [
            'prodi' => "ALTER TABLE `{$this->table}` ADD COLUMN `prodi` VARCHAR(100) NULL DEFAULT 'Teknik Informatika' AFTER `nama_lengkap`",
            'fakultas' => "ALTER TABLE `{$this->table}` ADD COLUMN `fakultas` VARCHAR(100) NULL DEFAULT 'Fakultas Ilmu Komputer' AFTER `prodi`",
            'jenjang' => "ALTER TABLE `{$this->table}` ADD COLUMN `jenjang` VARCHAR(50) NULL DEFAULT 'Strata 1' AFTER `fakultas`",
            'semester' => "ALTER TABLE `{$this->table}` ADD COLUMN `semester` INT(11) NOT NULL DEFAULT 1 AFTER `jenjang`",
            'status_akademik' => "ALTER TABLE `{$this->table}` ADD COLUMN `status_akademik` ENUM('Aktif','Cuti','Non-Aktif','Lulus','Drop Out') NOT NULL DEFAULT 'Aktif' AFTER `semester`",
            'angkatan' => "ALTER TABLE `{$this->table}` ADD COLUMN `angkatan` VARCHAR(4) NULL DEFAULT NULL AFTER `status_akademik`",
            'jenis_kelamin' => "ALTER TABLE `{$this->table}` ADD COLUMN `jenis_kelamin` ENUM('L','P') NULL DEFAULT 'L' AFTER `angkatan`",
            'ipk' => "ALTER TABLE `{$this->table}` ADD COLUMN `ipk` DECIMAL(3,2) NOT NULL DEFAULT 3.25 AFTER `jenis_kelamin`",
            'telepon' => "ALTER TABLE `{$this->table}` ADD COLUMN `telepon` VARCHAR(25) NULL DEFAULT NULL AFTER `ipk`",
            'alamat' => "ALTER TABLE `{$this->table}` ADD COLUMN `alamat` TEXT NULL DEFAULT NULL AFTER `telepon`",
        ];

        foreach ($columns_to_add as $col => $sql) {
            if (!in_array($col, $fields, true)) {
                try {
                    $this->db->query($sql);
                } catch (\Throwable $e) {}
            }
        }
    }

    /**
     * Mengambil daftar mahasiswa dengan filter prodi, semester, status, dan pencarian
     */
    public function get_mahasiswa_list($filter = [])
    {
        $this->db->select("
            akun_mahasiswa.id,
            akun_mahasiswa.nim,
            akun_mahasiswa.nama_lengkap,
            akun_mahasiswa.email,
            COALESCE(NULLIF(akun_mahasiswa.prodi, ''), 'Teknik Informatika') AS prodi,
            COALESCE(NULLIF(akun_mahasiswa.fakultas, ''), 'Fakultas Ilmu Komputer') AS fakultas,
            COALESCE(akun_mahasiswa.semester, 1) AS semester,
            COALESCE(NULLIF(akun_mahasiswa.status_akademik, ''), 'Aktif') AS status_akademik,
            akun_mahasiswa.angkatan,
            akun_mahasiswa.jenis_kelamin,
            akun_mahasiswa.ipk,
            akun_mahasiswa.telepon,
            akun_mahasiswa.alamat,
            akun_mahasiswa.foto,
            akun_mahasiswa.created_at,
            dosen_wali.dosen_id,
            dosen.nama_lengkap AS nama_dosen_wali,
            dosen.nim AS nidn_dosen_wali,
            krs.status AS status_krs,
            krs.total_sks AS krs_total_sks
        ");
        $this->db->from($this->table);
        $this->db->join('dosen_wali', 'dosen_wali.mahasiswa_id = akun_mahasiswa.id', 'left');
        $this->db->join("{$this->table_dosen} AS dosen", 'dosen.id = dosen_wali.dosen_id', 'left');
        
        // Ambil info KRS mahasiswa untuk periode berjalan (jika ada)
        $month = (int)date('n');
        $year = (int)date('Y');
        $sem_akademik = ($month >= 7 && $month <= 12) ? 'Ganjil' : 'Genap';
        $thn_akademik = $sem_akademik === 'Ganjil' ? $year . '/' . ($year + 1) : ($year - 1) . '/' . $year;

        $this->db->join(
            "krs",
            "krs.akun_id = akun_mahasiswa.id AND krs.tahun_akademik = '{$thn_akademik}' AND krs.semester_akademik = '{$sem_akademik}'",
            'left'
        );

        $this->db->where('akun_mahasiswa.deleted_at IS NULL', null, false);

        // Filter Program Studi (Prodi)
        if (!empty($filter['prodi'])) {
            $this->db->where('akun_mahasiswa.prodi', $filter['prodi']);
        }

        // Filter Semester
        if (!empty($filter['semester'])) {
            $this->db->where('akun_mahasiswa.semester', (int)$filter['semester']);
        }

        // Filter Status Akademik
        if (!empty($filter['status'])) {
            $this->db->where('akun_mahasiswa.status_akademik', $filter['status']);
        }

        // Filter Angkatan
        if (!empty($filter['angkatan'])) {
            $this->db->where('akun_mahasiswa.angkatan', $filter['angkatan']);
        }

        // Filter Pencarian (Keyword NIM / Nama / Email)
        if (!empty($filter['search'])) {
            $search = trim($filter['search']);
            $this->db->group_start();
            $this->db->like('akun_mahasiswa.nim', $search);
            $this->db->or_like('akun_mahasiswa.nama_lengkap', $search);
            $this->db->or_like('akun_mahasiswa.email', $search);
            $this->db->group_end();
        }

        // Urutkan berdasarkan Prodi, Semester, lalu Nama Lengkap
        $this->db->order_by('akun_mahasiswa.prodi', 'ASC');
        $this->db->order_by('akun_mahasiswa.semester', 'ASC');
        $this->db->order_by('akun_mahasiswa.nama_lengkap', 'ASC');

        $result = $this->db->get()->result();

        // Normalisasi data mahasiswa (misal angkatan diambil dari NIM jika masih kosong)
        foreach ($result as $mhs) {
            if (empty($mhs->angkatan)) {
                // Ambil 4 digit tahun dari awal NIM jika 4 digit berupa angka 20XX
                if (preg_match('/^(20\d{2})/', (string)$mhs->nim, $matches)) {
                    $mhs->angkatan = $matches[1];
                } else {
                    $mhs->angkatan = '2023';
                }
            }
        }

        return $result;
    }

    /**
     * Ambil daftar Program Studi yang unik di sistem
     */
    public function get_daftar_prodi()
    {
        $rows = $this->db->distinct()
            ->select('prodi')
            ->where('deleted_at IS NULL', null, false)
            ->where('prodi IS NOT NULL', null, false)
            ->where("prodi != ''", null, false)
            ->order_by('prodi', 'ASC')
            ->get($this->table)
            ->result_array();

        $prodi_db = array_filter(array_column($rows, 'prodi'));

        $defaults = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknologi Informasi',
            'Bisnis Digital',
            'Manajemen'
        ];

        $merged = array_unique(array_merge($prodi_db, $defaults));
        sort($merged);
        return $merged;
    }

    /**
     * Ambil daftar Semester yang tersedia (1 - 8 atau lebih)
     */
    public function get_daftar_semester()
    {
        $rows = $this->db->distinct()
            ->select('semester')
            ->where('deleted_at IS NULL', null, false)
            ->where('semester IS NOT NULL', null, false)
            ->order_by('semester', 'ASC')
            ->get($this->table)
            ->result_array();

        $sem_db = array_filter(array_column($rows, 'semester'));
        $defaults = [1, 2, 3, 4, 5, 6, 7, 8];
        $merged = array_unique(array_merge($sem_db, $defaults));
        sort($merged);
        return $merged;
    }

    /**
     * Ambil daftar Angkatan yang ada
     */
    public function get_daftar_angkatan()
    {
        $rows = $this->db->distinct()
            ->select('angkatan')
            ->where('deleted_at IS NULL', null, false)
            ->where('angkatan IS NOT NULL', null, false)
            ->where("angkatan != ''", null, false)
            ->order_by('angkatan', 'DESC')
            ->get($this->table)
            ->result_array();

        $angkatan_db = array_filter(array_column($rows, 'angkatan'));
        $defaults = ['2026', '2025', '2024', '2023', '2022', '2021', '2020'];
        $merged = array_unique(array_merge($angkatan_db, $defaults));
        rsort($merged);
        return $merged;
    }

    /**
     * Ringkasan Statistik untuk Card Dashboard Mahasiswa
     */
    public function get_summary_stats()
    {
        $total_mahasiswa = $this->db->where('deleted_at IS NULL', null, false)
            ->count_all_results($this->table);

        $total_aktif = $this->db->where('deleted_at IS NULL', null, false)
            ->group_start()
                ->where('status_akademik', 'Aktif')
                ->or_where('status_akademik IS NULL', null, false)
            ->group_end()
            ->count_all_results($this->table);

        $total_cuti = $this->db->where('deleted_at IS NULL', null, false)
            ->where('status_akademik', 'Cuti')
            ->count_all_results($this->table);

        $prodi_list = $this->get_daftar_prodi();
        $total_prodi = count($prodi_list);

        // Mahasiswa yang telah memiliki Dosen Wali
        $with_advisor = $this->db->from('dosen_wali')
            ->join('akun_mahasiswa', 'akun_mahasiswa.id = dosen_wali.mahasiswa_id', 'inner')
            ->where('akun_mahasiswa.deleted_at IS NULL', null, false)
            ->count_all_results();

        return [
            'total_mahasiswa' => $total_mahasiswa,
            'total_aktif'     => $total_aktif,
            'total_cuti'      => $total_cuti,
            'total_prodi'     => $total_prodi,
            'with_advisor'    => $with_advisor,
        ];
    }

    /**
     * Ambil data lengkap 1 mahasiswa berdasarkan ID
     */
    public function get_detail_mahasiswa($id)
    {
        $this->db->select("
            akun_mahasiswa.*,
            dosen_wali.dosen_id,
            dosen.nama_lengkap AS nama_dosen_wali,
            dosen.nim AS nidn_dosen_wali,
            dosen.email AS email_dosen_wali
        ");
        $this->db->from($this->table);
        $this->db->join('dosen_wali', 'dosen_wali.mahasiswa_id = akun_mahasiswa.id', 'left');
        $this->db->join("{$this->table_dosen} AS dosen", 'dosen.id = dosen_wali.dosen_id', 'left');
        $this->db->where('akun_mahasiswa.id', (int)$id);
        $this->db->where('akun_mahasiswa.deleted_at IS NULL', null, false);
        return $this->db->get()->row();
    }

    /**
     * Update data akademik mahasiswa
     */
    public function update_mahasiswa($id, $data)
    {
        $this->db->where('id', (int)$id);
        unset($data['role'], $data['biro']); // Hindari kolom role dan biro di akun_mahasiswa
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data);
    }
}
