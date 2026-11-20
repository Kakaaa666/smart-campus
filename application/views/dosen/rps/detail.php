<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: { preflight: false },
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                    }
                }
            }
        }
    }
</script>

<style>
    .obe-content-wrapper {
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .obe-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }
    .progress-bar-glow {
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
    }
</style>

<div class="pcoded-content obe-content-wrapper">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Alert Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                            <i class="fa fa-check"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Berhasil!</p>
                            <p class="text-xs"><?= $this->session->flashdata('success') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-emerald-500 hover:text-emerald-700 bg-transparent border-none cursor-pointer" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Validasi Publikasi Gagal</p>
                            <p class="text-xs"><?= $this->session->flashdata('error') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700 bg-transparent border-none cursor-pointer" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Top Header Card -->
                <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-800 rounded-3xl p-6 text-white mb-6 shadow-lg relative overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?= base_url('dosen/rps') ?>" class="text-blue-200 hover:text-white text-xs font-semibold flex items-center gap-1 no-underline">
                                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar MK RPS
                                </a>
                                <span class="text-white/40">•</span>
                                <span class="bg-blue-500/30 text-blue-200 text-xs font-semibold px-3 py-0.5 rounded-full border border-blue-400/20">
                                    Fase 2: Desain RPS
                                </span>
                                <span class="obe-badge <?= ($status_publikasi === 'PUBLISHED') ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' ?>">
                                    <?= $status_publikasi === 'PUBLISHED' ? 'PUBLISHED' : 'DRAFT' ?>
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">
                                <?= html_escape($mk->kode) ?> - <?= html_escape($mk->nama) ?>
                            </h2>
                            <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                                Susun rincian CPMK dan instrumen Sub-CPMK (pertemuan 1–16). Total bobot penilaian wajib tepat 100% untuk publikasi.
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <!-- Tombol Publikasikan RPS (Wajib 100%) -->
                            <form action="<?= base_url('dosen/rps/publish/' . $mk->id) ?>" method="POST" class="m-0" onsubmit="return confirmPublish(<?= $is_valid_100 ? 'true' : 'false' ?>, <?= (float)$total_bobot_nilai ?>)">
                                <button type="submit" id="btnPublishRps" <?= !$is_valid_100 ? 'disabled' : '' ?> class="px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 border-none <?= $is_valid_100 ? 'bg-emerald-500 hover:bg-emerald-600 text-white cursor-pointer hover:shadow-lg' : 'bg-slate-500/60 text-slate-300 cursor-not-allowed opacity-60' ?>" title="<?= !$is_valid_100 ? 'Total bobot saat ini ' . number_format($total_bobot_nilai, 1) . '%. Harus tepat 100% untuk publikasi.' : 'Publikasikan RPS ke Mahasiswa dan Sistem Akademik' ?>">
                                    <i class="fa fa-paper-plane"></i> Publikasikan RPS
                                </button>
                            </form>
                            <button type="button" onclick="openAddCpmkModal()" class="px-4 py-2.5 bg-white text-indigo-700 hover:bg-indigo-50 rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5 border-none cursor-pointer">
                                <i class="fa fa-plus-circle"></i> Tambah CPMK
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Live Bobot Indicator & Progress Bar -->
                <?php
                    $pct = min(100.0, max(0.0, (float)$total_bobot_nilai));
                    $diff = round(100.00 - (float)$total_bobot_nilai, 2);

                    if ($is_valid_100) {
                        $barColor = 'bg-emerald-500 progress-bar-glow';
                        $containerBg = 'bg-emerald-50/80 border-emerald-200 text-emerald-950';
                        $statusBadgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                        $statusText = 'Bobot Tepat 100.00% (Valid & Siap Dipublikasikan)';
                    } elseif ($total_bobot_nilai < 100.00) {
                        $barColor = $total_bobot_nilai > 70 ? 'bg-amber-500' : 'bg-rose-500';
                        $containerBg = 'bg-amber-50/80 border-amber-200 text-amber-950';
                        $statusBadgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
                        $statusText = 'Total bobot saat ini ' . number_format($total_bobot_nilai, 1) . '%. Kurang ' . number_format(abs($diff), 1) . '% lagi untuk publikasi.';
                    } else {
                        $barColor = 'bg-rose-600';
                        $containerBg = 'bg-rose-50/80 border-rose-200 text-rose-950';
                        $statusBadgeColor = 'bg-rose-100 text-rose-800 border-rose-200';
                        $statusText = 'Total bobot saat ini ' . number_format($total_bobot_nilai, 1) . '%. Kelebihan ' . number_format(abs($diff), 1) . '%. Harus tepat 100.00%.';
                    }
                ?>
                <div class="rounded-3xl p-5 mb-6 border shadow-sm transition <?= $containerBg ?>">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-lg <?= $is_valid_100 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                <i class="fa <?= $is_valid_100 ? 'fa-check' : 'fa-info-circle' ?>"></i>
                            </div>
                            <div>
                                <span class="text-xs font-semibold block text-slate-600">Status Validasi Akumulasi Bobot Sub-CPMK:</span>
                                <span class="text-sm font-bold block mt-0.5">
                                    <?= $statusText ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 self-end md:self-center">
                            <div class="text-right">
                                <span class="text-[11px] font-semibold text-slate-500 block">Akumulasi Seluruh Sub-CPMK:</span>
                                <span class="text-2xl font-mono font-bold <?= $is_valid_100 ? 'text-emerald-700' : 'text-slate-800' ?>">
                                    <?= number_format($total_bobot_nilai, 1) ?>% <span class="text-xs text-slate-400 font-normal">/ 100.0%</span>
                                </span>
                            </div>
                            <span class="obe-badge border <?= $statusBadgeColor ?>">
                                <?= $is_valid_100 ? '100% Sempurna' : ($total_bobot_nilai < 100 ? 'Kurang ' . number_format($diff, 1) . '%' : 'Kelebihan ' . number_format(abs($diff), 1) . '%') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div class="w-full bg-slate-200/90 rounded-full h-3 overflow-hidden shadow-inner">
                        <div class="h-full rounded-full transition-all duration-500 <?= $barColor ?>" style="width: <?= min(100.0, $pct) ?>%;"></div>
                    </div>
                    <?php if (!$is_valid_100): ?>
                    <p class="text-[11px] text-slate-500 mt-2 mb-0 italic">
                        <i class="fa fa-lock mr-1 text-slate-400"></i> Tombol <strong>"Publikasikan RPS"</strong> dinonaktifkan otomatis sampai total bobot seluruh Sub-CPMK tepat berjumlah 100.00%.
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Bagian 1: CPL yang Terhubung ke MK (Otomatis ditarik dari cpl_mk Fase 1) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 m-0">1. CPL yang Terhubung ke Mata Kuliah Ini</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Otomatis ditarik dari tabel <code class="text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded">cpl_mk</code> yang dirancang Kaprodi pada Fase 1.</p>
                        </div>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200 self-start sm:self-auto">
                            <?= count($cpls) ?> CPL Ditugaskan
                        </span>
                    </div>

                    <?php if (empty($cpls)): ?>
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs flex items-start gap-2.5">
                        <i class="fa fa-exclamation-circle text-base text-amber-600 mt-0.5"></i>
                        <div>
                            <strong>Belum Ada CPL Terpetakan:</strong> Mata kuliah ini belum memiliki pemetaan CPL di tabel matriks CPL–MK.
                            Silakan pastikan Kaprodi telah mengaitkan CPL untuk mata kuliah ini di menu Matriks CPL–MK (Fase 1).
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($cpls as $c): ?>
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-indigo-300 transition shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 rounded font-mono font-bold text-xs">
                                        <?= html_escape($c->kode_cpl) ?>
                                    </span>
                                    <span class="text-[11px] font-bold text-indigo-600 font-mono bg-white px-2 py-0.5 rounded-full border border-slate-200">
                                        Bobot MK: <?= number_format($c->bobot_cpl, 1) ?>%
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-500 block mb-1"><?= html_escape($c->kategori) ?></span>
                                <p class="text-xs text-slate-700 leading-relaxed m-0"><?= html_escape($c->deskripsi) ?></p>
                            </div>
                            <div class="mt-2.5 pt-2 border-t border-slate-200/60 text-[10px] text-slate-400 font-mono">
                                ID CPL-MK: #<?= (int)$c->cpl_mk_id ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Bagian 2: Desain CPMK & Sub-CPMK (CRUD) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 m-0">2. Capaian Pembelajaran Mata Kuliah (CPMK) & Rincian Sub-CPMK</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Setiap CPMK wajib memilih salah satu CPL terhubung, kemudian diuraikan ke dalam Sub-CPMK dengan instrumen asesmen.</p>
                        </div>
                        <button type="button" onclick="openAddCpmkModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm flex items-center gap-1.5 transition border-none cursor-pointer self-start md:self-auto">
                            <i class="fa fa-plus"></i> Tambah CPMK Baru
                        </button>
                    </div>

                    <?php if (empty($cpmks)): ?>
                    <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <i class="fa fa-folder-open-o text-4xl mb-2 block text-slate-300"></i>
                        Belum ada CPMK yang dibuat untuk mata kuliah ini.<br>
                        <button type="button" onclick="openAddCpmkModal()" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold border-none cursor-pointer">
                            + Tambah CPMK Pertama
                        </button>
                    </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($cpmks as $cpmk): ?>
                            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                                <!-- CPMK Header Card -->
                                <div class="bg-slate-50/90 px-5 py-3.5 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg font-mono font-bold text-xs shrink-0">
                                            <?= html_escape($cpmk->kode_cpmk) ?>
                                        </span>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-bold text-slate-800">
                                                    Mendukung CPL: <span class="font-mono text-blue-700"><?= html_escape($cpmk->kode_cpl ?: 'CPL Ditugaskan') ?></span>
                                                    <?php if (!empty($cpmk->kategori)): ?>
                                                    <span class="text-slate-400 font-normal">(<?= html_escape($cpmk->kategori) ?>)</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-700 m-0 leading-relaxed"><?= html_escape($cpmk->deskripsi) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                        <button type="button" onclick="openAddSubModal(<?= $cpmk->id ?>, '<?= html_escape(addslashes($cpmk->kode_cpmk)) ?>')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1 border-none cursor-pointer">
                                            <i class="fa fa-plus"></i> Sub-CPMK
                                        </button>
                                        <button type="button" onclick="editCpmk(<?= html_escape(json_encode($cpmk)) ?>)" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition border-none cursor-pointer" title="Edit CPMK">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('dosen/rps/delete_cpmk/' . $cpmk->id . '/' . $mk->id) ?>" data-sc-confirm="Hapus CPMK <?= html_escape($cpmk->kode_cpmk) ?> beserta seluruh Sub-CPMK turunannya?" data-confirm-title="Hapus CPMK" data-confirm-accept="Ya, Hapus" data-confirm-type="danger" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition" title="Hapus CPMK">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Sub-CPMK Table inside CPMK -->
                                <div class="p-4 overflow-x-auto">
                                    <?php $subs = $sub_cpmks[$cpmk->id] ?? []; ?>
                                    <?php if (empty($subs)): ?>
                                    <div class="p-4 text-center text-slate-400 text-xs italic bg-slate-50/50 rounded-xl">
                                        Belum ada Sub-CPMK untuk <?= html_escape($cpmk->kode_cpmk) ?>. Klik "+ Sub-CPMK" di atas untuk menambahkan instrumen evaluasi.
                                    </div>
                                    <?php else: ?>
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-slate-100/70 text-slate-700 font-bold border-b border-slate-200 text-[11px] uppercase tracking-wider">
                                                <th class="py-2.5 px-3 w-32">Kode Sub-CPMK</th>
                                                <th class="py-2.5 px-3">Deskripsi Kemampuan Akhir Tiap Tahap</th>
                                                <th class="py-2.5 px-3 w-36 text-center">Pertemuan Ke</th>
                                                <th class="py-2.5 px-3 w-32 text-center">Instrumen Evaluasi</th>
                                                <th class="py-2.5 px-3 w-28 text-center">Bobot Nilai</th>
                                                <th class="py-2.5 px-2 w-20 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php foreach ($subs as $sub): ?>
                                            <tr class="hover:bg-slate-50/60 transition">
                                                <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">
                                                    <?= html_escape($sub->kode_sub) ?>
                                                </td>
                                                <td class="py-2.5 px-3 text-slate-700 leading-relaxed">
                                                    <?= html_escape($sub->deskripsi) ?>
                                                </td>
                                                <td class="py-2.5 px-3 text-center">
                                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full font-semibold text-[11px]">
                                                        <?= html_escape($sub->pertemuan_ke) ?>
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-3 text-center">
                                                    <?php
                                                        $badgeEv = 'bg-blue-100 text-blue-700';
                                                        if ($sub->instrumen_evaluasi === 'UTS' || $sub->instrumen_evaluasi === 'UAS') $badgeEv = 'bg-purple-100 text-purple-700 font-bold';
                                                        elseif ($sub->instrumen_evaluasi === 'Proyek') $badgeEv = 'bg-amber-100 text-amber-700 font-bold';
                                                        elseif ($sub->instrumen_evaluasi === 'Kuis') $badgeEv = 'bg-emerald-100 text-emerald-700';
                                                    ?>
                                                    <span class="px-2.5 py-0.5 rounded-full font-semibold text-[11px] <?= $badgeEv ?>">
                                                        <?= html_escape($sub->instrumen_evaluasi) ?>
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-900">
                                                    <?= number_format($sub->bobot_nilai, 1) ?>%
                                                </td>
                                                <td class="py-2.5 px-2 text-center">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        <button type="button" onclick="editSub(<?= html_escape(json_encode($sub)) ?>, '<?= html_escape(addslashes($cpmk->kode_cpmk)) ?>')" class="text-slate-500 hover:text-blue-600 bg-transparent border-none cursor-pointer" title="Edit Sub-CPMK">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <a href="<?= base_url('dosen/rps/delete_sub_cpmk/' . $sub->id . '/' . $mk->id) ?>" data-sc-confirm="Hapus Sub-CPMK <?= html_escape($sub->kode_sub) ?>?" data-confirm-title="Hapus Sub-CPMK" data-confirm-accept="Ya, Hapus" data-confirm-type="danger" class="text-rose-500 hover:text-rose-700 ml-1" title="Hapus">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Bagian 3: Dedicated Tabel Pemetaan 16 Pertemuan Pembelajaran -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-800 m-0">3. Matriks Pemetaan Rencana 16 Pertemuan Pembelajaran</h3>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Syllabus RPS</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Pemetaan komprehensif rencana materi, CPMK, Sub-CPMK, instrumen evaluasi, dan bobot untuk setiap pertemuan 1 s/d 16.</p>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-xl">
                            Semester: 16 Pertemuan (Termasuk UTS & UAS)
                        </span>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-100/90 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                    <th class="py-3 px-3 w-32 text-center">Pertemuan</th>
                                    <th class="py-3 px-3 w-36">CPMK / Sub-CPMK</th>
                                    <th class="py-3 px-4">Deskripsi Kemampuan Akhir / Indikator</th>
                                    <th class="py-3 px-3 w-36 text-center">Instrumen Evaluasi</th>
                                    <th class="py-3 px-3 w-28 text-center">Bobot Nilai</th>
                                    <th class="py-3 px-3 w-28 text-center">Status Rencana</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php for ($p = 1; $p <= 16; $p++): ?>
                                    <?php
                                        $row = $matriks_16[$p] ?? null;
                                        $has_items = !empty($row['items']);
                                        $is_uts = ($p === 8);
                                        $is_uas = ($p === 16);

                                        $row_bg = '';
                                        if ($is_uts || $is_uas) {
                                            $row_bg = 'bg-purple-50/50';
                                        } elseif (!$has_items) {
                                            $row_bg = 'bg-slate-50/30';
                                        }
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition <?= $row_bg ?>">
                                        <!-- Pertemuan Ke -->
                                        <td class="py-3 px-3 text-center">
                                            <?php if ($is_uts): ?>
                                                <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg font-mono text-xs">
                                                    Ke-8 (UTS)
                                                </span>
                                            <?php elseif ($is_uas): ?>
                                                <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg font-mono text-xs">
                                                    Ke-16 (UAS)
                                                </span>
                                            <?php else: ?>
                                                <span class="font-mono font-bold text-slate-700 text-xs">
                                                    Pertemuan <?= $p ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- CPMK / Sub-CPMK -->
                                        <td class="py-3 px-3">
                                            <?php if ($has_items): ?>
                                                <div class="space-y-1">
                                                    <?php foreach ($row['items'] as $it): ?>
                                                        <div class="font-mono font-bold text-indigo-700 text-[11px]">
                                                            <?= html_escape($it->kode_sub) ?>
                                                            <span class="text-slate-400 font-normal">(<?= html_escape($it->kode_cpmk) ?>)</span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic text-[11px]">Belum dipetakan</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Deskripsi Kemampuan Akhir -->
                                        <td class="py-3 px-4">
                                            <?php if ($has_items): ?>
                                                <div class="space-y-1.5">
                                                    <?php foreach ($row['items'] as $it): ?>
                                                        <p class="text-slate-700 text-xs leading-relaxed m-0">
                                                            <?= html_escape($it->deskripsi) ?>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic text-xs">
                                                    <?= $is_uts ? 'Evaluasi Tengah Semester (Ujian Tertulis / Presentasi Tengah Semester)' : ($is_uas ? 'Evaluasi Akhir Semester (Ujian Akhir / Final Project Capstone)' : 'Materi pembelajaran dan penguatan kompetensi.') ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Instrumen Evaluasi -->
                                        <td class="py-3 px-3 text-center">
                                            <?php if ($has_items): ?>
                                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg font-semibold text-[11px]">
                                                    <?= html_escape($row['instrumen']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400 text-xs">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Bobot Nilai -->
                                        <td class="py-3 px-3 text-center font-mono font-bold">
                                            <?php if ($has_items && $row['bobot'] > 0): ?>
                                                <span class="text-slate-900 bg-slate-100 px-2 py-0.5 rounded">
                                                    <?= number_format($row['bobot'], 1) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400">0%</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Status Rencana -->
                                        <td class="py-3 px-3 text-center">
                                            <?php if ($has_items): ?>
                                                <span class="inline-flex items-center text-emerald-600 font-bold text-[11px] gap-1">
                                                    <i class="fa fa-check-circle"></i> Teragenda
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center text-slate-400 text-[11px] gap-1">
                                                    <i class="fa fa-minus-circle"></i> Teori / Non-Tes
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit CPMK -->
<div id="cpmkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden animate-fade-in p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-4 text-white flex items-center justify-between">
            <h3 id="cpmkModalTitle" class="text-base font-bold text-white m-0">Tambah CPMK Baru</h3>
            <button type="button" onclick="closeCpmkModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>
        <form action="<?= base_url('dosen/rps/save_cpmk') ?>" method="POST" class="p-6 m-0">
            <input type="hidden" name="mata_kuliah_id" value="<?= $mk->id ?>">
            <input type="hidden" name="id" id="cpmkIdInput" value="">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Terikat ke CPL Dititipkan <span class="text-rose-500">*</span>
                </label>
                <select name="cpl_mk_id" id="cpmkCplMkInput" required class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">-- Pilih Salah Satu CPL yang Terhubung --</option>
                    <?php foreach ($cpls as $c): ?>
                    <option value="<?= $c->cpl_mk_id ?>">
                        <?= html_escape($c->kode_cpl) ?> - <?= html_escape($c->kategori) ?> (Bobot CPL: <?= number_format($c->bobot_cpl, 0) ?>%)
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[11px] text-slate-400 mt-1 block">Wajib memilih CPL yang terdaftar pada matriks kurikulum MK ini.</span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode CPMK <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_cpmk" id="cpmkKodeInput" required placeholder="Contoh: CPMK-1, CPMK-2" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none uppercase font-mono">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Capaian Pembelajaran MK <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="cpmkDeskripsiInput" rows="3" required placeholder="Rumusan kemampuan mahasiswa setelah menyelesaikan mata kuliah ini..." class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCpmkModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition border-none cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition border-none cursor-pointer">
                    Simpan CPMK
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah / Edit Sub-CPMK -->
<div id="subCpmkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden animate-fade-in p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
            <div>
                <h3 id="subModalTitle" class="text-base font-bold text-white m-0">Tambah Sub-CPMK</h3>
                <span id="subModalCpmkLabel" class="text-xs text-emerald-100 block mt-0.5 font-mono"></span>
            </div>
            <button type="button" onclick="closeSubModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>
        <form action="<?= base_url('dosen/rps/save_sub_cpmk') ?>" method="POST" class="p-6 m-0">
            <input type="hidden" name="mata_kuliah_id" value="<?= $mk->id ?>">
            <input type="hidden" name="cpmk_id" id="modalSubCpmkId" value="">
            <input type="hidden" name="id" id="subIdInput" value="">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Sub-CPMK <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_sub" id="subKodeInput" required placeholder="Contoh: Sub-CPMK-1.1" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-mono">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kemampuan Akhir Tiap Tahap <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="subDeskripsiInput" rows="2" required placeholder="Tuliskan indikator kompetensi pada tahap/minggu ini..." class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pertemuan Ke (1–16) <span class="text-rose-500">*</span></label>
                    <select name="pertemuan_ke" id="subPertemuanInput" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                        <option value="1">Pertemuan 1</option>
                        <option value="2">Pertemuan 2</option>
                        <option value="3">Pertemuan 3</option>
                        <option value="4">Pertemuan 4</option>
                        <option value="5">Pertemuan 5</option>
                        <option value="6">Pertemuan 6</option>
                        <option value="7">Pertemuan 7</option>
                        <option value="8">Pertemuan 8 (UTS)</option>
                        <option value="9">Pertemuan 9</option>
                        <option value="10">Pertemuan 10</option>
                        <option value="11">Pertemuan 11</option>
                        <option value="12">Pertemuan 12</option>
                        <option value="13">Pertemuan 13</option>
                        <option value="14">Pertemuan 14</option>
                        <option value="15">Pertemuan 15</option>
                        <option value="16">Pertemuan 16 (UAS)</option>
                        <option value="1-2">Pertemuan 1-2</option>
                        <option value="3-4">Pertemuan 3-4</option>
                        <option value="5-7">Pertemuan 5-7</option>
                        <option value="9-11">Pertemuan 9-11</option>
                        <option value="12-15">Pertemuan 12-15</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instrumen Evaluasi <span class="text-rose-500">*</span></label>
                    <select name="instrumen_evaluasi" id="subInstrumenInput" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                        <option value="Tugas">Tugas</option>
                        <option value="Kuis">Kuis</option>
                        <option value="UTS">UTS</option>
                        <option value="Proyek">Proyek</option>
                        <option value="UAS">UAS</option>
                        <option value="Praktikum">Praktikum</option>
                        <option value="Partisipasi">Partisipasi</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1">Bobot Nilai (%) <span class="text-rose-500">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="number" step="0.5" min="0.5" max="100" name="bobot_nilai" id="subBobotInput" required placeholder="Contoh: 15" class="w-full text-xs font-bold px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <span class="text-sm font-bold text-slate-600">%</span>
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">Total seluruh bobot Sub-CPMK di mata kuliah ini harus tepat berjumlah 100%.</span>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeSubModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition border-none cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition border-none cursor-pointer">
                    Simpan Sub-CPMK
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddCpmkModal() {
        document.getElementById('cpmkModalTitle').innerText = 'Tambah CPMK Baru';
        document.getElementById('cpmkIdInput').value = '';
        document.getElementById('cpmkKodeInput').value = '';
        document.getElementById('cpmkDeskripsiInput').value = '';
        document.getElementById('cpmkModal').classList.remove('hidden');
    }

    function editCpmk(cpmk) {
        document.getElementById('cpmkModalTitle').innerText = 'Edit CPMK (' + cpmk.kode_cpmk + ')';
        document.getElementById('cpmkIdInput').value = cpmk.id;
        document.getElementById('cpmkCplMkInput').value = cpmk.cpl_mk_id;
        document.getElementById('cpmkKodeInput').value = cpmk.kode_cpmk;
        document.getElementById('cpmkDeskripsiInput').value = cpmk.deskripsi;
        document.getElementById('cpmkModal').classList.remove('hidden');
    }

    function closeCpmkModal() {
        document.getElementById('cpmkModal').classList.add('hidden');
    }

    function openAddSubModal(cpmkId, kodeCpmk) {
        document.getElementById('subModalTitle').innerText = 'Tambah Sub-CPMK';
        document.getElementById('modalSubCpmkId').value = cpmkId;
        document.getElementById('subIdInput').value = '';
        document.getElementById('subKodeInput').value = '';
        document.getElementById('subDeskripsiInput').value = '';
        document.getElementById('subBobotInput').value = '';
        document.getElementById('subModalCpmkLabel').innerText = 'Induk: ' + kodeCpmk;
        document.getElementById('subCpmkModal').classList.remove('hidden');
    }

    function editSub(sub, kodeCpmk) {
        document.getElementById('subModalTitle').innerText = 'Edit Sub-CPMK';
        document.getElementById('modalSubCpmkId').value = sub.cpmk_id;
        document.getElementById('subIdInput').value = sub.id;
        document.getElementById('subKodeInput').value = sub.kode_sub;
        document.getElementById('subDeskripsiInput').value = sub.deskripsi;
        document.getElementById('subPertemuanInput').value = sub.pertemuan_ke;
        document.getElementById('subInstrumenInput').value = sub.instrumen_evaluasi;
        document.getElementById('subBobotInput').value = sub.bobot_nilai;
        document.getElementById('subModalCpmkLabel').innerText = 'Induk: ' + (kodeCpmk || sub.kode_cpmk || 'CPMK');
        document.getElementById('subCpmkModal').classList.remove('hidden');
    }

    function closeSubModal() {
        document.getElementById('subCpmkModal').classList.add('hidden');
    }

    function confirmPublish(isValid, totalBobot) {
        if (!isValid) {
            alert("RPS tidak dapat dipublikasikan! Total bobot saat ini " + totalBobot.toFixed(1) + "%. Harus tepat 100% untuk publikasi.");
            return false;
        }
        return confirm("Apakah Anda yakin ingin mempublikasikan RPS ini? RPS akan berstatus PUBLISHED dan dapat diakses mahasiswa.");
    }
</script>
