<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header">
                            <h5>Operasional Akademik</h5>
                            <span>Kelola penawaran mata kuliah dan penetapan Dosen Wali untuk KRS mahasiswa.</span>
                        </div>
                        <div class="card-block">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
                            <?php endif; ?>

                            <div class="alert alert-info">
                                Periode aktif: <strong><?= html_escape($active_period['semester_akademik']) ?> <?= html_escape($active_period['tahun_akademik']) ?></strong>.
                                Data mata kuliah tidak diisi otomatis; katalog dan penawaran diatur Admin Akademik.
                            </div>

                            <h6 class="font-weight-bold mt-4">Tambah Penawaran Mata Kuliah</h6>
                            <?php if (!$lecturers): ?>
                                <div class="alert alert-warning">Belum ada akun Dosen aktif. Tambahkan akun Dosen sebelum membuat penawaran.</div>
                            <?php else: ?>
                                <?= form_open('admin/akademik/save-offering') ?>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="kode_mk">Kode Mata Kuliah</label>
                                            <input class="form-control" id="kode_mk" name="kode_mk" maxlength="32" required>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="nama_mk">Nama Mata Kuliah</label>
                                            <input class="form-control" id="nama_mk" name="nama_mk" maxlength="150" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="sks">SKS</label>
                                            <input class="form-control" id="sks" name="sks" type="number" min="1" max="24" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="kelas">Kelas</label>
                                            <input class="form-control" id="kelas" name="kelas" maxlength="20" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="prodi">Program Studi</label>
                                            <input class="form-control" id="prodi" name="prodi" maxlength="100" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="semester">Semester Mahasiswa</label>
                                            <input class="form-control" id="semester" name="semester" type="number" min="1" max="14" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="tahun_akademik">Tahun Akademik</label>
                                            <input class="form-control" id="tahun_akademik" name="tahun_akademik" value="<?= html_escape($active_period['tahun_akademik']) ?>" maxlength="9" placeholder="2026/2027" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="semester_akademik">Periode</label>
                                            <select class="form-control" id="semester_akademik" name="semester_akademik" required>
                                                <option value="Ganjil" <?= $active_period['semester_akademik'] === 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                                                <option value="Genap" <?= $active_period['semester_akademik'] === 'Genap' ? 'selected' : '' ?>>Genap</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="dosen_id">Dosen Pengampu</label>
                                            <select class="form-control" id="dosen_id" name="dosen_id" required>
                                                <option value="">Pilih dosen</option>
                                                <?php foreach ($lecturers as $lecturer): ?>
                                                    <option value="<?= (int)$lecturer->id ?>"><?= html_escape($lecturer->nama_lengkap) ?> (<?= html_escape($lecturer->nim) ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label for="hari">Hari</label>
                                            <select class="form-control" id="hari" name="hari" required>
                                                <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari): ?>
                                                    <option value="<?= $hari ?>"><?= $hari ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="waktu_mulai">Mulai</label>
                                            <input class="form-control" id="waktu_mulai" name="waktu_mulai" type="time" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="waktu_selesai">Selesai</label>
                                            <input class="form-control" id="waktu_selesai" name="waktu_selesai" type="time" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="ruangan">Ruangan</label>
                                            <input class="form-control" id="ruangan" name="ruangan" maxlength="100" required>
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary btn-block">Tambah Penawaran</button>
                                        </div>
                                    </div>
                                <?= form_close() ?>
                            <?php endif; ?>

                            <hr class="my-4">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-list-alt text-primary mr-1"></i> Penawaran Mata Kuliah</h6>
                            <div class="table-responsive border rounded" style="border-radius: 10px;">
                                <table class="table table-hover table-striped mb-0" style="min-width: 900px; vertical-align: middle;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="white-space: nowrap; padding: 12px 16px;">Periode</th>
                                            <th style="white-space: nowrap; padding: 12px 16px;">Program / Semester</th>
                                            <th style="padding: 12px 16px;">Mata Kuliah</th>
                                            <th style="white-space: nowrap; padding: 12px 16px;">Jadwal / Ruang</th>
                                            <th style="padding: 12px 16px;">Dosen Pengampu</th>
                                            <th style="white-space: nowrap; padding: 12px 16px; text-align: center;">Status</th>
                                            <th style="white-space: nowrap; padding: 12px 16px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$offerings): ?>
                                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada penawaran mata kuliah.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($offerings as $offering): ?>
                                                <tr>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <span class="font-weight-bold text-dark"><?= html_escape($offering->semester_akademik) ?></span><br>
                                                        <small class="text-muted"><?= html_escape($offering->tahun_akademik) ?></small>
                                                    </td>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <span class="badge badge-light border font-weight-bold"><?= html_escape($offering->prodi) ?></span><br>
                                                        <small class="text-muted font-weight-bold">Semester <?= (int)$offering->semester ?></small>
                                                    </td>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <strong class="text-dark d-block"><?= html_escape($offering->nama_mk) ?></strong>
                                                        <small class="text-muted font-monospace mr-1"><?= html_escape($offering->kode_mk) ?></small>
                                                        <span class="badge badge-primary px-1.5 py-0.5" style="font-size: 10px;"><?= (int)$offering->sks ?> SKS</span>
                                                        <span class="badge badge-secondary px-1.5 py-0.5" style="font-size: 10px;">Kelas <?= html_escape($offering->kelas) ?></span>
                                                    </td>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <span class="font-weight-bold text-dark"><?= html_escape($offering->hari) ?></span>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>-<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?> WIB<br>
                                                        <small class="text-muted"><i class="fa fa-building-o mr-1"></i><?= html_escape($offering->ruangan) ?></small>
                                                    </td>
                                                    <td style="padding: 12px 16px;">
                                                        <?= html_escape($offering->nama_dosen ?: '-') ?>
                                                    </td>
                                                    <td style="padding: 12px 16px; text-align: center;">
                                                        <span class="badge <?= $offering->aktif ? 'badge-success' : 'badge-secondary' ?> px-2 py-1 font-weight-bold" style="border-radius: 6px;">
                                                            <?= $offering->aktif ? 'Aktif' : 'Nonaktif' ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 12px 16px; text-align: center;">
                                                        <?= form_open('admin/akademik/toggle-offering', ['class' => 'd-inline']) ?>
                                                            <input type="hidden" name="penawaran_id" value="<?= (int)$offering->id ?>">
                                                            <input type="hidden" name="aktif" value="<?= $offering->aktif ? '0' : '1' ?>">
                                                            <button class="btn btn-sm <?= $offering->aktif ? 'btn-outline-danger' : 'btn-outline-success' ?> font-weight-bold" type="submit" style="border-radius: 6px;">
                                                                <?= $offering->aktif ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                            </button>
                                                        <?= form_close() ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <hr class="my-4">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-users text-primary mr-1"></i> Penetapan Dosen Wali</h6>
                            <?php if (!$students || !$lecturers): ?>
                                <div class="alert alert-secondary">Penetapan Dosen Wali memerlukan akun mahasiswa dan Dosen aktif.</div>
                            <?php else: ?>
                                <div class="table-responsive border rounded" style="border-radius: 10px;">
                                    <table class="table table-hover table-striped mb-0" style="min-width: 700px; vertical-align: middle;">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="white-space: nowrap; padding: 12px 16px;">Mahasiswa</th>
                                                <th style="white-space: nowrap; padding: 12px 16px;">Program Studi / Semester</th>
                                                <th style="white-space: nowrap; padding: 12px 16px;">Penugasan Dosen Wali</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <strong class="text-dark d-block"><?= html_escape($student->nama_lengkap) ?></strong>
                                                        <small class="text-muted font-monospace">NIM: <?= html_escape($student->nim) ?></small>
                                                    </td>
                                                    <td style="padding: 12px 16px; line-height: 1.4;">
                                                        <span class="font-weight-bold"><?= html_escape($student->prodi ?: '-') ?></span><br>
                                                        <span class="badge badge-light border">Semester <?= (int)$student->semester ?></span>
                                                    </td>
                                                    <td style="padding: 12px 16px;">
                                                        <?= form_open('admin/akademik/assign-advisor', ['class' => 'form-inline']) ?>
                                                            <input type="hidden" name="mahasiswa_id" value="<?= (int)$student->id ?>">
                                                            <select class="form-control form-control-sm mr-2" name="dosen_id" required style="border-radius: 6px; min-width: 220px;">
                                                                <option value="">Pilih Dosen Wali</option>
                                                                <?php foreach ($lecturers as $lecturer): ?>
                                                                    <option value="<?= (int)$lecturer->id ?>" <?= (int)$student->dosen_id === (int)$lecturer->id ? 'selected' : '' ?>>
                                                                        <?= html_escape($lecturer->nama_lengkap) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold" style="border-radius: 6px;">Simpan</button>
                                                        <?= form_close() ?>
                                                    </td>
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
