<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models/shared/M_akademik.php';

class M_akademik_mahasiswa extends M_akademik
{
    /**
     * Mengambil seluruh data ringkasan akademis mahasiswa yang disinkronkan secara real-time
     * dengan database (khs_nilai, akun_mahasiswa, krs, penawaran_matakuliah, dosen_wali).
     *
     * @param int $akun_id
     * @return array|null
     */
    public function get_student_ringkasan_data($akun_id)
    {
        $this->ensure_schema();

        $student = $this->get_account($akun_id);
        if (!$student) {
            return null;
        }

        // Normalisasi dan proteksi properti default mahasiswa (mencegah PHP Notice)
        $student->nim             = isset($student->nim) ? $student->nim : '';
        $student->nama_lengkap    = isset($student->nama_lengkap) ? $student->nama_lengkap : 'Mahasiswa';
        $student->email           = !empty($student->email) ? $student->email : ($student->nim . '@mhs.campuss.ac.id');
        $student->telepon         = !empty($student->telepon) ? $student->telepon : '081234567890';
        $student->foto            = !empty($student->foto) ? $student->foto : 'avatar-4.png';
        $student->status_akademik = !empty($student->status_akademik) ? $student->status_akademik : 'Aktif';
        $student->alamat          = !empty($student->alamat) ? $student->alamat : '';
        $student->ipk             = isset($student->ipk) ? (float)$student->ipk : 0.00;
        $student->semester        = isset($student->semester) ? (int)$student->semester : 1;
        $student->angkatan        = !empty($student->angkatan) ? $student->angkatan : '2024';
        $student->prodi           = !empty($student->prodi) ? $student->prodi : 'Sistem Informasi';
        $student->fakultas        = !empty($student->fakultas) ? $student->fakultas : 'Fakultas Teknologi Informasi';
        $student->jenjang         = !empty($student->jenjang) ? $student->jenjang : 'Strata 1';

        // Ambil data dosen wali
        $advisor = $this->get_advisor_for_student($akun_id);
        $dosen_wali = $advisor ? $advisor->nama_lengkap : 'Sugeng Widodo, S.Kom., M.Kom';
        $nip_dosen_wali = $advisor ? $advisor->nim : '-';

        // Ambil seluruh data khs_nilai
        $khs_rows = $this->db->where('akun_id', (int)$akun_id)
                             ->order_by('semester_mhs', 'ASC')
                             ->order_by('id', 'ASC')
                             ->get('khs_nilai')
                             ->result();

        // Kelompokkan per semester
        $semesters = [];
        $total_sks_lulus = 0;
        $total_sks_ditempuh = 0;
        $total_bobot = 0;
        $total_mk = count($khs_rows);

        $grade_counts = [
            'A'  => 0,
            'B+' => 0,
            'B'  => 0,
            'C+' => 0,
            'C'  => 0,
            'D'  => 0,
            'E'  => 0,
        ];

        foreach ($khs_rows as $row) {
            $smt = (int)$row->semester_mhs;
            if (!isset($semesters[$smt])) {
                $semesters[$smt] = [
                    'semester_mhs'      => $smt,
                    'tahun_akademik'    => $row->tahun_akademik,
                    'semester_akademik' => $row->semester_akademik,
                    'courses'           => [],
                    'sks_smt'           => 0,
                    'bobot_smt'         => 0,
                ];
            }
            $semesters[$smt]['courses'][] = $row;
            $semesters[$smt]['sks_smt'] += (int)$row->sks;
            $semesters[$smt]['bobot_smt'] += (float)$row->nxk;

            $total_sks_ditempuh += (int)$row->sks;
            $total_bobot += (float)$row->nxk;

            // Cek kelulusan (nilai bukan E dan TL)
            $huruf = strtoupper(trim($row->nilai_huruf));
            if ($huruf !== 'E' && $huruf !== 'TL') {
                $total_sks_lulus += (int)$row->sks;
            }

            if (isset($grade_counts[$huruf])) {
                $grade_counts[$huruf]++;
            } else {
                $grade_counts[$huruf] = 1;
            }
        }

        // Hitung kumulatif & IPS per semester untuk grafik & tabel
        $chart_labels = [];
        $chart_ips = [];
        $chart_ipk = [];
        $semester_details = [];

        $running_sks = 0;
        $running_bobot = 0;

        $max_ips = 0;
        $max_ips_period = '-';
        $min_ips = 4.0;
        $min_ips_period = '-';
        $total_ips_sum = 0;

        ksort($semesters);
        foreach ($semesters as $smt_num => $data_smt) {
            $sks_smt = $data_smt['sks_smt'];
            $bobot_smt = $data_smt['bobot_smt'];
            $ips = $sks_smt > 0 ? round($bobot_smt / $sks_smt, 2) : 0.00;

            $running_sks += $sks_smt;
            $running_bobot += $bobot_smt;
            $cum_ipk = $running_sks > 0 ? round($running_bobot / $running_sks, 2) : 0.00;

            $label = $data_smt['tahun_akademik'] . ' ' . $data_smt['semester_akademik'];
            $chart_labels[] = $label;
            $chart_ips[] = $ips;
            $chart_ipk[] = $cum_ipk;

            $semester_details[] = [
                'semester_mhs'      => $smt_num,
                'tahun_akademik'    => $data_smt['tahun_akademik'],
                'semester_akademik' => $data_smt['semester_akademik'],
                'label'             => $label,
                'sks'               => $sks_smt,
                'ips'               => $ips,
                'ipk'               => $cum_ipk,
                'status'            => 'Aktif'
            ];

            if ($ips >= $max_ips) {
                $max_ips = $ips;
                $max_ips_period = $label;
            }
            if ($ips <= $min_ips && $sks_smt > 0) {
                $min_ips = $ips;
                $min_ips_period = $label;
            }
            $total_ips_sum += $ips;
        }

        $grand_ipk = $total_sks_ditempuh > 0 ? round($total_bobot / $total_sks_ditempuh, 2) : (float)($student->ipk ?: 0.00);
        $avg_ips = count($semesters) > 0 ? round($total_ips_sum / count($semesters), 2) : $grand_ipk;
        if ($min_ips > 4.0) {
            $min_ips = $grand_ipk;
        }

        // Susun Riwayat Status Akademis per Semester (dari semester 1 s/d semester aktif mahasiswa)
        $riwayat_status = [];
        foreach ($semester_details as $sd) {
            $riwayat_status[] = [
                'tahun'        => $sd['tahun_akademik'],
                'periode'      => $sd['semester_akademik'],
                'status'       => 'Aktif',
                'semester_mhs' => $sd['semester_mhs']
            ];
        }

        // Tambahkan semester berjalan jika mahasiswa sudah berada di semester berikutnya
        $cur_semester = max(1, (int)($student->semester ?: 1));
        if ($cur_semester > count($semesters)) {
            $latest_sd = !empty($semester_details) ? end($semester_details) : null;
            $next_tahun = $latest_sd ? (int)$latest_sd['tahun_akademik'] : (int)date('Y');
            $next_period = ($latest_sd && $latest_sd['semester_akademik'] === 'Ganjil') ? 'Genap' : 'Ganjil';
            if ($latest_sd && $latest_sd['semester_akademik'] === 'Genap') {
                $next_tahun++;
            }

            $krs_active = $this->db->where('akun_id', (int)$akun_id)->order_by('id', 'DESC')->get('krs')->row();
            if ($krs_active) {
                $tahun_parts = explode('/', $krs_active->tahun_akademik);
                $tahun_str = $tahun_parts[0];
                $riwayat_status[] = [
                    'tahun'        => $tahun_str ?: (string)$next_tahun,
                    'periode'      => $krs_active->semester_akademik ?: $next_period,
                    'status'       => 'Aktif',
                    'semester_mhs' => $cur_semester
                ];
            } else {
                $riwayat_status[] = [
                    'tahun'        => (string)$next_tahun,
                    'periode'      => $next_period,
                    'status'       => 'Aktif',
                    'semester_mhs' => $cur_semester
                ];
            }
        }

        // Susun Tabel Statistik Nilai Lengkap (Per Term / Semester & Kumulatif)
        $statistik_table = [];
        $cum_mk = 0;
        $cum_sks_a = 0;
        $cum_sks_l = 0;
        $cum_mutu = 0;

        foreach ($semesters as $smt_num => $data_smt) {
            $smt_mk = count($data_smt['courses']);
            $smt_sks_a = (int)$data_smt['sks_smt'];
            $smt_mutu = (float)$data_smt['bobot_smt'];

            // SKS Lulus: yang nilainya >= C (nilai_huruf bukan D, E, TL)
            $smt_sks_l = 0;
            foreach ($data_smt['courses'] as $c) {
                $h = strtoupper(trim($c->nilai_huruf));
                if (!in_array($h, ['D', 'E', 'TL'], true)) {
                    $smt_sks_l += (int)$c->sks;
                }
            }

            $smt_ip = $smt_sks_a > 0 ? round($smt_mutu / $smt_sks_a, 2) : 0.00;

            $cum_mk += $smt_mk;
            $cum_sks_a += $smt_sks_a;
            $cum_sks_l += $smt_sks_l;
            $cum_mutu += $smt_mutu;
            $cum_ipk = $cum_sks_a > 0 ? round($cum_mutu / $cum_sks_a, 2) : 0.00;

            $statistik_table[] = [
                'tahun'        => $data_smt['tahun_akademik'],
                'semester'     => $data_smt['semester_akademik'],
                'semester_mhs' => $smt_num,
                'mk'           => $smt_mk,
                'sks_a'        => $smt_sks_a,
                'sks_l'        => $smt_sks_l,
                'mutu'         => $smt_mutu,
                'ip'           => $smt_ip,
                'cum_sks_a'    => $cum_sks_a,
                'cum_sks_l'    => $cum_sks_l,
                'cum_mutu'     => $cum_mutu,
                'cum_ipk'      => $cum_ipk,
                'cum_sks_dpo'  => $cum_sks_l,
                'is_ongoing'   => false,
            ];
        }

        // Jika mahasiswa memiliki semester aktif / berjalan (dari KRS)
        $cur_krs = $this->get_krs($akun_id, $this->get_active_period(), 'DISETUJUI');
        if ($cur_krs && !empty($cur_krs->mata_kuliah)) {
            $krs_tahun_prefix = explode('/', $cur_krs->tahun_akademik)[0];
            $already_has_khs = false;
            foreach ($statistik_table as $st_row) {
                if (strpos($st_row['tahun'], $krs_tahun_prefix) !== false && 
                    strtolower($st_row['semester']) === strtolower($cur_krs->semester_akademik)) {
                    $already_has_khs = true;
                    break;
                }
            }

            if (!$already_has_khs) {
                $krs_mk_count = count($cur_krs->mata_kuliah);
                $actual_krs_sks = array_sum(array_map(function($c) { return (int)($c->sks ?? 0); }, $cur_krs->mata_kuliah));
                $krs_sks = ($actual_krs_sks > 0) ? $actual_krs_sks : (int)$cur_krs->total_sks;
                $tot_sks_with_krs = $cum_sks_a + $krs_sks;

                $statistik_table[] = [
                    'tahun'        => $krs_tahun_prefix,
                    'semester'     => $cur_krs->semester_akademik,
                    'semester_mhs' => max(1, (int)($student->semester ?: 1)),
                    'mk'           => $krs_mk_count,
                    'sks_a'        => $krs_sks,
                    'sks_l'        => 0,
                    'mutu'         => 0.0,
                    'ip'           => 0,
                    'cum_sks_a'    => $tot_sks_with_krs,
                    'cum_sks_l'    => '',
                    'cum_mutu'     => '',
                    'cum_ipk'      => 0,
                    'cum_sks_dpo'  => '',
                    'is_ongoing'   => true,
                ];
            }
        }

        return [
            'student'            => $student,
            'dosen_wali'         => $dosen_wali,
            'nip_dosen_wali'     => $nip_dosen_wali,
            'sks_lulus'          => $total_sks_lulus,
            'total_sks'          => $total_sks_ditempuh,
            'total_bobot'        => $total_bobot,
            'total_mk'           => $total_mk,
            'ipk'                => $grand_ipk,
            'status_akademis'    => $student->status_akademik ?: 'Aktif',
            'riwayat_status'     => $riwayat_status,
            'statistik_table'    => $statistik_table,
            'chart_data'         => [
                'labels' => $chart_labels,
                'ips'    => $chart_ips,
                'ipk'    => $chart_ipk,
            ],
            'semester_details'   => $semester_details,
            'rangkuman'          => [
                'max_ips'        => $max_ips,
                'max_ips_period' => $max_ips_period,
                'min_ips'        => $min_ips,
                'min_ips_period' => $min_ips_period,
                'avg_ips'        => $avg_ips,
                'ipk'            => $grand_ipk,
                'sks_lulus'      => $total_sks_lulus,
                'total_mk'       => $total_mk,
            ],
            'grade_distribution' => $grade_counts
        ];
    }

    public function get_student_kehadiran_data($akun_id, $selected_period = null)
    {
        $account = $this->get_account($akun_id);
        if (!$account) {
            return null;
        }

        $active_period = $this->get_active_period();
        $periods_by_key = [];
        $add_period = function ($year, $semester, $semester_mhs = null) use (&$periods_by_key, $active_period, $account) {
            $year = trim((string)$year);
            $semester = ucfirst(strtolower(trim((string)$semester)));
            if ($year === '' || !in_array($semester, ['Ganjil', 'Genap'], true)) {
                return;
            }

            $key = $year . '_' . strtolower($semester);
            $is_active = ($year === $active_period['tahun_akademik'] && $semester === $active_period['semester_akademik']);
            $periods_by_key[$key] = [
                'key' => $key,
                'label' => $year . ' - ' . $semester,
                'display_label' => $year . ' - ' . $semester . ($is_active ? ' (Aktif)' : ''),
                'tahun_akademik' => $year,
                'tahun_singkat' => explode('/', $year)[0],
                'semester_akademik' => $semester,
                'semester_mhs' => $semester_mhs ? (int)$semester_mhs : (int)($account->semester ?? 0),
                'is_active' => $is_active
            ];
        };

        $add_period($active_period['tahun_akademik'], $active_period['semester_akademik'], $account->semester ?? null);

        $approved_krs_periods = $this->db->select('tahun_akademik, semester_akademik')
                                         ->where('akun_id', (int)$akun_id)
                                         ->where('status', 'DISETUJUI')
                                         ->group_by(['tahun_akademik', 'semester_akademik'])
                                         ->order_by('tahun_akademik', 'DESC')
                                         ->get('krs')
                                         ->result();
        foreach ($approved_krs_periods as $row) {
            $add_period($row->tahun_akademik, $row->semester_akademik);
        }

        $khs_periods = $this->db->select('tahun_akademik, semester_akademik, MAX(semester_mhs) AS semester_mhs')
                                ->where('akun_id', (int)$akun_id)
                                ->group_by(['tahun_akademik', 'semester_akademik'])
                                ->order_by('tahun_akademik', 'DESC')
                                ->get('khs_nilai')
                                ->result();
        foreach ($khs_periods as $row) {
            $add_period($row->tahun_akademik, $row->semester_akademik, $row->semester_mhs);
        }

        $available_periods = array_values($periods_by_key);
        usort($available_periods, function ($left, $right) {
            if ($left['is_active'] !== $right['is_active']) {
                return $left['is_active'] ? -1 : 1;
            }
            return strcmp($right['tahun_akademik'], $left['tahun_akademik']);
        });

        $chosen_period = $available_periods[0] ?? null;
        if ($selected_period !== null && $selected_period !== '') {
            foreach ($available_periods as $period) {
                if ($period['key'] === $selected_period
                    || $period['label'] === $selected_period
                    || ($period['tahun_akademik'] . ' ' . $period['semester_akademik']) === $selected_period
                    || (string)$period['semester_mhs'] === (string)$selected_period) {
                    $chosen_period = $period;
                    break;
                }
            }
        }

        $krs = $chosen_period
            ? $this->get_krs($akun_id, $chosen_period, $chosen_period['is_active'] ? null : 'DISETUJUI')
            : null;
        $krs_status = $krs ? strtoupper((string)$krs->status) : 'BELUM_KRS';
        $courses = [];

        if ($krs && $krs_status === 'DISETUJUI') {
            foreach ($krs->mata_kuliah as $index => $course) {
                $hari = trim((string)($course->hari ?? ''));
                $waktu_mulai = !empty($course->waktu_mulai) ? substr($course->waktu_mulai, 0, 5) : '';
                $waktu_selesai = !empty($course->waktu_selesai) ? substr($course->waktu_selesai, 0, 5) : '';
                $waktu = trim($waktu_mulai . ($waktu_selesai !== '' ? ' - ' . $waktu_selesai : ''));
                $ruangan = trim((string)($course->ruangan ?? ''));
                $jadwal = trim(implode(', ', array_filter([$hari, $waktu, $ruangan])));

                $courses[] = (object)[
                    'no' => $index + 1,
                    'kode_mk' => $course->kode_mk ?? '-',
                    'nama_mk' => $course->nama_mk ?? '-',
                    'kelas' => $course->kelas ?? '-',
                    'sks' => (int)($course->sks ?? 0),
                    'dosen' => $course->nama_dosen ?? '-',
                    'jadwal' => $jadwal !== '' ? $jadwal : '-',
                    'terselenggara' => null,
                    'hadir' => null,
                    'sakit' => null,
                    'ijin' => null,
                    'tugas' => null,
                    'tidak_hadir' => null,
                    'persentase_hadir' => '',
                    'persentase_num' => null,
                    'hari' => $hari,
                    'waktu' => $waktu,
                    'ruangan' => $ruangan,
                    'meetings' => [],
                    'p6_pengganti' => '',
                    'p7_pengganti' => ''
                ];
            }
        }

        return [
            'student' => $account,
            'available_periods' => $available_periods,
            'selected_period' => $chosen_period,
            'courses' => $courses,
            'krs' => $krs,
            'krs_status' => $krs_status
        ];
    }
}
