<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

                .krs-container {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .krs-card {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                    margin-bottom: 24px;
                    overflow: hidden;
                }

                .table-krs thead th {
                    background: #f8fafc;
                    color: #475569;
                    font-weight: 700;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                    border-top: none;
                    border-bottom: 1.5px solid #e2e8f0;
                    padding: 13px 16px;
                    white-space: nowrap;
                }

                .table-krs tbody td {
                    padding: 13px 16px;
                    vertical-align: middle;
                    color: #334155;
                    font-size: 13.5px;
                    border-top: 1px solid #f1f5f9;
                }

                .table-krs .badge {
                    display: inline-block;
                    white-space: nowrap;
                }

                @media (max-width: 767.98px) {
                    .table-krs {
                        min-width: 900px;
                    }
                }
                </style>

                <div class="krs-container">

                    <!-- Header Banner -->
                    <div class="krs-card" style="border-left: 5px solid #0284c7;">
                        <div style="padding: 22px 28px;">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-12">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #0284c7, #0369a1); display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                                            <i class="fa fa-th-large" style="font-size: 24px; color: #ffffff;"></i>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0 0 4px; font-weight: 800; color: #0f172a; font-size: 20px;">
                                                Pengambilan Rencana Studi (KRS)
                                            </h4>
                                            <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                                                Pemilihan paket mata kuliah semester aktif dan pengajuan persetujuan rencana studi ke Dosen Pembimbing Akademik.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12 text-lg-right mt-3 mt-lg-0">
                                    <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 10px;">
                                        <a href="<?= base_url('perwalian/frs') ?>" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px; font-weight: 600; font-size: 13px; padding: 9px 16px; background: #ffffff;">
                                            <i class="fa fa-file-text-o mr-1"></i> Lihat Dokumen FRS
                                        </a>
                                        <button type="button" class="btn btn-primary shadow-sm" onclick="alert('Rencana studi Anda telah berhasil tersimpan dan diajukan ke Dosen Wali.')" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 9px 18px; background: #0284c7; border-color: #0284c7;">
                                            <i class="fa fa-save mr-1"></i> Simpan Rencana Studi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $mhs_nama = $target_mahasiswa ? $target_mahasiswa->nama_lengkap : ($this->session->userdata('nama_lengkap') ?: 'Muhammad Eka');
                        $mhs_nim  = $target_mahasiswa ? $target_mahasiswa->nim : ($this->session->userdata('nim') ?: '210101001');
                        $mhs_prodi = $target_mahasiswa ? ($target_mahasiswa->prodi ?: 'D3 Sistem Informasi') : 'D3 Sistem Informasi';
                        $mhs_semester = $target_mahasiswa ? ($target_mahasiswa->semester ?: '5') : '5';
                    ?>

                    <!-- Summary Quota SKS -->
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <div class="krs-card p-3 mb-0">
                                <span class="text-muted" style="font-size: 12.5px; font-weight: 600;">Status Pembayaran SPP</span>
                                <h4 class="font-weight-bold mb-0 mt-1" style="color: #059669; font-size: 18px;">
                                    <i class="bi bi-patch-check-fill mr-1"></i> LUNAS / TERVERIFIKASI
                                </h4>
                                <small class="text-muted">Akses pengisian KRS terbuka penuh</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="krs-card p-3 mb-0">
                                <span class="text-muted" style="font-size: 12.5px; font-weight: 600;">Beban SKS Diambil</span>
                                <h4 class="font-weight-bold mb-0 mt-1" style="color: #0284c7; font-size: 18px;">
                                    21 SKS <small class="text-muted font-weight-normal">/ Maks. 24 SKS</small>
                                </h4>
                                <small class="text-success font-weight-bold">Beban studi optimal</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="krs-card p-3 mb-0">
                                <span class="text-muted" style="font-size: 12.5px; font-weight: 600;">Dosen Pembimbing Akademik</span>
                                <h4 class="font-weight-bold mb-0 mt-1" style="color: #0f172a; font-size: 16px;">
                                    Dr. Ir. H. Budi Santoso, M.Kom.
                                </h4>
                                <small class="text-muted">NIDN. 0412087501</small>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Pilihan Mata Kuliah -->
                    <div class="krs-card">
                        <div style="padding: 18px 24px; border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                            <h5 class="font-weight-bold mb-1" style="color: #0f172a; font-size: 16px;">
                                Paket Mata Kuliah Tersedia (Semester <?= htmlspecialchars($mhs_semester) ?>)
                            </h5>
                            <p class="text-muted mb-0" style="font-size: 13px;">
                                Centang mata kuliah yang ingin Anda ambil pada semester berjalan ini.
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-krs mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">Pilih</th>
                                        <th style="width: 100px;">Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th class="text-center" style="width: 80px;">SKS</th>
                                        <th class="text-center" style="width: 90px;">Kelas</th>
                                        <th>Jadwal Kuliah</th>
                                        <th>Ruangan</th>
                                        <th>Dosen Pengampu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI501</span></td>
                                        <td><strong style="color: #0f172a;">Pemrograman Web Lanjut</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Senin, 08.00 - 10.30</td>
                                        <td>Lab Komputer 2</td>
                                        <td>Budi Santoso, M.Kom.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI502</span></td>
                                        <td><strong style="color: #0f172a;">Rekayasa Perangkat Lunak</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Selasa, 10.00 - 12.30</td>
                                        <td>Ruang Teori 304</td>
                                        <td>Dr. Hendra Wijaya, M.T.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI503</span></td>
                                        <td><strong style="color: #0f172a;">Manajemen Basis Data Terdistribusi</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">B</td>
                                        <td>Rabu, 13.00 - 15.30</td>
                                        <td>Lab Basis Data</td>
                                        <td>Siti Rahma, M.Kom.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI504</span></td>
                                        <td><strong style="color: #0f172a;">Analisis &amp; Perancangan Sistem</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Kamis, 08.00 - 10.30</td>
                                        <td>Ruang Teori 201</td>
                                        <td>Agus Setiawan, M.Cs.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI505</span></td>
                                        <td><strong style="color: #0f172a;">Keamanan Sistem &amp; Jaringan</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Kamis, 13.00 - 15.30</td>
                                        <td>Lab Jaringan</td>
                                        <td>Rizky Kurniawan, M.T.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI506</span></td>
                                        <td><strong style="color: #0f172a;">Kewirausahaan Digital &amp; Start-up</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">C</td>
                                        <td>Jumat, 08.00 - 10.30</td>
                                        <td>Ruang Teori 402</td>
                                        <td>Maya Indah, S.E., M.M.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" checked style="width: 18px; height: 18px; accent-color: #0284c7;"></td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI507</span></td>
                                        <td><strong style="color: #0f172a;">Proyek Pengembangan Sistem Informasi</strong></td>
                                        <td class="text-center"><span class="badge badge-info" style="font-size: 11px;">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Jumat, 13.30 - 16.00</td>
                                        <td>Ruang Diskusi Proyek 1</td>
                                        <td>Tim Dosen Sistem Informasi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
