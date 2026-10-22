<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$student = $ringkasan['student'] ?? null;
$riwayat_status = $ringkasan['riwayat_status'] ?? [];
$chart_data = $ringkasan['chart_data'] ?? ['labels' => [], 'ips' => [], 'ipk' => []];
$rangkuman = $ringkasan['rangkuman'] ?? [];
$grade_distribution = $ringkasan['grade_distribution'] ?? [];
$statistik_table = $ringkasan['statistik_table'] ?? [];

// Variabel ekstraksi aman untuk menjamin tidak ada PHP Notice
$student_name     = !empty($student->nama_lengkap) ? $student->nama_lengkap : 'Mahasiswa';
$student_nim      = !empty($student->nim) ? $student->nim : '-';
$student_telepon  = !empty($student->telepon) ? $student->telepon : '081234567890';
$student_email    = !empty($student->email) ? $student->email : ($student_nim . '@mhs.campuss.ac.id');
$student_prodi    = !empty($student->prodi) ? $student->prodi : 'Sistem Informasi';
$student_fakultas = !empty($student->fakultas) ? $student->fakultas : 'Fakultas Teknologi Informasi';
$student_jenjang  = !empty($student->jenjang) ? $student->jenjang : 'Strata 1';
$student_semester = !empty($student->semester) ? (int)$student->semester : 1;
$student_angkatan = !empty($student->angkatan) ? $student->angkatan : '2024';
$student_status   = !empty($student->status_akademik) ? $student->status_akademik : ($ringkasan['status_akademis'] ?? 'Aktif');
$student_alamat   = !empty($student->alamat) ? $student->alamat : '';
$student_foto     = !empty($student->foto) ? base_url('assets/images/' . $student->foto) : base_url('assets/images/avatar-4.png');
?>

<style>
/* ======================================================== */
/* SMART CAMPUS RINGKASAN MAHASISWA - PREMIUM HARMONIZED    */
/* ======================================================== */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.pcoded-inner-content,
.main-body,
.page-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}

/* 1. HERO PROFILE CARD (Harmonized with student-hero-banner & custom-card-white) */
.ringkasan-profile-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-top: 4px solid #0284c7 !important;
    border-radius: 18px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    padding: 24px 26px !important;
    margin-bottom: 24px !important;
    position: relative !important;
    overflow: hidden !important;
    transition: all 0.25s ease;
}

.ringkasan-avatar-box {
    width: 100px;
    height: 125px;
    border-radius: 14px;
    border: 3px solid #ffffff;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.1);
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
    position: relative;
}

.ringkasan-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Clean 2x2 Grid for Stat KPI Cards (Balanced & Spacious) */
.ringkasan-stat-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    width: 100%;
}

/* Ponsel kecil (< 576px): 1 kolom per baris agar teks tetap lega */
@media (max-width: 575.98px) {
    .ringkasan-stat-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}

/* Stat KPI Cards on Header */
.ringkasan-stat-chip {
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    display: flex;
    align-items: center;
    width: 100%;
    min-width: 0;
    min-height: 68px;
    box-sizing: border-box;
}

.ringkasan-stat-chip:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
}

.ringkasan-stat-chip .stat-icon-bubble {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
    margin-right: 12px;
}

.ringkasan-stat-chip .stat-info-wrap {
    min-width: 0;
    flex: 1 1 auto;
    overflow: hidden;
}

.ringkasan-stat-chip .stat-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b;
    margin-bottom: 2px;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ringkasan-stat-chip .stat-value {
    font-size: 16px;
    font-weight: 800;
    line-height: 1.25;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ringkasan-stat-chip .stat-sub {
    font-size: 11px;
    color: #64748b;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 575.98px) {
    .ringkasan-avatar-box {
        width: 80px !important;
        height: 105px !important;
        border-radius: 12px !important;
    }
    .ringkasan-profile-card {
        padding: 16px 16px !important;
        border-radius: 14px !important;
    }
}

/* Action Buttons */
.ringkasan-btn-phone {
    background-color: #10b981 !important;
    border: 1px solid #059669 !important;
    color: #ffffff !important;
    border-radius: 8px !important;
    padding: 7px 14px !important;
    font-size: 13px !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ringkasan-btn-phone:hover {
    background-color: #059669 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
}

.ringkasan-btn-email {
    background-color: #64748b !important;
    border: 1px solid #475569 !important;
    color: #ffffff !important;
    border-radius: 8px !important;
    padding: 7px 14px !important;
    font-size: 13px !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ringkasan-btn-email:hover {
    background-color: #475569 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.25);
}

.ringkasan-btn-more {
    background-color: #0d9488 !important;
    border: 1px solid #0f766e !important;
    color: #ffffff !important;
    border-radius: 8px !important;
    padding: 7px 16px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.ringkasan-btn-more:hover {
    background-color: #0f766e !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
}

.ringkasan-btn-settings {
    color: #0284c7 !important;
    background: #f0f9ff !important;
    border: 1px solid #bae6fd !important;
    border-radius: 8px !important;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.ringkasan-btn-settings:hover {
    background: #0284c7 !important;
    border-color: #0284c7 !important;
    color: #ffffff !important;
    transform: rotate(45deg);
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}

/* 2. CARD STYLING (Harmonized with custom-card-white) */
.ringkasan-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.ringkasan-card-header {
    background: #ffffff;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ringkasan-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
}

/* Table Riwayat Status */
.table-status-header th {
    background-color: #f1f5f9 !important;
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    border: none !important;
    padding: 12px 16px !important;
}

.table-status-body td {
    font-size: 13px !important;
    color: #334155 !important;
    padding: 12px 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
}

/* Table Rangkuman Nilai */
.table-rangkuman-header th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-size: 12.5px !important;
    font-weight: 700 !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 11px 16px !important;
}

.table-rangkuman-body td {
    font-size: 13px !important;
    color: #1e293b !important;
    padding: 11px 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
}

/* Tab Navigation */
.ringkasan-nav-tabs {
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 0 12px;
}

.ringkasan-nav-tabs .nav-link {
    border: none !important;
    color: #64748b !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    padding: 14px 20px !important;
    border-radius: 0 !important;
    background: transparent !important;
    border-bottom: 3px solid transparent !important;
    transition: all 0.2s ease;
}

.ringkasan-nav-tabs .nav-link:hover {
    color: #0284c7 !important;
}

.ringkasan-nav-tabs .nav-link.active {
    color: #0284c7 !important;
    background: #ffffff !important;
    font-weight: 800 !important;
    border-bottom: 3px solid #0284c7 !important;
}

/* Chart Badges */
.badge-ips-toggle {
    background-color: #f59e0b;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 6px;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
}

.badge-ips-toggle.inactive {
    background-color: #e2e8f0;
    color: #64748b;
    text-decoration: line-through;
}

.badge-ipk-toggle {
    background-color: #0284c7;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 6px;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
}

.badge-ipk-toggle.inactive {
    background-color: #e2e8f0;
    color: #64748b;
    text-decoration: line-through;
}

/* Fullscreen Mode */
.card-fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 99999 !important;
    border-radius: 0 !important;
    margin: 0 !important;
    overflow-y: auto !important;
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
    <!-- BAR PEMILIH MAHASISWA (KHUSUS ADMIN / SUPERADMIN / DOSEN) -->
    <?php if (!empty($is_admin) || !empty($is_dosen)): ?>
        <div class="card mb-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border-left: 4px solid #0284c7 !important; border-radius: 14px;">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-1 mb-sm-0">
                    <i class="fa fa-info-circle text-primary mr-2" style="font-size: 18px;"></i>
                    <span class="text-dark font-weight-bold" style="font-size: 13px;">Mode Pratinjau Administrator / Dosen:</span>
                    <span class="text-muted ml-2" style="font-size: 12.5px;">Data di bawah tersinkronisasi langsung dari database mahasiswa yang dipilih.</span>
                </div>
                <form method="get" action="<?= base_url('ringkasan') ?>" class="form-inline mb-0">
                    <select name="student_id" class="form-control form-control-sm mr-2" style="font-size: 12.5px; min-width: 250px; border-radius: 8px;" onchange="this.form.submit()">
                        <?php foreach ($all_students as $st): ?>
                            <option value="<?= $st->id ?>" <?= ($selected_student_id == $st->id) ? 'selected' : '' ?>>
                                <?= html_escape($st->nama_lengkap) ?> (NIM: <?= html_escape($st->nim) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px; border-radius: 8px;">
                        <i class="fa fa-refresh mr-1"></i> Muat
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- PROFILE HEADER CARD (DESAIN PREMIUM & HARMONIS DENGAN BERANDA & KEUANGAN) -->
    <div class="ringkasan-profile-card">
        <!-- TOP ROW BADGES: STATUS & PERIODE AKADEMIK -->
        <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom flex-wrap" style="gap: 8px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                    <i class="fa fa-mortarboard mr-1"></i> PORTAL MAHASISWA
                </span>
                <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                    <i class="fa fa-calendar mr-1"></i> SMT <?= $student_semester ?> (ANGKATAN <?= html_escape($student_angkatan) ?>)
                </span>
                <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                    <i class="fa fa-check-circle mr-1"></i> STATUS: <?= strtoupper(html_escape($student_status)) ?>
                </span>
            </div>
            <div>
                <a href="<?= base_url('profil') ?>" class="ringkasan-btn-settings" title="Pengaturan Profil">
                    <i class="fa fa-cog" style="font-size: 16px;"></i>
                </a>
            </div>
        </div>

        <div class="row align-items-center">
            <!-- 1. FOTO & IDENTITAS MAHASISWA -->
            <div class="col-xl-5 col-lg-5 col-12 mb-3 mb-lg-0">
                <div class="d-flex align-items-start">
                    <!-- Foto Mahasiswa -->
                    <div class="ringkasan-avatar-box mr-3">
                        <img src="<?= $student_foto ?>" alt="<?= html_escape($student_name) ?>" class="ringkasan-avatar-img" onerror="this.src='<?= base_url('assets/images/avatar-4.png') ?>'">
                    </div>

                    <!-- Info Identitas -->
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex align-items-center flex-wrap mb-1">
                            <h4 class="font-weight-bold text-dark mb-0 mr-2" style="font-size: 19px; letter-spacing: 0.2px;">
                                <?= strtoupper(html_escape($student_name)) ?>
                            </h4>
                            <span class="badge badge-light border text-muted px-2 py-0.5 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                <?= html_escape($student_nim) ?>
                            </span>
                        </div>

                        <div class="text-muted mb-2 font-weight-normal" style="font-size: 13px; line-height: 1.4;">
                            <span class="text-primary font-weight-bold"><i class="fa fa-graduation-cap mr-1"></i><?= html_escape($student_prodi) ?></span>
                            <span class="mx-1">•</span>
                            <span><?= html_escape($student_fakultas) ?></span>
                        </div>

                        <!-- Tombol Aksi Kontak & Detail -->
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <a href="tel:<?= html_escape($student_telepon) ?>" class="ringkasan-btn-phone shadow-sm" title="Hubungi via Telepon (<?= html_escape($student_telepon) ?>)">
                                <i class="fa fa-phone"></i>
                            </a>
                            <a href="mailto:<?= html_escape($student_email) ?>" class="ringkasan-btn-email shadow-sm" title="Kirim Email (<?= html_escape($student_email) ?>)">
                                <i class="fa fa-envelope"></i>
                            </a>
                            <button class="ringkasan-btn-more shadow-sm" type="button" data-toggle="collapse" data-target="#detailProfilCollapse" aria-expanded="false" aria-controls="detailProfilCollapse">
                                <span>Lihat Lainnya</span>
                                <i class="fa fa-chevron-down ml-1" style="font-size: 10px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. QUICK METRIC STAT CARDS (GRID 2x2 SEIMBANG & LEGA) -->
            <div class="col-xl-7 col-lg-7 col-12">
                <div class="ringkasan-stat-grid">
                    <!-- Stat 1: IPK Kumulatif -->
                    <div class="ringkasan-stat-chip" style="background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%); border-color: #bae6fd;">
                        <div class="stat-icon-bubble" style="background: #0284c7; color: #ffffff;">
                            <i class="fa fa-graduation-cap"></i>
                        </div>
                        <div class="stat-info-wrap">
                            <span class="stat-label">IPK Kumulatif</span>
                            <span class="stat-value text-primary"><?= number_format($ringkasan['ipk'], 2) ?></span>
                            <span class="stat-sub">Skala 4.00 (Sangat Memuaskan)</span>
                        </div>
                    </div>

                    <!-- Stat 2: SKS Lulus -->
                    <div class="ringkasan-stat-chip" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #bbf7d0;">
                        <div class="stat-icon-bubble" style="background: #10b981; color: #ffffff;">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="stat-info-wrap">
                            <span class="stat-label">Total SKS Lulus</span>
                            <span class="stat-value text-success"><?= (int)$ringkasan['sks_lulus'] ?> <small style="font-size: 11px; font-weight: normal;">SKS</small></span>
                            <span class="stat-sub"><?= (int)$ringkasan['total_mk'] ?> Mata Kuliah Diselesaikan</span>
                        </div>
                    </div>

                    <!-- Stat 3: Dosen Wali -->
                    <div class="ringkasan-stat-chip" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-color: #fde68a;">
                        <div class="stat-icon-bubble" style="background: #f59e0b; color: #ffffff;">
                            <i class="fa fa-user-circle"></i>
                        </div>
                        <div class="stat-info-wrap">
                            <span class="stat-label">Dosen Pembimbing (PA)</span>
                            <span class="stat-value text-dark" style="font-size: 13.5px;" title="<?= html_escape($ringkasan['dosen_wali']) ?>">
                                <?= html_escape($ringkasan['dosen_wali']) ?>
                            </span>
                            <span class="stat-sub">Konsultasi Akademik</span>
                        </div>
                    </div>

                    <!-- Stat 4: Semester Perkuliahan -->
                    <div class="ringkasan-stat-chip" style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border-color: #e9d5ff;">
                        <div class="stat-icon-bubble" style="background: #8b5cf6; color: #ffffff;">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div class="stat-info-wrap">
                            <span class="stat-label">Status Perkuliahan</span>
                            <span class="stat-value" style="color: #7c3aed;">Semester <?= $student_semester ?> Aktif</span>
                            <span class="stat-sub">Tahun Akademik 2024/2025</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAIL TAMBAHAN (COLLAPSIBLE BOX) -->
        <div class="collapse mt-3 pt-3 border-top" id="detailProfilCollapse">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-graduation-cap text-primary mr-1"></i> Program Studi:</small>
                        <span class="text-dark font-weight-bold" style="font-size: 13px;"><?= html_escape($student_prodi) ?></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-building-o text-info mr-1"></i> Fakultas:</small>
                        <span class="text-dark font-weight-bold" style="font-size: 13px;"><?= html_escape($student_fakultas) ?></span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-bookmark-o text-warning mr-1"></i> Jenjang / Smt:</small>
                        <span class="text-dark font-weight-bold" style="font-size: 13px;"><?= html_escape($student_jenjang) ?> (Smt <?= $student_semester ?>)</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-calendar-o text-secondary mr-1"></i> Angkatan:</small>
                        <span class="text-dark font-weight-bold" style="font-size: 13px;"><?= html_escape($student_angkatan) ?></span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <div class="p-2.5 rounded bg-light border text-center" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-check-circle text-success mr-1"></i> Status:</small>
                        <span class="badge badge-success px-2 py-1" style="font-size: 11px; border-radius: 6px;"><?= html_escape($student_status) ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mt-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-envelope text-primary mr-1"></i> Email Mahasiswa:</small>
                        <span class="text-dark" style="font-size: 13px;"><?= html_escape($student_email) ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mt-2">
                    <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                        <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-phone text-success mr-1"></i> No. Telepon / WhatsApp:</small>
                        <span class="text-dark" style="font-size: 13px;"><?= html_escape($student_telepon) ?></span>
                    </div>
                </div>
                <?php if (!empty($student_alamat)): ?>
                    <div class="col-12 mt-2">
                        <div class="p-2.5 rounded bg-light border" style="border-radius: 10px; padding: 10px 14px;">
                            <small class="text-muted d-block font-weight-bold mb-1"><i class="fa fa-map-marker text-danger mr-1"></i> Domisili / Alamat:</small>
                            <span class="text-dark" style="font-size: 13px;"><?= html_escape($student_alamat) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- MAIN GRID KONTEN RINGKASAN -->
    <div class="row">
        <!-- KOLOM KIRI (RINGKASAN AKADEMIS & RIWAYAT STATUS AKADEMIS) -->
        <div class="col-xl-4 col-lg-5 col-12 mb-3">
            <!-- 1. KARTU RINGKASAN AKADEMIS -->
            <div class="ringkasan-card">
                <div class="ringkasan-card-header">
                    <h6 class="ringkasan-card-title">
                        <i class="fa fa-star text-warning mr-2" style="font-size: 15px;"></i> Ringkasan Akademis
                    </h6>
                </div>
                <div class="p-0">
                    <table class="table mb-0" style="font-size: 13.5px;">
                        <tbody>
                            <!-- Dosen Wali -->
                            <tr>
                                <td class="py-3 px-3 border-top-0 text-muted" style="width: 42%;">
                                    <i class="fa fa-user text-warning mr-2"></i> Dosen Wali
                                </td>
                                <td class="py-3 px-3 border-top-0 text-right font-weight-bold text-dark">
                                    <?= html_escape($ringkasan['dosen_wali']) ?>
                                </td>
                            </tr>
                            <!-- SKS Lulus -->
                            <tr>
                                <td class="py-3 px-3 text-muted">
                                    <i class="fa fa-book text-primary mr-2"></i> SKS Lulus
                                </td>
                                <td class="py-3 px-3 text-right font-weight-bold text-dark" style="font-size: 14.5px;">
                                    <?= (int)$ringkasan['sks_lulus'] ?>
                                </td>
                            </tr>
                            <!-- Total Nilai -->
                            <tr>
                                <td class="py-3 px-3 text-muted">
                                    <i class="fa fa-database text-info mr-2"></i> Total Nilai
                                </td>
                                <td class="py-3 px-3 text-right font-weight-bold text-dark">
                                    <?= (int)$ringkasan['total_mk'] ?> MK
                                </td>
                            </tr>
                            <!-- IPK -->
                            <tr>
                                <td class="py-3 px-3 text-muted">
                                    <i class="fa fa-id-card text-success mr-2"></i> IPK
                                </td>
                                <td class="py-3 px-3 text-right font-weight-bold text-dark" style="font-size: 15.5px;">
                                    <?= number_format($ringkasan['ipk'], 2) ?>
                                </td>
                            </tr>
                            <!-- Status Akademis -->
                            <tr>
                                <td class="py-3 px-3 text-muted border-bottom-0">
                                    <i class="fa fa-tag text-purple mr-2"></i> Status Akademis
                                </td>
                                <td class="py-3 px-3 text-right font-weight-bold border-bottom-0 text-dark">
                                    <?= html_escape($ringkasan['status_akademis']) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. KARTU RIWAYAT STATUS AKADEMIS -->
            <div class="ringkasan-card">
                <div class="ringkasan-card-header">
                    <h6 class="ringkasan-card-title">
                        <i class="fa fa-clock-o text-muted mr-2" style="font-size: 15px;"></i> Riwayat Status Akademis
                    </h6>
                </div>
                <div class="table-responsive p-0">
                    <table class="table mb-0">
                        <thead class="table-status-header">
                            <tr>
                                <th style="width: 30%;">Tahun</th>
                                <th style="width: 40%;">Periode</th>
                                <th style="width: 30%;">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-status-body">
                            <?php if (!empty($riwayat_status)): ?>
                                <?php foreach ($riwayat_status as $rs): ?>
                                    <tr>
                                        <td><?= html_escape($rs['tahun']) ?></td>
                                        <td><?= html_escape($rs['periode']) ?></td>
                                        <td><?= html_escape($rs['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>2024</td>
                                    <td>Ganjil</td>
                                    <td>Aktif</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (GRAFIK NILAI & STATISTIK NILAI) -->
        <div class="col-xl-8 col-lg-7 col-12 mb-3">
            <div class="ringkasan-card" id="rightMainCard">
                <!-- TABS HEADER -->
                <div class="d-flex align-items-center justify-content-between ringkasan-nav-tabs">
                    <ul class="nav nav-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tabGrafikLink" data-toggle="tab" href="#tabGrafik" role="tab">
                                <i class="fa fa-line-chart mr-1 text-dark"></i> Grafik Nilai
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabStatistikLink" data-toggle="tab" href="#tabStatistik" role="tab">
                                <i class="fa fa-clock-o mr-1 text-muted"></i> Statistik Nilai
                            </a>
                        </li>
                    </ul>
                    <div class="pr-2">
                        <a href="javascript:void(0)" class="text-primary" onclick="toggleFullscreenCard('rightMainCard')" title="Perbesar Tampilan">
                            <i class="fa fa-arrows-alt" style="font-size: 14px;"></i>
                        </a>
                    </div>
                </div>

                <!-- TABS CONTENT -->
                <div class="tab-content p-3 p-md-4">
                    <!-- TAB 1: GRAFIK NILAI -->
                    <div class="tab-pane fade show active" id="tabGrafik" role="tabpanel">
                        <h6 class="font-weight-bold text-primary mb-3" style="font-size: 14.5px;">Grafik Nilai Semester</h6>

                        <!-- TOGGLE BADGES (IPS & IPK) -->
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge-ips-toggle mr-2" id="toggleIPS" onclick="toggleDataset(0, this)">
                                IPS
                            </span>
                            <span class="badge-ipk-toggle" id="toggleIPK" onclick="toggleDataset(1, this)">
                                IPK
                            </span>
                        </div>

                        <!-- CANVAS GRAFIK NILAI -->
                        <div style="position: relative; width: 100%; min-height: 240px; margin-bottom: 20px;">
                            <canvas id="nilaiSemesterChart"></canvas>
                        </div>

                        <!-- LEGEND INDIKATOR -->
                        <div class="d-flex align-items-center justify-content-center mb-4" style="font-size: 12.5px;">
                            <span class="mr-4" style="color: #f59e0b; font-weight: 600;">
                                <i class="fa fa-circle mr-1"></i> IPS
                            </span>
                            <span style="color: #0288d1; font-weight: 600;">
                                <i class="fa fa-circle mr-1"></i> IPK
                            </span>
                        </div>

                        <!-- SECTION RANGKUMAN NILAI -->
                        <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14.5px;">Rangkuman Nilai</h6>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-rangkuman-header">
                                    <tr>
                                        <th style="width: 50%;">Jenis Rangkuman</th>
                                        <th style="width: 25%;">Nilai</th>
                                        <th style="width: 25%;">Periode Semester</th>
                                    </tr>
                                </thead>
                                <tbody class="table-rangkuman-body">
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle-o text-success mr-2 font-weight-bold"></i> Indeks Prestasi Semester Tertinggi
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            <?= number_format($rangkuman['max_ips'] ?? 0, 2) ?>
                                        </td>
                                        <td>
                                            <?= html_escape($rangkuman['max_ips_period'] ?? '-') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle-o text-danger mr-2 font-weight-bold"></i> Indeks Prestasi Semester Terendah
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            <?= number_format($rangkuman['min_ips'] ?? 0, 2) ?>
                                        </td>
                                        <td>
                                            <?= html_escape($rangkuman['min_ips_period'] ?? '-') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle-o text-info mr-2 font-weight-bold"></i> Rata-rata Indeks Prestasi Semester
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            <?= number_format($rangkuman['avg_ips'] ?? 0, 2) ?>
                                        </td>
                                        <td>
                                            Seluruh Semester
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle-o text-primary mr-2 font-weight-bold"></i> Indeks Prestasi Kumulatif (IPK)
                                        </td>
                                        <td class="font-weight-bold text-primary" style="font-size: 14px;">
                                            <?= number_format($ringkasan['ipk'] ?? 0, 2) ?>
                                        </td>
                                        <td>
                                            Semester Berjalan
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: STATISTIK NILAI (PERSIS SEPERTI GAMBAR CONTOH) -->
                    <div class="tab-pane fade" id="tabStatistik" role="tabpanel">
                        <h6 class="font-weight-bold text-primary mb-3" style="font-size: 15px;">Statistik Nilai</h6>

                        <!-- TABEL STATISTIK NILAI LENGKAP -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered mb-0 text-center" style="font-size: 13px; border-color: #e2e8f0;">
                                <thead>
                                    <tr style="background-color: #f8fafc; color: #334155; font-weight: 700;">
                                        <th colspan="2" class="align-middle border-bottom-0 py-2">Periode</th>
                                        <th colspan="5" class="align-middle border-bottom-0 py-2">Per Term / Semester</th>
                                        <th colspan="5" class="align-middle border-bottom-0 py-2">Kumulatif</th>
                                    </tr>
                                    <tr style="background-color: #f8fafc; color: #475569; font-size: 12.5px; font-weight: 700;">
                                        <th class="py-2" style="width: 8%;">Tahun</th>
                                        <th class="py-2" style="width: 10%;">Semester</th>
                                        <th class="py-2" style="width: 6%;">MK</th>
                                        <th class="py-2" style="width: 7%;">SKS A</th>
                                        <th class="py-2" style="width: 7%;">SKS L</th>
                                        <th class="py-2" style="width: 8%;">Mutu</th>
                                        <th class="py-2" style="width: 7%;">IP</th>
                                        <th class="py-2" style="width: 7%;">SKS A</th>
                                        <th class="py-2" style="width: 7%;">SKS L</th>
                                        <th class="py-2" style="width: 8%;">Mutu</th>
                                        <th class="py-2" style="width: 7%;">IPK</th>
                                        <th class="py-2" style="width: 8%;">SKS DPO</th>
                                    </tr>
                                </thead>
                                <tbody style="color: #1e293b; background-color: #ffffff;">
                                    <?php if (!empty($statistik_table)): ?>
                                        <?php foreach ($statistik_table as $row): ?>
                                            <tr>
                                                <td class="font-weight-normal"><?= html_escape($row['tahun']) ?></td>
                                                <td><?= html_escape($row['semester']) ?></td>
                                                <td><?= $row['mk'] ?></td>
                                                <td><?= $row['sks_a'] ?></td>
                                                <td><?= ($row['is_ongoing']) ? 0 : $row['sks_l'] ?></td>
                                                <td><?= ($row['is_ongoing']) ? '0.0' : number_format($row['mutu'], 1) ?></td>
                                                <td><?= ($row['is_ongoing']) ? '0' : number_format($row['ip'], 2) ?></td>
                                                <td><?= $row['cum_sks_a'] ?></td>
                                                <td><?= ($row['is_ongoing']) ? '' : $row['cum_sks_l'] ?></td>
                                                <td><?= ($row['is_ongoing']) ? '' : number_format($row['cum_mutu'], 1) ?></td>
                                                <td><?= ($row['is_ongoing']) ? '0' : number_format($row['cum_ipk'], 2) ?></td>
                                                <td><?= ($row['is_ongoing']) ? '' : $row['cum_sks_dpo'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="12" class="text-muted py-3">Belum ada rekaman statistik perkuliahan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- KOTAK KETERANGAN (PERSIS SEPERTI GAMBAR CONTOH) -->
                        <div class="card border shadow-none mb-0" style="background: #ffffff; border-radius: 8px; border-color: #e2e8f0 !important;">
                            <div class="card-header bg-white py-3 px-3 border-bottom" style="border-color: #e2e8f0 !important;">
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 13.5px;">
                                    <i class="fa fa-paperclip text-muted mr-2"></i> Keterangan
                                </h6>
                            </div>
                            <div class="card-body py-2 px-3" style="font-size: 13px; color: #475569;">
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">MK = jumlah mata kuliah diikuti</div>
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">SKS A = jumlah SKS yang diambil</div>
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">SKS L = jumlah SKS yang lulus, yaitu yang nilainya &gt;= C</div>
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">SKS Dpo = jumlah SKS diperoleh dari masing-masing mata kuliah, yaitu jumlah SKS dari mata kuliah yang nilainya &gt;= C</div>
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">Mutu = jumlah SKS x bobot nilai</div>
                                <div class="py-2" style="border-bottom: 1px dotted #e2e8f0;">IP = Indeks Prestasi</div>
                                <div class="py-2">IPK = Indeks Prestasi Kumulatif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT CHART.JS UNTUK GRAFIK NILAI SEMESTER -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var rawLabels = <?= json_encode(!empty($chart_data['labels']) ? $chart_data['labels'] : ['2024 Ganjil']) ?>;
    var rawIPS = <?= json_encode(!empty($chart_data['ips']) ? $chart_data['ips'] : [3.00]) ?>;
    var rawIPK = <?= json_encode(!empty($chart_data['ipk']) ? $chart_data['ipk'] : [3.00]) ?>;

    var ctx = document.getElementById('nilaiSemesterChart');
    if (!ctx) return;

    var chartInstance = null;

    if (typeof Chart !== 'undefined') {
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawLabels,
                datasets: [
                    {
                        label: 'IPS',
                        data: rawIPS,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.2,
                        fill: false
                    },
                    {
                        label: 'IPK',
                        data: rawIPK,
                        borderColor: '#0288d1',
                        backgroundColor: 'rgba(2, 136, 209, 0.1)',
                        pointBackgroundColor: '#0288d1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + Number(context.parsed.y).toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 4.0,
                        ticks: {
                            stepSize: 0.4,
                            color: '#64748b',
                            font: {
                                size: 11
                            },
                            callback: function(val) {
                                return Number(val).toFixed(1);
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Toggle Dataset Visibility via Badges
    window.toggleDataset = function(datasetIndex, element) {
        if (!chartInstance) return;
        var isVisible = chartInstance.isDatasetVisible(datasetIndex);
        chartInstance.setDatasetVisibility(datasetIndex, !isVisible);
        chartInstance.update();

        if (isVisible) {
            element.classList.add('inactive');
        } else {
            element.classList.remove('inactive');
        }
    };

    // Fullscreen Card Toggle
    window.toggleFullscreenCard = function(cardId) {
        var card = document.getElementById(cardId);
        if (!card) return;
        card.classList.toggle('card-fullscreen');
        if (chartInstance) {
            setTimeout(function() {
                chartInstance.resize();
            }, 200);
        }
    };
});
</script>
