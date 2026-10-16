-- Rename academic schema identifiers to Indonesian without dropping data.
-- Run after 20261016_001_academic_foundation.sql.

RENAME TABLE `academic_programs` TO `program_studi`,
    `academic_lecturer_programs` TO `penugasan_dosen_prodi`,
    `academic_curricula` TO `kurikulum`,
    `academic_courses` TO `mata_kuliah`,
    `academic_curriculum_courses` TO `kurikulum_mata_kuliah`,
    `academic_course_prerequisites` TO `prasyarat_mata_kuliah`,
    `academic_student_curricula` TO `kurikulum_mahasiswa`,
    `academic_offering_courses` TO `pemetaan_penawaran_mata_kuliah`,
    `academic_rules` TO `aturan_akademik`,
    `academic_rule_history` TO `histori_aturan_akademik`,
    `academic_catalog_history` TO `histori_katalog_akademik`,
    `academic_krs_validation_history` TO `histori_validasi_krs`,
    `academic_krs_detail_snapshots` TO `snapshot_detail_krs`;

ALTER TABLE `program_studi` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `faculty` `fakultas` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `degree_level` `jenjang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `program_studi` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `program_studi` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `lecturer_id` `dosen_id` int(11) NOT NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `program_id` `program_studi_id` int(11) NOT NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `assignment_type` `jenis_penugasan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `assigned_by` `ditetapkan_oleh` int(11) NULL;
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `penugasan_dosen_prodi` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `kurikulum` CHANGE COLUMN `program_id` `program_studi_id` int(11) NOT NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `cohort_start_year` `angkatan_mulai` smallint(5) unsigned NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `cohort_end_year` `angkatan_sampai` smallint(5) unsigned NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `valid_from` `berlaku_mulai` date NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `valid_until` `berlaku_sampai` date NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `kurikulum` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `kurikulum` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `mata_kuliah` CHANGE COLUMN `code` `kode` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `mata_kuliah` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `mata_kuliah` CHANGE COLUMN `description` `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `mata_kuliah` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL;
ALTER TABLE `mata_kuliah` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `mata_kuliah` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `curriculum_id` `kurikulum_id` int(11) NOT NULL;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `course_id` `mata_kuliah_id` int(11) NOT NULL;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `recommended_semester` `semester_rekomendasi` tinyint(2) unsigned NULL;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `credits` `sks` tinyint(2) unsigned NOT NULL;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `course_group` `kelompok_mata_kuliah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `is_required` `wajib` tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `kurikulum_mata_kuliah` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `curriculum_course_id` `kurikulum_mata_kuliah_id` int(11) NOT NULL;
ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `prerequisite_course_id` `prasyarat_mata_kuliah_id` int(11) NOT NULL;
ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `alternative_group` `kelompok_alternatif` smallint(5) unsigned NOT NULL DEFAULT 1;
ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `minimum_grade` `nilai_minimum` decimal(3,2) NULL;
ALTER TABLE `prasyarat_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `student_id` `mahasiswa_id` int(11) NOT NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `curriculum_id` `kurikulum_id` int(11) NOT NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `effective_from` `berlaku_mulai` date NOT NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `effective_until` `berlaku_sampai` date NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `changed_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `kurikulum_mahasiswa` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `offering_id` `penawaran_id` int(11) NOT NULL;
ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `course_id` `mata_kuliah_id` int(11) NOT NULL;
ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL;
ALTER TABLE `pemetaan_penawaran_mata_kuliah` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `aturan_akademik` CHANGE COLUMN `code` `kode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `version` `versi` int(11) unsigned NOT NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `name` `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `category` `kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `description` `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `scope_type` `jenis_cakupan` enum('GLOBAL','PROGRAM','CURRICULUM','COURSE','PERIOD') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'GLOBAL';
ALTER TABLE `aturan_akademik` CHANGE COLUMN `scope_id` `cakupan_id` int(11) NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `parameters_json` `parameter_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `effective_from` `berlaku_mulai` date NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `effective_until` `berlaku_sampai` date NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `created_by` `dibuat_oleh` int(11) NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `updated_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `aturan_akademik` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `aturan_akademik` CHANGE COLUMN `updated_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `rule_id` `aturan_id` int(11) NOT NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `version` `versi` int(11) unsigned NOT NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `before_json` `sebelum_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `after_json` `sesudah_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `changed_by` `diubah_oleh` int(11) NULL;
ALTER TABLE `histori_aturan_akademik` CHANGE COLUMN `changed_at` `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `entity_type` `jenis_entitas` enum('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `entity_id` `id_entitas` int(11) NOT NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `action` `aksi` enum('CREATE','UPDATE','STATUS_CHANGE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `before_json` `sebelum_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `after_json` `sesudah_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `change_reason` `alasan_perubahan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `performed_by` `dilakukan_oleh` int(11) NULL;
ALTER TABLE `histori_katalog_akademik` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `action` `aksi` enum('DRAFT','SUBMIT','APPROVE','REJECT','REVALIDATE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `result_json` `hasil_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `rule_versions_json` `versi_aturan_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `performed_by_staff_id` `dilakukan_oleh_staf_id` int(11) NULL;
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `performed_by_student_id` `dilakukan_oleh_mahasiswa_id` int(11) NULL;
ALTER TABLE `histori_validasi_krs` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();
ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `course_code` `kode_mata_kuliah` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `course_name` `nama_mata_kuliah` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `credits` `sks` tinyint(2) unsigned NOT NULL;
ALTER TABLE `snapshot_detail_krs` CHANGE COLUMN `created_at` `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP();