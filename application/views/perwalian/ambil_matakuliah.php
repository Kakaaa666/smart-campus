<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$selected_offerings = [];
if (!empty($krs->mata_kuliah)) {
    $selected_offerings = array_map('intval', array_column($krs->mata_kuliah, 'id'));
}
?>

<style>
    /* Styling Halaman Pemilihan KRS Interaktif */
    .krs-page-container {
        padding: 0 4px;
    }

    /* Card Header & Status */
    .krs-header-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        padding: 22px 24px;
        margin-bottom: 22px;
    }

    .status-krs-box {
        border-radius: 10px;
        padding: 14px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* SKS Meter Progress */
    .sks-meter-wrap {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 18px 22px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        margin-bottom: 22px;
    }

    .sks-progress-bar-bg {
        height: 14px;
        background: #f1f5f9;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        margin-top: 8px;
    }

    .sks-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 8px;
        transition: width 0.3s ease, background 0.3s ease;
    }

    .sks-progress-bar-fill.over-limit {
        background: linear-gradient(90deg, #ef4444 0%, #b91c1c 100%);
    }

    /* Filter Tabs & Pills */
    .krs-nav-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .krs-tab-pill {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .krs-tab-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .krs-tab-pill.active {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(2, 132, 199, 0.28);
    }

    /* Badges Jenis Mata Kuliah */
    .badge-jenis-mk {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 12px;
        display: inline-block;
        letter-spacing: 0.3px;
    }

    .badge-mk-wajib    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-mk-jurusan  { background: #faf5ff; color: #7c3aed; border: 1px solid #e9d5ff; }
    .badge-mk-luar     { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .badge-mk-umum     { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-mk-pilihan  { background: #fdf2f8; color: #be185d; border: 1px solid #fbcfe8; }

    /* Tabel Penawaran */
    .table-offerings-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .table-offerings thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1.5px solid #e2e8f0;
        border-top: none;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-offerings tbody td {
        padding: 13px 14px;
        vertical-align: middle;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
    }

    .table-offerings tbody tr.row-selected {
        background-color: #f0f9ff !important;
    }

    .table-offerings tbody tr.row-conflict {
        background-color: #fef2f2 !important;
        border-left: 4px solid #ef4444;
    }

    .custom-checkbox-krs {
        width: 19px;
        height: 19px;
        cursor: pointer;
        accent-color: #0284c7;
    }

    /* Peringatan Benturan Jadwal */
    .conflict-alert-box {
        display: none;
        background: #fef2f2;
        border: 1.5px solid #fca5a5;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 20px;
        color: #991b1b;
        animation: fadeIn 0.3s ease;
    }

    /* Live Visual Timetable (Jadwal Mingguan Interaktif) */
    .timetable-wrapper {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        padding: 20px;
        margin-bottom: 24px;
    }

    .timetable-grid {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .timetable-grid th {
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        padding: 10px 8px;
        border: 1px solid #e2e8f0;
    }

    .timetable-grid td {
        border: 1px solid #e2e8f0;
        padding: 6px;
        vertical-align: top;
        height: 72px;
        width: 15%;
        background: #ffffff;
        position: relative;
    }

    .timetable-grid td.time-col {
        width: 10%;
        background: #f8fafc;
        text-align: center;
        font-weight: 700;
        font-size: 11.5px;
        color: #64748b;
        vertical-align: middle;
    }

    .schedule-block {
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 11.5px;
        color: #ffffff;
        margin-bottom: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        transition: transform 0.15s ease;
        line-height: 1.3;
    }

    .schedule-block:hover {
        transform: scale(1.02);
    }

    .schedule-block.block-blue   { background: #0284c7; border-left: 3px solid #0369a1; }
    .schedule-block.block-purple { background: #7c3aed; border-left: 3px solid #6d28d9; }
    .schedule-block.block-orange { background: #ea580c; border-left: 3px solid #c2410c; }
    .schedule-block.block-green  { background: #059669; border-left: 3px solid #047857; }
    
    .schedule-block.block-conflict {
        background: #dc2626 !important;
        border-left: 3px solid #991b1b !important;
        animation: pulseConflict 1.5s infinite;
    }

    @keyframes pulseConflict {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }

    /* Selected Courses Chips */
    .chip-course {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        color: #1e293b;
        margin: 4px;
    }

    .chip-course .chip-remove {
        cursor: pointer;
        margin-left: 6px;
        color: #ef4444;
        font-weight: bold;
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content" style="padding-top: 20px;">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body krs-page-container">

                    <!-- Flash Notifikasi -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fa fa-check-circle mr-2"></i> <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fa fa-exclamation-triangle mr-2"></i> <?= html_escape($this->session->flashdata('error')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($is_admin_preview) && !empty($all_students)): ?>
                        <!-- Switcher Simulasi Antar Mahasiswa & Prodi (Untuk Verifikasi Kontekstual Prodi) -->
                        <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap mb-3 p-3 shadow-sm" style="border-radius: 10px; background: #f0f9ff; border: 1.5px solid #bae6fd;">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <i class="fa fa-users fa-2x text-primary mr-3"></i>
                                <div>
                                    <strong class="text-dark" style="font-size: 13.5px;">Simulasi Pratinjau Mahasiswa Berdasarkan Jurusan / Prodi:</strong>
                                    <div class="text-muted" style="font-size: 12px;">
                                        Uji perbedaan kategori matakuliah secara instan: Saat memilih <strong>Informatika</strong> matkul Akuntansi menjadi <em>Luar Jurusan</em>, dan saat memilih <strong>Akuntansi</strong> matkul Akuntansi menjadi <em>MK Wajib Prodi</em>.
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <label class="mb-0 font-weight-bold text-dark" style="font-size: 12.5px;">Ganti Mahasiswa:</label>
                                <select class="form-control form-control-sm" style="width: 290px; font-weight: 600; border-radius: 6px;" onchange="window.location.href='<?= base_url('perwalian/ambil_matakuliah') ?>?mahasiswa_id=' + this.value">
                                    <?php foreach ($all_students as $st): ?>
                                        <option value="<?= (int)$st->id ?>" <?= ((int)$target_mahasiswa->id === (int)$st->id) ? 'selected' : '' ?>>
                                            <?= html_escape($st->nama_lengkap) ?> &bull; <?= html_escape($st->prodi ?: 'Informatika') ?> (Sem <?= (int)$st->semester ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Header Informasi Mahasiswa & KRS -->
                    <div class="krs-header-card">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary px-3 py-1 mr-2" style="font-size: 12px; border-radius: 6px;">
                                        Periode <?= html_escape($academic_period['semester_akademik']) ?> <?= html_escape($academic_period['tahun_akademik']) ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 13px;">
                                        Kartu Rencana Studi (KRS Online)
                                    </span>
                                </div>
                                <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                    <?= html_escape($target_mahasiswa->nama_lengkap) ?>
                                </h4>
                                <div style="font-size: 13.5px; color: #475569;">
                                    NIM: <strong class="text-dark font-monospace"><?= html_escape($target_mahasiswa->nim) ?></strong> &bull;
                                    Prodi: <strong class="text-primary"><?= html_escape($target_mahasiswa->prodi ?: 'Teknik Informatika') ?></strong> &bull;
                                    Semester: <span class="badge badge-info">Semester <?= (int)($target_mahasiswa->semester ?: 1) ?></span>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12">
                                <div class="row">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <div class="status-krs-box">
                                            <small class="text-muted font-weight-bold">Status KRS:</small>
                                            <div class="mt-1">
                                                <?php 
                                                    $krs_stat = $krs ? $krs->status : 'DRAFT';
                                                    if ($krs_stat === 'DISETUJUI'):
                                                ?>
                                                    <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i> Disetujui</span>
                                                <?php elseif ($krs_stat === 'MENUNGGU'): ?>
                                                    <span class="badge badge-warning px-2 py-1"><i class="fa fa-clock-o mr-1"></i> Menunggu Dosen</span>
                                                <?php elseif ($krs_stat === 'DITOLAK'): ?>
                                                    <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i> Perlu Revisi</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary px-2 py-1"><i class="fa fa-pencil mr-1"></i> Mode Draf</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="status-krs-box d-flex justify-content-between align-items-center">
                                            <div class="mr-2 overflow-hidden">
                                                <small class="text-muted font-weight-bold">Dosen Wali:</small>
                                                <div class="font-weight-bold text-dark text-truncate mt-1" style="font-size: 13px;" title="<?= html_escape($advisor ? $advisor->nama_lengkap : 'Belum ditetapkan') ?>">
                                                    <?= html_escape($advisor ? $advisor->nama_lengkap : 'Belum ditetapkan') ?>
                                                </div>
                                                <small class="text-muted"><?= html_escape($advisor ? $advisor->nim : '-') ?></small>
                                            </div>
                                            <?php if (empty($is_admin_preview)): ?>
                                                <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1" style="border-radius: 8px; font-size: 11px; font-weight: 600;" data-toggle="modal" data-target="#modalPilihDosenWali">
                                                    <i class="fa fa-pencil mr-1"></i> Pilih Wali
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Indikator Beban Studi & Progress SKS Real-Time -->
                    <div class="sks-meter-wrap">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14.5px;">
                                    <i class="fa fa-balance-scale text-primary mr-1"></i> Beban Studi Mata Kuliah Dipilih
                                </h6>
                                <small class="text-muted">
                                    Minimal <strong><?= (int)$min_sks ?> SKS</strong>, Maksimal <strong><?= (int)$max_sks ?> SKS</strong> per semester.
                                </small>
                            </div>
                            <div class="text-right">
                                <span style="font-size: 18px; font-weight: 800; color: #0284c7;" id="sksCounterText">
                                    <span id="currentSksValue"><?= $krs ? (int)$krs->total_sks : 0 ?></span> / <?= (int)$max_sks ?>
                                </span>
                                <span class="font-weight-bold text-muted" style="font-size: 13px;">SKS</span>
                                <span id="sksStatusBadge" class="ml-2 badge badge-success" style="font-size: 11px;">Aman</span>
                            </div>
                        </div>
                        <div class="sks-progress-bar-bg">
                            <div class="sks-progress-bar-fill" id="sksProgressBar" style="width: <?= min(100, (($krs ? (int)$krs->total_sks : 0) / $max_sks) * 100) ?>%;"></div>
                        </div>
                    </div>

                    <!-- Kotak Peringatan Benturan Jadwal (Muncul Otomatis Saat Ada Tabrakan) -->
                    <div class="conflict-alert-box" id="conflictAlertBox">
                        <div class="d-flex align-items-start">
                            <div class="mr-3" style="font-size: 24px; color: #dc2626;">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="font-weight-bold mb-1" style="color: #991b1b;">
                                    Benturan Jadwal Kuliah Terdeteksi!
                                </h6>
                                <p class="mb-2" style="font-size: 13px; color: #7f1d1d;">
                                    Anda memilih mata kuliah dengan hari dan jam yang saling tumpang tindih. Pengajuan KRS tidak dapat diproses hingga benturan diselesaikan:
                                </p>
                                <ul class="mb-0 pl-3" id="conflictListDetails" style="font-size: 12.5px; font-weight: 600;">
                                    <!-- Rincian tabrakan diisi dinamis via JS -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Tab: Membedakan Semester Ganjil, Genap, Wajib, Jurusan, Luar Jurusan & Umum -->
                    <div class="krs-nav-tabs">
                        <div class="krs-tab-pill active" data-filter-type="all">
                            <i class="fa fa-th-large"></i> Semua Mata Kuliah
                        </div>
                        <div class="krs-tab-pill" data-filter-type="Wajib">
                            <i class="fa fa-bookmark text-primary"></i> MK Wajib Prodi
                        </div>
                        <div class="krs-tab-pill" data-filter-type="Jurusan">
                            <i class="fa fa-cubes text-purple" style="color: #7c3aed;"></i> MK Jurusan / Peminatan
                        </div>
                        <div class="krs-tab-pill" data-filter-type="Luar Jurusan">
                            <i class="fa fa-exchange text-warning" style="color: #ea580c;"></i> MK Luar Jurusan (MBKM)
                        </div>
                        <div class="krs-tab-pill" data-filter-type="Umum">
                            <i class="fa fa-globe text-success" style="color: #059669;"></i> MK Umum / MKU
                        </div>
                        <div class="krs-tab-pill" data-filter-semester="Ganjil">
                            <i class="fa fa-calendar-check-o"></i> Semester Ganjil
                        </div>
                        <div class="krs-tab-pill" data-filter-semester="Genap">
                            <i class="fa fa-calendar-o"></i> Semester Genap
                        </div>
                    </div>

                    <!-- Bar Filter Pencarian Cepat & Filter Jenjang Semester -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap: 10px;">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <label class="mb-0 font-weight-bold text-muted" style="font-size: 12.5px;">Filter Jenjang:</label>
                            <select id="selectSemesterLevel" class="form-control form-control-sm" style="width: 150px; border-radius: 6px;">
                                <option value="all">-- Semua Semester --</option>
                                <?php for ($s = 1; $s <= 8; $s++): ?>
                                    <option value="<?= $s ?>">Semester <?= $s ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div style="min-width: 280px;">
                            <div class="input-group input-group-sm">
                                <input type="text" id="searchCourseInput" class="form-control" placeholder="Cari nama / kode matkul / dosen..." style="border-radius: 6px 0 0 6px;">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white" style="border-radius: 0 6px 6px 0;">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= form_open('perwalian/simpan_krs', ['id' => 'formKrsMahasiswa']) ?>

                        <div class="row">
                            <!-- Kolom Kiri: Tabel Penawaran Mata Kuliah -->
                            <div class="col-xl-8 col-lg-12">
                                <div class="table-offerings-card">
                                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 14px;">
                                            <i class="fa fa-list-alt text-primary mr-2"></i> Katalog Penawaran Mata Kuliah
                                        </h6>
                                        <span class="text-muted" style="font-size: 12px;">
                                            Centang untuk memilih &amp; melihat jadwal langsung
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-offerings" id="tableCourses">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px; text-align: center;">Pilih</th>
                                                    <th>Mata Kuliah &amp; Kode</th>
                                                    <th style="text-align: center;">Kategori</th>
                                                    <th style="text-align: center;">SKS</th>
                                                    <th>Kelas / Periode</th>
                                                    <th>Jadwal &amp; Ruangan</th>
                                                    <th>Dosen Pengampu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($offerings as $item): 
                                                    $is_checked = in_array((int)$item->id, $selected_offerings, true);
                                                    $jenis = !empty($item->kategori_kontekstual) ? $item->kategori_kontekstual : (!empty($item->jenis_mk) ? $item->jenis_mk : 'Wajib');
                                                    $badge_cls = !empty($item->kategori_badge_class) ? $item->kategori_badge_class : 'badge-mk-wajib';
                                                    $label_kategori = !empty($item->kategori_label) ? $item->kategori_label : $jenis;
                                                ?>
                                                    <tr class="course-row <?= $is_checked ? 'row-selected' : '' ?>" 
                                                        id="row-offering-<?= (int)$item->id ?>"
                                                        data-id="<?= (int)$item->id ?>"
                                                        data-kode="<?= html_escape($item->kode_mk) ?>"
                                                        data-nama="<?= html_escape($item->nama_mk) ?>"
                                                        data-sks="<?= (int)$item->sks ?>"
                                                        data-hari="<?= html_escape($item->hari) ?>"
                                                        data-mulai="<?= html_escape(substr($item->waktu_mulai, 0, 5)) ?>"
                                                        data-selesai="<?= html_escape(substr($item->waktu_selesai, 0, 5)) ?>"
                                                        data-ruangan="<?= html_escape($item->ruangan) ?>"
                                                        data-jenis="<?= html_escape($jenis) ?>"
                                                        data-label-jenis="<?= html_escape($label_kategori) ?>"
                                                        data-prodi="<?= html_escape($item->prodi) ?>"
                                                        data-semester="<?= (int)$item->semester ?>"
                                                        data-periode="<?= html_escape($item->semester_akademik) ?>">
                                                        
                                                        <td style="text-align: center;">
                                                            <input type="checkbox" 
                                                                   name="penawaran[]" 
                                                                   value="<?= (int)$item->id ?>" 
                                                                   class="custom-checkbox-krs course-checkbox"
                                                                   data-id="<?= (int)$item->id ?>"
                                                                   data-sks="<?= (int)$item->sks ?>"
                                                                   data-hari="<?= html_escape($item->hari) ?>"
                                                                   data-mulai="<?= html_escape(substr($item->waktu_mulai, 0, 5)) ?>"
                                                                   data-selesai="<?= html_escape(substr($item->waktu_selesai, 0, 5)) ?>"
                                                                   <?= $is_checked ? 'checked' : '' ?>
                                                                   <?= !$can_edit_krs ? 'disabled' : '' ?>>
                                                        </td>

                                                        <td>
                                                            <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                                <?= html_escape($item->nama_mk) ?>
                                                            </div>
                                                            <div class="d-flex align-items-center flex-wrap mt-1" style="gap: 5px;">
                                                                <span class="badge badge-light border font-monospace" style="font-size: 11px;">
                                                                    <?= html_escape($item->kode_mk) ?>
                                                                </span>
                                                                <span class="badge badge-light border text-muted" style="font-size: 10.5px;">
                                                                    <i class="fa fa-graduation-cap mr-1"></i>Prodi: <?= html_escape($item->prodi) ?>
                                                                </span>
                                                                <?php if (!empty($item->prasyarat) && $item->prasyarat !== 'Tidak Ada'): ?>
                                                                    <small class="text-muted"><i class="fa fa-info-circle mr-1"></i>Syarat: <?= html_escape($item->prasyarat) ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>

                                                        <td style="text-align: center;">
                                                            <span class="badge-jenis-mk <?= $badge_cls ?>" title="Kategori untuk mahasiswa <?= html_escape($target_mahasiswa->prodi ?: 'Informatika') ?>">
                                                                <?= html_escape($label_kategori) ?>
                                                            </span>
                                                        </td>

                                                        <td style="text-align: center; font-weight: 800; color: #0284c7;">
                                                            <?= (int)$item->sks ?> SKS
                                                        </td>

                                                        <td>
                                                            <span class="font-weight-bold text-dark"><?= html_escape($item->kelas) ?></span>
                                                            <div style="font-size: 11.5px; color: #64748b;">
                                                                Sem <?= (int)$item->semester ?> (<?= html_escape($item->semester_akademik) ?>)
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="font-weight-bold text-dark" style="font-size: 12.5px;">
                                                                <i class="fa fa-calendar-check-o text-primary mr-1"></i> <?= html_escape($item->hari) ?>
                                                            </div>
                                                            <div style="font-size: 12px; color: #475569;">
                                                                <?= html_escape(substr($item->waktu_mulai, 0, 5)) ?> &ndash; <?= html_escape(substr($item->waktu_selesai, 0, 5)) ?> WIB
                                                            </div>
                                                            <small class="text-muted"><i class="fa fa-map-marker mr-1"></i><?= html_escape($item->ruangan) ?></small>
                                                        </td>

                                                        <td>
                                                            <div style="font-size: 12.5px; font-weight: 600; color: #1e293b;">
                                                                <?= html_escape($item->nama_dosen ?: 'Dosen belum tersedia') ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Visual Jadwal Mingguan (Timetable) & Ringkasan Pilihan -->
                            <div class="col-xl-4 col-lg-12">
                                <div class="timetable-wrapper">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 14px;">
                                            <i class="fa fa-calendar text-success mr-2"></i> Jadwal Mingguan Terpilih
                                        </h6>
                                        <span class="badge badge-light border" id="countSelectedBadge">0 MK Dipilih</span>
                                    </div>

                                    <!-- Chips Mata Kuliah Dipilih -->
                                    <div class="mb-3 p-2 bg-light rounded" id="selectedChipsContainer" style="min-height: 48px; border: 1px dashed #cbd5e1;">
                                        <small class="text-muted d-block text-center py-2" id="emptyChipsText">
                                            Belum ada mata kuliah dicentang. Centang mata kuliah di sebelah kiri untuk melihat jadwal langsung.
                                        </small>
                                    </div>

                                    <!-- Kalender Jadwal Mingguan (Senin - Sabtu) -->
                                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                                        <table class="timetable-grid">
                                            <thead>
                                                <tr>
                                                    <th>Waktu</th>
                                                    <th>Senin</th>
                                                    <th>Selasa</th>
                                                    <th>Rabu</th>
                                                    <th>Kamis</th>
                                                    <th>Jumat</th>
                                                    <th>Sabtu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $time_slots = [
                                                        ['08:00', '10:30', 'Pagi I (08:00 - 10:30)'],
                                                        ['10:30', '13:00', 'Siang I (10:30 - 13:00)'],
                                                        ['13:00', '15:30', 'Siang II (13:00 - 15:30)'],
                                                        ['15:30', '18:00', 'Sore (15:30 - 18:00)'],
                                                    ];
                                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                    foreach ($time_slots as $slot):
                                                ?>
                                                    <tr>
                                                        <td class="time-col">
                                                            <?= $slot[0] ?><br>&ndash;<br><?= $slot[1] ?>
                                                        </td>
                                                        <?php foreach ($days as $day): ?>
                                                            <td id="slot-<?= $day ?>-<?= str_replace(':', '', $slot[0]) ?>" 
                                                                data-day="<?= $day ?>" 
                                                                data-start="<?= $slot[0] ?>" 
                                                                data-end="<?= $slot[1] ?>">
                                                                <!-- Card Blok Jadwal Diinjeksi Real-Time via JS -->
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2 text-muted" style="font-size: 11.5px;">
                                        <i class="fa fa-info-circle mr-1"></i> Blok warna langsung menunjukkan jam kuliah Anda. Jika terjadi tabrakan, blok berubah menjadi <strong class="text-danger">Merah Berkedip</strong>.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bar Aksi Pengajuan KRS -->
                        <div class="p-3 bg-white rounded border d-flex justify-content-between align-items-center flex-wrap mt-3" style="box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05); gap: 12px;">
                            <div>
                                <a href="<?= base_url('perwalian/frs') ?>" class="btn btn-outline-secondary btn-sm font-weight-bold" style="border-radius: 8px;">
                                    <i class="fa fa-print mr-1"></i> Cetak / Pratinjau FRS
                                </a>
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                <?php if ($can_edit_krs): ?>
                                    <button class="btn btn-outline-primary font-weight-bold btn-sm" name="aksi" value="draft" type="submit" style="border-radius: 8px; padding: 8px 18px;">
                                        <i class="fa fa-save mr-1"></i> Simpan Draf
                                    </button>
                                    <button class="btn btn-primary font-weight-bold btn-sm" name="aksi" value="ajukan" id="btnAjukanKrs" type="submit" style="border-radius: 8px; padding: 8px 22px; background: #0284c7; border-color: #0284c7;">
                                        <i class="fa fa-paper-plane mr-1"></i> Ajukan ke Dosen Wali
                                    </button>
                                <?php else: ?>
                                    <span class="badge badge-light border text-muted px-3 py-2" style="font-size: 12px;">
                                        <i class="fa fa-lock mr-1"></i> KRS terkunci atau sedang dalam persetujuan Dosen Wali
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?= form_close() ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = Array.from(document.querySelectorAll('.course-checkbox'));
    const currentSksValEl = document.getElementById('currentSksValue');
    const sksProgressBar = document.getElementById('sksProgressBar');
    const sksStatusBadge = document.getElementById('sksStatusBadge');
    const btnAjukanKrs = document.getElementById('btnAjukanKrs');
    const conflictAlertBox = document.getElementById('conflictAlertBox');
    const conflictListDetails = document.getElementById('conflictListDetails');
    const selectedChipsContainer = document.getElementById('selectedChipsContainer');
    const emptyChipsText = document.getElementById('emptyChipsText');
    const countSelectedBadge = document.getElementById('countSelectedBadge');

    const MAX_SKS = <?= (int)$max_sks ?>;
    const MIN_SKS = <?= (int)$min_sks ?>;

    // Helper konversi waktu HH:MM ke menit dari tengah malam
    function timeToMinutes(timeStr) {
        if (!timeStr) return 0;
        const parts = timeStr.split(':');
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    // Helper cek benturan dua rentang waktu
    function isOverlapping(startA, endA, startB, endB) {
        return Math.max(startA, startB) < Math.min(endA, endB);
    }

    // Fungsi Utama Evaluasi KRS Real-Time
    function evaluateKrsState() {
        let totalSks = 0;
        const selectedCourses = [];

        // Kumpulkan data semua mata kuliah yang dicentang
        checkboxes.forEach(function(cb) {
            const row = document.getElementById('row-offering-' + cb.dataset.id);
            if (cb.checked) {
                totalSks += parseInt(cb.dataset.sks, 10);
                row.classList.add('row-selected');
                selectedCourses.push({
                    id: cb.dataset.id,
                    kode: row.dataset.kode,
                    nama: row.dataset.nama,
                    sks: parseInt(row.dataset.sks, 10),
                    hari: row.dataset.hari,
                    mulai: row.dataset.mulai,
                    selesai: row.dataset.selesai,
                    mulaiMenit: timeToMinutes(row.dataset.mulai),
                    selesaiMenit: timeToMinutes(row.dataset.selesai),
                    ruangan: row.dataset.ruangan,
                    jenis: row.dataset.jenis,
                    labelJenis: row.dataset.labelJenis || row.dataset.jenis,
                    prodi: row.dataset.prodi || ''
                });
            } else {
                row.classList.remove('row-selected');
                row.classList.remove('row-conflict');
            }
        });

        // 1. Update SKS Counter & Progress Bar
        currentSksValEl.textContent = totalSks;
        const percent = Math.min(100, (totalSks / MAX_SKS) * 100);
        sksProgressBar.style.width = percent + '%';

        if (totalSks > MAX_SKS) {
            sksProgressBar.classList.add('over-limit');
            sksStatusBadge.className = 'ml-2 badge badge-danger';
            sksStatusBadge.textContent = 'Melebihi Batas!';
        } else if (totalSks >= MIN_SKS) {
            sksProgressBar.classList.remove('over-limit');
            sksStatusBadge.className = 'ml-2 badge badge-success';
            sksStatusBadge.textContent = 'Memenuhi Syarat';
        } else {
            sksProgressBar.classList.remove('over-limit');
            sksStatusBadge.className = 'ml-2 badge badge-warning';
            sksStatusBadge.textContent = 'Kurang dari Minimal (' + MIN_SKS + ' SKS)';
        }

        // 2. Deteksi Benturan Jadwal (Conflict Detection)
        const conflicts = [];
        const conflictCourseIds = new Set();

        for (let i = 0; i < selectedCourses.length; i++) {
            for (let j = i + 1; j < selectedCourses.length; j++) {
                const c1 = selectedCourses[i];
                const c2 = selectedCourses[j];

                if (c1.hari === c2.hari && isOverlapping(c1.mulaiMenit, c1.selesaiMenit, c2.mulaiMenit, c2.selesaiMenit)) {
                    conflicts.push({
                        c1: c1,
                        c2: c2,
                        hari: c1.hari,
                        waktu: c1.mulai + '-' + c1.selesai + ' vs ' + c2.mulai + '-' + c2.selesai
                    });
                    conflictCourseIds.add(c1.id);
                    conflictCourseIds.add(c2.id);
                }
            }
        }

        // Tandai baris tabel yang bertabrakan
        selectedCourses.forEach(function(c) {
            const r = document.getElementById('row-offering-' + c.id);
            if (conflictCourseIds.has(c.id)) {
                r.classList.add('row-conflict');
            } else {
                r.classList.remove('row-conflict');
            }
        });

        // Tampilkan/Sembunyikan Kotak Peringatan Benturan
        if (conflicts.length > 0) {
            conflictAlertBox.style.display = 'block';
            let htmlList = '';
            conflicts.forEach(function(cf) {
                htmlList += `<li><strong>${cf.c1.kode} (${cf.c1.nama})</strong> bertabrakan dengan <strong>${cf.c2.kode} (${cf.c2.nama})</strong> pada hari ${cf.hari} [${cf.waktu}].</li>`;
            });
            conflictListDetails.innerHTML = htmlList;

            // Nonaktifkan tombol ajukan jika ada benturan
            if (btnAjukanKrs) {
                btnAjukanKrs.disabled = true;
                btnAjukanKrs.title = 'Selesaikan benturan jadwal terlebih dahulu sebelum mengajukan.';
            }
        } else {
            conflictAlertBox.style.display = 'none';
            conflictListDetails.innerHTML = '';
            if (btnAjukanKrs) {
                btnAjukanKrs.disabled = (totalSks > MAX_SKS);
                btnAjukanKrs.title = '';
            }
        }

        // 3. Render Visual Timetable Mingguan
        renderVisualTimetable(selectedCourses, conflictCourseIds);

        // 4. Render Selected Courses Chips
        renderSelectedChips(selectedCourses);
    }

    // Render Jadwal pada Grid Timetable Mingguan
    function renderVisualTimetable(courses, conflictIds) {
        // Kosongkan seluruh slot
        const allSlots = document.querySelectorAll('.timetable-grid td:not(.time-col)');
        allSlots.forEach(td => td.innerHTML = '');

        const slotDefinitions = [
            { idSuffix: '0800', start: timeToMinutes('08:00'), end: timeToMinutes('10:30') },
            { idSuffix: '1030', start: timeToMinutes('10:30'), end: timeToMinutes('13:00') },
            { idSuffix: '1300', start: timeToMinutes('13:00'), end: timeToMinutes('15:30') },
            { idSuffix: '1530', start: timeToMinutes('15:30'), end: timeToMinutes('18:00') },
        ];

        courses.forEach(function(c) {
            const isConflict = conflictIds.has(c.id);
            let colorCls = 'block-blue';
            if (c.jenis === 'Jurusan') colorCls = 'block-purple';
            else if (c.jenis === 'Luar Jurusan') colorCls = 'block-orange';
            else if (c.jenis === 'Umum') colorCls = 'block-green';

            if (isConflict) colorCls = 'block-conflict';

            const blockHtml = `
                <div class="schedule-block ${colorCls}" title="${c.kode}: ${c.nama} (${c.mulai}-${c.selesai})">
                    <div style="font-weight: 800;">${isConflict ? '⚠️ BENTROK: ' : ''}${c.kode}</div>
                    <div style="font-size: 10.5px; opacity: 0.95;">${c.mulai}-${c.selesai}</div>
                    <div style="font-size: 10px; opacity: 0.85;">${c.ruangan}</div>
                </div>
            `;

            // Cari slot yang tumpang tindih
            slotDefinitions.forEach(function(slot) {
                if (isOverlapping(c.mulaiMenit, c.selesaiMenit, slot.start, slot.end)) {
                    const targetTd = document.getElementById('slot-' + c.hari + '-' + slot.idSuffix);
                    if (targetTd) {
                        targetTd.innerHTML += blockHtml;
                    }
                }
            });
        });
    }

    // Render Chips Mata Kuliah Terpilih
    function renderSelectedChips(courses) {
        countSelectedBadge.textContent = courses.length + ' MK Dipilih';
        if (courses.length === 0) {
            emptyChipsText.style.display = 'block';
            selectedChipsContainer.innerHTML = '<small class="text-muted d-block text-center py-2" id="emptyChipsText">Belum ada mata kuliah dicentang.</small>';
            return;
        }

        let chipsHtml = '';
        courses.forEach(function(c) {
            const badgeLuar = (c.jenis === 'Luar Jurusan') 
                ? `<span class="badge badge-warning text-white ml-1" style="font-size: 10px; padding: 2px 5px; border-radius: 4px;">Luar Prodi (${c.prodi})</span>` 
                : '';
            chipsHtml += `
                <span class="chip-course">
                    <span class="mr-1"><strong>${c.kode}</strong> &bull; ${c.sks} SKS${badgeLuar}</span>
                    <span class="chip-remove" onclick="uncheckCourse(${c.id})" title="Batalkan Pilihan">&times;</span>
                </span>
            `;
        });
        selectedChipsContainer.innerHTML = chipsHtml;
    }

    // Uncheck helper via chip
    window.uncheckCourse = function(courseId) {
        const targetCb = document.querySelector(`.course-checkbox[data-id="${courseId}"]`);
        if (targetCb && !targetCb.disabled) {
            targetCb.checked = false;
            evaluateKrsState();
        }
    };

    // Event listener pada checkbox
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', evaluateKrsState);
    });

    // -------------------------------------------------------------
    // Filter Kategori (Wajib, Jurusan, Luar Jurusan, Umum, Ganjil, Genap)
    // -------------------------------------------------------------
    const tabPills = document.querySelectorAll('.krs-tab-pill');
    const selectSemesterLevel = document.getElementById('selectSemesterLevel');
    const searchCourseInput = document.getElementById('searchCourseInput');

    let currentFilterType = 'all';
    let currentFilterSemester = 'all';
    let currentSemesterLevel = 'all';
    let currentSearchKeyword = '';

    function filterTableRows() {
        const rows = document.querySelectorAll('.course-row');
        rows.forEach(function(row) {
            const rJenis = row.dataset.jenis;
            const rPeriode = row.dataset.periode;
            const rSem = row.dataset.semester;
            const rText = (row.dataset.kode + ' ' + row.dataset.nama).toLowerCase();

            let matchType = (currentFilterType === 'all' || rJenis === currentFilterType);
            let matchPeriod = (currentFilterSemester === 'all' || rPeriode === currentFilterSemester);
            let matchLevel = (currentSemesterLevel === 'all' || rSem === currentSemesterLevel);
            let matchSearch = (currentSearchKeyword === '' || rText.includes(currentSearchKeyword));

            if (matchType && matchPeriod && matchLevel && matchSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    tabPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            tabPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            if (this.dataset.filterType !== undefined) {
                currentFilterType = this.dataset.filterType;
                currentFilterSemester = 'all';
            } else if (this.dataset.filterSemester !== undefined) {
                currentFilterSemester = this.dataset.filterSemester;
                currentFilterType = 'all';
            }
            filterTableRows();
        });
    });

    if (selectSemesterLevel) {
        selectSemesterLevel.addEventListener('change', function() {
            currentSemesterLevel = this.value;
            filterTableRows();
        });
    }

    if (searchCourseInput) {
        searchCourseInput.addEventListener('input', function() {
            currentSearchKeyword = this.value.trim().toLowerCase();
            filterTableRows();
        });
    }

    // Jalankan inisialisasi awal saat halaman selesai dimuat
    evaluateKrsState();
});
</script>

<!-- MODAL PILIH DOSEN WALI -->
<div class="modal fade" id="modalPilihDosenWali" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
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
                        Pilih Dosen Wali Anda untuk meninjau dan menyetujui Kartu Rencana Studi (KRS).
                    </p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-dark">Dosen Wali:</label>
                        <select name="dosen_id" class="form-control" required style="border-radius: 8px; height: 44px;">
                            <option value="">-- Pilih Dosen Wali --</option>
                            <?php if (!empty($all_lecturers)): ?>
                                <?php foreach ($all_lecturers as $lec): ?>
                                    <option value="<?= (int)$lec->id ?>" <?= (!empty($advisor) && (int)$advisor->dosen_id === (int)$lec->id) ? 'selected' : '' ?>>
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
                        <i class="fa fa-save mr-1"></i> Simpan Dosen Wali
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
