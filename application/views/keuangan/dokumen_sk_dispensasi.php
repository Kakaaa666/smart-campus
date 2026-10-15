<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keputusan Dispensasi - <?= htmlspecialchars($pembayaran->nomor_referensi ?? 'SK-DISP') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Action Bar (Hanya tampil di browser, hilang saat diprint) */
        .top-action-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-badge-top {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .status-badge-top .pulse-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 1.8s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .bar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .btn-print {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        }

        .btn-print:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .btn-close-window {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .btn-close-window:hover {
            background: #f1f5f9;
            color: #334155;
        }

        /* Container Dokumen (Format A4) */
        .document-wrapper {
            max-width: 860px;
            margin: 30px auto 60px auto;
            padding: 0 16px;
        }

        .document-page {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 50px 60px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            font-weight: 900;
            color: rgba(2, 132, 199, 0.035);
            letter-spacing: 8px;
            text-transform: uppercase;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }

        .document-content {
            position: relative;
            z-index: 1;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 16px;
        }

        .kop-logo {
            width: 84px;
            height: 84px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-instansi-1 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #475569;
            text-transform: uppercase;
        }

        .kop-instansi-2 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #0f172a;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .kop-biro {
            font-size: 14.5px;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kop-alamat {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Garis Pemisah Kop */
        .kop-divider {
            border: none;
            margin-bottom: 24px;
        }

        .kop-line-thick {
            height: 3px;
            background: #0f172a;
            margin-bottom: 2px;
        }

        .kop-line-thin {
            height: 1px;
            background: #0f172a;
        }

        /* Header Judul SK */
        .sk-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .sk-title {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-decoration: underline;
            text-underline-offset: 4px;
            margin-bottom: 6px;
        }

        .sk-number {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            letter-spacing: 0.3px;
            margin-bottom: 10px;
        }

        .sk-tentang-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .sk-tentang-title {
            font-size: 14px;
            font-weight: 800;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        /* Paragraf Pembuka */
        .sk-section-p {
            font-size: 13.5px;
            color: #334155;
            text-align: justify;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        /* Tabel Data Mahasiswa */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data td {
            padding: 5px 8px;
            font-size: 13px;
            vertical-align: top;
        }

        .table-data td.col-label {
            width: 220px;
            color: #64748b;
            font-weight: 600;
        }

        .table-data td.col-separator {
            width: 12px;
            color: #64748b;
        }

        .table-data td.col-value {
            color: #0f172a;
            font-weight: 600;
        }

        /* Tabel Rincian Keputusan Keuangan */
        .table-rincian-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            font-size: 12.5px;
        }

        .table-rincian th {
            background: #f1f5f9;
            color: #1e293b;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .table-rincian td {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            color: #334155;
            vertical-align: middle;
        }

        .table-rincian tr.highlight-total td {
            background: #ecfdf5;
            font-weight: 700;
            color: #065f46;
        }

        /* Catatan Pertimbangan */
        .notes-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 12.5px;
            color: #166534;
        }

        .notes-box strong {
            display: block;
            margin-bottom: 4px;
            color: #14532d;
            font-size: 13px;
        }

        /* Area Tanda Tangan & QR Code */
        .signature-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding-top: 10px;
        }

        .signature-left {
            width: 45%;
        }

        .qr-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .qr-image {
            width: 78px;
            height: 78px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #ffffff;
            padding: 3px;
            flex-shrink: 0;
        }

        .qr-text {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }

        .qr-text strong {
            color: #0f172a;
            display: block;
            margin-bottom: 3px;
            font-size: 11.5px;
        }

        .signature-right {
            width: 48%;
            text-align: center;
        }

        .sign-date {
            font-size: 13px;
            color: #334155;
            margin-bottom: 6px;
        }

        .sign-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .stamp-signature-wrapper {
            position: relative;
            height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Stempel Digital Resmi */
        .digital-stamp {
            position: absolute;
            left: 20%;
            top: 50%;
            transform: translateY(-50%) rotate(-12deg);
            width: 105px;
            height: 105px;
            border: 2.5px dashed #0284c7;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #0284c7;
            opacity: 0.85;
            pointer-events: none;
            padding: 4px;
        }

        .stamp-inner {
            border: 1px solid #0284c7;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .stamp-text-univ {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .stamp-status {
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.5px;
            border-top: 1px solid #0284c7;
            border-bottom: 1px solid #0284c7;
            padding: 1px 4px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .stamp-date {
            font-size: 8px;
            font-weight: 700;
        }

        .digital-sign-badge {
            background: rgba(238, 242, 255, 0.7);
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 11.5px;
            color: #4338ca;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 2;
        }

        .sign-officer-name {
            font-size: 13.5px;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
            text-underline-offset: 3px;
            margin-top: 8px;
        }

        .sign-officer-nip {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Footer Dokumen */
        .doc-footer {
            margin-top: 36px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-size: 10.5px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff;
                color: #000000;
            }

            .top-action-bar {
                display: none !important;
            }

            .document-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .document-page {
                border: none !important;
                box-shadow: none !important;
                padding: 20mm 20mm 15mm 20mm !important;
                border-radius: 0 !important;
            }

            .watermark {
                color: rgba(0, 0, 0, 0.03) !important;
            }

            .table-data td, .table-rincian td, .table-rincian th {
                color: #000000 !important;
            }

            .notes-box {
                background: #f8fafc !important;
                border-left-color: #0f172a !important;
                color: #000000 !important;
            }

            .btn-action {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Non-Print) -->
    <div class="top-action-bar">
        <div class="bar-left">
            <span class="status-badge-top">
                <span class="pulse-dot"></span>
                <i class="bi bi-shield-fill-check"></i> SK Sah & Terverifikasi
            </span>
            <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                No. Referensi: <strong style="color: #1e293b;"><?= htmlspecialchars($pembayaran->nomor_referensi ?? '-') ?></strong>
            </span>
        </div>
        <div class="bar-right">
            <button type="button" class="btn-action btn-print" onclick="window.print()">
                <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
            </button>
            <button type="button" class="btn-action btn-close-window" onclick="window.close()">
                <i class="bi bi-x-lg"></i> Tutup
            </button>
        </div>
    </div>

    <!-- Main Container Format Surat Resmi -->
    <div class="document-wrapper">
        <div class="document-page">
            <div class="watermark">SMART CAMPUS</div>

            <div class="document-content">
                <!-- Kop Surat Resmi -->
                <div class="kop-surat">
                    <img src="<?= base_url('assets/images/smart-campus-icon.png') ?>" alt="Logo Universitas" class="kop-logo" onerror="this.src='<?= base_url('assets/images/favicon.png') ?>'">
                    <div class="kop-text">
                        <div class="kop-instansi-1">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
                        <div class="kop-instansi-2">Universitas Smart Campus Indonesia</div>
                        <div class="kop-biro">Biro Keuangan dan Administrasi Umum (BKA)</div>
                        <div class="kop-alamat">
                            Gedung Rektorat Lt. 2, Kampus Terpadu Smart Campus | Telp: (021) 7788-9900 | Fax: (021) 7788-9901<br>
                            Laman: https://smartcampus.ac.id | Pos-el: biro.keuangan@smartcampus.ac.id
                        </div>
                    </div>
                </div>

                <div class="kop-divider">
                    <div class="kop-line-thick"></div>
                    <div class="kop-line-thin"></div>
                </div>

                <?php
                    $waktu_verif = !empty($pembayaran->diverifikasi_at) ? $pembayaran->diverifikasi_at : (!empty($dispensasi->diproses_at) ? $dispensasi->diproses_at : $pembayaran->tanggal_pembayaran);
                    $tahun_sk = date('Y', strtotime($waktu_verif));
                    $bulan_sk = date('m', strtotime($waktu_verif));
                    $disp_id = !empty($dispensasi->id) ? (int)$dispensasi->id : (int)$pembayaran->id;
                    $nomor_sk_resmi = sprintf("SK-DISP/%s/%s/%04d", $tahun_sk, $bulan_sk, $disp_id);

                    $bulan_indo = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    $ts_verif = strtotime($waktu_verif);
                    $tgl_indo_verif = date('d', $ts_verif) . ' ' . ($bulan_indo[(int)date('m', $ts_verif)] ?? '') . ' ' . date('Y', $ts_verif);

                    $jenis_keringanan_txt = 'Pembebasan Biaya Kuliah (100%)';
                    if (!empty($dispensasi->jenis_keringanan)) {
                        switch (strtoupper($dispensasi->jenis_keringanan)) {
                            case 'BEBAS_SPP':
                                $jenis_keringanan_txt = 'Pembebasan Biaya Kuliah Penuh (Bebas Biaya 100%)';
                                break;
                            case 'POTONGAN':
                            case 'PENGURANGAN':
                                $jenis_keringanan_txt = 'Potongan / Pengurangan Nominal Tagihan';
                                break;
                            case 'PENUNDAAN':
                                $jenis_keringanan_txt = 'Penundaan Batas Jatuh Tempo Pembayaran';
                                break;
                            case 'ANGSURAN':
                                $jenis_keringanan_txt = 'Skema Pembayaran Angsuran Bertahap';
                                break;
                            default:
                                $jenis_keringanan_txt = htmlspecialchars($dispensasi->jenis_keringanan);
                                break;
                        }
                    }
                ?>

                <!-- Judul Surat Keputusan -->
                <div class="sk-header">
                    <div class="sk-title">SURAT KEPUTUSAN KEPALA BIRO KEUANGAN</div>
                    <div class="sk-number">Nomor: <?= $nomor_sk_resmi ?></div>
                    <div class="sk-tentang-label">Tentang</div>
                    <div class="sk-tentang-title">
                        PENETAPAN PERSETUJUAN DISPENSASI & PEMBEBASAN KEWAJIBAN BIAYA PENDIDIKAN<br>
                        TAHUN AKADEMIK <?= htmlspecialchars($tagihan->tahun_akademik ?? '2026/2027') ?>
                    </div>
                </div>

                <div class="sk-section-p">
                    Berdasarkan hasil evaluasi administratif dan verifikasi data permohonan keringanan/dispensasi biaya kuliah oleh Biro Keuangan Universitas Smart Campus Indonesia, dengan ini Kepala Biro Keuangan menetapkan persetujuan dispensasi pembebasan kewajiban pembayaran tagihan bagi mahasiswa di bawah ini:
                </div>

                <!-- Box Identitas Mahasiswa -->
                <div class="info-box">
                    <table class="table-data">
                        <tr>
                            <td class="col-label">Nama Lengkap Mahasiswa</td>
                            <td class="col-separator">:</td>
                            <td class="col-value"><?= htmlspecialchars(strtoupper($mahasiswa->nama_lengkap ?? 'MAHASISWA')) ?></td>
                        </tr>
                        <tr>
                            <td class="col-label">Nomor Induk Mahasiswa (NIM)</td>
                            <td class="col-separator">:</td>
                            <td class="col-value" style="font-family: monospace; font-size: 14px; letter-spacing: 0.5px;"><?= htmlspecialchars($mahasiswa->nim ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="col-label">Program Studi / Fakultas</td>
                            <td class="col-separator">:</td>
                            <td class="col-value"><?= htmlspecialchars($mahasiswa->prodi ?? '-') ?> / <?= htmlspecialchars($mahasiswa->fakultas ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="col-label">Semester / Tahun Akademik</td>
                            <td class="col-separator">:</td>
                            <td class="col-value">Semester <?= htmlspecialchars($tagihan->semester ?? $mahasiswa->semester ?? 'Aktif') ?> (T.A. <?= htmlspecialchars($tagihan->tahun_akademik ?? '2026/2027') ?>)</td>
                        </tr>
                        <tr>
                            <td class="col-label">Nomor Registrasi Referensi</td>
                            <td class="col-separator">:</td>
                            <td class="col-value" style="color: #0369a1; font-family: monospace;"><?= htmlspecialchars($pembayaran->nomor_referensi ?? '-') ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Tabel Rincian Penetapan Keuangan -->
                <div class="table-rincian-title">
                    <i class="bi bi-file-earmark-ruled-fill text-primary"></i> Rincian Penetapan Keputusan Dispensasi:
                </div>
                <table class="table-rincian">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">No</th>
                            <th>Komponen Tagihan</th>
                            <th style="text-align: right;">Nominal Tagihan</th>
                            <th>Jenis Keringanan / SK</th>
                            <th style="text-align: right;">Nominal Disetujui</th>
                            <th style="text-align: center;">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center; font-weight: 600;">1</td>
                            <td>
                                <strong><?= htmlspecialchars($tagihan->jenis_tagihan ?? 'Tagihan Pendidikan') ?></strong>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    T.A. <?= htmlspecialchars($tagihan->tahun_akademik ?? '-') ?> • Sem. <?= htmlspecialchars($tagihan->semester ?? '-') ?>
                                </div>
                            </td>
                            <td style="text-align: right; font-weight: 600; font-family: monospace;">
                                Rp <?= number_format((float)($tagihan->nominal ?? $pembayaran->nominal_pembayaran ?? 0), 0, ',', '.') ?>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #047857;"><?= $jenis_keringanan_txt ?></span>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #047857; font-family: monospace;">
                                Rp <?= number_format((float)($pembayaran->nominal_pembayaran ?? $tagihan->nominal ?? 0), 0, ',', '.') ?>
                            </td>
                            <td style="text-align: center;">
                                <span style="display: inline-block; padding: 4px 10px; background: #dcfce7; color: #15803d; border-radius: 12px; font-weight: 700; font-size: 11px;">
                                    LUNAS (SK)
                                </span>
                            </td>
                        </tr>
                        <tr class="highlight-total">
                            <td colspan="4" style="text-align: right; text-transform: uppercase; font-size: 12px;">Sisa Kewajiban Pembayaran Mahasiswa:</td>
                            <td style="text-align: right; font-size: 14px; font-family: monospace;">Rp 0</td>
                            <td style="text-align: center; font-size: 11px;">TERPENUHI 100%</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Catatan Pertimbangan Biro Keuangan -->
                <div class="notes-box">
                    <strong><i class="bi bi-info-circle-fill mr-1"></i> Dasar Pertimbangan & Catatan Persetujuan:</strong>
                    <div>
                        <strong>Alasan Permohonan:</strong>
                        <span style="font-style: italic;">"<?= htmlspecialchars($dispensasi->alasan ?? 'Kondisi ekonomi dan permohonan pembebasan biaya perkuliahan.') ?>"</span>
                    </div>
                    <?php if (!empty($dispensasi->catatan_admin)): ?>
                        <div style="margin-top: 4px;">
                            <strong>Catatan Admin Verifikator:</strong>
                            <span><?= htmlspecialchars($dispensasi->catatan_admin) ?></span>
                        </div>
                    <?php else: ?>
                        <div style="margin-top: 4px;">
                            <strong>Catatan Admin Verifikator:</strong>
                            <span>Permohonan telah divalidasi dan disetujui secara resmi oleh Biro Keuangan. Tagihan secara otomatis dinyatakan LUNAS di sistem akademik.</span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($dispensasi->berkas_pendukung)): ?>
                        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #bbf7d0;">
                            <strong>Berkas Pendukung Pengajuan:</strong>
                            <a href="<?= base_url('keuangan/lihat_berkas_keringanan/' . $dispensasi->id) ?>" target="_blank" style="color: #15803d; text-decoration: underline; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-paperclip"></i> Lihat Berkas Lampiran Mahasiswa (<?= htmlspecialchars($dispensasi->berkas_pendukung) ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sk-section-p" style="font-size: 13px;">
                    Surat Keputusan ini memiliki kekuatan hukum yang sah di lingkungan Universitas Smart Campus Indonesia. Mahasiswa yang bersangkutan berhak melanjutkan kegiatan akademik, perwalian FRS/KRS, dan perkuliahan pada semester aktif tanpa kendala administratif tagihan.
                </div>

                <!-- Bagian Pengesahan & Tanda Tangan -->
                <div class="signature-area">
                    <div class="signature-left">
                        <div class="qr-card">
                            <?php
                                $verify_token = strtoupper(substr(hash('sha256', ($pembayaran->nomor_referensi ?? 'REF') . ($mahasiswa->nim ?? 'NIM') . $nomor_sk_resmi), 0, 16));
                                $qr_payload = urlencode(base_url('keuangan/lihat_bukti/' . $pembayaran->id));
                                $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $qr_payload;
                            ?>
                            <img src="<?= $qr_url ?>" alt="QR Code Keabsahan" class="qr-image" onerror="this.style.display='none'">
                            <div class="qr-text">
                                <strong>VERIFIKASI KEABSAHAN DIGITAL</strong>
                                Dokumen resmi diterbitkan sistem.<br>
                                Token: <span style="font-family: monospace; font-weight: 700; color: #0284c7;"><?= $verify_token ?></span><br>
                                Waktu: <?= date('d/m/Y H:i', $ts_verif) ?> WIB
                            </div>
                        </div>
                    </div>

                    <div class="signature-right">
                        <div class="sign-date">Ditetapkan di: Kampus Utama Smart Campus</div>
                        <div class="sign-date">Pada tanggal: <?= $tgl_indo_verif ?></div>
                        <div class="sign-title">Kepala Biro Keuangan & Kemahasiswaan</div>

                        <div class="stamp-signature-wrapper">
                            <!-- Stempel Digital Resmi -->
                            <div class="digital-stamp">
                                <div class="stamp-inner">
                                    <div class="stamp-text-univ">UNIVERSITAS SMART CAMPUS</div>
                                    <div class="stamp-status">DISAHKAN</div>
                                    <div class="stamp-text-univ">BIRO KEUANGAN</div>
                                    <div class="stamp-date"><?= date('d/m/Y', $ts_verif) ?></div>
                                </div>
                            </div>

                            <div class="digital-sign-badge">
                                <i class="bi bi-patch-check-fill text-primary"></i> Ditandatangani Secara Elektronik
                            </div>
                        </div>

                        <div class="sign-officer-name">
                            <?= htmlspecialchars(!empty($verifikator->nama_lengkap) ? $verifikator->nama_lengkap : 'Biro Keuangan Universitas') ?>
                        </div>
                        <div class="sign-officer-nip">
                            NIP. <?= htmlspecialchars(!empty($verifikator->nim) ? $verifikator->nim : '198203152008121002') ?> / Staff Verifikator
                        </div>
                    </div>
                </div>

                <!-- Footer Dokumen -->
                <div class="doc-footer">
                    <div>Dicetak pada: <?= date('d/m/Y H:i:s') ?> WIB • Smart Campus Financial Information System</div>
                    <div>Halaman 1 dari 1 (Dokumen Sah)</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
