<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5>Formulir Rencana Studi (FRS)</h5>
                                <span><?= html_escape($academic_period['semester_akademik']) ?> <?= html_escape($academic_period['tahun_akademik']) ?></span>
                            </div>
                            <?php if ($krs && $krs->status === 'DISETUJUI'): ?>
                                <button type="button" class="btn btn-primary no-print" onclick="window.print()">
                                    <i class="fa fa-print mr-1"></i> Cetak FRS
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-block">
                            <div class="alert <?= $krs && $krs->status === 'DISETUJUI' ? 'alert-success' : 'alert-info' ?>">
                                <?php if (!$krs): ?>
                                    KRS untuk periode ini belum dibuat.
                                <?php elseif ($krs->status === 'MENUNGGU'): ?>
                                    KRS diajukan <?= $krs->diajukan_at ? html_escape(date('d M Y H:i', strtotime($krs->diajukan_at))) : '' ?> dan sedang menunggu persetujuan Dosen Wali.
                                <?php elseif ($krs->status === 'DITOLAK'): ?>
                                    KRS ditolak Dosen Wali. Perbaiki pilihan di halaman Perwalian lalu ajukan kembali.
                                <?php elseif ($krs->status === 'DRAFT'): ?>
                                    KRS masih berupa draf dan belum diajukan kepada Dosen Wali.
                                <?php else: ?>
                                    KRS telah disetujui oleh Dosen Wali pada <?= $krs->diproses_at ? html_escape(date('d M Y H:i', strtotime($krs->diproses_at))) : '-' ?>.
                                <?php endif; ?>
                            </div>

                            <?php if ($krs && $krs->status === 'DITOLAK' && $krs->catatan): ?>
                                <div class="alert alert-warning"><strong>Catatan:</strong> <?= nl2br(html_escape($krs->catatan)) ?></div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th style="width:25%">Nama</th><td><?= html_escape($target_mahasiswa->nama_lengkap) ?></td></tr>
                                        <tr><th>NIM</th><td><?= html_escape($target_mahasiswa->nim) ?></td></tr>
                                        <tr><th>Program Studi</th><td><?= html_escape($target_mahasiswa->prodi ?: '-') ?></td></tr>
                                        <tr><th>Semester</th><td><?= (int)$target_mahasiswa->semester ?> &mdash; <?= html_escape($academic_period['semester_akademik']) ?> <?= html_escape($academic_period['tahun_akademik']) ?></td></tr>
                                        <tr><th>Dosen Wali</th><td><?= html_escape($advisor ? $advisor->nama_lengkap : 'Belum ditetapkan') ?></td></tr>
                                        <?php if ($krs): ?>
                                            <tr><th>Status</th><td><?= html_escape($krs->status) ?></td></tr>
                                            <tr><th>Total SKS</th><td><?= (int)$krs->total_sks ?> / <?= (int)$max_sks ?></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($krs && $krs->mata_kuliah): ?>
                                <div class="table-responsive mt-4">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th>
                                                <th>Kelas</th><th>Jadwal</th><th>Ruangan</th><th>Dosen Pengampu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($krs->mata_kuliah as $index => $offering): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= html_escape($offering->kode_mk) ?></td>
                                                    <td><?= html_escape($offering->nama_mk) ?></td>
                                                    <td><?= (int)$offering->sks ?></td>
                                                    <td><?= html_escape($offering->kelas) ?></td>
                                                    <td><?= html_escape($offering->hari) ?>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>–<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?></td>
                                                    <td><?= html_escape($offering->ruangan) ?></td>
                                                    <td><?= html_escape($offering->nama_dosen ?: '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr><th colspan="3" class="text-right">Total Beban Studi</th><th><?= (int)$krs->total_sks ?> SKS</th><th colspan="4"></th></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Belum ada mata kuliah yang tersimpan di KRS periode ini.</p>
                            <?php endif; ?>
                            <div class="mt-3 no-print">
                                <a href="<?= base_url('perwalian') ?>" class="btn btn-outline-primary">Kembali ke Perwalian</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
@media print {
    .pcoded-header, .pcoded-navbar, .pcoded-main-container, .no-print { display: none !important; }
    .pcoded-content, .pcoded-inner-content, .main-body, .page-wrapper { margin: 0 !important; padding: 0 !important; }
}
</style>
