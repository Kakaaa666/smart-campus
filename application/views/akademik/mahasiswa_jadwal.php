<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header">
                            <h5>Jadwal Kuliah</h5>
                            <span><?= html_escape($active_period['semester_akademik']) ?> <?= html_escape($active_period['tahun_akademik']) ?></span>
                        </div>
                        <div class="card-block">
                            <?php if (!$krs || $krs->status !== 'DISETUJUI'): ?>
                                <div class="alert alert-info">
                                    Jadwal resmi tersedia setelah KRS disetujui Dosen Wali.
                                    Status KRS saat ini: <strong><?= html_escape($krs ? $krs->status : 'BELUM DIBUAT') ?></strong>.
                                    <a class="alert-link" href="<?= base_url('perwalian') ?>">Buka Perwalian</a>
                                </div>
                            <?php elseif (!$krs->mata_kuliah): ?>
                                <div class="alert alert-warning">KRS yang disetujui belum memiliki penawaran mata kuliah.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Hari / Waktu</th><th>Ruangan</th><th>Dosen</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($krs->mata_kuliah as $offering): ?>
                                                <tr>
                                                    <td><?= html_escape($offering->kode_mk) ?></td>
                                                    <td><?= html_escape($offering->nama_mk) ?></td>
                                                    <td><?= (int)$offering->sks ?></td>
                                                    <td><?= html_escape($offering->kelas) ?></td>
                                                    <td><?= html_escape($offering->hari) ?>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>-<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?></td>
                                                    <td><?= html_escape($offering->ruangan) ?></td>
                                                    <td><?= html_escape($offering->nama_dosen ?: '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
