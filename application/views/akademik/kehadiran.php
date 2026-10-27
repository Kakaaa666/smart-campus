<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$student = $kehadiran_data['student'] ?? null;
$courses = $kehadiran_data['courses'] ?? [];
$available_periods = $kehadiran_data['available_periods'] ?? [];
$selected_period = $kehadiran_data['selected_period'] ?? null;

$is_admin = !empty($is_admin);
$is_dosen = !empty($is_dosen);
$all_students = $all_students ?? [];
$selected_student_id = $selected_student_id ?? ($student ? (int)$student->id : 0);

if (!function_exists('render_badge_kehadiran')) {
    function render_badge_kehadiran($status) {
        if ($status === 'HADIR') {
            return '<span class="badge-hadir">Hadir</span>';
        } elseif ($status === 'SAKIT') {
            return '<span class="badge-sakit">Sakit</span>';
        } elseif ($status === 'IJIN') {
            return '<span class="badge-ijin">Ijin</span>';
        } elseif ($status === 'ALPHA') {
            return '<span class="badge-alpha">Alpa</span>';
        }
        return '';
    }
}
?>


<style>
/* ======================================================== */
/* KEHADIRAN KULIAH - PERSIS SESUAI FOTO SIAKAD & DATA RIIL */
/* ======================================================== */

.kehadiran-container {
    padding: 0 5px;
}

.kehadiran-card {
    border-radius: 6px !important;
    background: #ffffff !important;
    border: 1px solid #d0d7e2 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
    overflow: hidden;
    margin-bottom: 25px;
}

/* Header Biru Cerah persis sesuai screenshot */
.kehadiran-header {
    background: linear-gradient(135deg, #3bbcf6 0%, #0093e9 100%) !important;
    padding: 12px 20px !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.kehadiran-header .header-title-box {
    display: flex;
    align-items: center;
    gap: 12px;
}

.kehadiran-header .header-bell-icon {
    font-size: 1.35rem;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.15));
}

.kehadiran-header h4 {
    color: #ffffff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    margin: 0 !important;
    letter-spacing: 0.2px;
}

.kehadiran-header .header-controls {
    display: flex;
    align-items: center;
    gap: 14px;
    color: rgba(255, 255, 255, 0.85);
    font-size: 13.5px;
}

.kehadiran-header .header-controls a {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    transition: color 0.2s;
    cursor: pointer;
}

.kehadiran-header .header-controls a:hover {
    color: #ffffff;
}

/* Card Body & Tabs Bar */
.kehadiran-body {
    padding: 16px 20px 24px;
    background: #ffffff;
}

.kehadiran-nav-wrapper {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.kehadiran-tabs {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 4px;
    border-bottom: none !important;
}

.kehadiran-tabs .tab-item {
    margin-bottom: -2px;
    list-style: none;
}

.kehadiran-tabs .tab-link,
.kehadiran-tabs .nav-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 8px 16px !important;
    min-height: 40px !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    color: #55627a !important;
    background: #f8fafc !important;
    border: 1px solid #d0d7e2 !important;
    border-bottom: 2px solid #e2e8f0 !important;
    border-top: 3px solid transparent !important;
    border-top-left-radius: 5px !important;
    border-top-right-radius: 5px !important;
    text-decoration: none !important;
    transition: all 0.2s ease;
    cursor: pointer !important;
    user-select: none;
    white-space: nowrap !important;
    line-height: 1.2 !important;
    margin: 0 !important;
}

.kehadiran-tabs .tab-link:hover,
.kehadiran-tabs .nav-link:hover {
    color: #0288d1 !important;
    background: #f1f5f9 !important;
    border-top-color: #94a3b8 !important;
}

.kehadiran-tabs .tab-item.active .tab-link,
.kehadiran-tabs .tab-item.active .nav-link,
.kehadiran-tabs .tab-link.active,
.kehadiran-tabs .nav-link.active {
    color: #0f172a !important;
    font-weight: 700 !important;
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    border-bottom: 2px solid #ffffff !important;
    border-top: 3px solid #0288d1 !important;
    box-shadow: none !important;
}

/* Explicitly reset any stray global theme margin on tab icons */
.kehadiran-tabs .tab-icon,
.kehadiran-tabs .kehadiran-tab-icon {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    line-height: 1 !important;
    flex-shrink: 0 !important;
}

.kehadiran-tabs .tab-icon svg,
.kehadiran-tabs .kehadiran-tab-icon svg {
    display: block !important;
    margin: 0 !important;
    width: 16px;
    height: 16px;
    vertical-align: middle !important;
}

.kehadiran-tabs .tab-label {
    display: inline-block !important;
    line-height: 1.2 !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Period Dropdown Filter di Kanan Atas Tabel */
.kehadiran-filter-box {
    margin: 0 0 4px 0 !important;
    display: flex;
    align-items: center;
}

.select-periode-siakad {
    min-width: 175px;
    height: 38px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 6px 14px;
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
    background-color: #ffffff;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}

.select-periode-siakad:hover {
    border-color: #94a3b8;
}

.select-periode-siakad:focus {
    border-color: #0288d1;
    box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
}

/* Wrapper Tabel dengan Border Halus */
.table-kehadiran-box {
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    overflow: hidden;
    background: #ffffff;
}

.table-kehadiran-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Header Tabel Warna Slate / Lavender Grey persis seperti di screenshot */
.table-kehadiran {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    min-width: 980px;
}

.table-kehadiran thead th {
    background-color: #b8bdd0 !important;
    color: #2b3345 !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    padding: 12px 12px !important;
    border-bottom: 1px solid #9ea5bc !important;
    border-top: none !important;
    white-space: nowrap;
    vertical-align: middle;
    letter-spacing: 0.1px;
}

.table-kehadiran tbody td {
    padding: 12px 12px !important;
    font-size: 13.5px !important;
    color: #334155;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-kehadiran tbody tr:hover {
    background-color: #f8fafc;
}

.table-kehadiran td.col-center,
.table-kehadiran th.col-center {
    text-align: center;
}

.table-kehadiran td.col-number,
.table-kehadiran th.col-number {
    text-align: center;
    width: 45px;
}

.table-kehadiran .mk-kode {
    font-weight: 600;
    color: #475569;
    letter-spacing: 0.3px;
}

.table-kehadiran .mk-nama {
    font-weight: 600;
    color: #1e293b;
}

.table-kehadiran .mk-kelas {
    text-align: center;
    font-weight: 500;
    color: #334155;
}

.table-kehadiran .mk-persen {
    text-align: left;
    font-weight: 500;
    color: #1e293b;
    padding-left: 15px;
}

/* Badge Kehadiran persis gambar 2 dan gambar 3 */
.badge-hadir {
    background-color: #5cb85c !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    font-size: 11.5px !important;
    font-weight: 500 !important;
    padding: 4px 10px !important;
    display: inline-block;
}

.badge-sakit {
    background-color: #f0ad4e !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    font-size: 11.5px !important;
    font-weight: 500 !important;
    padding: 4px 10px !important;
    display: inline-block;
}

.badge-ijin {
    background-color: #f1c40f !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    font-size: 11.5px !important;
    font-weight: 500 !important;
    padding: 4px 10px !important;
    display: inline-block;
}

.badge-alpha {
    background-color: #d9534f !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    font-size: 11.5px !important;
    font-weight: 500 !important;
    padding: 4px 10px !important;
    display: inline-block;
}

/* Tombol Detil dengan Ikon List-ol Biru persis seperti screenshot */
.btn-detil-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0288d1;
    cursor: pointer;
    text-decoration: none;
    transition: transform 0.15s ease, color 0.15s ease;
    padding: 3px 6px;
    border-radius: 4px;
}

.btn-detil-icon:hover {
    color: #0277bd;
    background-color: #e1f5fe;
    transform: scale(1.08);
}

/* Admin / Dosen Student Selector Bar */
.admin-student-switcher {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.admin-student-switcher .info-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e293b;
    font-size: 14px;
    font-weight: 600;
}

/* Modal Detil Kehadiran Modern & Rapi */
.modal-detil-header {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
    color: #ffffff;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 16px 24px;
}

.modal-detil-header .modal-title {
    color: #ffffff !important;
    font-size: 16px;
    font-weight: 700;
}

.modal-detil-header .close {
    color: #ffffff;
    opacity: 0.85;
    text-shadow: none;
    font-size: 24px;
    line-height: 1;
}

.modal-detil-header .close:hover {
    opacity: 1;
}

/* Modal Summary Card */
.modal-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
}

.course-chips-wrap {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
}

.course-chip {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}

.chip-kode {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-family: monospace;
}

.chip-kelas {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.chip-sks {
    background: #ecfeff;
    color: #0e7490;
    border: 1px solid #a5f3fc;
}

.modal-course-title {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    margin: 4px 0 10px 0;
    letter-spacing: -0.2px;
}

.modal-meta-grid {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 13px;
    color: #64748b;
}

.modal-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.modal-meta-item strong {
    color: #1e293b;
}

/* Stat Percentage Box */
.modal-percentage-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 18px;
    text-align: center;
    min-width: 145px;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.modal-percentage-box .percentage-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #64748b;
}

.modal-percentage-box .percentage-value {
    font-size: 24px;
    font-weight: 800;
    color: #0284c7;
    line-height: 1.1;
    margin: 3px 0;
}

.modal-percentage-box .percentage-bar-wrap {
    width: 100%;
    height: 5px;
    background: #e2e8f0;
    border-radius: 4px;
    margin-top: 4px;
    overflow: hidden;
}

.modal-percentage-box .percentage-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    border-radius: 4px;
    transition: width 0.4s ease;
}

/* Attendance Metrics Strip - Pengganti Pills yang Berantakan */
.attendance-metrics-strip {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    margin-bottom: 16px;
}

@media (max-width: 768px) {
    .attendance-metrics-strip {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 480px) {
    .attendance-metrics-strip {
        grid-template-columns: repeat(2, 1fr);
    }
}

.att-metric-pill {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid transparent;
}

.att-metric-pill .att-metric-label {
    display: inline-flex;
    align-items: center;
}

.att-metric-pill .att-metric-value {
    font-size: 13px;
    font-weight: 800;
    margin-left: 6px;
    padding: 1px 7px;
    border-radius: 6px;
    min-width: 22px;
    text-align: center;
}

.metric-terselenggara {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #334155;
}

.metric-terselenggara .att-metric-value {
    background: #e2e8f0;
    color: #0f172a;
}

.metric-hadir {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #065f46;
}

.metric-hadir .att-metric-value {
    background: #10b981;
    color: #ffffff;
}

.metric-sakit {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}

.metric-sakit .att-metric-value {
    background: #f59e0b;
    color: #ffffff;
}

.metric-ijin {
    background: #fefce8;
    border-color: #fef08a;
    color: #854d0e;
}

.metric-ijin .att-metric-value {
    background: #eab308;
    color: #ffffff;
}

.metric-tugas {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}

.metric-tugas .att-metric-value {
    background: #16a34a;
    color: #ffffff;
}

.metric-alpha {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #9f1239;
}

.metric-alpha .att-metric-value {
    background: #f43f5e;
    color: #ffffff;
}

/* Info Card di Tab 3 (Pertemuan Kelas) */
.info-card-pertemuan {
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 18px 24px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.info-card-row {
    display: flex;
    align-items: flex-start;
    padding: 6px 0;
    font-size: 14px;
}

.info-card-row .info-label {
    width: 170px;
    font-weight: 700;
    color: #1e293b;
    flex-shrink: 0;
}

.info-card-row .info-sep {
    width: 20px;
    color: #64748b;
    flex-shrink: 0;
}

.info-card-row .info-value {
    color: #334155;
    font-weight: 500;
    flex-grow: 1;
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Admin / Dosen Student Selector -->
                    <?php if ($is_admin || $is_dosen): ?>
                    <div class="admin-student-switcher">
                        <div class="info-badge">
                            <i class="feather icon-user-check text-primary" style="font-size: 1.25rem;"></i>
                            <span>Pratinjau Data Kehadiran Mahasiswa:</span>
                            <span class="badge badge-light-primary px-3 py-1 text-primary font-weight-bold">
                                <?= htmlspecialchars($student->nama_lengkap ?? 'Mahasiswa') ?> (<?= htmlspecialchars($student->nim ?? '-') ?>)
                            </span>
                        </div>
                        <form method="GET" action="<?= base_url('kehadiran') ?>" class="form-inline m-0">
                            <?php if ($selected_period): ?>
                                <input type="hidden" name="periode" value="<?= htmlspecialchars($selected_period['key'] ?? '') ?>">
                            <?php endif; ?>
                            <label class="mr-2 text-muted font-weight-bold small">Pilih Mahasiswa:</label>
                            <select name="student_id" class="form-control form-control-sm" onchange="this.form.submit()" style="border-radius: 6px; font-weight: 500;">
                                <?php foreach ($all_students as $st): ?>
                                    <option value="<?= $st->id ?>" <?= ($selected_student_id == $st->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($st->nim) ?> - <?= htmlspecialchars($st->nama_lengkap) ?> (<?= htmlspecialchars($st->prodi ?? '') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- Main Kehadiran Card (Persis Screenshot) -->
                    <div class="kehadiran-card">
                        
                        <!-- Header Biru Cerah -->
                        <div class="kehadiran-header">
                            <div class="header-title-box">
                                <span class="header-bell-icon">
                                    <i class="fa fa-bell"></i>
                                </span>
                                <h4>Kehadiran Kuliah</h4>
                            </div>
                            <div class="header-controls">
                                <a href="javascript:void(0)" title="Ganti Tema/Mode" onclick="toggleThemeHint()"><i class="feather icon-droplet"></i></a>
                                <a href="javascript:void(0)" title="Minimize" onclick="toggleCardBody()"><i class="feather icon-minus"></i></a>
                                <a href="javascript:void(0)" title="Fullscreen" onclick="toggleFullscreen()"><i class="feather icon-maximize-2"></i></a>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="kehadiran-body" id="kehadiranCardBody">

                            <!-- Navigation Tabs -->
                            <div class="kehadiran-nav-wrapper">
                                <ul class="nav kehadiran-tabs" role="tablist">
                                    <!-- Tab 1: Rekapitulasi Kehadiran -->
                                    <li class="nav-item tab-item active" role="presentation">
                                        <a class="nav-link tab-link active" id="tab-rekap-link" data-toggle="tab" href="#tab-rekap" role="tab" aria-controls="tab-rekap" aria-selected="true" onclick="switchKehadiranTab('tab-rekap', event)">
                                            <span class="kehadiran-tab-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 2v20h20"></path>
                                                    <line x1="2" y1="6" x2="6" y2="6"></line>
                                                    <line x1="2" y1="11" x2="8" y2="11"></line>
                                                    <line x1="2" y1="16" x2="6" y2="16"></line>
                                                </svg>
                                            </span>
                                            <span class="tab-label">Rekapitulasi Kehadiran</span>
                                        </a>
                                    </li>

                                    <!-- Tab 2: Kehadiran Per-Pertemuan (Foto 2) -->
                                    <li class="nav-item tab-item" role="presentation">
                                        <a class="nav-link tab-link" id="tab-pertemuan-link" data-toggle="tab" href="#tab-pertemuan" role="tab" aria-controls="tab-pertemuan" aria-selected="false" onclick="switchKehadiranTab('tab-pertemuan', event)">
                                            <span class="kehadiran-tab-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    <path d="M9 16l2 2 4-4"></path>
                                                </svg>
                                            </span>
                                            <span class="tab-label">Kehadiran Per-Pertemuan</span>
                                        </a>
                                    </li>

                                    <!-- Tab 3: Pertemuan Kelas (Foto 3) -->
                                    <li class="nav-item tab-item" role="presentation">
                                        <a class="nav-link tab-link" id="tab-kelas-link" data-toggle="tab" href="#tab-kelas" role="tab" aria-controls="tab-kelas" aria-selected="false" onclick="switchKehadiranTab('tab-kelas', event)">
                                            <span class="kehadiran-tab-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="10" y1="6" x2="21" y2="6"></line>
                                                    <line x1="10" y1="12" x2="21" y2="12"></line>
                                                    <line x1="10" y1="18" x2="21" y2="18"></line>
                                                    <path d="M4 6h1v4"></path>
                                                    <path d="M4 10h2"></path>
                                                </svg>
                                            </span>
                                            <span class="tab-label">Pertemuan Kelas</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Dropdown Filter Periode (Foto 1: 2026 Ganjil, 2024 Ganjil, 2023 Genap, ...) -->
                                <div class="kehadiran-filter-box m-0">
                                    <form method="GET" action="<?= base_url('kehadiran') ?>" id="formPeriodeFilter" class="m-0">
                                        <?php if ($is_admin || $is_dosen): ?>
                                            <input type="hidden" name="student_id" value="<?= (int)$selected_student_id ?>">
                                        <?php endif; ?>
                                        <input type="hidden" name="active_tab" id="hiddenActiveTab" value="tab-rekap">
                                        <select name="periode" class="select-periode-siakad" onchange="submitPeriodeForm()">
                                            <?php foreach ($available_periods as $p): ?>
                                                <option value="<?= htmlspecialchars($p['key']) ?>" <?= ($selected_period && $selected_period['key'] === $p['key']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($p['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <!-- Tab Content -->
                            <div class="tab-content" id="kehadiranTabContent">
                                
                                <!-- ======================================================== -->
                                <!-- TAB 1: REKAPITULASI KEHADIRAN (SESUAI FOTO 1)            -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade show active" id="tab-rekap" role="tabpanel" aria-labelledby="tab-rekap-link">
                                    
                                    <div class="table-kehadiran-box">
                                        <div class="table-kehadiran-responsive">
                                            <table class="table-kehadiran">
                                                <thead>
                                                    <tr>
                                                        <th class="col-number">No</th>
                                                        <th style="min-width: 110px;">Kode</th>
                                                        <th style="min-width: 250px;">Mata Kuliah</th>
                                                        <th class="col-center" style="min-width: 65px;">Kelas</th>
                                                        <th class="col-center" style="min-width: 105px;">Terselenggara</th>
                                                        <th class="col-center" style="min-width: 65px;">Hadir</th>
                                                        <th class="col-center" style="min-width: 65px;">Sakit</th>
                                                        <th class="col-center" style="min-width: 65px;">Ijin</th>
                                                        <th class="col-center" style="min-width: 65px;">Tugas</th>
                                                        <th class="col-center" style="min-width: 85px;">Tidak Hadir</th>
                                                        <th class="col-center" style="min-width: 130px;">Persentase Hadir</th>
                                                        <th class="col-center" style="min-width: 65px;">Detil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($courses)): ?>
                                                        <tr>
                                                            <td colspan="12" class="text-center py-5">
                                                                <div class="py-3">
                                                                    <i class="feather icon-info text-info mb-2" style="font-size: 28px; display: inline-block;"></i>
                                                                    <h6 class="font-weight-bold text-dark mb-1">Belum Ada Rekapan Kehadiran</h6>
                                                                    <p class="text-muted small mb-0" style="max-width: 520px; margin: 0 auto; line-height: 1.6;">
                                                                        <?php if (!empty($selected_period['is_active'])): ?>
                                                                            <?php if (empty($kehadiran_data['krs'])): ?>
                                                                                Mahasiswa belum mengajukan Rencana Studi (KRS) untuk semester ini. Mata kuliah kehadiran akan tersedia setelah KRS disetujui.
                                                                            <?php elseif (($kehadiran_data['krs_status'] ?? '') !== 'DISETUJUI'): ?>
                                                                                Status KRS mahasiswa saat ini masih <strong><?= htmlspecialchars($kehadiran_data['krs_status'] ?? 'DRAFT') ?></strong>. Jadwal dan kehadiran aktif setelah KRS disetujui oleh Dosen Wali.
                                                                            <?php else: ?>
                                                                                KRS telah disetujui, tetapi belum ada catatan kehadiran dari dosen.
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            Tidak ada rekaman perkuliahan untuk periode semester ini.
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($courses as $idx => $c): ?>
                                                            <tr>
                                                                <td class="col-number"><?= $c->no ?></td>
                                                                <td class="mk-kode"><?= htmlspecialchars($c->kode_mk) ?></td>
                                                                <td class="mk-nama"><?= htmlspecialchars($c->nama_mk) ?></td>
                                                                <td class="mk-kelas"><?= htmlspecialchars($c->kelas) ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->terselenggara ?? '-' ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->hadir ?? '-' ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->sakit ?? '-' ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->ijin ?? '-' ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->tugas ?? '-' ?></td>
                                                                <td class="col-center font-weight-500"><?= $c->tidak_hadir ?? '-' ?></td>
                                                                <td class="mk-persen"><?= htmlspecialchars($c->persentase_hadir ?: '-') ?></td>
                                                                <td class="col-center">
                                                                    <a href="javascript:void(0)" class="btn-detil-icon" onclick="openDetailModal(<?= $idx ?>)" title="Lihat Rincian Pertemuan & Absensi">
                                                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0288d1" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                                            <line x1="10" y1="6" x2="21" y2="6"></line>
                                                                            <line x1="10" y1="12" x2="21" y2="12"></line>
                                                                            <line x1="10" y1="18" x2="21" y2="18"></line>
                                                                            <text x="0" y="8" font-size="7.5" font-family="Arial, sans-serif" font-weight="bold" fill="#0288d1" stroke="none">1</text>
                                                                            <line x1="0" y1="10.5" x2="7" y2="10.5" stroke-width="1.3"></line>
                                                                            <text x="0" y="18.5" font-size="7.5" font-family="Arial, sans-serif" font-weight="bold" fill="#0288d1" stroke="none">2</text>
                                                                        </svg>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- ======================================================== -->
                                <!-- TAB 2: KEHADIRAN PER-PERTEMUAN (PERSIS SESUAI FOTO 2)    -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade" id="tab-pertemuan" role="tabpanel" aria-labelledby="tab-pertemuan-link">
                                    
                                    <div class="table-kehadiran-box">
                                        <div class="table-kehadiran-responsive">
                                            <table class="table-kehadiran">
                                                <thead>
                                                    <tr>
                                                        <th class="col-number">No</th>
                                                        <th style="min-width: 100px;">Kode</th>
                                                        <th style="min-width: 230px;">Mata Kuliah</th>
                                                        <th class="col-center" style="width: 55px;">Kelas</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-1</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-2</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-3</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-4</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-5</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-6</th>
                                                        <th class="col-center" style="min-width: 100px;">Ke-6<br>Kelas Pengganti</th>
                                                        <th class="col-center" style="min-width: 100px;">Ke-7<br>Kelas Pengganti</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-7</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-8</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-9</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-10</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-11</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-12</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-13</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-14</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-15</th>
                                                        <th class="col-center" style="min-width: 65px;">Ke-16</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($courses)): ?>
                                                        <tr>
                                                            <td colspan="22" class="text-center py-5">
                                                                <div class="py-3">
                                                                    <i class="feather icon-calendar text-muted mb-2" style="font-size: 28px; display: inline-block;"></i>
                                                                    <h6 class="font-weight-bold text-dark mb-1">Belum Ada Riwayat Pertemuan</h6>
                                                                    <p class="text-muted small mb-0">
                                                                        <?php if (!empty($selected_period['is_active'])): ?>
                                                                            Rekap presensi tampil setelah dosen mencatat pertemuan pada mata kuliah yang disetujui.
                                                                        <?php else: ?>
                                                                            Tidak ada rekaman pertemuan pada periode ini.
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($courses as $c): ?>
                                                            <tr>
                                                                <td class="col-number"><?= $c->no ?></td>
                                                                <td class="mk-kode"><?= htmlspecialchars($c->kode_mk) ?></td>
                                                                <td class="mk-nama"><?= htmlspecialchars($c->nama_mk) ?></td>
                                                                <td class="mk-kelas"><?= htmlspecialchars($c->kelas) ?></td>



                                                                <!-- Ke-1 s/d Ke-6 -->
                                                                <?php for ($p = 1; $p <= 6; $p++): 
                                                                    $st = $c->meetings[$p - 1]['status'] ?? '';
                                                                ?>
                                                                    <td class="col-center"><?= render_badge_kehadiran($st) ?></td>
                                                                <?php endfor; ?>

                                                                <!-- Ke-6 Kelas Pengganti (Sesuai Foto 2) -->
                                                                <td class="col-center">
                                                                    <?= !empty($c->p6_pengganti) ? render_badge_kehadiran($c->p6_pengganti) : '' ?>
                                                                </td>

                                                                <!-- Ke-7 Kelas Pengganti (Sesuai Foto 2) -->
                                                                <td class="col-center">
                                                                    <?= !empty($c->p7_pengganti) ? render_badge_kehadiran($c->p7_pengganti) : '' ?>
                                                                </td>

                                                                <!-- Ke-7 s/d Ke-16 -->
                                                                <?php for ($p = 7; $p <= 16; $p++): 
                                                                    $st = $c->meetings[$p - 1]['status'] ?? '';
                                                                ?>
                                                                    <td class="col-center"><?= render_badge_kehadiran($st) ?></td>
                                                                <?php endfor; ?>

                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- ======================================================== -->
                                <!-- TAB 3: PERTEMUAN KELAS (PERSIS SESUAI FOTO 3)            -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade" id="tab-kelas" role="tabpanel" aria-labelledby="tab-kelas-link">
                                    
                                    <?php if (empty($courses)): ?>
                                        <div class="text-center py-5 rounded border bg-light">
                                            <i class="feather icon-book-open text-muted mb-2" style="font-size: 32px; display: inline-block;"></i>
                                            <h6 class="font-weight-bold text-dark mb-1">Pertemuan Kelas Belum Tersedia</h6>
                                            <p class="text-muted small mb-0">
                                                Detail mata kuliah tampil setelah KRS disetujui; rekap pertemuan muncul setelah dosen menginput presensi.
                                            </p>
                                        </div>
                                    <?php else: ?>

                                        <!-- Dropdown Pemilihan Mata Kuliah di Bagian Atas persis Gambar 3 -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <div style="min-width: 320px; max-width: 520px; width: 100%;">
                                                <select id="selectMatkulTab3" class="form-control" onchange="changeMatkulTab3(this.value)" style="border-radius: 5px; border: 1.5px solid #cbd5e1; font-weight: 500; height: 38px;">
                                                    <?php foreach ($courses as $cIdx => $c): ?>
                                                        <option value="<?= $cIdx ?>" <?= ($cIdx === 0) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($c->kode_mk) ?> | <?= htmlspecialchars($c->nama_mk) ?> - <?= htmlspecialchars($c->kelas) ?> (<?= $c->sks ?> SKS)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <?php $firstCourse = !empty($courses) ? $courses[0] : null; ?>

                                        <!-- Info Card Mata Kuliah persis format Gambar 3 -->
                                        <div class="info-card-pertemuan">
                                            <div class="info-card-row">
                                                <div class="info-label">Program Studi</div>
                                                <div class="info-sep">:</div>
                                                <div class="info-value" id="tab3Prodi">
                                                    <?= htmlspecialchars($student->prodi ?? '-') ?>
                                                </div>
                                            </div>
                                            <div class="info-card-row">
                                                <div class="info-label">Periode</div>
                                                <div class="info-sep">:</div>
                                                <div class="info-value" id="tab3Periode">
                                                    <?= htmlspecialchars($selected_period['label'] ?? '-') ?>
                                                </div>
                                            </div>
                                            <div class="info-card-row">
                                                <div class="info-label">Mata Kuliah</div>
                                                <div class="info-sep">:</div>
                                                <div class="info-value font-weight-bold" id="tab3MataKuliah">
                                                    <?= $firstCourse ? (htmlspecialchars($firstCourse->kode_mk . ' ' . $firstCourse->nama_mk . ' (' . $firstCourse->sks . ' sks)')) : '-' ?>
                                                </div>
                                            </div>
                                            <div class="info-card-row">
                                                <div class="info-label">Pengajar</div>
                                                <div class="info-sep">:</div>
                                                <div class="info-value" id="tab3Pengajar">
                                                    <?= $firstCourse ? htmlspecialchars($firstCourse->dosen) : '-' ?>
                                                </div>
                                            </div>
                                            <div class="info-card-row">
                                                <div class="info-label">Pertemuan / Jadwal</div>
                                                <div class="info-sep">:</div>
                                                <div class="info-value" id="tab3PertemuanJadwal">
                                                    <?= $firstCourse ? htmlspecialchars($firstCourse->jadwal) : '-' ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tabel Detail Pertemuan persis struktur Gambar 3 -->
                                        <div class="table-kehadiran-box">
                                            <div class="table-kehadiran-responsive">
                                                <table class="table-kehadiran" id="tab3MeetingsTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-number">No</th>
                                                            <th class="col-center" style="width: 60px;">Ke-</th>
                                                            <th style="min-width: 140px;">Tanggal</th>
                                                            <th style="min-width: 130px;">Jenis Pertemuan</th>
                                                            <th class="col-center" style="min-width: 110px;">Kehadiran</th>
                                                            <th style="min-width: 250px;">Pengajar</th>
                                                            <th style="min-width: 280px;">Materi Bahasan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tab3MeetingsTbody">
                                                        <?php if ($firstCourse && !empty($firstCourse->meetings)): ?>
                                                            <?php foreach ($firstCourse->meetings as $m): ?>
                                                                <tr>
                                                                    <td class="col-number"><?= $m['no'] ?></td>
                                                                    <td class="col-center font-weight-bold"><?= $m['pertemuan_ke'] ?></td>
                                                                    <td><?= htmlspecialchars($m['tanggal']) ?></td>
                                                                    <td><?= htmlspecialchars($m['jenis_pertemuan']) ?></td>
                                                                    <td class="col-center">
                                                                        <?php if ($m['status'] === 'HADIR'): ?>
                                                                            <span class="badge-hadir">Hadir</span>
                                                                        <?php elseif ($m['status'] === 'SAKIT'): ?>
                                                                            <span class="badge-sakit">Sakit</span>
                                                                        <?php elseif ($m['status'] === 'IJIN'): ?>
                                                                            <span class="badge-ijin">Ijin</span>
                                                                        <?php elseif ($m['status'] === 'ALPHA'): ?>
                                                                            <span class="badge-alpha">Alpa</span>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">-</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td style="white-space: pre-line;"><?= htmlspecialchars($m['pengajar']) ?></td>
                                                                    <td><?= htmlspecialchars($m['materi']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center py-4 text-muted">
                                                                    <i class="feather icon-info mr-1"></i> Data perkuliahan belum tersedia.
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL DETIL KEHADIRAN (POPUP SAAT KLIK IKON DETIL TAB 1) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalDetilKehadiran" tabindex="-1" role="dialog" aria-labelledby="modalDetilKehadiranLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 900px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden;">
            
            <div class="modal-detil-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title m-0" id="modalTitle">
                        <i class="feather icon-list mr-1"></i> Rincian Presensi & Kehadiran Kuliah
                    </h5>
                    <small id="modalSubtitle" class="text-white-50 mt-1 d-block font-weight-500">Mata Kuliah</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3 p-md-4 bg-white">
                
                <!-- Info Matkul & Statistik Bar Card -->
                <div class="modal-summary-card">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between" style="gap: 16px;">
                        <div>
                            <div class="course-chips-wrap">
                                <span class="course-chip chip-kode" id="modalBadgeKode">-</span>
                                <span class="course-chip chip-kelas" id="modalBadgeKelas">Kelas -</span>
                                <span class="course-chip chip-sks" id="modalBadgeSks">- SKS</span>
                            </div>
                            <h5 class="modal-course-title" id="modalNamaMK">-</h5>
                            <div class="modal-meta-grid">
                                <div class="modal-meta-item">
                                    <i class="feather icon-user text-primary" style="font-size: 14px;"></i>
                                    <span>Dosen Pengampu:</span>
                                    <strong id="modalNamaDosen" class="text-dark">-</strong>
                                </div>
                                <div class="modal-meta-item">
                                    <i class="feather icon-calendar text-success" style="font-size: 14px;"></i>
                                    <span>Jadwal Perkuliahan:</span>
                                    <strong id="modalJadwal" class="text-dark">-</strong>
                                </div>
                            </div>
                        </div>
                        <div class="text-md-right mt-2 mt-md-0">
                            <div class="modal-percentage-box">
                                <span class="percentage-label">Persentase Hadir</span>
                                <div class="percentage-value" id="modalPersentaseHadir">-</div>
                                <div class="percentage-bar-wrap">
                                    <div class="percentage-bar-fill" id="modalPercentageBar" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Metrics Strip (Rapi, Seimbang & Proporsional) -->
                <div class="attendance-metrics-strip">
                    <div class="att-metric-pill metric-terselenggara" title="Total pertemuan yang telah diselenggarakan">
                        <span class="att-metric-label">Terselenggara</span>
                        <span class="att-metric-value" id="modalStatTerselenggara">0</span>
                    </div>
                    <div class="att-metric-pill metric-hadir" title="Total kehadiran mahasiswa">
                        <span class="att-metric-label"><i class="feather icon-check-circle mr-1"></i>Hadir</span>
                        <span class="att-metric-value" id="modalStatHadir">0</span>
                    </div>
                    <div class="att-metric-pill metric-sakit" title="Ijin karena sakit">
                        <span class="att-metric-label"><i class="feather icon-activity mr-1"></i>Sakit</span>
                        <span class="att-metric-value" id="modalStatSakit">0</span>
                    </div>
                    <div class="att-metric-pill metric-ijin" title="Ijin ada keperluan resmi">
                        <span class="att-metric-label"><i class="feather icon-file-text mr-1"></i>Ijin</span>
                        <span class="att-metric-value" id="modalStatIjin">0</span>
                    </div>
                    <div class="att-metric-pill metric-tugas" title="Penugasan akademik luar kelas">
                        <span class="att-metric-label"><i class="feather icon-briefcase mr-1"></i>Tugas</span>
                        <span class="att-metric-value" id="modalStatTugas">0</span>
                    </div>
                    <div class="att-metric-pill metric-alpha" title="Tidak hadir tanpa keterangan">
                        <span class="att-metric-label"><i class="feather icon-x-circle mr-1"></i>Tidak Hadir</span>
                        <span class="att-metric-value" id="modalStatAlpha">0</span>
                    </div>
                </div>

                <!-- Tabel Detail Pertemuan 1 s/d 16 -->
                <div class="table-responsive rounded border" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0" style="font-size: 13px;">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">Ptm</th>
                                <th style="width: 130px;">Tanggal</th>
                                <th style="width: 120px;">Jenis</th>
                                <th>Topik / Pokok Bahasan</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th>Pengajar</th>
                            </tr>
                        </thead>
                        <tbody id="modalMeetingsTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                <small class="text-muted"><i class="feather icon-info text-info mr-1"></i> Rekap kehadiran akan tampil setelah dosen mencatat pertemuan kuliah.</small>
                <div>
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btn-sm px-3 ml-2" onclick="window.print()">
                        <i class="feather icon-printer mr-1"></i> Cetak Detil
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Logika Interaktif JS -->
<script>
var COURSES_DATA = <?= json_encode($courses, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
var STUDENT_PRODI = "<?= htmlspecialchars($student->prodi ?? 'Sistem Informasi') ?>";
var SELECTED_PERIODE_LBL = "<?= htmlspecialchars($selected_period['label'] ?? '') ?>";

// Menangani perubahan dropdown mata kuliah di Tab 3 (Pertemuan Kelas)
function changeMatkulTab3(courseIdx) {
    if (!COURSES_DATA || !COURSES_DATA[courseIdx]) return;
    var c = COURSES_DATA[courseIdx];

    document.getElementById('tab3Prodi').innerText = STUDENT_PRODI;
    document.getElementById('tab3Periode').innerText = SELECTED_PERIODE_LBL;
    document.getElementById('tab3MataKuliah').innerText = c.kode_mk + ' ' + c.nama_mk + ' (' + c.sks + ' sks)';
    document.getElementById('tab3Pengajar').innerText = c.dosen || '-';
    document.getElementById('tab3PertemuanJadwal').innerText = c.jadwal || '-';

    var tbody = document.getElementById('tab3MeetingsTbody');
    tbody.innerHTML = '';

    if (c.meetings && c.meetings.length > 0) {
        c.meetings.forEach(function(m) {
            var tr = document.createElement('tr');
            var badgeHtml = '';
            if (m.status === 'HADIR') {
                badgeHtml = '<span class="badge-hadir">Hadir</span>';
            } else if (m.status === 'SAKIT') {
                badgeHtml = '<span class="badge-sakit">Sakit</span>';
            } else if (m.status === 'IJIN') {
                badgeHtml = '<span class="badge-ijin">Ijin</span>';
            } else if (m.status === 'ALPHA') {
                badgeHtml = '<span class="badge-alpha">Alpa</span>';
            } else {
                badgeHtml = '<span class="text-muted small">-</span>';
            }

            tr.innerHTML = '<td class="col-number">' + m.no + '</td>' +
                           '<td class="col-center font-weight-bold">' + m.pertemuan_ke + '</td>' +
                           '<td>' + escapeHtml(m.tanggal) + '</td>' +
                           '<td>' + escapeHtml(m.jenis_pertemuan) + '</td>' +
                           '<td class="col-center">' + badgeHtml + '</td>' +
                           '<td style="white-space: pre-line;">' + escapeHtml(m.pengajar) + '</td>' +
                           '<td>' + escapeHtml(m.materi) + '</td>';
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pertemuan tercatat.</td></tr>';
    }
}

// Buka Modal Detil di Tab 1
function openDetailModal(courseIdx) {
    if (!COURSES_DATA || !COURSES_DATA[courseIdx]) return;
    var c = COURSES_DATA[courseIdx];

    document.getElementById('modalTitle').innerHTML = '<i class="feather icon-list mr-1"></i> ' + escapeHtml(c.nama_mk);
    document.getElementById('modalSubtitle').innerText = c.kode_mk + ' - Kelas ' + c.kelas + ' (' + c.sks + ' SKS)';
    if (document.getElementById('modalNamaMK')) {
        document.getElementById('modalNamaMK').innerText = c.nama_mk || '-';
    }
    document.getElementById('modalBadgeKode').innerText = c.kode_mk;
    document.getElementById('modalBadgeKelas').innerText = 'Kelas ' + c.kelas;
    document.getElementById('modalBadgeSks').innerText = c.sks + ' SKS';
    document.getElementById('modalNamaDosen').innerText = c.dosen || '-';
    document.getElementById('modalJadwal').innerText = c.jadwal || '-';
    
    var persenText = c.persentase_hadir || '-';
    document.getElementById('modalPersentaseHadir').innerText = persenText;
    var numVal = parseFloat(persenText) || 0;
    var pBar = document.getElementById('modalPercentageBar');
    if (pBar) {
        pBar.style.width = Math.min(100, Math.max(0, numVal)) + '%';
        if (numVal >= 80) {
            pBar.style.background = 'linear-gradient(90deg, #10b981 0%, #059669 100%)';
            document.getElementById('modalPersentaseHadir').style.color = '#059669';
        } else if (numVal >= 60) {
            pBar.style.background = 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)';
            document.getElementById('modalPersentaseHadir').style.color = '#d97706';
        } else {
            pBar.style.background = 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)';
            document.getElementById('modalPersentaseHadir').style.color = '#dc2626';
        }
    }

    document.getElementById('modalStatTerselenggara').innerText = c.terselenggara ?? '-';
    document.getElementById('modalStatHadir').innerText = c.hadir ?? '-';
    document.getElementById('modalStatSakit').innerText = c.sakit ?? '-';
    document.getElementById('modalStatIjin').innerText = c.ijin ?? '-';
    document.getElementById('modalStatTugas').innerText = c.tugas ?? '-';
    document.getElementById('modalStatAlpha').innerText = c.tidak_hadir ?? '-';

    var tbody = document.getElementById('modalMeetingsTableBody');
    tbody.innerHTML = '';

    if (c.meetings && c.meetings.length > 0) {
        c.meetings.forEach(function(m) {
            var tr = document.createElement('tr');
            var badgeHtml = '';
            if (m.status === 'HADIR') {
                badgeHtml = '<span class="badge-hadir">Hadir</span>';
            } else if (m.status === 'SAKIT') {
                badgeHtml = '<span class="badge-sakit">Sakit</span>';
            } else if (m.status === 'IJIN') {
                badgeHtml = '<span class="badge-ijin">Ijin</span>';
            } else if (m.status === 'ALPHA') {
                badgeHtml = '<span class="badge-alpha">Alpa</span>';
            } else {
                badgeHtml = '<span class="badge badge-secondary px-2 py-1">BELUM</span>';
            }

            tr.innerHTML = '<td class="text-center font-weight-bold text-muted">P' + m.pertemuan_ke + '</td>' +
                           '<td class="text-dark font-weight-500">' + escapeHtml(m.tanggal) + '</td>' +
                           '<td>' + escapeHtml(m.jenis_pertemuan) + '</td>' +
                           '<td><b>' + escapeHtml(m.materi) + '</b></td>' +
                           '<td class="text-center">' + badgeHtml + '</td>' +
                           '<td class="text-muted small" style="white-space: pre-line;">' + escapeHtml(m.pengajar) + '</td>';
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted">Belum ada pertemuan yang dicatat dosen.</td></tr>';
    }

    if (window.jQuery) {
        $('#modalDetilKehadiran').modal('show');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function toggleCardBody() {
    var b = document.getElementById('kehadiranCardBody');
    if (b) {
        b.style.display = (b.style.display === 'none') ? 'block' : 'none';
    }
}

function toggleFullscreen() {
    var elem = document.querySelector('.kehadiran-card');
    if (!document.fullscreenElement) {
        if (elem.requestFullscreen) { elem.requestFullscreen(); }
        else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); }
        else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
    } else {
        if (document.exitFullscreen) { document.exitFullscreen(); }
    }
}

function toggleThemeHint() {
    var h = document.querySelector('.kehadiran-header');
    if (h) {
        if (h.dataset.themeAlt === '1') {
            h.style.background = 'linear-gradient(135deg, #3bbcf6 0%, #0093e9 100%)';
            h.dataset.themeAlt = '0';
        } else {
            h.style.background = 'linear-gradient(135deg, #0288d1 0%, #01579b 100%)';
            h.dataset.themeAlt = '1';
        }
    }
}

// Beralih tab kehadiran secara instan & kompatibel dengan seluruh event / Bootstrap
function switchKehadiranTab(tabId, event) {
    if (event) {
        event.preventDefault();
    }

    // 1. Matikan seluruh tab navigation
    document.querySelectorAll('.kehadiran-tabs .tab-link, .kehadiran-tabs .nav-link').forEach(function(el) {
        el.classList.remove('active');
        el.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.kehadiran-tabs .tab-item, .kehadiran-tabs .nav-item').forEach(function(el) {
        el.classList.remove('active');
    });

    // 2. Aktifkan tab link yang dipilih
    var targetLink = document.querySelector('.kehadiran-tabs a[href="#' + tabId + '"]');
    if (targetLink) {
        targetLink.classList.add('active');
        targetLink.setAttribute('aria-selected', 'true');
        if (targetLink.parentElement) {
            targetLink.parentElement.classList.add('active');
        }
    }

    // 3. Tampilkan tab-pane yang dituju dan sembunyikan yang lain
    document.querySelectorAll('#kehadiranTabContent .tab-pane').forEach(function(pane) {
        pane.classList.remove('show', 'active');
        pane.style.display = 'none';
    });

    var targetPane = document.getElementById(tabId);
    if (targetPane) {
        targetPane.classList.add('show', 'active');
        targetPane.style.display = 'block';
    }

    // 4. Sinkronkan hidden input agar filter dropdown mengingat tab aktif
    var hiddenTab = document.getElementById('hiddenActiveTab');
    if (hiddenTab) {
        hiddenTab.value = tabId;
    }

    // 5. Trigger bootstrap tab jika tersedia
    if (window.jQuery && targetLink) {
        try {
            $(targetLink).tab('show');
        } catch(e) {}
    }
}

// Menjaga tab aktif saat submit form filter periode
function submitPeriodeForm() {
    var activeTabLink = document.querySelector('.kehadiran-tabs .tab-link.active, .kehadiran-tabs .nav-link.active');
    if (activeTabLink) {
        var href = activeTabLink.getAttribute('href');
        if (href) {
            document.getElementById('hiddenActiveTab').value = href.replace('#', '');
        }
    }
    document.getElementById('formPeriodeFilter').submit();
}

// Inisialisasi tab saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var activeTabParam = urlParams.get('active_tab') || 'tab-rekap';
    switchKehadiranTab(activeTabParam);

    // Event listener tambahan pada semua link tab untuk jaminan 100% responsif
    document.querySelectorAll('.kehadiran-tabs a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                switchKehadiranTab(href.substring(1), e);
            }
        });
    });
});
</script>
