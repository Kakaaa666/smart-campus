<div class="page-body">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Permohonan Menunggu</h5></div>
            <div class="card-block table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Mahasiswa</th><th>Tagihan</th><th>Jatuh Tempo</th><th>Diminta</th><th>Alasan</th><th>Keputusan</th></tr></thead>
                    <tbody>
                    <?php if (empty($dispensasi_menunggu)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada permohonan dispensasi yang menunggu.</td></tr>
                    <?php else: foreach ($dispensasi_menunggu as $item): ?>
                        <tr>
                            <td><strong><?= html_escape($item->nama_lengkap) ?></strong><br><small><?= html_escape($item->nim) ?> · <?= html_escape($item->prodi) ?></small></td>
                            <td><?= html_escape($item->jenis_tagihan) ?><br><small>Rp <?= number_format((float)$item->nominal, 0, ',', '.') ?></small></td>
                            <td><?= html_escape($item->jatuh_tempo_sekarang) ?></td>
                            <td><strong><?= html_escape($item->tanggal_jatuh_tempo_diminta) ?></strong></td>
                            <td><?= nl2br(html_escape($item->alasan)) ?></td>
                            <td>
                                <?= form_open('keuangan/proses_dispensasi', ['class' => 'd-flex flex-column', 'style' => 'gap:6px; min-width:170px;']) ?>
                                    <input type="hidden" name="dispensasi_id" value="<?= (int)$item->id ?>">
                                    <input type="text" name="catatan_admin" class="form-control form-control-sm" maxlength="500" placeholder="Catatan (opsional)">
                                    <div class="d-flex" style="gap:6px;">
                                        <button class="btn btn-success btn-sm" name="keputusan" value="setujui" type="submit" onclick="return confirm('Setujui dispensasi dan ubah jatuh tempo tagihan?')">Setujui</button>
                                        <button class="btn btn-outline-danger btn-sm" name="keputusan" value="tolak" type="submit" onclick="return confirm('Tolak permohonan dispensasi ini?')">Tolak</button>
                                    </div>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h5 class="mb-0">Riwayat Keputusan</h5></div>
            <div class="card-block table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Mahasiswa</th><th>Tagihan</th><th>Jatuh Tempo Baru</th><th>Status</th><th>Catatan</th><th>Diproses</th></tr></thead>
                    <tbody>
                    <?php $has_history = false; foreach ($dispensasi_selesai as $item): if ($item->status === 'MENUNGGU') continue; $has_history = true; ?>
                        <tr>
                            <td><?= html_escape($item->nama_lengkap) ?> <small>(<?= html_escape($item->nim) ?>)</small></td>
                            <td><?= html_escape($item->jenis_tagihan) ?></td>
                            <td><?= html_escape($item->tanggal_jatuh_tempo_diminta) ?></td>
                            <td><span class="badge <?= $item->status === 'DISETUJUI' ? 'badge-success' : 'badge-danger' ?>"><?= html_escape($item->status) ?></span></td>
                            <td><?= html_escape($item->catatan_admin ?: '-') ?></td>
                            <td><?= html_escape($item->diproses_at ?: '-') ?></td>
                        </tr>
                    <?php endforeach; if (!$has_history): ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada keputusan dispensasi.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
