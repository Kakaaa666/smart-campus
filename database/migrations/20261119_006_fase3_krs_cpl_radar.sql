-- =====================================================================
-- FASE 3: PENGISIAN KRS / FRS BERBASIS CPL & RADAR CHART
-- Migration: 20261119_006_fase3_krs_cpl_radar.sql
-- =====================================================================

-- 1. Pastikan tabel nilai_evaluasi memiliki struktur lengkap (id, krs_id, krs_detail_id, sub_cpmk_id, nilai_angka)
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

-- 2. Sinkronisasi kolom krs_id jika tabel nilai_evaluasi sebelumnya sudah ada
-- (Dijalankan dinamis dan idempotently oleh M_obe::ensure_complete_obe_schema)
