<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Tailwind CSS CDN dengan konfigurasi non-preflight agar selaras dengan layout Smart Campus -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false,
        },
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
    /* Styling pelengkap agar tampilan modern tetap mulus di dalam container Bootstrap */
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
    .obe-matrix-table th, .obe-matrix-table td {
        vertical-align: middle !important;
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
                    <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
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
                            <p class="font-bold text-sm">Perhatian</p>
                            <p class="text-xs"><?= $this->session->flashdata('error') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Top Header Card -->
                <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-800 rounded-3xl p-6 text-white mb-6 shadow-lg relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                        <i class="fa fa-graduation-cap text-9xl"></i>
                    </div>
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                <span class="bg-blue-500/30 text-blue-200 text-xs font-semibold px-3 py-1 rounded-full border border-blue-400/20 whitespace-nowrap shrink-0">
                                    Modul OBE & Kurikulum Kampus
                                </span>
                                <span class="bg-emerald-500/30 text-emerald-200 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-400/20 whitespace-nowrap shrink-0">
                                    Role: Ketua Program Studi
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">Master CPL & Matriks Kurikulum</h2>
                            <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                                Standar Capaian Pembelajaran Lulusan (CPL) untuk Program Studi <strong class="text-white"><?= html_escape($prodi ? $prodi->nama_prodi : 'Teknik Informatika') ?></strong> (<?= html_escape($prodi ? $prodi->fakultas : '') ?>).
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap shrink-0 self-start xl:self-auto">
                            <?php if ((int)$this->session->userdata('role') === 1 && !empty($all_prodi)): ?>
                            <form method="GET" class="inline-block m-0">
                                <select name="prodi_id" onchange="this.form.submit()" class="bg-white/20 text-white text-xs rounded-xl px-3 py-2 border border-white/30 backdrop-blur-md outline-none focus:ring-2 focus:ring-white/50 cursor-pointer">
                                    <?php foreach ($all_prodi as $ap): ?>
                                    <option value="<?= $ap->id ?>" class="text-slate-800" <?= ($prodi_id == $ap->id) ? 'selected' : '' ?>>
                                        Prodi: <?= html_escape($ap->nama_prodi) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                            <?php endif; ?>

                            <button type="button" onclick="openAddCplModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-xs shadow-md flex items-center gap-2 transition duration-200 transform hover:-translate-y-0.5 border-none cursor-pointer whitespace-nowrap">
                                <i class="fa fa-plus-circle"></i> Tambah CPL Baru
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPI Statistik CPL -->
                <?php
                    $count_sikap = 0;
                    $count_pengetahuan = 0;
                    $count_ku = 0;
                    $count_kk = 0;
                    foreach ($cpls as $c) {
                        if ($c->kategori === 'Sikap') $count_sikap++;
                        elseif ($c->kategori === 'Pengetahuan') $count_pengetahuan++;
                        elseif ($c->kategori === 'Keterampilan Umum') $count_ku++;
                        elseif ($c->kategori === 'Keterampilan Khusus') $count_kk++;
                    }
                    $total_cpl = count($cpls);
                ?>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Total CPL</span>
                            <span class="text-xl font-bold text-slate-800"><?= $total_cpl ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa fa-bookmark-o"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Sikap</span>
                            <span class="text-xl font-bold text-emerald-600"><?= $count_sikap ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <i class="fa fa-heart-o"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Pengetahuan</span>
                            <span class="text-xl font-bold text-amber-600"><?= $count_pengetahuan ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i class="fa fa-lightbulb-o"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Ket. Umum</span>
                            <span class="text-xl font-bold text-indigo-600"><?= $count_ku ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <i class="fa fa-cogs"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between col-span-2 md:col-span-1">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Ket. Khusus</span>
                            <span class="text-xl font-bold text-purple-600"><?= $count_kk ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <i class="fa fa-code"></i>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Tab: Master CPL vs Matriks Kurikulum -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6 overflow-hidden">
                    <div class="flex border-b border-slate-200 px-4 pt-3 bg-slate-50/50 gap-2">
                        <button type="button" id="tabBtnCpl" onclick="switchObeTab('cpl')" class="px-4 py-2.5 text-xs font-bold rounded-t-xl border-b-2 border-blue-600 text-blue-600 bg-white transition flex items-center gap-2 cursor-pointer">
                            <i class="fa fa-list-ul"></i> Master CPL Program Studi
                        </button>
                        <button type="button" id="tabBtnMatrix" onclick="switchObeTab('matrix')" class="px-4 py-2.5 text-xs font-semibold rounded-t-xl text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer">
                            <i class="fa fa-th"></i> Matriks Pemetaan Kurikulum (CPL vs MK)
                        </button>
                    </div>

                    <!-- TAB 1: MASTER CPL -->
                    <div id="tabContentCpl" class="p-6">
                        <!-- Filter Kategori & Pencarian -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-slate-600 mr-1"><i class="fa fa-filter"></i> Kategori:</span>
                                <?php
                                    $categories = [
                                        '' => 'Semua Kategori',
                                        'Sikap' => 'Sikap',
                                        'Pengetahuan' => 'Pengetahuan',
                                        'Keterampilan Umum' => 'Keterampilan Umum',
                                        'Keterampilan Khusus' => 'Keterampilan Khusus'
                                    ];
                                ?>
                                <?php foreach ($categories as $kVal => $kLabel): ?>
                                    <?php
                                        $isActive = ($kategori_filter === $kVal) || (empty($kategori_filter) && $kVal === '');
                                        $url = base_url('kaprodi/cpl') . ($kVal !== '' ? '?kategori=' . urlencode($kVal) : '') . ($prodi_id != 1 ? ($kVal !== '' ? '&' : '?') . 'prodi_id=' . $prodi_id : '');
                                    ?>
                                    <a href="<?= $url ?>" class="px-3 py-1.5 rounded-full text-xs font-semibold transition <?= $isActive ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                                        <?= $kLabel ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <input type="text" id="cplSearchInput" onkeyup="filterCplTable()" placeholder="Cari kode atau deskripsi CPL..." class="w-full md:w-64 text-xs px-3.5 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Tabel Master CPL -->
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="w-full text-left border-collapse text-xs" id="cplTable">
                                <thead>
                                    <tr class="bg-slate-100/80 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                                        <th class="py-3.5 px-4 w-28">Kode CPL</th>
                                        <th class="py-3.5 px-4 w-40">Kategori</th>
                                        <th class="py-3.5 px-4">Deskripsi Capaian Pembelajaran</th>
                                        <th class="py-3.5 px-4 w-32 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (empty($cpls)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">
                                            <i class="fa fa-folder-open-o text-4xl mb-2 block text-slate-300"></i>
                                            Belum ada CPL yang terdaftar untuk program studi ini.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach ($cpls as $cpl): ?>
                                        <?php
                                            $badgeClass = 'bg-slate-100 text-slate-700';
                                            if ($cpl->kategori === 'Sikap') $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                                            elseif ($cpl->kategori === 'Pengetahuan') $badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                                            elseif ($cpl->kategori === 'Keterampilan Umum') $badgeClass = 'bg-indigo-50 text-indigo-700 border border-indigo-200';
                                            elseif ($cpl->kategori === 'Keterampilan Khusus') $badgeClass = 'bg-purple-50 text-purple-700 border border-purple-200';
                                        ?>
                                        <tr class="hover:bg-slate-50/80 transition duration-150 cpl-row">
                                            <td class="py-3.5 px-4 text-center font-medium text-slate-500"><?= $no++ ?></td>
                                            <td class="py-3.5 px-4 font-bold text-slate-900 cpl-kode">
                                                <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-200 font-mono">
                                                    <?= html_escape($cpl->kode_cpl) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="obe-badge <?= $badgeClass ?> font-semibold">
                                                    <?= html_escape($cpl->kategori) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-700 leading-relaxed cpl-deskripsi font-normal text-xs">
                                                <?= html_escape($cpl->deskripsi) ?>
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button" onclick="openEditCplModal(<?= $cpl->id ?>, '<?= html_escape(addslashes($cpl->kode_cpl)) ?>', '<?= html_escape(addslashes($cpl->kategori)) ?>', '<?= html_escape(addslashes($cpl->deskripsi)) ?>')" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition border-none cursor-pointer" title="Edit CPL">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <a href="<?= base_url('kaprodi/cpl/delete/' . $cpl->id . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : '')) ?>" data-sc-confirm="Apakah Anda yakin ingin menghapus CPL <?= html_escape($cpl->kode_cpl) ?>? Relasi pivot CPMK terkait juga akan dibersihkan." data-confirm-title="Hapus CPL" data-confirm-accept="Ya, Hapus CPL" data-confirm-type="danger" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition" title="Hapus CPL">
                                                        <i class="fa fa-trash"></i>
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

                    <!-- TAB 2: MATRIKS PEMETAAN KURIKULUM (CPL VS MATA KULIAH) -->
                    <div id="tabContentMatrix" class="p-6 hidden">
                        <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Matriks Korelasi Kurikulum Outcome-Based Education (OBE)</h3>
                                <p class="text-xs text-slate-500 mt-1">Pemetaan relasi pemenuhan CPL program studi melalui CPMK pada setiap mata kuliah yang ditawarkan.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    <i class="fa fa-check-circle"></i> Terpetakan via CPMK
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-200">
                                    <i class="fa fa-minus"></i> Belum Terkorelasi
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border border-slate-200 obe-matrix-table">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                                        <th class="py-3 px-3 w-10 text-center sticky left-0 bg-slate-100 z-10">No</th>
                                        <th class="py-3 px-3 w-24 sticky left-10 bg-slate-100 z-10">Kode MK</th>
                                        <th class="py-3 px-4 w-64">Nama Mata Kuliah</th>
                                        <th class="py-3 px-2 w-16 text-center">Smt</th>
                                        <th class="py-3 px-2 w-16 text-center">SKS</th>
                                        <th class="py-3 px-4 w-48">Dosen Pengampu</th>
                                        <?php foreach ($matrix_data['cpls'] as $c): ?>
                                        <th class="py-3 px-2 text-center min-w-[70px] bg-blue-50/70 text-blue-900 border-l border-slate-200" title="<?= html_escape($c->kode_cpl . ': ' . $c->deskripsi) ?>">
                                            <span class="block font-bold font-mono text-xs"><?= html_escape($c->kode_cpl) ?></span>
                                            <span class="text-[10px] text-blue-600 block font-normal truncate max-w-[65px]"><?= html_escape($c->kategori) ?></span>
                                        </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (empty($matrix_data['courses'])): ?>
                                    <tr>
                                        <td colspan="<?= 6 + count($matrix_data['cpls']) ?>" class="py-8 text-center text-slate-400">
                                            Tidak ada data penawaran mata kuliah kurikulum untuk program studi ini.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php $mno = 1; foreach ($matrix_data['courses'] as $cr): ?>
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-2.5 px-3 text-center text-slate-500 sticky left-0 bg-white"><?= $mno++ ?></td>
                                            <td class="py-2.5 px-3 font-mono font-bold text-slate-800 sticky left-10 bg-white"><?= html_escape($cr->kode_mk) ?></td>
                                            <td class="py-2.5 px-4 font-semibold text-slate-800">
                                                <?= html_escape($cr->nama_mk) ?>
                                                <span class="text-[10px] text-slate-400 block font-normal">Kelas <?= html_escape($cr->kelas) ?></span>
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-medium text-slate-600"><?= $cr->semester ?></td>
                                            <td class="py-2.5 px-2 text-center font-bold text-slate-700"><?= $cr->sks ?></td>
                                            <td class="py-2.5 px-4 text-slate-600 text-xs truncate max-w-[180px]" title="<?= html_escape($cr->dosen_nama) ?>">
                                                <?= html_escape($cr->dosen_nama ?: '-') ?>
                                            </td>
                                            <?php foreach ($matrix_data['cpls'] as $c): ?>
                                                <?php
                                                    $is_mapped = isset($matrix_data['matrix_map'][$cr->id][$c->id]);
                                                    $cpmk_list = $is_mapped ? $matrix_data['matrix_map'][$cr->id][$c->id] : [];
                                                ?>
                                                <td class="py-2.5 px-2 text-center border-l border-slate-100 <?= $is_mapped ? 'bg-emerald-50/40' : '' ?>">
                                                    <?php if ($is_mapped): ?>
                                                        <span class="inline-flex flex-col items-center justify-center">
                                                            <i class="fa fa-check text-emerald-600 font-bold text-sm"></i>
                                                            <span class="text-[9px] font-mono text-emerald-700 font-bold mt-0.5">
                                                                <?= html_escape(implode(',', array_unique($cpmk_list))) ?>
                                                            </span>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-slate-300">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
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
</div>

<!-- Modal Tambah / Edit CPL -->
<div id="cplModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden animate-fade-in p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-4 text-white flex items-center justify-between">
            <h3 class="text-base font-bold text-white m-0" id="modalTitle">Tambah Capaian Pembelajaran Lulusan (CPL)</h3>
            <button type="button" onclick="closeCplModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>
        <form action="<?= base_url('kaprodi/cpl/save') ?>" method="POST" class="p-6 m-0">
            <input type="hidden" name="id" id="modalCplId" value="">
            <input type="hidden" name="prodi_id" value="<?= $prodi_id ?>">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode CPL <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_cpl" id="modalKodeCpl" required placeholder="Contoh: CPL-01" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none uppercase font-mono">
                <span class="text-[11px] text-slate-400 mt-1 block">Gunakan format standar kode CPL program studi.</span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kategori CPL <span class="text-rose-500">*</span></label>
                <select name="kategori" id="modalKategori" required class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="Sikap">Sikap</option>
                    <option value="Pengetahuan" selected>Pengetahuan</option>
                    <option value="Keterampilan Umum">Keterampilan Umum</option>
                    <option value="Keterampilan Khusus">Keterampilan Khusus</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Indikator CPL <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="modalDeskripsi" rows="4" required placeholder="Tuliskan rumusan kompetensi atau indikator kemampuan lulusan..." class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCplModal()" class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition border-none cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition border-none cursor-pointer">
                    Simpan CPL
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchObeTab(tab) {
        const btnCpl = document.getElementById('tabBtnCpl');
        const btnMatrix = document.getElementById('tabBtnMatrix');
        const contentCpl = document.getElementById('tabContentCpl');
        const contentMatrix = document.getElementById('tabContentMatrix');

        if (tab === 'cpl') {
            btnCpl.className = "px-4 py-2.5 text-xs font-bold rounded-t-xl border-b-2 border-blue-600 text-blue-600 bg-white transition flex items-center gap-2 cursor-pointer";
            btnMatrix.className = "px-4 py-2.5 text-xs font-semibold rounded-t-xl text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer";
            contentCpl.classList.remove('hidden');
            contentMatrix.classList.add('hidden');
        } else {
            btnMatrix.className = "px-4 py-2.5 text-xs font-bold rounded-t-xl border-b-2 border-blue-600 text-blue-600 bg-white transition flex items-center gap-2 cursor-pointer";
            btnCpl.className = "px-4 py-2.5 text-xs font-semibold rounded-t-xl text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer";
            contentMatrix.classList.remove('hidden');
            contentCpl.classList.add('hidden');
        }
    }

    function openAddCplModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Capaian Pembelajaran Lulusan (CPL)';
        document.getElementById('modalCplId').value = '';
        document.getElementById('modalKodeCpl').value = '';
        document.getElementById('modalKategori').value = 'Pengetahuan';
        document.getElementById('modalDeskripsi').value = '';
        document.getElementById('cplModal').classList.remove('hidden');
    }

    function openEditCplModal(id, kode, kategori, deskripsi) {
        document.getElementById('modalTitle').innerText = 'Edit CPL: ' + kode;
        document.getElementById('modalCplId').value = id;
        document.getElementById('modalKodeCpl').value = kode;
        document.getElementById('modalKategori').value = kategori;
        document.getElementById('modalDeskripsi').value = deskripsi;
        document.getElementById('cplModal').classList.remove('hidden');
    }

    function closeCplModal() {
        document.getElementById('cplModal').classList.add('hidden');
    }

    function filterCplTable() {
        const input = document.getElementById("cplSearchInput");
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName("cpl-row");

        for (let i = 0; i < rows.length; i++) {
            const kode = rows[i].querySelector(".cpl-kode").textContent.toLowerCase();
            const desc = rows[i].querySelector(".cpl-deskripsi").textContent.toLowerCase();
            if (kode.indexOf(filter) > -1 || desc.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
