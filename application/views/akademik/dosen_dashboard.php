<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.dash-metric-card {
    border-radius: 16px;
    border: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    overflow: hidden;
    position: relative;
    background: #ffffff;
}
.dash-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.dash-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.quick-action-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    transition: all 0.2s ease;
    text-decoration: none !important;
    display: block;
    padding: 20px;
    height: 100%;
}
.quick-action-card:hover {
    border-color: #6366f1;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.12);
}
.guide-step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e0e7ff;
    color: #4338ca;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 14px;
    flex-shrink: 0;
}
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- FLASH MESSAGE NOTIFICATIONS -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981;">
                            <i class="fa fa-check-circle mr-2 text-success"></i>
                            <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444;">
                            <i class="fa fa-exclamation-triangle mr-2 text-danger"></i>
                            <?= html_escape($this->session->flashdata('error')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- WELCOME BANNER DOSEN -->
                    <div class="card shadow-sm mb-4" style="border-radius: 18px; border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; background: #ffffff;">
                        <div class="card-body p-4 p-md-5">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                            <i class="fa fa-university mr-1"></i> PORTAL DOSEN & AKADEMIK
                                        </span>
                                        <span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 11px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                            <i class="fa fa-calendar mr-1"></i> TA <?= html_escape($academic_period['tahun_akademik']) ?> (<?= strtoupper(html_escape($academic_period['semester_akademik'])) ?>)
                                        </span>
                                    </div>
                                    <h3 class="font-weight-bold mb-2" style="color: #0f172a !important;">Selamat Datang di Portal Dosen</h3>
                                    <p class="mb-4" style="font-size: 15px; max-width: 600px; line-height: 1.6; color: #64748b !important;">
                                        Pusat kontrol dan monitoring aktivitas akademik perguruan tinggi. Dosen Wali dapat memvalidasi pengajuan Kartu Rencana Studi (KRS) mahasiswa bimbingan perwalian, mengelola perkuliahan, serta menginput penilaian hasil belajar.
                                    </p>
                                    <div class="d-flex flex-wrap" style="gap: 8px;">
                                        <a href="<?= base_url('dosen/persetujuan-krs') ?>" class="btn btn-warning font-weight-bold px-4 py-2" style="border-radius: 10px; color: #1e1b4b; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                                            <i class="fa fa-check-square-o mr-1"></i> Validasi KRS Bimbingan
                                            <?php if ((int)$stats['pending'] > 0): ?>
                                                <span class="badge badge-danger ml-1" style="border-radius: 8px;"><?= (int)$stats['pending'] ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <a href="<?= base_url('dosen/isi-nilai') ?>" class="btn btn-outline-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
                                            <i class="fa fa-pencil-square-o mr-1"></i> Pengisian Nilai
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                                    <div class="p-3 text-dark shadow-sm d-inline-block text-left w-100" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; max-width: 320px;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 18px;" class="mr-3">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted font-weight-bold">BIMBINGAN MENUNGGU</div>
                                                <h4 class="font-weight-bold text-danger mb-0"><?= (int)$stats['pending'] ?> Mahasiswa</h4>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>KRS Disetujui:</span>
                                            <strong class="text-success"><?= (int)($stats['approved'] ?? 0) ?> Mahasiswa</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted mt-1">
                                            <span>Perlu Revisi:</span>
                                            <strong class="text-danger"><?= (int)($stats['rejected'] ?? 0) ?> Mahasiswa</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RINGKASAN METRIK KRS MAHASISWA BIMBINGAN -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card dash-metric-card border-left border-warning" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Validasi</div>
                                        <div class="h3 font-weight-bold text-warning mb-0"><?= (int)($stats['pending'] ?? 0) ?></div>
                                        <small class="text-muted">Bimbingan Anda</small>
                                    </div>
                                    <div class="dash-icon-box bg-warning text-white">
                                        <i class="fa fa-hourglass-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card dash-metric-card border-left border-success" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">KRS Disetujui</div>
                                        <div class="h3 font-weight-bold text-success mb-0"><?= (int)($stats['approved'] ?? 0) ?></div>
                                        <small class="text-muted">Rencana studi sah</small>
                                    </div>
                                    <div class="dash-icon-box bg-success text-white">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card dash-metric-card border-left border-danger" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Perlu Revisi</div>
                                        <div class="h3 font-weight-bold text-danger mb-0"><?= (int)($stats['rejected'] ?? 0) ?></div>
                                        <small class="text-muted">Dikembalikan ke mahasiswa</small>
                                    </div>
                                    <div class="dash-icon-box bg-danger text-white">
                                        <i class="fa fa-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card dash-metric-card border-left border-primary" style="border-left-width: 4px !important;">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Total Pengajuan</div>
                                        <div class="h3 font-weight-bold text-primary mb-0"><?= (int)($stats['total'] ?? 0) ?></div>
                                        <small class="text-muted">Bimbingan Semester Aktif</small>
                                    </div>
                                    <div class="dash-icon-box bg-primary text-white">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MENU UTAMA DOSEN & PANDUAN VALIDASI -->
                    <div class="row">
                        <!-- KOLOM MENU PINTASAN DOSEN -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="font-weight-bold text-dark mb-0">
                                        <i class="fa fa-th-large text-primary mr-2"></i> Menu & Layanan Dosen
                                    </h5>
                                    <small class="text-muted">Pilih modul kerja akademik yang ingin Anda kelola</small>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <a href="<?= base_url('dosen/persetujuan-krs') ?>" class="quick-action-card">
                                                <div class="d-flex align-items-start">
                                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 20px;" class="mr-3 flex-shrink-0">
                                                        <i class="fa fa-check-square-o"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-weight-bold text-dark mb-1">Persetujuan KRS</h6>
                                                        <p class="text-muted small mb-0">Tinjau & setujui Kartu Rencana Studi mahasiswa bimbingan Anda.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="<?= base_url('dosen/isi-nilai') ?>" class="quick-action-card">
                                                <div class="d-flex align-items-start">
                                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 20px;" class="mr-3 flex-shrink-0">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-weight-bold text-dark mb-1">Pengisian Nilai</h6>
                                                        <p class="text-muted small mb-0">Input nilai tugas, kuis, UTS, dan UAS mata kuliah.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="<?= base_url('dosen/mahasiswa-bimbingan') ?>" class="quick-action-card">
                                                <div class="d-flex align-items-start">
                                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 20px;" class="mr-3 flex-shrink-0">
                                                        <i class="fa fa-users"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-weight-bold text-dark mb-1">Mahasiswa Bimbingan</h6>
                                                        <p class="text-muted small mb-0">Lihat data perkembangan studi mahasiswa binaan.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="<?= base_url('dosen/revisi-nilai') ?>" class="quick-action-card">
                                                <div class="d-flex align-items-start">
                                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fae8ff; color: #a21caf; display: flex; align-items: center; justify-content: center; font-size: 20px;" class="mr-3 flex-shrink-0">
                                                        <i class="fa fa-refresh"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-weight-bold text-dark mb-1">Revisi Nilai</h6>
                                                        <p class="text-muted small mb-0">Ajukan atau tinjau perbaikan nilai hasil ujian.</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM PANDUAN VALIDASI KRS -->
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="font-weight-bold text-dark mb-0">
                                        <i class="fa fa-info-circle text-info mr-2"></i> Prosedur Validasi KRS
                                    </h5>
                                    <small class="text-muted">Pedoman persetujuan rencana studi</small>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="guide-step-number">1</div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Periksa Kuota & SKS</h6>
                                            <p class="text-muted small mb-0">Pastikan total SKS yang diambil tidak melampaui batas maksimal beban studi semester ini.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="guide-step-number">2</div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Cek Keuangan & Jadwal</h6>
                                            <p class="text-muted small mb-0">Perhatikan status pembayaran SPP (lunas/dispensasi) dan pastikan tidak ada jadwal kuliah yang bentrok.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="guide-step-number">3</div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Persetujuan / Catatan</h6>
                                            <p class="text-muted small mb-0">Klik tombol <strong>Setujui</strong> jika sesuai, atau <strong>Tolak</strong> disertai catatan revisi agar mahasiswa dapat memperbaikinya.</p>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-lightbulb-o text-warning mr-1"></i> <strong>Kewenangan Dosen Wali:</strong>
                                        </small>
                                        <small class="text-muted d-block" style="font-size: 11px; line-height: 1.5;">
                                            Fitur persetujuan KRS ini dikhususkan bagi Dosen Wali untuk memvalidasi dan menyetujui rencana studi dari mahasiswa bimbingan masing-masing demi tertibnya kurikulum dan capaian SKS mahasiswa.
                                        </small>
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
