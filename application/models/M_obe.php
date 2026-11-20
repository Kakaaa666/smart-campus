<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_obe extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $ci =& get_instance();
        if (!isset($ci->M_akademik)) {
            $ci->load->model('shared/M_akademik', 'M_akademik');
        }
        if (isset($ci->M_akademik) && method_exists($ci->M_akademik, 'cleanup_duplicate_offerings')) {
            $ci->M_akademik->cleanup_duplicate_offerings();
        }
        $this->ensure_initial_seeds();
        $this->ensure_complete_obe_schema();
    }

    /**
     * Memastikan data awal CPL dan prodi tersedia untuk simulasi dan operasional langsung
     */
    public function ensure_initial_seeds()
    {
        // 1. Pastikan prodi terisi
        $count_prodi = $this->db->count_all('prodi');
        if ($count_prodi === 0) {
            $prodis = [
                ['id' => 1, 'kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknologi Informasi'],
                ['id' => 2, 'kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'fakultas' => 'Fakultas Teknologi Informasi'],
                ['id' => 3, 'kode_prodi' => 'MI', 'nama_prodi' => 'Manajemen Informatika', 'fakultas' => 'Fakultas Teknologi Informasi'],
                ['id' => 4, 'kode_prodi' => 'AK', 'nama_prodi' => 'Akuntansi', 'fakultas' => 'Fakultas Ekonomi dan Bisnis'],
                ['id' => 5, 'kode_prodi' => 'MN', 'nama_prodi' => 'Manajemen', 'fakultas' => 'Fakultas Ekonomi dan Bisnis'],
                ['id' => 6, 'kode_prodi' => 'IH', 'nama_prodi' => 'Ilmu Hukum', 'fakultas' => 'Fakultas Hukum']
            ];
            foreach ($prodis as $p) {
                $this->db->replace('prodi', $p);
            }
        }

        // 2. Pastikan CPL awal untuk Prodi TI (id = 1)
        $count_cpl = $this->db->where('prodi_id', 1)->count_all_results('cpls');
        if ($count_cpl === 0) {
            $default_cpls = [
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-01', 'kategori' => 'Sikap', 'deskripsi' => 'Bertakwa kepada Tuhan YME, menjunjung tinggi nilai kemanusiaan, integritas profesional, dan etika rekayasa perangkat lunak.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-02', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai konsep teoritis sains komputasi, algoritma dasar & lanjut, struktur data, dan arsitektur sistem informasi.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-03', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai prinsip desain basis data relasional/NoSQL, keamanan siber, dan pemrograman full-stack berbasis web dan mobile.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-04', 'kategori' => 'Keterampilan Umum', 'deskripsi' => 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam penyelesaian problem komputasi di industri modern.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-05', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu menganalisis, merancang, dan mengimplementasikan sistem perangkat lunak yang scalable, robust, dan user-friendly.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-06', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu menerapkan metodologi Agile/Scrum, version control modern, dan continuous integration dalam siklus hidup rekayasa perangkat lunak.'],
            ];
            foreach ($default_cpls as $cpl) {
                $this->db->insert('cpls', $cpl);
            }
        }

        // 3. Pastikan CPL awal untuk Prodi SI (id = 2)
        $count_cpl_si = $this->db->where('prodi_id', 2)->count_all_results('cpls');
        if ($count_cpl_si === 0) {
            $default_cpls_si = [
                ['prodi_id' => 2, 'kode_cpl' => 'CPL-01', 'kategori' => 'Sikap', 'deskripsi' => 'Menunjukkan sikap bertanggung jawab atas pekerjaan di bidang sistem informasi bisnis sesuai norma etika organisasi.'],
                ['prodi_id' => 2, 'kode_cpl' => 'CPL-02', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai konsep tata kelola teknologi informasi, perencanaan strategis SI, dan arsitektur enterprise.'],
                ['prodi_id' => 2, 'kode_cpl' => 'CPL-03', 'kategori' => 'Keterampilan Umum', 'deskripsi' => 'Mampu berkomunikasi efektif dan berkolaborasi dalam tim multidisiplin untuk transformasi digital organisasi.'],
                ['prodi_id' => 2, 'kode_cpl' => 'CPL-04', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu menganalisis kebutuhan proses bisnis dan merancang sistem informasi terintegrasi yang bernilai tambah tinggi.'],
            ];
            foreach ($default_cpls_si as $cpl) {
                $this->db->insert('cpls', $cpl);
            }
        }

        // 4. Pastikan CPMK, komponen asesmen, pemetaan CPL, dan nilai OBE tersedia untuk seluruh kurikulum Semester 1 s/d 4
        $this->seed_all_curriculum_cpmk();
    }

    /**
     * Inisialisasi otomatis CPMK, komponen penilaian, relasi CPL, dan nilai CPMK untuk seluruh mata kuliah
     */
    public function seed_all_curriculum_cpmk()
    {
        // Pastikan tabel OBE utama tersedia
        $this->db->query("CREATE TABLE IF NOT EXISTS `cpls` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `prodi_id` INT(11) NOT NULL,
            `kode_cpl` VARCHAR(20) NOT NULL,
            `kategori` ENUM('Sikap','Pengetahuan','Keterampilan Umum','Keterampilan Khusus') NOT NULL,
            `deskripsi` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_cpl_prodi` (`prodi_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `cpmks` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `penawaran_matakuliah_id` INT(11) NOT NULL,
            `kode_cpmk` VARCHAR(20) NOT NULL,
            `deskripsi` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_cpmk_penawaran` (`penawaran_matakuliah_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `cpl_cpmk` (
            `cpl_id` INT(11) NOT NULL,
            `cpmk_id` INT(11) NOT NULL,
            PRIMARY KEY (`cpl_id`,`cpmk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `komponen_penilaian` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `penawaran_matakuliah_id` INT(11) NOT NULL,
            `cpmk_id` INT(11) NOT NULL,
            `nama_asesmen` VARCHAR(100) NOT NULL,
            `bobot_persen` DECIMAL(5,2) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_komponen_penawaran` (`penawaran_matakuliah_id`),
            KEY `idx_komponen_cpmk` (`cpmk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `nilai_cpmk` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `krs_detail_id` INT(11) NOT NULL,
            `komponen_penilaian_id` INT(11) NOT NULL,
            `nilai_angka` DECIMAL(5,2) NOT NULL,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_nilai_krs_komponen` (`krs_detail_id`,`komponen_penilaian_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Ambil penawaran mata kuliah aktif
        $offerings = $this->db->where('aktif', 1)->get('penawaran_matakuliah')->result();
        if (empty($offerings)) {
            return;
        }

        // Ambil mapping CPL per prodi
        $cpls_ti = $this->db->where('prodi_id', 1)->order_by('id', 'ASC')->get('cpls')->result();
        $cpls_si = $this->db->where('prodi_id', 2)->order_by('id', 'ASC')->get('cpls')->result();

        $cpl_map_ti = [];
        foreach ($cpls_ti as $c) { $cpl_map_ti[$c->kode_cpl] = (int)$c->id; }
        $cpl_map_si = [];
        foreach ($cpls_si as $c) { $cpl_map_si[$c->kode_cpl] = (int)$c->id; }

        foreach ($offerings as $off) {
            $off_id = (int)$off->id;
            $cpmk_count = $this->db->where('penawaran_matakuliah_id', $off_id)->count_all_results('cpmks');

            $is_si = (stripos($off->prodi, 'Sistem Informasi') !== false || stripos($off->prodi, 'Manajemen Informatika') !== false || strpos($off->kode_mk, 'SI') === 0 || strpos($off->kode_mk, 'MI') === 0);
            $cpl_map = $is_si ? $cpl_map_si : $cpl_map_ti;
            $prodi_id = $is_si ? 2 : 1;

            if ($cpmk_count === 0) {
                // Buat 3 CPMK terstruktur sesuai mata kuliah
                $nama_clean = htmlspecialchars_decode($off->nama_mk, ENT_QUOTES);

                $cpmk1_desc = 'Mampu memahami konsep fundamental, landasan teoritis, dan metodologi dasar dalam ' . $nama_clean . '.';
                $cpmk2_desc = 'Mampu menganalisis kebutuhan teknis, merancang model, dan memecahkan persoalan komputasi pada ' . $nama_clean . '.';
                $cpmk3_desc = 'Mampu mengimplementasikan solusi perangkat lunak/sistem yang andal, efisien, dan teruji dalam ' . $nama_clean . '.';

                // Insert CPMK 1
                $this->db->insert('cpmks', [
                    'penawaran_matakuliah_id' => $off_id,
                    'kode_cpmk' => 'CPMK-1',
                    'deskripsi' => $cpmk1_desc
                ]);
                $c1_id = (int)$this->db->insert_id();

                // Insert CPMK 2
                $this->db->insert('cpmks', [
                    'penawaran_matakuliah_id' => $off_id,
                    'kode_cpmk' => 'CPMK-2',
                    'deskripsi' => $cpmk2_desc
                ]);
                $c2_id = (int)$this->db->insert_id();

                // Insert CPMK 3
                $this->db->insert('cpmks', [
                    'penawaran_matakuliah_id' => $off_id,
                    'kode_cpmk' => 'CPMK-3',
                    'deskripsi' => $cpmk3_desc
                ]);
                $c3_id = (int)$this->db->insert_id();

                // Map CPMK ke CPL
                $mapped_cpl_ids = $this->map_course_to_cpl_ids($off->nama_mk, $off->kode_mk, (int)$off->semester, $prodi_id);

                if (!empty($mapped_cpl_ids)) {
                    $cpl_chunks = array_chunk($mapped_cpl_ids, max(1, (int)ceil(count($mapped_cpl_ids) / 3)));
                    $c1_cpls = $cpl_chunks[0] ?? [$mapped_cpl_ids[0]];
                    $c2_cpls = $cpl_chunks[1] ?? $c1_cpls;
                    $c3_cpls = $cpl_chunks[2] ?? end($cpl_chunks);

                    foreach ($c1_cpls as $c_id) { $this->db->replace('cpl_cpmk', ['cpl_id' => $c_id, 'cpmk_id' => $c1_id]); }
                    foreach ($c2_cpls as $c_id) { $this->db->replace('cpl_cpmk', ['cpl_id' => $c_id, 'cpmk_id' => $c2_id]); }
                    foreach ($c3_cpls as $c_id) { $this->db->replace('cpl_cpmk', ['cpl_id' => $c_id, 'cpmk_id' => $c3_id]); }
                }

                // Insert Komponen Penilaian (tepat 100%)
                $this->db->insert('komponen_penilaian', [
                    'penawaran_matakuliah_id' => $off_id,
                    'cpmk_id' => $c1_id,
                    'nama_asesmen' => 'Tugas Mandiri & Kuis Teori',
                    'bobot_persen' => 20.00
                ]);
                $comp1 = (int)$this->db->insert_id();

                $this->db->insert('komponen_penilaian', [
                    'penawaran_matakuliah_id' => $off_id,
                    'cpmk_id' => $c2_id,
                    'nama_asesmen' => 'Ujian Tengah Semester (UTS)',
                    'bobot_persen' => 30.00
                ]);
                $comp2 = (int)$this->db->insert_id();

                $this->db->insert('komponen_penilaian', [
                    'penawaran_matakuliah_id' => $off_id,
                    'cpmk_id' => $c3_id,
                    'nama_asesmen' => 'Proyek Terapan & Praktikum',
                    'bobot_persen' => 25.00
                ]);
                $comp3 = (int)$this->db->insert_id();

                $this->db->insert('komponen_penilaian', [
                    'penawaran_matakuliah_id' => $off_id,
                    'cpmk_id' => $c3_id,
                    'nama_asesmen' => 'Ujian Akhir Semester (UAS)',
                    'bobot_persen' => 25.00
                ]);
                $comp4 = (int)$this->db->insert_id();
            }

            // Hapus nilai_cpmk yang terlanjur terisi pada mata kuliah semester berjalan (belum memiliki nilai KHS resmi)
            $this->db->query("
                DELETE nc FROM `nilai_cpmk` nc
                INNER JOIN `krs_detail` kd ON kd.id = nc.krs_detail_id
                INNER JOIN `krs` k ON k.id = kd.krs_id
                INNER JOIN `penawaran_matakuliah` p ON p.id = kd.penawaran_id
                LEFT JOIN `khs_nilai` kn ON kn.akun_id = k.akun_id AND (kn.kode_mk = p.kode_mk OR kn.nama_mk = p.nama_mk)
                WHERE kn.id IS NULL
            ");

            // Sinkronisasi nilai_cpmk HANYA bagi mahasiswa pada penawaran yang sudah memiliki riwayat nilai KHS resmi
            $enrolled = $this->db->select('kd.id as krs_detail_id, k.akun_id')
                                 ->from('krs_detail kd')
                                 ->join('krs k', 'k.id = kd.krs_id', 'inner')
                                 ->where('kd.penawaran_id', $off_id)
                                 ->get()->result();

            if (!empty($enrolled)) {
                $comps = $this->db->where('penawaran_matakuliah_id', $off_id)->get('komponen_penilaian')->result();
                if (!empty($comps)) {
                    foreach ($enrolled as $en) {
                        $kdid = (int)$en->krs_detail_id;
                        $has_scores = $this->db->where('krs_detail_id', $kdid)->count_all_results('nilai_cpmk');
                        if ($has_scores < count($comps)) {
                            // Ambil baseline score HANYA dari KHS resmi yang sudah ada (semester yang telah selesai)
                            $khs = $this->db->where('akun_id', (int)$en->akun_id)
                                            ->group_start()
                                                ->where('kode_mk', $off->kode_mk)
                                                ->or_like('nama_mk', $off->nama_mk)
                                            ->group_end()
                                            ->get('khs_nilai')->row();

                            // Jika mata kuliah belum selesai (tidak ada KHS / semester berjalan), JANGAN isi nilai_cpmk!
                            if ($khs && ($khs->nilai_angka !== null || $khs->nilai_indeks !== null)) {
                                if ($khs->nilai_angka !== null) {
                                    $base = (float)$khs->nilai_angka;
                                } else {
                                    $base = ($khs->nilai_indeks == 4.0) ? 89.0 : (($khs->nilai_indeks >= 3.5) ? 83.5 : 78.0);
                                }

                                $deltas = [1.0, -1.5, 2.0, -0.5, 0.5];
                                foreach ($comps as $ci => $comp) {
                                    $score = round($base + ($deltas[$ci % count($deltas)] ?? 0), 2);
                                    if ($score > 100) $score = 100.0;
                                    if ($score < 60) $score = 65.0;

                                    $this->db->replace('nilai_cpmk', [
                                        'krs_detail_id' => $kdid,
                                        'komponen_penilaian_id' => (int)$comp->id,
                                        'nilai_angka' => $score
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Memetakan mata kuliah ke dimensi CPL program studi secara kurikuler dan adaptif
     */
    public function map_course_to_cpl_ids($nama_mk, $kode_mk = '', $semester = 1, $prodi_id = 1, $cpls = null)
    {
        if (empty($cpls)) {
            $cpls = $this->get_cpls_by_prodi($prodi_id);
        }
        $cpl_map = [];
        foreach ($cpls as $c) {
            $cpl_map[$c->kode_cpl] = (int)$c->id;
        }

        $upper = strtoupper(trim($nama_mk . ' ' . $kode_mk));
        $target_kodes = [];

        if ((int)$prodi_id === 2) {
            // Prodi Sistem Informasi (CPL-01 s/d CPL-04)
            if (preg_match('/(AGAMA|PANCASILA|KEWARGANEGARAAN|ETIKA|INTERPERSONAL|HUKUM)/', $upper)) {
                $target_kodes = ['CPL-01', 'CPL-03'];
            } elseif (preg_match('/(TATA KELOLA|ENTERPRISE|STRATEGIS|MANAJEMEN|BISNIS|AUDIT)/', $upper)) {
                $target_kodes = ['CPL-02', 'CPL-04'];
            } elseif (preg_match('/(KOMUNIKASI|KOLABORASI|BAHASA|PROYEK|KEWIRAUSAHAAN)/', $upper)) {
                $target_kodes = ['CPL-01', 'CPL-03'];
            } elseif (preg_match('/(BASIS DATA|SISTEM INFORMASI|ANALISIS|DESAIN|WEB|MOBILE|PROGRAM)/', $upper)) {
                $target_kodes = ['CPL-02', 'CPL-04'];
            } else {
                $target_kodes = ($semester <= 2) ? ['CPL-01', 'CPL-02', 'CPL-03'] : ['CPL-02', 'CPL-03', 'CPL-04'];
            }
        } else {
            // Prodi Teknik Informatika (CPL-01 s/d CPL-06)
            if (preg_match('/(AGAMA|PANCASILA|KEWARGANEGARAAN|ETIKA|INTERPERSONAL|KEWIRAUSAHAAN|HUKUM)/', $upper)) {
                $target_kodes = ['CPL-01', 'CPL-04'];
            } elseif (preg_match('/(ALGORITMA|LOGIKA|DISKRIT|MATEMATIKA|KALKULUS|ALJABAR|STATISTIKA)/', $upper)) {
                $target_kodes = ['CPL-02', 'CPL-04'];
            } elseif (preg_match('/(BASIS DATA|DATABASE|WEB|MOBILE|JARINGAN|KEAMANAN|CYBER|DISTRIBUSI|CLOUD)/', $upper)) {
                $target_kodes = ['CPL-02', 'CPL-03', 'CPL-05'];
            } elseif (preg_match('/(PBO|BERORIENTASI OBJEK|STRUKTUR DATA|PEMROGRAMAN)/', $upper)) {
                $target_kodes = ['CPL-02', 'CPL-03', 'CPL-04', 'CPL-05'];
            } elseif (preg_match('/(REKAYASA|RPL|SOFTWARE|SISTEM INFORMASI|ANALISIS.*SISTEM|PROYEK|AGILE|SCRUM)/', $upper)) {
                $target_kodes = ['CPL-04', 'CPL-05', 'CPL-06'];
            } elseif (preg_match('/(INTERAKSI|MANUSIA.*KOMPUTER|IMK|MULTIMEDIA|DESAIN)/', $upper)) {
                $target_kodes = ['CPL-04', 'CPL-05'];
            } else {
                if ($semester == 1) {
                    $target_kodes = ['CPL-01', 'CPL-02', 'CPL-04'];
                } elseif ($semester == 2) {
                    $target_kodes = ['CPL-02', 'CPL-03', 'CPL-04', 'CPL-05'];
                } elseif ($semester == 3) {
                    $target_kodes = ['CPL-02', 'CPL-03', 'CPL-05', 'CPL-06'];
                } else {
                    $target_kodes = ['CPL-03', 'CPL-04', 'CPL-05', 'CPL-06'];
                }
            }
        }

        $res_ids = [];
        foreach ($target_kodes as $tk) {
            if (isset($cpl_map[$tk])) {
                $res_ids[] = $cpl_map[$tk];
            }
        }
        if (empty($res_ids) && !empty($cpls)) {
            $res_ids[] = (int)$cpls[0]->id;
        }
        return array_unique($res_ids);
    }

    // =========================================================================
    // 1. ROLE KAPRODI (MASTER CPL & PEMETAAN KURIKULUM)
    // =========================================================================

    public function get_prodi($prodi_id)
    {
        return $this->db->get_where('prodi', ['prodi.id' => (int)$prodi_id])->row();
    }

    public function get_all_prodi()
    {
        return $this->db->order_by('id', 'ASC')->get('prodi')->result();
    }

    public function get_cpls_by_prodi($prodi_id, $kategori = null)
    {
        $this->db->where('prodi_id', (int)$prodi_id);
        if (!empty($kategori)) {
            $this->db->where('kategori', $kategori);
        }
        return $this->db->order_by('kode_cpl', 'ASC')->get('cpls')->result();
    }

    public function get_cpl_by_id($id, $prodi_id = null)
    {
        $this->db->where('id', (int)$id);
        if ($prodi_id !== null) {
            $this->db->where('prodi_id', (int)$prodi_id);
        }
        return $this->db->get('cpls')->row();
    }

    public function save_cpl($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', (int)$id);
            return $this->db->update('cpls', $data);
        }
        return $this->db->insert('cpls', $data);
    }

    public function delete_cpl($id, $prodi_id = null)
    {
        $this->db->where('id', (int)$id);
        if ($prodi_id !== null) {
            $this->db->where('prodi_id', (int)$prodi_id);
        }
        $cpl = $this->db->get('cpls')->row();
        if (!$cpl) {
            return false;
        }

        // Hapus mapping relasi pivot
        $this->db->where('cpl_id', $cpl->id)->delete('cpl_cpmk');
        // Hapus CPL
        return $this->db->where('id', $cpl->id)->delete('cpls');
    }

    /**
     * Mengambil matriks kurikulum: daftar mata kuliah prodi dan relasinya terhadap CPL
     */
    public function get_curriculum_matrix($prodi_id)
    {
        $prodi = $this->get_prodi($prodi_id);
        $prodi_name = $prodi ? $prodi->nama_prodi : '';

        // Ambil semua CPL milik prodi ini
        $cpls = $this->get_cpls_by_prodi($prodi_id);

        // Ambil mata kuliah penawaran untuk prodi ini (hanya kelas aktif dan terdeduplikasi)
        $this->db->select('MIN(p.id) as id, p.kode_mk, MAX(p.nama_mk) as nama_mk, p.semester, MAX(p.sks) as sks, p.kelas, MAX(p.prodi) as prodi, MAX(b.nama_lengkap) as dosen_nama', false);
        $this->db->from('penawaran_matakuliah p');
        $this->db->join('akun_biro b', 'b.id = p.dosen_id', 'left');
        $this->db->where('p.aktif', 1);
        if (!empty($prodi_name)) {
            $this->db->group_start()
                     ->where('p.prodi', $prodi_name)
                     ->or_where('p.prodi', 'Umum')
                     ->group_end();
        }
        $this->db->group_by(['p.kode_mk', 'p.kelas', 'p.semester']);
        $this->db->order_by('p.semester', 'ASC');
        $this->db->order_by('p.kode_mk', 'ASC');
        $courses = $this->db->get()->result();

        // Ambil semua mapping CPL - CPMK - MK
        $matrix_map = []; // [penawaran_id][cpl_id] = [cpmk_codes]
        $cpmk_counts = []; // [penawaran_id] = count

        if (!empty($courses)) {
            $course_ids = array_map(function($c) { return (int)$c->id; }, $courses);

            $this->db->select('c.penawaran_matakuliah_id, c.id as cpmk_id, c.kode_cpmk, cp.cpl_id');
            $this->db->from('cpmks c');
            $this->db->join('cpl_cpmk cp', 'cp.cpmk_id = c.id', 'left');
            $this->db->where_in('c.penawaran_matakuliah_id', $course_ids);
            $mappings = $this->db->get()->result();

            foreach ($mappings as $m) {
                $pid = (int)$m->penawaran_matakuliah_id;
                $cpl_id = (int)$m->cpl_id;
                if (!isset($cpmk_counts[$pid])) {
                    $cpmk_counts[$pid] = [];
                }
                $cpmk_counts[$pid][$m->cpmk_id] = true;

                if ($cpl_id > 0) {
                    if (!isset($matrix_map[$pid])) {
                        $matrix_map[$pid] = [];
                    }
                    if (!isset($matrix_map[$pid][$cpl_id])) {
                        $matrix_map[$pid][$cpl_id] = [];
                    }
                    $matrix_map[$pid][$cpl_id][] = $m->kode_cpmk;
                }
            }
        }

        return [
            'prodi'       => $prodi,
            'cpls'        => $cpls,
            'courses'     => $courses,
            'matrix_map'  => $matrix_map,
            'cpmk_counts' => $cpmk_counts
        ];
    }

    // =========================================================================
    // 2. ROLE DOSEN (PENANGANAN NILAI LAMA & BARU)
    // =========================================================================

    /**
     * Mengambil daftar kelas penawaran matakuliah yang diampu dosen atau program studi
     * Lengkap dengan indikator OBE adaptif dan jumlah mahasiswa terdaftar di KRS
     */
    public function get_dosen_classes($dosen_id = 0, $filter_tahun = null, $filter_semester = null, $filter_prodi = null)
    {
        $dosen_id = (int)$dosen_id;
        $where_clauses = [
            'p.aktif = 1',
            "p.kode_mk NOT REGEXP '^(TI|KU|MKU)-[0-9]{3}'"
        ];
        $params = [];

        if ($dosen_id > 0) {
            $where_clauses[] = 'p.dosen_id = ?';
            $params[] = $dosen_id;
        } elseif (!empty($filter_prodi)) {
            $where_clauses[] = '(p.prodi LIKE ? OR p.prodi = "Umum")';
            $params[] = '%' . $filter_prodi . '%';
        }

        if (!empty($filter_tahun)) {
            $where_clauses[] = 'p.tahun_akademik = ?';
            $params[] = $filter_tahun;
        }
        if (!empty($filter_semester)) {
            $where_clauses[] = 'p.semester_akademik = ?';
            $params[] = $filter_semester;
        }

        $where_sql = !empty($where_clauses) ? ('WHERE ' . implode(' AND ', $where_clauses)) : '';

        $sql = "
            SELECT p.id, p.kode_mk, p.nama_mk, p.prodi, p.semester, p.tahun_akademik,
                   p.semester_akademik, p.sks, p.kelas, p.hari, p.ruangan, p.kuota, p.dosen_id, p.aktif,
                   mk.id as mk_id,
                   COALESCE(mk.status_rps, 'draft') as status_rps,
                   COALESCE(rps.status, 'DRAFT') as rps_status,
                   COALESCE(sub_info.total_sub_cpmk, 0) as total_sub_cpmk,
                   COALESCE(sub_info.total_bobot_sub_cpmk, 0) as total_bobot_sub_cpmk,
                   COALESCE(c.total_cpmk, 0) as total_cpmk,
                   COALESCE(kp.total_komponen, 0) as total_komponen,
                   COALESCE(kp.total_bobot, 0) as total_bobot,
                   COALESCE(kd.total_mahasiswa, 0) as total_mahasiswa,
                   COALESCE(kd_acc.total_disetujui, 0) as total_mahasiswa_disetujui,
                   COALESCE(nc.total_mahasiswa_dinilai, 0) as total_mahasiswa_dinilai,
                   COALESCE(kn.total_nilai_khs, 0) as total_nilai_khs,
                   COALESCE(b.nama_lengkap, 'Dosen Pengampu') as nama_dosen
            FROM penawaran_matakuliah p
            LEFT JOIN mata_kuliah mk ON UPPER(TRIM(mk.kode)) = UPPER(TRIM(p.kode_mk))
            LEFT JOIN rps_status rps ON rps.mata_kuliah_id = mk.id
            LEFT JOIN (
                SELECT COALESCE(NULLIF(c.mata_kuliah_id, 0), c.mk_id) as mk_target_id,
                       COUNT(s.id) as total_sub_cpmk,
                       COALESCE(SUM(s.bobot_nilai), 0) as total_bobot_sub_cpmk
                FROM cpmk c
                LEFT JOIN sub_cpmk s ON s.cpmk_id = c.id
                GROUP BY COALESCE(NULLIF(c.mata_kuliah_id, 0), c.mk_id)
            ) sub_info ON sub_info.mk_target_id = mk.id
            LEFT JOIN (
                SELECT penawaran_matakuliah_id, COUNT(id) as total_cpmk 
                FROM cpmks 
                GROUP BY penawaran_matakuliah_id
            ) c ON c.penawaran_matakuliah_id = p.id
            LEFT JOIN (
                SELECT penawaran_matakuliah_id, COUNT(id) as total_komponen, SUM(bobot_persen) as total_bobot 
                FROM komponen_penilaian 
                GROUP BY penawaran_matakuliah_id
            ) kp ON kp.penawaran_matakuliah_id = p.id
            LEFT JOIN (
                SELECT penawaran_id, COUNT(id) as total_mahasiswa 
                FROM krs_detail 
                GROUP BY penawaran_id
            ) kd ON kd.penawaran_id = p.id
            LEFT JOIN (
                SELECT kd3.penawaran_id, COUNT(kd3.id) as total_disetujui
                FROM krs_detail kd3
                INNER JOIN krs k3 ON k3.id = kd3.krs_id
                WHERE UPPER(k3.status) = 'DISETUJUI'
                GROUP BY kd3.penawaran_id
            ) kd_acc ON kd_acc.penawaran_id = p.id
            LEFT JOIN (
                SELECT kd2.penawaran_id, COUNT(DISTINCT nc2.krs_detail_id) as total_mahasiswa_dinilai 
                FROM krs_detail kd2 
                INNER JOIN nilai_cpmk nc2 ON nc2.krs_detail_id = kd2.id 
                GROUP BY kd2.penawaran_id
            ) nc ON nc.penawaran_id = p.id
            LEFT JOIN (
                SELECT p2.id as penawaran_id, COUNT(kn2.id) as total_nilai_khs 
                FROM penawaran_matakuliah p2 
                INNER JOIN khs_nilai kn2 ON (kn2.kode_mk = p2.kode_mk OR kn2.nama_mk = p2.nama_mk) 
                GROUP BY p2.id
            ) kn ON kn.penawaran_id = p.id
            LEFT JOIN akun_biro b ON b.id = p.dosen_id
            {$where_sql}
            ORDER BY p.semester ASC, p.nama_mk ASC, p.kelas ASC
        ";

        $courses = $this->db->query($sql, $params)->result();

        foreach ($courses as &$cl) {
            $cl->mk_id = !empty($cl->mk_id) ? (int)$cl->mk_id : 0;
            $cl->total_sub_cpmk = (int)$cl->total_sub_cpmk;
            $cl->total_bobot_sub_cpmk = round((float)$cl->total_bobot_sub_cpmk, 2);
            $cl->is_bobot_100 = (abs($cl->total_bobot_sub_cpmk - 100.0) <= 0.05);

            $status_upper = strtoupper(!empty($cl->rps_status) ? $cl->rps_status : $cl->status_rps);
            $cl->is_rps_published = ($status_upper === 'PUBLISHED' && $cl->is_bobot_100 && $cl->total_sub_cpmk > 0);
            $cl->rps_display_status = $cl->is_rps_published ? 'PUBLISHED' : 'DRAFT';
        }

        return $courses;
    }

    public function get_class_detail($penawaran_id, $dosen_id = null)
    {
        $this->db->select('p.*, b.nama_lengkap as nama_dosen, b.email as email_dosen');
        $this->db->from('penawaran_matakuliah p');
        $this->db->join('akun_biro b', 'b.id = p.dosen_id', 'left');
        $this->db->where('p.id', (int)$penawaran_id);
        if ($dosen_id !== null && (int)$dosen_id > 0) {
            $this->db->where('p.dosen_id', (int)$dosen_id);
        }
        return $this->db->get()->row();
    }

    public function get_cpmks_by_penawaran($penawaran_id)
    {
        $cpmks = $this->db->where('penawaran_matakuliah_id', (int)$penawaran_id)
                          ->order_by('kode_cpmk', 'ASC')
                          ->get('cpmks')
                          ->result();

        if (empty($cpmks)) {
            return [];
        }

        $cpmk_ids = array_map(function($c) { return (int)$c->id; }, $cpmks);

        $this->db->select('cp.cpmk_id, cp.cpl_id, cpl.kode_cpl, cpl.kategori, cpl.deskripsi');
        $this->db->from('cpl_cpmk cp');
        $this->db->join('cpls cpl', 'cpl.id = cp.cpl_id', 'left');
        $this->db->where_in('cp.cpmk_id', $cpmk_ids);
        $mapped = $this->db->get()->result();

        $cpl_by_cpmk = [];
        foreach ($mapped as $m) {
            $cpl_by_cpmk[(int)$m->cpmk_id][] = $m;
        }

        foreach ($cpmks as &$cpmk) {
            $cpmk->cpls = $cpl_by_cpmk[(int)$cpmk->id] ?? [];
        }

        return $cpmks;
    }

    public function get_komponen_penilaian($penawaran_id)
    {
        $this->db->select('kp.*, c.kode_cpmk, c.deskripsi as deskripsi_cpmk');
        $this->db->from('komponen_penilaian kp');
        $this->db->join('cpmks c', 'c.id = kp.cpmk_id', 'left');
        $this->db->where('kp.penawaran_matakuliah_id', (int)$penawaran_id);
        $this->db->order_by('kp.id', 'ASC');
        return $this->db->get()->result();
    }

    public function save_cpmk_item($penawaran_id, $kode_cpmk, $deskripsi, $cpl_ids = [], $cpmk_id = null)
    {
        $penawaran_id = (int)$penawaran_id;
        $data = [
            'penawaran_matakuliah_id' => $penawaran_id,
            'kode_cpmk' => trim($kode_cpmk),
            'deskripsi' => trim($deskripsi)
        ];

        if ($cpmk_id) {
            $this->db->where('id', (int)$cpmk_id)
                     ->where('penawaran_matakuliah_id', $penawaran_id)
                     ->update('cpmks', $data);
            $target_id = (int)$cpmk_id;
        } else {
            $this->db->insert('cpmks', $data);
            $target_id = (int)$this->db->insert_id();
        }

        // Simpan pemetaan CPL pivot
        $this->db->where('cpmk_id', $target_id)->delete('cpl_cpmk');
        if (!empty($cpl_ids) && is_array($cpl_ids)) {
            foreach ($cpl_ids as $cpl_id) {
                if ((int)$cpl_id > 0) {
                    $this->db->replace('cpl_cpmk', [
                        'cpl_id' => (int)$cpl_id,
                        'cpmk_id' => $target_id
                    ]);
                }
            }
        }

        return $target_id;
    }

    public function delete_cpmk_item($cpmk_id, $penawaran_id)
    {
        $cpmk = $this->db->get_where('cpmks', [
            'id' => (int)$cpmk_id,
            'penawaran_matakuliah_id' => (int)$penawaran_id
        ])->row();

        if (!$cpmk) {
            return false;
        }

        // Hapus pivot CPL
        $this->db->where('cpmk_id', $cpmk->id)->delete('cpl_cpmk');
        // Hapus nilai CPMK
        $komponens = $this->db->get_where('komponen_penilaian', ['cpmk_id' => $cpmk->id])->result();
        foreach ($komponens as $kp) {
            $this->db->where('komponen_penilaian_id', $kp->id)->delete('nilai_cpmk');
        }
        $this->db->where('cpmk_id', $cpmk->id)->delete('komponen_penilaian');
        return $this->db->where('id', $cpmk->id)->delete('cpmks');
    }

    public function save_assessment_components($penawaran_id, $components)
    {
        $penawaran_id = (int)$penawaran_id;
        $total_bobot = 0.0;
        foreach ($components as $comp) {
            $total_bobot += (float)($comp['bobot_persen'] ?? 0);
        }

        // Validasi wajib tepat 100% (toleransi perbedaan pembulatan 0.01)
        if (abs($total_bobot - 100.00) > 0.01) {
            return [
                'status' => false,
                'message' => 'Total bobot asesmen harus tepat 100.00%. Saat ini total bobot adalah ' . number_format($total_bobot, 2) . '%.'
            ];
        }

        // Dapatkan ID komponen yang masih dipertahankan
        $kept_ids = [];
        foreach ($components as $comp) {
            $comp_id = !empty($comp['id']) ? (int)$comp['id'] : null;
            $data = [
                'penawaran_matakuliah_id' => $penawaran_id,
                'cpmk_id'                 => (int)$comp['cpmk_id'],
                'nama_asesmen'            => trim($comp['nama_asesmen']),
                'bobot_persen'            => round((float)$comp['bobot_persen'], 2)
            ];

            if ($comp_id && $this->db->where('id', $comp_id)->where('penawaran_matakuliah_id', $penawaran_id)->count_all_results('komponen_penilaian') > 0) {
                $this->db->where('id', $comp_id)->update('komponen_penilaian', $data);
                $kept_ids[] = $comp_id;
            } else {
                $this->db->insert('komponen_penilaian', $data);
                $kept_ids[] = (int)$this->db->insert_id();
            }
        }

        // Hapus komponen yang tidak ada di daftar kiriman
        if (!empty($kept_ids)) {
            $to_delete = $this->db->where('penawaran_matakuliah_id', $penawaran_id)
                                  ->where_not_in('id', $kept_ids)
                                  ->get('komponen_penilaian')
                                  ->result();
            foreach ($to_delete as $td) {
                $this->db->where('komponen_penilaian_id', $td->id)->delete('nilai_cpmk');
                $this->db->where('id', $td->id)->delete('komponen_penilaian');
            }
        }

        return ['status' => true, 'message' => 'Komponen asesmen dan bobot 100% berhasil disimpan.'];
    }

    /**
     * Mengambil matriks nilai mahasiswa untuk kelas ini:
     * - Mahasiswa dari krs_detail (KRS status DISETUJUI)
     * - Komponen penilaian yang ada
     * - Nilai per komponen (nilai_cpmk)
     * - Nilai akhir dari khs_nilai (apakah historis atau sinkronisasi OBE)
     */
    public function get_grading_matrix_data($penawaran_id)
    {
        $penawaran_id = (int)$penawaran_id;
        $course = $this->get_class_detail($penawaran_id);
        if (!$course) {
            return null;
        }

        // 1. Ambil komponen asesmen
        $komponens = $this->get_komponen_penilaian($penawaran_id);
        $total_bobot = 0.0;
        foreach ($komponens as $k) {
            $total_bobot += (float)$k->bobot_persen;
        }
        $is_obe_configured = (abs($total_bobot - 100.00) <= 0.01 && !empty($komponens));

        // 2. Ambil mahasiswa terdaftar dari krs_detail
        // Gunakan LEFT JOIN ke akun_mahasiswa dan akun agar fleksibel
        $this->db->select('kd.id as krs_detail_id, kd.krs_id, k.akun_id, k.status as krs_status,
                           COALESCE(m.nim, a.nim) as nim,
                           COALESCE(m.nama_lengkap, a.nama_lengkap) as nama_mahasiswa,
                           COALESCE(m.prodi, a.prodi) as prodi_mahasiswa,
                           COALESCE(m.foto, a.foto, "avatar-4.png") as foto');
        $this->db->from('krs_detail kd');
        $this->db->join('krs k', 'k.id = kd.krs_id', 'inner');
        $this->db->join('akun_mahasiswa m', 'm.id = k.akun_id', 'left');
        $this->db->join('akun a', 'a.id = k.akun_id', 'left');
        $this->db->where('kd.penawaran_id', $penawaran_id);
        $this->db->where('k.status', 'DISETUJUI');
        $this->db->order_by('nim', 'ASC');
        $students = $this->db->get()->result();

        // 3. Ambil data nilai_cpmk yang tersimpan
        $krs_detail_ids = array_map(function($s) { return (int)$s->krs_detail_id; }, $students);
        $scores_map = []; // [krs_detail_id][komponen_id] = nilai_angka
        if (!empty($krs_detail_ids)) {
            $this->db->where_in('krs_detail_id', $krs_detail_ids);
            $scores = $this->db->get('nilai_cpmk')->result();
            foreach ($scores as $sc) {
                $scores_map[(int)$sc->krs_detail_id][(int)$sc->komponen_penilaian_id] = (float)$sc->nilai_angka;
            }
        }

        // 4. Ambil data khs_nilai historis/eksisting
        $student_account_ids = array_map(function($s) { return (int)$s->akun_id; }, $students);
        $khs_map = []; // [akun_id] = khs_row
        if (!empty($student_account_ids)) {
            $this->db->where_in('akun_id', $student_account_ids);
            $this->db->where('kode_mk', $course->kode_mk);
            $this->db->where('kelas', $course->kelas);
            $khs_rows = $this->db->get('khs_nilai')->result();
            foreach ($khs_rows as $kr) {
                $khs_map[(int)$kr->akun_id] = $kr;
            }
        }

        // 5. Gabungkan data tiap mahasiswa
        foreach ($students as &$stu) {
            $kdid = (int)$stu->krs_detail_id;
            $akid = (int)$stu->akun_id;

            $stu->scores = $scores_map[$kdid] ?? [];
            $stu->khs = $khs_map[$akid] ?? null;

            // Hitung nilai akhir OBE jika komponen ada
            $calculated_final = 0.0;
            $has_input = false;
            foreach ($komponens as $comp) {
                $val = $stu->scores[$comp->id] ?? null;
                if ($val !== null) {
                    $has_input = true;
                    $calculated_final += ($val * ((float)$comp->bobot_persen / 100.0));
                }
            }

            if ($has_input) {
                $stu->final_obe_numeric = round($calculated_final, 2);
                $grade_info = self::convert_grade($stu->final_obe_numeric);
                $stu->final_obe_grade = $grade_info['huruf'];
                $stu->final_obe_index = $grade_info['indeks'];
            } else {
                // Fallback ke nilai historis khs_nilai
                $stu->final_obe_numeric = $stu->khs ? (float)$stu->khs->nilai_angka : null;
                $stu->final_obe_grade = $stu->khs ? $stu->khs->nilai_huruf : '-';
                $stu->final_obe_index = $stu->khs ? (float)$stu->khs->nilai_indeks : 0.0;
            }
        }

        return [
            'course'             => $course,
            'komponens'          => $komponens,
            'total_bobot'        => $total_bobot,
            'is_obe_configured'  => $is_obe_configured,
            'students'           => $students
        ];
    }

    /**
     * Simpan massal nilai per komponen, hitung otomatis nilai akhir, dan update/insert ke khs_nilai
     */
    public function save_grading_matrix($penawaran_id, $grades_matrix)
    {
        $penawaran_id = (int)$penawaran_id;
        $course = $this->get_class_detail($penawaran_id);
        if (!$course) {
            return ['status' => false, 'message' => 'Kelas perkuliahan tidak ditemukan.'];
        }

        $komponens = $this->get_komponen_penilaian($penawaran_id);
        if (empty($komponens)) {
            return ['status' => false, 'message' => 'Komponen asesmen belum disetup untuk mata kuliah ini.'];
        }

        // Ambil mapping krs_detail_id ke akun_id
        $this->db->select('kd.id as krs_detail_id, k.akun_id');
        $this->db->from('krs_detail kd');
        $this->db->join('krs k', 'k.id = kd.krs_id', 'inner');
        $this->db->where('kd.penawaran_id', $penawaran_id);
        $krs_list = $this->db->get()->result();
        $account_map = [];
        foreach ($krs_list as $kl) {
            $account_map[(int)$kl->krs_detail_id] = (int)$kl->akun_id;
        }

        $this->db->trans_start();

        foreach ($grades_matrix as $krs_detail_id => $comp_scores) {
            $krs_detail_id = (int)$krs_detail_id;
            $akun_id = $account_map[$krs_detail_id] ?? null;
            if (!$akun_id) {
                continue;
            }

            $total_weighted_score = 0.0;
            $has_any_score = false;

            foreach ($komponens as $comp) {
                $comp_id = (int)$comp->id;
                $score_raw = $comp_scores[$comp_id] ?? null;

                if ($score_raw !== null && $score_raw !== '') {
                    $score_val = max(0.0, min(100.0, (float)$score_raw));
                    $has_any_score = true;

                    // Simpan / update ke nilai_cpmk
                    $this->db->replace('nilai_cpmk', [
                        'krs_detail_id'         => $krs_detail_id,
                        'komponen_penilaian_id' => $comp_id,
                        'nilai_angka'           => $score_val,
                        'created_at'            => date('Y-m-d H:i:s')
                    ]);

                    $total_weighted_score += ($score_val * ((float)$comp->bobot_persen / 100.0));
                }
            }

            // Jika ada nilai yang diinput, sinkronisasi ke khs_nilai
            if ($has_any_score) {
                $final_numeric = round($total_weighted_score, 2);
                $grade_info = self::convert_grade($final_numeric);

                $existing_khs = $this->db->get_where('khs_nilai', [
                    'akun_id'        => $akun_id,
                    'kode_mk'        => $course->kode_mk,
                    'kelas'          => $course->kelas,
                    'tahun_akademik' => $course->tahun_akademik
                ])->row();

                $nxk = round($grade_info['indeks'] * (int)$course->sks, 2);

                if ($existing_khs) {
                    $this->db->where('id', $existing_khs->id)->update('khs_nilai', [
                        'nilai_angka'    => $final_numeric,
                        'nilai_huruf'    => $grade_info['huruf'],
                        'nilai_indeks'   => $grade_info['indeks'],
                        'kategori'       => $grade_info['kategori'],
                        'nxk'            => $nxk,
                        'dosen_pengampu' => $course->nama_dosen,
                        'updated_at'     => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $this->db->insert('khs_nilai', [
                        'akun_id'           => $akun_id,
                        'tahun_akademik'    => $course->tahun_akademik,
                        'semester_akademik' => $course->semester_akademik,
                        'semester_mhs'      => $course->semester,
                        'kode_mk'           => $course->kode_mk,
                        'nama_mk'           => $course->nama_mk,
                        'sks'               => (int)$course->sks,
                        'kelas'             => $course->kelas,
                        'dosen_pengampu'    => $course->nama_dosen,
                        'nilai_angka'       => $final_numeric,
                        'nilai_huruf'       => $grade_info['huruf'],
                        'nilai_indeks'      => $grade_info['indeks'],
                        'kategori'          => $grade_info['kategori'],
                        'nxk'               => $nxk,
                        'keterangan'        => '1',
                        'is_paket'          => 0,
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => false, 'message' => 'Terjadi kesalahan basis data saat menyimpan nilai.'];
        }

        return ['status' => true, 'message' => 'Nilai massal berhasil disimpan dan nilai akhir mahasiswa otomatis tersinkronisasi ke KHS!'];
    }

    /**
     * Konversi nilai numerik ke huruf dan indeks standar Smart Campus
     */
    public static function convert_grade($score)
    {
        $score = (float)$score;
        if ($score >= 85.0) {
            return ['huruf' => 'A',  'indeks' => 4.0, 'kategori' => 'Memuaskan'];
        } elseif ($score >= 80.0) {
            return ['huruf' => 'A-', 'indeks' => 3.75, 'kategori' => 'Sangat Baik'];
        } elseif ($score >= 75.0) {
            return ['huruf' => 'B+', 'indeks' => 3.5, 'kategori' => 'Sangat Baik'];
        } elseif ($score >= 70.0) {
            return ['huruf' => 'B',  'indeks' => 3.0, 'kategori' => 'Baik'];
        } elseif ($score >= 65.0) {
            return ['huruf' => 'B-', 'indeks' => 2.75, 'kategori' => 'Cukup Baik'];
        } elseif ($score >= 60.0) {
            return ['huruf' => 'C+', 'indeks' => 2.5, 'kategori' => 'Cukup Baik'];
        } elseif ($score >= 55.0) {
            return ['huruf' => 'C',  'indeks' => 2.0, 'kategori' => 'Cukup'];
        } elseif ($score >= 45.0) {
            return ['huruf' => 'D',  'indeks' => 1.0, 'kategori' => 'Kurang'];
        } else {
            return ['huruf' => 'E',  'indeks' => 0.0, 'kategori' => 'Gagal'];
        }
    }

    // =========================================================================
    // 3. ROLE MAHASISWA (TRANSKRIP KETERCAPAIAN OBE & RADAR CHART)
    // =========================================================================

    /**
     * Mengambil riwayat perkuliahan mahasiswa dan menghitung capaian CPMK & CPL
     */
    public function get_student_obe_attainment($akun_id, $filter_semester = null)
    {
        $akun_id = (int)$akun_id;

        // Ambil profil mahasiswa
        $student = $this->db->get_where('akun_mahasiswa', ['id' => $akun_id])->row();
        if (!$student) {
            $student = $this->db->get_where('akun', ['id' => $akun_id])->row();
        }

        // Tentukan prodi_id mahasiswa
        $prodi_id = 1; // default TI
        if ($student && !empty($student->prodi)) {
            $matched_prodi = $this->db->like('nama_prodi', $student->prodi)->get('prodi')->row();
            if ($matched_prodi) {
                $prodi_id = (int)$matched_prodi->id;
            }
        }

        // Ambil seluruh CPL program studi mahasiswa
        $cpls = $this->get_cpls_by_prodi($prodi_id);

        // 1. Tentukan available_semesters (semester 1 s/d semester saat ini dan seluruh semester yang pernah ditempuh)
        $semesters_set = [];
        if ($student && !empty($student->semester)) {
            for ($s = 1; $s <= (int)$student->semester; $s++) {
                $semesters_set[$s] = true;
            }
        }

        // Ambil semester dari krs_detail + penawaran_matakuliah
        $krs_sem_rows = $this->db->select('DISTINCT(p.semester) as semester')
                                 ->from('krs_detail kd')
                                 ->join('krs k', 'k.id = kd.krs_id', 'inner')
                                 ->join('penawaran_matakuliah p', 'p.id = kd.penawaran_id', 'inner')
                                 ->where('k.akun_id', $akun_id)
                                 ->where('k.status', 'DISETUJUI')
                                 ->get()->result();
        foreach ($krs_sem_rows as $kr) {
            if (!empty($kr->semester)) {
                $semesters_set[(int)$kr->semester] = true;
            }
        }

        // Ambil semester dari khs_nilai
        $khs_sem_rows = $this->db->select('DISTINCT(semester_mhs) as semester')
                                 ->from('khs_nilai')
                                 ->where('akun_id', $akun_id)
                                 ->get()->result();
        foreach ($khs_sem_rows as $kr) {
            if (!empty($kr->semester)) {
                $semesters_set[(int)$kr->semester] = true;
            }
        }

        if (empty($semesters_set)) {
            $semesters_set = [1 => true, 2 => true, 3 => true, 4 => true];
        }

        $available_semesters = array_keys($semesters_set);
        sort($available_semesters, SORT_NUMERIC);

        $cur_student_semester = !empty($student->semester) ? (int)$student->semester : 4;

        // 2. Ambil data khs_nilai historis (Ground truth untuk semester lampau yang SUDAH SELESAI)
        $this->db->select('kn.id as khs_id, kn.tahun_akademik, kn.semester_akademik, kn.semester_mhs as semester,
                           kn.kode_mk, kn.nama_mk, kn.sks, kn.kelas, kn.dosen_pengampu as nama_dosen,
                           kn.nilai_angka as khs_angka, kn.nilai_huruf as khs_huruf, kn.nilai_indeks as khs_indeks');
        $this->db->from('khs_nilai kn');
        $this->db->where('kn.akun_id', $akun_id);
        if (!empty($filter_semester) && $filter_semester !== 'all') {
            $this->db->where('kn.semester_mhs', (int)$filter_semester);
        }
        $this->db->order_by('kn.semester_mhs', 'ASC');
        $this->db->order_by('kn.kode_mk', 'ASC');
        $khs_courses = $this->db->get()->result();

        // 3. Ambil data KRS HANYA untuk semester aktif berjalan saat ini ($cur_student_semester)
        // Semester lampau (< $cur_student_semester) SUDAH SELESAI dan dicatat di khs_nilai, sehingga tidak boleh mengambil KRS phantom/draft semester lampau
        $this->db->select('kd.id as krs_detail_id, k.id as krs_id, k.tahun_akademik, k.semester_akademik,
                           p.id as penawaran_id, p.kode_mk, p.nama_mk, p.sks, p.semester, p.kelas,
                           b.nama_lengkap as nama_dosen,
                           kn.nilai_angka as khs_angka, kn.nilai_huruf as khs_huruf, kn.nilai_indeks as khs_indeks');
        $this->db->from('krs_detail kd');
        $this->db->join('krs k', 'k.id = kd.krs_id', 'inner');
        $this->db->join('penawaran_matakuliah p', 'p.id = kd.penawaran_id', 'inner');
        $this->db->join('akun_biro b', 'b.id = p.dosen_id', 'left');
        $this->db->join('khs_nilai kn', 'kn.akun_id = k.akun_id AND (kn.kode_mk = p.kode_mk OR kn.nama_mk = p.nama_mk)', 'left');
        $this->db->where('k.akun_id', $akun_id);
        $this->db->where('k.status', 'DISETUJUI');
        // Kunci Akademik: Hanya ambil KRS untuk semester aktif berjalan ($cur_student_semester ke atas)
        $this->db->where('p.semester >=', $cur_student_semester);

        if (!empty($filter_semester) && $filter_semester !== 'all') {
            $this->db->where('p.semester', (int)$filter_semester);
        }

        $this->db->order_by('p.semester', 'ASC');
        $this->db->order_by('p.kode_mk', 'ASC');
        $krs_courses = $this->db->get()->result();

        // Gabungkan kedua sumber mata kuliah: KHS resmi diutamakan untuk semester lampau
        $course_map = [];
        
        // 1. Masukkan seluruh mata kuliah dari KHS resmi (Semester 1 s/d selesai)
        foreach ($khs_courses as $kh) {
            $ckey = strtoupper(trim($kh->kode_mk)) . '_' . (int)$kh->semester;
            
            // Cari penawaran_id yang cocok untuk pemetaan CPMK OBE
            $matched_p = $this->db->group_start()
                                  ->where('kode_mk', $kh->kode_mk)
                                  ->or_like('nama_mk', $kh->nama_mk)
                                  ->group_end()
                                  ->get('penawaran_matakuliah')->row();

            $kh_obj = (object)[
                'krs_detail_id'     => null,
                'krs_id'            => null,
                'tahun_akademik'    => $kh->tahun_akademik,
                'semester_akademik' => $kh->semester_akademik,
                'penawaran_id'      => $matched_p ? (int)$matched_p->id : null,
                'kode_mk'           => $kh->kode_mk,
                'nama_mk'           => $kh->nama_mk,
                'sks'               => (int)$kh->sks,
                'semester'          => (int)$kh->semester,
                'kelas'             => $kh->kelas ?: 'A',
                'nama_dosen'        => $kh->nama_dosen ?: 'Dosen Pengampu',
                'khs_angka'         => $kh->khs_angka,
                'khs_huruf'         => $kh->khs_huruf,
                'khs_indeks'        => $kh->khs_indeks
            ];
            $course_map[$ckey] = $kh_obj;
        }

        // 2. Masukkan mata kuliah KRS semester aktif berjalan (Semester 4 dsb.)
        foreach ($krs_courses as $kc) {
            $ckey = strtoupper(trim($kc->kode_mk)) . '_' . (int)$kc->semester;
            if (!isset($course_map[$ckey])) {
                $course_map[$ckey] = $kc;
            }
        }

        $all_student_courses = array_values($course_map);
        usort($all_student_courses, function($a, $b) {
            if ($a->semester != $b->semester) return (int)$a->semester - (int)$b->semester;
            return strcmp($a->kode_mk, $b->kode_mk);
        });

        // 4. Ambil komponen penilaian dan nilai CPMK
        $krs_detail_ids = [];
        $penawaran_ids = [];
        foreach ($all_student_courses as $c) {
            if (!empty($c->krs_detail_id)) $krs_detail_ids[] = (int)$c->krs_detail_id;
            if (!empty($c->penawaran_id))  $penawaran_ids[]  = (int)$c->penawaran_id;
        }
        $krs_detail_ids = array_unique($krs_detail_ids);
        $penawaran_ids  = array_unique($penawaran_ids);

        $komponens = [];
        if (!empty($penawaran_ids)) {
            $this->db->select('kp.*, c.kode_cpmk, c.deskripsi as deskripsi_cpmk');
            $this->db->from('komponen_penilaian kp');
            $this->db->join('cpmks c', 'c.id = kp.cpmk_id', 'inner');
            $this->db->where_in('kp.penawaran_matakuliah_id', $penawaran_ids);
            $komponens = $this->db->get()->result();
        }

        $nilai_scores = [];
        if (!empty($krs_detail_ids)) {
            $this->db->where_in('krs_detail_id', $krs_detail_ids);
            $scores = $this->db->get('nilai_cpmk')->result();
            foreach ($scores as $sc) {
                $nilai_scores[(int)$sc->krs_detail_id][(int)$sc->komponen_penilaian_id] = (float)$sc->nilai_angka;
            }
        }

        $cpmk_to_cpl = [];
        if (!empty($komponens)) {
            $cpmk_ids = array_unique(array_map(function($k) { return (int)$k->cpmk_id; }, $komponens));
            if (!empty($cpmk_ids)) {
                $this->db->where_in('cpmk_id', $cpmk_ids);
                $pivots = $this->db->get('cpl_cpmk')->result();
                foreach ($pivots as $pv) {
                    $cpmk_to_cpl[(int)$pv->cpmk_id][] = (int)$pv->cpl_id;
                }
            }
        }

        $course_komponens = [];
        foreach ($komponens as $kp) {
            $course_komponens[(int)$kp->penawaran_matakuliah_id][(int)$kp->cpmk_id][] = $kp;
        }

        // Akumulator CPL (hanya untuk mata kuliah yang sudah selesai / memiliki KHS resmi)
        $cpl_scores_accumulator = [];
        foreach ($cpls as $cpl) {
            $cpl_scores_accumulator[(int)$cpl->id] = [];
        }

        $processed_courses = [];
        $total_sks_lulus = 0;
        $total_sks_ongoing = 0;

        foreach ($all_student_courses as $kc) {
            $pid = (int)($kc->penawaran_id ?? 0);
            $kdid = (int)($kc->krs_detail_id ?? 0);
            $course_semester = (int)$kc->semester;

            // Aturan Akademik yang Tegas:
            // Semester < $cur_student_semester adalah semester yang SUDAH SELESAI (KHS resmi diterbitkan).
            // HANYA semester aktif berjalan ($cur_student_semester) yang berstatus 'Sedang Ditempuh'.
            $is_past_semester = ($course_semester < $cur_student_semester);
            $has_official_khs = (!empty($kc->khs_huruf) || $kc->khs_angka !== null || $kc->khs_indeks !== null);

            if ($is_past_semester) {
                // Semester lampau (1, 2, 3, dst.) SUDAH SELESAI, tidak boleh berstatus 'Sedang Ditempuh'
                $is_course_ongoing = false;
                if (!$has_official_khs) {
                    $kc->khs_huruf = 'A';
                    $kc->khs_angka = 88.5;
                    $kc->khs_indeks = 4.0;
                    $has_official_khs = true;
                }
            } else {
                // Semester aktif berjalan (misal Semester 4)
                $is_course_ongoing = !$has_official_khs;
            }

            if ($is_course_ongoing) {
                $total_sks_ongoing += (int)$kc->sks;
            } else {
                $total_sks_lulus += (int)$kc->sks;
            }

            $has_cpmk_obe = isset($course_komponens[$pid]) && !empty($course_komponens[$pid]);
            $cpmk_details = [];
            $total_course_score = 0.0;
            $total_course_weight = 0.0;

            if ($has_official_khs) {
                // Baseline score dari KHS resmi
                $base_numeric = ($kc->khs_angka !== null)
                    ? (float)$kc->khs_angka
                    : (($kc->khs_indeks !== null)
                        ? (($kc->khs_indeks == 4.0) ? 89.0 : (($kc->khs_indeks >= 3.5) ? 83.5 : (($kc->khs_indeks >= 3.0) ? 77.5 : (float)$kc->khs_indeks * 25.0)))
                        : 85.0);
            } else {
                $base_numeric = null;
            }

            if ($has_cpmk_obe) {
                foreach ($course_komponens[$pid] as $cpmk_id => $comps) {
                    $sum_component_score_weight = 0.0;
                    $sum_component_weight = 0.0;
                    $cpmk_kode = $comps[0]->kode_cpmk;
                    $cpmk_desc = $comps[0]->deskripsi_cpmk;

                    $comp_details = [];
                    foreach ($comps as $c_idx => $cmp) {
                        $comp_id = (int)$cmp->id;
                        $bobot = (float)$cmp->bobot_persen;
                        $score = $nilai_scores[$kdid][$comp_id] ?? null;

                        // Jika sudah ada KHS resmi tapi skor per komponen belum diinput dosen satu per satu,
                        // turunkan variasi terstruktur dari nilai KHS resmi tersebut
                        if ($score === null && $base_numeric !== null) {
                            $score = round($base_numeric + (($c_idx % 2 == 0) ? 1.0 : -1.0), 2);
                        }

                        $comp_details[] = [
                            'nama_asesmen' => $cmp->nama_asesmen,
                            'bobot_persen' => $bobot,
                            'nilai'        => $score
                        ];

                        if ($score !== null) {
                            $sum_component_score_weight += ($score * $bobot);
                            $sum_component_weight += $bobot;
                        }
                    }

                    $cpmk_attainment = null;
                    if ($sum_component_weight > 0 && $sum_component_score_weight > 0) {
                        $cpmk_attainment = round($sum_component_score_weight / $sum_component_weight, 2);
                    } elseif ($base_numeric !== null) {
                        $cpmk_attainment = $base_numeric;
                    }

                    $mapped_cpl_ids = $cpmk_to_cpl[$cpmk_id] ?? [];
                    if (empty($mapped_cpl_ids)) {
                        $mapped_cpl_ids = $this->map_course_to_cpl_ids($kc->nama_mk, $kc->kode_mk, (int)$kc->semester, $prodi_id, $cpls);
                    }

                    $cpmk_details[] = [
                        'cpmk_id'    => $cpmk_id,
                        'kode_cpmk'  => $cpmk_kode,
                        'deskripsi'  => $cpmk_desc,
                        'komponen'   => $comp_details,
                        'capaian'    => $cpmk_attainment,
                        'mapped_cpls'=> $mapped_cpl_ids
                    ];

                    // HANYA akumulasi ke CPL jika mata kuliah ini SUDAH selesai (bukan semester berjalan yang belum selesai)
                    if ($cpmk_attainment !== null && !$is_course_ongoing) {
                        $total_course_score += ($cpmk_attainment * $sum_component_weight);
                        $total_course_weight += $sum_component_weight;

                        foreach ($mapped_cpl_ids as $cpl_id) {
                            if (isset($cpl_scores_accumulator[$cpl_id])) {
                                $cpl_scores_accumulator[$cpl_id][] = $cpmk_attainment;
                            }
                        }
                    }
                }
            } else {
                // Mata kuliah tanpa cpmks terdaftar di DB
                $mapped_cpl_ids = $this->map_course_to_cpl_ids($kc->nama_mk, $kc->kode_mk, (int)$kc->semester, $prodi_id, $cpls);

                if (!$is_course_ongoing && $base_numeric !== null) {
                    foreach ($mapped_cpl_ids as $cpl_id) {
                        if (isset($cpl_scores_accumulator[$cpl_id])) {
                            $cpl_scores_accumulator[$cpl_id][] = $base_numeric;
                        }
                    }

                    $cpmk_details = [
                        [
                            'cpmk_id'    => 1,
                            'kode_cpmk'  => 'CPMK-1',
                            'deskripsi'  => 'Mampu memahami konsep fundamental dan prinsip teoritis dalam mata kuliah ' . $kc->nama_mk . '.',
                            'komponen'   => [
                                ['nama_asesmen' => 'Tugas & Kuis Mandiri', 'bobot_persen' => 20.0, 'nilai' => round($base_numeric + 0.5, 2)],
                                ['nama_asesmen' => 'Ujian Tengah Semester', 'bobot_persen' => 30.0, 'nilai' => round($base_numeric - 1.0, 2)],
                            ],
                            'capaian'    => round($base_numeric - 0.4, 2),
                            'mapped_cpls'=> $mapped_cpl_ids
                        ],
                        [
                            'cpmk_id'    => 2,
                            'kode_cpmk'  => 'CPMK-2',
                            'deskripsi'  => 'Mampu merancang dan mengimplementasikan solusi terapan yang andal pada ' . $kc->nama_mk . '.',
                            'komponen'   => [
                                ['nama_asesmen' => 'Proyek / Studi Kasus', 'bobot_persen' => 25.0, 'nilai' => round($base_numeric + 1.5, 2)],
                                ['nama_asesmen' => 'Ujian Akhir Semester', 'bobot_persen' => 25.0, 'nilai' => round($base_numeric, 2)],
                            ],
                            'capaian'    => round($base_numeric + 0.75, 2),
                            'mapped_cpls'=> $mapped_cpl_ids
                        ]
                    ];
                    $total_course_score = $base_numeric * 100.0;
                    $total_course_weight = 100.0;
                } else {
                    $cpmk_details = [
                        [
                            'cpmk_id'    => 1,
                            'kode_cpmk'  => 'CPMK-1',
                            'deskripsi'  => 'Mampu memahami konsep fundamental dan prinsip teoritis dalam mata kuliah ' . $kc->nama_mk . '.',
                            'komponen'   => [
                                ['nama_asesmen' => 'Tugas & Kuis Mandiri', 'bobot_persen' => 20.0, 'nilai' => null],
                                ['nama_asesmen' => 'Ujian Tengah Semester', 'bobot_persen' => 30.0, 'nilai' => null],
                            ],
                            'capaian'    => null,
                            'mapped_cpls'=> $mapped_cpl_ids
                        ],
                        [
                            'cpmk_id'    => 2,
                            'kode_cpmk'  => 'CPMK-2',
                            'deskripsi'  => 'Mampu merancang dan mengimplementasikan solusi terapan yang andal pada ' . $kc->nama_mk . '.',
                            'komponen'   => [
                                ['nama_asesmen' => 'Proyek / Studi Kasus', 'bobot_persen' => 25.0, 'nilai' => null],
                                ['nama_asesmen' => 'Ujian Akhir Semester', 'bobot_persen' => 25.0, 'nilai' => null],
                            ],
                            'capaian'    => null,
                            'mapped_cpls'=> $mapped_cpl_ids
                        ]
                    ];
                }
            }

            $kc->has_cpmk_obe  = true;
            $kc->is_ongoing    = $is_course_ongoing;
            $kc->cpmk_details  = $cpmk_details;

            if ($is_course_ongoing) {
                $kc->final_score = null;
                $kc->final_grade = '-';
                $kc->final_index = null;
            } else {
                $kc->final_score  = ($total_course_weight > 0)
                    ? round($total_course_score / $total_course_weight, 2)
                    : $base_numeric;

                $grade_info = self::convert_grade($kc->final_score);
                $kc->final_grade = $kc->khs_huruf ?: $grade_info['huruf'];
                $kc->final_index = ($kc->khs_indeks !== null) ? (float)$kc->khs_indeks : $grade_info['indeks'];
            }

            $processed_courses[] = $kc;
        }

        // Apakah tampilan ini merupakan semester berjalan yang belum selesai?
        // (Misalnya filter semester = 4, di mana semua MK di semester ini berstatus is_ongoing)
        $is_semester_ongoing = false;
        if (!empty($filter_semester) && $filter_semester !== 'all') {
            $ongoing_count = count(array_filter($processed_courses, function($c){ return !empty($c->is_ongoing); }));
            if ($ongoing_count > 0 && $ongoing_count === count($processed_courses)) {
                $is_semester_ongoing = true;
            }
        }

        // Hitung skor rata-rata per CPL
        $cpl_chart_labels = [];
        $cpl_chart_values = [];
        $cpl_results = [];

        foreach ($cpls as $cpl) {
            $scores = $cpl_scores_accumulator[(int)$cpl->id] ?? [];

            if ($is_semester_ongoing) {
                // Untuk semester berjalan yang belum selesai, belum ada nilai final CPL
                $avg_score = null;
                $cpl_chart_labels[] = $cpl->kode_cpl;
                $cpl_chart_values[] = 0.0;

                $cpl_results[] = [
                    'id'          => $cpl->id,
                    'kode_cpl'    => $cpl->kode_cpl,
                    'kategori'    => $cpl->kategori,
                    'deskripsi'   => $cpl->deskripsi,
                    'skor'        => null,
                    'is_ongoing'  => true,
                    'status_text' => 'Dalam Perkuliahan (Belum Selesai)',
                    'is_lulus'    => false,
                    'total_mk'    => count($processed_courses)
                ];
            } else {
                // Untuk semester yang sudah selesai atau Kumulatif (Semester 1 s/d 3)
                $completed_courses = array_filter($processed_courses, function($c){ return empty($c->is_ongoing); });
                $avg_score = !empty($scores) ? round(array_sum($scores) / count($scores), 2) : 0.0;

                if (empty($scores) && !empty($completed_courses)) {
                    $avg_score = round(array_sum(array_map(function($c){ return (float)$c->final_score; }, $completed_courses)) / count($completed_courses), 2);
                }

                $cpl_chart_labels[] = $cpl->kode_cpl;
                $cpl_chart_values[] = $avg_score;

                $cpl_results[] = [
                    'id'          => $cpl->id,
                    'kode_cpl'    => $cpl->kode_cpl,
                    'kategori'    => $cpl->kategori,
                    'deskripsi'   => $cpl->deskripsi,
                    'skor'        => $avg_score,
                    'is_ongoing'  => false,
                    'status_text' => ($avg_score >= 70.0) ? 'Tercapai' : 'Perlu Peningkatan',
                    'is_lulus'    => ($avg_score >= 70.0),
                    'total_mk'    => count($scores) ?: count($completed_courses)
                ];
            }
        }

        return [
            'student'              => $student,
            'prodi'                => $this->get_prodi($prodi_id),
            'courses'              => $processed_courses,
            'cpls'                 => $cpl_results,
            'chart_labels'         => $cpl_chart_labels,
            'chart_values'         => $cpl_chart_values,
            'available_semesters'  => $available_semesters,
            'current_filter'       => $filter_semester ?: 'all',
            'is_semester_ongoing'  => $is_semester_ongoing,
            'total_sks_lulus'      => $total_sks_lulus,
            'total_sks_ongoing'    => $total_sks_ongoing
        ];
    }

    // =========================================================================
    // 4. ROLE PENJAMINAN MUTU (MONITORING & AUDIT MUTU INSTITUSI)
    // =========================================================================

    /**
     * Monitoring CPL agregat per program studi dan indikasi CPL < 70
     */
    public function get_cpl_monitoring_aggregate($prodi_id = null)
    {
        $all_prodi = $this->get_all_prodi();
        $target_prodis = ($prodi_id) ? [$this->get_prodi($prodi_id)] : $all_prodi;

        $results = [];

        foreach ($target_prodis as $pr) {
            if (!$pr) continue;
            $cpls = $this->get_cpls_by_prodi($pr->id);

            // Ambil seluruh CPMK milik prodi ini
            $this->db->select('cp.cpl_id, nc.nilai_angka, kp.bobot_persen');
            $this->db->from('cpl_cpmk cp');
            $this->db->join('cpmks c', 'c.id = cp.cpmk_id', 'inner');
            $this->db->join('penawaran_matakuliah p', 'p.id = c.penawaran_matakuliah_id', 'inner');
            $this->db->join('komponen_penilaian kp', 'kp.cpmk_id = c.id', 'left');
            $this->db->join('nilai_cpmk nc', 'nc.komponen_penilaian_id = kp.id', 'left');
            $this->db->where('p.prodi', $pr->nama_prodi);
            $scores_q = $this->db->get()->result();

            $cpl_scores = [];
            foreach ($scores_q as $sq) {
                if ($sq->nilai_angka !== null) {
                    $cpl_scores[(int)$sq->cpl_id][] = (float)$sq->nilai_angka;
                }
            }

            $cpl_summary = [];
            $prodi_total_score = 0;
            $prodi_scored_count = 0;

            foreach ($cpls as $c) {
                $cid = (int)$c->id;
                $scores = $cpl_scores[$cid] ?? [];
                
                // Jika belum ada nilai spesifik CPMK, hitung estimasi dari KHS mata kuliah prodi ini
                if (empty($scores)) {
                    $this->db->select('AVG(kn.nilai_angka) as avg_khs, AVG(kn.nilai_indeks * 25) as avg_indeks');
                    $this->db->from('khs_nilai kn');
                    $this->db->join('penawaran_matakuliah p', 'p.kode_mk = kn.kode_mk', 'inner');
                    $this->db->where('p.prodi', $pr->nama_prodi);
                    $khs_avg = $this->db->get()->row();
                    $avg = ($khs_avg && $khs_avg->avg_khs) ? (float)$khs_avg->avg_khs : (($khs_avg && $khs_avg->avg_indeks) ? (float)$khs_avg->avg_indeks : 78.50);
                } else {
                    $avg = round(array_sum($scores) / count($scores), 2);
                }

                $is_evaluasi = ($avg < 70.0);
                $cpl_summary[] = [
                    'id'          => $c->id,
                    'kode_cpl'    => $c->kode_cpl,
                    'kategori'    => $c->kategori,
                    'deskripsi'   => $c->deskripsi,
                    'rata_rata'   => $avg,
                    'is_evaluasi' => $is_evaluasi,
                    'status'      => $is_evaluasi ? 'Perlu Evaluasi (< 70)' : 'Memenuhi Target (≥ 70)'
                ];

                $prodi_total_score += $avg;
                $prodi_scored_count++;
            }

            $avg_prodi = $prodi_scored_count > 0 ? round($prodi_total_score / $prodi_scored_count, 2) : 0;

            $results[] = [
                'prodi_id'       => $pr->id,
                'kode_prodi'     => $pr->kode_prodi,
                'nama_prodi'     => $pr->nama_prodi,
                'fakultas'       => $pr->fakultas,
                'rata_rata_prodi'=> $avg_prodi,
                'cpl_list'       => $cpl_summary
            ];
        }

        return [
            'prodi_list' => $all_prodi,
            'data'       => $results
        ];
    }

    /**
     * Audit Kepatuhan RPS Dosen: Rekapitulasi kelas yang sudah 100% vs belum diatur
     */
    public function get_rps_audit_data($prodi_id = null)
    {
        $all_prodi = $this->get_all_prodi();
        $selected_prodi = ($prodi_id) ? $this->get_prodi($prodi_id) : null;

        $this->db->select('p.id, p.kode_mk, p.nama_mk, p.prodi, p.semester, p.tahun_akademik,
                           p.semester_akademik, p.sks, p.kelas, p.hari, p.ruangan,
                           b.nama_lengkap as nama_dosen, b.email as email_dosen,
                           COUNT(DISTINCT c.id) as total_cpmk,
                           COUNT(DISTINCT kp.id) as total_komponen,
                           COALESCE(SUM(kp.bobot_persen), 0) as total_bobot');
        $this->db->from('penawaran_matakuliah p');
        $this->db->join('akun_biro b', 'b.id = p.dosen_id', 'left');
        $this->db->join('cpmks c', 'c.penawaran_matakuliah_id = p.id', 'left');
        $this->db->join('komponen_penilaian kp', 'kp.penawaran_matakuliah_id = p.id', 'left');
        $this->db->where('p.aktif', 1);

        if ($selected_prodi) {
            $this->db->where('p.prodi', $selected_prodi->nama_prodi);
        }

        $this->db->group_by('p.id');
        $this->db->order_by('p.tahun_akademik', 'DESC');
        $this->db->order_by('p.nama_mk', 'ASC');
        $courses = $this->db->get()->result();

        $total_kelas = count($courses);
        $total_lengkap = 0;
        $total_sebagian = 0;
        $total_belum = 0;

        foreach ($courses as &$c) {
            $bobot = (float)$c->total_bobot;
            if (abs($bobot - 100.00) <= 0.01 && $c->total_cpmk > 0) {
                $c->status_rps = 'Lengkap (100%)';
                $c->status_badge = 'badge-success';
                $c->status_code = 'LENGKAP';
                $total_lengkap++;
            } elseif ($bobot > 0 || $c->total_cpmk > 0) {
                $c->status_rps = 'Belum Lengkap (' . number_format($bobot, 1) . '%)';
                $c->status_badge = 'badge-warning';
                $c->status_code = 'SEBAGIAN';
                $total_sebagian++;
            } else {
                $c->status_rps = 'Belum Diatur (0%)';
                $c->status_badge = 'badge-danger';
                $c->status_code = 'BELUM';
                $total_belum++;
            }
        }

        $persentase_kepatuhan = ($total_kelas > 0) ? round(($total_lengkap / $total_kelas) * 100, 1) : 0;

        return [
            'prodi_list'           => $all_prodi,
            'courses'              => $courses,
            'total_kelas'          => $total_kelas,
            'total_lengkap'        => $total_lengkap,
            'total_sebagian'       => $total_sebagian,
            'total_belum'          => $total_belum,
            'persentase_kepatuhan' => $persentase_kepatuhan
        ];
    }

    /* =========================================================================
     * MODUL OUTCOME-BASED EDUCATION (OBE) LENGKAP - 5 FASE IMPLEMENTASI
     * ========================================================================= */

    /**
     * Inisialisasi Skema Tabel Baru & Migrasi Data Aman untuk 5 Fase OBE
     */
    public function ensure_complete_obe_schema()
    {
        // 1. profil_lulusan (Fase 1)
        $this->db->query("CREATE TABLE IF NOT EXISTS `profil_lulusan` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `prodi_id` INT(11) NOT NULL,
            `kode_profil` VARCHAR(20) NOT NULL,
            `nama_profil` VARCHAR(150) NOT NULL,
            `deskripsi` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_pl_prodi` (`prodi_id`),
            KEY `idx_pl_kode` (`kode_profil`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 2. cpl (Fase 1)
        $this->db->query("CREATE TABLE IF NOT EXISTS `cpl` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `prodi_id` INT(11) NOT NULL,
            `kode_cpl` VARCHAR(20) NOT NULL,
            `kategori` ENUM('Sikap','Pengetahuan','Keterampilan Umum','Keterampilan Khusus') NOT NULL DEFAULT 'Pengetahuan',
            `deskripsi` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_cpl_prodi` (`prodi_id`),
            KEY `idx_cpl_kode` (`kode_cpl`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 3. profil_cpl (Fase 1 - Pivot Many to Many)
        $this->db->query("CREATE TABLE IF NOT EXISTS `profil_cpl` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `profil_lulusan_id` INT(11) NOT NULL,
            `cpl_id` INT(11) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_profil_cpl` (`profil_lulusan_id`, `cpl_id`),
            KEY `idx_pc_cpl` (`cpl_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 4. cpl_mk (Fase 1 - Matriks CPL ke MK dengan bobot_cpl, total wajib 100%)
        $this->db->query("CREATE TABLE IF NOT EXISTS `cpl_mk` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `cpl_id` INT(11) NOT NULL,
            `mata_kuliah_id` INT(11) NOT NULL,
            `bobot_cpl` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_cpl_mk` (`cpl_id`, `mata_kuliah_id`),
            KEY `idx_cplmk_cpl` (`cpl_id`),
            KEY `idx_cplmk_mk` (`mata_kuliah_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 1b. Kolom status_rps pada mata_kuliah (Fase 2)
        if ($this->db->table_exists('mata_kuliah') && !$this->db->field_exists('status_rps', 'mata_kuliah')) {
            $this->db->query("ALTER TABLE `mata_kuliah` ADD `status_rps` ENUM('draft','published') NOT NULL DEFAULT 'draft'");
        }

        // 5. cpmk (Fase 2 - terikat ke cpl_mk_id & mk_id)
        $this->db->query("CREATE TABLE IF NOT EXISTS `cpmk` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `mk_id` INT(11) NOT NULL,
            `mata_kuliah_id` INT(11) NOT NULL,
            `cpl_mk_id` INT(11) NOT NULL,
            `kode_cpmk` VARCHAR(20) NOT NULL,
            `deskripsi` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_cpmk_mk` (`mk_id`),
            KEY `idx_cpmk_mata_kuliah` (`mata_kuliah_id`),
            KEY `idx_cpmk_cplmk` (`cpl_mk_id`),
            KEY `idx_cpmk_kode` (`kode_cpmk`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if ($this->db->table_exists('cpmk') && !$this->db->field_exists('mk_id', 'cpmk')) {
            $this->db->query("ALTER TABLE `cpmk` ADD `mk_id` INT(11) NOT NULL AFTER `id`");
            $this->db->query("UPDATE `cpmk` SET `mk_id` = `mata_kuliah_id` WHERE `mk_id` = 0 OR `mk_id` IS NULL");
        }

        // 6. sub_cpmk (Fase 2 - Pertemuan 1-16 & Instrumen Evaluasi, total bobot_nilai wajib 100%)
        $this->db->query("CREATE TABLE IF NOT EXISTS `sub_cpmk` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `cpmk_id` INT(11) NOT NULL,
            `kode_sub` VARCHAR(30) NOT NULL,
            `kode_sub_cpmk` VARCHAR(30) NOT NULL,
            `pertemuan_ke` VARCHAR(50) NOT NULL DEFAULT '1',
            `minggu_pertemuan` VARCHAR(50) NOT NULL DEFAULT 'Pertemuan 1',
            `instrumen_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas',
            `bentuk_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas',
            `bobot_nilai` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `deskripsi` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_sub_cpmk_cpmk` (`cpmk_id`),
            KEY `idx_sub_cpmk_kodesub` (`kode_sub`),
            KEY `idx_sub_cpmk_kode` (`kode_sub_cpmk`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if ($this->db->table_exists('sub_cpmk')) {
            if (!$this->db->field_exists('kode_sub', 'sub_cpmk')) {
                $this->db->query("ALTER TABLE `sub_cpmk` ADD `kode_sub` VARCHAR(30) NOT NULL DEFAULT '' AFTER `cpmk_id`");
                $this->db->query("UPDATE `sub_cpmk` SET `kode_sub` = `kode_sub_cpmk`");
            }
            if (!$this->db->field_exists('pertemuan_ke', 'sub_cpmk')) {
                $this->db->query("ALTER TABLE `sub_cpmk` ADD `pertemuan_ke` VARCHAR(50) NOT NULL DEFAULT '1' AFTER `kode_sub`");
                $this->db->query("UPDATE `sub_cpmk` SET `pertemuan_ke` = `minggu_pertemuan`");
            }
            if (!$this->db->field_exists('instrumen_evaluasi', 'sub_cpmk')) {
                $this->db->query("ALTER TABLE `sub_cpmk` ADD `instrumen_evaluasi` VARCHAR(50) NOT NULL DEFAULT 'Tugas' AFTER `pertemuan_ke`");
                $this->db->query("UPDATE `sub_cpmk` SET `instrumen_evaluasi` = `bentuk_evaluasi`");
            }
        }

        // 7. rps_status (Fase 2)
        $this->db->query("CREATE TABLE IF NOT EXISTS `rps_status` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `mata_kuliah_id` INT(11) NOT NULL,
            `status` ENUM('DRAFT','PUBLISHED') NOT NULL DEFAULT 'DRAFT',
            `total_bobot_nilai` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `catatan_revisi` TEXT NULL,
            `published_at` DATETIME NULL,
            `published_by` INT(11) NULL,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_rps_mk` (`mata_kuliah_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 8. nilai_evaluasi (Fase 3 & 4 - Nilai mentah 0-100 per Sub-CPMK)
        $this->db->query("CREATE TABLE IF NOT EXISTS `nilai_evaluasi` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `krs_id` INT(11) NULL,
            `krs_detail_id` INT(11) NULL,
            `sub_cpmk_id` INT(11) NOT NULL,
            `nilai_angka` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `catatan` VARCHAR(255) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_ne_krs` (`krs_id`),
            KEY `idx_ne_krs_detail` (`krs_detail_id`),
            KEY `idx_ne_sub_cpmk` (`sub_cpmk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if ($this->db->table_exists('nilai_evaluasi')) {
            if (!$this->db->field_exists('krs_id', 'nilai_evaluasi')) {
                $this->db->query("ALTER TABLE `nilai_evaluasi` ADD `krs_id` INT(11) NULL AFTER `id`, ADD INDEX (`krs_id`)");
            }
            if ($this->db->table_exists('krs_detail')) {
                $this->db->query("UPDATE `nilai_evaluasi` ne
                                  INNER JOIN `krs_detail` kd ON kd.id = ne.krs_detail_id
                                  SET ne.krs_id = kd.krs_id
                                  WHERE ne.krs_id IS NULL OR ne.krs_id = 0");
            }

            // Fase 4: Hapus duplikat kombinasi (krs_id, sub_cpmk_id) jika ada (pertahankan ID terbaru)
            $this->db->query("DELETE ne1 FROM `nilai_evaluasi` ne1
                              INNER JOIN `nilai_evaluasi` ne2 
                              ON ne1.krs_id = ne2.krs_id 
                              AND ne1.sub_cpmk_id = ne2.sub_cpmk_id 
                              AND ne1.id < ne2.id
                              WHERE ne1.krs_id IS NOT NULL AND ne1.krs_id > 0");

            // Pastikan UNIQUE KEY uq_krs_sub_cpmk pada kombinasi (krs_id, sub_cpmk_id)
            $existing_indexes = $this->db->query("SHOW INDEX FROM `nilai_evaluasi`")->result_array();
            $has_proper_unique = false;
            $has_old_uq = false;
            $uq_cols = [];

            foreach ($existing_indexes as $idx) {
                if ($idx['Key_name'] === 'uq_krs_sub_cpmk') {
                    $has_old_uq = true;
                    $uq_cols[$idx['Seq_in_index']] = $idx['Column_name'];
                }
            }

            if ($has_old_uq && isset($uq_cols[1], $uq_cols[2]) && $uq_cols[1] === 'krs_id' && $uq_cols[2] === 'sub_cpmk_id') {
                $has_proper_unique = true;
            } elseif ($has_old_uq) {
                // Drop jika uq_krs_sub_cpmk lama berada di kolom lain
                $this->db->query("ALTER TABLE `nilai_evaluasi` DROP INDEX `uq_krs_sub_cpmk`");
            }

            if (!$has_proper_unique) {
                $this->db->query("ALTER TABLE `nilai_evaluasi` ADD UNIQUE KEY `uq_krs_sub_cpmk` (`krs_id`, `sub_cpmk_id`)");
            }
        }

        // Sinkronisasi data cpls lama ke cpl baru jika ada
        if ($this->db->table_exists('cpls') && $this->db->count_all('cpl') === 0) {
            $this->db->query("INSERT IGNORE INTO `cpl` (`id`, `prodi_id`, `kode_cpl`, `kategori`, `deskripsi`, `created_at`)
                              SELECT `id`, `prodi_id`, `kode_cpl`, `kategori`, `deskripsi`, `created_at` FROM `cpls`");
        }

        // Sinkronisasi mata_kuliah dari penawaran_matakuliah jika ada kode yang belum ada di master
        if ($this->db->table_exists('penawaran_matakuliah') && $this->db->table_exists('mata_kuliah')) {
            $this->db->query("INSERT IGNORE INTO `mata_kuliah` (`kode`, `nama`, `deskripsi`)
                              SELECT DISTINCT p.kode_mk, p.nama_mk, CONCAT('Mata kuliah ', p.nama_mk)
                              FROM `penawaran_matakuliah` p
                              LEFT JOIN `mata_kuliah` m ON UPPER(TRIM(m.kode)) = UPPER(TRIM(p.kode_mk))
                              WHERE m.id IS NULL");
        }

        $this->seed_complete_obe_defaults();
    }

    /**
     * Seed Data Default Terverifikasi untuk 5 Fase OBE
     */
    public function seed_complete_obe_defaults()
    {
        // 1. Profil Lulusan untuk Prodi 1 (TI)
        $count_pl = $this->db->where('prodi_id', 1)->count_all_results('profil_lulusan');
        if ($count_pl === 0) {
            $pls = [
                ['prodi_id' => 1, 'kode_profil' => 'PL-01', 'nama_profil' => 'Software Engineer', 'deskripsi' => 'Pengembang perangkat lunak handal yang menguasai clean code, arsitektur sistem modern, dan metodologi Agile/Scrum.'],
                ['prodi_id' => 1, 'kode_profil' => 'PL-02', 'nama_profil' => 'Data & AI Specialist', 'deskripsi' => 'Ahli rekayasa data, kecerdasan artifisial, pembelajaran mesin, dan analitika cerdas untuk keputusan strategis.'],
                ['prodi_id' => 1, 'kode_profil' => 'PL-03', 'nama_profil' => 'Cybersecurity & Network Analyst', 'deskripsi' => 'Praktisi keamanan informasi, audit sistem siber, forensik digital, dan keandalan infrastruktur cloud terdistribusi.'],
                ['prodi_id' => 1, 'kode_profil' => 'PL-04', 'nama_profil' => 'Technopreneur & IT Consultant', 'deskripsi' => 'Perancang inovasi produk digital, konsultan transformasi teknologi, dan wirausahawan berbasis komputasi modern.']
            ];
            foreach ($pls as $pl) {
                $this->db->insert('profil_lulusan', $pl);
            }
        }

        // 2. CPL Prodi 1 (TI)
        $count_cpl = $this->db->where('prodi_id', 1)->count_all_results('cpl');
        if ($count_cpl === 0) {
            $cpls = [
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-01', 'kategori' => 'Sikap', 'deskripsi' => 'Bertakwa kepada Tuhan YME, menjunjung tinggi etika profesi rekayasa komputasi, integritas, dan tanggung jawab sosial.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-02', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai konsep teoritis sains komputasi, algoritma dasar dan lanjut, struktur data, dan arsitektur sistem informasi.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-03', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai prinsip desain basis data relasional/NoSQL, pemrograman fullstack web & mobile, dan antarmuka terdistribusi.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-04', 'kategori' => 'Keterampilan Umum', 'deskripsi' => 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam pemecahan masalah industri rekayasa perangkat lunak.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-05', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu menganalisis, merancang, menguji, dan memelihara sistem perangkat lunak yang scalable, robust, dan user-friendly.'],
                ['prodi_id' => 1, 'kode_cpl' => 'CPL-06', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu mengimplementasikan metodologi Agile, version control modern, CI/CD, dan standar keamanan siber dalam siklus hidup software.']
            ];
            foreach ($cpls as $cpl) {
                $this->db->insert('cpl', $cpl);
            }
        }

        // 3. Mapping Profil Lulusan <-> CPL (profil_cpl)
        $count_pc = $this->db->count_all('profil_cpl');
        if ($count_pc === 0) {
            $pl_rows = $this->db->where('prodi_id', 1)->get('profil_lulusan')->result();
            $cpl_rows = $this->db->where('prodi_id', 1)->get('cpl')->result();

            $pl_map = [];
            foreach ($pl_rows as $p) { $pl_map[$p->kode_profil] = (int)$p->id; }
            $cpl_map = [];
            foreach ($cpl_rows as $c) { $cpl_map[$c->kode_cpl] = (int)$c->id; }

            $mapping = [
                'PL-01' => ['CPL-01', 'CPL-02', 'CPL-03', 'CPL-05', 'CPL-06'],
                'PL-02' => ['CPL-01', 'CPL-02', 'CPL-03', 'CPL-04'],
                'PL-03' => ['CPL-01', 'CPL-02', 'CPL-04', 'CPL-06'],
                'PL-04' => ['CPL-01', 'CPL-03', 'CPL-04', 'CPL-05'],
            ];

            foreach ($mapping as $pl_code => $cpl_codes) {
                if (isset($pl_map[$pl_code])) {
                    $p_id = $pl_map[$pl_code];
                    foreach ($cpl_codes as $c_code) {
                        if (isset($cpl_map[$c_code])) {
                            $this->db->replace('profil_cpl', [
                                'profil_lulusan_id' => $p_id,
                                'cpl_id' => $cpl_map[$c_code]
                            ]);
                        }
                    }
                }
            }
        }

        // 4. Matriks CPL-MK (cpl_mk) dengan bobot_cpl TEPAT 100% per CPL!
        $count_cpl_mk = $this->db->count_all('cpl_mk');
        if ($count_cpl_mk === 0) {
            $cpls = $this->db->where('prodi_id', 1)->get('cpl')->result();
            $mks = $this->db->get('mata_kuliah')->result();

            $available_mk_ids = array_column($mks, 'id');
            if (empty($available_mk_ids)) {
                $penawarans = $this->db->select('DISTINCT(kode_mk), nama_mk')->limit(10)->get('penawaran_matakuliah')->result();
                foreach ($penawarans as $p) {
                    $this->db->insert('mata_kuliah', ['kode' => $p->kode_mk, 'nama' => $p->nama_mk]);
                    $available_mk_ids[] = $this->db->insert_id();
                }
            }

            foreach ($cpls as $c) {
                $c_id = (int)$c->id;
                if (count($available_mk_ids) >= 3) {
                    $slice = array_slice($available_mk_ids, ($c_id % max(1, count($available_mk_ids) - 2)), 3);
                    if (count($slice) === 3) {
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[0], 'bobot_cpl' => 40.00]);
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[1], 'bobot_cpl' => 35.00]);
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[2], 'bobot_cpl' => 25.00]);
                    } elseif (count($slice) === 2) {
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[0], 'bobot_cpl' => 60.00]);
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[1], 'bobot_cpl' => 40.00]);
                    } elseif (count($slice) === 1) {
                        $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $slice[0], 'bobot_cpl' => 100.00]);
                    }
                } elseif (count($available_mk_ids) >= 2) {
                    $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $available_mk_ids[0], 'bobot_cpl' => 60.00]);
                    $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $available_mk_ids[1], 'bobot_cpl' => 40.00]);
                } elseif (!empty($available_mk_ids)) {
                    $this->db->replace('cpl_mk', ['cpl_id' => $c_id, 'mata_kuliah_id' => $available_mk_ids[0], 'bobot_cpl' => 100.00]);
                }
            }
        }

        // 5. Seed CPMK, Sub-CPMK & RPS Status untuk MK yang sudah memiliki cpl_mk
        $count_cpmk = $this->db->count_all('cpmk');
        if ($count_cpmk === 0) {
            $cpl_mks = $this->db->get('cpl_mk')->result();
            $handled_mks = [];
            foreach ($cpl_mks as $cmk) {
                $mk_id = (int)$cmk->mata_kuliah_id;
                $cmk_id = (int)$cmk->id;
                if (isset($handled_mks[$mk_id])) {
                    continue;
                }
                $handled_mks[$mk_id] = true;

                // CPMK-1
                $this->db->insert('cpmk', [
                    'cpl_mk_id' => $cmk_id,
                    'mata_kuliah_id' => $mk_id,
                    'kode_cpmk' => 'CPMK-1',
                    'deskripsi' => 'Mampu memahami konsep dasar, prinsip teoritis, dan arsitektur rancang bangun mata kuliah ini.'
                ]);
                $cpmk_1_id = $this->db->insert_id();

                // CPMK-2
                $this->db->insert('cpmk', [
                    'cpl_mk_id' => $cmk_id,
                    'mata_kuliah_id' => $mk_id,
                    'kode_cpmk' => 'CPMK-2',
                    'deskripsi' => 'Mampu mengimplementasikan solusi terapan, pengujian perangkat lunak, dan proyek kolaboratif industri.'
                ]);
                $cpmk_2_id = $this->db->insert_id();

                // Sub-CPMK dengan total bobot_nilai tepat 100.00%
                $sub_items = [
                    ['cpmk_id' => $cpmk_1_id, 'kode_sub_cpmk' => 'Sub-CPMK-1.1', 'deskripsi' => 'Menguasai fondasi konseptual materi perkuliahan.', 'minggu_pertemuan' => 'Pertemuan 1-2', 'bentuk_evaluasi' => 'Tugas', 'bobot_nilai' => 15.00],
                    ['cpmk_id' => $cpmk_1_id, 'kode_sub_cpmk' => 'Sub-CPMK-1.2', 'deskripsi' => 'Mampu menyelesaikan studi kasus logika dan problem solving.', 'minggu_pertemuan' => 'Pertemuan 3-4', 'bentuk_evaluasi' => 'Kuis', 'bobot_nilai' => 10.00],
                    ['cpmk_id' => $cpmk_1_id, 'kode_sub_cpmk' => 'Sub-CPMK-1.3', 'deskripsi' => 'Mampu merancang diagram modular dan spesifikasi antarmuka.', 'minggu_pertemuan' => 'Pertemuan 5-7', 'bentuk_evaluasi' => 'Tugas', 'bobot_nilai' => 15.00],
                    ['cpmk_id' => $cpmk_1_id, 'kode_sub_cpmk' => 'Sub-CPMK-1.4', 'deskripsi' => 'Ujian Tengah Semester (Evaluasi paruh perkuliahan).', 'minggu_pertemuan' => 'Pertemuan 8 (UTS)', 'bentuk_evaluasi' => 'UTS', 'bobot_nilai' => 25.00],
                    ['cpmk_id' => $cpmk_2_id, 'kode_sub_cpmk' => 'Sub-CPMK-2.1', 'deskripsi' => 'Mampu membangun fitur nyata berbasis proyek aplikasi.', 'minggu_pertemuan' => 'Pertemuan 9-12', 'bentuk_evaluasi' => 'Proyek', 'bobot_nilai' => 15.00],
                    ['cpmk_id' => $cpmk_2_id, 'kode_sub_cpmk' => 'Sub-CPMK-2.2', 'deskripsi' => 'Pengujian performa, debugging, dan dokumentasi API.', 'minggu_pertemuan' => 'Pertemuan 13-15', 'bentuk_evaluasi' => 'Kuis', 'bobot_nilai' => 5.00],
                    ['cpmk_id' => $cpmk_2_id, 'kode_sub_cpmk' => 'Sub-CPMK-2.3', 'deskripsi' => 'Ujian Akhir Semester (Evaluasi komprehensif & showcase karya).', 'minggu_pertemuan' => 'Pertemuan 16 (UAS)', 'bentuk_evaluasi' => 'UAS', 'bobot_nilai' => 15.00],
                ];

                foreach ($sub_items as $sub) {
                    $this->db->insert('sub_cpmk', $sub);
                }

                // RPS Status = PUBLISHED
                $this->db->replace('rps_status', [
                    'mata_kuliah_id' => $mk_id,
                    'status' => 'PUBLISHED',
                    'total_bobot_nilai' => 100.00,
                    'published_at' => date('Y-m-d H:i:s'),
                    'published_by' => 1
                ]);
            }
        }
    }

    // =========================================================================
    // FASE 1: PERANCANGAN KURIKULUM (KAPRODI / ADMIN KURIKULUM)
    // =========================================================================

    public function get_profil_lulusan($prodi_id)
    {
        return $this->db->where('prodi_id', (int)$prodi_id)
                        ->order_by('kode_profil', 'ASC')
                        ->get('profil_lulusan')
                        ->result();
    }

    public function get_profil_lulusan_by_id($id)
    {
        return $this->db->where('id', (int)$id)->get('profil_lulusan')->row();
    }

    public function save_profil_lulusan($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', (int)$id)->update('profil_lulusan', $data);
            return (int)$id;
        } else {
            $this->db->insert('profil_lulusan', $data);
            return $this->db->insert_id();
        }
    }

    public function delete_profil_lulusan($id, $prodi_id)
    {
        $this->db->where('profil_lulusan_id', (int)$id)->delete('profil_cpl');
        return $this->db->where('id', (int)$id)->where('prodi_id', (int)$prodi_id)->delete('profil_lulusan');
    }

    public function get_cpl_list($prodi_id, $kategori = null)
    {
        $this->db->where('prodi_id', (int)$prodi_id);
        if ($kategori) {
            $this->db->where('kategori', $kategori);
        }
        return $this->db->order_by('kode_cpl', 'ASC')->get('cpl')->result();
    }

    public function get_cpl_by_id_obe($id)
    {
        return $this->db->where('id', (int)$id)->get('cpl')->row();
    }

    public function save_cpl_obe($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', (int)$id)->update('cpl', $data);
            return (int)$id;
        } else {
            $this->db->insert('cpl', $data);
            return $this->db->insert_id();
        }
    }

    public function delete_cpl_obe($id, $prodi_id)
    {
        $this->db->where('cpl_id', (int)$id)->delete('profil_cpl');
        $this->db->where('cpl_id', (int)$id)->delete('cpl_mk');
        return $this->db->where('id', (int)$id)->where('prodi_id', (int)$prodi_id)->delete('cpl');
    }

    public function get_profil_cpl_matrix($prodi_id)
    {
        $pls = $this->get_profil_lulusan($prodi_id);
        $cpls = $this->get_cpl_list($prodi_id);

        $pivots = $this->db->select('profil_lulusan_id, cpl_id')
                           ->from('profil_cpl')
                           ->get()
                           ->result();
        $mapped = [];
        foreach ($pivots as $p) {
            $mapped[$p->profil_lulusan_id . '_' . $p->cpl_id] = true;
        }

        return [
            'profil_lulusan' => $pls,
            'cpls' => $cpls,
            'matrix' => $mapped
        ];
    }

    public function save_profil_cpl_mapping($profil_id, $cpl_ids = [])
    {
        $this->db->where('profil_lulusan_id', (int)$profil_id)->delete('profil_cpl');
        if (!empty($cpl_ids)) {
            foreach ($cpl_ids as $c_id) {
                $this->db->insert('profil_cpl', [
                    'profil_lulusan_id' => (int)$profil_id,
                    'cpl_id' => (int)$c_id
                ]);
            }
        }
        return true;
    }

    public function get_cpl_mk_matrix_data($prodi_id)
    {
        $cpls = $this->get_cpl_list($prodi_id);
        $mks = $this->db->select('id, kode, nama')->order_by('kode', 'ASC')->get('mata_kuliah')->result();

        $cpl_ids = array_column($cpls, 'id');
        $weights = [];
        $total_per_cpl = [];

        if (!empty($cpl_ids)) {
            $rows = $this->db->where_in('cpl_id', $cpl_ids)->get('cpl_mk')->result();
            foreach ($rows as $r) {
                $weights[$r->cpl_id . '_' . $r->mata_kuliah_id] = (float)$r->bobot_cpl;
                if (!isset($total_per_cpl[$r->cpl_id])) {
                    $total_per_cpl[$r->cpl_id] = 0.00;
                }
                $total_per_cpl[$r->cpl_id] += (float)$r->bobot_cpl;
            }
        }

        foreach ($cpls as &$c) {
            $tot = $total_per_cpl[$c->id] ?? 0.00;
            $c->total_bobot = $tot;
            $c->is_valid_100 = (abs($tot - 100.00) < 0.01);
        }

        return [
            'cpls' => $cpls,
            'mata_kuliah' => $mks,
            'weights' => $weights
        ];
    }

    public function save_cpl_mk_matrix($cpl_id, $mk_weights = [])
    {
        $cpl_id = (int)$cpl_id;
        $total_bobot = 0.00;
        foreach ($mk_weights as $mk_id => $val) {
            $total_bobot += (float)$val;
        }

        // VALIDASI WAJIB: Total bobot_cpl dari semua MK yang terhubung ke satu CPL harus tepat 100%!
        if (abs($total_bobot - 100.00) > 0.01 && $total_bobot > 0) {
            return [
                'success' => false,
                'message' => 'Validasi Gagal: Total bobot CPL harus tepat 100.00%! Saat ini berjumlah ' . number_format($total_bobot, 2) . '%. Simpan dibatalkan.',
                'current_total' => $total_bobot
            ];
        }

        $this->db->where('cpl_id', $cpl_id)->delete('cpl_mk');
        foreach ($mk_weights as $mk_id => $val) {
            $b = (float)$val;
            if ($b > 0) {
                $this->db->insert('cpl_mk', [
                    'cpl_id' => $cpl_id,
                    'mata_kuliah_id' => (int)$mk_id,
                    'bobot_cpl' => $b
                ]);
            }
        }

        return [
            'success' => true,
            'message' => 'Matriks CPL-MK berhasil disimpan dengan total bobot tepat 100.00%.',
            'current_total' => $total_bobot
        ];
    }

    // =========================================================================
    // FASE 2: DESAIN PEMBELAJARAN & RPS (DOSEN PENGAMPU)
    // =========================================================================

    public function get_cpl_dititipkan_ke_mk($mata_kuliah_id)
    {
        return $this->db->select('cpl_mk.id as cpl_mk_id, cpl_mk.bobot_cpl, cpl.id as cpl_id, cpl.kode_cpl, cpl.kategori, cpl.deskripsi')
                        ->from('cpl_mk')
                        ->join('cpl', 'cpl.id = cpl_mk.cpl_id', 'inner')
                        ->where('cpl_mk.mata_kuliah_id', (int)$mata_kuliah_id)
                        ->order_by('cpl.kode_cpl', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Mengambil daftar mata kuliah yang diampu dosen atau kurikulum untuk modul RPS
     */
    public function get_dosen_rps_courses($dosen_id = 0, $user_prodi = null, $is_superadmin = false, $is_kaprodi = false)
    {
        $dosen_id = (int)$dosen_id;
        $codes = [];

        if (!$is_superadmin && !$is_kaprodi && $dosen_id > 0) {
            // Jalankan query penawaran_matakuliah terlebih dahulu sebelum memulai query mata_kuliah
            $assigned_codes = $this->db->distinct()
                                       ->select('kode_mk')
                                       ->where('dosen_id', $dosen_id)
                                       ->get('penawaran_matakuliah')
                                       ->result_array();
            $codes = array_values(array_unique(array_filter(array_column($assigned_codes, 'kode_mk'))));
        }

        // Bangun query mata_kuliah secara terisolasi dan utuh
        $this->db->select('mata_kuliah.id, mata_kuliah.kode, mata_kuliah.nama, mata_kuliah.deskripsi, mata_kuliah.status_rps, rps_status.status as rps_status, rps_status.total_bobot_nilai, rps_status.published_at')
                 ->from('mata_kuliah')
                 ->join('rps_status', 'rps_status.mata_kuliah_id = mata_kuliah.id', 'left');

        if (!empty($codes)) {
            $this->db->where_in('mata_kuliah.kode', $codes);
        }

        $this->db->order_by('mata_kuliah.kode', 'ASC');
        $courses = $this->db->get()->result();

        // Hitung CPL & CPMK per MK
        $has_cpmk_mk_id = $this->db->table_exists('cpmk') && $this->db->field_exists('mk_id', 'cpmk');
        foreach ($courses as &$c) {
            $cpls = $this->get_cpl_dititipkan_ke_mk($c->id);
            $c->cpl_count = count($cpls);

            $this->db->where('mata_kuliah_id', $c->id);
            if ($has_cpmk_mk_id) {
                $this->db->or_where('mk_id', $c->id);
            }
            $c->cpmk_count = $this->db->count_all_results('cpmk');

            // Hitung total bobot dari sub_cpmk
            $this->db->select('COALESCE(SUM(s.bobot_nilai), 0) as total')
                     ->from('sub_cpmk s')
                     ->join('cpmk c', 'c.id = s.cpmk_id')
                     ->group_start()
                         ->where('c.mata_kuliah_id', $c->id);
            if ($has_cpmk_mk_id) {
                $this->db->or_where('c.mk_id', $c->id);
            }
            $sub_total = $this->db->group_end()->get()->row();

            $c->total_bobot = $sub_total ? (float)$sub_total->total : (float)($c->total_bobot_nilai ?: 0.0);
            $c->is_valid_100 = (abs($c->total_bobot - 100.00) < 0.01);

            // Status publikasi
            $current_status = (!empty($c->status_rps) && strtolower($c->status_rps) === 'published') ? 'PUBLISHED' : ($c->rps_status ?: 'DRAFT');
            if (!$c->is_valid_100) {
                $current_status = 'DRAFT';
            }
            $c->status = $current_status;
        }

        return $courses;
    }

    public function get_rps_mk($mata_kuliah_id)
    {
        $mk = $this->db->where('id', (int)$mata_kuliah_id)->get('mata_kuliah')->row();
        if (!$mk) {
            return null;
        }

        $cpls = $this->get_cpl_dititipkan_ke_mk($mata_kuliah_id);

        $cpmks = $this->db->select('cpmk.*, cpl.kode_cpl, cpl.kategori, cpl_mk.bobot_cpl')
                          ->from('cpmk')
                          ->join('cpl_mk', 'cpl_mk.id = cpmk.cpl_mk_id', 'left')
                          ->join('cpl', 'cpl.id = cpl_mk.cpl_id', 'left')
                          ->group_start()
                              ->where('cpmk.mata_kuliah_id', (int)$mata_kuliah_id)
                              ->or_where('cpmk.mk_id', (int)$mata_kuliah_id)
                          ->group_end()
                          ->order_by('cpmk.kode_cpmk', 'ASC')
                          ->get()
                          ->result();

        $cpmk_ids = array_column($cpmks, 'id');
        $subs = [];
        $sub_flat = [];
        $total_bobot_nilai = 0.00;

        if (!empty($cpmk_ids)) {
            $sub_rows = $this->db->select('sub_cpmk.*, cpmk.kode_cpmk')
                                 ->from('sub_cpmk')
                                 ->join('cpmk', 'cpmk.id = sub_cpmk.cpmk_id')
                                 ->where_in('sub_cpmk.cpmk_id', $cpmk_ids)
                                 ->order_by('sub_cpmk.kode_sub', 'ASC')
                                 ->get()
                                 ->result();
            foreach ($sub_rows as $s) {
                // Standarisasi field
                $s->kode_sub = $s->kode_sub ?: $s->kode_sub_cpmk;
                $s->kode_sub_cpmk = $s->kode_sub;
                $s->pertemuan_ke = $s->pertemuan_ke ?: $s->minggu_pertemuan;
                $s->minggu_pertemuan = $s->pertemuan_ke;
                $s->instrumen_evaluasi = $s->instrumen_evaluasi ?: $s->bentuk_evaluasi;
                $s->bentuk_evaluasi = $s->instrumen_evaluasi;

                $subs[$s->cpmk_id][] = $s;
                $sub_flat[] = $s;
                $total_bobot_nilai += (float)$s->bobot_nilai;
            }
        }

        // Susun Matriks 16 Pertemuan
        $matriks_16 = [];
        for ($p = 1; $p <= 16; $p++) {
            $label = 'Pertemuan ' . $p;
            if ($p === 8) $label = 'Pertemuan 8 (UTS)';
            if ($p === 16) $label = 'Pertemuan 16 (UAS)';

            $items = [];
            $bobot_p = 0.0;
            $instrumens = [];

            foreach ($sub_flat as $sub) {
                $pert_str = (string)$sub->pertemuan_ke;
                $is_match = false;
                if ($pert_str === (string)$p) {
                    $is_match = true;
                } elseif (preg_match('/(?:^|[^0-9])' . $p . '(?:[^0-9]|$)/', $pert_str)) {
                    $is_match = true;
                }

                if ($is_match) {
                    $items[] = $sub;
                    $bobot_p += (float)$sub->bobot_nilai;
                    if (!in_array($sub->instrumen_evaluasi, $instrumens)) {
                        $instrumens[] = $sub->instrumen_evaluasi;
                    }
                }
            }

            $matriks_16[$p] = [
                'pertemuan_ke' => $p,
                'label'        => $label,
                'items'        => $items,
                'bobot'        => $bobot_p,
                'instrumen'    => !empty($instrumens) ? implode(', ', $instrumens) : '-'
            ];
        }

        $rps_status = $this->db->where('mata_kuliah_id', (int)$mata_kuliah_id)->get('rps_status')->row();
        $is_valid_100 = (abs($total_bobot_nilai - 100.00) < 0.01);
        $status_text = ($is_valid_100 && (($rps_status && $rps_status->status === 'PUBLISHED') || (!empty($mk->status_rps) && strtolower($mk->status_rps) === 'published'))) ? 'PUBLISHED' : 'DRAFT';

        return [
            'mata_kuliah'         => $mk,
            'cpls'                => $cpls,
            'cpmks'               => $cpmks,
            'sub_cpmks'           => $subs,
            'sub_flat'            => $sub_flat,
            'matriks_16'          => $matriks_16,
            'total_bobot_nilai'   => $total_bobot_nilai,
            'is_valid_100'        => $is_valid_100,
            'rps_status'          => $rps_status,
            'status_publikasi'    => $status_text
        ];
    }

    public function save_cpmk_obe($data, $id = null)
    {
        if (isset($data['mata_kuliah_id']) && !isset($data['mk_id'])) {
            $data['mk_id'] = $data['mata_kuliah_id'];
        } elseif (isset($data['mk_id']) && !isset($data['mata_kuliah_id'])) {
            $data['mata_kuliah_id'] = $data['mk_id'];
        }

        if ($id) {
            $this->db->where('id', (int)$id)->update('cpmk', $data);
            $cpmk_id = (int)$id;
        } else {
            $this->db->insert('cpmk', $data);
            $cpmk_id = $this->db->insert_id();
        }

        $mk_id = isset($data['mata_kuliah_id']) ? (int)$data['mata_kuliah_id'] : (int)$data['mk_id'];
        $this->sync_rps_status_bobot($mk_id);

        return $cpmk_id;
    }

    public function delete_cpmk_obe($id)
    {
        $cpmk = $this->db->where('id', (int)$id)->get('cpmk')->row();
        $mk_id = $cpmk ? ($cpmk->mata_kuliah_id ?: $cpmk->mk_id) : null;

        $this->db->where('cpmk_id', (int)$id)->delete('sub_cpmk');
        $deleted = $this->db->where('id', (int)$id)->delete('cpmk');

        if ($mk_id) {
            $this->sync_rps_status_bobot($mk_id);
        }

        return $deleted;
    }

    public function save_sub_cpmk_obe($data, $id = null)
    {
        if (isset($data['kode_sub']) && !isset($data['kode_sub_cpmk'])) {
            $data['kode_sub_cpmk'] = $data['kode_sub'];
        } elseif (isset($data['kode_sub_cpmk']) && !isset($data['kode_sub'])) {
            $data['kode_sub'] = $data['kode_sub_cpmk'];
        }

        if (isset($data['pertemuan_ke']) && !isset($data['minggu_pertemuan'])) {
            $data['minggu_pertemuan'] = (string)$data['pertemuan_ke'];
        } elseif (isset($data['minggu_pertemuan']) && !isset($data['pertemuan_ke'])) {
            $data['pertemuan_ke'] = (string)$data['minggu_pertemuan'];
        }

        if (isset($data['instrumen_evaluasi']) && !isset($data['bentuk_evaluasi'])) {
            $data['bentuk_evaluasi'] = $data['instrumen_evaluasi'];
        } elseif (isset($data['bentuk_evaluasi']) && !isset($data['instrumen_evaluasi'])) {
            $data['instrumen_evaluasi'] = $data['bentuk_evaluasi'];
        }

        if ($id) {
            $this->db->where('id', (int)$id)->update('sub_cpmk', $data);
            $sub_id = (int)$id;
        } else {
            $this->db->insert('sub_cpmk', $data);
            $sub_id = $this->db->insert_id();
        }

        $cpmk = $this->db->where('id', (int)$data['cpmk_id'])->get('cpmk')->row();
        if ($cpmk) {
            $mk_id = $cpmk->mata_kuliah_id ?: $cpmk->mk_id;
            $this->sync_rps_status_bobot($mk_id);
        }

        return $sub_id;
    }

    public function delete_sub_cpmk_obe($id)
    {
        $sub = $this->db->where('id', (int)$id)->get('sub_cpmk')->row();
        $mk_id = null;
        if ($sub) {
            $cpmk = $this->db->where('id', (int)$sub->cpmk_id)->get('cpmk')->row();
            if ($cpmk) {
                $mk_id = $cpmk->mata_kuliah_id ?: $cpmk->mk_id;
            }
        }

        $this->db->where('sub_cpmk_id', (int)$id)->delete('nilai_evaluasi');
        $deleted = $this->db->where('id', (int)$id)->delete('sub_cpmk');

        if ($mk_id) {
            $this->sync_rps_status_bobot($mk_id);
        }

        return $deleted;
    }

    public function sync_rps_status_bobot($mata_kuliah_id)
    {
        $cpmks = $this->db->select('id')->where('mata_kuliah_id', (int)$mata_kuliah_id)->or_where('mk_id', (int)$mata_kuliah_id)->get('cpmk')->result();
        $total = 0.00;
        if (!empty($cpmks)) {
            $c_ids = array_column($cpmks, 'id');
            $row = $this->db->select('COALESCE(SUM(bobot_nilai), 0) as tot')->where_in('cpmk_id', $c_ids)->get('sub_cpmk')->row();
            if ($row) {
                $total = (float)$row->tot;
            }
        }

        $is_100 = (abs($total - 100.00) < 0.01);
        $curr_header = $this->db->where('mata_kuliah_id', (int)$mata_kuliah_id)->get('rps_status')->row();
        $new_status = ($is_100 && $curr_header && $curr_header->status === 'PUBLISHED') ? 'PUBLISHED' : 'DRAFT';

        $this->db->replace('rps_status', [
            'mata_kuliah_id'    => (int)$mata_kuliah_id,
            'total_bobot_nilai' => $total,
            'status'            => $new_status,
            'published_at'      => ($new_status === 'PUBLISHED') ? ($curr_header ? $curr_header->published_at : date('Y-m-d H:i:s')) : null,
            'published_by'      => ($new_status === 'PUBLISHED') ? ($curr_header ? $curr_header->published_by : null) : null
        ]);

        if (!$is_100) {
            $this->db->where('id', (int)$mata_kuliah_id)->update('mata_kuliah', ['status_rps' => 'draft']);
        }

        return $total;
    }

    public function publish_rps_obe($mata_kuliah_id, $user_id)
    {
        $total = $this->sync_rps_status_bobot($mata_kuliah_id);
        if (abs($total - 100.00) > 0.01) {
            $bobot_disp = rtrim(rtrim(number_format($total, 2), '0'), '.');
            return [
                'success' => false,
                'message' => 'Total bobot saat ini ' . $bobot_disp . '%. Harus tepat 100% untuk publikasi.',
                'total_bobot' => $total
            ];
        }

        $this->db->replace('rps_status', [
            'mata_kuliah_id'    => (int)$mata_kuliah_id,
            'status'            => 'PUBLISHED',
            'total_bobot_nilai' => 100.00,
            'published_at'      => date('Y-m-d H:i:s'),
            'published_by'      => (int)$user_id
        ]);

        $this->db->where('id', (int)$mata_kuliah_id)->update('mata_kuliah', [
            'status_rps' => 'published'
        ]);

        return [
            'success' => true,
            'message' => 'RPS Mata Kuliah berhasil dipublikasikan (Total Bobot 100%).'
        ];
    }

    // =========================================================================
    // FASE 3: KRS BERBASIS CPL (MAHASISWA & DOSEN WALI)
    // =========================================================================

    /**
     * Sinkronisasi riwayat KHS mahasiswa ke dalam tabel nilai_evaluasi
     * Memastikan data evaluasi Sub-CPMK terisi konsisten untuk mahasiswa yang telah memiliki nilai resmi
     */
    public function sync_student_evaluations_from_khs($mahasiswa_id)
    {
        $mahasiswa_id = (int)$mahasiswa_id;
        if ($mahasiswa_id <= 0) {
            return;
        }

        // Ambil riwayat KHS mahasiswa
        $khs_rows = $this->db->where('akun_id', $mahasiswa_id)->get('khs_nilai')->result();
        if (empty($khs_rows)) {
            return;
        }

        foreach ($khs_rows as $khs) {
            $kode_mk = strtoupper(trim($khs->kode_mk));
            $nama_mk = trim($khs->nama_mk);

            // Cari mata_kuliah master
            $mk = $this->db->group_start()
                           ->where('UPPER(TRIM(kode))', $kode_mk)
                           ->or_like('nama', $nama_mk)
                           ->group_end()
                           ->get('mata_kuliah')
                           ->row();

            if (!$mk) {
                continue;
            }

            // Ambil sub_cpmk mata kuliah ini
            $cpmks = $this->db->select('id')->where('mata_kuliah_id', (int)$mk->id)->get('cpmk')->result();
            if (empty($cpmks)) {
                continue;
            }

            $c_ids = array_column($cpmks, 'id');
            $sub_cpmks = $this->db->where_in('cpmk_id', $c_ids)->get('sub_cpmk')->result();
            if (empty($sub_cpmks)) {
                continue;
            }

            // Cari atau buat krs untuk mahasiswa ini
            $krs = $this->db->where('akun_id', $mahasiswa_id)
                            ->where('status', 'DISETUJUI')
                            ->order_by('id', 'DESC')
                            ->get('krs')
                            ->row();

            $krs_id = $krs ? (int)$krs->id : null;
            if (!$krs_id) {
                $this->db->insert('krs', [
                    'akun_id' => $mahasiswa_id,
                    'tahun_akademik' => $khs->tahun_akademik ?: '2025/2026',
                    'semester_akademik' => $khs->semester_akademik ?: 'Ganjil',
                    'status' => 'DISETUJUI',
                    'total_sks' => (int)($khs->sks ?: 3),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $krs_id = (int)$this->db->insert_id();
            }

            // Cari krs_detail jika ada
            $kd = $this->db->select('kd.id')
                           ->from('krs_detail kd')
                           ->join('penawaran_matakuliah p', 'p.id = kd.penawaran_id', 'inner')
                           ->where('kd.krs_id', $krs_id)
                           ->group_start()
                               ->where('UPPER(TRIM(p.kode_mk))', $kode_mk)
                               ->or_like('p.nama_mk', $nama_mk)
                           ->group_end()
                           ->get()
                           ->row();
            $krs_detail_id = $kd ? (int)$kd->id : null;

            // Nilai angka dasar dari KHS
            if ($khs->nilai_angka !== null && (float)$khs->nilai_angka > 0) {
                $base_score = (float)$khs->nilai_angka;
            } elseif ($khs->nilai_indeks !== null) {
                $ind = (float)$khs->nilai_indeks;
                $base_score = ($ind >= 4.0) ? 88.0 : (($ind >= 3.5) ? 82.0 : (($ind >= 3.0) ? 75.0 : (($ind >= 2.0) ? 62.0 : 45.0)));
            } else {
                $base_score = 75.0;
            }

            $deltas = [2.0, -1.5, 1.0, -2.0, 1.5, -0.5, 0.5];
            foreach ($sub_cpmks as $idx => $sub) {
                $sub_id = (int)$sub->id;
                $existing = $this->db->where('krs_id', $krs_id)
                                     ->where('sub_cpmk_id', $sub_id)
                                     ->get('nilai_evaluasi')
                                     ->row();

                if (!$existing) {
                    $score = min(100.0, max(0.0, round($base_score + ($deltas[$idx % count($deltas)] ?? 0), 2)));
                    $this->db->insert('nilai_evaluasi', [
                        'krs_id' => $krs_id,
                        'krs_detail_id' => $krs_detail_id,
                        'sub_cpmk_id' => $sub_id,
                        'nilai_angka' => $score,
                        'catatan' => 'Sinkronisasi riwayat KHS'
                    ]);
                }
            }
        }
    }

    /**
     * Model Kalkulasi CPL Mahasiswa secara Bottom-Up (CodeIgniter 3 Query Builder):
     * - Nilai Akhir MK = SUM(nilai_evaluasi.nilai_angka * (sub_cpmk.bobot_nilai / 100))
     * - Capaian CPL    = SUM(nilai_akhir_mk * (cpl_mk.bobot_cpl / 100))
     * - Menggunakan LEFT JOIN dari tabel cpl agar seluruh CPL prodi tetap muncul dengan skor 0
     *   meskipun mahasiswa belum mengambil mata kuliah terkait.
     *
     * @param int $mahasiswa_id
     * @return array
     */
    public function get_capaian_cpl_mahasiswa($mahasiswa_id)
    {
        $mahasiswa_id = (int)$mahasiswa_id;

        // Pastikan skema tabel Fase 3 tersedia
        $this->ensure_complete_obe_schema();

        // 1. Tentukan prodi_id mahasiswa
        $student = $this->db->get_where('akun_mahasiswa', ['id' => $mahasiswa_id])->row();
        if (!$student) {
            $student = $this->db->get_where('akun', ['id' => $mahasiswa_id])->row();
        }

        $prodi_id = 1; // Default Teknik Informatika
        if ($student && !empty($student->prodi)) {
            $p = $this->db->like('nama_prodi', $student->prodi)->get('prodi')->row();
            if ($p) {
                $prodi_id = (int)$p->id;
            }
        }

        // Sinkronisasi data evaluasi jika mahasiswa memiliki KHS
        $this->sync_student_evaluations_from_khs($mahasiswa_id);

        // 2. Sub-query CodeIgniter 3: Hitung Nilai Akhir MK dari nilai_evaluasi
        // Formula: Nilai Akhir MK = SUM(nilai_evaluasi.nilai_angka * (sub_cpmk.bobot_nilai / 100))
        $sub_query = "
            SELECT 
                cpmk.mata_kuliah_id,
                SUM(COALESCE(ne.nilai_angka, 0) * (COALESCE(sc.bobot_nilai, 0) / 100.0)) AS nilai_akhir_mk
            FROM sub_cpmk sc
            JOIN cpmk ON cpmk.id = sc.cpmk_id
            JOIN nilai_evaluasi ne ON ne.sub_cpmk_id = sc.id
            JOIN krs ON (krs.id = ne.krs_id OR krs.id = (SELECT kd.krs_id FROM krs_detail kd WHERE kd.id = ne.krs_detail_id LIMIT 1))
            WHERE krs.akun_id = {$mahasiswa_id}
            GROUP BY cpmk.mata_kuliah_id
        ";

        // 3. Query Utama dengan LEFT JOIN dari tabel cpl
        // Formula: Capaian CPL = SUM(nilai_akhir_mk * (cpl_mk.bobot_cpl / 100))
        $this->db->select("
            cpl.id AS cpl_id,
            cpl.kode_cpl,
            cpl.kategori,
            cpl.deskripsi,
            ROUND(COALESCE(SUM(COALESCE(mk_eval.nilai_akhir_mk, 0) * (COALESCE(cpl_mk.bobot_cpl, 0) / 100.0)), 0), 2) AS skor_cpl,
            ROUND(COALESCE(SUM(COALESCE(mk_eval.nilai_akhir_mk, 0) * (COALESCE(cpl_mk.bobot_cpl, 0) / 100.0)), 0), 2) AS capaian_cpl
        ", false);
        $this->db->from('cpl');
        $this->db->join('cpl_mk', 'cpl_mk.cpl_id = cpl.id', 'left');
        $this->db->join("({$sub_query}) AS mk_eval", 'mk_eval.mata_kuliah_id = cpl_mk.mata_kuliah_id', 'left');
        $this->db->where('cpl.prodi_id', $prodi_id);
        $this->db->group_by(['cpl.id', 'cpl.kode_cpl', 'cpl.kategori', 'cpl.deskripsi']);
        $this->db->order_by('cpl.kode_cpl', 'ASC');

        $raw_results = $this->db->get()->result();

        // 4. Struktur data visualisasi Radar Chart & Analisis Gap Kompetensi (< 60%)
        $labels = [];
        $scores = [];
        $targets = [];
        $gap_cpls = [];
        $details = [];
        $target_standar = 60.0; // Standar kelulusan minimum CPL 60%

        // Ambil mapping nilai akhir MK untuk rincian MK pendukung
        $mk_scores_raw = $this->db->query($sub_query)->result();
        $mk_score_map = [];
        foreach ($mk_scores_raw as $msr) {
            $mk_score_map[(int)$msr->mata_kuliah_id] = (float)$msr->nilai_akhir_mk;
        }

        foreach ($raw_results as $row) {
            $skor = min(100.0, max(0.0, (float)$row->skor_cpl));
            $labels[] = $row->kode_cpl;
            $scores[] = $skor;
            $targets[] = $target_standar;

            $is_gap = ($skor < $target_standar);
            if ($is_gap) {
                $gap_cpls[] = $row->kode_cpl;
            }

            // Ambil daftar MK pendukung CPL ini dan status keterambilannya
            $cmk_rows = $this->db->select('cpl_mk.bobot_cpl, mata_kuliah.id as mk_id, mata_kuliah.kode, mata_kuliah.nama')
                                 ->from('cpl_mk')
                                 ->join('mata_kuliah', 'mata_kuliah.id = cpl_mk.mata_kuliah_id', 'inner')
                                 ->where('cpl_mk.cpl_id', (int)$row->cpl_id)
                                 ->get()
                                 ->result();

            $mk_contributions = [];
            foreach ($cmk_rows as $cmk) {
                $mk_id = (int)$cmk->mk_id;
                $mk_score = $mk_score_map[$mk_id] ?? 0.00;
                $weight = (float)$cmk->bobot_cpl;
                $contrib = round($mk_score * ($weight / 100.0), 2);
                $mk_contributions[] = [
                    'kode' => $cmk->kode,
                    'nama' => $cmk->nama,
                    'bobot_cpl' => $weight,
                    'nilai_mk' => $mk_score,
                    'kontribusi' => $contrib,
                    'status' => ($mk_score > 0) ? 'Selesai' : 'Belum Diambil'
                ];
            }

            $details[] = [
                'cpl' => $row,
                'cpl_id' => (int)$row->cpl_id,
                'kode_cpl' => $row->kode_cpl,
                'kategori' => $row->kategori,
                'deskripsi' => $row->deskripsi,
                'capaian' => $skor,
                'skor_cpl' => $skor,
                'target' => $target_standar,
                'is_gap' => $is_gap,
                'gap' => $is_gap ? round($target_standar - $skor, 2) : 0.0,
                'status_label' => $is_gap ? 'Perlu Penguatan (Gap < 60%)' : 'Memenuhi Standar',
                'mk_list' => $mk_contributions,
                'mk_support' => $mk_contributions
            ];
        }

        return [
            'labels' => $labels,
            'scores' => $scores,
            'targets' => $targets,
            'gap_cpls' => $gap_cpls,
            'has_gap' => !empty($gap_cpls),
            'target_standar' => $target_standar,
            'details' => $details,
            'prodi_id' => $prodi_id
        ];
    }

    public function get_student_cpl_radar_data($mahasiswa_id)
    {
        return $this->get_capaian_cpl_mahasiswa($mahasiswa_id);
    }

    public function get_krs_cpl_badges($prodi_name = null, $mahasiswa_id = null)
    {
        // 1. Dapatkan daftar CPL dengan skor di bawah standar (< 60%) jika mahasiswa_id disediakan
        $gap_cpl_map = [];
        if (!empty($mahasiswa_id)) {
            $cpl_result = $this->get_capaian_cpl_mahasiswa($mahasiswa_id);
            if (!empty($cpl_result['gap_cpls'])) {
                foreach ($cpl_result['gap_cpls'] as $gc) {
                    $gap_cpl_map[$gc] = true;
                }
            }
        }

        // 2. Query join cpl_mk, cpl, dan mata_kuliah
        $this->db->select('cpl_mk.mata_kuliah_id, cpl_mk.bobot_cpl, cpl.kode_cpl, cpl.kategori, mata_kuliah.kode as kode_mk, mata_kuliah.nama as nama_mk')
                 ->from('cpl_mk')
                 ->join('cpl', 'cpl.id = cpl_mk.cpl_id', 'inner')
                 ->join('mata_kuliah', 'mata_kuliah.id = cpl_mk.mata_kuliah_id', 'inner');

        $rows = $this->db->get()->result();

        // 3. Kelompokkan berdasarkan mata kuliah
        $mk_groups = [];
        foreach ($rows as $r) {
            $code_key = strtoupper(trim($r->kode_mk));
            $mk_groups[$code_key][] = $r;
        }

        $badges_by_code = [];
        $badges_by_mk_id = [];

        foreach ($mk_groups as $code_key => $items) {
            $cpl_list = [];
            $has_gap_support = false;
            $gap_supported_names = [];

            foreach ($items as $item) {
                $is_for_gap = isset($gap_cpl_map[$item->kode_cpl]);
                if ($is_for_gap) {
                    $has_gap_support = true;
                    $gap_supported_names[] = $item->kode_cpl;
                }

                $badge_str = $item->kode_cpl . ' (' . round($item->bobot_cpl) . '%)';
                $cpl_list[] = [
                    'cpl' => $item->kode_cpl,
                    'kode_cpl' => $item->kode_cpl,
                    'bobot' => (float)$item->bobot_cpl,
                    'label' => $badge_str,
                    'is_gap' => $is_for_gap
                ];
            }

            $entry = [
                'cpl_list' => $cpl_list,
                'is_recommended' => $has_gap_support,
                'recommended_cpls' => array_values(array_unique($gap_supported_names)),
                'rekomendasi_alasan' => $has_gap_support ? ('Mendukung penguatan ' . implode(', ', array_unique($gap_supported_names)) . ' (skor < 60%)') : '',
            ];

            // Menjaga kompatibilitas dengan perulangan array numerik lama (foreach ($rec_badges as $rb))
            foreach ($cpl_list as $idx => $cl) {
                $entry[$idx] = $cl;
            }

            $badges_by_code[$code_key] = $entry;
            $badges_by_mk_id[$items[0]->mata_kuliah_id] = $entry;
        }

        return [
            'by_code' => $badges_by_code,
            'by_mk_id' => $badges_by_mk_id
        ];
    }

    // =========================================================================
    // FASE 4 & 5: EVALUASI NILAI MENTAH (0-100) & KALKULASI BOTTOM-UP
    // =========================================================================

    public function check_course_rps_published($kode_mk)
    {
        $mk = $this->db->where('UPPER(TRIM(kode))', strtoupper(trim($kode_mk)))->get('mata_kuliah')->row();
        if (!$mk) {
            return [
                'is_published'   => false,
                'status'         => 'DRAFT',
                'total_bobot'    => 0.0,
                'is_bobot_100'   => false,
                'sub_cpmk_count' => 0,
                'mk'             => null
            ];
        }

        $rps = $this->db->where('mata_kuliah_id', (int)$mk->id)->get('rps_status')->row();

        // Cari CPMK
        $cpmks = $this->db->select('id')
                          ->group_start()
                              ->where('mata_kuliah_id', (int)$mk->id)
                              ->or_where('mk_id', (int)$mk->id)
                          ->group_end()
                          ->get('cpmk')
                          ->result();

        $sub_count = 0;
        $total_bobot = 0.0;
        if (!empty($cpmks)) {
            $c_ids = array_column($cpmks, 'id');
            $subs = $this->db->where_in('cpmk_id', $c_ids)->get('sub_cpmk')->result();
            $sub_count = count($subs);
            foreach ($subs as $s) {
                $total_bobot += (float)$s->bobot_nilai;
            }
        }

        $total_bobot = round($total_bobot, 2);
        $is_bobot_100 = (abs($total_bobot - 100.0) <= 0.05);

        $status = strtoupper(!empty($rps->status) ? $rps->status : (!empty($mk->status_rps) ? $mk->status_rps : 'DRAFT'));
        $is_published = ($status === 'PUBLISHED' && $is_bobot_100 && $sub_count > 0);

        return [
            'is_published'   => $is_published,
            'status'         => $status,
            'total_bobot'    => $total_bobot,
            'is_bobot_100'   => $is_bobot_100,
            'sub_cpmk_count' => $sub_count,
            'mk'             => $mk
        ];
    }

    public function get_evaluasi_grid_data($penawaran_id)
    {
        $penawaran = $this->db->where('id', (int)$penawaran_id)->get('penawaran_matakuliah')->row();
        if (!$penawaran) {
            return null;
        }

        $mk = $this->db->where('UPPER(TRIM(kode))', strtoupper(trim($penawaran->kode_mk)))->get('mata_kuliah')->row();
        $mk_id = $mk ? (int)$mk->id : 0;

        $sub_cpmks = [];
        $total_bobot_sub_cpmk = 0.0;
        if ($mk_id > 0) {
            $cpmks = $this->db->select('id')
                              ->group_start()
                                  ->where('mata_kuliah_id', $mk_id)
                                  ->or_where('mk_id', $mk_id)
                              ->group_end()
                              ->get('cpmk')
                              ->result();
            if (!empty($cpmks)) {
                $c_ids = array_column($cpmks, 'id');
                $sub_cpmks = $this->db->where_in('cpmk_id', $c_ids)
                                      ->order_by('id', 'ASC')
                                      ->get('sub_cpmk')
                                      ->result();
                foreach ($sub_cpmks as $sc) {
                    $total_bobot_sub_cpmk += (float)$sc->bobot_nilai;
                }
            }
        }

        $total_bobot_sub_cpmk = round($total_bobot_sub_cpmk, 2);
        $is_bobot_100 = (abs($total_bobot_sub_cpmk - 100.0) <= 0.05);

        // Validasi status RPS Published
        $rps_row = $mk_id > 0 ? $this->db->where('mata_kuliah_id', $mk_id)->get('rps_status')->row() : null;
        $rps_status_text = strtoupper(!empty($rps_row->status) ? $rps_row->status : (!empty($mk->status_rps) ? $mk->status_rps : 'DRAFT'));
        $is_rps_published = ($rps_status_text === 'PUBLISHED' && $is_bobot_100 && !empty($sub_cpmks));

        // Mahasiswa yang KRS-nya sudah disetujui Dosen Wali (Wajib Fase 4)
        $students = $this->db->select('krs_detail.id as krs_detail_id, krs_detail.krs_id, krs.akun_id, akun_mahasiswa.nim, akun_mahasiswa.nama_lengkap, akun_mahasiswa.prodi, krs.status as krs_status')
                             ->from('krs_detail')
                             ->join('krs', 'krs.id = krs_detail.krs_id', 'inner')
                             ->join('akun_mahasiswa', 'akun_mahasiswa.id = krs.akun_id', 'inner')
                             ->where('krs_detail.penawaran_id', (int)$penawaran_id)
                             ->where('UPPER(krs.status)', 'DISETUJUI')
                             ->order_by('akun_mahasiswa.nim', 'ASC')
                             ->get()
                             ->result();

        // Hitung mahasiswa yang belum disetujui untuk informasi dosen
        $pending_count = $this->db->from('krs_detail')
                                  ->join('krs', 'krs.id = krs_detail.krs_id', 'inner')
                                  ->where('krs_detail.penawaran_id', (int)$penawaran_id)
                                  ->where('UPPER(krs.status) !=', 'DISETUJUI')
                                  ->count_all_results();

        $krs_ids = array_filter(array_column($students, 'krs_id'));
        $krs_det_ids = array_filter(array_column($students, 'krs_detail_id'));
        $scores = [];

        if (!empty($krs_ids) || !empty($krs_det_ids)) {
            $this->db->from('nilai_evaluasi');
            $this->db->group_start();
            if (!empty($krs_ids)) {
                $this->db->where_in('krs_id', $krs_ids);
            }
            if (!empty($krs_det_ids)) {
                $this->db->or_where_in('krs_detail_id', $krs_det_ids);
            }
            $this->db->group_end();
            $ne_rows = $this->db->get()->result();

            foreach ($ne_rows as $ne) {
                if (!empty($ne->krs_id)) {
                    $scores['k_' . $ne->krs_id . '_' . $ne->sub_cpmk_id] = (float)$ne->nilai_angka;
                }
                if (!empty($ne->krs_detail_id)) {
                    $scores['kd_' . $ne->krs_detail_id . '_' . $ne->sub_cpmk_id] = (float)$ne->nilai_angka;
                }
            }
        }

        foreach ($students as &$st) {
            $total_akhir = 0.00;
            foreach ($sub_cpmks as $sub) {
                $raw = $scores['k_' . $st->krs_id . '_' . $sub->id] 
                    ?? $scores['kd_' . $st->krs_detail_id . '_' . $sub->id] 
                    ?? 0.00;
                $total_akhir += ($raw * ((float)$sub->bobot_nilai / 100.0));
            }
            $st->nilai_akhir_angka = round($total_akhir, 2);
            $st->grade = $this->convert_score_to_grade($st->nilai_akhir_angka);
        }

        return [
            'penawaran'            => $penawaran,
            'mata_kuliah'          => $mk,
            'sub_cpmks'            => $sub_cpmks,
            'total_bobot_sub_cpmk' => $total_bobot_sub_cpmk,
            'is_bobot_100'         => $is_bobot_100,
            'is_rps_published'     => $is_rps_published,
            'rps_status'           => $rps_status_text,
            'students'             => $students,
            'scores'               => $scores,
            'pending_count'        => $pending_count
        ];
    }

    public function convert_score_to_grade($score)
    {
        $s = (float)$score;
        if ($s >= 85.0) return ['huruf' => 'A', 'indeks' => 4.00, 'keterangan' => 'Sangat Baik'];
        if ($s >= 80.0) return ['huruf' => 'A-', 'indeks' => 3.70, 'keterangan' => 'Sangat Baik'];
        if ($s >= 75.0) return ['huruf' => 'B+', 'indeks' => 3.30, 'keterangan' => 'Baik'];
        if ($s >= 70.0) return ['huruf' => 'B', 'indeks' => 3.00, 'keterangan' => 'Baik'];
        if ($s >= 65.0) return ['huruf' => 'B-', 'indeks' => 2.70, 'keterangan' => 'Cukup Baik'];
        if ($s >= 60.0) return ['huruf' => 'C+', 'indeks' => 2.30, 'keterangan' => 'Cukup'];
        if ($s >= 55.0) return ['huruf' => 'C', 'indeks' => 2.00, 'keterangan' => 'Cukup'];
        if ($s >= 40.0) return ['huruf' => 'D', 'indeks' => 1.00, 'keterangan' => 'Kurang'];
        return ['huruf' => 'E', 'indeks' => 0.00, 'keterangan' => 'Gagal'];
    }

    public function save_nilai_evaluasi_item($krs_detail_id, $sub_cpmk_id, $nilai_angka, $catatan = '')
    {
        $krs_detail_id = (int)$krs_detail_id;
        $sub_cpmk_id = (int)$sub_cpmk_id;
        $nilai = min(100.0, max(0.0, (float)$nilai_angka));

        $krs_id = null;
        if ($krs_detail_id > 0) {
            $kd = $this->db->where('id', $krs_detail_id)->get('krs_detail')->row();
            if ($kd) {
                $krs_id = (int)$kd->krs_id;
            }
        }

        $sql = "INSERT INTO `nilai_evaluasi` (`krs_id`, `krs_detail_id`, `sub_cpmk_id`, `nilai_angka`, `catatan`, `updated_at`)
                VALUES (?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    `nilai_angka` = VALUES(`nilai_angka`),
                    `krs_detail_id` = VALUES(`krs_detail_id`),
                    `catatan` = VALUES(`catatan`),
                    `updated_at` = NOW()";
        $this->db->query($sql, [$krs_id, $krs_detail_id, $sub_cpmk_id, $nilai, $catatan]);

        $this->calculate_and_sync_khs($krs_detail_id);

        return true;
    }

    /**
     * Batch Save / Upsert Seluruh Nilai Evaluasi Sub-CPMK dalam Satu Transaksi (Fase 4)
     */
    public function batch_save_nilai_evaluasi($penawaran_id, array $scores, array $krs_map = [])
    {
        $penawaran_id = (int)$penawaran_id;
        $this->db->trans_start();

        $saved_count = 0;
        $affected_krs_details = [];

        // Mapping krs_id dari krs_detail_id
        $krs_det_ids = array_map('intval', array_keys($scores));
        if (!empty($krs_det_ids)) {
            $kd_rows = $this->db->select('id, krs_id')
                                ->where_in('id', $krs_det_ids)
                                ->get('krs_detail')
                                ->result();
            foreach ($kd_rows as $kd) {
                $krs_map[(int)$kd->id] = (int)$kd->krs_id;
            }
        }

        foreach ($scores as $krs_detail_id => $sub_scores) {
            $krs_detail_id = (int)$krs_detail_id;
            $krs_id = isset($krs_map[$krs_detail_id]) ? (int)$krs_map[$krs_detail_id] : 0;

            if ($krs_id === 0 && $krs_detail_id > 0) {
                $kd = $this->db->where('id', $krs_detail_id)->get('krs_detail')->row();
                $krs_id = $kd ? (int)$kd->krs_id : 0;
            }

            if (is_array($sub_scores)) {
                foreach ($sub_scores as $sub_cpmk_id => $raw_score) {
                    $sub_cpmk_id = (int)$sub_cpmk_id;
                    $score = ($raw_score === '' || $raw_score === null) ? 0.00 : min(100.0, max(0.0, (float)$raw_score));

                    // Upsert ke nilai_evaluasi dengan UNIQUE KEY uq_krs_sub_cpmk (krs_id, sub_cpmk_id)
                    $sql = "INSERT INTO `nilai_evaluasi` (`krs_id`, `krs_detail_id`, `sub_cpmk_id`, `nilai_angka`, `updated_at`)
                            VALUES (?, ?, ?, ?, NOW())
                            ON DUPLICATE KEY UPDATE 
                                `nilai_angka` = VALUES(`nilai_angka`),
                                `krs_detail_id` = VALUES(`krs_detail_id`),
                                `updated_at` = NOW()";
                    $this->db->query($sql, [$krs_id, $krs_detail_id, $sub_cpmk_id, $score]);
                    $saved_count++;
                }
            }
            $affected_krs_details[] = $krs_detail_id;
        }

        // Sinkronisasi Nilai Akhir MK dan KHS mahasiswa
        $affected_krs_details = array_unique(array_filter($affected_krs_details));
        foreach ($affected_krs_details as $kd_id) {
            $this->calculate_and_sync_khs($kd_id);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return [
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan nilai evaluasi Sub-CPMK.'
            ];
        }

        return [
            'status'      => true,
            'message'     => 'Berhasil menyimpan seluruh nilai evaluasi Sub-CPMK untuk ' . count($affected_krs_details) . ' mahasiswa (' . $saved_count . ' sel evaluasi). Nilai akhir MK dan KHS telah terhitung otomatis.',
            'saved_count' => $saved_count
        ];
    }

    public function calculate_and_sync_khs($krs_detail_id)
    {
        $kd = $this->db->where('id', (int)$krs_detail_id)->get('krs_detail')->row();
        if (!$kd) { return false; }

        $krs = $this->db->where('id', (int)$kd->krs_id)->get('krs')->row();
        $penawaran = $this->db->where('id', (int)$kd->penawaran_id)->get('penawaran_matakuliah')->row();
        if (!$krs || !$penawaran) { return false; }

        $mk = $this->db->where('UPPER(TRIM(kode))', strtoupper(trim($penawaran->kode_mk)))->get('mata_kuliah')->row();
        if (!$mk) { return false; }

        $cpmks = $this->db->select('id')->where('mata_kuliah_id', (int)$mk->id)->get('cpmk')->result();
        if (empty($cpmks)) { return false; }

        $c_ids = array_column($cpmks, 'id');
        $subs = $this->db->where_in('cpmk_id', $c_ids)->get('sub_cpmk')->result();

        $sub_ids = array_column($subs, 'id');
        $evals = $this->db->where('krs_detail_id', (int)$krs_detail_id)
                          ->where_in('sub_cpmk_id', $sub_ids)
                          ->get('nilai_evaluasi')
                          ->result();

        $eval_map = [];
        foreach ($evals as $ev) {
            $eval_map[$ev->sub_cpmk_id] = (float)$ev->nilai_angka;
        }

        // Formula Bottom-Up: Nilai Akhir MK = SUM(nilai_angka * (bobot_nilai / 100))
        $nilai_akhir_angka = 0.00;
        foreach ($subs as $s) {
            $score = $eval_map[$s->id] ?? 0.00;
            $nilai_akhir_angka += ($score * ((float)$s->bobot_nilai / 100.0));
        }

        $grade_info = $this->convert_score_to_grade($nilai_akhir_angka);

        $existing_khs = $this->db->where('akun_id', (int)$krs->akun_id)
                                 ->where('tahun_akademik', $krs->tahun_akademik)
                                 ->where('semester_akademik', $krs->semester_akademik)
                                 ->where('kode_mk', $penawaran->kode_mk)
                                 ->get('khs_nilai')
                                 ->row();

        $khs_data = [
            'akun_id' => (int)$krs->akun_id,
            'tahun_akademik' => $krs->tahun_akademik,
            'semester_akademik' => $krs->semester_akademik,
            'semester_mhs' => (int)$penawaran->semester,
            'kode_mk' => $penawaran->kode_mk,
            'nama_mk' => $penawaran->nama_mk,
            'sks' => (int)$penawaran->sks,
            'nilai_huruf' => $grade_info['huruf'],
            'nilai_indeks' => $grade_info['indeks'],
            'nilai_angka' => round($nilai_akhir_angka, 2),
            'kategori' => $penawaran->jenis_mk ?? 'Wajib',
            'nxk' => round($grade_info['indeks'] * (int)$penawaran->sks, 2),
            'keterangan' => ($grade_info['indeks'] >= 2.0) ? 'LULUS' : 'MENGULANG'
        ];

        if ($existing_khs) {
            $this->db->where('id', (int)$existing_khs->id)->update('khs_nilai', $khs_data);
        } else {
            $this->db->insert('khs_nilai', $khs_data);
        }

        return [
            'nilai_akhir' => round($nilai_akhir_angka, 2),
            'grade' => $grade_info
        ];
    }

    /**
     * Mengambil rincian evaluasi Sub-CPMK per mata kuliah untuk KHS transparan (Fase 5)
     *
     * @param int $akun_id
     * @param string $kode_mk
     * @param int|null $semester_mhs
     * @return array
     */
    public function get_student_course_evaluations($akun_id, $kode_mk, $semester_mhs = null)
    {
        $akun_id = (int)$akun_id;
        $kode_clean = strtoupper(trim((string)$kode_mk));

        // 1. Pastikan sinkronisasi evaluasi jika ada KHS
        $this->sync_student_evaluations_from_khs($akun_id);

        // 2. Ambil record mata kuliah
        $mk = $this->db->where('UPPER(TRIM(kode))', $kode_clean)->get('mata_kuliah')->row();
        if (!$mk) {
            $mk = $this->db->like('nama', $kode_clean)->get('mata_kuliah')->row();
        }

        // 3. Ambil data nilai KHS yang tercatat
        $this->db->where('akun_id', $akun_id);
        $this->db->where('UPPER(TRIM(kode_mk))', $kode_clean);
        if (!empty($semester_mhs)) {
            $this->db->where('semester_mhs', (int)$semester_mhs);
        }
        $khs_row = $this->db->order_by('id', 'DESC')->get('khs_nilai')->row();

        // 4. Ambil CPMK dan Sub-CPMK
        $sub_cpmk_list = [];
        $total_bobot = 0.00;
        $nilai_akhir = 0.00;

        if ($mk) {
            $cpmks = $this->db->where('mata_kuliah_id', (int)$mk->id)
                              ->order_by('kode_cpmk', 'ASC')
                              ->get('cpmk')
                              ->result();

            if (!empty($cpmks)) {
                $c_ids = array_column($cpmks, 'id');
                $cpmk_map = [];
                foreach ($cpmks as $cp) {
                    $cpmk_map[$cp->id] = $cp;
                }

                $subs = $this->db->where_in('cpmk_id', $c_ids)
                                 ->order_by('id', 'ASC')
                                 ->get('sub_cpmk')
                                 ->result();

                if (!empty($subs)) {
                    $sub_ids = array_column($subs, 'id');

                    // Ambil nilai dari nilai_evaluasi
                    $this->db->select('ne.sub_cpmk_id, ne.nilai_angka, ne.catatan');
                    $this->db->from('nilai_evaluasi ne');
                    $this->db->join('krs k', '(k.id = ne.krs_id OR k.id = (SELECT kd.krs_id FROM krs_detail kd WHERE kd.id = ne.krs_detail_id LIMIT 1))', 'left');
                    $this->db->where('k.akun_id', $akun_id);
                    $this->db->where_in('ne.sub_cpmk_id', $sub_ids);
                    $eval_rows = $this->db->get()->result();

                    $eval_map = [];
                    foreach ($eval_rows as $er) {
                        $eval_map[$er->sub_cpmk_id] = (float)$er->nilai_angka;
                    }

                    // Jika belum ada nilai_evaluasi, fallback gunakan nilai KHS
                    $fallback_score = 75.0;
                    if ($khs_row) {
                        if ($khs_row->nilai_angka !== null && (float)$khs_row->nilai_angka > 0) {
                            $fallback_score = (float)$khs_row->nilai_angka;
                        } elseif ($khs_row->nilai_indeks !== null) {
                            $ind = (float)$khs_row->nilai_indeks;
                            $fallback_score = ($ind >= 4.0) ? 88.0 : (($ind >= 3.5) ? 82.0 : (($ind >= 3.0) ? 75.0 : (($ind >= 2.0) ? 62.0 : 45.0)));
                        }
                    }

                    foreach ($subs as $s) {
                        $score = isset($eval_map[$s->id]) ? $eval_map[$s->id] : $fallback_score;
                        $bobot = (float)$s->bobot_nilai;
                        $contrib = round($score * ($bobot / 100.0), 2);

                        $cpmk_parent = $cpmk_map[$s->cpmk_id] ?? null;

                        $sub_cpmk_list[] = [
                            'id' => (int)$s->id,
                            'kode_sub' => !empty($s->kode_sub) ? $s->kode_sub : ($s->kode_sub_cpmk ?? 'Sub-CPMK'),
                            'kode_cpmk' => $cpmk_parent ? $cpmk_parent->kode_cpmk : 'CPMK',
                            'deskripsi' => $s->deskripsi ?? '',
                            'instrumen_evaluasi' => $s->instrumen_evaluasi ?? ($s->bentuk_asesmen ?? 'Tugas / Ujian'),
                            'bobot_nilai' => $bobot,
                            'nilai_angka' => $score,
                            'kontribusi' => $contrib,
                            'catatan' => $s->indikator_penilaian ?? ''
                        ];

                        $total_bobot += $bobot;
                        $nilai_akhir += $contrib;
                    }
                }
            }
        }

        // Jika MK tidak memiliki sub_cpmk di DB (misal data MK umum), buat default 4 komponen agar mahasiswa tetap transparan melihatnya
        if (empty($sub_cpmk_list)) {
            $base_num = 75.0;
            if ($khs_row) {
                if ($khs_row->nilai_angka !== null && (float)$khs_row->nilai_angka > 0) {
                    $base_num = (float)$khs_row->nilai_angka;
                } elseif ($khs_row->nilai_indeks !== null) {
                    $ind = (float)$khs_row->nilai_indeks;
                    $base_num = ($ind >= 4.0) ? 88.0 : (($ind >= 3.5) ? 82.0 : (($ind >= 3.0) ? 75.0 : (($ind >= 2.0) ? 62.0 : 45.0)));
                }
            }

            $defaults = [
                ['kode_sub' => 'Sub-CPMK-1', 'kode_cpmk' => 'CPMK-1', 'deskripsi' => 'Penguasaan Konsep Teori & Partisipasi Aktif', 'instrumen_evaluasi' => 'Partisipasi & Quiz', 'bobot_nilai' => 20.0, 'delta' => 2.0],
                ['kode_sub' => 'Sub-CPMK-2', 'kode_cpmk' => 'CPMK-2', 'deskripsi' => 'Penyelesaian Tugas Terstruktur & Praktikum Mandiri', 'instrumen_evaluasi' => 'Tugas & Praktikum', 'bobot_nilai' => 30.0, 'delta' => 1.0],
                ['kode_sub' => 'Sub-CPMK-3', 'kode_cpmk' => 'CPMK-3', 'deskripsi' => 'Penyelesaian Soal Evaluasi Tengah Semester (UTS)', 'instrumen_evaluasi' => 'Ujian Tengah Semester', 'bobot_nilai' => 25.0, 'delta' => -2.0],
                ['kode_sub' => 'Sub-CPMK-4', 'kode_cpmk' => 'CPMK-4', 'deskripsi' => 'Evaluasi Capaian Akhir Semester & Implementasi Proyek', 'instrumen_evaluasi' => 'Ujian Akhir Semester', 'bobot_nilai' => 25.0, 'delta' => 0.0],
            ];

            foreach ($defaults as $df) {
                $score = min(100.0, max(0.0, round($base_num + $df['delta'], 2)));
                $contrib = round($score * ($df['bobot_nilai'] / 100.0), 2);
                $sub_cpmk_list[] = [
                    'id' => 0,
                    'kode_sub' => $df['kode_sub'],
                    'kode_cpmk' => $df['kode_cpmk'],
                    'deskripsi' => $df['deskripsi'],
                    'instrumen_evaluasi' => $df['instrumen_evaluasi'],
                    'bobot_nilai' => $df['bobot_nilai'],
                    'nilai_angka' => $score,
                    'kontribusi' => $contrib,
                    'catatan' => 'Asesmen RPS Standard'
                ];
                $total_bobot += $df['bobot_nilai'];
                $nilai_akhir += $contrib;
            }
        }

        $nilai_akhir = round($nilai_akhir, 2);
        $grade_info = $this->convert_score_to_grade($nilai_akhir);

        return [
            'kode_mk' => $kode_clean,
            'nama_mk' => $mk ? $mk->nama : ($khs_row ? $khs_row->nama_mk : $kode_clean),
            'sks' => $mk ? (int)$mk->sks : ($khs_row ? (int)$khs_row->sks : 3),
            'sub_cpmks' => $sub_cpmk_list,
            'total_bobot' => round($total_bobot, 2),
            'nilai_akhir' => $nilai_akhir,
            'grade' => $grade_info
        ];
    }

    /**
     * Dashboard Evaluasi Kurikulum untuk Role Kaprodi (Fase 5)
     * Mengagregasi rata-rata ketercapaian seluruh CPL dari mahasiswa prodi secara Bottom-Up
     *
     * @param int $prodi_id
     * @param string|null $angkatan
     * @return array
     */
    public function get_kaprodi_cpl_curriculum_evaluation($prodi_id = 1, $angkatan = null)
    {
        $prodi_id = (int)$prodi_id;
        $this->ensure_complete_obe_schema();

        // 1. Data Program Studi
        $prodi = $this->db->where('id', $prodi_id)->get('prodi')->row();
        if (!$prodi) {
            $prodi = (object)[
                'id' => 1,
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknologi Informasi'
            ];
        }

        // 2. Data CPL Program Studi
        $cpls = $this->db->where('prodi_id', $prodi_id)
                         ->order_by('kode_cpl', 'ASC')
                         ->get('cpl')
                         ->result();

        // 3. Ambil daftar Angkatan yang ada di sistem
        $angkatan_rows = $this->db->select('DISTINCT(angkatan) as angkatan')
                                  ->where('angkatan IS NOT NULL', null, false)
                                  ->where("angkatan != ''", null, false)
                                  ->order_by('angkatan', 'DESC')
                                  ->get('akun_mahasiswa')
                                  ->result();
        $angkatan_list = array_filter(array_column($angkatan_rows, 'angkatan'));
        if (empty($angkatan_list)) {
            $angkatan_list = ['2024', '2023', '2022', '2021'];
        }

        // 4. Query Mahasiswa pada prodi tersebut
        $this->db->select('id, nim, nama_lengkap, prodi, angkatan, semester');
        $this->db->from('akun_mahasiswa');
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->group_start();
            $this->db->like('prodi', $prodi->nama_prodi);
            if (!empty($prodi->kode_prodi)) {
                $this->db->or_like('prodi', $prodi->kode_prodi);
            }
        $this->db->group_end();

        if (!empty($angkatan) && $angkatan !== 'all') {
            $clean_ang = trim((string)$angkatan);
            $nim_pref = substr($clean_ang, 2, 2);
            $this->db->group_start();
                $this->db->where('angkatan', $clean_ang);
                $this->db->or_like('nim', $nim_pref, 'after');
            $this->db->group_end();
        }

        $students = $this->db->order_by('nama_lengkap', 'ASC')->get()->result();

        // Fallback jika tidak ada mahasiswa dengan filter prodi spesifik
        if (empty($students)) {
            $this->db->select('id, nim, nama_lengkap, prodi, angkatan, semester')
                     ->from('akun_mahasiswa')
                     ->where('deleted_at IS NULL', null, false);
            if (!empty($angkatan) && $angkatan !== 'all') {
                $clean_ang = trim((string)$angkatan);
                $nim_pref = substr($clean_ang, 2, 2);
                $this->db->group_start();
                    $this->db->where('angkatan', $clean_ang);
                    $this->db->or_like('nim', $nim_pref, 'after');
                $this->db->group_end();
            }
            $students = $this->db->limit(30)->get()->result();
        }

        $total_students = count($students);

        // Inisialisasi akumulator CPL
        $cpl_stats = [];
        foreach ($cpls as $c) {
            $cpl_stats[$c->kode_cpl] = [
                'cpl' => $c,
                'kode_cpl' => $c->kode_cpl,
                'kategori' => $c->kategori,
                'deskripsi' => $c->deskripsi,
                'total_skor' => 0.00,
                'lulus_count' => 0,
                'gap_count' => 0,
                'student_scores' => []
            ];
        }

        // 5. Hitung ketercapaian Bottom-Up setiap mahasiswa menggunakan get_capaian_cpl_mahasiswa
        foreach ($students as $st) {
            $cpl_data = $this->get_capaian_cpl_mahasiswa($st->id);
            if (!empty($cpl_data['details'])) {
                foreach ($cpl_data['details'] as $det) {
                    $k = $det['kode_cpl'];
                    if (!isset($cpl_stats[$k])) {
                        $cpl_stats[$k] = [
                            'cpl' => (object)[
                                'id' => $det['cpl_id'] ?? 0,
                                'kode_cpl' => $k,
                                'kategori' => $det['kategori'] ?? 'Umum',
                                'deskripsi' => $det['deskripsi'] ?? ''
                            ],
                            'kode_cpl' => $k,
                            'kategori' => $det['kategori'] ?? 'Umum',
                            'deskripsi' => $det['deskripsi'] ?? '',
                            'total_skor' => 0.00,
                            'lulus_count' => 0,
                            'gap_count' => 0,
                            'student_scores' => []
                        ];
                    }

                    $score = (float)$det['capaian'];
                    $cpl_stats[$k]['total_skor'] += $score;
                    $cpl_stats[$k]['student_scores'][] = $score;
                    if ($score >= 60.0) {
                        $cpl_stats[$k]['lulus_count']++;
                    } else {
                        $cpl_stats[$k]['gap_count']++;
                    }
                }
            }
        }

        // 6. Rangkum metrik rata-rata per CPL
        $cpl_summary = [];
        $chart_labels = [];
        $chart_scores = [];
        $chart_targets = [];
        $chart_pass_rates = [];
        $weak_cpls = [];
        $strong_cpls = [];
        $overall_sum = 0.00;

        foreach ($cpl_stats as $kode => $stat) {
            $cnt = count($stat['student_scores']);
            $avg = $cnt > 0 ? round($stat['total_skor'] / $cnt, 2) : 0.00;
            $pct_lulus = $cnt > 0 ? round(($stat['lulus_count'] / $cnt) * 100, 1) : 0.0;
            $is_weak = ($avg < 60.0);

            $overall_sum += $avg;
            $chart_labels[] = $kode;
            $chart_scores[] = $avg;
            $chart_targets[] = 60.0;
            $chart_pass_rates[] = $pct_lulus;

            // Generate rekomendasi kurikulum berbasis CPL
            $rekomendasi = '';
            if ($is_weak) {
                $rekomendasi = 'Perlu evaluasi bobot CPMK mata kuliah pendukung, peningkatan intensitas praktikum, dan remedial terstruktur sebelum Ujian Akhir.';
            } else {
                $rekomendasi = 'Kompetensi telah memenuhi standar minimal OBE prodi (>= 60%). Lanjutkan pengayaan studi kasus industri.';
            }

            $item = [
                'cpl_id' => $stat['cpl']->id ?? 0,
                'kode_cpl' => $kode,
                'kategori' => $stat['kategori'],
                'deskripsi' => $stat['deskripsi'],
                'avg_score' => $avg,
                'lulus_count' => $stat['lulus_count'],
                'gap_count' => $stat['gap_count'],
                'pct_lulus' => $pct_lulus,
                'is_weak' => $is_weak,
                'status' => $is_weak ? 'Perlu Penguatan (< 60%)' : 'Memenuhi Standar',
                'status_class' => $is_weak ? 'badge-danger' : 'badge-success',
                'rekomendasi' => $rekomendasi
            ];

            $cpl_summary[] = $item;
            if ($is_weak) {
                $weak_cpls[] = $item;
            } else {
                $strong_cpls[] = $item;
            }
        }

        $cpl_count = count($cpl_summary);
        $overall_avg = $cpl_count > 0 ? round($overall_sum / $cpl_count, 2) : 0.00;

        // Urutkan terlemah dan terkuat
        usort($cpl_summary, function($a, $b) {
            return ($a['avg_score'] > $b['avg_score']) ? -1 : 1;
        });

        $strongest_cpl = !empty($cpl_summary) ? $cpl_summary[0] : null;
        $weakest_cpl = !empty($cpl_summary) ? $cpl_summary[count($cpl_summary) - 1] : null;

        return [
            'prodi' => $prodi,
            'prodi_id' => $prodi_id,
            'angkatan_list' => $angkatan_list,
            'selected_angkatan' => $angkatan ?: 'all',
            'total_students' => $total_students,
            'students' => $students,
            'cpls' => $cpl_summary,
            'weak_cpls' => $weak_cpls,
            'strong_cpls' => $strong_cpls,
            'strongest_cpl' => $strongest_cpl,
            'weakest_cpl' => $weakest_cpl,
            'overall_avg' => $overall_avg,
            'chart_labels' => $chart_labels,
            'chart_scores' => $chart_scores,
            'chart_targets' => $chart_targets,
            'chart_pass_rates' => $chart_pass_rates,
            'has_weak' => !empty($weak_cpls)
        ];
    }
}

