<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Custom Styles for Dashboard Biro Akademik -->
                    <style>
                        .akademik-hero {
                            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
                            border-radius: 16px;
                            padding: 28px 32px;
                            color: #ffffff;
                            margin-bottom: 24px;
                            position: relative;
                            overflow: hidden;
                            box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.25);
                        }
                        .akademik-hero::before {
                            content: '';
                            position: absolute;
                            top: -40px;
                            right: -40px;
                            width: 200px;
                            height: 200px;
                            border-radius: 50%;
                            background: rgba(255, 255, 255, 0.08);
                            pointer-events: none;
                        }
                        .akademik-stat-card {
                            background: #ffffff;
                            border-radius: 14px;
                            padding: 20px 24px;
                            border: 1px solid rgba(226, 232, 240, 0.9);
                            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
                            transition: transform 0.2s ease, box-shadow 0.2s ease;
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                        }
                        .akademik-stat-card:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
                        }
                        .stat-icon-wrapper {
                            width: 48px;
                            height: 48px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 20px;
                        }
                        .quick-link-tile {
                            background: #ffffff;
                            border: 1px solid #e2e8f0;
                            border-radius: 14px;
                            padding: 20px;
                            transition: all 0.2s ease;
                            text-decoration: none !important;
                            color: inherit;
                            display: flex;
                            align-items: flex-start;
                            gap: 16px;
                            height: 100%;
                        }
                        .quick-link-tile:hover {
                            background: #f8fafc;
                            border-color: #10b981;
                            transform: translateY(-2px);
                            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.08);
                        }
                        .quick-link-icon {
                            width: 44px;
                            height: 44px;
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 18px;
                            flex-shrink: 0;
                        }
                        /* Responsive Table Styles */
                        .table-responsive-akademik {
                            overflow-x: auto;
                            -webkit-overflow-scrolling: touch;
                        }
                        .table-responsive-akademik::-webkit-scrollbar {
                            height: 6px;
                        }
                        .table-responsive-akademik::-webkit-scrollbar-track {
                            background: #f1f5f9;
                            border-radius: 4px;
                        }
                        .table-responsive-akademik::-webkit-scrollbar-thumb {
                            background: #cbd5e1;
                            border-radius: 4px;
                        }
                        .table-responsive-akademik::-webkit-scrollbar-thumb:hover {
                            background: #94a3b8;
                        }
                        .table-penawaran-dashboard {
                            min-width: 960px;
                            vertical-align: middle;
                        }
                        .table-penawaran-dashboard th,
                        .table-penawaran-dashboard td {
                            vertical-align: middle !important;
                        }
                        .table-penawaran-dashboard .badge-mk-code {
                            background: #f1f5f9;
                            border: 1px solid #e2e8f0;
                            color: #64748b;
                            font-family: monospace;
                            font-size: 11px;
                            padding: 2px 7px;
                            border-radius: 4px;
                            display: inline-block;
                        }
                    </style>

                    <!-- Hero Banner -->
                    <div class="akademik-hero">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-light text-dark font-weight-bold px-3 py-1 mr-2" style="border-radius: 20px; font-size: 12px;">
                                        <i class="fa fa-calendar mr-1 text-emerald-600"></i> Periode Aktif: <?= html_escape($active_period['semester_akademik']) ?> <?= html_escape($active_period['tahun_akademik']) ?>
                                    </span>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="border-radius: 20px; font-size: 11px;">
                                        Biro Akademik
                                    </span>
                                </div>
                                <h3 class="font-weight-bold mb-2 text-white" style="letter-spacing: -0.5px;">Pusat Kendali Biro Akademik</h3>
                                <p class="text-white-50 mb-0" style="max-width: 620px; font-size: 14px; line-height: 1.5;">
                                    Monitoring operasional perkuliahan, penawaran mata kuliah, penetapan Dosen Wali, verifikasi KHS &amp; SKPI, serta distribusi kurikulum terpadu.
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-right">
                                <a href="<?= base_url('admin/akademik/operasional') ?>" class="btn btn-light text-emerald-800 font-weight-bold px-4 py-2.5 shadow-sm" style="border-radius: 10px;">
                                    <i class="fa fa-graduation-cap mr-1.5"></i> Operasional Akademik
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                            <i class="fa fa-check-circle mr-1"></i> <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                            <i class="fa fa-exclamation-triangle mr-1"></i> <?= html_escape($this->session->flashdata('error')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Stat KPI Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="akademik-stat-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">Mahasiswa Terdaftar</span>
                                        <h3 class="font-weight-bold text-dark mt-1 mb-0"><?= number_format($total_mahasiswa, 0, ',', '.') ?></h3>
                                    </div>
                                    <div class="stat-icon-wrapper" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="small text-muted">Akun aktif sistem</span>
                                    <a href="<?= base_url('admin/pengguna') ?>" class="small font-weight-bold text-primary">Lihat Data &rarr;</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="akademik-stat-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">Dosen Pengampu</span>
                                        <h3 class="font-weight-bold text-dark mt-1 mb-0"><?= number_format($total_dosen, 0, ',', '.') ?></h3>
                                    </div>
                                    <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="small text-muted">Tenaga Pengajar &amp; Wali</span>
                                    <span class="badge badge-success px-2 py-1" style="border-radius: 6px;">Aktif</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="akademik-stat-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">Penawaran MK</span>
                                        <h3 class="font-weight-bold text-dark mt-1 mb-0"><?= number_format($total_penawaran, 0, ',', '.') ?></h3>
                                    </div>
                                    <div class="stat-icon-wrapper" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5;">
                                        <i class="fa fa-book"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="small text-muted">Kelas perkuliahan aktif</span>
                                    <a href="<?= base_url('admin/akademik/operasional') ?>" class="small font-weight-bold text-indigo">Kelola &rarr;</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="akademik-stat-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">Antrean SKPI</span>
                                        <h3 class="font-weight-bold text-dark mt-1 mb-0"><?= (int)$skpi_waiting ?></h3>
                                    </div>
                                    <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                                        <i class="fa fa-certificate"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="small text-muted">Menunggu validasi</span>
                                    <a href="<?= base_url('admin/skpi') ?>" class="small font-weight-bold text-warning font-weight-bold">Proses &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shortcut Grid -->
                    <div class="card mb-4" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <div class="card-header bg-white" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                            <h5 class="font-weight-bold text-dark mb-0">Navigasi Cepat Modul Akademik</h5>
                            <span class="text-muted small">Akses langsung ke seluruh layanan operasional dan pendataan akademik.</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('admin/akademik/operasional') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                                            <i class="fa fa-graduation-cap"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">Operasional Akademik</h6>
                                            <p class="text-muted small mb-0">Input penawaran mata kuliah semester dan penetapan Dosen Wali mahasiswa.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('akademik/khs') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(14, 165, 233, 0.1); color: #0284c7;">
                                            <i class="fa fa-file-text-o"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">KHS Mahasiswa</h6>
                                            <p class="text-muted small mb-0">Direktori dan filter KHS mahasiswa per program studi, angkatan, serta cetak rapor.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('akademik/transkrip?tab=obe') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(99, 102, 241, 0.1); color: #4f46e5;">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">Transkrip Akademik &amp; OBE</h6>
                                            <p class="text-muted small mb-0">Pantau rekapitulasi kumulatif nilai dan pemenuhan ketercapaian CPL &amp; CPMK kurikulum OBE.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('admin/katalog-akademik') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(234, 88, 12, 0.1); color: #c2410c;">
                                            <i class="fa fa-sitemap"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">Master Katalog Akademik</h6>
                                            <p class="text-muted small mb-0">Kelola master kurikulum, struktur mata kuliah, dan rekonsiliasi paket semester.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('admin/pengguna') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">Data Mahasiswa</h6>
                                            <p class="text-muted small mb-0">Direktori data mahasiswa, status semester, persetujuan semester pendek.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="<?= base_url('admin/skpi') ?>" class="quick-link-tile">
                                        <div class="quick-link-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                                            <i class="fa fa-certificate"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">Validasi SKPI</h6>
                                            <p class="text-muted small mb-0">Verifikasi dokumen portofolio &amp; sertifikat Surat Keterangan Pendamping Ijazah.</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Penawaran Terbaru -->
                    <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px; gap: 10px;">
                            <div>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    <h5 class="font-weight-bold text-dark mb-0">Penawaran Mata Kuliah Terbaru</h5>
                                    <span class="badge badge-light border text-muted d-inline-block d-xl-none" style="font-size: 11px; font-weight: 500;">
                                        <i class="fa fa-arrows-h mr-1 text-emerald-600"></i>Geser tabel
                                    </span>
                                </div>
                                <span class="text-muted small">Daftar kelas perkuliahan aktif pada periode berjalan.</span>
                            </div>
                            <a href="<?= base_url('admin/akademik/operasional') ?>" class="btn btn-sm btn-outline-success font-weight-bold px-3 py-1.5" style="border-radius: 8px;">
                                Kelola di Operasional &rarr;
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive table-responsive-akademik">
                                <table class="table table-hover table-striped mb-0 table-penawaran-dashboard">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 230px; width: 25%; white-space: nowrap;">Mata Kuliah</th>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 150px; width: 16%; white-space: nowrap;">Program Studi</th>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 130px; width: 14%; white-space: nowrap;">SKS / Kelas</th>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 200px; width: 20%; white-space: nowrap;">Jadwal &amp; Ruangan</th>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 180px; width: 18%; white-space: nowrap;">Dosen Pengampu</th>
                                            <th style="padding: 14px 18px; font-size: 13px; min-width: 90px; width: 7%; text-align: center; white-space: nowrap;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recent_offerings)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    Belum ada data penawaran mata kuliah. Buka menu Operasional Akademik untuk menambahkan penawaran baru.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recent_offerings as $off): ?>
                                                <tr>
                                                    <td style="padding: 14px 18px; min-width: 230px;">
                                                        <strong class="text-dark d-block mb-1" style="font-size: 13.5px; line-height: 1.4;"><?= html_escape($off->nama_mk) ?></strong>
                                                        <span class="badge-mk-code"><?= html_escape($off->kode_mk) ?></span>
                                                    </td>
                                                    <td style="padding: 14px 18px; min-width: 150px;">
                                                        <div class="d-flex flex-column align-items-start" style="gap: 4px;">
                                                            <span class="badge badge-light border text-dark font-weight-bold" style="font-size: 11.5px; padding: 4px 8px; border-radius: 6px;"><?= html_escape($off->prodi) ?></span>
                                                            <span class="small text-muted font-weight-500">Semester <?= (int)$off->semester ?></span>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 14px 18px; min-width: 130px;">
                                                        <div class="d-inline-flex flex-column align-items-start" style="gap: 5px;">
                                                            <span class="badge badge-primary px-2.5 py-1 font-weight-bold text-nowrap" style="border-radius: 6px; font-size: 11px; letter-spacing: 0.3px;"><?= (int)$off->sks ?> SKS</span>
                                                            <span class="badge badge-secondary px-2.5 py-1 font-weight-bold text-nowrap" style="border-radius: 6px; font-size: 11px; letter-spacing: 0.3px;">Kelas <?= html_escape($off->kelas) ?></span>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 14px 18px; min-width: 200px;">
                                                        <div class="font-weight-bold text-dark text-nowrap" style="font-size: 13px;">
                                                            <?= html_escape($off->hari) ?>, <?= html_escape(substr($off->waktu_mulai, 0, 5)) ?> &ndash; <?= html_escape(substr($off->waktu_selesai, 0, 5)) ?> WIB
                                                        </div>
                                                        <div class="small text-muted mt-1 text-nowrap">
                                                            <i class="fa fa-map-marker mr-1 text-danger"></i><?= html_escape($off->ruangan) ?>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 14px 18px; min-width: 180px;">
                                                        <div class="font-weight-bold text-dark" style="font-size: 13px; line-height: 1.35;"><?= html_escape($off->nama_dosen ?? '-') ?></div>
                                                        <div class="small text-muted mt-1 font-monospace" style="font-size: 11px;">NIDN/NIP: <?= html_escape($off->nip_dosen ?? $off->nim ?? '-') ?></div>
                                                    </td>
                                                    <td style="padding: 14px 18px; min-width: 90px; text-align: center; white-space: nowrap;">
                                                        <?php if ((int)$off->aktif === 1): ?>
                                                            <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 20px; font-size: 11px; letter-spacing: 0.3px;">Aktif</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary px-3 py-1 font-weight-bold" style="border-radius: 20px; font-size: 11px; letter-spacing: 0.3px;">Non-aktif</span>
                                                        <?php endif; ?>
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
