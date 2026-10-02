<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

                .frs-container {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .frs-card {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
                    margin-bottom: 24px;
                    overflow: hidden;
                }

                .table-frs thead th {
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

                .table-frs tbody td {
                    padding: 13px 16px;
                    vertical-align: middle;
                    color: #334155;
                    font-size: 13.5px;
                    border-top: 1px solid #f1f5f9;
                }

                .badge-sks {
                    background: #e0f2fe;
                    color: #0369a1;
                    border: 1px solid #bae6fd;
                    padding: 3px 8px;
                    border-radius: 6px;
                    font-size: 11.5px;
                    font-weight: 700;
                }

                @media print {
                    @page { size: A4 portrait; margin: 12mm; }
                    html, body { width: 100% !important; margin: 0 !important; padding: 0 !important; background: #fff !important; }
                    .pcoded-header, .pcoded-navbar, .pcoded-main-container, .no-print { display: none !important; }
                    .pcoded-content, .pcoded-inner-content, .main-body, .page-wrapper {
                        width: 100% !important; min-width: 0 !important; margin: 0 !important; padding: 0 !important;
                    }
                    .print-letterhead { display: block !important; }
                    .frs-card { box-shadow: none !important; border: 1px solid #cbd5e1 !important; border-radius: 0 !important; margin-bottom: 12px !important; }
                    .table-frs th, .table-frs td { padding: 6px 8px !important; font-size: 11px !important; }
                }
                </style>

                <!-- Kop Cetak FRS (Mode Print) -->
                <div class="print-letterhead mb-4" style="display:none; text-align:center; border-bottom:3px double #0f172a; padding-bottom:12px;">
                    <h3 style="margin:0; font-size:20px; font-weight:800; color:#0f172a;">UNIVERSITAS SMART CAMPUS</h3>
                    <h5 style="margin:4px 0; font-size:14px; font-weight:700; color:#334155;">BIRO ADMINISTRASI AKADEMIK &amp; KEMAHASISWAAN</h5>
                    <p style="margin:0; font-size:11px; color:#64748b;">Jl. Kampus Terpadu No. 123 | Telp: (021) 789-0123 | Email: akademik@smartcampus.ac.id</p>
                    <div style="margin-top:14px; font-size:15px; font-weight:800; text-decoration:underline;">FORMULIR RENCANA STUDI (FRS) MAHASISWA</div>
                    <div style="font-size:12px; color:#475569;">Tahun Akademik 2026/2027 &bull; Semester Ganjil</div>
                </div>

                <div class="frs-container">

                    <!-- Header Banner -->
                    <div class="frs-card no-print" style="border-left: 5px solid #0284c7;">
                        <div style="padding: 22px 28px;">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-12">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #0284c7, #0369a1); display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                                            <i class="fa fa-th-large" style="font-size: 24px; color: #ffffff;"></i>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0 0 4px; font-weight: 800; color: #0f172a; font-size: 20px;">
                                                Formulir Rencana Studi (FRS) Mahasiswa
                                            </h4>
                                            <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                                                Dokumen resmi rencana perkuliahan semester aktif yang telah divalidasi dan disetujui Dosen Pembimbing Akademik.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12 text-lg-right mt-3 mt-lg-0">
                                    <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 10px;">
                                        <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="btn btn-light shadow-sm" style="border-radius: 8px; font-weight: 600; font-size: 13px; padding: 9px 16px; border: 1px solid #cbd5e1;">
                                            <i class="fa fa-pencil mr-1"></i> Ubah Pilihan MK
                                        </a>
                                        <button onclick="window.print()" class="btn btn-primary shadow-sm" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 9px 18px; background: #0284c7; border-color: #0284c7;">
                                            <i class="fa fa-print mr-1"></i> Cetak FRS
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
                        $mhs_fakultas = $target_mahasiswa ? ($target_mahasiswa->fakultas ?: 'Fakultas Ilmu Komputer') : 'Fakultas Ilmu Komputer';
                        $mhs_semester = $target_mahasiswa ? ($target_mahasiswa->semester ?: '5') : '5';
                    ?>

                    <!-- Identitas Mahasiswa & Status Persetujuan -->
                    <div class="frs-card p-4 mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="font-weight-bold mb-3" style="color: #0f172a; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 8px;">
                                    <i class="fa fa-user-circle mr-2 text-primary"></i>Data Mahasiswa
                                </h6>
                                <table class="table table-sm table-borderless mb-0" style="font-size: 13.5px;">
                                    <tr>
                                        <td style="width: 140px; color: #64748b;">Nama Lengkap</td>
                                        <td style="width: 10px;">:</td>
                                        <td><strong style="color: #0f172a;"><?= htmlspecialchars($mhs_nama) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">Nomor Induk (NIM)</td>
                                        <td>:</td>
                                        <td><span style="font-family: monospace; font-weight: 600; color: #334155;"><?= htmlspecialchars($mhs_nim) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">Program Studi</td>
                                        <td>:</td>
                                        <td><?= htmlspecialchars($mhs_prodi) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">Fakultas</td>
                                        <td>:</td>
                                        <td><?= htmlspecialchars($mhs_fakultas) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold mb-3" style="color: #0f172a; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 8px;">
                                    <i class="fa fa-graduation-cap mr-2 text-primary"></i>Informasi Akademik &amp; Dosen Wali
                                </h6>
                                <table class="table table-sm table-borderless mb-0" style="font-size: 13.5px;">
                                    <tr>
                                        <td style="width: 150px; color: #64748b;">Tahun &amp; Semester</td>
                                        <td style="width: 10px;">:</td>
                                        <td><strong>2026/2027 &bull; Semester <?= htmlspecialchars($mhs_semester) ?> (Ganjil)</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">Dosen Pembimbing</td>
                                        <td>:</td>
                                        <td>Dr. Ir. H. Budi Santoso, M.Kom. <small class="text-muted">(NIDN: 0412087501)</small></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">IPK Kumulatif</td>
                                        <td>:</td>
                                        <td><strong style="color: #059669;">3.82</strong> <span class="text-muted">(Beban Maksimal: 24 SKS)</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">Status Validasi FRS</td>
                                        <td>:</td>
                                        <td>
                                            <span class="badge badge-success px-2 py-1" style="font-size: 12px; border-radius: 12px; font-weight: 700; background: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;">
                                                <i class="fa fa-check-circle mr-1"></i> Disetujui Dosen Wali &amp; Kaprodi
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Daftar Mata Kuliah yang Diambil -->
                    <div class="frs-card">
                        <div style="padding: 18px 24px; border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #0f172a; font-size: 16px;">
                                        Rincian Mata Kuliah Terpilih Semester 5
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Total beban studi yang diambil pada semester ini: <strong>21 SKS</strong> dari batas maksimal <strong>24 SKS</strong>.
                                    </p>
                                </div>
                                <span class="badge badge-primary px-3 py-2 mt-2 mt-sm-0" style="font-size: 13px; border-radius: 20px; font-weight: 700;">
                                    Total: 21 SKS
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-frs mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th style="width: 100px;">Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th class="text-center" style="width: 70px;">SKS</th>
                                        <th class="text-center" style="width: 80px;">Kelas</th>
                                        <th>Jadwal Kuliah</th>
                                        <th>Ruangan</th>
                                        <th>Dosen Pengampu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">1</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI501</span></td>
                                        <td><strong style="color: #0f172a;">Pemrograman Web Lanjut</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Senin, 08.00 - 10.30</td>
                                        <td>Lab Komputer 2</td>
                                        <td>Budi Santoso, M.Kom.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">2</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI502</span></td>
                                        <td><strong style="color: #0f172a;">Rekayasa Perangkat Lunak</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Selasa, 10.00 - 12.30</td>
                                        <td>Ruang Teori 304</td>
                                        <td>Dr. Hendra Wijaya, M.T.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">3</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI503</span></td>
                                        <td><strong style="color: #0f172a;">Manajemen Basis Data Terdistribusi</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">B</td>
                                        <td>Rabu, 13.00 - 15.30</td>
                                        <td>Lab Basis Data</td>
                                        <td>Siti Rahma, M.Kom.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">4</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI504</span></td>
                                        <td><strong style="color: #0f172a;">Analisis &amp; Perancangan Sistem</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Kamis, 08.00 - 10.30</td>
                                        <td>Ruang Teori 201</td>
                                        <td>Agus Setiawan, M.Cs.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">5</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI505</span></td>
                                        <td><strong style="color: #0f172a;">Keamanan Sistem &amp; Jaringan</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Kamis, 13.00 - 15.30</td>
                                        <td>Lab Jaringan</td>
                                        <td>Rizky Kurniawan, M.T.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">6</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI506</span></td>
                                        <td><strong style="color: #0f172a;">Kewirausahaan Digital &amp; Start-up</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">C</td>
                                        <td>Jumat, 08.00 - 10.30</td>
                                        <td>Ruang Teori 402</td>
                                        <td>Maya Indah, S.E., M.M.</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">7</td>
                                        <td><span style="font-family: monospace; font-weight: 700; color: #0369a1;">SI507</span></td>
                                        <td><strong style="color: #0f172a;">Proyek Pengembangan Sistem Informasi</strong></td>
                                        <td class="text-center"><span class="badge-sks">3 SKS</span></td>
                                        <td class="text-center font-weight-bold">A</td>
                                        <td>Jumat, 13.30 - 16.00</td>
                                        <td>Ruang Diskusi Proyek 1</td>
                                        <td>Tim Dosen Sistem Informasi</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f8fafc; font-weight: 700;">
                                        <td colspan="3" class="text-right pr-3">Total Beban SKS Semester Ini:</td>
                                        <td class="text-center" style="color: #0284c7; font-size: 15px;">21 SKS</td>
                                        <td colspan="4" class="text-muted font-weight-normal" style="font-size: 12px;">(Telah memenuhi syarat minimum 12 SKS dan tidak melampaui batas 24 SKS)</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Blok Tanda Tangan Pengesahan (Muncul di Print dan Web) -->
                    <div class="frs-card p-4 mt-4">
                        <div class="row text-center" style="font-size: 13px;">
                            <div class="col-4">
                                <p class="mb-1 text-muted">Mahasiswa Yang Bersangkutan,</p>
                                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #059669; font-size: 11px; font-weight: 700; border: 1px dashed #10b981; padding: 4px 10px; border-radius: 6px;">
                                        <i class="fa fa-check mr-1"></i> TTD Digital Mahasiswa
                                    </span>
                                </div>
                                <strong style="color: #0f172a; text-decoration: underline;"><?= htmlspecialchars($mhs_nama) ?></strong>
                                <div class="text-muted" style="font-size: 12px;">NIM. <?= htmlspecialchars($mhs_nim) ?></div>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 text-muted">Dosen Pembimbing Akademik,</p>
                                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #0284c7; font-size: 11px; font-weight: 700; border: 1px dashed #0284c7; padding: 4px 10px; border-radius: 6px;">
                                        <i class="fa fa-check-circle mr-1"></i> Telah Disetujui Dosen Wali
                                    </span>
                                </div>
                                <strong style="color: #0f172a; text-decoration: underline;">Dr. Ir. H. Budi Santoso, M.Kom.</strong>
                                <div class="text-muted" style="font-size: 12px;">NIDN. 0412087501</div>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 text-muted">Ketua Program Studi,</p>
                                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #0284c7; font-size: 11px; font-weight: 700; border: 1px dashed #0284c7; padding: 4px 10px; border-radius: 6px;">
                                        <i class="fa fa-check-circle mr-1"></i> Telah Divalidasi Prodi
                                    </span>
                                </div>
                                <strong style="color: #0f172a; text-decoration: underline;">Dr. Fajar Nugroho, M.Kom.</strong>
                                <div class="text-muted" style="font-size: 12px;">NIDN. 0423048202</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
