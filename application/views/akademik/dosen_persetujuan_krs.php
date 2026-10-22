<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* Custom Aesthetic Styling for KRS Validation Hub */
.krs-stat-card {
    border-radius: 14px;
    border: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    overflow: hidden;
    position: relative;
}
.krs-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.09);
}
.krs-stat-card .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.krs-filter-pill {
    height: 38px;
    padding: 0 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    color: #475569;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    text-decoration: none !important;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.krs-filter-pill:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}
.krs-filter-pill.active {
    background: #4f46e5;
    color: #ffffff !important;
    border-color: #4f46e5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.28);
}
.krs-filter-pill.active i {
    color: #ffffff !important;
}
.krs-filter-pill .badge {
    margin-left: 7px;
    font-size: 11px;
    font-weight: 700;
    padding: 2.5px 8px;
    border-radius: 12px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.krs-filter-pill.active .badge {
    background: rgba(255, 255, 255, 0.25);
    border-color: transparent;
    color: #ffffff;
}
.student-krs-card {
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    margin-bottom: 24px;
    transition: all 0.25s ease;
    overflow: hidden;
}
.student-krs-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}
.student-avatar {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    flex-shrink: 0;
    text-transform: uppercase;
}
.badge-category-wajib {
    background-color: #e0e7ff;
    color: #3730a3;
    font-weight: 600;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}
.badge-category-jurusan {
    background-color: #dbeafe;
    color: #1e40af;
    font-weight: 600;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}
.badge-category-luar {
    background-color: #fef3c7;
    color: #92400e;
    font-weight: 600;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}
.badge-category-umum {
    background-color: #f3e8ff;
    color: #6b21a8;
    font-weight: 600;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}
.badge-conflict {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #f87171;
    font-weight: 700;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    animation: pulseConflict 1.8s infinite;
}
@keyframes pulseConflict {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
.timetable-mini-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    background: #f8fafc;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}
.timetable-day-col {
    background: #ffffff;
    border-radius: 8px;
    padding: 8px;
    min-height: 120px;
    border: 1px solid #f1f5f9;
}
.timetable-day-header {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    text-align: center;
    margin-bottom: 6px;
    padding-bottom: 4px;
    border-bottom: 1px solid #e2e8f0;
}
.timetable-event {
    font-size: 10px;
    padding: 5px 6px;
    border-radius: 6px;
    margin-bottom: 4px;
    background: #e0f2fe;
    color: #0369a1;
    border-left: 3px solid #0284c7;
    line-height: 1.25;
}
.timetable-event.conflict {
    background: #fee2e2;
    color: #991b1b;
    border-left: 3px solid #dc2626;
}
.progress-sks {
    height: 8px;
    border-radius: 4px;
    background: #e2e8f0;
    overflow: hidden;
}
.sks-breakdown-tag {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    margin-right: 4px;
    margin-bottom: 4px;
}

/* ADVISOR EDIT MODAL & RESPONSIVE IMPROVEMENTS */
#modalAdvisorEditKrs .modal-xl {
    max-width: 95vw;
    width: 1240px;
    margin: 1.5rem auto;
}
@media (max-width: 1280px) {
    #modalAdvisorEditKrs .modal-xl {
        max-width: 96vw;
        width: 96vw;
    }
}
.adv-cat-filter {
    transition: all 0.2s ease;
    border-color: #cbd5e1;
    color: #475569;
    background: #ffffff;
}
.adv-cat-filter:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #94a3b8;
}
.adv-cat-filter.active {
    background: #2563eb !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
    box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25);
}
#advTableCourses th {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    background: #f8fafc;
    border-top: none;
    border-bottom: 2px solid #e2e8f0;
}
#advTableCourses td {
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
}
#advTableCourses tbody tr:hover {
    background-color: #f8fafc;
}
#advTableCourses tbody tr.table-info {
    background-color: #eff6ff !important;
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- FLASH MESSAGE ALERTS -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981;">
                            <i class="fa fa-check-circle mr-2 text-success" style="font-size: 16px;"></i>
                            <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444;">
                            <i class="fa fa-exclamation-triangle mr-2 text-danger" style="font-size: 16px;"></i>
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- PAGE HEADER & ACADEMIC BANNER -->
                    <div class="card shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; background: #ffffff;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                            <i class="fa fa-calendar-check-o text-primary mr-1"></i> TAHUN AKADEMIK <?= html_escape($academic_period['tahun_akademik']) ?> (SEMESTER <?= strtoupper(html_escape($academic_period['semester_akademik'])) ?>)
                                        </span>
                                        <?php if ($is_admin): ?>
                                            <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                                <i class="fa fa-shield mr-1"></i> MODE ADMINISTRATOR BIRO
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                                <i class="fa fa-user-md mr-1"></i> DOSEN WALI
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="font-weight-bold mb-1" style="color: #0f172a !important;">Persetujuan KRS Mahasiswa Bimbingan</h4>
                                    <p class="mb-0" style="font-size: 14px; color: #64748b !important;">
                                        Tinjau rencana studi, kesesuaian kuota SKS, jadwal kuliah, status SPP, dan setujui KRS mahasiswa bimbingan Anda.
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                    <div class="d-inline-block text-dark p-3 text-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; min-width: 140px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                        <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">PERLU TINDAKAN</small>
                                        <span class="font-weight-bold text-danger" style="font-size: 28px; line-height: 1.2;">
                                            <?= (int)($stats['pending'] ?? 0) ?>
                                        </span>
                                        <small class="text-muted d-block" style="font-size: 10px;">Mahasiswa Menunggu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STATS CARDS -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card krs-stat-card border-left border-warning" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Validasi</div>
                                        <div class="h4 font-weight-bold text-warning mb-0"><?= (int)($stats['pending'] ?? 0) ?></div>
                                        <small class="text-muted">Perlu keputusan Dosen</small>
                                    </div>
                                    <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card krs-stat-card border-left border-success" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Telah Disetujui</div>
                                        <div class="h4 font-weight-bold text-success mb-0"><?= (int)($stats['approved'] ?? 0) ?></div>
                                        <small class="text-muted">Siap cetak FRS</small>
                                    </div>
                                    <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card krs-stat-card border-left border-danger" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Perlu Revisi / Tolak</div>
                                        <div class="h4 font-weight-bold text-danger mb-0"><?= (int)($stats['rejected'] ?? 0) ?></div>
                                        <small class="text-muted">Menunggu perbaikan mhs</small>
                                    </div>
                                    <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                                        <i class="fa fa-exclamation-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card krs-stat-card border-left border-primary" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Total Mahasiswa</div>
                                        <div class="h4 font-weight-bold text-primary mb-0"><?= (int)($stats['total_students'] ?? 0) ?></div>
                                        <small class="text-muted">Bimbingan akademik</small>
                                    </div>
                                    <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOOLBAR FILTER & CONTROLS -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
                        <div class="card-body p-3">
                            <form method="GET" action="<?= base_url('dosen/persetujuan-krs') ?>" id="krsFilterForm">
                                <input type="hidden" name="status" id="filterStatusInput" value="<?= html_escape($active_status) ?>">

                                <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between" style="gap: 12px;">
                                    <!-- STATUS PILLS -->
                                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                                        <a href="javascript:void(0)" onclick="setKrsStatus('semua')" class="krs-filter-pill <?= ($active_status === 'semua' || empty($active_status)) ? 'active' : '' ?>">
                                            <i class="fa fa-th-large mr-1.5 text-primary"></i> Semua
                                            <span class="badge"><?= (int)($stats['pending'] ?? 0) + (int)($stats['approved'] ?? 0) + (int)($stats['rejected'] ?? 0) ?></span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="setKrsStatus('menunggu')" class="krs-filter-pill <?= ($active_status === 'menunggu') ? 'active' : '' ?>">
                                            <i class="fa fa-clock-o mr-1.5 text-warning"></i> Menunggu
                                            <span class="badge"><?= (int)($stats['pending'] ?? 0) ?></span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="setKrsStatus('disetujui')" class="krs-filter-pill <?= ($active_status === 'disetujui') ? 'active' : '' ?>">
                                            <i class="fa fa-check mr-1.5 text-success"></i> Disetujui
                                            <span class="badge"><?= (int)($stats['approved'] ?? 0) ?></span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="setKrsStatus('ditolak')" class="krs-filter-pill <?= ($active_status === 'ditolak') ? 'active' : '' ?>">
                                            <i class="fa fa-times mr-1.5 text-danger"></i> Perlu Revisi
                                            <span class="badge"><?= (int)($stats['rejected'] ?? 0) ?></span>
                                        </a>
                                    </div>

                                    <!-- PRODI & SEARCH & FILTER DOSEN -->
                                    <div class="d-flex flex-wrap align-items-center justify-content-xl-end" style="gap: 8px;">
                                        <?php if ($is_admin && !empty($all_lecturers)): ?>
                                            <div class="flex-grow-1" style="min-width: 170px; max-width: 220px;">
                                                <select name="dosen_id" class="form-control font-weight-bold" onchange="document.getElementById('krsFilterForm').submit()" style="border-radius: 20px; height: 38px; border: 1px solid #cbd5e1; font-size: 12.5px; background: #ffffff;">
                                                    <option value="0" <?= ($selected_dosen == 0) ? 'selected' : '' ?>>-- Semua Dosen Wali --</option>
                                                    <?php foreach ($all_lecturers as $lec): ?>
                                                        <option value="<?= (int)$lec->id ?>" <?= ($selected_dosen == $lec->id) ? 'selected' : '' ?>>
                                                            Dosen Wali: <?= html_escape($lec->nama_lengkap) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-inline-flex align-items-center px-3" style="height: 38px; border-radius: 20px; background: #eff6ff; border: 1px solid #bfdbfe; font-size: 12px; font-weight: 600; color: #1d4ed8; white-space: nowrap;">
                                                <i class="fa fa-user-circle mr-1.5 text-primary"></i> Mahasiswa Bimbingan Anda
                                            </div>
                                        <?php endif; ?>

                                        <div style="min-width: 140px;">
                                            <select name="prodi" class="form-control font-weight-semibold" onchange="document.getElementById('krsFilterForm').submit()" style="border-radius: 20px; height: 38px; font-size: 12.5px; border: 1px solid #cbd5e1; background: #ffffff; padding-left: 14px; padding-right: 24px;">
                                                <option value="all" <?= ($active_prodi === 'all') ? 'selected' : '' ?>>Semua Prodi</option>
                                                <option value="Informatika" <?= ($active_prodi === 'Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                                                <option value="Sistem Informasi" <?= ($active_prodi === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                                                <option value="Akuntansi" <?= ($active_prodi === 'Akuntansi') ? 'selected' : '' ?>>Akuntansi</option>
                                            </select>
                                        </div>

                                        <div class="position-relative" style="width: 215px;">
                                            <input type="text" name="q" value="<?= html_escape($keyword) ?>" class="form-control" placeholder="Cari Nama / NIM..." style="height: 38px; border-radius: 20px; padding-left: 36px; padding-right: 32px; font-size: 12.5px; border: 1px solid #cbd5e1; background: #ffffff; box-shadow: none;">
                                            <i class="fa fa-search position-absolute text-muted" style="left: 13px; top: 50%; transform: translateY(-50%); font-size: 13px; pointer-events: none;"></i>
                                            <?php if (!empty($keyword)): ?>
                                                <a href="<?= base_url('dosen/persetujuan-krs?status=' . urlencode($active_status) . '&prodi=' . urlencode($active_prodi)) ?>" class="position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); text-decoration: none;" title="Hapus pencarian">
                                                    <i class="fa fa-times-circle text-secondary"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- KRS STUDENT LIST -->
                    <?php if (empty($krs_list)): ?>
                        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 16px;">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div style="width: 72px; height: 72px; border-radius: 50%; background: #f1f5f9; display: inline-flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 32px;">
                                        <i class="fa fa-folder-open-o"></i>
                                    </div>
                                </div>
                                <h5 class="font-weight-bold text-dark">Tidak Ada Pengajuan KRS Mahasiswa Bimbingan</h5>
                                <p class="text-muted mb-3" style="max-width: 480px; margin: 0 auto; font-size: 13px;">
                                    <?php if ($active_status === 'menunggu'): ?>
                                        Semua mahasiswa bimbingan Anda telah disetujui atau belum ada yang mengajukan KRS untuk semester aktif ini.
                                    <?php else: ?>
                                        Tidak ditemukan data KRS mahasiswa bimbingan Anda dengan kriteria filter yang Anda pilih.
                                    <?php endif; ?>
                                </p>
                                <a href="<?= base_url('dosen/persetujuan-krs?status=semua') ?>" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;">
                                    <i class="fa fa-refresh mr-1"></i> Tampilkan Semua Pengajuan
                                </a>
                            </div>
                        </div>
                    <?php else: ?>

                        <?php foreach ($krs_list as $item): ?>
                            <?php
                                $courses = !empty($item->krs_detail->mata_kuliah) ? $item->krs_detail->mata_kuliah : [];
                                $actual_courses_sks = !empty($courses) ? array_sum(array_map(function($c) { return (int)($c->sks ?? 0); }, $courses)) : 0;
                                $total_sks = ($actual_courses_sks > 0) ? $actual_courses_sks : (int)$item->total_sks;
                                $max_sks = 24;
                                $sks_pct = min(100, round(($total_sks / $max_sks) * 100));

                                $status_badge_class = 'badge-warning text-dark';
                                $status_text = 'Menunggu Validasi';
                                if ($item->status === 'DISETUJUI') {
                                    $status_badge_class = 'badge-success text-white';
                                    $status_text = 'Telah Disetujui';
                                } elseif ($item->status === 'DITOLAK') {
                                    $status_badge_class = 'badge-danger text-white';
                                    $status_text = 'Perlu Revisi';
                                } elseif ($item->status === 'DRAFT') {
                                    $status_badge_class = 'badge-secondary text-white';
                                    $status_text = 'Draft (Belum Diajukan)';
                                }

                                $initial = strtoupper(substr($item->nama_lengkap ?? 'M', 0, 1));
                            ?>

                            <div class="student-krs-card" id="krs-card-<?= (int)$item->id ?>">
                                <!-- CARD HEADER / PROFILE SECTION -->
                                <div class="p-3 p-md-4 border-bottom bg-white">
                                    <div class="row align-items-center">
                                        <div class="col-xl-6 col-lg-5 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-center">
                                                <div class="student-avatar mr-3 flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #4f46e5, #3b82f6); color: #fff; font-size: 20px; font-weight: 700; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);">
                                                    <?= $initial ?>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 8px;">
                                                        <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16.5px; letter-spacing: -0.2px;">
                                                            <?= html_escape($item->nama_lengkap) ?>
                                                        </h5>
                                                        <span class="badge <?= $status_badge_class ?> font-weight-bold px-2.5 py-1" style="border-radius: 6px; font-size: 11px;">
                                                            <?= $status_text ?>
                                                        </span>
                                                        <?php if (!empty($item->has_conflict)): ?>
                                                            <span class="badge badge-conflict">
                                                                <i class="fa fa-warning mr-1"></i> BENTROK JADWAL
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-muted d-flex align-items-center flex-wrap" style="font-size: 12px; gap: 4px 6px;">
                                                        <span>NIM: <strong class="text-dark font-monospace"><?= html_escape($item->nim) ?></strong></span>
                                                        <span>&bull;</span>
                                                        <span><?= html_escape($item->prodi ?: '-') ?></span>
                                                        <span>&bull;</span>
                                                        <span>Semester <?= (int)$item->semester ?></span>
                                                        <?php if (!empty($item->nama_dosen_wali)): ?>
                                                            <span>&bull;</span>
                                                            <span class="text-primary font-weight-bold" style="white-space: nowrap;"><i class="fa fa-user-circle-o mr-1"></i>Wali: <?= html_escape($item->nama_dosen_wali) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- RIGHT SIDE: SKS METER & STATUS SPP & ACTIONS -->
                                        <div class="col-xl-6 col-lg-7">
                                            <div class="d-flex flex-column align-items-lg-end justify-content-center" style="gap: 8px;">
                                                <!-- ROW 1: STATUS KEUANGAN & TOTAL BEBAN CHIPS -->
                                                <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 8px;">
                                                    <?php 
                                                        $is_lunas = !empty($item->status_keuangan['boleh_krs'])
                                                                    || !empty($item->status_keuangan['buka_krs'])
                                                                    || !empty($item->status_keuangan['is_lunas'])
                                                                    || (isset($item->status_keuangan['status']) && strtoupper($item->status_keuangan['status']) === 'LUNAS');
                                                        $is_pending = (isset($item->status_keuangan['status']) && strtoupper($item->status_keuangan['status']) === 'PENDING');
                                                    ?>

                                                    <!-- Keuangan Chip -->
                                                    <div class="d-inline-flex align-items-center px-2.5 py-1 rounded" style="background: <?= $is_lunas ? '#f0fdf4' : ($is_pending ? '#fffbeb' : '#fef2f2') ?>; border: 1px solid <?= $is_lunas ? '#bbf7d0' : ($is_pending ? '#fde68a' : '#fecaca') ?>; font-size: 11.5px; gap: 6px;">
                                                        <span class="text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.3px;">KEUANGAN:</span>
                                                        <?php if ($is_lunas): ?>
                                                            <span class="font-weight-bold text-success"><i class="fa fa-check-circle mr-1"></i>LUNAS / DIIZINKAN</span>
                                                        <?php elseif ($is_pending): ?>
                                                            <span class="font-weight-bold text-warning"><i class="fa fa-clock-o mr-1"></i>VERIFIKASI PENDING</span>
                                                        <?php else: ?>
                                                            <span class="font-weight-bold text-danger"><i class="fa fa-times-circle mr-1"></i>TUNGGAKAN SPP</span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- SKS Chip -->
                                                    <div class="d-inline-flex align-items-center px-2.5 py-1 rounded" style="background: #eff6ff; border: 1px solid #bfdbfe; font-size: 11.5px; gap: 6px;">
                                                        <span class="text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.3px;">TOTAL BEBAN:</span>
                                                        <span class="font-weight-bold text-primary"><?= $total_sks ?> <small class="text-muted">/ <?= $max_sks ?> SKS</small></span>
                                                        <div class="progress" style="width: 50px; height: 5px; margin-bottom: 0; border-radius: 3px; background: #dbeafe;">
                                                            <div class="progress-bar <?= ($total_sks > 24) ? 'bg-danger' : (($total_sks >= 20) ? 'bg-success' : 'bg-primary') ?>" style="width: <?= $sks_pct ?>%;"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- ROW 2: ACTION BUTTONS TOOLBAR -->
                                                <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 6px;">
                                                    <button class="btn btn-sm font-weight-bold d-inline-flex align-items-center" type="button" data-toggle="collapse" data-target="#krs-collapse-<?= (int)$item->id ?>" aria-expanded="false" style="border-radius: 8px; height: 32px; font-size: 12px; background: #ffffff; border: 1px solid #cbd5e1; color: #334155;">
                                                        <i class="fa fa-list-alt mr-1.5 text-muted"></i> Detail Matkul
                                                    </button>

                                                    <button type="button" class="btn btn-sm font-weight-bold d-inline-flex align-items-center" onclick="openAdvisorEditModal(<?= (int)$item->id ?>)" style="border-radius: 8px; height: 32px; font-size: 12px; background: #fef3c7; border: 1px solid #fde68a; color: #92400e;" title="Pilihkan atau gantikan mata kuliah mahasiswa">
                                                        <i class="fa fa-pencil-square-o mr-1.5 text-warning"></i> Edit / Pilihkan Matkul
                                                    </button>

                                                    <?php if ($item->status === 'MENUNGGU'): ?>
                                                        <button type="button" class="btn btn-sm font-weight-bold text-white d-inline-flex align-items-center" onclick="quickApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')" style="border-radius: 8px; height: 32px; font-size: 12px; background: #10b981; border: 1px solid #059669; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);">
                                                            <i class="fa fa-check-circle mr-1.5"></i> Setujui KRS
                                                        </button>
                                                        <button type="button" class="btn btn-sm font-weight-bold d-inline-flex align-items-center" onclick="openRejectModal(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')" style="border-radius: 8px; height: 32px; font-size: 12px; background: #ffffff; border: 1px solid #fca5a5; color: #dc2626;">
                                                            <i class="fa fa-times-circle mr-1.5"></i> Tolak
                                                        </button>
                                                    <?php elseif ($item->status === 'DISETUJUI'): ?>
                                                        <button type="button" class="btn btn-sm font-weight-bold d-inline-flex align-items-center" onclick="cancelApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')" style="border-radius: 8px; height: 32px; font-size: 12px; background: #ffffff; border: 1px solid #e2e8f0; color: #64748b;" title="Batalkan persetujuan KRS">
                                                            <i class="fa fa-undo mr-1.5"></i> Batal
                                                        </button>
                                                    <?php elseif ($item->status === 'DITOLAK'): ?>
                                                        <button type="button" class="btn btn-sm font-weight-bold d-inline-flex align-items-center" onclick="cancelApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')" style="border-radius: 8px; height: 32px; font-size: 12px; background: #ffffff; border: 1px solid #e2e8f0; color: #64748b;" title="Batalkan penolakan KRS">
                                                            <i class="fa fa-undo mr-1.5"></i> Batal
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CONFLICT BANNER IF ANY -->
                                    <?php if (!empty($item->has_conflict)): ?>
                                        <div class="alert alert-danger mt-3 mb-0 p-2 px-3 d-flex align-items-center" style="border-radius: 8px; font-size: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                                            <i class="fa fa-exclamation-triangle mr-2 text-danger" style="font-size: 16px;"></i>
                                            <div>
                                                <strong>Peringatan Benturan Jadwal:</strong>
                                                <?php foreach ($item->conflicts as $cf): ?>
                                                    <span class="d-block">
                                                        Mata kuliah <strong><?= html_escape($cf['mk_1']) ?></strong> bertabrakan dengan <strong><?= html_escape($cf['mk_2']) ?></strong> pada hari <strong><?= html_escape($cf['hari']) ?></strong> (<?= html_escape($cf['jam_1']) ?> vs <?= html_escape($cf['jam_2']) ?>).
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- CATATAN REVISI / REJECTED REASON IF ANY -->
                                    <?php if (!empty($item->catatan)): ?>
                                        <?php
                                            $catatan_bg = '#f8fafc';
                                            $catatan_border = '#e2e8f0';
                                            $catatan_left = '#94a3b8';
                                            $catatan_icon = 'fa-commenting-o text-muted';
                                            $catatan_text = '#334155';
                                            if ($item->status === 'DISETUJUI') {
                                                $catatan_bg = '#f0fdf4';
                                                $catatan_border = '#dcfce7';
                                                $catatan_left = '#10b981';
                                                $catatan_icon = 'fa-check-circle text-success';
                                                $catatan_text = '#166534';
                                            } elseif ($item->status === 'DITOLAK') {
                                                $catatan_bg = '#fef2f2';
                                                $catatan_border = '#fee2e2';
                                                $catatan_left = '#ef4444';
                                                $catatan_icon = 'fa-exclamation-circle text-danger';
                                                $catatan_text = '#991b1b';
                                            } elseif ($item->status === 'MENUNGGU') {
                                                $catatan_bg = '#fffbeb';
                                                $catatan_border = '#fef3c7';
                                                $catatan_left = '#f59e0b';
                                                $catatan_icon = 'fa-clock-o text-warning';
                                                $catatan_text = '#92400e';
                                            }
                                        ?>
                                        <div class="mt-3 px-3 py-2 rounded d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between" style="background: <?= $catatan_bg ?>; border: 1px solid <?= $catatan_border ?>; border-left: 3px solid <?= $catatan_left ?>; border-radius: 8px; font-size: 12px; color: <?= $catatan_text ?>; gap: 8px;">
                                            <div class="d-flex align-items-center">
                                                <i class="fa <?= $catatan_icon ?> mr-2" style="font-size: 13.5px;"></i>
                                                <div>
                                                    <strong style="color: inherit;">Catatan Dosen Wali:</strong>
                                                    <span class="ml-1"><?= html_escape($item->catatan) ?></span>
                                                </div>
                                            </div>
                                            <?php if (!empty($item->diproses_at)): ?>
                                                <div class="text-muted small flex-shrink-0" style="font-size: 11px; white-space: nowrap;">
                                                    <i class="fa fa-clock-o mr-1"></i> Diperbarui <?= date('d M Y H:i', strtotime($item->diproses_at)) ?> <?= !empty($item->nama_validator) ? 'oleh ' . html_escape($item->nama_validator) : '' ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- COLLAPSIBLE DETAILS (COURSES, SKS BREAKDOWN, TIMETABLE, & VALIDATION FORM) -->
                                <div class="collapse show" id="krs-collapse-<?= (int)$item->id ?>">
                                    <div class="p-3 p-md-4 bg-light" style="border-top: 1px solid #f1f5f9;">

                                        <!-- SKS COMPOSITION BADGES -->
                                        <div class="d-flex align-items-center flex-wrap mb-3">
                                            <span class="text-muted small font-weight-bold mr-2">Komposisi Beban Studi:</span>
                                            <span class="sks-breakdown-tag" style="background: #e0e7ff; color: #3730a3;">
                                                <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> Wajib Prodi: <?= (int)$item->sks_wajib ?> SKS
                                            </span>
                                            <span class="sks-breakdown-tag" style="background: #dbeafe; color: #1e40af;">
                                                <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> Pilihan Jurusan: <?= (int)$item->sks_jurusan ?> SKS
                                            </span>
                                            <span class="sks-breakdown-tag" style="background: #fef3c7; color: #92400e;">
                                                <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> Luar Prodi (Lintas): <?= (int)$item->sks_luar ?> SKS
                                            </span>
                                            <span class="sks-breakdown-tag" style="background: #f3e8ff; color: #6b21a8;">
                                                <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> Mata Kuliah Umum: <?= (int)$item->sks_umum ?> SKS
                                            </span>
                                        </div>

                                        <!-- COURSE TABLE -->
                                        <div class="table-responsive bg-white rounded border mb-3 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                            <table class="table table-hover table-sm mb-0" style="min-width: 750px; font-size: 13px;">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="py-2 px-3" style="width: 100px; white-space: nowrap;">Kode MK</th>
                                                        <th class="py-2 px-3" style="white-space: nowrap;">Mata Kuliah</th>
                                                        <th class="py-2 px-3 text-center" style="width: 70px; white-space: nowrap;">SKS</th>
                                                        <th class="py-2 px-3" style="width: 130px; white-space: nowrap;">Kategori</th>
                                                        <th class="py-2 px-3" style="white-space: nowrap;">Jadwal & Hari</th>
                                                        <th class="py-2 px-3" style="white-space: nowrap;">Ruangan</th>
                                                        <th class="py-2 px-3" style="white-space: nowrap;">Dosen Pengampu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($courses)): ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center py-4 text-muted">
                                                                <i class="fa fa-info-circle mr-1"></i> Belum ada mata kuliah yang dipilih pada rencana studi ini.
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($courses as $c): ?>
                                                            <?php
                                                                $kat = $c->kategori_kontekstual ?? ($c->jenis_mk ?? 'Wajib');
                                                                $badge_cat_class = 'badge-category-wajib';
                                                                if ($kat === 'Jurusan') $badge_cat_class = 'badge-category-jurusan';
                                                                elseif ($kat === 'Luar Jurusan') $badge_cat_class = 'badge-category-luar';
                                                                elseif ($kat === 'Umum') $badge_cat_class = 'badge-category-umum';
                                                            ?>
                                                            <tr class="<?= !empty($c->conflict) ? 'table-danger' : '' ?>">
                                                                <td class="font-weight-bold text-dark py-2 align-middle">
                                                                    <?= html_escape($c->kode_mk) ?>
                                                                </td>
                                                                <td class="font-weight-bold py-2 align-middle">
                                                                    <?= html_escape($c->nama_mk) ?>
                                                                    <?php if (!empty($c->conflict)): ?>
                                                                        <span class="badge badge-conflict ml-1">Bentrok</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center font-weight-bold text-primary py-2 align-middle">
                                                                    <?= (int)$c->sks ?>
                                                                </td>
                                                                <td class="py-2 align-middle">
                                                                    <span class="<?= $badge_cat_class ?>"><?= html_escape($kat) ?></span>
                                                                </td>
                                                                <td class="py-2 align-middle text-dark">
                                                                    <i class="fa fa-clock-o text-muted mr-1"></i>
                                                                    <strong><?= html_escape($c->hari) ?></strong>, <?= html_escape(substr($c->waktu_mulai, 0, 5)) ?> - <?= html_escape(substr($c->waktu_selesai, 0, 5)) ?> WIB
                                                                </td>
                                                                <td class="py-2 align-middle text-muted">
                                                                    <?= html_escape($c->ruangan ?: '-') ?>
                                                                </td>
                                                                <td class="py-2 align-middle text-muted">
                                                                    <?= html_escape($c->nama_dosen ?: '-') ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- WEEKLY TIMETABLE PREVIEW TOGGLE -->
                                        <div class="mb-3">
                                            <a class="text-primary font-weight-bold small" data-toggle="collapse" href="#timetable-preview-<?= (int)$item->id ?>" role="button" aria-expanded="false">
                                                <i class="fa fa-calendar-o mr-1"></i> Tampilkan Matriks Jadwal Mingguan Mahasiswa
                                            </a>
                                            <div class="collapse mt-2" id="timetable-preview-<?= (int)$item->id ?>">
                                                <div class="timetable-mini-grid">
                                                    <?php
                                                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                        foreach ($days as $day):
                                                            $day_courses = array_filter($courses, function($co) use ($day) {
                                                                return strcasecmp($co->hari ?? '', $day) === 0;
                                                            });
                                                    ?>
                                                        <div class="timetable-day-col">
                                                            <div class="timetable-day-header"><?= $day ?></div>
                                                            <?php if (empty($day_courses)): ?>
                                                                <div class="text-center text-muted" style="font-size: 9px; margin-top: 20px;">Kosong</div>
                                                            <?php else: ?>
                                                                <?php foreach ($day_courses as $dc): ?>
                                                                    <div class="timetable-event <?= !empty($dc->conflict) ? 'conflict' : '' ?>">
                                                                        <strong><?= html_escape($dc->nama_mk) ?></strong><br>
                                                                        <span><?= substr($dc->waktu_mulai, 0, 5) ?>-<?= substr($dc->waktu_selesai, 0, 5) ?> (<?= html_escape($dc->ruangan) ?>)</span>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ACTION DECISION FORM -->
                                        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
                                            <div class="card-body p-3">
                                                <?= form_open('dosen/proses-krs', ['id' => 'form-krs-' . (int)$item->id, 'class' => 'm-0']) ?>
                                                    <input type="hidden" name="krs_id" value="<?= (int)$item->id ?>">

                                                    <?php if ($item->status === 'MENUNGGU'): ?>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-7 mb-2 mb-md-0">
                                                                <label class="small font-weight-bold text-muted mb-1" for="catatan-<?= (int)$item->id ?>">
                                                                    Catatan Evaluasi / Alasan Revisi (Wajib jika menolak):
                                                                </label>
                                                                <input type="text" name="catatan" id="catatan-<?= (int)$item->id ?>" class="form-control form-control-sm" placeholder="Contoh: Jadwal bentrok pada hari Selasa, silakan ganti matakuliah pilihan..." maxlength="400" style="border-radius: 8px;">
                                                            </div>
                                                            <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                                                <button type="submit" name="aksi" value="setujui" class="btn btn-success btn-sm px-3 mr-1" style="border-radius: 8px; font-weight: 600;" data-sc-confirm="Apakah Anda yakin ingin MENYETUJUI rencana studi untuk <?= html_escape(addslashes($item->nama_lengkap)) ?>?">
                                                                    <i class="fa fa-check mr-1"></i> Setujui (Validasi KRS)
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger btn-sm px-3 font-weight-bold" style="border-radius: 8px;" onclick="handleCardReject(<?= (int)$item->id ?>)">
                                                                    <i class="fa fa-times mr-1"></i> Tolak &amp; Minta Revisi
                                                                </button>
                                                            </div>
                                                        </div>

                                                    <?php elseif ($item->status === 'DISETUJUI'): ?>
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <div class="text-success small font-weight-bold">
                                                                <i class="fa fa-check-circle mr-1"></i> Rencana studi telah disetujui pada <?= !empty($item->diproses_at) ? date('d M Y H:i', strtotime($item->diproses_at)) : '-' ?>
                                                                <?php if (!empty($item->nama_validator)): ?>
                                                                    oleh <strong><?= html_escape($item->nama_validator) ?></strong>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div>
                                                                <button type="submit" name="aksi" value="batalkan" class="btn btn-outline-secondary btn-sm font-weight-bold" style="border-radius: 8px;" data-sc-confirm="Batalkan persetujuan KRS dan kembalikan ke antrean peninjauan?">
                                                                    <i class="fa fa-undo mr-1"></i> Batalkan Persetujuan
                                                                </button>
                                                            </div>
                                                        </div>

                                                    <?php elseif ($item->status === 'DITOLAK'): ?>
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <div class="text-danger small font-weight-bold">
                                                                <i class="fa fa-exclamation-circle mr-1"></i> Rencana studi ditolak & meminta perbaikan mahasiswa.
                                                            </div>
                                                            <div>
                                                                <button type="submit" name="aksi" value="batalkan" class="btn btn-outline-secondary btn-sm font-weight-bold" style="border-radius: 8px;" data-sc-confirm="Kembalikan status KRS ke antrean peninjauan?">
                                                                    <i class="fa fa-undo mr-1"></i> Kembalikan ke Menunggu
                                                                </button>
                                                            </div>
                                                        </div>

                                                    <?php else: ?>
                                                        <div class="text-muted small">
                                                            <i class="fa fa-info-circle mr-1"></i> KRS masih berupa draft oleh mahasiswa dan belum diajukan secara resmi.
                                                        </div>
                                                    <?php endif; ?>

                                                <?= form_close() ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL QUICK REJECT / MINTA REVISI -->
<div class="modal fade" id="modalQuickReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <?= form_open('dosen/proses-krs', ['id' => 'formQuickReject']) ?>
                <input type="hidden" name="krs_id" id="quickRejectKrsId" value="">
                <input type="hidden" name="aksi" value="tolak">
                <div class="modal-header bg-danger text-white" style="border-radius: 14px 14px 0 0;">
                    <h5 class="modal-title font-weight-bold text-white">
                        <i class="fa fa-exclamation-triangle mr-2"></i> Minta Revisi KRS Mahasiswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Berikan catatan perbaikan yang jelas agar mahasiswa <strong id="quickRejectStudentName" class="text-dark"></strong> dapat memperbaiki pemilihan mata kuliah atau kuota SKS.
                    </p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-dark">Catatan Revisi Dosen Wali (Wajib):</label>
                        <textarea name="catatan" id="quickRejectNote" class="form-control" rows="3" placeholder="Contoh: SKS melebihi batas atau ada jadwal kuliah yang bertabrakan di hari Rabu..." required style="border-radius: 8px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 14px 14px;">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 font-weight-bold" style="border-radius: 8px;">
                        <i class="fa fa-paper-plane mr-1"></i> Kirim Catatan Revisi
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- MODAL DOSEN WALI: EDIT / PILIHKAN / GANTIKAN MATA KULIAH -->
<div class="modal fade" id="modalAdvisorEditKrs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden; max-height: 92vh; display: flex; flex-direction: column;">
            <?= form_open('dosen/simpan_krs_mahasiswa', ['id' => 'formAdvisorEditKrs', 'style' => 'display: flex; flex-direction: column; max-height: 92vh; height: 100%; min-height: 0; margin: 0;']) ?>
                <input type="hidden" name="krs_id" id="advEditKrsId" value="">
                <input type="hidden" name="aksi" id="advEditAksi" value="simpan">

                <!-- MODAL HEADER -->
                <div class="modal-header py-3 px-4 bg-white align-items-center" style="border-bottom: 1px solid #e2e8f0; flex-shrink: 0;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb; font-size: 19px; border: 1px solid #bfdbfe;">
                            <i class="fa fa-pencil-square-o"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold text-dark mb-1" style="font-size: 17px; letter-spacing: -0.2px;">
                                Hak Akses Dosen Wali: Edit &amp; Pilihkan Mata Kuliah
                            </h5>
                            <div class="text-muted d-flex align-items-center flex-wrap" style="font-size: 12.5px; gap: 8px;">
                                <span>Mahasiswa: <strong class="text-dark" id="advStudentName">-</strong></span>
                                <span>&bull;</span>
                                <span>NIM: <strong class="text-dark font-monospace" id="advStudentNim">-</strong></span>
                                <span>&bull;</span>
                                <span>Prodi: <strong class="text-dark" id="advStudentProdi">-</strong></span>
                                <span>&bull;</span>
                                <span class="badge badge-primary px-2.5 py-1" style="font-size: 11px; border-radius: 6px;" id="advStudentSem">Semester -</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 26px; outline: none; opacity: 0.6;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- MODAL BODY -->
                <div class="modal-body p-4" style="background: #f8fafc; overflow-y: auto; flex: 1 1 auto; min-height: 0;">
                    <!-- LOADING SPINNER -->
                    <div id="advModalLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="sr-only">Memuat data penawaran...</span>
                        </div>
                        <p class="text-muted mt-3 mb-0 font-weight-bold">Memuat katalog mata kuliah dan rencana studi mahasiswa...</p>
                    </div>

                    <!-- MODAL CONTENT (SHOWN AFTER LOADED) -->
                    <div id="advModalContent" style="display: none;">
                        <!-- TOP STATUS & SKS STRIP -->
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                            <div class="card-body py-2.5 px-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
                                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                    <span class="text-secondary small font-weight-bold">Total SKS Terpilih:</span>
                                    <span id="advTotalSksBadge" class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 14px; border-radius: 6px;">
                                        0 / 24 SKS
                                    </span>
                                    <span id="advCountCoursesBadge" class="badge badge-light border text-muted px-2.5 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                        0 Mata Kuliah
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fa fa-info-circle text-primary mr-1"></i> Centang mata kuliah untuk menambahkan, atau hapus centang untuk mengganti/membatalkan.
                                </div>
                            </div>
                        </div>

                        <!-- LIVE SCHEDULE CONFLICT ALERT -->
                        <div id="advConflictAlert" class="alert alert-danger shadow-sm mb-3" style="border-radius: 10px; display: none; border-left: 5px solid #ef4444; background: #fef2f2;">
                            <div class="d-flex align-items-start">
                                <i class="fa fa-exclamation-triangle fa-2x text-danger mr-3 mt-1"></i>
                                <div>
                                    <strong class="text-danger" style="font-size: 13.5px;">Peringatan Benturan Jadwal Kuliah:</strong>
                                    <div id="advConflictDetails" class="mt-1 small" style="color: #991b1b; line-height: 1.5;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- SEARCH & CATEGORY FILTER BAR -->
                        <div class="bg-white p-2.5 rounded-lg border shadow-sm mb-3 d-flex justify-content-between align-items-center flex-wrap" style="border-color: #e2e8f0 !important; border-radius: 10px; gap: 10px;">
                            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                <button type="button" class="btn btn-sm adv-cat-filter active" data-cat="all" style="border-radius: 20px; font-weight: 600; font-size: 11.5px; padding: 4px 12px;">Semua</button>
                                <button type="button" class="btn btn-sm adv-cat-filter" data-cat="Wajib" style="border-radius: 20px; font-weight: 600; font-size: 11.5px; padding: 4px 12px;">Wajib Prodi</button>
                                <button type="button" class="btn btn-sm adv-cat-filter" data-cat="Jurusan" style="border-radius: 20px; font-weight: 600; font-size: 11.5px; padding: 4px 12px;">Pilihan Jurusan</button>
                                <button type="button" class="btn btn-sm adv-cat-filter" data-cat="Luar Jurusan" style="border-radius: 20px; font-weight: 600; font-size: 11.5px; padding: 4px 12px;">Luar Prodi</button>
                                <button type="button" class="btn btn-sm adv-cat-filter" data-cat="Umum" style="border-radius: 20px; font-weight: 600; font-size: 11.5px; padding: 4px 12px;">Umum</button>
                            </div>
                            <div class="position-relative" style="min-width: 280px; flex-grow: 1; max-width: 360px;">
                                <i class="fa fa-search position-absolute text-muted" style="left: 12px; top: 10px; font-size: 12px;"></i>
                                <input type="text" id="advSearchInput" class="form-control form-control-sm pl-4" placeholder="Cari nama matkul / kode / dosen..." style="border-radius: 20px; font-size: 12px; height: 32px; border-color: #cbd5e1;">
                            </div>
                        </div>

                        <!-- CATALOG TABLE -->
                        <div class="table-responsive bg-white rounded border shadow-sm mb-3" style="border-radius: 10px; max-height: 360px; overflow-y: auto;">
                            <table class="table table-hover table-sm mb-0" style="font-size: 12.5px;" id="advTableCourses">
                                <thead>
                                    <tr>
                                        <th class="py-2.5 px-3 text-center" style="width: 45px;">Pilih</th>
                                        <th class="py-2.5 px-2" style="width: 85px;">Kode MK</th>
                                        <th class="py-2.5 px-3">Mata Kuliah</th>
                                        <th class="py-2.5 px-2 text-center" style="width: 65px;">SKS</th>
                                        <th class="py-2.5 px-2" style="width: 105px;">Kategori</th>
                                        <th class="py-2.5 px-2" style="width: 170px;">Jadwal &amp; Hari</th>
                                        <th class="py-2.5 px-2" style="width: 130px;">Ruangan</th>
                                        <th class="py-2.5 px-2" style="width: 160px;">Dosen Pengampu</th>
                                        <th class="py-2.5 px-3 text-center" style="width: 110px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="advCoursesTbody">
                                    <!-- Injected via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- ADVISOR EVALUATION NOTE -->
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark small mb-1">
                                <i class="fa fa-commenting-o mr-1 text-primary"></i> Catatan Penyesuaian Dosen Wali untuk Mahasiswa (Opsional):
                            </label>
                            <textarea name="catatan" id="advCatatanInput" class="form-control" rows="2" placeholder="Contoh: Mata kuliah Pengantar Akuntansi digantikan dengan Algoritma Pemrograman sesuai kurikulum semester 4..." style="border-radius: 8px; font-size: 12.5px; border-color: #cbd5e1;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- MODAL FOOTER -->
                <div class="modal-footer py-2.5 px-4 bg-white d-flex justify-content-between align-items-center flex-wrap" style="border-top: 1px solid #e2e8f0; flex-shrink: 0;">
                    <button type="button" class="btn btn-light btn-sm px-3 text-secondary border font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">
                        <i class="fa fa-times mr-1"></i> Batal
                    </button>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <button type="button" class="btn btn-outline-primary btn-sm px-3 font-weight-bold" onclick="submitAdvisorEdit('simpan')" id="btnAdvSaveDraft" style="border-radius: 8px;">
                            <i class="fa fa-save mr-1"></i> Simpan Pilihan (Status: Menunggu)
                        </button>
                        <button type="button" class="btn btn-success btn-sm px-4 font-weight-bold" onclick="submitAdvisorEdit('setujui')" id="btnAdvSaveAndApprove" style="border-radius: 8px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);">
                            <i class="fa fa-check-circle mr-1"></i> Simpan &amp; Langsung Setujui KRS
                        </button>
                    </div>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI PERSETUJUAN KRS (WEB THEME) -->
<div class="modal fade" id="modalConfirmKrsApproval" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 20px 45px rgba(0,0,0,0.22); overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #ecfdf5; color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px;">
                    <i class="feather icon-check-circle"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Konfirmasi Persetujuan KRS</h5>
                <p class="text-muted small mb-4" id="confirmApproveMessage" style="line-height: 1.6; font-size: 13.5px;">
                    Apakah Anda yakin ingin <strong>MENYETUJUI</strong> rencana studi (KRS) untuk mahasiswa ini?
                </p>
                <div class="d-flex justify-content-center" style="gap: 12px;">
                    <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 9px; min-width: 110px;">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success px-4 font-weight-bold" id="btnConfirmApproveSubmit" style="border-radius: 9px; min-width: 130px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);">
                        <i class="fa fa-check mr-1"></i> Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI PEMBATALAN STATUS KRS (WEB THEME) -->
<div class="modal fade" id="modalConfirmKrsCancel" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 20px 45px rgba(0,0,0,0.22); overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #eff6ff; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px;">
                    <i class="feather icon-rotate-ccw"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Konfirmasi Pembatalan Status</h5>
                <p class="text-muted small mb-4" id="confirmCancelMessage" style="line-height: 1.6; font-size: 13.5px;">
                    Batalkan status persetujuan KRS dan kembalikan ke antrean peninjauan?
                </p>
                <div class="d-flex justify-content-center" style="gap: 12px;">
                    <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 9px; min-width: 110px;">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary px-4 font-weight-bold" id="btnConfirmCancelSubmit" style="border-radius: 9px; min-width: 130px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);">
                        <i class="fa fa-undo mr-1"></i> Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FORM POST TERSEMBUNYI UNTUK AKSI CEPAT -->
<?= form_open('dosen/proses-krs', ['id' => 'formQuickAction', 'style' => 'display:none;']) ?>
    <input type="hidden" name="krs_id" id="quickActionKrsId" value="">
    <input type="hidden" name="aksi" id="quickActionAksi" value="">
    <input type="hidden" name="catatan" id="quickActionCatatan" value="">
<?= form_close() ?>

<script>
function scAlert(message, title, type) {
    if (window.SCDialog && typeof window.SCDialog.alert === 'function') {
        return window.SCDialog.alert(message, { title: title || 'Informasi', type: type || 'info' });
    }
    return Promise.resolve();
}

function scConfirm(message, title, acceptText, cancelText, type) {
    if (window.SCDialog && typeof window.SCDialog.confirm === 'function') {
        return window.SCDialog.confirm(message, {
            title: title || 'Konfirmasi',
            acceptText: acceptText || 'Lanjutkan',
            cancelText: cancelText || 'Batal',
            type: type || 'confirm'
        });
    }
    return Promise.resolve(true);
}

function setKrsStatus(status) {
    document.getElementById('filterStatusInput').value = status;
    document.getElementById('krsFilterForm').submit();
}

var currentTargetKrsId = 0;

function quickApprove(krsId, studentName) {
    currentTargetKrsId = krsId;
    var msgEl = document.getElementById('confirmApproveMessage');
    if (msgEl) {
        msgEl.innerHTML = 'Apakah Anda yakin ingin <strong>MENYETUJUI</strong> rencana studi (KRS) untuk <strong>' + studentName + '</strong>?<br><span class="text-muted" style="font-size: 12px;">Data jadwal dan presensi kuliah mahasiswa akan langsung aktif dan tersinkronisasi.</span>';
    }
    $('#modalConfirmKrsApproval').modal('show');
}

document.getElementById('btnConfirmApproveSubmit').addEventListener('click', function() {
    $('#modalConfirmKrsApproval').modal('hide');
    document.getElementById('quickActionKrsId').value = currentTargetKrsId;
    document.getElementById('quickActionAksi').value = 'setujui';
    document.getElementById('quickActionCatatan').value = 'KRS telah diperiksa dan disetujui oleh Dosen Wali.';
    document.getElementById('formQuickAction').submit();
});

function openRejectModal(krsId, studentName) {
    document.getElementById('quickRejectKrsId').value = krsId;
    document.getElementById('quickRejectStudentName').innerText = studentName;
    document.getElementById('quickRejectNote').value = '';
    $('#modalQuickReject').modal('show');
}

function cancelApprove(krsId, studentName) {
    currentTargetKrsId = krsId;
    var msgEl = document.getElementById('confirmCancelMessage');
    if (msgEl) {
        msgEl.innerHTML = 'Batalkan status keputusan KRS untuk <strong>' + studentName + '</strong> dan kembalikan ke antrean peninjauan?';
    }
    $('#modalConfirmKrsCancel').modal('show');
}

document.getElementById('btnConfirmCancelSubmit').addEventListener('click', function() {
    $('#modalConfirmKrsCancel').modal('hide');
    document.getElementById('quickActionKrsId').value = currentTargetKrsId;
    document.getElementById('quickActionAksi').value = 'batalkan';
    document.getElementById('quickActionCatatan').value = '';
    document.getElementById('formQuickAction').submit();
});

function handleCardReject(krsId) {
    var noteInput = document.getElementById('catatan-' + krsId);
    if (!noteInput || !noteInput.value.trim()) {
        scAlert('Mohon isi alasan / catatan revisi terlebih dahulu sebelum menolak pengajuan KRS mahasiswa.', 'Catatan Revisi Wajib Diisi', 'warning');
        if (noteInput) noteInput.focus();
        return;
    }
    scConfirm('Apakah Anda yakin ingin MENOLAK dan meminta revisi rencana studi mahasiswa ini dengan catatan: "' + noteInput.value.trim() + '"?', 'Konfirmasi Tolak & Minta Revisi', 'Ya, Kirim Revisi', 'Batal')
        .then(function(confirmed) {
            if (confirmed) {
                var form = document.getElementById('form-krs-' + krsId);
                var inputAksi = document.createElement('input');
                inputAksi.type = 'hidden';
                inputAksi.name = 'aksi';
                inputAksi.value = 'tolak';
                form.appendChild(inputAksi);
                form.submit();
            }
        });
}

function validateReject(krsId) {
    handleCardReject(krsId);
    return false;
}

// =========================================================================
// FITUR DOSEN WALI: EDIT / PILIHKAN / GANTIKAN MATA KULIAH MAHASISWA
// =========================================================================
let advAllOfferings = [];
let advInitialCourseIds = new Set();
let advCurrentSelectedIds = new Set();
let advMaxSks = 24;
let advMinSks = 12;

function timeToMinutes(timeStr) {
    if (!timeStr) return 0;
    const parts = timeStr.split(':');
    return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
}

function openAdvisorEditModal(krsId) {
    $('#modalAdvisorEditKrs').modal('show');
    document.getElementById('advModalLoading').style.display = 'block';
    document.getElementById('advModalContent').style.display = 'none';
    document.getElementById('advEditKrsId').value = krsId;
    document.getElementById('advCatatanInput').value = '';

    fetch('<?= base_url('dosen/get_krs_offerings_ajax?krs_id=') ?>' + krsId)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                scAlert(data.message || 'Gagal memuat data penawaran mata kuliah.', 'Gagal Memuat Data', 'error');
                $('#modalAdvisorEditKrs').modal('hide');
                return;
            }

            const k = data.krs;
            document.getElementById('advStudentName').textContent = k.nama_lengkap;
            document.getElementById('advStudentNim').textContent = k.nim;
            document.getElementById('advStudentProdi').textContent = k.prodi || 'Informatika';
            document.getElementById('advStudentSem').textContent = 'Semester ' + (k.semester || 1);

            advAllOfferings = data.offerings || [];
            advInitialCourseIds = new Set(data.current_ids || []);
            advCurrentSelectedIds = new Set(data.current_ids || []);
            advMaxSks = data.max_sks || 24;
            advMinSks = data.min_sks || 12;

            renderAdvisorOfferingsTable();
            recalculateAdvisorState();

            document.getElementById('advModalLoading').style.display = 'none';
            document.getElementById('advModalContent').style.display = 'block';
        })
        .catch(err => {
            console.error('Error fetching offerings:', err);
            scAlert('Terjadi kesalahan jaringan saat memuat data mata kuliah.', 'Kesalahan Jaringan', 'error');
            $('#modalAdvisorEditKrs').modal('hide');
        });
}

function renderAdvisorOfferingsTable() {
    const tbody = document.getElementById('advCoursesTbody');
    tbody.innerHTML = '';

    const filterKeyword = (document.getElementById('advSearchInput').value || '').trim().toLowerCase();
    const activeCatBtn = document.querySelector('.adv-cat-filter.active');
    const filterCat = activeCatBtn ? activeCatBtn.dataset.cat : 'all';

    advAllOfferings.forEach(item => {
        const itemId = parseInt(item.id, 10);
        const isChecked = advCurrentSelectedIds.has(itemId);
        const isOriginallySelected = advInitialCourseIds.has(itemId);
        const cat = item.kategori_kontekstual || item.jenis_mk || 'Wajib';

        // Filtering
        if (filterCat !== 'all' && cat !== filterCat) return;
        if (filterKeyword !== '') {
            const searchHaystack = (item.kode_mk + ' ' + item.nama_mk + ' ' + (item.nama_dosen || '') + ' ' + (item.ruangan || '')).toLowerCase();
            if (!searchHaystack.includes(filterKeyword)) return;
        }

        let badgeCatClass = 'badge-primary';
        if (cat === 'Jurusan') badgeCatClass = 'badge-info';
        else if (cat === 'Luar Jurusan') badgeCatClass = 'badge-warning text-dark';
        else if (cat === 'Umum') badgeCatClass = 'badge-success';

        let badgeOrigin = '';
        if (isOriginallySelected && isChecked) {
            badgeOrigin = '<span class="badge badge-light border text-muted" style="font-size: 10px;">Asal Mahasiswa</span>';
        } else if (!isOriginallySelected && isChecked) {
            badgeOrigin = '<span class="badge badge-warning text-dark font-weight-bold" style="font-size: 10px;">+ Dipilihkan Dosen</span>';
        }

        const tr = document.createElement('tr');
        tr.id = 'adv-row-' + itemId;
        if (isChecked) tr.classList.add('table-info');

        tr.innerHTML = `
            <td class="text-center align-middle py-2" style="width: 45px;">
                <input type="checkbox" name="penawaran[]" value="${itemId}" class="adv-checkbox" data-id="${itemId}" data-sks="${item.sks}" ${isChecked ? 'checked' : ''} style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
            </td>
            <td class="font-weight-bold text-dark align-middle py-2 font-monospace" style="font-size: 12px; white-space: nowrap; width: 85px;">
                ${item.kode_mk}
            </td>
            <td class="align-middle py-2">
                <div class="font-weight-bold text-dark" style="font-size: 13px; line-height: 1.3;">${item.nama_mk}</div>
                <div class="small text-muted" style="font-size: 11px;">Kelas: ${item.kelas || 'A'} &bull; Prodi: ${item.prodi || '-'}</div>
            </td>
            <td class="text-center align-middle py-2" style="width: 65px;">
                <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 11.5px;">${item.sks} SKS</span>
            </td>
            <td class="align-middle py-2" style="width: 105px; white-space: nowrap;">
                <span class="badge ${badgeCatClass} px-2 py-1" style="font-size: 10.5px; border-radius: 5px;">${cat}</span>
            </td>
            <td class="align-middle py-2" style="white-space: nowrap; width: 170px;">
                <div><strong class="text-dark" style="font-size: 12px;">${item.hari}</strong></div>
                <small class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o mr-1"></i>${(item.waktu_mulai || '').substring(0, 5)} - ${(item.waktu_selesai || '').substring(0, 5)} WIB</small>
            </td>
            <td class="align-middle py-2 text-muted small" style="white-space: nowrap; width: 130px;">
                <i class="fa fa-building-o mr-1 text-muted"></i>${item.ruangan || '-'}
            </td>
            <td class="align-middle py-2 text-muted small" style="max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${item.nama_dosen || '-'}">
                <i class="fa fa-user-o mr-1 text-muted"></i>${item.nama_dosen || '-'}
            </td>
            <td class="text-center align-middle py-2" id="adv-origin-${itemId}" style="width: 110px; white-space: nowrap;">
                ${badgeOrigin}
            </td>
        `;

        tbody.appendChild(tr);
    });

    // Checkbox event listeners
    document.querySelectorAll('.adv-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const id = parseInt(this.dataset.id, 10);
            if (this.checked) {
                advCurrentSelectedIds.add(id);
                document.getElementById('adv-row-' + id)?.classList.add('table-info');
            } else {
                advCurrentSelectedIds.delete(id);
                document.getElementById('adv-row-' + id)?.classList.remove('table-info');
            }

            // Update badge origin
            const originCell = document.getElementById('adv-origin-' + id);
            if (originCell) {
                if (this.checked) {
                    originCell.innerHTML = advInitialCourseIds.has(id)
                        ? '<span class="badge badge-light border text-muted" style="font-size: 10px;">Asal Mahasiswa</span>'
                        : '<span class="badge badge-warning text-dark font-weight-bold" style="font-size: 10px;">+ Dipilihkan Dosen</span>';
                } else {
                    originCell.innerHTML = '';
                }
            }

            recalculateAdvisorState();
        });
    });
}

function recalculateAdvisorState() {
    let totalSks = 0;
    let selectedCourses = [];

    advAllOfferings.forEach(item => {
        if (advCurrentSelectedIds.has(parseInt(item.id, 10))) {
            totalSks += parseInt(item.sks, 10);
            selectedCourses.push(item);
        }
    });

    const sksBadge = document.getElementById('advTotalSksBadge');
    sksBadge.textContent = totalSks + ' / ' + advMaxSks + ' SKS';
    if (totalSks > advMaxSks) {
        sksBadge.className = 'badge badge-danger px-3 py-1 font-weight-bold';
    } else if (totalSks >= 20) {
        sksBadge.className = 'badge badge-success px-3 py-1 font-weight-bold';
    } else {
        sksBadge.className = 'badge badge-primary px-3 py-1 font-weight-bold';
    }

    document.getElementById('advCountCoursesBadge').textContent = selectedCourses.length + ' Mata Kuliah';

    // Deteksi benturan jadwal
    const conflicts = [];
    for (let i = 0; i < selectedCourses.length; i++) {
        for (let j = i + 1; j < selectedCourses.length; j++) {
            const a = selectedCourses[i];
            const b = selectedCourses[j];
            if (a.hari && b.hari && a.hari.toLowerCase() === b.hari.toLowerCase()) {
                const startA = timeToMinutes(a.waktu_mulai);
                const endA   = timeToMinutes(a.waktu_selesai);
                const startB = timeToMinutes(b.waktu_mulai);
                const endB   = timeToMinutes(b.waktu_selesai);
                if (Math.max(startA, startB) < Math.min(endA, endB)) {
                    conflicts.push({
                        a: a,
                        b: b,
                        hari: a.hari,
                        jamA: (a.waktu_mulai || '').substring(0, 5) + '-' + (a.waktu_selesai || '').substring(0, 5),
                        jamB: (b.waktu_mulai || '').substring(0, 5) + '-' + (b.waktu_selesai || '').substring(0, 5)
                    });
                }
            }
        }
    }

    const conflictAlert = document.getElementById('advConflictAlert');
    const conflictDetails = document.getElementById('advConflictDetails');

    if (conflicts.length > 0) {
        conflictAlert.style.display = 'block';
        let detailHtml = '';
        conflicts.forEach(cf => {
            detailHtml += `<div>&bull; <strong>${cf.a.kode_mk} (${cf.a.nama_mk})</strong> bertabrakan dengan <strong>${cf.b.kode_mk} (${cf.b.nama_mk})</strong> pada hari <strong>${cf.hari}</strong> (${cf.jamA} vs ${cf.jamB}).</div>`;
        });
        conflictDetails.innerHTML = detailHtml;
    } else {
        conflictAlert.style.display = 'none';
        conflictDetails.innerHTML = '';
    }

    const btnApprove = document.getElementById('btnAdvSaveAndApprove');
    const btnDraft = document.getElementById('btnAdvSaveDraft');

    if (totalSks > advMaxSks || selectedCourses.length === 0) {
        btnApprove.disabled = true;
        btnDraft.disabled = (selectedCourses.length === 0);
    } else {
        btnApprove.disabled = false;
        btnDraft.disabled = false;
    }
}

function submitAdvisorEdit(action) {
    if (advCurrentSelectedIds.size === 0) {
        scAlert('Pilih minimal 1 mata kuliah untuk rencana studi mahasiswa.', 'Mata Kuliah Kosong', 'warning');
        return;
    }

    let totalSks = 0;
    advAllOfferings.forEach(item => {
        if (advCurrentSelectedIds.has(parseInt(item.id, 10))) {
            totalSks += parseInt(item.sks, 10);
        }
    });

    if (totalSks > advMaxSks) {
        scAlert('Total beban studi (' + totalSks + ' SKS) melebihi batas maksimal ' + advMaxSks + ' SKS.', 'Batas SKS Terlampaui', 'warning');
        return;
    }

    const conflictAlert = document.getElementById('advConflictAlert');
    const hasConflict = (conflictAlert && conflictAlert.style.display !== 'none');

    function doSubmit() {
        const confirmMsg = (action === 'setujui')
            ? 'Simpan perubahan mata kuliah dan LANGSUNG SETUJUI rencana studi (KRS) mahasiswa ini?'
            : 'Simpan perubahan mata kuliah mahasiswa ini (status tetap Menunggu persetujuan)?';
        const confirmTitle = (action === 'setujui') ? 'Konfirmasi Simpan & Setujui' : 'Konfirmasi Simpan Rencana Studi';
        const acceptBtnText = (action === 'setujui') ? 'Ya, Simpan & Setujui' : 'Ya, Simpan';

        scConfirm(confirmMsg, confirmTitle, acceptBtnText, 'Batal').then(function(confirmed) {
            if (confirmed) {
                document.getElementById('advEditAksi').value = action;
                document.getElementById('formAdvisorEditKrs').submit();
            }
        });
    }

    if (hasConflict) {
        scConfirm('Peringatan: Terdapat benturan jadwal perkuliahan pada mata kuliah yang dipilih. Apakah Anda yakin ingin tetap menyimpannya?', 'Peringatan Benturan Jadwal', 'Tetap Simpan', 'Periksa Kembali').then(function(confirmed) {
            if (confirmed) {
                doSubmit();
            }
        });
    } else {
        doSubmit();
    }
}

// Filter listeners for modal
document.querySelectorAll('.adv-cat-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.adv-cat-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        renderAdvisorOfferingsTable();
    });
});

document.getElementById('advSearchInput')?.addEventListener('input', function() {
    renderAdvisorOfferingsTable();
});
</script>
