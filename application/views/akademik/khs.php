<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$student = $khs_data['student'] ?? null;
$courses = $khs_data['courses'] ?? [];
$summary = $khs_data['summary'] ?? [];
$available_periods = $khs_data['available_periods'] ?? [];
$selected_period = $khs_data['selected_period'] ?? null;
$selected_jenis = $this->input->get('jenis', true) ?: 'khs';

$is_admin = !empty($is_admin);
$is_dosen = !empty($is_dosen);
$all_students = $all_students ?? [];
$selected_student_id = $selected_student_id ?? ($student ? (int)$student->id : 0);
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
    padding: 10px 20px;
    font-size: 13.5px;
    color: #1e293b;
}

.khs-meta-label {
    width: 145px;
    color: #475569;
    font-weight: 500;
}

.khs-meta-sep {
    width: 20px;
    color: #64748b;
    text-align: center;
}

.khs-meta-value {
    color: #0f172a;
    flex: 1;
}

/* Tabel KHS */
.table-khs-wrapper {
    padding: 0 20px;
}

.table-khs {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.table-khs thead th {
    background-color: #bce4f7 !important;
    color: #0f3d64 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    padding: 11px 12px !important;
    border-top: none !important;
    border-bottom: 1px solid #90cdf4 !important;
    vertical-align: middle;
}

.table-khs tbody td {
    padding: 11px 12px !important;
    font-size: 13px !important;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
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

/* PRINT STYLING */
@media print {
    body {
        background: #ffffff !important;
        font-size: 12px !important;
        color: #000000 !important;
    }
    .pcoded-header,
    .pcoded-navbar,
    .khs-controls-bar,
    .breadcrumbs,
    .btn,
    footer,
    .admin-student-selector {
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
    .khs-card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
    }
    .khs-card-header {
        background: #ffffff !important;
        border-bottom: 2px solid #000000 !important;
        padding: 0 0 10px 0 !important;
    }
    .khs-card-header h5 {
        color: #000000 !important;
        font-size: 18px !important;
        text-align: center;
        text-transform: uppercase;
    }
    .table-khs thead th {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border: 1px solid #000000 !important;
    }
    .table-khs tbody td {
        border: 1px solid #333333 !important;
        color: #000000 !important;
    }
    .khs-summary-box {
        border-top: 1px solid #000000 !important;
        background: transparent !important;
        padding: 10px 0 !important;
    }
    .badge-grade {
        border: 1px solid #555555 !important;
        color: #000000 !important;
        background: transparent !important;
    }
    .print-only-signatures {
        display: block !important;
        margin-top: 40px;
    }
}

@media screen {
    .print-only-signatures {
        display: none;
    }
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- KARTU HASIL STUDI CONTAINER -->
                    <div class="card khs-card" id="khsContainerCard">
                        
                        <!-- Header Kartu -->
                        <div class="card-header khs-card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="feather icon-file-text text-white mr-2" style="font-size: 18px;"></i>
                                <h5>Kartu Hasil Studi</h5>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="text-white" onclick="toggleKhsCardFullscreen()" title="Perbesar Tampilan">
                                    <i class="feather icon-maximize-2" style="font-size: 16px;"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Bar Filter & Tombol Cetak -->
                        <div class="khs-controls-bar">
                            <form method="GET" action="<?= current_url() ?>" id="khsFilterForm" class="d-flex flex-wrap align-items-center justify-content-between">
                                
                                <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                                    
                                    <!-- Jika role Superadmin / Dosen: Pemilih Mahasiswa -->
                                    <?php if ($is_admin || $is_dosen): ?>
                                        <div class="d-flex align-items-center mr-3 mb-2 mb-sm-0 admin-student-selector">
                                            <label class="font-weight-bold mr-2 mb-0" style="white-space: nowrap; font-size: 13px; color: #334155;">
                                                <i class="feather icon-user mr-1 text-primary"></i> Mahasiswa:
                                            </label>
                                            <select name="student_id" class="form-control khs-filter-select" onchange="document.getElementById('khsFilterForm').submit();" style="min-width: 250px;">
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
                                        <i class="feather icon-printer mr-2"></i> Cetak / Eksport
                                    </button>
                                </div>

                            </form>
                        </div>

                        <!-- Panel Metadata Mahasiswa (Persis sesuai foto) -->
                        <?php if ($student): ?>
                            <div class="khs-meta-box">
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-md-7 d-flex align-items-center">
                                        <span class="khs-meta-label">NRP</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px;"><?= html_escape($student->nim) ?></span>
                                    </div>
                                    <div class="col-md-5 d-flex align-items-center">
                                        <span class="khs-meta-label">Jenjang Pendidikan</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->jenjang ?? 'Diploma 3') ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="col-md-7 d-flex align-items-center">
                                        <span class="khs-meta-label">Nama</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold" style="font-size: 14px; letter-spacing: 0.3px;">
                                            <?= strtoupper(html_escape($student->nama_lengkap)) ?>
                                        </span>
                                    </div>
                                    <div class="col-md-5 d-flex align-items-center">
                                        <span class="khs-meta-label">Program Studi</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->prodi) ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center py-2">
                                    <div class="col-md-7 d-flex align-items-center">
                                        <span class="khs-meta-label">Dosen Wali</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold"><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></span>
                                    </div>
                                    <div class="col-md-5 d-flex align-items-center">
                                        <span class="khs-meta-label">Semester / Status</span>
                                        <span class="khs-meta-sep">:</span>
                                        <span class="khs-meta-value font-weight-bold">
                                            Semester <?= html_escape($student->semester ?? '1') ?> (<?= html_escape($student->status_akademik ?? 'Aktif') ?>)
                                            <?php if (!empty($summary['is_paket_semester'])): ?>
                                                <span class="badge badge-info ml-1 px-2 py-1" style="border-radius: 4px; font-size: 11px; font-weight: 500;">
                                                    <i class="feather icon-package mr-1"></i> Paket Semester 1 (Dipaketkan)
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Alert Penjelasan: Semester Aktif (Baru KRS) vs Riwayat KHS yang Selesai -->
                        <?php if ($student): ?>
                            <div class="mx-3 mb-3 p-3 d-flex align-items-center" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                                <div class="mr-3 text-success">
                                    <i class="feather icon-info" style="font-size: 22px;"></i>
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
                            <table class="table table-khs">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No.</th>
                                        <?php if ($selected_jenis === 'transkrip'): ?>
                                            <th class="text-center" style="width: 80px;">Smt</th>
                                        <?php endif; ?>
                                        <th style="width: 120px;">Kode</th>
                                        <th>Mata Kuliah</th>
                                        <th class="text-center" style="width: 70px;">SKS</th>
                                        <th class="text-center" style="width: 70px;">Nilai</th>
                                        <th class="text-center" style="width: 70px;">Index</th>
                                        <th style="width: 140px;">Kategori</th>
                                        <th class="text-center" style="width: 70px;">NxK</th>
                                        <th class="text-center" style="width: 100px;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($courses)): ?>
                                        <?php $no = 1; foreach ($courses as $c): ?>
                                            <tr>
                                                <td class="text-center text-muted"><?= $no++ ?></td>
                                                <?php if ($selected_jenis === 'transkrip'): ?>
                                                    <td class="text-center"><span class="badge badge-light border">Smt <?= (int)$c->semester_mhs ?></span></td>
                                                <?php endif; ?>
                                                <td class="font-weight-bold" style="color: #475569; letter-spacing: 0.3px;">
                                                    <?= html_escape($c->kode_mk) ?>
                                                </td>
                                                <td class="font-weight-bold" style="color: #1e293b;">
                                                    <?= html_escape($c->nama_mk) ?>
                                                    <?php if (!empty($c->is_paket)): ?>
                                                        <span class="badge badge-light border text-muted ml-1" style="font-size: 10.5px;">Paket</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center"><?= (int)$c->sks ?></td>
                                                <td class="text-center font-weight-bold">
                                                    <?php 
                                                        $grade = strtoupper(trim($c->nilai_huruf));
                                                        $badge_cls = 'badge-grade-a';
                                                        if (strpos($grade, 'B') !== false) $badge_cls = 'badge-grade-b';
                                                        elseif (strpos($grade, 'C') !== false) $badge_cls = 'badge-grade-c';
                                                        elseif (strpos($grade, 'D') !== false || strpos($grade, 'E') !== false) $badge_cls = 'badge-grade-d';
                                                    ?>
                                                    <span class="badge-grade <?= $badge_cls ?>"><?= html_escape($c->nilai_huruf) ?></span>
                                                </td>
                                                <td class="text-center"><?= number_format((float)$c->nilai_indeks, 1) ?></td>
                                                <td><?= html_escape($c->kategori) ?></td>
                                                <td class="text-center font-weight-bold"><?= (int)$c->nxk == $c->nxk ? (int)$c->nxk : number_format((float)$c->nxk, 1) ?></td>
                                                <td class="text-center"><?= html_escape($c->keterangan) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?= ($selected_jenis === 'transkrip') ? 10 : 9 ?>" class="text-center py-5 text-muted">
                                                <i class="feather icon-clock text-warning" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                                <span class="font-weight-bold" style="font-size: 14px; color: #334155;">Belum Ada Rekaman Nilai KHS</span>
                                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">
                                                    Mahasiswa baru saja mengisi KRS pada semester aktif ini. Nilai KHS akan terbit setelah evaluasi ujian akhir semester selesai diinput.
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Ringkasan (Persis sesuai foto) -->
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
                                                <i class="feather icon-check-circle mr-1"></i> Total SKS Ditempuh <?= !empty($selected_period['semester_mhs']) ? '(s/d Smt ' . $selected_period['semester_mhs'] . ')' : '' ?>
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
                                                <i class="feather icon-award mr-1"></i> IPK Kumulatif <?= !empty($selected_period['semester_mhs']) ? '(s/d Smt ' . $selected_period['semester_mhs'] . ')' : '' ?>
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

                        <!-- TANDA TANGAN RESMI CETAK (HANYA MUNCUL SAAT PRINT) -->
                        <div class="print-only-signatures px-4">
                            <div class="row text-center mt-5" style="font-size: 12px;">
                                <div class="col-4">
                                    <p class="mb-5">Mahasiswa,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></u></p>
                                    <p class="text-muted">NRP. <?= html_escape($student->nim ?? '') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-5">Dosen Wali,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></u></p>
                                    <p class="text-muted">NIP. <?= html_escape($student->nip_dosen_wali ?? '-') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-5">Ketua Program Studi,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u>Dr. Ir. Hendra Setiawan, M.T.</u></p>
                                    <p class="text-muted">NIP. 197805122003121002</p>
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
</script>
