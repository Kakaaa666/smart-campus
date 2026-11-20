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
                            <p class="font-bold text-sm">Peringatan / Validasi Gagal</p>
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
                        <i class="fa fa-graduation-cap text-9xl"></i>
                    </div>
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                <span class="bg-blue-500/30 text-blue-200 text-xs font-semibold px-3 py-1 rounded-full border border-blue-400/20 whitespace-nowrap shrink-0">
                                    Fase 1: Perancangan Kurikulum OBE
                                </span>
                                <span class="bg-emerald-500/30 text-emerald-200 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-400/20 whitespace-nowrap shrink-0">
                                    Role: Kaprodi / Admin Kurikulum
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">Profil Lulusan, CPL & Matriks Kurikulum</h2>
                            <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                                Standar Kurikulum Outcome-Based Education (OBE) untuk Program Studi <strong class="text-white"><?= html_escape($prodi ? $prodi->nama_prodi : 'Teknik Informatika') ?></strong> (<?= html_escape($prodi ? $prodi->fakultas : '') ?>).
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap shrink-0 self-start xl:self-auto">
                            <?php if ((int)$this->session->userdata('role') === 1 && !empty($all_prodi)): ?>
                            <form method="GET" class="inline-block m-0">
                                <input type="hidden" name="tab" value="<?= html_escape($active_tab) ?>">
                                <select name="prodi_id" onchange="this.form.submit()" class="bg-white/20 text-white text-xs rounded-xl px-3 py-2 border border-white/30 backdrop-blur-md outline-none focus:ring-2 focus:ring-white/50 cursor-pointer">
                                    <?php foreach ($all_prodi as $ap): ?>
                                    <option value="<?= $ap->id ?>" class="text-slate-800" <?= ($prodi_id == $ap->id) ? 'selected' : '' ?>>
                                        Prodi: <?= html_escape($ap->nama_prodi) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                            <?php endif; ?>

                            <button type="button" onclick="openAddProfilModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3.5 py-2.5 rounded-xl font-semibold text-xs shadow-md flex items-center gap-1.5 transition border-none cursor-pointer whitespace-nowrap">
                                <i class="fa fa-user-plus"></i> Tambah Profil
                            </button>
                            <button type="button" onclick="openAddCplModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3.5 py-2.5 rounded-xl font-semibold text-xs shadow-md flex items-center gap-1.5 transition border-none cursor-pointer whitespace-nowrap">
                                <i class="fa fa-plus-circle"></i> Tambah CPL
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPI Statistik Kurikulum -->
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
                    $total_pl = count($profil_lulusan);
                ?>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Profil Lulusan</span>
                            <span class="text-xl font-bold text-indigo-700"><?= $total_pl ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <i class="fa fa-id-badge"></i>
                        </div>
                    </div>
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
                            <span class="text-xl font-bold text-blue-600"><?= $count_ku ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa fa-cogs"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Ket. Khusus</span>
                            <span class="text-xl font-bold text-purple-600"><?= $count_kk ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <i class="fa fa-code"></i>
                        </div>
                    </div>
                </div>

                <!-- Navigasi 3 Tab -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6 overflow-hidden">
                    <div class="flex border-b border-slate-200 px-4 pt-3 bg-slate-50/50 gap-2 flex-wrap">
                        <button type="button" id="tabBtnProfil" onclick="switchObeTab('profil')" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition flex items-center gap-2 cursor-pointer <?= ($active_tab === 'profil') ? 'border-b-2 border-blue-600 text-blue-600 bg-white' : 'text-slate-500 hover:text-slate-800' ?>">
                            <i class="fa fa-id-card-o"></i> 1. Profil Lulusan & Relasi CPL
                        </button>
                        <button type="button" id="tabBtnCpl" onclick="switchObeTab('cpl')" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition flex items-center gap-2 cursor-pointer <?= ($active_tab === 'cpl') ? 'border-b-2 border-blue-600 text-blue-600 bg-white' : 'text-slate-500 hover:text-slate-800' ?>">
                            <i class="fa fa-list-ul"></i> 2. Master CPL Program Studi
                        </button>
                        <button type="button" id="tabBtnCplMk" onclick="switchObeTab('cpl_mk')" class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition flex items-center gap-2 cursor-pointer <?= ($active_tab === 'cpl_mk') ? 'border-b-2 border-blue-600 text-blue-600 bg-white' : 'text-slate-500 hover:text-slate-800' ?>">
                            <i class="fa fa-th"></i> 3. Matriks CPL–MK (Bobot Wajib 100%)
                        </button>
                    </div>

                    <!-- TAB 1: PROFIL LULUSAN & RELASI CPL -->
                    <div id="tabContentProfil" class="p-6 <?= ($active_tab === 'profil') ? '' : 'hidden' ?>">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Profil Lulusan Program Studi</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Daftar peran profesi atau kompetensi lulusan setelah 3-5 tahun menyelesaikan perkuliahan.</p>
                            </div>
                            <button type="button" onclick="openAddProfilModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-xl text-xs font-semibold shadow-sm flex items-center gap-2 transition border-none cursor-pointer shrink-0">
                                <i class="fa fa-plus"></i> Tambah Profil Lulusan
                            </button>
                        </div>

                        <!-- Grid Profil Lulusan Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <?php if (empty($profil_lulusan)): ?>
                            <div class="col-span-2 py-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <i class="fa fa-folder-open-o text-3xl mb-2 block text-slate-300"></i>
                                Belum ada profil lulusan. Klik tombol "Tambah Profil Lulusan" untuk memulai.
                            </div>
                            <?php else: ?>
                                <?php foreach ($profil_lulusan as $pl): ?>
                                <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-4.5 hover:shadow-md transition">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-lg font-mono font-bold text-xs">
                                                <?= html_escape($pl->kode_profil) ?>
                                            </span>
                                            <h4 class="text-sm font-bold text-slate-900 m-0"><?= html_escape($pl->nama_profil) ?></h4>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" onclick="openEditProfilModal(<?= $pl->id ?>, '<?= html_escape(addslashes($pl->kode_profil)) ?>', '<?= html_escape(addslashes($pl->nama_profil)) ?>', '<?= html_escape(addslashes($pl->deskripsi)) ?>')" class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition border-none cursor-pointer" title="Edit Profil">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a href="<?= base_url('kaprodi/cpl/delete_profil/' . $pl->id . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : '')) ?>" data-sc-confirm="Apakah Anda yakin ingin menghapus Profil Lulusan <?= html_escape($pl->kode_profil) ?>?" data-confirm-title="Hapus Profil" data-confirm-accept="Ya, Hapus" data-confirm-type="danger" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition" title="Hapus Profil">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed mb-3 m-0"><?= html_escape($pl->deskripsi) ?></p>

                                    <!-- Quick CPL Mapping Badges -->
                                    <div class="pt-2.5 border-t border-slate-200/70 flex flex-wrap items-center gap-1.5">
                                        <span class="text-[11px] font-semibold text-slate-500 mr-1">CPL Terkait:</span>
                                        <?php
                                            $has_cpl = false;
                                            foreach ($profil_cpl_data['cpls'] as $c) {
                                                if (isset($profil_cpl_data['matrix'][$pl->id . '_' . $c->id])) {
                                                    $has_cpl = true;
                                                    echo '<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-mono font-bold">' . html_escape($c->kode_cpl) . '</span>';
                                                }
                                            }
                                            if (!$has_cpl) {
                                                echo '<span class="text-[10px] text-slate-400 italic">Belum dipetakan ke CPL</span>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Matriks Interaktif Pemetaan Profil Lulusan <-> CPL -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 m-0">Matriks Korelasi Profil Lulusan <-> CPL</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Centang CPL yang mendukung pemenuhan profil lulusan yang bersangkutan.</p>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-slate-100/90 text-slate-800 font-bold border-b border-slate-200">
                                            <th class="py-3 px-4 w-48 sticky left-0 bg-slate-100 z-10">Profil Lulusan</th>
                                            <?php foreach ($profil_cpl_data['cpls'] as $c): ?>
                                            <th class="py-3 px-3 text-center min-w-[75px] bg-blue-50/70 text-blue-900 border-l border-slate-200" title="<?= html_escape($c->kode_cpl . ': ' . $c->deskripsi) ?>">
                                                <span class="block font-bold font-mono"><?= html_escape($c->kode_cpl) ?></span>
                                                <span class="text-[9px] font-normal text-blue-600 block truncate max-w-[70px]"><?= html_escape($c->kategori) ?></span>
                                            </th>
                                            <?php endforeach; ?>
                                            <th class="py-3 px-3 text-center w-28 bg-slate-100 border-l border-slate-200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php if (empty($profil_lulusan)): ?>
                                        <tr>
                                            <td colspan="<?= 2 + count($profil_cpl_data['cpls']) ?>" class="py-6 text-center text-slate-400">
                                                Belum ada profil lulusan untuk program studi ini.
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($profil_lulusan as $pl): ?>
                                            <form action="<?= base_url('kaprodi/cpl/save_profil_cpl') ?>" method="POST">
                                                <input type="hidden" name="profil_lulusan_id" value="<?= $pl->id ?>">
                                                <input type="hidden" name="prodi_id" value="<?= $prodi_id ?>">
                                                <tr class="hover:bg-slate-50/60 transition">
                                                    <td class="py-3 px-4 sticky left-0 bg-white font-semibold text-slate-800">
                                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-mono font-bold text-xs mr-1">
                                                            <?= html_escape($pl->kode_profil) ?>
                                                        </span>
                                                        <?= html_escape($pl->nama_profil) ?>
                                                    </td>
                                                    <?php foreach ($profil_cpl_data['cpls'] as $c): ?>
                                                        <?php $is_checked = isset($profil_cpl_data['matrix'][$pl->id . '_' . $c->id]); ?>
                                                        <td class="py-3 px-3 text-center border-l border-slate-100 <?= $is_checked ? 'bg-blue-50/30' : '' ?>">
                                                            <input type="checkbox" name="cpl_ids[]" value="<?= $c->id ?>" <?= $is_checked ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td class="py-3 px-3 text-center border-l border-slate-200">
                                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-[11px] shadow-sm transition border-none cursor-pointer">
                                                            <i class="fa fa-save"></i> Simpan
                                                        </button>
                                                    </td>
                                                </tr>
                                            </form>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: MASTER CPL -->
                    <div id="tabContentCpl" class="p-6 <?= ($active_tab === 'cpl') ? '' : 'hidden' ?>">
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
                                        $url = base_url('kaprodi/cpl?tab=cpl') . ($kVal !== '' ? '&kategori=' . urlencode($kVal) : '') . ($prodi_id != 1 ? '&prodi_id=' . $prodi_id : '');
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
                                                    <a href="<?= base_url('kaprodi/cpl/delete/' . $cpl->id . ($prodi_id != 1 ? '?prodi_id=' . $prodi_id : '')) ?>" data-sc-confirm="Apakah Anda yakin ingin menghapus CPL <?= html_escape($cpl->kode_cpl) ?>?" data-confirm-title="Hapus CPL" data-confirm-accept="Ya, Hapus CPL" data-confirm-type="danger" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition" title="Hapus CPL">
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

                    <!-- TAB 3: MATRIKS CPL-MK (DENGAN BOBOT WAJIB 100%) -->
                    <div id="tabContentCplMk" class="p-6 <?= ($active_tab === 'cpl_mk') ? '' : 'hidden' ?>">
                        <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Matriks CPL–Mata Kuliah & Distribusi Bobot (%)</h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    Pemetaan mata kuliah pendukung untuk setiap CPL beserta persentase kontribusinya. 
                                    <strong class="text-rose-600">Total bobot MK per CPL WAJIB tepat 100.00%.</strong>
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    <i class="fa fa-check-circle"></i> Bobot 100% (Valid)
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs text-rose-700 font-semibold bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">
                                    <i class="fa fa-exclamation-circle"></i> != 100% (Perlu Revisi)
                                </span>
                            </div>
                        </div>

                        <!-- Card Rekapitulasi Status Bobot per CPL -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                            <?php foreach ($cpl_mk_data['cpls'] as $c): ?>
                            <div class="p-3.5 rounded-2xl border transition <?= $c->is_valid_100 ? 'bg-emerald-50/40 border-emerald-200' : 'bg-rose-50/40 border-rose-200' ?>">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded <?= $c->is_valid_100 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                        <?= html_escape($c->kode_cpl) ?>
                                    </span>
                                    <span class="text-xs font-bold <?= $c->is_valid_100 ? 'text-emerald-700' : 'text-rose-700' ?>">
                                        <?= number_format($c->total_bobot, 2) ?>% / 100%
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-600 truncate mb-2" title="<?= html_escape($c->deskripsi) ?>">
                                    <?= html_escape($c->deskripsi) ?>
                                </p>
                                <button type="button" onclick="openAturBobotModal(<?= $c->id ?>, '<?= html_escape(addslashes($c->kode_cpl)) ?>')" class="w-full py-1.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition border-none cursor-pointer <?= $c->is_valid_100 ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white' ?>">
                                    <i class="fa fa-sliders"></i> Atur Bobot MK
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tabel Matriks Lengkap CPL vs MK -->
                        <div class="overflow-x-auto rounded-2xl border border-slate-200 obe-matrix-table">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                                        <th class="py-3 px-3 w-10 text-center sticky left-0 bg-slate-100 z-10">No</th>
                                        <th class="py-3 px-3 w-28 sticky left-10 bg-slate-100 z-10">Kode MK</th>
                                        <th class="py-3 px-4 min-w-[200px]">Nama Mata Kuliah</th>
                                        <?php foreach ($cpl_mk_data['cpls'] as $c): ?>
                                        <th class="py-3 px-2 text-center min-w-[90px] border-l border-slate-200 <?= $c->is_valid_100 ? 'bg-emerald-50/70 text-emerald-900' : 'bg-rose-50/70 text-rose-900' ?>">
                                            <span class="block font-bold font-mono text-xs"><?= html_escape($c->kode_cpl) ?></span>
                                            <span class="text-[10px] font-semibold block"><?= number_format($c->total_bobot, 1) ?>%</span>
                                        </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (empty($cpl_mk_data['mata_kuliah'])): ?>
                                    <tr>
                                        <td colspan="<?= 3 + count($cpl_mk_data['cpls']) ?>" class="py-8 text-center text-slate-400">
                                            Tidak ada master data mata kuliah.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php $mno = 1; foreach ($cpl_mk_data['mata_kuliah'] as $mk): ?>
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-2.5 px-3 text-center text-slate-500 sticky left-0 bg-white"><?= $mno++ ?></td>
                                            <td class="py-2.5 px-3 font-mono font-bold text-slate-800 sticky left-10 bg-white"><?= html_escape($mk->kode) ?></td>
                                            <td class="py-2.5 px-4 font-semibold text-slate-800"><?= html_escape($mk->nama) ?></td>
                                            <?php foreach ($cpl_mk_data['cpls'] as $c): ?>
                                                <?php
                                                    $key = $c->id . '_' . $mk->id;
                                                    $val = isset($cpl_mk_data['weights'][$key]) ? (float)$cpl_mk_data['weights'][$key] : 0.0;
                                                ?>
                                                <td class="py-2.5 px-2 text-center border-l border-slate-100 <?= ($val > 0) ? 'bg-blue-50/50' : '' ?>">
                                                    <?php if ($val > 0): ?>
                                                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 font-mono font-bold rounded text-[11px]">
                                                            <?= number_format($val, 1) ?>%
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

<!-- Modal Tambah / Edit Profil Lulusan -->
<div id="profilModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden animate-fade-in p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300">
        <div class="bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-4 text-white flex items-center justify-between">
            <h3 class="text-base font-bold text-white m-0" id="profilModalTitle">Tambah Profil Lulusan</h3>
            <button type="button" onclick="closeProfilModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>
        <form action="<?= base_url('kaprodi/cpl/save_profil') ?>" method="POST" class="p-6 m-0">
            <input type="hidden" name="id" id="modalProfilId" value="">
            <input type="hidden" name="prodi_id" value="<?= $prodi_id ?>">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Profil <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_profil" id="modalKodeProfil" required placeholder="Contoh: PL-01" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none uppercase font-mono">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Peran / Profesi Lulusan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_profil" id="modalNamaProfil" required placeholder="Contoh: Software Engineer, Data Scientist" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Profil</label>
                <textarea name="deskripsi" id="modalDeskripsiProfil" rows="3" placeholder="Deskripsi kompetensi dan lingkup kerja lulusan..." class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeProfilModal()" class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition border-none cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition border-none cursor-pointer">
                    Simpan Profil
                </button>
            </div>
        </form>
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

<!-- Modal Atur Bobot MK per CPL (Validasi Wajib 100%) -->
<div id="bobotModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden animate-fade-in p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 flex flex-col max-h-[90vh]">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-4 text-white flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-base font-bold text-white m-0" id="bobotModalTitle">Atur Bobot MK untuk CPL</h3>
                <p class="text-xs text-blue-200 mt-0.5">Distribusikan persentase bobot MK pendukung. Total WAJIB tepat 100.00%.</p>
            </div>
            <button type="button" onclick="closeAturBobotModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>

        <form action="<?= base_url('kaprodi/cpl/save_cpl_mk') ?>" method="POST" id="formBobotCplMk" onsubmit="return validateBobotForm()" class="m-0 flex flex-col flex-1 overflow-hidden">
            <input type="hidden" name="cpl_id" id="modalBobotCplId" value="">
            <input type="hidden" name="prodi_id" value="<?= $prodi_id ?>">

            <!-- Live Total Indicator Bar -->
            <div id="liveTotalBar" class="px-6 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
                <span class="text-xs font-semibold text-slate-600">Total Akumulasi Bobot:</span>
                <span id="liveTotalText" class="text-sm font-bold font-mono px-3 py-1 rounded-full bg-slate-200 text-slate-800">
                    0.00% / 100.00%
                </span>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-3">
                <p class="text-xs text-slate-500 mb-3">Masukkan persentase bobot (0 - 100) untuk mata kuliah yang berkontribusi terhadap CPL ini:</p>
                <div class="space-y-2.5">
                    <?php foreach ($cpl_mk_data['mata_kuliah'] as $mk): ?>
                    <div class="flex items-center justify-between gap-4 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition">
                        <div class="flex-1 min-w-0">
                            <span class="font-mono font-bold text-xs text-indigo-700 mr-2"><?= html_escape($mk->kode) ?></span>
                            <span class="text-xs font-semibold text-slate-800 truncate"><?= html_escape($mk->nama) ?></span>
                        </div>
                        <div class="w-32 flex items-center gap-1.5 shrink-0">
                            <input type="number" step="0.5" min="0" max="100" name="mk_weights[<?= $mk->id ?>]" id="weight_mk_<?= $mk->id ?>" value="0" oninput="recalculateLiveBobot()" placeholder="0" class="w-full text-xs font-bold text-right px-3 py-1.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <span class="text-xs font-semibold text-slate-500">%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
                <span id="warningBobotText" class="text-xs font-semibold text-rose-600"></span>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeAturBobotModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-200 hover:bg-slate-300 rounded-xl transition border-none cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitBobot" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition border-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Simpan Matriks (Wajib 100%)
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function switchObeTab(tab) {
        const tabs = ['profil', 'cpl', 'cpl_mk'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1).replace('_', 'Mk'));
            const content = document.getElementById('tabContent' + t.charAt(0).toUpperCase() + t.slice(1).replace('_', 'Mk'));
            if (btn && content) {
                if (t === tab) {
                    btn.className = "px-4 py-2.5 text-xs font-bold rounded-t-xl border-b-2 border-blue-600 text-blue-600 bg-white transition flex items-center gap-2 cursor-pointer";
                    content.classList.remove('hidden');
                } else {
                    btn.className = "px-4 py-2.5 text-xs font-semibold rounded-t-xl text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer";
                    content.classList.add('hidden');
                }
            }
        });
    }

    // Modal Profil
    function openAddProfilModal() {
        document.getElementById('profilModalTitle').innerText = 'Tambah Profil Lulusan';
        document.getElementById('modalProfilId').value = '';
        document.getElementById('modalKodeProfil').value = '';
        document.getElementById('modalNamaProfil').value = '';
        document.getElementById('modalDeskripsiProfil').value = '';
        document.getElementById('profilModal').classList.remove('hidden');
    }

    function openEditProfilModal(id, kode, nama, deskripsi) {
        document.getElementById('profilModalTitle').innerText = 'Edit Profil Lulusan: ' + kode;
        document.getElementById('modalProfilId').value = id;
        document.getElementById('modalKodeProfil').value = kode;
        document.getElementById('modalNamaProfil').value = nama;
        document.getElementById('modalDeskripsiProfil').value = deskripsi;
        document.getElementById('profilModal').classList.remove('hidden');
    }

    function closeProfilModal() {
        document.getElementById('profilModal').classList.add('hidden');
    }

    // Modal CPL
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

    // Modal Atur Bobot CPL-MK
    function openAturBobotModal(cplId, kodeCpl) {
        document.getElementById('bobotModalTitle').innerText = 'Atur Bobot MK untuk ' + kodeCpl;
        document.getElementById('modalBobotCplId').value = cplId;

        // Reset semua field input
        document.querySelectorAll('input[id^="weight_mk_"]').forEach(el => el.value = '0');

        // Fetch data bobot via AJAX
        fetch('<?= base_url("kaprodi/cpl/get_cpl_mk_json/") ?>' + cplId)
            .then(res => res.json())
            .then(data => {
                if (data && data.weights) {
                    for (const [mkId, weight] of Object.entries(data.weights)) {
                        const inputEl = document.getElementById('weight_mk_' + mkId);
                        if (inputEl) {
                            inputEl.value = weight;
                        }
                    }
                }
                recalculateLiveBobot();
                document.getElementById('bobotModal').classList.remove('hidden');
            })
            .catch(err => {
                recalculateLiveBobot();
                document.getElementById('bobotModal').classList.remove('hidden');
            });
    }

    function closeAturBobotModal() {
        document.getElementById('bobotModal').classList.add('hidden');
    }

    function recalculateLiveBobot() {
        let total = 0.0;
        document.querySelectorAll('input[id^="weight_mk_"]').forEach(el => {
            const val = parseFloat(el.value) || 0.0;
            total += val;
        });

        const liveTotalText = document.getElementById('liveTotalText');
        const warningText = document.getElementById('warningBobotText');
        const btnSubmit = document.getElementById('btnSubmitBobot');

        liveTotalText.innerText = total.toFixed(2) + '% / 100.00%';

        if (Math.abs(total - 100.0) < 0.01) {
            liveTotalText.className = "text-sm font-bold font-mono px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300";
            warningText.innerText = "✓ Total tepat 100.00% (Valid untuk disimpan)";
            warningText.className = "text-xs font-semibold text-emerald-600";
            btnSubmit.removeAttribute('disabled');
        } else {
            liveTotalText.className = "text-sm font-bold font-mono px-3 py-1 rounded-full bg-rose-100 text-rose-800 border border-rose-300";
            warningText.innerText = "⚠ Total harus tepat 100.00% (Selisih: " + (100.0 - total).toFixed(2) + "%)";
            warningText.className = "text-xs font-semibold text-rose-600";
            btnSubmit.setAttribute('disabled', 'disabled');
        }
    }

    function validateBobotForm() {
        let total = 0.0;
        document.querySelectorAll('input[id^="weight_mk_"]').forEach(el => {
            const val = parseFloat(el.value) || 0.0;
            total += val;
        });

        if (Math.abs(total - 100.0) > 0.01) {
            alert('Validasi Gagal: Total bobot MK untuk CPL ini harus tepat 100.00%! Saat ini: ' + total.toFixed(2) + '%. Mohon sesuaikan bobot.');
            return false;
        }
        return true;
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
