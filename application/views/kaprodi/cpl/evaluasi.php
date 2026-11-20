<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Tailwind CSS non-preflight for seamless admin integration -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false,
        }
    }
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .eval-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .eval-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- TOP BAR: HEADER & FILTERS -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fa fa-university mr-1"></i> Program Studi <?= html_escape($eval_data['prodi']->nama_prodi ?? 'Teknik Informatika') ?>
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <i class="fa fa-mortar-board mr-1"></i> Penjaminan Mutu OBE
                                </span>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900 mt-2 mb-1">
                                Evaluasi Ketercapaian Kurikulum &amp; CPL Program Studi
                            </h2>
                            <p class="text-xs text-slate-500 m-0">
                                Rekapitulasi rata-rata capaian seluruh Capaian Pembelajaran Lulusan (CPL) dari asesmen bottom-up Sub-CPMK mahasiswa untuk perbaikan berkelanjutan (CQI).
                            </p>
                        </div>

                        <!-- FILTER ANGKATAN & PRODI -->
                        <div class="flex items-center gap-2 flex-wrap bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <form method="GET" class="flex items-center gap-2 m-0 flex-wrap">
                                <?php if (!empty($is_superadmin) && !empty($all_prodi)): ?>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-slate-600 font-semibold"><i class="fa fa-sitemap text-blue-600"></i> Prodi:</span>
                                        <select name="prodi_id" onchange="this.form.submit()" class="text-xs bg-white text-slate-800 border border-slate-300 rounded-lg px-2.5 py-1.5 outline-none font-medium cursor-pointer">
                                            <?php foreach ($all_prodi as $pr): ?>
                                                <option value="<?= $pr->id ?>" <?= ($prodi_id == $pr->id) ? 'selected' : '' ?>>
                                                    <?= html_escape($pr->nama_prodi) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-slate-600 font-semibold"><i class="fa fa-calendar text-emerald-600"></i> Angkatan:</span>
                                    <select name="angkatan" onchange="this.form.submit()" class="text-xs bg-white text-slate-800 border border-slate-300 rounded-lg px-2.5 py-1.5 outline-none font-medium cursor-pointer">
                                        <option value="all" <?= ($selected_angkatan === 'all') ? 'selected' : '' ?>>-- Seluruh Angkatan --</option>
                                        <?php foreach ($eval_data['angkatan_list'] as $ang): ?>
                                            <option value="<?= $ang ?>" <?= ($selected_angkatan == $ang) ? 'selected' : '' ?>>
                                                Angkatan <?= html_escape($ang) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <a href="<?= base_url('kaprodi/cpl') ?>" class="text-xs bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg px-3 py-1.5 font-semibold text-decoration-none transition inline-flex items-center gap-1.5">
                                    <i class="fa fa-cog text-blue-600"></i> Kelola CPL
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 4 KPI SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                    <!-- KPI 1: Rata-Rata CPL Prodi -->
                    <div class="eval-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-Rata Capaian CPL</span>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-black <?= ($eval_data['overall_avg'] >= 60.0) ? 'text-blue-700' : 'text-amber-700' ?>">
                                <?= number_format((float)$eval_data['overall_avg'], 1) ?>%
                            </span>
                            <span class="text-xs font-medium text-slate-400">/ 100%</span>
                        </div>
                        <div class="mt-2 text-xs <?= ($eval_data['overall_avg'] >= 60.0) ? 'text-emerald-600 font-semibold' : 'text-amber-600 font-semibold' ?> flex items-center gap-1">
                            <i class="fa <?= ($eval_data['overall_avg'] >= 60.0) ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                            <span><?= ($eval_data['overall_avg'] >= 60.0) ? 'Memenuhi Standar Mutu OBE' : 'Perlu Peningkatan Kurikulum' ?></span>
                        </div>
                    </div>

                    <!-- KPI 2: Mahasiswa Teranalisis -->
                    <div class="eval-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mahasiswa Teranalisis</span>
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-black text-slate-800">
                                <?= (int)$eval_data['total_students'] ?>
                            </span>
                            <span class="text-xs font-medium text-slate-400">Mahasiswa</span>
                        </div>
                        <div class="mt-2 text-xs text-slate-500">
                            Cakupan: <strong><?= ($selected_angkatan === 'all') ? 'Seluruh Angkatan Aktif' : 'Angkatan ' . html_escape($selected_angkatan) ?></strong>
                        </div>
                    </div>

                    <!-- KPI 3: CPL Memenuhi Standar (>= 60%) -->
                    <div class="eval-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">CPL Memenuhi Standar</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                                <i class="fa fa-check-square-o"></i>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-black text-emerald-600">
                                <?= count($eval_data['strong_cpls']) ?>
                            </span>
                            <span class="text-xs font-medium text-slate-400">dari <?= count($eval_data['cpls']) ?> CPL (>= 60%)</span>
                        </div>
                        <div class="mt-2 text-xs text-slate-500">
                            <?php if (!empty($eval_data['strongest_cpl'])): ?>
                                Tertinggi: <strong class="text-emerald-700"><?= html_escape($eval_data['strongest_cpl']['kode_cpl']) ?> (<?= number_format($eval_data['strongest_cpl']['avg_score'], 1) ?>%)</strong>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- KPI 4: CPL Perlu Penguatan (< 60%) -->
                    <div class="eval-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">CPL Perlu Penguatan</span>
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                                <i class="fa fa-warning"></i>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-black <?= count($eval_data['weak_cpls']) > 0 ? 'text-rose-600' : 'text-emerald-600' ?>">
                                <?= count($eval_data['weak_cpls']) ?>
                            </span>
                            <span class="text-xs font-medium text-slate-400">CPL di bawah 60%</span>
                        </div>
                        <div class="mt-2 text-xs text-slate-500">
                            <?php if (!empty($eval_data['weakest_cpl'])): ?>
                                Terendah: <strong class="text-rose-700"><?= html_escape($eval_data['weakest_cpl']['kode_cpl']) ?> (<?= number_format($eval_data['weakest_cpl']['avg_score'], 1) ?>%)</strong>
                            <?php else: ?>
                                Seluruh CPL Memenuhi Standar
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ALERT NOTICE JIKA ADA CPL LEMAH (< 60%) -->
                <?php if ($eval_data['has_weak']): ?>
                <div class="mb-6 p-4 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-950 flex items-start gap-3.5 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-amber-200 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold m-0 text-amber-950 flex items-center gap-2">
                            <span>Peringatan Evaluasi Kurikulum: Terdeteksi <?= count($eval_data['weak_cpls']) ?> Dimensi CPL di Bawah Standar Minimum (&lt; 60%)</span>
                        </h4>
                        <p class="text-xs text-amber-900 mt-1 mb-0 leading-relaxed">
                            CPL berikut memerlukan evaluasi kurikulum terpadu: 
                            <?php foreach ($eval_data['weak_cpls'] as $wc): ?>
                                <span class="bg-amber-200/80 font-bold px-2 py-0.5 rounded text-amber-900 mr-1"><?= html_escape($wc['kode_cpl']) ?> (<?= number_format($wc['avg_score'], 1) ?>%)</span>
                            <?php endforeach; ?>.
                            Disarankan untuk meninjau kembali silabus RPS, meningkatkan proporsi asesmen praktikum, serta memberikan modul remedial terstruktur pada mata kuliah pendukung CPL tersebut.
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- CHARTS ROW: BAR CHART & RADAR CHART (SIDE-BY-SIDE) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
                    
                    <!-- BAR CHART: Rata-Rata Capaian CPL vs Target Standar (7 Cols) -->
                    <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-sm font-bold text-slate-900 m-0">
                                    <i class="fa fa-bar-chart text-blue-600 mr-1.5"></i> Profil Rata-Rata Capaian CPL vs Target Standar (60%)
                                </h3>
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-blue-200">
                                    Garis Standar: 60.0%
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                Grafik batang membandingkan skor rata-rata mahasiswa prodi terhadap batas ambang minimal mutu kelulusan OBE.
                            </p>
                        </div>

                        <!-- Canvas Bar Chart -->
                        <div class="relative w-full h-72">
                            <canvas id="cplBarChart"></canvas>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-around text-xs text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                                <span>Rata-rata &ge; 60% (Lulus)</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                                <span>Rata-rata &lt; 60% (Perlu Penguatan)</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3.5 h-1 bg-emerald-500 inline-block border-t border-dashed"></span>
                                <span>Ambang Batas (60%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- RADAR CHART: Poligon Profil Kurikulum Prodi (5 Cols) -->
                    <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-sm font-bold text-slate-900 m-0">
                                    <i class="fa fa-compass text-teal-600 mr-1.5"></i> Radar Keseimbangan Kurikulum Prodi
                                </h3>
                                <span class="bg-teal-50 text-teal-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-teal-200">
                                    Poligon OBE
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                Visualisasi poligon keseimbangan dimensi CPL (Sikap, Pengetahuan, Keterampilan Umum, Keterampilan Khusus).
                            </p>
                        </div>

                        <!-- Canvas Radar Chart -->
                        <div class="relative w-full h-72 flex items-center justify-center my-auto">
                            <canvas id="cplRadarProdiChart"></canvas>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-around text-xs text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-teal-600 inline-block"></span>
                                <span>Rata-Rata Prodi</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
                                <span>Target Minimal (60%)</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DETAIL DATA TABLE: CAPAIAN CPL & REKOMENDASI TINDAK LANJUT -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 m-0">
                                Matriks Evaluasi Capaian Pembelajaran Lulusan &amp; Rekomendasi
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Rincian agregasi data per CPL beserta persentase mahasiswa yang telah memenuhi standar kompetensi.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold border border-slate-200">
                                Total CPL: <?= count($eval_data['cpls']) ?> Dimensi
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive border border-slate-200 rounded-xl overflow-hidden">
                        <table class="table table-hover mb-0" style="font-size: 13px;">
                            <thead style="background: #f8fafc; color: #1e293b;">
                                <tr>
                                    <th class="text-center py-3" style="width: 45px;">No.</th>
                                    <th class="py-3" style="width: 100px;">Kode CPL</th>
                                    <th class="py-3" style="width: 140px;">Dimensi Kategori</th>
                                    <th class="py-3">Deskripsi Capaian Pembelajaran Lulusan</th>
                                    <th class="text-center py-3" style="width: 120px;">Rata-Rata Capaian</th>
                                    <th class="text-center py-3" style="width: 120px;">% Kelulusan Mhs</th>
                                    <th class="text-center py-3" style="width: 135px;">Status Evaluasi</th>
                                    <th class="py-3" style="width: 250px;">Rekomendasi Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($eval_data['cpls'] as $item): ?>
                                    <?php 
                                        $avg = (float)$item['avg_score'];
                                        $is_weak = $item['is_weak'];
                                    ?>
                                    <tr class="<?= $is_weak ? 'bg-rose-50/30' : '' ?>">
                                        <td class="text-center font-bold text-slate-600"><?= $no++ ?></td>
                                        <td>
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 font-mono font-bold text-xs rounded-md">
                                                <?= html_escape($item['kode_cpl']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-semibold text-slate-600">
                                                <?= html_escape($item['kategori']) ?>
                                            </span>
                                        </td>
                                        <td style="color: #334155; line-height: 1.45;">
                                            <?= html_escape($item['deskripsi']) ?>
                                        </td>
                                        <!-- Rata-Rata Skor -->
                                        <td class="text-center">
                                            <span class="font-extrabold <?= $is_weak ? 'text-rose-600' : 'text-blue-700' ?>" style="font-size: 14px;">
                                                <?= number_format($avg, 1) ?>%
                                            </span>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-1 overflow-hidden">
                                                <div class="<?= $is_weak ? 'bg-rose-500' : 'bg-blue-600' ?> h-1.5 rounded-full" style="width: <?= min(100, max(0, $avg)) ?>%;"></div>
                                            </div>
                                        </td>
                                        <!-- % Mahasiswa Lulus (>= 60) -->
                                        <td class="text-center">
                                            <span class="font-bold text-slate-800">
                                                <?= number_format((float)$item['pct_lulus'], 1) ?>%
                                            </span>
                                            <small class="block text-slate-400 text-[11px]">
                                                (<?= $item['lulus_count'] ?> / <?= $item['lulus_count'] + $item['gap_count'] ?> mhs)
                                            </small>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="text-center">
                                            <?php if ($is_weak): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1">
                                                    <i class="fa fa-warning"></i> Perlu Penguatan
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                                    <i class="fa fa-check"></i> Memenuhi Standar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- Rekomendasi -->
                                        <td style="font-size: 12px; color: #475569; line-height: 1.4;">
                                            <?php if ($is_weak): ?>
                                                <strong class="text-rose-700 block mb-0.5"><i class="fa fa-arrow-circle-right mr-1"></i> Perhatian Khusus:</strong>
                                            <?php endif; ?>
                                            <?= html_escape($item['rekomendasi']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = <?= json_encode($eval_data['chart_labels']) ?>;
        const scores = <?= json_encode($eval_data['chart_scores']) ?>;
        const targets = <?= json_encode($eval_data['chart_targets']) ?>;

        // 1. BAR CHART: Rata-rata Skor per CPL vs 60% Target
        const ctxBar = document.getElementById('cplBarChart').getContext('2d');
        const barColors = scores.map(s => (s >= 60.0 ? '#2563eb' : '#f43f5e'));

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rata-Rata Capaian (%)',
                        data: scores,
                        backgroundColor: barColors,
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 24,
                    },
                    {
                        label: 'Standar Kelulusan (60%)',
                        data: targets,
                        type: 'line',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: {
                            stepSize: 20,
                            callback: function(value) { return value + '%'; },
                            font: { size: 10 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: 'bold', size: 11 },
                            color: '#1e293b'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });

        // 2. RADAR CHART: Poligon Profil Kurikulum Prodi
        const ctxRadar = document.getElementById('cplRadarProdiChart').getContext('2d');
        new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rata-Rata Prodi',
                        data: scores,
                        backgroundColor: 'rgba(13, 148, 136, 0.25)',
                        borderColor: '#0d9488',
                        pointBackgroundColor: '#0d9488',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#0d9488',
                        borderWidth: 2,
                    },
                    {
                        label: 'Batas Minimal (60%)',
                        data: targets,
                        backgroundColor: 'transparent',
                        borderColor: '#10b981',
                        borderWidth: 1.5,
                        borderDash: [4, 4],
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: '#e2e8f0' },
                        grid: { color: '#f1f5f9' },
                        pointLabels: {
                            font: { size: 11, weight: 'bold', family: "'Inter', sans-serif" },
                            color: '#1e293b'
                        },
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: {
                            stepSize: 20,
                            font: { size: 9 },
                            backdropColor: 'transparent',
                            color: '#64748b'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.r.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
