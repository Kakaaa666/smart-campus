<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="card" style="border-radius: 14px; border: 1px solid #fee2e2;">
                    <div class="card-block text-center p-5">
                        <div class="mb-3" style="font-size: 42px; color: #dc2626;">
                            <i class="feather icon-lock"></i>
                        </div>
                        <h4 class="font-weight-bold mb-2">Akses Akademik Belum Terbuka</h4>
                        <p class="text-muted mx-auto mb-3" style="max-width: 620px;">
                            <?= html_escape($status_krs['pesan'] ?? 'Lunasi tagihan wajib UKT/SPP semester aktif untuk mengakses akademik.') ?>
                        </p>
                        <span class="badge badge-danger mb-4">
                            Status pembayaran: <?= html_escape($status_krs['status'] ?? 'BELUM_BAYAR') ?>
                        </span>
                        <div>
                            <a href="<?= base_url('mahasiswa/keuangan') ?>" class="btn btn-primary">
                                <i class="feather icon-credit-card mr-1"></i> Buka Keuangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
