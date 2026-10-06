<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header"><h5>Mahasiswa Bimbingan</h5><span>Mahasiswa yang ditetapkan Admin Akademik sebagai bimbingan Anda.</span></div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Nama</th><th>NIM</th><th>Program Studi</th><th>Semester</th><th>Status KRS Aktif</th><th>SKS</th></tr></thead>
                                    <tbody>
                                        <?php if (!$students): ?>
                                            <tr><td colspan="6" class="text-center text-muted">Belum ada mahasiswa yang ditetapkan kepada Anda.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td><?= html_escape($student->nama_lengkap) ?></td>
                                                    <td><?= html_escape($student->nim) ?></td>
                                                    <td><?= html_escape($student->prodi ?: '-') ?></td>
                                                    <td><?= (int)$student->semester ?></td>
                                                    <td><?= html_escape($student->status_krs ?: 'BELUM DIBUAT') ?></td>
                                                    <td><?= (int)$student->total_sks ?></td>
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
</div>
