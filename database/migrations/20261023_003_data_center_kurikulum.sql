-- Curriculum planning and offering management additions.
-- Run after 20261016_001_academic_foundation.sql and
-- 20261016_002_nama_skema_akademik_indonesia.sql.

ALTER TABLE `kurikulum_mata_kuliah`
    ADD COLUMN `pola_semester` ENUM('SEMUA','GANJIL','GENAP') NOT NULL DEFAULT 'SEMUA'
        AFTER `semester_rekomendasi`,
    ADD COLUMN `semester_akhir` TINYINT(1) NOT NULL DEFAULT 0
        AFTER `pola_semester`;

ALTER TABLE `prasyarat_mata_kuliah`
    ADD COLUMN `jenis_relasi` ENUM('PRASYARAT','KOREQUISITE') NOT NULL DEFAULT 'PRASYARAT'
        AFTER `prasyarat_mata_kuliah_id`;

ALTER TABLE `pemetaan_penawaran_mata_kuliah`
    ADD COLUMN `program_studi_id` INT(11) NULL DEFAULT NULL
        AFTER `mata_kuliah_id`,
    ADD KEY `idx_pemetaan_penawaran_program` (`program_studi_id`),
    ADD CONSTRAINT `fk_pemetaan_penawaran_program`
        FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE `histori_katalog_akademik`
    MODIFY COLUMN `jenis_entitas`
        ENUM('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM','OFFERING')
        NOT NULL;
