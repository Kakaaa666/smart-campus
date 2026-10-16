<?php
$root = dirname(__DIR__);
define('BASEPATH', $root . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);
define('ENVIRONMENT', 'development');
require APPPATH . 'config/database.php';

$config = $db[$active_group];
$connection = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
if ($connection->connect_error) {
    fwrite(STDERR, 'Koneksi database gagal: ' . $connection->connect_error . PHP_EOL);
    exit(1);
}
$connection->set_charset($config['char_set']);
$connection->begin_transaction();

try {
    // Pastikan tabel akun_biro tersedia
    $connection->query("CREATE TABLE IF NOT EXISTS `akun_biro` (
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

    // Pastikan tabel akun_mahasiswa tersedia (TANPA ROLE & BIRO)
    $connection->query("CREATE TABLE IF NOT EXISTS `akun_mahasiswa` (
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

    // Pastikan tabel pengajuan_skpi tersedia
    $connection->query("CREATE TABLE IF NOT EXISTS `pengajuan_skpi` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `mahasiswa_id` INT(11) NOT NULL,
        `kategori_id` INT(11) NOT NULL,
        `kategori_nama` VARCHAR(150) NULL,
        `nama_kegiatan` VARCHAR(255) NOT NULL,
        `penyelenggara` VARCHAR(255) NULL,
        `tingkat` VARCHAR(100) NULL DEFAULT 'Lokal / Kampus',
        `peran` VARCHAR(100) NULL,
        `prestasi` VARCHAR(100) NULL,
        `tanggal_kegiatan` DATE NULL,
        `tahun` INT(4) NULL,
        `no_sertifikat` VARCHAR(100) NULL,
        `file_bukti` VARCHAR(255) NULL,
        `status` ENUM('WAITING','YES','NO') NOT NULL DEFAULT 'WAITING',
        `catatan_validasi` TEXT NULL,
        `divalidasi_oleh` INT(11) NULL,
        `tanggal_validasi` DATETIME NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_skpi_mahasiswa` (`mahasiswa_id`),
        KEY `idx_skpi_kategori` (`kategori_id`),
        KEY `idx_skpi_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 1. Seed Akun Biro & Staf & Dosen
    $biro_accounts = [
        ['superadmin@staff.campus.ac.id', 'Super Admin Smart Campus', 1, 'semua', 'Rektorat', 'Administrasi Kampus', 'superadmin'],
        ['adminKeuangan@staff.campus.ac.id', 'Admin Keuangan', 2, 'keuangan', 'Fakultas Administrasi', 'Keuangan', 'adminKeuangan'],
        ['adminAkademik@staff.campus.ac.id', 'Admin Akademik', 2, 'akademik', 'Fakultas Administrasi', 'Akademik', 'adminAkademik'],
        ['adminKemahasiswaan@staff.campus.ac.id', 'Admin Kemahasiswaan', 2, 'kemahasiswaan', 'Fakultas Administrasi', 'Kemahasiswaan', 'adminKemahasiswaan'],
        ['adminPenjaminanMutu@staff.campus.ac.id', 'Admin Penjaminan Mutu', 2, 'penjaminan_mutu', 'Fakultas Administrasi', 'Penjaminan Mutu', 'adminPenjaminanMutu'],
        ['sugeng@staff.campus.ac.id', 'Sugeng Widodo, S.Kom., M.Kom', 4, 'dosen', 'Fakultas Teknologi Informasi', 'Teknik Informatika', 'sugeng'],
        ['dosen@staff.campus.ac.id', 'Dr. Ir. Hendra Setiawan, M.T.', 4, 'dosen', 'Fakultas Teknologi Informasi', 'Teknik Informatika', 'dosen'],
        ['maya.sartika@smartcampus.ac.id', 'Dr. Maya Sartika, S.E., M.Ak.', 4, 'dosen', 'Fakultas Ekonomi & Bisnis', 'Akuntansi', 'maya'],
    ];

    $find_biro = $connection->prepare('SELECT id FROM akun_biro WHERE email = ? OR nim = ? ORDER BY (email = ?) DESC, id ASC');
    $update_biro = $connection->prepare('UPDATE akun_biro SET nim = ?, nama_lengkap = ?, email = ?, password = ?, role = ?, biro = ?, fakultas = ?, prodi = ?, updated_at = NOW(), deleted_at = NULL WHERE id = ?');
    $insert_biro = $connection->prepare('INSERT INTO akun_biro (nim, nama_lengkap, email, password, role, biro, fakultas, prodi, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NULL)');

    foreach ($biro_accounts as $acc) {
        list($email, $name, $role, $bureau, $faculty, $program, $nim) = $acc;
        $plain = $role === 1 ? 'superadmin123' : ($role === 2 ? 'admin123' : 'dosen123');
        $pass = password_hash($plain, PASSWORD_DEFAULT);

        $find_biro->bind_param('sss', $email, $nim, $email);
        $find_biro->execute();
        $find_biro->bind_result($aid);
        $matches = [];
        while ($find_biro->fetch()) {
            $matches[] = $aid;
        }
        $find_biro->free_result();

        if ($matches) {
            $aid = $matches[0];
            $update_biro->bind_param('ssssisssi', $nim, $name, $email, $pass, $role, $bureau, $faculty, $program, $aid);
            $update_biro->execute();
            echo 'Diperbarui (Biro): ' . $email . PHP_EOL;
        } else {
            $insert_biro->bind_param('ssssisss', $nim, $name, $email, $pass, $role, $bureau, $faculty, $program);
            $insert_biro->execute();
            echo 'Dibuat (Biro): ' . $email . PHP_EOL;
        }
    }

    // 2. Seed Akun Mahasiswa (TANPA ROLE & BIRO)
    $mhs_accounts = [
        ['2001@mhs.campuss.ac.id', 'Muhammad Arkan Pratama', 'Fakultas Teknologi Informasi', 'Teknik Informatika', '2001', 1],
        ['2002@mhs.campuss.ac.id', 'Salsabila Putri Ramadhani', 'Fakultas Teknologi Informasi', 'Teknik Informatika', '2002', 2],
        ['2003@mhs.campuss.ac.id', 'Rizky Aditya Saputra', 'Fakultas Teknologi Informasi', 'Sistem Informasi', '2003', 3],
        ['2004@mhs.campuss.ac.id', 'Nabila Ayu Lestari', 'Fakultas Teknologi Informasi', 'Teknik Informatika', '2004', 4],
        ['2005@mhs.campuss.ac.id', 'Daffa Maulana Firmansyah', 'Fakultas Teknologi Informasi', 'Teknik Informatika', '2005', 5],
        ['241221009@mhs.campuss.ac.id', 'MOCHAMMAD ARTHUR ARIESTA TAMPI', 'Fakultas Teknologi Informasi', 'Sistem Informasi', '241221009', 4],
    ];

    $find_mhs = $connection->prepare('SELECT id FROM akun_mahasiswa WHERE email = ? OR nim = ? ORDER BY (email = ?) DESC, id ASC');
    $update_mhs = $connection->prepare('UPDATE akun_mahasiswa SET nim = ?, nama_lengkap = ?, email = ?, password = ?, fakultas = ?, prodi = ?, semester = ?, updated_at = NOW(), deleted_at = NULL WHERE id = ?');
    $insert_mhs = $connection->prepare('INSERT INTO akun_mahasiswa (nim, nama_lengkap, email, password, fakultas, prodi, semester, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NULL)');

    foreach ($mhs_accounts as $acc) {
        list($email, $name, $faculty, $program, $nim, $semester) = $acc;
        $plain = 'user123';
        $pass = password_hash($plain, PASSWORD_DEFAULT);

        $find_mhs->bind_param('sss', $email, $nim, $email);
        $find_mhs->execute();
        $find_mhs->bind_result($aid);
        $matches = [];
        while ($find_mhs->fetch()) {
            $matches[] = $aid;
        }
        $find_mhs->free_result();

        if ($matches) {
            $aid = $matches[0];
            $update_mhs->bind_param('ssssssii', $nim, $name, $email, $pass, $faculty, $program, $semester, $aid);
            $update_mhs->execute();
            echo 'Diperbarui (Mahasiswa): ' . $email . PHP_EOL;
        } else {
            $insert_mhs->bind_param('ssssssi', $nim, $name, $email, $pass, $faculty, $program, $semester);
            $insert_mhs->execute();
            echo 'Dibuat (Mahasiswa): ' . $email . PHP_EOL;
        }
    }

    $connection->commit();
    echo 'Seeder akun_biro dan akun_mahasiswa selesai.' . PHP_EOL;
} catch (Throwable $error) {
    $connection->rollback();
    fwrite(STDERR, 'Seeder dibatalkan: ' . $error->getMessage() . PHP_EOL);
    exit(1);
} finally {
    $connection->close();
}
