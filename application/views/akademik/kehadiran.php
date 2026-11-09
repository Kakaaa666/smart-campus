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

// Hitung metrik statistik agregat untuk Quick KPI Strip
$total_mk = count($courses);
$total_terselenggara = 0;
$total_hadir = 0;
$total_sakit = 0;
$total_ijin = 0;
$total_tugas = 0;
$total_tidak_hadir = 0;

foreach ($courses as $c) {
    $total_terselenggara += (int)($c->terselenggara ?? 0);
    $total_hadir += (int)($c->hadir ?? 0);
    $total_sakit += (int)($c->sakit ?? 0);
    $total_ijin += (int)($c->ijin ?? 0);
    $total_tugas += (int)($c->tugas ?? 0);
    $total_tidak_hadir += (int)($c->tidak_hadir ?? 0);
}

$valid_presensi = $total_hadir + $total_sakit + $total_ijin + $total_tugas;
$overall_percentage = ($total_terselenggara > 0) ? round(($valid_presensi / $total_terselenggara) * 100, 1) : 100.0;
$is_syarat_uas_aman = ($overall_percentage >= 75.0);

if (!function_exists('render_badge_kehadiran')) {
    function render_badge_kehadiran($status) {
        $st = strtoupper(trim((string)$status));
        if ($st === 'HADIR') {
            return '<span class="att-micro-badge badge-hadir" title="Hadir"><i class="fa fa-check mr-1"></i>Hadir</span>';
        } elseif ($st === 'SAKIT') {
            return '<span class="att-micro-badge badge-sakit" title="Sakit"><i class="fa fa-plus-square mr-1"></i>Sakit</span>';
        } elseif ($st === 'IJIN') {
            return '<span class="att-micro-badge badge-ijin" title="Ijin"><i class="fa fa-envelope-o mr-1"></i>Ijin</span>';
        } elseif ($st === 'ALPHA' || $st === 'TIDAK HADIR' || $st === 'ALPA') {
            return '<span class="att-micro-badge badge-alpha" title="Alpa / Tidak Hadir"><i class="fa fa-times mr-1"></i>Alpa</span>';
        } elseif ($st === 'TUGAS') {
            return '<span class="att-micro-badge badge-tugas" title="Penugasan"><i class="fa fa-briefcase mr-1"></i>Tugas</span>';
        }
        return '<span class="att-micro-badge badge-belum" title="Belum Terselenggara">-</span>';
    }
}
?>

<style>
/* ======================================================== */
/* SMART CAMPUS KEHADIRAN KULIAH - MODERN HARMONIZED DESIGN  */
/* ======================================================== */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.pcoded-inner-content,
.main-body,
.page-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}

/* Base Card Styling */
.kehadiran-card {
    border-radius: 16px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    overflow: hidden;
    margin-bottom: 25px;
    transition: all 0.25s ease;
}

/* Header Biru Khas Smart Campus yang Modern & Elegan */
.kehadiran-header {
    background: linear-gradient(135deg, #0288d1 0%, #0093e9 100%) !important;
    padding: 16px 24px !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    position: relative;
}

.kehadiran-header .header-title-box {
    display: flex;
    align-items: center;
    gap: 14px;
}

.kehadiran-header .header-bell-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(4px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}

.kehadiran-header h4 {
    color: #ffffff !important;
    font-size: 17px !important;
    font-weight: 700 !important;
    margin: 0 !important;
    letter-spacing: -0.2px;
}

.kehadiran-header .header-sub {
    color: rgba(255, 255, 255, 0.85);
    font-size: 12px;
    margin-top: 2px;
    display: block;
    font-weight: 500;
}

.kehadiran-header .header-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.kehadiran-header .header-btn-action {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    font-size: 13px;
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.25);
    cursor: pointer;
}

.kehadiran-header .header-btn-action:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

@media (max-width: 640px) {
    .kehadiran-header {
        padding: 14px 16px !important;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .kehadiran-header .header-controls {
        align-self: flex-end;
    }
}

/* Card Body */
.kehadiran-body {
    padding: 22px 24px 28px;
    background: #ffffff;
}

/* Quick KPI Strip / Metrik Ringkasan Kehadiran */
.kehadiran-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

/* Saat layar mengecil / browser di-resize / laptop 13-14" (<= 1280px):
   Ubah ke 2x2 grid agar kartu tetap luas, lega, dan proporsional */
@media (max-width: 1280px) {
    .kehadiran-kpi-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
}

/* Di layar tablet / ponsel (< 640px): 1 kolom per baris */
@media (max-width: 640px) {
    .kehadiran-kpi-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

.kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
    min-width: 0;
    height: 100%;
    box-sizing: border-box;
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
    border-color: #cbd5e1;
}

.kpi-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.kpi-icon-blue { background: #e0f2fe; color: #0288d1; }
.kpi-icon-green { background: #ecfdf5; color: #10b981; }
.kpi-icon-amber { background: #fffbeb; color: #f59e0b; }
.kpi-icon-purple { background: #f3e8ff; color: #9333ea; }

.kpi-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.kpi-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.kpi-value-row {
    display: flex;
    align-items: baseline;
    gap: 6px;
    flex-wrap: wrap;
    margin: 1px 0 2px;
    min-height: 26px;
}

.kpi-value {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
    margin: 0;
    white-space: nowrap;
}

.kpi-subtitle {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Tabs Navigation & Filter Wrapper */
.kehadiran-nav-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 12px;
}

.kehadiran-tabs {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 6px;
    border-bottom: none !important;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

.kehadiran-tabs::-webkit-scrollbar {
    height: 3px;
}

.kehadiran-tabs::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .kehadiran-nav-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
    .kehadiran-filter-box {
        width: 100%;
        margin-top: 4px !important;
    }
    .kehadiran-filter-box form {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .select-periode-modern {
        flex: 1;
        min-width: 0;
    }
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
    padding: 10px 18px !important;
    min-height: 42px !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #64748b !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-bottom: 2px solid #e2e8f0 !important;
    border-top: 3px solid transparent !important;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.2s ease;
    cursor: pointer !important;
    user-select: none;
    white-space: nowrap !important;
    line-height: 1.2 !important;
}

.kehadiran-tabs .tab-link:hover,
.kehadiran-tabs .nav-link:hover {
    color: #0288d1 !important;
    background: #f1f5f9 !important;
    border-top-color: #38bdf8 !important;
}

.kehadiran-tabs .tab-item.active .tab-link,
.kehadiran-tabs .tab-item.active .nav-link,
.kehadiran-tabs .tab-link.active,
.kehadiran-tabs .nav-link.active {
    color: #0288d1 !important;
    font-weight: 700 !important;
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    border-bottom: 2px solid #ffffff !important;
    border-top: 3px solid #0288d1 !important;
    box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.02) !important;
}

.kehadiran-tab-count {
    background: #e0f2fe;
    color: #0288d1;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
}

.kehadiran-tabs .tab-item.active .kehadiran-tab-count {
    background: #0288d1;
    color: #ffffff;
}

/* Filter Periode Dropdown di Kanan */
.kehadiran-filter-box {
    margin: 0 0 6px 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-periode-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
}

.select-periode-modern {
    min-width: 180px;
    height: 40px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    padding: 6px 14px;
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
    background-color: #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}

.select-periode-modern:hover {
    border-color: #0288d1;
}

.select-periode-modern:focus {
    border-color: #0288d1;
    box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
}

/* ======================================================== */
/* TAB 1: REKAPITULASI KEHADIRAN TABLE                      */
/* ======================================================== */
.table-kehadiran-box {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
}

.table-kehadiran-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-kehadiran {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    min-width: 980px;
}

.table-kehadiran thead th {
    background: #f8fafc !important;
    color: #334155 !important;
    font-size: 12.5px !important;
    font-weight: 700 !important;
    padding: 13px 12px !important;
    border-bottom: 2px solid #e2e8f0 !important;
    border-top: none !important;
    white-space: nowrap;
    vertical-align: middle;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.table-kehadiran tbody td {
    padding: 13px 12px !important;
    font-size: 13px !important;
    color: #334155;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-kehadiran tbody tr {
    transition: background-color 0.15s ease;
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
    width: 48px;
    color: #64748b;
    font-weight: 600;
}

/* Kode & Nama Mata Kuliah */
.mk-code-pill {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    font-family: monospace;
    letter-spacing: 0.2px;
    display: inline-block;
}

.mk-title-main {
    font-weight: 700;
    color: #0f172a;
    font-size: 13.5px;
    line-height: 1.3;
}

.mk-meta-sub {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.mk-kelas-badge {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-block;
}

/* Angka Metrik Presensi dalam Tabel */
.cell-stat-val {
    font-weight: 600;
    color: #1e293b;
    font-size: 13.5px;
}

.cell-stat-val.hadir-highlight {
    color: #059669;
    font-weight: 700;
}

.cell-stat-val.warn-highlight {
    color: #d97706;
    font-weight: 700;
}

.cell-stat-val.danger-highlight {
    color: #e11d48;
    font-weight: 700;
}

.cell-stat-zero {
    color: #94a3b8;
    font-weight: 400;
}

/* Bar Persentase Hadir di Tabel */
.persen-cell-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 130px;
}

.persen-bar-mini {
    flex: 1;
    height: 6px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.persen-fill-mini {
    height: 100%;
    border-radius: 4px;
    transition: width 0.4s ease;
}

.persen-fill-green { background: #10b981; }
.persen-fill-amber { background: #f59e0b; }
.persen-fill-red { background: #ef4444; }

.persen-pill-badge {
    font-size: 11.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    white-space: nowrap;
}

.badge-persen-green {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.badge-persen-amber {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

.badge-persen-red {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
}

/* Tombol Detil Modern */
.btn-detil-modern {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #0288d1;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none !important;
}

.btn-detil-modern:hover {
    background: #0288d1;
    color: #ffffff !important;
    border-color: #0288d1;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
    transform: translateY(-1px);
}

/* Baris Footer Total Rekapitulasi */
.table-kehadiran tfoot th {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    padding: 13px 12px !important;
    border-top: 2px solid #cbd5e1 !important;
}

/* ======================================================== */
/* TAB 2: KEHADIRAN PER-PERTEMUAN GRID & BADGES             */
/* ======================================================== */
.table-pertemuan-wrap {
    position: relative;
}

.table-pertemuan th.sticky-col,
.table-pertemuan td.sticky-col {
    position: sticky;
    background: #ffffff;
    z-index: 2;
}

.table-pertemuan th.sticky-col {
    background: #f8fafc !important;
    z-index: 3;
}

.sticky-col-1 { left: 0; min-width: 45px; }
.sticky-col-2 { left: 45px; min-width: 95px; }
.sticky-col-3 { left: 140px; min-width: 200px; box-shadow: 2px 0 5px rgba(0,0,0,0.03); }

.att-micro-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 3px 7px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.2;
}

.badge-hadir {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.badge-sakit {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}

.badge-ijin {
    background: #fefce8;
    color: #ca8a04;
    border: 1px solid #fef08a;
}

.badge-tugas {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.badge-alpha {
    background: #fff1f2;
    color: #e11d48;
    border: 1px solid #fecdd3;
}

.badge-belum {
    background: #f8fafc;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
    min-width: 22px;
}

/* Legenda Status Presensi */
.presensi-legend-bar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 18px;
    margin-top: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.legend-title {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.legend-items-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #334155;
    font-weight: 500;
}

/* ======================================================== */
/* TAB 3: PERTEMUAN KELAS & SILABUS MATERI                  */
/* ======================================================== */
.info-card-pertemuan-modern {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 20px;
}

.tab3-grid-meta {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

@media (max-width: 768px) {
    .tab3-grid-meta {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

.tab3-meta-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.tab3-meta-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #e0f2fe;
    color: #0288d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.tab3-meta-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}

.tab3-meta-value {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}

/* Admin / Dosen Student Selector Bar */
.admin-student-switcher {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 18px;
    margin-bottom: 20px;
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
    font-size: 13.5px;
    font-weight: 600;
}

/* Modal Detil Kehadiran Modern & Rapi */
.modal-detil-header {
    background: linear-gradient(135deg, #0288d1 0%, #0093e9 100%) !important;
    color: #ffffff;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 16px 24px;
}

.modal-detil-header .modal-title {
    color: #ffffff !important;
    font-size: 16px;
    font-weight: 700;
}

.modal-summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 16px;
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
}

/* Stat Percentage Box in Modal */
.modal-percentage-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 18px;
    text-align: center;
    min-width: 145px;
}

.modal-percentage-box .percentage-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
}

.modal-percentage-box .percentage-value {
    font-size: 24px;
    font-weight: 800;
    color: #0288d1;
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
    border-radius: 4px;
    transition: width 0.4s ease;
}

/* Attendance Metrics Strip in Modal */
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

.att-metric-pill .att-metric-value {
    font-size: 13px;
    font-weight: 800;
    margin-left: 6px;
    padding: 1px 7px;
    border-radius: 6px;
    min-width: 22px;
    text-align: center;
}

.metric-terselenggara { background: #f8fafc; border-color: #cbd5e1; color: #334155; }
.metric-terselenggara .att-metric-value { background: #e2e8f0; color: #0f172a; }

.metric-hadir { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.metric-hadir .att-metric-value { background: #10b981; color: #ffffff; }

.metric-sakit { background: #fffbeb; border-color: #fde68a; color: #92400e; }
.metric-sakit .att-metric-value { background: #f59e0b; color: #ffffff; }

.metric-ijin { background: #fefce8; border-color: #fef08a; color: #854d0e; }
.metric-ijin .att-metric-value { background: #eab308; color: #ffffff; }

.metric-tugas { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.metric-tugas .att-metric-value { background: #16a34a; color: #ffffff; }

.metric-alpha { background: #fff1f2; border-color: #fecdd3; color: #9f1239; }
.metric-alpha .att-metric-value { background: #f43f5e; color: #ffffff; }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Admin / Dosen Student Selector Bar -->
                    <?php if ($is_admin || $is_dosen): ?>
                    <div class="admin-student-switcher">
                        <div class="info-badge">
                            <i class="fa fa-user-circle text-primary" style="font-size: 1.25rem;"></i>
                            <span>Pratinjau Data Kehadiran Mahasiswa:</span>
                            <span class="badge badge-light-primary px-3 py-1 text-primary font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                <?= htmlspecialchars($student->nama_lengkap ?? 'Mahasiswa') ?> (<?= htmlspecialchars($student->nim ?? '-') ?>)
                            </span>
                        </div>
                        <form method="GET" action="<?= base_url('kehadiran') ?>" class="form-inline m-0">
                            <?php if ($selected_period): ?>
                                <input type="hidden" name="periode" value="<?= htmlspecialchars($selected_period['key'] ?? '') ?>">
                            <?php endif; ?>
                            <label class="mr-2 text-muted font-weight-bold small">Pilih Mahasiswa:</label>
                            <select name="student_id" class="form-control form-control-sm" onchange="this.form.submit()" style="border-radius: 6px; font-weight: 600;">
                                <?php foreach ($all_students as $st): ?>
                                    <option value="<?= $st->id ?>" <?= ($selected_student_id == $st->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($st->nim) ?> - <?= htmlspecialchars($st->nama_lengkap) ?> (<?= htmlspecialchars($st->prodi ?? '') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- Quick KPI Strip Summary Kehadiran (Harmonized with KHS & Jadwal) -->
                    <div class="kehadiran-kpi-grid">
                        <!-- KPI 1: Rata-rata Kehadiran -->
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-icon-blue">
                                <i class="fa fa-pie-chart"></i>
                            </div>
                            <div class="kpi-content">
                                <div class="kpi-title" title="Rata-rata Kehadiran Mahasiswa">Rata-rata Kehadiran</div>
                                <div class="kpi-value-row">
                                    <span class="kpi-value text-primary"><?= number_format($overall_percentage, 1) ?>%</span>
                                    <span class="badge <?= $is_syarat_uas_aman ? 'badge-success' : 'badge-danger' ?> px-2 py-0.5 font-weight-bold" style="font-size: 10px; border-radius: 4px;">
                                        <?= $is_syarat_uas_aman ? 'Sangat Baik' : 'Waspada' ?>
                                    </span>
                                </div>
                                <div class="kpi-subtitle" title="Tingkat Presensi Perkuliahan">
                                    <i class="fa fa-check-circle-o text-muted mr-1"></i>Presensi Mahasiswa
                                </div>
                            </div>
                        </div>

                        <!-- KPI 2: Total Pertemuan -->
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-icon-green">
                                <i class="fa fa-calendar-check-o"></i>
                            </div>
                            <div class="kpi-content">
                                <div class="kpi-title" title="Total Pertemuan Kuliah">Pertemuan Kuliah</div>
                                <div class="kpi-value-row">
                                    <span class="kpi-value text-success"><?= (int)$total_hadir ?> / <?= (int)$total_terselenggara ?></span>
                                </div>
                                <div class="kpi-subtitle" title="Sesi perkuliahan yang dihadiri">
                                    <i class="fa fa-check-circle text-success mr-1"></i>Sesi Kuliah Dihadiri
                                </div>
                            </div>
                        </div>

                        <!-- KPI 3: Rincian Sakit & Izin -->
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-icon-amber">
                                <i class="fa fa-stethoscope"></i>
                            </div>
                            <div class="kpi-content">
                                <div class="kpi-title" title="Sakit, Ijin & Alpa">Sakit &amp; Ijin / Alpa</div>
                                <div class="kpi-value-row">
                                    <span class="kpi-value text-warning"><?= (int)($total_sakit + $total_ijin) ?></span>
                                    <span class="text-muted font-weight-bold" style="font-size: 12px;">(<?= (int)$total_tidak_hadir ?> Alpa)</span>
                                </div>
                                <div class="kpi-subtitle" title="Rincian: Sakit <?= (int)$total_sakit ?>, Ijin <?= (int)$total_ijin ?>, Alpa <?= (int)$total_tidak_hadir ?>">
                                    <span>S: <?= (int)$total_sakit ?> &bull; I: <?= (int)$total_ijin ?> &bull; A: <?= (int)$total_tidak_hadir ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- KPI 4: Status Kelayakan Ujian -->
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-icon-purple">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div class="kpi-content">
                                <div class="kpi-title" title="Syarat Mengikuti Ujian Akhir Semester">Syarat Ikut UAS</div>
                                <div class="kpi-value-row">
                                    <span class="badge <?= $is_syarat_uas_aman ? 'badge-success' : 'badge-danger' ?> px-2.5 py-1 font-weight-bold" style="font-size: 11px; border-radius: 5px; letter-spacing: 0.2px;">
                                        <i class="fa <?= $is_syarat_uas_aman ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> mr-1"></i><?= $is_syarat_uas_aman ? 'MEMENUHI SYARAT' : 'BELUM MEMENUHI' ?>
                                    </span>
                                </div>
                                <div class="kpi-subtitle" title="Ambang batas minimal kehadiran 75% Dikti">
                                    <i class="fa fa-info-circle text-muted mr-1"></i>Min. Hadir 75% Dikti
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Kehadiran Card -->
                    <div class="kehadiran-card">
                        
                        <!-- Header Biru Elegan -->
                        <div class="kehadiran-header">
                            <div class="header-title-box">
                                <span class="header-bell-icon">
                                    <i class="fa fa-calendar-check-o"></i>
                                </span>
                                <div>
                                    <h4>Kehadiran Kuliah</h4>
                                    <span class="header-sub">
                                        <?= htmlspecialchars($student->nama_lengkap ?? 'Mahasiswa') ?> &bull; NIM: <?= htmlspecialchars($student->nim ?? '-') ?> &bull; <?= htmlspecialchars($selected_period['label'] ?? '2024 Genap') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="header-controls">
                                <button type="button" class="header-btn-action" title="Cetak Ringkasan Kehadiran" onclick="window.print()">
                                    <i class="fa fa-print"></i>
                                </button>
                                <button type="button" class="header-btn-action" title="Buka / Tutup Kartu" onclick="toggleCardBody()">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="header-btn-action" title="Layar Penuh" onclick="toggleFullscreen()">
                                    <i class="fa fa-expand"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="kehadiran-body" id="kehadiranCardBody">

                            <!-- Navigation Tabs & Period Filter -->
                            <div class="kehadiran-nav-wrapper">
                                <ul class="nav kehadiran-tabs" role="tablist">
                                    <!-- Tab 1: Rekapitulasi Kehadiran -->
                                    <li class="nav-item tab-item active" role="presentation">
                                        <a class="nav-link tab-link active" id="tab-rekap-link" data-toggle="tab" href="#tab-rekap" role="tab" aria-controls="tab-rekap" aria-selected="true" onclick="switchKehadiranTab('tab-rekap', event)">
                                            <i class="fa fa-bar-chart mr-1 text-primary"></i>
                                            <span>Rekapitulasi Kehadiran</span>
                                            <span class="kehadiran-tab-count"><?= $total_mk ?></span>
                                        </a>
                                    </li>

                                    <!-- Tab 2: Kehadiran Per-Pertemuan -->
                                    <li class="nav-item tab-item" role="presentation">
                                        <a class="nav-link tab-link" id="tab-pertemuan-link" data-toggle="tab" href="#tab-pertemuan" role="tab" aria-controls="tab-pertemuan" aria-selected="false" onclick="switchKehadiranTab('tab-pertemuan', event)">
                                            <i class="fa fa-calendar-o mr-1 text-primary"></i>
                                            <span>Kehadiran Per-Pertemuan</span>
                                        </a>
                                    </li>

                                    <!-- Tab 3: Pertemuan Kelas & Silabus -->
                                    <li class="nav-item tab-item" role="presentation">
                                        <a class="nav-link tab-link" id="tab-kelas-link" data-toggle="tab" href="#tab-kelas" role="tab" aria-controls="tab-kelas" aria-selected="false" onclick="switchKehadiranTab('tab-kelas', event)">
                                            <i class="fa fa-book mr-1 text-primary"></i>
                                            <span>Pertemuan Kelas &amp; Silabus</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Dropdown Filter Periode -->
                                <div class="kehadiran-filter-box m-0">
                                    <form method="GET" action="<?= base_url('kehadiran') ?>" id="formPeriodeFilter" class="m-0 d-flex align-items-center">
                                        <?php if ($is_admin || $is_dosen): ?>
                                            <input type="hidden" name="student_id" value="<?= (int)$selected_student_id ?>">
                                        <?php endif; ?>
                                        <input type="hidden" name="active_tab" id="hiddenActiveTab" value="tab-rekap">
                                        <label class="filter-periode-label mr-2"><i class="fa fa-clock-o mr-1"></i> Periode:</label>
                                        <select name="periode" class="select-periode-modern" onchange="submitPeriodeForm()">
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
                                <!-- TAB 1: REKAPITULASI KEHADIRAN                            -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade show active" id="tab-rekap" role="tabpanel" aria-labelledby="tab-rekap-link">
                                    
                                    <div class="table-kehadiran-box">
                                        <div class="table-kehadiran-responsive">
                                            <table class="table-kehadiran">
                                                <thead>
                                                    <tr>
                                                        <th class="col-number">No</th>
                                                        <th style="min-width: 105px;">Kode</th>
                                                        <th style="min-width: 260px;">Mata Kuliah</th>
                                                        <th class="col-center" style="min-width: 75px;">Kelas</th>
                                                        <th class="col-center" style="min-width: 100px;">Terselenggara</th>
                                                        <th class="col-center" style="min-width: 70px;">Hadir</th>
                                                        <th class="col-center" style="min-width: 65px;">Sakit</th>
                                                        <th class="col-center" style="min-width: 65px;">Ijin</th>
                                                        <th class="col-center" style="min-width: 65px;">Tugas</th>
                                                        <th class="col-center" style="min-width: 85px;">Tidak Hadir</th>
                                                        <th style="min-width: 160px;">Persentase Hadir</th>
                                                        <th class="col-center" style="min-width: 80px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($courses)): ?>
                                                        <tr>
                                                            <td colspan="12" class="text-center py-5">
                                                                <div class="py-4">
                                                                    <i class="fa fa-info-circle text-info mb-2" style="font-size: 32px; display: inline-block;"></i>
                                                                    <h6 class="font-weight-bold text-dark mb-1">Belum Ada Rekapan Kehadiran</h6>
                                                                    <p class="text-muted small mb-0" style="max-width: 520px; margin: 0 auto; line-height: 1.6;">
                                                                        <?php if (!empty($selected_period['is_active'])): ?>
                                                                            Data kehadiran otomatis disinkronkan setelah Rencana Studi (KRS) disetujui Dosen Wali dan dosen memulai sesi presensi kelas.
                                                                        <?php else: ?>
                                                                            Tidak ada rekaman perkuliahan untuk periode semester ini.
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($courses as $idx => $c): ?>
                                                            <?php
                                                                $persen_val = (float)str_replace('%', '', (string)$c->persentase_hadir);
                                                                $persen_badge_class = 'badge-persen-green';
                                                                $persen_fill_class = 'persen-fill-green';
                                                                if ($persen_val < 75.0) {
                                                                    $persen_badge_class = 'badge-persen-red';
                                                                    $persen_fill_class = 'persen-fill-red';
                                                                } elseif ($persen_val < 85.0) {
                                                                    $persen_badge_class = 'badge-persen-amber';
                                                                    $persen_fill_class = 'persen-fill-amber';
                                                                }
                                                            ?>
                                                            <tr>
                                                                <td class="col-number"><?= $c->no ?></td>
                                                                <td>
                                                                    <span class="mk-code-pill"><?= htmlspecialchars($c->kode_mk) ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="mk-title-main"><?= htmlspecialchars($c->nama_mk) ?></div>
                                                                    <div class="mk-meta-sub">
                                                                        <span><i class="fa fa-user-circle-o text-muted mr-1"></i><?= htmlspecialchars($c->dosen ?? 'Dosen Pengampu') ?></span>
                                                                        <span>&bull;</span>
                                                                        <span><?= (int)($c->sks ?? 3) ?> SKS</span>
                                                                    </div>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="mk-kelas-badge">Kelas <?= htmlspecialchars($c->kelas) ?></span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="cell-stat-val"><?= (int)$c->terselenggara ?></span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="cell-stat-val hadir-highlight"><?= (int)$c->hadir ?></span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="<?= ((int)$c->sakit > 0) ? 'cell-stat-val warn-highlight' : 'cell-stat-zero' ?>">
                                                                        <?= (int)$c->sakit ?>
                                                                    </span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="<?= ((int)$c->ijin > 0) ? 'cell-stat-val warn-highlight' : 'cell-stat-zero' ?>">
                                                                        <?= (int)$c->ijin ?>
                                                                    </span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="<?= ((int)$c->tugas > 0) ? 'cell-stat-val' : 'cell-stat-zero' ?>">
                                                                        <?= (int)$c->tugas ?>
                                                                    </span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="<?= ((int)$c->tidak_hadir > 0) ? 'cell-stat-val danger-highlight' : 'cell-stat-zero' ?>">
                                                                        <?= (int)$c->tidak_hadir ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="persen-cell-wrap">
                                                                        <div class="persen-bar-mini">
                                                                            <div class="persen-fill-mini <?= $persen_fill_class ?>" style="width: <?= min(100, max(0, $persen_val)) ?>%;"></div>
                                                                        </div>
                                                                        <span class="persen-pill-badge <?= $persen_badge_class ?>">
                                                                            <?= htmlspecialchars($c->persentase_hadir) ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="col-center">
                                                                    <button type="button" class="btn-detil-modern" onclick="openDetailModal(<?= $idx ?>)" title="Lihat Rincian Presensi Sesi Perkuliahan">
                                                                        <i class="fa fa-list-alt"></i>
                                                                        <span>Detil</span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                                <?php if (!empty($courses)): ?>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="4" class="text-right">TOTAL &amp; RATA-RATA:</th>
                                                            <th class="col-center font-weight-bold"><?= (int)$total_terselenggara ?></th>
                                                            <th class="col-center font-weight-bold text-success"><?= (int)$total_hadir ?></th>
                                                            <th class="col-center font-weight-bold"><?= (int)$total_sakit ?></th>
                                                            <th class="col-center font-weight-bold"><?= (int)$total_ijin ?></th>
                                                            <th class="col-center font-weight-bold"><?= (int)$total_tugas ?></th>
                                                            <th class="col-center font-weight-bold <?= ($total_tidak_hadir > 0) ? 'text-danger' : '' ?>"><?= (int)$total_tidak_hadir ?></th>
                                                            <th colspan="2">
                                                                <span class="badge <?= $is_syarat_uas_aman ? 'badge-success' : 'badge-danger' ?> px-2.5 py-1" style="font-size: 12px; border-radius: 6px;">
                                                                    <?= number_format($overall_percentage, 2) ?>% (<?= $is_syarat_uas_aman ? 'Lulus Syarat UAS' : 'Waspada' ?>)
                                                                </span>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                <?php endif; ?>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- ======================================================== -->
                                <!-- TAB 2: KEHADIRAN PER-PERTEMUAN GRID MATRIX               -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade" id="tab-pertemuan" role="tabpanel" aria-labelledby="tab-pertemuan-link">
                                    
                                    <div class="table-kehadiran-box table-pertemuan-wrap">
                                        <div class="table-kehadiran-responsive">
                                            <table class="table-kehadiran table-pertemuan">
                                                <thead>
                                                    <tr>
                                                        <th class="col-number sticky-col sticky-col-1">No</th>
                                                        <th class="sticky-col sticky-col-2">Kode</th>
                                                        <th class="sticky-col sticky-col-3">Mata Kuliah</th>
                                                        <th class="col-center" style="width: 65px;">Kelas</th>
                                                        <th class="col-center" style="min-width: 60px;">P1</th>
                                                        <th class="col-center" style="min-width: 60px;">P2</th>
                                                        <th class="col-center" style="min-width: 60px;">P3</th>
                                                        <th class="col-center" style="min-width: 60px;">P4</th>
                                                        <th class="col-center" style="min-width: 60px;">P5</th>
                                                        <th class="col-center" style="min-width: 60px;">P6</th>
                                                        <th class="col-center" style="min-width: 85px;">P6 Pengganti</th>
                                                        <th class="col-center" style="min-width: 85px;">P7 Pengganti</th>
                                                        <th class="col-center" style="min-width: 60px;">P7</th>
                                                        <th class="col-center" style="min-width: 60px;">P8 (UTS)</th>
                                                        <th class="col-center" style="min-width: 60px;">P9</th>
                                                        <th class="col-center" style="min-width: 60px;">P10</th>
                                                        <th class="col-center" style="min-width: 60px;">P11</th>
                                                        <th class="col-center" style="min-width: 60px;">P12</th>
                                                        <th class="col-center" style="min-width: 60px;">P13</th>
                                                        <th class="col-center" style="min-width: 60px;">P14</th>
                                                        <th class="col-center" style="min-width: 60px;">P15</th>
                                                        <th class="col-center" style="min-width: 60px;">P16 (UAS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($courses)): ?>
                                                        <tr>
                                                            <td colspan="22" class="text-center py-5">
                                                                <div class="py-4 text-muted">
                                                                    <i class="fa fa-calendar-times-o mb-2" style="font-size: 32px; display: inline-block;"></i>
                                                                    <h6 class="font-weight-bold text-dark mb-1">Belum Ada Riwayat Pertemuan</h6>
                                                                    <p class="small mb-0">Presensi per pertemuan aktif setelah jadwal dan KRS resmi disetujui.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($courses as $c): ?>
                                                            <tr>
                                                                <td class="col-number sticky-col sticky-col-1"><?= $c->no ?></td>
                                                                <td class="sticky-col sticky-col-2">
                                                                    <span class="mk-code-pill"><?= htmlspecialchars($c->kode_mk) ?></span>
                                                                </td>
                                                                <td class="sticky-col sticky-col-3">
                                                                    <span class="mk-title-main" style="font-size: 12.5px;"><?= htmlspecialchars($c->nama_mk) ?></span>
                                                                </td>
                                                                <td class="col-center">
                                                                    <span class="mk-kelas-badge"><?= htmlspecialchars($c->kelas) ?></span>
                                                                </td>

                                                                <!-- P1 s/d P6 -->
                                                                <?php for ($p = 1; $p <= 6; $p++): 
                                                                    $st = $c->meetings[$p - 1]['status'] ?? 'BELUM';
                                                                ?>
                                                                    <td class="col-center"><?= render_badge_kehadiran($st) ?></td>
                                                                <?php endfor; ?>

                                                                <!-- P6 Pengganti -->
                                                                <td class="col-center">
                                                                    <?= !empty($c->p6_pengganti) ? render_badge_kehadiran($c->p6_pengganti) : '<span class="att-micro-badge badge-belum">-</span>' ?>
                                                                </td>

                                                                <!-- P7 Pengganti -->
                                                                <td class="col-center">
                                                                    <?= !empty($c->p7_pengganti) ? render_badge_kehadiran($c->p7_pengganti) : '<span class="att-micro-badge badge-belum">-</span>' ?>
                                                                </td>

                                                                <!-- P7 s/d P16 -->
                                                                <?php for ($p = 7; $p <= 16; $p++): 
                                                                    $st = $c->meetings[$p - 1]['status'] ?? 'BELUM';
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

                                    <!-- Legenda Status Presensi -->
                                    <div class="presensi-legend-bar">
                                        <div class="legend-title">
                                            <i class="fa fa-info-circle text-primary"></i>
                                            <span>Legenda Presensi:</span>
                                        </div>
                                        <div class="legend-items-wrap">
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-hadir"><i class="fa fa-check mr-1"></i>Hadir</span>
                                                <span>Mahasiswa Hadir</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-sakit"><i class="fa fa-plus-square mr-1"></i>Sakit</span>
                                                <span>Surat Keterangan Dokter</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-ijin"><i class="fa fa-envelope-o mr-1"></i>Ijin</span>
                                                <span>Permohonan Resmi Disetujui</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-tugas"><i class="fa fa-briefcase mr-1"></i>Tugas</span>
                                                <span>Penugasan Luar Kampus</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-alpha"><i class="fa fa-times mr-1"></i>Alpa</span>
                                                <span>Tanpa Keterangan</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="att-micro-badge badge-belum">-</span>
                                                <span>Belum Berjalan</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- ======================================================== -->
                                <!-- TAB 3: PERTEMUAN KELAS & SILABUS MATERI                  -->
                                <!-- ======================================================== -->
                                <div class="tab-pane fade" id="tab-kelas" role="tabpanel" aria-labelledby="tab-kelas-link">
                                    
                                    <?php if (empty($courses)): ?>
                                        <div class="text-center py-5 rounded border bg-light">
                                            <i class="fa fa-book text-muted mb-2" style="font-size: 32px; display: inline-block;"></i>
                                            <h6 class="font-weight-bold text-dark mb-1">Pertemuan Kelas Belum Tersedia</h6>
                                            <p class="text-muted small mb-0">
                                                Detail silabus dan pertemuan kelas akan ditampilkan setelah Rencana Studi (KRS) disetujui Dosen Wali.
                                            </p>
                                        </div>
                                    <?php else: ?>

                                        <!-- Dropdown Pemilihan Mata Kuliah Tab 3 -->
                                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap: 12px;">
                                            <div>
                                                <h5 class="font-weight-bold text-dark mb-1" style="font-size: 15px;">
                                                    <i class="fa fa-bookmark text-primary mr-1"></i> Silabus &amp; Catatan Perkuliahan
                                                </h5>
                                                <small class="text-muted">Pilih mata kuliah untuk melihat pokok bahasan, tanggal, dan pengajar per pertemuan.</small>
                                            </div>
                                            <div style="min-width: 320px; max-width: 480px; width: 100%;">
                                                <select id="selectMatkulTab3" class="form-control" onchange="changeMatkulTab3(this.value)" style="border-radius: 8px; border: 1.5px solid #cbd5e1; font-weight: 600; height: 42px;">
                                                    <?php foreach ($courses as $cIdx => $c): ?>
                                                        <option value="<?= $cIdx ?>" <?= ($cIdx === 0) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($c->kode_mk) ?> | <?= htmlspecialchars($c->nama_mk) ?> - Kelas <?= htmlspecialchars($c->kelas) ?> (<?= $c->sks ?> SKS)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <?php $firstCourse = !empty($courses) ? $courses[0] : null; ?>

                                        <!-- Info Card Mata Kuliah Tab 3 Modern -->
                                        <div class="info-card-pertemuan-modern">
                                            <div class="tab3-grid-meta">
                                                <div class="tab3-meta-item">
                                                    <div class="tab3-meta-icon"><i class="fa fa-graduation-cap"></i></div>
                                                    <div>
                                                        <div class="tab3-meta-label">Program Studi &amp; Periode</div>
                                                        <div class="tab3-meta-value" id="tab3ProdiPeriode">
                                                            <?= htmlspecialchars($student->prodi ?? 'Teknik Informatika') ?> &bull; <?= htmlspecialchars($selected_period['label'] ?? '2024 Genap') ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab3-meta-item">
                                                    <div class="tab3-meta-icon"><i class="fa fa-user"></i></div>
                                                    <div>
                                                        <div class="tab3-meta-label">Dosen Pengampu</div>
                                                        <div class="tab3-meta-value text-primary" id="tab3Pengajar">
                                                            <?= $firstCourse ? htmlspecialchars($firstCourse->dosen) : '-' ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab3-meta-item">
                                                    <div class="tab3-meta-icon"><i class="fa fa-clock-o"></i></div>
                                                    <div>
                                                        <div class="tab3-meta-label">Jadwal &amp; Ruangan</div>
                                                        <div class="tab3-meta-value" id="tab3PertemuanJadwal">
                                                            <?= $firstCourse ? htmlspecialchars($firstCourse->jadwal) : '-' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tabel Detail Pertemuan Silabus -->
                                        <div class="table-kehadiran-box">
                                            <div class="table-kehadiran-responsive">
                                                <table class="table-kehadiran" id="tab3MeetingsTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-number">No</th>
                                                            <th class="col-center" style="width: 70px;">Sesi</th>
                                                            <th style="min-width: 140px;">Tanggal</th>
                                                            <th style="min-width: 140px;">Jenis Sesi</th>
                                                            <th class="col-center" style="min-width: 110px;">Status Hadir</th>
                                                            <th style="min-width: 240px;">Pengajar</th>
                                                            <th style="min-width: 280px;">Pokok Bahasan / Materi Perkuliahan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tab3MeetingsTbody">
                                                        <?php if ($firstCourse && !empty($firstCourse->meetings)): ?>
                                                            <?php foreach ($firstCourse->meetings as $m): ?>
                                                                <tr>
                                                                    <td class="col-number"><?= $m['no'] ?></td>
                                                                    <td class="col-center font-weight-bold">
                                                                        <span class="badge badge-light px-2.5 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                                                            P<?= $m['pertemuan_ke'] ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="font-weight-500"><?= htmlspecialchars($m['tanggal']) ?></td>
                                                                    <td>
                                                                        <span class="badge badge-light-primary px-2.5 py-1" style="border-radius: 6px; font-size: 11px;">
                                                                            <?= htmlspecialchars($m['jenis_pertemuan']) ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="col-center">
                                                                        <?= render_badge_kehadiran($m['status']) ?>
                                                                    </td>
                                                                    <td style="white-space: pre-line;" class="font-weight-500"><?= htmlspecialchars($m['pengajar']) ?></td>
                                                                    <td class="font-weight-600 text-dark"><?= htmlspecialchars($m['materi']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center py-4 text-muted">
                                                                    <i class="fa fa-info-circle mr-1"></i> Data perkuliahan belum tersedia.
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
<!-- MODAL DETIL KEHADIRAN (POPUP SAAT KLIK DETIL TAB 1)      -->
<!-- ======================================================== -->
<div class="modal fade" id="modalDetilKehadiran" tabindex="-1" role="dialog" aria-labelledby="modalDetilKehadiranLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 900px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            
            <div class="modal-detil-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title m-0" id="modalTitle">
                        <i class="fa fa-list-alt mr-1"></i> Rincian Presensi &amp; Kehadiran Kuliah
                    </h5>
                    <small id="modalSubtitle" class="text-white-50 mt-1 d-block font-weight-500">Mata Kuliah</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline: none; opacity: 0.9;">
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
                                    <i class="fa fa-user-circle text-primary" style="font-size: 14px;"></i>
                                    <span>Dosen Pengampu:</span>
                                    <strong id="modalNamaDosen" class="text-dark ml-1">-</strong>
                                </div>
                                <div class="modal-meta-item mt-1">
                                    <i class="fa fa-calendar-check-o text-success" style="font-size: 14px;"></i>
                                    <span>Jadwal Perkuliahan:</span>
                                    <strong id="modalJadwal" class="text-dark ml-1">-</strong>
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

                <!-- Attendance Metrics Strip -->
                <div class="attendance-metrics-strip">
                    <div class="att-metric-pill metric-terselenggara" title="Total pertemuan yang telah diselenggarakan">
                        <span class="att-metric-label">Terselenggara</span>
                        <span class="att-metric-value" id="modalStatTerselenggara">0</span>
                    </div>
                    <div class="att-metric-pill metric-hadir" title="Total kehadiran mahasiswa">
                        <span class="att-metric-label"><i class="fa fa-check mr-1"></i>Hadir</span>
                        <span class="att-metric-value" id="modalStatHadir">0</span>
                    </div>
                    <div class="att-metric-pill metric-sakit" title="Ijin karena sakit">
                        <span class="att-metric-label"><i class="fa fa-plus-square mr-1"></i>Sakit</span>
                        <span class="att-metric-value" id="modalStatSakit">0</span>
                    </div>
                    <div class="att-metric-pill metric-ijin" title="Ijin ada keperluan resmi">
                        <span class="att-metric-label"><i class="fa fa-envelope-o mr-1"></i>Ijin</span>
                        <span class="att-metric-value" id="modalStatIjin">0</span>
                    </div>
                    <div class="att-metric-pill metric-tugas" title="Penugasan akademik luar kelas">
                        <span class="att-metric-label"><i class="fa fa-briefcase mr-1"></i>Tugas</span>
                        <span class="att-metric-value" id="modalStatTugas">0</span>
                    </div>
                    <div class="att-metric-pill metric-alpha" title="Tidak hadir tanpa keterangan">
                        <span class="att-metric-label"><i class="fa fa-times mr-1"></i>Tidak Hadir</span>
                        <span class="att-metric-value" id="modalStatAlpha">0</span>
                    </div>
                </div>

                <!-- Tabel Detail Pertemuan 1 s/d 16 -->
                <div class="table-responsive rounded border" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0" style="font-size: 13px;">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">Sesi</th>
                                <th style="width: 130px;">Tanggal</th>
                                <th style="width: 120px;">Jenis Sesi</th>
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
                <small class="text-muted"><i class="fa fa-check-circle text-success mr-1"></i> Data diverifikasi secara otomatis oleh sistem presensi Smart Campus</small>
                <div>
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btn-sm px-3 ml-2" onclick="window.print()">
                        <i class="fa fa-print mr-1"></i> Cetak Detil
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Logika Interaktif JS -->
<script>
var COURSES_DATA = <?= json_encode($courses, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
var STUDENT_PRODI = "<?= htmlspecialchars($student->prodi ?? 'Teknik Informatika') ?>";
var SELECTED_PERIODE_LBL = "<?= htmlspecialchars($selected_period['label'] ?? '') ?>";

// Menangani perubahan dropdown mata kuliah di Tab 3 (Pertemuan Kelas)
function changeMatkulTab3(courseIdx) {
    if (!COURSES_DATA || !COURSES_DATA[courseIdx]) return;
    var c = COURSES_DATA[courseIdx];

    var elProdi = document.getElementById('tab3ProdiPeriode');
    if (elProdi) {
        elProdi.innerText = STUDENT_PRODI + ' • ' + SELECTED_PERIODE_LBL;
    }
    var elDosen = document.getElementById('tab3Pengajar');
    if (elDosen) {
        elDosen.innerText = c.dosen || '-';
    }
    var elJadwal = document.getElementById('tab3PertemuanJadwal');
    if (elJadwal) {
        elJadwal.innerText = c.jadwal || '-';
    }

    var tbody = document.getElementById('tab3MeetingsTbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (c.meetings && c.meetings.length > 0) {
        c.meetings.forEach(function(m) {
            var tr = document.createElement('tr');
            var badgeHtml = '';
            var st = (m.status || '').toUpperCase();
            if (st === 'HADIR') {
                badgeHtml = '<span class="att-micro-badge badge-hadir"><i class="fa fa-check mr-1"></i>Hadir</span>';
            } else if (st === 'SAKIT') {
                badgeHtml = '<span class="att-micro-badge badge-sakit"><i class="fa fa-plus-square mr-1"></i>Sakit</span>';
            } else if (st === 'IJIN') {
                badgeHtml = '<span class="att-micro-badge badge-ijin"><i class="fa fa-envelope-o mr-1"></i>Ijin</span>';
            } else if (st === 'ALPHA' || st === 'ALPA') {
                badgeHtml = '<span class="att-micro-badge badge-alpha"><i class="fa fa-times mr-1"></i>Alpa</span>';
            } else if (st === 'TUGAS') {
                badgeHtml = '<span class="att-micro-badge badge-tugas"><i class="fa fa-briefcase mr-1"></i>Tugas</span>';
            } else {
                badgeHtml = '<span class="att-micro-badge badge-belum">-</span>';
            }

            tr.innerHTML = '<td class="col-number">' + m.no + '</td>' +
                           '<td class="col-center font-weight-bold"><span class="badge badge-light px-2.5 py-1" style="font-size: 11.5px; border-radius: 6px;">P' + m.pertemuan_ke + '</span></td>' +
                           '<td class="font-weight-500">' + escapeHtml(m.tanggal) + '</td>' +
                           '<td><span class="badge badge-light-primary px-2.5 py-1" style="border-radius: 6px; font-size: 11px;">' + escapeHtml(m.jenis_pertemuan) + '</span></td>' +
                           '<td class="col-center">' + badgeHtml + '</td>' +
                           '<td style="white-space: pre-line;" class="font-weight-500">' + escapeHtml(m.pengajar) + '</td>' +
                           '<td class="font-weight-600 text-dark">' + escapeHtml(m.materi) + '</td>';
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

    document.getElementById('modalTitle').innerHTML = '<i class="fa fa-list-alt mr-1"></i> ' + escapeHtml(c.nama_mk);
    document.getElementById('modalSubtitle').innerText = c.kode_mk + ' - Kelas ' + c.kelas + ' (' + c.sks + ' SKS)';
    if (document.getElementById('modalNamaMK')) {
        document.getElementById('modalNamaMK').innerText = c.nama_mk || '-';
    }
    document.getElementById('modalBadgeKode').innerText = c.kode_mk;
    document.getElementById('modalBadgeKelas').innerText = 'Kelas ' + c.kelas;
    document.getElementById('modalBadgeSks').innerText = c.sks + ' SKS';
    document.getElementById('modalNamaDosen').innerText = c.dosen || '-';
    document.getElementById('modalJadwal').innerText = c.jadwal || '-';
    
    var persenText = c.persentase_hadir || '0.00%';
    document.getElementById('modalPersentaseHadir').innerText = persenText;
    var numVal = parseFloat(persenText) || 0;
    var pBar = document.getElementById('modalPercentageBar');
    if (pBar) {
        pBar.style.width = Math.min(100, Math.max(0, numVal)) + '%';
        if (numVal >= 80) {
            pBar.style.background = '#10b981';
            document.getElementById('modalPersentaseHadir').style.color = '#059669';
        } else if (numVal >= 75) {
            pBar.style.background = '#f59e0b';
            document.getElementById('modalPersentaseHadir').style.color = '#d97706';
        } else {
            pBar.style.background = '#ef4444';
            document.getElementById('modalPersentaseHadir').style.color = '#dc2626';
        }
    }

    document.getElementById('modalStatTerselenggara').innerText = c.terselenggara;
    document.getElementById('modalStatHadir').innerText = c.hadir;
    document.getElementById('modalStatSakit').innerText = c.sakit;
    document.getElementById('modalStatIjin').innerText = c.ijin;
    document.getElementById('modalStatTugas').innerText = c.tugas;
    document.getElementById('modalStatAlpha').innerText = c.tidak_hadir;

    var tbody = document.getElementById('modalMeetingsTableBody');
    tbody.innerHTML = '';

    if (c.meetings && c.meetings.length > 0) {
        c.meetings.forEach(function(m) {
            var tr = document.createElement('tr');
            var badgeHtml = '';
            var st = (m.status || '').toUpperCase();
            if (st === 'HADIR') {
                badgeHtml = '<span class="att-micro-badge badge-hadir"><i class="fa fa-check mr-1"></i>Hadir</span>';
            } else if (st === 'SAKIT') {
                badgeHtml = '<span class="att-micro-badge badge-sakit"><i class="fa fa-plus-square mr-1"></i>Sakit</span>';
            } else if (st === 'IJIN') {
                badgeHtml = '<span class="att-micro-badge badge-ijin"><i class="fa fa-envelope-o mr-1"></i>Ijin</span>';
            } else if (st === 'ALPHA' || st === 'ALPA') {
                badgeHtml = '<span class="att-micro-badge badge-alpha"><i class="fa fa-times mr-1"></i>Alpa</span>';
            } else if (st === 'TUGAS') {
                badgeHtml = '<span class="att-micro-badge badge-tugas"><i class="fa fa-briefcase mr-1"></i>Tugas</span>';
            } else {
                badgeHtml = '<span class="att-micro-badge badge-belum">-</span>';
            }

            tr.innerHTML = '<td class="text-center font-weight-bold text-muted">P' + m.pertemuan_ke + '</td>' +
                           '<td class="text-dark font-weight-500">' + escapeHtml(m.tanggal) + '</td>' +
                           '<td>' + escapeHtml(m.jenis_pertemuan) + '</td>' +
                           '<td><b>' + escapeHtml(m.materi) + '</b></td>' +
                           '<td class="text-center">' + badgeHtml + '</td>' +
                           '<td class="text-muted small" style="white-space: pre-line;">' + escapeHtml(m.pengajar) + '</td>';
            tbody.appendChild(tr);
        });
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

// Beralih tab kehadiran secara instan & responsif
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
