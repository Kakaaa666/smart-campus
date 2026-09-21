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
                                        <div style="font-weight: 700; font-size: 15px;">Buka Kontrol Akses Tugas Akhir</div>
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
                                                    <a href="<?= base_url('keuangan?mahasiswa_id=' . $m->id) ?>" target="_blank"
                                                       class="btn btn-sm" style="background:#eff6ff; color:#1d4ed8; border-radius:8px; font-size:12px; font-weight:600; padding:6px 14px; border:1px solid #bfdbfe;">
                                                        <i class="fa fa-eye mr-1"></i>Pratinjau
                                                    </a>
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

<script>
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
