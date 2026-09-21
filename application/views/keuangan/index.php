<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                
                <!-- Custom Styling Khusus Modul Keuangan Mahasiswa -->
                <style>
                .custom-card-white {
                    background-color: #ffffff !important;
                    background: #ffffff !important;
                    border-radius: 16px !important;
                    border: 1px solid #edf2f7 !important;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
                    overflow: hidden !important;
                    margin-bottom: 24px !important;
                    transition: all 0.25s ease-in-out;
                }

                .custom-card-header {
                    background-color: #ffffff !important;
                    border-bottom: 1px solid #f1f5f9 !important;
                    border-top-left-radius: 16px !important;
                    border-top-right-radius: 16px !important;
                    padding: 18px 24px !important;
                }

                .custom-card-header h5 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 700;
                    color: #1565c0;
                }

                /* Metric Stat Card */
                .fin-stat-card {
                    padding: 20px 22px;
                    border-radius: 14px;
                    border: 1px solid #eef2f6;
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }
                .fin-stat-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
                }
                .fin-stat-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 22px;
                }

                /* Nav Tabs Styling */
                .nav-tabs-keuangan {
                    border-bottom: 2px solid #e2e8f0;
                    padding: 0 20px;
                    background: #ffffff;
                }
                .nav-tabs-keuangan .nav-link {
                    border: none;
                    color: #64748b;
                    font-weight: 600;
                    font-size: 14px;
                    padding: 16px 20px;
                    margin-bottom: -2px;
                    border-bottom: 3px solid transparent;
                    transition: all 0.2s ease;
                }
                .nav-tabs-keuangan .nav-link i {
                    margin-right: 8px;
                    font-size: 16px;
                }
                .nav-tabs-keuangan .nav-link:hover {
                    color: #1976d2;
                    border-bottom-color: #90caf9;
                }
                .nav-tabs-keuangan .nav-link.active {
                    color: #1565c0;
                    font-weight: 700;
                    background: transparent;
                    border-bottom: 3px solid #1976d2;
                }

                /* Badges Status */
                .badge-status {
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 11.5px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    letter-spacing: 0.3px;
                }
                .badge-status i {
                    margin-right: 5px;
                    font-size: 13px;
                }
                .badge-belum-bayar {
                    background-color: #fee2e2;
                    color: #dc2626;
                    border: 1px solid #fca5a5;
                }
                .badge-pending {
                    background-color: #fef3c7;
                    color: #b45309;
                    border: 1px solid #fcd34d;
                }
                .badge-lunas {
                    background-color: #d1fae5;
                    color: #047857;
                    border: 1px solid #6ee7b7;
                }
                .badge-ditolak {
                    background-color: #ffe4e6;
                    color: #be123c;
                    border: 1px solid #fda4af;
                }

                /* Banner Simulasi Biaya */
                .banner-biaya-simulasi {
                    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #3b82f6 100%);
                    border-radius: 16px;
                    color: #ffffff;
                    padding: 24px 28px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 8px 24px rgba(30, 58, 138, 0.2);
                    margin-bottom: 24px;
                }
                .banner-biaya-simulasi::after {
                    content: "";
                    position: absolute;
                    top: -50px;
                    right: -50px;
                    width: 180px;
                    height: 180px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.08);
                    pointer-events: none;
                }
                .tag-simulasi {
                    background: #f59e0b;
                    color: #78350f;
                    font-size: 11px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.8px;
                    padding: 4px 10px;
                    border-radius: 6px;
                    display: inline-block;
                }

                /* Bank Card Rekening */
                .bank-rek-box {
                    border: 1.5px dashed #cbd5e1;
                    border-radius: 12px;
                    padding: 16px;
                    background: #f8fafc;
                    transition: border-color 0.2s ease, background 0.2s ease;
                }
                .bank-rek-box:hover {
                    border-color: #1976d2;
                    background: #f0f7ff;
                }

                /* Table Design */
                .table-keuangan thead th {
                    background-color: #f8fafc;
                    color: #475569;
                    font-weight: 700;
                    font-size: 12.5px;
                    border-top: none;
                    border-bottom: 1.5px solid #e2e8f0;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    padding: 14px 16px;
                }
                .table-keuangan tbody td {
                    vertical-align: middle;
                    padding: 14px 16px;
                    color: #334155;
                    font-size: 13.5px;
                    border-top: 1px solid #f1f5f9;
                }

                /* Realtime Floating Toast */
                #realtimeToast {
                    position: fixed;
                    bottom: 28px;
                    right: 28px;
                    z-index: 9999;
                    min-width: 320px;
                    max-width: 420px;
                    border-radius: 14px;
                    box-shadow: 0 12px 32px rgba(0,0,0,0.18);
                    display: none;
                    animation: slideUpFade 0.3s ease-out;
                }
                @keyframes slideUpFade {
                    from { transform: translateY(30px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
                </style>

                <!-- Toast Real-time Notifikasi -->
                <div id="realtimeToast" class="alert alert-success alert-dismissible p-3" role="alert" style="background:#10b981; color:#fff; border:none;">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="font-size:26px;"><i class="fa fa-bell"></i></div>
                        <div>
                            <strong style="font-size:14px; display:block;" id="toastTitle">Pembaruan Verifikasi</strong>
                            <span style="font-size:12.5px; opacity:0.95;" id="toastBody">Pembayaran Anda telah diverifikasi oleh Admin Keuangan.</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white" onclick="$('#realtimeToast').fadeOut();" style="opacity:0.9;">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- 1. Header Halaman Keuangan -->
                <div class="card custom-card-white">
                    <div class="card-block" style="padding: 22px 28px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-1">
                                    <h4 class="m-0 mr-2" style="font-size: 20px; font-weight: 700; color: #1e293b;">
                                        <i class="bi bi-wallet2 mr-2 text-primary"></i> Modul Keuangan Mahasiswa
                                    </h4>
                                    <span class="tag-simulasi">Data Simulasi</span>
                                </div>
                                <p class="m-0" style="color: #64748b; font-size: 13.5px;">
                                    Mahasiswa: <strong><?= htmlspecialchars($mahasiswa_info['nama_lengkap']) ?></strong> (NIM: <?= htmlspecialchars($mahasiswa_info['nim']) ?>) &middot; 
                                    <span><?= htmlspecialchars($mahasiswa_info['prodi']) ?> &mdash; Semester <?= htmlspecialchars($mahasiswa_info['semester']) ?></span>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <span class="badge badge-light px-3 py-2" style="border: 1.5px solid #cbd5e1; font-size: 12px; border-radius: 8px;">
                                    <i class="fa fa-refresh mr-1 text-primary"></i> Sync Real-time: <strong class="text-success">Aktif</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Rincian Biaya Kuliah (Sesuai Permintaan Spesifik User) -->
                <div class="banner-biaya-simulasi">
                    <div class="row align-items-center">
                        <div class="col-lg-5 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-warning text-dark font-weight-bold mr-2" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">DATA SIMULASI</span>
                                <span style="font-size: 13px; opacity: 0.9;">Semester Ganjil &bull; 2026/2027</span>
                            </div>
                            <div style="font-size: 14px; opacity: 0.85; font-weight: 500;">Total Biaya Semester</div>
                            <h2 style="font-weight: 800; font-size: 32px; margin: 4px 0 6px; letter-spacing: -0.5px;">
                                Rp 4.500.000
                            </h2>
                            <div style="font-size: 13.5px; opacity: 0.95;">
                                <i class="fa fa-graduation-cap mr-1"></i> Program Studi: <strong><?= htmlspecialchars($mahasiswa_info['prodi']) ?></strong> (Semester <?= htmlspecialchars($mahasiswa_info['semester']) ?>)
                            </div>
                        </div>
                        <div class="col-lg-7" style="border-left: 1px solid rgba(255,255,255,0.2); padding-left: 24px;">
                            <div class="font-weight-bold mb-2" style="font-size: 13.5px; letter-spacing: 0.5px; text-transform: uppercase;">
                                <i class="fa fa-list-ul mr-1"></i> Rincian Komponen Biaya Semester
                            </div>
                            <div class="row" style="font-size: 13px;">
                                <div class="col-sm-6 mb-1">
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> SPP / UKT: <strong>Rp 3.500.000</strong>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> Praktikum: <strong>Rp 500.000</strong>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> Fasilitas Akademik: <strong>Rp 300.000</strong>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> SI &amp; Administrasi: <strong>Rp 200.000</strong>
                                </div>
                            </div>
                            <div class="mt-2 pt-2" style="border-top: 1px dashed rgba(255,255,255,0.2); font-size: 11.5px; opacity: 0.85;">
                                <i class="fa fa-info-circle mr-1"></i> <em>Catatan: Biaya tambahan seperti tugas akhir, ujian tugas akhir, dan wisuda tidak termasuk dalam tagihan semester reguler. Angka ini adalah <strong>Data Simulasi</strong> sistem kampus.</em>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Super Admin Simulation Preview Banner -->
                <?php if (isset($is_admin_preview) && $is_admin_preview && !empty($target_mahasiswa)): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; background-color: #f0fdf4; border: 1.5px solid #86efac; color: #166534; box-shadow: 0 4px 12px rgba(22, 101, 52, 0.08);">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill mr-2" style="font-size: 24px; color: #15803d;"></i>
                                    <div>
                                        <strong style="font-size: 14px;">Mode Simulasi Super Admin:</strong>
                                        <div style="font-size: 12.5px; color: #14532d;">
                                            Melihat data keuangan mahasiswa: <strong><?= htmlspecialchars($target_mahasiswa->nama_lengkap) ?> (NIM: <?= htmlspecialchars($target_mahasiswa->nim) ?> &bull; <?= htmlspecialchars($target_mahasiswa->prodi ?: 'D3 Sistem Informasi') ?>)</strong>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($daftar_mahasiswa) && count($daftar_mahasiswa) > 1): ?>
                                <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                    <label class="mb-1 d-block font-weight-bold" style="font-size: 12px; color: #14532d;">Ganti Akun Mahasiswa:</label>
                                    <select class="form-control form-control-sm d-inline-block w-auto" style="border-radius: 6px;" onchange="window.location.href='<?= base_url('keuangan?mahasiswa_id=') ?>' + this.value;">
                                        <?php foreach ($daftar_mahasiswa as $dm): ?>
                                            <option value="<?= $dm->id ?>" <?= $dm->id == $target_mahasiswa->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dm->nama_lengkap) ?> (<?= htmlspecialchars($dm->nim) ?> &mdash; <?= htmlspecialchars($dm->prodi ?: 'D3 SI') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Flash Message Alerts -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill mr-2" style="font-size: 20px;"></i>
                            <div>
                                <strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill mr-2" style="font-size: 20px;"></i>
                            <div>
                                <strong>Perhatian:</strong> <?= $this->session->flashdata('error') ?>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- 2. Ringkasan Finansial Mahasiswa (4 Stat Cards) -->
                <div class="row mb-3">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Total Tagihan Semester</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #1e293b; font-size: 19px;">
                                        Rp <?= number_format($ringkasan['total_tagihan'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-muted"><?= count($daftar_tagihan) ?> Item Kewajiban</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #eff6ff; color: #1d4ed8;">
                                    <i class="bi bi-calculator"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Sisa Belum Dibayar</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #dc2626; font-size: 19px;" id="statBelumNominal">
                                        Rp <?= number_format($ringkasan['total_belum_bayar'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-danger font-weight-bold" id="statBelumCount"><?= $ringkasan['count_belum_bayar'] ?> Tagihan Menunggu</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #fef2f2; color: #dc2626;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Total Lunas</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #059669; font-size: 19px;" id="statLunasNominal">
                                        Rp <?= number_format($ringkasan['total_terbayar'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-success font-weight-bold" id="statLunasCount"><?= $ringkasan['count_lunas'] ?> Tagihan Terverifikasi</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Dalam Verifikasi</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #d97706; font-size: 19px;" id="statPendingCount">
                                        <?= $ringkasan['count_pending'] ?> Tagihan
                                    </h4>
                                    <small class="text-warning font-weight-bold">Menunggu Verifikasi Admin</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #fffbeb; color: #d97706;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Status Pembayaran Semester & Widget Akses KRS (Real-time Sync) -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php 
                            $krs_bg = '#f8fafc';
                            $krs_border = '#e2e8f0';
                            $krs_badge_class = 'badge-belum-bayar';
                            $krs_icon = 'bi-exclamation-octagon-fill';
                            $krs_color = '#dc2626';

                            if ($status_krs['status'] === 'LUNAS') {
                                $krs_bg = '#f0fdf4';
                                $krs_border = '#86efac';
                                $krs_badge_class = 'badge-lunas';
                                $krs_icon = 'bi-check-circle-fill';
                                $krs_color = '#15803d';
                            } elseif ($status_krs['status'] === 'PENDING') {
                                $krs_bg = '#fffbeb';
                                $krs_border = '#fde68a';
                                $krs_badge_class = 'badge-pending';
                                $krs_icon = 'bi-hourglass-split';
                                $krs_color = '#b45309';
                            } elseif ($status_krs['status'] === 'DITOLAK') {
                                $krs_bg = '#fff1f2';
                                $krs_border = '#fecdd3';
                                $krs_badge_class = 'badge-ditolak';
                                $krs_icon = 'bi-x-circle-fill';
                                $krs_color = '#be123c';
                            }
                        ?>

                        <div class="card custom-card-white" id="cardStatusKRS" style="border-left: 6px solid <?= $krs_color ?> !important; background: <?= $krs_bg ?> !important; border-color: <?= $krs_border ?> !important;">
                            <div class="card-block" style="padding: 22px 26px;">
                                <div class="row align-items-center">
                                    <div class="col-lg-8 mb-3 mb-lg-0">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 mt-1" style="font-size: 32px; color: <?= $krs_color ?>;" id="krsIconContainer">
                                                <i class="bi <?= $krs_icon ?>" id="krsIcon"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center mb-1 flex-wrap">
                                                    <h5 class="mb-0 font-weight-bold mr-2" style="color: #1e293b; font-size: 17px;">
                                                        Status Akses Kartu Rencana Studi (KRS)
                                                    </h5>
                                                    <span class="badge-status <?= $krs_badge_class ?>" id="badgeStatusKRS">
                                                        <i class="bi <?= $krs_icon ?>"></i> <span id="textStatusKRS"><?= $status_krs['status'] ?></span>
                                                    </span>
                                                </div>
                                                <p class="mb-1 font-weight-bold" id="pesanKRS" style="color: <?= $krs_color ?>; font-size: 14.5px;">
                                                    <?= $status_krs['pesan'] ?>
                                                </p>
                                                <?php if ($status_krs['status'] === 'DITOLAK' && !empty($status_krs['alasan_penolakan'])): ?>
                                                    <div class="mt-2 p-2 bg-white rounded border border-danger text-danger" id="boxRejectReason" style="font-size: 13px;">
                                                        <i class="bi bi-info-circle mr-1"></i> <strong>Alasan Penolakan:</strong> <?= htmlspecialchars($status_krs['alasan_penolakan']) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <small class="text-muted d-block mt-1">
                                                    Syarat Pengisian KRS: Pembayaran SPP / UKT telah diverifikasi (LUNAS). Perubahan dari admin otomatis sinkron tanpa perlu refresh manual.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-lg-right" id="containerBtnKRS">
                                        <?php if ($status_krs['buka_krs']): ?>
                                            <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-success btn-lg px-4 shadow" style="border-radius: 10px; font-weight: 700; font-size: 15px;">
                                                <i class="bi bi-unlock-fill mr-2"></i> Buka KRS Sekarang
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-secondary btn-lg px-4" disabled style="border-radius: 10px; font-weight: 600; font-size: 14.5px; opacity: 0.7; cursor: not-allowed;">
                                                <i class="bi bi-lock-fill mr-2"></i> Akses KRS Terkunci
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Area Konten Ber-Tab -->
                <div class="card custom-card-white">
                    <ul class="nav nav-tabs nav-tabs-keuangan" id="keuanganTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-tagihan-link" data-toggle="tab" href="#tab-tagihan" role="tab">
                                <i class="bi bi-receipt"></i> Tagihan Semester (<?= count($daftar_tagihan) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-riwayat-link" data-toggle="tab" href="#tab-riwayat" role="tab">
                                <i class="bi bi-clock-history"></i> Riwayat Pembayaran (<?= count($riwayat_pembayaran) ?>)
                            </a>
                        </li>
                        <?php if ($is_semester_akhir): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-ta-link" data-toggle="tab" href="#tab-ta" role="tab">
                                    <i class="bi bi-mortarboard"></i> Pembayaran Tugas Akhir 
                                    <?php if ($akses_tugas_akhir): ?>
                                        <span class="badge badge-success ml-1" style="font-size: 10px;">Buka</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary ml-1" style="font-size: 10px;">Tutup</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-biaya-link" data-toggle="tab" href="#tab-biaya" role="tab">
                                <i class="bi bi-info-square"></i> Rincian Biaya Kuliah &amp; Tambahan
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="keuanganTabContent">
                        
                        <!-- TAB 1: TAGIHAN SEMESTER AKTIF -->
                        <div class="tab-pane fade show active p-4" id="tab-tagihan" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #1e293b; font-size: 16px;">
                                        Daftar Tagihan Semester Berjalan (2026/2027 Ganjil)
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Pilih tagihan yang ingin dibayarkan secara langsung melalui tombol di tabel atau gunakan tombol konfirmasi pembayaran.
                                    </p>
                                </div>
                                <div class="mt-2 mt-md-0">
                                    <?php if (!empty($tagihan_pilihan)): ?>
                                        <button type="button" class="btn btn-primary px-3 shadow-sm btn-open-bayar-general" style="border-radius: 8px; font-weight: 600;">
                                            <i class="bi bi-credit-card mr-1"></i> Konfirmasi Pembayaran
                                        </button>
                                    <?php else: ?>
                                        <span class="badge badge-success px-3 py-2" style="border-radius: 8px; font-size: 13px;">
                                            <i class="bi bi-check2-all mr-1"></i> Seluruh Tagihan Lunas / Sedang Diproses
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Jenis Kewajiban / Tagihan</th>
                                            <th>Tahun Akademik</th>
                                            <th>Semester</th>
                                            <th>Nominal</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Status</th>
                                            <th style="width: 170px;" class="text-center">Aksi Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($daftar_tagihan)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-check-circle text-success mr-2" style="font-size: 24px;"></i>
                                                    <p class="mb-0 mt-2 font-weight-bold">Tidak ada tagihan tertagih untuk akun Anda saat ini.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($daftar_tagihan as $item): ?>
                                                <?php 
                                                    $badge_cls = 'badge-belum-bayar';
                                                    $icon_cls  = 'bi-exclamation-circle';
                                                    $label_st  = $item->status;
                                                    if ($item->status === 'LUNAS') {
                                                        $badge_cls = 'badge-lunas';
                                                        $icon_cls  = 'bi-check-circle-fill';
                                                        $label_st  = 'Lunas';
                                                    } elseif ($item->status === 'PENDING') {
                                                        $badge_cls = 'badge-pending';
                                                        $icon_cls  = 'bi-hourglass-split';
                                                        $label_st  = 'Menunggu Verifikasi';
                                                    } elseif ($item->status === 'DITOLAK') {
                                                        $badge_cls = 'badge-ditolak';
                                                        $icon_cls  = 'bi-x-circle-fill';
                                                        $label_st  = 'Ditolak';
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="font-weight-bold text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <strong style="color: #1e293b; font-size: 14px;"><?= htmlspecialchars($item->jenis_tagihan) ?></strong>
                                                        <?php if (stripos($item->jenis_tagihan, 'SPP') !== false): ?>
                                                            <span class="badge badge-info ml-1" style="font-size: 10.5px;">Syarat KRS</span>
                                                        <?php endif; ?>
                                                        <?php if (stripos($item->jenis_tagihan, 'Tugas Akhir') !== false): ?>
                                                            <span class="badge badge-warning text-dark ml-1" style="font-size: 10.5px;">Semester Akhir</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($item->tahun_akademik) ?></td>
                                                    <td>
                                                        <span class="badge badge-light px-2 py-1" style="border: 1px solid #e2e8f0; font-size: 12px;">
                                                            <?= htmlspecialchars($item->semester) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1565c0; font-size: 14.5px;">
                                                            Rp <?= number_format($item->nominal, 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-calendar3 mr-1 text-muted"></i>
                                                        <?= date('d M Y', strtotime($item->jatuh_tempo)) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge-status <?= $badge_cls ?>">
                                                            <i class="bi <?= $icon_cls ?>"></i> <?= $label_st ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($item->status === 'BELUM_BAYAR'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-primary btn-sm btn-block btn-bayar-item" 
                                                                    data-id="<?= $item->id ?>"
                                                                    data-jenis="<?= htmlspecialchars($item->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($item->nominal, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($item->semester) ?> <?= htmlspecialchars($item->tahun_akademik) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($item->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-upload mr-1"></i> Bayar Sekarang
                                                            </button>
                                                        <?php elseif ($item->status === 'DITOLAK'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm btn-block btn-bayar-item" 
                                                                    data-id="<?= $item->id ?>"
                                                                    data-jenis="<?= htmlspecialchars($item->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($item->nominal, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($item->semester) ?> <?= htmlspecialchars($item->tahun_akademik) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($item->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-arrow-repeat mr-1"></i> Upload Ulang
                                                            </button>
                                                        <?php elseif ($item->status === 'PENDING'): ?>
                                                            <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                                                <i class="bi bi-clock mr-1"></i> Verifikasi Admin
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success px-2 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                                                <i class="bi bi-check-lg mr-1"></i> Selesai
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: RIWAYAT PEMBAYARAN & OPSI EDIT JIKA PENDING -->
                        <div class="tab-pane fade p-4" id="tab-riwayat" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #1e293b; font-size: 16px;">
                                        Riwayat Pembayaran &amp; Opsi Edit
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Jika terdapat kesalahan pada bukti/data yang berstatus <strong>Menunggu Verifikasi (PENDING)</strong>, Anda dapat <strong>mengedit</strong> atau <strong>membatalkannya langsung</strong> tanpa menunggu penolakan admin.
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">No</th>
                                            <th>Waktu Pengiriman</th>
                                            <th>Kewajiban / Tagihan</th>
                                            <th>Metode Bayar</th>
                                            <th>Nominal</th>
                                            <th>No. Ref</th>
                                            <th>Bukti</th>
                                            <th>Status</th>
                                            <th style="width: 160px;" class="text-center">Aksi / Koreksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($riwayat_pembayaran)): ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 36px;"></i>
                                                    <p class="mb-0 mt-2 font-weight-bold">Belum ada riwayat konfirmasi pembayaran yang dikirim.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($riwayat_pembayaran as $r): ?>
                                                <?php 
                                                    $r_badge = 'badge-pending';
                                                    $r_icon  = 'bi-hourglass-split';
                                                    if ($r->status === 'LUNAS') {
                                                        $r_badge = 'badge-lunas';
                                                        $r_icon  = 'bi-check-circle-fill';
                                                    } elseif ($r->status === 'DITOLAK') {
                                                        $r_badge = 'badge-ditolak';
                                                        $r_icon  = 'bi-x-circle-fill';
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="font-weight-bold text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <strong><?= date('d M Y', strtotime($r->tanggal_pembayaran)) ?></strong><br>
                                                        <small class="text-muted"><?= date('H:i', strtotime($r->created_at)) ?> WIB</small>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1e293b;"><?= htmlspecialchars($r->jenis_tagihan) ?></strong><br>
                                                        <small class="text-muted"><?= htmlspecialchars($r->semester) ?> <?= htmlspecialchars($r->tahun_akademik) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light px-2 py-1" style="border: 1px solid #cbd5e1;">
                                                            <?= htmlspecialchars($r->metode_pembayaran) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1565c0;">
                                                            Rp <?= number_format($r->nominal_pembayaran, 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($r->nomor_referensi)): ?>
                                                            <code><?= htmlspecialchars($r->nomor_referensi) ?></code>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('keuangan/lihat_bukti/' . $r->id) ?>" target="_blank" class="btn btn-outline-primary btn-sm px-2" style="border-radius: 6px;">
                                                            <i class="bi bi-file-earmark-text mr-1"></i> Lihat
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge-status <?= $r_badge ?>">
                                                            <i class="bi <?= $r_icon ?>"></i> <?= $r->status ?>
                                                        </span>
                                                        <?php if ($r->status === 'DITOLAK' && !empty($r->alasan_penolakan)): ?>
                                                            <div class="mt-1">
                                                                <small class="text-danger font-weight-bold d-block">
                                                                    <i class="bi bi-info-circle mr-1"></i> <?= htmlspecialchars($r->alasan_penolakan) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($r->status === 'LUNAS' && !empty($r->nama_verifikator)): ?>
                                                            <div class="mt-1">
                                                                <small class="text-muted d-block" style="font-size: 11px;">
                                                                    Oleh: <?= htmlspecialchars($r->nama_verifikator) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($r->status === 'PENDING'): ?>
                                                            <!-- Tombol Opsi Edit Jika Ada Kesalahan -->
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button type="button" 
                                                                        class="btn btn-warning text-dark font-weight-bold btn-edit-pembayaran"
                                                                        data-id="<?= $r->id ?>"
                                                                        data-tagihan="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                        data-nominal="Rp <?= number_format($r->nominal_pembayaran, 0, ',', '.') ?>"
                                                                        data-metode="<?= htmlspecialchars($r->metode_pembayaran) ?>"
                                                                        data-tanggal="<?= $r->tanggal_pembayaran ?>"
                                                                        data-referensi="<?= htmlspecialchars($r->nomor_referensi) ?>"
                                                                        style="border-radius: 6px 0 0 6px;"
                                                                        title="Edit Data / Bukti Pembayaran">
                                                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                                                </button>
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-batal-pembayaran"
                                                                        data-id="<?= $r->id ?>"
                                                                        data-tagihan="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                        style="border-radius: 0 6px 6px 0;"
                                                                        title="Batalkan Pengajuan">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        <?php elseif ($r->status === 'DITOLAK'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm btn-bayar-item"
                                                                    data-id="<?= $r->tagihan_id ?>"
                                                                    data-jenis="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($r->nominal_tagihan, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($r->semester) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($r->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-arrow-repeat mr-1"></i> Kirim Ulang
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-success font-weight-bold" style="font-size: 12.5px;">
                                                                <i class="bi bi-check-all"></i> Terverifikasi
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB KHUSUS MAHASISWA SEMESTER AKHIR: PEMBAYARAN TUGAS AKHIR -->
                        <?php if ($is_semester_akhir): ?>
                            <div class="tab-pane fade p-4" id="tab-ta" role="tabpanel">
                                <div class="p-3 mb-4 rounded" style="background-color: #f8fafc; border: 1.5px solid #e2e8f0;">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-mortarboard mr-2 text-primary" style="font-size: 24px;"></i>
                                                <h5 class="mb-0 font-weight-bold" style="color: #1e293b;">
                                                    Menu Pembayaran Tugas Akhir &amp; Kelulusan
                                                </h5>
                                            </div>
                                            <p class="text-muted mb-0" style="font-size: 13px;">
                                                Menu ini khusus terbuka bagi Anda yang telah memasuki <strong>Semester Akhir (Semester <?= htmlspecialchars($mahasiswa_info['semester']) ?>)</strong> pada Program Studi <?= htmlspecialchars($mahasiswa_info['prodi']) ?>.
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                            <?php if ($akses_tugas_akhir): ?>
                                                <span class="badge badge-success px-3 py-2" style="font-size: 13px; border-radius: 8px;">
                                                    <i class="bi bi-unlock-fill mr-1"></i> Akses Dibuka oleh Admin
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-3 py-2" style="font-size: 13px; border-radius: 8px;">
                                                    <i class="bi bi-lock-fill mr-1"></i> Akses Ditutup oleh Admin
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!$akses_tugas_akhir): ?>
                                    <div class="alert alert-warning text-center py-5" style="border-radius: 12px; background-color: #fffbeb; border: 1.5px solid #fde68a;">
                                        <i class="bi bi-shield-lock text-warning" style="font-size: 48px;"></i>
                                        <h5 class="font-weight-bold mt-3 mb-1" style="color: #92400e;">Akses Pembayaran Tugas Akhir Sedang Ditutup</h5>
                                        <p class="text-muted mb-0" style="font-size: 13.5px; max-width: 540px; margin: 0 auto;">
                                            Periode pembayaran bimbingan / ujian tugas akhir belum dibuka atau sedang ditutup oleh Bagian Keuangan Kampus. Silakan hubungi admin keuangan jika jadwal pendaftaran tugas akhir Anda sudah dimulai.
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($biaya_tambahan as $bt): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100" style="border-radius: 12px; border: 1.5px solid #e2e8f0; background: #ffffff;">
                                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                <strong style="color: #1e293b; font-size: 15px;"><?= htmlspecialchars($bt['jenis_biaya']) ?></strong>
                                                                <span class="badge badge-info" style="font-size: 11px;"><?= htmlspecialchars($bt['peruntukan']) ?></span>
                                                            </div>
                                                            <h4 class="font-weight-bold text-primary mb-2" style="font-size: 18px;">
                                                                Rp <?= number_format($bt['nominal'], 0, ',', '.') ?>
                                                            </h4>
                                                            <p class="text-muted mb-3" style="font-size: 12.5px;">
                                                                <?= htmlspecialchars($bt['keterangan']) ?>
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm btn-block btn-open-bayar-general" style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-credit-card mr-1"></i> Pilih &amp; Bayar Item Ini
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- TAB 3: INFORMASI BIAYA PERKULIAHAN LENGKAP -->
                        <div class="tab-pane fade p-4" id="tab-biaya" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #1e293b; font-size: 16px;">
                                        Rincian Biaya Kuliah &amp; Biaya Tambahan
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Standar acuan biaya perkuliahan Program Studi <strong><?= htmlspecialchars($mahasiswa_info['prodi']) ?></strong> Semester Ganjil 2026/2027.
                                    </p>
                                </div>
                                <span class="tag-simulasi">Data Simulasi</span>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                                <i class="fa fa-folder-open mr-1"></i> 1. Rincian Biaya Kuliah Semester (Wajib SPP / UKT)
                            </h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Komponen Biaya</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot = 0; foreach ($komponen_biaya as $kb): $tot += $kb['nominal']; ?>
                                            <tr>
                                                <td class="text-center font-weight-bold"><?= $kb['no'] ?></td>
                                                <td>
                                                    <strong style="color: #1e293b;"><?= htmlspecialchars($kb['komponen']) ?></strong>
                                                    <?php if ($kb['syarat_krs']): ?>
                                                        <span class="badge badge-danger ml-1" style="font-size: 10px;">Syarat Mutlak KRS</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge badge-light px-2 py-1" style="border: 1px solid #cbd5e1;"><?= htmlspecialchars($kb['kategori']) ?></span></td>
                                                <td style="font-size: 13px; color: #475569;"><?= htmlspecialchars($kb['keterangan']) ?></td>
                                                <td class="text-right font-weight-bold" style="color: #1565c0; font-size: 14px;">
                                                    Rp <?= number_format($kb['nominal'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr style="background-color: #f8fafc;">
                                            <td colspan="4" class="text-right font-weight-bold" style="font-size: 14.5px;">TOTAL BIAYA SEMESTER:</td>
                                            <td class="text-right font-weight-bold" style="color: #1e3a8a; font-size: 16px;">
                                                Rp <?= number_format($tot, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                                <i class="fa fa-plus-circle mr-1"></i> 2. Biaya Tambahan (Di Luar Tagihan SPP / UKT Semester Reguler)
                            </h6>
                            <p class="text-muted mb-2" style="font-size: 12.5px;">
                                Biaya berikut tidak termasuk dalam tagihan semester reguler dan hanya dikenakan apabila mahasiswa menggunakan layanan tersebut (misalnya di semester akhir).
                            </p>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Jenis Biaya Tambahan</th>
                                            <th>Peruntukan</th>
                                            <th>Keterangan</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($biaya_tambahan as $bt): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold"><?= $bt['no'] ?></td>
                                                <td><strong style="color: #1e293b;"><?= htmlspecialchars($bt['jenis_biaya']) ?></strong></td>
                                                <td><span class="badge badge-info px-2 py-1"><?= htmlspecialchars($bt['peruntukan']) ?></span></td>
                                                <td style="font-size: 13px; color: #475569;"><?= htmlspecialchars($bt['keterangan']) ?></td>
                                                <td class="text-right font-weight-bold" style="color: #047857; font-size: 14px;">
                                                    Rp <?= number_format($bt['nominal'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 5. Informasi Rekening Bank Resmi -->
                <div class="card custom-card-white">
                    <div class="card-header custom-card-header">
                        <h5>
                            <i class="bi bi-credit-card-2-front mr-2"></i> Rekening Resmi Kampus &amp; Panduan Transfer
                        </h5>
                    </div>
                    <div class="card-block" style="padding: 24px;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong class="text-primary font-weight-bold">BANK BNI</strong>
                                        <span class="badge badge-primary px-2 py-1">Virtual Account</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Virtual Account Mahasiswa:</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 font-weight-bold text-dark">8808-<?= htmlspecialchars($mahasiswa_info['nim']) ?></h5>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-copy-rek" data-copy="8808<?= htmlspecialchars($mahasiswa_info['nim']) ?>">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Smart Campus - <?= htmlspecialchars($mahasiswa_info['nama_lengkap']) ?></small>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong class="text-primary font-weight-bold">BANK MANDIRI</strong>
                                        <span class="badge badge-info px-2 py-1">Transfer Bank</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Rekening Kampus:</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 font-weight-bold text-dark">137-00-1928374-1</h5>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-copy-rek" data-copy="1370019283741">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Yayasan Smart Campus Indonesia</small>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong class="text-primary font-weight-bold">BANK BCA</strong>
                                        <span class="badge badge-success px-2 py-1">Transfer Bank</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Rekening Giro Kampus:</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 font-weight-bold text-dark">829-501-8890</h5>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-copy-rek" data-copy="8295018890">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Smart Campus Operasional</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL KONFIRMASI PEMBAYARAN & UPLOAD BUKTI (BARU)
     ======================================================= -->
<div class="modal fade" id="modalKonfirmasiBayar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-primary text-white" style="background-color: #1565c0 !important; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-cash-stack mr-2"></i> Konfirmasi Pembayaran Tagihan Mahasiswa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span>&times;</span>
                </button>
            </div>

            <?= form_open_multipart('keuangan/konfirmasi_pembayaran', ['id' => 'formKonfirmasiPembayaran']) ?>
                <div class="modal-body" style="padding: 26px;">
                    
                    <div class="p-3 mb-4 rounded" style="background-color: #f0f7ff; border: 1.5px solid #bfdbfe;">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <span class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 700;">Tagihan yang Dipilih</span>
                                <h5 class="font-weight-bold mb-1" id="modalDetailJenis" style="color: #1e3a8a;">SPP / UKT</h5>
                                <div style="font-size: 13px; color: #475569;">
                                    <span id="modalDetailSemester">Ganjil 2026/2027</span> &bull; 
                                    Jatuh Tempo: <span id="modalDetailTempo" class="font-weight-bold text-danger">30 Sep 2026</span>
                                </div>
                            </div>
                            <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                                <span class="text-muted" style="font-size: 12px;">Nominal Kewajiban:</span>
                                <h4 class="font-weight-bold text-primary mb-0" id="modalDetailNominal" style="color: #1565c0 !important;">
                                    Rp 3.500.000
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="select_tagihan_id" class="font-weight-bold" style="font-size: 13.5px;">
                            Pilih Tagihan yang Akan Dibayarkan <span class="text-danger">*</span>
                        </label>
                        <select name="tagihan_id" id="select_tagihan_id" class="form-control" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                            <?php if (empty($tagihan_pilihan)): ?>
                                <option value="" disabled selected>Tidak ada tagihan tertunggak</option>
                            <?php else: ?>
                                <?php foreach ($tagihan_pilihan as $tp): ?>
                                    <option value="<?= $tp->id ?>" 
                                            data-jenis="<?= htmlspecialchars($tp->jenis_tagihan) ?>"
                                            data-nominal="Rp <?= number_format($tp->nominal, 0, ',', '.') ?>"
                                            data-semester="<?= htmlspecialchars($tp->semester) ?> <?= htmlspecialchars($tp->tahun_akademik) ?>"
                                            data-tempo="<?= date('d M Y', strtotime($tp->jatuh_tempo)) ?>">
                                        <?= htmlspecialchars($tp->jenis_tagihan) ?> - Rp <?= number_format($tp->nominal, 0, ',', '.') ?> (<?= htmlspecialchars($tp->semester) ?> <?= htmlspecialchars($tp->tahun_akademik) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="metode_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Metode / Rekening Tujuan <span class="text-danger">*</span>
                            </label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                                <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                <option value="Virtual Account BNI">Virtual Account BNI</option>
                                <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                <option value="Teller Kampus">Pembayaran di Teller Kampus</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="tanggal_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Tanggal Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nomor_referensi" class="font-weight-bold" style="font-size: 13.5px;">
                            Nomor Referensi Transaksi / Kode Jurnal <small class="text-muted">(Opsional)</small>
                        </label>
                        <input type="text" name="nomor_referensi" id="nomor_referensi" class="form-control" placeholder="Contoh: TRX-20260921-987123 atau No. Resi Bank" style="height: 44px; border-radius: 8px; font-size: 14px;">
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold" style="font-size: 13.5px;">
                            Unggah Bukti Transfer <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="bukti_pembayaran" class="custom-file-input" id="buktiPembayaranInput" accept="image/png, image/jpeg, image/jpg, application/pdf" required>
                            <label class="custom-file-label" for="buktiPembayaranInput" id="buktiFileLabel" style="border-radius: 8px; height: 44px; line-height: 30px;">
                                Pilih berkas bukti transfer (JPG, PNG, PDF maks. 3MB)
                            </label>
                        </div>
                        <small class="text-muted mt-1 d-block">Mendukung format JPG, JPEG, PNG, dan PDF (maks. 3MB).</small>
                    </div>

                </div>

                <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow" style="border-radius: 8px; font-weight: 600; background-color: #1565c0;">
                        <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Kirim Konfirmasi
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL EDIT PEMBAYARAN MAHASISWA (FITUR BARU)
     Mahasiswa dapat mengoreksi data tanpa menunggu ditolak admin
     ======================================================= -->
<div class="modal fade" id="modalEditPembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-pencil-square mr-2"></i> Koreksi / Edit Bukti Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span>&times;</span>
                </button>
            </div>

            <?= form_open_multipart('keuangan/edit_pembayaran', ['id' => 'formEditPembayaran']) ?>
                <input type="hidden" name="pembayaran_id" id="edit_pembayaran_id" value="">

                <div class="modal-body" style="padding: 26px;">
                    
                    <div class="alert alert-warning mb-4" style="border-radius: 10px; font-size: 13px; border-left: 4px solid #f59e0b;">
                        <i class="bi bi-info-circle-fill mr-1"></i>
                        Anda sedang mengoreksi pembayaran yang berstatus <strong>Menunggu Verifikasi (PENDING)</strong>. Anda dapat mengganti metode transfer, tanggal bayar, nomor referensi, atau mengunggah ulang bukti transfer baru.
                    </div>

                    <div class="p-3 mb-4 rounded" style="background-color: #fefce8; border: 1.5px solid #fde047;">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <span class="text-muted" style="font-size: 12px; font-weight: 700;">Tagihan:</span>
                                <h5 class="font-weight-bold mb-0 text-dark" id="editTagihanJudul">-</h5>
                            </div>
                            <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                                <span class="text-muted" style="font-size: 12px;">Nominal:</span>
                                <h4 class="font-weight-bold text-dark mb-0" id="editTagihanNominal">-</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_metode_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select name="metode_pembayaran" id="edit_metode_pembayaran" class="form-control" required style="height: 44px; border-radius: 8px;">
                                <option value="Virtual Account BNI">Virtual Account BNI</option>
                                <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                <option value="Teller Kampus">Pembayaran di Teller Kampus</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_tanggal_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Tanggal Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal_pembayaran" id="edit_tanggal_pembayaran" class="form-control" max="<?= date('Y-m-d') ?>" required style="height: 44px; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_nomor_referensi" class="font-weight-bold" style="font-size: 13.5px;">
                            Nomor Referensi Transaksi / Kode Jurnal
                        </label>
                        <input type="text" name="nomor_referensi" id="edit_nomor_referensi" class="form-control" placeholder="Nomor Resi / Bukti Transfer" style="height: 44px; border-radius: 8px;">
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold" style="font-size: 13.5px;">
                            Unggah Bukti Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti file bukti saat ini)</small>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="bukti_pembayaran" class="custom-file-input" id="editBuktiInput" accept="image/png, image/jpeg, image/jpg, application/pdf">
                            <label class="custom-file-label" for="editBuktiInput" id="editBuktiLabel" style="border-radius: 8px; height: 44px; line-height: 30px;">
                                Pilih berkas bukti baru (opsional)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 text-dark font-weight-bold shadow" style="border-radius: 8px;">
                        <i class="bi bi-check2-circle mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL BATALKAN PEMBAYARAN (KONFIRMASI MAHASISWA)
     ======================================================= -->
<div class="modal fade" id="modalBatalPembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header bg-danger text-white" style="border-radius: 16px 16px 0 0; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-exclamation-triangle mr-2"></i> Batalkan Pengajuan Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px;">
                    <i class="bi bi-trash"></i>
                </div>
                <h5 class="font-weight-bold" style="color: #1e293b;">Yakin Ingin Membatalkan?</h5>
                <p style="font-size: 13.5px; color: #64748b;">
                    Pengajuan untuk tagihan <strong id="batalTagihanNama" class="text-dark"></strong> akan dibatalkan. Status tagihan akan dikembalikan menjadi <strong>BELUM BAYAR</strong> sehingga Anda dapat mengonfirmasi ulang kapan saja.
                </p>
            </div>
            <div class="modal-footer bg-light" style="padding: 14px 24px;">
                <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Kembali</button>
                <form method="POST" action="<?= base_url('keuangan/batalkan_pembayaran') ?>" style="display:inline;">
                    <input type="hidden" name="pembayaran_id" id="batalPembayaranId" value="">
                    <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px;">
                        <i class="bi bi-check mr-1"></i> Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     JAVASCRIPT INTERAKTIF & REAL-TIME SYNC
     ======================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const selectTagihan       = document.getElementById('select_tagihan_id');
    const modalDetailJenis    = document.getElementById('modalDetailJenis');
    const modalDetailNominal  = document.getElementById('modalDetailNominal');
    const modalDetailSemester = document.getElementById('modalDetailSemester');
    const modalDetailTempo    = document.getElementById('modalDetailTempo');
    const buktiInput          = document.getElementById('buktiPembayaranInput');
    const buktiLabel          = document.getElementById('buktiFileLabel');

    // 1. Update Preview Kotak Detail Tagihan
    function updateModalDetailFromSelect() {
        if (!selectTagihan) return;
        const opt = selectTagihan.options[selectTagihan.selectedIndex];
        if (opt && opt.value) {
            if (modalDetailJenis) modalDetailJenis.textContent = opt.getAttribute('data-jenis') || '-';
            if (modalDetailNominal) modalDetailNominal.textContent = opt.getAttribute('data-nominal') || '-';
            if (modalDetailSemester) modalDetailSemester.textContent = opt.getAttribute('data-semester') || '-';
            if (modalDetailTempo) modalDetailTempo.textContent = opt.getAttribute('data-tempo') || '-';
        }
    }

    if (selectTagihan) {
        selectTagihan.addEventListener('change', updateModalDetailFromSelect);
    }

    // 2. Klik Tombol Bayar Sekarang / Upload Ulang
    document.querySelectorAll('.btn-bayar-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const tagihanId = this.getAttribute('data-id');
            const jenis     = this.getAttribute('data-jenis');
            const nominal   = this.getAttribute('data-nominal');
            const semester  = this.getAttribute('data-semester');
            const tempo     = this.getAttribute('data-tempo');

            if (selectTagihan) selectTagihan.value = tagihanId;
            if (modalDetailJenis) modalDetailJenis.textContent = jenis;
            if (modalDetailNominal) modalDetailNominal.textContent = nominal;
            if (modalDetailSemester) modalDetailSemester.textContent = semester;
            if (modalDetailTempo) modalDetailTempo.textContent = tempo;

            $('#modalKonfirmasiBayar').modal('show');
        });
    });

    // 3. Tombol General Konfirmasi Bayar
    const btnOpenGeneral = document.querySelector('.btn-open-bayar-general');
    if (btnOpenGeneral) {
        btnOpenGeneral.addEventListener('click', function() {
            updateModalDetailFromSelect();
            $('#modalKonfirmasiBayar').modal('show');
        });
    }

    // 4. Modal Edit Pembayaran Mahasiswa
    document.querySelectorAll('.btn-edit-pembayaran').forEach(btn => {
        btn.addEventListener('click', function() {
            const id      = this.getAttribute('data-id');
            const tagihan = this.getAttribute('data-tagihan');
            const nominal = this.getAttribute('data-nominal');
            const metode  = this.getAttribute('data-metode');
            const tgl     = this.getAttribute('data-tanggal');
            const ref     = this.getAttribute('data-referensi');

            document.getElementById('edit_pembayaran_id').value = id;
            document.getElementById('editTagihanJudul').textContent = tagihan;
            document.getElementById('editTagihanNominal').textContent = nominal;
            document.getElementById('edit_metode_pembayaran').value = metode;
            document.getElementById('edit_tanggal_pembayaran').value = tgl;
            document.getElementById('edit_nomor_referensi').value = ref;

            $('#modalEditPembayaran').modal('show');
        });
    });

    // 5. Modal Batalkan Pembayaran
    document.querySelectorAll('.btn-batal-pembayaran').forEach(btn => {
        btn.addEventListener('click', function() {
            const id      = this.getAttribute('data-id');
            const tagihan = this.getAttribute('data-tagihan');

            document.getElementById('batalPembayaranId').value = id;
            document.getElementById('batalTagihanNama').textContent = tagihan;

            $('#modalBatalPembayaran').modal('show');
        });
    });

    // 6. File input labels
    if (buktiInput && buktiLabel) {
        buktiInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            buktiLabel.textContent = file ? file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)' : 'Pilih berkas bukti transfer';
        });
    }
    const editBukti = document.getElementById('editBuktiInput');
    const editLabel = document.getElementById('editBuktiLabel');
    if (editBukti && editLabel) {
        editBukti.addEventListener('change', function(e) {
            const file = e.target.files[0];
            editLabel.textContent = file ? file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)' : 'Pilih berkas bukti baru (opsional)';
        });
    }

    // 7. Salin Virtual Account / Rekening
    document.querySelectorAll('.btn-copy-rek').forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const orig = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                    setTimeout(() => { this.innerHTML = orig; }, 1500);
                });
            }
        });
    });

    // =======================================================
    // 8. REAL-TIME VERIFICATION SYNC (POLLING AJAX)
    // =======================================================
    let lastHash = '';
    const activeAkunId = <?= isset($akun_id_aktif) ? (int)$akun_id_aktif : (int)$user['id'] ?>;

    function checkRealtimeStatus() {
        $.ajax({
            url: '<?= base_url('keuangan/status_realtime?mahasiswa_id=') ?>' + activeAkunId,
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.success && resp.data) {
                    const d = resp.data;
                    
                    if (lastHash && lastHash !== d.hash) {
                        // Terjadi perubahan status dari Admin Keuangan!
                        $('#toastTitle').text('Status Pembayaran Diperbarui!');
                        if (d.status_krs === 'LUNAS') {
                            $('#toastBody').html('Selamat! Pembayaran Anda telah <strong>DIVERIFIKASI (LUNAS)</strong>. Akses KRS terbuka.');
                            $('#realtimeToast').css('background', '#10b981').fadeIn();
                        } else if (d.status_krs === 'DITOLAK') {
                            $('#toastBody').html('Perhatian: Pembayaran Anda <strong>DITOLAK</strong> oleh Admin. Silakan perbaiki bukti pembayaran.');
                            $('#realtimeToast').css('background', '#ef4444').fadeIn();
                        } else {
                            $('#toastBody').text('Status pembayaran Anda telah diperbarui oleh Admin Keuangan.');
                            $('#realtimeToast').css('background', '#1565c0').fadeIn();
                        }

                        // Reload data halaman secara halus setelah 2 detik
                        setTimeout(function() {
                            window.location.reload();
                        }, 2500);
                    }
                    lastHash = d.hash;
                }
            },
            error: function() {
                // Abaikan kesalahan koneksi sementara
            }
        });
    }

    // Jalankan polling setiap 5 detik
    setInterval(checkRealtimeStatus, 5000);
    // Jalankan satu kali segera saat halaman dimuat
    checkRealtimeStatus();

    $('#modalKonfirmasiBayar').on('shown.bs.modal', function () {
        updateModalDetailFromSelect();
    });
});
</script>
