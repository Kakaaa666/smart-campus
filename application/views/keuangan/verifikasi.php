<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Custom Styling Khusus Verifikasi Pembayaran -->
                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

                .verif-container {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .verif-card {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                    margin-bottom: 24px;
                    overflow: hidden;
                }

                .verif-card-header {
                    padding: 20px 24px;
                    border-bottom: 1px solid #f1f5f9;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 12px;
                }

                .table-verif thead th {
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

                .table-verif tbody td {
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

                .btn-approve-action {
                    background: linear-gradient(135deg, #10b981, #059669);
                    color: #ffffff;
                    border: none;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 700;
                    padding: 8px 14px;
                    transition: all 0.2s ease;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .btn-approve-action:hover {
                    background: linear-gradient(135deg, #059669, #047857);
                    color: #fff;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                }

                .btn-reject-action {
                    background: #fff;
                    color: #dc2626;
                    border: 1.5px solid #fca5a5;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 600;
                    padding: 7px 12px;
                    transition: all 0.2s ease;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                }

                .btn-reject-action:hover {
                    background: #fee2e2;
                    color: #b91c1c;
                }

                .nav-verif-tabs {
                    border-bottom: 2px solid #e2e8f0;
                    padding: 0 24px;
                    background: #ffffff;
                }

                .nav-verif-tabs .nav-link {
                    border: none;
                    color: #64748b;
                    font-weight: 600;
                    font-size: 14px;
                    padding: 16px 20px;
                    margin-bottom: -2px;
                    border-bottom: 3px solid transparent;
                    transition: all 0.2s ease;
                }

                .nav-verif-tabs .nav-link.active {
                    color: #0284c7;
                    font-weight: 700;
                    background: transparent;
                    border-bottom: 3px solid #0284c7;
                }
                </style>

                <div class="verif-container">

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
                    <div class="verif-card" style="border-left: 5px solid #0284c7;">
                        <div style="padding: 22px 26px;">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg,#0284c7,#0369a1); display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                                            <i class="fa fa-check-square-o" style="font-size: 24px; color: #ffffff;"></i>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0 0 4px; font-weight: 800; color: #0f172a; font-size: 20px;">
                                                Verifikasi Pembayaran Mahasiswa
                                            </h4>
                                            <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                                                Periksa bukti transfer perbankan, validasi nomor referensi, dan setujui status tagihan menjadi <strong>LUNAS</strong>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                    <span class="badge" style="background: #fef3c7; color: #b45309; font-size: 13px; padding: 8px 16px; border-radius: 20px; font-weight: 700; border: 1px solid #fde68a;">
                                        <i class="fa fa-clock-o mr-1"></i> <span id="badge-count-pending"><?= count($pembayaran_pending) ?></span> Menunggu Tindakan
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter & Live Search Bar -->
                    <div class="verif-card">
                        <div style="padding: 16px 24px;">
                            <form method="GET" action="<?= base_url('keuangan/verifikasi') ?>">
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
                                        <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Pencarian Instan</label>
                                        <div class="input-group">
                                            <input type="text" id="liveSearchVerif" class="form-control form-control-sm" placeholder="Ketik nama, NIM, atau jenis tagihan..." style="height: 38px; border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="background:#f8fafc; border-radius: 0 8px 8px 0;"><i class="fa fa-search text-muted"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Main Tab Cards -->
                    <div class="verif-card">
                        <ul class="nav nav-tabs nav-verif-tabs" id="verifTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-antrian-nav" data-toggle="tab" href="#tab-antrian" role="tab">
                                    <i class="fa fa-hourglass-half mr-1 text-warning"></i> Antrian Menunggu Verifikasi
                                    <span class="badge badge-warning text-dark ml-1" id="tab-badge-pending"><?= count($pembayaran_pending) ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-riwayat-nav" data-toggle="tab" href="#tab-riwayat" role="tab">
                                    <i class="fa fa-history mr-1 text-success"></i> Riwayat Selesai Diverifikasi
                                    <span class="badge badge-light ml-1" style="border:1px solid #cbd5e1;"><?= count($pembayaran_selesai) ?></span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <!-- TAB 1: Antrian PENDING -->
                            <div class="tab-pane fade show active" id="tab-antrian" role="tabpanel">
                                <div class="table-responsive">
                                    <?php if (empty($pembayaran_pending)): ?>
                                        <div style="text-align:center; padding: 56px 24px; color: #94a3b8;" id="empty-state-pending">
                                            <div style="width: 70px; height: 70px; border-radius: 50%; background: #ecfdf5; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                                <i class="fa fa-check-circle" style="font-size: 36px; color: #10b981;"></i>
                                            </div>
                                            <h5 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Tidak Ada Antrian Pembayaran Menunggu</h5>
                                            <p style="font-size: 13.5px; color: #64748b; margin: 0;">Semua konfirmasi transfer dari mahasiswa telah selesai diproses oleh tim admin.</p>
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-verif mb-0" id="tablePending">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">No</th>
                                                    <th>Mahasiswa</th>
                                                    <th>Fakultas &amp; Program Studi</th>
                                                    <th>Kewajiban Tagihan</th>
                                                    <th>Nominal</th>
                                                    <th>Metode &amp; Ref</th>
                                                    <th>Bukti Transfer</th>
                                                    <th class="text-center" style="width: 190px;">Aksi Verifikasi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-pending">
                                                <?php foreach ($pembayaran_pending as $i => $p): ?>
                                                    <tr class="searchable-row" id="row-pay-<?= $p->id ?>" data-search="<?= strtolower($p->nama_mahasiswa . ' ' . $p->nim_mahasiswa . ' ' . ($p->fakultas_mahasiswa ?? '') . ' ' . ($p->prodi_mahasiswa ?? '') . ' ' . $p->jenis_tagihan) ?>">
                                                        <td class="text-center font-weight-bold text-muted"><?= $i + 1 ?></td>
                                                        <td>
                                                            <div style="font-weight: 700; font-size: 14px; color: #0f172a;"><?= htmlspecialchars($p->nama_mahasiswa) ?></div>
                                                            <div style="font-size: 12px; color: #64748b; font-family: monospace;">NIM: <?= htmlspecialchars($p->nim_mahasiswa) ?></div>
                                                        </td>
                                                        <td>
                                                            <div class="badge-fakultas mb-1"><?= htmlspecialchars($p->fakultas_mahasiswa ?: 'Fakultas Ilmu Komputer') ?></div>
                                                            <div>
                                                                <span class="badge-prodi"><?= htmlspecialchars($p->prodi_mahasiswa ?: 'D3 Sistem Informasi') ?></span>
                                                                <small class="text-muted ml-1">Smstr <?= htmlspecialchars($p->semester_mahasiswa ?: '5') ?></small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="font-weight: 700; font-size: 13.5px; color: #1e293b;"><?= htmlspecialchars($p->jenis_tagihan) ?></div>
                                                            <div style="font-size: 12px; color: #64748b;"><?= $p->tahun_akademik ?> &bull; <?= $p->semester ?></div>
                                                        </td>
                                                        <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                                                            Rp <?= number_format($p->nominal_pembayaran, 0, ',', '.') ?>
                                                        </td>
                                                        <td style="font-size: 12.5px;">
                                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars($p->metode_pembayaran) ?></div>
                                                            <small class="text-muted" style="font-family: monospace;"><?= $p->nomor_referensi ?: '-' ?></small>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url('keuangan/lihat_bukti/' . $p->id) ?>" target="_blank"
                                                               class="btn btn-sm" style="background:#eff6ff; color:#1d4ed8; border-radius:8px; font-size:12px; font-weight:600; padding:6px 12px; border:1px solid #bfdbfe;">
                                                                <i class="fa fa-eye mr-1"></i>Lihat Bukti
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <div style="display:flex; gap:6px; justify-content:center;">
                                                                <!-- Tombol Setujui yang langsung jalan -->
                                                                <button type="button" class="btn-approve-action"
                                                                        onclick="konfirmasiVerifikasi(<?= $p->id ?>, '<?= addslashes($p->nama_mahasiswa) ?>', '<?= addslashes($p->jenis_tagihan) ?>', <?= $p->nominal_pembayaran ?>)">
                                                                    <i class="fa fa-check"></i>Setujui
                                                                </button>
                                                                <!-- Tombol Tolak -->
                                                                <button type="button" class="btn-reject-action"
                                                                        onclick="bukaModalTolak(<?= $p->id ?>, '<?= addslashes($p->nama_mahasiswa) ?>', '<?= addslashes($p->jenis_tagihan) ?>')">
                                                                    <i class="fa fa-times"></i>Tolak
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

                            <!-- TAB 2: Riwayat Selesai Diverifikasi -->
                            <div class="tab-pane fade" id="tab-riwayat" role="tabpanel">
                                <div class="table-responsive">
                                    <?php if (empty($pembayaran_selesai)): ?>
                                        <div style="text-align:center; padding: 48px 24px; color: #94a3b8;">
                                            <p style="font-size: 14px; margin: 0;">Belum ada riwayat verifikasi pada filter ini.</p>
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-verif mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">No</th>
                                                    <th>Waktu Verifikasi</th>
                                                    <th>Mahasiswa</th>
                                                    <th>Program Studi</th>
                                                    <th>Kewajiban Tagihan</th>
                                                    <th>Nominal</th>
                                                    <th>Status</th>
                                                    <th>Verifikator</th>
                                                    <th>Bukti</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pembayaran_selesai as $idx => $ps): ?>
                                                    <tr>
                                                        <td class="text-center text-muted"><?= $idx + 1 ?></td>
                                                        <td style="font-size: 12.5px;">
                                                            <?= $ps->diverifikasi_at ? date('d M Y, H:i', strtotime($ps->diverifikasi_at)) : '-' ?>
                                                        </td>
                                                        <td>
                                                            <div style="font-weight:700; color:#1e293b;"><?= htmlspecialchars($ps->nama_mahasiswa) ?></div>
                                                            <small class="text-muted" style="font-family: monospace;">NIM: <?= htmlspecialchars($ps->nim_mahasiswa) ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="badge-prodi"><?= htmlspecialchars($ps->prodi_mahasiswa ?: '-') ?></span>
                                                        </td>
                                                        <td>
                                                            <div style="font-weight:600;"><?= htmlspecialchars($ps->jenis_tagihan) ?></div>
                                                            <small class="text-muted"><?= $ps->tahun_akademik ?></small>
                                                        </td>
                                                        <td style="font-weight:700; color:#1e293b;">
                                                            Rp <?= number_format($ps->nominal_pembayaran, 0, ',', '.') ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($ps->status === 'LUNAS'): ?>
                                                                <span class="badge badge-success" style="padding:5px 10px; border-radius:12px; font-weight:700;">
                                                                    <i class="fa fa-check mr-1"></i>LUNAS
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger" style="padding:5px 10px; border-radius:12px; font-weight:700;" title="<?= htmlspecialchars($ps->alasan_penolakan) ?>">
                                                                    <i class="fa fa-times mr-1"></i>DITOLAK
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($ps->nama_verifikator ?: 'Admin Keuangan') ?></td>
                                                        <td>
                                                            <a href="<?= base_url('keuangan/lihat_bukti/' . $ps->id) ?>" target="_blank"
                                                               class="btn btn-sm btn-light" style="border:1px solid #cbd5e1; border-radius:6px; font-size:11.5px;">
                                                                <i class="fa fa-file-image-o"></i>
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
    </div>
</div>

<!-- =======================================================
     MODAL SETUJUI VERIFIKASI PEMBAYARAN (FIXED INPUT ID)
======================================================= -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; padding:18px 24px; border:none;">
                <h5 class="modal-title" style="font-weight:700; font-size:16px;">
                    <i class="fa fa-check-circle mr-2"></i>Konfirmasi Persetujuan Pembayaran
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:0.9;"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px 28px;">
                <div style="text-align:center; margin-bottom:18px;">
                    <div style="width:64px; height:64px; border-radius:50%; background:#d1fae5; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                        <i class="fa fa-check" style="font-size:30px; color:#059669;"></i>
                    </div>
                    <h5 style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:4px;">Setujui Pembayaran Menjadi LUNAS?</h5>
                    <p style="font-size:13px; color:#64748b; margin:0;">Status tagihan mahasiswa akan otomatis diperbarui seketika.</p>
                </div>
                <div style="background:#f0fdf4; border-radius:12px; padding:16px 20px; border:1.5px solid #a7f3d0;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:13px;">
                        <span style="color:#64748b;">Nama Mahasiswa:</span>
                        <strong style="color:#0f172a;" id="verif-nama">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:13px;">
                        <span style="color:#64748b;">Kewajiban Tagihan:</span>
                        <strong style="color:#0f172a;" id="verif-jenis">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:14px; padding-top:6px; border-top:1px dashed #a7f3d0;">
                        <span style="color:#059669; font-weight:600;">Nominal Pembayaran:</span>
                        <strong style="color:#047857; font-size:17px; font-weight:800;" id="verif-nominal">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:14px 28px 22px; border:none; background:#fafafa; display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f1f5f9; color:#475569; border-radius:8px; font-weight:600; padding:9px 18px;">
                    Batal
                </button>
                <form id="form-verifikasi" method="POST" action="<?= base_url('keuangan/verifikasi_pembayaran') ?>" style="display:inline;">
                    <!-- INPUT HIDDEN VALID DENGAN ID YANG SESUAI (MENCEGAH ERROR NULL) -->
                    <input type="hidden" name="pembayaran_id" id="input-verif-id" value="">
                    <button type="button" id="btn-submit-approve" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:8px; font-weight:700; padding:9px 22px; border:none; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        <i class="fa fa-check mr-1"></i>Ya, Setujui LUNAS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL TOLAK PEMBAYARAN (FIXED INPUT ID)
======================================================= -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; padding:18px 24px; border:none;">
                <h5 class="modal-title" style="font-weight:700; font-size:16px;">
                    <i class="fa fa-times-circle mr-2"></i>Tolak Konfirmasi Pembayaran
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:0.9;"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px 28px;">
                <div style="text-align:center; margin-bottom:16px;">
                    <div style="width:58px; height:58px; border-radius:50%; background:#fee2e2; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                        <i class="fa fa-times" style="font-size:28px; color:#dc2626;"></i>
                    </div>
                    <p style="font-size:13.5px; color:#64748b; margin-bottom:2px;">Tolak pembayaran dari:</p>
                    <h6 style="font-size:15px; font-weight:700; color:#1e293b;" id="tolak-info"></h6>
                </div>
                <div style="background:#fff1f2; border-radius:12px; padding:16px 18px; border:1.5px solid #fecdd3; margin-bottom:12px;">
                    <label style="font-size:12.5px; font-weight:700; color:#991b1b; margin-bottom:6px; display:block;">
                        <i class="fa fa-exclamation-triangle mr-1"></i>Alasan Penolakan (Wajib Diisi):
                    </label>
                    <textarea id="input-alasan" rows="3"
                              placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / nama pemilik rekening berbeda..."
                              style="width:100%; border:1.5px solid #fca5a5; border-radius:8px; padding:10px 12px; font-size:13px; resize:none; color:#1e293b; background:#fff;"></textarea>
                </div>
                <p style="font-size:12px; color:#64748b; text-align:center; margin:0;">
                    Mahasiswa akan menerima notifikasi penolakan dan diminta mengunggah ulang berkas bukti transfer.
                </p>
            </div>
            <div class="modal-footer" style="padding:14px 28px 22px; border:none; background:#fafafa; display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f1f5f9; color:#475569; border-radius:8px; font-weight:600; padding:9px 18px;">
                    Batal
                </button>
                <form id="form-tolak" method="POST" action="<?= base_url('keuangan/tolak_pembayaran') ?>" style="display:inline;">
                    <!-- INPUT HIDDEN VALID -->
                    <input type="hidden" name="pembayaran_id" id="input-tolak-id" value="">
                    <input type="hidden" name="alasan_penolakan" id="input-alasan-hidden" value="">
                    <button type="button" id="btn-submit-reject" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border-radius:8px; font-weight:700; padding:9px 22px; border:none;">
                        <i class="fa fa-times mr-1"></i>Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     JAVASCRIPT LOGIC DENGAN DUKUNGAN AJAX & FALLBACK LENGKAP
======================================================= -->
<script>
// Fungsi Buka Modal Verifikasi (Pastikan elemen ID selalu ada)
function konfirmasiVerifikasi(pembayaranId, namaMhs, jenisTghn, nominal) {
    var inputId = document.getElementById('input-verif-id');
    if (!inputId) {
        alert('Komponen form verifikasi gagal dimuat.');
        return;
    }
    inputId.value = pembayaranId;
    document.getElementById('verif-nama').textContent   = namaMhs;
    document.getElementById('verif-jenis').textContent  = jenisTghn;
    document.getElementById('verif-nominal').textContent = 'Rp ' + parseInt(nominal).toLocaleString('id-ID');
    
    // Tampilkan modal via bootstrap jQuery
    if (typeof $ !== 'undefined' && $('#modalVerifikasi').modal) {
        $('#modalVerifikasi').modal('show');
    } else {
        // Fallback konfirmasi langsung
        if (confirm('Setujui pembayaran dari ' + namaMhs + ' untuk ' + jenisTghn + ' sebesar Rp ' + parseInt(nominal).toLocaleString('id-ID') + ' menjadi LUNAS?')) {
            eksekusiVerifikasiAjax(pembayaranId);
        }
    }
}

// Fungsi Buka Modal Tolak
function bukaModalTolak(pembayaranId, namaMhs, jenisTghn) {
    var inputId = document.getElementById('input-tolak-id');
    if (!inputId) {
        alert('Komponen form penolakan gagal dimuat.');
        return;
    }
    inputId.value = pembayaranId;
    document.getElementById('tolak-info').textContent = namaMhs + ' — ' + jenisTghn;
    document.getElementById('input-alasan').value = '';
    
    if (typeof $ !== 'undefined' && $('#modalTolak').modal) {
        $('#modalTolak').modal('show');
    } else {
        var alasan = prompt('Masukkan alasan penolakan untuk ' + namaMhs + ':');
        if (alasan && alasan.trim()) {
            eksekusiTolakAjax(pembayaranId, alasan.trim());
        }
    }
}

// Eksekusi Verifikasi via AJAX untuk respon instan tanpa freeze
function eksekusiVerifikasiAjax(pembayaranId) {
    var btnSubmit = document.getElementById('btn-submit-approve');
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Menyetujui...';
    }

    var formData = new FormData();
    formData.append('pembayaran_id', pembayaranId);

    fetch('<?= base_url("keuangan/verifikasi_pembayaran") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            if (typeof $ !== 'undefined') $('#modalVerifikasi').modal('hide');
            // Hapus baris tabel yang disetujui
            var row = document.getElementById('row-pay-' + pembayaranId);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.background = '#d1fae5';
                setTimeout(function() {
                    if (row.parentNode) row.parentNode.removeChild(row);
                    updatePendingCounters(-1);
                }, 300);
            } else {
                window.location.reload();
            }
            alert('Sukses: ' + data.message);
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(function(err) {
        // Fallback form submit biasa jika fetch gagal
        document.getElementById('form-verifikasi').submit();
    })
    .finally(function() {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa fa-check mr-1"></i>Ya, Setujui LUNAS';
        }
    });
}

// Eksekusi Tolak via AJAX
function eksekusiTolakAjax(pembayaranId, alasan) {
    var btnSubmit = document.getElementById('btn-submit-reject');
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Menolak...';
    }

    var formData = new FormData();
    formData.append('pembayaran_id', pembayaranId);
    formData.append('alasan_penolakan', alasan);

    fetch('<?= base_url("keuangan/tolak_pembayaran") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            if (typeof $ !== 'undefined') $('#modalTolak').modal('hide');
            var row = document.getElementById('row-pay-' + pembayaranId);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.background = '#fee2e2';
                setTimeout(function() {
                    if (row.parentNode) row.parentNode.removeChild(row);
                    updatePendingCounters(-1);
                }, 300);
            } else {
                window.location.reload();
            }
            alert('Sukses: ' + data.message);
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(function() {
        document.getElementById('form-tolak').submit();
    })
    .finally(function() {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa fa-times mr-1"></i>Tolak Pembayaran';
        }
    });
}

// Update counter pending di UI
function updatePendingCounters(diff) {
    var badgePending = document.getElementById('badge-count-pending');
    var tabBadge = document.getElementById('tab-badge-pending');
    if (badgePending) {
        var current = parseInt(badgePending.textContent) || 0;
        var newCount = Math.max(0, current + diff);
        badgePending.textContent = newCount;
        if (tabBadge) tabBadge.textContent = newCount;
        if (newCount === 0) {
            var tbody = document.getElementById('tbody-pending');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted"><i class="fa fa-check-circle text-success" style="font-size:36px;"></i><p class="mt-2 font-weight-bold">Semua pembayaran telah selesai diverifikasi.</p></td></tr>';
            }
        }
    }
}

// Event Listener tombol submit di modal
document.addEventListener('DOMContentLoaded', function() {
    var btnApprove = document.getElementById('btn-submit-approve');
    if (btnApprove) {
        btnApprove.addEventListener('click', function() {
            var id = document.getElementById('input-verif-id').value;
            if (id) eksekusiVerifikasiAjax(id);
        });
    }

    var btnReject = document.getElementById('btn-submit-reject');
    if (btnReject) {
        btnReject.addEventListener('click', function() {
            var id = document.getElementById('input-tolak-id').value;
            var alasan = document.getElementById('input-alasan').value.trim();
            if (!alasan) {
                alert('Alasan penolakan wajib diisi.');
                document.getElementById('input-alasan').focus();
                return;
            }
            document.getElementById('input-alasan-hidden').value = alasan;
            eksekusiTolakAjax(id, alasan);
        });
    }

    // Live Search Antrian Verifikasi
    var searchInput = document.getElementById('liveSearchVerif');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var keyword = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('#tablePending .searchable-row');
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
