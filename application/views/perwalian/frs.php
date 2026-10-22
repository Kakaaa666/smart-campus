<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$period_label = !empty($selected_periode) ? $selected_periode : (!empty($academic_period['tahun_akademik']) ? (explode('/', $academic_period['tahun_akademik'])[0] . ' - ' . $academic_period['semester_akademik']) : '2024 - Genap');
$clean_year   = explode('/', $academic_period['tahun_akademik'] ?? '2024')[0];
$clean_sem    = ucfirst(strtolower($academic_period['semester_akademik'] ?? 'Ganjil'));

$validator_name = !empty($krs->nama_validator) ? $krs->nama_validator : (!empty($advisor->nama_lengkap) ? $advisor->nama_lengkap : 'Yekti Asmoro Kanthi, S.Si., M.A.B');
$validation_time = !empty($krs->diproses_at) ? date('d/m/Y H:i:s', strtotime($krs->diproses_at)) : '31/08/2026 04:09:26';
$kaprodi_name = 'Dr. Subari, S.Kom., M.Kom';
$advisor_name = !empty($advisor->nama_lengkap) ? $advisor->nama_lengkap : 'Sugeng Widodo, S.Kom., M.Kom';

// Data Mahasiswa Terkait
$student_name     = !empty($target_mahasiswa->nama_lengkap) ? $target_mahasiswa->nama_lengkap : 'Mahasiswa';
$student_nim      = !empty($target_mahasiswa->nim) ? $target_mahasiswa->nim : '-';
$student_telepon  = !empty($target_mahasiswa->telepon) ? $target_mahasiswa->telepon : '081234567890';
$student_email    = !empty($target_mahasiswa->email) ? $target_mahasiswa->email : ($student_nim . '@mhs.campuss.ac.id');
$student_prodi    = !empty($target_mahasiswa->prodi) ? $target_mahasiswa->prodi : 'Teknik Informatika';
$student_fakultas = !empty($target_mahasiswa->fakultas) ? $target_mahasiswa->fakultas : 'Fakultas Teknologi Informasi';
$student_semester = !empty($target_mahasiswa->semester) ? (int)$target_mahasiswa->semester : 1;
$student_angkatan = !empty($target_mahasiswa->angkatan) ? $target_mahasiswa->angkatan : '2023';
$student_status   = !empty($target_mahasiswa->status_akademik) ? $target_mahasiswa->status_akademik : 'Aktif';
$student_alamat   = !empty($target_mahasiswa->alamat) ? $target_mahasiswa->alamat : 'Belum diisi';
$student_foto     = !empty($target_mahasiswa->foto) ? base_url('assets/images/' . $target_mahasiswa->foto) : base_url('assets/images/avatar-4.png');
$total_sks_frs    = !empty($krs->total_sks) ? (int)$krs->total_sks : 0;
?>

<style>
/* ======================================================== */
/* SMART CAMPUS FRS - HARMONIZED WEB DESIGN SYSTEM          */
/* ======================================================== */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.pcoded-inner-content,
.main-body,
.page-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}
/* 1. KARTU UTAMA FRS & HEADER BAR RESPONSIVE */
.frs-main-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    margin-bottom: 24px !important;
    overflow: hidden !important;
}

.frs-header-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: nowrap;
    position: relative;
}

.frs-nav-tabs-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
    flex: 1 1 auto;
    min-width: 0;
}

.frs-nav-tabs-wrapper::-webkit-scrollbar {
    display: none;
}

.frs-nav-tabs {
    display: flex;
    flex-wrap: nowrap;
    white-space: nowrap;
    border-bottom: none !important;
    background: transparent !important;
    padding: 0 8px !important;
    margin-bottom: 0 !important;
}

.frs-nav-tabs .nav-link {
    border: none !important;
    color: #64748b !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    padding: 16px 20px !important;
    border-radius: 0 !important;
    background: transparent !important;
    border-bottom: 3px solid transparent !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.frs-nav-tabs .nav-link:hover {
    color: #0284c7 !important;
    background: rgba(2, 132, 199, 0.04) !important;
}

.frs-nav-tabs .nav-link.active {
    color: #0284c7 !important;
    background: #ffffff !important;
    font-weight: 800 !important;
    border-bottom: 3px solid #0284c7 !important;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.02);
}

.frs-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    flex-shrink: 0;
}

/* 2. BANNER RINCIAN AKADEMIK MAHASISWA */
.frs-academic-banner {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 20px;
}

.frs-info-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.frs-info-item {
    display: flex;
    align-items: flex-start;
    font-size: 13.5px;
    line-height: 1.5;
}

.frs-info-label {
    width: 135px;
    min-width: 135px;
    color: #64748b;
    font-weight: 500;
}

.frs-info-sep {
    width: 14px;
    min-width: 14px;
    color: #64748b;
    font-weight: 600;
}

.frs-info-value {
    color: #0f172a;
    flex: 1 1 auto;
    min-width: 0;
    word-break: break-word;
}

/* 3. MODERN RESPONSIVE TABLE */
.table-frs-modern {
    width: 100%;
    min-width: 780px;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.table-frs-modern thead th {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important;
    color: #0f3d64 !important;
    font-size: 12.5px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 13px 14px !important;
    border-bottom: 1.5px solid #cbd5e1 !important;
    border-top: none !important;
    border-left: 1px solid #f1f5f9;
    border-right: 1px solid #f1f5f9;
    vertical-align: middle !important;
    white-space: nowrap;
}

.table-frs-modern thead th:first-child {
    border-left: none;
}
.table-frs-modern thead th:last-child {
    border-right: none;
}

.table-frs-modern tbody tr {
    transition: background 0.15s ease;
}

.table-frs-modern tbody tr:hover {
    background-color: #f8fafc !important;
}

.table-frs-modern tbody td {
    padding: 12px 14px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    border-left: 1px solid #f8fafc;
    border-right: 1px solid #f8fafc;
}

.table-frs-modern tbody td:first-child {
    border-left: none;
}
.table-frs-modern tbody td:last-child {
    border-right: none;
}

.dosen-pengampu-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.dosen-pengampu-avatar {
    width: 28px;
    height: 28px;
    min-width: 28px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0284c7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.dosen-pengampu-name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.35;
    word-break: normal;
}

/* 4. PESERTA TERHUBUNG CARD */
.frs-participant-box {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.frs-participant-box-header {
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.frs-participant-box-body {
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.frs-participant-row {
    padding: 8px 10px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #f1f5f9;
    transition: all 0.15s ease;
}

.frs-participant-row:hover {
    background: #f8fafc;
    border-color: #e2e8f0;
}

.frs-participant-row .custom-control {
    min-height: 20px;
    margin-right: 0;
    padding-left: 22px;
    width: 100%;
}

.frs-participant-label {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 8px;
    width: 100%;
    margin-bottom: 0 !important;
    cursor: pointer;
}

.frs-participant-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.35;
    word-break: normal;
    flex: 1 1 auto;
    min-width: 0;
}

.frs-participant-badge {
    font-size: 10px !important;
    font-weight: 700 !important;
    padding: 3px 8px !important;
    border-radius: 6px !important;
    white-space: nowrap !important;
    flex-shrink: 0;
}

/* Responsive Media Queries */
@media (max-width: 991.98px) {
    .frs-header-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .frs-nav-tabs-wrapper {
        width: 100%;
        border-bottom: 1px solid #e2e8f0;
    }
    .frs-toolbar {
        width: 100%;
        justify-content: space-between;
        background: #ffffff;
        padding: 10px 16px;
    }
}

@media (max-width: 575.98px) {
    .frs-nav-tabs .nav-link {
        padding: 12px 14px !important;
        font-size: 12.5px !important;
    }
    .frs-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .frs-toolbar select,
    .frs-toolbar button {
        width: 100% !important;
    }
    .frs-academic-banner {
        padding: 12px;
    }
    .frs-info-item {
        flex-direction: column;
        gap: 2px;
        margin-bottom: 6px;
    }
    .frs-info-label {
        width: auto;
        min-width: unset;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .frs-info-sep {
        display: none;
    }
    .frs-info-value {
        font-size: 13px;
    }
}

/* Print Stylesheet */
@media print {
    body {
        background: #ffffff !important;
        font-family: Arial, sans-serif !important;
    }
    .pcoded-header,
    .pcoded-navbar,
    .pcoded-main-container,
    .no-print,
    .breadcrumb-title,
    .page-header,
    .frs-profile-card,
    .card-footer,
    #formKirimKomentar {
        display: none !important;
    }
    .pcoded-content,
    .pcoded-inner-content,
    .main-body,
    .page-wrapper,
    .page-body {
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        width: 100% !important;
    }
    .frs-main-card {
        border: none !important;
        box-shadow: none !important;
    }
    .frs-nav-tabs,
    .frs-header-bar {
        display: none !important;
    }
    .tab-content > .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
    #content-jadwal, #content-wajib {
        display: none !important;
    }
    .jadwal-print-header,
    .print-only-signatures {
        display: block !important;
        margin-bottom: 20px;
    }
    .jadwal-print-header {
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
}

@media screen {
    .jadwal-print-header,
    .print-only-signatures {
        display: none !important;
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
                        <h5 class="font-weight-bold mb-1">FORMULIR RENCANA STUDI (FRS RESMI)</h5>
                        <p class="mb-2">Tahun Akademik <?= html_escape($clean_year) ?> - Semester <?= strtoupper(html_escape($clean_sem)) ?></p>
                        <div class="row text-left my-2" style="font-size: 12px;">
                            <div class="col-6">
                                <div><strong>NRP / NIM:</strong> <?= html_escape($student_nim) ?></div>
                                <div><strong>Nama Mahasiswa:</strong> <?= html_escape($student_name) ?></div>
                                <div><strong>Program Studi:</strong> <?= html_escape($student_prodi) ?></div>
                            </div>
                            <div class="col-6 text-right">
                                <div><strong>Dosen Wali:</strong> <?= html_escape($advisor_name) ?></div>
                                <div><strong>Total Beban SKS:</strong> <?= (int)$total_sks_frs ?> SKS</div>
                                <div><strong>Status FRS:</strong> <?= html_escape($krs ? strtoupper($krs->status) : 'DISETUJUI') ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($is_admin_preview)): ?>
                        <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap no-print mb-3 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1.5px solid #bae6fd;">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <i class="fa fa-info-circle fa-2x text-primary mr-3"></i>
                                <div>
                                    <strong class="text-dark" style="font-size: 13.5px;">Mode Pratinjau Administrator:</strong>
                                    <div class="text-muted" style="font-size: 12px;">Melihat FRS untuk: <strong><?= html_escape($student_name) ?> (<?= html_escape($student_nim) ?>)</strong> &bull; <?= html_escape($student_prodi) ?></div>
                                </div>
                            </div>
                            <?php if (!empty($all_students)): ?>
                                <div class="d-flex align-items-center mt-2 mt-md-0">
                                    <label class="mr-2 mb-0 font-weight-bold text-dark" style="font-size: 13px;">Ganti Mahasiswa:</label>
                                    <select class="form-control form-control-sm font-weight-bold" style="width: auto; max-width: 290px; border-radius: 8px;" onchange="window.location.href='<?= base_url('perwalian/frs') ?>?mahasiswa_id=' + this.value + '&periode=<?= urlencode($period_label) ?>'">
                                        <?php foreach ($all_students as $st): ?>
                                            <option value="<?= (int)$st->id ?>" <?= ((int)$st->id === (int)$target_mahasiswa->id) ? 'selected' : '' ?>>
                                                <?= html_escape($st->nim) ?> - <?= html_escape($st->nama_lengkap) ?> (<?= html_escape($st->prodi ?: 'Informatika') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- ======================================================== -->
                    <!-- KARTU UTAMA FRS (TABS PENGAMBILAN, JADWAL, WAJIB)         -->
                    <!-- ======================================================== -->
                    <div class="frs-main-card">
                        <!-- HEADER DENGAN TAB NAVIGASI & ACTION TOOLBAR -->
                        <div class="frs-header-bar no-print">
                            <!-- Tabs Navigasi Modern (Scrollable di layar kecil tanpa wrap berantakan) -->
                            <div class="frs-nav-tabs-wrapper">
                                <ul class="nav frs-nav-tabs border-0" id="frsTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-pengambilan" data-toggle="tab" href="#content-pengambilan" role="tab">
                                            <i class="fa fa-file-text-o mr-2 text-primary"></i> Pengambilan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-jadwal" data-toggle="tab" href="#content-jadwal" role="tab">
                                            <i class="fa fa-calendar-check-o mr-2 text-info"></i> Jadwal Perkuliahan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-wajib" data-toggle="tab" href="#content-wajib" role="tab">
                                            <i class="fa fa-book mr-2 text-warning"></i> Mata Kuliah Wajib
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Toolbar Filter Periode & Cetak / Eksport -->
                            <div class="frs-toolbar">
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <span class="text-muted font-weight-bold small d-none d-sm-inline">Periode:</span>
                                    <select id="periodeSelect" class="form-control form-control-sm bg-white font-weight-bold" style="border: 1px solid #cbd5e1; border-radius: 8px; width: auto; font-size: 13px;" onchange="window.location.href='<?= base_url('perwalian/frs') ?>?periode=' + encodeURIComponent(this.value) + '<?= !empty($is_admin_preview) ? '&mahasiswa_id=' . (int)$target_mahasiswa->id : '' ?>'">
                                        <?php if (!empty($available_periods)): ?>
                                            <?php foreach ($available_periods as $ap): ?>
                                                <?php $is_sel = ($period_label === $ap['label'] || (strpos($period_label, $ap['label']) !== false) || ($selected_periode === $ap['label'])); ?>
                                                <option value="<?= htmlspecialchars($ap['label']) ?>" <?= $is_sel ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($ap['display_label'] ?? $ap['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="2024 - Genap" <?= (strpos($period_label, '2024') !== false && strpos(strtolower($period_label), 'genap') !== false) ? 'selected' : '' ?>>2024 - Genap (Semester 4 - Aktif)</option>
                                            <option value="2024 - Ganjil" <?= (strpos($period_label, '2024') !== false && strpos(strtolower($period_label), 'ganjil') !== false) ? 'selected' : '' ?>>2024 - Ganjil (Semester 3)</option>
                                            <option value="2023 - Genap" <?= (strpos($period_label, '2023') !== false && strpos(strtolower($period_label), 'genap') !== false) ? 'selected' : '' ?>>2023 - Genap (Semester 2)</option>
                                            <option value="2023 - Ganjil" <?= (strpos($period_label, '2023') !== false && strpos(strtolower($period_label), 'ganjil') !== false) ? 'selected' : '' ?>>2023 - Ganjil (Semester 1)</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold d-inline-flex align-items-center justify-content-center" style="background: #0284c7; border-color: #0284c7; border-radius: 8px; font-size: 12.5px; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);" onclick="window.print()">
                                    <i class="fa fa-print mr-1.5" style="font-size: 13.5px;"></i> Cetak / Eksport
                                </button>
                            </div>
                        </div>

                        <!-- BODY CONTENT SETIAP TAB -->
                        <div class="card-body p-4 tab-content" id="frsTabsContent">

                            <!-- ======================================================== -->
                            <!-- TAB 1: PENGAMBILAN (FORMULIR UTAMA FRS)                  -->
                            <!-- ======================================================== -->
                            <div class="tab-pane fade show active" id="content-pengambilan" role="tabpanel">
                                
                                <!-- Banner Rincian Akademis Mahasiswa (Responsif & Rapi Sejajar) -->
                                <div class="frs-academic-banner mb-4">
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                                            <div class="frs-info-grid">
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">NRP / NIM</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value font-monospace font-weight-bold"><?= html_escape($student_nim) ?></span>
                                                </div>
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">Nama Mahasiswa</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value text-uppercase font-weight-bold"><?= html_escape($student_name) ?></span>
                                                </div>
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">Fakultas / Prodi</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value font-weight-500"><?= html_escape($student_fakultas) ?> / <?= html_escape($student_prodi) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="frs-info-grid">
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">Dosen Wali</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value font-weight-bold"><?= html_escape($advisor_name) ?></span>
                                                </div>
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">Status Validasi</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value">
                                                        <?php if ($krs && $krs->status === 'DISETUJUI'): ?>
                                                            <span class="badge badge-success px-2.5 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-check-circle mr-1"></i> DISETUJUI</span>
                                                        <?php elseif ($krs && $krs->status === 'MENUNGGU'): ?>
                                                            <span class="badge badge-warning px-2.5 py-1 font-weight-bold text-dark" style="border-radius: 6px;"><i class="fa fa-clock-o mr-1"></i> Menunggu Validasi</span>
                                                        <?php elseif ($krs && $krs->status === 'DITOLAK'): ?>
                                                            <span class="badge badge-danger px-2.5 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-times-circle mr-1"></i> Ditolak</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary px-2.5 py-1 font-weight-bold" style="border-radius: 6px;">Draf Mahasiswa</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="frs-info-item">
                                                    <span class="frs-info-label">Divalidasi Oleh</span>
                                                    <span class="frs-info-sep">:</span>
                                                    <span class="frs-info-value font-weight-500">
                                                        <?= html_escape($validator_name) ?> 
                                                        <span class="text-muted font-italic small d-inline-block">(<?= $validation_time ?>)</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabel Mata Kuliah FRS (Elegan & Modern) -->
                                <div class="table-responsive">
                                    <table class="table table-frs-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px; min-width: 50px;">No</th>
                                                <th style="width: 130px; min-width: 110px;">Kode MK</th>
                                                <th style="min-width: 220px;">Nama Mata Kuliah</th>
                                                <th class="text-center" style="width: 80px; min-width: 75px;">Kelas</th>
                                                <th class="text-center" style="width: 90px; min-width: 85px;">SKS</th>
                                                <th class="text-center" style="width: 70px; min-width: 65px;">Ke-</th>
                                                <th style="width: 240px; min-width: 220px;">Dosen Pengampu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($krs->mata_kuliah)): ?>
                                                <?php foreach ($krs->mata_kuliah as $idx => $mk): 
                                                    $dosen_display = (!empty($mk->nama_dosen) && trim($mk->nama_dosen) !== '-') 
                                                        ? $mk->nama_dosen 
                                                        : (class_exists('M_akademik') ? M_akademik::resolve_default_lecturer($mk)['nama'] : 'Dr. Ir. Hendra Setiawan, M.T.');
                                                ?>
                                                    <tr>
                                                        <td class="text-center font-weight-bold text-muted"><?= $idx + 1 ?></td>
                                                        <td>
                                                            <span class="font-weight-bold px-2 py-0.5" style="background: #f1f5f9; color: #334155; border-radius: 6px; font-size: 12.5px; font-family: monospace; border: 1px solid #e2e8f0; display: inline-block;">
                                                                <?= html_escape($mk->kode_mk) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-bold text-dark" style="font-size: 14px;">
                                                                <?= html_escape(strtoupper($mk->nama_mk)) ?>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-1" style="gap: 6px;">
                                                                <span class="badge badge-light border text-muted px-2 py-0" style="font-size: 11px;">
                                                                    <?= html_escape($mk->kategori_label ?? 'Wajib') ?>
                                                                </span>
                                                                <?php if (!empty($mk->prodi)): ?>
                                                                    <small class="text-muted" style="font-size: 11px;">&bull; <?= html_escape($mk->prodi) ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-secondary px-2.5 py-1 font-weight-bold" style="border-radius: 6px; font-size: 12px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                                                <?= html_escape($mk->kelas) ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge px-2.5 py-1 font-weight-bold" style="border-radius: 6px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                                                <?= (int)$mk->sks ?> SKS
                                                            </span>
                                                        </td>
                                                        <td class="text-center font-weight-bold text-secondary">
                                                            <?= !empty($mk->ke) ? (int)$mk->ke : 1 ?>
                                                        </td>
                                                        <td>
                                                            <div class="dosen-pengampu-wrap">
                                                                <div class="dosen-pengampu-avatar">
                                                                    <i class="fa fa-user"></i>
                                                                </div>
                                                                <div class="dosen-pengampu-name">
                                                                    <?= html_escape($dosen_display) ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted">
                                                        <i class="fa fa-folder-open-o text-muted mb-2 d-block" style="font-size: 32px;"></i>
                                                        Belum ada mata kuliah yang tersimpan di FRS periode ini.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: #f8fafc; font-weight: 700;">
                                                <td colspan="4" class="text-right py-3 px-3 text-secondary" style="font-size: 14px;">
                                                    TOTAL BEBAN STUDI TERPILIH:
                                                </td>
                                                <td class="text-center py-3 px-3">
                                                    <span class="badge badge-success px-3 py-1.5" style="font-size: 13px; border-radius: 8px;">
                                                        <?= (int)$total_sks_frs ?> SKS
                                                    </span>
                                                </td>
                                                <td colspan="2" class="text-center text-muted font-weight-normal small">Status: Valid Terdaftar</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- TANDA TANGAN RESMI CETAK (HANYA MUNCUL SAAT PRINT) -->
                                <div class="print-only-signatures mt-5">
                                    <div class="row text-center" style="font-size: 12px;">
                                        <div class="col-4">
                                            <p class="mb-5">Mahasiswa,</p>
                                            <br><br>
                                            <p class="font-weight-bold mb-0"><u><?= strtoupper(html_escape($student_name)) ?></u></p>
                                            <p class="text-muted">NRP / NIM: <?= html_escape($student_nim) ?></p>
                                        </div>
                                        <div class="col-4">
                                            <p class="mb-5">Dosen Wali,</p>
                                            <br><br>
                                            <p class="font-weight-bold mb-0"><u><?= html_escape($advisor_name) ?></u></p>
                                            <p class="text-muted">NIP. <?= html_escape(!empty($advisor->nip) ? $advisor->nip : '198204152010121004') ?></p>
                                        </div>
                                        <div class="col-4">
                                            <p class="mb-5">Ketua Program Studi,</p>
                                            <br><br>
                                            <p class="font-weight-bold mb-0"><u><?= html_escape($kaprodi_name) ?></u></p>
                                            <p class="text-muted">NIP. 197805122003121002</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ======================================================== -->
                            <!-- TAB 2: JADWAL PERKULIAHAN (PERSIS DENGAN GAMBAR USER)     -->
                            <!-- ======================================================== -->
                            <div class="tab-pane fade" id="content-jadwal" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-frs-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px; min-width: 50px;">No</th>
                                                <th style="width: 130px; min-width: 110px;">Kode MK</th>
                                                <th style="min-width: 200px;">Nama MK</th>
                                                <th class="text-center" style="width: 75px; min-width: 70px;">SKS</th>
                                                <th class="text-center" style="width: 80px; min-width: 75px;">Kelas</th>
                                                <th style="width: 180px; min-width: 160px;">Hari / Waktu</th>
                                                <th style="width: 160px; min-width: 140px;">Ruangan</th>
                                                <th style="width: 240px; min-width: 220px;">Dosen Pengampu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($krs->mata_kuliah)): ?>
                                                <?php foreach ($krs->mata_kuliah as $j_idx => $j_mk): 
                                                    // Resolusi cerdas jika nama_dosen masih kosong
                                                    $dosen_display = (!empty($j_mk->nama_dosen) && trim($j_mk->nama_dosen) !== '-') 
                                                        ? $j_mk->nama_dosen 
                                                        : (class_exists('M_akademik') ? M_akademik::resolve_default_lecturer($j_mk)['nama'] : 'Dr. Ir. Hendra Setiawan, M.T.');
                                                ?>
                                                    <tr>
                                                        <td class="text-center font-weight-bold text-muted"><?= $j_idx + 1 ?></td>
                                                        <td>
                                                            <span class="font-weight-bold px-2 py-0.5" style="background: #f1f5f9; color: #334155; border-radius: 6px; font-size: 12.5px; font-family: monospace; border: 1px solid #e2e8f0; display: inline-block;">
                                                                <?= html_escape($j_mk->kode_mk) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-bold text-dark text-uppercase" style="font-size: 13.5px;">
                                                                <?= html_escape(strtoupper($j_mk->nama_mk)) ?>
                                                            </div>
                                                            <?php if (!empty($j_mk->kategori_label)): ?>
                                                                <small class="text-muted"><?= html_escape($j_mk->kategori_label) ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center font-weight-bold text-primary">
                                                            <span class="badge px-2 py-1" style="background: #e0e7ff; color: #3730a3; border-radius: 6px; font-size: 12px;">
                                                                <?= (int)$j_mk->sks ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-light border font-weight-bold px-2 py-1" style="border-radius: 6px; font-size: 11.5px;">
                                                                <?= html_escape($j_mk->kelas) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-bold text-dark mb-0.5" style="font-size: 13px;">
                                                                <i class="fa fa-calendar-o text-primary mr-1"></i> <?= html_escape($j_mk->hari ?: 'Senin') ?>
                                                            </div>
                                                            <div class="text-secondary small">
                                                                <i class="fa fa-clock-o text-muted mr-1"></i> <?= html_escape(!empty($j_mk->waktu_mulai) ? substr($j_mk->waktu_mulai, 0, 5) . ' - ' . substr($j_mk->waktu_selesai, 0, 5) : '08:00 - 10:30') ?> WIB
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-secondary" style="font-size: 13px;">
                                                                <i class="fa fa-building-o text-muted mr-1"></i>
                                                                <?= html_escape(!empty($j_mk->ruangan) ? $j_mk->ruangan : 'Gedung Kuliah') ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="dosen-pengampu-wrap">
                                                                <div class="dosen-pengampu-avatar">
                                                                    <i class="fa fa-user"></i>
                                                                </div>
                                                                <div class="dosen-pengampu-name">
                                                                    <?= html_escape($dosen_display) ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-5 text-muted">
                                                        <i class="fa fa-calendar-times-o text-muted mb-2 d-block" style="font-size: 32px;"></i>
                                                        Belum ada jadwal perkuliahan pada semester ini.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ======================================================== -->
                            <!-- TAB 3: MATA KULIAH WAJIB (PERSIS DENGAN REFERENSI WEB)    -->
                            <!-- ======================================================== -->
                            <div class="tab-pane fade" id="content-wajib" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-frs-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px; min-width: 50px;">No</th>
                                                <th style="width: 140px; min-width: 120px;">Kode MK</th>
                                                <th style="min-width: 220px;">Nama Mata Kuliah</th>
                                                <th class="text-center" style="width: 85px; min-width: 80px;">Semester</th>
                                                <th class="text-center" style="width: 75px; min-width: 70px;">SKS</th>
                                                <th class="text-center" style="width: 75px; min-width: 70px;">Nilai</th>
                                                <th style="min-width: 200px;">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($mata_kuliah_wajib)): ?>
                                                <?php foreach ($mata_kuliah_wajib as $w_idx => $w_mk): 
                                                    $is_high = !empty($w_mk['is_highlight']);
                                                    $row_bg = $is_high ? 'background-color: #f0f9ff;' : '';
                                                ?>
                                                    <tr style="<?= $row_bg ?>">
                                                        <td class="text-center font-weight-bold text-muted"><?= $w_idx + 1 ?></td>
                                                        <td>
                                                            <span class="font-weight-bold px-2 py-0.5 <?= $is_high ? 'text-primary' : 'text-dark' ?>" style="background: #f1f5f9; border-radius: 6px; font-size: 12.5px; font-family: monospace; border: 1px solid #e2e8f0; display: inline-block;">
                                                                <?= html_escape($w_mk['kode_mk']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-bold text-uppercase <?= $is_high ? 'text-primary' : 'text-dark' ?>" style="font-size: 13.5px;">
                                                                <?= html_escape(strtoupper($w_mk['nama_mk'])) ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center font-weight-bold <?= $is_high ? 'text-primary' : 'text-secondary' ?>">
                                                            <?= (int)$w_mk['semester'] ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge px-2 py-1 font-weight-bold" style="background: #e0e7ff; color: #3730a3; border-radius: 6px; font-size: 12px;">
                                                                <?= (int)$w_mk['sks'] ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center font-weight-bold text-dark">
                                                            <?= html_escape($w_mk['nilai'] ?? '-') ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($w_mk['is_terambil'])): ?>
                                                                <span class="badge badge-success px-2.5 py-1 font-weight-bold" style="border-radius: 6px;">
                                                                    <i class="fa fa-check mr-1"></i> Terambil
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-secondary small"><?= html_escape($w_mk['keterangan'] ?? '-') ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted">
                                                        <i class="fa fa-book text-muted mb-2 d-block" style="font-size: 32px;"></i>
                                                        Belum ada kurikulum mata kuliah wajib untuk program studi ini.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- 3. KARTU FORUM DISKUSI & KOMENTAR FRS                    -->
                    <!-- ======================================================== -->
                    <div class="card frs-main-card no-print">
                        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mr-2" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; font-size: 14px;">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 15px;">Forum Diskusi &amp; Catatan Perwalian FRS</h6>
                            </div>
                            <small class="text-muted">Terhubung dengan Dosen Wali &amp; Kaprodi</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Kolom Kiri: Peserta Diskusi (Responsif & Rapi Tanpa Teks Terpotong) -->
                                <div class="col-xl-4 col-lg-4 col-md-5 mb-3 mb-md-0">
                                    <div class="frs-participant-box">
                                        <div class="frs-participant-box-header">
                                            <span class="font-weight-bold text-dark" style="font-size: 13.5px;"><i class="fa fa-users text-primary mr-1"></i> Peserta Terhubung</span>
                                            <span class="badge badge-primary px-2 py-0.5" style="border-radius: 6px; font-size: 10px; background-color: #0284c7;">4 Aktif</span>
                                        </div>
                                        <div class="frs-participant-box-body">
                                            <!-- Peserta 1: Mahasiswa -->
                                            <div class="frs-participant-row">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="peserta-saya" checked disabled>
                                                    <label class="custom-control-label frs-participant-label" for="peserta-saya">
                                                        <span class="frs-participant-name" title="<?= html_escape($student_name) ?>">
                                                            <?= html_escape($student_name) ?>
                                                        </span>
                                                        <span class="badge badge-light border text-muted frs-participant-badge">Mahasiswa</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Peserta 2: Kaprodi -->
                                            <div class="frs-participant-row">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="peserta-subari" checked>
                                                    <label class="custom-control-label frs-participant-label" for="peserta-subari">
                                                        <span class="frs-participant-name" title="<?= html_escape($kaprodi_name) ?>">
                                                            <?= html_escape($kaprodi_name) ?>
                                                        </span>
                                                        <span class="badge badge-light border text-primary frs-participant-badge">Kaprodi</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Peserta 3: Dosen Wali -->
                                            <div class="frs-participant-row">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="peserta-dosenwali" checked>
                                                    <label class="custom-control-label frs-participant-label" for="peserta-dosenwali">
                                                        <span class="frs-participant-name" title="<?= html_escape($advisor_name) ?>">
                                                            <?= html_escape($advisor_name) ?>
                                                        </span>
                                                        <span class="badge badge-light border text-warning frs-participant-badge" style="color: #b45309 !important; background: #fffbeb !important; border-color: #fde68a !important;">Dosen Wali</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Peserta 4: Biro Akademik -->
                                            <div class="frs-participant-row">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="peserta-yekti" checked>
                                                    <label class="custom-control-label frs-participant-label" for="peserta-yekti">
                                                        <span class="frs-participant-name" title="<?= html_escape($validator_name) ?>">
                                                            <?= html_escape($validator_name) ?>
                                                        </span>
                                                        <span class="badge badge-light border text-info frs-participant-badge" style="color: #0369a1 !important; background: #f0f9ff !important; border-color: #bae6fd !important;">Biro Akademik</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Thread Komentar & Input Form -->
                                <div class="col-xl-8 col-lg-8 col-md-7">
                                    <!-- Thread Komentar Terdahulu -->
                                    <div id="diskusiThread" class="mb-3 p-3 rounded" style="max-height: 280px; overflow-y: auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px !important;">
                                        <?php if (!empty($diskusi_list)): ?>
                                            <?php foreach ($diskusi_list as $komentar): ?>
                                                <div class="d-flex mb-3 align-items-start" style="gap: 12px;">
                                                    <img src="<?= base_url('assets/images/' . (!empty($komentar->pengirim_foto) ? $komentar->pengirim_foto : 'avatar-4.png')) ?>" 
                                                         alt="Avatar" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #ffffff; flex-shrink: 0;">
                                                    <div class="flex-grow-1 p-3 rounded shadow-sm" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px !important;">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?= html_escape($komentar->pengirim_nama) ?></span>
                                                            <small class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o mr-1"></i><?= date('d M Y H:i', strtotime($komentar->created_at)) ?></small>
                                                        </div>
                                                        <div class="text-secondary" style="font-size: 13px; line-height: 1.5;"><?= nl2br(html_escape($komentar->pesan)) ?></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-center py-4 text-muted small">
                                                <i class="fa fa-commenting-o mb-1 d-block text-muted" style="font-size: 24px;"></i>
                                                Belum ada pesan atau catatan dalam diskusi FRS ini. Kirim catatan pertama Anda di bawah.
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Form Input Komentar Baru -->
                                    <form id="formKirimKomentar" action="<?= base_url('perwalian/kirim_komentar_frs') ?>" method="POST">
                                        <input type="hidden" name="mahasiswa_id" value="<?= (int)$target_mahasiswa->id ?>">
                                        <input type="hidden" name="krs_id" value="<?= !empty($krs->id) ? (int)$krs->id : '' ?>">
                                        <input type="hidden" name="tahun_akademik" value="<?= html_escape($clean_year) ?>">
                                        <input type="hidden" name="semester_akademik" value="<?= html_escape($clean_sem) ?>">

                                        <div class="d-flex align-items-start" style="gap: 12px;">
                                            <img src="<?= base_url('assets/images/' . (!empty($current_user_avatar) ? $current_user_avatar : 'avatar-4.png')) ?>" 
                                                 alt="Saya" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #ffffff; flex-shrink: 0;">
                                            <div class="flex-grow-1">
                                                <textarea name="pesan" id="inputKomentar" class="form-control" rows="3" placeholder="Tuliskan catatan konsultasi atau pertanyaan terkait rencana studi..." style="border: 1.5px solid #cbd5e1; border-radius: 12px; resize: vertical; font-size: 13.5px;"></textarea>
                                                <div class="text-right mt-2">
                                                    <button type="submit" id="btnPostKomentar" class="btn btn-primary px-4 py-2 font-weight-bold" style="background: #0284c7; border-color: #0284c7; border-radius: 20px; font-size: 13px; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.35);">
                                                        <i class="fa fa-paper-plane mr-1"></i> Kirim Catatan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var formKomentar = document.getElementById('formKirimKomentar');
    var inputKomentar = document.getElementById('inputKomentar');
    var btnPost = document.getElementById('btnPostKomentar');
    var diskusiThread = document.getElementById('diskusiThread');

    if (formKomentar) {
        formKomentar.addEventListener('submit', function(e) {
            var val = (inputKomentar.value || '').trim();
            if (!val) {
                e.preventDefault();
                inputKomentar.focus();
                return;
            }

            if (window.fetch && window.FormData) {
                e.preventDefault();
                btnPost.disabled = true;
                btnPost.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Mengirim...';

                var formData = new FormData(formKomentar);
                fetch(formKomentar.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(resp) {
                    btnPost.disabled = false;
                    btnPost.innerHTML = '<i class="fa fa-paper-plane mr-1"></i> Kirim Catatan';

                    if (resp.status === 'success') {
                        var newMsg = document.createElement('div');
                        newMsg.className = 'd-flex mb-3 align-items-start';
                        newMsg.style.gap = '12px';
                        newMsg.innerHTML = '<img src="<?= base_url('assets/images/') ?>' + (resp.data.foto || 'avatar-4.png') + '" alt="Avatar" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #ffffff; flex-shrink: 0;">' +
                            '<div class="flex-grow-1 p-3 rounded shadow-sm" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px !important;">' +
                            '<div class="d-flex justify-content-between align-items-center mb-1">' +
                            '<span class="font-weight-bold text-dark" style="font-size: 13px;">' + resp.data.nama + '</span>' +
                            '<small class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o mr-1"></i>' + resp.data.waktu + '</small>' +
                            '</div>' +
                            '<div class="text-secondary" style="font-size: 13px; line-height: 1.5;">' + resp.data.pesan + '</div>' +
                            '</div>';
                        diskusiThread.appendChild(newMsg);
                        diskusiThread.scrollTop = diskusiThread.scrollHeight;
                        inputKomentar.value = '';
                    } else {
                        alert(resp.message || 'Gagal mengirim komentar.');
                    }
                })
                .catch(function(err) {
                    btnPost.disabled = false;
                    btnPost.innerHTML = '<i class="fa fa-paper-plane mr-1"></i> Kirim Catatan';
                    formKomentar.submit();
                });
            }
        });
    }
});
</script>
