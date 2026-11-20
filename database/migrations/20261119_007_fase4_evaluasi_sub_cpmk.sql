-- =====================================================================
-- FASE 4: INPUT NILAI EVALUASI PER SUB-CPMK (ROLE: DOSEN PENGAMPU)
-- Migration: 20261119_007_fase4_evaluasi_sub_cpmk.sql
-- =====================================================================

-- 1. Pastikan tabel nilai_evaluasi ada dengan kolom krs_id & krs_detail_id
CREATE TABLE IF NOT EXISTS `nilai_evaluasi` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `krs_id` INT(11) NULL,
    `krs_detail_id` INT(11) NULL,
    `sub_cpmk_id` INT(11) NOT NULL,
    `nilai_angka` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `catatan` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ne_krs` (`krs_id`),
    KEY `idx_ne_krs_detail` (`krs_detail_id`),
    KEY `idx_ne_sub_cpmk` (`sub_cpmk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Sinkronisasi krs_id dari krs_detail jika ada data yang krs_id-nya masih kosong
UPDATE `nilai_evaluasi` ne
INNER JOIN `krs_detail` kd ON kd.id = ne.krs_detail_id
SET ne.krs_id = kd.krs_id
WHERE ne.krs_id IS NULL OR ne.krs_id = 0;

-- 3. Hapus duplikasi data kombinasi (krs_id, sub_cpmk_id) jika ada (pertahankan ID terbaru)
DELETE ne1 FROM `nilai_evaluasi` ne1
INNER JOIN `nilai_evaluasi` ne2 
ON ne1.krs_id = ne2.krs_id 
AND ne1.sub_cpmk_id = ne2.sub_cpmk_id 
AND ne1.id < ne2.id
WHERE ne1.krs_id IS NOT NULL AND ne1.krs_id > 0;

-- 4. Tambahkan UNIQUE KEY uq_krs_sub_cpmk pada kombinasi (krs_id, sub_cpmk_id)
SET @dbname = DATABASE();
SET @tablename = "nilai_evaluasi";
SET @indexname = "uq_krs_sub_cpmk";

-- Cek apakah uq_krs_sub_cpmk sudah ada dan apakah mencakup krs_id
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      table_schema = @dbname
      AND table_name = @tablename
      AND index_name = @indexname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `nilai_evaluasi` ADD UNIQUE KEY `uq_krs_sub_cpmk` (`krs_id`, `sub_cpmk_id`)"
));
PREPARE alterIndexIfNotExists FROM @preparedStatement;
EXECUTE alterIndexIfNotExists;
DEALLOCATE PREPARE alterIndexIfNotExists;
