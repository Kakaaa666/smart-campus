<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Styling Khusus Dashboard Keuangan Admin -->
                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

                .admin-container {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .admin-card {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                    margin-bottom: 24px;
                    overflow: hidden;
                }

                .admin-card-header {
                    padding: 20px 24px;
                    border-bottom: 1px solid #f1f5f9;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 12px;
                }

                .admin-card-header h5 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 700;
                    color: #0f172a;
                }

                .stat-box-modern {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    padding: 22px 24px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .stat-box-modern:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
                }

                .table-admin thead th {
                    background: #f8fafc;
                    color: #475569;
                    font-weight: 700;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                    border-top: none;
                    border-bottom: 1.5px solid #e2e8f0;
                    padding: 14px 16px;
                    white-space: nowrap;
                }

                .table-admin tbody td {
                    padding: 14px 16px;
                    vertical-align: middle;
                    color: #334155;
                    font-size: 13.5px;
                    border-top: 1px solid #f1f5f9;
                }

                .badge-fakultas {
                    background: #e0f2fe;
                    color: #0369a1;
                    border: 1px solid #bae6fd;
                    padding: 3px 8px;
                    border-radius: 6px;
                    font-size: 11px;
                    font-weight: 700;
                    display: inline-block;
                }

                .badge-prodi {
                    background: #f1f5f9;
                    color: #334155;
                    border: 1px solid #cbd5e1;
                    padding: 2px 8px;
                    border-radius: 6px;
                    font-size: 11px;
                    font-weight: 600;
                    display: inline-block;
                }

                .shortcut-btn {
                    border-radius: 12px;
                    padding: 14px 18px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    color: #ffffff;
                    text-decoration: none;
                    transition: all 0.2s ease;
                    margin-bottom: 16px;
                }
                .shortcut-btn:hover {
                    color: #ffffff;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
                }
                </style>

                <div class="admin-container">

                    <!-- Flash Message -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981;">
                            <i class="fa fa-check-circle mr-2"></i><strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444;">
                            <i class="fa fa-exclamation-triangle mr-2"></i><strong>Perhatian:</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <!-- Header Banner -->
                    <div class="admin-card" style="border-left: 5px solid #1565c0;">
                        <div style="padding: 24px 28px;">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg,#1565c0,#0d47a1); display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(21, 101, 192, 0.25);">
                                            <i class="fa fa-tachometer" style="font-size: 24px; color: #ffffff;"></i>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0 0 4px; font-weight: 800; color: #0f172a; font-size: 22px;">
                                                Dashboard Eksekutif Keuangan Kampus
                                            </h4>
                                            <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                                                Ikhtisar penerimaan SPP/UKT, rasio realisasi biaya pendidikan, dan kontrol akses mahasiswa.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                    <a href="<?= base_url('keuangan/laporan') ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 700; padding: 8px 16px;">
                                        <i class="fa fa-bar-chart mr-1"></i>Buka Laporan Rektorat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0"><i class="fa fa-inbox mr-2 text-primary"></i>Progres Penagihan dari Biro Keuangan</h5>
                            <span class="text-muted small">Snapshot terbaru yang dikirim Admin Keuangan</span>
                        </div>
                        <div class="card-block table-responsive">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>Dikirim</th><th>Periode</th><th>Jumlah Tagihan</th><th>Lunas</th><th>Menunggu</th><th>Tunggakan</th><th>Nominal Tertagih</th><th>Nominal Lunas</th><th>Sisa Tunggakan</th></tr></thead>
                                <tbody>
                                <?php if (empty($laporan_progres_pimpinan)): ?>
                                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada laporan progres yang dikirim Biro Keuangan.</td></tr>
                                <?php else: foreach ($laporan_progres_pimpinan as $laporan): ?>
                                    <tr>
                                        <td><?= html_escape($laporan->dikirim_at) ?><br><small><?= html_escape($laporan->nama_pengirim ?: 'Admin Keuangan') ?></small></td>
                                        <td><?= html_escape($laporan->semester) ?> <?= html_escape($laporan->tahun_akademik) ?></td>
                                        <td><?= number_format((int)$laporan->jumlah_tagihan) ?></td>
                                        <td><?= number_format((int)$laporan->jumlah_lunas) ?></td>
                                        <td><?= number_format((int)$laporan->jumlah_pending) ?></td>
                                        <td><?= number_format((int)$laporan->jumlah_tunggakan) ?></td>
                                        <td>Rp <?= number_format((float)$laporan->nominal_total, 0, ',', '.') ?></td>
                                        <td class="text-success font-weight-bold">Rp <?= number_format((float)$laporan->nominal_lunas, 0, ',', '.') ?></td>
                                        <td class="text-danger font-weight-bold">Rp <?= number_format((float)$laporan->nominal_tunggakan, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 4 Kartu KPI Keuangan Utama -->
                    <div class="row mb-3">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-box-modern" style="border-left: 4px solid #10b981;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Realisasi Lunas</span>
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #059669;">
                                        <i class="fa fa-check-circle" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-bold mb-1" style="color: #047857; font-size: 22px;">
                                    Rp <?= number_format($nominal_lunas, 0, ',', '.') ?>
                                </h3>
                                <small class="text-success font-weight-bold">
                                    <i class="fa fa-check mr-1"></i><?= $stat_lunas ?> Transaksi Berhasil
                                </small>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-box-modern" style="border-left: 4px solid #f59e0b;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Menunggu Verifikasi</span>
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fffbeb; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                        <i class="fa fa-clock-o" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-bold mb-1" style="color: #b45309; font-size: 22px;">
                                    Rp <?= number_format($nominal_pending, 0, ',', '.') ?>
                                </h3>
                                <small class="text-warning font-weight-bold">
                                    <i class="fa fa-exclamation-circle mr-1"></i><?= $stat_pending ?> Pembayaran Pending
                                </small>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-box-modern" style="border-left: 4px solid #ef4444;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Tunggakan / Belum Bayar</span>
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fef2f2; display: flex; align-items: center; justify-content: center; color: #dc2626;">
                                        <i class="fa fa-exclamation-triangle" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-bold mb-1" style="color: #dc2626; font-size: 22px;">
                                    Rp <?= number_format($nominal_belum, 0, ',', '.') ?>
                                </h3>
                                <small class="text-danger font-weight-bold">
                                    Total Kewajiban Terbuka
                                </small>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-box-modern" style="border-left: 4px solid #6366f1;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Total Mahasiswa</span>
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #eef2ff; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                                        <i class="fa fa-users" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-bold mb-1" style="color: #312e81; font-size: 22px;">
                                    <?= $stat_mahasiswa ?> Mahasiswa
                                </h3>
                                <small class="text-primary font-weight-bold">
                                    Aktif Semester Ini
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Pintasan Cepat Menu Operasional -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="<?= base_url('keuangan/verifikasi') ?>" class="shortcut-btn" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                                <div class="d-flex align-items-center">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                                        <i class="fa fa-check-square-o" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 15px;">Buka Verifikasi Pembayaran</div>
                                        <div style="font-size: 12.5px; opacity: 0.9;">Ada <strong><?= $stat_pending ?> pembayaran</strong> menunggu persetujuan Anda</div>
                                    </div>
                                </div>
                                <i class="fa fa-arrow-right" style="font-size: 16px;"></i>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('keuangan/kontrol_ta') ?>" class="shortcut-btn" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                                <div class="d-flex align-items-center">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                                        <i class="fa fa-graduation-cap" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 15px;">Buka Kontrol Akses Tagihan</div>
                                        <div style="font-size: 12.5px; opacity: 0.9;">Buka / tutup izin tagihan semester akhir per-mahasiswa</div>
                                    </div>
                                </div>
                                <i class="fa fa-arrow-right" style="font-size: 16px;"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Filter Fakultas / Prodi & Live Search -->
                    <div class="admin-card">
                        <div style="padding: 16px 24px;">
                            <form method="GET" action="<?= base_url('keuangan/admin') ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Fakultas</label>
                                        <select name="fakultas" class="form-control form-control-sm" style="height: 38px; border-radius: 8px;" onchange="this.form.submit()">
                                            <option value="">Semua Fakultas</option>
                                            <?php foreach ($daftar_fakultas as $fak): ?>
                                                <option value="<?= htmlspecialchars($fak) ?>" <?= ($filter_fakultas === $fak) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($fak) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Program Studi</label>
                                        <select name="prodi" class="form-control form-control-sm" style="height: 38px; border-radius: 8px;" onchange="this.form.submit()">
                                            <option value="">Semua Program Studi</option>
                                            <?php foreach ($daftar_prodi as $prd): ?>
                                                <option value="<?= htmlspecialchars($prd) ?>" <?= ($filter_prodi === $prd) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($prd) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Pencarian Cepat</label>
                                        <div class="input-group">
                                            <input type="text" id="liveSearchDashboard" class="form-control form-control-sm" placeholder="Ketik nama mahasiswa atau NIM..." style="height: 38px; border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="background:#f8fafc; border-radius: 0 8px 8px 0;"><i class="fa fa-search text-muted"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Monitoring Kemajuan Pembayaran Mahasiswa -->
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <div>
                                <h5><i class="fa fa-users mr-2" style="color:#1565c0;"></i>Status Tagihan Mahasiswa (Per Jurusan)</h5>
                                <div style="font-size: 13px; color: #64748b; margin-top: 3px;">
                                    Monitoring kemajuan pelunasan tagihan per mahasiswa
                                </div>
                            </div>
                            <span class="badge badge-light px-3 py-2" style="border: 1px solid #cbd5e1; font-size: 12px; font-weight: 700;">
                                Total: <?= count($mahasiswa_belum_lunas) ?> Mahasiswa
                            </span>
                        </div>
                        <div class="table-responsive">
                            <?php if (empty($mahasiswa_belum_lunas)): ?>
                                <div style="text-align:center; padding: 48px 24px; color: #94a3b8;">
                                    <i class="fa fa-users" style="font-size: 44px; display: block; margin-bottom: 12px; color: #cbd5e1;"></i>
                                    <p style="font-weight: 600; color: #475569; font-size: 15px;">Tidak ada data mahasiswa pada filter yang dipilih</p>
                                </div>
                            <?php else: ?>
                                <table class="table table-admin mb-0" id="tableDashboardMhs">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;" class="text-center">No</th>
                                            <th>Mahasiswa</th>
                                            <th>Fakultas</th>
                                            <th>Program Studi</th>
                                            <th class="text-right">Total Tagihan</th>
                                            <th class="text-right">Realisasi Lunas</th>
                                            <th class="text-center">Progress</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($mahasiswa_belum_lunas as $i => $m): ?>
                                            <?php
                                                $pct = ($m->total_nominal > 0) ? round(($m->nominal_lunas / $m->total_nominal) * 100) : 0;
                                                $is_fully_lunas = ($m->tagihan_belum == 0 && $m->tagihan_pending == 0 && $m->total_tagihan > 0);
                                                $is_pending = ($m->tagihan_pending > 0);
                                            ?>
                                            <tr class="searchable-row" data-search="<?= strtolower($m->nama_lengkap . ' ' . $m->nim . ' ' . ($m->fakultas ?? '') . ' ' . ($m->prodi ?? '')) ?>">
                                                <td class="text-center font-weight-bold text-muted"><?= $i + 1 ?></td>
                                                <td>
                                                    <div style="font-weight: 700; font-size: 14px; color: #0f172a;"><?= htmlspecialchars($m->nama_lengkap) ?></div>
                                                    <div style="font-size: 12px; color: #64748b; font-family: monospace;">NIM: <?= htmlspecialchars($m->nim) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge-fakultas"><?= htmlspecialchars($m->fakultas ?: '-') ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge-prodi"><?= htmlspecialchars($m->prodi ?: '-') ?></span>
                                                    <small class="text-muted ml-1">Smstr <?= htmlspecialchars($m->semester_mhs ?: '1') ?></small>
                                                </td>
                                                <td class="text-right font-weight-bold" style="color: #0f172a;">
                                                    Rp <?= number_format($m->total_nominal, 0, ',', '.') ?>
                                                </td>
                                                <td class="text-right font-weight-bold" style="color: #059669;">
                                                    Rp <?= number_format($m->nominal_lunas, 0, ',', '.') ?>
                                                </td>
                                                <td class="text-center" style="width: 140px;">
                                                    <div class="progress" style="height: 7px; border-radius: 4px; background: #e2e8f0; margin-bottom: 3px;">
                                                        <div class="progress-bar <?= ($pct == 100) ? 'bg-success' : 'bg-primary' ?>"
                                                             role="progressbar" style="width: <?= $pct ?>%; border-radius: 4px;"></div>
                                                    </div>
                                                    <small style="font-weight: 700; color: #475569;"><?= $pct ?>% Terbayar</small>
                                                </td>
                                                <td>
                                                    <?php if ($is_fully_lunas): ?>
                                                        <span class="badge badge-success" style="padding: 5px 10px; border-radius: 12px; font-weight: 700;">
                                                            <i class="fa fa-check mr-1"></i>LUNAS PENUH
                                                        </span>
                                                    <?php elseif ($is_pending): ?>
                                                        <span class="badge badge-warning text-dark" style="padding: 5px 10px; border-radius: 12px; font-weight: 700;">
                                                            <i class="fa fa-clock-o mr-1"></i>ADA PENDING
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger" style="padding: 5px 10px; border-radius: 12px; font-weight: 700;">
                                                            <i class="fa fa-exclamation mr-1"></i>BELUM LUNAS
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-sm"
                                                            style="background:#eff6ff; color:#1d4ed8; border-radius:8px; font-size:12px; font-weight:600; padding:6px 14px; border:1px solid #bfdbfe;"
                                                            onclick="bukaDetailTagihan(<?= (int)$m->id ?>)">
                                                        <i class="fa fa-receipt mr-1"></i>Rincian Tagihan
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Rincian Tagihan Mahasiswa -->
<div class="modal fade" id="modalDetailTagihan" tabindex="-1" role="dialog" aria-labelledby="modalDetailTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width:850px;">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 10px 40px rgba(0,0,0,0.15); overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#1976d2,#1565c0); color:#fff; border-bottom:none; padding:18px 24px;">
                <h5 class="modal-title font-weight-bold" id="modalDetailTagihanLabel" style="font-size:16px; display:flex; align-items:center; margin:0;">
                    <i class="fa fa-receipt mr-2"></i>Rincian Tagihan Mahasiswa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:0.9; text-shadow:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalDetailTagihanBody" style="padding:0; max-height:75vh; overflow-y:auto;">
                <!-- Konten dinamis AJAX -->
            </div>
            <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:12px 24px;">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius:8px; font-weight:600; padding:6px 16px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function bukaDetailTagihan(mahasiswaId) {
    if (!mahasiswaId) return;

    var $modal = $('#modalDetailTagihan');
    var $modalBody = $('#modalDetailTagihanBody');

    // Tampilkan modal langsung dengan status memuat
    $modalBody.html(
        '<div style="padding:48px 24px;text-align:center;">' +
        '<div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem;border-width:3px;"></div>' +
        '<div style="margin-top:16px;color:#64748b;font-weight:600;font-size:13px;">Memuat rincian tagihan mahasiswa...</div>' +
        '</div>'
    );
    $modal.modal('show');

    var url = '<?= base_url("keuangan/detail_tagihan_mahasiswa") ?>?mahasiswa_id=' + encodeURIComponent(mahasiswaId);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        cache: false,
        xhrFields: { withCredentials: true },
        success: function(resp) {
            if (!resp || !resp.success) {
                var errMsg = (resp && resp.message) ? resp.message : 'Data rincian tagihan tidak ditemukan.';
                $modalBody.html(
                    '<div style="padding:40px 24px;text-align:center;">' +
                    '<i class="fa fa-exclamation-circle text-danger" style="font-size:42px;margin-bottom:12px;"></i>' +
                    '<h6 style="font-weight:700;color:#1e293b;margin-bottom:6px;">Gagal Memuat Data</h6>' +
                    '<p style="color:#64748b;font-size:13px;margin-bottom:0;">' + errMsg + '</p>' +
                    '</div>'
                );
                return;
            }

            var mhs = resp.mahasiswa || {};
            var rs = resp.ringkasan || {};
            var tagihan = resp.tagihan || [];

            var inisial = (mhs.nama || 'M').trim().split(/\s+/).map(function(w){ return w ? w[0] : ''; }).join('').substring(0,2).toUpperCase();

            var rows = '';
            if (tagihan.length === 0) {
                rows = '<tr><td colspan="5" style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;">Belum ada tagihan untuk mahasiswa ini.</td></tr>';
            } else {
                tagihan.forEach(function(t, i) {
                    var badgeStyle = '';
                    var badgeLabel = t.status_tagihan;
                    var badgeIcon = 'fa-circle';

                    if (t.status_tagihan === 'LUNAS') {
                        badgeStyle = 'background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;';
                        badgeLabel = 'LUNAS';
                        badgeIcon = 'fa-check-circle';
                    } else if (t.status_tagihan === 'PENDING') {
                        badgeStyle = 'background:#fef3c7;color:#b45309;border:1px solid #fde68a;';
                        badgeLabel = 'Menunggu Verifikasi';
                        badgeIcon = 'fa-clock-o';
                    } else {
                        badgeStyle = 'background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;';
                        badgeLabel = 'Belum Bayar';
                        badgeIcon = 'fa-exclamation-circle';
                    }

                    var pembayaranHtml = '';
                    if (t.pembayaran) {
                        var pStatusStyle = t.pembayaran.status === 'LUNAS' 
                            ? 'background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;'
                            : 'background:#fef3c7;color:#b45309;border:1px solid #fde68a;';
                        pembayaranHtml = '<div>'
                            + '<span style="font-size:10.5px;padding:2px 8px;border-radius:12px;font-weight:700;' + pStatusStyle + '">' + t.pembayaran.status + '</span> '
                            + '<span style="color:#475569;font-size:11px;">' + (t.pembayaran.tanggal || '') + ' &bull; ' + (t.pembayaran.metode || '') + '</span>'
                            + (t.pembayaran.verifikator ? ' <div style="font-size:10.5px;color:#94a3b8;margin-top:2px;">Diverifikasi oleh: ' + t.pembayaran.verifikator + '</div>' : '')
                            + '<div style="margin-top:6px;">'
                            + '<a href="<?= base_url("keuangan/lihat_bukti/") ?>' + t.pembayaran.id + '" target="_blank" class="btn btn-xs" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:6px;font-size:10.5px;padding:3px 8px;font-weight:600;"><i class="fa fa-eye mr-1"></i>Lihat Bukti</a>'
                            + '</div></div>';
                    } else {
                        pembayaranHtml = '<span style="color:#94a3b8;font-size:12px;font-style:italic;">Belum ada pembayaran</span>';
                    }

                    rows += '<tr>'
                        + '<td style="font-weight:600;color:#94a3b8;width:30px;text-align:center;">' + (i + 1) + '</td>'
                        + '<td style="font-size:13px;"><strong style="color:#1e293b;">' + (t.jenis || '-') + '</strong><div style="font-size:11px;color:#94a3b8;margin-top:2px;">' + (t.tahun || '') + ' &bull; ' + (t.semester || '') + '</div></td>'
                        + '<td style="font-weight:700;color:#1e293b;font-size:13px;white-space:nowrap;">Rp ' + Number(t.nominal || 0).toLocaleString('id-ID') + '</td>'
                        + '<td style="white-space:nowrap;"><span style="font-weight:700;padding:4px 10px;border-radius:12px;font-size:11px;display:inline-flex;align-items:center;gap:4px;' + badgeStyle + '"><i class="fa ' + badgeIcon + '"></i>' + badgeLabel + '</span></td>'
                        + '<td style="font-size:12px;">' + pembayaranHtml + '</td>'
                        + '</tr>';
                });
            }

            var html = '<div style="padding:24px 28px;">'
                + '<div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #e2e8f0;">'
                + '<div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#1976d2,#1565c0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:800;flex-shrink:0;">' + inisial + '</div>'
                + '<div style="flex:1;min-width:0;">'
                + '<div style="font-size:17px;font-weight:800;color:#0f172a;line-height:1.3;">' + (mhs.nama || '-') + '</div>'
                + '<div style="font-size:12.5px;color:#64748b;margin-top:3px;">'
                + '<span>NIM: <strong>' + (mhs.nim || '-') + '</strong></span> &bull; '
                + '<span>' + (mhs.prodi || '-') + '</span> &bull; '
                + '<span>Semester ' + (mhs.semester || '-') + '</span>'
                + (mhs.fakultas ? ' &bull; <span class="badge badge-light" style="font-size:11px;font-weight:600;border:1px solid #e2e8f0;">' + mhs.fakultas + '</span>' : '')
                + '</div></div></div>'

                + '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;">'
                + '<div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px;text-align:center;"><div style="font-size:10.5px;color:#1d4ed8;font-weight:700;text-transform:uppercase;">Total Tagihan</div><div style="font-size:17px;font-weight:800;color:#1e293b;margin-top:4px;">Rp ' + Number(rs.total_tagihan || 0).toLocaleString('id-ID') + '</div></div>'
                + '<div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:12px;text-align:center;"><div style="font-size:10.5px;color:#15803d;font-weight:700;text-transform:uppercase;">Sudah Lunas</div><div style="font-size:17px;font-weight:800;color:#15803d;margin-top:4px;">Rp ' + Number(rs.total_lunas || 0).toLocaleString('id-ID') + '</div></div>'
                + '<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:12px;text-align:center;"><div style="font-size:10.5px;color:#b45309;font-weight:700;text-transform:uppercase;">Menunggu Verifikasi</div><div style="font-size:17px;font-weight:800;color:#b45309;margin-top:4px;">Rp ' + Number(rs.total_pending || 0).toLocaleString('id-ID') + '</div></div>'
                + '<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px;text-align:center;"><div style="font-size:10.5px;color:#b91c1c;font-weight:700;text-transform:uppercase;">Belum Bayar</div><div style="font-size:17px;font-weight:800;color:#b91c1c;margin-top:4px;">Rp ' + Number(rs.total_belum || 0).toLocaleString('id-ID') + '</div></div>'
                + '</div>'

                + '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">'
                + '<h6 style="font-weight:700;color:#0f172a;margin:0;font-size:14px;"><i class="fa fa-list-alt mr-2" style="color:#1976d2;"></i>Daftar Tagihan Mahasiswa</h6>'
                + '<span style="font-size:12px;color:#64748b;font-weight:600;">' + tagihan.length + ' Tagihan Terdaftar</span>'
                + '</div>'

                + '<div class="table-responsive" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">'
                + '<table class="table table-hover mb-0" style="font-size:13px;">'
                + '<thead style="background:#f8fafc;">'
                + '<tr>'
                + '<th style="width:35px;text-align:center;color:#475569;font-weight:700;">No</th>'
                + '<th style="color:#475569;font-weight:700;">Jenis Tagihan</th>'
                + '<th style="color:#475569;font-weight:700;">Nominal</th>'
                + '<th style="color:#475569;font-weight:700;width:160px;">Status</th>'
                + '<th style="color:#475569;font-weight:700;">Riwayat Pembayaran</th>'
                + '</tr></thead>'
                + '<tbody>' + rows + '</tbody>'
                + '</table>'
                + '</div>'

                + '<div style="margin-top:18px;padding:14px 18px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">'
                + '<div><strong style="color:#475569;font-size:13px;">Total Realisasi Pembayaran Resmi:</strong></div>'
                + '<div style="font-size:19px;font-weight:800;color:#15803d;">Rp ' + Number(rs.total_pengeluaran || rs.total_lunas || 0).toLocaleString('id-ID') + '</div>'
                + '</div>'
                + '</div>';

            $modalBody.html(html);
        },
        error: function(xhr, status, err) {
            console.error('AJAX error:', status, err);
            var message = 'Gagal memuat rincian tagihan. Silakan periksa koneksi atau sesi login Anda.';
            try {
                var json = JSON.parse(xhr.responseText || '{}');
                if (json && json.message) {
                    message = json.message;
                }
            } catch (e) {
                if (typeof xhr.responseText === 'string' && xhr.responseText.toLowerCase().indexOf('<html') !== -1) {
                    message = 'Sesi login tidak valid atau rute tidak dapat diakses.';
                }
            }

            $modalBody.html(
                '<div style="padding:40px 24px;text-align:center;">' +
                '<i class="fa fa-exclamation-triangle text-warning" style="font-size:42px;margin-bottom:12px;"></i>' +
                '<h6 style="font-weight:700;color:#1e293b;margin-bottom:6px;">Gagal Memuat Data</h6>' +
                '<p style="color:#64748b;font-size:13px;margin-bottom:16px;">' + message + '</p>' +
                '<button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-weight:600;" onclick="bukaDetailTagihan(' + mahasiswaId + ')"><i class="fa fa-refresh mr-1"></i>Coba Lagi</button>' +
                '</div>'
            );
        }
    });
}

// Live search pencarian mahasiswa
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('liveSearchDashboard');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var keyword = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('#tableDashboardMhs .searchable-row');
            rows.forEach(function(row) {
                var text = row.getAttribute('data-search') || '';
                if (keyword === '' || text.indexOf(keyword) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
