<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Pengaturan nama hari dalam bahasa Indonesia & deteksi hari ini
$days_map = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$today_indonesia = $days_map[date('l')] ?? 'Senin';

// Palet warna estetik untuk tiap hari perkuliahan
$day_palette = [
    'Senin'  => ['bg' => '#eef2ff', 'color' => '#3730a3', 'border' => '#818cf8', 'pill_bg' => '#4338ca', 'pill_color' => '#ffffff'],
    'Selasa' => ['bg' => '#ecfdf5', 'color' => '#065f46', 'border' => '#34d399', 'pill_bg' => '#059669', 'pill_color' => '#ffffff'],
    'Rabu'   => ['bg' => '#fffbeb', 'color' => '#92400e', 'border' => '#fcd34d', 'pill_bg' => '#d97706', 'pill_color' => '#ffffff'],
    'Kamis'  => ['bg' => '#faf5ff', 'color' => '#6b21a8', 'border' => '#c084fc', 'pill_bg' => '#7c3aed', 'pill_color' => '#ffffff'],
    'Jumat'  => ['bg' => '#f0fdfa', 'color' => '#115e59', 'border' => '#5eead4', 'pill_bg' => '#0d9488', 'pill_color' => '#ffffff'],
    'Sabtu'  => ['bg' => '#fff1f2', 'color' => '#9f1239', 'border' => '#fda4af', 'pill_bg' => '#e11d48', 'pill_color' => '#ffffff'],
    'Minggu' => ['bg' => '#f8fafc', 'color' => '#334155', 'border' => '#cbd5e1', 'pill_bg' => '#64748b', 'pill_color' => '#ffffff'],
];

// Ringkasan data jadwal & grouping per hari
$courses_by_day = [
    'Senin'  => [],
    'Selasa' => [],
    'Rabu'   => [],
    'Kamis'  => [],
    'Jumat'  => [],
    'Sabtu'  => []
];
$total_sks = 0;
$total_mk = 0;
$unique_days = [];

if (!empty($krs) && !empty($krs->mata_kuliah)) {
    foreach ($krs->mata_kuliah as $mk) {
        $hari = trim($mk->hari);
        if (!isset($courses_by_day[$hari])) {
            $courses_by_day[$hari] = [];
        }
        $courses_by_day[$hari][] = $mk;
        $total_sks += (int)$mk->sks;
        $total_mk++;
        $unique_days[$hari] = true;
    }
}
$active_days_count = count($unique_days);
?>

<style>
/* ======================================================== */
/* MODERN JADWAL KULIAH STYLING                             */
/* ======================================================== */

.jadwal-hero-card {
    border-radius: 18px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-top: 4px solid #3b82f6 !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
    position: relative;
    overflow: hidden;
}

/* Stat Box Clean */
.stat-box-clean {
    border-radius: 14px;
    padding: 13px 15px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.stat-box-clean:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

/* View Mode Switcher & Filter Pills */
.filter-pill-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    padding: 7px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-block;
    margin-right: 6px;
    margin-bottom: 8px;
    text-decoration: none !important;
}
.filter-pill-btn:hover {
    background: #f1f5f9;
    color: #1e293b;
}
.filter-pill-btn.active {
    background: #3b82f6;
    color: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Segmented View Mode Switcher (Mode Tabel / Mode Kartu Hari) */
.view-mode-btn-group {
    display: inline-flex !important;
    align-items: stretch;
    border: 1.5px solid #0288d1 !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    padding: 0 !important;
    background: #ffffff !important;
    box-shadow: 0 2px 8px rgba(2, 136, 209, 0.12) !important;
}

.view-mode-btn-group .btn-mode-switch,
.view-mode-btn-group .btn {
    border: none !important;
    border-radius: 0 !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    padding: 8px 18px !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    outline: none !important;
}

.view-mode-btn-group .btn-mode-switch.active,
.view-mode-btn-group .btn.btn-primary {
    background-color: #0288d1 !important;
    color: #ffffff !important;
    border: none !important;
}

.view-mode-btn-group .btn-mode-switch:not(.active),
.view-mode-btn-group .btn.btn-outline-primary {
    background-color: #ffffff !important;
    color: #0288d1 !important;
    border: none !important;
}

.view-mode-btn-group .btn-mode-switch:not(.active):hover,
.view-mode-btn-group .btn.btn-outline-primary:hover {
    background-color: #f0f9ff !important;
    color: #0288d1 !important;
}

/* Search Box Container */
.search-jadwal-input {
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    padding: 9px 16px 9px 40px;
    font-size: 13.5px;
    transition: all 0.2s ease;
    background: #ffffff;
}
.search-jadwal-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}

/* Modern Card for Courses */
.course-card-modern {
    border-radius: 14px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.course-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    border-color: #cbd5e1;
}

/* Day Column Header */
.day-header-badge {
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}

/* Table Styling */
.table-jadwal-modern {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    min-width: 950px; /* Cegah tabel jadwal terhimpit */
}
.table-jadwal-modern thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 14px 16px;
    border-top: 1px solid #e2e8f0;
    border-bottom: 2px solid #cbd5e1;
    white-space: nowrap;
}
.table-jadwal-modern tbody tr {
    transition: background 0.15s ease;
}
.table-jadwal-modern tbody tr:hover {
    background-color: #f8faff;
}
.table-jadwal-modern tbody td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #edf2f7;
    font-size: 13.5px;
    line-height: 1.5;
}

/* Pulse animation for today indicator */
.pulse-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    margin-right: 6px;
    animation: pulseGlow 1.8s infinite;
}
@keyframes pulseGlow {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1.15); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Print Specific Optimizations */
@media print {
    body {
        background: #ffffff !important;
        font-size: 12px !important;
    }
    .pcoded-header,
    .pcoded-navbar,
    .btn,
    .view-mode-controls,
    .search-filter-section,
    .jadwal-hero-card .btn,
    .breadcrumbs,
    footer {
        display: none !important;
    }
    .pcoded-content,
    .pcoded-inner-content,
    .main-body,
    .page-wrapper,
    .page-body {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .jadwal-print-header {
        display: block !important;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
    .table-jadwal-modern thead th {
        background: #eee !important;
        color: #000 !important;
    }
    .table-jadwal-modern td, .table-jadwal-modern th {
        border: 1px solid #666 !important;
    }
    #viewCards {
        display: none !important;
    }
    #viewTable {
        display: block !important;
    }
}
@media screen {
    .jadwal-print-header {
        display: none;
    }
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- HEADER KHUSUS CETAK DOKUMEN RESMI (HANYA MUNCUL SAAT PRINT) -->
                    <div class="jadwal-print-header text-center">
                        <h4 class="font-weight-bold mb-1" style="text-transform: uppercase;">SMART CAMPUS UNIVERSITY</h4>
                        <h5 class="font-weight-bold mb-1">JADWAL KULIAH MAHASISWA (KRS RESMI)</h5>
                        <p class="mb-2">Tahun Akademik <?= html_escape($active_period['tahun_akademik']) ?> - Semester <?= strtoupper(html_escape($active_period['semester_akademik'])) ?></p>
                        <div class="row text-left my-2" style="font-size: 12px;">
                            <div class="col-6">
                                <div><strong>NIM:</strong> <?= html_escape($student->nim ?? '-') ?></div>
                                <div><strong>Nama Mahasiswa:</strong> <?= html_escape($student->nama_lengkap ?? '-') ?></div>
                                <div><strong>Program Studi:</strong> <?= html_escape($student->prodi ?? 'Teknik Informatika') ?></div>
                            </div>
                            <div class="col-6 text-right">
                                <div><strong>Dosen Wali:</strong> <?= html_escape($krs->nama_dosen_wali ?? 'Dr. Sugeng Santoso, M.Kom.') ?></div>
                                <div><strong>Total Beban SKS:</strong> <?= (int)$total_sks ?> SKS</div>
                                <div><strong>Tanggal Cetak:</strong> <?= date('d F Y') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- HERO BANNER INFORMASI JADWAL MAHASISWA                   -->
                    <!-- ======================================================== -->
                    <div class="card jadwal-hero-card mb-4">
                        <div class="card-body p-4 p-md-4">
                            <div class="row align-items-center">
                                <div class="col-lg-7 mb-4 mb-lg-0">
                                    <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11.5px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                            <i class="fa fa-calendar-check-o mr-1"></i> JADWAL KULIAH AKTIF
                                        </span>
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11.5px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                            <i class="fa fa-graduation-cap mr-1"></i> TA <?= html_escape($active_period['tahun_akademik']) ?> (SEMESTER <?= strtoupper(html_escape($active_period['semester_akademik'])) ?>)
                                        </span>
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11.5px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                            <i class="fa fa-check-circle mr-1"></i> STATUS: <?= html_escape($krs ? strtoupper($krs->status) : 'BELUM ISI') ?>
                                        </span>
                                    </div>
                                    <h3 class="font-weight-bold mb-2" style="font-size: 24px; letter-spacing: -0.3px; color: #0f172a !important;">
                                        Jadwal Perkuliahan Mahasiswa
                                    </h3>
                                    <p class="mb-3" style="font-size: 14px; max-width: 580px; line-height: 1.6; color: #64748b !important;">
                                        Pantau agenda tatap muka perkuliahan, ruangan kelas, jam mulai, dan dosen pengampu mata kuliah sesuai formulir KRS yang telah disetujui.
                                    </p>
                                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                        <button type="button" class="btn btn-warning font-weight-bold px-3 py-2" onclick="window.print();" style="border-radius: 10px; color: #1e1b4b; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                                            <i class="fa fa-print mr-1"></i> Cetak Jadwal (PDF)
                                        </button>
                                        <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-outline-primary font-weight-bold px-3 py-2" style="border-radius: 10px;">
                                            <i class="fa fa-book mr-1"></i> Rencana Studi (KRS)
                                        </a>
                                        <a href="<?= base_url('beranda') ?>" class="btn btn-outline-secondary font-weight-bold px-3 py-2" style="border-radius: 10px;">
                                            <i class="fa fa-home mr-1"></i> Kembali ke Beranda
                                        </a>
                                    </div>
                                </div>

                                <!-- 4 MINI STATS KANAN DENGAN KONTRAS TINGGI -->
                                <div class="col-lg-5">
                                    <div class="row" style="margin-left: -6px; margin-right: -6px;">
                                        <div class="col-6 mb-2" style="padding-left: 6px; padding-right: 6px;">
                                            <div class="stat-box-clean h-100" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10.5px; color: #2563eb; letter-spacing: 0.5px;">TOTAL SKS</small>
                                                <div class="font-weight-bold mt-1" style="font-size: 19px; color: #1e3a8a;">
                                                    <i class="fa fa-bookmark text-warning mr-1"></i> <?= (int)$total_sks ?> <span style="font-size: 13px; font-weight: normal; color: #475569;">SKS</span>
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 11px; color: #64748b;">Beban Semester Ini</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2" style="padding-left: 6px; padding-right: 6px;">
                                            <div class="stat-box-clean h-100" style="background: #f0fdfa; border: 1px solid #99f6e4;">
                                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10.5px; color: #0d9488; letter-spacing: 0.5px;">MATA KULIAH</small>
                                                <div class="font-weight-bold mt-1" style="font-size: 19px; color: #115e59;">
                                                    <i class="fa fa-th-list text-info mr-1"></i> <?= (int)$total_mk ?> <span style="font-size: 13px; font-weight: normal; color: #475569;">Kelas</span>
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 11px; color: #64748b;">Terdaftar Resmi</small>
                                            </div>
                                        </div>
                                        <div class="col-6" style="padding-left: 6px; padding-right: 6px;">
                                            <div class="stat-box-clean h-100" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10.5px; color: #16a34a; letter-spacing: 0.5px;">HARI AKTIF</small>
                                                <div class="font-weight-bold mt-1" style="font-size: 19px; color: #14532d;">
                                                    <i class="fa fa-calendar-o text-success mr-1"></i> <?= (int)$active_days_count ?> <span style="font-size: 13px; font-weight: normal; color: #475569;">Hari</span>
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 11px; color: #64748b;">Senin s/d Sabtu</small>
                                            </div>
                                        </div>
                                        <div class="col-6" style="padding-left: 6px; padding-right: 6px;">
                                            <div class="stat-box-clean h-100" style="background: #fffbeb; border: 1px solid #fde68a;">
                                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10.5px; color: #d97706; letter-spacing: 0.5px;">DOSEN WALI</small>
                                                <div class="font-weight-bold mt-1 text-truncate" style="font-size: 13.5px; color: #78350f;" title="<?= html_escape($krs->nama_dosen_wali ?? 'Dr. Sugeng Santoso, M.Kom.') ?>">
                                                    <i class="fa fa-user-circle text-warning mr-1"></i> <?= html_escape($krs->nama_dosen_wali ?? 'Dr. Sugeng Santoso') ?>
                                                </div>
                                                <small class="d-block mt-1 text-truncate" style="font-size: 11px; color: #64748b;">Pembimbing Akademik</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!$krs || $krs->status !== 'DISETUJUI'): ?>
                        <!-- JIKA STATUS KRS BELUM DISETUJUI -->
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body p-5 text-center">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: #fef3c7; color: #d97706; font-size: 32px;">
                                        <i class="fa fa-hourglass-half"></i>
                                    </div>
                                </div>
                                <h4 class="font-weight-bold text-dark mb-2">Jadwal Kuliah Belum Tersedia</h4>
                                <p class="text-muted mx-auto mb-4" style="max-width: 520px; font-size: 14.5px; line-height: 1.6;">
                                    Jadwal kuliah resmi dapat diakses setelah Rencana Studi (KRS) Anda disetujui oleh Dosen Wali. Status KRS Anda saat ini: 
                                    <span class="badge badge-warning px-2 py-1 font-weight-bold"><?= html_escape($krs ? $krs->status : 'BELUM DIBUAT') ?></span>.
                                </p>
                                <a href="<?= base_url('perwalian') ?>" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
                                    <i class="fa fa-folder-open-o mr-1"></i> Buka Menu Perwalian & KRS
                                </a>
                            </div>
                        </div>
                    <?php elseif (empty($krs->mata_kuliah)): ?>
                        <!-- JIKA TIDAK ADA MATA KULIAH DI KRS -->
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body p-5 text-center">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: #fee2e2; color: #dc2626; font-size: 32px;">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <h4 class="font-weight-bold text-dark mb-2">Belum Ada Mata Kuliah Terdaftar</h4>
                                <p class="text-muted mx-auto mb-4" style="max-width: 500px; font-size: 14.5px;">
                                    KRS Anda belum memiliki penawaran mata kuliah yang terdaftar pada semester ini.
                                </p>
                                <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
                                    <i class="fa fa-plus-circle mr-1"></i> Ambil Rencana Studi
                                </a>
                            </div>
                        </div>
                    <?php else: ?>

                        <!-- ======================================================== -->
                        <!-- BAR KONTROL INTERAKTIF: PENCARIAN & FILTER HARI          -->
                        <!-- ======================================================== -->
                        <div class="card border-0 shadow-sm mb-4 search-filter-section" style="border-radius: 16px;">
                            <div class="card-body p-3 p-md-4">
                                <div class="row align-items-center">
                                    <!-- Search Input -->
                                    <div class="col-lg-5 col-md-6 mb-3 mb-lg-0">
                                        <div class="position-relative">
                                            <i class="fa fa-search position-absolute text-muted" style="top: 13px; left: 14px; font-size: 14px;"></i>
                                            <input type="text" id="inputCariJadwal" class="form-control search-jadwal-input" placeholder="Cari nama mata kuliah, kode, ruangan, dosen..." onkeyup="filterJadwalInteractive()">
                                        </div>
                                    </div>

                                    <!-- Switch Mode Tampilan & Info Hari Ini -->
                                    <div class="col-lg-7 col-md-6 text-md-right view-mode-controls">
                                        <div class="btn-group view-mode-btn-group" role="group">
                                            <button type="button" id="btnModeTable" class="btn btn-mode-switch active" onclick="switchViewMode('table')">
                                                <i class="fa fa-table mr-1.5"></i> Mode Tabel
                                            </button>
                                            <button type="button" id="btnModeCards" class="btn btn-mode-switch" onclick="switchViewMode('cards')">
                                                <i class="fa fa-th-large mr-1.5"></i> Mode Kartu Hari
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Tab Hari Kuliah -->
                                <hr class="my-3" style="border-color: #f1f5f9;">
                                <div class="d-flex align-items-center flex-wrap" style="gap: 4px;">
                                    <span class="text-muted font-weight-bold mr-2 mb-2" style="font-size: 12.5px;">
                                        <i class="fa fa-filter mr-1"></i> FILTER HARI:
                                    </span>
                                    <a href="javascript:void(0)" class="filter-pill-btn active" data-day="ALL" onclick="filterByDay('ALL', this)">
                                        Semua Hari <span class="badge badge-light text-dark ml-1"><?= (int)$total_mk ?></span>
                                    </a>
                                    <?php 
                                    $all_weekdays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    foreach ($all_weekdays as $day): 
                                        $count_day = count($courses_by_day[$day] ?? []);
                                        if ($count_day === 0) continue;
                                        $is_today = ($day === $today_indonesia);
                                    ?>
                                        <a href="javascript:void(0)" class="filter-pill-btn" data-day="<?= $day ?>" onclick="filterByDay('<?= $day ?>', this)">
                                            <?php if ($is_today): ?>
                                                <span class="pulse-indicator"></span>
                                            <?php endif; ?>
                                            <?= $day ?>
                                            <span class="badge badge-light text-dark ml-1"><?= $count_day ?></span>
                                            <?php if ($is_today): ?>
                                                <span class="badge badge-success ml-1" style="font-size: 9px; padding: 2px 5px;">Hari Ini</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- ======================================================== -->
                        <!-- TAMPILAN 1: MODE TABEL LENGKAP & ESTETIK (DEFAULT)       -->
                        <!-- ======================================================== -->
                        <div id="viewTable" class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #eef2f6;">
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 16px;">
                                        <i class="fa fa-list-alt text-primary mr-2"></i> Daftar Lengkap Jadwal Perkuliahan
                                    </h5>
                                    <small class="text-muted">Periode Semester <?= html_escape($active_period['semester_akademik']) ?> <?= html_escape($active_period['tahun_akademik']) ?></small>
                                </div>
                                <div>
                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                        <span id="filteredCount"><?= (int)$total_mk ?></span> dari <?= (int)$total_mk ?> Mata Kuliah
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-jadwal-modern mb-0" id="tableJadwal">
                                        <thead>
                                            <tr>
                                                <th style="width: 105px;">KODE</th>
                                                <th>MATA KULIAH</th>
                                                <th class="text-center" style="width: 75px;">SKS</th>
                                                <th style="width: 90px;">KELAS</th>
                                                <th style="width: 180px;">HARI / WAKTU</th>
                                                <th style="width: 170px;">RUANGAN</th>
                                                <th>DOSEN PENGAMPU</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($krs->mata_kuliah as $offering): 
                                                $palette = $day_palette[$offering->hari] ?? $day_palette['Minggu'];
                                                $is_today = ($offering->hari === $today_indonesia);
                                                $search_text = strtolower($offering->kode_mk . ' ' . $offering->nama_mk . ' ' . $offering->hari . ' ' . $offering->ruangan . ' ' . $offering->nama_dosen . ' ' . $offering->kelas);
                                            ?>
                                                <tr class="row-jadwal" data-day="<?= html_escape($offering->hari) ?>" data-search="<?= html_escape($search_text) ?>">
                                                    <!-- KODE MK -->
                                                    <td>
                                                        <span class="font-weight-bold px-2 py-1" style="background: #f1f5f9; color: #334155; border-radius: 6px; font-size: 12.5px; font-family: monospace; border: 1px solid #e2e8f0; display: inline-block;">
                                                            <?= html_escape($offering->kode_mk) ?>
                                                        </span>
                                                    </td>

                                                    <!-- MATA KULIAH -->
                                                    <td>
                                                        <div class="font-weight-bold text-dark" style="font-size: 14.5px; color: #1e293b;">
                                                            <?= html_escape($offering->nama_mk) ?>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1" style="gap: 6px;">
                                                            <span class="badge badge-light border text-muted px-2 py-0" style="font-size: 11px;">
                                                                <?= html_escape($offering->kategori_label ?? 'Mata Kuliah Wajib') ?>
                                                            </span>
                                                            <?php if (!empty($offering->prodi)): ?>
                                                                <small class="text-muted" style="font-size: 11px;">&bull; <?= html_escape($offering->prodi) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>

                                                    <!-- SKS -->
                                                    <td class="text-center">
                                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                                            <?= (int)$offering->sks ?> SKS
                                                        </span>
                                                    </td>

                                                    <!-- KELAS -->
                                                    <td>
                                                        <span class="badge badge-light border font-weight-bold px-2 py-1" style="border-radius: 6px; font-size: 12px; color: #475569;">
                                                            <?= html_escape($offering->kelas) ?>
                                                        </span>
                                                    </td>

                                                    <!-- HARI & WAKTU -->
                                                    <td>
                                                        <div class="d-inline-flex align-items-center font-weight-bold px-2 py-1 mb-1" style="background: <?= $palette['bg'] ?>; color: <?= $palette['color'] ?>; border-radius: 6px; font-size: 12px; border: 1px solid <?= $palette['border'] ?>;">
                                                            <?php if ($is_today): ?>
                                                                <span class="pulse-indicator"></span>
                                                            <?php endif; ?>
                                                            <?= html_escape($offering->hari) ?>
                                                            <?php if ($is_today): ?>
                                                                <span class="badge badge-success ml-1" style="font-size: 9px; padding: 1px 4px;">Hari Ini</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="font-weight-bold text-dark mt-1" style="font-size: 13px;">
                                                            <i class="fa fa-clock-o text-muted mr-1"></i>
                                                            <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?> - <?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?> WIB
                                                        </div>
                                                    </td>

                                                    <!-- RUANGAN -->
                                                    <td>
                                                        <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                            <i class="fa fa-map-marker text-danger mr-1"></i>
                                                            <?= html_escape($offering->ruangan) ?>
                                                        </div>
                                                        <small class="text-muted d-block" style="font-size: 11px;">Gedung Kuliah</small>
                                                    </td>

                                                    <!-- DOSEN -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mr-2" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; font-size: 14px; flex-shrink: 0;">
                                                                <i class="fa fa-user"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                                    <?= html_escape($offering->nama_dosen ?: 'Dr. Ir. Hendra Setiawan, M.T.') ?>
                                                                </div>
                                                                <small class="text-muted" style="font-size: 11px;">Dosen Pengampu</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pesan Kosong Jika Hasil Pencarian Nihil -->
                                <div id="noMatchAlert" class="p-5 text-center" style="display: none;">
                                    <i class="fa fa-search text-muted mb-2" style="font-size: 32px;"></i>
                                    <h6 class="font-weight-bold text-dark mb-1">Mata Kuliah Tidak Ditemukan</h6>
                                    <p class="text-muted small mb-0">Coba ganti kata kunci pencarian atau pilih filter "Semua Hari".</p>
                                </div>
                            </div>
                        </div>

                        <!-- ======================================================== -->
                        <!-- TAMPILAN 2: MODE KARTU PER HARI (GRID / TIMELINE)         -->
                        <!-- ======================================================== -->
                        <div id="viewCards" style="display: none;">
                            <div class="row">
                                <?php foreach ($all_weekdays as $day): 
                                    $day_courses = $courses_by_day[$day] ?? [];
                                    if (empty($day_courses)) continue;
                                    $palette = $day_palette[$day] ?? $day_palette['Minggu'];
                                    $is_today = ($day === $today_indonesia);
                                ?>
                                    <div class="col-lg-6 mb-4 card-day-group" data-day="<?= $day ?>">
                                        <div class="day-header-badge" style="background: <?= $palette['bg'] ?>; color: <?= $palette['color'] ?>; border: 1px solid <?= $palette['border'] ?>;">
                                            <div>
                                                <?php if ($is_today): ?>
                                                    <span class="pulse-indicator"></span>
                                                <?php endif; ?>
                                                <i class="fa fa-calendar-check-o mr-1"></i> <?= $day ?>
                                                <?php if ($is_today): ?>
                                                    <span class="badge badge-success ml-2 font-weight-bold" style="font-size: 10px;">HARI INI</span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="badge badge-pill" style="background: <?= $palette['pill_bg'] ?>; color: <?= $palette['pill_color'] ?>; font-size: 11px; padding: 4px 10px;">
                                                <?= count($day_courses) ?> Mata Kuliah
                                            </span>
                                        </div>

                                        <!-- Daftar Kartu Mata Kuliah di Hari Ini -->
                                        <div class="courses-day-list">
                                            <?php foreach ($day_courses as $mk): 
                                                $search_text = strtolower($mk->kode_mk . ' ' . $mk->nama_mk . ' ' . $mk->hari . ' ' . $mk->ruangan . ' ' . $mk->nama_dosen . ' ' . $mk->kelas);
                                            ?>
                                                <div class="course-card-modern p-3 mb-3 item-card-jadwal" data-day="<?= $day ?>" data-search="<?= html_escape($search_text) ?>" style="border-left: 4px solid <?= $palette['pill_bg'] ?>;">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="font-weight-bold px-2 py-1" style="background: #f1f5f9; color: #334155; border-radius: 6px; font-size: 11.5px; font-family: monospace; border: 1px solid #e2e8f0;">
                                                            <?= html_escape($mk->kode_mk) ?>
                                                        </span>
                                                        <div class="d-flex align-items-center" style="gap: 5px;">
                                                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 11.5px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                                                <?= (int)$mk->sks ?> SKS
                                                            </span>
                                                            <span class="badge badge-light border font-weight-bold px-2 py-1" style="border-radius: 6px; font-size: 11.5px;">
                                                                Kelas <?= html_escape($mk->kelas) ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <h5 class="font-weight-bold text-dark mb-2" style="font-size: 15px; line-height: 1.4;">
                                                        <?= html_escape($mk->nama_mk) ?>
                                                    </h5>

                                                    <div class="d-flex flex-wrap text-muted mb-2" style="font-size: 12.5px; gap: 14px;">
                                                        <div>
                                                            <i class="fa fa-clock-o text-primary mr-1"></i>
                                                            <strong class="text-dark"><?= html_escape(substr($mk->waktu_mulai, 0, 5)) ?> - <?= html_escape(substr($mk->waktu_selesai, 0, 5)) ?> WIB</strong>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-map-marker text-danger mr-1"></i>
                                                            <strong class="text-dark"><?= html_escape($mk->ruangan) ?></strong>
                                                        </div>
                                                    </div>

                                                    <div class="pt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid #f1f5f9; font-size: 12px;">
                                                        <div class="d-flex align-items-center text-truncate" style="max-width: 80%;">
                                                            <i class="fa fa-user-circle text-muted mr-1"></i>
                                                            <span class="font-weight-bold text-dark text-truncate">
                                                                <?= html_escape($mk->nama_dosen ?: 'Dr. Ir. Hendra Setiawan, M.T.') ?>
                                                            </span>
                                                        </div>
                                                        <span class="badge badge-light text-muted px-2 py-1 border" style="font-size: 10.5px;">
                                                            <?= html_escape($mk->kategori_label ?? 'Wajib') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- INTERACTIVE JAVASCRIPT: FILTER HARI, PENCARIAN, VIEW MODE-->
<!-- ======================================================== -->
<script>
var currentActiveDay = 'ALL';
var currentViewMode = 'table';

function switchViewMode(mode) {
    currentViewMode = mode;
    var tableEl = document.getElementById('viewTable');
    var cardsEl = document.getElementById('viewCards');
    var btnTable = document.getElementById('btnModeTable');
    var btnCards = document.getElementById('btnModeCards');

    if (mode === 'table') {
        tableEl.style.display = 'block';
        cardsEl.style.display = 'none';
        btnTable.className = 'btn btn-mode-switch active';
        btnCards.className = 'btn btn-mode-switch';
    } else {
        tableEl.style.display = 'none';
        cardsEl.style.display = 'block';
        btnTable.className = 'btn btn-mode-switch';
        btnCards.className = 'btn btn-mode-switch active';
    }
    filterJadwalInteractive();
}

function filterByDay(day, btnElem) {
    currentActiveDay = day;
    
    // Update active state di pill buttons
    var pills = document.querySelectorAll('.filter-pill-btn');
    pills.forEach(function(pill) {
        pill.classList.remove('active');
    });
    if (btnElem) {
        btnElem.classList.add('active');
    }

    filterJadwalInteractive();
}

function filterJadwalInteractive() {
    var searchInput = document.getElementById('inputCariJadwal');
    var keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
    
    // 1. Filter baris di Mode Tabel
    var rows = document.querySelectorAll('.row-jadwal');
    var visibleCount = 0;

    rows.forEach(function(row) {
        var rowDay = row.getAttribute('data-day');
        var rowSearch = row.getAttribute('data-search') || '';

        var matchDay = (currentActiveDay === 'ALL' || rowDay === currentActiveDay);
        var matchKeyword = (keyword === '' || rowSearch.indexOf(keyword) !== -1);

        if (matchDay && matchKeyword) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    var countElem = document.getElementById('filteredCount');
    if (countElem) {
        countElem.innerText = visibleCount;
    }

    var noMatch = document.getElementById('noMatchAlert');
    if (noMatch) {
        noMatch.style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    // 2. Filter item di Mode Kartu
    var cardItems = document.querySelectorAll('.item-card-jadwal');
    cardItems.forEach(function(card) {
        var cardDay = card.getAttribute('data-day');
        var cardSearch = card.getAttribute('data-search') || '';

        var matchDay = (currentActiveDay === 'ALL' || cardDay === currentActiveDay);
        var matchKeyword = (keyword === '' || cardSearch.indexOf(keyword) !== -1);

        card.style.display = (matchDay && matchKeyword) ? 'block' : 'none';
    });

    // Sembunyikan kolom hari jika seluruh kartu di dalamnya tersembunyi
    var dayGroups = document.querySelectorAll('.card-day-group');
    dayGroups.forEach(function(group) {
        var groupDay = group.getAttribute('data-day');
        if (currentActiveDay !== 'ALL' && groupDay !== currentActiveDay) {
            group.style.display = 'none';
        } else {
            var visibleChildren = group.querySelectorAll('.item-card-jadwal:not([style*="display: none"])');
            group.style.display = (visibleChildren.length > 0) ? 'block' : 'none';
        }
    });
}
</script>
