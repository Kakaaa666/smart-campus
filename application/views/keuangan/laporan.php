<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <style>
                .laporan-card {
                    background: #ffffff; border-radius: 16px;
                    border: 1px solid #e8edf3;
                    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
                    overflow: hidden; margin-bottom: 24px;
                }
                .laporan-card-header {
                    padding: 18px 24px; border-bottom: 1px solid #f1f5f9;
                    display: flex; align-items: center; justify-content: space-between;
                }
                .laporan-card-header h5 { margin:0; font-size:16px; font-weight:700; color:#1e293b; }
                .table-laporan thead th {
                    background: #f8fafc; color: #475569; font-weight: 700;
                    font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.6px;
                    border-top: none; border-bottom: 1.5px solid #e2e8f0; padding: 14px 16px; white-space: nowrap;
                }
                .table-laporan tbody td {
                    padding: 13px 16px; vertical-align: middle;
                    color: #334155; font-size: 13.5px; border-top: 1px solid #f1f5f9;
                }
                .summary-box {
                    border-radius: 14px; padding: 20px 22px;
                    text-align: center;
                }
                .badge-st {
                    padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
                    display: inline-flex; align-items: center; gap: 5px;
                }
                .badge-lunas-st  { background:#d1fae5; color:#047857; border:1px solid #6ee7b7; }
                .badge-pending-st{ background:#fef3c7; color:#b45309; border:1px solid #fcd34d; }
                .badge-ditolak-st{ background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; }
                .badge-belum-st  { background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; }
                .badge-fakultas {
                    background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;
                    padding: 2px 7px; border-radius: 5px; font-size: 11px; font-weight: 700;
                }
                .badge-prodi {
                    background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;
                    padding: 2px 7px; border-radius: 5px; font-size: 11px; font-weight: 600;
                }
                @media print {
                    .no-print { display: none !important; }
                    .laporan-card { box-shadow: none !important; border: 1px solid #ddd !important; }
                    body { background: white !important; }
                }
                </style>

                <!-- Header Laporan Eksekutif -->
                <div class="laporan-card no-print" style="border-left:4px solid #6366f1;">
                    <div style="padding:20px 28px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div style="display:flex;align-items:center;gap:14px;">
                                    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#4338ca);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa fa-bar-chart" style="font-size:22px;color:#fff;"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin:0 0 4px;font-size:20px;font-weight:800;color:#1e293b;">
                                            Laporan Keuangan Mahasiswa ke Rektorat
                                        </h4>
                                        <p style="margin:0;color:#64748b;font-size:13px;">
                                            Monitoring eksekutif penerimaan SPP/UKT, praktikum, dan biaya studi kampus
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                                <a href="<?= base_url('keuangan/admin') ?>" class="btn btn-sm mr-2"
                                   style="background:#f1f5f9;color:#475569;border-radius:8px;font-weight:600;padding:9px 16px;">
                                    <i class="fa fa-arrow-left mr-1"></i>Kembali
                                </a>
                                <a href="<?= base_url('keuangan/export_word') . '?' . http_build_query($_GET) ?>" class="btn btn-sm mr-2"
                                   style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border-radius:8px;font-size:13px;font-weight:700;padding:9px 18px;box-shadow:0 4px 12px rgba(37,99,235,0.25);">
                                    <i class="fa fa-file-word-o mr-1"></i>Ekspor Word (.doc)
                                </a>
                                <button onclick="window.print()" class="btn btn-sm"
                                        style="background:linear-gradient(135deg,#6366f1,#4338ca);color:#fff;border-radius:8px;font-size:13px;font-weight:700;padding:9px 18px;">
                                    <i class="fa fa-print mr-1"></i>Cetak Rinci
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kop Surat Resmi (Hanya Muncul Saat Dicetak / Mode Print) -->
                <div class="d-none d-print-block mb-4" style="text-align:center; border-bottom:3px double #0f172a; padding-bottom:12px;">
                    <h3 style="margin:0; font-size:20px; font-weight:800; color:#1e3a8a;">UNIVERSITAS SMART CAMPUS</h3>
                    <h5 style="margin:4px 0; font-size:14px; font-weight:700; color:#334155;">BIRO ADMINISTRASI KEUANGAN DAN AKADEMIK</h5>
                    <p style="margin:0; font-size:11px; color:#64748b;">Jl. Kampus Terpadu No. 123 | Telp: (021) 789-0123 | Email: keuangan@smartcampus.ac.id</p>
                    <div style="margin-top:12px; font-size:14px; font-weight:800; text-decoration:underline;">LAPORAN EKSEKUTIF PENERIMAAN KEUANGAN KAMPUS</div>
                    <div style="font-size:11.5px; color:#475569;">Periode: Tahun Akademik <?= htmlspecialchars($tahun_akademik) ?> &bull; Semester <?= htmlspecialchars($semester) ?></div>
                </div>

                <!-- Filter Periode, Fakultas & Prodi -->
                <div class="laporan-card no-print">
                    <div class="laporan-card-header">
                        <h5><i class="fa fa-filter mr-2" style="color:#6366f1;"></i>Filter Periode &amp; Jurusan</h5>
                    </div>
                    <div style="padding:20px 24px;">
                        <form method="GET" action="<?= base_url('keuangan/laporan') ?>">
                            <div class="row">
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;margin-bottom:6px;display:block;">Tahun Akademik</label>
                                    <select name="tahun_akademik" class="form-control" style="border-radius:8px;font-size:13.5px;border:1.5px solid #e2e8f0; height: 40px;">
                                        <?php foreach ($daftar_tahun as $t): ?>
                                            <option value="<?= $t->tahun_akademik ?>" <?= ($t->tahun_akademik == $tahun_akademik) ? 'selected' : '' ?>>
                                                <?= $t->tahun_akademik ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <?php if (empty($daftar_tahun)): ?>
                                            <option value="2026/2027">2026/2027</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;margin-bottom:6px;display:block;">Semester</label>
                                    <select name="semester" class="form-control" style="border-radius:8px;font-size:13.5px;border:1.5px solid #e2e8f0; height: 40px;">
                                        <option value="Ganjil" <?= $semester == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                                        <option value="Genap"  <?= $semester == 'Genap' ? 'selected' : '' ?>>Genap</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;margin-bottom:6px;display:block;">Fakultas</label>
                                    <select name="fakultas" class="form-control" style="border-radius:8px;font-size:13.5px;border:1.5px solid #e2e8f0; height: 40px;">
                                        <option value="">-- Semua Fakultas --</option>
                                        <?php foreach ($daftar_fakultas as $fak): ?>
                                            <option value="<?= htmlspecialchars($fak) ?>" <?= ($filter_fakultas === $fak) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($fak) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;margin-bottom:6px;display:block;">Program Studi</label>
                                    <select name="prodi" class="form-control" style="border-radius:8px;font-size:13.5px;border:1.5px solid #e2e8f0; height: 40px;">
                                        <option value="">-- Semua Prodi --</option>
                                        <?php foreach ($daftar_prodi as $prd): ?>
                                            <option value="<?= htmlspecialchars($prd) ?>" <?= ($filter_prodi === $prd) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prd) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn"
                                            style="background:linear-gradient(135deg,#1565c0,#1976d2);color:#fff;border-radius:8px;font-weight:600;padding:8px 16px;font-size:13.5px;width:100%; height: 40px;">
                                        <i class="fa fa-filter mr-1"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Header Laporan (versi print) -->
                <div style="text-align:center;margin-bottom:20px;display:none;" class="print-only-header">
                    <h3 style="font-weight:800;color:#1e293b;margin-bottom:4px;">LAPORAN KEUANGAN MAHASISWA &bull; REKTORAT</h3>
                    <p style="color:#475569;margin-bottom:4px;">Smart Campus &bull; Tahun Akademik <?= $tahun_akademik ?> Semester <?= $semester ?></p>
                    <?php if (!empty($filter_fakultas) || !empty($filter_prodi)): ?>
                        <p style="color:#64748b;font-size:13px;margin-bottom:4px;">
                            <?= !empty($filter_fakultas) ? 'Fakultas: ' . htmlspecialchars($filter_fakultas) : '' ?>
                            <?= (!empty($filter_fakultas) && !empty($filter_prodi)) ? ' &bull; ' : '' ?>
                            <?= !empty($filter_prodi) ? 'Program Studi: ' . htmlspecialchars($filter_prodi) : '' ?>
                        </p>
                    <?php endif; ?>
                    <p style="color:#94a3b8;font-size:12px;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB</p>
                    <hr>
                </div>
                <style>@media print { .print-only-header { display:block !important; } }</style>

                <!-- Rekap per Jenis Tagihan -->
                <div class="laporan-card">
                    <div class="laporan-card-header">
                        <div>
                            <h5><i class="fa fa-table mr-2" style="color:#1976d2;"></i>Rekapitulasi Penerimaan Keuangan</h5>
                            <div style="font-size:13px;color:#64748b;margin-top:3px;">
                                Periode: <strong><?= $tahun_akademik ?> &mdash; Semester <?= $semester ?></strong>
                                <?php if (!empty($filter_fakultas)): ?> &bull; <?= htmlspecialchars($filter_fakultas) ?><?php endif; ?>
                                <?php if (!empty($filter_prodi)): ?> &bull; <?= htmlspecialchars($filter_prodi) ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (empty($laporan_rekap)): ?>
                        <div style="text-align:center;padding:40px 24px;color:#94a3b8;">
                            <i class="fa fa-inbox" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                            <p style="font-size:15px;font-weight:600;color:#475569;">Tidak ada data untuk kriteria filter ini</p>
                        </div>
                    <?php else: ?>
                        <?php
                            $grand_total   = 0;
                            $grand_lunas   = 0;
                            $grand_pending = 0;
                            $grand_belum   = 0;
                            foreach ($laporan_rekap as $r) {
                                $grand_total   += $r->total_nominal;
                                $grand_lunas   += $r->nominal_lunas;
                                $grand_pending += $r->nominal_pending;
                                $grand_belum   += $r->nominal_belum;
                            }
                        ?>
                        <!-- Summary Boxes -->
                        <div class="row" style="padding:20px 16px 8px;">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="summary-box" style="background:#f0f7ff;border:1.5px solid #bfdbfe;">
                                    <div style="font-size:11.5px;color:#3b82f6;font-weight:700;text-transform:uppercase;">Total Tagihan</div>
                                    <div style="font-size:22px;font-weight:800;color:#1e293b;margin:8px 0 0;">Rp <?= number_format($grand_total, 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="summary-box" style="background:#f0fdf4;border:1.5px solid #6ee7b7;">
                                    <div style="font-size:11.5px;color:#10b981;font-weight:700;text-transform:uppercase;">Terlunasi (LUNAS)</div>
                                    <div style="font-size:22px;font-weight:800;color:#047857;margin:8px 0 0;">Rp <?= number_format($grand_lunas, 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="summary-box" style="background:#fffbeb;border:1.5px solid #fcd34d;">
                                    <div style="font-size:11.5px;color:#f59e0b;font-weight:700;text-transform:uppercase;">Dalam Verifikasi</div>
                                    <div style="font-size:22px;font-weight:800;color:#b45309;margin:8px 0 0;">Rp <?= number_format($grand_pending, 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="summary-box" style="background:#fef2f2;border:1.5px solid #fca5a5;">
                                    <div style="font-size:11.5px;color:#ef4444;font-weight:700;text-transform:uppercase;">Belum Terbayar</div>
                                    <div style="font-size:22px;font-weight:800;color:#dc2626;margin:8px 0 0;">Rp <?= number_format($grand_belum, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-laporan mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Tagihan</th>
                                        <th class="text-center">Jumlah Mahasiswa</th>
                                        <th>Total Kewajiban</th>
                                        <th>Lunas</th>
                                        <th>Pending</th>
                                        <th>Belum Bayar</th>
                                        <th>% Lunas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($laporan_rekap as $i => $r): ?>
                                        <?php
                                            $pct_lunas = $r->jumlah_tagihan > 0 ? round(($r->jumlah_lunas / $r->jumlah_tagihan) * 100) : 0;
                                            $bar_color = $pct_lunas >= 100 ? '#10b981' : ($pct_lunas >= 50 ? '#f59e0b' : '#ef4444');
                                        ?>
                                        <tr>
                                            <td style="font-weight:600;color:#94a3b8;"><?= $i + 1 ?></td>
                                            <td style="font-weight:700;font-size:14px;color:#1e293b;"><?= htmlspecialchars($r->jenis_tagihan) ?></td>
                                            <td style="text-align:center;">
                                                <span style="background:#f1f5f9;padding:3px 12px;border-radius:20px;font-weight:700;font-size:13px;">
                                                    <?= $r->jumlah_tagihan ?>
                                                </span>
                                            </td>
                                            <td style="font-weight:700;color:#1e293b;">Rp <?= number_format($r->total_nominal, 0, ',', '.') ?></td>
                                            <td style="color:#047857;font-weight:600;">
                                                Rp <?= number_format($r->nominal_lunas, 0, ',', '.') ?>
                                                <div style="font-size:11px;color:#94a3b8;"><?= $r->jumlah_lunas ?> mhs</div>
                                            </td>
                                            <td style="color:#b45309;font-weight:600;">
                                                Rp <?= number_format($r->nominal_pending, 0, ',', '.') ?>
                                                <div style="font-size:11px;color:#94a3b8;"><?= $r->jumlah_pending ?> mhs</div>
                                            </td>
                                            <td style="color:#dc2626;font-weight:600;">
                                                Rp <?= number_format($r->nominal_belum, 0, ',', '.') ?>
                                                <div style="font-size:11px;color:#94a3b8;"><?= $r->jumlah_belum ?> mhs</div>
                                            </td>
                                            <td style="min-width:100px;">
                                                <div style="display:flex;align-items:center;gap:8px;">
                                                    <div class="progress flex-grow-1" style="height:6px;border-radius:3px;">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width:<?= $pct_lunas ?>%;background-color:<?= $bar_color ?>;border-radius:3px;"></div>
                                                    </div>
                                                    <small style="font-size:12px;font-weight:700;color:<?= $bar_color ?>;min-width:32px;"><?= $pct_lunas ?>%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr style="background:#f0f7ff;">
                                        <td colspan="3" style="font-weight:800;color:#1565c0;font-size:14px;text-align:right;">TOTAL KESELURUHAN:</td>
                                        <td style="font-weight:800;color:#1565c0;">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                                        <td style="font-weight:800;color:#047857;">Rp <?= number_format($grand_lunas, 0, ',', '.') ?></td>
                                        <td style="font-weight:800;color:#b45309;">Rp <?= number_format($grand_pending, 0, ',', '.') ?></td>
                                        <td style="font-weight:800;color:#dc2626;">Rp <?= number_format($grand_belum, 0, ',', '.') ?></td>
                                        <td>
                                            <?php $total_pct = $grand_total > 0 ? round(($grand_lunas/$grand_total)*100) : 0; ?>
                                            <strong style="color:#1565c0;"><?= $total_pct ?>%</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Rincian Seluruh Transaksi Pembayaran -->
                <?php if (!empty($semua_pembayaran)): ?>
                <div class="laporan-card">
                    <div class="laporan-card-header">
                        <div>
                            <h5><i class="fa fa-list mr-2" style="color:#6366f1;"></i>Rincian Transaksi Pembayaran</h5>
                            <div style="font-size:13px;color:#64748b;margin-top:3px;">
                                Periode: <strong><?= $tahun_akademik ?> &mdash; Semester <?= $semester ?></strong>
                                &bull; <?= count($semua_pembayaran) ?> transaksi
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-laporan mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa &amp; NIM</th>
                                    <th>Fakultas / Prodi</th>
                                    <th>Jenis Tagihan</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Rekening Pengirim</th>
                                    <th>Tgl Bayar</th>
                                    <th>Status</th>
                                    <th>Verifikator</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semua_pembayaran as $i => $p): ?>
                                    <tr>
                                        <td style="font-weight:600;color:#94a3b8;"><?= $i + 1 ?></td>
                                        <td>
                                            <div style="font-weight:700;color:#1e293b;"><?= htmlspecialchars($p->nama_mahasiswa) ?></div>
                                            <small class="text-muted" style="font-family:monospace;"><?= htmlspecialchars($p->nim_mahasiswa) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge-fakultas"><?= htmlspecialchars($p->fakultas_mahasiswa ?: 'Fakultas Ilmu Komputer') ?></span>
                                            <div class="mt-1"><span class="badge-prodi"><?= htmlspecialchars($p->prodi_mahasiswa ?: 'D3 Sistem Informasi') ?></span></div>
                                        </td>
                                        <td style="font-size:13px;"><?= htmlspecialchars($p->jenis_tagihan) ?></td>
                                        <td style="font-weight:700;color:#1e293b;">Rp <?= number_format($p->nominal_pembayaran, 0, ',', '.') ?></td>
                                        <td style="font-size:13px;"><?= htmlspecialchars($p->metode_pembayaran) ?></td>
                                        <td style="font-size:12px;background:#f8fafc;">
                                            <div style="font-family:monospace;">No: <?= htmlspecialchars($p->nomor_rekening ?: $p->nomor_referensi ?: '-') ?></div>
                                            <small class="text-muted">Nama: <?= htmlspecialchars($p->nama_rekening ?: '-') ?></small>
                                        </td>
                                        <td style="font-size:13px;white-space:nowrap;"><?= date('d M Y', strtotime($p->tanggal_pembayaran)) ?></td>
                                        <td>
                                            <?php if ($p->status === 'LUNAS'): ?>
                                                <span class="badge-st badge-lunas-st"><i class="fa fa-check-circle"></i>LUNAS</span>
                                            <?php elseif ($p->status === 'PENDING'): ?>
                                                <span class="badge-st badge-pending-st"><i class="fa fa-clock-o"></i>PENDING</span>
                                            <?php else: ?>
                                                <span class="badge-st badge-ditolak-st"><i class="fa fa-times-circle"></i>DITOLAK</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="font-size:13px;">
                                            <?= $p->nama_verifikator
                                                ? htmlspecialchars($p->nama_verifikator)
                                                : '<span style="color:#94a3b8;">—</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
