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
                   COALESCE(c.total_cpmk, 0) as total_cpmk,
                   COALESCE(kp.total_komponen, 0) as total_komponen,
                   COALESCE(kp.total_bobot, 0) as total_bobot,
                   COALESCE(kd.total_mahasiswa, 0) as total_mahasiswa,
                   COALESCE(nc.total_mahasiswa_dinilai, 0) as total_mahasiswa_dinilai,
                   COALESCE(kn.total_nilai_khs, 0) as total_nilai_khs,
                   COALESCE(b.nama_lengkap, 'Dosen Pengampu') as nama_dosen
            FROM penawaran_matakuliah p
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

        return $this->db->query($sql, $params)->result();
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
}
