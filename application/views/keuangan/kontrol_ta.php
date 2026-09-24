<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Custom Styling Khusus Kontrol Akses Tugas Akhir -->
                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

                .ta-container {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .ta-card {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                    margin-bottom: 24px;
                    overflow: hidden;
                }

                .ta-stat-card {
                    background: #ffffff;
                    border-radius: 14px;
                    border: 1px solid #e2e8f0;
                    padding: 20px 22px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
                }

                .table-ta thead th {
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

                .table-ta tbody td {
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

                .badge-ta-open {
                    background: #d1fae5;
                    color: #047857;
                    border: 1.5px solid #6ee7b7;
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .badge-ta-closed {
                    background: #f1f5f9;
                    color: #64748b;
                    border: 1.5px solid #cbd5e1;
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .btn-toggle-open {
                    background: linear-gradient(135deg, #10b981, #059669);
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 700;
                    padding: 7px 14px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .btn-toggle-open:hover {
                    background: #047857;
                    color: #fff;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                }

                .btn-toggle-close {
                    background: #fff;
                    color: #dc2626;
                    border: 1.5px solid #fca5a5;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 600;
                    padding: 7px 14px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .btn-toggle-close:hover {
                    background: #fee2e2;
                    color: #b91c1c;
                }
                </style>

                <div class="ta-container">

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
                    <div class="ta-card" style="border-left: 5px solid #8b5cf6;">
                        <div style="padding: 22px 26px;">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg,#8b5cf6,#6d28d9); display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(139, 92, 246, 0.25);">
                                            <i class="fa fa-graduation-cap" style="font-size: 24px; color: #ffffff;"></i>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0 0 4px; font-weight: 800; color: #0f172a; font-size: 20px;">
                                                Kontrol Akses Tugas Akhir Per-Mahasiswa
                                            </h4>
                                            <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                                                Atur izin pembayaran biaya semester akhir &amp; tugas akhir secara perorangan sesuai kesiapan studi masing-masing mahasiswa.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                                    <!-- Aksi Massal (Bulk Actions) -->
                                    <div class="btn-group" role="group">
                                        <form id="form-bulk-open" method="POST" action="<?= base_url('keuangan/toggle_akses_ta_bulk') ?>" style="display:inline;">
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="semester_min" value="5">
                                            <button type="button" onclick="konfirmasiBulk('open')" class="btn btn-sm btn-success mr-2" style="border-radius: 8px; font-weight: 700; padding: 9px 15px;">
                                                <i class="fa fa-unlock mr-1"></i>Buka Semua Sem. Akhir
                                            </button>
                                        </form>
                                        <form id="form-bulk-close" method="POST" action="<?= base_url('keuangan/toggle_akses_ta_bulk') ?>" style="display:inline;">
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="semester_min" value="5">
                                            <button type="button" onclick="konfirmasiBulk('close')" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600; padding: 9px 15px;">
                                                <i class="fa fa-lock mr-1"></i>Tutup Semua
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Statistik -->
                    <?php
                        $count_total   = count($daftar_mahasiswa_ta);
                        $count_dibuka  = 0;
                        $count_ditutup = 0;
                        $count_akhir   = 0;
                        foreach ($daftar_mahasiswa_ta as $m) {
                            if ((int)$m->akses_ta === 1) $count_dibuka++;
                            else $count_ditutup++;
                            if ((int)$m->semester >= 5) $count_akhir++;
                        }
                    ?>
                    <div class="row mb-3">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="ta-stat-card">
                                <div class="text-muted" style="font-size: 13px; font-weight: 600;">Total Mahasiswa Terdaftar</div>
                                <h3 class="font-weight-bold mb-0 mt-1" style="color: #0f172a;"><?= $count_total ?></h3>
                                <small class="text-muted">Dalam sistem akademik</small>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="ta-stat-card" style="border-left: 4px solid #10b981;">
                                <div class="text-muted" style="font-size: 13px; font-weight: 600;">Akses Tugas Akhir DIBUKA</div>
                                <h3 class="font-weight-bold mb-0 mt-1" style="color: #059669;" id="stat-count-dibuka"><?= $count_dibuka ?></h3>
                                <small class="text-success font-weight-bold">Tagihan TA aktif ditagihkan</small>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="ta-stat-card" style="border-left: 4px solid #94a3b8;">
                                <div class="text-muted" style="font-size: 13px; font-weight: 600;">Akses Tugas Akhir DITUTUP</div>
                                <h3 class="font-weight-bold mb-0 mt-1" style="color: #475569;" id="stat-count-ditutup"><?= $count_ditutup ?></h3>
                                <small class="text-muted">Biaya TA tidak ditagihkan</small>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="ta-stat-card" style="border-left: 4px solid #8b5cf6;">
                                <div class="text-muted" style="font-size: 13px; font-weight: 600;">Mahasiswa Tingkat Akhir</div>
                                <h3 class="font-weight-bold mb-0 mt-1" style="color: #6d28d9;"><?= $count_akhir ?></h3>
                                <small class="text-purple font-weight-bold">Semester 5 ke atas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Filter & Live Search Bar -->
                    <div class="ta-card">
                        <div style="padding: 16px 24px;">
                            <form method="GET" action="<?= base_url('keuangan/kontrol_ta') ?>">
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
                                        <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Pencarian Nama / NIM</label>
                                        <div class="input-group">
                                            <input type="text" id="liveSearchTA" class="form-control form-control-sm" placeholder="Ketik nama mahasiswa atau NIM..." style="height: 38px; border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="background:#f8fafc; border-radius: 0 8px 8px 0;"><i class="fa fa-search text-muted"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Daftar Mahasiswa & Toggle Akses -->
                    <div class="ta-card">
                        <div class="table-responsive">
                            <table class="table table-ta mb-0" id="tableMahasiswaTA">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th>Mahasiswa</th>
                                        <th>Fakultas &amp; Program Studi</th>
                                        <th class="text-center">Semester</th>
                                        <th>Status Akses Tugas Akhir</th>
                                        <th>Status Tagihan TA</th>
                                        <th class="text-center" style="width: 170px;">Tindakan Buka / Tutup</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($daftar_mahasiswa_ta)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fa fa-user-times" style="font-size: 36px;"></i>
                                                <p class="mb-0 mt-2 font-weight-bold">Tidak ada data mahasiswa yang sesuai dengan filter.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($daftar_mahasiswa_ta as $i => $m): ?>
                                            <?php
                                                $is_open = ((int)$m->akses_ta === 1);
                                                $is_sem_akhir = ((int)$m->semester >= 5);
                                            ?>
                                            <tr class="searchable-row" id="row-mhs-<?= $m->id ?>" data-search="<?= strtolower($m->nama_lengkap . ' ' . $m->nim . ' ' . ($m->fakultas ?? '') . ' ' . ($m->prodi ?? '')) ?>">
                                                <td class="text-center font-weight-bold text-muted"><?= $i + 1 ?></td>
                                                <td>
                                                    <div style="font-weight: 700; font-size: 14px; color: #0f172a;"><?= htmlspecialchars($m->nama_lengkap) ?></div>
                                                    <div style="font-size: 12px; color: #64748b; font-family: monospace;">NIM: <?= htmlspecialchars($m->nim) ?></div>
                                                </td>
                                                <td>
                                                    <div class="badge-fakultas mb-1"><?= htmlspecialchars($m->fakultas ?: 'Fakultas Ilmu Komputer') ?></div>
                                                    <div><span class="badge-prodi"><?= htmlspecialchars($m->prodi ?: 'D3 Sistem Informasi') ?></span></div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-light px-2 py-1" style="font-size: 12px; border: 1px solid #cbd5e1; font-weight: 700;">
                                                        Sem. <?= htmlspecialchars($m->semester ?: '1') ?>
                                                    </span>
                                                    <?php if ($is_sem_akhir): ?>
                                                        <span class="badge badge-primary d-block mt-1" style="font-size: 9.5px;">Tingkat Akhir</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span id="badge-status-<?= $m->id ?>" class="<?= $is_open ? 'badge-ta-open' : 'badge-ta-closed' ?>">
                                                        <i class="fa <?= $is_open ? 'fa-check-circle' : 'fa-lock' ?>"></i>
                                                        <span id="text-status-<?= $m->id ?>"><?= $is_open ? 'Akses DIBUKA' : 'Akses DITUTUP' ?></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($is_open): ?>
                                                        <div style="font-size: 13px; font-weight: 700; color: #059669;">
                                                            Rp <?= number_format($m->ta_tagihan_nominal ?: 1250000, 0, ',', '.') ?>
                                                        </div>
                                                        <small class="badge badge-<?= ($m->ta_tagihan_status === 'LUNAS') ? 'success' : 'warning text-dark' ?>">
                                                            <?= $m->ta_tagihan_status ?: 'BELUM_BAYAR' ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size: 12.5px; font-style: italic;">
                                                            Tidak Ditagihkan
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                            id="btn-toggle-<?= $m->id ?>"
                                                            class="<?= $is_open ? 'btn-toggle-close' : 'btn-toggle-open' ?>"
                                                            onclick="toggleAksesTAMhs(<?= $m->id ?>, <?= $is_open ? 0 : 1 ?>, '<?= addslashes($m->nama_lengkap) ?>')">
                                                        <i class="fa <?= $is_open ? 'fa-lock' : 'fa-unlock' ?> mr-1"></i>
                                                        <span id="btn-text-<?= $m->id ?>"><?= $is_open ? 'Tutup Akses' : 'Buka Akses' ?></span>
                                                    </button>
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

<!-- Toast Real-time Notifikasi (Pengganti native alert) -->
<style>
@keyframes slideUpFade {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
<div id="realtimeToast" class="alert p-3" role="alert" style="position: fixed; bottom: 28px; right: 28px; z-index: 100000; min-width: 320px; max-width: 420px; border-radius: 14px; box-shadow: 0 12px 32px rgba(0,0,0,0.18); display: none; animation: slideUpFade 0.3s ease-out; color: #fff; border: none;">
    <div class="d-flex align-items-center">
        <div class="mr-3" style="font-size:26px;"><i id="toastIcon" class="fa fa-check-circle"></i></div>
        <div>
            <strong style="font-size:14px; display:block;" id="toastTitle">Judul</strong>
            <span style="font-size:12.5px; opacity:0.95;" id="toastBody">Pesan...</span>
        </div>
    </div>
    <button type="button" class="close text-white" onclick="document.getElementById('realtimeToast').style.display='none';" style="opacity:0.9; outline: none; border: none; background: transparent; padding: 0; position: absolute; right: 15px; top: 15px; font-size: 20px;">
        <span>&times;</span>
    </button>
</div>

<!-- Modal Konfirmasi Bulk Action -->
<div class="modal fade" id="modalConfirmBulk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-body p-4 text-center">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fa fa-exclamation-triangle" style="font-size: 32px; color: #f59e0b;"></i>
                </div>
                <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 12px;">Konfirmasi Tindakan</h4>
                <p style="font-size: 15px; color: #475569; margin-bottom: 24px;" id="modalConfirmMessage">
                    Apakah Anda yakin ingin melakukan tindakan ini?
                </p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-light mr-3" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 10px 24px; color: #475569; background: #f1f5f9; border: none;">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmAction" style="border-radius: 8px; font-weight: 600; padding: 10px 24px;">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Toggle Akses TA Real-time -->
<script>
function konfirmasiBulk(type) {
    var msg = type === 'open' 
        ? 'Buka akses Tugas Akhir untuk semua mahasiswa tingkat akhir (semester 5+)?'
        : 'Tutup akses Tugas Akhir untuk semua mahasiswa tingkat akhir?';
        
    document.getElementById('modalConfirmMessage').innerText = msg;
    var btnConfirm = document.getElementById('btnConfirmAction');
    
    if (type === 'open') {
        btnConfirm.className = 'btn btn-success';
        btnConfirm.innerHTML = '<i class="fa fa-check mr-1"></i>Ya, Buka Akses';
        btnConfirm.onclick = function() {
            btnConfirm.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Memproses...';
            btnConfirm.disabled = true;
            document.getElementById('form-bulk-open').submit();
        };
    } else {
        btnConfirm.className = 'btn btn-danger';
        btnConfirm.innerHTML = '<i class="fa fa-check mr-1"></i>Ya, Tutup Akses';
        btnConfirm.onclick = function() {
            btnConfirm.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Memproses...';
            btnConfirm.disabled = true;
            document.getElementById('form-bulk-close').submit();
        };
    }
    
    if (typeof $ !== 'undefined' && $('#modalConfirmBulk').modal) {
        $('#modalConfirmBulk').modal('show');
    } else {
        if(confirm(msg)) {
            if(type === 'open') document.getElementById('form-bulk-open').submit();
            else document.getElementById('form-bulk-close').submit();
        }
    }
}
function showNotification(type, title, message) {
    var toast = document.getElementById('realtimeToast');
    if (!toast) {
        alert(title + ': ' + message);
        return;
    }
    
    var icon = document.getElementById('toastIcon');
    var tTitle = document.getElementById('toastTitle');
    var tBody = document.getElementById('toastBody');
    
    if(type === 'success') {
        toast.style.background = '#10b981';
        icon.className = 'fa fa-check-circle';
    } else if(type === 'error') {
        toast.style.background = '#ef4444';
        icon.className = 'fa fa-times-circle';
    } else if(type === 'warning') {
        toast.style.background = '#f59e0b';
        icon.className = 'fa fa-exclamation-circle';
    }
    
    tTitle.textContent = title;
    tBody.textContent = message;
    
    toast.style.display = 'block';
    
    // Auto hide
    setTimeout(function() {
        toast.style.display = 'none';
    }, 4500);
}

function toggleAksesTAMhs(akunId, targetStatus, namaMhs) {
    var btn = document.getElementById('btn-toggle-' + akunId);
    var btnText = document.getElementById('btn-text-' + akunId);
    var badge = document.getElementById('badge-status-' + akunId);
    var textStatus = document.getElementById('text-status-' + akunId);

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Menyimpan...';
    }

    var formData = new FormData();
    formData.append('akun_id', akunId);
    formData.append('status', targetStatus);

    fetch('<?= base_url("keuangan/toggle_akses_ta_mhs") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            var isNowOpen = (data.status_int === 1);
            // Update UI tombol
            if (btn) {
                btn.className = isNowOpen ? 'btn-toggle-close' : 'btn-toggle-open';
                btn.onclick = function() { toggleAksesTAMhs(akunId, isNowOpen ? 0 : 1, namaMhs); };
                btn.innerHTML = '<i class="fa ' + (isNowOpen ? 'fa-lock' : 'fa-unlock') + ' mr-1"></i><span>' + (isNowOpen ? 'Tutup Akses' : 'Buka Akses') + '</span>';
            }
            // Update UI badge
            if (badge) {
                badge.className = isNowOpen ? 'badge-ta-open' : 'badge-ta-closed';
                badge.innerHTML = '<i class="fa ' + (isNowOpen ? 'fa-check-circle' : 'fa-lock') + ' mr-1"></i><span>' + (isNowOpen ? 'Akses DIBUKA' : 'Akses DITUTUP') + '</span>';
            }

            // Update statistik angka
            var elDibuka = document.getElementById('stat-count-dibuka');
            var elDitutup = document.getElementById('stat-count-ditutup');
            if (elDibuka && elDitutup) {
                var curDibuka = parseInt(elDibuka.textContent) || 0;
                var curDitutup = parseInt(elDitutup.textContent) || 0;
                if (isNowOpen) {
                    elDibuka.textContent = curDibuka + 1;
                    elDitutup.textContent = Math.max(0, curDitutup - 1);
                } else {
                    elDibuka.textContent = Math.max(0, curDibuka - 1);
                    elDitutup.textContent = curDitutup + 1;
                }
            }

            showNotification('success', 'Berhasil', data.message);
        } else {
            showNotification('error', 'Gagal', data.message || 'Terjadi kesalahan sistem');
            setTimeout(function() { window.location.reload(); }, 2500);
        }
    })
    .catch(function(err) {
        showNotification('error', 'Koneksi Gagal', 'Gagal menghubungi server.');
        setTimeout(function() { window.location.reload(); }, 2500);
    })
    .finally(function() {
        if (btn) btn.disabled = false;
    });
}

// Live Search Mahasiswa
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('liveSearchTA');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var keyword = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('#tableMahasiswaTA .searchable-row');
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
