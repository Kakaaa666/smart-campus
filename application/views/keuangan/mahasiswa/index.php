<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                
                <!-- Custom Styling Khusus Modul Keuangan Mahasiswa -->
                <style>
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

                .page-wrapper {
                    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                /* ── Premium Card Base ── */
                .custom-card-white {
                    background: rgba(255,255,255,0.95) !important;
                    backdrop-filter: blur(12px);
                    -webkit-backdrop-filter: blur(12px);
                    border-radius: 18px !important;
                    border: 1px solid rgba(226,232,240,0.8) !important;
                    box-shadow: 0 4px 24px rgba(15,23,42,0.06), 0 1px 4px rgba(15,23,42,0.04) !important;
                    overflow: hidden !important;
                    margin-bottom: 24px !important;
                    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
                    animation: fadeUpIn 0.45s cubic-bezier(0.22,1,0.36,1) both;
                }
                .custom-card-white:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 12px 40px rgba(15,23,42,0.1) !important;
                }

                @keyframes fadeUpIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to   { opacity: 1; transform: translateY(0); }
                }
                @keyframes orbPulse {
                    0%,100% { transform: scale(1) translate(0,0); opacity:0.7; }
                    50%     { transform: scale(1.12) translate(15px,-15px); opacity:1; }
                }
                @keyframes cardShine {
                    0%   { left:-100%; }
                    100% { left: 200%; }
                }

                .custom-card-header {
                    background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%) !important;
                    border-bottom: 1px solid #f1f5f9 !important;
                    border-top-left-radius: 18px !important;
                    border-top-right-radius: 18px !important;
                    padding: 20px 26px !important;
                }
                .custom-card-header h5 {
                    margin: 0;
                    font-size: 16.5px;
                    font-weight: 800;
                    color: #0f172a;
                }
                .custom-card-header > .badge { margin-left: auto; white-space: nowrap; }
                @media (max-width: 575.98px) {
                    .custom-card-header > .badge {
                        margin-left: 0;
                    }
                }

                .billing-intro {
                    padding-bottom: 24px;
                }

                /* ── Stat Cards ── */
                .fin-stat-card {
                    padding: 22px 24px;
                    border-radius: 16px;
                    border: 1px solid rgba(226,232,240,0.8);
                    background: rgba(255,255,255,0.95);
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
                    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
                }
                .fin-stat-card::after {
                    content: '';
                    position: absolute;
                    top:0; left:-80%;
                    width: 50%; height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                    transform: skewX(-15deg);
                    transition: none;
                }
                .fin-stat-card:hover {
                    transform: translateY(-5px) scale(1.01);
                    box-shadow: 0 14px 36px rgba(0,0,0,0.09);
                }
                .fin-stat-card:hover::after {
                    animation: cardShine 0.6s ease forwards;
                }
                .fin-stat-icon {
                    width: 50px;
                    height: 50px;
                    border-radius: 14px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 22px;
                }

                /* ── Nav Tabs ── */
                .nav-tabs-keuangan {
                    border-bottom: 2px solid #e2e8f0;
                    padding: 0 24px;
                    background: linear-gradient(135deg, #ffffff, #fafbff);
                }
                .nav-tabs-keuangan .nav-link {
                    border: none;
                    color: #64748b;
                    font-weight: 600;
                    font-size: 13.5px;
                    padding: 18px 20px;
                    margin-bottom: -2px;
                    border-bottom: 3px solid transparent;
                    transition: all 0.25s ease;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .nav-tabs-keuangan .nav-link i { font-size: 15px; }
                .nav-tabs-keuangan .nav-link:hover {
                    color: #0284c7;
                    border-bottom-color: #bae6fd;
                    background: rgba(2,132,199,0.04);
                }
                .nav-tabs-keuangan .nav-link.active {
                    color: #0284c7;
                    font-weight: 800;
                    background: transparent;
                    border-bottom: 3px solid #0284c7;
                }

                /* ── Status Badges ── */
                .badge-status {
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 11.5px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    letter-spacing: 0.3px;
                }
                .badge-status i { margin-right: 5px; font-size: 13px; }
                .badge-belum-bayar { background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; }
                .badge-pending {
                    background:#fef3c7; color:#b45309; border:1px solid #fcd34d;
                    animation: pulseBadge 2.5s ease-in-out infinite;
                }
                .badge-lunas {
                    background-color: #d1fae5;
                    color: #047857;
                    border: 1px solid #6ee7b7;
                }
                .badge-ditolak {
                    background-color: #ffe4e6;
                    color: #be123c;
                    border: 1px solid #fda4af;
                }

                .payment-stepper {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    margin: 0;
                    padding: 0;
                    list-style: none;
                    overflow: hidden;
                    border: 1px solid var(--sc-line);
                    border-radius: 14px;
                    background: var(--sc-surface);
                    box-shadow: 0 8px 20px rgba(16, 45, 61, .08);
                }
                .payment-stepper-item {
                    position: relative;
                    min-width: 0;
                    border-right: 1px solid var(--sc-line);
                }
                .payment-stepper-item:last-child { border-right: 0; }
                .payment-stepper-button {
                    display: flex;
                    width: 100%;
                    min-height: 48px;
                    align-items: center;
                    gap: 8px;
                    padding: 9px 10px;
                    border: 0;
                    background: transparent;
                    color: var(--sc-muted);
                    text-align: left;
                    font-size: 12px;
                    line-height: 1.3;
                    cursor: pointer;
                }
                .payment-stepper-button:disabled {
                    color: color-mix(in srgb, var(--sc-muted) 75%, var(--sc-surface));
                    cursor: not-allowed;
                }
                .payment-step-marker {
                    display: inline-flex;
                    flex: 0 0 20px;
                    width: 20px;
                    height: 20px;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid var(--sc-ink);
                    border-radius: 50%;
                    background: var(--sc-ink);
                    color: #fff;
                    font-size: 11px;
                    font-weight: 700;
                }
                .payment-stepper-item.is-current {
                    background: color-mix(in srgb, var(--sc-accent) 12%, var(--sc-surface));
                    box-shadow: inset 0 -3px 0 var(--sc-accent-strong);
                }
                .payment-stepper-item.is-complete .payment-step-marker {
                    background: var(--sc-accent-strong);
                    border-color: var(--sc-accent-strong);
                }
                .payment-stepper-panel[hidden] { display: none !important; }
                .payment-tracker {
                    display: grid;
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    gap: 8px;
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }
                .payment-tracker .payment-step {
                    position: relative;
                    min-width: 0;
                    text-align: center;
                    color: #1e40af;
                    font-size: 11px;
                    line-height: 1.35;
                }
                .payment-tracker .payment-step strong,
                .payment-tracker .payment-step small,
                .payment-tracker .payment-step.is-rejected,
                .payment-tracker .payment-step.is-rejected strong,
                .payment-tracker .payment-step.is-rejected small {
                    color: #1e40af !important;
                }
                .payment-tracker .payment-step:not(:last-child)::after {
                    position: absolute;
                    top: 15px;
                    left: calc(50% + 18px);
                    width: calc(100% - 28px);
                    height: 2px;
                    background: var(--sc-line);
                    content: '';
                }
                .payment-tracker .payment-step-marker {
                    position: relative;
                    z-index: 1;
                    display: flex;
                    width: 30px;
                    height: 30px;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 7px;
                    border: 2px solid var(--sc-line);
                    border-radius: 50%;
                    background: var(--sc-surface);
                    color: var(--sc-muted);
                    font-size: 12px;
                }
                .payment-tracker .payment-step.is-complete,
                .payment-tracker .payment-step.is-current { color: var(--sc-ink); }
                .payment-tracker .payment-step.is-complete .payment-step-marker,
                .payment-tracker .payment-step.is-current .payment-step-marker {
                    border-color: var(--sc-accent-strong);
                    background: var(--sc-accent-strong);
                    color: #fff;
                }
                .payment-tracker .payment-step.is-complete:not(:last-child)::after { background: var(--sc-accent-strong); }
                .payment-tracker .payment-step.is-current .payment-step-marker {
                    box-shadow: 0 0 0 4px color-mix(in srgb, var(--sc-accent) 18%, transparent);
                }
                .payment-tracker .payment-step.is-rejected,
                .payment-tracker .payment-step.is-rejected .payment-step-marker { color: #b91c1c; }
                .payment-tracker .payment-step.is-rejected .payment-step-marker {
                    border-color: #dc2626;
                    background: #dc2626;
                }
                .payment-progress-message {
                    display: block;
                    width: 100%;
                    min-height: 0;
                    margin: 0 0 24px;
                    padding: 14px 16px;
                    border-radius: 8px;
                    background: #f0f7ff;
                    color: #1e40af !important;
                    line-height: 1.5;
                }
                #modalProgresPembayaran .modal-body {
                    display: block;
                }
                #paymentProgressTimeline.payment-tracker {
                    width: 100%;
                    min-height: 0;
                }
                body #modalKonfirmasiBayar .modal-header {
                    background: linear-gradient(115deg, var(--sc-ink), var(--sc-accent-strong)) !important;
                }
                @media (max-width: 575.98px) {
                    .payment-stepper-button { min-height: 54px; gap: 5px; padding: 7px 5px; font-size: 10px; }
                    .payment-step-marker { flex-basis: 18px; width: 18px; height: 18px; }
                    .payment-stepper-item { overflow-wrap: anywhere; }
                    .payment-tracker { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px 6px; }
                    .payment-tracker .payment-step { font-size: 9px; }
                    .payment-tracker .payment-step::after { display: none; }
                }

                #formKonfirmasiPembayaran .form-group > label,
                #modalEditPembayaran .form-group > label {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    color: #334155 !important;
                    font-size: 13.5px !important;
                    line-height: 1.4 !important;
                    margin-bottom: 6px !important;
                }

                /* Banner Simulasi Biaya */
                .banner-biaya-simulasi {
                    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
                    border-radius: 18px;
                    color: #ffffff;
                    padding: 28px 32px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
                    margin-bottom: 24px;
                }
                .banner-biaya-simulasi::after {
                    content: "";
                    position: absolute;
                    top: -60px;
                    right: -60px;
                    width: 220px;
                    height: 220px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.06);
                    pointer-events: none;
                }
                .tag-simulasi {
                    background: #f59e0b;
                    color: #78350f;
                    font-size: 11px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.8px;
                    padding: 4px 10px;
                    border-radius: 6px;
                    display: inline-block;
                }

                /* ── Bank Rekening Box ── */
                .bank-rek-box {
                    border: 1.5px dashed #cbd5e1;
                    border-radius: 14px;
                    padding: 18px;
                    background: linear-gradient(135deg, #ffffff, #f8faff);
                    transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
                    position: relative;
                    overflow: hidden;
                }
                .bank-rek-box:hover {
                    border-color: #0284c7;
                    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
                    transform: translateY(-3px) scale(1.01);
                    box-shadow: 0 8px 24px rgba(2,132,199,0.12);
                }

                /* ── Keuangan Table ── */
                .table-keuangan thead th {
                    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
                    color: #475569;
                    font-weight: 700;
                    font-size: 12px;
                    border-top: none;
                    border-bottom: 1.5px solid #e2e8f0;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                    padding: 14px 16px;
                }
                .table-keuangan tbody td {
                    vertical-align: middle;
                    padding: 14px 16px;
                    color: #334155;
                    font-size: 13.5px;
                    border-top: 1px solid #f1f5f9;
                    transition: background 0.15s ease;
                }
                .table-keuangan tbody tr:hover td { background: rgba(2,132,199,0.03); }

                /* ── Toast ── */
                #realtimeToast {
                    position: fixed;
                    bottom: 28px; right: 28px;
                    z-index: 9999;
                    min-width: 320px; max-width: 420px;
                    border-radius: 16px;
                    box-shadow: 0 16px 48px rgba(0,0,0,0.2);
                    display: none;
                    animation: toastSlideUp 0.4s cubic-bezier(0.34,1.56,0.64,1);
                }
                @keyframes toastSlideUp {
                    from { transform: translateY(40px) scale(0.9); opacity:0; }
                    to   { transform: translateY(0) scale(1); opacity:1; }
                }

                /* ── Progress Bar ── */
                .progress-bar {
                    animation: progressGrow 1.4s cubic-bezier(0.4,0,0.2,1) both;
                    animation-delay: 0.4s;
                }
                @keyframes progressGrow { from { width:0% !important; } }

                /* ── Buttons ── */
                .btn { transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1) !important; }
                .btn:hover { transform: translateY(-2px); }
                .btn-primary:hover { box-shadow: 0 6px 18px rgba(2,132,199,0.4) !important; }
                .btn-success:hover { box-shadow: 0 6px 18px rgba(5,150,105,0.4) !important; }

                /* ── Alert Animated ── */
                .alert { animation: fadeUpIn 0.35s ease both; }

                /* ── Step Wizard Badges ── */
                .step-badge {
                    width: 32px; height: 32px;
                    border-radius: 50%;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    font-size: 14px;
                    flex-shrink: 0;
                }

                /* ── Billing info card divider ── */
                .cost-divider {
                    border-top: 1px dashed rgba(255,255,255,0.2);
                    padding-top: 14px;
                    margin-top: 14px;
                }

                /* ── Scrollbar ── */
                ::-webkit-scrollbar { width:6px; height:6px; }
                ::-webkit-scrollbar-track { background:#f1f5f9; }
                ::-webkit-scrollbar-thumb {
                    background: linear-gradient(180deg,#0284c7,#6366f1);
                    border-radius: 10px;
                }

                @keyframes pulseDot {
                    0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.5;transform:scale(0.8);}
                }
                </style>

                <!-- Toast Real-time Notifikasi -->
                <div id="realtimeToast" class="alert alert-success alert-dismissible p-3" role="alert" style="background:#10b981; color:#fff; border:none;">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="font-size:26px;"><i class="fa fa-bell"></i></div>
                        <div>
                            <strong style="font-size:14px; display:block;" id="toastTitle">Pembaruan Verifikasi</strong>
                            <span style="font-size:12.5px; opacity:0.95;" id="toastBody">Pembayaran Anda telah diverifikasi oleh Admin Keuangan.</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white" onclick="$('#realtimeToast').fadeOut();" style="opacity:0.9;">
                        <span>&times;</span>
                    </button>
                </div>

                <?php
                    $total_biaya_semester = 0;
                    foreach ($komponen_biaya as $komponen) {
                        $total_biaya_semester += (float)$komponen['nominal'];
                    }
                ?>

                <!-- Banner Rincian Biaya Kuliah & Deskripsi Keuangan -->
                <div class="banner-biaya-simulasi">
                    <div class="banner-content">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-warning text-dark font-weight-bold mr-2" style="font-size: 11px; padding: 5px 10px; border-radius: 6px;">INFORMASI TARIF KAMPUS</span>
                                <span style="font-size: 13.5px; opacity: 0.9; font-weight: 600;">Tahun Akademik 2026/2027 &bull; Semester Ganjil</span>
                            </div>
                            <div style="font-size: 15px; opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Total Biaya Perkuliahan Semester
                            </div>
                            <h1 style="font-weight: 800; font-size: 38px; margin: 6px 0 8px; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                Rp <?= number_format($total_biaya_semester, 0, ',', '.') ?>
                            </h1>
                            <div style="font-size: 14px; opacity: 0.95; line-height: 1.6;">
                                <i class="fa fa-graduation-cap mr-1 text-warning"></i> Program Studi: <strong><?= htmlspecialchars($mahasiswa_info['prodi']) ?></strong> &bull; Semester <strong><?= htmlspecialchars($mahasiswa_info['semester']) ?></strong>
                            </div>
                            <!-- Status Akses Semester Akhir Mahasiswa -->
                            <?php if ($is_semester_akhir): ?>
                                <div class="mt-3">
                                    <?php if ($akses_ta_mahasiswa): ?>
                                        <span style="background:rgba(16,185,129,0.2);color:#6ee7b7;border:1px solid rgba(110,231,183,0.35);font-size:12px;border-radius:20px;font-weight:700;padding:7px 16px;display:inline-flex;align-items:center;gap:6px;backdrop-filter:blur(4px);">
                                            <i class="fa fa-unlock"></i> Akses Biaya Semester Akhir: DIBUKA
                                        </span>
                                    <?php elseif (!empty($validasi_tagihan_akhir) && $validasi_tagihan_akhir->status === 'MENUNGGU'): ?>
                                        <span style="background:rgba(245,158,11,0.2);color:#fbbf24;border:1px solid rgba(251,191,36,0.3);font-size:12px;border-radius:20px;font-weight:700;padding:7px 16px;display:inline-flex;align-items:center;gap:6px;">
                                            <i class="fa fa-clock-o"></i> Menunggu validasi Biro Keuangan
                                        </span>
                                    <?php elseif (!empty($validasi_tagihan_akhir) && $validasi_tagihan_akhir->status === 'DITOLAK'): ?>
                                        <span style="background:rgba(239,68,68,0.2);color:#fca5a5;border:1px solid rgba(252,165,165,0.3);font-size:12px;border-radius:20px;font-weight:700;padding:7px 16px;display:inline-flex;align-items:center;gap:6px;">
                                            <i class="fa fa-times-circle"></i> Validasi belum disetujui
                                        </span>
                                    <?php else: ?>
                                        <span style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.15);font-size:12px;border-radius:20px;font-weight:600;padding:7px 16px;display:inline-flex;align-items:center;gap:6px;backdrop-filter:blur(4px);">
                                            <i class="fa fa-lock"></i> Tagihan semester akhir menunggu persetujuan
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6" style="border-left: 1.5px solid rgba(255,255,255,0.25); padding-left: 28px;">
                            <div class="font-weight-bold mb-3" style="font-size: 14.5px; letter-spacing: 0.5px; text-transform: uppercase;">
                                <i class="fa fa-list-ul mr-1 text-warning"></i> Rincian Transparansi Biaya Pokok
                            </div>
                            <div class="row" style="font-size: 13.5px; line-height: 1.8;">
                                <div class="col-sm-6 mb-2">
                                    <div style="opacity: 0.85; font-size: 12px;">Kewajiban Pokok Kuliah</div>
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> SPP / UKT: <strong>Rp <?= number_format($komponen_biaya[0]['nominal'], 0, ',', '.') ?></strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div style="opacity: 0.85; font-size: 12px;">Akademik &amp; Laboratorium</div>
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> Praktikum: <strong>Rp <?= number_format($komponen_biaya[1]['nominal'], 0, ',', '.') ?></strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div style="opacity: 0.85; font-size: 12px;">Sarana Kampus</div>
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> Fasilitas: <strong>Rp <?= number_format($komponen_biaya[2]['nominal'], 0, ',', '.') ?></strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div style="opacity: 0.85; font-size: 12px;">Layanan Digital</div>
                                    <i class="fa fa-check-circle mr-1" style="color: #86efac;"></i> SI &amp; Admin: <strong>Rp <?= number_format($komponen_biaya[3]['nominal'], 0, ',', '.') ?></strong>
                                </div>
                            </div>
                            <div class="mt-2 pt-2" style="border-top: 1px dashed rgba(255,255,255,0.25); font-size: 12px; opacity: 0.9;">
                                <i class="fa fa-info-circle mr-1 text-warning"></i> Biaya semester akhir bersifat kondisional dan hanya muncul setelah akses dibuka oleh Bagian Keuangan.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekening Resmi Pembayaran Kampus (DITAMPILKAN DI ATAS AGAR MAHASISWA PASTI MEMBACA) -->
                <div class="card custom-card-white" style="border-left: 5px solid #0284c7; margin-bottom: 24px;">
                    <div class="card-header custom-card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 12px;">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <div>
                                <h5 class="mb-0" style="color: #0f172a; font-weight: 800;">
                                    Rekening Resmi Pembayaran Kampus
                                </h5>
                                <small class="text-muted">Gunakan salah satu saluran resmi di bawah ini untuk melakukan pembayaran</small>
                            </div>
                        </div>
                        <span class="badge badge-primary px-3 py-2 mt-2 mt-sm-0" style="border-radius: 8px; font-size: 12px; font-weight: 700;">
                            <i class="fa fa-shield mr-1"></i> Jalur Resmi Terverifikasi
                        </span>
                    </div>
                    <div class="card-block" style="padding: 24px;">
                        <div class="alert alert-warning mb-3 py-2 px-3" style="border-radius: 10px; font-size: 12.5px; border-left: 4px solid #f59e0b; background:#fffbeb;">
                            <i class="fa fa-exclamation-circle mr-1 text-warning"></i> <strong>Perhatian:</strong> Pastikan Anda mentransfer ke nomor rekening / Virtual Account resmi di bawah ini. Simpan bukti transfer untuk diunggah pada form pembayaran.
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong style="color: #0f172a; font-size: 15px;">BANK BNI</strong>
                                        <span class="badge badge-primary px-2 py-1" style="border-radius: 6px;">Virtual Account</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Virtual Account Mahasiswa:</p>
                                    <div class="d-flex align-items-center justify-content-between" style="background:#f8fafc; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
                                        <h5 class="mb-0 font-weight-bold text-primary" style="letter-spacing: 0.5px;">8808-<?= htmlspecialchars($mahasiswa_info['nim']) ?></h5>
                                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-copy-rek" data-copy="8808<?= htmlspecialchars($mahasiswa_info['nim']) ?>" title="Salin VA">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Smart Campus - <?= htmlspecialchars($mahasiswa_info['nama_lengkap']) ?></small>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong style="color: #0f172a; font-size: 15px;">BANK MANDIRI</strong>
                                        <span class="badge badge-info px-2 py-1" style="border-radius: 6px;">Transfer Bank</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Rekening Kampus:</p>
                                    <div class="d-flex align-items-center justify-content-between" style="background:#f8fafc; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
                                        <h5 class="mb-0 font-weight-bold" style="color:#0f172a; letter-spacing: 0.5px;">137-00-1928374-1</h5>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-copy-rek" data-copy="1370019283741" title="Salin No. Rekening">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Yayasan Smart Campus Indonesia</small>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="bank-rek-box h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong style="color: #0f172a; font-size: 15px;">BANK BCA</strong>
                                        <span class="badge badge-success px-2 py-1" style="border-radius: 6px;">Transfer Giro</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 12px;">No. Rekening Giro Kampus:</p>
                                    <div class="d-flex align-items-center justify-content-between" style="background:#f8fafc; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
                                        <h5 class="mb-0 font-weight-bold" style="color:#0f172a; letter-spacing: 0.5px;">829-501-8890</h5>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-copy-rek" data-copy="8295018890" title="Salin No. Rekening">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">a.n. Smart Campus Operasional</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Super Admin Simulation Preview Banner -->
                <?php if (isset($is_admin_preview) && $is_admin_preview && !empty($target_mahasiswa)): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; background-color: #f0fdf4; border: 1.5px solid #86efac; color: #166534; box-shadow: 0 4px 12px rgba(22, 101, 52, 0.08);">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill mr-2" style="font-size: 24px; color: #15803d;"></i>
                                    <div>
                                        <strong style="font-size: 14px;">Mode Simulasi Super Admin:</strong>
                                        <div style="font-size: 12.5px; color: #14532d;">
                                            Melihat data keuangan mahasiswa: <strong><?= htmlspecialchars($target_mahasiswa->nama_lengkap) ?> (NIM: <?= htmlspecialchars($target_mahasiswa->nim) ?> &bull; <?= htmlspecialchars($target_mahasiswa->prodi ?: 'D3 Sistem Informasi') ?>)</strong>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($daftar_mahasiswa) && count($daftar_mahasiswa) > 1): ?>
                                <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                    <label class="mb-1 d-block font-weight-bold" style="font-size: 12px; color: #14532d;">Ganti Akun Mahasiswa:</label>
                                    <select class="form-control form-control-sm d-inline-block w-auto" style="border-radius: 6px;" onchange="window.location.href='<?= base_url('keuangan?mahasiswa_id=') ?>' + this.value;">
                                        <?php foreach ($daftar_mahasiswa as $dm): ?>
                                            <option value="<?= $dm->id ?>" <?= $dm->id == $target_mahasiswa->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dm->nama_lengkap) ?> (<?= htmlspecialchars($dm->nim) ?> &mdash; <?= htmlspecialchars($dm->prodi ?: 'D3 SI') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Flash Message Alerts -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill mr-2" style="font-size: 20px;"></i>
                            <div>
                                <strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill mr-2" style="font-size: 20px;"></i>
                            <div>
                                <strong>Perhatian:</strong> <?= $this->session->flashdata('error') ?>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- Ringkasan Finansial Mahasiswa (4 Stat Cards) -->
                <div class="row mb-3">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Total Tagihan Aktif</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #0f172a; font-size: 20px;">
                                        Rp <?= number_format($ringkasan['total_tagihan'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-muted"><?= count($daftar_tagihan) ?> Item Kewajiban</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #eff6ff; color: #1d4ed8;">
                                    <i class="bi bi-calculator"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Sisa Belum Dibayar</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #dc2626; font-size: 20px;" id="statBelumNominal">
                                        Rp <?= number_format($ringkasan['total_belum_bayar'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-danger font-weight-bold" id="statBelumCount"><?= $ringkasan['count_belum_bayar'] ?> Tagihan Menunggu</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #fef2f2; color: #dc2626;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Total Lunas</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #059669; font-size: 20px;" id="statLunasNominal">
                                        Rp <?= number_format($ringkasan['total_terbayar'], 0, ',', '.') ?>
                                    </h4>
                                    <small class="text-success font-weight-bold" id="statLunasCount"><?= $ringkasan['count_lunas'] ?> Tagihan Terverifikasi</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="fin-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 600;">Dalam Verifikasi</span>
                                    <h4 class="mb-0 mt-1 font-weight-bold" style="color: #d97706; font-size: 20px;" id="statPendingCount">
                                        <?= $ringkasan['count_pending'] ?> Tagihan
                                    </h4>
                                    <small class="text-warning font-weight-bold">Menunggu Verifikasi Admin</small>
                                </div>
                                <div class="fin-stat-icon" style="background-color: #fffbeb; color: #d97706;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Area Konten Ber-Tab -->
                <div class="card custom-card-white">
                    <ul class="nav nav-tabs nav-tabs-keuangan" id="keuanganTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-tagihan-link" data-toggle="tab" href="#tab-tagihan" role="tab">
                                <i class="bi bi-receipt"></i> Tagihan Semester (<?= count($daftar_tagihan) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-riwayat-link" data-toggle="tab" href="#tab-riwayat" role="tab">
                                <i class="bi bi-clock-history"></i> Riwayat Pembayaran (<?= count($riwayat_pembayaran) ?>)
                            </a>
                        </li>
                        <?php if ($is_semester_akhir || $akses_tugas_akhir || $ambil_semester_pendek): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-ta-link" data-toggle="tab" href="#tab-ta" role="tab">
                                    <i class="bi bi-mortarboard"></i> Pembayaran Semester Akhir
                                    <?php if ($akses_tugas_akhir): ?>
                                        <span class="badge badge-success ml-1" style="font-size: 10px;">Buka</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary ml-1" style="font-size: 10px;">Tutup</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-biaya-link" data-toggle="tab" href="#tab-biaya" role="tab">
                                <i class="bi bi-info-square"></i> Rincian Biaya Kuliah &amp; Tambahan
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="keuanganTabContent">
                        
                        <!-- TAB 1: TAGIHAN SEMESTER AKTIF -->
                        <div class="tab-pane fade show active p-4" id="tab-tagihan" role="tabpanel">
                            <div class="billing-header-card p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5 class="font-weight-bold mb-0" style="color: #0f172a; font-size: 16px;">
                                                Daftar Tagihan Semester Berjalan (2026/2027 Ganjil)
                                            </h5>
                                            <span class="badge badge-primary ml-2 px-2 py-1" style="font-size: 11px; border-radius: 6px; font-weight: 600;">Aktif</span>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.5;">
                                            Pilih tagihan yang ingin dibayarkan secara langsung melalui tombol di tabel, atau gunakan tombol konfirmasi pembayaran di bawah ini.
                                        </p>
                                    </div>
                                    <div class="col-lg-5 col-md-12">
                                        <div class="d-flex align-items-center justify-content-lg-end flex-wrap" style="gap: 10px;">
                                            <?php if (!empty($tagihan_pilihan)): ?>
                                                <button type="button" class="btn btn-primary px-3 shadow-sm btn-open-bayar-general" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 9px 16px;">
                                                    <i class="bi bi-credit-card mr-1"></i> Konfirmasi Pembayaran
                                                </button>
                                            <?php else: ?>
                                                <span class="badge badge-success px-3 py-2" style="border-radius: 8px; font-size: 12.5px; font-weight: 600;">
                                                    <i class="bi bi-check2-all mr-1"></i> Seluruh Tagihan Lunas
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2 px-3 py-2" style="border-left:3px solid #1684cf; border-radius:4px; background:#f1f7fd; color:#526477; font-size:12px; line-height:1.5;">
                                <i class="bi bi-info-circle mr-1 text-primary"></i>
                                <strong>Keterangan:</strong> Jenis tagihan menunjukkan kewajiban yang harus dibayar; tahun akademik dan semester menunjukkan periodenya; nominal adalah jumlah tagihan; jatuh tempo adalah batas akhir pembayaran; status dan aksi menunjukkan proses yang dapat dilakukan.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Jenis Kewajiban / Tagihan</th>
                                            <th>Tahun Akademik</th>
                                            <th>Semester</th>
                                            <th>Nominal</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Status</th>
                                            <th style="width: 170px;" class="text-center">Aksi Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($daftar_tagihan)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-check-circle text-success mr-2" style="font-size: 24px;"></i>
                                                    <p class="mb-0 mt-2 font-weight-bold">Tidak ada tagihan tertagih untuk akun Anda saat ini.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                                $latest_payment_by_tagihan = [];
                                                foreach ($riwayat_pembayaran as $payment_record) {
                                                    $payment_tagihan_id = (int)$payment_record->tagihan_id;
                                                    if (!isset($latest_payment_by_tagihan[$payment_tagihan_id])) {
                                                        $latest_payment_by_tagihan[$payment_tagihan_id] = $payment_record;
                                                    }
                                                }
                                            ?>
                                            <?php $no = 1; foreach ($daftar_tagihan as $item): ?>
                                                <?php 
                                                    $badge_cls = 'badge-belum-bayar';
                                                    $icon_cls  = 'bi-exclamation-circle';
                                                    $label_st  = $item->status;
                                                    if ($item->status === 'LUNAS') {
                                                        $badge_cls = 'badge-lunas';
                                                        $icon_cls  = 'bi-check-circle-fill';
                                                        $label_st  = 'Lunas';
                                                    } elseif ($item->status === 'PENDING') {
                                                        $badge_cls = 'badge-pending';
                                                        $icon_cls  = 'bi-hourglass-split';
                                                        $label_st  = 'Menunggu Verifikasi';
                                                    } elseif ($item->status === 'DITOLAK') {
                                                        $badge_cls = 'badge-ditolak';
                                                        $icon_cls  = 'bi-x-circle-fill';
                                                        $label_st  = 'Ditolak';
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="font-weight-bold text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <strong style="color: #1e293b; font-size: 14px;"><?= htmlspecialchars($item->jenis_tagihan) ?></strong>
                                                        <?php if (stripos($item->jenis_tagihan, 'SPP') !== false): ?>
                                                            <span class="badge badge-info ml-1" style="font-size: 10.5px;">Syarat KRS</span>
                                                        <?php endif; ?>
                                                        <?php if (stripos($item->jenis_tagihan, 'Tugas Akhir') !== false): ?>
                                                            <span class="badge badge-warning text-dark ml-1" style="font-size: 10.5px;">Semester Akhir</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($item->tahun_akademik) ?></td>
                                                    <td>
                                                        <span class="badge badge-light px-2 py-1" style="border: 1px solid #e2e8f0; font-size: 12px;">
                                                            <?= htmlspecialchars($item->semester) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1565c0; font-size: 14.5px;">
                                                            Rp <?= number_format($item->nominal, 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-calendar3 mr-1 text-muted"></i>
                                                        <?= date('d M Y', strtotime($item->jatuh_tempo)) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge-status <?= $badge_cls ?>">
                                                            <i class="bi <?= $icon_cls ?>"></i> <?= $label_st ?>
                                                        </span>
                                                        <?php $latest_payment = $latest_payment_by_tagihan[(int)$item->id] ?? null; ?>
                                                        <button type="button" class="btn btn-link btn-sm d-block p-0 mt-1 btn-lihat-progres"
                                                                data-jenis="<?= htmlspecialchars($item->jenis_tagihan, ENT_QUOTES, 'UTF-8') ?>"
                                                                data-status="<?= htmlspecialchars($item->status, ENT_QUOTES, 'UTF-8') ?>"
                                                                data-tagihan-dibuat="<?= !empty($item->created_at) ? date('d M Y, H:i', strtotime($item->created_at)) . ' WIB' : '-' ?>"
                                                                data-pembayaran-dikirim="<?= $latest_payment ? date('d M Y, H:i', strtotime($latest_payment->created_at)) . ' WIB' : '' ?>"
                                                                data-diverifikasi="<?= $latest_payment && !empty($latest_payment->diverifikasi_at) ? date('d M Y, H:i', strtotime($latest_payment->diverifikasi_at)) . ' WIB' : '' ?>"
                                                                data-admin="<?= htmlspecialchars($latest_payment->nama_verifikator ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                                data-alasan="<?= htmlspecialchars($latest_payment->alasan_penolakan ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                            <i class="bi bi-diagram-3 mr-1"></i>Lihat Progres
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($item->status === 'BELUM_BAYAR'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-primary btn-sm btn-block btn-bayar-item" 
                                                                    data-id="<?= $item->id ?>"
                                                                    data-jenis="<?= htmlspecialchars($item->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($item->nominal, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($item->semester) ?> <?= htmlspecialchars($item->tahun_akademik) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($item->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-upload mr-1"></i> Bayar Sekarang
                                                            </button>
                                                        <?php elseif ($item->status === 'DITOLAK'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm btn-block btn-bayar-item" 
                                                                    data-id="<?= $item->id ?>"
                                                                    data-jenis="<?= htmlspecialchars($item->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($item->nominal, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($item->semester) ?> <?= htmlspecialchars($item->tahun_akademik) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($item->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-arrow-repeat mr-1"></i> Upload Ulang
                                                            </button>
                                                        <?php elseif ($item->status === 'PENDING'): ?>
                                                            <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                                                <i class="bi bi-clock mr-1"></i> Verifikasi Admin
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success px-2 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                                                <i class="bi bi-check-lg mr-1"></i> Selesai
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: RIWAYAT PEMBAYARAN & OPSI EDIT JIKA PENDING -->
                        <div class="tab-pane fade p-4" id="tab-riwayat" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #1e293b; font-size: 16px;">
                                        Riwayat Pembayaran &amp; Opsi Edit
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Jika terdapat kesalahan pada bukti/data yang berstatus <strong>Menunggu Verifikasi (PENDING)</strong>, Anda dapat <strong>mengedit</strong> atau <strong>membatalkannya langsung</strong> tanpa menunggu penolakan admin.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-2 px-3 py-2" style="border-left:3px solid #1684cf; border-radius:4px; background:#f1f7fd; color:#526477; font-size:12px; line-height:1.5;">
                                <i class="bi bi-info-circle mr-1 text-primary"></i>
                                <strong>Keterangan:</strong> Tanggal dan jam bayar adalah waktu transfer yang Anda masukkan; waktu “Dikirim” menunjukkan kapan konfirmasi tercatat di sistem. Kolom lainnya menampilkan tagihan, metode, nominal, rekening pengirim, bukti, status, dan aksi koreksi.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">No</th>
                                            <th>Tanggal &amp; Jam Pembayaran</th>
                                            <th>Kewajiban / Tagihan</th>
                                            <th>Metode Bayar</th>
                                            <th>Nominal</th>
                                            <th>Rekening Pengirim</th>
                                            <th>Bukti</th>
                                            <th>Status</th>
                                            <th style="width: 160px;" class="text-center">Aksi / Koreksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($riwayat_pembayaran)): ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 36px;"></i>
                                                    <p class="mb-0 mt-2 font-weight-bold">Belum ada riwayat konfirmasi pembayaran yang dikirim.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($riwayat_pembayaran as $r): ?>
                                                <?php 
                                                    $r_badge = 'badge-pending';
                                                    $r_icon  = 'bi-hourglass-split';
                                                    if ($r->status === 'LUNAS') {
                                                        $r_badge = 'badge-lunas';
                                                        $r_icon  = 'bi-check-circle-fill';
                                                    } elseif ($r->status === 'DITOLAK') {
                                                        $r_badge = 'badge-ditolak';
                                                        $r_icon  = 'bi-x-circle-fill';
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="font-weight-bold text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <strong><?= date('d M Y', strtotime($r->tanggal_pembayaran)) ?></strong><br>
                                                        <small class="text-muted">Jam bayar: <?= !empty($r->jam_pembayaran) ? date('H:i', strtotime($r->jam_pembayaran)) . ' WIB' : 'Belum dicatat' ?></small><br>
                                                        <small class="text-muted">Dikirim: <?= date('H:i', strtotime($r->created_at)) ?> WIB</small>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1e293b;"><?= htmlspecialchars($r->jenis_tagihan) ?></strong><br>
                                                        <small class="text-muted"><?= htmlspecialchars($r->semester) ?> <?= htmlspecialchars($r->tahun_akademik) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light px-2 py-1" style="border: 1px solid #cbd5e1;">
                                                            <?= htmlspecialchars($r->metode_pembayaran) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong style="color: #1565c0;">
                                                            Rp <?= number_format($r->nominal_pembayaran, 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($r->nomor_rekening) || !empty($r->nama_rekening)): ?>
                                                            <small class="d-block text-muted">No. Rekening</small>
                                                            <code><?= htmlspecialchars($r->nomor_rekening ?: '-') ?></code><br>
                                                            <small class="d-block text-muted">Nama Pemilik</small>
                                                            <small><?= htmlspecialchars($r->nama_rekening ?: '-') ?></small>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('keuangan/lihat_bukti/' . $r->id) ?>" target="_blank" class="btn btn-outline-primary btn-sm px-2" style="border-radius: 6px;">
                                                            <i class="bi bi-file-earmark-text mr-1"></i> Lihat
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge-status <?= $r_badge ?>">
                                                            <i class="bi <?= $r_icon ?>"></i> <?= $r->status ?>
                                                        </span>
                                                        <?php if ($r->status === 'DITOLAK' && !empty($r->alasan_penolakan)): ?>
                                                            <div class="mt-1">
                                                                <small class="text-danger font-weight-bold d-block">
                                                                    <i class="bi bi-info-circle mr-1"></i> <?= htmlspecialchars($r->alasan_penolakan) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($r->status === 'LUNAS' && !empty($r->nama_verifikator)): ?>
                                                            <div class="mt-1">
                                                                <small class="text-muted d-block" style="font-size: 11px;">
                                                                    Oleh: <?= htmlspecialchars($r->nama_verifikator) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($r->status === 'PENDING'): ?>
                                                            <!-- Tombol Opsi Edit Jika Ada Kesalahan -->
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button type="button" 
                                                                        class="btn btn-warning text-dark font-weight-bold btn-edit-pembayaran"
                                                                        data-id="<?= $r->id ?>"
                                                                        data-tagihan="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                        data-nominal="Rp <?= number_format($r->nominal_pembayaran, 0, ',', '.') ?>"
                                                                        data-metode="<?= htmlspecialchars($r->metode_pembayaran) ?>"
                                                                        data-tanggal="<?= $r->tanggal_pembayaran ?>"
                                                                        data-jam="<?= htmlspecialchars($r->jam_pembayaran ?? '') ?>"
                                                                        data-rekening="<?= htmlspecialchars($r->nomor_rekening) ?>"
                                                                        data-nama-rekening="<?= htmlspecialchars($r->nama_rekening) ?>"
                                                                        style="border-radius: 6px 0 0 6px;"
                                                                        title="Edit Data / Bukti Pembayaran">
                                                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                                                </button>
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-batal-pembayaran"
                                                                        data-id="<?= $r->id ?>"
                                                                        data-tagihan="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                        style="border-radius: 0 6px 6px 0;"
                                                                        title="Batalkan Pengajuan">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        <?php elseif ($r->status === 'DITOLAK'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm btn-bayar-item"
                                                                    data-id="<?= $r->tagihan_id ?>"
                                                                    data-jenis="<?= htmlspecialchars($r->jenis_tagihan) ?>"
                                                                    data-nominal="Rp <?= number_format($r->nominal_tagihan, 0, ',', '.') ?>"
                                                                    data-semester="<?= htmlspecialchars($r->semester) ?>"
                                                                    data-tempo="<?= date('d M Y', strtotime($r->jatuh_tempo)) ?>"
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-arrow-repeat mr-1"></i> Kirim Ulang
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-success font-weight-bold" style="font-size: 12.5px;">
                                                                <i class="bi bi-check-all"></i> Terverifikasi
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Kartu Total Pengeluaran Resmi Mahasiswa (Sesuai Revisi) -->
                            <div class="mt-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #1565c0 100%); border-radius: 14px; color: #fff; padding: 24px 28px;">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.8; margin-bottom: 4px;">
                                            <i class="bi bi-receipt-cutoff mr-1"></i> Total Pengeluaran Resmi Terverifikasi
                                        </div>
                                        <h2 class="mb-1 font-weight-bold" style="font-size: 30px; letter-spacing: -1px; color: #ffffff !important; text-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?>
                                        </h2>
                                        <p style="font-size: 13px; opacity: 0.85; margin-bottom: 0;">
                                            Akumulasi total pembayaran dengan status <strong>LUNAS</strong> yang telah diverifikasi oleh Admin Keuangan.
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-md-right mt-3 mt-md-0">
                                        <div style="background: rgba(255,255,255,0.12); border-radius: 12px; padding: 16px 20px; display: inline-block; border: 1px solid rgba(255,255,255,0.2);">
                                            <?php
                                                $jumlah_lunas = 0;
                                                foreach ($riwayat_pembayaran as $rp) {
                                                    if ($rp->status === 'LUNAS') $jumlah_lunas++;
                                                }
                                            ?>
                                            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">Jumlah Transaksi Lunas</div>
                                            <div style="font-size: 28px; font-weight: 800;"><?= $jumlah_lunas ?></div>
                                            <div style="font-size: 11.5px; opacity: 0.75; margin-top: 2px;">Dari <?= count($riwayat_pembayaran) ?> total transaksi</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB KHUSUS MAHASISWA SEMESTER AKHIR -->
                        <?php if ($is_semester_akhir || $akses_tugas_akhir || $ambil_semester_pendek): ?>
                            <div class="tab-pane fade p-4" id="tab-ta" role="tabpanel">
                                <div class="p-3 mb-4 rounded" style="background-color: #f8fafc; border: 1.5px solid #e2e8f0;">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-mortarboard mr-2 text-primary" style="font-size: 24px;"></i>
                                                <h5 class="mb-0 font-weight-bold" style="color: #1e293b;">
                                                    Menu Pembayaran Semester Akhir &amp; Kelulusan
                                                </h5>
                                            </div>
                                            <p class="text-muted mb-0" style="font-size: 13px;">
                                                Menu ini khusus terbuka bagi Anda yang telah memasuki <strong>Semester Akhir (Semester <?= htmlspecialchars($mahasiswa_info['semester']) ?>)</strong> pada Program Studi <?= htmlspecialchars($mahasiswa_info['prodi']) ?>.
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                            <?php if ($akses_tugas_akhir): ?>
                                                <span class="badge badge-success px-3 py-2" style="font-size: 13px; border-radius: 8px;">
                                                    <i class="bi bi-unlock-fill mr-1"></i> Akses Dibuka oleh Admin
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-3 py-2" style="font-size: 13px; border-radius: 8px;">
                                                    <i class="bi bi-lock-fill mr-1"></i> Akses Ditutup oleh Admin
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($is_semester_akhir && !$akses_tugas_akhir): ?>
                                    <div class="alert alert-warning text-center py-5" style="border-radius: 12px; background-color: #fffbeb; border: 1.5px solid #fde68a;">
                                        <i class="bi bi-shield-lock text-warning" style="font-size: 48px;"></i>
                                        <h5 class="font-weight-bold mt-3 mb-1" style="color: #92400e;">Akses Pembayaran Semester Akhir Sedang Ditutup</h5>
                                        <p class="text-muted mb-0" style="font-size: 13.5px; max-width: 540px; margin: 0 auto;">
                                            Periode pembayaran semester akhir belum dibuka atau sedang ditutup oleh Bagian Keuangan Kampus. Silakan hubungi admin keuangan jika jadwal pembayaran semester akhir Anda sudah dimulai.
                                        </p>
                                    </div>
                                <?php endif; ?>
                                    <div class="row">
                                        <?php foreach ($biaya_tambahan as $bt): ?>
                                            <?php
                                            $tagihan_biaya = null;
                                            foreach ($tagihan_pilihan as $tp) {
                                                if ($tp->jenis_tagihan === $bt['jenis_biaya']) {
                                                    $tagihan_biaya = $tp;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100" style="border-radius: 12px; border: 1.5px solid #e2e8f0; background: #ffffff;">
                                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                <strong style="color: #1e293b; font-size: 15px;"><?= htmlspecialchars(str_replace('Tugas Akhir', 'Semester Akhir', $bt['jenis_biaya'])) ?></strong>
                                                                <span class="badge badge-info" style="font-size: 11px;"><?= htmlspecialchars($bt['peruntukan']) ?></span>
                                                            </div>
                                                            <h4 class="font-weight-bold text-primary mb-2" style="font-size: 18px;">
                                                                Rp <?= number_format($bt['nominal'], 0, ',', '.') ?>
                                                            </h4>
                                                            <p class="text-muted mb-3" style="font-size: 12.5px;">
                                                                <?= htmlspecialchars($bt['keterangan']) ?>
                                                            </p>
                                                        </div>
                                                        <div>
                                                                <button type="button" class="btn btn-outline-primary btn-sm btn-block btn-open-bayar-general"
                                                                    data-id="<?= $tagihan_biaya ? (int)$tagihan_biaya->id : '' ?>"
                                                                    data-jenis="<?= htmlspecialchars($bt['jenis_biaya']) ?>"
                                                                    <?= $tagihan_biaya ? '' : 'disabled title="Tagihan belum tersedia"' ?>
                                                                    style="border-radius: 6px; font-weight: 600;">
                                                                <i class="bi bi-credit-card mr-1"></i> Pilih &amp; Bayar Item Ini
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                            </div>
                        <?php endif; ?>

                        <!-- TAB 3: INFORMASI BIAYA PERKULIAHAN LENGKAP -->
                        <div class="tab-pane fade p-4" id="tab-biaya" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="color: #1e293b; font-size: 16px;">
                                        Rincian Biaya Kuliah &amp; Biaya Tambahan
                                    </h5>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        Standar acuan biaya perkuliahan Program Studi <strong><?= htmlspecialchars($mahasiswa_info['prodi']) ?></strong> Semester Ganjil 2026/2027.
                                    </p>
                                </div>
                                <span class="tag-simulasi">Data Simulasi</span>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                                <i class="fa fa-folder-open mr-1"></i> 1. Rincian Biaya Kuliah Semester (Wajib SPP / UKT)
                            </h6>
                            <div class="mb-2 px-3 py-2" style="border-left:3px solid #1684cf; border-radius:4px; background:#f1f7fd; color:#526477; font-size:12px; line-height:1.5;">
                                <i class="bi bi-info-circle mr-1 text-primary"></i>
                                <strong>Keterangan:</strong> Tabel ini merinci komponen biaya semester wajib. Kategori mengelompokkan biaya, keterangan menjelaskan penggunaannya, dan nominal menunjukkan jumlah per komponen; total tercantum di baris terakhir.
                            </div>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Komponen Biaya</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot = 0; foreach ($komponen_biaya as $kb): $tot += $kb['nominal']; ?>
                                            <tr>
                                                <td class="text-center font-weight-bold"><?= $kb['no'] ?></td>
                                                <td>
                                                    <strong style="color: #1e293b;"><?= htmlspecialchars($kb['komponen']) ?></strong>
                                                    <?php if ($kb['syarat_krs']): ?>
                                                        <span class="badge badge-danger ml-1" style="font-size: 10px;">Syarat Mutlak KRS</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge badge-light px-2 py-1" style="border: 1px solid #cbd5e1;"><?= htmlspecialchars($kb['kategori']) ?></span></td>
                                                <td style="font-size: 13px; color: #475569;"><?= htmlspecialchars($kb['keterangan']) ?></td>
                                                <td class="text-right font-weight-bold" style="color: #1565c0; font-size: 14px;">
                                                    Rp <?= number_format($kb['nominal'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr style="background-color: #f8fafc;">
                                            <td colspan="4" class="text-right font-weight-bold" style="font-size: 14.5px;">TOTAL BIAYA SEMESTER:</td>
                                            <td class="text-right font-weight-bold" style="color: #1e3a8a; font-size: 16px;">
                                                Rp <?= number_format($tot, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                                <i class="fa fa-plus-circle mr-1"></i> 2. Biaya Tambahan (Di Luar Tagihan SPP / UKT Semester Reguler)
                            </h6>
                            <p class="text-muted mb-2" style="font-size: 12.5px;">
                                Biaya berikut tidak termasuk dalam tagihan semester reguler dan hanya dikenakan apabila mahasiswa menggunakan layanan tersebut (misalnya di semester akhir).
                            </p>
                            <div class="mb-2 px-3 py-2" style="border-left:3px solid #1684cf; border-radius:4px; background:#f1f7fd; color:#526477; font-size:12px; line-height:1.5;">
                                <i class="bi bi-info-circle mr-1 text-primary"></i>
                                <strong>Keterangan:</strong> Jenis biaya menunjukkan layanan tambahan; peruntukan menjelaskan siapa atau kondisi yang dikenai biaya; keterangan berisi rincian layanan; nominal adalah biaya untuk layanan tersebut.
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-keuangan">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Jenis Biaya Tambahan</th>
                                            <th>Peruntukan</th>
                                            <th>Keterangan</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($biaya_tambahan as $bt): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold"><?= $bt['no'] ?></td>
                                                <td><strong style="color: #1e293b;"><?= htmlspecialchars($bt['jenis_biaya']) ?></strong></td>
                                                <td><span class="badge badge-info px-2 py-1"><?= htmlspecialchars($bt['peruntukan']) ?></span></td>
                                                <td style="font-size: 13px; color: #475569;"><?= htmlspecialchars($bt['keterangan']) ?></td>
                                                <td class="text-right font-weight-bold" style="color: #047857; font-size: 14px;">
                                                    Rp <?= number_format($bt['nominal'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>




<!-- =======================================================
     MODAL KONFIRMASI PEMBAYARAN & UPLOAD BUKTI (BARU)
     ======================================================= -->


<div class="modal fade" id="modalKonfirmasiBayar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-primary text-white" style="padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-cash-stack mr-2"></i> Konfirmasi Pembayaran Tagihan Mahasiswa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span>&times;</span>
                </button>
            </div>

            <?= form_open_multipart('keuangan/konfirmasi_pembayaran', ['id' => 'formKonfirmasiPembayaran']) ?>
                <div class="modal-body" style="padding: 26px;">
                    <ol class="payment-stepper mb-4" id="paymentWizardSteps" aria-label="Tahapan konfirmasi pembayaran">
                        <li class="payment-stepper-item is-current">
                            <button type="button" class="payment-stepper-button" data-wizard-target="0" aria-current="step">
                                <span class="payment-step-marker">1</span><span>Input Info Bayar</span>
                            </button>
                        </li>
                        <li class="payment-stepper-item">
                            <button type="button" class="payment-stepper-button" data-wizard-target="1" disabled>
                                <span class="payment-step-marker">2</span><span>Upload Bukti Bayar</span>
                            </button>
                        </li>
                        <li class="payment-stepper-item">
                            <button type="button" class="payment-stepper-button" data-wizard-target="2" disabled>
                                <span class="payment-step-marker">3</span><span>Pilih Tanggungan</span>
                            </button>
                        </li>
                    </ol>

                    <section class="payment-stepper-panel" data-wizard-panel="0">
                    <p class="mb-3 text-muted" style="font-size:12px;">
                        <span class="text-danger font-weight-bold">*</span> Isi informasi sesuai transaksi pembayaran yang sudah dilakukan.
                    </p>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="metode_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Metode / Rekening Tujuan <span class="text-danger">*</span>
                            </label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                                <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                <option value="Virtual Account BNI">Virtual Account BNI</option>
                                <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                <option value="Teller Kampus">Pembayaran di Teller Kampus</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="tanggal_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Tanggal Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="jam_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Jam Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_pembayaran" id="jam_pembayaran" class="form-control" value="<?= date('H:i') ?>" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nomor_rekening" class="font-weight-bold" style="font-size: 13.5px;">
                            Nomor Rekening <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" placeholder="Masukkan nomor rekening pengirim" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama_rekening" class="font-weight-bold" style="font-size: 13.5px;">
                            Nama Pemilik Rekening <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" placeholder="Masukkan nama pemilik rekening" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                    </div>
                    </section>

                    <section class="payment-stepper-panel" data-wizard-panel="1" hidden>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold" style="font-size: 13.5px;">
                            Unggah Bukti Transfer <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="bukti_pembayaran" class="custom-file-input" id="buktiPembayaranInput" accept="image/png, image/jpeg, image/jpg, application/pdf" required>
                            <label class="custom-file-label" for="buktiPembayaranInput" id="buktiFileLabel" style="border-radius: 8px; height: 44px; line-height: 30px;">
                                Pilih berkas bukti transfer (JPG, PNG, PDF maks. 3MB)
                            </label>
                        </div>
                        <small class="text-muted mt-1 d-block">Mendukung format JPG, JPEG, PNG, dan PDF (maks. 3MB).</small>
                    </div>
                    </section>

                    <section class="payment-stepper-panel" data-wizard-panel="2" hidden>
                        <p class="mb-3 text-muted" style="font-size:12px;">
                            Pilih tanggungan yang dibayar dan periksa kembali rinciannya sebelum mengirim.
                        </p>
                        <div class="form-group mb-3">
                            <label for="select_tagihan_id" class="font-weight-bold" style="font-size: 13.5px;">
                                Pilih Tanggungan / Tagihan <span class="text-danger">*</span>
                            </label>
                            <select name="tagihan_id" id="select_tagihan_id" class="form-control" required style="height: 44px; border-radius: 8px; font-size: 14px;">
                                <?php if (empty($tagihan_pilihan)): ?>
                                    <option value="" disabled selected>Tidak ada tagihan tertunggak</option>
                                <?php else: ?>
                                    <?php foreach ($tagihan_pilihan as $tp): ?>
                                        <option value="<?= $tp->id ?>"
                                                data-jenis="<?= htmlspecialchars($tp->jenis_tagihan) ?>"
                                                data-status="<?= htmlspecialchars($tp->status, ENT_QUOTES, 'UTF-8') ?>"
                                                data-nominal="Rp <?= number_format($tp->nominal, 0, ',', '.') ?>"
                                                data-semester="<?= htmlspecialchars($tp->semester) ?> <?= htmlspecialchars($tp->tahun_akademik) ?>"
                                                data-tempo="<?= date('d M Y', strtotime($tp->jatuh_tempo)) ?>">
                                            <?= htmlspecialchars(str_replace('Tugas Akhir', 'Semester Akhir', $tp->jenis_tagihan)) ?> - Rp <?= number_format($tp->nominal, 0, ',', '.') ?> (<?= htmlspecialchars($tp->semester) ?> <?= htmlspecialchars($tp->tahun_akademik) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="p-3 rounded" style="background-color: #f0f7ff; border: 1.5px solid #bfdbfe;">
                            <div class="row align-items-center">
                                <div class="col-sm-8">
                                    <span class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 700;">Rincian Tanggungan</span>
                                    <h5 class="font-weight-bold mb-1" id="modalDetailJenis" style="color: #1e3a8a;">-</h5>
                                    <div style="font-size: 13px; color: #475569;">
                                        <span id="modalDetailSemester">-</span> &bull;
                                        Jatuh Tempo: <span id="modalDetailTempo" class="font-weight-bold text-danger">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                                    <span class="text-muted" style="font-size: 12px;">Nominal Kewajiban:</span>
                                    <h4 class="font-weight-bold text-primary mb-0" id="modalDetailNominal" style="color: #1565c0 !important;">-</h4>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

                <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-outline-secondary px-4" id="paymentWizardPrevious" style="border-radius: 8px;" hidden>Kembali</button>
                    <button type="button" class="btn btn-primary px-4" id="paymentWizardNext" style="border-radius: 8px; font-weight: 600; background-color: #1565c0;">
                        Lanjut <i class="bi bi-arrow-right ml-1"></i>
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow" id="paymentWizardSubmit" style="border-radius: 8px; font-weight: 600; background-color: #1565c0;" hidden>
                        <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Kirim Konfirmasi
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProgresPembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border:0; border-radius:14px; overflow:hidden;">
            <div class="modal-header" style="background:#eff6ff; border-bottom:1px solid #dbeafe;">
                <div>
                    <span class="d-block text-uppercase text-primary font-weight-bold" style="font-size:11px;">Pelacakan Pembayaran</span>
                    <h5 class="modal-title font-weight-bold mb-0" id="progressTagihanTitle" style="color:#1e40af !important;">Progres Tagihan</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup" style="color:#1e40af !important;"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div id="progressStatusMessage" class="payment-progress-message">Status pembayaran</div>
                <ol class="payment-tracker" id="paymentProgressTimeline">
                    <li class="payment-step" data-progress-step><span class="payment-step-marker">1</span><strong>Tagihan dibuat</strong><small class="d-block mt-1" data-step-detail></small></li>
                    <li class="payment-step" data-progress-step><span class="payment-step-marker">2</span><strong>Pembayaran dikirim</strong><small class="d-block mt-1" data-step-detail></small></li>
                    <li class="payment-step" data-progress-step><span class="payment-step-marker">3</span><strong>Verifikasi admin</strong><small class="d-block mt-1" data-step-detail></small></li>
                    <li class="payment-step" data-progress-step><span class="payment-step-marker">4</span><strong>Pembayaran selesai</strong><small class="d-block mt-1" data-step-detail></small></li>
                </ol>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:7px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL EDIT PEMBAYARAN MAHASISWA (FITUR BARU)
     Mahasiswa dapat mengoreksi data tanpa menunggu ditolak admin
     ======================================================= -->
<div class="modal fade" id="modalEditPembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-pencil-square mr-2"></i> Koreksi / Edit Bukti Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span>&times;</span>
                </button>
            </div>

            <?= form_open_multipart('keuangan/edit_pembayaran', ['id' => 'formEditPembayaran']) ?>
                <input type="hidden" name="pembayaran_id" id="edit_pembayaran_id" value="">

                <div class="modal-body" style="padding: 26px;">
                    
                    <div class="alert alert-warning mb-4" style="border-radius: 10px; font-size: 13px; border-left: 4px solid #f59e0b;">
                        <i class="bi bi-info-circle-fill mr-1"></i>
                        Anda sedang mengoreksi pembayaran yang berstatus <strong>Menunggu Verifikasi (PENDING)</strong>. Anda dapat mengganti metode transfer, tanggal bayar, nomor rekening, nama pemilik rekening, atau mengunggah ulang bukti transfer baru.
                    </div>

                    <div class="p-3 mb-4 rounded" style="background-color: #fefce8; border: 1.5px solid #fde047;">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <span class="text-muted" style="font-size: 12px; font-weight: 700;">Tagihan:</span>
                                <h5 class="font-weight-bold mb-0 text-dark" id="editTagihanJudul">-</h5>
                            </div>
                            <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                                <span class="text-muted" style="font-size: 12px;">Nominal:</span>
                                <h4 class="font-weight-bold text-dark mb-0" id="editTagihanNominal">-</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_metode_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select name="metode_pembayaran" id="edit_metode_pembayaran" class="form-control" required style="height: 44px; border-radius: 8px;">
                                <option value="Virtual Account BNI">Virtual Account BNI</option>
                                <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                <option value="Teller Kampus">Pembayaran di Teller Kampus</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_tanggal_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Tanggal Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal_pembayaran" id="edit_tanggal_pembayaran" class="form-control" max="<?= date('Y-m-d') ?>" required style="height: 44px; border-radius: 8px;">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_jam_pembayaran" class="font-weight-bold" style="font-size: 13.5px;">
                                Jam Transfer / Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_pembayaran" id="edit_jam_pembayaran" class="form-control" required style="height: 44px; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_nomor_rekening" class="font-weight-bold" style="font-size: 13.5px;">
                            Nomor Rekening <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nomor_rekening" id="edit_nomor_rekening" class="form-control" placeholder="Masukkan nomor rekening pengirim" required style="height: 44px; border-radius: 8px;">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_nama_rekening" class="font-weight-bold" style="font-size: 13.5px;">
                            Nama Pemilik Rekening <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_rekening" id="edit_nama_rekening" class="form-control" placeholder="Masukkan nama pemilik rekening" required style="height: 44px; border-radius: 8px;">
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold" style="font-size: 13.5px;">
                            Unggah Bukti Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti file bukti saat ini)</small>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="bukti_pembayaran" class="custom-file-input" id="editBuktiInput" accept="image/png, image/jpeg, image/jpg, application/pdf">
                            <label class="custom-file-label" for="editBuktiInput" id="editBuktiLabel" style="border-radius: 8px; height: 44px; line-height: 30px;">
                                Pilih berkas bukti baru (opsional)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 text-dark font-weight-bold shadow" style="border-radius: 8px;">
                        <i class="bi bi-check2-circle mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL BATALKAN PEMBAYARAN (KONFIRMASI MAHASISWA)
     ======================================================= -->
<div class="modal fade" id="modalBatalPembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header bg-danger text-white" style="border-radius: 16px 16px 0 0; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold">
                    <i class="bi bi-exclamation-triangle mr-2"></i> Batalkan Pengajuan Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px;">
                    <i class="bi bi-trash"></i>
                </div>
                <h5 class="font-weight-bold" style="color: #1e293b;">Yakin Ingin Membatalkan?</h5>
                <p style="font-size: 13.5px; color: #64748b;">
                    Pengajuan untuk tagihan <strong id="batalTagihanNama" class="text-dark"></strong> akan dibatalkan. Status tagihan akan dikembalikan menjadi <strong>BELUM BAYAR</strong> sehingga Anda dapat mengonfirmasi ulang kapan saja.
                </p>
            </div>
            <div class="modal-footer bg-light" style="padding: 14px 24px;">
                <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Kembali</button>
                <form method="POST" action="<?= base_url('keuangan/batalkan_pembayaran') ?>" style="display:inline;">
                    <input type="hidden" name="pembayaran_id" id="batalPembayaranId" value="">
                    <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px;">
                        <i class="bi bi-check mr-1"></i> Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     JAVASCRIPT INTERAKTIF & REAL-TIME SYNC
     ======================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const selectTagihan       = document.getElementById('select_tagihan_id');
    const modalDetailJenis    = document.getElementById('modalDetailJenis');
    const modalDetailNominal  = document.getElementById('modalDetailNominal');
    const modalDetailSemester = document.getElementById('modalDetailSemester');
    const modalDetailTempo    = document.getElementById('modalDetailTempo');
    const buktiInput          = document.getElementById('buktiPembayaranInput');
    const buktiLabel          = document.getElementById('buktiFileLabel');
    const wizardForm          = document.getElementById('formKonfirmasiPembayaran');
    const wizardSteps         = Array.from(document.querySelectorAll('#paymentWizardSteps [data-wizard-target]'));
    const wizardPanels        = Array.from(document.querySelectorAll('#formKonfirmasiPembayaran [data-wizard-panel]'));
    const wizardNext          = document.getElementById('paymentWizardNext');
    const wizardPrevious      = document.getElementById('paymentWizardPrevious');
    const wizardSubmit        = document.getElementById('paymentWizardSubmit');
    let currentWizardStep = 0;
    let unlockedWizardStep = 0;
    const completedWizardSteps = new Set();

    function renderWizardStep(stepIndex) {
        currentWizardStep = stepIndex;
        wizardPanels.forEach(function(panel, index) {
            panel.hidden = index !== stepIndex;
        });
        wizardSteps.forEach(function(button, index) {
            const item = button.closest('.payment-stepper-item');
            const isCurrent = index === stepIndex;
            button.disabled = index > unlockedWizardStep;
            if (isCurrent) {
                button.setAttribute('aria-current', 'step');
            } else {
                button.removeAttribute('aria-current');
            }
            item.classList.toggle('is-current', isCurrent);
            item.classList.toggle('is-complete', completedWizardSteps.has(index));
            const marker = button.querySelector('.payment-step-marker');
            marker.textContent = completedWizardSteps.has(index) ? '\u2713' : String(index + 1);
        });
        wizardPrevious.hidden = stepIndex === 0;
        wizardNext.hidden = stepIndex === wizardPanels.length - 1;
        wizardSubmit.hidden = stepIndex !== wizardPanels.length - 1;
    }

    function validateWizardStep(stepIndex) {
        const requiredFields = Array.from(wizardPanels[stepIndex].querySelectorAll(':required'));
        for (const field of requiredFields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    }

    if (wizardForm && wizardPanels.length && wizardSteps.length) {
        renderWizardStep(0);
        wizardNext.addEventListener('click', function() {
            if (!validateWizardStep(currentWizardStep)) return;
            completedWizardSteps.add(currentWizardStep);
            unlockedWizardStep = Math.min(currentWizardStep + 1, wizardPanels.length - 1);
            renderWizardStep(unlockedWizardStep);
        });
        wizardPrevious.addEventListener('click', function() {
            renderWizardStep(Math.max(0, currentWizardStep - 1));
        });
        wizardSteps.forEach(function(button, index) {
            button.addEventListener('click', function() {
                if (index <= unlockedWizardStep) renderWizardStep(index);
            });
        });
        wizardForm.addEventListener('submit', function(event) {
            if (currentWizardStep !== wizardPanels.length - 1) {
                event.preventDefault();
                return;
            }
            for (let index = 0; index < wizardPanels.length; index++) {
                const invalidField = Array.from(wizardPanels[index].querySelectorAll(':required'))
                    .find(function(field) { return !field.checkValidity(); });
                if (invalidField) {
                    event.preventDefault();
                    completedWizardSteps.delete(index);
                    unlockedWizardStep = Math.max(unlockedWizardStep, index);
                    renderWizardStep(index);
                    invalidField.reportValidity();
                    return;
                }
                completedWizardSteps.add(index);
            }
        });
        $('#modalKonfirmasiBayar').on('hidden.bs.modal', function() {
            completedWizardSteps.clear();
            unlockedWizardStep = 0;
            renderWizardStep(0);
        });
    }

    // 1. Update Preview Kotak Detail Tagihan
    function updateModalDetailFromSelect() {
        if (!selectTagihan) return;
        const opt = selectTagihan.options[selectTagihan.selectedIndex];
        if (opt && opt.value) {
            if (modalDetailJenis) modalDetailJenis.textContent = (opt.getAttribute('data-jenis') || '-').replace(/Tugas Akhir/gi, 'Semester Akhir');
            if (modalDetailNominal) modalDetailNominal.textContent = opt.getAttribute('data-nominal') || '-';
            if (modalDetailSemester) modalDetailSemester.textContent = opt.getAttribute('data-semester') || '-';
            if (modalDetailTempo) modalDetailTempo.textContent = opt.getAttribute('data-tempo') || '-';
        }
    }

    if (selectTagihan) {
        selectTagihan.addEventListener('change', updateModalDetailFromSelect);
        updateModalDetailFromSelect();
    }

    // 2. Klik Tombol Bayar Sekarang / Upload Ulang
    document.querySelectorAll('.btn-bayar-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const tagihanId = this.getAttribute('data-id');
            const jenis     = this.getAttribute('data-jenis');
            const nominal   = this.getAttribute('data-nominal');
            const semester  = this.getAttribute('data-semester');
            const tempo     = this.getAttribute('data-tempo');

            if (selectTagihan) selectTagihan.value = tagihanId;
            if (modalDetailJenis) modalDetailJenis.textContent = (jenis || '-').replace(/Tugas Akhir/gi, 'Semester Akhir');
            if (modalDetailNominal) modalDetailNominal.textContent = nominal;
            if (modalDetailSemester) modalDetailSemester.textContent = semester;
            if (modalDetailTempo) modalDetailTempo.textContent = tempo;

            $('#modalKonfirmasiBayar').modal('show');
        });
    });

    document.querySelectorAll('.btn-lihat-progres').forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.getAttribute('data-status') || 'BELUM_BAYAR';
            const title = this.getAttribute('data-jenis') || 'Tagihan';
            const sentAt = this.getAttribute('data-pembayaran-dikirim') || '';
            const verifiedAt = this.getAttribute('data-diverifikasi') || '';
            const admin = this.getAttribute('data-admin') || '';
            const rejection = this.getAttribute('data-alasan') || '';
            const statusMessage = document.getElementById('progressStatusMessage');
            const steps = Array.from(document.querySelectorAll('#paymentProgressTimeline [data-progress-step]'));
            const stepDetails = Array.from(document.querySelectorAll('#paymentProgressTimeline [data-step-detail]'));
            const completionIndex = status === 'LUNAS' ? 3 : status === 'PENDING' ? 2 : status === 'DITOLAK' ? 2 : 0;

            document.getElementById('progressTagihanTitle').textContent = title;
            statusMessage.textContent = status === 'LUNAS'
                ? 'Pembayaran telah diverifikasi dan dinyatakan lunas.'
                : status === 'PENDING'
                    ? 'Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi admin.'
                    : status === 'DITOLAK'
                        ? 'Pembayaran ditolak admin. Periksa catatan dan kirim ulang bukti yang benar.'
                        : 'Tagihan tersedia. Silakan kirim pembayaran dan bukti transfer untuk memulai verifikasi.';
            statusMessage.style.background = status === 'DITOLAK' ? '#fff1f2' : status === 'LUNAS' ? '#ecfdf5' : '#f8fafc';
            statusMessage.style.setProperty('color', '#1e40af', 'important');

            const details = [
                this.getAttribute('data-tagihan-dibuat') || 'Tagihan tersedia',
                sentAt || 'Menunggu pembayaran',
                status === 'LUNAS' || status === 'DITOLAK'
                    ? (admin ? 'Oleh ' + admin : 'Diproses admin') + (verifiedAt ? ' · ' + verifiedAt : '')
                    : status === 'PENDING' ? 'Menunggu tindakan admin' : 'Menunggu bukti pembayaran',
                status === 'LUNAS' ? 'Selesai' : status === 'DITOLAK' ? 'Belum selesai' : 'Menunggu verifikasi'
            ];

            steps.forEach((step, index) => {
                step.classList.toggle('is-complete', index < completionIndex || (status === 'LUNAS' && index === completionIndex));
                step.classList.toggle('is-current', status !== 'LUNAS' && index === completionIndex);
                step.classList.toggle('is-rejected', status === 'DITOLAK' && index === completionIndex);
                stepDetails[index].textContent = details[index];
                const marker = step.querySelector('.payment-step-marker');
                if (marker) marker.innerHTML = step.classList.contains('is-complete') ? '<i class="bi bi-check-lg"></i>' : String(index + 1);
            });

            if (status === 'DITOLAK' && rejection) {
                statusMessage.textContent += ' Catatan admin: ' + rejection;
            }
            $('#modalProgresPembayaran').modal('show');
        });
    });

    // 3. Tombol General Konfirmasi Bayar dan Pilih & Bayar Item Tugas Akhir
    document.querySelectorAll('.btn-open-bayar-general').forEach(btn => {
        btn.addEventListener('click', function() {
            const tagihanId = this.getAttribute('data-id');
            const jenisItem = this.getAttribute('data-jenis');
            if (!selectTagihan) {
                return;
            }

            if (tagihanId) {
                selectTagihan.value = tagihanId;
            } else if (jenisItem) {
                const opsiItem = Array.from(selectTagihan.options).find(function(option) {
                    return option.getAttribute('data-jenis') === jenisItem;
                });
                if (opsiItem) selectTagihan.value = opsiItem.value;
            }
            updateModalDetailFromSelect();
            $('#modalKonfirmasiBayar').modal('show');
        });
    });

    // 4. Modal Edit Pembayaran Mahasiswa
    document.querySelectorAll('.btn-edit-pembayaran').forEach(btn => {
        btn.addEventListener('click', function() {
            const id      = this.getAttribute('data-id');
            const tagihan = this.getAttribute('data-tagihan');
            const nominal = this.getAttribute('data-nominal');
            const metode  = this.getAttribute('data-metode');
            const tgl     = this.getAttribute('data-tanggal');
            const jam     = this.getAttribute('data-jam');
            const rekening = this.getAttribute('data-rekening');
            const namaRekening = this.getAttribute('data-nama-rekening');

            document.getElementById('edit_pembayaran_id').value = id;
            document.getElementById('editTagihanJudul').textContent = tagihan;
            document.getElementById('editTagihanNominal').textContent = nominal;
            document.getElementById('edit_metode_pembayaran').value = metode;
            document.getElementById('edit_tanggal_pembayaran').value = tgl;
            document.getElementById('edit_jam_pembayaran').value = jam || '';
            document.getElementById('edit_nomor_rekening').value = rekening;
            document.getElementById('edit_nama_rekening').value = namaRekening;

            $('#modalEditPembayaran').modal('show');
        });
    });

    // 5. Modal Batalkan Pembayaran
    document.querySelectorAll('.btn-batal-pembayaran').forEach(btn => {
        btn.addEventListener('click', function() {
            const id      = this.getAttribute('data-id');
            const tagihan = this.getAttribute('data-tagihan');

            document.getElementById('batalPembayaranId').value = id;
            document.getElementById('batalTagihanNama').textContent = tagihan;

            $('#modalBatalPembayaran').modal('show');
        });
    });

    // 6. File input labels
    if (buktiInput && buktiLabel) {
        buktiInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            buktiLabel.textContent = file ? file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)' : 'Pilih berkas bukti transfer';
        });
    }
    const editBukti = document.getElementById('editBuktiInput');
    const editLabel = document.getElementById('editBuktiLabel');
    if (editBukti && editLabel) {
        editBukti.addEventListener('change', function(e) {
            const file = e.target.files[0];
            editLabel.textContent = file ? file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)' : 'Pilih berkas bukti baru (opsional)';
        });
    }

    // 7. Salin Virtual Account / Rekening
    document.querySelectorAll('.btn-copy-rek').forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const orig = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                    setTimeout(() => { this.innerHTML = orig; }, 1500);
                });
            }
        });
    });

    // =======================================================
    // 8. REAL-TIME VERIFICATION SYNC (POLLING AJAX)
    // =======================================================
    let lastHash = '';
    const activeAkunId = <?= isset($akun_id_aktif) ? (int)$akun_id_aktif : (int)$user['id'] ?>;

    function checkRealtimeStatus() {
        $.ajax({
            url: '<?= base_url('keuangan/status_realtime?mahasiswa_id=') ?>' + activeAkunId,
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.success && resp.data) {
                    const d = resp.data;
                    
                    if (lastHash && lastHash !== d.hash) {
                        // Terjadi perubahan status dari Admin Keuangan!
                        $('#toastTitle').text('Status Pembayaran Diperbarui!');
                        if (d.status_krs === 'LUNAS') {
                            $('#toastBody').html('Selamat! Pembayaran Anda telah <strong>DIVERIFIKASI (LUNAS)</strong>. Akses KRS terbuka.');
                            $('#realtimeToast').css('background', '#10b981').fadeIn();
                        } else if (d.status_krs === 'DITOLAK') {
                            $('#toastBody').html('Perhatian: Pembayaran Anda <strong>DITOLAK</strong> oleh Admin. Silakan perbaiki bukti pembayaran.');
                            $('#realtimeToast').css('background', '#ef4444').fadeIn();
                        } else {
                            $('#toastBody').text('Status pembayaran Anda telah diperbarui oleh Admin Keuangan.');
                            $('#realtimeToast').css('background', '#1565c0').fadeIn();
                        }

                        // Reload data halaman secara halus setelah 2 detik
                        setTimeout(function() {
                            window.location.reload();
                        }, 2500);
                    }
                    lastHash = d.hash;
                }
            },
            error: function() {
                // Abaikan kesalahan koneksi sementara
            }
        });
    }

    // Jalankan polling setiap 5 detik
    setInterval(checkRealtimeStatus, 5000);
    // Jalankan satu kali segera saat halaman dimuat
    checkRealtimeStatus();

    $('#modalKonfirmasiBayar').on('shown.bs.modal', function () {
        updateModalDetailFromSelect();
    });
});
</script>
