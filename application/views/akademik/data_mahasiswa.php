<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* Styling Elemen Halaman Data Mahasiswa */
    .data-mhs-container {
        padding: 0 5px;
    }

    .stat-card-mhs {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        padding: 20px;
        display: flex;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        margin-bottom: 20px;
    }

    .stat-card-mhs:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .stat-blue   { background: #eff6ff; color: #2563eb; }
    .stat-green  { background: #ecfdf5; color: #059669; }
    .stat-purple { background: #faf5ff; color: #7c3aed; }
    .stat-amber  { background: #fffbeb; color: #d97706; }

    .stat-info h3 {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 2px 0;
        line-height: 1.2;
    }

    .stat-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        font-weight: 500;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        padding: 22px 24px;
        margin-bottom: 24px;
    }

    .filter-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-card-title i {
        color: #2563eb;
    }

    /* Quick Filter Pills */
    .prodi-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
    }

    .prodi-pill-btn {
        font-size: 12.5px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
    }

    .prodi-pill-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
        text-decoration: none;
    }

    .prodi-pill-btn.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.28);
    }

    /* Precision Filter Form Layout & Elements */
    .filter-form-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        margin-left: -7px;
        margin-right: -7px;
    }

    .filter-col {
        padding-left: 7px;
        padding-right: 7px;
        margin-bottom: 6px;
    }

    .filter-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 7px;
        display: block;
        line-height: 1.2;
    }

    .filter-control {
        height: 42px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background-color: #ffffff !important;
        padding: 8px 12px !important;
        width: 100% !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .filter-control:focus {
        border-color: #166534 !important;
        box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.15) !important;
        outline: none !important;
    }

    .filter-input-group {
        display: flex;
        width: 100%;
        height: 42px;
        box-sizing: border-box;
    }

    .filter-input-group .filter-control {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none !important;
        flex: 1 1 auto;
    }

    .filter-btn-search {
        background-color: #166534 !important;
        border: 1.5px solid #166534 !important;
        color: #ffffff !important;
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        padding: 0 16px !important;
        height: 42px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .filter-btn-search:hover {
        background-color: #14532d !important;
        border-color: #14532d !important;
    }

    .filter-btn-submit {
        background-color: #166534 !important;
        border: 1.5px solid #166534 !important;
        color: #ffffff !important;
        height: 42px !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0 16px !important;
        box-shadow: 0 2px 6px rgba(22, 101, 52, 0.18);
        cursor: pointer;
        transition: background-color 0.15s ease, transform 0.1s ease;
    }

    .filter-btn-submit:hover {
        background-color: #14532d !important;
        border-color: #14532d !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Data Table Card */
    .mhs-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .mhs-table-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .table-mhs {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 980px; /* Cegah tabel data mahasiswa terhimpit */
    }

    .table-mhs thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        padding: 14px 16px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .table-mhs tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        line-height: 1.5;
    }

    .table-mhs tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Avatar Mahasiswa */
    .mhs-avatar-wrap {
        position: relative;
        display: inline-block;
        flex-shrink: 0;
    }

    .mhs-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #e2e8f0;
    }

    .mhs-avatar-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    /* Badges */
    .badge-nim {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        background: #f8fafc;
        color: #0f172a;
        font-size: 12.5px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        letter-spacing: 0.5px;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-semester {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .badge-prodi {
        display: inline-flex;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 6px;
        white-space: nowrap;
        gap: 5px;
    }

    .badge-prodi-ti { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-prodi-si { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-prodi-ds { background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; }
    .badge-prodi-bd { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-prodi-other { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }

    .badge-status-aktif {
        background: #dcfce7 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
        font-weight: 700 !important;
        font-size: 11.5px !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        white-space: nowrap !important;
    }

    .badge-status-cuti {
        background: #fef9c3 !important;
        color: #854d0e !important;
        border: 1px solid #fef08a !important;
        font-weight: 700 !important;
        font-size: 11.5px !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        white-space: nowrap !important;
    }

    .badge-status-nonaktif {
        background: #fee2e2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
        font-weight: 700 !important;
        font-size: 11.5px !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        white-space: nowrap !important;
    }

    .btn-action-view {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 6px;
        padding: 5px 9px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .btn-action-view:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .btn-action-edit {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #059669;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .btn-action-edit:hover {
        background: #059669;
        border-color: #059669;
        color: #fff;
    }

    /* Modal Styling */
    .modal-detail-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 22px;
    }

    .detail-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 13.5px;
    }

    .detail-label {
        width: 160px;
        color: #64748b;
        font-weight: 600;
        flex-shrink: 0;
    }

    .detail-val {
        color: #0f172a;
        font-weight: 600;
        flex: 1;
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content" style="padding-top: 20px;">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body data-mhs-container">

                    <!-- Flash Message Notifikasi -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fa fa-check-circle mr-2"></i> <?= html_escape($this->session->flashdata('success')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fa fa-exclamation-triangle mr-2"></i> <?= html_escape($this->session->flashdata('error')) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Header Direktori Mahasiswa -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                <i class="fa fa-users text-primary mr-2"></i> Direktori & Data Mahasiswa
                            </h4>
                            <p class="text-muted mb-0" style="font-size: 13.5px;">
                                Kelola direktori mahasiswa, program studi, jenjang semester, dan status akademik Biro Akademik.
                            </p>
                        </div>
                        <div>
                            <span class="badge badge-primary px-3 py-2" style="font-size: 12px; border-radius: 8px;">
                                <i class="fa fa-graduation-cap mr-1"></i> Biro Akademik
                            </span>
                        </div>
                    </div>

                    <!-- 4 Ringkasan Statistik Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <div class="stat-card-mhs">
                                <div class="stat-icon-wrapper stat-blue">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?= number_format($stats['total_mahasiswa'] ?? 0) ?></h3>
                                    <p>Total Mahasiswa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <div class="stat-card-mhs">
                                <div class="stat-icon-wrapper stat-green">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?= number_format($stats['total_aktif'] ?? 0) ?></h3>
                                    <p>Mahasiswa Aktif</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <div class="stat-card-mhs">
                                <div class="stat-icon-wrapper stat-purple">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?= number_format($stats['total_prodi'] ?? 0) ?></h3>
                                    <p>Program Studi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <div class="stat-card-mhs">
                                <div class="stat-icon-wrapper stat-amber">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?= number_format($stats['with_advisor'] ?? 0) ?></h3>
                                    <p>Ber-Dosen Wali</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Filter Pencarian & Program Studi -->
                    <div class="filter-card">
                        <div class="filter-card-header">
                            <h5 class="filter-card-title">
                                <i class="fa fa-filter"></i> Filter & Pencarian Mahasiswa
                            </h5>
                            <?php if (!empty($filter['prodi']) || !empty($filter['semester']) || !empty($filter['status']) || !empty($filter['search'])): ?>
                                <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-outline-danger font-weight-bold" style="font-size: 11.5px; border-radius: 6px;">
                                    <i class="fa fa-refresh mr-1"></i> Reset Filter
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Quick Filter Pills Khusus Program Studi (Membedakan Prodi dengan 1 Klik) -->
                        <div class="mb-2">
                            <label class="text-muted font-weight-bold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Pilihan Cepat Program Studi:
                            </label>
                            <div class="prodi-pills">
                                <a href="<?= base_url('admin/pengguna?' . http_build_query(array_merge($filter, ['prodi' => '']))) ?>" 
                                   class="prodi-pill-btn <?= empty($filter['prodi']) ? 'active' : '' ?>">
                                    <i class="fa fa-th-large mr-1"></i> Semua Prodi
                                </a>
                                <?php foreach ($prodi_list as $p): ?>
                                    <a href="<?= base_url('admin/pengguna?' . http_build_query(array_merge($filter, ['prodi' => $p]))) ?>" 
                                       class="prodi-pill-btn <?= ($filter['prodi'] === $p) ? 'active' : '' ?>">
                                        <i class="fa fa-graduation-cap mr-1"></i> <?= html_escape($p) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Form Filter Lengkap: Prodi, Semester, Status, dan Input Pencarian -->
                        <form method="GET" action="<?= base_url('admin/pengguna') ?>" class="filter-form-row">
                            <!-- Filter Prodi Dropdown -->
                            <div class="filter-col col-xl-3 col-lg-3 col-md-6 col-12">
                                <label for="filterProdi" class="filter-label">Program Studi</label>
                                <select name="prodi" id="filterProdi" class="form-control filter-control">
                                    <option value="">-- Semua Program Studi --</option>
                                    <?php foreach ($prodi_list as $p): ?>
                                        <option value="<?= html_escape($p) ?>" <?= ($filter['prodi'] === $p) ? 'selected' : '' ?>>
                                            <?= html_escape($p) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filter Semester Dropdown -->
                            <div class="filter-col col-xl-2 col-lg-2 col-md-3 col-6">
                                <label for="filterSemester" class="filter-label">Semester</label>
                                <select name="semester" id="filterSemester" class="form-control filter-control">
                                    <option value="">-- Semua --</option>
                                    <?php foreach ($semester_list as $sem): ?>
                                        <option value="<?= (int)$sem ?>" <?= ((string)$filter['semester'] === (string)$sem) ? 'selected' : '' ?>>
                                            Semester <?= (int)$sem ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filter Status Akademik Dropdown -->
                            <div class="filter-col col-xl-2 col-lg-2 col-md-3 col-6">
                                <label for="filterStatus" class="filter-label">Status Akademik</label>
                                <select name="status" id="filterStatus" class="form-control filter-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="Aktif" <?= ($filter['status'] === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Cuti" <?= ($filter['status'] === 'Cuti') ? 'selected' : '' ?>>Cuti</option>
                                    <option value="Non-Aktif" <?= ($filter['status'] === 'Non-Aktif') ? 'selected' : '' ?>>Non-Aktif</option>
                                    <option value="Lulus" <?= ($filter['status'] === 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                                </select>
                            </div>

                            <!-- Input Pencarian (NIM / Nama) -->
                            <div class="filter-col col-xl-3 col-lg-3 col-md-8 col-12">
                                <label for="searchQuery" class="filter-label">Cari NIM atau Nama</label>
                                <div class="filter-input-group">
                                    <input type="text" name="q" id="searchQuery" class="form-control filter-control" 
                                           placeholder="Ketik NIM / Nama mahasiswa..." 
                                           value="<?= html_escape($filter['search']) ?>">
                                    <button class="btn filter-btn-search" type="submit" title="Cari">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Tombol Submit / Terapkan (Sejajar Sempurna Secara Horizontal) -->
                            <div class="filter-col col-xl-2 col-lg-2 col-md-4 col-12">
                                <label class="filter-label d-none d-md-block" style="visibility: hidden; margin-bottom: 7px;">&nbsp;</label>
                                <button type="submit" class="btn filter-btn-submit">
                                    <i class="fa fa-sliders mr-2"></i> Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Data Mahasiswa -->
                    <div class="mhs-table-card">
                        <div class="mhs-table-header">
                            <div>
                                <h6 style="font-weight: 800; color: #0f172a; margin: 0 0 2px 0;">
                                    Daftar Mahasiswa
                                </h6>
                                <small class="text-muted">
                                    Menampilkan <strong><?= count($students) ?></strong> mahasiswa
                                    <?php if (!empty($filter['prodi'])): ?>
                                        pada Program Studi <strong><?= html_escape($filter['prodi']) ?></strong>
                                    <?php endif; ?>
                                    <?php if (!empty($filter['semester'])): ?>
                                        Semester <strong><?= (int)$filter['semester'] ?></strong>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="<?= base_url('admin/akademik') ?>" class="btn btn-sm btn-outline-primary" style="font-size: 12px; font-weight: 600; border-radius: 6px;">
                                    <i class="fa fa-graduation-cap mr-1"></i> Kelola Dosen Wali di Operasional
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-mhs">
                                <thead>
                                    <tr>
                                        <th style="width: 45px; text-align: center;">No</th>
                                        <th style="min-width: 230px;">Mahasiswa</th>
                                        <th style="text-align: center; width: 85px;">NIM</th>
                                        <th style="min-width: 200px;">Program Studi &amp; Fakultas</th>
                                        <th style="text-align: center; width: 110px;">Semester</th>
                                        <th style="text-align: center; width: 95px;">Angkatan</th>
                                        <th style="min-width: 200px;">Dosen Wali</th>
                                        <th style="text-align: center; width: 100px;">Status</th>
                                        <th style="text-align: center; width: 125px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div style="max-width: 360px; margin: 0 auto; color: #64748b;">
                                                    <i class="fa fa-folder-open-o" style="font-size: 42px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                                    <h6 style="font-weight: 700; color: #334155;">Tidak Ada Data Mahasiswa</h6>
                                                    <p style="font-size: 13px; margin-bottom: 14px;">
                                                        Tidak ditemukan mahasiswa yang sesuai dengan filter atau kriteria pencarian yang Anda masukkan.
                                                    </p>
                                                    <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-primary" style="border-radius: 6px; font-weight: 600;">
                                                        <i class="fa fa-refresh mr-1"></i> Tampilkan Semua Mahasiswa
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php 
                                        $no = 1;
                                        foreach ($students as $mhs): 
                                            // Tentukan class warna badge prodi
                                            $prodi_class = 'badge-prodi-other';
                                            if (stripos($mhs->prodi, 'Informatika') !== false) {
                                                $prodi_class = 'badge-prodi-ti';
                                            } elseif (stripos($mhs->prodi, 'Sistem Informasi') !== false) {
                                                $prodi_class = 'badge-prodi-si';
                                            } elseif (stripos($mhs->prodi, 'Sains Data') !== false) {
                                                $prodi_class = 'badge-prodi-ds';
                                            } elseif (stripos($mhs->prodi, 'Bisnis') !== false || stripos($mhs->prodi, 'Manajemen') !== false) {
                                                $prodi_class = 'badge-prodi-bd';
                                            }

                                            // Inisial untuk avatar jika foto kosong
                                            $initials = '';
                                            $words = explode(' ', trim($mhs->nama_lengkap));
                                            foreach (array_slice($words, 0, 2) as $w) {
                                                $initials .= strtoupper(substr($w, 0, 1));
                                            }
                                        ?>
                                            <tr>
                                                <td style="text-align: center; color: #64748b; font-weight: 600;">
                                                    <?= $no++ ?>
                                                </td>

                                                <!-- Identitas Mahasiswa -->
                                                <td style="min-width: 230px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mhs-avatar-wrap mr-3">
                                                            <?php if (!empty($mhs->foto)): ?>
                                                                <img src="<?= base_url('assets/images/' . $mhs->foto) ?>" 
                                                                     alt="<?= html_escape($mhs->nama_lengkap) ?>" 
                                                                     class="mhs-avatar"
                                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                                <div class="mhs-avatar-initials" style="display:none;"><?= $initials ?: 'M' ?></div>
                                                            <?php else: ?>
                                                                <div class="mhs-avatar-initials"><?= $initials ?: 'M' ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div style="font-weight: 700; color: #0f172a; font-size: 14px; white-space: nowrap;">
                                                                <?= html_escape($mhs->nama_lengkap) ?>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted" style="font-size: 12px; margin-top: 2px; white-space: nowrap;">
                                                                <i class="fa fa-envelope-o mr-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                                <span class="text-truncate" style="max-width: 190px;" title="<?= html_escape($mhs->email ?: '-') ?>"><?= html_escape($mhs->email ?: '-') ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- NIM -->
                                                <td style="text-align: center;">
                                                    <span class="badge-nim">
                                                        <?= html_escape($mhs->nim) ?>
                                                    </span>
                                                </td>

                                                <!-- Prodi & Fakultas -->
                                                <td>
                                                    <span class="badge-prodi <?= $prodi_class ?>">
                                                        <i class="fa fa-graduation-cap mr-1"></i> <?= html_escape($mhs->prodi) ?>
                                                    </span>
                                                    <div style="font-size: 11.5px; color: #64748b; margin-top: 3px;">
                                                        <?= html_escape($mhs->fakultas ?: 'Fakultas Ilmu Komputer') ?>
                                                    </div>
                                                </td>

                                                <!-- Semester -->
                                                <td style="text-align: center;">
                                                    <span class="badge-semester">
                                                        Semester <?= (int)$mhs->semester ?>
                                                    </span>
                                                </td>

                                                <!-- Angkatan -->
                                                <td style="text-align: center;">
                                                    <span style="font-weight: 600; color: #334155; font-size: 13px;">
                                                        <?= html_escape($mhs->angkatan ?: '2023') ?>
                                                    </span>
                                                </td>

                                                <!-- Dosen Wali -->
                                                <td>
                                                    <?php if (!empty($mhs->nama_dosen_wali)): ?>
                                                        <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                                            <?= html_escape($mhs->nama_dosen_wali) ?>
                                                        </div>
                                                        <?php if (!empty($mhs->nidn_dosen_wali)): ?>
                                                            <small class="text-muted">NIDN: <?= html_escape($mhs->nidn_dosen_wali) ?></small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-light text-muted border" style="font-size: 11px;">
                                                            <i class="fa fa-minus-circle mr-1"></i> Belum Ditetapkan
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Status Akademik -->
                                                <td style="text-align: center;">
                                                    <?php 
                                                        $status = $mhs->status_akademik ?? 'Aktif';
                                                        if ($status === 'Aktif'):
                                                    ?>
                                                        <span class="badge-status-aktif"><i class="fa fa-check mr-1"></i> Aktif</span>
                                                    <?php elseif ($status === 'Cuti'): ?>
                                                        <span class="badge-status-cuti"><i class="fa fa-pause mr-1"></i> Cuti</span>
                                                    <?php elseif ($status === 'Lulus'): ?>
                                                        <span class="badge badge-info" style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:12px;"><i class="fa fa-graduation-cap mr-1"></i> Lulus</span>
                                                    <?php else: ?>
                                                        <span class="badge-status-nonaktif"><i class="fa fa-times mr-1"></i> <?= html_escape($status) ?></span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Aksi (Detail & Edit Cepat) -->
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                                        <button type="button" 
                                                                class="btn-action-view" 
                                                                onclick="bukaDetailMhs(<?= (int)$mhs->id ?>)"
                                                                title="Lihat Detail Profil">
                                                            <i class="fa fa-eye"></i> Detail
                                                        </button>
                                                        <button type="button" 
                                                                class="btn-action-edit" 
                                                                onclick="bukaEditMhs(<?= htmlspecialchars(json_encode($mhs), ENT_QUOTES, 'UTF-8') ?>)"
                                                                title="Ubah Semester / Prodi / Status">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mhs-table-card">
                        <div class="mhs-table-header">
                            <div>
                                <h6 style="font-weight: 800; color: #0f172a; margin: 0 0 2px 0;">
                                    Persetujuan Semester Pendek
                                </h6>
                                <small class="text-muted">Persetujuan otomatis membuat tagihan pada menu Keuangan mahasiswa.</small>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-mhs">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Fakultas / Program Studi</th>
                                        <th style="text-align: center;">Semester</th>
                                        <th>Status Semester Pendek</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($short_semester_students)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada data mahasiswa.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($short_semester_students as $student): ?>
                                            <?php $short_semester_status = $student->status_semester_pendek ?: 'BELUM_DIPROSES'; ?>
                                            <tr>
                                                <td>
                                                    <?= html_escape($student->nama_lengkap) ?><br>
                                                    <small><?= html_escape($student->nim) ?></small>
                                                </td>
                                                <td>
                                                    <?= html_escape($student->fakultas ?: '-') ?><br>
                                                    <?= html_escape($student->prodi ?: '-') ?>
                                                </td>
                                                <td style="text-align: center;"><?= (int)$student->semester ?></td>
                                                <td>
                                                    <?php if ($short_semester_status === 'DISETUJUI'): ?>
                                                        <span class="badge badge-success">Disetujui</span>
                                                    <?php elseif ($short_semester_status === 'DITOLAK'): ?>
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Belum diproses</span>
                                                    <?php endif; ?>
                                                    <?php if ($student->semester_pendek_diproses_at): ?>
                                                        <br><small><?= html_escape($student->semester_pendek_diproses_at) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <?php if ($short_semester_status !== 'DISETUJUI'): ?>
                                                        <?= form_open('admin/pengguna/proses-semester-pendek', ['class' => 'd-inline']) ?>
                                                            <input type="hidden" name="mahasiswa_id" value="<?= (int)$student->id ?>">
                                                            <input type="hidden" name="keputusan" value="DISETUJUI">
                                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                                        <?= form_close() ?>
                                                    <?php endif; ?>
                                                    <?php if ($short_semester_status !== 'DISETUJUI' && $short_semester_status !== 'DITOLAK'): ?>
                                                        <?= form_open('admin/pengguna/proses-semester-pendek', ['class' => 'd-inline']) ?>
                                                            <input type="hidden" name="mahasiswa_id" value="<?= (int)$student->id ?>">
                                                            <input type="hidden" name="keputusan" value="DITOLAK">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                                                        <?= form_close() ?>
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
<!-- Modal Detail Mahasiswa -->
<div class="modal fade" id="modalDetailMhs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header modal-detail-header d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold" style="color: #0f172a; font-size: 16px;">
                    <i class="fa fa-id-card-o text-primary mr-2"></i> Detail Informasi Mahasiswa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalDetailContent">
                <!-- Data detail diisi via JavaScript -->
                <div class="text-center py-4">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2 text-muted">Memuat data mahasiswa...</p>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-dismiss="modal" style="border-radius: 6px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Cepat Status & Semester Mahasiswa -->
<div class="modal fade" id="modalEditMhs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header modal-detail-header d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold" style="color: #0f172a; font-size: 16px;">
                    <i class="fa fa-pencil-square-o text-success mr-2"></i> Ubah Data Akademik Mahasiswa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/pengguna/update_akademik') ?>
                <input type="hidden" name="id" id="editMhsId">
                <input type="hidden" name="redirect_query" value="<?= html_escape($_SERVER['QUERY_STRING'] ?? '') ?>">

                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3">
                        <strong id="editMhsNama" style="color: #0f172a; font-size: 14.5px;">Nama Mahasiswa</strong>
                        <div class="text-muted" style="font-size: 12.5px;">NIM: <span id="editMhsNim" class="font-weight-bold"></span></div>
                    </div>

                    <div class="form-group">
                        <label for="editMhsProdi" class="font-weight-bold" style="font-size: 13px;">Program Studi</label>
                        <select name="prodi" id="editMhsProdi" class="form-control" required style="border-radius: 6px;">
                            <?php foreach ($prodi_list as $p): ?>
                                <option value="<?= html_escape($p) ?>"><?= html_escape($p) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editMhsSemester" class="font-weight-bold" style="font-size: 13px;">Semester</label>
                            <select name="semester" id="editMhsSemester" class="form-control" required style="border-radius: 6px;">
                                <?php for ($i = 1; $i <= 14; $i++): ?>
                                    <option value="<?= $i ?>">Semester <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editMhsStatus" class="font-weight-bold" style="font-size: 13px;">Status Akademik</label>
                            <select name="status_akademik" id="editMhsStatus" class="form-control" required style="border-radius: 6px;">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Lulus">Lulus</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editMhsAngkatan" class="font-weight-bold" style="font-size: 13px;">Tahun Angkatan</label>
                            <input type="text" name="angkatan" id="editMhsAngkatan" class="form-control" maxlength="4" style="border-radius: 6px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editMhsFakultas" class="font-weight-bold" style="font-size: 13px;">Fakultas</label>
                            <input type="text" name="fakultas" id="editMhsFakultas" class="form-control" style="border-radius: 6px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm font-weight-bold" style="border-radius: 6px;">
                        <i class="fa fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk membuka Modal Detail Mahasiswa
    function bukaDetailMhs(id) {
        $('#modalDetailMhs').modal('show');
        $('#modalDetailContent').html(`
            <div class="text-center py-4">
                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Memuat data mahasiswa...</p>
            </div>
        `);

        $.ajax({
            url: '<?= base_url("admin/pengguna/ajax_detail") ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var m = res.data;
                    var avatarHtml = '';
                    if (m.foto) {
                        avatarHtml = `<img src="<?= base_url('assets/images/') ?>${m.foto}" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 2.5px solid #2563eb;">`;
                    } else {
                        var inisial = m.nama_lengkap.substring(0, 2).toUpperCase();
                        avatarHtml = `<div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1e40af); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800;">${inisial}</div>`;
                    }

                    var html = `
                        <div class="d-flex align-items-center p-3 mb-3 bg-light rounded" style="border: 1px solid #e2e8f0;">
                            <div class="mr-3">
                                ${avatarHtml}
                            </div>
                            <div>
                                <h5 class="mb-1 font-weight-bold" style="color: #0f172a;">${m.nama_lengkap || '-'}</h5>
                                <div class="badge-nim mb-1 d-inline-block">NIM: ${m.nim || '-'}</div>
                                <div style="font-size: 13px; color: #475569;">
                                    <span class="badge badge-primary mr-1">${m.prodi || 'Teknik Informatika'}</span>
                                    <span>Semester ${m.semester || 1}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-primary mb-2" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa fa-user mr-1"></i> Data Identitas
                                </h6>
                                <div class="detail-row">
                                    <div class="detail-label">Nama Lengkap</div>
                                    <div class="detail-val">${m.nama_lengkap || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">NIM</div>
                                    <div class="detail-val font-monospace">${m.nim || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email Kampus</div>
                                    <div class="detail-val">${m.email || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Jenis Kelamin</div>
                                    <div class="detail-val">${m.jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Nomor Telepon</div>
                                    <div class="detail-val">${m.telepon || '-'}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-primary mb-2" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa fa-graduation-cap mr-1"></i> Data Akademik
                                </h6>
                                <div class="detail-row">
                                    <div class="detail-label">Program Studi</div>
                                    <div class="detail-val">${m.prodi || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Fakultas</div>
                                    <div class="detail-val">${m.fakultas || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Semester Aktif</div>
                                    <div class="detail-val">Semester ${m.semester || 1}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Tahun Angkatan</div>
                                    <div class="detail-val">${m.angkatan || '-'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Status Akademik</div>
                                    <div class="detail-val">
                                        <span class="badge ${m.status_akademik === 'Aktif' ? 'badge-success' : 'badge-warning'}">${m.status_akademik || 'Aktif'}</span>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Dosen Wali</div>
                                    <div class="detail-val">${m.nama_dosen_wali ? m.nama_dosen_wali : '<span class="text-muted font-italic">Belum Ditetapkan</span>'}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#modalDetailContent').html(html);
                } else {
                    $('#modalDetailContent').html('<div class="alert alert-danger">Gagal memuat data mahasiswa.</div>');
                }
            },
            error: function() {
                $('#modalDetailContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat server.</div>');
            }
        });
    }

    // Fungsi untuk membuka Modal Edit Cepat Mahasiswa
    function bukaEditMhs(mhs) {
        $('#editMhsId').val(mhs.id);
        $('#editMhsNama').text(mhs.nama_lengkap || '-');
        $('#editMhsNim').text(mhs.nim || '-');
        $('#editMhsProdi').val(mhs.prodi || '');
        $('#editMhsSemester').val(mhs.semester || 1);
        $('#editMhsStatus').val(mhs.status_akademik || 'Aktif');
        $('#editMhsAngkatan').val(mhs.angkatan || '');
        $('#editMhsFakultas').val(mhs.fakultas || 'Fakultas Ilmu Komputer');
        $('#modalEditMhs').modal('show');
    }
</script>
