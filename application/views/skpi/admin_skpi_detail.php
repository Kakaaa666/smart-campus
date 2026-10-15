<style>
    /* Styling Khusus Detail Validasi SKPI Mahasiswa per 10 Kategori */
    .admin-detail-header-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
    }
    .mhs-detail-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 22px;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
        flex-shrink: 0;
    }
    .kategori-quick-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }
    .kategori-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    .kategori-pill:hover, .kategori-pill.active {
        background: #0284c7;
        color: #ffffff !important;
        border-color: #0284c7;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }
    .kategori-pill .badge-count {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 11px;
    }
    .kategori-pill:hover .badge-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Kategori Card Panels */
    .skpi-category-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        overflow: visible !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .skpi-category-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .skpi-category-card .table-responsive {
        overflow: visible !important;
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
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
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
        flex-shrink: 0;
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
    .table-skpi-detail th {
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }
    .table-skpi-detail td {
        vertical-align: middle;
        padding: 12px 14px;
        font-size: 13px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
    }

    /* Dropdown Status Button */
    .btn-status-dropdown {
        min-width: 120px;
        font-size: 11.5px;
        font-weight: 700;
        border-radius: 8px;
        padding: 6px 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        transition: all 0.2s ease-in-out;
    }
    .btn-status-dropdown:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .btn-status-dropdown:after {
        margin-left: 6px;
    }
    .dropdown-menu-status {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        min-width: 180px;
        padding: 6px 0;
        z-index: 1060;
    }
    .item-status-choice {
        font-size: 12.5px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.15s ease;
    }
    .item-status-choice:hover {
        background-color: #f1f5f9;
    }
    .item-status-choice.active {
        background-color: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
    }

    /* Floating Toast */
    .toast-skpi-alert {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        z-index: 99999;
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .toast-skpi-alert.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Tombol Kembali -->
                    <div class="mb-3">
                        <a href="<?= base_url('admin/skpi') ?>" class="btn btn-outline-secondary btn-sm px-3 font-weight-bold" style="border-radius: 8px;">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Mahasiswa
                        </a>
                    </div>

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

                    <!-- Card Header Profil Mahasiswa & Status Rekap -->
                    <div class="admin-detail-header-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 16px;">
                            <div class="d-flex align-items-center">
                                <div class="mhs-detail-avatar mr-3">
                                    <?= strtoupper(substr($mahasiswa->nama_lengkap ?: 'M', 0, 1)) ?>
                                </div>
                                <div>
                                    <h4 class="font-weight-bold text-dark mb-1"><?= html_escape($mahasiswa->nama_lengkap) ?></h4>
                                    <div class="text-muted" style="font-size: 13px;">
                                        <span class="mr-2">NIM: <strong class="text-dark"><?= html_escape($mahasiswa->nim) ?></strong></span>
                                        <span class="mx-1">&bull;</span>
                                        <span class="mr-2"><?= html_escape($mahasiswa->prodi ?: '-') ?></span>
                                        <?php if (!empty($mahasiswa->semester)): ?>
                                            <span class="mx-1">&bull;</span>
                                            <span>Semester <?= html_escape($mahasiswa->semester) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Rekap Angka Mahasiswa Ini -->
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <div class="badge badge-warning text-dark px-3 py-2 font-weight-bold" style="font-size: 12px; border-radius: 8px;">
                                    <i class="fa fa-clock-o mr-1"></i> <span id="mhs-stat-waiting"><?= $summary['waiting'] ?></span> Belum Validasi
                                </div>
                                <div class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 12px; border-radius: 8px;">
                                    <i class="fa fa-check mr-1"></i> <span id="mhs-stat-valid"><?= $summary['valid'] ?></span> Telah Valid
                                </div>
                                <div class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 12px; border-radius: 8px;">
                                    <i class="fa fa-times mr-1"></i> <span id="mhs-stat-invalid"><?= $summary['invalid'] ?></span> Tidak Valid
                                </div>
                                <div class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 12px; border-radius: 8px;">
                                    <i class="fa fa-folder-open mr-1"></i> <span id="mhs-stat-total"><?= $summary['total'] ?></span> Total Pengajuan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigasi Cepat 10 Kategori SKPI -->
                    <div class="kategori-quick-nav">
                        <?php foreach ($kategori_list as $kId => $kInfo): ?>
                            <a href="#kategori-<?= $kId ?>" class="kategori-pill">
                                <i class="<?= $kInfo['icon'] ?>" style="color: <?= $kInfo['color'] ?>;"></i>
                                <span><?= $kId ?>. <?= html_escape($kInfo['nama']) ?></span>
                                <span class="badge-count" id="nav-count-<?= $kId ?>"><?= $kategori_data[$kId]['count'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- DAFTAR 10 KATEGORI SKPI & KEGIATAN MAHASISWA -->
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
                                    <span class="badge badge-light border px-3 py-2 text-dark font-weight-normal" style="font-size: 12px; border-radius: 8px;">
                                        Total: <strong id="kat-count-<?= $kId ?>"><?= $count ?></strong> kegiatan 
                                        &bull; Valid: <strong class="text-success" id="kat-valid-<?= $kId ?>"><?= $kat_data['valid'] ?></strong>
                                        &bull; Menunggu: <strong class="text-warning" id="kat-waiting-<?= $kId ?>"><?= $kat_data['waiting'] ?></strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Tabel Kegiatan Pada Kategori Ini -->
                            <div class="table-responsive">
                                <table class="table table-hover table-skpi-detail mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%;" class="text-center">#</th>
                                            <th style="width: 32%;">Nama Kegiatan</th>
                                            <th style="width: 26%;">Penyelenggara &amp; Tingkat</th>
                                            <th style="width: 16%;">No. Sertifikat</th>
                                            <th style="width: 10%;" class="text-center">Berkas Bukti</th>
                                            <th style="width: 12%;" class="text-center">Status Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-kat-<?= $kId ?>">
                                        <?php if (!empty($items)): ?>
                                            <?php $no = 1; foreach ($items as $item): ?>
                                                <tr id="row-submission-<?= $item->id ?>">
                                                    <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                                    <td>
                                                        <div class="font-weight-bold text-dark" style="font-size: 13.5px;"><?= html_escape($item->nama_kegiatan) ?></div>
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
                                                        <div class="text-dark font-weight-semibold"><?= html_escape($item->penyelenggara ?: '-') ?></div>
                                                        <span class="badge badge-light border text-secondary" style="font-size: 11px;">
                                                            <i class="fa fa-globe mr-1"></i><?= html_escape($item->tingkat ?: 'Lokal / Kampus') ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($item->no_sertifikat)): ?>
                                                            <span class="text-dark font-weight-semibold" style="font-size: 12px;"><?= html_escape($item->no_sertifikat) ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted" style="font-size: 11px;">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($item->file_bukti)): ?>
                                                            <a href="<?= base_url('uploads/skpi/' . $item->file_bukti) ?>" target="_blank" class="btn btn-outline-info btn-sm" style="padding: 5px 10px; font-size: 11.5px; border-radius: 6px;">
                                                                <i class="fa fa-file-pdf-o"></i> Lihat
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted" style="font-size: 11px;">Tidak Ada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <!-- DROPDOWN STATUS LANGSUNG DENGAN KETERANGAN TIDAK VALID -->
                                                    <td class="text-center cell-status-container">
                                                        <div class="dropdown d-inline-block">
                                                            <button class="btn btn-sm dropdown-toggle font-weight-bold btn-status-dropdown btn-status-<?= $item->id ?> <?= ($item->status === 'YES' ? 'btn-success' : ($item->status === 'NO' ? 'btn-danger' : 'btn-warning text-dark')) ?>" 
                                                                    type="button" 
                                                                    id="dropdownStatus<?= $item->id ?>" 
                                                                    data-toggle="dropdown" 
                                                                    aria-haspopup="true" 
                                                                    aria-expanded="false">
                                                                <?php if ($item->status === 'YES'): ?>
                                                                    <i class="fa fa-check mr-1"></i> Valid
                                                                <?php elseif ($item->status === 'NO'): ?>
                                                                    <i class="fa fa-times mr-1"></i> Tidak Valid
                                                                <?php else: ?>
                                                                    <i class="fa fa-clock-o mr-1"></i> Menunggu
                                                                <?php endif; ?>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-status shadow" aria-labelledby="dropdownStatus<?= $item->id ?>">
                                                                <div class="dropdown-header font-weight-bold text-uppercase py-1 px-3 text-muted" style="font-size: 10px; letter-spacing: 0.5px;">Pilih Status:</div>
                                                                <a class="dropdown-item item-status-choice <?= ($item->status === 'YES' ? 'active' : '') ?>" 
                                                                   href="javascript:void(0)" 
                                                                   data-id="<?= $item->id ?>" 
                                                                   data-kat-id="<?= $kId ?>" 
                                                                   data-status="YES">
                                                                    <i class="fa fa-check text-success mr-2"></i> Valid (Disetujui)
                                                                </a>
                                                                <a class="dropdown-item item-status-choice <?= ($item->status === 'NO' ? 'active' : '') ?>" 
                                                                   href="javascript:void(0)" 
                                                                   data-id="<?= $item->id ?>" 
                                                                   data-kat-id="<?= $kId ?>" 
                                                                   data-status="NO">
                                                                    <i class="fa fa-times text-danger mr-2"></i> Tidak Valid (Ditolak)...
                                                                </a>
                                                                <div class="dropdown-divider my-1"></div>
                                                                <a class="dropdown-item item-status-choice <?= ($item->status === 'WAITING' ? 'active' : '') ?>" 
                                                                   href="javascript:void(0)" 
                                                                   data-id="<?= $item->id ?>" 
                                                                   data-kat-id="<?= $kId ?>" 
                                                                   data-status="WAITING">
                                                                    <i class="fa fa-clock-o text-warning mr-2"></i> Menunggu Validasi
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <!-- Tampilan Keterangan / Alasan Tidak Valid -->
                                                        <div class="wrap-catatan mt-1" id="wrap-catatan-<?= $item->id ?>" style="<?= ($item->status === 'NO' && !empty($item->catatan_validasi)) ? '' : 'display: none;' ?>">
                                                            <div class="badge-catatan" 
                                                                 style="font-size: 11px; max-width: 170px; margin: 4px auto 0; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 8px; text-align: left; line-height: 1.35; cursor: pointer;" 
                                                                 title="Klik untuk mengubah keterangan alasan tidak valid">
                                                                <i class="fa fa-exclamation-circle mr-1"></i><strong>Alasan:</strong> <span class="catatan-text"><?= html_escape($item->catatan_validasi) ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3" style="font-size: 13px;">
                                                    Belum ada pengajuan kegiatan pada kategori ini.
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi menampilkan notifikasi floating toast
    function showToastSuccess(message) {
        var existing = $('.toast-skpi-alert');
        if (existing.length) existing.remove();

        var toast = $('<div class="toast-skpi-alert">' +
            '<i class="fa fa-check-circle text-success" style="font-size: 16px;"></i>' +
            '<span>' + message + '</span>' +
        '</div>');

        $('body').append(toast);
        setTimeout(function() { toast.addClass('show'); }, 20);
        setTimeout(function() {
            toast.removeClass('show');
            setTimeout(function() { toast.remove(); }, 300);
        }, 2500);
    }

    // Fungsi menghitung ulang status per-kategori di halaman
    function refreshCategoryHeaderCounters(katId) {
        if (!katId) return;
        var tableCard = $('#kategori-' + katId);
        var validCount = tableCard.find('.btn-status-dropdown.btn-success').length;
        var waitingCount = tableCard.find('.btn-status-dropdown.btn-warning').length;
        $('#kat-valid-' + katId).text(validCount);
        $('#kat-waiting-' + katId).text(waitingCount);
    }

    // Fungsi eksekusi pengiriman data status ke server via AJAX
    function executeStatusUpdate(itemId, katId, newStatus, catatan, dropdownContainer, btn) {
        var originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>...');

        $.ajax({
            url: '<?= base_url("admin/skpi/update_status") ?>',
            type: 'POST',
            data: {
                id: itemId,
                status: newStatus,
                catatan_validasi: catatan || ''
            },
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false);

                if (res.status) {
                    // Update tampilan dan warna tombol dropdown
                    btn.removeClass('btn-success btn-danger btn-warning text-dark');
                    if (newStatus === 'YES') {
                        btn.addClass('btn-success').html('<i class="fa fa-check mr-1"></i> Valid');
                    } else if (newStatus === 'NO') {
                        btn.addClass('btn-danger').html('<i class="fa fa-times mr-1"></i> Tidak Valid');
                    } else {
                        btn.addClass('btn-warning text-dark').html('<i class="fa fa-clock-o mr-1"></i> Menunggu');
                    }

                    // Update active state menu dropdown
                    dropdownContainer.find('.item-status-choice').removeClass('active font-weight-bold');
                    dropdownContainer.find('.item-status-choice[data-status="' + newStatus + '"]').addClass('active font-weight-bold');

                    // Tampilkan atau sembunyikan kotak keterangan alasan
                    var wrapCatatan = $('#wrap-catatan-' + itemId);
                    var textCatatan = (res.catatan_validasi || catatan || '').trim();
                    if (newStatus === 'NO' && textCatatan !== '') {
                        wrapCatatan.find('.catatan-text').text(textCatatan);
                        wrapCatatan.show();
                    } else {
                        wrapCatatan.hide();
                    }

                    // Update rekap status mahasiswa pada banner atas
                    if (res.summary) {
                        $('#mhs-stat-waiting').text(res.summary.waiting);
                        $('#mhs-stat-valid').text(res.summary.valid);
                        $('#mhs-stat-invalid').text(res.summary.invalid);
                        $('#mhs-stat-total').text(res.summary.total);
                    }

                    // Update angka per kategori
                    refreshCategoryHeaderCounters(katId);

                    // Tampilkan toast notifikasi berhasil
                    showToastSuccess(res.message);
                } else {
                    btn.html(originalHtml);
                    alert(res.message || 'Gagal mengubah status validasi.');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(originalHtml);
                alert('Terjadi kesalahan jaringan atau server.');
            }
        });
    }

    // Tangani pemilihan status langsung dari Dropdown (Tanpa Modal)
    $(document).on('click', '.item-status-choice', function(e) {
        e.preventDefault();

        var itemId = $(this).data('id');
        var katId = $(this).data('kat-id');
        var newStatus = $(this).data('status');
        var dropdownContainer = $(this).closest('.dropdown');
        var btn = dropdownContainer.find('.btn-status-dropdown');

        if (newStatus === 'NO') {
            // Minta input keterangan alasan Tidak Valid
            var currentCatatan = $('#wrap-catatan-' + itemId).find('.catatan-text').text().trim() || '';
            
            if (window.SCDialog && typeof window.SCDialog.prompt === 'function') {
                window.SCDialog.prompt('Silakan masukkan keterangan / alasan mengapa pengajuan kegiatan ini Tidak Valid:', {
                    title: 'Keterangan Tidak Valid',
                    defaultValue: currentCatatan,
                    placeholder: 'Contoh: Berkas sertifikat buram, nomor SK tidak sesuai...',
                    required: true,
                    acceptText: 'Simpan Tidak Valid',
                    cancelText: 'Batal'
                }).then(function(keterangan) {
                    if (keterangan === null) {
                        // User membatalkan dialog
                        return;
                    }
                    if (keterangan.trim() === '') {
                        keterangan = 'Berkas sertifikat belum memenuhi persyaratan validasi.';
                    }
                    executeStatusUpdate(itemId, katId, newStatus, keterangan, dropdownContainer, btn);
                });
            } else {
                var keterangan = window.prompt('Silakan masukkan keterangan / alasan mengapa pengajuan kegiatan ini Tidak Valid:', currentCatatan);
                if (keterangan === null) return;
                if (keterangan.trim() === '') {
                    keterangan = 'Berkas sertifikat belum memenuhi persyaratan validasi.';
                }
                executeStatusUpdate(itemId, katId, newStatus, keterangan, dropdownContainer, btn);
            }
        } else {
            // Untuk status YES atau WAITING, langsung update tanpa catatan
            executeStatusUpdate(itemId, katId, newStatus, '', dropdownContainer, btn);
        }
    });

    // Klik pada badge catatan untuk memperbarui keterangan tanpa ubah status
    $(document).on('click', '.badge-catatan', function() {
        var wrap = $(this).closest('.wrap-catatan');
        var itemId = wrap.attr('id').replace('wrap-catatan-', '');
        var katId = $(this).closest('.skpi-category-card').attr('id').replace('kategori-', '');
        var currentCatatan = wrap.find('.catatan-text').text().trim() || '';
        var dropdownContainer = wrap.siblings('.dropdown');
        var btn = dropdownContainer.find('.btn-status-dropdown');

        if (window.SCDialog && typeof window.SCDialog.prompt === 'function') {
            window.SCDialog.prompt('Perbarui keterangan / alasan Tidak Valid:', {
                title: 'Ubah Keterangan Tidak Valid',
                defaultValue: currentCatatan,
                required: true,
                acceptText: 'Perbarui',
                cancelText: 'Batal'
            }).then(function(keterangan) {
                if (keterangan === null) return;
                executeStatusUpdate(itemId, katId, 'NO', keterangan, dropdownContainer, btn);
            });
        } else {
            var keterangan = window.prompt('Perbarui keterangan / alasan Tidak Valid:', currentCatatan);
            if (keterangan === null) return;
            executeStatusUpdate(itemId, katId, 'NO', keterangan, dropdownContainer, btn);
        }
    });
});
</script>
