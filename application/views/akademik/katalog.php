<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$old_input = is_array($old_input) ? $old_input : [];
$active_form = '';
$value = function ($key, $record = null, $fallback = '') use ($old_input, &$active_form) {
    return isset($old_input['catalog_form']) && $old_input['catalog_form'] === $active_form && array_key_exists($key, $old_input)
        ? $old_input[$key]
        : ($record && isset($record->$key) ? $record->$key : $fallback);
};
?>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= html_escape($success) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= nl2br(html_escape(strip_tags($error))) ?></div>
                    <?php endif; ?>
                    <div class="alert alert-info">
                        Data baru berstatus tinjauan/draf. Aktifkan hanya setelah kode, pemetaan, dan kebijakan kampus diverifikasi.
                        Perubahan dan alasannya dicatat ke histori katalog.
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h5>Program Studi</h5></div>
                        <div class="card-block">
                            <?php $active_form = 'program'; ?>
                            <?php $program = $editing['program']; ?>
                            <?= form_open('admin/katalog-akademik/save_program') ?>
                                <input type="hidden" name="catalog_form" value="program">
                                <input type="hidden" name="id" value="<?= (int)($program->id ?? 0) ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label>Kode (opsional)</label>
                                        <input class="form-control" name="kode" maxlength="32" value="<?= html_escape($value('kode', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Nama Program Studi</label>
                                        <input class="form-control" name="nama" required maxlength="150" value="<?= html_escape($value('nama', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fakultas/Unit</label>
                                        <input class="form-control" name="fakultas" maxlength="150" value="<?= html_escape($value('fakultas', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Jenjang</label>
                                        <input class="form-control" name="jenjang" maxlength="50" value="<?= html_escape($value('jenjang', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <?php $program_status = $value('status', $program, 'PENDING_REVIEW'); ?>
                                        <select class="form-control" name="status" required>
                                            <option value="PENDING_REVIEW" <?= $program_status === 'PENDING_REVIEW' ? 'selected' : '' ?>>Perlu ditinjau</option>
                                            <option value="ACTIVE" <?= $program_status === 'ACTIVE' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="INACTIVE" <?= $program_status === 'INACTIVE' ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Berlaku Mulai</label>
                                        <input type="date" class="form-control" name="berlaku_mulai" value="<?= html_escape($value('berlaku_mulai', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Berlaku Sampai</label>
                                        <input type="date" class="form-control" name="berlaku_sampai" value="<?= html_escape($value('berlaku_sampai', $program)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Alasan perubahan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><?= $program ? 'Simpan Perubahan' : 'Tambah Program Studi' ?></button>
                                <?php if ($program): ?><a class="btn btn-light" href="<?= base_url('admin/katalog-akademik') ?>">Batal</a><?php endif; ?>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Kode</th><th>Program Studi</th><th>Fakultas/Unit</th><th>Jenjang</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        <?php foreach ($programs as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->kode ?: '-') ?></td>
                                                <td><?= html_escape($item->nama) ?></td>
                                                <td><?= html_escape($item->fakultas ?: '-') ?></td>
                                                <td><?= html_escape($item->jenjang ?: '-') ?></td>
                                                <td><?= html_escape($item->status) ?></td>
                                                <td><a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/katalog-akademik?edit_program=' . (int)$item->id) ?>">Ubah</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$programs): ?><tr><td colspan="6" class="text-center text-muted">Belum ada program studi.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Total <?= (int)$counts['programs'] ?> data; daftar menampilkan maksimal <?= (int)$limit ?> per halaman.</small>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h5>Master Mata Kuliah</h5></div>
                        <div class="card-block">
                            <?php $active_form = 'course'; ?>
                            <?php $course = $editing['course']; ?>
                            <?= form_open('admin/katalog-akademik/save_course') ?>
                                <input type="hidden" name="catalog_form" value="course">
                                <input type="hidden" name="id" value="<?= (int)($course->id ?? 0) ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label>Kode Mata Kuliah</label>
                                        <input class="form-control" name="kode" required maxlength="32" value="<?= html_escape($value('kode', $course)) ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Nama Mata Kuliah</label>
                                        <input class="form-control" name="nama" required maxlength="150" value="<?= html_escape($value('nama', $course)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <?php $course_status = $value('status', $course, 'PENDING_REVIEW'); ?>
                                        <select class="form-control" name="status" required>
                                            <option value="PENDING_REVIEW" <?= $course_status === 'PENDING_REVIEW' ? 'selected' : '' ?>>Perlu ditinjau</option>
                                            <option value="ACTIVE" <?= $course_status === 'ACTIVE' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="INACTIVE" <?= $course_status === 'INACTIVE' ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-9">
                                        <label>Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" maxlength="5000"><?= html_escape($value('deskripsi', $course)) ?></textarea>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Alasan perubahan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><?= $course ? 'Simpan Perubahan' : 'Tambah Mata Kuliah' ?></button>
                                <?php if ($course): ?><a class="btn btn-light" href="<?= base_url('admin/katalog-akademik') ?>">Batal</a><?php endif; ?>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Kode</th><th>Nama</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        <?php foreach ($courses as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->kode) ?></td>
                                                <td><?= html_escape($item->nama) ?></td>
                                                <td><?= html_escape($item->status) ?></td>
                                                <td><a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/katalog-akademik?edit_course=' . (int)$item->id) ?>">Ubah</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$courses): ?><tr><td colspan="4" class="text-center text-muted">Belum ada mata kuliah.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Total <?= (int)$counts['courses'] ?> data; daftar menampilkan maksimal <?= (int)$limit ?> per halaman.</small>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h5>Kurikulum</h5></div>
                        <div class="card-block">
                            <?php $active_form = 'curriculum'; ?>
                            <?php $curriculum = $editing['curriculum']; ?>
                            <?= form_open('admin/katalog-akademik/save_curriculum') ?>
                                <input type="hidden" name="catalog_form" value="curriculum">
                                <input type="hidden" name="id" value="<?= (int)($curriculum->id ?? 0) ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Program Studi</label>
                                        <?php $selected_program = (string)$value('program_studi_id', $curriculum, ''); ?>
                                        <select class="form-control" name="program_studi_id" required>
                                            <option value="">Pilih program studi</option>
                                            <?php foreach ($program_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= $selected_program === (string)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->nama) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Kode Kurikulum</label>
                                        <input class="form-control" name="kode" required maxlength="32" value="<?= html_escape($value('kode', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Nama Kurikulum</label>
                                        <input class="form-control" name="nama" required maxlength="150" value="<?= html_escape($value('nama', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Angkatan Awal</label>
                                        <input class="form-control" name="angkatan_mulai" inputmode="numeric" maxlength="4" value="<?= html_escape($value('angkatan_mulai', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Angkatan Akhir</label>
                                        <input class="form-control" name="angkatan_sampai" inputmode="numeric" maxlength="4" value="<?= html_escape($value('angkatan_sampai', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Status</label>
                                        <?php $curriculum_status = $value('status', $curriculum, 'DRAFT'); ?>
                                        <select class="form-control" name="status" required>
                                            <option value="DRAFT" <?= $curriculum_status === 'DRAFT' ? 'selected' : '' ?>>Draf</option>
                                            <option value="ACTIVE" <?= $curriculum_status === 'ACTIVE' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="ARCHIVED" <?= $curriculum_status === 'ARCHIVED' ? 'selected' : '' ?>>Arsip</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Berlaku Mulai</label>
                                        <input type="date" class="form-control" name="berlaku_mulai" value="<?= html_escape($value('berlaku_mulai', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Berlaku Sampai</label>
                                        <input type="date" class="form-control" name="berlaku_sampai" value="<?= html_escape($value('berlaku_sampai', $curriculum)) ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Alasan perubahan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><?= $curriculum ? 'Simpan Perubahan' : 'Tambah Kurikulum' ?></button>
                                <?php if ($curriculum): ?><a class="btn btn-light" href="<?= base_url('admin/katalog-akademik') ?>">Batal</a><?php endif; ?>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Program Studi</th><th>Kode</th><th>Nama</th><th>Angkatan</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        <?php foreach ($curricula as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->program_name) ?></td>
                                                <td><?= html_escape($item->kode) ?></td>
                                                <td><?= html_escape($item->nama) ?></td>
                                                <td><?= html_escape(($item->angkatan_mulai ?: '-') . ' - ' . ($item->angkatan_sampai ?: '-')) ?></td>
                                                <td><?= html_escape($item->status) ?></td>
                                                <td><a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/katalog-akademik?edit_curriculum=' . (int)$item->id) ?>">Ubah</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$curricula): ?><tr><td colspan="6" class="text-center text-muted">Belum ada kurikulum.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Total <?= (int)$counts['curricula'] ?> data; daftar menampilkan maksimal <?= (int)$limit ?> per halaman.</small>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h5>Pemetaan Mata Kuliah ke Kurikulum</h5></div>
                        <div class="card-block">
                            <?php $active_form = 'mapping'; ?>
                            <?php $mapping = $editing['curriculum_course']; ?>
                            <?= form_open('admin/katalog-akademik/save_mapping') ?>
                                <input type="hidden" name="catalog_form" value="mapping">
                                <input type="hidden" name="id" value="<?= (int)($mapping->id ?? 0) ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Kurikulum (cari kode/nama prodi)</label>
                                        <input type="search" class="form-control mb-2" id="curriculumSearch" placeholder="Ketik minimal dua karakter">
                                        <select class="form-control" name="kurikulum_id" id="curriculumSelect" required>
                                            <option value="">Pilih kurikulum</option>
                                            <?php foreach ($curriculum_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('kurikulum_id', $mapping, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->kode . ' - ' . $item->program_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Mata Kuliah (cari kode/nama)</label>
                                        <input type="search" class="form-control mb-2" id="courseSearch" placeholder="Ketik minimal dua karakter">
                                        <select class="form-control" name="mata_kuliah_id" id="courseSelect" required>
                                            <option value="">Pilih mata kuliah</option>
                                            <?php foreach ($course_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('mata_kuliah_id', $mapping, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->kode . ' - ' . $item->nama) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Semester Rekomendasi</label>
                                        <input type="number" class="form-control" name="semester_rekomendasi" min="1" max="14" value="<?= html_escape($value('semester_rekomendasi', $mapping)) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>SKS</label>
                                        <input type="number" class="form-control" name="sks" required min="1" max="24" value="<?= html_escape($value('sks', $mapping)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Kelompok Mata Kuliah</label>
                                        <input class="form-control" name="kelompok_mata_kuliah" maxlength="50" value="<?= html_escape($value('kelompok_mata_kuliah', $mapping)) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Kewajiban</label>
                                        <?php $wajib = (string)$value('wajib', $mapping, '1'); ?>
                                        <select class="form-control" name="wajib" required>
                                            <option value="1" <?= $wajib === '1' ? 'selected' : '' ?>>Wajib</option>
                                            <option value="0" <?= $wajib === '0' ? 'selected' : '' ?>>Pilihan</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status Pemetaan</label>
                                        <?php $mapping_status = $value('status', $mapping, 'ACTIVE'); ?>
                                        <select class="form-control" name="status" required>
                                            <option value="ACTIVE" <?= $mapping_status === 'ACTIVE' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="INACTIVE" <?= $mapping_status === 'INACTIVE' ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Alasan perubahan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><?= $mapping ? 'Simpan Perubahan' : 'Petakan Mata Kuliah' ?></button>
                                <?php if ($mapping): ?><a class="btn btn-light" href="<?= base_url('admin/katalog-akademik') ?>">Batal</a><?php endif; ?>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Kurikulum</th><th>Mata Kuliah</th><th>Semester</th><th>SKS</th><th>Kelompok</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        <?php foreach ($curriculum_courses as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->curriculum_code) ?></td>
                                                <td><?= html_escape($item->kode_mata_kuliah . ' - ' . $item->nama_mata_kuliah) ?></td>
                                                <td><?= $item->semester_rekomendasi ? (int)$item->semester_rekomendasi : '-' ?></td>
                                                <td><?= (int)$item->sks ?></td>
                                                <td><?= html_escape($item->kelompok_mata_kuliah ?: '-') ?></td>
                                                <td><?= html_escape($item->status) ?></td>
                                                <td><a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/katalog-akademik?edit_mapping=' . (int)$item->id) ?>">Ubah</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$curriculum_courses): ?><tr><td colspan="7" class="text-center text-muted">Belum ada pemetaan.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Total <?= (int)$counts['curriculum_courses'] ?> data; daftar menampilkan maksimal <?= (int)$limit ?> per halaman.</small>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header"><h5>Penugasan Dosen ke Program Studi</h5></div>
                        <div class="card-block">
                            <?php $active_form = 'lecturer_assignment'; ?>
                            <?= form_open('admin/katalog-akademik/save_lecturer_assignment') ?>
                                <input type="hidden" name="catalog_form" value="lecturer_assignment">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Dosen (cari nama/NIP/NIDN)</label>
                                        <input type="search" class="form-control mb-2" id="lecturerSearch" placeholder="Ketik minimal dua karakter">
                                        <select class="form-control" name="dosen_id" id="lecturerSelect" required>
                                            <option value="">Pilih dosen</option>
                                            <?php foreach ($lecturer_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('dosen_id', null, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->nama_lengkap . ' (' . $item->nim . ')') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Program Studi Aktif</label>
                                        <select class="form-control" name="program_studi_id" required>
                                            <option value="">Pilih program studi</option>
                                            <?php foreach ($active_program_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('program_studi_id', null, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape(($item->kode ? $item->kode . ' - ' : '') . $item->nama) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Jenis Penugasan</label>
                                        <input class="form-control" name="jenis_penugasan" required maxlength="50" value="<?= html_escape($value('jenis_penugasan')) ?>" placeholder="Sesuai nomenklatur kampus">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Berlaku Mulai (opsional)</label>
                                        <input type="date" class="form-control" name="berlaku_mulai" value="<?= html_escape($value('berlaku_mulai')) ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Berlaku Sampai (opsional)</label>
                                        <input type="date" class="form-control" name="berlaku_sampai" value="<?= html_escape($value('berlaku_sampai')) ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Alasan penugasan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">Tambah Penugasan</button>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Dosen</th><th>Program Studi</th><th>Jenis</th><th>Periode Berlaku</th><th>Status</th><th>Penonaktifan</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($lecturer_assignments as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->lecturer_name . ' (' . $item->lecturer_nim . ')') ?></td>
                                                <td><?= html_escape(($item->program_code ? $item->program_code . ' - ' : '') . $item->program_name) ?></td>
                                                <td><?= html_escape($item->jenis_penugasan) ?></td>
                                                <td><?= html_escape($item->berlaku_mulai ?: 'Tidak ditentukan') ?> - <?= html_escape($item->berlaku_sampai ?: 'Tidak ditentukan') ?></td>
                                                <td><?= html_escape($item->status) ?></td>
                                                <td>
                                                    <?php if ($item->status === 'ACTIVE'): ?>
                                                        <?= form_open('admin/katalog-akademik/deactivate_lecturer_assignment', ['class' => 'form-inline']) ?>
                                                            <input type="hidden" name="id" value="<?= (int)$item->id ?>">
                                                            <input class="form-control form-control-sm mr-1" name="reason" required minlength="5" maxlength="500" placeholder="Alasan">
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">Nonaktifkan</button>
                                                        <?= form_close() ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$lecturer_assignments): ?><tr><td colspan="6" class="text-center text-muted">Belum ada penugasan dosen.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Total <?= (int)$counts['lecturer_assignments'] ?> penugasan; daftar menampilkan maksimal <?= (int)$limit ?> per halaman.</small>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header"><h5>Penetapan dan Histori Kurikulum Mahasiswa</h5></div>
                        <div class="card-block">
                            <div class="alert alert-info">
                                Penetapan hanya menerima kurikulum aktif yang nama program studinya cocok persis dengan prodi pada data mahasiswa. Perubahan periode sebelumnya dan penetapan baru dicatat di histori.
                            </div>
                            <?php $active_form = 'student_curriculum'; ?>
                            <?= form_open('admin/katalog-akademik/save_student_curriculum') ?>
                                <input type="hidden" name="catalog_form" value="student_curriculum">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Mahasiswa (cari nama/NIM/prodi)</label>
                                        <input type="search" class="form-control mb-2" id="studentSearch" placeholder="Ketik minimal dua karakter">
                                        <select class="form-control" name="mahasiswa_id" id="studentSelect" required>
                                            <option value="">Pilih mahasiswa</option>
                                            <?php foreach ($student_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('mahasiswa_id', null, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->nama_lengkap . ' (' . $item->nim . ') - ' . $item->prodi) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Kurikulum Aktif yang Cocok dengan Prodi</label>
                                        <select class="form-control" name="kurikulum_id" id="studentCurriculumSelect" required>
                                            <option value="">Pilih kurikulum</option>
                                            <?php foreach ($student_curriculum_options as $item): ?>
                                                <option value="<?= (int)$item->id ?>" <?= (int)$value('kurikulum_id', null, 0) === (int)$item->id ? 'selected' : '' ?>>
                                                    <?= html_escape($item->kode . ' - ' . $item->nama) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Berlaku Mulai</label>
                                        <input type="date" class="form-control" name="berlaku_mulai" required value="<?= html_escape($value('berlaku_mulai')) ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Berlaku Sampai (opsional)</label>
                                        <input type="date" class="form-control" name="berlaku_sampai" value="<?= html_escape($value('berlaku_sampai')) ?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Alasan penetapan/perubahan</label>
                                        <input class="form-control" name="reason" required minlength="5" maxlength="500" value="<?= html_escape($value('reason')) ?>">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">Tetapkan Kurikulum</button>
                            <?= form_close() ?>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Tanggal Penetapan</th><th>NIM</th><th>Mahasiswa</th><th>Prodi lama</th><th>Kurikulum</th><th>Periode Berlaku</th><th>Alasan</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($student_curriculum_assignments as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->assigned_at) ?></td>
                                                <td><?= html_escape($item->nim) ?></td>
                                                <td><?= html_escape($item->nama_lengkap) ?></td>
                                                <td><?= html_escape($item->prodi ?: '-') ?></td>
                                                <td><?= html_escape($item->curriculum_code . ' - ' . $item->curriculum_name) ?></td>
                                                <td><?= html_escape($item->berlaku_mulai . ' - ' . ($item->berlaku_sampai ?: 'berjalan')) ?></td>
                                                <td><?= html_escape($item->alasan_perubahan ?: '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$student_curriculum_assignments): ?><tr><td colspan="7" class="text-center text-muted">Belum ada histori penetapan kurikulum.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header"><h5>Rekonsiliasi Penawaran Lama</h5></div>
                        <div class="card-block">
                            <div class="alert alert-warning">
                                Ini hanya pratinjau pencocokan kode persis (case-insensitive). Tidak ada penawaran lama yang diubah atau ditautkan otomatis; verifikasi kode, nama, prodi, dan kurikulum sebelum tahap pemetaan manual.
                            </div>
                            <p>Total penawaran: <strong><?= (int)$offering_reconciliation_counts['total'] ?></strong>;
                                sudah memiliki tautan katalog: <strong><?= (int)$offering_reconciliation_counts['mapped'] ?></strong>;
                                belum tertaut: <strong><?= (int)$offering_reconciliation_counts['unmapped'] ?></strong>.
                            </p>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>Periode/Kelas</th><th>Prodi lama</th><th>Kode dan Nama Penawaran</th><th>Status Rekonsiliasi</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($offering_reconciliation as $item): ?>
                                            <tr>
                                                <td><?= html_escape($item->tahun_akademik . ' ' . $item->semester_akademik . ' / ' . $item->kelas) ?></td>
                                                <td><?= html_escape($item->prodi) ?></td>
                                                <td><?= html_escape($item->kode_mk . ' - ' . $item->nama_mk) ?></td>
                                                <td>
                                                    <?php if ($item->mata_kuliah_id): ?>
                                                        <span class="text-success">Tertaut: <?= html_escape($item->linked_course_code . ' - ' . $item->linked_course_name) ?></span>
                                                    <?php elseif ($item->candidate_course_id): ?>
                                                        <span class="text-warning">Kandidat kode cocok (belum tertaut): <?= html_escape($item->candidate_course_code . ' - ' . $item->candidate_course_name) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Belum ada kandidat kode persis</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (!$offering_reconciliation): ?><tr><td colspan="4" class="text-center text-muted">Belum ada penawaran lama.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (max($counts) > $limit): ?>
                        <nav aria-label="Halaman katalog akademik">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="<?= base_url('admin/katalog-akademik?page=' . ($page - 1)) ?>">Sebelumnya</a></li>
                                <?php endif; ?>
                                <?php if (max($counts) > $page * $limit): ?>
                                    <li class="page-item"><a class="page-link" href="<?= base_url('admin/katalog-akademik?page=' . ($page + 1)) ?>">Berikutnya</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    function searchOptions(inputSelector, selectSelector, endpoint, valueFormatter) {
        var timer = null;
        var request = null;
        $(inputSelector).on('input', function () {
            var query = $.trim($(this).val());
            window.clearTimeout(timer);
            if (request) {
                request.abort();
                request = null;
            }
            if (query.length < 2) {
                return;
            }
            timer = window.setTimeout(function () {
                request = $.getJSON(endpoint, {q: query})
                    .done(function (items) {
                        var select = $(selectSelector);
                        var selected = select.val();
                        select.empty().append($('<option>', {value: '', text: 'Pilih data'}));
                        $.each(items, function (_, item) {
                            var option = $('<option>', {value: item.id, text: valueFormatter(item)});
                            if (String(item.id) === String(selected)) {
                                option.prop('selected', true);
                            }
                            select.append(option);
                        });
                    })
                    .fail(function (xhr, status) {
                        if (status === 'abort') {
                            return;
                        }
                        var message = xhr.responseJSON && xhr.responseJSON.error
                            ? xhr.responseJSON.error
                            : 'Pencarian katalog gagal. Coba lagi.';
                        window.alert(message);
                    });
            }, 250);
        });
    }

    searchOptions(
        '#courseSearch',
        '#courseSelect',
        '<?= base_url('admin/katalog-akademik/search_courses') ?>',
        function (item) { return item.kode + ' - ' + item.nama; }
    );
    searchOptions(
        '#curriculumSearch',
        '#curriculumSelect',
        '<?= base_url('admin/katalog-akademik/search_curricula') ?>',
        function (item) { return item.kode + ' - ' + item.program_name; }
    );
    searchOptions(
        '#lecturerSearch',
        '#lecturerSelect',
        '<?= base_url('admin/katalog-akademik/search_lecturers') ?>',
        function (item) { return item.nama_lengkap + ' (' + item.nim + ')'; }
    );
    searchOptions(
        '#studentSearch',
        '#studentSelect',
        '<?= base_url('admin/katalog-akademik/search_students') ?>',
        function (item) { return item.nama_lengkap + ' (' + item.nim + ') - ' + item.prodi; }
    );

    $('#studentSelect').on('change', function () {
        var select = $('#studentCurriculumSelect');
        var selectedStudent = $(this).val();
        select.empty().append($('<option>', {value: '', text: 'Pilih kurikulum'}));
        if (!selectedStudent) {
            return;
        }
        $.getJSON('<?= base_url('admin/katalog-akademik/student_curricula_options') ?>', {mahasiswa_id: selectedStudent})
            .done(function (items) {
                $.each(items, function (_, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.kode + ' - ' + item.nama
                    }));
                });
            })
            .fail(function () {
                window.alert('Kurikulum mahasiswa tidak dapat dimuat. Muat ulang halaman dan coba kembali.');
            });
    });
});
</script>
