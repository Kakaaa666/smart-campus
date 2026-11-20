<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($title ?? 'Cetak Dokumen SKPI') ?></title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 20px;
            font-size: 11pt;
            line-height: 1.5;
        }

        /* Top Action Bar (Hanya tampil di layar monitor) */
        .no-print-bar {
            max-width: 210mm;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e2e8f0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-print {
            background: #0288d1;
            color: #ffffff;
        }
        .btn-print:hover {
            background: #026aa2;
        }

        .btn-back {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #e2e8f0;
        }

        /* Kertas A4 Siap Cetak */
        .page-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 18mm 20mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border-radius: 4px;
            position: relative;
        }

        /* KOP SURAT FORMAL */
        .kop-container {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 18px;
            position: relative;
        }

        .kop-container::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 1px;
            background: #0f172a;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 18px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-kementerian {
            font-size: 10pt;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #334155;
            margin: 0;
        }

        .kop-universitas {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0288d1;
            margin: 2px 0;
        }

        .kop-fakultas {
            font-size: 11pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0;
        }

        .kop-prodi {
            font-size: 10.5pt;
            font-weight: 600;
            color: #1e293b;
            margin: 1px 0;
        }

        .kop-alamat {
            font-size: 8pt;
            color: #64748b;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }

        /* JUDUL DOKUMEN */
        .doc-title-container {
            text-align: center;
            margin: 16px 0 20px 0;
        }

        .doc-main-title {
            font-size: 12pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
            margin: 0;
            text-decoration: underline;
        }

        .doc-en-title {
            font-size: 9.5pt;
            font-style: italic;
            font-weight: 500;
            color: #475569;
            margin: 2px 0;
        }

        .doc-reg-number {
            font-size: 9.5pt;
            font-weight: 600;
            color: #0f172a;
            margin-top: 4px;
        }

        /* IDENTITAS MAHASISWA (GRID) */
        .identity-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 9pt;
        }

        .identity-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            row-gap: 6px;
            column-gap: 20px;
        }

        .identity-row {
            display: flex;
            align-items: baseline;
        }

        .id-label {
            width: 140px;
            color: #475569;
            font-weight: 500;
            flex-shrink: 0;
        }

        .id-label-en {
            display: block;
            font-size: 7.5pt;
            font-style: italic;
            color: #94a3b8;
        }

        .id-sep {
            width: 15px;
            text-align: center;
            color: #64748b;
            flex-shrink: 0;
        }

        .id-val {
            flex: 1;
            font-weight: 700;
            color: #0f172a;
        }

        /* SECTION HEADER */
        .section-heading {
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #0f172a;
            border-left: 3.5px solid #0288d1;
            padding-left: 8px;
            margin: 18px 0 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-heading-en {
            font-size: 8.5pt;
            font-style: italic;
            color: #64748b;
            font-weight: 500;
            text-transform: none;
        }

        /* TABEL CPL RESMI */
        .table-cpl {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 16px;
        }

        .table-cpl th, 
        .table-cpl td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .table-cpl thead th {
            background-color: #f1f5f9 !important;
            color: #0f172a;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.2px;
        }

        .cpl-code {
            font-weight: 700;
            color: #0288d1;
            text-align: center;
            white-space: nowrap;
        }

        .cpl-category {
            font-size: 8pt;
            font-weight: 600;
            color: #475569;
            text-align: center;
            white-space: nowrap;
        }

        .cpl-desc {
            color: #1e293b;
            line-height: 1.4;
            text-align: justify;
        }

        .cpl-score {
            font-weight: 800;
            text-align: center;
            white-space: nowrap;
            font-size: 9pt;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
        }

        .status-tercapai {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            border: 1px solid #86efac;
        }

        .status-peningkatan {
            background-color: #fef3c7 !important;
            color: #b45309 !important;
            border: 1px solid #fde68a;
        }

        /* SUMMARY & SIGNATURE FOOTER */
        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 20px;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .summary-notes {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 8pt;
            color: #475569;
            line-height: 1.4;
        }

        .summary-notes strong {
            color: #0f172a;
        }

        .signature-box {
            text-align: center;
            font-size: 8.5pt;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .signature-date {
            margin-bottom: 6px;
            color: #334155;
        }

        .signature-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 60px; /* Ruang untuk tanda tangan & cap stempel */
        }

        .signature-name {
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
            font-size: 9pt;
        }

        .signature-nip {
            color: #475569;
            font-size: 8pt;
            margin-top: 2px;
        }

        /* PRINT STYLING */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }

            .no-print-bar {
                display: none !important;
            }

            .page-sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Navigasi & Cetak (Layar Saja) -->
    <div class="no-print-bar">
        <div>
            <a href="<?= base_url('mahasiswa/capaian') ?>" class="btn-action btn-back">
                <i class="fa fa-arrow-left"></i> Kembali ke Portofolio
            </a>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <span style="font-size: 12px; color: #64748b;">Gunakan kertas <strong>A4 Portrait</strong> dengan margin standar</span>
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fa fa-print"></i> Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen SKPI / Portofolio CPL -->
    <div class="page-sheet">

        <!-- KOP SURAT RESMI KAMPUS & PRODI -->
        <div class="kop-container">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Kampus" class="kop-logo" onerror="this.src='https://ui-avatars.com/api/?name=SC&background=0288d1&color=fff&size=100';">
            <div class="kop-text">
                <p class="kop-kementerian">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</p>
                <h1 class="kop-universitas">UNIVERSITAS SMART CAMPUS INDONESIA</h1>
                <h2 class="kop-fakultas"><?= html_escape($prodi->fakultas ?? 'FAKULTAS TEKNOLOGI INFORMASI') ?></h2>
                <h3 class="kop-prodi">PROGRAM STUDI <?= strtoupper(html_escape($prodi->nama_prodi ?? 'TEKNIK INFORMATIKA')) ?></h3>
                <p class="kop-alamat">
                    Jl. Kampus Terpadu No. 10, Kompleks Pendidikan Siber &bull; Telp: (021) 7891234 &bull; Laman: https://smart-campus.ac.id &bull; Surel: fti@smart-campus.ac.id
                </p>
            </div>
        </div>

        <!-- JUDUL SURAT -->
        <div class="doc-title-container">
            <h2 class="doc-main-title">SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)</h2>
            <div class="doc-en-title">DIPLOMA SUPPLEMENT &amp; COMPETENCY PORTFOLIO (OBE)</div>
            <div class="doc-reg-number">Nomor Registrasi: <?= html_escape($nomor_skpi) ?></div>
        </div>

        <!-- IDENTITAS PEMEGANG SKPI -->
        <div class="identity-box">
            <div class="identity-grid">
                <div class="identity-row">
                    <div class="id-label">
                        Nama Lengkap
                        <span class="id-label-en">Full Name</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val"><?= html_escape($student->nama_lengkap) ?></div>
                </div>
                <div class="identity-row">
                    <div class="id-label">
                        Program Studi
                        <span class="id-label-en">Study Program</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val"><?= html_escape($prodi->nama_prodi ?? 'Teknik Informatika') ?></div>
                </div>

                <div class="identity-row">
                    <div class="id-label">
                        Nomor Induk (NIM)
                        <span class="id-label-en">Student ID Number</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val"><?= html_escape($student->nim) ?></div>
                </div>
                <div class="identity-row">
                    <div class="id-label">
                        Jenjang Kualifikasi
                        <span class="id-label-en">Qualification Level</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val">Sarjana (S1) / KKNI Level 6</div>
                </div>

                <div class="identity-row">
                    <div class="id-label">
                        Tahun Masuk / Angkatan
                        <span class="id-label-en">Year of Entry</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val"><?= html_escape($student->angkatan ?? '2023') ?></div>
                </div>
                <div class="identity-row">
                    <div class="id-label">
                        Sistem Penilaian
                        <span class="id-label-en">Grading Framework</span>
                    </div>
                    <div class="id-sep">:</div>
                    <div class="id-val">Outcome-Based Education (OBE)</div>
                </div>
            </div>
        </div>

        <!-- DAFTAR CAPAIAN PEMBELAJARAN LULUSAN (CPL) -->
        <div class="section-heading">
            <span>I. Capaian Pembelajaran Lulusan (Learning Outcomes Attainment)</span>
            <span class="section-heading-en">Standar Kelulusan Minimum: 60.0%</span>
        </div>

        <table class="table-cpl">
            <thead>
                <tr>
                    <th style="width: 35px;">No.</th>
                    <th style="width: 75px;">Kode CPL</th>
                    <th style="width: 105px;">Dimensi CPL</th>
                    <th>Deskripsi Kemampuan Kerja Lulusan</th>
                    <th style="width: 80px;">Capaian (%)</th>
                    <th style="width: 100px;">Status Kualifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $details = $cpl_data['details'] ?? [];
                    $no = 1;
                    $total_cpl = count($details);
                    $passed_count = 0;
                    $score_sum = 0;
                ?>
                <?php if (!empty($details)): ?>
                    <?php foreach ($details as $d): ?>
                        <?php 
                            $skor = (float)$d['capaian'];
                            $score_sum += $skor;
                            $is_passed = ($skor >= 60.0);
                            if ($is_passed) $passed_count++;
                        ?>
                        <tr>
                            <td style="text-align: center; font-weight: 700;"><?= $no++ ?></td>
                            <td class="cpl-code"><?= html_escape($d['kode_cpl']) ?></td>
                            <td class="cpl-category"><?= html_escape($d['kategori']) ?></td>
                            <td class="cpl-desc"><?= html_escape($d['deskripsi']) ?></td>
                            <td class="cpl-score" style="color: <?= $is_passed ? '#0288d1' : '#b45309' ?>;">
                                <?= number_format($skor, 1) ?>%
                            </td>
                            <td style="text-align: center;">
                                <?php if ($is_passed): ?>
                                    <span class="status-badge status-tercapai">
                                        <i class="fa fa-check"></i> Tercapai
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-peningkatan">
                                        <i class="fa fa-clock-o"></i> Perlu Ditingkatkan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #94a3b8;">
                            Data capaian CPL sedang dalam pemrosesan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- FOOTER: CATATAN RESMI & PENGESAHAN KAPRODI -->
        <div class="footer-grid">
            <!-- Catatan Metrik & Verifikasi Dokumen -->
            <div class="summary-notes">
                <strong>Catatan Keterangan &amp; Validasi Kompetensi:</strong>
                <ul style="margin: 4px 0 0 0; padding-left: 16px;">
                    <li>Capaian CPL dihitung secara <em>bottom-up</em> dari seluruh nilai evaluasi Sub-CPMK mata kuliah kurikulum OBE yang telah ditempuh.</li>
                    <li>Ambang batas kelulusan kompetensi minimal Program Studi adalah <strong>60.0%</strong>.</li>
                    <li>Rangkuman Capaian: <strong><?= $passed_count ?> dari <?= $total_cpl ?> CPL Tercapai</strong> (Rata-rata Akumulatif: <strong><?= $total_cpl > 0 ? number_format($score_sum / $total_cpl, 1) : 0 ?>%</strong>).</li>
                    <li>Dokumen ini merupakan lampiran resmi ijazah yang sah dan dapat diverifikasi melalui Sistem Informasi Akademik Smart Campus.</li>
                </ul>
            </div>

            <!-- Tanda Tangan & Pengesahan Kaprodi -->
            <div class="signature-box">
                <div class="signature-date">
                    Jakarta, <?= html_escape($tanggal_cetak) ?>
                </div>
                <div class="signature-title">
                    Ketua Program Studi <?= html_escape($prodi->nama_prodi ?? 'Teknik Informatika') ?>
                </div>
                <!-- Ruang tanda tangan dan stempel formal -->
                <div class="signature-name">
                    <?= html_escape($kaprodi->nama_lengkap ?? 'Sugeng Widodo, S.Kom., M.Kom.') ?>
                </div>
                <div class="signature-nip">
                    NIP. <?= html_escape($kaprodi->nip ?? '198203152008121003') ?>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
