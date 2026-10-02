<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['role_menus'] = [
    1 => [
        'label' => 'Superadmin',
        'menus' => [
            'beranda' => ['label' => 'Pusat Kendali', 'route' => 'beranda', 'bureau' => 'Rektorat'],
            'akademik' => ['label' => 'Biro Akademik', 'route' => 'superadmin/akademik', 'bureau' => 'Akademik'],
            'keuangan' => ['label' => 'Biro Keuangan', 'route' => 'superadmin/keuangan', 'bureau' => 'Keuangan'],
            'kemahasiswaan' => ['label' => 'Biro Kemahasiswaan', 'route' => 'superadmin/kemahasiswaan', 'bureau' => 'Kemahasiswaan'],
            'perpustakaan' => ['label' => 'Biro Perpustakaan', 'route' => 'superadmin/perpustakaan', 'bureau' => 'Perpustakaan'],
            'sarana_prasarana' => ['label' => 'Biro Sarana Prasarana', 'route' => 'superadmin/sarana-prasarana', 'bureau' => 'Sarana Prasarana'],
            'penjaminan_mutu' => ['label' => 'Biro Penjaminan Mutu', 'route' => 'superadmin/penjaminan-mutu', 'bureau' => 'Penjaminan Mutu'],
            'pengguna' => ['label' => 'Manajemen Pengguna', 'route' => 'superadmin/pengguna', 'bureau' => 'Rektorat'],
        ],
    ],
    2 => [
        'label' => 'Admin Biro',
        'menus' => [
            'akademik' => ['label' => 'Operasional Akademik', 'route' => 'admin/akademik', 'bureau' => 'Akademik'],
            'keuangan' => ['label' => 'Operasional Keuangan', 'route' => 'admin/keuangan', 'bureau' => 'Keuangan'],
            'dispensasi_keuangan' => ['label' => 'Verifikasi Dispensasi Tagihan', 'route' => 'keuangan/dispensasi', 'bureau' => 'Keuangan'],
            'validasi_tagihan_akhir' => ['label' => 'Validasi Tagihan Semester Akhir', 'route' => 'keuangan/validasi_tagihan_akhir', 'bureau' => 'Keuangan'],
            'laporan_progres' => ['label' => 'Laporan Progres Penagihan', 'route' => 'keuangan/laporan', 'bureau' => 'Keuangan'],
            'kemahasiswaan' => ['label' => 'Layanan Kemahasiswaan', 'route' => 'admin/kemahasiswaan', 'bureau' => 'Kemahasiswaan'],
            'perpustakaan' => ['label' => 'Operasional Perpustakaan', 'route' => 'admin/perpustakaan', 'bureau' => 'Perpustakaan'],
            'sarana_prasarana' => ['label' => 'Operasional Sarana Prasarana', 'route' => 'admin/sarana-prasarana', 'bureau' => 'Sarana Prasarana'],
            'penjaminan_mutu' => ['label' => 'Operasional Penjaminan Mutu', 'route' => 'admin/penjaminan-mutu', 'bureau' => 'Penjaminan Mutu'],
            'pengguna' => ['label' => 'Data Mahasiswa', 'route' => 'admin/pengguna', 'bureau' => 'Akademik'],
        ],
    ],
    3 => [
        'label' => 'Mahasiswa',
        'menus' => [
            'ringkasan' => ['label' => 'Ringkasan', 'route' => 'mahasiswa/ringkasan', 'bureau' => 'Akademik'],
            'akademik' => ['label' => 'Akademik', 'route' => 'mahasiswa/akademik', 'bureau' => 'Akademik'],
            'kehadiran' => ['label' => 'Kehadiran Kuliah', 'route' => 'mahasiswa/kehadiran', 'bureau' => 'Akademik'],
            'perwalian' => ['label' => 'Perwalian', 'route' => 'perwalian', 'bureau' => 'Akademik'],
            'keuangan' => ['label' => 'Keuangan', 'route' => 'mahasiswa/keuangan', 'bureau' => 'Keuangan'],
            'kuisioner' => ['label' => 'Kuisioner', 'route' => 'mahasiswa/kuisioner', 'bureau' => 'Kemahasiswaan'],
            'merdeka_belajar' => ['label' => 'Merdeka Belajar', 'route' => 'mahasiswa/merdeka_belajar', 'bureau' => 'Kemahasiswaan'],
            'skpi' => ['label' => 'Pengajuan SKPI', 'route' => 'mahasiswa/skpi', 'bureau' => 'Kemahasiswaan'],
            'verifikasi' => ['label' => 'Verifikasi Ijazah', 'route' => 'mahasiswa/verifikasi', 'bureau' => 'Akademik'],
            'perpustakaan' => ['label' => 'Perpustakaan', 'route' => 'mahasiswa/perpustakaan', 'bureau' => 'Perpustakaan'],
            'profil' => ['label' => 'Profil dan Keamanan Akun', 'route' => 'profil', 'bureau' => 'Rektorat'],
        ],
    ],
    4 => [
        'label' => 'Dosen',
        'menus' => [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'dosen', 'bureau' => 'Akademik'],
            'isi_nilai' => ['label' => 'Isi Nilai', 'route' => 'dosen/isi-nilai', 'bureau' => 'Akademik'],
            'revisi_nilai' => ['label' => 'Revisi Nilai', 'route' => 'dosen/revisi-nilai', 'bureau' => 'Akademik'],
            'mahasiswa_bimbingan' => ['label' => 'Mahasiswa Bimbingan', 'route' => 'dosen/mahasiswa-bimbingan', 'bureau' => 'Akademik'],
            'akademik' => ['label' => 'Akademik', 'route' => 'dosen/akademik', 'bureau' => 'Akademik'],
            'perkuliahan' => ['label' => 'Perkuliahan', 'route' => 'dosen/perkuliahan', 'bureau' => 'Akademik'],
            'profil' => ['label' => 'Profile', 'route' => 'dosen/profil', 'bureau' => 'Akademik'],
        ],
    ],
];
