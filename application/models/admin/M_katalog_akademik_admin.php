<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_katalog_akademik_admin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->schema_ready();
    }

    public function raw_table_exists($table)
    {
        $res = $this->db->query("SHOW TABLES LIKE " . $this->db->escape($table));
        return ($res && $res->num_rows() > 0);
    }

    public function schema_ready()
    {
        // 1. Eksekusi pembuatan skema tabel dan migrasi 001, 002, 003 jika belum lengkap
        $this->auto_migrate_schema();

        // 2. Bersihkan cache nama tabel dan kolom CodeIgniter
        if (isset($this->db->data_cache['table_names'])) {
            unset($this->db->data_cache['table_names']);
        }
        if (isset($this->db->data_cache['field_names'])) {
            unset($this->db->data_cache['field_names']);
        }

        // 3. Pastikan data awal terisi
        $this->ensure_initial_seeds();

        $ci =& get_instance();
        if (!isset($ci->M_akademik)) {
            $ci->load->model('shared/M_akademik', 'M_akademik');
        }
        if (isset($ci->M_akademik) && method_exists($ci->M_akademik, 'cleanup_duplicate_offerings')) {
            $ci->M_akademik->cleanup_duplicate_offerings();
        }

        // 4. Bersihkan cache lagi agar query builder CI mengenali semua tabel dan kolom
        if (isset($this->db->data_cache['table_names'])) {
            unset($this->db->data_cache['table_names']);
        }
        if (isset($this->db->data_cache['field_names'])) {
            unset($this->db->data_cache['field_names']);
        }

        foreach ([
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
            'prasyarat_mata_kuliah',
            'penugasan_dosen_prodi',
            'kurikulum_mahasiswa',
            'pemetaan_penawaran_mata_kuliah',
            'histori_katalog_akademik',
        ] as $table) {
            if (!$this->raw_table_exists($table)) {
                return false;
            }
        }

        $curriculum_fields = $this->db->list_fields('kurikulum_mata_kuliah');
        $prerequisite_fields = $this->db->list_fields('prasyarat_mata_kuliah');
        $offering_mapping_fields = $this->db->list_fields('pemetaan_penawaran_mata_kuliah');
        if (!in_array('pola_semester', $curriculum_fields, true)
            || !in_array('semester_akhir', $curriculum_fields, true)
            || !in_array('jenis_relasi', $prerequisite_fields, true)
            || !in_array('program_studi_id', $offering_mapping_fields, true)
            || !$this->raw_table_exists('penawaran_matakuliah')
            || !$this->raw_table_exists('krs_detail')) {
            return false;
        }

        return true;
    }

    public function auto_migrate_schema()
    {
        if (isset($this->db->data_cache['table_names'])) {
            unset($this->db->data_cache['table_names']);
        }
        if (isset($this->db->data_cache['field_names'])) {
            unset($this->db->data_cache['field_names']);
        }

        try {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        } catch (\Throwable $e) {}

        // 1. Cek jika tabel migrasi 001 (bahasa Inggris) sudah ada tetapi belum di-rename ke bahasa Indonesia (002)
        $this->run_migration_002_if_needed();

        // 2. Buat seluruh tabel skema akademik berbahasa Indonesia jika belum ada
        $this->create_indonesian_schema_direct();

        // 3. Jalankan migrasi 003 (data center kurikulum) jika kolom belum lengkap
        $this->run_migration_003_if_needed();

        try {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {}

        if (isset($this->db->data_cache['table_names'])) {
            unset($this->db->data_cache['table_names']);
        }
        if (isset($this->db->data_cache['field_names'])) {
            unset($this->db->data_cache['field_names']);
        }
    }

    protected function run_migration_002_if_needed()
    {
        $table_renames = [
            'academic_programs' => 'program_studi',
            'academic_lecturer_programs' => 'penugasan_dosen_prodi',
            'academic_curricula' => 'kurikulum',
            'academic_courses' => 'mata_kuliah',
            'academic_curriculum_courses' => 'kurikulum_mata_kuliah',
            'academic_course_prerequisites' => 'prasyarat_mata_kuliah',
            'academic_student_curricula' => 'kurikulum_mahasiswa',
            'academic_offering_courses' => 'pemetaan_penawaran_mata_kuliah',
            'academic_rules' => 'aturan_akademik',
            'academic_rule_history' => 'histori_aturan_akademik',
            'academic_catalog_history' => 'histori_katalog_akademik',
            'academic_krs_validation_history' => 'histori_validasi_krs',
            'academic_krs_detail_snapshots' => 'snapshot_detail_krs',
        ];

        foreach ($table_renames as $old_name => $new_name) {
            if ($this->raw_table_exists($old_name) && !$this->raw_table_exists($new_name)) {
                try {
                    $this->db->query("RENAME TABLE `{$old_name}` TO `{$new_name}`");
                } catch (\Throwable $e) {}
            }
        }

        if ($this->raw_table_exists('program_studi') && $this->db->field_exists('code', 'program_studi')) {
            $alter_statements = [
                "ALTER TABLE `program_studi` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `faculty` `fakultas` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `degree_level` `jenjang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `program_studi` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `program_studi` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `lecturer_id` `dosen_id` int(11) NOT NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `program_id` `program_studi_id` int(11) NOT NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `assignment_type` `jenis_penugasan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `assigned_by` `ditetapkan_oleh` int(11) NULL",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `program_id` `program_studi_id` int(11) NOT NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `cohort_start_year` `angkatan_mulai` smallint(5) unsigned NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `cohort_end_year` `angkatan_sampai` smallint(5) unsigned NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `kurikulum` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `description` `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `mata_kuliah` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `curriculum_id` `kurikulum_id` int(11) NOT NULL",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `course_id` `mata_kuliah_id` int(11) NOT NULL",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `recommended_semester` `semester_rekomendasi` tinyint(2) unsigned NULL",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `credits` `sks` tinyint(2) unsigned NOT NULL",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `course_group` `kelompok_mata_kuliah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `is_required` `wajib` tinyint(1) NOT NULL DEFAULT 1",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `curriculum_course_id` `kurikulum_mata_kuliah_id` int(11) NOT NULL",
                "ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `prerequisite_course_id` `prasyarat_mata_kuliah_id` int(11) NOT NULL",
                "ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `alternative_group` `kelompok_alternatif` smallint(5) unsigned NOT NULL DEFAULT 1",
                "ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `minimum_grade` `nilai_minimum` decimal(3,2) NULL",
                "ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `student_id` `mahasiswa_id` int(11) NOT NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `curriculum_id` `kurikulum_id` int(11) NOT NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `effective_from` `berlaku_mulai` date NOT NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `effective_until` `berlaku_sampai` date NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `changed_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `offering_id` `penawaran_id` int(11) NOT NULL",
                "ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `course_id` `mata_kuliah_id` int(11) NOT NULL",
                "ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL",
                "ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `code` `kode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `version` `versi` int(11) unsigned NOT NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `category` `kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `description` `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `scope_type` `jenis_cakupan` enum('GLOBAL','PROGRAM','CURRICULUM','COURSE','PERIOD') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'GLOBAL'",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `scope_id` `cakupan_id` int(11) NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `parameters_json` `parameter_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `effective_from` `berlaku_mulai` date NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `effective_until` `berlaku_sampai` date NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `aturan_akademik` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `rule_id` `aturan_id` int(11) NOT NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `version` `versi` int(11) unsigned NOT NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `before_json` `sebelum_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `after_json` `sesudah_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) NOT NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `changed_by` `diubah_oleh` int(11) NULL",
                "ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `changed_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `entity_type` `jenis_entitas` enum('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `entity_id` `id_entitas` int(11) NOT NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `action` `aksi` enum('CREATE','UPDATE','STATUS_CHANGE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `before_json` `sebelum_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `after_json` `sesudah_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) NOT NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `performed_by` `dilakukan_oleh` int(11) NULL",
                "ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `action` `aksi` enum('DRAFT','SUBMIT','APPROVE','REJECT','REVALIDATE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `result_json` `hasil_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `rule_versions_json` `versi_aturan_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `performed_by_staff_id` `dilakukan_oleh_staf_id` int(11) NULL",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `performed_by_student_id` `dilakukan_oleh_mahasiswa_id` int(11) NULL",
                "ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()",
                "ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `course_code` `kode_mata_kuliah` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `course_name` `nama_mata_kuliah` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
                "ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `credits` `sks` tinyint(2) unsigned NOT NULL",
                "ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()"
            ];

            foreach ($alter_statements as $sql) {
                try {
                    $this->db->query($sql);
                } catch (\Throwable $e) {}
            }
        }
    }

    protected function run_migration_003_if_needed()
    {
        // 1. kurikulum_mata_kuliah: pola_semester & semester_akhir
        if ($this->raw_table_exists('kurikulum_mata_kuliah')) {
            $fields = $this->db->list_fields('kurikulum_mata_kuliah');
            if (!in_array('pola_semester', $fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `kurikulum_mata_kuliah` ADD COLUMN `pola_semester` ENUM('SEMUA','GANJIL','GENAP') NOT NULL DEFAULT 'SEMUA' AFTER `semester_rekomendasi`");
                } catch (\Throwable $e) {}
            }
            if (!in_array('semester_akhir', $fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `kurikulum_mata_kuliah` ADD COLUMN `semester_akhir` TINYINT(1) NOT NULL DEFAULT 0 AFTER `pola_semester`");
                } catch (\Throwable $e) {}
            }
        }

        // 2. prasyarat_mata_kuliah: jenis_relasi
        if ($this->raw_table_exists('prasyarat_mata_kuliah')) {
            $fields = $this->db->list_fields('prasyarat_mata_kuliah');
            if (!in_array('jenis_relasi', $fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `prasyarat_mata_kuliah` ADD COLUMN `jenis_relasi` ENUM('PRASYARAT','KOREQUISITE') NOT NULL DEFAULT 'PRASYARAT' AFTER `prasyarat_mata_kuliah_id`");
                } catch (\Throwable $e) {}
            }
        }

        // 3. pemetaan_penawaran_mata_kuliah: program_studi_id
        if ($this->raw_table_exists('pemetaan_penawaran_mata_kuliah')) {
            $fields = $this->db->list_fields('pemetaan_penawaran_mata_kuliah');
            if (!in_array('program_studi_id', $fields, true)) {
                try {
                    $this->db->query("ALTER TABLE `pemetaan_penawaran_mata_kuliah` ADD COLUMN `program_studi_id` INT(11) NULL DEFAULT NULL AFTER `mata_kuliah_id`");
                } catch (\Throwable $e) {}
                try {
                    $this->db->query("ALTER TABLE `pemetaan_penawaran_mata_kuliah` ADD KEY `idx_pemetaan_penawaran_program` (`program_studi_id`)");
                } catch (\Throwable $e) {}
                if ($this->raw_table_exists('program_studi')) {
                    try {
                        $this->db->query("ALTER TABLE `pemetaan_penawaran_mata_kuliah` ADD CONSTRAINT `fk_pemetaan_penawaran_program` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT");
                    } catch (\Throwable $e) {}
                }
            }
        }

        // 4. histori_katalog_akademik: update jenis_entitas ENUM
        if ($this->raw_table_exists('histori_katalog_akademik')) {
            try {
                $this->db->query("ALTER TABLE `histori_katalog_akademik` MODIFY COLUMN `jenis_entitas` ENUM('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM','OFFERING') NOT NULL");
            } catch (\Throwable $e) {}
        }
    }

    public function create_indonesian_schema_direct()
    {
        $ddl_list = [
            // 1. program_studi
            "CREATE TABLE IF NOT EXISTS `program_studi` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `kode` VARCHAR(32) NULL DEFAULT NULL,
                `nama` VARCHAR(150) NOT NULL,
                `fakultas` VARCHAR(150) NULL DEFAULT NULL,
                `jenjang` VARCHAR(50) NULL DEFAULT NULL,
                `status` ENUM('PENDING_REVIEW','ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
                `berlaku_mulai` DATE NULL DEFAULT NULL,
                `berlaku_sampai` DATE NULL DEFAULT NULL,
                `dibuat_oleh` INT(11) NULL DEFAULT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_program_studi_kode` (`kode`),
                KEY `idx_program_studi_nama` (`nama`),
                KEY `idx_program_studi_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 2. penugasan_dosen_prodi
            "CREATE TABLE IF NOT EXISTS `penugasan_dosen_prodi` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `dosen_id` INT(11) NOT NULL,
                `program_studi_id` INT(11) NOT NULL,
                `jenis_penugasan` VARCHAR(50) NOT NULL DEFAULT 'HOMELINK_PRODI',
                `status` ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
                `berlaku_mulai` DATE NULL DEFAULT NULL,
                `berlaku_sampai` DATE NULL DEFAULT NULL,
                `ditetapkan_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_penugasan_dosen_prodi_active` (`dosen_id`,`program_studi_id`,`status`),
                KEY `idx_penugasan_dosen_prodi_dates` (`berlaku_mulai`,`berlaku_sampai`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 3. kurikulum
            "CREATE TABLE IF NOT EXISTS `kurikulum` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `program_studi_id` INT(11) NOT NULL,
                `kode` VARCHAR(32) NOT NULL,
                `nama` VARCHAR(150) NOT NULL,
                `angkatan_mulai` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
                `angkatan_sampai` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
                `berlaku_mulai` DATE NULL DEFAULT NULL,
                `berlaku_sampai` DATE NULL DEFAULT NULL,
                `status` ENUM('DRAFT','ACTIVE','ARCHIVED') NOT NULL DEFAULT 'ACTIVE',
                `dibuat_oleh` INT(11) NULL DEFAULT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_kurikulum_program_kode` (`program_studi_id`,`kode`),
                KEY `idx_kurikulum_status` (`program_studi_id`,`status`,`angkatan_mulai`,`angkatan_sampai`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 4. mata_kuliah
            "CREATE TABLE IF NOT EXISTS `mata_kuliah` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `kode` VARCHAR(32) NOT NULL,
                `nama` VARCHAR(150) NOT NULL,
                `deskripsi` TEXT NULL DEFAULT NULL,
                `status` ENUM('PENDING_REVIEW','ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
                `dibuat_oleh` INT(11) NULL DEFAULT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_mata_kuliah_kode` (`kode`),
                KEY `idx_mata_kuliah_nama` (`nama`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 5. kurikulum_mata_kuliah
            "CREATE TABLE IF NOT EXISTS `kurikulum_mata_kuliah` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `kurikulum_id` INT(11) NOT NULL,
                `mata_kuliah_id` INT(11) NOT NULL,
                `semester_rekomendasi` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
                `pola_semester` ENUM('SEMUA','GANJIL','GENAP') NOT NULL DEFAULT 'SEMUA',
                `semester_akhir` TINYINT(1) NOT NULL DEFAULT 0,
                `sks` TINYINT(2) UNSIGNED NOT NULL DEFAULT 3,
                `kelompok_mata_kuliah` VARCHAR(50) NULL DEFAULT NULL,
                `wajib` TINYINT(1) NOT NULL DEFAULT 1,
                `status` ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_kurikulum_mata_kuliah` (`kurikulum_id`,`mata_kuliah_id`),
                KEY `idx_kurikulum_mata_kuliah_sem` (`kurikulum_id`,`semester_rekomendasi`,`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 6. prasyarat_mata_kuliah
            "CREATE TABLE IF NOT EXISTS `prasyarat_mata_kuliah` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `kurikulum_mata_kuliah_id` INT(11) NOT NULL,
                `prasyarat_mata_kuliah_id` INT(11) NOT NULL,
                `jenis_relasi` ENUM('PRASYARAT','KOREQUISITE') NOT NULL DEFAULT 'PRASYARAT',
                `kelompok_alternatif` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 1,
                `nilai_minimum` DECIMAL(3,2) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_prasyarat_mata_kuliah` (`kurikulum_mata_kuliah_id`,`prasyarat_mata_kuliah_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 7. kurikulum_mahasiswa
            "CREATE TABLE IF NOT EXISTS `kurikulum_mahasiswa` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `mahasiswa_id` INT(11) NOT NULL,
                `kurikulum_id` INT(11) NOT NULL,
                `berlaku_mulai` DATE NOT NULL,
                `berlaku_sampai` DATE NULL DEFAULT NULL,
                `alasan_perubahan` VARCHAR(500) NULL DEFAULT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_kurikulum_mahasiswa_effective` (`mahasiswa_id`,`berlaku_mulai`,`berlaku_sampai`),
                KEY `idx_kurikulum_mahasiswa_kurikulum` (`kurikulum_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 8. pemetaan_penawaran_mata_kuliah
            "CREATE TABLE IF NOT EXISTS `pemetaan_penawaran_mata_kuliah` (
                `penawaran_id` INT(11) NOT NULL,
                `mata_kuliah_id` INT(11) NOT NULL,
                `program_studi_id` INT(11) NULL DEFAULT NULL,
                `dibuat_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`penawaran_id`),
                KEY `idx_pemetaan_penawaran_course` (`mata_kuliah_id`),
                KEY `idx_pemetaan_penawaran_program` (`program_studi_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 9. aturan_akademik
            "CREATE TABLE IF NOT EXISTS `aturan_akademik` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `kode` VARCHAR(64) NOT NULL,
                `versi` INT(11) UNSIGNED NOT NULL DEFAULT 1,
                `nama` VARCHAR(150) NOT NULL,
                `kategori` VARCHAR(50) NOT NULL,
                `deskripsi` TEXT NULL DEFAULT NULL,
                `jenis_cakupan` ENUM('GLOBAL','PROGRAM','CURRICULUM','COURSE','PERIOD') NOT NULL DEFAULT 'GLOBAL',
                `cakupan_id` INT(11) NULL DEFAULT NULL,
                `parameter_json` LONGTEXT NOT NULL,
                `berlaku_mulai` DATE NULL DEFAULT NULL,
                `berlaku_sampai` DATE NULL DEFAULT NULL,
                `status` ENUM('DRAFT','ACTIVE','INACTIVE','NEEDS_REVIEW') NOT NULL DEFAULT 'DRAFT',
                `alasan_perubahan` VARCHAR(500) NULL DEFAULT NULL,
                `dibuat_oleh` INT(11) NULL DEFAULT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_aturan_akademik_kode_versi` (`kode`,`versi`),
                KEY `idx_aturan_akademik_status` (`status`,`kategori`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 10. histori_aturan_akademik
            "CREATE TABLE IF NOT EXISTS `histori_aturan_akademik` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `aturan_id` INT(11) NOT NULL,
                `versi` INT(11) UNSIGNED NOT NULL,
                `sebelum_json` LONGTEXT NULL DEFAULT NULL,
                `sesudah_json` LONGTEXT NOT NULL,
                `alasan_perubahan` VARCHAR(500) NOT NULL,
                `diubah_oleh` INT(11) NULL DEFAULT NULL,
                `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_histori_aturan_versi` (`aturan_id`,`versi`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 11. histori_katalog_akademik
            "CREATE TABLE IF NOT EXISTS `histori_katalog_akademik` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `jenis_entitas` ENUM('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM','OFFERING') NOT NULL,
                `id_entitas` INT(11) NOT NULL,
                `aksi` ENUM('CREATE','UPDATE','STATUS_CHANGE') NOT NULL,
                `sebelum_json` LONGTEXT NULL DEFAULT NULL,
                `sesudah_json` LONGTEXT NOT NULL,
                `alasan_perubahan` VARCHAR(500) NOT NULL,
                `dilakukan_oleh` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_histori_katalog_entitas` (`jenis_entitas`,`id_entitas`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 12. histori_validasi_krs
            "CREATE TABLE IF NOT EXISTS `histori_validasi_krs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `krs_id` INT(11) NOT NULL,
                `aksi` ENUM('DRAFT','SUBMIT','APPROVE','REJECT','REVALIDATE') NOT NULL,
                `hasil_json` LONGTEXT NOT NULL,
                `versi_aturan_json` LONGTEXT NOT NULL,
                `dilakukan_oleh_staf_id` INT(11) NULL DEFAULT NULL,
                `dilakukan_oleh_mahasiswa_id` INT(11) NULL DEFAULT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_histori_validasi_krs` (`krs_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 13. snapshot_detail_krs
            "CREATE TABLE IF NOT EXISTS `snapshot_detail_krs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `krs_detail_id` INT(11) NOT NULL,
                `kode_mata_kuliah` VARCHAR(32) NOT NULL,
                `nama_mata_kuliah` VARCHAR(150) NOT NULL,
                `sks` TINYINT(2) UNSIGNED NOT NULL,
                `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_snapshot_krs_detail` (`krs_detail_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 14. penawaran_matakuliah (jika belum dibuat modul akademik lain)
            "CREATE TABLE IF NOT EXISTS `penawaran_matakuliah` (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            // 15. krs_detail (jika belum dibuat modul akademik lain)
            "CREATE TABLE IF NOT EXISTS `krs_detail` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `krs_id` INT(11) NOT NULL,
                `penawaran_id` INT(11) NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_krs_detail_offering` (`krs_id`,`penawaran_id`),
                KEY `idx_krs_detail_penawaran` (`penawaran_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        ];

        foreach ($ddl_list as $sql) {
            try {
                $this->db->query($sql);
            } catch (\Throwable $e) {}
        }
    }

    public function ensure_initial_seeds()
    {
        if (!$this->raw_table_exists('program_studi') || !$this->raw_table_exists('mata_kuliah') || !$this->raw_table_exists('kurikulum')) {
            return;
        }

        // 1. Seed program_studi jika kosong
        $count_prodi = $this->db->count_all('program_studi');
        if ($count_prodi === 0) {
            if ($this->raw_table_exists('prodi') && $this->db->count_all('prodi') > 0) {
                $existing_prodi = $this->db->get('prodi')->result();
                foreach ($existing_prodi as $p) {
                    $this->db->insert('program_studi', [
                        'id' => (int)$p->id,
                        'kode' => !empty($p->kode_prodi) ? $p->kode_prodi : ('PR' . $p->id),
                        'nama' => $p->nama_prodi,
                        'fakultas' => !empty($p->fakultas) ? $p->fakultas : 'Fakultas Teknologi Informasi',
                        'jenjang' => 'Strata 1',
                        'status' => 'ACTIVE',
                        'berlaku_mulai' => '2020-01-01',
                        'berlaku_sampai' => '2030-12-31',
                        'dibuat_pada' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                $default_prodis = [
                    ['id' => 1, 'kode' => 'TI', 'nama' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknologi Informasi', 'jenjang' => 'Strata 1', 'status' => 'ACTIVE', 'berlaku_mulai' => '2020-01-01'],
                    ['id' => 2, 'kode' => 'SI', 'nama' => 'Sistem Informasi', 'fakultas' => 'Fakultas Teknologi Informasi', 'jenjang' => 'Strata 1', 'status' => 'ACTIVE', 'berlaku_mulai' => '2020-01-01'],
                    ['id' => 3, 'kode' => 'MI', 'nama' => 'Manajemen Informatika', 'fakultas' => 'Fakultas Teknologi Informasi', 'jenjang' => 'Diploma 3', 'status' => 'ACTIVE', 'berlaku_mulai' => '2020-01-01'],
                    ['id' => 4, 'kode' => 'AK', 'nama' => 'Akuntansi', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'jenjang' => 'Strata 1', 'status' => 'ACTIVE', 'berlaku_mulai' => '2020-01-01'],
                ];
                foreach ($default_prodis as $dp) {
                    $this->db->insert('program_studi', $dp);
                }
            }
        }

        // 2. Seed mata_kuliah jika kosong
        $count_courses = $this->db->count_all('mata_kuliah');
        if ($count_courses === 0) {
            if ($this->raw_table_exists('penawaran_matakuliah')) {
                $offerings = $this->db->select('kode_mk, nama_mk')
                                      ->group_by(['kode_mk', 'nama_mk'])
                                      ->get('penawaran_matakuliah')
                                      ->result();
                $inserted_codes = [];
                foreach ($offerings as $off) {
                    $code = trim((string)$off->kode_mk);
                    $name = trim((string)$off->nama_mk);
                    if ($code !== '' && !isset($inserted_codes[strtoupper($code)])) {
                        $this->db->insert('mata_kuliah', [
                            'kode' => $code,
                            'nama' => $name,
                            'status' => 'ACTIVE',
                            'dibuat_pada' => date('Y-m-d H:i:s'),
                        ]);
                        $inserted_codes[strtoupper($code)] = true;
                    }
                }
            }
        }

        if ($this->db->count_all('mata_kuliah') === 0) {
            $default_courses = [
                ['kode' => 'TIS101', 'nama' => 'Algoritma dan Pemrograman', 'status' => 'ACTIVE'],
                ['kode' => 'TIS102', 'nama' => 'Struktur Data & Algoritma', 'status' => 'ACTIVE'],
                ['kode' => 'TIS201', 'nama' => 'Sistem Basis Data', 'status' => 'ACTIVE'],
                ['kode' => 'TIS202', 'nama' => 'Pemrograman Web Lanjut', 'status' => 'ACTIVE'],
                ['kode' => 'TIS301', 'nama' => 'Rekayasa Perangkat Lunak', 'status' => 'ACTIVE'],
                ['kode' => 'TIS302', 'nama' => 'Jaringan Komputer & Keamanan', 'status' => 'ACTIVE'],
                ['kode' => 'TIS401', 'nama' => 'Kecerdasan Buatan & Machine Learning', 'status' => 'ACTIVE'],
                ['kode' => 'TIS402', 'nama' => 'Cloud Computing & DevOps', 'status' => 'ACTIVE'],
            ];
            foreach ($default_courses as $dc) {
                $this->db->insert('mata_kuliah', $dc);
            }
        }

        // 3. Pastikan kurikulum untuk tiap prodi aktif
        $active_programs = $this->db->where('status', 'ACTIVE')->get('program_studi')->result();
        foreach ($active_programs as $prog) {
            $existing_curr = $this->db->where('program_studi_id', $prog->id)->get('kurikulum')->row();
            if (!$existing_curr) {
                $curr_code = 'KUR-' . ($prog->kode ?: 'PRODI' . $prog->id) . '-2024';
                $this->db->insert('kurikulum', [
                    'program_studi_id' => $prog->id,
                    'kode' => $curr_code,
                    'nama' => 'Kurikulum ' . $prog->nama . ' 2024',
                    'angkatan_mulai' => 2020,
                    'angkatan_sampai' => 2028,
                    'berlaku_mulai' => '2024-01-01',
                    'berlaku_sampai' => '2028-12-31',
                    'status' => 'ACTIVE',
                    'dibuat_pada' => date('Y-m-d H:i:s'),
                ]);
                $kurikulum_id = (int)$this->db->insert_id();

                if ($this->raw_table_exists('kurikulum_mata_kuliah') && $kurikulum_id > 0) {
                    $courses = $this->db->limit(10)->get('mata_kuliah')->result();
                    $sem = 1;
                    foreach ($courses as $c) {
                        $this->db->insert('kurikulum_mata_kuliah', [
                            'kurikulum_id' => $kurikulum_id,
                            'mata_kuliah_id' => $c->id,
                            'semester_rekomendasi' => $sem,
                            'sks' => 3,
                            'kelompok_mata_kuliah' => 'Mata Kuliah Wajib Prodi',
                            'wajib' => 1,
                            'status' => 'ACTIVE',
                            'dibuat_pada' => date('Y-m-d H:i:s'),
                        ]);
                        $sem = ($sem % 8) + 1;
                    }
                }
            }
        }

        // 4. Hubungkan pemetaan_penawaran_mata_kuliah
        if ($this->raw_table_exists('pemetaan_penawaran_mata_kuliah') && $this->raw_table_exists('penawaran_matakuliah')) {
            $mapped_count = $this->db->count_all('pemetaan_penawaran_mata_kuliah');
            if ($mapped_count === 0) {
                $unmapped = $this->db->select('penawaran_matakuliah.id AS penawaran_id, mata_kuliah.id AS mk_id')
                                     ->from('penawaran_matakuliah')
                                     ->join('mata_kuliah', 'UPPER(TRIM(mata_kuliah.kode)) = UPPER(TRIM(penawaran_matakuliah.kode_mk))', 'inner')
                                     ->get()
                                     ->result();
                foreach ($unmapped as $um) {
                    $this->db->insert('pemetaan_penawaran_mata_kuliah', [
                        'penawaran_id' => $um->penawaran_id,
                        'mata_kuliah_id' => $um->mk_id,
                        'dibuat_pada' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 5. Hubungkan kurikulum_mahasiswa
        if ($this->raw_table_exists('kurikulum_mahasiswa') && $this->raw_table_exists('akun_mahasiswa')) {
            $assigned_students = $this->db->count_all('kurikulum_mahasiswa');
            if ($assigned_students === 0) {
                $students = $this->db->where('deleted_at IS NULL', null, false)->get('akun_mahasiswa')->result();
                foreach ($students as $st) {
                    $match_curr = $this->db->select('kurikulum.id')
                                           ->from('kurikulum')
                                           ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                                           ->where('program_studi.status', 'ACTIVE')
                                           ->where('kurikulum.status', 'ACTIVE')
                                           ->where('TRIM(LOWER(program_studi.nama))', strtolower(trim((string)$st->prodi)))
                                           ->get()
                                           ->row();
                    if ($match_curr) {
                        $this->db->insert('kurikulum_mahasiswa', [
                            'mahasiswa_id' => $st->id,
                            'kurikulum_id' => $match_curr->id,
                            'berlaku_mulai' => '2024-01-01',
                            'alasan_perubahan' => 'Penetapan kurikulum awal mahasiswa',
                            'dibuat_pada' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        // 6. Hubungkan penugasan dosen ke program studi
        if ($this->raw_table_exists('penugasan_dosen_prodi') && $this->raw_table_exists('akun_biro')) {
            $assigned_lecturers = $this->db->count_all('penugasan_dosen_prodi');
            if ($assigned_lecturers === 0) {
                $lecturers = $this->db->where('role', 4)->where('deleted_at IS NULL', null, false)->get('akun_biro')->result();
                $first_prodi = $this->db->where('status', 'ACTIVE')->get('program_studi')->row();
                if ($first_prodi) {
                    foreach ($lecturers as $lec) {
                        $pid = $first_prodi->id;
                        if (!empty($lec->prodi)) {
                            $find_p = $this->db->where('TRIM(LOWER(nama))', strtolower(trim((string)$lec->prodi)))->get('program_studi')->row();
                            if ($find_p) $pid = $find_p->id;
                        }
                        $this->db->insert('penugasan_dosen_prodi', [
                            'dosen_id' => $lec->id,
                            'program_studi_id' => $pid,
                            'jenis_penugasan' => 'HOMELINK_PRODI',
                            'status' => 'ACTIVE',
                            'berlaku_mulai' => '2024-01-01',
                            'dibuat_pada' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }
    }

    public function get_programs($limit, $offset)
    {
        return $this->db->order_by('nama', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get('program_studi')
                        ->result();
    }

    public function get_courses($limit, $offset)
    {
        return $this->db->order_by('kode', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get('mata_kuliah')
                        ->result();
    }

    public function get_curricula($limit, $offset)
    {
        return $this->db->select('kurikulum.*, program_studi.nama AS program_name')
                        ->from('kurikulum')
                        ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                        ->order_by('program_studi.nama', 'ASC')
                        ->order_by('kurikulum.kode', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_curriculum_courses($limit, $offset)
    {
        return $this->db->select('kurikulum_mata_kuliah.*, kurikulum.kode AS curriculum_code, mata_kuliah.kode AS kode_mata_kuliah, mata_kuliah.nama AS nama_mata_kuliah')
                        ->from('kurikulum_mata_kuliah')
                        ->join('kurikulum', 'kurikulum.id = kurikulum_mata_kuliah.kurikulum_id', 'inner')
                        ->join('mata_kuliah', 'mata_kuliah.id = kurikulum_mata_kuliah.mata_kuliah_id', 'inner')
                        ->order_by('kurikulum.kode', 'ASC')
                        ->order_by('kurikulum_mata_kuliah.semester_rekomendasi', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_curriculum_course_relations(array $mapping_ids)
    {
        $relations = [];
        $mapping_ids = array_values(array_filter(array_map('intval', $mapping_ids)));
        if (!$mapping_ids) {
            return $relations;
        }

        foreach ($mapping_ids as $mapping_id) {
            $relations[$mapping_id] = ['PRASYARAT' => [], 'KOREQUISITE' => []];
        }

        $rows = $this->db->select('prasyarat_mata_kuliah.kurikulum_mata_kuliah_id AS target_id, prasyarat_mata_kuliah.prasyarat_mata_kuliah_id AS source_id, prasyarat_mata_kuliah.jenis_relasi, prasyarat_mata_kuliah.nilai_minimum, source_course.kode AS source_code, source_course.nama AS source_name')
                         ->from('prasyarat_mata_kuliah')
                         ->join('kurikulum_mata_kuliah AS source_mapping', 'source_mapping.id = prasyarat_mata_kuliah.prasyarat_mata_kuliah_id', 'inner')
                         ->join('mata_kuliah AS source_course', 'source_course.id = source_mapping.mata_kuliah_id', 'inner')
                         ->where_in('prasyarat_mata_kuliah.kurikulum_mata_kuliah_id', $mapping_ids)
                         ->order_by('source_course.kode', 'ASC')
                         ->get()
                         ->result();
        foreach ($rows as $row) {
            if (isset($relations[(int)$row->target_id][$row->jenis_relasi])) {
                $relations[(int)$row->target_id][$row->jenis_relasi][] = $row;
            }
        }

        return $relations;
    }

    public function get_curriculum_course_options($curriculum_id, $exclude_mapping_id = 0)
    {
        $this->db->select('kurikulum_mata_kuliah.id, mata_kuliah.kode, mata_kuliah.nama, kurikulum_mata_kuliah.semester_rekomendasi, kurikulum_mata_kuliah.status')
                 ->from('kurikulum_mata_kuliah')
                 ->join('mata_kuliah', 'mata_kuliah.id = kurikulum_mata_kuliah.mata_kuliah_id', 'inner')
                 ->where('kurikulum_mata_kuliah.kurikulum_id', (int)$curriculum_id)
                 ->where('kurikulum_mata_kuliah.status', 'ACTIVE')
                 ->order_by('kurikulum_mata_kuliah.semester_rekomendasi', 'ASC')
                 ->order_by('mata_kuliah.kode', 'ASC');
        if ((int)$exclude_mapping_id > 0) {
            $this->db->where('kurikulum_mata_kuliah.id !=', (int)$exclude_mapping_id);
        }

        return $this->db->get()->result();
    }

    public function get_program_options()
    {
        return $this->db->order_by('nama', 'ASC')->get('program_studi')->result();
    }

    public function get_active_program_options()
    {
        return $this->db->where('status', 'ACTIVE')
                        ->order_by('nama', 'ASC')
                        ->get('program_studi')
                        ->result();
    }

    public function get_lecturer_program_assignments($limit, $offset)
    {
        return $this->db->select('penugasan_dosen_prodi.*, akun_biro.nim AS lecturer_nim, akun_biro.nama_lengkap AS lecturer_name, program_studi.kode AS program_code, program_studi.nama AS program_name')
                        ->from('penugasan_dosen_prodi')
                        ->join('akun_biro', 'akun_biro.id = penugasan_dosen_prodi.dosen_id', 'inner')
                        ->join('program_studi', 'program_studi.id = penugasan_dosen_prodi.program_studi_id', 'inner')
                        ->order_by('penugasan_dosen_prodi.status', 'ASC')
                        ->order_by('penugasan_dosen_prodi.diubah_pada', 'DESC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_lecturer_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, nim, nama_lengkap')
                 ->from('akun_biro')
                 ->where('role', 4)
                 ->where('deleted_at IS NULL', null, false)
                 ->group_start()
                 ->like('nim', $search)
                 ->or_like('nama_lengkap', $search)
                 ->group_end()
                 ->order_by('nama_lengkap', 'ASC')
                 ->limit(20);

        $results = $this->db->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, nim, nama_lengkap')
                                 ->where('id', (int)$selected_id)
                                 ->where('role', 4)
                                 ->where('deleted_at IS NULL', null, false)
                                 ->get('akun_biro')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $lecturer) {
                    if ((int)$lecturer->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }

        return $results;
    }

    public function get_student_curriculum_assignments($limit, $offset)
    {
        return $this->db->select('akun_mahasiswa.id AS mahasiswa_id, akun_mahasiswa.nim, akun_mahasiswa.nama_lengkap, akun_mahasiswa.prodi, kurikulum_mahasiswa.id AS assignment_id, kurikulum_mahasiswa.kurikulum_id, kurikulum_mahasiswa.berlaku_mulai, kurikulum_mahasiswa.berlaku_sampai, kurikulum_mahasiswa.alasan_perubahan, kurikulum_mahasiswa.dibuat_pada AS assigned_at, kurikulum.kode AS curriculum_code, kurikulum.nama AS curriculum_name')
                        ->from('kurikulum_mahasiswa')
                        ->join('akun_mahasiswa', 'akun_mahasiswa.id = kurikulum_mahasiswa.mahasiswa_id', 'inner')
                        ->join('kurikulum', 'kurikulum.id = kurikulum_mahasiswa.kurikulum_id', 'inner')
                        ->order_by('kurikulum_mahasiswa.dibuat_pada', 'DESC')
                        ->order_by('kurikulum_mahasiswa.id', 'DESC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function count_active_students()
    {
        return $this->db->where('deleted_at IS NULL', null, false)
                        ->count_all_results('akun_mahasiswa');
    }

    public function get_student_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, nim, nama_lengkap, prodi, angkatan')
                 ->from('akun_mahasiswa')
                 ->where('deleted_at IS NULL', null, false);
        if ($search !== '') {
            $this->db->group_start()
                     ->like('nim', $search)
                     ->or_like('nama_lengkap', $search)
                     ->or_like('prodi', $search)
                     ->group_end();
        }
        $results = $this->db->order_by('nama_lengkap', 'ASC')->limit(20)->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, nim, nama_lengkap, prodi, angkatan')
                                 ->where('id', (int)$selected_id)
                                 ->where('deleted_at IS NULL', null, false)
                                 ->get('akun_mahasiswa')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $student) {
                    if ((int)$student->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }

        return $results;
    }

    public function get_curricula_for_student($mahasiswa_id)
    {
        $student = $this->db->select('prodi')
                            ->where('id', (int)$mahasiswa_id)
                            ->where('deleted_at IS NULL', null, false)
                            ->get('akun_mahasiswa')
                            ->row();
        if (!$student || trim((string)$student->prodi) === '') {
            return [];
        }

        return $this->db->select('kurikulum.id, kurikulum.kode, kurikulum.nama, program_studi.nama AS program_name')
                        ->from('kurikulum')
                        ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                        ->where('kurikulum.status', 'ACTIVE')
                        ->where('LOWER(TRIM(program_studi.nama)) = LOWER(TRIM(' . $this->db->escape($student->prodi) . '))', null, false)
                        ->order_by('kurikulum.kode', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_offering_reconciliation($limit, $offset)
    {
        return $this->db->select('penawaran_matakuliah.id, penawaran_matakuliah.kode_mk, penawaran_matakuliah.nama_mk, penawaran_matakuliah.prodi, penawaran_matakuliah.tahun_akademik, penawaran_matakuliah.semester_akademik, penawaran_matakuliah.kelas, pemetaan_penawaran_mata_kuliah.mata_kuliah_id, linked_course.kode AS linked_course_code, linked_course.nama AS linked_course_name, exact_course.id AS candidate_course_id, exact_course.kode AS candidate_course_code, exact_course.nama AS candidate_course_name')
                        ->from('penawaran_matakuliah')
                        ->join('pemetaan_penawaran_mata_kuliah', 'pemetaan_penawaran_mata_kuliah.penawaran_id = penawaran_matakuliah.id', 'left')
                        ->join('mata_kuliah AS linked_course', 'linked_course.id = pemetaan_penawaran_mata_kuliah.mata_kuliah_id', 'left')
                        ->join('mata_kuliah AS exact_course', 'UPPER(TRIM(exact_course.kode)) = UPPER(TRIM(penawaran_matakuliah.kode_mk))', 'left', false)
                        ->order_by('penawaran_matakuliah.tahun_akademik', 'DESC')
                        ->order_by('penawaran_matakuliah.prodi', 'ASC')
                        ->order_by('penawaran_matakuliah.kode_mk', 'ASC')
                        ->order_by('penawaran_matakuliah.kelas', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_offering_reconciliation_counts()
    {
        $mapped = $this->db->from('pemetaan_penawaran_mata_kuliah')->count_all_results();
        $total = $this->db->count_all('penawaran_matakuliah');

        return [
            'total' => (int)$total,
            'mapped' => (int)$mapped,
            'unmapped' => max(0, (int)$total - (int)$mapped),
        ];
    }

    public function get_offerings($limit, $offset)
    {
        return $this->db->select('penawaran_matakuliah.*, pemetaan_penawaran_mata_kuliah.mata_kuliah_id, mata_kuliah.kode AS catalog_course_code, mata_kuliah.nama AS catalog_course_name, mapped_program.kode AS program_code, akun_biro.nama_lengkap AS lecturer_name')
                        ->from('penawaran_matakuliah')
                        ->join('pemetaan_penawaran_mata_kuliah', 'pemetaan_penawaran_mata_kuliah.penawaran_id = penawaran_matakuliah.id', 'left')
                        ->join('mata_kuliah', 'mata_kuliah.id = pemetaan_penawaran_mata_kuliah.mata_kuliah_id', 'left')
                        ->join('program_studi AS mapped_program', 'mapped_program.id = pemetaan_penawaran_mata_kuliah.program_studi_id', 'left')
                        ->join('akun_biro', 'akun_biro.id = penawaran_matakuliah.dosen_id', 'left')
                        ->order_by('penawaran_matakuliah.tahun_akademik', 'DESC')
                        ->order_by('penawaran_matakuliah.semester_akademik', 'ASC')
                        ->order_by('penawaran_matakuliah.prodi', 'ASC')
                        ->order_by('penawaran_matakuliah.kode_mk', 'ASC')
                        ->order_by('penawaran_matakuliah.kelas', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_offering_record($id)
    {
        return $this->db->select('penawaran_matakuliah.*, pemetaan_penawaran_mata_kuliah.mata_kuliah_id, COALESCE(pemetaan_penawaran_mata_kuliah.program_studi_id, legacy_program.id) AS program_studi_id', false)
                        ->from('penawaran_matakuliah')
                        ->join('pemetaan_penawaran_mata_kuliah', 'pemetaan_penawaran_mata_kuliah.penawaran_id = penawaran_matakuliah.id', 'left')
                        ->join('program_studi AS legacy_program', 'LOWER(TRIM(legacy_program.nama)) = LOWER(TRIM(penawaran_matakuliah.prodi))', 'left', false)
                        ->where('penawaran_matakuliah.id', (int)$id)
                        ->get()
                        ->row();
    }

    public function save_student_curriculum_assignment($mahasiswa_id, $kurikulum_id, $berlaku_mulai, $berlaku_sampai, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }
        $mahasiswa_id = (int)$mahasiswa_id;
        $kurikulum_id = (int)$kurikulum_id;
        $actor_id = (int)$actor_id;

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting student curriculum assignment');
            return ['success' => false, 'message' => 'Transaksi penetapan kurikulum tidak dapat dimulai.'];
        }

        $student_query = $this->db->query(
            'SELECT `id`, `prodi` FROM `akun_mahasiswa` WHERE `id` = ? AND `deleted_at` IS NULL FOR UPDATE',
            [$mahasiswa_id]
        );
        $curriculum_query = $this->db->query(
            'SELECT kurikulum.*, program_studi.nama AS program_name, program_studi.status AS program_status, program_studi.berlaku_mulai AS program_valid_from, program_studi.berlaku_sampai AS program_valid_until
             FROM kurikulum
             INNER JOIN program_studi ON program_studi.id = kurikulum.program_studi_id
             WHERE kurikulum.id = ? FOR UPDATE',
            [$kurikulum_id]
        );
        $student = $student_query ? $student_query->row() : null;
        $curriculum = $curriculum_query ? $curriculum_query->row() : null;
        if (!$student_query || !$curriculum_query) {
            $this->log_database_error('locking student and curriculum');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Data mahasiswa atau kurikulum gagal dibaca.'];
        }
        if (!$student || !$curriculum
            || $curriculum->status !== 'ACTIVE'
            || $curriculum->program_status !== 'ACTIVE'
            || trim((string)$student->prodi) === ''
            || strcasecmp(trim((string)$student->prodi), trim((string)$curriculum->program_name)) !== 0) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Mahasiswa dan kurikulum harus berasal dari program studi yang sama dan masih aktif.'];
        }
        if (!empty($curriculum->berlaku_mulai) && $berlaku_mulai < $curriculum->berlaku_mulai) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada sebelum kurikulum mulai berlaku.'];
        }
        if (!empty($curriculum->berlaku_sampai) && $berlaku_mulai > $curriculum->berlaku_sampai) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada setelah masa berlaku kurikulum.'];
        }
        if (!empty($curriculum->berlaku_sampai)
            && ($berlaku_sampai === null || $berlaku_sampai > $curriculum->berlaku_sampai)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan tidak boleh melewati masa berlaku kurikulum.'];
        }
        if (!empty($curriculum->program_valid_from) && $berlaku_mulai < $curriculum->program_valid_from) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada sebelum masa berlaku program studi.'];
        }
        if (!empty($curriculum->program_valid_until)
            && ($berlaku_mulai > $curriculum->program_valid_until
                || $berlaku_sampai === null
                || $berlaku_sampai > $curriculum->program_valid_until)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan tidak boleh melewati masa berlaku program studi.'];
        }

        $overlap_sql = 'SELECT * FROM `kurikulum_mahasiswa`
                        WHERE `mahasiswa_id` = ?
                        AND (`berlaku_sampai` IS NULL OR `berlaku_sampai` >= ?)';
        $overlap_params = [$mahasiswa_id, $berlaku_mulai];
        if ($berlaku_sampai !== null) {
            $overlap_sql .= ' AND `berlaku_mulai` <= ?';
            $overlap_params[] = $berlaku_sampai;
        }
        $overlap_sql .= ' ORDER BY `berlaku_mulai` ASC FOR UPDATE';
        $overlap_query = $this->db->query($overlap_sql, $overlap_params);
        if (!$overlap_query) {
            $this->log_database_error('checking overlapping student curricula');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Histori kurikulum mahasiswa gagal dibaca.'];
        }

        $overlaps = $overlap_query->result();
        $previous_open_assignment = null;
        if (count($overlaps) === 1
            && $overlaps[0]->berlaku_sampai === null
            && $overlaps[0]->berlaku_mulai < $berlaku_mulai) {
            $previous_open_assignment = $overlaps[0];
        } elseif (count($overlaps) > 0) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan bertabrakan dengan histori kurikulum mahasiswa.'];
        }

        if ($previous_open_assignment) {
            $previous_end = date('Y-m-d', strtotime($berlaku_mulai . ' -1 day'));
            $sebelum_json = json_encode($previous_open_assignment, JSON_UNESCAPED_UNICODE);
            $after_previous = (array)$previous_open_assignment;
            $after_previous['berlaku_sampai'] = $previous_end;
            $sesudah_json = json_encode($after_previous, JSON_UNESCAPED_UNICODE);
            if ($sebelum_json === false || $sesudah_json === false
                || !$this->db->where('id', (int)$previous_open_assignment->id)
                             ->update('kurikulum_mahasiswa', ['berlaku_sampai' => $previous_end])) {
                $this->log_database_error('closing previous student curriculum assignment');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Penetapan kurikulum sebelumnya gagal ditutup.'];
            }
            $audit_saved = $this->db->insert('histori_katalog_akademik', [
                'jenis_entitas' => 'STUDENT_CURRICULUM',
                'id_entitas' => (int)$previous_open_assignment->id,
                'aksi' => 'UPDATE',
                'sebelum_json' => $sebelum_json,
                'sesudah_json' => $sesudah_json,
                'alasan_perubahan' => $reason,
                'dilakukan_oleh' => $actor_id,
            ]);
            if (!$audit_saved) {
                $this->log_database_error('auditing previous student curriculum assignment');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Perubahan histori kurikulum sebelumnya gagal dicatat; transaksi dibatalkan.'];
            }
        }

        $assignment = [
            'mahasiswa_id' => $mahasiswa_id,
            'kurikulum_id' => $kurikulum_id,
            'berlaku_mulai' => $berlaku_mulai,
            'berlaku_sampai' => $berlaku_sampai,
            'alasan_perubahan' => $reason,
            'diubah_oleh' => $actor_id,
        ];
        if (!$this->db->insert('kurikulum_mahasiswa', $assignment)) {
            $this->log_database_error('saving student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal disimpan.'];
        }
        $assignment_id = (int)$this->db->insert_id();
        $sesudah_json = json_encode($assignment, JSON_UNESCAPED_UNICODE);
        if ($sesudah_json === false) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot penetapan kurikulum mahasiswa: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal dicatat untuk audit.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'STUDENT_CURRICULUM',
            'id_entitas' => $assignment_id,
            'aksi' => 'CREATE',
            'sebelum_json' => null,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('auditing student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal dicatat ke histori audit; seluruh perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penetapan kurikulum gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Kurikulum mahasiswa berhasil ditetapkan dan dicatat dalam histori.'];
    }

    public function save_lecturer_program_assignment(array $data, $actor_id)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }

        $dosen_id = (int)$data['dosen_id'];
        $program_studi_id = (int)$data['program_studi_id'];
        $actor_id = (int)$actor_id;
        $lecturer = $this->db->select('id')
                             ->where('id', $dosen_id)
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->get('akun_biro')
                             ->row();
        $program = $this->db->select('id')
                            ->where('id', $program_studi_id)
                            ->where('status', 'ACTIVE')
                            ->get('program_studi')
                            ->row();
        if (!$lecturer || !$program) {
            return ['success' => false, 'message' => 'Pilih akun dosen aktif dan program studi berstatus aktif.'];
        }

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting lecturer program assignment');
            return ['success' => false, 'message' => 'Transaksi penugasan dosen tidak dapat dimulai.'];
        }

        $locked_lecturer = $this->db->query(
            'SELECT `id` FROM `akun_biro` WHERE `id` = ? AND `role` = 4 AND `deleted_at` IS NULL FOR UPDATE',
            [$dosen_id]
        );
        $locked_program = $this->db->query(
            'SELECT `id` FROM `program_studi` WHERE `id` = ? AND `status` = ? FOR UPDATE',
            [$program_studi_id, 'ACTIVE']
        );
        if (!$locked_lecturer || !$locked_lecturer->row() || !$locked_program || !$locked_program->row()) {
            if (!$locked_lecturer || !$locked_program) {
                $this->log_database_error('locking lecturer and program for assignment');
            }
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Dosen atau program studi tidak lagi aktif. Muat ulang data dan coba kembali.'];
        }

        $overlap = $this->db->where('dosen_id', $dosen_id)
                            ->where('program_studi_id', $program_studi_id)
                            ->where('jenis_penugasan', $data['jenis_penugasan'])
                            ->where('status', 'ACTIVE')
                            ->group_start()
                            ->where('berlaku_sampai IS NULL', null, false)
                            ->or_where('berlaku_sampai >=', $data['berlaku_mulai'] ?: '1000-01-01')
                            ->group_end()
                            ->group_start()
                            ->where('berlaku_mulai IS NULL', null, false)
                            ->or_where('berlaku_mulai <=', $data['berlaku_sampai'] ?: '9999-12-31')
                            ->group_end()
                            ->get('penugasan_dosen_prodi')
                            ->row();
        if ($overlap) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Sudah ada penugasan aktif dengan jenis dan periode yang bertabrakan.'];
        }

        $reason = $data['alasan_perubahan'];
        unset($data['alasan_perubahan']);
        $data['ditetapkan_oleh'] = $actor_id;
        $data['status'] = 'ACTIVE';
        $data['dibuat_pada'] = date('Y-m-d H:i:s');
        $saved = $this->db->insert('penugasan_dosen_prodi', $data);
        if (!$saved) {
            $this->log_database_error('saving lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan dosen gagal disimpan.'];
        }

        $assignment_id = (int)$this->db->insert_id();
        $sesudah_json = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($sesudah_json === false) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot penugasan dosen: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Penugasan gagal dicatat untuk audit.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'LECTURER_ASSIGNMENT',
            'id_entitas' => $assignment_id,
            'aksi' => 'CREATE',
            'sebelum_json' => null,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing lecturer program assignment audit');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan gagal dicatat ke histori audit; perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penugasan dosen gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Penugasan dosen berhasil disimpan.'];
    }

    public function deactivate_lecturer_program_assignment($id, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }
        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting lecturer assignment deactivation');
            return ['success' => false, 'message' => 'Transaksi penonaktifan penugasan tidak dapat dimulai.'];
        }

        $query = $this->db->query(
            'SELECT * FROM `penugasan_dosen_prodi` WHERE `id` = ? AND `status` = ? FOR UPDATE',
            [(int)$id, 'ACTIVE']
        );
        $before = $query ? $query->row() : null;
        if (!$query || !$before) {
            if (!$query) {
                $this->log_database_error('locking lecturer assignment for deactivation');
            }
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan aktif tidak ditemukan atau gagal dibaca.'];
        }

        $updated = $this->db->where('id', (int)$id)->update('penugasan_dosen_prodi', ['status' => 'INACTIVE']);
        $after = (array)$before;
        $after['status'] = 'INACTIVE';
        $sebelum_json = json_encode($before, JSON_UNESCAPED_UNICODE);
        $sesudah_json = json_encode($after, JSON_UNESCAPED_UNICODE);
        if (!$updated || $sebelum_json === false || $sesudah_json === false) {
            $this->log_database_error('deactivating lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan gagal dinonaktifkan atau snapshot audit gagal dibuat.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'LECTURER_ASSIGNMENT',
            'id_entitas' => (int)$id,
            'aksi' => 'STATUS_CHANGE',
            'sebelum_json' => $sebelum_json,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => (int)$actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing lecturer assignment deactivation audit');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penonaktifan gagal dicatat ke histori; perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing lecturer assignment deactivation');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penonaktifan penugasan gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Penugasan dosen berhasil dinonaktifkan.'];
    }

    public function get_course_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, kode, nama')
                 ->from('mata_kuliah')
                 ->order_by('kode', 'ASC');
        if ($search !== '') {
            $this->db->group_start()
                     ->like('kode', $search)
                     ->or_like('nama', $search)
                     ->group_end();
        }
        $query = $this->db->limit(20)->get();
        $results = $query->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, kode, nama')
                                 ->where('id', (int)$selected_id)
                                 ->get('mata_kuliah')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $course) {
                    if ((int)$course->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }
        return $results;
    }

    public function get_curriculum_options($search = '', $selected_id = 0)
    {
        $this->db->select('kurikulum.id, kurikulum.kode, program_studi.nama AS program_name')
                 ->from('kurikulum')
                 ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                 ->order_by('program_studi.nama', 'ASC')
                 ->order_by('kurikulum.kode', 'ASC');
        if ($search !== '') {
            $this->db->group_start()
                     ->like('kurikulum.kode', $search)
                     ->or_like('kurikulum.nama', $search)
                     ->or_like('program_studi.nama', $search)
                     ->group_end();
        }
        $results = $this->db->limit(20)->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('kurikulum.id, kurikulum.kode, program_studi.nama AS program_name')
                                 ->from('kurikulum')
                                 ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                                 ->where('kurikulum.id', (int)$selected_id)
                                 ->get()
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $curriculum) {
                    if ((int)$curriculum->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }
        return $results;
    }

    public function get_record($table, $id)
    {
        $allowed_tables = [
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
        ];
        if (!in_array($table, $allowed_tables, true) || (int)$id < 1) {
            return null;
        }

        return $this->db->where('id', (int)$id)->get($table)->row();
    }

    public function count_records($table)
    {
        $allowed_tables = [
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
            'penugasan_dosen_prodi',
            'kurikulum_mahasiswa',
            'penawaran_matakuliah',
        ];
        if (!in_array($table, $allowed_tables, true)) {
            return 0;
        }

        return $this->db->count_all($table);
    }

    public function save_program($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('PROGRAM', 'program_studi', $id, $data, $actor_id, $reason);
    }

    public function save_course($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('COURSE', 'mata_kuliah', $id, $data, $actor_id, $reason);
    }

    public function save_curriculum($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('CURRICULUM', 'kurikulum', $id, $data, $actor_id, $reason);
    }

    public function save_curriculum_course($id, array $data, $actor_id, $reason)
    {
        return $this->save_curriculum_course_with_relations($id, $data, [], null, $actor_id, $reason);
    }

    public function save_curriculum_course_with_relations($id, array $data, array $relations, $minimum_grade, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema Data Center Kurikulum belum tersedia. Jalankan migrasi 20261023_003_data_center_kurikulum.sql.'];
        }

        $id = (int)$id;
        $actor_id = (int)$actor_id;
        $relation_ids = [
            'PRASYARAT' => array_values(array_unique(array_filter(array_map('intval', $relations['PRASYARAT'] ?? [])))),
            'KOREQUISITE' => array_values(array_unique(array_filter(array_map('intval', $relations['KOREQUISITE'] ?? [])))),
        ];
        if (array_intersect($relation_ids['PRASYARAT'], $relation_ids['KOREQUISITE'])) {
            return ['success' => false, 'message' => 'Satu mata kuliah tidak dapat sekaligus menjadi prasyarat dan co-requisite.'];
        }
        if ($minimum_grade !== null && ((float)$minimum_grade < 0 || (float)$minimum_grade > 4)) {
            return ['success' => false, 'message' => 'Nilai minimum prasyarat harus berada pada rentang 0 sampai 4.'];
        }

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting curriculum relation transaction');
            return ['success' => false, 'message' => 'Transaksi pemetaan kurikulum tidak dapat dimulai.'];
        }

        $before = null;
        if ($id > 0) {
            $record_query = $this->db->query('SELECT * FROM `kurikulum_mata_kuliah` WHERE `id` = ? FOR UPDATE', [$id]);
            if (!$record_query || !($before = $record_query->row())) {
                $this->log_database_error('locking curriculum mapping');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Pemetaan kurikulum yang akan diperbarui tidak ditemukan.'];
            }
        }

        $curriculum = $this->db->where('id', (int)$data['kurikulum_id'])->get('kurikulum')->row();
        $course = $this->db->where('id', (int)$data['mata_kuliah_id'])->get('mata_kuliah')->row();
        if (!$curriculum || !$course) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Pilih kurikulum dan mata kuliah yang tersedia.'];
        }
        if ($before && ((int)$before->kurikulum_id !== (int)$data['kurikulum_id'])) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Pemetaan yang sudah dibuat tidak dapat dipindahkan ke kurikulum lain. Buat pemetaan baru pada kurikulum tujuan.'];
        }

        $all_relation_ids = array_values(array_unique(array_merge($relation_ids['PRASYARAT'], $relation_ids['KOREQUISITE'])));
        $relation_rows = [];
        if ($all_relation_ids) {
            $relation_rows = $this->db->where_in('id', $all_relation_ids)
                                      ->where('kurikulum_id', (int)$data['kurikulum_id'])
                                      ->where('status', 'ACTIVE')
                                      ->get('kurikulum_mata_kuliah')
                                      ->result();
            if (count($relation_rows) !== count($all_relation_ids) || in_array($id, $all_relation_ids, true)) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Prasyarat dan co-requisite harus berupa mata kuliah lain pada kurikulum yang sama.'];
            }
        }
        $before_relations = $id > 0 ? $this->get_raw_mapping_relations($id) : [];
        $existing_minimum_grades = [];
        foreach ($before_relations as $relation) {
            if ($relation['jenis_relasi'] === 'PRASYARAT') {
                $existing_minimum_grades[(int)$relation['source_id']] = $relation['nilai_minimum'];
            }
        }
        if ($id > 0) {
            $saved = $this->db->where('id', $id)->update('kurikulum_mata_kuliah', $data);
            $mapping_id = $id;
        } else {
            $saved = $this->db->insert('kurikulum_mata_kuliah', $data);
            $mapping_id = (int)$this->db->insert_id();
        }
        if (!$saved || $mapping_id < 1) {
            $this->log_database_error('saving curriculum mapping');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Pemetaan mata kuliah gagal disimpan. Periksa duplikasi kurikulum dan mata kuliah.'];
        }

        if (!$this->prerequisite_relations_are_valid($mapping_id, (int)$data['kurikulum_id'], $relation_ids['PRASYARAT'])) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Relasi prasyarat membentuk siklus atau semester prasyarat tidak lebih awal dari mata kuliah tujuan.'];
        }

        $this->db->group_start()
                 ->where('kurikulum_mata_kuliah_id', $mapping_id)
                 ->or_group_start()
                    ->where('jenis_relasi', 'KOREQUISITE')
                    ->where('prasyarat_mata_kuliah_id', $mapping_id)
                 ->group_end()
                 ->group_end()
                 ->delete('prasyarat_mata_kuliah');
        $alternative_group = 1;
        foreach ($relation_ids['PRASYARAT'] as $source_id) {
            $minimum_grade_for_source = $minimum_grade !== null
                ? (float)$minimum_grade
                : ($existing_minimum_grades[$source_id] ?? null);
            $this->db->insert('prasyarat_mata_kuliah', [
                'kurikulum_mata_kuliah_id' => $mapping_id,
                'prasyarat_mata_kuliah_id' => $source_id,
                'jenis_relasi' => 'PRASYARAT',
                'kelompok_alternatif' => $alternative_group++,
                'nilai_minimum' => $minimum_grade_for_source,
            ]);
        }
        foreach ($relation_ids['KOREQUISITE'] as $source_id) {
            $this->db->insert('prasyarat_mata_kuliah', [
                'kurikulum_mata_kuliah_id' => $mapping_id,
                'prasyarat_mata_kuliah_id' => $source_id,
                'jenis_relasi' => 'KOREQUISITE',
                'kelompok_alternatif' => 1,
                'nilai_minimum' => null,
            ]);
            $this->db->insert('prasyarat_mata_kuliah', [
                'kurikulum_mata_kuliah_id' => $source_id,
                'prasyarat_mata_kuliah_id' => $mapping_id,
                'jenis_relasi' => 'KOREQUISITE',
                'kelompok_alternatif' => 1,
                'nilai_minimum' => null,
            ]);
        }

        $before_snapshot = $before ? ['mapping' => $before, 'relations' => $before_relations] : null;
        $after_snapshot = ['mapping' => $data, 'relations' => $this->get_raw_mapping_relations($mapping_id)];
        if (!$this->write_catalog_history('CURRICULUM_COURSE', $mapping_id, $before_snapshot, $after_snapshot, $reason, $actor_id)
            || !$this->db->trans_status()) {
            $this->log_database_error('writing curriculum mapping history');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Perubahan pemetaan gagal dicatat ke histori; seluruh perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing curriculum mapping transaction');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi pemetaan kurikulum gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Pemetaan kurikulum dan relasi mata kuliah berhasil disimpan.'];
    }

    public function save_offering_center($id, array $data, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema Data Center Kurikulum belum tersedia. Jalankan migrasi 20261023_003_data_center_kurikulum.sql.'];
        }

        $id = (int)$id;
        $actor_id = (int)$actor_id;
        $program = $this->db->where('id', (int)$data['program_studi_id'])
                            ->where('status', 'ACTIVE')
                            ->get('program_studi')
                            ->row();
        $course = $this->db->where('id', (int)$data['mata_kuliah_id'])
                           ->where('status', 'ACTIVE')
                           ->get('mata_kuliah')
                           ->row();
        $lecturer = $this->db->where('id', (int)$data['dosen_id'])
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->get('akun_biro')
                             ->row();
        if (!$program || !$course || !$lecturer) {
            return ['success' => false, 'message' => 'Pilih program studi aktif, mata kuliah aktif, dan dosen aktif yang tersedia.'];
        }

        $eligible_mappings = $this->db->select('kurikulum_mata_kuliah.sks, kurikulum_mata_kuliah.semester_rekomendasi, kurikulum_mata_kuliah.pola_semester, kurikulum_mata_kuliah.semester_akhir')
                                      ->from('kurikulum_mata_kuliah')
                                      ->join('kurikulum', 'kurikulum.id = kurikulum_mata_kuliah.kurikulum_id', 'inner')
                                      ->where('kurikulum.program_studi_id', (int)$program->id)
                                      ->where('kurikulum.status', 'ACTIVE')
                                      ->where('kurikulum_mata_kuliah.mata_kuliah_id', (int)$course->id)
                                      ->where('kurikulum_mata_kuliah.status', 'ACTIVE')
                                      ->get()
                                      ->result();
        $eligible = false;
        foreach ($eligible_mappings as $mapping) {
            $semester_matches = $mapping->semester_rekomendasi === null
                || (int)$mapping->semester_rekomendasi === (int)$data['semester'];
            $term_matches = $mapping->pola_semester === 'SEMUA'
                || $mapping->pola_semester === strtoupper($data['semester_akademik']);
            $final_semester_matches = empty($mapping->semester_akhir)
                || ($mapping->semester_rekomendasi !== null
                    && (int)$mapping->semester_rekomendasi === (int)$data['semester']);
            if ($semester_matches && $term_matches && $final_semester_matches
                && (int)$mapping->sks === (int)$data['sks']) {
                $eligible = true;
                break;
            }
        }
        if (!$eligible) {
            return ['success' => false, 'message' => 'Mata kuliah belum dipetakan aktif ke kurikulum aktif prodi ini, atau semester, pola ganjil/genap, dan SKS tidak sesuai pemetaan.'];
        }

        $offering = [
            'kode_mk' => $course->kode,
            'nama_mk' => $course->nama,
            'prodi' => $program->nama,
            'semester' => (int)$data['semester'],
            'tahun_akademik' => $data['tahun_akademik'],
            'semester_akademik' => $data['semester_akademik'],
            'sks' => (int)$data['sks'],
            'kelas' => strtoupper($data['kelas']),
            'hari' => $data['hari'],
            'waktu_mulai' => $data['waktu_mulai'],
            'waktu_selesai' => $data['waktu_selesai'],
            'ruangan' => trim($data['ruangan']),
            'dosen_id' => (int)$data['dosen_id'],
            'aktif' => 1,
        ];

        if (!$this->offering_is_available($offering, $id)) {
            return ['success' => false, 'message' => 'Kelas duplikat atau terdapat benturan jadwal dosen/ruangan pada periode yang sama.'];
        }
        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting course offering transaction');
            return ['success' => false, 'message' => 'Transaksi penawaran mata kuliah tidak dapat dimulai.'];
        }

        $before = null;
        if ($id > 0) {
            $before = $this->get_offering_record($id);
            if (!$before) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Penawaran kelas yang akan diubah tidak ditemukan.'];
            }
            $enrolled = $this->db->where('penawaran_id', $id)->count_all_results('krs_detail');
            if ($enrolled > 0) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Kelas yang sudah dipilih dalam KRS tidak dapat diubah agar histori mahasiswa tetap konsisten. Nonaktifkan kelas dan buat penawaran pengganti.'];
            }
        }

        if ($id > 0) {
            $saved = $this->db->where('id', $id)->update('penawaran_matakuliah', $offering);
            $offering_id = $id;
        } else {
            $saved = $this->db->insert('penawaran_matakuliah', $offering);
            $offering_id = (int)$this->db->insert_id();
        }
        if (!$saved || $offering_id < 1) {
            $this->log_database_error('saving course offering');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penawaran kelas gagal disimpan. Periksa data periode dan relasi.'];
        }

        if ($before && $before->mata_kuliah_id) {
            $mapping_saved = $this->db->where('penawaran_id', $offering_id)
                                      ->update('pemetaan_penawaran_mata_kuliah', [
                                          'mata_kuliah_id' => (int)$course->id,
                                          'program_studi_id' => (int)$program->id,
                                      ]);
        } elseif ($before) {
            $mapping_saved = $this->db->insert('pemetaan_penawaran_mata_kuliah', [
                'penawaran_id' => $offering_id,
                'mata_kuliah_id' => (int)$course->id,
                'program_studi_id' => (int)$program->id,
                'dibuat_oleh' => $actor_id,
            ]);
        } else {
            $mapping_saved = $this->db->insert('pemetaan_penawaran_mata_kuliah', [
                'penawaran_id' => $offering_id,
                'mata_kuliah_id' => (int)$course->id,
                'program_studi_id' => (int)$program->id,
                'dibuat_oleh' => $actor_id,
            ]);
        }
        $after_snapshot = ['offering' => $offering, 'mata_kuliah_id' => (int)$course->id, 'program_studi_id' => (int)$program->id];
        if (!$mapping_saved || !$this->write_catalog_history('OFFERING', $offering_id, $before, $after_snapshot, $reason, $actor_id)
            || !$this->db->trans_status()) {
            $this->log_database_error('writing course offering mapping or history');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penawaran gagal dicatat ke pemetaan katalog/histori; seluruh perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing course offering transaction');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penawaran mata kuliah gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Penawaran kelas dan dosen pengampu berhasil disimpan.'];
    }

    public function set_offering_active_center($offering_id, $active, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema Data Center Kurikulum belum tersedia. Jalankan migrasi 20261023_003_data_center_kurikulum.sql.'];
        }
        $offering_id = (int)$offering_id;
        $before = $this->get_offering_record($offering_id);
        if (!$before) {
            return ['success' => false, 'message' => 'Penawaran kelas tidak ditemukan.'];
        }
        $active = $active ? 1 : 0;
        if ((int)$before->aktif === $active) {
            return ['success' => true, 'message' => 'Status kelas sudah sesuai.'];
        }
        if ($active && !$this->offering_is_available((array)$before, $offering_id)) {
            return ['success' => false, 'message' => 'Kelas tidak dapat diaktifkan karena jadwal berbenturan atau kelas duplikat.'];
        }
        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting course offering status transaction');
            return ['success' => false, 'message' => 'Transaksi status kelas tidak dapat dimulai.'];
        }
        $saved = $this->db->where('id', $offering_id)->update('penawaran_matakuliah', ['aktif' => $active]);
        $after = (array)$before;
        $after['aktif'] = $active;
        if (!$saved || !$this->write_catalog_history('OFFERING', $offering_id, $before, $after, $reason, (int)$actor_id)
            || !$this->db->trans_status()) {
            $this->log_database_error('writing course offering status history');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Status kelas gagal dicatat ke histori; perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing course offering status transaction');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi status kelas gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => $active ? 'Kelas berhasil diaktifkan.' : 'Kelas berhasil dinonaktifkan.'];
    }

    private function get_raw_mapping_relations($mapping_id)
    {
        return $this->db->select('kurikulum_mata_kuliah_id AS target_id, prasyarat_mata_kuliah_id AS source_id, jenis_relasi, kelompok_alternatif, nilai_minimum')
                        ->where('kurikulum_mata_kuliah_id', (int)$mapping_id)
                        ->order_by('jenis_relasi', 'ASC')
                        ->order_by('prasyarat_mata_kuliah_id', 'ASC')
                        ->get('prasyarat_mata_kuliah')
                        ->result_array();
    }

    private function prerequisite_relations_are_valid($mapping_id, $curriculum_id, array $source_ids)
    {
        $mapping_rows = $this->db->select('id, semester_rekomendasi')
                                 ->where('kurikulum_id', (int)$curriculum_id)
                                 ->get('kurikulum_mata_kuliah')
                                 ->result();
        $semesters = [];
        foreach ($mapping_rows as $row) {
            $semesters[(int)$row->id] = $row->semester_rekomendasi !== null
                ? (int)$row->semester_rekomendasi
                : null;
        }
        $adjacency = [];
        $indegree = [];
        $nodes = [];
        $edges = $this->db->select('relations.kurikulum_mata_kuliah_id AS target_id, relations.prasyarat_mata_kuliah_id AS source_id')
                          ->from('prasyarat_mata_kuliah AS relations')
                          ->join('kurikulum_mata_kuliah AS target', 'target.id = relations.kurikulum_mata_kuliah_id', 'inner')
                          ->join('kurikulum_mata_kuliah AS source', 'source.id = relations.prasyarat_mata_kuliah_id', 'inner')
                          ->where('relations.jenis_relasi', 'PRASYARAT')
                          ->where('target.kurikulum_id', (int)$curriculum_id)
                          ->where('source.kurikulum_id', (int)$curriculum_id)
                          ->get()
                          ->result();
        $edge_pairs = [];
        foreach ($edges as $edge) {
            if ((int)$edge->target_id !== (int)$mapping_id) {
                $edge_pairs[] = [(int)$edge->target_id, (int)$edge->source_id];
            }
        }
        foreach ($source_ids as $source_id) {
            $edge_pairs[] = [(int)$mapping_id, (int)$source_id];
        }
        foreach ($edge_pairs as $edge) {
            list($target_id, $source_id) = $edge;
            $target_semester = $semesters[$target_id] ?? null;
            $source_semester = $semesters[$source_id] ?? null;
            if ($target_semester !== null && $source_semester !== null && $source_semester >= $target_semester) {
                return false;
            }
            $adjacency[$target_id][] = $source_id;
            $indegree[$source_id] = ($indegree[$source_id] ?? 0) + 1;
            $indegree[$target_id] = $indegree[$target_id] ?? 0;
            $nodes[$target_id] = true;
            $nodes[$source_id] = true;
        }

        $queue = [];
        foreach (array_keys($nodes) as $node_id) {
            if (($indegree[$node_id] ?? 0) === 0) {
                $queue[] = (int)$node_id;
            }
        }
        $visited_count = 0;
        for ($index = 0; $index < count($queue); $index++) {
            $node_id = $queue[$index];
            $visited_count++;
            foreach ($adjacency[$node_id] ?? [] as $source_id) {
                $indegree[$source_id]--;
                if ($indegree[$source_id] === 0) {
                    $queue[] = $source_id;
                }
            }
        }

        return $visited_count === count($nodes);
    }

    private function offering_is_available(array $offering, $exclude_id = 0)
    {
        $duplicate = $this->db->where('kode_mk', $offering['kode_mk'])
                              ->where('kelas', $offering['kelas'])
                              ->where('prodi', $offering['prodi'])
                              ->where('semester', (int)$offering['semester'])
                              ->where('tahun_akademik', $offering['tahun_akademik'])
                              ->where('semester_akademik', $offering['semester_akademik']);
        if ((int)$exclude_id > 0) {
            $duplicate->where('id !=', (int)$exclude_id);
        }
        if ($duplicate->count_all_results('penawaran_matakuliah') > 0) {
            return false;
        }

        $conflicts = $this->db->where('tahun_akademik', $offering['tahun_akademik'])
                              ->where('semester_akademik', $offering['semester_akademik'])
                              ->where('hari', $offering['hari'])
                              ->where('aktif', 1);
        if ((int)$exclude_id > 0) {
            $conflicts->where('id !=', (int)$exclude_id);
        }
        $conflicts->group_start()
                      ->where('dosen_id', (int)$offering['dosen_id'])
                      ->or_where('ruangan', $offering['ruangan'])
                  ->group_end()
                  ->group_start()
                      ->where('waktu_mulai <', $offering['waktu_selesai'])
                      ->where('waktu_selesai >', $offering['waktu_mulai'])
                  ->group_end();

        return $conflicts->count_all_results('penawaran_matakuliah') === 0;
    }

    private function write_catalog_history($entity_type, $entity_id, $before, $after, $reason, $actor_id)
    {
        $before_json = $before === null ? null : json_encode($before, JSON_UNESCAPED_UNICODE);
        $after_json = json_encode($after, JSON_UNESCAPED_UNICODE);
        if ($after_json === false || ($before !== null && $before_json === false)) {
            log_message('error', 'Gagal membentuk snapshot histori Data Center Kurikulum: ' . json_last_error_msg());
            return false;
        }
        return $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => $entity_type,
            'id_entitas' => (int)$entity_id,
            'aksi' => $before === null ? 'CREATE' : 'UPDATE',
            'sebelum_json' => $before_json,
            'sesudah_json' => $after_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => (int)$actor_id,
        ]);
    }

    private function save_catalog_record($jenis_entitas, $table, $id, array $data, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia. Jalankan migrasi akademik terlebih dahulu.'];
        }

        $id = (int)$id;
        $actor_id = (int)$actor_id;
        $now = date('Y-m-d H:i:s');
        if ($table !== 'kurikulum_mata_kuliah') {
            if ($id > 0) {
                $data['diubah_oleh'] = $actor_id;
                $data['diubah_pada'] = $now;
            } else {
                $data['dibuat_oleh'] = $actor_id;
                $data['diubah_oleh'] = $actor_id;
            }
        }
        if ($id === 0) {
            $data['dibuat_pada'] = $now;
            $data['diubah_pada'] = $now;
        }

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting academic catalog transaction');
            return ['success' => false, 'message' => 'Transaksi katalog akademik tidak dapat dimulai.'];
        }
        $before = null;
        if ($id > 0) {
            $record_query = $this->db->query(
                'SELECT * FROM ' . $this->db->protect_identifiers($table) . ' WHERE `id` = ? FOR UPDATE',
                [$id]
            );
            if (!$record_query) {
                $this->log_database_error('locking academic catalog record');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Data katalog gagal dibaca untuk pembaruan.'];
            }
            $before = $record_query->row();
            if (!$before) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Data katalog yang akan diperbarui tidak ditemukan.'];
            }
        }

        $sesudah_json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $sebelum_json = $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null;
        if ($sesudah_json === false || ($before && $sebelum_json === false)) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot audit katalog akademik: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Perubahan tidak dapat dicatat untuk audit.'];
        }

        if ($id > 0) {
            $saved = $this->db->where('id', $id)->update($table, $data);
            $id_entitas = $id;
        } else {
            $saved = $this->db->insert($table, $data);
            $id_entitas = (int)$this->db->insert_id();
        }

        if (!$saved || $id_entitas < 1) {
            $this->log_database_error('saving academic catalog record');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Data katalog gagal disimpan. Periksa kode duplikat dan relasi data.'];
        }

        $aksi = !$before
            ? 'CREATE'
            : ((isset($before->status) && isset($data['status']) && $before->status !== $data['status']) ? 'STATUS_CHANGE' : 'UPDATE');
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => $jenis_entitas,
            'id_entitas' => $id_entitas,
            'aksi' => $aksi,
            'sebelum_json' => $sebelum_json,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);

        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing academic catalog audit history');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Perubahan gagal disimpan ke histori audit; seluruh perubahan dibatalkan.'];
        }

        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing academic catalog transaction');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi katalog akademik gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Data katalog berhasil disimpan.'];
    }

    private function log_database_error($operation)
    {
        $error = $this->db->error();
        log_message('error', 'Database error while ' . $operation . ': ' . ($error['message'] ?? 'unknown database error'));
    }
}
