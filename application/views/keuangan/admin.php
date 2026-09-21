<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <style>
                .admin-stat-card {
                    border-radius: 16px;
                    padding: 22px 24px;
                    border: none;
                    position: relative;
                    overflow: hidden;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                    color: #ffffff;
                }
                .admin-stat-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important;
                }
                .admin-stat-card .stat-icon {
                    width: 52px; height: 52px; border-radius: 14px;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 24px;
                    background: rgba(255,255,255,0.2);
                }
                .admin-stat-card .stat-value {
                    font-size: 30px; font-weight: 800; line-height: 1; margin: 12px 0 4px;
                }
                .admin-stat-card .stat-label { font-size: 13px; font-weight: 500; opacity: 0.88; }
                .card-pending  { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
                .card-lunas    { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
                .card-ditolak  { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); }
                .card-mhs      { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }
                
                .admin-card {
                    background: #ffffff; border-radius: 16px; border: 1px solid #e8edf3;
                    box-shadow: 0 2px 12px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 24px;
                }
                .admin-card-header {
                    padding: 18px 24px; border-bottom: 1px solid #f1f5f9;
                    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
                }
                .admin-card-header h5 { margin: 0; font-size: 16px; font-weight: 700; color: #1e293b; }
                
                /* Badges */
                .badge-fakultas {
                    background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;
                    padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;
                    display: inline-block;
                }
                .badge-prodi {
                    background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;
                    padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600;
                    display: inline-block;
                }
                .badge-st {
                    padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
                    display: inline-flex; align-items: center; gap: 5px; letter-spacing: 0.4px;
                }
                .badge-pending-st  { background: #fef3c7; color: #b45309; border: 1px solid #fcd34d; }
                .badge-lunas-st    { background: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }
                .badge-ditolak-st  { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
                .badge-belum-st    { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

                .table-admin thead th {
                    background: #f8fafc; color: #475569; font-weight: 700; font-size: 12.5px;
                    text-transform: uppercase; letter-spacing: 0.6px; border-top: none;
                    border-bottom: 1.5px solid #e2e8f0; padding: 14px 16px; white-space: nowrap;
                }
                .table-admin tbody td {
                    padding: 14px 16px; vertical-align: middle;
                    color: #334155; font-size: 13.5px; border-top: 1px solid #f1f5f9;
                }
                .table-admin tbody tr:hover { background-color: #f8fafc; }
                .progress-slim { height: 6px; border-radius: 3px; margin-top: 4px; }
                .progress-slim .progress-bar { border-radius: 3px; }
                
                .detail-row {
                    display: flex; border-bottom: 1px solid #f1f5f9;
                    padding: 10px 0; align-items: flex-start;
                }
                .detail-row:last-child { border-bottom: none; }
                .detail-label {
                    min-width: 150px; font-size: 12.5px; color: #64748b;
                    font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; padding-top: 2px;
                }
                .detail-value { flex: 1; font-size: 14px; color: #1e293b; font-weight: 500; }
                
                /* Filter Bar */
                .filter-box {
                    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;
                }
                </style>

                <!-- Header Dashboard & Shortcut Laporan -->
                <div class="admin-card" style="border-left: 4px solid #1976d2;">
                    <div style="padding: 20px 28px;">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div style="display:flex; align-items:center; gap:14px;">
                                    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#1565c0,#1976d2);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa fa-money" style="font-size:22px;color:#fff;"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin:0 0 4px;font-size:20px;font-weight:800;color:#1e293b;">
                                            Dashboard Admin Keuangan
                                        </h4>
                                        <p style="margin:0;color:#64748b;font-size:13px;">
                                            Verifikasi pembayaran real-time, kontrol akses Tugas Akhir, dan pembeda Fakultas / Program Studi
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                                <a href="<?= base_url('keuangan/laporan') ?>" class="btn btn-sm mr-2"
                                   style="background:linear-gradient(135deg,#6366f1,#4338ca);color:#fff;border-radius:8px;font-size:13px;font-weight:600;padding:9px 18px;">
                                    <i class="fa fa-bar-chart mr-1"></i> Laporan Rektorat
                                </a>
                                <a href="<?= base_url('keuangan?mahasiswa_id=3') ?>" target="_blank" class="btn btn-sm btn-outline-primary"
                                   style="border-radius:8px;font-size:13px;font-weight:600;padding:8px 14px;" title="Lihat Tampilan Mahasiswa">
                                    <i class="fa fa-external-link mr-1"></i> Preview Mahasiswa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Feedback -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                         style="border-radius:12px;background:#f0fdf4;border:1.5px solid #86efac;color:#166534;">
                        <i class="fa fa-check-circle mr-2"></i>
                        <strong><?= $this->session->flashdata('success') ?></strong>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                         style="border-radius:12px;background:#fef2f2;border:1.5px solid #fca5a5;color:#991b1b;">
                        <i class="fa fa-exclamation-circle mr-2"></i>
                        <strong><?= $this->session->flashdata('error') ?></strong>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- Baris Statistik & Widget Kontrol Akses Tugas Akhir -->
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="admin-stat-card card-pending">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><div class="stat-value"><?= $stat_pending ?></div><div class="stat-label">Menunggu Verifikasi</div></div>
                                <div class="stat-icon"><i class="fa fa-clock-o"></i></div>
                            </div>
                            <div style="margin-top:14px;font-size:12px;opacity:0.8;"><i class="fa fa-arrow-right mr-1"></i>Verifikasi real-time</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="admin-stat-card card-lunas">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><div class="stat-value"><?= $stat_lunas ?></div><div class="stat-label">Diverifikasi LUNAS</div></div>
                                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                            </div>
                            <div style="margin-top:14px;font-size:12px;opacity:0.8;"><i class="fa fa-check mr-1"></i>Akses KRS Terbuka</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="admin-stat-card card-ditolak">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><div class="stat-value"><?= $stat_ditolak ?></div><div class="stat-label">Pembayaran Ditolak</div></div>
                                <div class="stat-icon"><i class="fa fa-times-circle"></i></div>
                            </div>
                            <div style="margin-top:14px;font-size:12px;opacity:0.8;"><i class="fa fa-exclamation-triangle mr-1"></i>Perlu upload ulang</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="admin-stat-card card-mhs">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><div class="stat-value"><?= $stat_mahasiswa ?></div><div class="stat-label">Total Mahasiswa Aktif</div></div>
                                <div class="stat-icon"><i class="fa fa-users"></i></div>
                            </div>
                            <div style="margin-top:14px;font-size:12px;opacity:0.8;"><i class="fa fa-graduation-cap mr-1"></i>Lintas Fakultas &amp; Prodi</div>
                        </div>
                    </div>
                </div>

                <!-- WIDGET KONTROL AKSES PEMBAYARAN TUGAS AKHIR (ADMIN KONTROL) -->
                <div class="admin-card mb-4" style="background: #faf5ff; border: 1.5px solid #e9d5ff;">
                    <div style="padding: 18px 24px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #7e22ce; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;" class="mr-3">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <h5 style="margin: 0; font-weight: 700; color: #581c87; font-size: 16px;">
                                            Kontrol Akses Pembayaran Tugas Akhir &amp; Kelulusan
                                        </h5>
                                        <p style="margin: 0; color: #7e22ce; font-size: 13px;">
                                            Status saat ini: 
                                            <?php if ($akses_tugas_akhir): ?>
                                                <strong class="text-success"><i class="fa fa-check-circle"></i> AKSES DIBUKA</strong> (Mahasiswa semester akhir dapat membayar bimbingan/ujian TA).
                                            <?php else: ?>
                                                <strong class="text-danger"><i class="fa fa-lock"></i> AKSES DITUTUP</strong> (Menu pembayaran Tugas Akhir terkunci untuk mahasiswa).
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                <form method="POST" action="<?= base_url('keuangan/toggle_akses_ta') ?>" style="display: inline;">
                                    <input type="hidden" name="status" value="<?= $akses_tugas_akhir ? '0' : '1' ?>">
                                    <?php if ($akses_tugas_akhir): ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold px-3 py-2" style="border-radius: 8px;">
                                            <i class="fa fa-lock mr-1"></i> Tutup Akses Tugas Akhir
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-success font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fa fa-unlock-alt mr-1"></i> Buka Akses Tugas Akhir
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FILTER BAR (MEMBEDAKAN FAKULTAS, PRODI & LIVE SEARCH) -->
                <div class="filter-box">
                    <form method="GET" action="<?= base_url('keuangan/admin') ?>" id="formFilterAdmin">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; text-transform: uppercase;">
                                    <i class="fa fa-university mr-1 text-primary"></i> Filter Fakultas
                                </label>
                                <select name="fakultas" class="form-control form-control-sm" style="height: 38px; border-radius: 8px;" onchange="document.getElementById('formFilterAdmin').submit();">
                                    <option value="">-- Semua Fakultas --</option>
                                    <?php foreach ($daftar_fakultas as $fak): ?>
                                        <option value="<?= htmlspecialchars($fak) ?>" <?= ($filter_fakultas === $fak) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fak) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-2 mb-md-0">
                                <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; text-transform: uppercase;">
                                    <i class="fa fa-graduation-cap mr-1 text-primary"></i> Filter Program Studi (Prodi)
                                </label>
                                <select name="prodi" class="form-control form-control-sm" style="height: 38px; border-radius: 8px;" onchange="document.getElementById('formFilterAdmin').submit();">
                                    <option value="">-- Semua Program Studi --</option>
                                    <?php foreach ($daftar_prodi as $prd): ?>
                                        <option value="<?= htmlspecialchars($prd) ?>" <?= ($filter_prodi === $prd) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($prd) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; text-transform: uppercase;">
                                    <i class="fa fa-search mr-1 text-primary"></i> Cari Cepat Mahasiswa (Nama / NIM)
                                </label>
                                <div class="input-group">
                                    <input type="text" id="liveSearchInput" class="form-control form-control-sm" placeholder="Ketik nama, NIM, atau prodi..." style="height: 38px; border-radius: 8px 0 0 8px;">
                                    <div class="input-group-append">
                                        <?php if (!empty($filter_fakultas) || !empty($filter_prodi)): ?>
                                            <a href="<?= base_url('keuangan/admin') ?>" class="btn btn-sm btn-outline-secondary" style="height: 38px; line-height: 24px; border-radius: 0 8px 8px 0;" title="Reset Filter">
                                                <i class="fa fa-times text-danger"></i> Reset
                                            </a>
                                        <?php else: ?>
                                            <span class="input-group-text" style="border-radius: 0 8px 8px 0;"><i class="fa fa-search"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel 1: Antrian Verifikasi Pembayaran (Pending) -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div>
                            <h5><i class="fa fa-hourglass-half mr-2" style="color:#f59e0b;"></i>Antrian Verifikasi Pembayaran (Pending)</h5>
                            <div style="font-size:13px;color:#64748b;margin-top:3px;">
                                Bukti transfer mahasiswa menunggu persetujuan verifikasi keuangan
                            </div>
                        </div>
                        <span class="badge" style="background:#fef3c7;color:#b45309;font-size:13px;padding:6px 14px;border-radius:20px;font-weight:700;">
                            <?= count($pembayaran_pending) ?> Menunggu
                        </span>
                    </div>
                    <div class="table-responsive">
                        <?php if (empty($pembayaran_pending)): ?>
                            <div style="text-align:center;padding:48px 24px;color:#94a3b8;">
                                <i class="fa fa-check-circle" style="font-size:48px;color:#10b981;display:block;margin-bottom:12px;"></i>
                                <p style="font-size:16px;font-weight:600;color:#475569;">Tidak ada antrian pembayaran menunggu verifikasi</p>
                                <p style="font-size:13px;">Semua konfirmasi pembayaran mahasiswa telah diproses.</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-admin mb-0" id="tablePending">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Fakultas &amp; Program Studi</th>
                                        <th>Kewajiban Tagihan</th>
                                        <th>Nominal</th>
                                        <th>Metode &amp; Ref</th>
                                        <th>Bukti Transfer</th>
                                        <th class="text-center" style="width: 170px;">Aksi Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pembayaran_pending as $i => $p): ?>
                                        <tr class="searchable-row" data-search="<?= strtolower($p->nama_mahasiswa . ' ' . $p->nim_mahasiswa . ' ' . ($p->fakultas_mahasiswa ?? '') . ' ' . ($p->prodi_mahasiswa ?? '')) ?>">
                                            <td style="font-weight:600;color:#94a3b8;"><?= $i + 1 ?></td>
                                            <td>
                                                <div style="font-weight:700;font-size:14px;color:#1e293b;"><?= htmlspecialchars($p->nama_mahasiswa) ?></div>
                                                <div style="font-size:12px;color:#64748b; font-family: monospace;">NIM: <?= htmlspecialchars($p->nim_mahasiswa) ?></div>
                                            </td>
                                            <td>
                                                <div class="badge-fakultas mb-1"><?= htmlspecialchars($p->fakultas_mahasiswa ?: 'Fakultas Ilmu Komputer') ?></div>
                                                <div>
                                                    <span class="badge-prodi"><?= htmlspecialchars($p->prodi_mahasiswa ?: 'D3 Sistem Informasi') ?></span>
                                                    <small class="text-muted ml-1">Smstr <?= htmlspecialchars($p->semester_mahasiswa ?: '5') ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-weight:600;font-size:13.5px;"><?= htmlspecialchars($p->jenis_tagihan) ?></div>
                                                <div style="font-size:12px;color:#64748b;"><?= $p->tahun_akademik ?> &bull; <?= $p->semester ?></div>
                                            </td>
                                            <td style="font-weight:700;color:#1e293b; font-size: 14.5px;">
                                                Rp <?= number_format($p->nominal_pembayaran, 0, ',', '.') ?>
                                            </td>
                                            <td style="font-size:12.5px;">
                                                <div><?= htmlspecialchars($p->metode_pembayaran) ?></div>
                                                <small class="text-muted" style="font-family: monospace;"><?= $p->nomor_referensi ?: '-' ?></small>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('keuangan/lihat_bukti/' . $p->id) ?>" target="_blank"
                                                   class="btn btn-sm" style="background:#eff6ff;color:#1d4ed8;border-radius:8px;font-size:12px;font-weight:600;">
                                                    <i class="fa fa-eye mr-1"></i>Lihat Bukti
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div style="display:flex;gap:6px;justify-content:center;">
                                                    <button class="btn btn-sm"
                                                            style="background:linear-gradient(135deg,#10b981,#047857);color:#fff;border-radius:8px;font-size:12px;font-weight:600;padding:7px 14px;"
                                                            onclick="konfirmasiVerifikasi(<?= $p->id ?>, '<?= addslashes($p->nama_mahasiswa) ?>', '<?= addslashes($p->jenis_tagihan) ?>', <?= $p->nominal_pembayaran ?>)">
                                                        <i class="fa fa-check mr-1"></i>Setujui
                                                    </button>
                                                    <button class="btn btn-sm"
                                                            style="background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;border-radius:8px;font-size:12px;font-weight:600;padding:7px 12px;"
                                                            onclick="bukaModalTolak(<?= $p->id ?>, '<?= addslashes($p->nama_mahasiswa) ?>', '<?= addslashes($p->jenis_tagihan) ?>')">
                                                        <i class="fa fa-times mr-1"></i>Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tabel 2: Status Tagihan Mahasiswa (dengan Kolom Fakultas & Prodi) -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div>
                            <h5><i class="fa fa-users mr-2" style="color:#6366f1;"></i>Status Tagihan Semua Mahasiswa (Per Fakultas &amp; Prodi)</h5>
                            <div style="font-size:13px;color:#64748b;margin-top:3px;">
                                Monitoring kemajuan pembayaran mahasiswa per jurusan untuk memudahkan pengawasan
                            </div>
                        </div>
                        <span class="badge badge-light px-3 py-2" style="border: 1px solid #cbd5e1; font-size: 12px;">
                            Total: <strong><?= count($mahasiswa_belum_lunas) ?> Mahasiswa</strong>
                        </span>
                    </div>
                    <div class="table-responsive">
                        <?php if (empty($mahasiswa_belum_lunas)): ?>
                            <div style="text-align:center;padding:48px 24px;color:#94a3b8;">
                                <i class="fa fa-users" style="font-size:48px;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
                                <p style="font-size:15px;font-weight:600;color:#475569;">Belum ada data mahasiswa yang sesuai filter</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-admin mb-0" id="tableMhs">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Fakultas</th>
                                        <th>Program Studi</th>
                                        <th>Total Tagihan</th>
                                        <th>Terbayar</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mahasiswa_belum_lunas as $i => $mhs): ?>
                                        <?php
                                            $pct = $mhs->total_tagihan > 0
                                                ? round(($mhs->nominal_lunas / $mhs->total_nominal) * 100)
                                                : 0;
                                            $bar_color = $pct >= 100 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
                                        ?>
                                        <tr class="searchable-row" data-search="<?= strtolower($mhs->nama_lengkap . ' ' . $mhs->nim . ' ' . ($mhs->fakultas ?? '') . ' ' . ($mhs->prodi ?? '')) ?>">
                                            <td style="font-weight:600;color:#94a3b8;"><?= $i + 1 ?></td>
                                            <td>
                                                <div style="font-weight:700;font-size:14px;color:#1e293b;"><?= htmlspecialchars($mhs->nama_lengkap) ?></div>
                                                <div style="font-size:12px;color:#64748b; font-family: monospace;">NIM: <?= htmlspecialchars($mhs->nim) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge-fakultas"><?= htmlspecialchars($mhs->fakultas ?: 'Fakultas Ilmu Komputer') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge-prodi"><?= htmlspecialchars($mhs->prodi ?: 'D3 Sistem Informasi') ?></span>
                                                <small class="text-muted d-block mt-1">Semester <?= htmlspecialchars($mhs->semester_mhs ?: '5') ?></small>
                                            </td>
                                            <td style="font-weight:700;color:#1e293b;">
                                                Rp <?= number_format($mhs->total_nominal, 0, ',', '.') ?>
                                                <div style="font-size:11px;color:#64748b;font-weight:400;"><?= $mhs->total_tagihan ?> tagihan</div>
                                            </td>
                                            <td style="font-weight:700;color:#047857;">
                                                Rp <?= number_format($mhs->nominal_lunas, 0, ',', '.') ?>
                                            </td>
                                            <td style="min-width:130px;">
                                                <div style="display:flex;align-items:center;gap:8px;">
                                                    <div class="progress flex-grow-1 progress-slim">
                                                        <div class="progress-bar" role="progressbar" style="width:<?= $pct ?>%;background-color:<?= $bar_color ?>;"></div>
                                                    </div>
                                                    <small style="font-size:12px;font-weight:700;color:<?= $bar_color ?>;min-width:32px;"><?= $pct ?>%</small>
                                                </div>
                                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                                                    <?= $mhs->tagihan_lunas ?> lunas &bull; <?= $mhs->tagihan_pending ?> pending &bull; <?= $mhs->tagihan_belum ?> belum
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($mhs->tagihan_belum == 0 && $mhs->tagihan_pending == 0): ?>
                                                    <span class="badge-st badge-lunas-st"><i class="fa fa-check-circle"></i>LUNAS</span>
                                                <?php elseif ($mhs->tagihan_pending > 0): ?>
                                                    <span class="badge-st badge-pending-st"><i class="fa fa-clock-o"></i>PENDING</span>
                                                <?php else: ?>
                                                    <span class="badge-st badge-belum-st"><i class="fa fa-circle-o"></i>BELUM</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('keuangan?mahasiswa_id=' . $mhs->id) ?>" target="_blank"
                                                   class="btn btn-sm" style="background:#eff6ff;color:#1d4ed8;border-radius:8px;font-size:12px;font-weight:600;">
                                                    <i class="fa fa-eye mr-1"></i>Detail
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

<!-- Modal Setujui Verifikasi -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,#10b981,#047857);color:#fff;border-radius:16px 16px 0 0;padding:20px 24px;">
                <h5 class="modal-title" style="font-weight:700;font-size:16px;">
                    <i class="fa fa-check-circle mr-2"></i>Konfirmasi Persetujuan Pembayaran
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:0.9;"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:26px;">
                <div style="text-align:center;margin-bottom:18px;">
                    <div style="width:60px;height:60px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="fa fa-check" style="font-size:28px;color:#047857;"></i>
                    </div>
                    <p style="font-size:14.5px;color:#1e293b;">Setujui pembayaran ini menjadi <strong class="text-success">LUNAS</strong>?</p>
                </div>
                <div style="background:#f0fdf4;border-radius:12px;padding:14px 18px;border:1.5px solid #6ee7b7;">
                    <div class="detail-row"><span class="detail-label">Mahasiswa</span><span class="detail-value" id="verif-nama"></span></div>
                    <div class="detail-row"><span class="detail-label">Tagihan</span><span class="detail-value" id="verif-jenis"></span></div>
                    <div class="detail-row"><span class="detail-label">Nominal</span><span class="detail-value" id="verif-nominal" style="font-weight:800;color:#047857;font-size:16px;"></span></div>
                </div>
                <p style="margin-top:14px;font-size:12.5px;color:#64748b;text-align:center;">
                    <i class="fa fa-bolt text-warning mr-1"></i> Setelah disetujui, desktop mahasiswa akan ter-update secara <strong>real-time</strong> dan akses KRS akan langsung terbuka.
                </p>
            </div>
            <div class="modal-footer" style="padding:12px 24px 20px;border:none;">
                <button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f1f5f9;color:#475569;border-radius:8px;font-weight:600;padding:8px 18px;">Batal</button>
                <form id="form-verifikasi" method="POST" action="<?= base_url('keuangan/verifikasi_pembayaran') ?>" style="display:inline;">
                    <?= form_hidden('pembayaran_id', '', 'id="input-verif-id"') ?>
                    <button type="submit" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#10b981,#047857);color:#fff;border-radius:8px;font-weight:700;padding:9px 20px;">
                        <i class="fa fa-check mr-1"></i>Ya, Setujui LUNAS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,#ef4444,#b91c1c);color:#fff;border-radius:16px 16px 0 0;padding:20px 24px;">
                <h5 class="modal-title" style="font-weight:700;font-size:16px;">
                    <i class="fa fa-times-circle mr-2"></i>Tolak Pembayaran Mahasiswa
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:0.9;"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px 28px;">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="width:56px;height:56px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="fa fa-times" style="font-size:26px;color:#dc2626;"></i>
                    </div>
                    <p style="font-size:14px;color:#475569;margin-bottom:2px;">Tolak pembayaran dari:</p>
                    <p style="font-size:15px;font-weight:700;color:#1e293b;" id="tolak-info"></p>
                </div>
                <div style="background:#fef2f2;border-radius:12px;padding:14px 18px;border:1.5px solid #fca5a5;margin-bottom:14px;">
                    <label style="font-size:12.5px;font-weight:700;color:#991b1b;margin-bottom:6px;display:block;">
                        <i class="fa fa-exclamation-triangle mr-1"></i>Alasan Penolakan (wajib diisi)
                    </label>
                    <textarea id="input-alasan" rows="3"
                              placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / nama pengirim berbeda..."
                              style="width:100%;border:1.5px solid #fca5a5;border-radius:8px;padding:10px 12px;font-size:13px;resize:none;color:#1e293b;background:#fff;"></textarea>
                </div>
                <p style="font-size:12px;color:#64748b;text-align:center;">
                    Mahasiswa akan menerima notifikasi real-time dan diminta mengunggah ulang bukti pembayaran.
                </p>
            </div>
            <div class="modal-footer" style="padding:8px 24px 20px;border:none;">
                <button type="button" class="btn btn-sm" data-dismiss="modal"
                        style="background:#f1f5f9;color:#475569;border-radius:8px;font-weight:600;padding:8px 18px;">Batal</button>
                <form id="form-tolak" method="POST" action="<?= base_url('keuangan/tolak_pembayaran') ?>" style="display:inline;">
                    <?= form_hidden('pembayaran_id', '', 'id="input-tolak-id"') ?>
                    <input type="hidden" id="input-alasan-hidden" name="alasan_penolakan" value="">
                    <button type="button" id="btn-submit-tolak" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#ef4444,#b91c1c);color:#fff;border-radius:8px;font-weight:700;padding:9px 20px;">
                        <i class="fa fa-times mr-1"></i>Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function konfirmasiVerifikasi(pembayaranId, namaMhs, jenisTghn, nominal) {
    document.getElementById('input-verif-id').value = pembayaranId;
    document.getElementById('verif-nama').textContent   = namaMhs;
    document.getElementById('verif-jenis').textContent  = jenisTghn;
    document.getElementById('verif-nominal').textContent = 'Rp ' + parseInt(nominal).toLocaleString('id-ID');
    $('#modalVerifikasi').modal('show');
}

function bukaModalTolak(pembayaranId, namaMhs, jenisTghn) {
    document.getElementById('input-tolak-id').value = pembayaranId;
    document.getElementById('tolak-info').textContent = namaMhs + ' \u2014 ' + jenisTghn;
    document.getElementById('input-alasan').value = '';
    $('#modalTolak').modal('show');
}

document.getElementById('btn-submit-tolak').addEventListener('click', function() {
    var alasan = document.getElementById('input-alasan').value.trim();
    if (!alasan) {
        alert('Alasan penolakan wajib diisi.');
        document.getElementById('input-alasan').focus();
        return;
    }
    document.getElementById('input-alasan-hidden').value = alasan;
    document.getElementById('form-tolak').submit();
});

// Live Search Mahasiswa di Tabel Admin
document.getElementById('liveSearchInput').addEventListener('keyup', function() {
    var keyword = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.searchable-row');
    rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        if (keyword === '' || text.indexOf(keyword) > -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
