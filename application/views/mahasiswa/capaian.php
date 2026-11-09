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

<!-- Chart.js CDN untuk Radar Chart Visualisasi CPL -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .accordion-content {
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Header / Student Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <?php
                                    $foto_file = !empty($student->foto) ? $student->foto : 'avatar-4.png';
                                    $foto_url = base_url('assets/images/' . $foto_file);
                                ?>
                                <img src="<?= $foto_url ?>" alt="Foto Mahasiswa" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-500 shadow-md">
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[9px] text-white font-bold" title="Mahasiswa Aktif">
                                    <i class="fa fa-check"></i>
                                </span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-xl font-bold text-slate-900 m-0">
                                        <?= html_escape($student->nama_lengkap) ?>
                                    </h2>
                                    <span class="bg-blue-50 text-blue-700 text-xs font-mono font-bold px-2.5 py-0.5 rounded-lg border border-blue-200">
                                        NIM: <?= html_escape($student->nim) ?>
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-3 flex-wrap">
                                    <span><i class="fa fa-university text-slate-400"></i> <?= html_escape($student->prodi ?? ($prodi ? $prodi->nama_prodi : 'Teknik Informatika')) ?></span>
                                    <span>&bull;</span>
                                    <span><i class="fa fa-clock-o text-slate-400"></i> Semester <?= $student->semester ?? 1 ?> (Aktif)</span>
                                    <span>&bull;</span>
                                    <span><i class="fa fa-check-circle text-emerald-600"></i> SKS Selesai: <strong class="text-slate-800"><?= (int)($total_sks_lulus ?? 63) ?> SKS</strong></span>
                                    <?php if (!empty($total_sks_ongoing)): ?>
                                    <span>&bull;</span>
                                    <span><i class="fa fa-spinner text-amber-500"></i> Sedang Ditempuh: <strong class="text-amber-700"><?= (int)$total_sks_ongoing ?> SKS</strong></span>
                                    <?php endif; ?>
                                    <span>&bull;</span>
                                    <span><i class="fa fa-star text-amber-500"></i> IPK: <strong class="text-slate-800"><?= number_format((float)($student->ipk ?? 3.5), 2) ?></strong></span>
                                </p>
                            </div>
                        </div>

                        <!-- Filter Switcher (Admin / Dosen Mode) -->
                        <?php if (($is_admin || $is_dosen) && !empty($all_students)): ?>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 flex items-center gap-2">
                            <span class="text-xs text-slate-600 font-semibold whitespace-nowrap">Lihat Mahasiswa:</span>
                            <form method="GET" class="m-0">
                                <?php if ($filter_semester !== 'all'): ?>
                                <input type="hidden" name="semester" value="<?= $filter_semester ?>">
                                <?php endif; ?>
                                <select name="student_id" onchange="this.form.submit()" class="text-xs bg-white text-slate-800 border border-slate-300 rounded-xl px-3 py-1.5 outline-none cursor-pointer">
                                    <?php foreach ($all_students as $as): ?>
                                    <option value="<?= $as->id ?>" <?= ($selected_student_id == $as->id) ? 'selected' : '' ?>>
                                        <?= html_escape($as->nim . ' - ' . $as->nama_lengkap) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Filter Semester Bar -->
                    <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-600 mr-2 flex items-center gap-1.5">
                                <i class="fa fa-filter text-blue-600"></i> Filter Cakupan Transkrip:
                            </span>

                            <!-- Option Kumulatif -->
                            <?php
                                $kumulatifUrl = base_url('mahasiswa/capaian?semester=all') . (($is_admin || $is_dosen) ? '&student_id=' . $selected_student_id : '');
                                $isKumulatif = ($filter_semester === 'all');
                            ?>
                            <a href="<?= $kumulatifUrl ?>" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 <?= $isKumulatif ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                                <i class="fa fa-globe"></i> Kumulatif (Semua Semester)
                            </a>

                            <!-- Semester Specific -->
                            <?php
                                $semestersToShow = !empty($available_semesters) ? $available_semesters : [1, 2, 3, 4];
                            ?>
                            <?php foreach ($semestersToShow as $sem): ?>
                                <?php
                                    $semUrl = base_url('mahasiswa/capaian?semester=' . $sem) . (($is_admin || $is_dosen) ? '&student_id=' . $selected_student_id : '');
                                    $isSemActive = ($filter_semester === (int)$sem);
                                    $isOngoingBtn = ((int)$sem === (int)($student->semester ?? 4));
                                ?>
                                <a href="<?= $semUrl ?>" class="px-3 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap shrink-0 inline-flex items-center gap-1 <?= $isSemActive ? 'bg-blue-600 text-white shadow-sm' : ($isOngoingBtn ? 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') ?>">
                                    Semester <?= $sem ?> <?= $isOngoingBtn ? '<span class="text-[10px] opacity-90 font-medium">(Berjalan)</span>' : '' ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <div class="text-xs text-slate-500 font-medium">
                            Menampilkan data: <strong class="text-blue-700"><?= $isKumulatif ? 'Seluruh Semester (Kumulatif 1 s/d ' . ($student->semester ?? 4) . ')' : 'Semester ' . $filter_semester . ($is_semester_ongoing ? ' (Sedang Berjalan)' : '') ?></strong>
                        </div>
                    </div>
                </div>

                <!-- BANNER NOTIFIKASI SEMESTER BERJALAN -->
                <?php if ($is_semester_ongoing): ?>
                <div class="mb-6 p-4 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-900 flex items-start gap-3.5 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-amber-200/80 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa fa-clock-o text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold m-0 text-amber-950 flex items-center gap-2">
                            <span>Semester <?= $filter_semester ?> Merupakan Semester Berjalan (Sedang Ditempuh)</span>
                            <span class="bg-amber-200 text-amber-900 text-[10px] font-bold px-2 py-0.5 rounded-full">Belum Selesai</span>
                        </h4>
                        <p class="text-xs text-amber-800 mt-1 mb-0 leading-relaxed">
                            Mahasiswa baru saja memasuki Semester <?= $filter_semester ?> (beban terdaftar <strong><?= (int)($total_sks_ongoing ?? 19) ?> SKS</strong>). 
                            Perkuliahan dan proses penilaian oleh dosen pengampu saat ini masih berlangsung. Nilai akhir mata kuliah, KHS resmi, serta evaluasi CPL final akan diterbitkan secara otomatis setelah masa perkuliahan dan ujian akhir semester diselesaikan.
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- SECTION 1: RADAR CHART CPL & PROGRES KOMPETENSI (SIDE-BY-SIDE) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

                    <!-- RADAR CHART (5 COLS) -->
                    <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-bold text-slate-900 m-0">Radar Ketercapaian CPL</h3>
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200">
                                    Target Ambang: 70.0
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                Poligon visualisasi profil kompetensi mahasiswa terhadap Capaian Pembelajaran Lulusan program studi.
                            </p>
                        </div>

                        <!-- Canvas Radar Chart -->
                        <div class="relative w-full h-72 flex flex-col items-center justify-center my-auto">
                            <?php if ($is_semester_ongoing): ?>
                            <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/60 backdrop-blur-[1px] p-4 text-center rounded-xl">
                                <span class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-lg mb-2 shadow-sm">
                                    <i class="fa fa-hourglass-half"></i>
                                </span>
                                <strong class="text-xs text-amber-950 font-bold">Perkuliahan Semester <?= $filter_semester ?> Masih Berjalan</strong>
                                <small class="text-[11px] text-slate-500 max-w-xs mt-1">Nilai CPL final semester ini akan terbit setelah evaluasi akhir semester selesai.</small>
                            </div>
                            <?php endif; ?>
                            <canvas id="cplRadarChart"></canvas>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-around text-xs text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                                <span>Capaian Mahasiswa</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
                                <span>Target Minimal (70)</span>
                            </div>
                        </div>
                    </div>

                    <!-- CPL COMPETENCY SCORE BREAKDOWN (7 COLS) -->
                    <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 m-0">Rincian Capaian per Dimensi CPL</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <?= $is_semester_ongoing ? 'Status pemenuhan CPL pada semester berjalan yang sedang ditempuh.' : 'Nilai rata-rata akumulatif yang diperoleh dari seluruh instrumen CPMK mata kuliah terkait.' ?>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                            <?php if (empty($cpls)): ?>
                            <div class="p-6 text-center text-slate-400">
                                Tidak ada data CPL yang terdaftar.
                            </div>
                            <?php else: ?>
                                <?php foreach ($cpls as $c): ?>
                                <?php
                                    $isOngoingCpl = !empty($c['is_ongoing']);
                                    $score = $c['skor'] !== null ? (float)$c['skor'] : null;
                                    $is_passed = ($score !== null && $score >= 70.0);
                                    $barColor = $isOngoingCpl 
                                        ? 'bg-amber-300' 
                                        : ($is_passed ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-amber-500 to-rose-500');
                                ?>
                                <div class="p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 bg-slate-50/50 hover:bg-white transition duration-150">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-mono font-bold text-xs rounded-md">
                                                <?= html_escape($c['kode_cpl']) ?>
                                            </span>
                                            <span class="text-[11px] font-semibold text-slate-500">
                                                <?= html_escape($c['kategori']) ?>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <?php if ($isOngoingCpl): ?>
                                                <span class="text-xs font-bold text-amber-700">
                                                    <i class="fa fa-clock-o mr-1"></i> Sedang Berjalan
                                                </span>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800">
                                                    Dalam Perkuliahan
                                                </span>
                                            <?php else: ?>
                                                <span class="text-xs font-black <?= $is_passed ? 'text-emerald-600' : 'text-amber-600' ?>">
                                                    <?= number_format((float)$score, 1) ?> / 100
                                                </span>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold <?= $is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                                                    <?= $is_passed ? 'Tercapai' : 'Perlu Peningkatan' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden my-2">
                                        <div class="<?= $barColor ?> h-2 rounded-full transition-all duration-700" style="width: <?= $isOngoingCpl ? '25%' : min(100, max(0, (float)$score)) ?>%;"></div>
                                    </div>
                                    <p class="text-[11px] text-slate-600 leading-relaxed m-0">
                                        <?= html_escape($c['deskripsi']) ?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- SECTION 2: ACCORDION MATA KULIAH & PROGRES CPMK -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 m-0">Transkrip Mata Kuliah &amp; Rincian CPMK</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Klik pada mata kuliah untuk membuka breakdown kalkulasi nilai CPMK dan komponen asesmen individual.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                                <i class="fa fa-check-circle"></i> Selesai (KHS Resmi)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-semibold border border-amber-200">
                                <i class="fa fa-clock-o"></i> Sedang Ditempuh
                            </span>
                        </div>
                    </div>

                    <?php if (empty($courses)): ?>
                    <div class="p-12 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <i class="fa fa-book text-4xl mb-2 block text-slate-300"></i>
                        Tidak ada mata kuliah yang terdaftar pada filter semester ini.
                    </div>
                    <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($courses as $idx => $cs): ?>
                        <?php $isOngoingCourse = !empty($cs->is_ongoing); ?>
                        <div class="border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 transition duration-150">
                            <!-- Accordion Header -->
                            <button type="button" onclick="toggleCourseAccordion(<?= $idx ?>)" class="w-full text-left p-4 bg-slate-50/60 hover:bg-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 transition border-none cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <?php if ($isOngoingCourse): ?>
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-bold text-sm shrink-0" title="Sedang Ditempuh (Belum Selesai)">
                                        <i class="fa fa-clock-o text-base"></i>
                                    </div>
                                    <?php else: ?>
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        <?= html_escape($cs->final_grade ?: '-') ?>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold text-xs text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                <?= html_escape($cs->kode_mk) ?>
                                            </span>
                                            <h4 class="text-sm font-bold text-slate-900 m-0">
                                                <?= html_escape($cs->nama_mk) ?>
                                            </h4>
                                            <?php if ($isOngoingCourse): ?>
                                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200">
                                                Sedang Ditempuh
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-3 flex-wrap">
                                            <span><?= $cs->sks ?> SKS</span>
                                            <span>&bull;</span>
                                            <span>Semester <?= $cs->semester ?></span>
                                            <span>&bull;</span>
                                            <span>Kelas <?= html_escape($cs->kelas) ?></span>
                                            <span>&bull;</span>
                                            <span>Dosen: <?= html_escape($cs->nama_dosen ?: 'Dosen Pengampu') ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 shrink-0">
                                    <div class="text-right">
                                        <?php if ($isOngoingCourse): ?>
                                        <span class="text-xs font-bold text-amber-700 block">
                                            Sedang Ditempuh
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">Belum Terbit KHS</span>
                                        <?php else: ?>
                                        <span class="text-xs font-bold text-slate-900 block">
                                            Nilai: <?= $cs->final_score !== null ? number_format((float)$cs->final_score, 2) : '-' ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">Indeks: <?= number_format((float)$cs->final_index, 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($isOngoingCourse): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            Dalam Perkuliahan
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <?= count($cs->cpmk_details) ?> CPMK OBE
                                        </span>
                                    <?php endif; ?>
                                    <div class="w-7 h-7 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 text-xs accordion-arrow-<?= $idx ?> transition-transform">
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                            </button>

                            <!-- Accordion Body -->
                            <div id="courseAccordionContent-<?= $idx ?>" class="hidden p-5 bg-white border-t border-slate-200">
                                <?php if ($isOngoingCourse): ?>
                                    <!-- Status Info Perkuliahan Berjalan -->
                                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-center gap-2">
                                        <i class="fa fa-info-circle text-amber-600"></i>
                                        <span>Mata kuliah ini <strong>sedang ditempuh pada Semester <?= $cs->semester ?></strong>. Komponen asesmen di bawah merupakan rencana evaluasi pembelajaran yang akan dinilai oleh dosen pengampu selama semester berjalan.</span>
                                    </div>
                                <?php else: ?>
                                    <!-- Formula Header -->
                                    <div class="mb-4 p-3 bg-blue-50/70 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-center gap-2">
                                        <i class="fa fa-calculator text-blue-600"></i>
                                        <span>Rumus Standar OBE: <strong>Capaian CPMK = &sum;(Nilai Komponen &times; Bobot) &divide; Total Bobot CPMK</strong></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($cs->cpmk_details)): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php foreach ($cs->cpmk_details as $cpmk): ?>
                                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/40">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white font-mono font-bold text-xs">
                                                    <?= html_escape($cpmk['kode_cpmk']) ?>
                                                </span>
                                                <span class="text-xs font-black text-slate-800">
                                                    Capaian: 
                                                    <?php if ($cpmk['capaian'] !== null): ?>
                                                        <span class="text-blue-600"><?= number_format($cpmk['capaian'], 2) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-amber-600 font-semibold">Proses Perkuliahan</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                                <?= html_escape($cpmk['deskripsi']) ?>
                                            </p>

                                            <!-- Rincian Nilai per Komponen Asesmen -->
                                            <div class="border-t border-slate-200 pt-2 space-y-1.5">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Komponen Pengukur:</span>
                                                <?php foreach ($cpmk['komponen'] as $cmp): ?>
                                                <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100 last:border-b-0">
                                                    <span class="text-slate-700">
                                                        <?= html_escape($cmp['nama_asesmen']) ?>
                                                        <span class="text-[10px] text-slate-400">(<?= number_format($cmp['bobot_persen'], 0) ?>%)</span>
                                                    </span>
                                                    <?php if ($cmp['nilai'] !== null): ?>
                                                    <span class="font-mono font-bold text-slate-900">
                                                        <?= number_format((float)$cmp['nilai'], 2) ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                        Belum Dinilai
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Chart.js Radar Chart
    document.addEventListener("DOMContentLoaded", function () {
        const labels = <?= json_encode($chart_labels) ?>;
        const dataValues = <?= json_encode($chart_values) ?>;

        const ctx = document.getElementById('cplRadarChart').getContext('2d');

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Capaian Mahasiswa',
                        data: dataValues,
                        backgroundColor: 'rgba(37, 99, 235, 0.25)',
                        borderColor: '#2563eb',
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#2563eb',
                        borderWidth: 2,
                    },
                    {
                        label: 'Batas Minimal (70)',
                        data: labels.map(() => 70),
                        backgroundColor: 'transparent',
                        borderColor: '#34d399',
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
                        angleLines: {
                            color: '#e2e8f0'
                        },
                        grid: {
                            color: '#f1f5f9'
                        },
                        pointLabels: {
                            font: {
                                size: 11,
                                weight: 'bold',
                                family: "'Open Sans', sans-serif"
                            },
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.r.toFixed(1) + ' / 100';
                            }
                        }
                    }
                }
            }
        });
    });

    // Accordion Toggle
    function toggleCourseAccordion(idx) {
        const content = document.getElementById('courseAccordionContent-' + idx);
        const arrow = document.querySelector('.accordion-arrow-' + idx);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }
</script>
