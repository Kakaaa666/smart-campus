<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Tailwind CSS CDN non-preflight -->
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

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Header Banner -->
                <div class="bg-gradient-to-r from-teal-900 via-emerald-900 to-slate-900 rounded-3xl p-6 text-white mb-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 opacity-10 pointer-events-none">
                        <i class="fa fa-shield text-9xl"></i>
                    </div>
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                <span class="bg-emerald-500/30 text-emerald-200 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-400/20 whitespace-nowrap shrink-0">
                                    Sistem Penjaminan Mutu Internal (SPMI)
                                </span>
                                <span class="bg-blue-500/30 text-blue-200 text-xs font-semibold px-3 py-1 rounded-full border border-blue-400/20 whitespace-nowrap shrink-0">
                                    Audit OBE & Akreditasi
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">Dashboard Penjaminan Mutu OBE</h2>
                            <p class="text-emerald-100 text-sm mt-1 max-w-2xl">
                                Monitoring ketercapaian CPL lintas program studi dan audit kepatuhan dosen dalam melengkapi rencana asesmen RPS 100%.
                            </p>
                        </div>

                        <!-- Prodi Filter Selector -->
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/20 shrink-0 self-start xl:self-auto">
                            <form method="GET" action="<?= base_url('penjaminan-mutu/obe/' . ($active_tab === 'audit' ? 'audit_rps' : 'monitoring_cpl')) ?>" class="m-0 flex flex-wrap items-center gap-2">
                                <span class="text-xs text-white/80 whitespace-nowrap font-medium">Filter Prodi:</span>
                                <select name="prodi_id" onchange="this.form.submit()" class="text-xs bg-slate-800 text-white border border-white/30 rounded-xl px-3 py-1.5 outline-none cursor-pointer max-w-full">
                                    <option value="">-- Seluruh Program Studi --</option>
                                    <?php foreach ($prodi_list as $pl): ?>
                                    <option value="<?= $pl->id ?>" <?= ($prodi_id == $pl->id) ? 'selected' : '' ?>>
                                        <?= html_escape($pl->nama_prodi) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- KPI Audit Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Kepatuhan RPS Institusi</span>
                            <span class="text-2xl font-black text-emerald-600"><?= $rps_audit['persentase_kepatuhan'] ?>%</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Target Standar: &ge; 85%</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <i class="fa fa-percent"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">RPS Lengkap (100%)</span>
                            <span class="text-2xl font-bold text-slate-800"><?= $rps_audit['total_lengkap'] ?></span>
                            <span class="text-[10px] text-emerald-600 block mt-0.5">Memenuhi Syarat OBE</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">RPS Belum Lengkap</span>
                            <span class="text-2xl font-bold text-amber-600"><?= $rps_audit['total_sebagian'] ?></span>
                            <span class="text-[10px] text-amber-600 block mt-0.5">Bobot &lt; 100%</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">RPS Belum Diatur</span>
                            <span class="text-2xl font-bold text-rose-600"><?= $rps_audit['total_belum'] ?></span>
                            <span class="text-[10px] text-rose-600 block mt-0.5">Butuh Tindak Lanjut</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                            <i class="fa fa-times-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6 overflow-hidden">
                    <div class="flex border-b border-slate-200 px-4 pt-3 bg-slate-50/50 gap-2">
                        <a href="<?= base_url('penjaminan-mutu/obe/monitoring_cpl' . ($prodi_id ? '?prodi_id=' . $prodi_id : '')) ?>" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition flex items-center gap-2 <?= $active_tab === 'monitoring' ? 'border-b-2 border-emerald-600 text-emerald-700 bg-white shadow-sm' : 'text-slate-500 hover:text-slate-800' ?>">
                            <i class="fa fa-bar-chart"></i> 1. Monitoring Ketercapaian CPL Antar-Prodi
                        </a>
                        <a href="<?= base_url('penjaminan-mutu/obe/audit_rps' . ($prodi_id ? '?prodi_id=' . $prodi_id : '')) ?>" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition flex items-center gap-2 <?= $active_tab === 'audit' ? 'border-b-2 border-emerald-600 text-emerald-700 bg-white shadow-sm' : 'text-slate-500 hover:text-slate-800' ?>">
                            <i class="fa fa-list-alt"></i> 2. Audit Kepatuhan RPS Dosen Pengampu (100%)
                        </a>
                    </div>

                    <?php if ($active_tab === 'monitoring'): ?>
                    <!-- TAB 1: MONITORING CPL & PERBANDINGAN GRAFIK -->
                    <div class="p-6">
                        <!-- Chart Section -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                            <div class="lg:col-span-8 bg-slate-50/60 p-5 rounded-2xl border border-slate-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 m-0">Grafik Perbandingan Rata-rata Ketercapaian CPL Antar-Prodi</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Nilai agregat ketercapaian CPL program studi dengan garis standar kelulusan (70.0).</p>
                                    </div>
                                </div>
                                <div class="relative w-full h-72">
                                    <canvas id="prodiComparisonChart"></canvas>
                                </div>
                            </div>

                            <div class="lg:col-span-4 flex flex-col justify-between space-y-3">
                                <div class="bg-blue-50/70 p-4 rounded-2xl border border-blue-200">
                                    <span class="text-xs font-bold text-blue-900 block mb-1">
                                        <i class="fa fa-info-circle text-blue-600 mr-1"></i> Standar Evaluasi Mutu
                                    </span>
                                    <p class="text-xs text-blue-800 leading-relaxed m-0">
                                        Sesuai regulasi SPMI, capaian CPL dengan skor <strong>&lt; 70.00</strong> dikategorikan sebagai <strong>"Perlu Evaluasi"</strong> dan wajib dilakukan tinjauan RPS serta metode pengajaran pada semester berikutnya.
                                    </p>
                                </div>

                                <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200">
                                    <span class="text-xs font-bold text-amber-900 block mb-1">
                                        <i class="fa fa-warning text-amber-600 mr-1"></i> Peringatan Audit
                                    </span>
                                    <?php
                                        $eval_cpl_count = 0;
                                        foreach ($cpl_monitoring['data'] as $pData) {
                                            foreach ($pData['cpl_list'] as $cp) {
                                                if ($cp['is_evaluasi']) $eval_cpl_count++;
                                            }
                                        }
                                    ?>
                                    <p class="text-xs text-amber-800 leading-relaxed m-0">
                                        Ditemukan sebanyak <strong class="text-rose-600"><?= $eval_cpl_count ?> CPL</strong> yang berada di bawah target ambang kelulusan (&lt; 70).
                                    </p>
                                </div>

                                <div class="bg-emerald-50/70 p-4 rounded-2xl border border-emerald-200">
                                    <span class="text-xs font-bold text-emerald-900 block mb-1">
                                        <i class="fa fa-lightbulb-o text-emerald-600 mr-1"></i> Rekomendasi Tindak Lanjut
                                    </span>
                                    <p class="text-xs text-emerald-800 leading-relaxed m-0">
                                        Biro Penjaminan Mutu merekomendasikan lokakarya peningkatan metode asesmen berbasis studi kasus (Case Method) dan Team-Based Project.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Detail CPL per Program Studi -->
                        <div class="space-y-6">
                            <?php foreach ($cpl_monitoring['data'] as $pData): ?>
                            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="bg-slate-100/90 px-5 py-3.5 flex items-center justify-between border-b border-slate-200 flex-wrap gap-2">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-teal-600 text-white font-bold flex items-center justify-center font-mono text-xs">
                                            <?= html_escape($pData['kode_prodi']) ?>
                                        </span>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900 m-0">
                                                Program Studi <?= html_escape($pData['nama_prodi']) ?>
                                            </h4>
                                            <span class="text-[11px] text-slate-500"><?= html_escape($pData['fakultas']) ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-600">Rata-rata CPL Prodi:</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-black <?= $pData['rata_rata_prodi'] >= 70 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                            <?= number_format($pData['rata_rata_prodi'], 1) ?> / 100
                                        </span>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs border-collapse min-w-[750px]">
                                        <thead>
                                            <tr class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                                <th class="py-3 px-4 w-28 whitespace-nowrap">Kode CPL</th>
                                                <th class="py-3 px-3 w-40 whitespace-nowrap">Kategori</th>
                                                <th class="py-3 px-4 min-w-[220px]">Deskripsi Rumusan CPL</th>
                                                <th class="py-3 px-3 w-32 text-center whitespace-nowrap">Rata-rata Skor</th>
                                                <th class="py-3 px-4 w-44 text-center whitespace-nowrap">Status Audit Mutu</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <?php if (empty($pData['cpl_list'])): ?>
                                            <tr>
                                                <td colspan="5" class="py-6 text-center text-slate-400">Belum ada CPL untuk program studi ini.</td>
                                            </tr>
                                            <?php else: ?>
                                                <?php foreach ($pData['cpl_list'] as $cp): ?>
                                                <tr class="hover:bg-slate-50/80 transition">
                                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">
                                                            <?= html_escape($cp['kode_cpl']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-3 font-semibold text-slate-600">
                                                        <?= html_escape($cp['kategori']) ?>
                                                    </td>
                                                    <td class="py-3 px-4 text-slate-700 leading-relaxed">
                                                        <?= html_escape($cp['deskripsi']) ?>
                                                    </td>
                                                    <td class="py-3 px-3 text-center font-bold font-mono text-sm <?= $cp['is_evaluasi'] ? 'text-rose-600' : 'text-emerald-600' ?>">
                                                        <?= number_format($cp['rata_rata'], 2) ?>
                                                    </td>
                                                    <td class="py-3 px-4 text-center">
                                                        <?php if ($cp['is_evaluasi']): ?>
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                                                <i class="fa fa-exclamation-circle"></i> Perlu Evaluasi (&lt; 70)
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                <i class="fa fa-check-circle"></i> Memenuhi Target (&ge; 70)
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
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php else: ?>
                    <!-- TAB 2: AUDIT KEPATUHAN RPS DOSEN (100%) -->
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 m-0">Tabel Rekapitulasi Kepatuhan RPS &amp; Bobot Asesmen Dosen</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Audit menyeluruh terhadap seluruh kelas penawaran mata kuliah yang aktif di sistem.</p>
                            </div>
                            <div>
                                <input type="text" id="auditSearchInput" onkeyup="filterAuditTable()" placeholder="Cari dosen, kode MK, atau status..." class="text-xs px-3.5 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-64">
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="w-full text-left text-xs border-collapse min-w-[880px]" id="auditTable">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                        <th class="py-3.5 px-3 w-10 text-center whitespace-nowrap">No</th>
                                        <th class="py-3.5 px-3 w-28 whitespace-nowrap">Kode MK</th>
                                        <th class="py-3.5 px-4 min-w-[200px]">Mata Kuliah &amp; Kelas</th>
                                        <th class="py-3.5 px-4 min-w-[180px]">Dosen Pengampu</th>
                                        <th class="py-3.5 px-3 whitespace-nowrap">Prodi</th>
                                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Tahun / Smt</th>
                                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Total Bobot (%)</th>
                                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Status Audit Kepatuhan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (empty($rps_audit['courses'])): ?>
                                    <tr>
                                        <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data penawaran mata kuliah yang terdeteksi.</td>
                                    </tr>
                                    <?php else: ?>
                                        <?php $ano = 1; foreach ($rps_audit['courses'] as $rc): ?>
                                        <tr class="hover:bg-slate-50/80 transition audit-row">
                                            <td class="py-3 px-3 text-center text-slate-500 font-medium"><?= $ano++ ?></td>
                                            <td class="py-3 px-3 font-mono font-bold text-slate-800 audit-code">
                                                <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                    <?= html_escape($rc->kode_mk) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 audit-name">
                                                <span class="font-bold text-slate-900 block"><?= html_escape($rc->nama_mk) ?></span>
                                                <span class="text-[10px] text-slate-400 block font-normal">Kelas <?= html_escape($rc->kelas) ?> &bull; <?= $rc->sks ?> SKS</span>
                                            </td>
                                            <td class="py-3 px-4 font-semibold text-slate-800 audit-dosen">
                                                <?= html_escape($rc->nama_dosen ?: 'Belum ditentukan') ?>
                                            </td>
                                            <td class="py-3 px-3 text-slate-600 font-medium audit-prodi">
                                                <?= html_escape($rc->prodi) ?>
                                            </td>
                                            <td class="py-3 px-3 text-center text-slate-600 font-mono text-[11px]">
                                                <?= html_escape($rc->tahun_akademik) ?>
                                            </td>
                                            <td class="py-3 px-3 text-center font-bold font-mono text-sm">
                                                <span class="<?= abs((float)$rc->total_bobot - 100.0) <= 0.01 ? 'text-emerald-600 font-black' : 'text-slate-700' ?>">
                                                    <?= number_format((float)$rc->total_bobot, 1) ?>%
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center audit-status">
                                                <?php if ($rc->status_code === 'LENGKAP'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <i class="fa fa-check-circle"></i> Lengkap (100%)
                                                    </span>
                                                <?php elseif ($rc->status_code === 'SEBAGIAN'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <i class="fa fa-exclamation-triangle"></i> Belum Lengkap (<?= number_format((float)$rc->total_bobot, 0) ?>%)
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                        <i class="fa fa-times-circle"></i> Belum Diatur (0%)
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
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    <?php if ($active_tab === 'monitoring'): ?>
    // Inisialisasi Grafik Perbandingan Prodi (Chart.js)
    document.addEventListener("DOMContentLoaded", function () {
        const prodiNames = <?= json_encode(array_map(function($p){ return $p['kode_prodi'] . ' (' . $p['nama_prodi'] . ')'; }, $cpl_monitoring['data'])) ?>;
        const prodiScores = <?= json_encode(array_map(function($p){ return (float)$p['rata_rata_prodi']; }, $cpl_monitoring['data'])) ?>;

        const ctx = document.getElementById('prodiComparisonChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: prodiNames,
                datasets: [
                    {
                        label: 'Rata-rata Capaian CPL',
                        data: prodiScores,
                        backgroundColor: prodiScores.map(score => score >= 70 ? 'rgba(16, 185, 129, 0.7)' : 'rgba(244, 63, 94, 0.7)'),
                        borderColor: prodiScores.map(score => score >= 70 ? '#059669' : '#e11d48'),
                        borderWidth: 1.5,
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: { size: 10 }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, weight: 'bold' }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(c) {
                                return 'Rata-rata CPL: ' + c.parsed.y.toFixed(2) + ' / 100';
                            }
                        }
                    }
                }
            }
        });
    });
    <?php endif; ?>

    function filterAuditTable() {
        const input = document.getElementById("auditSearchInput");
        if (!input) return;
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName("audit-row");

        for (let i = 0; i < rows.length; i++) {
            const text = rows[i].textContent.toLowerCase();
            if (text.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
