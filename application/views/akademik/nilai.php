<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$student = $nilai_data['student'] ?? null;
$courses = $nilai_data['courses'] ?? [];
$available_periods = $nilai_data['available_periods'] ?? [];
$selected_period = $nilai_data['selected_period'] ?? null;

$is_admin = !empty($is_admin);
$is_dosen = !empty($is_dosen);
$all_students = $all_students ?? [];
$selected_student_id = $selected_student_id ?? ($student ? (int)$student->id : 0);
?>

<style>
/* ======================================================== */
/* HALAMAN NILAI PERKULIAHAN - PERSIS SESUAI FOTO SIAKAD    */
/* ======================================================== */

.nilai-card {
    border-radius: 10px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
    overflow: hidden;
    margin-bottom: 25px;
}

/* Header Biru Tosca Muda dengan Ikon Hati sesuai Foto */
.nilai-card-header {
    background: linear-gradient(135deg, #29b6f6 0%, #0288d1 100%) !important;
    padding: 14px 22px !important;
    border-bottom: none !important;
}

.nilai-card-header h5 {
    color: #ffffff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    margin: 0 !important;
    letter-spacing: 0.2px;
}

/* Controls Bar */
.nilai-controls-bar {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 16px 22px;
}

.nilai-filter-select {
    border-radius: 10px !important;
    border: 1.5px solid #bae6fd !important;
    font-size: 14px !important;
    height: 38px !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    padding: 6px 14px !important;
    box-shadow: none !important;
    font-weight: 500;
}

.nilai-filter-select:focus {
    border-color: #0288d1 !important;
    box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15) !important;
}

.nilai-btn-print {
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

.nilai-btn-print:hover {
    background: #f8fafc !important;
    color: #0288d1 !important;
    border-color: #0288d1 !important;
}

/* Tabel Nilai */
.table-nilai-wrapper {
    padding: 10px 22px 25px;
}

.table-nilai {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    min-width: 880px; /* Cegah kolom terhimpit saat layar menyempit */
}

.table-nilai thead th {
    background-color: #ffffff !important;
    color: #1e293b !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    padding: 14px 16px !important;
    border-top: none !important;
    border-bottom: 2px solid #e2e8f0 !important;
    vertical-align: middle;
    white-space: nowrap;
}

.table-nilai tbody td {
    padding: 14px 16px !important;
    font-size: 13.5px !important;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    line-height: 1.55;
}

.table-nilai tbody tr:hover {
    background-color: #f8fafc;
}

/* Badge Grade Nilai */
.badge-grade {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 12.5px;
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
    .nilai-controls-bar,
    .breadcrumbs,
    .btn,
    footer,
    .admin-student-selector,
    .alert-semester-info {
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
    .nilai-card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
    }
    .nilai-card-header {
        background: #ffffff !important;
        border-bottom: 2px solid #000000 !important;
        padding: 0 0 10px 0 !important;
    }
    .nilai-card-header h5 {
        color: #000000 !important;
        font-size: 18px !important;
        text-align: center;
        text-transform: uppercase;
    }
    .table-nilai thead th {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border: 1px solid #000000 !important;
    }
    .table-nilai tbody td {
        border: 1px solid #333333 !important;
        color: #000000 !important;
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

                    <!-- NILAI CONTAINER CARD -->
                    <div class="card nilai-card" id="nilaiContainerCard">
                        
                        <!-- Header Kartu: Biru Tosca dengan Ikon Hati (Persis Foto) -->
                        <div class="card-header nilai-card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-heart text-white mr-2" style="font-size: 18px;"></i>
                                <h5>Nilai</h5>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="text-white" onclick="toggleNilaiFullscreen()" title="Perbesar Tampilan">
                                    <i class="feather icon-maximize-2" style="font-size: 16px;"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Bar Filter & Tombol Cetak / Ekspor -->
                        <div class="nilai-controls-bar">
                            <form method="GET" action="<?= current_url() ?>" id="nilaiFilterForm" class="d-flex flex-wrap align-items-center justify-content-between">
                                
                                <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                                    
                                    <!-- Jika role Superadmin / Dosen: Pemilih Mahasiswa -->
                                    <?php if ($is_admin || $is_dosen): ?>
                                        <div class="d-flex align-items-center mr-3 mb-2 mb-sm-0 admin-student-selector">
                                            <label class="font-weight-bold mr-2 mb-0" style="white-space: nowrap; font-size: 13px; color: #334155;">
                                                <i class="feather icon-user mr-1 text-primary"></i> Mahasiswa:
                                            </label>
                                            <select name="student_id" class="form-control nilai-filter-select" onchange="document.getElementById('nilaiFilterForm').submit();" style="min-width: 250px;">
                                                <?php foreach ($all_students as $st): ?>
                                                    <option value="<?= $st->id ?>" <?= ((int)$st->id === (int)$selected_student_id) ? 'selected' : '' ?>>
                                                        <?= html_escape($st->nim) ?> - <?= html_escape($st->nama_lengkap) ?> (<?= html_escape($st->prodi) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Dropdown Periode Semester (Format sesuai Gambar 2: Semester X (Tahun / Semester) (Paket)) -->
                                    <div class="mr-2 mb-2 mb-sm-0">
                                        <select name="periode" class="form-control nilai-filter-select" onchange="document.getElementById('nilaiFilterForm').submit();" style="min-width: 240px;" <?= empty($available_periods) ? 'disabled' : '' ?>>
                                            <?php if (!empty($available_periods)): ?>
                                                <?php foreach ($available_periods as $p): ?>
                                                    <option value="<?= $p['key'] ?>" <?= ($p['key'] === ($selected_period['key'] ?? '')) ? 'selected' : '' ?>>
                                                        <?= html_escape($p['dropdown_label'] ?? ($p['label'] . (!empty($p['is_paket']) ? ' (Paket)' : ''))) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">-- Belum Ada Nilai Semester --</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                </div>

                                <!-- Tombol Cetak / Ekspor (Persis di kanan sesuai foto) -->
                                <div class="mb-2 mb-md-0">
                                    <button type="button" class="btn btn-outline-secondary nilai-btn-print d-flex align-items-center" onclick="window.print()">
                                        <i class="feather icon-printer mr-2"></i> Cetak / Ekspor
                                    </button>
                                </div>

                            </form>
                        </div>

                        <!-- Alert Penjelasan: Semester Aktif vs Nilai Selesai -->
                        <?php if ($student): ?>
                            <div class="mx-4 mt-3 mb-2 p-3 d-flex align-items-center alert-semester-info" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 20px !important;">
                                <div class="mr-3 text-success">
                                    <i class="feather icon-info" style="font-size: 22px;"></i>
                                </div>
                                <div style="font-size: 13.5px; color: #166534; line-height: 1.6;">
                                    <strong>Informasi Semester Mahasiswa:</strong> Mahasiswa (<strong><?= html_escape($student->nim) ?></strong> - <?= html_escape($student->nama_lengkap) ?>) saat ini aktif di <strong>Semester <?= (int)($student->semester ?? 1) ?></strong>.
                                    <?php if ((int)($student->semester ?? 1) > 1): ?>
                                        Nilai yang ditampilkan pada pilihan di atas merupakan nilai resmi dari semester yang telah selesai ditempuh (Semester 1 s/d <?= (int)$student->semester - 1 ?>).
                                    <?php else: ?>
                                        Sebagai mahasiswa baru Semester 1, nilai semester aktif akan muncul setelah masa evaluasi akhir semester selesai.
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tabel Nilai (Persis Kolom & Format Foto: No | Kode | Mata Kuliah | SKS | Kelas | Dosen | Nilai Angka | Grade Nilai) -->
                        <div class="table-nilai-wrapper table-responsive">
                            <table class="table table-nilai">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th style="width: 110px;">Kode</th>
                                        <th>Mata Kuliah</th>
                                        <th class="text-center" style="width: 65px;">SKS</th>
                                        <th class="text-center" style="width: 65px;">Kelas</th>
                                        <th>Dosen</th>
                                        <th class="text-center" style="width: 110px;">Nilai Angka</th>
                                        <th class="text-center" style="width: 100px;">Grade Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($courses)): ?>
                                        <?php foreach ($courses as $c): ?>
                                            <tr>
                                                <td class="text-muted"><?= $c->no ?></td>
                                                <td>
                                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 12px; font-family: monospace; letter-spacing: 0.5px;">
                                                        <?= html_escape($c->kode_mk) ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold" style="color: #0f172a; line-height: 1.45;">
                                                    <?= html_escape($c->nama_mk) ?>
                                                </td>
                                                <td class="text-center font-weight-bold text-primary"><?= (int)$c->sks ?></td>
                                                <td class="text-center font-weight-bold text-secondary"><?= html_escape($c->kelas ?? 'A') ?></td>
                                                <td style="color: #334155; line-height: 1.5;">
                                                    <?= html_escape(str_replace(',', ', ', str_replace(', ', ',', $c->dosen ?? '-'))) ?>
                                                </td>
                                                <td class="text-center font-weight-bold" style="color: #1e293b;">
                                                    <?= html_escape($c->display_angka ?? '-') ?>
                                                </td>
                                                <td class="text-center font-weight-bold">
                                                    <?php 
                                                        $grade = strtoupper(trim($c->grade_nilai ?? ''));
                                                        $badge_cls = 'badge-grade-a';
                                                        if (strpos($grade, 'B') !== false) $badge_cls = 'badge-grade-b';
                                                        elseif (strpos($grade, 'C') !== false) $badge_cls = 'badge-grade-c';
                                                        elseif (strpos($grade, 'D') !== false || strpos($grade, 'E') !== false) $badge_cls = 'badge-grade-d';
                                                    ?>
                                                    <span class="badge-grade <?= $badge_cls ?>"><?= html_escape($grade ?: '-') ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="feather icon-clock text-warning" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                                <span class="font-weight-bold" style="font-size: 14px; color: #334155;">Belum Ada Rekaman Nilai Perkuliahan</span>
                                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">
                                                    Mahasiswa sedang menempuh semester aktif ini. Nilai angka dan grade akan muncul setelah dosen menyelesaikan penilaian akhir semester.
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- TANDA TANGAN RESMI CETAK (HANYA MUNCUL SAAT PRINT) -->
                        <div class="print-only-signatures px-4">
                            <div class="row text-center mt-5" style="font-size: 12px;">
                                <div class="col-4">
                                    <p class="mb-5">Mahasiswa,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= strtoupper(html_escape($student->nama_lengkap ?? '')) ?></u></p>
                                    <p class="text-muted">NRP/NIM. <?= html_escape($student->nim ?? '') ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-5">Dosen Wali,</p>
                                    <br><br>
                                    <p class="font-weight-bold mb-0"><u><?= html_escape($student->nama_dosen_wali ?? 'Sugeng Widodo, S.Kom., M.Kom') ?></u></p>
                                    <p class="text-muted">NIDN. <?= html_escape($student->nip_dosen_wali ?? '-') ?></p>
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
function toggleNilaiFullscreen() {
    var elem = document.getElementById('nilaiContainerCard');
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
