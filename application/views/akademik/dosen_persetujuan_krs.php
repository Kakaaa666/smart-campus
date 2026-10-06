<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header"><h5>Persetujuan KRS</h5><span>Periksa beban SKS dan jadwal sebelum memberi keputusan.</span></div>
                        <div class="card-block">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
                            <?php endif; ?>
                            <?php if (!$pending_krs): ?>
                                <div class="alert alert-info mb-0">Tidak ada KRS yang menunggu keputusan Anda.</div>
                            <?php else: ?>
                                <?php foreach ($pending_krs as $request): ?>
                                    <section class="border rounded p-3 mb-4">
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <div>
                                                <h6 class="font-weight-bold mb-1"><?= html_escape($request->nama_lengkap) ?> (<?= html_escape($request->nim) ?>)</h6>
                                                <div class="text-muted">
                                                    <?= html_escape($request->prodi ?: '-') ?>, Semester <?= (int)$request->semester ?> &middot;
                                                    <?= html_escape($request->semester_akademik) ?> <?= html_escape($request->tahun_akademik) ?>
                                                </div>
                                            </div>
                                            <strong class="text-primary"><?= (int)$request->total_sks ?> SKS</strong>
                                        </div>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm table-bordered">
                                                <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Jadwal</th><th>Ruangan</th><th>Dosen Pengampu</th></tr></thead>
                                                <tbody>
                                                    <?php foreach ($request->krs_detail->mata_kuliah as $offering): ?>
                                                        <tr>
                                                            <td><?= html_escape($offering->kode_mk) ?></td>
                                                            <td><?= html_escape($offering->nama_mk) ?></td>
                                                            <td><?= (int)$offering->sks ?></td>
                                                            <td><?= html_escape($offering->hari) ?>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>-<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?></td>
                                                            <td><?= html_escape($offering->ruangan) ?></td>
                                                            <td><?= html_escape($offering->nama_dosen ?: '-') ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (!$request->krs_detail->mata_kuliah): ?>
                                                        <tr><td colspan="6" class="text-muted text-center">KRS tidak memiliki mata kuliah.</td></tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?= form_open('dosen/proses-krs', ['class' => 'form-row align-items-end']) ?>
                                            <input type="hidden" name="krs_id" value="<?= (int)$request->id ?>">
                                            <div class="form-group col-md-8">
                                                <label for="catatan-<?= (int)$request->id ?>">Catatan jika ditolak</label>
                                                <input class="form-control" id="catatan-<?= (int)$request->id ?>" name="catatan" maxlength="500" placeholder="Wajib diisi saat menolak">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <button class="btn btn-success" type="submit" name="aksi" value="setujui">Setujui</button>
                                                <button class="btn btn-outline-danger" type="submit" name="aksi" value="tolak">Tolak &amp; Kembalikan</button>
                                            </div>
                                        <?= form_close() ?>
                                    </section>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
