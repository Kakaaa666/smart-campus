<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$selected_offerings = [];
if (!empty($krs->mata_kuliah)) {
    $selected_offerings = array_map('intval', array_column($krs->mata_kuliah, 'id'));
}
?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header">
                            <h5>Rencana Studi Semester <?= html_escape($academic_period['semester_akademik']) ?> <?= html_escape($academic_period['tahun_akademik']) ?></h5>
                            <span><?= html_escape($target_mahasiswa->nama_lengkap) ?> &mdash; <?= html_escape($target_mahasiswa->nim) ?> &mdash; <?= html_escape($target_mahasiswa->prodi ?: 'Program studi belum diatur') ?></span>
                        </div>
                        <div class="card-block">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success" role="alert"><?= html_escape($this->session->flashdata('success')) ?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger" role="alert"><?= html_escape($this->session->flashdata('error')) ?></div>
                            <?php endif; ?>

                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted d-block">Status Pembayaran Semester</small>
                                        <strong class="<?= $status_krs['buka_krs'] ? 'text-success' : 'text-danger' ?>">
                                            <?= html_escape($status_krs['status']) ?>
                                        </strong>
                                        <div class="small text-muted mt-1"><?= html_escape($status_krs['pesan']) ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted d-block">Status KRS</small>
                                        <strong><?= html_escape($krs ? $krs->status : 'BELUM DIBUAT') ?></strong>
                                        <div class="small text-muted mt-1">
                                            <?= $krs ? (int)$krs->total_sks : 0 ?> / <?= (int)$max_sks ?> SKS
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted d-block">Dosen Wali</small>
                                        <?php if ($advisor): ?>
                                            <strong><?= html_escape($advisor->nama_lengkap) ?></strong>
                                            <div class="small text-muted"><?= html_escape($advisor->nim) ?></div>
                                        <?php else: ?>
                                            <strong class="text-warning">Belum ditetapkan</strong>
                                            <div class="small text-muted">Hubungi Admin Akademik untuk penetapan Dosen Wali.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($krs && $krs->status === 'DITOLAK' && $krs->catatan): ?>
                                <div class="alert alert-warning">
                                    <strong>Catatan Dosen Wali:</strong> <?= nl2br(html_escape($krs->catatan)) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($krs && $krs->status === 'MENUNGGU'): ?>
                                <div class="alert alert-info">
                                    KRS Anda sedang menunggu keputusan Dosen Wali. Pilihan mata kuliah dikunci selama proses ini.
                                </div>
                            <?php elseif ($krs && $krs->status === 'DISETUJUI'): ?>
                                <div class="alert alert-success">
                                    KRS telah disetujui. Jadwal resmi dapat dilihat pada FRS.
                                    <a class="alert-link ml-1" href="<?= base_url('perwalian/frs') ?>">Buka FRS</a>
                                </div>
                            <?php endif; ?>

                            <?php if (!$offerings): ?>
                                <div class="alert alert-secondary mb-0">
                                    Belum ada penawaran mata kuliah untuk program studi, semester, dan periode akademik Anda.
                                    Admin Akademik perlu menambahkan penawaran terlebih dahulu.
                                </div>
                            <?php else: ?>
                                <?= form_open('perwalian/simpan_krs') ?>
                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">Penawaran Mata Kuliah</h6>
                                            <small class="text-muted">Pilih <?= (int)$min_sks ?>-<?= (int)$max_sks ?> SKS dan hindari jadwal yang bertabrakan.</small>
                                        </div>
                                        <div class="mt-2 mt-md-0">
                                            Beban dipilih: <strong id="selectedSks"><?= $krs ? (int)$krs->total_sks : 0 ?></strong> / <?= (int)$max_sks ?> SKS
                                        </div>
                                    </div>
<<<<<<< Updated upstream
                                </div>
                                <div class="col-lg-5 col-md-12 text-lg-right mt-3 mt-lg-0">
                                    <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 10px;">
                                        <a href="<?= base_url('perwalian/frs') ?>" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px; font-weight: 600; font-size: 13px; padding: 9px 16px; background: #ffffff;">
                                            <i class="fa fa-file-text-o mr-1"></i> Lihat Dokumen FRS
                                        </a>
                                        <button type="button" class="btn btn-primary shadow-sm" onclick="SCDialog.alert('Rencana studi Anda telah berhasil tersimpan dan diajukan ke Dosen Wali.', { title: 'Rencana Studi Diajukan', type: 'success' })" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 9px 18px; background: #0284c7; border-color: #0284c7;">
                                            <i class="fa fa-save mr-1"></i> Simpan Rencana Studi
                                        </button>
=======
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Pilih</th>
                                                    <th>Kode / Mata Kuliah</th>
                                                    <th>SKS</th>
                                                    <th>Kelas</th>
                                                    <th>Jadwal</th>
                                                    <th>Ruangan</th>
                                                    <th>Dosen Pengampu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($offerings as $offering): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="penawaran[]" value="<?= (int)$offering->id ?>"
                                                                   data-sks="<?= (int)$offering->sks ?>"
                                                                   <?= in_array((int)$offering->id, $selected_offerings, true) ? 'checked' : '' ?>
                                                                   <?= !$can_edit_krs ? 'disabled' : '' ?>
                                                                   aria-label="Pilih <?= html_escape($offering->kode_mk) ?>">
                                                        </td>
                                                        <td><strong><?= html_escape($offering->kode_mk) ?></strong><br><?= html_escape($offering->nama_mk) ?></td>
                                                        <td><?= (int)$offering->sks ?></td>
                                                        <td><?= html_escape($offering->kelas) ?></td>
                                                        <td><?= html_escape($offering->hari) ?>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>–<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?></td>
                                                        <td><?= html_escape($offering->ruangan) ?></td>
                                                        <td><?= html_escape($offering->nama_dosen ?: 'Dosen belum tersedia') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
>>>>>>> Stashed changes
                                    </div>
                                    <?php if ($can_edit_krs): ?>
                                        <div class="d-flex justify-content-end flex-wrap" style="gap:8px">
                                            <button class="btn btn-outline-primary" name="aksi" value="draft" type="submit">Simpan Draf</button>
                                            <button class="btn btn-primary" name="aksi" value="ajukan" type="submit">Ajukan ke Dosen Wali</button>
                                        </div>
                                    <?php elseif (!$is_admin_preview && !$status_krs['buka_krs']): ?>
                                        <div class="alert alert-warning mb-0"><?= html_escape($status_krs['pesan']) ?></div>
                                    <?php endif; ?>
                                <?= form_close() ?>
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
    const checkboxes = Array.from(document.querySelectorAll('input[name="penawaran[]"]'));
    const total = document.getElementById('selectedSks');
    function updateTotal() {
        total.textContent = checkboxes.reduce(function(sum, checkbox) {
            return sum + (checkbox.checked ? Number(checkbox.dataset.sks) : 0);
        }, 0);
    }
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotal);
    });
});
</script>
