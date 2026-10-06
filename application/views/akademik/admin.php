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

                            <hr>
                            <h6 class="font-weight-bold">Penawaran Mata Kuliah</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr><th>Periode</th><th>Program / Semester</th><th>Mata Kuliah</th><th>Jadwal / Ruang</th><th>Dosen</th><th>Status</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$offerings): ?>
                                            <tr><td colspan="7" class="text-center text-muted">Belum ada penawaran mata kuliah.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($offerings as $offering): ?>
                                                <tr>
                                                    <td><?= html_escape($offering->semester_akademik) ?><br><?= html_escape($offering->tahun_akademik) ?></td>
                                                    <td><?= html_escape($offering->prodi) ?><br>Semester <?= (int)$offering->semester ?></td>
                                                    <td><strong><?= html_escape($offering->kode_mk) ?></strong><br><?= html_escape($offering->nama_mk) ?> (<?= (int)$offering->sks ?> SKS) &mdash; Kelas <?= html_escape($offering->kelas) ?></td>
                                                    <td><?= html_escape($offering->hari) ?>, <?= html_escape(substr($offering->waktu_mulai, 0, 5)) ?>-<?= html_escape(substr($offering->waktu_selesai, 0, 5)) ?><br><?= html_escape($offering->ruangan) ?></td>
                                                    <td><?= html_escape($offering->nama_dosen ?: '-') ?></td>
                                                    <td><?= $offering->aktif ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td>
                                                        <?= form_open('admin/akademik/toggle-offering', ['class' => 'd-inline']) ?>
                                                            <input type="hidden" name="penawaran_id" value="<?= (int)$offering->id ?>">
                                                            <input type="hidden" name="aktif" value="<?= $offering->aktif ? '0' : '1' ?>">
                                                            <button class="btn btn-sm <?= $offering->aktif ? 'btn-outline-danger' : 'btn-outline-success' ?>" type="submit">
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

                            <hr>
                            <h6 class="font-weight-bold">Penetapan Dosen Wali</h6>
                            <?php if (!$students || !$lecturers): ?>
                                <div class="alert alert-secondary">Penetapan Dosen Wali memerlukan akun mahasiswa dan Dosen aktif.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead><tr><th>Mahasiswa</th><th>Program Studi / Semester</th><th>Penugasan Dosen Wali</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td><?= html_escape($student->nama_lengkap) ?><br><small><?= html_escape($student->nim) ?></small></td>
                                                    <td><?= html_escape($student->prodi ?: '-') ?> / <?= (int)$student->semester ?></td>
                                                    <td>
                                                        <?= form_open('admin/akademik/assign-advisor', ['class' => 'form-inline']) ?>
                                                            <input type="hidden" name="mahasiswa_id" value="<?= (int)$student->id ?>">
                                                            <select class="form-control form-control-sm mr-2" name="dosen_id" required>
                                                                <option value="">Pilih Dosen Wali</option>
                                                                <?php foreach ($lecturers as $lecturer): ?>
                                                                    <option value="<?= (int)$lecturer->id ?>" <?= (int)$student->dosen_id === (int)$lecturer->id ? 'selected' : '' ?>>
                                                                        <?= html_escape($lecturer->nama_lengkap) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
