<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan extends CI_Model {

    protected $table_tagihan    = 'tagihan';
    protected $table_pembayaran = 'pembayaran';
    protected $table_akun       = 'akun';
    protected $settings_file;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->settings_file = APPPATH . 'config/keuangan_settings.json';
    }

    /**
     * Ambil seluruh tagihan milik akun mahasiswa tertentu
     */
    public function get_tagihan_by_akun($akun_id)
    {
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->order_by('jatuh_tempo', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table_tagihan)->result();
    }

    /**
     * Ambil tagihan yang belum lunas (BELUM_BAYAR atau DITOLAK)
     * Untuk ditampilkan pada pilihan tagihan di modal pembayaran
     */
    public function get_tagihan_belum_lunas($akun_id)
    {
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->where_in('status', ['BELUM_BAYAR', 'DITOLAK']);
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table_tagihan)->result();
    }

    /**
     * Ambil tagihan semester aktif utama (misalnya SPP / UKT)
     */
    public function get_tagihan_aktif($akun_id)
    {
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table_tagihan)->row();
    }

    /**
     * Ambil detail tagihan spesifik berdasarkan ID dan Akun ID
     * Proteksi keamanan anti-IDOR: mahasiswa hanya bisa mengakses tagihannya sendiri
     */
    public function get_tagihan_by_id_and_akun($tagihan_id, $akun_id)
    {
        return $this->db->get_where($this->table_tagihan, [
            'id'      => (int)$tagihan_id,
            'akun_id' => (int)$akun_id
        ])->row();
    }

    /**
     * Ambil seluruh riwayat pembayaran milik mahasiswa beserta detail tagihannya
     */
    public function get_riwayat_pembayaran($akun_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan, 
                           tagihan.jatuh_tempo, 
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('pembayaran.akun_id', (int)$akun_id);
        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil detail satu pembayaran berdasarkan ID dan Akun ID (untuk validasi unduh bukti / edit)
     */
    public function get_pembayaran_by_id_and_akun($pembayaran_id, $akun_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan, 
                           tagihan.jatuh_tempo');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->where('pembayaran.id', (int)$pembayaran_id);
        $this->db->where('pembayaran.akun_id', (int)$akun_id);
        return $this->db->get()->row();
    }

    /**
     * Simpan record konfirmasi pembayaran baru
     */
    public function insert_pembayaran($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_pembayaran, $data);
        return $this->db->insert_id();
    }

    /**
     * Update pembayaran yang berstatus PENDING oleh mahasiswa (Edit Data / Ganti Bukti)
     */
    public function update_pembayaran_pending($pembayaran_id, $akun_id, $data)
    {
        // Pastikan hanya pembayaran berstatus PENDING milik akun tersebut yang bisa diubah
        $this->db->where('id', (int)$pembayaran_id);
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->where('status', 'PENDING');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table_pembayaran, $data);
    }

    /**
     * Batalkan pembayaran PENDING oleh mahasiswa:
     * - Hapus data pembayaran
     * - Kembalikan status tagihan ke BELUM_BAYAR
     */
    public function batalkan_pembayaran($pembayaran_id, $akun_id)
    {
        $pembayaran = $this->db->get_where($this->table_pembayaran, [
            'id'      => (int)$pembayaran_id,
            'akun_id' => (int)$akun_id,
            'status'  => 'PENDING'
        ])->row();

        if (!$pembayaran) {
            return false;
        }

        // Kembalikan status tagihan ke BELUM_BAYAR
        $this->update_status_tagihan($pembayaran->tagihan_id, 'BELUM_BAYAR');

        // Hapus file bukti transfer lama jika ada
        if (!empty($pembayaran->bukti_pembayaran)) {
            $file_path = FCPATH . 'uploads/bukti_pembayaran/' . $pembayaran->bukti_pembayaran;
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }

        // Hapus record pembayaran
        $this->db->where('id', (int)$pembayaran_id);
        return $this->db->delete($this->table_pembayaran);
    }

    /**
     * Update status pada tabel tagihan
     */
    public function update_status_tagihan($tagihan_id, $status)
    {
        $this->db->where('id', (int)$tagihan_id);
        return $this->db->update($this->table_tagihan, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Cek status akses KRS mahasiswa berdasarkan status pembayaran semester aktif
     * Prioritas syarat KRS: Tagihan SPP / UKT semester aktif
     */
    public function cek_status_krs($akun_id)
    {
        // Cari tagihan utama SPP / UKT semester aktif
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->like('jenis_tagihan', 'SPP', 'after');
        $tagihan_spp = $this->db->get($this->table_tagihan)->row();

        // Jika tidak ada tagihan spesifik SPP, ambil tagihan pertama
        if (!$tagihan_spp) {
            $tagihan_spp = $this->get_tagihan_aktif($akun_id);
        }

        if (!$tagihan_spp) {
            return [
                'status'    => 'BELUM_BAYAR',
                'pesan'     => 'KRS belum dapat diakses. Silakan selesaikan pembayaran semester terlebih dahulu.',
                'buka_krs'  => false,
                'tagihan'   => null
            ];
        }

        switch ($tagihan_spp->status) {
            case 'LUNAS':
                return [
                    'status'    => 'LUNAS',
                    'pesan'     => 'Pembayaran telah diverifikasi. Akses KRS terbuka penuh.',
                    'buka_krs'  => true,
                    'tagihan'   => $tagihan_spp
                ];
            case 'PENDING':
                return [
                    'status'    => 'PENDING',
                    'pesan'     => 'KRS belum dapat diakses. Pembayaran sedang menunggu verifikasi Admin Keuangan.',
                    'buka_krs'  => false,
                    'tagihan'   => $tagihan_spp
                ];
            case 'DITOLAK':
                // Cari alasan penolakan terakhir jika ada
                $last_reject = $this->db->where([
                    'tagihan_id' => $tagihan_spp->id,
                    'status'     => 'DITOLAK'
                ])->order_by('id', 'DESC')->get($this->table_pembayaran)->row();

                return [
                    'status'           => 'DITOLAK',
                    'pesan'            => 'KRS belum dapat diakses. Pembayaran ditolak. Silakan perbaiki pembayaran.',
                    'alasan_penolakan' => $last_reject ? $last_reject->alasan_penolakan : 'Bukti pembayaran tidak sesuai atau tidak terbaca.',
                    'buka_krs'         => false,
                    'tagihan'          => $tagihan_spp
                ];
            case 'BELUM_BAYAR':
            default:
                return [
                    'status'    => 'BELUM_BAYAR',
                    'pesan'     => 'KRS belum dapat diakses. Silakan selesaikan pembayaran semester terlebih dahulu.',
                    'buka_krs'  => false,
                    'tagihan'   => $tagihan_spp
                ];
        }
    }

    /**
     * Mengambil status realtime mahasiswa untuk polling AJAX desktop
     */
    public function get_status_realtime_mahasiswa($akun_id)
    {
        $status_krs = $this->cek_status_krs($akun_id);

        $this->db->where('akun_id', (int)$akun_id);
        $tagihan = $this->db->get($this->table_tagihan)->result();

        $count_pending = 0;
        $count_lunas   = 0;
        $count_belum   = 0;
        $hash_string   = '';

        foreach ($tagihan as $t) {
            if ($t->status === 'LUNAS') $count_lunas++;
            elseif ($t->status === 'PENDING') $count_pending++;
            else $count_belum++;
            $hash_string .= "{$t->id}:{$t->status}:{$t->updated_at}|";
        }

        // Ambil pembayaran terakhir
        $this->db->where('akun_id', (int)$akun_id);
        $this->db->order_by('updated_at', 'DESC');
        $last_bayar = $this->db->get($this->table_pembayaran)->row();

        if ($last_bayar) {
            $hash_string .= "pay:{$last_bayar->id}:{$last_bayar->status}:{$last_bayar->updated_at}";
        }

        return [
            'status_krs'         => $status_krs['status'],
            'pesan_krs'          => $status_krs['pesan'],
            'buka_krs'           => $status_krs['buka_krs'],
            'count_pending'      => $count_pending,
            'count_lunas'        => $count_lunas,
            'count_belum'        => $count_belum,
            'last_status_bayar'  => $last_bayar ? $last_bayar->status : null,
            'alasan_penolakan'   => ($last_bayar && $last_bayar->status === 'DITOLAK') ? $last_bayar->alasan_penolakan : null,
            'hash'               => md5($hash_string),
            'timestamp'          => date('Y-m-d H:i:s')
        ];
    }

    // ======================================================
    // PENGATURAN AKSES TUGAS AKHIR (ADMIN CONTROL)
    // ======================================================

    /**
     * Ambil status buka/tutup akses pembayaran Tugas Akhir
     */
    public function get_setting_akses_ta()
    {
        if (file_exists($this->settings_file)) {
            $content = @file_get_contents($this->settings_file);
            $json = json_decode($content, true);
            if (isset($json['akses_tugas_akhir'])) {
                return (bool)$json['akses_tugas_akhir'];
            }
        }
        return true; // Default terbuka
    }

    /**
     * Simpan status buka/tutup akses pembayaran Tugas Akhir
     */
    public function set_setting_akses_ta($status, $user_name = 'Admin')
    {
        $data = [
            'akses_tugas_akhir' => (bool)$status,
            'updated_at'        => date('Y-m-d H:i:s'),
            'updated_by'        => $user_name
        ];
        return (bool)@file_put_contents($this->settings_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    // ======================================================
    // METHODS KHUSUS ADMIN KEUANGAN
    // ======================================================

    /**
     * Hitung jumlah pembayaran berdasarkan status
     */
    public function count_pembayaran_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results($this->table_pembayaran);
    }

    /**
     * Hitung jumlah mahasiswa aktif (role = 3, deleted_at IS NULL)
     */
    public function count_mahasiswa_aktif()
    {
        return $this->db->where('role', 3)->where('deleted_at IS NULL')->count_all_results($this->table_akun);
    }

    /**
     * Ambil daftar unik Fakultas dan Program Studi yang ada di sistem
     */
    public function get_daftar_fakultas_prodi()
    {
        $fakultas = $this->db->distinct()
                             ->select('fakultas')
                             ->where('role', 3)
                             ->where('deleted_at IS NULL')
                             ->where('fakultas IS NOT NULL')
                             ->get($this->table_akun)
                             ->result_array();

        $prodi = $this->db->distinct()
                          ->select('prodi, fakultas')
                          ->where('role', 3)
                          ->where('deleted_at IS NULL')
                          ->where('prodi IS NOT NULL')
                          ->get($this->table_akun)
                          ->result_array();

        return [
            'fakultas' => array_column($fakultas, 'fakultas'),
            'prodi'    => array_column($prodi, 'prodi')
        ];
    }

    /**
     * Ambil semua data pembayaran berdasarkan status, fakultas, dan prodi
     */
    public function get_semua_pembayaran($status = null, $fakultas = null, $prodi = null)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan,
                           tagihan.jatuh_tempo,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           mhs.semester as semester_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');

        if ($status !== null && $status !== '') {
            $this->db->where('pembayaran.status', $status);
        }
        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil detail satu pembayaran untuk modal admin (dengan data mahasiswa)
     */
    public function get_detail_pembayaran_admin($pembayaran_id)
    {
        $this->db->select('pembayaran.*, 
                           tagihan.jenis_tagihan, 
                           tagihan.tahun_akademik, 
                           tagihan.semester, 
                           tagihan.nominal as nominal_tagihan,
                           tagihan.jatuh_tempo,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.email as email_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           mhs.semester as semester_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran);
        $this->db->join($this->table_tagihan, 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('pembayaran.id', (int)$pembayaran_id);
        return $this->db->get()->row();
    }

    /**
     * Daftar mahasiswa yang memiliki tagihan dengan filter fakultas dan prodi
     */
    public function get_mahasiswa_belum_lunas($fakultas = null, $prodi = null)
    {
        $this->db->select('akun.id, akun.nim, akun.nama_lengkap, akun.email, 
                           akun.fakultas, akun.prodi, akun.semester as semester_mhs,
                           COUNT(tagihan.id) as total_tagihan,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN 1 ELSE 0 END) as tagihan_lunas,
                           SUM(CASE WHEN tagihan.status = "PENDING" THEN 1 ELSE 0 END) as tagihan_pending,
                           SUM(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN 1 ELSE 0 END) as tagihan_belum,
                           SUM(tagihan.nominal) as total_nominal,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN tagihan.nominal ELSE 0 END) as nominal_lunas');
        $this->db->from($this->table_akun . ' as akun');
        $this->db->join($this->table_tagihan . ' as tagihan', 'akun.id = tagihan.akun_id', 'inner');
        $this->db->where('akun.role', 3);
        $this->db->where('akun.deleted_at IS NULL');

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('akun.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('akun.prodi', $prodi);
        }

        $this->db->group_by('akun.id');
        $this->db->order_by('tagihan_belum', 'DESC');
        $this->db->order_by('akun.prodi', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Rekap laporan keuangan berdasarkan tahun akademik & semester
     */
    public function get_rekap_laporan($tahun_akademik, $semester, $fakultas = null, $prodi = null)
    {
        $this->db->select('tagihan.jenis_tagihan,
                           COUNT(tagihan.id) as jumlah_tagihan,
                           SUM(tagihan.nominal) as total_nominal,
                           SUM(CASE WHEN tagihan.status = "LUNAS" THEN tagihan.nominal ELSE 0 END) as nominal_lunas,
                           SUM(CASE WHEN tagihan.status = "PENDING" THEN tagihan.nominal ELSE 0 END) as nominal_pending,
                           SUM(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN tagihan.nominal ELSE 0 END) as nominal_belum,
                           COUNT(CASE WHEN tagihan.status = "LUNAS" THEN 1 END) as jumlah_lunas,
                           COUNT(CASE WHEN tagihan.status = "PENDING" THEN 1 END) as jumlah_pending,
                           COUNT(CASE WHEN tagihan.status IN ("BELUM_BAYAR","DITOLAK") THEN 1 END) as jumlah_belum');
        $this->db->from($this->table_tagihan . ' as tagihan');
        $this->db->join($this->table_akun . ' as mhs', 'tagihan.akun_id = mhs.id', 'inner');
        $this->db->where('tagihan.tahun_akademik', $tahun_akademik);
        $this->db->where('tagihan.semester', $semester);

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->group_by('tagihan.jenis_tagihan');
        return $this->db->get()->result();
    }

    /**
     * Ambil semua pembayaran untuk laporan rektorat (dengan filter)
     */
    public function get_semua_pembayaran_laporan($tahun_akademik, $semester, $fakultas = null, $prodi = null)
    {
        $this->db->select('pembayaran.*,
                           tagihan.jenis_tagihan,
                           tagihan.tahun_akademik,
                           tagihan.semester,
                           tagihan.nominal as nominal_tagihan,
                           mhs.nama_lengkap as nama_mahasiswa,
                           mhs.nim as nim_mahasiswa,
                           mhs.fakultas as fakultas_mahasiswa,
                           mhs.prodi as prodi_mahasiswa,
                           verif.nama_lengkap as nama_verifikator');
        $this->db->from($this->table_pembayaran . ' as pembayaran');
        $this->db->join($this->table_tagihan . ' as tagihan', 'pembayaran.tagihan_id = tagihan.id', 'inner');
        $this->db->join($this->table_akun . ' as mhs', 'pembayaran.akun_id = mhs.id', 'inner');
        $this->db->join($this->table_akun . ' as verif', 'pembayaran.diverifikasi_oleh = verif.id', 'left');
        $this->db->where('tagihan.tahun_akademik', $tahun_akademik);
        $this->db->where('tagihan.semester', $semester);

        if ($fakultas !== null && $fakultas !== '') {
            $this->db->where('mhs.fakultas', $fakultas);
        }
        if ($prodi !== null && $prodi !== '') {
            $this->db->where('mhs.prodi', $prodi);
        }

        $this->db->order_by('pembayaran.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil daftar tahun akademik yang tersedia di database
     */
    public function get_daftar_tahun_akademik()
    {
        return $this->db->distinct()
                        ->select('tahun_akademik, semester')
                        ->order_by('tahun_akademik', 'DESC')
                        ->get($this->table_tagihan)
                        ->result();
    }

    /**
     * Informasi komponen tarif biaya satu semester sebagai panduan dan transparansi mahasiswa
     * Realistis & Sesuai Spesifikasi: Total Rp 4.500.000 (Data Simulasi)
     */
    public function get_informasi_komponen_semester()
    {
        return [
            [
                'no'          => 1,
                'kategori'    => 'Kewajiban Pokok',
                'komponen'    => 'SPP / UKT',
                'nominal'     => 3500000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Biaya kuliah semester pokok, bimbingan akademik dosen wali, dan perkuliahan reguler. Syarat mutlak pembukaan akses KRS.',
                'syarat_krs'  => true
            ],
            [
                'no'          => 2,
                'kategori'    => 'Akademik & Lab',
                'komponen'    => 'Praktikum',
                'nominal'     => 500000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Praktikum dan penggunaan laboratorium komputer/sains, modul praktikum, dan lisensi perangkat lunak praktikum.',
                'syarat_krs'  => false
            ],
            [
                'no'          => 3,
                'kategori'    => 'Sarana Kampus',
                'komponen'    => 'Fasilitas Akademik',
                'nominal'     => 300000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Penunjang kegiatan akademik, akses perpustakaan digital, sarana e-learning, dan fasilitas pendukung kampus.',
                'syarat_krs'  => false
            ],
            [
                'no'          => 4,
                'kategori'    => 'Layanan Kampus',
                'komponen'    => 'Sistem Informasi & Administrasi',
                'nominal'     => 200000,
                'sifat'       => 'Wajib',
                'keterangan'  => 'Layanan akademik, portal Smart Campus, administrasi kesiswaan, dan pemeliharaan server perkuliahan.',
                'syarat_krs'  => false
            ]
        ];
    }

    /**
     * Rincian Biaya Tambahan (Di Luar SPP/UKT Semester Reguler)
     */
    public function get_informasi_biaya_tambahan()
    {
        return [
            [
                'no'          => 1,
                'jenis_biaya' => 'Bimbingan Tugas Akhir',
                'nominal'     => 500000,
                'keterangan'  => 'Honorarium bimbingan intensif laporan tugas akhir / skripsi bersama dosen pembimbing.',
                'peruntukan'  => 'Mahasiswa Semester Akhir'
            ],
            [
                'no'          => 2,
                'jenis_biaya' => 'Ujian Tugas Akhir',
                'nominal'     => 750000,
                'keterangan'  => 'Administrasi pelaksanaan sidang komprehensif / sidang tugas akhir serta penguji.',
                'peruntukan'  => 'Mahasiswa Semester Akhir'
            ],
            [
                'no'          => 3,
                'jenis_biaya' => 'Cetak & Administrasi Dokumen Akademik',
                'nominal'     => 100000,
                'keterangan'  => 'Pencetakan transkrip nilai resmi, legalisir ijazah, dan administrasi berkas kelulusan.',
                'peruntukan'  => 'Sesuai Kebutuhan'
            ],
            [
                'no'          => 4,
                'jenis_biaya' => 'Wisuda',
                'nominal'     => 1500000,
                'keterangan'  => 'Prosesi wisuda sarjana/diploma, toga wisuda, ijazah digital & cetak berhologram, serta dokumentasi.',
                'peruntukan'  => 'Calon Wisudawan'
            ]
        ];
    }
}
