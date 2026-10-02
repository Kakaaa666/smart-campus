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
    $column = $connection->query("SHOW COLUMNS FROM akun LIKE 'biro'");
    if (!$column || $column->num_rows === 0) {
        if (!$connection->query("ALTER TABLE akun ADD COLUMN biro VARCHAR(50) NOT NULL DEFAULT 'keuangan' AFTER role")) {
            throw new RuntimeException($connection->error);
        }
    }

    $accounts = [
        ['superadmin@staff.campus.ac.id', 'Super Admin Smart Campus', 1, 'semua', 'Rektorat', 'Administrasi Kampus', null, null],
        ['adminKeuangan@staff.campus.ac.id', 'Admin Keuangan', 2, 'keuangan', 'Fakultas Administrasi', 'Keuangan', null, null],
        ['adminAkademik@staff.campus.ac.id', 'Admin Akademik', 2, 'akademik', 'Fakultas Administrasi', 'Akademik', null, null],
        ['adminKemahasiswaan@staff.campus.ac.id', 'Admin Kemahasiswaan', 2, 'kemahasiswaan', 'Fakultas Administrasi', 'Kemahasiswaan', null, null],
        ['dosen@staff.campus.ac.id', 'Dosen Dummy', 4, 'dosen', 'Fakultas Teknologi Informasi', 'Informatika', null, null],
        ['2001@mhs.campuss.ac.id', 'Muhammad Arkan Pratama', 3, 'mahasiswa', 'Fakultas Teknologi Informasi', 'D3 Informatika', 2001, 1],
        ['2002@mhs.campuss.ac.id', 'Salsabila Putri Ramadhani', 3, 'mahasiswa', 'Fakultas Teknologi Informasi', 'D3 Informatika', 2002, 2],
        ['2003@mhs.campuss.ac.id', 'Rizky Aditya Saputra', 3, 'mahasiswa', 'Fakultas Teknologi Informasi', 'D3 Informatika', 2003, 3],
        ['2004@mhs.campuss.ac.id', 'Nabila Ayu Lestari', 3, 'mahasiswa', 'Fakultas Teknologi Informasi', 'D3 Informatika', 2004, 4],
        ['2005@mhs.campuss.ac.id', 'Daffa Maulana Firmansyah', 3, 'mahasiswa', 'Fakultas Teknologi Informasi', 'D3 Informatika', 2005, 5],
    ];

    $find = $connection->prepare('SELECT id FROM akun WHERE email = ? OR nim = ? ORDER BY (email = ?) DESC, id ASC');
    $update = $connection->prepare('UPDATE akun SET nim = ?, nama_lengkap = ?, email = ?, password = ?, role = ?, biro = ?, fakultas = ?, prodi = ?, semester = ?, updated_at = NOW(), deleted_at = NULL WHERE id = ?');
    $insert = $connection->prepare('INSERT INTO akun (nim, nama_lengkap, email, password, role, biro, fakultas, prodi, semester, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NULL)');
    $verify = $connection->prepare('SELECT password FROM akun WHERE email = ?');
    if (!$find || !$update || !$insert || !$verify) {
        throw new RuntimeException($connection->error);
    }

    foreach ($accounts as $account) {
        list($email, $name, $role, $bureau, $faculty, $program, $student_number, $semester) = $account;
        $nim = $student_number === null ? $email : (string)$student_number;
        $plain_password = $role === 1 ? 'superadmin123' : ($role === 2 ? 'admin123' : ($role === 4 ? 'dosen123' : 'user123'));
        $password = password_hash($plain_password, PASSWORD_DEFAULT);
        $find->bind_param('sss', $email, $nim, $email);
        if (!$find->execute()) {
            throw new RuntimeException($find->error);
        }
        $find->bind_result($account_id);
        $matches = [];
        while ($find->fetch()) {
            $matches[] = $account_id;
        }
        $find->free_result();

        if (count($matches) > 1) {
            throw new RuntimeException('Email/NIM target cocok ke lebih dari satu akun: ' . $email . '. Tidak ada data yang disimpan.');
        }

        if ($matches) {
            $account_id = $matches[0];
            $update->bind_param('ssssisssii', $nim, $name, $email, $password, $role, $bureau, $faculty, $program, $semester, $account_id);
            if (!$update->execute()) {
                throw new RuntimeException($update->error);
            }
            echo 'Diperbarui: ' . $email . PHP_EOL;
        } else {
            $insert->bind_param('ssssisssi', $nim, $name, $email, $password, $role, $bureau, $faculty, $program, $semester);
            if (!$insert->execute()) {
                throw new RuntimeException($insert->error);
            }
            echo 'Dibuat: ' . $email . PHP_EOL;
        }

        $verify->bind_param('s', $email);
        if (!$verify->execute()) {
            throw new RuntimeException($verify->error);
        }
        $verify->bind_result($stored_password);
        if (!$verify->fetch() || !password_verify($plain_password, $stored_password)) {
            throw new RuntimeException('Password tersimpan tidak cocok: ' . $email);
        }
        $verify->free_result();
    }

    $connection->commit();
    echo 'Seeder akun dummy selesai.' . PHP_EOL;
} catch (Throwable $error) {
    $connection->rollback();
    fwrite(STDERR, 'Seeder dibatalkan: ' . $error->getMessage() . PHP_EOL);
    exit(1);
} finally {
    $connection->close();
}
