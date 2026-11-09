<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$student = $khs_data['student'] ?? null;
$courses = $khs_data['courses'] ?? [];
$summary = $khs_data['summary'] ?? [];
$available_periods = $khs_data['available_periods'] ?? [];
$selected_period = $khs_data['selected_period'] ?? null;
$selected_jenis = $selected_jenis ?? ($this->input->get('jenis', true) ?: ($this->uri->segment(3) === 'transkrip' ? 'transkrip' : 'khs'));
$active_tab = $active_tab ?? ($this->input->get('tab', true) ?: 'nilai');
$obe_attainment = $obe_attainment ?? null;
$obe_filter_semester = $obe_filter_semester ?? ($this->input->get('semester_obe', true) ?: ($this->input->get('semester', true) ?: 'all'));

$is_admin = !empty($is_admin);
$is_dosen = !empty($is_dosen);
$all_students = $all_students ?? [];
$students_list = $students_list ?? [];
$total_students = $total_students ?? count($students_list);
$prodi_options = $prodi_options ?? [];
$angkatan_options = $angkatan_options ?? [];
$filters = $filters ?? [
    'prodi'    => $this->input->get('prodi', true) ?: '',
    'angkatan' => $this->input->get('angkatan', true) ?: '',
    'semester' => $this->input->get('semester', true) ?: '',
    'status'   => $this->input->get('status', true) ?: '',
    'q'        => $this->input->get('q', true) ?: '',
];
$selected_student_id = (int)($selected_student_id ?? ($student ? $student->id : 0));
$is_search_mode = ($is_admin || $is_dosen) && empty($selected_student_id);

if ($selected_jenis === 'transkrip' && empty($obe_attainment) && $selected_student_id > 0) {
    $ci =& get_instance();
    $ci->load->model('M_obe');
    try {
        $filter_sem = (!empty($obe_filter_semester) && $obe_filter_semester !== 'all') ? (int)$obe_filter_semester : 'all';
        $obe_attainment = $ci->M_obe->get_student_obe_attainment($selected_student_id, $filter_sem);
    } catch (\Throwable $e) {}
}
?>

<style>
/* ======================================================== */
/* KARTU HASIL STUDI (KHS) STYLING - PIXEL PERFECT DESIGN    */
/* ======================================================== */

.khs-card {
    border-radius: 10px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
    overflow: hidden;
    margin-bottom: 25px;
}

/* Header Biru Tosca Muda khas SIAKAD */
.khs-card-header {
    background: linear-gradient(135deg, #29b6f6 0%, #0288d1 100%) !important;
    padding: 13px 20px !important;
    border-bottom: none !important;
}

.khs-card-header h5 {
    color: #ffffff !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    margin: 0 !important;
    letter-spacing: 0.2px;
}

/* Controls Bar */
.khs-controls-bar {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 15px 20px;
}

.khs-filter-select {
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 13.5px !important;
    height: 38px !important;
    color: #334155 !important;
    background-color: #ffffff !important;
    padding: 6px 12px !important;
    box-shadow: none !important;
}

.khs-filter-select:focus {
    border-color: #0288d1 !important;
    box-shadow: 0 0 0 2px rgba(2, 136, 209, 0.15) !important;
}

.khs-btn-print {
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    color: #334155 !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    height: 38px !important;
    padding: 6px 16px !important;
    transition: all 0.2s ease;
}

.khs-btn-print:hover {
    background: #f8fafc !important;
    color: #0288d1 !important;
    border-color: #0288d1 !important;
}

/* Metadata Box */
.khs-meta-box {
    padding: 12px 20px;
    font-size: 13.5px;
    color: #1e293b;
}

.khs-meta-label {
    width: 140px;
    min-width: 110px;
    max-width: 145px;
    color: #475569;
    font-weight: 500;
    flex-shrink: 0;
}

.khs-meta-sep {
    width: 18px;
    color: #64748b;
    text-align: center;
    flex-shrink: 0;
}

.khs-meta-value {
    color: #0f172a;
    flex: 1;
    min-width: 0;
}

@media (max-width: 992px) {
    .khs-meta-box {
        padding: 10px 16px;
    }
    .khs-meta-label {
        width: 120px;
        font-size: 13px;
    }
}

@media (max-width: 576px) {
    .khs-meta-box {
        padding: 8px 12px;
    }
    .khs-meta-label {
        width: 105px;
        font-size: 12px;
    }
    .khs-meta-value {
        font-size: 12.5px;
    }
}

/* Tabel KHS */
.table-khs-wrapper {
    padding: 0 20px;
}

.table-khs {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    min-width: 880px; /* Cegah tabel KHS terhimpit */
}

.table-khs thead th {
    background-color: #bce4f7 !important;
    color: #0f3d64 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    padding: 12px 14px !important;
    border-top: none !important;
    border-bottom: 1px solid #90cdf4 !important;
    vertical-align: middle;
    white-space: nowrap;
}

.table-khs tbody td {
    padding: 12px 14px !important;
    font-size: 13px !important;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    line-height: 1.5;
}

.table-khs tbody tr:hover {
    background-color: #f8fafc;
}

/* Footer Summary */
.khs-summary-box {
    padding: 18px 20px;
    font-size: 13.5px;
    color: #1e293b;
    border-top: 1px solid #e2e8f0;
    margin-top: 5px;
}

.khs-summary-label {
    width: 180px;
    color: #475569;
    font-weight: 500;
}

.khs-summary-sep {
    width: 20px;
    color: #64748b;
    text-align: center;
}

.khs-summary-val {
    color: #0f172a;
    font-weight: 600;
}

/* Badge Nilai */
.badge-grade {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 12px;
}
.badge-grade-a { background: #e0f2fe; color: #0369a1; }
.badge-grade-b { background: #dcfce7; color: #15803d; }
.badge-grade-c { background: #fef3c7; color: #b45309; }
.badge-grade-d { background: #fee2e2; color: #b91c1c; }

/* Transkrip Summary Footer (Sesuai Palet Warna & Tema Smart Campus) */
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-green,
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-light {
    background-color: #e0f2fe !important;
}
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-green td,
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-light td {
    background-color: #e0f2fe !important;
    color: #026aa2 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    padding: 7px 14px !important;
    border: 1px solid #bae6fd !important;
    vertical-align: middle;
}
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-teal,
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-accent {
    background-color: #bce4f7 !important;
}
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-teal td,
.table-khs tfoot.khs-transkrip-footer tr.transkrip-row-accent td {
    background-color: #bce4f7 !important;
    color: #0f3d64 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    padding: 7px 14px !important;
    border: 1px solid #90cdf4 !important;
    vertical-align: middle;
}
.table-khs tfoot.khs-transkrip-footer .transkrip-title-cell {
    letter-spacing: 0.3px;
    text-transform: uppercase;
    text-align: right !important;
    padding-right: 14px !important;
}
.table-khs tfoot.khs-transkrip-footer .transkrip-val-cell {
    font-size: 13.5px !important;
    text-align: center !important;
    white-space: nowrap !important;
}
.table-khs tfoot.khs-transkrip-footer .transkrip-empty-cell {
    background-color: transparent !important;
}
.table-khs tfoot.khs-transkrip-footer .transkrip-notes-cell {
    font-size: 12px !important;
    font-weight: 700 !important;
    line-height: 1.55 !important;
    padding: 10px 14px !important;
    text-transform: none !important;
}

/* Badges for SMT & Paket */
.khs-smt-pill {
    display: inline-block;
    padding: 2px 7px;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
}
.khs-paket-pill {
    display: inline-block;
    padding: 1px 6px;
    font-size: 10.5px;
    font-weight: 500;
    color: #64748b;
    background: #f8fafc;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
}

/* ======================================================== */
/* KHS DIRECTORY & FILTER STYLING (ADMIN / DOSEN)           */
/* ======================================================== */
.khs-directory-filter-card {
    background: #ffffff !important;
    border-radius: 14px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important;
    padding: 22px 24px !important;
    margin-bottom: 22px !important;
}

@media (max-width: 991.98px) {
    .khs-directory-filter-card {
        padding: 18px 18px !important;
        border-radius: 12px !important;
    }
}

@media (max-width: 575.98px) {
    .khs-directory-filter-card {
        padding: 15px 14px !important;
        border-radius: 10px !important;
        margin-bottom: 16px !important;
    }
}

.khs-filter-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(2, 136, 209, 0.12), rgba(2, 136, 209, 0.05));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #0288d1;
    flex-shrink: 0;
}

.khs-filter-title {
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
}

.khs-prodi-pills-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px dashed #cbd5e1;
}

.khs-prodi-pills-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 4px;
    flex-shrink: 0;
}

.khs-prodi-pill {
    font-size: 12px;
    font-weight: 600;
    padding: 5px 13px;
    border-radius: 20px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #475569;
    text-decoration: none !important;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.khs-prodi-pill:hover {
    background: #e2e8f0;
    color: #0f172a;
    text-decoration: none !important;
}

.khs-prodi-pill.active {
    background: #0288d1;
    border-color: #0288d1;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.28);
}

@media (max-width: 991.98px) {
    .khs-prodi-pills-wrapper {
        gap: 6px;
        margin-bottom: 15px;
        padding-bottom: 12px;
    }
    .khs-prodi-pills-label {
        width: 100%;
        margin-bottom: 2px;
    }
    .khs-prodi-pill {
        font-size: 11.5px;
        padding: 4px 11px;
    }
}

.khs-form-label {
    font-size: 12.5px !important;
    font-weight: 700 !important;
    color: #334155 !important;
    margin-bottom: 7px !important;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.khs-form-input {
    height: 42px !important;
    font-size: 13.5px !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    padding: 8px 14px !important;
    transition: all 0.2s ease;
    width: 100%;
}

select.khs-form-input {
    padding-right: 32px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 10px center !important;
    background-size: 15px 15px !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    cursor: pointer;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.khs-form-input:focus {
    border-color: #0288d1 !important;
    box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15) !important;
    outline: none !important;
}

.khs-search-input-group {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.khs-search-input-group i.search-icon {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
    z-index: 4;
}

.khs-search-input-group .khs-form-input {
    padding-left: 38px !important;
    width: 100%;
}

.khs-filter-btn-group {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.khs-btn-search-submit {
    height: 42px;
    border-radius: 8px;
    background: linear-gradient(135deg, #0288d1 0%, #026aa2 100%);
    border: none;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 13.5px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(2, 136, 209, 0.28);
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
    flex: 1.3;
}

.khs-btn-search-submit i {
    margin-right: 6px;
    font-size: 13.5px;
}

.khs-btn-search-submit:hover {
    background: linear-gradient(135deg, #026aa2 0%, #01579b 100%);
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(2, 136, 209, 0.38);
}

.khs-btn-search-reset {
    height: 42px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #475569 !important;
    font-weight: 600;
    font-size: 13.5px;
    padding: 0 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    text-decoration: none !important;
    white-space: nowrap;
    flex: 1;
}

.khs-btn-search-reset i {
    margin-right: 6px;
    font-size: 13.5px;
}

.khs-btn-search-reset:hover {
    background: #f1f5f9;
    color: #0f172a !important;
    border-color: #94a3b8;
    text-decoration: none !important;
}

@media (max-width: 575.98px) {
    .khs-filter-btn-group {
        flex-direction: column;
        gap: 8px;
    }
    .khs-btn-search-reset,
    .khs-btn-search-submit {
        width: 100%;
        flex: none;
    }
}

/* Tabel Hasil Pencarian Mahasiswa */
.khs-table-card {
    background: #ffffff !important;
    border-radius: 12px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05) !important;
    overflow: hidden;
    margin-bottom: 30px !important;
}

.khs-table-header {
    padding: 16px 24px !important;
    background: #ffffff !important;
    border-bottom: 1px solid #f1f5f9 !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.khs-table-title {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-student-directory {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.table-student-directory thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 18px !important;
    border-top: none !important;
    border-bottom: 2px solid #e2e8f0 !important;
    vertical-align: middle;
}

.table-student-directory tbody td {
    padding: 14px 18px !important;
    font-size: 13px !important;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-student-directory tbody tr:hover {
    background-color: #f0f9ff;
}

/* Badge & Avatar */
.mhs-avatar-badge {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0288d1 0%, #00acc1 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 12px;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
}

.badge-prodi {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
    font-weight: 600;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
}

.badge-angkatan {
    background: #f5f3ff;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
    font-weight: 600;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
}

.badge-semester {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-weight: 600;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
}

.badge-status-aktif {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    font-weight: 700;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge-status-cuti {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
    font-weight: 700;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-view-khs {
    background: linear-gradient(135deg, #0288d1 0%, #026aa2 100%);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 12.5px;
    padding: 7px 16px;
    border-radius: 6px;
    border: none;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    text-decoration: none !important;
}

.btn-view-khs:hover {
    background: linear-gradient(135deg, #026aa2 0%, #01579b 100%);
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(2, 136, 209, 0.35);
}

.btn-back-to-search {
    background: #ffffff;
    color: #0288d1 !important;
    border: 1.5px solid #0288d1;
    font-weight: 700;
    font-size: 13.5px;
    padding: 8px 18px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    transition: all 0.2s ease;
    text-decoration: none !important;
}

.btn-back-to-search:hover {
    background: #0288d1;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(2, 136, 209, 0.25);
}

/* ======================================================== */
/* MODERN PROFESSIONAL PRINT STYLING (A4 PORTRAIT)          */
/* ======================================================== */
/* ======================================================== */
/* MODERN PROFESSIONAL PRINT STYLING (A4 PORTRAIT)          */
/* ======================================================== */
@media print {
    @page {
        size: A4 portrait;
        margin: 8mm 10mm 12mm 10mm;
    }

    html, body {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
        font-size: 10px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 1. Complete reset of wrappers, sidebar offsets, card shadows and backgrounds */
    #pcoded,
    #pcoded.pcoded,
    #pcoded.pcoded:not([vertical-nav-type="collapsed"]) .pcoded-content,
    #pcoded.pcoded[theme-layout="vertical"] .pcoded-content,
    body .pcoded-content,
    body[class*="theme-"] #pcoded .pcoded-content,
    .pcoded,
    .pcoded-container,
    .navbar-wrapper,
    .pcoded-main-container,
    .pcoded-wrapper,
    .pcoded-content,
    .pcoded-inner-content,
    .main-body,
    .page-wrapper,
    .page-body,
    #khsContainerCard,
    .khs-card,
    body .card,
    .card,
    .table-khs-wrapper,
    .table-responsive {
        margin: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        left: 0 !important;
        right: 0 !important;
        float: none !important;
        position: static !important;
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    /* 2. Hide all non-printable screen UI */
    .pcoded-header,
    .pcoded-navbar,
    .pcoded-overlay-box,
    .sidebar_toggle,
    .transkrip-tab-navigation,
    .khs-controls-bar,
    .obe-filter-bar,
    .breadcrumbs,
    .page-header,
    .btn,
    .btn-back-to-search,
    .khs-back-toolbar,
    .admin-student-selector,
    .khs-directory-filter-card,
    .khs-table-card,
    footer,
    .no-print,
    .d-print-none,
    .alert,
    .alert-success,
    .alert-info,
    .alert-warning,
    .mx-3.mb-3.p-3,
    .mx-3.my-3.p-3,
    .khs-card-header,
    .khs-meta-box {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* 3. Official University Letterhead & Metadata Block (Anti Page-Break) */
    .print-only-letterhead {
        display: block !important;
        width: 100% !important;
        margin-bottom: 8px !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }

    .table-meta-print {
        display: table !important;
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .table-meta-print td {
        border: none !important;
        background: transparent !important;
    }

    /* 4. Table Container Overflow Reset (Prevent Chromium clipping & pagination bugs) */
    .table-responsive,
    .table-khs-wrapper {
        overflow: visible !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }

    /* 5. Professional Table Layout for KHS & Transkrip */
    .table-khs {
        min-width: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        margin: 4px 0 !important;
        font-size: 9.5px !important;
        color: #000000 !important;
    }

    .table-khs thead {
        display: table-header-group !important;
    }

    .table-khs tfoot {
        display: table-row-group !important;
    }

    .table-khs tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .table-khs thead th {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border: 1px solid #334155 !important;
        padding: 6px 3px !important;
        font-weight: 700 !important;
        font-size: 9.5px !important;
        text-align: center !important;
        vertical-align: middle !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
    }

    .table-khs tbody td {
        border: 1px solid #475569 !important;
        color: #000000 !important;
        padding: 5px 3px !important;
        font-size: 9px !important;
        vertical-align: middle !important;
        line-height: 1.25 !important;
    }

    /* Column Widths: Transkrip Table (8 columns) */
    .table-khs.transkrip-table th:nth-child(1),
    .table-khs.transkrip-table td:nth-child(1),
    .table-khs th.col-no,
    .table-khs td.col-no {
        width: 38px !important;
        min-width: 38px !important;
        max-width: 44px !important;
        text-align: center !important;
        white-space: nowrap !important;
        word-break: keep-all !important;
        overflow-wrap: normal !important;
        padding: 5px 2px !important;
    }
    .table-khs.transkrip-table th:nth-child(2),
    .table-khs.transkrip-table td:nth-child(2),
    .table-khs th.col-smt,
    .table-khs td.col-smt {
        width: 46px !important;
        min-width: 46px !important;
        text-align: center !important;
        white-space: nowrap !important;
        padding: 5px 2px !important;
    }
    .table-khs.transkrip-table th:nth-child(3),
    .table-khs.transkrip-table td:nth-child(3),
    .table-khs th.col-kode,
    .table-khs td.col-kode {
        width: 80px !important;
        min-width: 80px !important;
        text-align: center !important;
        white-space: nowrap !important;
        font-weight: 600 !important;
        padding: 5px 4px !important;
    }
    .table-khs.transkrip-table th:nth-child(4),
    .table-khs.transkrip-table td:nth-child(4),
    .table-khs th.col-nama,
    .table-khs td.col-nama {
        text-align: left !important;
        padding: 5px 8px !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    .table-khs.transkrip-table th:nth-child(5),
    .table-khs.transkrip-table td:nth-child(5),
    .table-khs th.col-sks,
    .table-khs td.col-sks {
        width: 48px !important;
        min-width: 48px !important;
        text-align: center !important;
        white-space: nowrap !important;
        padding: 5px 2px !important;
    }
    .table-khs.transkrip-table th:nth-child(6),
    .table-khs.transkrip-table td:nth-child(6),
    .table-khs th.col-nilai,
    .table-khs td.col-nilai {
        width: 50px !important;
        min-width: 50px !important;
        text-align: center !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        padding: 5px 2px !important;
    }
    .table-khs.transkrip-table th:nth-child(7),
    .table-khs.transkrip-table td:nth-child(7),
    .table-khs th.col-index,
    .table-khs td.col-index {
        width: 56px !important;
        min-width: 56px !important;
        text-align: center !important;
        white-space: nowrap !important;
        padding: 5px 2px !important;
    }
    .table-khs.transkrip-table th:nth-child(8),
    .table-khs.transkrip-table td:nth-child(8),
    .table-khs th.col-nxk,
    .table-khs td.col-nxk {
        width: 56px !important;
        min-width: 56px !important;
        text-align: center !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        padding: 5px 2px !important;
    }

    /* Column Widths: KHS Semester Table (9 columns) */
    .table-khs.khs-table th:nth-child(1),
    .table-khs.khs-table td:nth-child(1) { width: 38px !important; min-width: 38px !important; text-align: center !important; white-space: nowrap !important; padding: 5px 2px !important; }
    .table-khs.khs-table th:nth-child(2),
    .table-khs.khs-table td:nth-child(2) { width: 80px !important; min-width: 80px !important; text-align: center !important; white-space: nowrap !important; font-weight: 600 !important; padding: 5px 4px !important; }
    .table-khs.khs-table th:nth-child(3),
    .table-khs.khs-table td:nth-child(3) { text-align: left !important; padding: 5px 8px !important; word-wrap: break-word !important; }
    .table-khs.khs-table th:nth-child(4),
    .table-khs.khs-table td:nth-child(4) { width: 46px !important; min-width: 46px !important; text-align: center !important; white-space: nowrap !important; padding: 5px 2px !important; }
    .table-khs.khs-table th:nth-child(5),
    .table-khs.khs-table td:nth-child(5) { width: 48px !important; min-width: 48px !important; text-align: center !important; font-weight: 700 !important; white-space: nowrap !important; padding: 5px 2px !important; }
    .table-khs.khs-table th:nth-child(6),
    .table-khs.khs-table td:nth-child(6) { width: 50px !important; min-width: 50px !important; text-align: center !important; white-space: nowrap !important; padding: 5px 2px !important; }
    .table-khs.khs-table th:nth-child(7),
    .table-khs.khs-table td:nth-child(7) { width: 75px !important; min-width: 75px !important; text-align: center !important; white-space: nowrap !important; padding: 5px 4px !important; }
    .table-khs.khs-table th:nth-child(8),
    .table-khs.khs-table td:nth-child(8) { width: 54px !important; min-width: 54px !important; text-align: center !important; font-weight: 700 !important; white-space: nowrap !important; padding: 5px 2px !important; }
    .table-khs.khs-table th:nth-child(9),
    .table-khs.khs-table td:nth-child(9) { width: 54px !important; min-width: 54px !important; text-align: center !important; white-space: nowrap !important; padding: 5px 2px !important; }

    /* Footer Transkrip Styling (Column-Aligned) */
    .table-khs tfoot.khs-transkrip-footer tr td {
        border: 1px solid #334155 !important;
        padding: 5px 6px !important;
        font-size: 9.5px !important;
    }
    .table-khs tfoot.khs-transkrip-footer tr.transkrip-row-green td,
    .table-khs tfoot.khs-transkrip-footer tr.transkrip-row-teal td {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
    .transkrip-title-cell {
        font-weight: 700 !important;
        text-align: right !important;
        padding-right: 12px !important;
        letter-spacing: 0.3px !important;
    }
    .transkrip-val-cell {
        font-weight: 700 !important;
        text-align: center !important;
    }
    .transkrip-empty-cell {
        background-color: #f8fafc !important;
    }
    .transkrip-notes-cell {
        font-size: 8.5px !important;
        color: #475569 !important;
        padding: 5px 8px !important;
    }

    /* Badges in print - Clean & crisp without pill borders */
    .khs-smt-pill,
    .khs-paket-pill,
    .badge-grade,
    .badge {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: #000000 !important;
    }
    .khs-smt-pill {
        font-weight: 600 !important;
        font-size: 9.5px !important;
    }
    .khs-paket-pill {
        font-size: 8.5px !important;
        color: #64748b !important;
    }
    .badge-grade {
        font-weight: 800 !important;
        font-size: 9.5px !important;
    }

    /* Summary Box in print (for KHS semester) */
    .khs-summary-box {
        border: 1px solid #334155 !important;
        background: #f8fafc !important;
        padding: 8px 12px !important;
        margin-top: 8px !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        font-size: 10px !important;
    }
    .khs-summary-label {
        width: 180px !important;
        font-size: 10px !important;
    }
    .khs-summary-val {
        font-size: 10.5px !important;
    }

    /* Signatures in print */
    .print-only-signatures {
        display: block !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin-top: 14px !important;
        padding: 0 !important;
    }
    .print-only-signatures .row {
        margin: 0 !important;
    }
    .print-only-signatures p {
        margin-bottom: 2px !important;
        font-size: 10px !important;
    }

    /* OBE Transkrip print rules */
    .obe-content-wrapper {
        padding: 0 !important;
    }
    .obe-card {
        border: 1px solid #94a3b8 !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin-bottom: 12px !important;
        padding: 10px !important;
    }
    .obe-course-content {
        display: block !important;
    }
    .obe-chevron-pill {
        display: none !important;
    }
}

@media screen {
    .print-only-letterhead,
    .print-only-signatures,
    .table-meta-print {
        display: none !important;
    }
}

/* ======================================================== */
/* TRANSKRIP UNIFIED TABS & OBE STYLING                    */
/* ======================================================== */
.transkrip-tab-navigation {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.transkrip-tab-pill-group {
    display: inline-flex;
    background: #e2e8f0;
    padding: 4px;
    border-radius: 10px;
    gap: 4px;
}

.transkrip-tab-btn {
    border: none;
    background: transparent;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none !important;
}

.transkrip-tab-btn:hover {
    color: #0288d1;
    background: rgba(255, 255, 255, 0.6);
}

.transkrip-tab-btn.active {
    background: #ffffff;
    color: #0288d1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* OBE Filter Bar */
.obe-filter-bar {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.obe-filter-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.obe-filter-heading {
    font-size: 13.5px;
    display: inline-flex;
    align-items: center;
    letter-spacing: -0.1px;
}

.obe-pills-list {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.obe-pill-btn {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none !important;
    border: 1px solid transparent;
    white-space: nowrap;
}

.obe-pill-btn i {
    margin-right: 6px;
}

.obe-pill-btn.active {
    background: #0288d1;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
}

.obe-pill-btn.inactive {
    background: #f1f5f9;
    color: #475569 !important;
    border-color: #e2e8f0;
}

.obe-pill-btn.inactive:hover {
    background: #e2e8f0;
    color: #0f172a !important;
}

.obe-pill-btn.ongoing {
    background: #fef3c7;
    color: #92400e !important;
    border-color: #fde68a;
}

.obe-pill-btn.ongoing:hover {
    background: #fde68a;
}

@media (max-width: 576px) {
    .obe-filter-bar {
        padding: 12px 14px;
        gap: 10px;
    }
    .obe-filter-top {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .obe-filter-top .khs-btn-print {
        width: 100%;
        justify-content: center;
    }
    .obe-pill-btn {
        padding: 5px 11px;
        font-size: 11.5px;
    }
}

/* OBE Quick Stats Strip */
.obe-stats-strip {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 20px 18px;
}

.obe-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

@media (max-width: 992px) {
    .obe-stats-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }
}

@media (max-width: 860px) {
    .obe-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .obe-stat-card:nth-child(3):last-child {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .obe-stats-strip {
        padding: 12px 14px 16px;
    }
    .obe-stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .obe-stat-card:nth-child(3):last-child {
        grid-column: span 1;
    }
}

.obe-stat-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
    min-width: 0;
    height: 100%;
    margin-bottom: 0;
    box-sizing: border-box;
}

.obe-stat-card:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    transform: translateY(-1px);
}

.obe-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    margin-right: 0;
}

.obe-stat-icon.icon-success {
    background: #ecfdf5;
    color: #10b981;
    border: 1px solid #a7f3d0;
}

.obe-stat-icon.icon-warning {
    background: #fffbeb;
    color: #f59e0b;
    border: 1px solid #fde68a;
}

.obe-stat-icon.icon-primary {
    background: #f0f9ff;
    color: #0288d1;
    border: 1px solid #bae6fd;
}

.obe-stat-content {
    flex: 1;
    min-width: 0;
}

.obe-stat-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 2px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    line-height: 1.35;
    white-space: normal;
}

.obe-stat-val {
    font-size: 18px;
    font-weight: 800;
    line-height: 1.2;
}

/* OBE Content Wrapper */
.obe-content-wrapper {
    padding: 20px;
}

/* OBE Cards */
.obe-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    padding: 20px;
    margin-bottom: 20px;
}

.obe-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.obe-card-subtitle {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
    margin-bottom: 15px;
}

/* Radar Legend */
.obe-legend-pill {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.obe-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
    display: inline-block;
    flex-shrink: 0;
}

/* CPL Items */
.obe-cpl-list {
    max-height: 380px;
    overflow-y: auto;
    padding-right: 8px;
}

.obe-cpl-list::-webkit-scrollbar {
    width: 6px;
}

.obe-cpl-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.obe-cpl-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.obe-cpl-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.obe-cpl-item {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 10px;
    padding: 13px 16px;
    margin-bottom: 10px;
    transition: all 0.2s ease;
}

.obe-cpl-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.obe-cpl-code-pill {
    background: #e0f2fe;
    color: #0369a1;
    font-size: 11px;
    font-weight: 700;
    font-family: monospace;
    padding: 3px 8px;
    border-radius: 5px;
    margin-right: 8px;
    border: 1px solid #bae6fd;
}

.obe-progress-track {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 9999px;
    overflow: hidden;
    margin: 9px 0 7px;
}

.obe-progress-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.6s ease;
}

.obe-progress-fill.passed {
    background: linear-gradient(90deg, #10b981 0%, #14b8a6 100%);
}

.obe-progress-fill.need-improve {
    background: linear-gradient(90deg, #f59e0b 0%, #ef4444 100%);
}

.obe-progress-fill.ongoing {
    background: #fbbf24;
}

/* Course Accordions */
.obe-course-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    background: #ffffff;
}

.obe-course-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
}

.obe-course-header-btn {
    width: 100%;
    text-align: left;
    padding: 14px 18px;
    background: #f8fafc;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    transition: background 0.15s ease;
}

.obe-course-header-btn:hover {
    background: #f1f5f9;
}

/* Grade Boxes */
.obe-grade-box {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
    margin-right: 14px;
    flex-shrink: 0;
}

.obe-grade-box.grade-a {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.obe-grade-box.grade-b {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.obe-grade-box.grade-c {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}

.obe-grade-box.grade-ongoing {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
    font-size: 16px;
}

.obe-course-code-pill {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    font-family: monospace;
    padding: 2.5px 7px;
    margin-right: 8px;
}

.obe-course-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}

.obe-ongoing-badge {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: 8px;
}

.obe-course-meta {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.obe-meta-sep {
    display: inline-block;
    margin: 0 8px;
    color: #cbd5e1;
}

.obe-cpmk-count-pill {
    padding: 5px 11px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    margin-right: 12px;
    white-space: nowrap;
}

.obe-cpmk-count-pill.passed {
    background: #e0f2fe;
    color: #0288d1;
    border: 1px solid #bae6fd;
}

.obe-cpmk-count-pill.ongoing {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.obe-chevron-pill {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 13px;
    transition: transform 0.25s ease, background-color 0.2s ease;
    flex-shrink: 0;
}

.obe-chevron-pill:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.obe-course-content {
    display: none;
    padding: 18px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
}

.obe-cpmk-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px 16px;
    background: #f8fafc;
    margin-bottom: 12px;
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

<?php if ($is_search_mode): ?>
    <!-- ======================================================== -->
    <!-- MODE 1: PENCARIAN & DIREKTORI MAHASISWA (ADMIN / DOSEN) -->
    <!-- ======================================================== -->
    
    <!-- CARD FILTER & PENCARIAN -->
    <div class="khs-directory-filter-card">
        <!-- Header Filter -->
        <div class="d-flex align-items-center justify-content-between flex-wrap pb-3 mb-3" style="border-bottom: 1px solid #f1f5f9; gap: 12px;">
            <div class="d-flex align-items-center">
                <div class="khs-filter-icon-box mr-3">
                    <i class="fa fa-sliders"></i>
                </div>
                <div>
                    <h5 class="khs-filter-title mb-0">Filter &amp; Pencarian Mahasiswa</h5>
                    <span class="text-muted" style="font-size: 13px;">Saring data KHS mahasiswa berdasarkan Program Studi, Angkatan, Semester, atau NIM/Nama.</span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <span class="badge badge-primary px-3 py-1.5 font-weight-bold" style="border-radius: 20px; font-size: 12px;">
                    <i class="fa fa-users mr-1"></i> <?= count($students_list) ?> Mahasiswa Ditemukan
                </span>
                <?php if (!empty($filters['prodi']) || !empty($filters['angkatan']) || !empty($filters['semester']) || !empty($filters['status']) || !empty($filters['q'])): ?>
                    <a href="<?= current_url() ?><?= $selected_jenis === 'transkrip' ? '?jenis=transkrip' : '' ?>" class="btn btn-sm btn-outline-danger font-weight-bold px-2.5 py-1 d-inline-flex align-items-center" style="border-radius: 6px; font-size: 12px; gap: 6px;" title="Reset Semua Filter">
                        <i class="fa fa-refresh" style="margin-right: 6px;"></i><span>Reset</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($prodi_options)): ?>
            <!-- Quick Filter Pills Prodi (1 Klik Langsung Filter) -->
            <div class="khs-prodi-pills-wrapper">
                <span class="khs-prodi-pills-label">
                    <i class="fa fa-tags text-primary"></i> Pilihan Cepat Prodi:
                </span>
                <?php
                $base_f = $filters;
                $base_f['prodi'] = '';
                $all_prodi_url = current_url() . (!empty(array_filter($base_f)) ? '?' . http_build_query(array_filter($base_f)) : '');
                ?>
                <a href="<?= $all_prodi_url ?>" class="khs-prodi-pill <?= empty($filters['prodi']) ? 'active' : '' ?>">
                    Semua Prodi
                </a>
                <?php foreach ($prodi_options as $p): ?>
                    <?php
                    $p_f = $filters;
                    $p_f['prodi'] = $p;
                    $p_url = current_url() . '?' . http_build_query(array_filter($p_f));
                    ?>
                    <a href="<?= $p_url ?>" class="khs-prodi-pill <?= ($filters['prodi'] === $p) ? 'active' : '' ?>">
                        <?= html_escape($p) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="GET" action="<?= current_url() ?>">
            <input type="hidden" name="jenis" value="<?= html_escape($selected_jenis) ?>">
            
            <!-- Row 1: Parameter Kategori (Prodi, Angkatan, Semester, Status) -->
            <div class="row">
                <!-- Dropdown Program Studi / Jurusan -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3">
                    <label class="khs-form-label">
                        <i class="fa fa-graduation-cap text-primary"></i> Program Studi / Jurusan
                    </label>
                    <select name="prodi" class="form-control khs-form-input">
                        <option value="">-- Semua Jurusan / Prodi --</option>
                        <?php foreach ($prodi_options as $p): ?>
                            <option value="<?= html_escape($p) ?>" <?= ($filters['prodi'] === $p) ? 'selected' : '' ?>>
                                <?= html_escape($p) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dropdown Angkatan -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3">
                    <label class="khs-form-label">
                        <i class="fa fa-calendar text-primary"></i> Tahun Angkatan
                    </label>
                    <select name="angkatan" class="form-control khs-form-input">
                        <option value="">-- Semua Angkatan --</option>
                        <?php foreach ($angkatan_options as $a): ?>
                            <option value="<?= html_escape($a) ?>" <?= ($filters['angkatan'] === (string)$a) ? 'selected' : '' ?>>
                                Angkatan <?= html_escape($a) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dropdown Semester -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3">
                    <label class="khs-form-label">
                        <i class="fa fa-layer-group text-primary"></i> Semester
                    </label>
                    <select name="semester" class="form-control khs-form-input">
                        <option value="">-- Semua Semester --</option>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?= $s ?>" <?= ($filters['semester'] === (string)$s) ? 'selected' : '' ?>>
                                Semester <?= $s ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Dropdown Status Akademik -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3">
                    <label class="khs-form-label">
                        <i class="fa fa-check-circle text-primary"></i> Status Mahasiswa
                    </label>
                    <select name="status" class="form-control khs-form-input">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" <?= ($filters['status'] === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="Cuti" <?= ($filters['status'] === 'Cuti') ? 'selected' : '' ?>>Cuti</option>
                        <option value="Non-Aktif" <?= ($filters['status'] === 'Non-Aktif') ? 'selected' : '' ?>>Non-Aktif</option>
                        <option value="Lulus" <?= ($filters['status'] === 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Pencarian Teks & Tombol Aksi (Responsif & Rapi di Segala Layar) -->
            <div class="row align-items-end">
                <!-- Input Pencarian Nama / NIM -->
                <div class="col-xl-7 col-lg-7 col-md-12 col-12 mb-3 mb-lg-0">
                    <label class="khs-form-label">
                        <i class="fa fa-search text-primary"></i> Cari Mahasiswa
                    </label>
                    <div class="khs-search-input-group">
                        <i class="fa fa-search search-icon"></i>
                        <input type="text" name="q" value="<?= html_escape($filters['q']) ?>" class="form-control khs-form-input" placeholder="Ketik NIM atau Nama lengkap mahasiswa...">
                    </div>
                </div>

                <!-- Tombol Aksi Filter & Reset (Inline & Sejajar) -->
                <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                    <label class="khs-form-label d-none d-lg-block" style="visibility: hidden;">&nbsp;</label>
                    <div class="khs-filter-btn-group">
                        <a href="<?= current_url() ?><?= $selected_jenis === 'transkrip' ? '?jenis=transkrip' : '' ?>" class="khs-btn-search-reset text-center">
                            <i class="fa fa-refresh"></i><span>Reset Filter</span>
                        </a>
                        <button type="submit" class="khs-btn-search-submit">
                            <i class="fa fa-filter"></i><span>Terapkan Filter &amp; Cari</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- CARD DAFTAR HASIL PENCARIAN MAHASISWA -->
    <div class="khs-table-card">
        <div class="khs-table-header">
            <div>
                <h5 class="khs-table-title">
                    <i class="fa fa-id-card-o text-primary"></i> Direktori Mahasiswa
                    <span class="badge badge-primary ml-2 px-2.5 py-1" style="border-radius: 12px; font-size: 11.5px;">
                        <?= count($students_list) ?> Mahasiswa Ditemukan
                    </span>
                </h5>
                <span class="text-muted" style="font-size: 12.5px;">
                    Klik tombol <strong>Lihat KHS</strong> pada salah satu mahasiswa di bawah ini untuk menampilkan lembar Kartu Hasil Studi lengkap.
                </span>
            </div>
            <?php if (!empty($filters['prodi']) || !empty($filters['angkatan']) || !empty($filters['semester']) || !empty($filters['status']) || !empty($filters['q'])): ?>
                <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span class="text-muted mr-1" style="font-size: 12px;">Filter Aktif:</span>
                    <?php if (!empty($filters['prodi'])): ?>
                        <span class="badge badge-light border text-primary" style="font-size: 11px;">Prodi: <?= html_escape($filters['prodi']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['angkatan'])): ?>
                        <span class="badge badge-light border text-purple" style="font-size: 11px;">Angkatan: <?= html_escape($filters['angkatan']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['semester'])): ?>
                        <span class="badge badge-light border text-info" style="font-size: 11px;">Smt <?= html_escape($filters['semester']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['status'])): ?>
                        <span class="badge badge-light border text-success" style="font-size: 11px;"><?= html_escape($filters['status']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['q'])): ?>
                        <span class="badge badge-light border text-dark" style="font-size: 11px;">"<?= html_escape($filters['q']) ?>"</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-student-directory">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No.</th>
                        <th style="width: 140px;">NIM / NRP</th>
                        <th>Nama Lengkap &amp; Jenjang</th>
                        <th>Program Studi</th>
                        <th class="text-center" style="width: 110px;">Angkatan</th>
                        <th class="text-center" style="width: 110px;">Semester</th>
                        <th class="text-center" style="width: 110px;">Status</th>
                        <th class="text-center" style="width: 90px;">IPK</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students_list)): ?>
                        <?php $no = 1; foreach ($students_list as $st): ?>
                            <?php
                            $query_params = array_filter([
                                'student_id' => $st->id,
                                'jenis'      => ($selected_jenis === 'transkrip' ? 'transkrip' : null),
                                'prodi'      => !empty($filters['prodi']) ? $filters['prodi'] : null,
                                'angkatan'   => !empty($filters['angkatan']) ? $filters['angkatan'] : null,
                                'semester'   => !empty($filters['semester']) ? $filters['semester'] : null,
                                'status'     => !empty($filters['status']) ? $filters['status'] : null,
                                'q'          => !empty($filters['q']) ? $filters['q'] : null,
                            ]);
                            $view_url = current_url() . '?' . http_build_query($query_params);
                            $initial = strtoupper(substr(trim($st->nama_lengkap), 0, 1));
                            $status_label = $st->status_akademik ?? 'Aktif';
                            $is_aktif = ($status_label === 'Aktif');
                            ?>
                            <tr>
                                <td class="text-center text-muted font-weight-bold"><?= $no++ ?></td>
                                <td>
                                    <span class="font-weight-bold" style="font-family: monospace; font-size: 13.5px; color: #1e293b; letter-spacing: 0.3px;">
                                        <?= html_escape($st->nim) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mhs-avatar-badge"><?= $initial ?></div>
                                        <div>
                                            <div class="font-weight-bold" style="color: #0f172a; font-size: 13.5px;">
                                                <?= html_escape($st->nama_lengkap) ?>
                                            </div>
                                            <small class="text-muted font-weight-medium">
                                                <i class="fa fa-graduation-cap mr-1"></i><?= html_escape($st->jenjang ?? 'Strata 1') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-prodi">
                                        <?= html_escape($st->prodi) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-angkatan">
                                        <?= html_escape($st->angkatan ?? '2024') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-semester">
                                        Smt <?= (int)($st->semester ?? 1) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="<?= $is_aktif ? 'badge-status-aktif' : 'badge-status-cuti' ?>">
                                        <i class="fa <?= $is_aktif ? 'fa-check' : 'fa-exclamation-circle' ?>" style="font-size: 11px;"></i>
                                        <?= html_escape($status_label) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 4px; font-size: 12.5px;">
                                        <?= number_format((float)($st->ipk ?? 3.25), 2) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= $view_url ?>" class="btn-view-khs">
                                        <i class="fa fa-file-text-o mr-1"></i> Lihat KHS
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fa fa-user-times text-muted mb-3" style="font-size: 42px; display: block;"></i>
                                <h6 class="font-weight-bold text-dark mb-1">Mahasiswa Tidak Ditemukan</h6>
                                <p class="text-muted mb-3" style="font-size: 13px;">
                                    Tidak ada data mahasiswa yang cocok dengan kriteria filter Program Studi, Angkatan, Semester, atau kata kunci pencarian.
                                </p>
                                <a href="<?= current_url() ?><?= $selected_jenis === 'transkrip' ? '?jenis=transkrip' : '' ?>" class="btn btn-outline-primary btn-sm px-3 py-1 font-weight-bold d-inline-flex align-items-center" style="border-radius: 6px; gap: 6px;">
                                    <i class="fa fa-refresh" style="margin-right: 6px;"></i><span>Reset Filter</span>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- ======================================================== -->
    <!-- MODE 2: TAMPILAN DETAIL KHS MAHASISWA                     -->
    <!-- ======================================================== -->
    
    <?php if ($is_admin || $is_dosen): ?>
        <?php
        $back_params = array_filter([
            'jenis'    => ($selected_jenis === 'transkrip' ? 'transkrip' : null),
            'prodi'    => !empty($filters['prodi']) ? $filters['prodi'] : null,
            'angkatan' => !empty($filters['angkatan']) ? $filters['angkatan'] : null,
            'semester' => !empty($filters['semester']) ? $filters['semester'] : null,
            'status'   => !empty($filters['status']) ? $filters['status'] : null,
            'q'        => !empty($filters['q']) ? $filters['q'] : null,
        ]);
        $back_url = current_url() . (!empty($back_params) ? '?' . http_build_query($back_params) : '');
        ?>
        <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap khs-back-toolbar">
            <a href="<?= $back_url ?>" class="btn-back-to-search">
                <i class="fa fa-arrow-left mr-1"></i> Kembali ke Pencarian Mahasiswa
            </a>
            <div class="text-muted" style="font-size: 13px;">
                Menampilkan KHS: <strong class="text-dark"><?= html_escape($student->nim ?? '') ?> - <?= html_escape($student->nama_lengkap ?? '') ?></strong> (<?= html_escape($student->prodi ?? '') ?>)
            </div>
        </div>
    <?php endif; ?>

    <!-- KARTU HASIL STUDI CONTAINER -->
    <div class="card khs-card" id="khsContainerCard">
        
        <!-- Header Kartu -->
        <div class="card-header khs-card-header d-flex justify-content-between align-items-center no-print">
            <div class="d-flex align-items-center">
                <i class="fa fa-file-text-o text-white mr-2" style="font-size: 18px;"></i>
                <h5><?= ($selected_jenis === 'transkrip') ? 'Transkrip Akademik &amp; Capaian OBE' : 'Kartu Hasil Studi' ?></h5>
            </div>
            <div>
                <a href="javascript:void(0)" class="text-white" onclick="toggleKhsCardFullscreen()" title="Perbesar Tampilan">
                    <i class="fa fa-arrows-alt" style="font-size: 15px;"></i>
                </a>
            </div>
        </div>

        <?php if ($selected_jenis === 'transkrip'): ?>
        <!-- Tab Switcher Navigation (Transkrip Nilai Kumulatif vs Transkrip Capaian OBE) -->
        <div class="transkrip-tab-navigation no-print">
            <div class="transkrip-tab-pill-group">
                <button type="button" class="transkrip-tab-btn <?= ($active_tab !== 'obe') ? 'active' : '' ?>" id="btnTabNilai" onclick="switchTranskripTab('nilai')">
                    <i class="fa fa-file-text-o mr-1.5"></i> Transkrip Nilai Kumulatif
                </button>
                <button type="button" class="transkrip-tab-btn <?= ($active_tab === 'obe') ? 'active' : '' ?>" id="btnTabObe" onclick="switchTranskripTab('obe')">
                    <i class="fa fa-bar-chart mr-1.5"></i> Transkrip Capaian OBE (CPL &amp; CPMK)
                </button>
            </div>
            <div class="d-none d-md-flex align-items-center">
                <span class="badge badge-light border text-muted px-2.5 py-1" style="font-size: 12px; border-radius: 6px;">
                    <i class="fa fa-graduation-cap text-primary mr-1"></i> Rekapitulasi Terpadu Hasil Studi &amp; Capaian OBE
                </span>
            </div>
        </div>
        <?php endif; ?>

        <!-- ======================================================== -->
        <!-- PANE TAB 1: TRANSKRIP NILAI AKADEMIK KUMULATIF           -->
        <!-- ======================================================== -->
        <div id="paneTranskripNilai" style="<?= ($selected_jenis === 'transkrip' && $active_tab === 'obe') ? 'display: none;' : '' ?>">

        <?php
            $bulan_indo_arr = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $tgl_cetak_indo = date('j') . ' ' . ($bulan_indo_arr[(int)date('n')] ?? date('F')) . ' ' . date('Y');
        ?>

        <!-- KOP SURAT RESMI UNIVERSITAS (HANYA MUNCUL SAAT CETAK / PRINT) -->
        <div class="print-only-letterhead">
            <div class="d-flex align-items-center justify-content-between pb-2 mb-2" style="border-bottom: 3px double #0f172a;">
                <div style="width: 75px; text-align: left; flex-shrink: 0;">
                    <img src="<?= base_url('assets/images/smart-campus-icon.png') ?>" alt="Logo" style="width: 65px; height: auto;" onerror="this.src='<?= base_url('assets/images/favicon.png') ?>'">
                </div>
                <div class="text-center flex-grow-1 px-2" style="line-height: 1.25;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; color: #1e293b; text-transform: uppercase;">
                        KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI
                    </div>
                    <div style="font-size: 15px; font-weight: 800; color: #0f172a; letter-spacing: 0.8px; text-transform: uppercase; margin-top: 2px;">
                        UNIVERSITAS SMART CAMPUS INDONESIA
                    </div>
                    <div style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase; margin-top: 1px;">
                        BIRO ADMINISTRASI AKADEMIK &amp; KEMAHASISWAAN (BAAK)
                    </div>
                    <div style="font-size: 9px; color: #475569; margin-top: 3px;">
                        Kampus Terpadu: Jl. Ringroad Smart Campus No. 101 | Telp: (021) 7788-9900 | Fax: (021) 7788-9901<br>
                        Laman: https://smartcampus.ac.id | Pos-el: baak@smartcampus.ac.id
                    </div>
                </div>
                <div style="width: 75px; flex-shrink: 0;"></div>
            </div>
            
            <div class="text-center my-2">
                <h5 style="margin: 0; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a;">
                    <?= ($selected_jenis === 'transkrip') ? 'TRANSKRIP AKADEMIK SEMENTARA' : 'KARTU HASIL STUDI (KHS)' ?>
                </h5>
                <div style="font-size: 10.5px; font-weight: 600; color: #475569; margin-top: 2px;">
                    <?php if ($selected_jenis === 'transkrip'): ?>
                        Rekapitulasi Kumulatif Hasil Studi Mahasiswa
                    <?php else: ?>
                        Periode: <?= html_escape($selected_period['label'] ?? '') ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TABEL DATA MAHASISWA KHUSUS CETAK (RINGKAS & ANTI-POTONG HALAMAN) -->
            <?php if ($student): ?>
                <table class="table-meta-print">
                    <tr>
                        <td style="width: 14%; font-weight: 600;">NRP / NIM</td>
                        <td style="width: 2%;">:</td>
                        <td style="width: 34%; font-weight: 700;"><?= html_escape($student->nim ?? '') ?></td>
                        <td style="width: 16%; font-weight: 600;">Jenjang</td>
                        <td style="width: 2%;">:</td>
                        <td style="width: 32%; font-weight: 700;"><?= html_escape($student->jenjang ?? 'Diploma 3') ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Nama Mahasiswa</td>
                        <td>:</td>
                        <td style="font-weight: 700;"><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></td>
                        <td style="font-weight: 600;">Program Studi</td>
                        <td>:</td>
                        <td style="font-weight: 700;"><?= html_escape($student->prodi ?? '') ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Dosen Wali</td>
                        <td>:</td>
                        <td style="font-weight: 700;"><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></td>
                        <td style="font-weight: 600;">Semester / Status</td>
                        <td>:</td>
                        <td style="font-weight: 700;">Semester <?= html_escape($student->semester ?? '1') ?> (<?= html_escape($student->status_akademik ?? 'Aktif') ?>)</td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>

        <!-- Bar Filter & Tombol Cetak -->
        <div class="khs-controls-bar no-print">
            <form method="GET" action="<?= current_url() ?>" id="khsFilterForm" class="d-flex flex-wrap align-items-center justify-content-between">
                
                <!-- Filter state hidden inputs to preserve state across semester changes -->
                <?php if (!empty($filters['prodi'])): ?><input type="hidden" name="prodi" value="<?= html_escape($filters['prodi']) ?>"><?php endif; ?>
                <?php if (!empty($filters['angkatan'])): ?><input type="hidden" name="angkatan" value="<?= html_escape($filters['angkatan']) ?>"><?php endif; ?>
                <?php if (!empty($filters['semester'])): ?><input type="hidden" name="semester" value="<?= html_escape($filters['semester']) ?>"><?php endif; ?>
                <?php if (!empty($filters['status'])): ?><input type="hidden" name="status" value="<?= html_escape($filters['status']) ?>"><?php endif; ?>
                <?php if (!empty($filters['q'])): ?><input type="hidden" name="q" value="<?= html_escape($filters['q']) ?>"><?php endif; ?>

                <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                    
                    <!-- Jika role Superadmin / Dosen: Pemilih Mahasiswa -->
                    <?php if ($is_admin || $is_dosen): ?>
                        <div class="d-flex align-items-center mr-3 mb-2 mb-sm-0 admin-student-selector">
                            <label class="font-weight-bold mr-2 mb-0" style="white-space: nowrap; font-size: 13px; color: #334155;">
                                <i class="fa fa-user mr-1 text-primary"></i> Mahasiswa:
                            </label>
                            <select name="student_id" class="form-control khs-filter-select" onchange="if(this.value===''){ window.location.href='<?= $back_url ?>'; } else { document.getElementById('khsFilterForm').submit(); }" style="min-width: 250px;">
                                <option value="">&larr; [Kembali ke Pencarian Mahasiswa]</option>
                                <?php foreach ($all_students as $st): ?>
                                    <option value="<?= $st->id ?>" <?= ((int)$st->id === (int)$selected_student_id) ? 'selected' : '' ?>>
                                        <?= html_escape($st->nim) ?> - <?= html_escape($st->nama_lengkap) ?> (<?= html_escape($st->prodi) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                                    <!-- Dropdown 1: Jenis KHS -->
                                    <div class="mr-2 mb-2 mb-sm-0">
                                        <select name="jenis" class="form-control khs-filter-select" onchange="document.getElementById('khsFilterForm').submit();" style="min-width: 190px;">
                                            <option value="khs" <?= $selected_jenis !== 'transkrip' ? 'selected' : '' ?>>KHS Per-Semester</option>
                                            <option value="transkrip" <?= $selected_jenis === 'transkrip' ? 'selected' : '' ?>>Transkrip Kumulatif</option>
                                        </select>
                                    </div>

                                    <!-- Dropdown 2: Periode / Semester -->
                                    <?php if ($selected_jenis !== 'transkrip'): ?>
                                        <div class="mr-2 mb-2 mb-sm-0">
                                            <select name="periode" class="form-control khs-filter-select" onchange="document.getElementById('khsFilterForm').submit();" style="min-width: 190px;" <?= empty($available_periods) ? 'disabled' : '' ?>>
                                                <?php if (!empty($available_periods)): ?>
                                                    <?php foreach ($available_periods as $p): ?>
                                                        <option value="<?= $p['key'] ?>" <?= ($p['key'] === ($selected_period['key'] ?? '')) ? 'selected' : '' ?>>
                                                            <?= html_escape($p['label']) ?><?= !empty($p['is_paket']) ? ' (Paket)' : '' ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">-- Belum Ada Riwayat KHS --</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <!-- Tombol Cetak / Eksport -->
                                <div class="mb-2 mb-md-0">
                                    <button type="button" class="btn btn-outline-secondary khs-btn-print d-flex align-items-center" onclick="window.print()">
                                        <i class="fa fa-print mr-2"></i> Cetak / Eksport
                                    </button>
                                </div>

                            </form>
                        </div>

                        <!-- Panel Metadata Mahasiswa (Persis sesuai foto) -->
                        <?php if ($student): ?>
                            <div class="khs-meta-box d-print-none no-print">
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">NRP</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px;"><?= html_escape($student->nim) ?></span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Jenjang Pendidikan</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->jenjang ?? 'Diploma 3') ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">Nama</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px; letter-spacing: 0.3px;">
                                            <?= strtoupper(html_escape($student->nama_lengkap)) ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Program Studi</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->prodi) ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">Dosen Wali</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Semester / Status</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold d-inline-flex align-items-center flex-wrap" style="gap: 6px;">
                                            <span>Semester <?= html_escape($student->semester ?? '1') ?> (<?= html_escape($student->status_akademik ?? 'Aktif') ?>)</span>
                                            <?php if (!empty($summary['is_paket_semester'])): ?>
                                                <span class="badge badge-info px-2 py-1 d-inline-flex align-items-center" style="border-radius: 4px; font-size: 11px; font-weight: 500; white-space: nowrap;">
                                                    <i class="fa fa-cube mr-1"></i> Paket Semester 1 (Dipaketkan)
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Alert Penjelasan: Semester Aktif (Baru KRS) vs Riwayat KHS yang Selesai -->
                        <?php if ($student): ?>
                            <div class="mx-3 mb-3 p-3 d-flex align-items-center no-print" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                                <div class="mr-3 text-success">
                                    <i class="fa fa-info-circle" style="font-size: 22px;"></i>
                                </div>
                                <div style="font-size: 13px; color: #166534; line-height: 1.5;">
                                    <strong>Status Semester Aktif:</strong> Mahasiswa saat ini berstatus aktif di <strong>Semester <?= (int)($student->semester ?? 1) ?></strong> (telah menyelesaikan pengisian KRS semester berjalan).
                                    <?php if ((int)($student->semester ?? 1) > 1): ?>
                                        <br>Nilai KHS yang ditampilkan di bawah ini adalah rekaman nilai resmi dari semester yang telah tuntas dievaluasi (<strong>Semester 1 s/d Semester <?= (int)$student->semester - 1 ?></strong>). Nilai untuk Semester <?= (int)$student->semester ?> akan diterbitkan setelah masa perkuliahan dan evaluasi ujian semester berjalan berakhir.
                                    <?php else: ?>
                                        <br>Mahasiswa baru Semester 1 baru menempuh paket perkuliahan semester awal. Nilai KHS akan diterbitkan secara resmi setelah periode ujian semester 1 selesai.
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tabel KHS -->
                        <div class="table-khs-wrapper table-responsive">
                            <table class="table table-khs <?= ($selected_jenis === 'transkrip') ? 'transkrip-table' : 'khs-table' ?>">
                                <thead>
                                    <tr>
                                        <th class="text-center col-no" style="width: 42px;">No.</th>
                                        <?php if ($selected_jenis === 'transkrip'): ?>
                                            <th class="text-center col-smt" style="width: 55px;">Smt</th>
                                        <?php endif; ?>
                                        <th class="col-kode" style="width: 90px;">Kode</th>
                                        <th class="col-nama">Mata Kuliah</th>
                                        <th class="text-center col-sks" style="width: 55px;">SKS</th>
                                        <th class="text-center col-nilai" style="width: 55px;">Nilai</th>
                                        <th class="text-center col-index" style="width: 60px;">Index</th>
                                        <?php if ($selected_jenis !== 'transkrip'): ?>
                                            <th style="width: 140px;">Kategori</th>
                                        <?php endif; ?>
                                        <th class="text-center col-nxk" style="width: 65px;">NxK</th>
                                        <?php if ($selected_jenis !== 'transkrip'): ?>
                                            <th class="text-center" style="width: 100px;">Keterangan</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($courses)): ?>
                                        <?php $no = 1; foreach ($courses as $c): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold col-no"><?= $no++ ?></td>
                                                <?php if ($selected_jenis === 'transkrip'): ?>
                                                    <td class="text-center col-smt"><span class="khs-smt-pill">Smt <?= (int)$c->semester_mhs ?></span></td>
                                                <?php endif; ?>
                                                <td class="font-weight-bold col-kode" style="color: #475569; letter-spacing: 0.3px;">
                                                    <?= html_escape($c->kode_mk) ?>
                                                </td>
                                                <td class="font-weight-bold col-nama" style="color: #1e293b;">
                                                    <?= html_escape($c->nama_mk) ?>
                                                    <?php if (!empty($c->is_paket)): ?>
                                                        <span class="khs-paket-pill ml-1">Paket</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center col-sks"><?= (int)$c->sks ?></td>
                                                <td class="text-center font-weight-bold col-nilai">
                                                    <?php 
                                                        $grade = strtoupper(trim($c->nilai_huruf));
                                                        $badge_cls = 'badge-grade-a';
                                                        if (strpos($grade, 'B') !== false) $badge_cls = 'badge-grade-b';
                                                        elseif (strpos($grade, 'C') !== false) $badge_cls = 'badge-grade-c';
                                                        elseif (strpos($grade, 'D') !== false || strpos($grade, 'E') !== false) $badge_cls = 'badge-grade-d';
                                                    ?>
                                                    <span class="badge-grade <?= $badge_cls ?>"><?= html_escape($c->nilai_huruf) ?></span>
                                                </td>
                                                <td class="text-center col-index"><?= number_format((float)$c->nilai_indeks, 1) ?></td>
                                                <?php if ($selected_jenis !== 'transkrip'): ?>
                                                    <td><?= html_escape($c->kategori) ?></td>
                                                <?php endif; ?>
                                                <td class="text-center font-weight-bold col-nxk"><?= (int)$c->nxk == $c->nxk ? (int)$c->nxk : number_format((float)$c->nxk, 1) ?></td>
                                                <?php if ($selected_jenis !== 'transkrip'): ?>
                                                    <td class="text-center"><?= html_escape($c->keterangan) ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?= ($selected_jenis === 'transkrip') ? 8 : 9 ?>" class="text-center py-5 text-muted">
                                                <i class="fa fa-clock-o text-warning" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                                <span class="font-weight-bold" style="font-size: 14px; color: #334155;">Belum Ada Rekaman Nilai KHS</span>
                                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">
                                                    Mahasiswa baru saja mengisi KRS pada semester aktif ini. Nilai KHS akan terbit setelah evaluasi ujian akhir semester selesai diinput.
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                                <?php if ($selected_jenis === 'transkrip'): ?>
                                    <!-- Footer Ringkasan Transkrip Sesuai Foto Resmi SIAKAD (Data Dinamis Mahasiswa) -->
                                    <tfoot class="khs-transkrip-footer">
                                        <tr class="transkrip-row-green">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">TOTAL AMBIL SKS</td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= (int)($summary['total_ambil_sks'] ?? 0) ?></td>
                                            <td colspan="3" class="transkrip-empty-cell"></td>
                                        </tr>
                                        <tr class="transkrip-row-green">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">TOTAL SKS LULUS</td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= (int)($summary['total_sks_lulus'] ?? 0) ?></td>
                                            <td colspan="3" class="transkrip-empty-cell"></td>
                                        </tr>
                                        <tr class="transkrip-row-teal">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">TOTAL NILAI (NxK)</td>
                                            <td colspan="3" class="transkrip-empty-cell"></td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= number_format((float)($summary['total_nilai'] ?? 0), 1) ?></td>
                                        </tr>
                                        <tr class="transkrip-row-green">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">INDEKS PRESTASI KUMULATIF (IPK)</td>
                                            <td colspan="2" class="transkrip-empty-cell"></td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= number_format((float)($summary['ipk_transkrip'] ?? $summary['grand_ipk'] ?? 0), 2) ?></td>
                                            <td class="transkrip-empty-cell"></td>
                                        </tr>
                                        <tr class="transkrip-row-teal">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">TOTAL SKS D</td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= (int)($summary['total_sks_d'] ?? 0) ?></td>
                                            <td colspan="3" class="transkrip-empty-cell"></td>
                                        </tr>
                                        <tr class="transkrip-row-green">
                                            <td colspan="4" class="text-right font-weight-bold transkrip-title-cell">TOTAL SKS E</td>
                                            <td class="text-center font-weight-bold transkrip-val-cell"><?= (int)($summary['total_sks_e'] ?? 0) ?></td>
                                            <td colspan="3" class="transkrip-empty-cell"></td>
                                        </tr>
                                        <tr class="transkrip-row-teal">
                                            <td colspan="8" class="text-left transkrip-notes-cell">
                                                <div class="mb-1">* IP tidak termasuk matakuliah dengan nilai kosong</div>
                                                <div>* IPK termasuk mata kuliah dengan nilai D dan E.</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>

                        <!-- Footer Ringkasan KHS Per-Semester (Hanya ditampilkan saat mode KHS Per-Semester) -->
                        <?php if ($selected_jenis !== 'transkrip'): ?>
                        <div class="khs-summary-box">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="khs-summary-label">Jumlah Bobot</span>
                                        <span class="khs-summary-sep">:</span>
                                        <span class="khs-summary-val"><?= (int)$summary['jumlah_bobot'] == $summary['jumlah_bobot'] ? (int)$summary['jumlah_bobot'] : number_format((float)$summary['jumlah_bobot'], 2) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="khs-summary-label"><?= ($selected_jenis === 'transkrip') ? 'Total Seluruh SKS' : 'Jumlah SKS Semester Ini' ?></span>
                                        <span class="khs-summary-sep">:</span>
                                        <span class="khs-summary-val font-weight-bold text-dark"><?= (int)$summary['jumlah_sks'] ?> SKS</span>
                                    </div>
                                </div>
                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="khs-summary-label"><?= ($selected_jenis === 'transkrip') ? 'Indeks Prestasi Kumulatif' : 'Index Prestasi (IPS)' ?></span>
                                        <span class="khs-summary-sep">:</span>
                                        <span class="khs-summary-val font-weight-bold" style="font-size: 15px; color: #0288d1;">
                                            <?= number_format((float)$summary['ip'], 2) ?>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="khs-summary-label">Beban Studi Max Smt. y.a.d</span>
                                        <span class="khs-summary-sep">:</span>
                                        <span class="khs-summary-val font-weight-bold"><?= (int)$summary['beban_max'] ?> SKS</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Baris Akumulasi SKS & IPK Kumulatif (Menjawab SKS yang sudah ditempuh) -->
                            <?php if ($selected_jenis !== 'transkrip'): ?>
                                <div class="row pt-3 mt-3" style="border-top: 1px dashed #cbd5e1; background: #f8fafc; border-radius: 6px; padding: 12px 14px; margin-left: 0; margin-right: 0;">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="font-weight-bold text-primary" style="width: 220px; font-size: 13px;">
                                                <i class="fa fa-check-circle mr-1"></i> Total SKS Ditempuh <?= !empty($selected_period['semester_mhs']) ? '(s/d Smt ' . $selected_period['semester_mhs'] . ')' : '' ?>
                                            </span>
                                            <span class="khs-summary-sep">:</span>
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 13px; border-radius: 4px;">
                                                <?= (int)($summary['sks_kumulatif_sd'] ?? $summary['jumlah_sks']) ?> SKS
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted" style="font-size: 12px;">
                                            <span style="width: 220px; padding-left: 20px;">Rentang Semester:</span>
                                            <span class="khs-summary-sep">:</span>
                                            <span>Semester 1 s/d Semester <?= html_escape($selected_period['semester_mhs'] ?? '1') ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="font-weight-bold text-success" style="width: 220px; font-size: 13px;">
                                                <i class="fa fa-trophy mr-1"></i> IPK Kumulatif <?= !empty($selected_period['semester_mhs']) ? '(s/d Smt ' . $selected_period['semester_mhs'] . ')' : '' ?>
                                            </span>
                                            <span class="khs-summary-sep">:</span>
                                            <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 13px; border-radius: 4px;">
                                                <?= number_format((float)($summary['ipk_kumulatif_sd'] ?? $summary['ip']), 2) ?>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted" style="font-size: 12px;">
                                            <span style="width: 220px; padding-left: 20px;">Total Seluruh SKS Selesai:</span>
                                            <span class="khs-summary-sep">:</span>
                                            <span class="font-weight-bold text-dark"><?= (int)($summary['total_seluruh_sks'] ?? $summary['jumlah_sks']) ?> SKS (IPK Total: <?= number_format((float)($summary['grand_ipk'] ?? $summary['ip']), 2) ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- TANDA TANGAN RESMI CETAK (HANYA MUNCUL SAAT PRINT) -->
                        <div class="print-only-signatures px-4">
                            <div class="row mb-3" style="font-size: 11px;">
                                <div class="col-7"></div>
                                <div class="col-5 text-center">
                                    Diterbitkan di: Kampus Terpadu<br>
                                    Pada tanggal: <?= $tgl_cetak_indo ?>
                                </div>
                            </div>
                            <div class="row text-center mt-3" style="font-size: 11.5px;">
                                <div class="col-4">
                                    <p class="mb-4">Mahasiswa,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></u></p>
                                    <p class="text-muted">NRP. <?= html_escape($student->nim ?? '') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-4">Dosen Wali,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></u></p>
                                    <p class="text-muted">NIP. <?= html_escape($student->nip_dosen_wali ?? '-') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-4">Ketua Program Studi,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u>Dr. Ir. Hendra Setiawan, M.T.</u></p>
                                    <p class="text-muted">NIP. 197805122003121002</p>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /#paneTranskripNilai -->

                    <?php if ($selected_jenis === 'transkrip'): ?>
                    <!-- ======================================================== -->
                    <!-- PANE TAB 2: TRANSKRIP KETERCAPAIAN OBE (CPL & CPMK)      -->
                    <!-- ======================================================== -->
                    <div id="paneTranskripObe" style="<?= ($active_tab !== 'obe') ? 'display: none;' : '' ?>">
                        <?php
                            $obe_courses = $obe_attainment['courses'] ?? [];
                            $obe_cpls = $obe_attainment['cpls'] ?? [];
                            $obe_chart_labels = $obe_attainment['chart_labels'] ?? [];
                            $obe_chart_values = $obe_attainment['chart_values'] ?? [];
                            $obe_available_semesters = $obe_attainment['available_semesters'] ?? [1, 2, 3, 4];
                            $obe_filter_sem = $obe_attainment['filter_semester'] ?? $obe_filter_semester;
                            $obe_is_ongoing = !empty($obe_attainment['is_semester_ongoing']);
                            $obe_total_sks_lulus = $obe_attainment['total_sks_lulus'] ?? ($summary['total_sks_lulus'] ?? 0);
                            $obe_total_sks_ongoing = $obe_attainment['total_sks_ongoing'] ?? 0;
                        ?>

                        <!-- Bar Filter Semester OBE & Cetak -->
                        <div class="obe-filter-bar no-print">
                            <div class="obe-filter-top">
                                <span class="obe-filter-heading font-weight-bold text-dark">
                                    <i class="fa fa-filter text-primary mr-1"></i> Filter Cakupan OBE:
                                </span>
                                <button type="button" class="btn btn-outline-secondary khs-btn-print d-flex align-items-center" onclick="window.print()">
                                    <i class="fa fa-print mr-2"></i> Cetak Transkrip OBE
                                </button>
                            </div>

                            <div class="obe-pills-list">
                                <?php
                                    $kumulatifUrl = base_url('akademik/transkrip?tab=obe&semester_obe=all') . (($is_admin || $is_dosen) ? '&student_id=' . $selected_student_id : '');
                                    $isKumulatif = ($obe_filter_sem === 'all');
                                ?>
                                <a href="<?= $kumulatifUrl ?>" class="obe-pill-btn <?= $isKumulatif ? 'active' : 'inactive' ?>">
                                    <i class="fa fa-globe"></i> Kumulatif (Semua Semester)
                                </a>

                                <?php foreach ($obe_available_semesters as $sem): ?>
                                    <?php
                                        $semUrl = base_url('akademik/transkrip?tab=obe&semester_obe=' . $sem) . (($is_admin || $is_dosen) ? '&student_id=' . $selected_student_id : '');
                                        $isSemActive = ((string)$obe_filter_sem === (string)$sem);
                                        $isOngoingBtn = ((int)$sem === (int)($student->semester ?? 4));
                                    ?>
                                    <a href="<?= $semUrl ?>" class="obe-pill-btn <?= $isSemActive ? 'active' : ($isOngoingBtn ? 'ongoing' : 'inactive') ?>">
                                        Semester <?= $sem ?> <?= $isOngoingBtn ? '<small class="ml-1 font-weight-bold" style="font-size: 10px;">(Berjalan)</small>' : '' ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- KOP SURAT RESMI OBE (HANYA MUNCUL SAAT CETAK / PRINT) -->
                        <div class="print-only-letterhead">
                            <div class="d-flex align-items-center justify-content-between pb-2 mb-2" style="border-bottom: 3px double #0f172a;">
                                <div style="width: 75px; text-align: left; flex-shrink: 0;">
                                    <img src="<?= base_url('assets/images/smart-campus-icon.png') ?>" alt="Logo" style="width: 65px; height: auto;" onerror="this.src='<?= base_url('assets/images/favicon.png') ?>'">
                                </div>
                                <div class="text-center flex-grow-1 px-2" style="line-height: 1.25;">
                                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; color: #1e293b; text-transform: uppercase;">
                                        KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI
                                    </div>
                                    <div style="font-size: 15px; font-weight: 800; color: #0f172a; letter-spacing: 0.8px; text-transform: uppercase; margin-top: 2px;">
                                        UNIVERSITAS SMART CAMPUS INDONESIA
                                    </div>
                                    <div style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase; margin-top: 1px;">
                                        PUSAT PENGEMBANGAN KURIKULUM &amp; PENJAMINAN MUTU OBE
                                    </div>
                                    <div style="font-size: 9px; color: #475569; margin-top: 3px;">
                                        Kampus Terpadu: Jl. Ringroad Smart Campus No. 101 | Telp: (021) 7788-9900 | Fax: (021) 7788-9901<br>
                                        Laman: https://smartcampus.ac.id | Pos-el: baak@smartcampus.ac.id
                                    </div>
                                </div>
                                <div style="width: 75px; flex-shrink: 0;"></div>
                            </div>
                            
                            <div class="text-center my-2">
                                <h5 style="margin: 0; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a;">
                                    TRANSKRIP CAPAIAN OUTCOME-BASED EDUCATION (OBE)
                                </h5>
                                <div style="font-size: 10.5px; font-weight: 600; color: #475569; margin-top: 2px;">
                                    Capaian Pembelajaran Lulusan (CPL) &amp; Capaian Pembelajaran Mata Kuliah (CPMK)
                                </div>
                            </div>

                            <!-- TABEL DATA MAHASISWA KHUSUS CETAK (RINGKAS & ANTI-POTONG HALAMAN) -->
                            <?php if ($student): ?>
                                <table class="table-meta-print">
                                    <tr>
                                        <td style="width: 14%; font-weight: 600;">NRP / NIM</td>
                                        <td style="width: 2%;">:</td>
                                        <td style="width: 34%; font-weight: 700;"><?= html_escape($student->nim ?? '') ?></td>
                                        <td style="width: 16%; font-weight: 600;">Jenjang</td>
                                        <td style="width: 2%;">:</td>
                                        <td style="width: 32%; font-weight: 700;"><?= html_escape($student->jenjang ?? 'Strata 1') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600;">Nama Mahasiswa</td>
                                        <td>:</td>
                                        <td style="font-weight: 700;"><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></td>
                                        <td style="font-weight: 600;">Program Studi</td>
                                        <td>:</td>
                                        <td style="font-weight: 700;"><?= html_escape($student->prodi ?? '') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600;">Dosen Wali</td>
                                        <td>:</td>
                                        <td style="font-weight: 700;"><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></td>
                                        <td style="font-weight: 600;">Semester / Status</td>
                                        <td>:</td>
                                        <td style="font-weight: 700;">Semester <?= html_escape($student->semester ?? '1') ?> (<?= html_escape($student->status_akademik ?? 'Aktif') ?>)</td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>

                        <!-- Panel Metadata Mahasiswa -->
                        <?php if ($student): ?>
                            <div class="khs-meta-box d-print-none no-print">
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">NRP</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px;"><?= html_escape($student->nim) ?></span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Jenjang Pendidikan</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->jenjang ?? 'Strata 1') ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">Nama</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px; letter-spacing: 0.3px;">
                                            <?= strtoupper(html_escape($student->nama_lengkap)) ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Program Studi</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->prodi) ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2">
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center mb-1 mb-md-0">
                                        <span class="khs-meta-label">Dosen Wali</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 d-flex align-items-center">
                                        <span class="khs-meta-label">Semester / Status</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold d-inline-flex align-items-center flex-wrap" style="gap: 6px;">
                                            <span>Semester <?= html_escape($student->semester ?? '1') ?></span>
                                            <span class="obe-meta-sep" style="margin: 0 2px;">&bull;</span>
                                            <span class="badge badge-success px-2 py-0.5 d-inline-flex align-items-center" style="border-radius: 4px; font-size: 11px; white-space: nowrap;">
                                                <i class="fa fa-check-circle mr-1"></i><?= html_escape($student->status_akademik ?? 'Aktif') ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- OBE Quick KPI Metric Strip -->
                            <div class="obe-stats-strip">
                                <div class="obe-stats-grid">
                                    <div class="obe-stat-card">
                                        <div class="obe-stat-icon icon-success">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        <div class="obe-stat-content">
                                            <span class="obe-stat-label">SKS Selesai (KHS Resmi)</span>
                                            <div class="obe-stat-val text-success">
                                                <?= (int)$obe_total_sks_lulus ?> <small class="text-muted font-weight-normal" style="font-size: 12px;">SKS</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($obe_total_sks_ongoing)): ?>
                                    <div class="obe-stat-card">
                                        <div class="obe-stat-icon icon-warning">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <div class="obe-stat-content">
                                            <span class="obe-stat-label">Semester Berjalan (Aktif)</span>
                                            <div class="obe-stat-val text-warning" style="color: #d97706 !important;">
                                                <?= (int)$obe_total_sks_ongoing ?> <small class="text-muted font-weight-normal" style="font-size: 12px;">SKS</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="obe-stat-card">
                                        <div class="obe-stat-icon icon-primary">
                                            <i class="fa fa-trophy"></i>
                                        </div>
                                        <div class="obe-stat-content">
                                            <span class="obe-stat-label">IPK Kumulatif Resmi</span>
                                            <div class="obe-stat-val text-primary">
                                                <?= !empty($summary['ipk_transkrip']) ? number_format((float)$summary['ipk_transkrip'], 2) : (!empty($summary['ipk']) ? number_format((float)$summary['ipk'], 2) : '3.82') ?> <small class="text-muted font-weight-normal" style="font-size: 12px;">/ 4.00</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Banner Notifikasi Semester Berjalan -->
                        <?php if ($obe_is_ongoing): ?>
                            <div class="mx-3 my-3 p-3 d-flex align-items-center no-print" style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;">
                                <div class="mr-3 text-warning">
                                    <i class="fa fa-clock-o" style="font-size: 24px; color: #d97706;"></i>
                                </div>
                                <div style="font-size: 13px; color: #92400e; line-height: 1.5;">
                                    <strong>Semester <?= (int)$obe_filter_sem ?> Merupakan Semester Berjalan (Sedang Ditempuh):</strong>
                                    <br>Mahasiswa baru saja memasuki Semester <?= (int)$obe_filter_sem ?> (beban terdaftar <strong><?= (int)$obe_total_sks_ongoing ?> SKS</strong>). Perkuliahan dan proses evaluasi oleh dosen pengampu saat ini masih berlangsung. Nilai akhir mata kuliah serta evaluasi CPL final akan diperbarui setelah masa perkuliahan dan evaluasi ujian semester berakhir.
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- OBE Section Cards & Visualizations -->
                        <div class="obe-content-wrapper">
                            <?php if (empty($obe_attainment)): ?>
                                <div class="p-5 text-center text-muted border border-dashed rounded-lg bg-light">
                                    <i class="fa fa-info-circle text-primary mb-2" style="font-size: 32px; display: block;"></i>
                                    <strong class="text-dark d-block mb-1">Data Ketercapaian OBE Belum Tersedia</strong>
                                    <span>Data pemetaan CPL dan asesmen OBE untuk mahasiswa ini belum diinisialisasi atau sedang dalam proses penyusunan kurikulum.</span>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <!-- Radar Chart CPL (5 cols) -->
                                    <div class="col-lg-5 mb-4">
                                        <div class="obe-card h-100 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h4 class="obe-card-title">Radar Ketercapaian CPL</h4>
                                                    <span class="badge badge-primary px-2.5 py-1" style="font-size: 11px; border-radius: 6px;">
                                                        Target Ambang: 70.0
                                                    </span>
                                                </div>
                                                <p class="obe-card-subtitle">
                                                    Poligon visualisasi profil kompetensi mahasiswa terhadap Capaian Pembelajaran Lulusan program studi.
                                                </p>
                                            </div>
                                            <div class="radar-chart-container position-relative py-2 my-auto" style="height: 290px;">
                                                <?php if ($obe_is_ongoing): ?>
                                                <div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3" style="top: 0; left: 0; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(1px); z-index: 5; border-radius: 8px;">
                                                    <i class="fa fa-clock-o text-warning mb-2" style="font-size: 26px;"></i>
                                                    <strong style="font-size: 13px; color: #92400e;">Semester <?= (int)$obe_filter_sem ?> Sedang Berjalan</strong>
                                                    <small class="text-muted mt-1" style="font-size: 11px; max-width: 260px;">Nilai CPL final semester ini akan terbit setelah evaluasi akhir semester selesai.</small>
                                                </div>
                                                <?php endif; ?>
                                                <canvas id="cplRadarChart"></canvas>
                                            </div>
                                            <div class="pt-3 mt-2 border-top d-flex align-items-center justify-content-center flex-wrap" style="gap: 16px;">
                                                <div class="obe-legend-pill">
                                                    <span class="obe-legend-dot" style="background: #0288d1;"></span>
                                                    <span class="font-weight-600 text-dark">Capaian Mahasiswa</span>
                                                </div>
                                                <div class="obe-legend-pill">
                                                    <span class="obe-legend-dot" style="background: #10b981;"></span>
                                                    <span class="font-weight-600 text-dark">Target Minimal (70)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rincian Dimensi CPL (7 cols) -->
                                    <div class="col-lg-7 mb-4">
                                        <div class="obe-card h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <h4 class="obe-card-title">Rincian Capaian per Dimensi CPL</h4>
                                            </div>
                                            <p class="obe-card-subtitle">
                                                <?= $obe_is_ongoing ? 'Status pemenuhan CPL pada semester berjalan yang sedang ditempuh.' : 'Nilai rata-rata akumulatif yang diperoleh dari seluruh instrumen CPMK mata kuliah terkait.' ?>
                                            </p>
                                            <div class="obe-cpl-list">
                                                <?php if (empty($obe_cpls)): ?>
                                                    <div class="p-4 text-center text-muted">Tidak ada data CPL yang terdaftar.</div>
                                                <?php else: ?>
                                                    <?php foreach ($obe_cpls as $c): ?>
                                                        <?php
                                                            $isOngoingCpl = !empty($c['is_ongoing']);
                                                            $score = $c['skor'] !== null ? (float)$c['skor'] : null;
                                                            $is_passed = ($score !== null && $score >= 70.0);
                                                            $fillClass = $isOngoingCpl ? 'ongoing' : ($is_passed ? 'passed' : 'need-improve');
                                                        ?>
                                                        <div class="obe-cpl-item">
                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="obe-cpl-code-pill"><?= html_escape($c['kode_cpl']) ?></span>
                                                                    <span class="text-muted font-weight-bold" style="font-size: 12px;">
                                                                        <?= html_escape($c['kategori']) ?>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <?php if ($isOngoingCpl): ?>
                                                                        <span class="badge badge-warning text-dark px-2.5 py-1 font-weight-bold" style="font-size: 11px;">
                                                                            <i class="fa fa-clock-o mr-1"></i> Sedang Berjalan
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="font-weight-bold mr-2 <?= $is_passed ? 'text-success' : 'text-danger' ?>" style="font-size: 13.5px;">
                                                                            <?= number_format((float)$score, 1) ?> / 100
                                                                        </span>
                                                                        <span class="badge <?= $is_passed ? 'badge-success' : 'badge-danger' ?> px-2.5 py-1 font-weight-bold" style="font-size: 11px;">
                                                                            <i class="fa <?= $is_passed ? 'fa-check' : 'fa-exclamation-triangle' ?> mr-1"></i><?= $is_passed ? 'Tercapai' : 'Perlu Peningkatan' ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="obe-progress-track">
                                                                <div class="obe-progress-fill <?= $fillClass ?>" style="width: <?= $isOngoingCpl ? '25%' : min(100, max(0, (float)$score)) ?>%;"></div>
                                                            </div>
                                                            <p class="mb-0 text-muted" style="font-size: 11.5px; line-height: 1.45;">
                                                                <?= html_escape($c['deskripsi']) ?>
                                                            </p>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion Transkrip Mata Kuliah & Rincian CPMK -->
                                <div class="obe-card mt-2">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                                        <div class="mb-2 mb-sm-0">
                                            <h4 class="obe-card-title">Transkrip Mata Kuliah &amp; Rincian CPMK (Portofolio OBE)</h4>
                                            <p class="obe-card-subtitle mb-0">Klik pada mata kuliah untuk membuka breakdown kalkulasi nilai CPMK dan komponen asesmen individual.</p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-success px-2.5 py-1 font-weight-bold mr-2" style="font-size: 11.5px; border-radius: 6px;">
                                                <i class="fa fa-check-circle mr-1"></i> Selesai (KHS Resmi)
                                            </span>
                                            <span class="badge badge-warning text-dark px-2.5 py-1 font-weight-bold" style="font-size: 11.5px; border-radius: 6px;">
                                                <i class="fa fa-clock-o mr-1"></i> Sedang Ditempuh
                                            </span>
                                        </div>
                                    </div>

                                    <?php if (empty($obe_courses)): ?>
                                        <div class="p-5 text-center text-muted border border-dashed rounded-lg">
                                            <i class="fa fa-book text-muted mb-2" style="font-size: 32px; display: block;"></i>
                                            Tidak ada mata kuliah yang terdaftar pada filter semester ini.
                                        </div>
                                    <?php else: ?>
                                        <div class="obe-courses-container">
                                            <?php foreach ($obe_courses as $idx => $cs): ?>
                                                <?php
                                                    $isOngoingCourse = !empty($cs->is_ongoing);
                                                    $finalGrade = strtoupper(trim((string)($cs->final_grade ?? '')));
                                                    $gradeClass = 'grade-a';
                                                    if ($isOngoingCourse) {
                                                        $gradeClass = 'grade-ongoing';
                                                    } elseif (strpos($finalGrade, 'A') !== false) {
                                                        $gradeClass = 'grade-a';
                                                    } elseif (strpos($finalGrade, 'B') !== false) {
                                                        $gradeClass = 'grade-b';
                                                    } else {
                                                        $gradeClass = 'grade-c';
                                                    }
                                                ?>
                                                <div class="obe-course-card">
                                                    <button type="button" class="obe-course-header-btn" onclick="toggleCourseAccordion(<?= $idx ?>)">
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($isOngoingCourse): ?>
                                                                <div class="obe-grade-box grade-ongoing" title="Sedang Ditempuh">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="obe-grade-box <?= $gradeClass ?>">
                                                                    <?= html_escape($cs->final_grade ?: '-') ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="d-flex align-items-center flex-wrap">
                                                                    <span class="obe-course-code-pill"><?= html_escape($cs->kode_mk) ?></span>
                                                                    <span class="obe-course-title"><?= html_escape($cs->nama_mk) ?></span>
                                                                    <?php if ($isOngoingCourse): ?>
                                                                        <span class="obe-ongoing-badge">Sedang Ditempuh</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="obe-course-meta">
                                                                    <span><?= (int)$cs->sks ?> SKS</span>
                                                                    <span class="obe-meta-sep">&bull;</span>
                                                                    <span>Semester <?= (int)$cs->semester ?></span>
                                                                    <span class="obe-meta-sep">&bull;</span>
                                                                    <span>Kelas <?= html_escape($cs->kelas) ?></span>
                                                                    <span class="obe-meta-sep">&bull;</span>
                                                                    <span><i class="fa fa-user-circle-o text-muted mr-1"></i> Dosen: <?= html_escape($cs->nama_dosen ?: 'Dosen Pengampu') ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-2 mt-sm-0">
                                                            <div class="text-right mr-3">
                                                                <?php if ($isOngoingCourse): ?>
                                                                    <span class="font-weight-bold d-block" style="font-size: 12.5px; color: #d97706;">Sedang Ditempuh</span>
                                                                    <small class="text-muted" style="font-size: 10.5px;">Belum Terbit KHS</small>
                                                                <?php else: ?>
                                                                    <span class="font-weight-bold text-dark d-block" style="font-size: 13.5px;">
                                                                        Nilai: <?= $cs->final_score !== null ? number_format((float)$cs->final_score, 1) : '-' ?>
                                                                    </span>
                                                                    <small class="text-muted" style="font-size: 11px;">Indeks: <?= number_format((float)$cs->final_index, 1) ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="obe-cpmk-count-pill <?= $isOngoingCourse ? 'ongoing' : 'passed' ?>">
                                                                <?= count($cs->cpmk_details) ?> CPMK OBE
                                                            </span>
                                                            <div class="obe-chevron-pill accordion-arrow-<?= $idx ?>">
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                    <div id="courseAccordionContent-<?= $idx ?>" class="obe-course-content">
                                                        <?php if ($isOngoingCourse): ?>
                                                            <div class="mb-3 p-2.5 rounded-lg border text-dark d-flex align-items-center" style="background: #fef3c7; border-color: #fde68a !important; font-size: 12px;">
                                                                <i class="fa fa-info-circle text-warning mr-2" style="font-size: 16px;"></i>
                                                                <span>Mata kuliah ini sedang ditempuh pada Semester <?= $cs->semester ?>. Komponen di bawah merupakan rencana asesmen yang dinilai oleh dosen pengampu.</span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="mb-3 p-2.5 rounded-lg border text-primary d-flex align-items-center" style="background: #f0f9ff; border-color: #bae6fd; font-size: 12px;">
                                                                <i class="fa fa-check-circle mr-2 text-primary" style="font-size: 16px;"></i>
                                                                <span>Rumus Standar OBE: <strong>Capaian CPMK = &sum;(Nilai Komponen &times; Bobot) &divide; Total Bobot CPMK</strong></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($cs->cpmk_details)): ?>
                                                            <div class="row">
                                                                <?php foreach ($cs->cpmk_details as $cpmk): ?>
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="obe-cpmk-card h-100">
                                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                                <span class="badge badge-primary px-2 py-0.5 font-weight-bold font-mono" style="font-size: 11px;">
                                                                                    <?= html_escape($cpmk['kode_cpmk']) ?>
                                                                                </span>
                                                                                <span class="font-weight-bold text-dark" style="font-size: 12.5px;">
                                                                                    Capaian: 
                                                                                    <?php if ($cpmk['capaian'] !== null): ?>
                                                                                        <span class="text-primary font-weight-bold"><?= number_format($cpmk['capaian'], 1) ?></span>
                                                                                    <?php else: ?>
                                                                                        <span class="text-warning font-weight-bold" style="color: #d97706 !important;">Proses Evaluasi</span>
                                                                                    <?php endif; ?>
                                                                                </span>
                                                                            </div>
                                                                            <p class="text-muted mb-2" style="font-size: 11.5px; line-height: 1.4;">
                                                                                <?= html_escape($cpmk['deskripsi']) ?>
                                                                            </p>
                                                                            <div class="border-top pt-2 mt-2">
                                                                                <small class="text-muted font-weight-bold text-uppercase d-block mb-1" style="font-size: 9.5px; letter-spacing: 0.5px;">Komponen Pengukur:</small>
                                                                                <?php foreach ($cpmk['komponen'] as $cmp): ?>
                                                                                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light" style="font-size: 11.5px;">
                                                                                        <span class="text-dark">
                                                                                            <?= html_escape($cmp['nama_asesmen']) ?>
                                                                                            <small class="text-muted">(<?= number_format($cmp['bobot_persen'], 0) ?>%)</small>
                                                                                        </span>
                                                                                        <?php if ($cmp['nilai'] !== null): ?>
                                                                                            <span class="font-weight-bold font-mono text-dark"><?= number_format((float)$cmp['nilai'], 1) ?></span>
                                                                                        <?php else: ?>
                                                                                            <span class="badge badge-light border text-muted px-1.5 py-0.5" style="font-size: 10px;">Belum Dinilai</span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TANDA TANGAN RESMI CETAK OBE -->
                        <div class="print-only-signatures px-4">
                            <div class="row mb-3" style="font-size: 11px;">
                                <div class="col-7"></div>
                                <div class="col-5 text-center">
                                    Diterbitkan di: Kampus Terpadu<br>
                                    Pada tanggal: <?= $tgl_cetak_indo ?>
                                </div>
                            </div>
                            <div class="row text-center mt-3" style="font-size: 11.5px;">
                                <div class="col-4">
                                    <p class="mb-4">Mahasiswa,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></u></p>
                                    <p class="text-muted">NRP. <?= html_escape($student->nim ?? '') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-4">Dosen Wali,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></u></p>
                                    <p class="text-muted">NIP. <?= html_escape($student->nip_dosen_wali ?? '-') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-4">Ketua Program Studi,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u>Dr. Ir. Hendra Setiawan, M.T.</u></p>
                                    <p class="text-muted">NIP. 197805122003121002</p>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /#paneTranskripObe -->
                    <?php endif; ?>

                </div>

<?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN untuk Radar Chart OBE -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleKhsCardFullscreen() {
    var elem = document.getElementById('khsContainerCard');
    if (!document.fullscreenElement) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}

// Unified Transkrip Tab Switcher
function switchTranskripTab(tab) {
    var paneNilai = document.getElementById('paneTranskripNilai');
    var paneObe = document.getElementById('paneTranskripObe');
    var btnNilai = document.getElementById('btnTabNilai');
    var btnObe = document.getElementById('btnTabObe');
    
    if (tab === 'obe') {
        if (paneNilai) paneNilai.style.display = 'none';
        if (paneObe) paneObe.style.display = 'block';
        if (btnNilai) btnNilai.classList.remove('active');
        if (btnObe) btnObe.classList.add('active');
        
        try {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', 'obe');
            window.history.replaceState(null, '', url.toString());
        } catch(e) {}
        
        setTimeout(initObeRadarChart, 60);
    } else {
        if (paneNilai) paneNilai.style.display = 'block';
        if (paneObe) paneObe.style.display = 'none';
        if (btnNilai) btnNilai.classList.add('active');
        if (btnObe) btnObe.classList.remove('active');
        
        try {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', 'nilai');
            window.history.replaceState(null, '', url.toString());
        } catch(e) {}
    }
}

// Inisialisasi Chart.js Radar Chart
var cplRadarChartInstance = null;
function initObeRadarChart() {
    var canvas = document.getElementById('cplRadarChart');
    if (!canvas) return;
    
    if (cplRadarChartInstance) {
        cplRadarChartInstance.resize();
        return;
    }
    
    var labels = <?= json_encode($obe_chart_labels ?? []) ?>;
    var dataValues = <?= json_encode($obe_chart_values ?? []) ?>;
    if (!labels || labels.length === 0) return;
    
    var ctx = canvas.getContext('2d');
    cplRadarChartInstance = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Capaian Mahasiswa',
                    data: dataValues,
                    backgroundColor: 'rgba(2, 136, 209, 0.25)',
                    borderColor: '#0288d1',
                    pointBackgroundColor: '#0288d1',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#0288d1',
                    borderWidth: 2
                },
                {
                    label: 'Batas Minimal (70)',
                    data: labels.map(function() { return 70; }),
                    backgroundColor: 'transparent',
                    borderColor: '#10b981',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 15,
                    bottom: 10,
                    left: 15,
                    right: 15
                }
            },
            scales: {
                r: {
                    angleLines: { color: '#e2e8f0' },
                    grid: { color: '#f1f5f9' },
                    pointLabels: {
                        font: { size: 11.5, weight: 'bold', family: "'Open Sans', sans-serif" },
                        color: '#1e293b',
                        padding: 12
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: {
                        stepSize: 20,
                        font: { size: 9 },
                        backdropColor: 'rgba(255, 255, 255, 0.85)',
                        backdropPadding: 3,
                        color: '#64748b'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + Number(context.parsed.r).toFixed(1) + ' / 100';
                        }
                    }
                }
            }
        }
    });
}

// Toggle Course Accordion
function toggleCourseAccordion(idx) {
    var content = document.getElementById('courseAccordionContent-' + idx);
    var arrow = document.querySelector('.accordion-arrow-' + idx);
    if (!content) return;
    
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        content.style.display = 'none';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var initialTab = <?= json_encode($active_tab ?? 'nilai') ?>;
    if (initialTab === 'obe') {
        switchTranskripTab('obe');
    }
});
</script>
