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
    padding: 8px 18px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    color: #4b5563;
    background: #f3f4f6;
    margin-right: 8px;
    margin-bottom: 8px;
    display: inline-flex;
    align-items: center;
    text-decoration: none !important;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.krs-filter-pill:hover {
    background: #e5e7eb;
    color: #1f2937;
}
.krs-filter-pill.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}
.krs-filter-pill.active .badge {
    background: rgba(255, 255, 255, 0.25);
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
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
                        <div class="card-body p-3">
                            <form method="GET" action="<?= base_url('dosen/persetujuan-krs') ?>" id="krsFilterForm">
                                <input type="hidden" name="status" id="filterStatusInput" value="<?= html_escape($active_status) ?>">

                                <div class="row align-items-center">
                                    <!-- STATUS PILLS -->
                                    <div class="col-xl-6 col-lg-12 mb-3 mb-xl-0">
                                        <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                                            <a href="javascript:void(0)" onclick="setKrsStatus('menunggu')" class="krs-filter-pill <?= ($active_status === 'menunggu') ? 'active' : '' ?>">
                                                <i class="fa fa-clock-o mr-1"></i> Menunggu
                                                <span class="badge badge-light ml-2"><?= (int)($stats['pending'] ?? 0) ?></span>
                                            </a>
                                            <a href="javascript:void(0)" onclick="setKrsStatus('disetujui')" class="krs-filter-pill <?= ($active_status === 'disetujui') ? 'active' : '' ?>">
                                                <i class="fa fa-check mr-1"></i> Disetujui
                                                <span class="badge badge-light ml-2"><?= (int)($stats['approved'] ?? 0) ?></span>
                                            </a>
                                            <a href="javascript:void(0)" onclick="setKrsStatus('ditolak')" class="krs-filter-pill <?= ($active_status === 'ditolak') ? 'active' : '' ?>">
                                                <i class="fa fa-times mr-1"></i> Perlu Revisi
                                                <span class="badge badge-light ml-2"><?= (int)($stats['rejected'] ?? 0) ?></span>
                                            </a>
                                            <a href="javascript:void(0)" onclick="setKrsStatus('semua')" class="krs-filter-pill <?= ($active_status === 'semua' || empty($active_status)) ? 'active' : '' ?>">
                                                <i class="fa fa-list mr-1"></i> Semua Pengajuan
                                            </a>
                                        </div>
                                    </div>

                                    <!-- PRODI & SEARCH & FILTER DOSEN -->
                                    <div class="col-xl-6 col-lg-12">
                                        <div class="d-flex flex-wrap align-items-center justify-content-xl-end" style="gap: 8px;">
                                            <?php if ($is_admin && !empty($all_lecturers)): ?>
                                                <div class="mr-2 mb-2 flex-grow-1" style="min-width: 170px; max-width: 220px;">
                                                    <select name="dosen_id" class="form-control form-control-sm font-weight-bold" onchange="document.getElementById('krsFilterForm').submit()" style="border-radius: 8px;">
                                                        <option value="0" <?= ($selected_dosen == 0) ? 'selected' : '' ?>>-- Semua Dosen Wali --</option>
                                                        <?php foreach ($all_lecturers as $lec): ?>
                                                            <option value="<?= (int)$lec->id ?>" <?= ($selected_dosen == $lec->id) ? 'selected' : '' ?>>
                                                                Dosen Wali: <?= html_escape($lec->nama_lengkap) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php else: ?>
                                                <div class="mr-2 mb-2">
                                                    <span class="badge badge-light text-primary border font-weight-bold p-2 px-3" style="border-radius: 8px; font-size: 12px;">
                                                        <i class="fa fa-user-circle mr-1"></i> Mahasiswa Bimbingan Anda
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="mr-2 mb-2" style="min-width: 130px;">
                                                <select name="prodi" class="form-control form-control-sm" onchange="document.getElementById('krsFilterForm').submit()" style="border-radius: 8px;">
                                                    <option value="all" <?= ($active_prodi === 'all') ? 'selected' : '' ?>>Semua Prodi</option>
                                                    <option value="Informatika" <?= ($active_prodi === 'Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                                                    <option value="Sistem Informasi" <?= ($active_prodi === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                                                    <option value="Akuntansi" <?= ($active_prodi === 'Akuntansi') ? 'selected' : '' ?>>Akuntansi</option>
                                                </select>
                                            </div>

                                            <div class="input-group input-group-sm mb-2" style="width: 180px;">
                                                <input type="text" name="q" value="<?= html_escape($keyword) ?>" class="form-control" placeholder="Cari Nama / NIM..." style="border-radius: 8px 0 0 8px;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit" style="border-radius: 0 8px 8px 0;">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
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
                                $total_sks = (int)$item->total_sks;
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
                                        <div class="col-lg-6 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-center">
                                                <div class="student-avatar mr-3">
                                                    <?= $initial ?>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center flex-wrap mb-1">
                                                        <h5 class="font-weight-bold mb-0 text-dark mr-2" style="font-size: 16px;">
                                                            <?= html_escape($item->nama_lengkap) ?>
                                                        </h5>
                                                        <span class="badge <?= $status_badge_class ?> font-weight-bold px-2 py-1" style="border-radius: 6px; font-size: 11px;">
                                                            <?= $status_text ?>
                                                        </span>
                                                        <?php if (!empty($item->has_conflict)): ?>
                                                            <span class="badge badge-conflict ml-1">
                                                                <i class="fa fa-warning mr-1"></i> BENTROK JADWAL
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-muted" style="font-size: 12px;">
                                                        <span class="font-weight-bold text-dark">NIM: <?= html_escape($item->nim) ?></span>
                                                        <span class="mx-1">&bull;</span>
                                                        <span><?= html_escape($item->prodi ?: '-') ?></span>
                                                        <span class="mx-1">&bull;</span>
                                                        <span>Semester <?= (int)$item->semester ?></span>
                                                        <?php if (!empty($item->nama_dosen_wali)): ?>
                                                            <span class="mx-1">&bull;</span>
                                                            <span class="text-primary font-weight-bold"><i class="fa fa-user-circle-o mr-1"></i>Wali: <?= html_escape($item->nama_dosen_wali) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- RIGHT SIDE: SKS METER & STATUS SPP -->
                                        <div class="col-lg-6">
                                            <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 10px;">
                                                <!-- STATUS KEUANGAN -->
                                                <div class="mr-3 mb-2 mb-md-0 text-right">
                                                    <small class="text-muted d-block font-weight-bold" style="font-size: 10px;">STATUS KEUANGAN</small>
                                                    <?php 
                                                        $is_lunas = !empty($item->status_keuangan['boleh_krs'])
                                                                    || !empty($item->status_keuangan['buka_krs'])
                                                                    || !empty($item->status_keuangan['is_lunas'])
                                                                    || (isset($item->status_keuangan['status']) && strtoupper($item->status_keuangan['status']) === 'LUNAS');
                                                        $is_pending = (isset($item->status_keuangan['status']) && strtoupper($item->status_keuangan['status']) === 'PENDING');
                                                    ?>
                                                    <?php if ($is_lunas): ?>
                                                        <span class="badge badge-success px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                                                            <i class="fa fa-check mr-1"></i> LUNAS / DIIZINKAN
                                                        </span>
                                                    <?php elseif ($is_pending): ?>
                                                        <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11px; border-radius: 6px;" title="Pembayaran sedang diverifikasi Admin Keuangan">
                                                            <i class="fa fa-clock-o mr-1"></i> MENUNGGU VERIFIKASI
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger px-2 py-1" style="font-size: 11px; border-radius: 6px;" title="<?= html_escape($item->status_keuangan['pesan'] ?? 'Belum Lunas') ?>">
                                                            <i class="fa fa-times-circle mr-1"></i> TUNGGAKAN SPP
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- TOTAL SKS METER -->
                                                <div class="bg-light p-2 rounded text-center mr-3" style="min-width: 110px; border: 1px solid #e2e8f0;">
                                                    <small class="text-muted d-block font-weight-bold" style="font-size: 10px;">TOTAL BEBAN</small>
                                                    <span class="font-weight-bold text-primary" style="font-size: 18px; line-height: 1.2;">
                                                        <?= $total_sks ?> <small class="text-muted" style="font-size: 12px;">/ <?= $max_sks ?> SKS</small>
                                                    </span>
                                                    <div class="progress progress-sks mt-1" style="height: 4px;">
                                                        <div class="progress-bar <?= ($total_sks > 24) ? 'bg-danger' : (($total_sks >= 20) ? 'bg-success' : 'bg-primary') ?>" style="width: <?= $sks_pct ?>%;"></div>
                                                    </div>
                                                </div>

                                                <!-- QUICK ACTION BUTTONS -->
                                                <button class="btn btn-outline-secondary btn-sm mr-2" type="button" data-toggle="collapse" data-target="#krs-collapse-<?= (int)$item->id ?>" aria-expanded="false" style="border-radius: 8px;">
                                                    <i class="fa fa-list-alt mr-1"></i> Detail Matkul
                                                </button>

                                                <?php if ($item->status === 'MENUNGGU'): ?>
                                                    <button type="button" class="btn btn-success btn-sm px-3 font-weight-bold mr-1" style="border-radius: 8px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);" onclick="quickApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')">
                                                        <i class="fa fa-check-circle mr-1"></i> Setujui KRS
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm px-2 font-weight-bold" style="border-radius: 8px;" onclick="openRejectModal(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')">
                                                        <i class="fa fa-times-circle mr-1"></i> Tolak
                                                    </button>
                                                <?php elseif ($item->status === 'DISETUJUI'): ?>
                                                    <span class="badge badge-success font-weight-bold py-2 px-3 mr-1" style="border-radius: 8px; font-size: 12px;">
                                                        <i class="fa fa-check-circle mr-1"></i> Disetujui
                                                    </span>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;" onclick="cancelApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')">
                                                        <i class="fa fa-undo mr-1"></i> Batal
                                                    </button>
                                                <?php elseif ($item->status === 'DITOLAK'): ?>
                                                    <span class="badge badge-danger font-weight-bold py-2 px-3 mr-1" style="border-radius: 8px; font-size: 12px;">
                                                        <i class="fa fa-times-circle mr-1"></i> Perlu Revisi
                                                    </span>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;" onclick="cancelApprove(<?= (int)$item->id ?>, '<?= html_escape(addslashes($item->nama_lengkap)) ?>')">
                                                        <i class="fa fa-undo mr-1"></i> Batal
                                                    </button>
                                                <?php endif; ?>
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
                                        <div class="mt-2 p-2 px-3 rounded" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 12px; color: #92400e;">
                                            <i class="fa fa-commenting-o mr-1"></i>
                                            <strong>Catatan Dosen Wali:</strong> <?= html_escape($item->catatan) ?>
                                            <?php if (!empty($item->diproses_at)): ?>
                                                <span class="text-muted ml-2">(Diperbarui <?= date('d M Y H:i', strtotime($item->diproses_at)) ?> oleh <?= html_escape($item->nama_validator ?: 'Dosen') ?>)</span>
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
                                            <table class="table table-hover table-sm mb-0" style="font-size: 13px;">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="py-2" style="width: 100px;">Kode MK</th>
                                                        <th class="py-2">Mata Kuliah</th>
                                                        <th class="py-2 text-center" style="width: 70px;">SKS</th>
                                                        <th class="py-2" style="width: 130px;">Kategori</th>
                                                        <th class="py-2">Jadwal & Hari</th>
                                                        <th class="py-2">Ruangan</th>
                                                        <th class="py-2">Dosen Pengampu</th>
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
                                                                <button type="submit" name="aksi" value="setujui" class="btn btn-success btn-sm px-3 mr-1" style="border-radius: 8px; font-weight: 600;" onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI rencana studi untuk <?= html_escape(addslashes($item->nama_lengkap)) ?>?')">
                                                                    <i class="fa fa-check mr-1"></i> Setujui (Validasi KRS)
                                                                </button>
                                                                <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px; font-weight: 600;" onclick="return validateReject(<?= (int)$item->id ?>)">
                                                                    <i class="fa fa-times mr-1"></i> Tolak & Minta Revisi
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
                                                                <button type="submit" name="aksi" value="batalkan" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;" onclick="return confirm('Batalkan persetujuan KRS dan kembalikan ke antrean peninjauan?')">
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
                                                                <button type="submit" name="aksi" value="batalkan" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;" onclick="return confirm('Kembalikan status KRS ke antrean peninjauan?')">
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

<!-- FORM POST TERSEMBUNYI UNTUK AKSI CEPAT -->
<?= form_open('dosen/proses-krs', ['id' => 'formQuickAction', 'style' => 'display:none;']) ?>
    <input type="hidden" name="krs_id" id="quickActionKrsId" value="">
    <input type="hidden" name="aksi" id="quickActionAksi" value="">
    <input type="hidden" name="catatan" id="quickActionCatatan" value="">
<?= form_close() ?>

<script>
function setKrsStatus(status) {
    document.getElementById('filterStatusInput').value = status;
    document.getElementById('krsFilterForm').submit();
}

function quickApprove(krsId, studentName) {
    if (confirm('Apakah Anda yakin ingin MENYETUJUI rencana studi (KRS) untuk ' + studentName + '?')) {
        document.getElementById('quickActionKrsId').value = krsId;
        document.getElementById('quickActionAksi').value = 'setujui';
        document.getElementById('quickActionCatatan').value = 'KRS telah diperiksa dan disetujui oleh Dosen Wali.';
        document.getElementById('formQuickAction').submit();
    }
}

function openRejectModal(krsId, studentName) {
    document.getElementById('quickRejectKrsId').value = krsId;
    document.getElementById('quickRejectStudentName').innerText = studentName;
    document.getElementById('quickRejectNote').value = '';
    $('#modalQuickReject').modal('show');
}

function cancelApprove(krsId, studentName) {
    if (confirm('Batalkan status keputusan KRS untuk ' + studentName + ' dan kembalikan ke antrean peninjauan?')) {
        document.getElementById('quickActionKrsId').value = krsId;
        document.getElementById('quickActionAksi').value = 'batalkan';
        document.getElementById('quickActionCatatan').value = '';
        document.getElementById('formQuickAction').submit();
    }
}

function validateReject(krsId) {
    var noteInput = document.getElementById('catatan-' + krsId);
    if (!noteInput || !noteInput.value.trim()) {
        alert('Mohon isi alasan / catatan revisi terlebih dahulu sebelum menolak pengajuan KRS mahasiswa.');
        if (noteInput) noteInput.focus();
        return false;
    }
    return confirm('Apakah Anda yakin ingin MENOLAK dan meminta revisi rencana studi mahasiswa ini dengan catatan: "' + noteInput.value.trim() + '"?');
}
</script>
