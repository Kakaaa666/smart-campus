-- =====================================================================
-- FASE 2: DESAIN PEMBELAJARAN & RPS (ROLE: DOSEN PENGAMPU)
-- Migration: 20261119_005_fase2_rps_cpmk_sub_cpmk.sql
-- =====================================================================

-- 1. Tambah status_rps pada tabel mata_kuliah (jika belum ada)
SET @dbname = DATABASE();
SET @tablename = "mata_kuliah";
SET @columnname = "status_rps";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `mata_kuliah` ADD `status_rps` ENUM('draft', 'published') NOT NULL DEFAULT 'draft' AFTER `deskripsi`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 2. Tabel CPMK (Capaian Pembelajaran Mata Kuliah)
-- Menghubungkan Mata Kuliah (mk_id) ke CPL yang dititipkan (cpl_mk_id)
CREATE TABLE IF NOT EXISTS `cpmk` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `mk_id` INT(11) NOT NULL,
    `mata_kuliah_id` INT(11) NOT NULL,
    `cpl_mk_id` INT(11) NOT NULL,
    `kode_cpmk` VARCHAR(20) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cpmk_mk` (`mk_id`),
    KEY `idx_cpmk_mata_kuliah` (`mata_kuliah_id`),
    KEY `idx_cpmk_cplmk` (`cpl_mk_id`),
    KEY `idx_cpmk_kode` (`kode_cpmk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pastikan kolom mk_id ada di cpmk jika tabel sudah ada sebelumnya
SET @cpmk_col = "mk_id";
SET @cpmk_table = "cpmk";
SET @prep_cpmk = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = @cpmk_table AND table_schema = @dbname AND column_name = @cpmk_col) > 0,
  "SELECT 1",
  "ALTER TABLE `cpmk` ADD `mk_id` INT(11) NOT NULL AFTER `id`, ADD KEY `idx_cpmk_mk_id` (`mk_id`)"
));
PREPARE alterCpmk FROM @prep_cpmk;
EXECUTE alterCpmk;
DEALLOCATE PREPARE alterCpmk;
UPDATE `cpmk` SET `mk_id` = `mata_kuliah_id` WHERE `mk_id` = 0 OR `mk_id` IS NULL;

-- 3. Tabel Sub-CPMK (Pertemuan 1-16, Instrumen Evaluasi, Bobot Nilai)
CREATE TABLE IF NOT EXISTS `sub_cpmk` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cpmk_id` INT(11) NOT NULL,
    `kode_sub` VARCHAR(30) NOT NULL,
    `kode_sub_cpmk` VARCHAR(30) NOT NULL,
    `pertemuan_ke` VARCHAR(50) NOT NULL DEFAULT '1',
    `minggu_pertemuan` VARCHAR(50) NOT NULL DEFAULT 'Pertemuan 1',
    `instrumen_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas',
    `bentuk_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas',
    `bobot_nilai` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `deskripsi` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sub_cpmk_cpmk` (`cpmk_id`),
    KEY `idx_sub_cpmk_kode` (`kode_sub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pastikan kolom kode_sub, pertemuan_ke, instrumen_evaluasi ada jika tabel sudah ada sebelumnya
SET @col_kode_sub = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'sub_cpmk' AND table_schema = @dbname AND column_name = 'kode_sub') > 0, "SELECT 1", "ALTER TABLE `sub_cpmk` ADD `kode_sub` VARCHAR(30) NOT NULL DEFAULT '' AFTER `cpmk_id`"));
PREPARE stmt_kodesub FROM @col_kode_sub; EXECUTE stmt_kodesub; DEALLOCATE PREPARE stmt_kodesub;

SET @col_pertemuan = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'sub_cpmk' AND table_schema = @dbname AND column_name = 'pertemuan_ke') > 0, "SELECT 1", "ALTER TABLE `sub_cpmk` ADD `pertemuan_ke` VARCHAR(50) NOT NULL DEFAULT '1' AFTER `kode_sub`"));
PREPARE stmt_pert FROM @col_pertemuan; EXECUTE stmt_pert; DEALLOCATE PREPARE stmt_pert;

SET @col_instrumen = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'sub_cpmk' AND table_schema = @dbname AND column_name = 'instrumen_evaluasi') > 0, "SELECT 1", "ALTER TABLE `sub_cpmk` ADD `instrumen_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas' AFTER `pertemuan_ke`"));
PREPARE stmt_inst FROM @col_instrumen; EXECUTE stmt_inst; DEALLOCATE PREPARE stmt_inst;

-- Sinkronisasi field jika kosong
UPDATE `sub_cpmk` SET `kode_sub` = `kode_sub_cpmk` WHERE `kode_sub` = '' OR `kode_sub` IS NULL;
UPDATE `sub_cpmk` SET `kode_sub_cpmk` = `kode_sub` WHERE `kode_sub_cpmk` = '' OR `kode_sub_cpmk` IS NULL;
UPDATE `sub_cpmk` SET `pertemuan_ke` = `minggu_pertemuan` WHERE `pertemuan_ke` = '' OR `pertemuan_ke` IS NULL;
UPDATE `sub_cpmk` SET `minggu_pertemuan` = `pertemuan_ke` WHERE `minggu_pertemuan` = '' OR `minggu_pertemuan` IS NULL;
UPDATE `sub_cpmk` SET `instrumen_evaluasi` = `bentuk_evaluasi` WHERE `instrumen_evaluasi` = '' OR `instrumen_evaluasi` IS NULL;
UPDATE `sub_cpmk` SET `bentuk_evaluasi` = `instrumen_evaluasi` WHERE `bentuk_evaluasi` = '' OR `bentuk_evaluasi` IS NULL;

-- 4. Header / Status Tabel RPS (Fase 2)
CREATE TABLE IF NOT EXISTS `rps_status` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `mata_kuliah_id` INT(11) NOT NULL,
    `status` ENUM('DRAFT','PUBLISHED') NOT NULL DEFAULT 'DRAFT',
    `total_bobot_nilai` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `catatan_revisi` TEXT NULL,
    `published_at` DATETIME NULL,
    `published_by` INT(11) NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_rps_mk` (`mata_kuliah_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
