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
                            <p class="font-bold text-sm">Peringatan</p>
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
                    <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                        <i class="fa fa-book text-9xl"></i>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-blue-500/30 text-blue-200 text-xs font-semibold px-3 py-1 rounded-full border border-blue-400/20">
                                    Fase 2: Desain Pembelajaran & RPS
                                </span>
                                <span class="bg-emerald-500/30 text-emerald-200 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-400/20">
                                    Role: Dosen Pengampu
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">Rencana Pembelajaran Semester (RPS) OBE</h2>
                            <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                                Susun Capaian Pembelajaran Mata Kuliah (CPMK) berdasarkan CPL yang dititipkan Kaprodi, dan rancang Sub-CPMK dengan instrumen evaluasi total bobot 100%.
                            </p>
                        </div>
                        <div class="shrink-0">
                            <a href="<?= base_url('dosen/nilai') ?>" class="px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl text-xs font-semibold backdrop-blur-md transition flex items-center gap-2 border border-white/20 no-underline">
                                <i class="fa fa-calculator"></i> Menu Input Nilai Evaluasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold block">Total Mata Kuliah</span>
                            <span class="text-2xl font-bold text-slate-800 font-mono"><?= (int)$total_mk ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            <i class="fa fa-book"></i>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold block">RPS Dipublikasikan</span>
                            <span class="text-2xl font-bold text-emerald-600 font-mono"><?= (int)$published_count ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold block">RPS Draft (Perlu 100%)</span>
                            <span class="text-2xl font-bold text-amber-600 font-mono"><?= (int)$draft_count ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>

                <!-- Daftar Mata Kuliah & Status RPS -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 m-0">Daftar Mata Kuliah Kurikulum</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih mata kuliah untuk melengkapi dan mempublikasikan RPS.</p>
                        </div>
                        <div>
                            <input type="text" id="courseSearch" onkeyup="filterCourses()" placeholder="Cari kode atau nama mata kuliah..." class="text-xs px-3.5 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64">
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left border-collapse text-xs" id="rpsTable">
                            <thead>
                                <tr class="bg-slate-100/90 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                    <th class="py-3.5 px-4 w-12 text-center">No</th>
                                    <th class="py-3.5 px-4 w-28">Kode MK</th>
                                    <th class="py-3.5 px-4">Nama Mata Kuliah</th>
                                    <th class="py-3.5 px-4 w-32 text-center">CPL Dititipkan</th>
                                    <th class="py-3.5 px-4 w-28 text-center">Jml CPMK</th>
                                    <th class="py-3.5 px-4 w-36 text-center">Total Bobot Evaluasi</th>
                                    <th class="py-3.5 px-4 w-28 text-center">Status RPS</th>
                                    <th class="py-3.5 px-4 w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php if (empty($courses)): ?>
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400">
                                        Tidak ada mata kuliah aktif yang terdaftar.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $no = 1; foreach ($courses as $c): ?>
                                    <tr class="hover:bg-slate-50/80 transition rps-row">
                                        <td class="py-3.5 px-4 text-center font-medium text-slate-500"><?= $no++ ?></td>
                                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900 course-code">
                                            <?= html_escape($c->kode) ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-800 course-name">
                                            <?= html_escape($c->nama) ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <?php if ($c->cpl_count > 0): ?>
                                                <span class="inline-block px-2.5 py-0.5 bg-blue-100 text-blue-700 font-bold rounded-full text-xs">
                                                    <?= $c->cpl_count ?> CPL
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                            <?= $c->cpmk_count ?> CPMK
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-full <?= $c->is_valid_100 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' ?>">
                                                <?= number_format($c->total_bobot, 1) ?>% / 100%
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <?php if ($c->status === 'PUBLISHED'): ?>
                                                <span class="obe-badge bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    <i class="fa fa-check-circle mr-1"></i> PUBLISHED
                                                </span>
                                            <?php else: ?>
                                                <span class="obe-badge bg-amber-100 text-amber-700 border border-amber-200">
                                                    <i class="fa fa-clock-o mr-1"></i> DRAFT
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <a href="<?= base_url('dosen/rps/detail/' . $c->id) ?>" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5 no-underline">
                                                <i class="fa fa-pencil-square-o"></i> Kelola RPS
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function filterCourses() {
        const input = document.getElementById("courseSearch");
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName("rps-row");

        for (let i = 0; i < rows.length; i++) {
            const code = rows[i].querySelector(".course-code").textContent.toLowerCase();
            const name = rows[i].querySelector(".course-name").textContent.toLowerCase();
            if (code.indexOf(filter) > -1 || name.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
