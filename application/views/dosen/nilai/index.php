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

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Alert Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
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
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Perhatian</p>
                            <p class="text-xs"><?= $this->session->flashdata('error') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700 bg-transparent border-none cursor-pointer" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Header Banner -->
                <div class="bg-gradient-to-r from-slate-900 via-indigo-900 to-blue-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-white mb-5 sm:mb-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 opacity-5 sm:opacity-10 pointer-events-none">
                        <i class="fa fa-pencil-square-o text-7xl sm:text-9xl"></i>
                    </div>
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 sm:gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5">
                                <?php if (!empty($is_kaprodi)): ?>
                                <span class="bg-emerald-500/30 text-emerald-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-emerald-400/30 inline-flex items-center gap-1.5 shadow-sm whitespace-nowrap shrink-0">
                                    <i class="fa fa-shield"></i> Ketua Program Studi <?= html_escape($user_prodi ?? 'Teknik Informatika') ?>
                                </span>
                                <?php elseif (!empty($is_superadmin)): ?>
                                <span class="bg-amber-500/30 text-amber-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-amber-400/30 inline-flex items-center gap-1.5 shadow-sm whitespace-nowrap shrink-0">
                                    <i class="fa fa-shield"></i> Administrator Sistem
                                </span>
                                <?php else: ?>
                                <span class="bg-indigo-500/30 text-indigo-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-indigo-400/30 inline-flex items-center gap-1.5 shadow-sm whitespace-nowrap shrink-0">
                                    <i class="fa fa-graduation-cap"></i> Dosen Pengampu
                                </span>
                                <?php endif; ?>
                                <span class="bg-blue-500/30 text-blue-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-blue-400/20 whitespace-nowrap shrink-0">
                                    Sistem Penilaian Adaptif
                                </span>
                                <span class="bg-purple-500/30 text-purple-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-purple-400/20 whitespace-nowrap shrink-0">
                                    Outcome-Based Education
                                </span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white m-0 leading-tight">
                                <?= !empty($is_kaprodi) ? 'Monitoring &amp; Penilaian OBE Program Studi' : 'Kelas &amp; Penilaian Akademik' ?>
                            </h2>
                            <p class="text-blue-100 text-xs sm:text-sm mt-1 sm:mt-1.5 max-w-2xl leading-relaxed">
                                <?= !empty($is_kaprodi)
                                    ? 'Pantau instrumen asesmen CPMK, konfigurasi bobot 100%, dan status penilaian mahasiswa seluruh kelas pada Program Studi ' . html_escape($user_prodi ?? 'Teknik Informatika') . '.'
                                    : 'Kelola instrumen asesmen CPMK berbobot 100%, input nilai mahasiswa massal, dan pantau arsip nilai historis lampau.'
                                ?>
                            </p>
                        </div>

                        <?php if ((!empty($is_superadmin) || !empty($is_kaprodi)) && !empty($all_lecturers)): ?>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-2.5 sm:p-3 border border-white/20 shrink-0 self-stretch sm:self-start xl:self-auto w-full sm:w-auto">
                            <form method="GET" class="m-0 flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="text-xs text-white/90 whitespace-nowrap font-medium"><i class="fa fa-filter"></i> Filter Dosen:</span>
                                <select name="dosen_id" onchange="this.form.submit()" class="text-xs bg-slate-800 text-white border border-white/30 rounded-xl px-3 py-1.5 outline-none cursor-pointer w-full sm:w-auto max-w-full">
                                    <option value="0" <?= empty($dosen_id) ? 'selected' : '' ?>>
                                        <?= !empty($is_kaprodi) ? '-- Semua Kelas Prodi ' . html_escape($user_prodi ?? 'TI') . ' --' : '-- Semua Dosen Pengampu --' ?>
                                    </option>
                                    <?php foreach ($all_lecturers as $lec): ?>
                                    <option value="<?= $lec->id ?>" <?= ($dosen_id == $lec->id) ? 'selected' : '' ?>>
                                        <?= html_escape($lec->nama_lengkap) ?><?= (!empty($user_id) && (int)$lec->id === (int)$user_id) ? ' (Saya)' : '' ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- KPI Mini Cards -->
                <?php
                    $total_kelas = count($classes);
                    $kelas_obe_lengkap = 0;
                    $kelas_historis = 0;
                    $total_mhs_all = 0;
                    foreach ($classes as $cl) {
                        $total_mhs_all += (int)$cl->total_mahasiswa;
                        if (abs((float)$cl->total_bobot - 100.0) <= 0.01 && $cl->total_cpmk > 0) {
                            $kelas_obe_lengkap++;
                        }
                        if ($cl->total_nilai_khs > 0) {
                            $kelas_historis++;
                        }
                    }
                ?>
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
                    <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md hover:border-blue-200">
                        <div class="min-w-0 flex-1 mr-2">
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium block truncate" title="<?= !empty($is_kaprodi) && empty($dosen_id) ? 'Total Kelas Prodi' : 'Total Kelas Diampu' ?>">
                                <?= !empty($is_kaprodi) && empty($dosen_id) ? 'Total Kelas<span class="hidden sm:inline"> Prodi</span>' : 'Total Kelas<span class="hidden sm:inline"> Diampu</span>' ?>
                            </span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-800 leading-tight mt-0.5 block"><?= $total_kelas ?></span>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                            <i class="fa fa-book"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md hover:border-emerald-200">
                        <div class="min-w-0 flex-1 mr-2">
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium block truncate" title="Asesmen OBE 100%">
                                <span class="hidden sm:inline">Asesmen </span>OBE 100%
                            </span>
                            <span class="text-xl sm:text-2xl font-bold text-emerald-600 leading-tight mt-0.5 block"><?= $kelas_obe_lengkap ?></span>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md hover:border-amber-200">
                        <div class="min-w-0 flex-1 mr-2">
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium block truncate" title="Arsip Nilai Historis">
                                <span class="hidden sm:inline">Arsip </span>Nilai Historis
                            </span>
                            <span class="text-xl sm:text-2xl font-bold text-amber-600 leading-tight mt-0.5 block"><?= $kelas_historis ?></span>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                            <i class="fa fa-history"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md hover:border-indigo-200">
                        <div class="min-w-0 flex-1 mr-2">
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium block truncate" title="<?= !empty($is_kaprodi) && empty($dosen_id) ? 'Total Mahasiswa Prodi' : 'Total Mahasiswa' ?>">
                                <?= !empty($is_kaprodi) && empty($dosen_id) ? '<span class="hidden sm:inline">Total </span>Mahasiswa<span class="hidden sm:inline"> Prodi</span>' : '<span class="hidden sm:inline">Total </span>Mahasiswa' ?>
                            </span>
                            <span class="text-xl sm:text-2xl font-bold text-indigo-600 leading-tight mt-0.5 block"><?= $total_mhs_all ?></span>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-4 sm:p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 mb-4">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 m-0">
                                <?= !empty($is_kaprodi) && empty($dosen_id) ? 'Daftar Kelas Perkuliahan Prodi ' . html_escape($user_prodi ?? 'Teknik Informatika') : 'Daftar Kelas Mata Kuliah' ?>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                <?= !empty($is_kaprodi) && empty($dosen_id) ? 'Menampilkan seluruh kelas kurikulum aktif yang diselenggarakan oleh Program Studi ' . html_escape($user_prodi ?? 'Teknik Informatika') . '.' : 'Sistem otomatis mendeteksi apakah kelas menggunakan skema OBE aktif atau riwayat nilai lama.' ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <input type="text" id="classSearchInput" onkeyup="filterClassTable()" placeholder="Cari nama atau kode mata kuliah..." class="text-xs px-3.5 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64">
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left border-collapse text-xs min-w-[980px]" id="classTable">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                    <th class="py-3.5 px-4 w-12 text-center whitespace-nowrap">No</th>
                                    <th class="py-3.5 px-4 w-28 whitespace-nowrap">Kode MK</th>
                                    <th class="py-3.5 px-4 min-w-[240px]">Mata Kuliah & Program Studi</th>
                                    <th class="py-3.5 px-4 text-center whitespace-nowrap w-24">Kelas</th>
                                    <th class="py-3.5 px-3 text-center whitespace-nowrap w-20">SKS</th>
                                    <th class="py-3.5 px-4 text-center whitespace-nowrap w-28">Tahun / Smt</th>
                                    <th class="py-3.5 px-4 text-center whitespace-nowrap min-w-[190px]">Status Konfigurasi Asesmen</th>
                                    <th class="py-3.5 px-4 text-center whitespace-nowrap w-24">Mahasiswa</th>
                                    <th class="py-3.5 px-4 text-center w-52 whitespace-nowrap">Aksi Penilaian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php if (empty($classes)): ?>
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-slate-400">
                                        <i class="fa fa-folder-open-o text-4xl mb-2 block text-slate-300"></i>
                                        Tidak ada kelas perkuliahan yang ditemukan.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $cno = 1; foreach ($classes as $cl): ?>
                                    <?php
                                        $bobot = (float)$cl->total_bobot;
                                        $is_obe_ready = (abs($bobot - 100.0) <= 0.01 && $cl->total_cpmk > 0);
                                        $is_partial = ($bobot > 0 && !$is_obe_ready);
                                        $is_historical = ($cl->total_komponen == 0 && $cl->total_nilai_khs > 0);
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition class-row">
                                        <td class="py-3.5 px-4 text-center font-medium text-slate-500 whitespace-nowrap"><?= $cno++ ?></td>
                                        <td class="py-3.5 px-4 font-mono font-bold text-slate-800 class-code whitespace-nowrap">
                                            <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 inline-block whitespace-nowrap font-mono">
                                                <?= html_escape($cl->kode_mk) ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 class-name min-w-[240px]">
                                            <span class="font-bold text-slate-900 block"><?= html_escape($cl->nama_mk) ?></span>
                                            <span class="text-[11px] text-slate-500 block mt-0.5">
                                                Prodi <?= html_escape($cl->prodi) ?> &bull; Semester <?= $cl->semester ?>
                                                <?php if (!empty($cl->nama_dosen)): ?>
                                                    &bull; <span class="text-indigo-600 font-semibold"><i class="fa fa-user-circle-o"></i> <?= html_escape($cl->nama_dosen) ?></span>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-blue-700 whitespace-nowrap">
                                            <span class="bg-blue-50 px-3 py-1 rounded-md border border-blue-200 inline-flex items-center justify-center whitespace-nowrap font-mono text-xs font-bold">
                                                <?= html_escape($cl->kelas) ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-3 text-center font-semibold text-slate-700 whitespace-nowrap"><?= $cl->sks ?> SKS</td>
                                        <td class="py-3.5 px-4 text-center text-slate-600 font-medium whitespace-nowrap">
                                            <?= html_escape($cl->tahun_akademik) ?>
                                            <span class="block text-[10px] text-slate-400 whitespace-nowrap"><?= html_escape($cl->semester_akademik) ?></span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <?php if ($is_obe_ready): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                    <i class="fa fa-check-circle"></i> OBE Siap (100%)
                                                </span>
                                                <?php if ($cl->total_nilai_khs > 0): ?>
                                                    <span class="block text-[10px] text-blue-600 font-semibold mt-1 whitespace-nowrap"><i class="fa fa-archive"></i> <?= $cl->total_nilai_khs ?> arsip nilai KHS</span>
                                                <?php else: ?>
                                                    <span class="block text-[10px] text-slate-400 mt-1 whitespace-nowrap"><?= $cl->total_cpmk ?> CPMK &bull; <?= $cl->total_komponen ?> Asesmen</span>
                                                <?php endif; ?>
                                            <?php elseif ($is_partial): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                                    <i class="fa fa-exclamation-circle"></i> Bobot: <?= number_format($bobot, 1) ?>% (Belum 100%)
                                                </span>
                                                <span class="block text-[10px] text-amber-600 mt-1 whitespace-nowrap">Perlu pelengkapan bobot</span>
                                            <?php elseif ($cl->total_nilai_khs > 0 || $is_historical): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                                    <i class="fa fa-history"></i> Nilai Historis (KHS)
                                                </span>
                                                <span class="block text-[10px] text-slate-400 mt-1 whitespace-nowrap"><?= $cl->total_nilai_khs ?> nilai tersimpan di KHS</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap">
                                                    <i class="fa fa-clock-o"></i> Belum Dikonfigurasi
                                                </span>
                                                <span class="block text-[10px] text-slate-400 mt-1 whitespace-nowrap">0% Komponen Asesmen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <span class="font-bold text-slate-800"><?= $cl->total_mahasiswa ?></span>
                                            <span class="text-[10px] text-slate-400 block whitespace-nowrap">KRS Terdaftar</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2 flex-nowrap">
                                                <a href="<?= base_url('dosen/nilai/setup_cpmk/' . $cl->id) ?>" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-xs transition flex items-center gap-1 whitespace-nowrap shrink-0" title="Konfigurasi CPMK dan Distribusi Bobot Asesmen">
                                                    <i class="fa fa-sliders"></i> Setup CPMK
                                                </a>
                                                <a href="<?= base_url('dosen/nilai/matriks/' . $cl->id) ?>" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm flex items-center gap-1.5 whitespace-nowrap shrink-0" title="Input dan Lihat Matriks Nilai Mahasiswa">
                                                    <i class="fa fa-table"></i> Nilai
                                                </a>
                                            </div>
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
    function filterClassTable() {
        const input = document.getElementById("classSearchInput");
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName("class-row");

        for (let i = 0; i < rows.length; i++) {
            const code = rows[i].querySelector(".class-code").textContent.toLowerCase();
            const name = rows[i].querySelector(".class-name").textContent.toLowerCase();
            if (code.indexOf(filter) > -1 || name.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
