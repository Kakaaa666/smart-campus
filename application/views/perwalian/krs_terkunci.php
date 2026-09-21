<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <style>
                .custom-card-white {
                    background-color: #ffffff !important;
                    background: #ffffff !important;
                    border-radius: 16px !important;
                    -webkit-border-radius: 16px !important;
                    -moz-border-radius: 16px !important;
                    border: 1px solid #edf2f7 !important;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
                    overflow: hidden !important;
                    margin-bottom: 24px !important;
                }
                .krs-lock-box {
                    padding: 40px 30px;
                    text-align: center;
                    border-radius: 14px;
                    background: #ffffff;
                }
                .krs-lock-icon {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 38px;
                    margin-bottom: 20px;
                }
                .badge-status {
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 13px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    letter-spacing: 0.3px;
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
                .badge-ditolak {
                    background-color: #ffe4e6;
                    color: #be123c;
                    border: 1px solid #fda4af;
                }
                </style>

                <!-- Page Header: Perwalian -->
                <div class="card custom-card-white">
                    <div class="card-block" style="padding: 22px 28px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="m-b-5" style="font-size: 20px; font-weight: 700; color: #1e293b;">
                                    <i class="fa fa-th-large mr-2 text-primary"></i> <?= isset($page_title) ? $page_title : 'Perwalian & Kartu Rencana Studi (KRS)' ?>
                                </h4>
                                <p class="m-b-0" style="color: #64748b; font-size: 13.5px;">
                                    Pemilihan mata kuliah, validasi FRS, dan bimbingan rencana studi semester baru.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <ul style="display: inline-flex; align-items: center; list-style: none; margin: 0; padding: 0; font-size: 13.5px;">
                                    <li>
                                        <a href="<?= base_url('beranda') ?>" style="color: #1565c0; text-decoration: none; font-weight: 500;">
                                            <i class="fa fa-home mr-1"></i> Beranda
                                        </a>
                                    </li>
                                    <li style="color: #94a3b8; margin: 0 8px;">/</li>
                                    <li style="color: #64748b; font-weight: 600;">Perwalian</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($is_admin_preview) && $is_admin_preview && !empty($target_mahasiswa)): ?>
                    <div class="alert alert-info mb-3" style="border-radius: 12px; background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1;">
                        <i class="bi bi-shield-check mr-2"></i>
                        <strong>Mode Super Admin:</strong> Anda melihat simulasi akses perwalian untuk mahasiswa: <strong><?= htmlspecialchars($target_mahasiswa->nama_lengkap) ?> (NIM: <?= htmlspecialchars($target_mahasiswa->nim) ?>)</strong>.
                    </div>
                <?php endif; ?>

                <?php 
                    $status_val = isset($status_krs['status']) ? $status_krs['status'] : 'BELUM_BAYAR';
                    $pesan_val  = isset($status_krs['pesan']) ? $status_krs['pesan'] : 'KRS belum dapat diakses. Silakan selesaikan pembayaran semester terlebih dahulu.';

                    $icon_box = 'bi-lock-fill';
                    $icon_bg  = '#fef2f2';
                    $icon_col = '#dc2626';
                    $badge_c  = 'badge-belum-bayar';

                    if ($status_val === 'PENDING') {
                        $icon_box = 'bi-hourglass-split';
                        $icon_bg  = '#fffbeb';
                        $icon_col = '#d97706';
                        $badge_c  = 'badge-pending';
                    } elseif ($status_val === 'DITOLAK') {
                        $icon_box = 'bi-x-circle-fill';
                        $icon_bg  = '#fff1f2';
                        $icon_col = '#be123c';
                        $badge_c  = 'badge-ditolak';
                    }
                ?>

                <!-- Box Tampilan Akses KRS Terkunci -->
                <div class="card custom-card-white">
                    <div class="krs-lock-box">
                        <div class="krs-lock-icon" style="background-color: <?= $icon_bg ?>; color: <?= $icon_col ?>;">
                            <i class="bi <?= $icon_box ?>"></i>
                        </div>

                        <div class="mb-2">
                            <span class="badge-status <?= $badge_c ?>">
                                <i class="bi <?= $icon_box ?> mr-1"></i> Status Pembayaran: <?= $status_val ?>
                            </span>
                        </div>

                        <h4 class="font-weight-bold mb-2" style="color: #1e293b; font-size: 20px;">
                            Akses Pengisian KRS Belum Terbuka
                        </h4>

                        <div class="alert alert-warning d-inline-block text-center mb-3 px-4 py-2" style="border-radius: 10px; max-width: 650px; font-weight: 600; font-size: 15px; color: <?= $icon_col ?>; background-color: <?= $icon_bg ?>; border-color: <?= $icon_col ?>33;">
                            <?= $pesan_val ?>
                        </div>

                        <?php if ($status_val === 'DITOLAK' && !empty($status_krs['alasan_penolakan'])): ?>
                            <div class="mx-auto mb-4 text-left p-3 rounded" style="max-width: 550px; background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; font-size: 13.5px;">
                                <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle mr-1"></i> Catatan Verifikator Keuangan:</strong>
                                <?= htmlspecialchars($status_krs['alasan_penolakan']) ?>
                            </div>
                        <?php endif; ?>

                        <p class="text-muted mx-auto mb-4" style="max-width: 550px; font-size: 13.5px; line-height: 1.6;">
                            Sesuai kebijakan akademik Smart Campus, mahasiswa wajib melunasi pembayaran kewajiban semester aktif (SPP / UKT) dan telah terverifikasi oleh Bagian Keuangan agar formulir perwalian dan pengambilan mata kuliah dapat diakses.
                        </p>

                        <?php if (isset($status_krs['tagihan']) && !empty($status_krs['tagihan'])): ?>
                            <div class="mx-auto mb-4 p-3 rounded text-left" style="max-width: 500px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 13px;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Kewajiban Tagihan:</span>
                                    <strong class="text-dark"><?= htmlspecialchars($status_krs['tagihan']->jenis_tagihan) ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Semester / Tahun:</span>
                                    <span><?= htmlspecialchars($status_krs['tagihan']->semester) ?> <?= htmlspecialchars($status_krs['tagihan']->tahun_akademik) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Nominal:</span>
                                    <strong style="color: #1565c0; font-size: 14px;">Rp <?= number_format($status_krs['tagihan']->nominal, 0, ',', '.') ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Batas Jatuh Tempo:</span>
                                    <span class="text-danger font-weight-bold"><?= date('d M Y', strtotime($status_krs['tagihan']->jatuh_tempo)) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary btn-lg px-4 shadow" style="border-radius: 10px; font-weight: 700; font-size: 15px; background-color: #1565c0;">
                                <i class="bi bi-wallet2 mr-2"></i> Buka Menu Keuangan & Konfirmasi Pembayaran
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
