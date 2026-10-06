<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pusat Kendali Akademik</h5>
                            <span>Periode <?= html_escape($active_period['semester_akademik']) ?> <?= html_escape($active_period['tahun_akademik']) ?></span>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 h-100"><small class="text-muted d-block">Penawaran Aktif</small><strong class="h3"><?= (int)$overview['penawaran_aktif'] ?></strong></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 h-100"><small class="text-muted d-block">KRS Menunggu</small><strong class="h3 text-warning"><?= (int)$overview['krs']['MENUNGGU'] ?></strong></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 h-100"><small class="text-muted d-block">KRS Disetujui</small><strong class="h3 text-success"><?= (int)$overview['krs']['DISETUJUI'] ?></strong></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 h-100"><small class="text-muted d-block">KRS Ditolak</small><strong class="h3 text-danger"><?= (int)$overview['krs']['DITOLAK'] ?></strong></div>
                                </div>
                            </div>
                            <div class="alert alert-info mb-0">
                                Pengelolaan penawaran dan penetapan Dosen Wali dilakukan oleh Admin Biro Akademik.
                                Keputusan KRS dilakukan Dosen Wali yang ditetapkan, bukan oleh Superadmin.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
