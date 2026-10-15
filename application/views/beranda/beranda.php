<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* Aesthetic Styling for Student Dashboard & Portal */
.student-hero-banner {
    border-radius: 18px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-top: 4px solid #3b82f6 !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
    position: relative;
    overflow: hidden;
}
.student-profile-card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    transition: all 0.25s ease;
}
.student-profile-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.student-avatar-large {
    width: 96px;
    height: 96px;
    border-radius: 20px;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}
.advisor-badge-card {
    border-radius: 16px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease;
}
.advisor-badge-card:hover {
    border-color: #6366f1;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.1);
}
.metric-summary-card {
    border-radius: 16px;
    border: none;
    background: #ffffff;
    box-shadow: 0 4px 18px rgba(0,0,0,0.04);
    transition: all 0.25s ease;
    overflow: hidden;
}
.metric-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.metric-icon-bubble {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.schedule-badge-today {
    border-left: 4px solid #4f46e5 !important;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.schedule-badge-today:hover {
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.1);
    transform: translateX(3px);
}
.quick-feature-box {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 16px;
    display: flex;
    align-items: center;
    text-decoration: none !important;
    color: #1e293b;
    transition: all 0.2s ease;
}
.quick-feature-box:hover {
    border-color: #6366f1;
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.1);
    color: #4338ca;
}
.superadmin-hub { border: 1px solid #dbe4f0; border-radius: 14px; background: #fff; box-shadow: 0 5px 20px rgba(15, 23, 42, .06); margin-bottom: 24px; }
.superadmin-hub .hub-head { padding: 22px 24px; border-bottom: 1px solid #edf2f7; }
.superadmin-hub .hub-head h4 { margin: 0 0 5px; color: #0f172a; font-weight: 800; }
.superadmin-hub .hub-head p { margin: 0; color: #64748b; font-size: 13px; }
.superadmin-hub .hub-body { padding: 18px; }
.superadmin-mode { display: flex; align-items: center; min-height: 92px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 10px; color: #0f172a; background: #fff; transition: transform .15s ease, box-shadow .15s ease; }
.superadmin-mode:hover { color: #0f172a; text-decoration: none; transform: translateY(-2px); box-shadow: 0 7px 18px rgba(15, 23, 42, .09); }
.superadmin-mode .mode-icon { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; margin-right: 12px; border-radius: 10px; color: #fff; font-size: 18px; }
.superadmin-mode strong { display: block; font-size: 14px; }
.superadmin-mode small { display: block; margin-top: 3px; color: #64748b; font-size: 11.5px; }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content" style="padding-top: 20px;">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- FLASH MESSAGE NOTIFICATIONS -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981;">
                            <i class="fa fa-check-circle mr-2 text-success"></i>
                            <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444;">
                            <i class="fa fa-exclamation-triangle mr-2 text-danger"></i>
                            <?= html_escape($this->session->flashdata('error')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- KHUSUS SUPERADMIN: PUSAT KENDALI -->
                    <?php if ((int)$this->session->userdata('role') === 1): ?>
                        <section class="superadmin-hub" aria-label="Pusat Kendali Super Admin">
                            <div class="hub-head">
                                <h4><i class="fa fa-shield mr-2" style="color:#2563eb;"></i>Pusat Kendali Super Admin</h4>
                                <p>Masuk ke mode mahasiswa atau dashboard biro untuk memeriksa data, alur, dan pembaruan sistem.</p>
                            </div>
                            <div class="hub-body">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <a class="superadmin-mode" href="<?= base_url('keuangan') ?>">
                                            <span class="mode-icon" style="background:#2563eb;"><i class="fa fa-user"></i></span>
                                            <span><strong>Mode Mahasiswa</strong><small>Periksa profil, keuangan, KRS, dan perwalian mahasiswa</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/keuangan/admin') ?>">
                                            <span class="mode-icon" style="background:#059669;"><i class="fa fa-money"></i></span>
                                            <span><strong>Biro Keuangan</strong><small>Dashboard, verifikasi, kontrol tagihan, dan laporan</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/akademik/jadwal') ?>">
                                            <span class="mode-icon" style="background:#7c3aed;"><i class="fa fa-graduation-cap"></i></span>
                                            <span><strong>Biro Akademik</strong><small>Jadwal, nilai, KHS, kurikulum, dan kalender</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/perpustakaan') ?>">
                                            <span class="mode-icon" style="background:#d97706;"><i class="fa fa-book"></i></span>
                                            <span><strong>Biro Perpustakaan</strong><small>Area kendali dan integrasi layanan perpustakaan</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/kemahasiswaan') ?>">
                                            <span class="mode-icon" style="background:#dc2626;"><i class="fa fa-users"></i></span>
                                            <span><strong>Biro Kemahasiswaan</strong><small>Beasiswa, kegiatan, kuisioner, SKPI, dan layanan mahasiswa</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/sarana-prasarana') ?>">
                                            <span class="mode-icon" style="background:#0f766e;"><i class="fa fa-building"></i></span>
                                            <span><strong>Biro Sarana Prasarana</strong><small>Fasilitas, inventaris, ruang, dan pemeliharaan kampus</small></span>
                                        </a>
                                    </div>
                                    <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                        <a class="superadmin-mode" href="<?= base_url('superadmin/penjaminan-mutu') ?>">
                                            <span class="mode-icon" style="background:#be123c;"><i class="fa fa-check-square-o"></i></span>
                                            <span><strong>Biro Penjaminan Mutu</strong><small>Standar, audit, evaluasi, dan tindak lanjut mutu</small></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($student)): ?>
                        <!-- ======================================================== -->
                        <!-- BERANDA UTAMA MAHASISWA (PROFIL & PERKULIAHAN)          -->
                        <!-- ======================================================== -->

                        <!-- HERO BANNER SAMBUTAN MAHASISWA -->
                        <div class="card student-hero-banner mb-4">
                            <div class="card-body p-4 p-md-5">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                            <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                                <i class="fa fa-mortarboard mr-1"></i> PORTAL MAHASISWA
                                            </span>
                                            <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                                <i class="fa fa-calendar mr-1"></i> TA <?= html_escape($academic_period['tahun_akademik']) ?> (SEMESTER <?= strtoupper(html_escape($academic_period['semester_akademik'])) ?>)
                                            </span>
                                            <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                                <i class="fa fa-check-circle mr-1"></i> STATUS: <?= strtoupper(html_escape($student->status_akademik ?: 'AKTIF')) ?>
                                            </span>
                                        </div>
                                        <h3 class="font-weight-bold mb-2" style="color: #0f172a !important;">
                                            Selamat Datang, <?= html_escape($student->nama_lengkap) ?>!
                                        </h3>
                                        <p class="mb-3" style="font-size: 14.5px; max-width: 620px; line-height: 1.6; color: #64748b !important;">
                                            Pantau perkembangan akademik, pemilihan rencana studi (KRS), jadwal perkuliahan mingguan, dan konsultasi dengan Dosen Wali Anda.
                                        </p>
                                        <div class="d-flex flex-wrap" style="gap: 8px;">
                                            <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-warning font-weight-bold px-4 py-2" style="border-radius: 10px; color: #1e1b4b; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                                                <i class="fa fa-book mr-1"></i> Rencana Studi (KRS)
                                            </a>
                                            <a href="<?= base_url('akademik') ?>" class="btn btn-outline-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
                                                <i class="fa fa-calendar-check-o mr-1"></i> Jadwal Kuliah
                                            </a>
                                            <a href="<?= base_url('keuangan') ?>" class="btn btn-outline-secondary font-weight-bold px-4 py-2" style="border-radius: 10px;">
                                                <i class="fa fa-credit-card mr-1"></i> Tagihan SPP
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-center text-lg-right mt-4 mt-lg-0">
                                        <div class="d-inline-block text-left p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; min-width: 250px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                            <small class="font-weight-bold d-block text-uppercase" style="font-size: 11px; color: #64748b;">HARI INI</small>
                                            <h5 class="font-weight-bold mb-1" style="color: #0f172a;"><?= $today ?>, <?= date('d M Y') ?></h5>
                                            <hr style="border-color: #e2e8f0;" class="my-2">
                                            <small class="font-weight-bold d-block text-uppercase" style="font-size: 11px; color: #64748b;">JADWAL KULIAH HARI INI</small>
                                            <strong style="font-size: 14px; color: #1e293b;">
                                                <i class="fa fa-clock-o text-warning mr-1"></i> <?= count($today_courses) ?> Mata Kuliah Aktif
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4 KARTU METRIK INFORMASI PERKULIAHAN -->
                        <div class="row mb-4">
                            <!-- METRIK 1: STATUS KRS -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card metric-summary-card h-100">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px;">STATUS KRS</small>
                                            <?php
                                                $krs_status = !empty($krs) ? strtoupper($krs->status) : 'BELUM ISI';
                                                $krs_badge_color = 'text-warning';
                                                $krs_bg = '#fef3c7';
                                                $krs_icon = 'fa-hourglass-half';
                                                $krs_label = 'Menunggu Dosen';
                                                if ($krs_status === 'DISETUJUI') {
                                                    $krs_badge_color = 'text-success';
                                                    $krs_bg = '#dcfce7';
                                                    $krs_icon = 'fa-check-circle';
                                                    $krs_label = 'Telah Disetujui';
                                                } elseif ($krs_status === 'DITOLAK') {
                                                    $krs_badge_color = 'text-danger';
                                                    $krs_bg = '#fee2e2';
                                                    $krs_icon = 'fa-times-circle';
                                                    $krs_label = 'Perlu Revisi';
                                                } elseif ($krs_status === 'DRAFT' || $krs_status === 'BELUM ISI') {
                                                    $krs_badge_color = 'text-secondary';
                                                    $krs_bg = '#f1f5f9';
                                                    $krs_icon = 'fa-pencil';
                                                    $krs_label = 'Draf Belum Diajukan';
                                                }
                                            ?>
                                            <h5 class="font-weight-bold mb-0 <?= $krs_badge_color ?>"><?= $krs_label ?></h5>
                                            <small class="text-muted"><?= !empty($krs) ? (int)$krs->total_sks . ' SKS Dipilih' : '0 SKS' ?></small>
                                        </div>
                                        <div class="metric-icon-bubble" style="background: <?= $krs_bg ?>; color: <?= ($krs_status === 'DISETUJUI') ? '#16a34a' : (($krs_status === 'DITOLAK') ? '#dc2626' : '#d97706') ?>;">
                                            <i class="fa <?= $krs_icon ?>"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- METRIK 2: STATUS KEUANGAN / SPP -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card metric-summary-card h-100">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px;">STATUS SPP KULIAH</small>
                                            <?php
                                                $is_lunas = !empty($status_keuangan['buka_krs']) || (isset($status_keuangan['status']) && strtoupper($status_keuangan['status']) === 'LUNAS');
                                            ?>
                                            <?php if ($is_lunas): ?>
                                                <h5 class="font-weight-bold mb-0 text-success">Lunas / Terverifikasi</h5>
                                                <small class="text-muted">Akses KRS terbuka</small>
                                            <?php else: ?>
                                                <h5 class="font-weight-bold mb-0 text-danger">Tunggakan SPP</h5>
                                                <small class="text-muted">Perlu pelunasan tagihan</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="metric-icon-bubble" style="background: <?= $is_lunas ? '#dcfce7' : '#fee2e2' ?>; color: <?= $is_lunas ? '#16a34a' : '#dc2626' ?>;">
                                            <i class="fa <?= $is_lunas ? 'fa-check' : 'fa-credit-card' ?>"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- METRIK 3: INDEKS PRESTASI KUMULATIF -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card metric-summary-card h-100">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px;">IPK KUMULATIF</small>
                                            <h4 class="font-weight-bold mb-0 text-primary"><?= number_format((float)($student->ipk ?: 3.85), 2) ?></h4>
                                            <small class="text-muted">Maksimal 24 SKS</small>
                                        </div>
                                        <div class="metric-icon-bubble" style="background: #e0e7ff; color: #4338ca;">
                                            <i class="fa fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- METRIK 4: SEMESTER & MATA KULIAH -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card metric-summary-card h-100">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px;">SEMESTER BERJALAN</small>
                                            <h4 class="font-weight-bold mb-0 text-dark">Semester <?= (int)$student->semester ?></h4>
                                            <small class="text-muted"><?= count($courses) ?> Matakuliah Aktif</small>
                                        </div>
                                        <div class="metric-icon-bubble" style="background: #f1f5f9; color: #475569;">
                                            <i class="fa fa-book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PROFIL LENGKAP MAHASISWA & DOSEN WALI (PA) -->
                        <div class="row mb-4">
                            <!-- KOLOM KIRI: BIODATA & IDENTITAS MAHASISWA -->
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card student-profile-card h-100">
                                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-user-circle text-primary mr-2"></i> Identitas & Profil Mahasiswa
                                        </h5>
                                        <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                            Angkatan <?= html_escape($student->angkatan ?: '2023') ?>
                                        </span>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-start flex-wrap mb-4 pb-3 border-bottom">
                                            <div class="mr-4 mb-3 mb-sm-0 text-center">
                                                <?php
                                                    $foto_mhs = !empty($student->foto) ? base_url('assets/images/' . $student->foto) : base_url('assets/images/avatar-4.png');
                                                ?>
                                                <img src="<?= $foto_mhs ?>" alt="<?= html_escape($student->nama_lengkap) ?>" class="student-avatar-large mb-2" onerror="this.src='<?= base_url('assets/images/avatar-4.png') ?>'">
                                                <div>
                                                    <span class="badge badge-success px-2 py-1" style="font-size: 10px; border-radius: 6px;">
                                                        <i class="fa fa-check-circle mr-1"></i> <?= strtoupper(html_escape($student->status_akademik ?: 'AKTIF')) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="font-weight-bold text-dark mb-1"><?= html_escape($student->nama_lengkap) ?></h4>
                                                <div class="text-primary font-weight-bold mb-2" style="font-size: 15px;">
                                                    NIM: <?= html_escape($student->nim) ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 mb-2">
                                                        <small class="text-muted d-block">Program Studi:</small>
                                                        <strong class="text-dark" style="font-size: 13px;"><?= html_escape($student->prodi ?: 'Teknik Informatika') ?></strong>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <small class="text-muted d-block">Fakultas:</small>
                                                        <strong class="text-dark" style="font-size: 13px;"><?= html_escape($student->fakultas ?: 'Fakultas Teknologi Informasi') ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DETAIL KONTAK & ALAMAT -->
                                        <h6 class="font-weight-bold text-dark mb-3" style="font-size: 13.5px;">
                                            <i class="fa fa-address-card-o text-muted mr-1"></i> Informasi Kontak & Domisili
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 rounded bg-light border">
                                                    <small class="text-muted d-block font-weight-bold mb-1">
                                                        <i class="fa fa-phone text-success mr-1"></i> No. Telepon / WhatsApp:
                                                    </small>
                                                    <strong class="text-dark" style="font-size: 13px;">
                                                        <?= html_escape($student->telepon ?: '0821-4567-8901') ?>
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 rounded bg-light border">
                                                    <small class="text-muted d-block font-weight-bold mb-1">
                                                        <i class="fa fa-envelope text-info mr-1"></i> Email Kampus:
                                                    </small>
                                                    <strong class="text-dark text-truncate d-block" style="font-size: 13px;">
                                                        <?= html_escape($student->email ?: $student->nim . '@mhs.campuss.ac.id') ?>
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="p-3 rounded bg-light border">
                                                    <small class="text-muted d-block font-weight-bold mb-1">
                                                        <i class="fa fa-map-marker text-danger mr-1"></i> Alamat Domisili:
                                                    </small>
                                                    <span class="text-dark" style="font-size: 13px; line-height: 1.5;">
                                                        <?= html_escape($student->alamat ?: 'Jl. Anggrek No. 12, Kebayoran Baru, Jakarta Selatan') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- KOLOM KANAN: DOSEN WALI -->
                            <div class="col-lg-5">
                                <div class="card student-profile-card h-100">
                                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-user-md text-info mr-2"></i> Dosen Wali
                                        </h5>
                                        <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px;" data-toggle="modal" data-target="#modalPilihWaliBeranda">
                                            <i class="fa fa-pencil mr-1"></i> Ganti Wali
                                        </button>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="advisor-badge-card p-4 text-center mb-3">
                                            <div class="mb-3">
                                                <div style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);">
                                                    <i class="fa fa-user-circle"></i>
                                                </div>
                                            </div>
                                            <h5 class="font-weight-bold text-dark mb-1">
                                                <?= html_escape($student->nama_dosen_wali ?: 'Dr. Sugeng Santoso, M.Kom.') ?>
                                            </h5>
                                            <div class="badge badge-info px-3 py-1 font-weight-bold mb-3" style="border-radius: 12px; font-size: 11px;">
                                                Dosen Wali
                                            </div>
                                            <div class="text-left bg-white p-3 rounded border" style="font-size: 13px;">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="gap: 10px;">
                                                    <span class="text-muted">NIDN / Kode Dosen:</span>
                                                    <strong class="text-dark font-monospace"><?= html_escape($student->nidn_dosen_wali ?: 'sugeng') ?></strong>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="gap: 10px;">
                                                    <span class="text-muted">Email Dosen:</span>
                                                    <strong class="text-dark text-truncate" style="max-width: 220px;" title="<?= html_escape($student->email_dosen_wali ?: 'sugeng@staff.campus.ac.id') ?>"><?= html_escape($student->email_dosen_wali ?: 'sugeng@staff.campus.ac.id') ?></strong>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center py-2" style="gap: 10px;">
                                                    <span class="text-muted">Status Validasi KRS:</span>
                                                    <strong class="<?= ($krs_status === 'DISETUJUI') ? 'text-success' : 'text-warning' ?>">
                                                        <i class="fa <?= ($krs_status === 'DISETUJUI') ? 'fa-check-circle' : 'fa-clock-o' ?> mr-1"></i>
                                                        <?= $krs_label ?>
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded" style="background: #eef2ff; border: 1px dashed #c7d2fe; font-size: 12px; color: #3730a3;">
                                            <i class="fa fa-info-circle mr-1"></i>
                                            <strong>Konsultasi Akademik:</strong> Dosen wali bertugas memvalidasi beban studi, memberikan persetujuan KRS, serta mendampingi kelancaran masa perkuliahan Anda.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- JADWAL PERKULIAHAN & AGENDA AKADEMIK -->
                        <div class="row">
                            <!-- JADWAL KULIAH HARI INI & JADWAL MINGGUAN -->
                            <div class="col-lg-8 mb-4">
                                <div class="card student-profile-card h-100">
                                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="font-weight-bold text-dark mb-0">
                                                <i class="fa fa-calendar-check-o text-primary mr-2"></i> Jadwal Perkuliahan Anda
                                            </h5>
                                            <small class="text-muted">Mata kuliah aktif semester aktif ini</small>
                                        </div>
                                        <a href="<?= base_url('akademik') ?>" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px; font-weight: 600;">
                                            Lihat Kalender Lengkap &raquo;
                                        </a>
                                    </div>
                                    <div class="card-body pt-0">

                                        <!-- SUB-SECTION: HARI INI -->
                                        <div class="mb-4">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 13.5px;">
                                                    <i class="fa fa-clock-o text-warning mr-1"></i> Kuliah Hari Ini (<?= $today ?>)
                                                </h6>
                                                <span class="badge badge-light border text-muted"><?= count($today_courses) ?> Mata Kuliah</span>
                                            </div>

                                            <?php if (empty($today_courses)): ?>
                                                <div class="p-3 text-center rounded border" style="background: #f8fafc;">
                                                    <i class="fa fa-coffee text-muted mr-1" style="font-size: 16px;"></i>
                                                    <span class="text-muted small">Tidak ada jadwal kuliah pada hari <?= $today ?>. Selamat belajar mandiri!</span>
                                                </div>
                                            <?php else: ?>
                                                <?php foreach ($today_courses as $tc): ?>
                                                    <div class="schedule-badge-today p-3 mb-2">
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <div>
                                                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px;">
                                                                    <?= html_escape($tc->nama_mk) ?>
                                                                </h6>
                                                                <div class="small text-muted">
                                                                    <span class="badge badge-primary mr-1" style="border-radius: 4px;"><?= html_escape($tc->kode_mk) ?></span>
                                                                    <span class="mr-2"><i class="fa fa-clock-o mr-1"></i><?= substr($tc->waktu_mulai, 0, 5) ?> - <?= substr($tc->waktu_selesai, 0, 5) ?> WIB</span>
                                                                    <span class="mr-2"><i class="fa fa-building-o mr-1"></i><?= html_escape($tc->ruangan ?: 'Lab Komputer') ?></span>
                                                                    <span><i class="fa fa-bookmark-o mr-1"></i><?= (int)$tc->sks ?> SKS</span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2 mt-sm-0 text-sm-right">
                                                                <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 11px;">
                                                                    <i class="fa fa-circle text-white mr-1" style="font-size: 8px;"></i> Terjadwal
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>

                                        <!-- SUB-SECTION: DAFTAR SEMUA MATA KULIAH SEMESTER INI -->
                                        <h6 class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                                            <i class="fa fa-list text-primary mr-1"></i> Semua Mata Kuliah Semester Ini
                                        </h6>
                                        <?php if (empty($courses)): ?>
                                            <div class="text-center py-4 rounded border" style="background: #f8fafc;">
                                                <i class="fa fa-folder-open-o text-muted mb-2 d-block" style="font-size: 28px;"></i>
                                                <p class="text-muted small mb-2">Anda belum mengambil mata kuliah untuk semester ini.</p>
                                                <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-primary btn-sm px-3 font-weight-bold" style="border-radius: 8px;">
                                                    <i class="fa fa-plus-circle mr-1"></i> Ambil Mata Kuliah Sekarang
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm border mb-0" style="font-size: 12.5px;">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Kode & Nama Mata Kuliah</th>
                                                            <th class="text-center">SKS</th>
                                                            <th>Jadwal Kuliah</th>
                                                            <th>Ruang</th>
                                                            <th>Kelas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($courses as $c): ?>
                                                            <tr>
                                                                <td>
                                                                    <strong class="text-dark d-block"><?= html_escape($c->nama_mk) ?></strong>
                                                                    <small class="text-muted"><?= html_escape($c->kode_mk) ?></small>
                                                                </td>
                                                                <td class="text-center font-weight-bold text-primary"><?= (int)$c->sks ?></td>
                                                                <td>
                                                                    <span class="font-weight-bold text-dark"><?= html_escape($c->hari) ?></span><br>
                                                                    <small class="text-muted"><?= substr($c->waktu_mulai, 0, 5) ?> - <?= substr($c->waktu_selesai, 0, 5) ?></small>
                                                                </td>
                                                                <td><?= html_escape($c->ruangan ?: 'Lab') ?></td>
                                                                <td><span class="badge badge-light border"><?= html_escape($c->kelas ?: 'Reguler') ?></span></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>

                            <!-- PINTASAN LAYANAN & AGENDA AKADEMIK -->
                            <div class="col-lg-4 mb-4">
                                <!-- AGENDA AKADEMIK PENTING -->
                                <div class="card student-profile-card mb-4">
                                    <div class="card-header bg-white py-3 border-0">
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-calendar text-warning mr-2"></i> Agenda Semester Ini
                                        </h5>
                                        <small class="text-muted">Kalender kegiatan akademik</small>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-start mb-3 pb-2 border-bottom">
                                            <div class="mr-3 text-center" style="width: 44px; height: 44px; border-radius: 10px; background: #e0e7ff; color: #4338ca; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                <strong style="font-size: 13px; line-height: 1;">KRS</strong>
                                                <small style="font-size: 9px; font-weight: 700;">AKTIF</small>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 12.5px;">Masa Bimbingan & KRS</strong>
                                                <small class="text-muted">Pengisian & validasi oleh Dosen Wali</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3 pb-2 border-bottom">
                                            <div class="mr-3 text-center" style="width: 44px; height: 44px; border-radius: 10px; background: #dcfce7; color: #15803d; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                <strong style="font-size: 13px; line-height: 1;">UTS</strong>
                                                <small style="font-size: 9px; font-weight: 700;">W-8</small>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 12.5px;">Ujian Tengah Semester</strong>
                                                <small class="text-muted">Evaluasi pembelajaran paruh semester</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 text-center" style="width: 44px; height: 44px; border-radius: 10px; background: #fee2e2; color: #b91c1c; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                <strong style="font-size: 13px; line-height: 1;">UAS</strong>
                                                <small style="font-size: 9px; font-weight: 700;">W-16</small>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 12.5px;">Ujian Akhir Semester</strong>
                                                <small class="text-muted">Penilaian akhir & pencetakan KHS</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PINTASAN CEPAT LAYANAN -->
                                <div class="card student-profile-card">
                                    <div class="card-header bg-white py-3 border-0">
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-th text-info mr-2"></i> Pintasan Layanan
                                        </h5>
                                        <small class="text-muted">Akses cepat menu akademik</small>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="quick-feature-box">
                                                    <i class="fa fa-pencil-square-o text-primary mr-2" style="font-size: 18px;"></i>
                                                    <span class="font-weight-bold" style="font-size: 12px;">Ambil KRS</span>
                                                </a>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <a href="<?= base_url('perwalian/frs') ?>" class="quick-feature-box">
                                                    <i class="fa fa-file-text-o text-success mr-2" style="font-size: 18px;"></i>
                                                    <span class="font-weight-bold" style="font-size: 12px;">Cetak FRS</span>
                                                </a>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <a href="<?= base_url('akademik') ?>" class="quick-feature-box">
                                                    <i class="fa fa-calendar text-warning mr-2" style="font-size: 18px;"></i>
                                                    <span class="font-weight-bold" style="font-size: 12px;">Jadwal</span>
                                                </a>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <a href="<?= base_url('keuangan') ?>" class="quick-feature-box">
                                                    <i class="fa fa-money text-danger mr-2" style="font-size: 18px;"></i>
                                                    <span class="font-weight-bold" style="font-size: 12px;">Keuangan</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL PILIH / GANTI DOSEN WALI DARI BERANDA -->
                        <div class="modal fade" id="modalPilihWaliBeranda" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
                                    <?= form_open('perwalian/pilih-dosen-wali') ?>
                                        <div class="modal-header bg-primary text-white" style="border-radius: 14px 14px 0 0;">
                                            <h5 class="modal-title font-weight-bold text-white">
                                                <i class="fa fa-user-circle mr-2"></i> Pilih Dosen Wali
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <p class="text-muted small mb-3">
                                                Pilih Dosen Wali Anda untuk keperluan peninjauan dan persetujuan pengajuan Kartu Rencana Studi (KRS).
                                            </p>
                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold small text-dark">Dosen Wali:</label>
                                                <select name="dosen_id" class="form-control" required style="border-radius: 8px; height: 44px;">
                                                    <option value="">-- Pilih Dosen Wali --</option>
                                                    <?php if (!empty($all_lecturers)): ?>
                                                        <?php foreach ($all_lecturers as $lec): ?>
                                                            <option value="<?= (int)$lec->id ?>" <?= (!empty($student->dosen_id) && (int)$student->dosen_id === (int)$lec->id) ? 'selected' : '' ?>>
                                                                <?= html_escape($lec->nama_lengkap) ?> (<?= html_escape($lec->prodi ?: 'Dosen') ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light" style="border-radius: 0 0 14px 14px;">
                                            <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold" style="border-radius: 8px;">
                                                <i class="fa fa-save mr-1"></i> Simpan Pilihan
                                            </button>
                                        </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>