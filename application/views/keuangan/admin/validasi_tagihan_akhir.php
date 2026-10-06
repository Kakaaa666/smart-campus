<div class="page-body">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
        <?php endif; ?>

        <div class="alert alert-info">
            Sistem memasukkan mahasiswa yang sudah mencapai semester akhir sesuai jenjang ke antrean ini. Tagihan semester akhir belum dibuat sampai Admin Keuangan menyetujui.
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Menunggu Validasi Keuangan</h5></div>
            <div class="card-block table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Mahasiswa</th><th>Program Studi</th><th>Semester</th><th>Periode Tagihan</th><th>Masuk Antrean</th><th>Validasi</th></tr></thead>
                    <tbody>
                    <?php if (empty($antrian_tagihan_akhir)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada mahasiswa semester akhir yang menunggu validasi.</td></tr>
                    <?php else: foreach ($antrian_tagihan_akhir as $item): ?>
                        <tr>
                            <td><strong><?= html_escape($item->nama_lengkap) ?></strong><br><small><?= html_escape($item->nim) ?> · <?= html_escape($item->email) ?></small></td>
                            <td><?= html_escape($item->fakultas) ?><br><small><?= html_escape($item->prodi) ?></small></td>
                            <td><?= (int)$item->semester_mahasiswa ?></td>
                            <td><?= html_escape($item->semester) ?> <?= html_escape($item->tahun_akademik) ?></td>
                            <td><?= html_escape($item->diajukan_at) ?></td>
                            <td>
                                <?= form_open('keuangan/proses_validasi_tagihan_akhir', ['class' => 'd-flex flex-column', 'style' => 'gap:6px; min-width:170px;']) ?>
                                    <input type="hidden" name="validasi_id" value="<?= (int)$item->id ?>">
                                    <input type="text" name="catatan" class="form-control form-control-sm" maxlength="500" placeholder="Catatan (opsional)">
                                    <div class="d-flex" style="gap:6px;">
                                        <button class="btn btn-success btn-sm" name="keputusan" value="setujui" type="submit" data-sc-confirm="Setujui dan buat tagihan semester akhir untuk mahasiswa ini?">Setujui</button>
                                        <button class="btn btn-outline-danger btn-sm" name="keputusan" value="tolak" type="submit" data-sc-confirm="Tolak validasi semester akhir ini?">Tolak</button>
                                    </div>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
