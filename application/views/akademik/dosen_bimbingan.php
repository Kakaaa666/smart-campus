<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content" style="padding-top: 15px;">
        <div class="main-body">
            <div class="page-wrapper" style="padding: 24px;">
                <div class="page-body">

                    <!-- FLASH NOTIFICATIONS -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981;">
                            <i class="fa fa-check-circle mr-2 text-success"></i>
                            <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- BANNER HEADER MAHASISWA BIMBINGAN -->
                    <div class="card shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; background: #ffffff;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                            <i class="fa fa-users text-primary mr-1"></i> PERWALIAN AKADEMIK
                                        </span>
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                            <i class="fa fa-user-md mr-1"></i> DOSEN WALI
                                        </span>
                                    </div>
                                    <h4 class="font-weight-bold mb-1" style="color: #0f172a !important;">Daftar Mahasiswa Bimbingan</h4>
                                    <p class="mb-0" style="font-size: 14px; color: #64748b !important;">
                                        Mahasiswa yang memilih Anda sebagai Dosen Wali untuk pendampingan rencana studi dan perkuliahan.
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                    <div class="d-inline-block text-dark p-3 text-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; min-width: 140px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                        <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">TOTAL BIMBINGAN</small>
                                        <span class="font-weight-bold text-primary" style="font-size: 28px; line-height: 1.2;">
                                            <?= count($students) ?>
                                        </span>
                                        <small class="text-muted d-block" style="font-size: 10px;">Mahasiswa Terdaftar</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD TABEL MAHASISWA -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 16px;">
                                    <i class="fa fa-graduation-cap text-primary mr-2"></i> Mahasiswa Asuhan Dosen Wali
                                </h5>
                                <small class="text-muted">Total <?= count($students) ?> mahasiswa di bawah perwalian Anda</small>
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                <?php if (!empty($is_admin) && !empty($all_lecturers)): ?>
                                    <form method="GET" action="<?= base_url('dosen/mahasiswa-bimbingan') ?>" class="form-inline">
                                        <label class="small text-muted font-weight-bold mr-2">Pilih Dosen Wali:</label>
                                        <select name="dosen_id" class="form-control form-control-sm" onchange="this.form.submit()" style="border-radius: 8px; min-width: 200px;">
                                            <option value="0" <?= ($selected_dosen == 0) ? 'selected' : '' ?>>-- Semua Dosen Wali --</option>
                                            <?php foreach ($all_lecturers as $lec): ?>
                                                <option value="<?= (int)$lec->id ?>" <?= ($selected_dosen == $lec->id) ? 'selected' : '' ?>>
                                                    <?= html_escape($lec->nama_lengkap) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                <?php endif; ?>
                                <a href="<?= base_url('dosen/persetujuan-krs') ?>" class="btn btn-warning btn-sm font-weight-bold px-3" style="border-radius: 8px; color: #1e1b4b;">
                                    <i class="fa fa-check-square-o mr-1"></i> Halaman Validasi KRS &raquo;
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="vertical-align: middle;">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700; width: 60px;">No</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700;">Mahasiswa</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700;">Program Studi</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700; text-align: center;">Semester</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700; text-align: center;">Beban SKS</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700; text-align: center;">Status KRS</th>
                                            <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; color: #475569; font-weight: 700; text-align: center; width: 140px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($students)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <div style="font-size: 40px; color: #cbd5e1; margin-bottom: 10px;">
                                                        <i class="fa fa-folder-open-o"></i>
                                                    </div>
                                                    <h6 class="font-weight-bold text-dark mb-1">Belum Ada Mahasiswa Bimbingan</h6>
                                                    <p class="small text-muted mb-0">Belum ada mahasiswa yang memilih Anda sebagai Dosen Wali.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($students as $student): ?>
                                                <?php
                                                    $initial = strtoupper(substr($student->nama_lengkap ?? 'M', 0, 1));
                                                    $krs_stat = strtoupper($student->status_krs ?? '');
                                                    $badge_cls = 'badge-secondary';
                                                    $stat_label = 'Belum Mengajukan';
                                                    if ($krs_stat === 'DISETUJUI') {
                                                        $badge_cls = 'badge-success';
                                                        $stat_label = 'Disetujui';
                                                    } elseif ($krs_stat === 'MENUNGGU') {
                                                        $badge_cls = 'badge-warning text-dark';
                                                        $stat_label = 'Menunggu Validasi';
                                                    } elseif ($krs_stat === 'DITOLAK') {
                                                        $badge_cls = 'badge-danger';
                                                        $stat_label = 'Perlu Revisi';
                                                    } elseif ($krs_stat === 'DRAFT') {
                                                        $badge_cls = 'badge-info';
                                                        $stat_label = 'Draf Pengajuan';
                                                    }
                                                ?>
                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                    <td style="padding: 16px 20px; color: #64748b; font-weight: 600;"><?= $no++ ?></td>
                                                    <td style="padding: 16px 20px;">
                                                        <div class="d-flex align-items-center">
                                                            <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 15px; margin-right: 12px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(59,130,246,0.3);">
                                                                <?= $initial ?>
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold text-dark" style="font-size: 14px;"><?= html_escape($student->nama_lengkap) ?></div>
                                                                <small class="text-muted font-monospace" style="font-size: 12px;">NIM: <?= html_escape($student->nim) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 16px 20px;">
                                                        <span class="badge badge-light text-dark border px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 12px;">
                                                            <?= html_escape($student->prodi ?: 'Teknik Informatika') ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 16px 20px; text-align: center;">
                                                        <span class="badge badge-primary px-2 py-1" style="border-radius: 6px; font-size: 11px;">
                                                            Semester <?= (int)$student->semester ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 16px 20px; text-align: center;">
                                                        <strong class="text-dark" style="font-size: 14px;"><?= (int)$student->total_sks ?></strong>
                                                        <small class="text-muted">SKS</small>
                                                    </td>
                                                    <td style="padding: 16px 20px; text-align: center;">
                                                        <span class="badge <?= $badge_cls ?> px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px;">
                                                            <?= $stat_label ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 16px 20px; text-align: center;">
                                                        <a href="<?= base_url('dosen/persetujuan-krs?q=' . urlencode($student->nim)) ?>" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px; font-size: 12px; font-weight: 600;">
                                                            <i class="fa fa-search mr-1"></i> Tinjau KRS
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

                </div>
            </div>
        </div>
    </div>
</div>
