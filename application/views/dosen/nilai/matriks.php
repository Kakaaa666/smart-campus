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

                <!-- Back button & actions -->
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <a href="<?= base_url('dosen/nilai') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-sm">
                        <i class="fa fa-arrow-left"></i> Kembali ke Daftar Kelas
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('dosen/nilai/setup_cpmk/' . $course->id) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-sm">
                            <i class="fa fa-sliders"></i> Ubah Setup CPMK &amp; Bobot
                        </a>
                    </div>
                </div>

                <!-- Course Detail Header Banner -->
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-white mb-5 sm:mb-6 shadow-xl relative overflow-hidden">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 sm:gap-5 relative z-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5">
                                <span class="bg-blue-500/30 text-blue-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-blue-400/20 whitespace-nowrap shrink-0">
                                    Matriks Penilaian Massal
                                </span>
                                <?php if ($is_obe): ?>
                                <span class="bg-emerald-500/30 text-emerald-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-emerald-400/20 whitespace-nowrap shrink-0">
                                    OBE Aktif (Total Bobot 100%)
                                </span>
                                <?php else: ?>
                                <span class="bg-amber-500/30 text-amber-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full border border-amber-400/20 whitespace-nowrap shrink-0">
                                    Mode Nilai Historis / Standar
                                </span>
                                <?php endif; ?>
                            </div>
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

                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/20 w-full sm:w-auto min-w-[200px] shrink-0 self-stretch sm:self-start xl:self-auto">
                            <span class="text-[11px] text-white/80 block uppercase font-bold tracking-wider mb-1">Peserta Kelas</span>
                            <div class="text-2xl font-black text-white">
                                <?= count($students) ?> <span class="text-xs font-normal text-blue-200">Mahasiswa (KRS)</span>
                            </div>
                            <span class="text-[11px] text-white/70 block mt-1">
                                <?= count($komponens) ?> Komponen Asesmen Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Adaptif untuk Mata Kuliah Tanpa Asesmen Lengkap -->
                <?php if (!$is_obe): ?>
                <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold shrink-0 mt-0.5">
                        <i class="fa fa-info"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold m-0 text-amber-900">Perhatian: Mode Nilai Historis</h4>
                        <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                            Mata kuliah ini belum memiliki konfigurasi komponen asesmen berbobot tepat 100% (data semester lampau atau belum diatur).
                            Tabel di bawah tetap menampilkan riwayat nilai mahasiswa yang tercatat pada <strong>khs_nilai</strong>.
                            Untuk mengaktifkan penilaian matriks berbasis CPMK, silakan klik tombol <strong>"Ubah Setup CPMK & Bobot"</strong> di atas.
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form Grid Matriks Nilai Mahasiswa -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 m-0">Grid Matriks Penilaian</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Input skor 0 - 100 pada setiap komponen. Nilai akhir dan konversi huruf dikalkulasi secara otomatis.</p>
                        </div>
                        <?php if ($is_obe): ?>
                        <div>
                            <button type="button" onclick="document.getElementById('formMatriksNilai').submit()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center gap-2 border-none cursor-pointer">
                                <i class="fa fa-save"></i> Simpan Seluruh Nilai ke KHS
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($students)): ?>
                    <div class="p-12 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <i class="fa fa-users text-4xl mb-2 block text-slate-300"></i>
                        Belum ada mahasiswa yang mengambil mata kuliah ini pada rencana studi (KRS berstatus DISETUJUI).
                    </div>
                    <?php else: ?>
                    <form action="<?= base_url('dosen/nilai/save_matriks') ?>" method="POST" id="formMatriksNilai">
                        <input type="hidden" name="penawaran_id" value="<?= $course->id ?>">

                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                                        <th class="py-3 px-3 w-10 text-center sticky left-0 bg-slate-100 z-10">No</th>
                                        <th class="py-3 px-3 w-28 sticky left-10 bg-slate-100 z-10">NIM</th>
                                        <th class="py-3 px-4 min-w-[200px]">Nama Mahasiswa</th>

                                        <?php if ($is_obe): ?>
                                            <?php foreach ($komponens as $kp): ?>
                                            <th class="py-3 px-3 text-center min-w-[130px] bg-blue-50/60 border-l border-slate-200">
                                                <span class="block font-bold text-slate-800 text-xs"><?= html_escape($kp->nama_asesmen) ?></span>
                                                <span class="text-[10px] text-blue-700 font-mono font-bold block mt-0.5">
                                                    <?= html_escape($kp->kode_cpmk) ?> &bull; <?= number_format($kp->bobot_persen, 0) ?>%
                                                </span>
                                            </th>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <th class="py-3 px-3 w-28 text-center bg-slate-100 border-l border-slate-200">Nilai Akhir</th>
                                        <th class="py-3 px-3 w-20 text-center bg-slate-100">Grade</th>
                                        <th class="py-3 px-3 w-20 text-center bg-slate-100">Indeks</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php $sno = 1; foreach ($students as $stu): ?>
                                    <tr class="hover:bg-slate-50/80 transition student-row" data-krsid="<?= $stu->krs_detail_id ?>">
                                        <td class="py-3 px-3 text-center text-slate-500 font-medium sticky left-0 bg-white"><?= $sno++ ?></td>
                                        <td class="py-3 px-3 font-mono font-bold text-slate-900 sticky left-10 bg-white">
                                            <?= html_escape($stu->nim) ?>
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">
                                            <?= html_escape($stu->nama_mahasiswa) ?>
                                            <span class="text-[10px] text-slate-400 block font-normal"><?= html_escape($stu->prodi_mahasiswa ?: 'Mahasiswa') ?></span>
                                        </td>

                                        <?php if ($is_obe): ?>
                                            <?php foreach ($komponens as $kp): ?>
                                            <?php
                                                $val = isset($stu->scores[$kp->id]) ? (float)$stu->scores[$kp->id] : '';
                                            ?>
                                            <td class="py-2 px-3 text-center border-l border-slate-100 bg-blue-50/20">
                                                <input type="number" step="0.01" min="0" max="100"
                                                       name="grades[<?= $stu->krs_detail_id ?>][<?= $kp->id ?>]"
                                                       value="<?= $val ?>"
                                                       placeholder="0 - 100"
                                                       data-bobot="<?= (float)$kp->bobot_persen ?>"
                                                       oninput="calculateStudentFinal(this)"
                                                       class="w-20 text-center font-bold text-xs px-2 py-1.5 border border-slate-200 rounded-lg focus:border-blue-500 focus:bg-white focus:outline-none transition score-input">
                                            </td>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <!-- Nilai Akhir -->
                                        <td class="py-3 px-3 text-center font-mono font-black text-sm text-slate-900 border-l border-slate-200 final-score-cell">
                                            <?= $stu->final_obe_numeric !== null ? number_format($stu->final_obe_numeric, 2) : '-' ?>
                                        </td>

                                        <!-- Grade Huruf -->
                                        <td class="py-3 px-3 text-center grade-cell">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-xs <?= (!empty($stu->final_obe_grade) && $stu->final_obe_grade !== '-') ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' ?>">
                                                <?= $stu->final_obe_grade ?: '-' ?>
                                            </span>
                                        </td>

                                        <!-- Indeks -->
                                        <td class="py-3 px-3 text-center font-bold text-slate-600 index-cell">
                                            <?= $stu->final_obe_index !== null ? number_format((float)$stu->final_obe_index, 2) : '0.00' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($is_obe): ?>
                        <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 flex-wrap gap-4">
                            <div class="text-xs text-slate-500">
                                <i class="fa fa-info-circle text-blue-500 mr-1"></i> Menyimpan nilai otomatis meng-update tabel <strong>nilai_cpmk</strong> dan memperbarui nilai akhir di <strong>khs_nilai</strong>.
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center gap-2 border-none cursor-pointer">
                                <i class="fa fa-save"></i> Simpan Seluruh Nilai ke KHS
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function calculateStudentFinal(input) {
        const row = input.closest('.student-row');
        const scoreInputs = row.querySelectorAll('.score-input');
        let totalScore = 0.0;
        let hasAnyInput = false;

        scoreInputs.forEach(inp => {
            const val = parseFloat(inp.value);
            const bobot = parseFloat(inp.getAttribute('data-bobot')) || 0.0;
            if (!isNaN(val)) {
                hasAnyInput = true;
                totalScore += (val * (bobot / 100.0));
            }
        });

        const finalCell = row.querySelector('.final-score-cell');
        const gradeCell = row.querySelector('.grade-cell');
        const indexCell = row.querySelector('.index-cell');

        if (hasAnyInput) {
            const finalNum = Math.round(totalScore * 100) / 100;
            finalCell.innerText = finalNum.toFixed(2);

            const gradeInfo = convertScoreToGrade(finalNum);
            gradeCell.innerHTML = `<span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-xs bg-emerald-100 text-emerald-800">${gradeInfo.huruf}</span>`;
            indexCell.innerText = gradeInfo.indeks.toFixed(2);
        } else {
            finalCell.innerText = '-';
            gradeCell.innerHTML = `<span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-xs bg-slate-100 text-slate-500">-</span>`;
            indexCell.innerText = '0.00';
        }
    }

    function convertScoreToGrade(score) {
        if (score >= 85.0) return { huruf: 'A',  indeks: 4.0 };
        if (score >= 80.0) return { huruf: 'A-', indeks: 3.75 };
        if (score >= 75.0) return { huruf: 'B+', indeks: 3.5 };
        if (score >= 70.0) return { huruf: 'B',  indeks: 3.0 };
        if (score >= 65.0) return { huruf: 'B-', indeks: 2.75 };
        if (score >= 60.0) return { huruf: 'C+', indeks: 2.5 };
        if (score >= 55.0) return { huruf: 'C',  indeks: 2.0 };
        if (score >= 45.0) return { huruf: 'D',  indeks: 1.0 };
        return { huruf: 'E', indeks: 0.0 };
    }
</script>
