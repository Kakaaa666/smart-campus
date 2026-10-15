<style>
    .admin-skpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .admin-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }
    .admin-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .admin-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffffff;
    }
    .admin-filter-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .table-rekap-skpi th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 16px;
    }
    .table-rekap-skpi td {
        vertical-align: middle;
        padding: 14px 16px;
        font-size: 13.5px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
    }
    .row-mahasiswa-skpi {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    .row-mahasiswa-skpi:hover {
        background-color: #f0f7ff !important;
    }
    .mhs-avatar-badge {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }
    .badge-count-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
    }
    .badge-count-pill.pill-waiting {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-count-pill.pill-valid {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .badge-count-pill.pill-total {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Flashdata Notifikasi -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle mr-2"></i> <strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-triangle mr-2"></i> <strong>Peringatan!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <!-- Ringkasan Statistik SKPI -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="admin-stat-card border-info">
                                <div>
                                    <span class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">MAHASISWA MENGAJUKAN</span>
                                    <h3 class="font-weight-bold text-info mb-0"><?= count($mahasiswa_list) ?></h3>
                                    <small class="text-muted">Total mahasiswa terdaftar</small>
                                </div>
                                <div class="admin-stat-icon bg-info">
                                    <i class="fa fa-users text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="admin-stat-card border-warning">
                                <div>
                                    <span class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">BELUM TERVALIDASI</span>
                                    <h3 class="font-weight-bold text-warning mb-0"><?= $summary['waiting'] ?></h3>
                                    <small class="text-muted">Perlu diperiksa akademik</small>
                                </div>
                                <div class="admin-stat-icon bg-warning">
                                    <i class="fa fa-clock-o text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="admin-stat-card border-success">
                                <div>
                                    <span class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">SUDAH TERVALIDASI</span>
                                    <h3 class="font-weight-bold text-success mb-0"><?= $summary['valid'] ?></h3>
                                    <small class="text-muted">Disetujui &amp; sah</small>
                                </div>
                                <div class="admin-stat-icon bg-success">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="admin-stat-card border-primary">
                                <div>
                                    <span class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL PENGAJUAN</span>
                                    <h3 class="font-weight-bold text-primary mb-0"><?= $summary['total'] ?></h3>
                                    <small class="text-muted">Seluruh kegiatan SKPI</small>
                                </div>
                                <div class="admin-stat-icon bg-primary">
                                    <i class="fa fa-folder-open text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Filter & Pencarian -->
                    <div class="admin-filter-bar">
                        <form method="GET" action="<?= base_url('admin/skpi') ?>" class="form-row align-items-center">
                            <div class="col-lg-6 col-md-6 my-1">
                                <label class="font-weight-bold text-muted mb-1" style="font-size: 11px;">PENCARIAN MAHASISWA</label>
                                <div class="input-group mb-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                    </div>
                                    <input type="text" name="keyword" class="form-control" placeholder="Cari nama mahasiswa atau NIM..." value="<?= html_escape($filter['keyword']) ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 my-1">
                                <label class="font-weight-bold text-muted mb-1" style="font-size: 11px;">STATUS PENGAJUAN</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="WAITING" <?= ($filter['status'] === 'WAITING') ? 'selected' : '' ?>>Ada yang Belum Divalidasi</option>
                                    <option value="YES" <?= ($filter['status'] === 'YES') ? 'selected' : '' ?>>Semua Sudah Tervalidasi</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-12 my-1 align-self-end">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    <i class="fa fa-filter mr-1"></i> Filter Data
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Rekap Mahasiswa Pengaju SKPI -->
                    <div class="admin-skpi-card">
                        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap" style="border-bottom: 1px solid #e2e8f0;">
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-users text-primary mr-2"></i>Daftar Mahasiswa Pengaju SKPI</h5>
                                <small class="text-muted">Klik pada nama mahasiswa atau tombol periksa untuk melihat rincian SKPI berdasarkan 10 kategori.</small>
                            </div>
                            <span class="badge badge-light border px-3 py-2 text-dark font-weight-semibold">
                                Ditemukan: <strong><?= count($mahasiswa_list) ?></strong> mahasiswa
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rekap-skpi mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;" class="text-center">#</th>
                                        <th style="width: 40%;">Nama Mahasiswa</th>
                                        <th style="width: 18%;" class="text-center">Belum Tervalidasi</th>
                                        <th style="width: 18%;" class="text-center">Sudah Tervalidasi</th>
                                        <th style="width: 19%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($mahasiswa_list)): ?>
                                        <?php $no = 1; foreach ($mahasiswa_list as $row): ?>
                                            <?php 
                                                $detail_url = base_url('admin/skpi/detail/' . $row->mahasiswa_id); 
                                                $initial = strtoupper(substr($row->nama_mahasiswa ?: 'M', 0, 1));
                                            ?>
                                            <tr class="row-mahasiswa-skpi" onclick="window.location='<?= $detail_url ?>'">
                                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mhs-avatar-badge mr-3">
                                                            <?= $initial ?>
                                                        </div>
                                                        <div>
                                                            <a href="<?= $detail_url ?>" class="font-weight-bold text-dark d-block text-decoration-none" style="font-size: 14.5px;">
                                                                <?= html_escape($row->nama_mahasiswa) ?>
                                                            </a>
                                                            <div class="text-muted" style="font-size: 12px;">
                                                                <span>NIM: <strong><?= html_escape($row->nim) ?></strong></span>
                                                                <span class="mx-1">&bull;</span>
                                                                <span><?= html_escape($row->prodi ?: '-') ?></span>
                                                                <?php if (!empty($row->semester)): ?>
                                                                    <span class="mx-1">&bull;</span>
                                                                    <span>Sem <?= html_escape($row->semester) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ((int)$row->total_waiting > 0): ?>
                                                        <span class="badge-count-pill pill-waiting">
                                                            <i class="fa fa-clock-o"></i> <?= $row->total_waiting ?> Belum Divalidasi
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11.5px;">
                                                            0 Menunggu
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ((int)$row->total_valid > 0): ?>
                                                        <span class="badge-count-pill pill-valid">
                                                            <i class="fa fa-check"></i> <?= $row->total_valid ?> Telah Valid
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11.5px;">
                                                            0 Valid
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" onclick="event.stopPropagation();">
                                                    <a href="<?= $detail_url ?>" class="btn btn-primary btn-sm px-3 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                                        <i class="fa fa-folder-open-o mr-1"></i> Buka SKPI (10 Kategori)
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">
                                                <i class="fa fa-users fa-3x d-block mb-3 text-muted"></i>
                                                <h5>Tidak ada data mahasiswa pengaju SKPI</h5>
                                                <p class="text-muted mb-0">Belum ada pengajuan kegiatan atau tidak sesuai dengan kata kunci pencarian.</p>
                                            </td>
                                        </tr>
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
