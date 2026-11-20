-- =====================================================================
-- MODUL OUTCOME-BASED EDUCATION (OBE) LENGKAP - SMART CAMPUS SYSTEM
-- Migration: 20261119_004_obe_system_complete.sql
-- =====================================================================

-- 1. Tabel Profil Lulusan (Fase 1)
CREATE TABLE IF NOT EXISTS `profil_lulusan` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `prodi_id` INT(11) NOT NULL,
    `kode_profil` VARCHAR(20) NOT NULL,
    `nama_profil` VARCHAR(150) NOT NULL,
    `deskripsi` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_pl_prodi` (`prodi_id`),
    KEY `idx_pl_kode` (`kode_profil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Capaian Pembelajaran Lulusan (CPL) (Fase 1)
CREATE TABLE IF NOT EXISTS `cpl` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `prodi_id` INT(11) NOT NULL,
    `kode_cpl` VARCHAR(20) NOT NULL,
    `kategori` ENUM('Sikap','Pengetahuan','Keterampilan Umum','Keterampilan Khusus') NOT NULL DEFAULT 'Pengetahuan',
    `deskripsi` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cpl_prodi` (`prodi_id`),
    KEY `idx_cpl_kode` (`kode_cpl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrasi data dari tabel lama cpls ke cpl jika cpls ada dan cpl kosong
INSERT INTO `cpl` (`id`, `prodi_id`, `kode_cpl`, `kategori`, `deskripsi`, `created_at`)
SELECT `id`, `prodi_id`, `kode_cpl`, `kategori`, `deskripsi`, `created_at`
FROM `cpls`
WHERE (SELECT COUNT(*) FROM `cpl`) = 0
ON DUPLICATE KEY UPDATE `deskripsi` = VALUES(`deskripsi`);

-- 3. Tabel Pivot Profil Lulusan <-> CPL (Fase 1)
CREATE TABLE IF NOT EXISTS `profil_cpl` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `profil_lulusan_id` INT(11) NOT NULL,
    `cpl_id` INT(11) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_profil_cpl` (`profil_lulusan_id`, `cpl_id`),
    KEY `idx_pc_cpl` (`cpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel Matriks Pemetaan CPL ke Mata Kuliah (Fase 1)
-- Total bobot_cpl per CPL harus tepat 100%
CREATE TABLE IF NOT EXISTS `cpl_mk` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cpl_id` INT(11) NOT NULL,
    `mata_kuliah_id` INT(11) NOT NULL,
    `bobot_cpl` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_cpl_mk` (`cpl_id`, `mata_kuliah_id`),
    KEY `idx_cplmk_cpl` (`cpl_id`),
    KEY `idx_cplmk_mk` (`mata_kuliah_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel Capaian Pembelajaran Mata Kuliah (CPMK) (Fase 2)
-- Terikat ke cpl_mk_id (CPL yang dititipkan ke MK tersebut)
CREATE TABLE IF NOT EXISTS `cpmk` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cpl_mk_id` INT(11) NOT NULL,
    `mata_kuliah_id` INT(11) NOT NULL,
    `kode_cpmk` VARCHAR(20) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cpmk_cplmk` (`cpl_mk_id`),
    KEY `idx_cpmk_mk` (`mata_kuliah_id`),
    KEY `idx_cpmk_kode` (`kode_cpmk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel Sub-CPMK (Pertemuan 1-16 & Bobot Nilai) (Fase 2)
-- Total bobot_nilai dari seluruh Sub-CPMK dalam satu MK wajib tepat 100%
CREATE TABLE IF NOT EXISTS `sub_cpmk` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cpmk_id` INT(11) NOT NULL,
    `kode_sub_cpmk` VARCHAR(20) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `minggu_pertemuan` VARCHAR(50) NOT NULL DEFAULT 'Pertemuan 1',
    `bentuk_evaluasi` ENUM('Tugas','Kuis','UTS','Proyek','UAS','Praktikum','Partisipasi') NOT NULL DEFAULT 'Tugas',
    `bobot_nilai` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sub_cpmk_cpmk` (`cpmk_id`),
    KEY `idx_sub_cpmk_kode` (`kode_sub_cpmk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabel Status Publikasi RPS per Mata Kuliah (Fase 2)
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

-- 8. Tabel Input Nilai Evaluasi Sub-CPMK (Fase 4)
-- Dosen menginput nilai mentah 0-100 per Sub-CPMK untuk setiap mahasiswa di kelas
CREATE TABLE IF NOT EXISTS `nilai_evaluasi` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `krs_detail_id` INT(11) NOT NULL,
    `sub_cpmk_id` INT(11) NOT NULL,
    `nilai_angka` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `catatan` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_krs_sub_cpmk` (`krs_detail_id`, `sub_cpmk_id`),
    KEY `idx_ne_krs` (`krs_detail_id`),
    KEY `idx_ne_sub_cpmk` (`sub_cpmk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
