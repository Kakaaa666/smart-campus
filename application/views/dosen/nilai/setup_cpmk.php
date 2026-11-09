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

                <!-- Alert Flash Messages -->
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

                <!-- Back button & Breadcrumb header -->
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <a href="<?= base_url('dosen/nilai') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-sm">
                        <i class="fa fa-arrow-left"></i> Kembali ke Daftar Kelas
                    </a>
                    <a href="<?= base_url('dosen/nilai/matriks/' . $course->id) ?>" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl transition shadow-sm">
                        <i class="fa fa-table"></i> Buka Matriks Nilai Mahasiswa
                    </a>
                </div>

                <!-- Course Detail Banner -->
                <div class="bg-gradient-to-r from-indigo-800 via-blue-800 to-slate-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-white mb-5 sm:mb-6 shadow-xl relative overflow-hidden">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 sm:gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <span class="bg-blue-500/30 text-blue-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-blue-400/20 mb-2 inline-flex items-center whitespace-nowrap shrink-0">
                                Konfigurasi RPS &amp; Instrumen Asesmen OBE
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white m-0">
                                <?= html_escape($course->nama_mk) ?>
                            </h2>
                            <p class="text-blue-100 text-xs mt-1.5 flex items-center gap-3 sm:gap-4 flex-wrap">
                                <span><i class="fa fa-barcode"></i> Kode: <strong class="text-white"><?= html_escape($course->kode_mk) ?></strong></span>
                                <span><i class="fa fa-graduation-cap"></i> SKS: <strong class="text-white"><?= $course->sks ?> SKS</strong></span>
                                <span><i class="fa fa-users"></i> Kelas: <strong class="text-white"><?= html_escape($course->kelas) ?></strong></span>
                                <span><i class="fa fa-calendar"></i> Tahun: <strong class="text-white"><?= html_escape($course->tahun_akademik) ?> (<?= html_escape($course->semester_akademik) ?>)</strong></span>
                                <span><i class="fa fa-user"></i> Dosen: <strong class="text-white"><?= html_escape($course->nama_dosen) ?></strong></span>
                            </p>
                        </div>

                        <!-- Live Status Validasi 100% -->
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/20 w-full sm:w-auto min-w-[200px] shrink-0 self-stretch sm:self-start xl:self-auto">
                            <span class="text-[11px] text-white/80 block uppercase font-bold tracking-wider mb-1">Status Bobot Asesmen</span>
                            <div class="flex items-center gap-2">
                                <span id="bannerBobotPercent" class="text-2xl font-black <?= $is_valid_100 ? 'text-emerald-300' : 'text-amber-300' ?>">
                                    <?= number_format($total_bobot, 2) ?>%
                                </span>
                                <span id="bannerBobotBadge" class="text-[11px] px-2.5 py-1 rounded-full font-bold whitespace-nowrap shrink-0 <?= $is_valid_100 ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-amber-500/30 text-amber-200 border border-amber-400/30' ?>">
                                    <?= $is_valid_100 ? 'Tepat 100%' : 'Belum 100%' ?>
                                </span>
                            </div>
                            <span id="bannerBobotNote" class="text-[11px] text-white/70 block mt-1">
                                <?= $is_valid_100 ? 'Siap digunakan untuk input nilai.' : 'Wajib bernilai tepat 100.00%.' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- KOLOM KIRI: DAFTAR CPMK & RELASI CPL (5 COLS) -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 m-0">1. Capaian Pembelajaran Mata Kuliah (CPMK)</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Rumusan kompetensi spesifik untuk mata kuliah ini.</p>
                                </div>
                                <button type="button" onclick="openAddCpmkModal()" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition border-none cursor-pointer flex items-center gap-1.5 shadow-sm">
                                    <i class="fa fa-plus"></i> Tambah
                                </button>
                            </div>

                            <div class="space-y-3" id="cpmkListContainer">
                                <?php if (empty($cpmks)): ?>
                                <div class="p-6 text-center text-slate-400 border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                    <i class="fa fa-info-circle text-3xl mb-1 block text-slate-300"></i>
                                    Belum ada CPMK yang dibuat.<br>
                                    <span class="text-[11px]">Klik "Tambah" di atas untuk menambahkan CPMK pertama.</span>
                                </div>
                                <?php else: ?>
                                    <?php foreach ($cpmks as $cp): ?>
                                    <div class="p-4 rounded-xl border border-slate-200 hover:border-blue-300 bg-slate-50/40 hover:bg-white transition duration-150">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 font-bold font-mono text-xs">
                                                <?= html_escape($cp->kode_cpmk) ?>
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <button type="button" onclick="openEditCpmkModal(<?= $cp->id ?>, '<?= html_escape(addslashes($cp->kode_cpmk)) ?>', '<?= html_escape(addslashes($cp->deskripsi)) ?>', <?= html_escape(json_encode(array_map(function($m){ return (int)$m->cpl_id; }, $cp->cpls))) ?>)" class="text-slate-400 hover:text-amber-600 p-1 text-xs bg-transparent border-none cursor-pointer" title="Edit CPMK">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <a href="<?= base_url('dosen/nilai/delete_cpmk/' . $cp->id . '/' . $course->id) ?>" data-sc-confirm="Hapus CPMK <?= html_escape($cp->kode_cpmk) ?> beserta seluruh komponen yang terhubung?" data-confirm-title="Hapus CPMK" data-confirm-accept="Ya, Hapus" data-confirm-type="danger" class="text-slate-400 hover:text-rose-600 p-1 text-xs" title="Hapus CPMK">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-700 leading-relaxed mb-2">
                                            <?= html_escape($cp->deskripsi) ?>
                                        </p>
                                        <!-- CPL Terhubung -->
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[10px] text-slate-400 font-semibold">Terkorelasi CPL:</span>
                                            <?php if (empty($cp->cpls)): ?>
                                                <span class="text-[10px] text-amber-600 italic">Belum dipetakan</span>
                                            <?php else: ?>
                                                <?php foreach ($cp->cpls as $cpl_item): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" title="<?= html_escape($cpl_item->deskripsi) ?>">
                                                    <?= html_escape($cpl_item->kode_cpl) ?>
                                                </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: FORM DISTRIBUSI BOBOT ASESMEN (7 COLS) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 m-0">2. Instrumen Asesmen &amp; Distribusi Bobot (100%)</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Tentukan jenis instrumen asesmen dan persentase bobot masing-masing.</p>
                                </div>
                                <button type="button" onclick="addAssessmentRow()" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition border-none cursor-pointer flex items-center gap-1.5 shadow-sm">
                                    <i class="fa fa-plus-circle"></i> Tambah Asesmen
                                </button>
                            </div>

                            <form action="<?= base_url('dosen/nilai/save_komponen') ?>" method="POST" id="formKomponen" onsubmit="return validateKomponenForm(event)">
                                <input type="hidden" name="penawaran_id" value="<?= $course->id ?>">

                                <div class="overflow-x-auto rounded-xl border border-slate-200 mt-4 mb-4">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead>
                                            <tr class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                                                <th class="py-3 px-3 w-40">CPMK Pengukur <span class="text-rose-500">*</span></th>
                                                <th class="py-3 px-4">Nama Asesmen / Tugas / Ujian <span class="text-rose-500">*</span></th>
                                                <th class="py-3 px-3 w-28 text-center">Bobot (%) <span class="text-rose-500">*</span></th>
                                                <th class="py-3 px-2 w-12 text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="komponenRowsContainer" class="divide-y divide-slate-100 bg-white">
                                            <?php if (empty($komponens)): ?>
                                                <!-- Row default jika kosong -->
                                                <tr class="komponen-row">
                                                    <td class="py-2.5 px-3">
                                                        <select name="cpmk_id[]" required class="w-full text-xs px-2 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none">
                                                            <option value="">-- Pilih CPMK --</option>
                                                            <?php foreach ($cpmks as $c): ?>
                                                            <option value="<?= $c->id ?>"><?= html_escape($c->kode_cpmk) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td class="py-2.5 px-4">
                                                        <input type="text" name="nama_asesmen[]" required placeholder="Contoh: Tugas 1 / UTS Praktik" class="w-full text-xs px-2.5 py-1.5 border border-slate-200 rounded-lg focus:outline-none">
                                                    </td>
                                                    <td class="py-2.5 px-3 text-center">
                                                        <input type="number" step="0.5" min="1" max="100" name="bobot_persen[]" value="25" oninput="calculateTotalWeight()" required class="w-20 text-center font-bold text-xs px-2 py-1.5 border border-slate-200 rounded-lg focus:outline-none bobot-input">
                                                    </td>
                                                    <td class="py-2.5 px-2 text-center">
                                                        <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-rose-600 bg-transparent border-none cursor-pointer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($komponens as $k): ?>
                                                <tr class="komponen-row">
                                                    <input type="hidden" name="komponen_id[]" value="<?= $k->id ?>">
                                                    <td class="py-2.5 px-3">
                                                        <select name="cpmk_id[]" required class="w-full text-xs px-2 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none">
                                                            <option value="">-- Pilih CPMK --</option>
                                                            <?php foreach ($cpmks as $c): ?>
                                                            <option value="<?= $c->id ?>" <?= ($k->cpmk_id == $c->id) ? 'selected' : '' ?>>
                                                                <?= html_escape($c->kode_cpmk) ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td class="py-2.5 px-4">
                                                        <input type="text" name="nama_asesmen[]" value="<?= html_escape($k->nama_asesmen) ?>" required class="w-full text-xs px-2.5 py-1.5 border border-slate-200 rounded-lg focus:outline-none">
                                                    </td>
                                                    <td class="py-2.5 px-3 text-center">
                                                        <input type="number" step="0.5" min="1" max="100" name="bobot_persen[]" value="<?= (float)$k->bobot_persen ?>" oninput="calculateTotalWeight()" required class="w-20 text-center font-bold text-xs px-2 py-1.5 border border-slate-200 rounded-lg focus:outline-none bobot-input">
                                                    </td>
                                                    <td class="py-2.5 px-2 text-center">
                                                        <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-rose-600 bg-transparent border-none cursor-pointer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-slate-50 font-bold border-t border-slate-200">
                                                <td colspan="2" class="py-3 px-4 text-right text-slate-700">Total Akumulasi Bobot:</td>
                                                <td class="py-3 px-3 text-center">
                                                    <span id="displayTotalBobot" class="text-sm font-black text-slate-800">
                                                        <?= number_format($total_bobot, 2) ?>%
                                                    </span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Indikator Status & Tombol Simpan -->
                                <div id="weightAlertContainer" class="p-3.5 rounded-xl mb-4 text-xs flex items-center justify-between <?= $is_valid_100 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' ?>">
                                    <div class="flex items-center gap-2">
                                        <i id="weightAlertIcon" class="fa <?= $is_valid_100 ? 'fa-check-circle text-emerald-600' : 'fa-exclamation-circle text-rose-600' ?> text-base"></i>
                                        <span id="weightAlertText" class="font-bold">
                                            <?= $is_valid_100 ? 'Validasi Terpenuhi: Total bobot tepat 100.00%.' : 'Total bobot asesmen belum 100.00% (saat ini: ' . number_format($total_bobot, 2) . '%). Total bobot harus pas 100% agar dapat disimpan.' ?>
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">Aturan OBE</span>
                                </div>

                                <div class="flex items-center justify-end gap-3">
                                    <button type="submit" id="btnSubmitBobot" class="px-6 py-2.5 rounded-xl font-bold text-xs text-white transition shadow-md border-none cursor-pointer <?= $is_valid_100 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed' ?>">
                                        <i class="fa fa-save"></i> Simpan Komponen &amp; Bobot 100%
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit CPMK -->
<div id="cpmkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-4 text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white m-0" id="modalCpmkTitle">Tambah CPMK Baru</h3>
            <button type="button" onclick="closeCpmkModal()" class="text-white/80 hover:text-white text-xl bg-transparent border-none cursor-pointer">
                &times;
            </button>
        </div>
        <form action="<?= base_url('dosen/nilai/save_cpmk') ?>" method="POST" class="p-6 m-0">
            <input type="hidden" name="penawaran_id" value="<?= $course->id ?>">
            <input type="hidden" name="cpmk_id" id="formCpmkId" value="">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode CPMK <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_cpmk" id="formKodeCpmk" required placeholder="Contoh: CPMK-1" class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none uppercase font-mono font-bold">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Capaian Pembelajaran <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="formDeskripsiCpmk" rows="3" required placeholder="Jelaskan kompetensi spesifik yang diharapkan dapat dicapai mahasiswa pada mata kuliah ini..." class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1">Relasi CPL Program Studi yang Didukung</label>
                <p class="text-[11px] text-slate-400 mb-2">Pilih satu atau lebih CPL yang dikorelasikan langsung dengan CPMK ini:</p>
                <div class="space-y-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-xl bg-slate-50">
                    <?php if (empty($available_cpls)): ?>
                        <span class="text-xs text-slate-400">Belum ada CPL untuk program studi ini.</span>
                    <?php else: ?>
                        <?php foreach ($available_cpls as $ac): ?>
                        <label class="flex items-start gap-2.5 text-xs text-slate-700 hover:text-slate-900 cursor-pointer">
                            <input type="checkbox" name="cpl_ids[]" value="<?= $ac->id ?>" class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cpl-checkbox">
                            <div>
                                <span class="font-bold font-mono text-blue-700"><?= html_escape($ac->kode_cpl) ?></span>
                                <span class="text-[10px] text-slate-500 ml-1">(<?= html_escape($ac->kategori) ?>)</span>
                                <p class="text-[11px] text-slate-500 mt-0.5"><?= html_escape($ac->deskripsi) ?></p>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
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

<script>
    // CPMK Modal
    function openAddCpmkModal() {
        document.getElementById('modalCpmkTitle').innerText = 'Tambah CPMK Baru';
        document.getElementById('formCpmkId').value = '';
        document.getElementById('formKodeCpmk').value = '';
        document.getElementById('formDeskripsiCpmk').value = '';
        document.querySelectorAll('.cpl-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('cpmkModal').classList.remove('hidden');
    }

    function openEditCpmkModal(id, kode, deskripsi, selectedCpls) {
        document.getElementById('modalCpmkTitle').innerText = 'Edit ' + kode;
        document.getElementById('formCpmkId').value = id;
        document.getElementById('formKodeCpmk').value = kode;
        document.getElementById('formDeskripsiCpmk').value = deskripsi;

        document.querySelectorAll('.cpl-checkbox').forEach(cb => {
            cb.checked = selectedCpls.includes(parseInt(cb.value));
        });

        document.getElementById('cpmkModal').classList.remove('hidden');
    }

    function closeCpmkModal() {
        document.getElementById('cpmkModal').classList.add('hidden');
    }

    // Dynamic Assessment Rows
    const cpmkOptionsHtml = `<?php foreach ($cpmks as $c): ?><option value="<?= $c->id ?>"><?= html_escape($c->kode_cpmk) ?></option><?php endforeach; ?>`;

    function addAssessmentRow() {
        const tbody = document.getElementById('komponenRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'komponen-row animate-fade-in';
        tr.innerHTML = `
            <td class="py-2.5 px-3">
                <select name="cpmk_id[]" required class="w-full text-xs px-2 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none">
                    <option value="">-- Pilih CPMK --</option>
                    ${cpmkOptionsHtml}
                </select>
            </td>
            <td class="py-2.5 px-4">
                <input type="text" name="nama_asesmen[]" required placeholder="Contoh: Kuis / Tugas 2 / Proyek" class="w-full text-xs px-2.5 py-1.5 border border-slate-200 rounded-lg focus:outline-none">
            </td>
            <td class="py-2.5 px-3 text-center">
                <input type="number" step="0.5" min="1" max="100" name="bobot_persen[]" value="20" oninput="calculateTotalWeight()" required class="w-20 text-center font-bold text-xs px-2 py-1.5 border border-slate-200 rounded-lg focus:outline-none bobot-input">
            </td>
            <td class="py-2.5 px-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-rose-600 bg-transparent border-none cursor-pointer">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        calculateTotalWeight();
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        const totalRows = document.querySelectorAll('.komponen-row').length;
        if (totalRows <= 1) {
            if (window.SCDialog) {
                SCDialog.alert('Minimal harus ada 1 komponen penilaian.', { title: 'Komponen Asesmen', type: 'warning' });
            } else {
                alert('Minimal harus ada 1 komponen penilaian.');
            }
            return;
        }
        row.remove();
        calculateTotalWeight();
    }

    function calculateTotalWeight() {
        const inputs = document.querySelectorAll('.bobot-input');
        let total = 0.0;
        inputs.forEach(inp => {
            const val = parseFloat(inp.value) || 0.0;
            total += val;
        });

        const display = document.getElementById('displayTotalBobot');
        const bannerPercent = document.getElementById('bannerBobotPercent');
        const bannerBadge = document.getElementById('bannerBobotBadge');
        const bannerNote = document.getElementById('bannerBobotNote');
        const alertBox = document.getElementById('weightAlertContainer');
        const alertText = document.getElementById('weightAlertText');
        const alertIcon = document.getElementById('weightAlertIcon');
        const btnSubmit = document.getElementById('btnSubmitBobot');

        display.innerText = total.toFixed(2) + '%';
        bannerPercent.innerText = total.toFixed(2) + '%';

        const isExact100 = Math.abs(total - 100.0) <= 0.01;

        if (isExact100) {
            bannerPercent.className = "text-2xl font-black text-emerald-300";
            bannerBadge.className = "text-[11px] px-2.5 py-1 rounded-full font-bold whitespace-nowrap shrink-0 bg-emerald-500/30 text-emerald-200 border border-emerald-400/30";
            bannerBadge.innerText = "Tepat 100%";
            bannerNote.innerText = "Validasi terpenuhi. Siap disimpan & digunakan untuk penilaian.";

            alertBox.className = "p-3.5 rounded-xl mb-4 text-xs flex items-center justify-between bg-emerald-50 text-emerald-800 border border-emerald-200";
            alertIcon.className = "fa fa-check-circle text-emerald-600 text-base";
            alertText.innerText = "Validasi Terpenuhi: Total bobot tepat 100.00%.";

            btnSubmit.className = "px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-blue-600 hover:bg-blue-700 transition shadow-md border-none cursor-pointer";
            btnSubmit.disabled = false;
        } else {
            bannerPercent.className = "text-2xl font-black text-amber-300";
            bannerBadge.className = "text-[11px] px-2.5 py-1 rounded-full font-bold whitespace-nowrap shrink-0 bg-amber-500/30 text-amber-200 border border-amber-400/30";
            bannerBadge.innerText = "Belum 100%";
            bannerNote.innerText = "Wajib pas 100.00% sebelum dapat disimpan.";

            alertBox.className = "p-3.5 rounded-xl mb-4 text-xs flex items-center justify-between bg-rose-50 text-rose-800 border border-rose-200";
            alertIcon.className = "fa fa-exclamation-circle text-rose-600 text-base";
            alertText.innerText = `Total bobot asesmen belum 100.00% (saat ini: ${total.toFixed(2)}%). Silakan sesuaikan bobot hingga tepat 100%.`;

            btnSubmit.className = "px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-slate-400 transition shadow-none border-none cursor-not-allowed";
            btnSubmit.disabled = true;
        }
    }

    function validateKomponenForm(e) {
        const inputs = document.querySelectorAll('.bobot-input');
        let total = 0.0;
        inputs.forEach(inp => {
            total += parseFloat(inp.value) || 0.0;
        });

        if (Math.abs(total - 100.0) > 0.01) {
            if (window.SCDialog) {
                SCDialog.alert(`Total bobot komponen wajib tepat 100.00%! Saat ini bernilai ${total.toFixed(2)}%.`, { title: 'Validasi Bobot Asesmen', type: 'error' });
            } else {
                alert(`Total bobot komponen wajib tepat 100.00%! Saat ini bernilai ${total.toFixed(2)}%.`);
            }
            e.preventDefault();
            return false;
        }
        return true;
    }

    // Inisialisasi perhitungan saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        calculateTotalWeight();
    });
</script>
