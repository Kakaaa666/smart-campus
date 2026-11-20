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
    .obe-eval-table th, .obe-eval-table td {
        vertical-align: middle !important;
    }
    .score-input:focus {
        background-color: #ecfdf5 !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
    }
    .score-input.invalid-val {
        background-color: #fff1f2 !important;
        border-color: #f43f5e !important;
        color: #e11d48 !important;
    }
</style>

<div class="pcoded-content obe-content-wrapper">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Alert Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0">
                            <i class="fa fa-check"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm m-0">Berhasil Disimpan!</p>
                            <p class="text-xs m-0 mt-0.5 text-emerald-700"><?= $this->session->flashdata('success') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-emerald-500 hover:text-emerald-700 bg-transparent border-none cursor-pointer p-1" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold shrink-0">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm m-0">Pemberitahuan</p>
                            <p class="text-xs m-0 mt-0.5 text-rose-700"><?= $this->session->flashdata('error') ?></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700 bg-transparent border-none cursor-pointer p-1" onclick="this.parentElement.remove()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Header Banner Card -->
                <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-800 rounded-3xl p-6 text-white mb-6 shadow-lg relative overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <a href="<?= base_url('dosen/nilai') ?>" class="text-blue-200 hover:text-white text-xs font-semibold flex items-center gap-1 no-underline transition">
                                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Kelas
                                </a>
                                <span class="text-white/40">•</span>
                                <span class="bg-emerald-500/20 text-emerald-200 text-xs font-bold px-3 py-0.5 rounded-full border border-emerald-400/30 flex items-center gap-1">
                                    <i class="fa fa-check-circle"></i> RPS Published (Bobot 100%)
                                </span>
                                <span class="text-white/40">•</span>
                                <span class="text-blue-200 text-xs">Fase 4: Input Nilai Evaluasi per Sub-CPMK</span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight text-white m-0">
                                <?= html_escape($penawaran->kode_mk) ?> - <?= html_escape($penawaran->nama_mk) ?>
                            </h2>
                            <p class="text-blue-100 text-sm mt-1 max-w-2xl m-0">
                                Kelas <strong><?= html_escape($penawaran->kelas) ?></strong> &bull; Semester <?= $penawaran->semester ?> &bull; <?= $penawaran->sks ?> SKS &bull; Tahun Akademik <?= html_escape($penawaran->tahun_akademik) ?> (<?= html_escape($penawaran->semester_akademik) ?>)
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="<?= base_url('dosen/rps/detail/' . ($mata_kuliah ? $mata_kuliah->id : 1)) ?>" class="px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl text-xs font-semibold backdrop-blur-md transition flex items-center gap-1.5 border border-white/20 no-underline">
                                <i class="fa fa-book"></i> Desain RPS
                            </a>
                            <button type="button" onclick="submitEvaluasiForm()" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 border-none cursor-pointer">
                                <i class="fa fa-save"></i> Simpan Nilai
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk Alur Penilaian & Status Mahasiswa -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                    <div class="lg:col-span-2 bg-blue-50/70 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa fa-calculator"></i>
                        </div>
                        <div class="text-xs text-blue-900 leading-relaxed">
                            <strong class="font-bold block text-sm mb-0.5">Panduan Input Nilai Evaluasi Sub-CPMK:</strong>
                            Ketikkan skor mentah pada rentang <strong>0 – 100</strong> (boleh desimal). Jika dikosongkan, nilai otomatis bernilai <strong>0</strong>. 
                            Kolom <strong>Nilai Akhir</strong> dan <strong>Grade Mutu</strong> terhitung otomatis secara <em>real-time</em> berdasarkan formula bobot Sub-CPMK: 
                            <span class="font-mono font-semibold bg-white px-2 py-0.5 rounded border border-blue-200">&sum;(Skor &times; Bobot%)</span>.
                        </div>
                    </div>

                    <div class="bg-indigo-50/70 border border-indigo-200 rounded-2xl p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa fa-user-check"></i>
                        </div>
                        <div class="text-xs text-indigo-950 leading-relaxed">
                            <strong class="font-bold block text-sm mb-0.5">Validasi Mahasiswa KRS:</strong>
                            Menampilkan <strong><?= count($students) ?> mahasiswa</strong> yang mengambil MK ini dan KRS-nya <strong>telah disetujui Dosen Wali</strong>.
                            <?php if (!empty($pending_count) && $pending_count > 0): ?>
                            <span class="block text-amber-700 font-semibold mt-1">
                                <i class="fa fa-info-circle"></i> Catatan: Ada <?= $pending_count ?> mahasiswa yang KRS-nya belum disetujui.
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form Evaluasi Spreadsheet Grid -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 overflow-hidden">
                    <form action="<?= base_url('dosen/nilai/save_evaluasi') ?>" method="POST" id="formEvaluasi" class="m-0">
                        <input type="hidden" name="penawaran_id" value="<?= $penawaran->id ?>">

                        <!-- Action Toolbar -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Matriks Penilaian Sub-CPMK Mahasiswa</h3>
                                <p class="text-xs text-slate-500 mt-0.5 m-0">Total <?= count($students) ?> mahasiswa terdaftar dengan status KRS disetujui.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <input type="text" id="studentSearch" onkeyup="filterStudentGrid()" placeholder="Cari NIM atau nama..." class="text-xs px-3.5 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-44 md:w-56">
                                <button type="button" onclick="fillZeroes()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 border border-slate-200 cursor-pointer" title="Isi seluruh sel input kosong dengan angka 0">
                                    <i class="fa fa-magic"></i> Isi Kosong (0)
                                </button>
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5 border-none cursor-pointer">
                                    <i class="fa fa-save"></i> Simpan Nilai
                                </button>
                            </div>
                        </div>

                        <?php if (empty($sub_cpmks)): ?>
                        <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa fa-exclamation-triangle text-3xl mb-2 block text-amber-400"></i>
                            <p class="font-semibold text-slate-700 m-0">RPS & Sub-CPMK belum dirancang untuk mata kuliah ini.</p>
                            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                                Silakan rancang CPMK dan instrumen Sub-CPMK terlebih dahulu di menu Desain Pembelajaran & RPS.
                            </p>
                            <a href="<?= base_url('dosen/rps/detail/' . ($mata_kuliah ? $mata_kuliah->id : 1)) ?>" class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs no-underline shadow-sm">
                                <i class="fa fa-sliders"></i> Atur RPS Mata Kuliah
                            </a>
                        </div>
                        <?php elseif (empty($students)): ?>
                        <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa fa-users text-3xl mb-2 block text-slate-300"></i>
                            <p class="font-semibold text-slate-700 m-0">Belum ada mahasiswa dengan KRS berstatus DISETUJUI.</p>
                            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                                Mahasiswa akan otomatis muncul di tabel ini setelah pengajuan KRS pada kelas ini disetujui oleh Dosen Wali.
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="overflow-x-auto rounded-2xl border border-slate-200 obe-eval-table max-h-[70vh]">
                            <table class="w-full text-left border-collapse text-xs" id="evalTable">
                                <thead class="sticky top-0 z-20 bg-slate-100 shadow-sm">
                                    <tr class="text-slate-800 font-bold border-b border-slate-200">
                                        <th class="py-3 px-3 w-10 text-center sticky left-0 bg-slate-100 z-30">No</th>
                                        <th class="py-3 px-3 w-28 sticky left-10 bg-slate-100 z-30">NIM</th>
                                        <th class="py-3 px-4 min-w-[200px] sticky left-38 bg-slate-100 z-30">Nama Mahasiswa</th>
                                        
                                        <!-- Header Kolom Sub-CPMK: kode_sub, instrumen_evaluasi, bobot_nilai -->
                                        <?php foreach ($sub_cpmks as $sub): ?>
                                        <?php
                                            $kode_sub_label = !empty($sub->kode_sub) ? $sub->kode_sub : $sub->kode_sub_cpmk;
                                            $instrumen_label = !empty($sub->instrumen_evaluasi) ? $sub->instrumen_evaluasi : (!empty($sub->bentuk_evaluasi) ? $sub->bentuk_evaluasi : 'Tugas');
                                            $bobot_val = (float)$sub->bobot_nilai;
                                        ?>
                                        <th class="py-3 px-2.5 text-center min-w-[125px] bg-blue-50/70 border-l border-slate-200" title="<?= html_escape($sub->deskripsi ?? '') ?>">
                                            <span class="block font-mono font-bold text-xs text-blue-900"><?= html_escape($kode_sub_label) ?></span>
                                            <span class="text-[11px] font-semibold text-indigo-700 block mt-0.5"><?= html_escape($instrumen_label) ?></span>
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                Bobot: <?= number_format($bobot_val, 1) ?>%
                                            </span>
                                        </th>
                                        <?php endforeach; ?>

                                        <!-- Kolom Akhir (Read-only): Nilai Akhir (0-100) & Grade (A/B/C/D/E) -->
                                        <th class="py-3 px-3 text-center w-28 bg-indigo-50 text-indigo-900 border-l border-slate-200">
                                            Nilai Akhir
                                            <span class="block text-[10px] font-normal text-indigo-600">(0 - 100)</span>
                                        </th>
                                        <th class="py-3 px-2.5 text-center w-20 bg-indigo-50 text-indigo-900">
                                            Grade
                                            <span class="block text-[10px] font-normal text-indigo-600">(A - E)</span>
                                        </th>
                                        <th class="py-3 px-2 text-center w-16 bg-indigo-50 text-indigo-900">Indeks</th>
                                        <th class="py-3 px-3 text-center w-24 bg-indigo-50 text-indigo-900">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php $no = 1; foreach ($students as $st): ?>
                                    <tr class="hover:bg-slate-50/80 transition eval-row" data-krs-id="<?= $st->krs_id ?>" data-krs-detail-id="<?= $st->krs_detail_id ?>">
                                        <input type="hidden" name="krs_map[<?= $st->krs_detail_id ?>]" value="<?= $st->krs_id ?>">
                                        
                                        <td class="py-2.5 px-3 text-center text-slate-500 sticky left-0 bg-white z-10"><?= $no++ ?></td>
                                        <td class="py-2.5 px-3 font-mono font-bold text-slate-900 sticky left-10 bg-white z-10 student-nim">
                                            <?= html_escape($st->nim) ?>
                                        </td>
                                        <td class="py-2.5 px-4 font-semibold text-slate-800 sticky left-38 bg-white z-10 truncate max-w-[210px] student-name" title="<?= html_escape($st->nama_lengkap) ?>">
                                            <?= html_escape($st->nama_lengkap) ?>
                                        </td>

                                        <!-- Input Nilai per Sub-CPMK -->
                                        <?php foreach ($sub_cpmks as $colIdx => $sub): ?>
                                            <?php
                                                $key_k = $st->krs_id . '_' . $sub->id;
                                                $key_kd = $st->krs_detail_id . '_' . $sub->id;
                                                $val = isset($scores[$key_k]) ? (float)$scores[$key_k] : (isset($scores[$key_kd]) ? (float)$scores[$key_kd] : 0.0);
                                            ?>
                                            <td class="py-2 px-2 text-center border-l border-slate-100">
                                                <input type="number" 
                                                       step="any" 
                                                       min="0" 
                                                       max="100" 
                                                       name="scores[<?= $st->krs_detail_id ?>][<?= $sub->id ?>]" 
                                                       value="<?= $val > 0 ? (float)$val : '' ?>" 
                                                       placeholder="0" 
                                                       data-weight="<?= (float)$sub->bobot_nilai ?>" 
                                                       data-col-index="<?= $colIdx ?>"
                                                       oninput="recalcRow(this)" 
                                                       onkeydown="handleKeyNav(event, this)"
                                                       class="w-18 text-center text-xs font-mono font-bold px-2.5 py-1.5 border border-slate-200 rounded-lg score-input focus:outline-none transition">
                                            </td>
                                        <?php endforeach; ?>

                                        <!-- Kolom Akhir (Read-only): Nilai Akhir (0-100) & Grade (A/B/C/D/E) -->
                                        <td class="py-2.5 px-3 text-center border-l border-slate-200 font-mono font-bold text-sm text-indigo-700 row-final-score">
                                            <?= number_format($st->nilai_akhir_angka, 2) ?>
                                        </td>
                                        <td class="py-2.5 px-2.5 text-center font-bold text-xs row-grade-letter">
                                            <?php
                                                $huruf = $st->grade['huruf'] ?? 'E';
                                                $badgeStyle = in_array($huruf, ['A', 'A-']) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' :
                                                              (in_array($huruf, ['B+', 'B', 'B-']) ? 'bg-blue-100 text-blue-800 border-blue-300' :
                                                              (in_array($huruf, ['C+', 'C']) ? 'bg-amber-100 text-amber-800 border-amber-300' :
                                                              ($huruf === 'D' ? 'bg-orange-100 text-orange-800 border-orange-300' : 'bg-rose-100 text-rose-800 border-rose-300')));
                                            ?>
                                            <span class="inline-block px-2.5 py-0.5 rounded font-bold text-xs border <?= $badgeStyle ?>">
                                                <?= $huruf ?>
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-2 text-center font-mono font-semibold text-slate-700 row-grade-index">
                                            <?= number_format($st->grade['indeks'], 2) ?>
                                        </td>
                                        <td class="py-2.5 px-3 text-center row-status">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= ($st->grade['indeks'] >= 2.0) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' ?>">
                                                <?= ($st->grade['indeks'] >= 2.0) ? 'LULUS' : 'MENGULANG' ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <!-- Footer Actions -->
                        <div class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <span class="text-xs text-slate-500">
                                <i class="fa fa-info-circle text-blue-500"></i> Perubahan skor langsung dikalkulasi di kolom Nilai Akhir & Grade. Klik <strong>Simpan Nilai</strong> untuk menyimpan seluruh nilai evaluasi.
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="<?= base_url('dosen/nilai') ?>" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold no-underline transition">
                                    Batal
                                </a>
                                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 border-none cursor-pointer">
                                    <i class="fa fa-save"></i> Simpan Nilai
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Hitung otomatis Nilai Akhir (0-100) & Grade (A/B/C/D/E) secara Real-Time
     */
    function recalcRow(inputEl) {
        const tr = inputEl.closest('tr');
        if (!tr) return;

        let rawVal = parseFloat(inputEl.value);
        if (inputEl.value !== '' && (isNaN(rawVal) || rawVal < 0 || rawVal > 100)) {
            inputEl.classList.add('invalid-val');
        } else {
            inputEl.classList.remove('invalid-val');
        }

        const inputs = tr.querySelectorAll('input.score-input');
        let totalScore = 0.0;

        inputs.forEach(inp => {
            let val = parseFloat(inp.value);
            if (isNaN(val) || val < 0) {
                val = 0.0; // Default 0 jika kosong
            } else if (val > 100) {
                val = 100.0;
            }
            const weight = parseFloat(inp.getAttribute('data-weight')) || 0.0;
            totalScore += (val * (weight / 100.0));
        });

        const finalScore = Math.min(100.0, Math.max(0.0, totalScore));
        
        // Update Read-only Nilai Akhir (0-100)
        const finalScoreEl = tr.querySelector('.row-final-score');
        if (finalScoreEl) {
            finalScoreEl.innerText = finalScore.toFixed(2);
        }

        // Hitung Grade Huruf Mutu & Indeks
        let letter = 'E';
        let idx = '0.00';
        let pass = false;
        let badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';

        if (finalScore >= 85.0) { 
            letter = 'A'; idx = '4.00'; pass = true; 
            badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } else if (finalScore >= 80.0) { 
            letter = 'A-'; idx = '3.70'; pass = true; 
            badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } else if (finalScore >= 75.0) { 
            letter = 'B+'; idx = '3.30'; pass = true; 
            badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
        } else if (finalScore >= 70.0) { 
            letter = 'B'; idx = '3.00'; pass = true; 
            badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
        } else if (finalScore >= 65.0) { 
            letter = 'B-'; idx = '2.70'; pass = true; 
            badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
        } else if (finalScore >= 60.0) { 
            letter = 'C+'; idx = '2.30'; pass = true; 
            badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
        } else if (finalScore >= 55.0) { 
            letter = 'C'; idx = '2.00'; pass = true; 
            badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
        } else if (finalScore >= 40.0) { 
            letter = 'D'; idx = '1.00'; pass = false; 
            badgeClass = 'bg-orange-100 text-orange-800 border-orange-300';
        } else { 
            letter = 'E'; idx = '0.00'; pass = false; 
            badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
        }

        const letterEl = tr.querySelector('.row-grade-letter');
        if (letterEl) {
            letterEl.innerHTML = `<span class="inline-block px-2.5 py-0.5 rounded font-bold text-xs border ${badgeClass}">${letter}</span>`;
        }

        const gradeIndexEl = tr.querySelector('.row-grade-index');
        if (gradeIndexEl) {
            gradeIndexEl.innerText = idx;
        }

        const statusEl = tr.querySelector('.row-status');
        if (statusEl) {
            statusEl.innerHTML = `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${pass ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'}">${pass ? 'LULUS' : 'MENGULANG'}</span>`;
        }
    }

    /**
     * Navigasi keyboard mirip spreadsheet Excel (Panah Atas / Bawah / Enter)
     */
    function handleKeyNav(e, inputEl) {
        const colIdx = inputEl.getAttribute('data-col-index');
        const currentTr = inputEl.closest('tr');
        
        if (e.key === 'ArrowDown' || e.key === 'Enter') {
            e.preventDefault();
            const nextTr = currentTr.nextElementSibling;
            if (nextTr && nextTr.classList.contains('eval-row')) {
                const target = nextTr.querySelector(`input[data-col-index="${colIdx}"]`);
                if (target) { target.focus(); target.select(); }
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevTr = currentTr.previousElementSibling;
            if (prevTr && prevTr.classList.contains('eval-row')) {
                const target = prevTr.querySelector(`input[data-col-index="${colIdx}"]`);
                if (target) { target.focus(); target.select(); }
            }
        }
    }

    /**
     * Fitur kemudahan: Isi seluruh sel input kosong dengan angka 0
     */
    function fillZeroes() {
        const inputs = document.querySelectorAll('input.score-input');
        let filledCount = 0;
        inputs.forEach(inp => {
            if (inp.value === '' || inp.value === null) {
                inp.value = '0';
                recalcRow(inp);
                filledCount++;
            }
        });
        alert('Telah mengisi ' + filledCount + ' sel evaluasi yang kosong dengan angka 0.');
    }

    /**
     * Filter pencarian mahasiswa pada grid berdasarkan NIM atau Nama
     */
    function filterStudentGrid() {
        const input = document.getElementById("studentSearch");
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName("eval-row");

        for (let i = 0; i < rows.length; i++) {
            const nim = rows[i].querySelector(".student-nim").textContent.toLowerCase();
            const name = rows[i].querySelector(".student-name").textContent.toLowerCase();
            if (nim.indexOf(filter) > -1 || name.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    /**
     * Validasi sebelum submit form untuk memastikan tidak ada input > 100 atau < 0
     */
    function submitEvaluasiForm() {
        const inputs = document.querySelectorAll('input.score-input');
        let hasInvalid = false;

        inputs.forEach(inp => {
            const val = parseFloat(inp.value);
            if (inp.value !== '' && (isNaN(val) || val < 0 || val > 100)) {
                inp.classList.add('invalid-val');
                hasInvalid = true;
            }
        });

        if (hasInvalid) {
            alert('Perhatian: Terdapat nilai yang berada di luar rentang 0 sampai 100. Silakan periksa kembali sel input yang berwarna merah.');
            return false;
        }

        document.getElementById('formEvaluasi').submit();
    }
</script>
