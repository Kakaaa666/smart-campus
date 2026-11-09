<style>
    /* Styling Khusus Modul SKPI Mahasiswa */
    .skpi-well {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 20px 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 25px;
    }
    .skpi-well .lead-text {
        font-size: 15px;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    .skpi-status-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 12px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .skpi-status-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
    }
    .skpi-status-item .label-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .label-warning-custom { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .label-success-custom { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .label-danger-custom  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .label-primary-custom { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }

    /* Filter Card & Controls */
    .skpi-filter-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }
    .skpi-search-wrap {
        position: relative;
        width: 100%;
    }
    .skpi-search-wrap .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #94a3b8;
        pointer-events: none;
        z-index: 3;
    }
    .skpi-search-input {
        padding-left: 38px !important;
        padding-right: 14px !important;
        height: 42px !important;
        min-height: 42px !important;
        border-radius: 10px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        transition: all 0.2s ease !important;
    }
    .skpi-search-input:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12) !important;
        outline: none !important;
    }
    .skpi-select-status {
        height: 42px !important;
        min-height: 42px !important;
        border-radius: 10px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        font-size: 13.5px !important;
        padding: 0 14px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        cursor: pointer;
    }
    .skpi-select-status:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12) !important;
        outline: none !important;
    }
    .skpi-filter-btn {
        height: 42px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 0 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px;
    }

    /* Filter Kategori Card - Simpel & Rapi */
    .kategori-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 18px;
        margin-bottom: 22px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }
    .kategori-filter-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .filter-card-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .filter-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .filter-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #e0f2fe;
        color: #0284c7;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .filter-main-label {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        letter-spacing: 0.2px;
        cursor: pointer;
    }
    .filter-sub-label {
        font-size: 12px;
        color: #64748b;
        margin-left: 6px;
    }
    .filter-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        max-width: 480px;
        justify-content: flex-end;
    }
    .select-wrapper {
        position: relative;
        width: 100%;
        min-width: 260px;
    }
    .select-prefix-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #0284c7;
        font-size: 13px;
        pointer-events: none;
        z-index: 2;
    }
    .select-caret-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 11px;
        pointer-events: none;
        z-index: 2;
    }
    .filter-select-input {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 32px 8px 34px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        height: 38px;
        line-height: 1.4;
        cursor: pointer;
        outline: none !important;
        transition: all 0.2s ease;
        width: 100%;
    }
    .filter-select-input:hover {
        background: #ffffff;
        border-color: #94a3b8;
    }
    .filter-select-input:focus {
        background: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        color: #0f172a;
    }
    .btn-reset-filter {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 12px;
        white-space: nowrap;
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
    }
    .skpi-category-card.filter-fade-in {
        animation: skpiFilterFadeIn 0.24s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes skpiFilterFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Kategori Card Panels */
    .skpi-category-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .skpi-category-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .skpi-card-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .skpi-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }
    .skpi-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .skpi-title-text h4 {
        margin: 0 0 2px 0;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }
    .skpi-title-text p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }
    .btn-tambah-kategori {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 13px;
        padding: 7px 15px;
        border-radius: 8px;
        background: #0284c7;
        color: #ffffff;
        border: 1px solid #0284c7;
        box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
        transition: all 0.15s ease;
    }
    .btn-tambah-kategori:hover, .btn-tambah-kategori:focus {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(2, 132, 199, 0.3);
        text-decoration: none;
    }

    /* Tabel Kategori */
    .skpi-table {
        margin-bottom: 0;
    }
    .skpi-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }
    .skpi-table td {
        vertical-align: middle;
        padding: 12px 14px;
        font-size: 13px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
    /* Action buttons */
    .skpi-action-btn {
        padding: 5px 9px;
        font-size: 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin: 2px;
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">

                <!-- Notifikasi Flashdata -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-2"></i> <strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2"></i> <strong>Peringatan!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Header Banner Well Konsep SAKTI SKPI -->
                <div class="skpi-well text-center">
                    <p class="lead-text">
                        <strong>SKPI</strong> merupakan singkatan dari <strong>S</strong>urat <strong>K</strong>eterangan <strong>P</strong>endamping <strong>I</strong>jazah.<br>
                        Surat ini berisi rekam jejak kegiatan, sertifikasi kompetensi, karya inovasi, dan prestasi yang pernah Anda ikuti selama kuliah di <strong>Smart Campus</strong>, baik kegiatan internal maupun eksternal.
                    </p>
                    <ul class="skpi-status-list">
                        <li class="skpi-status-item">
                            <i class="fa fa-info-circle text-muted"></i> <span>Status Pengajuan:</span>
                        </li>
                        <li class="skpi-status-item">
                            <span class="label-badge label-warning-custom"><i class="fa fa-clock-o mr-1"></i> Belum Validasi</span>
                            <strong id="summary-WAITING" class="ml-1 text-dark"><?= $summary['waiting'] ?></strong>
                        </li>
                        <li class="skpi-status-item">
                            <span class="label-badge label-success-custom"><i class="fa fa-check mr-1"></i> Valid</span>
                            <strong id="summary-YES" class="ml-1 text-dark"><?= $summary['valid'] ?></strong>
                        </li>
                        <li class="skpi-status-item">
                            <span class="label-badge label-danger-custom"><i class="fa fa-times mr-1"></i> Tdk Valid</span>
                            <strong id="summary-NO" class="ml-1 text-dark"><?= $summary['invalid'] ?></strong>
                        </li>
                        <li class="skpi-status-item">
                            <span class="label-badge label-primary-custom"><i class="fa fa-folder-open mr-1"></i> Total Pengajuan</span>
                            <strong id="summary-TOTAL" class="ml-1 text-dark"><?= $summary['total'] ?></strong>
                        </li>
                    </ul>
                </div>

                <!-- Filter & Pencarian -->
                <div class="skpi-filter-box">
                    <form method="GET" action="<?= base_url('skpi') ?>" class="form-row align-items-center">
                        <div class="col-md-5 my-1">
                            <div class="skpi-search-wrap">
                                <i class="fa fa-search search-icon"></i>
                                <input type="text" name="nama" class="form-control skpi-search-input" placeholder="Cari nama kegiatan, penyelenggara, atau no sertifikat..." value="<?= html_escape($filter['nama']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3 my-1">
                            <select name="status" class="form-control skpi-select-status">
                                <option value="">-- Semua Status Validasi --</option>
                                <option value="WAITING" <?= ($filter['status'] === 'WAITING') ? 'selected' : '' ?>>Belum Divalidasi (Waiting)</option>
                                <option value="YES" <?= ($filter['status'] === 'YES') ? 'selected' : '' ?>>Valid (Disetujui)</option>
                                <option value="NO" <?= ($filter['status'] === 'NO') ? 'selected' : '' ?>>Tidak Valid (Ditolak)</option>
                            </select>
                        </div>
                        <div class="col-md-4 my-1 text-md-right d-flex justify-content-md-end align-items-center flex-wrap" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary skpi-filter-btn">
                                <i class="fa fa-filter"></i> Terapkan Filter
                            </button>
                            <?php if (!empty($filter['nama']) || !empty($filter['status'])): ?>
                                <a href="<?= base_url('skpi') ?>" class="btn btn-outline-secondary skpi-filter-btn">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Filter Kategori Kegiatan SKPI (Simpel & Rapi) -->
                <div class="kategori-filter-card" id="kategoriFilterCard">
                    <div class="filter-card-inner">
                        <div class="filter-left">
                            <div class="filter-icon-badge">
                                <i class="fa fa-filter"></i>
                            </div>
                            <div class="filter-title-wrap">
                                <label for="selectKategoriFilter" class="filter-main-label">Filter Kategori Kegiatan:</label>
                                <span class="filter-sub-label d-none d-md-inline">&bull; Saring daftar kegiatan berdasarkan kategori</span>
                            </div>
                        </div>
                        <div class="filter-right">
                            <div class="select-wrapper">
                                <i class="fa fa-folder-open select-prefix-icon"></i>
                                <select id="selectKategoriFilter" class="form-control filter-select-input" onchange="applyCategoryFilter(this.value)">
                                    <option value="all">Semua Kategori (10 Kategori Kegiatan &bull; <?= $summary['total'] ?> Pengajuan)</option>
                                    <?php foreach ($kategori_list as $kId => $kInfo): ?>
                                        <option value="<?= $kId ?>">
                                            <?= $kId ?>. <?= html_escape($kInfo['nama']) ?> (<?= $kategori_data[$kId]['count'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fa fa-chevron-down select-caret-icon"></i>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-reset-filter" id="btnResetFilterKategori" style="display: none;" onclick="applyCategoryFilter('all')">
                                <i class="fa fa-refresh mr-1"></i> Tampilkan Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Kategori yang sedang Difilter -->
                <div id="filterCategoryNotice" style="display: none; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px;" class="mb-4 py-2.5 px-3.5 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle mr-2 text-primary" style="font-size: 16px;"></i>
                        <span style="font-size: 13px;" class="text-slate-700">
                            Menampilkan kategori: <strong id="noticeCategoryTitle" class="text-dark"></strong>
                            <span class="text-muted ml-1" id="noticeCategoryCount"></span>
                        </span>
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-primary font-weight-bold p-0 text-decoration-none" onclick="applyCategoryFilter('all')">
                        <i class="fa fa-refresh mr-1"></i> Tampilkan Semua Kategori
                    </button>
                </div>

                <!-- DAFTAR 10 KATEGORI DENGAN TOMBOL TAMBAH DI SETIAP KATEGORI -->
                <?php foreach ($kategori_list as $kId => $kInfo): ?>
                    <?php 
                        $kat_data = $kategori_data[$kId];
                        $items    = $kat_data['items'];
                        $count    = $kat_data['count'];
                    ?>
                    <div class="skpi-category-card" id="kategori-<?= $kId ?>">
                        <!-- Header Kategori -->
                        <div class="skpi-card-header">
                            <div class="skpi-card-title">
                                <div class="skpi-card-icon" style="background-color: <?= $kInfo['color'] ?>;">
                                    <i class="<?= $kInfo['icon'] ?>"></i>
                                </div>
                                <div class="skpi-title-text">
                                    <h4><?= $kId ?>. <?= html_escape($kInfo['nama']) ?></h4>
                                    <p><?= html_escape($kInfo['deskripsi']) ?></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light border mr-3 px-2 py-1 font-weight-normal text-muted" style="font-size: 12px;">
                                    Total: <strong><?= $count ?></strong> kegiatan (Valid: <?= $kat_data['valid'] ?>, Waiting: <?= $kat_data['waiting'] ?>)
                                </span>
                                <!-- TOMBOL TAMBAH KHUSUS PADA SETIAP KATEGORI -->
                                <button type="button" 
                                        class="btn-tambah-kategori" 
                                        data-toggle="modal" 
                                        data-target="#modalFormKegiatan" 
                                        data-kategori-id="<?= $kId ?>" 
                                        data-kategori-nama="<?= html_escape($kInfo['nama']) ?>">
                                    <i class="fa fa-plus"></i> Tambah Kegiatan
                                </button>
                            </div>
                        </div>

                        <!-- Body Tabel Kategori -->
                        <div class="table-responsive">
                            <table class="table table-hover skpi-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 4%;" class="text-center">#</th>
                                        <th style="width: 38%;">Nama Kegiatan</th>
                                        <th style="width: 22%;">Penyelenggara & Tingkat</th>
                                        <th style="width: 14%;" class="text-center">Status</th>
                                        <th style="width: 10%;" class="text-center">Berkas</th>
                                        <th style="width: 12%;" class="text-center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php $no = 1; foreach ($items as $item): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                                <td>
                                                    <div class="font-weight-bold text-dark" style="font-size: 14px;"><?= html_escape($item->nama_kegiatan) ?></div>
                                                    <div class="text-muted" style="font-size: 12px;">
                                                        <?php if (!empty($item->peran)): ?>
                                                            <span class="mr-2"><i class="fa fa-user-circle-o mr-1"></i><?= html_escape($item->peran) ?></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item->tanggal_kegiatan)): ?>
                                                            <span><i class="fa fa-calendar mr-1"></i><?= date('d M Y', strtotime($item->tanggal_kegiatan)) ?></span>
                                                        <?php elseif (!empty($item->tahun)): ?>
                                                            <span><i class="fa fa-calendar mr-1"></i>Tahun <?= $item->tahun ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-dark"><?= html_escape($item->penyelenggara ?: '-') ?></div>
                                                    <span class="badge badge-light border text-secondary" style="font-size: 11px;">
                                                        <i class="fa fa-globe mr-1"></i><?= html_escape($item->tingkat ?: 'Lokal / Kampus') ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($item->status === 'YES'): ?>
                                                        <span class="badge badge-success px-2 py-1" style="font-size: 11px;"><i class="fa fa-check mr-1"></i>Valid</span>
                                                    <?php elseif ($item->status === 'NO'): ?>
                                                        <span class="badge badge-danger px-2 py-1" style="font-size: 11px;"><i class="fa fa-times mr-1"></i>Tidak Valid</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11px;"><i class="fa fa-clock-o mr-1"></i>Belum Divalidasi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($item->file_bukti)): ?>
                                                        <a href="<?= base_url('uploads/skpi/' . $item->file_bukti) ?>" target="_blank" class="btn btn-outline-info btn-sm skpi-action-btn" title="Lihat Berkas Bukti">
                                                            <i class="fa fa-file-pdf-o"></i> Bukti
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size: 11px;">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm skpi-action-btn btn-view-detail" 
                                                            data-id="<?= $item->id ?>" 
                                                            title="Detail Kegiatan">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm skpi-action-btn btn-edit-item" 
                                                            data-id="<?= $item->id ?>" 
                                                            title="Edit Kegiatan">
                                                        <i class="fa fa-pencil text-dark"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm skpi-action-btn btn-delete-item" 
                                                            data-id="<?= $item->id ?>" 
                                                            data-nama="<?= html_escape($item->nama_kegiatan) ?>" 
                                                            title="Hapus Kegiatan">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3" style="font-size: 13px;">
                                                Belum ada kegiatan pada kategori ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
</div>

<!-- ============================================================== -->
<!-- MODAL FORM TAMBAH / EDIT KEGIATAN SKPI                         -->
<!-- ============================================================== -->
<div class="modal fade" id="modalFormKegiatan" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <form id="formSkpi" method="POST" action="<?= base_url('skpi/simpan') ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" id="form_id" value="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalFormTitle">
                        <i class="fa fa-plus-circle mr-2"></i> Tambah Kegiatan SKPI
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <!-- Alert Notif di dalam modal -->
                    <div id="modal-alert" class="alert alert-danger d-none"></div>

                    <!-- Kategori Otomatis Berdasarkan Tombol Tambah pada Tabel/Kategori yang Dipilih -->
                    <input type="hidden" name="kategori_id" id="form_kategori_id" value="1">
                    <div class="alert alert-primary d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 8px; background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
                        <i class="fa fa-folder-open mr-2" style="font-size: 18px;"></i>
                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; display: block; opacity: 0.85;">Kategori Kegiatan:</span>
                            <strong id="modal_kategori_display" style="font-size: 14px;">1. Prestasi dan Penghargaan</strong>
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Nama Kegiatan / Sertifikasi / Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kegiatan" id="form_nama_kegiatan" class="form-control" placeholder="Contoh: Juara 2 Hackathon Mobile App / Pelatihan Web Developer" required>
                    </div>

                    <div class="row">
                        <!-- Penyelenggara -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Penyelenggara / Instansi</label>
                            <input type="text" name="penyelenggara" id="form_penyelenggara" class="form-control" placeholder="Contoh: Kemdikbudristek, Google, Telkom, Kampus">
                        </div>
                        <!-- Tingkat -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Tingkat Kegiatan</label>
                            <select name="tingkat" id="form_tingkat" class="form-control">
                                <option value="Lokal / Kampus">Lokal / Kampus</option>
                                <option value="Regional">Regional / Wilayah</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Peran / Capaian -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Peran / Capaian</label>
                            <input type="text" name="peran" id="form_peran" class="form-control" placeholder="Contoh: Juara 1, Peserta, Ketua, Pemakalah, Asisten">
                        </div>
                        <!-- Tanggal Kegiatan -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal_kegiatan" id="form_tanggal_kegiatan" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <!-- No Sertifikat -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Nomor Sertifikat / SK</label>
                            <input type="text" name="no_sertifikat" id="form_no_sertifikat" class="form-control" placeholder="Contoh: 042/CERT/HIMA/2024">
                        </div>
                        <!-- Upload Bukti -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Berkas Bukti (Sertifikat/SK/Foto)</label>
                            <input type="file" name="file_bukti" id="form_file_bukti" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Format: PDF, JPG, PNG (Maks. 5 MB). <span id="file_current_label"></span></small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="btnSubmitForm">
                        <i class="fa fa-save mr-1"></i> Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL DETAIL KEGIATAN SKPI                                     -->
<!-- ============================================================== -->
<div class="modal fade" id="modalDetailKegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-info-circle mr-2"></i> Detail Pengajuan SKPI</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                <div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL HAPUS KONFIRMASI                                         -->
<!-- ============================================================== -->
<div class="modal fade" id="modalHapusKegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-trash mr-2"></i> Hapus Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p>Apakah Anda yakin ingin menghapus data kegiatan:</p>
                <div class="alert alert-warning mb-0">
                    <strong id="deleteItemName" class="text-dark"></strong>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fa fa-trash mr-1"></i> Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var kategoriNames = {
        <?php foreach ($kategori_list as $kId => $kInfo): ?>
            <?= $kId ?>: <?= json_encode($kInfo['nama']) ?>,
        <?php endforeach; ?>
    };

    // Saat tombol "Tambah Kegiatan" di kategori spesifik diklik
    $('#modalFormKegiatan').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var katId = button.data('kategori-id');
        var katNama = button.data('kategori-nama');
        
        // Reset form jika mode tambah
        if (button.hasClass('btn-tambah-kategori') || button.hasClass('btn-outline-primary')) {
            $('#formSkpi')[0].reset();
            $('#form_id').val('');
            $('#modalFormTitle').html('<i class="fa fa-plus-circle mr-2"></i> Tambah Kegiatan SKPI');
            $('#file_current_label').text('');
            $('#modal-alert').addClass('d-none').text('');
            
            if (katId) {
                $('#form_kategori_id').val(katId);
                var labelKat = katNama || kategoriNames[katId] || ('Kategori ' + katId);
                $('#modal_kategori_display').text(katId + '. ' + labelKat);
            }
        }
    });

    // Tombol Edit
    $('.btn-edit-item').on('click', function() {
        var id = $(this).data('id');
        $('#modal-alert').addClass('d-none').text('');
        
        $.ajax({
            url: '<?= base_url("skpi/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status && res.data) {
                    var d = res.data;
                    $('#form_id').val(d.id);
                    $('#form_kategori_id').val(d.kategori_id);
                    var labelKat = d.kategori_nama || kategoriNames[d.kategori_id] || ('Kategori ' + d.kategori_id);
                    $('#modal_kategori_display').text(d.kategori_id + '. ' + labelKat);
                    $('#form_nama_kegiatan').val(d.nama_kegiatan);
                    $('#form_penyelenggara').val(d.penyelenggara);
                    $('#form_tingkat').val(d.tingkat);
                    $('#form_peran').val(d.peran);
                    $('#form_tanggal_kegiatan').val(d.tanggal_kegiatan);
                    $('#form_no_sertifikat').val(d.no_sertifikat);
                    
                    if (d.file_bukti) {
                        $('#file_current_label').html('Berkas saat ini: <a href="' + d.file_url + '" target="_blank" class="font-weight-bold">' + d.file_bukti + '</a>');
                    } else {
                        $('#file_current_label').text('');
                    }

                    $('#modalFormTitle').html('<i class="fa fa-pencil-square mr-2"></i> Edit Kegiatan SKPI');
                    $('#modalFormKegiatan').modal('show');
                } else {
                    SCDialog.alert('Gagal mengambil data kegiatan: ' + (res.message || 'Kesalahan sistem'), { title: 'Gagal Memuat Data', type: 'error' });
                }
            },
            error: function() {
                SCDialog.alert('Terjadi kesalahan saat memuat data kegiatan.', { title: 'Kesalahan Sistem', type: 'error' });
            }
        });
    });

    // Tombol Detail
    $('.btn-view-detail').on('click', function() {
        var id = $(this).data('id');
        $('#detailContent').html('<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
        $('#modalDetailKegiatan').modal('show');

        $.ajax({
            url: '<?= base_url("skpi/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status && res.data) {
                    var d = res.data;
                    var statusBadge = '';
                    if (d.status === 'YES') {
                        statusBadge = '<span class="badge badge-success px-3 py-1 font-weight-bold"><i class="fa fa-check mr-1"></i> Valid (Disetujui)</span>';
                    } else if (d.status === 'NO') {
                        statusBadge = '<span class="badge badge-danger px-3 py-1 font-weight-bold"><i class="fa fa-times mr-1"></i> Tidak Valid (Ditolak)</span>';
                    } else {
                        statusBadge = '<span class="badge badge-warning text-dark px-3 py-1 font-weight-bold"><i class="fa fa-clock-o mr-1"></i> Belum Divalidasi (Waiting)</span>';
                    }

                    var html = '' +
                        '<div class="table-responsive">' +
                        '<table class="table table-bordered mb-0">' +
                        '<tr><th class="bg-light" style="width: 30%;">Kategori</th><td><strong>' + (d.kategori_id) + '. ' + (d.kategori_nama || '-') + '</strong></td></tr>' +
                        '<tr><th class="bg-light">Nama Kegiatan</th><td class="font-weight-bold text-primary" style="font-size: 15px;">' + (d.nama_kegiatan || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Penyelenggara</th><td>' + (d.penyelenggara || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Tingkat</th><td>' + (d.tingkat || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Peran / Capaian</th><td>' + (d.peran || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Tanggal Kegiatan</th><td>' + (d.tanggal_kegiatan || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Nomor Sertifikat/SK</th><td>' + (d.no_sertifikat || '-') + '</td></tr>' +
                        '<tr><th class="bg-light">Status Validasi</th><td>' + statusBadge + '</td></tr>' +
                        (d.catatan_validasi ? '<tr><th class="bg-light">Catatan Verifikator</th><td class="text-danger">' + d.catatan_validasi + '</td></tr>' : '') +
                        '<tr><th class="bg-light">Berkas Bukti</th><td>' + (d.file_url ? '<a href="' + d.file_url + '" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fa fa-download mr-1"></i> Unduh / Buka Berkas</a>' : '<span class="text-muted">Tidak ada berkas diunggah</span>') + '</td></tr>' +
                        '</table>' +
                        '</div>';

                    $('#detailContent').html(html);
                } else {
                    $('#detailContent').html('<div class="alert alert-danger">Gagal memuat detail kegiatan.</div>');
                }
            },
            error: function() {
                $('#detailContent').html('<div class="alert alert-danger">Terjadi kesalahan sistem saat memuat data.</div>');
            }
        });
    });

    // Tombol Hapus
    $('.btn-delete-item').on('click', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        $('#deleteItemName').text(nama);
        $('#confirmDeleteBtn').attr('href', '<?= base_url("skpi/hapus/") ?>' + id);
        $('#modalHapusKegiatan').modal('show');
    });

    // Cek apakah ada anchor kategori di URL saat halaman dimuat
    var initialHash = window.location.hash;
    if (initialHash && initialHash.indexOf('#kategori-') === 0) {
        var katIdFromHash = initialHash.replace('#kategori-', '');
        if ($('#selectKategoriFilter option[value="' + katIdFromHash + '"]').length > 0) {
            window.applyCategoryFilter(katIdFromHash);
        }
    }
});

// Fungsi Filter Kategori Kegiatan SKPI (Simpel & Rapi)
window.applyCategoryFilter = function(kategoriId) {
    kategoriId = String(kategoriId || 'all');

    // Sinkronkan dropdown select
    $('#selectKategoriFilter').val(kategoriId);

    // Filter card kategori di bawah
    if (kategoriId === 'all') {
        $('.skpi-category-card').stop(true, true).fadeIn(180).addClass('filter-fade-in');
        $('#btnResetFilterKategori').hide();
        $('#filterCategoryNotice').slideUp(180);
    } else {
        $('.skpi-category-card').not('#kategori-' + kategoriId).stop(true, true).hide().removeClass('filter-fade-in');
        var targetCard = $('#kategori-' + kategoriId);
        targetCard.stop(true, true).fadeIn(200).addClass('filter-fade-in');

        var optionSelected = $('#selectKategoriFilter option:selected');
        var optionText = optionSelected.text().trim();

        $('#btnResetFilterKategori').show();
        $('#noticeCategoryTitle').text(optionText);
        $('#noticeCategoryCount').text('');
        $('#filterCategoryNotice').slideDown(180);

        var filterCard = $('#kategoriFilterCard');
        if (filterCard.length) {
            var scrollTarget = filterCard.offset().top - 70;
            if ($(window).scrollTop() > scrollTarget) {
                $('html, body').animate({ scrollTop: scrollTarget }, 250);
            }
        }
    }
};
</script>
