<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header"><h5>Aktivitas Akademik Dosen</h5><span>Persetujuan rencana studi mahasiswa bimbingan.</span></div>
                        <div class="card-block">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <small class="text-muted d-block">KRS Menunggu Keputusan</small>
                                        <strong class="h3"><?= (int)$pending_krs_count ?></strong>
                                        <div><a href="<?= base_url('dosen/persetujuan-krs') ?>">Buka antrean persetujuan</a></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <small class="text-muted d-block">Mahasiswa Bimbingan</small>
                                        <a href="<?= base_url('dosen/mahasiswa-bimbingan') ?>">Lihat daftar mahasiswa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
