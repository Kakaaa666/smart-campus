<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_skpi_mahasiswa extends CI_Model
{
    protected $table = 'pengajuan_skpi';

    protected $kategori = [
        1 => [
            'id'        => 1,
            'nama'      => 'Prestasi dan Penghargaan',
            'deskripsi' => 'Capaian kejuaraan, kompetisi ilmiah, seni, olahraga, atau penghargaan bergengsi tingkat regional, nasional, maupun internasional.',
            'icon'      => 'fa fa-trophy',
            'color'     => '#f59e0b',
            'badge'     => 'warning'
        ],
        2 => [
            'id'        => 2,
            'nama'      => 'Pengalaman Organisasi',
            'deskripsi' => 'Rekam jejak kepengurusan di BEM, DPM, Himpunan Mahasiswa Program Studi, UKM, atau organisasi kemahasiswaan eksternal.',
            'icon'      => 'fa fa-sitemap',
            'color'     => '#3b82f6',
            'badge'     => 'primary'
        ],
        3 => [
            'id'        => 3,
            'nama'      => 'Kepanitiaan Kegiatan',
            'deskripsi' => 'Partisipasi kepanitiaan dalam event seminar, festival, workshop, masa bimbingan, atau kegiatan berskala kampus dan luar kampus.',
            'icon'      => 'fa fa-users',
            'color'     => '#10b981',
            'badge'     => 'success'
        ],
        4 => [
            'id'        => 4,
            'nama'      => 'Seminar, Workshop dan Pelatihan',
            'deskripsi' => 'Keikutsertaan sebagai peserta atau pemateri dalam pelatihan kompetensi, lokakarya, simposium, atau seminar ilmiah.',
            'icon'      => 'fa fa-graduation-cap',
            'color'     => '#6366f1',
            'badge'     => 'info'
        ],
        5 => [
            'id'        => 5,
            'nama'      => 'Sertifikasi Keahlian & Profesi',
            'deskripsi' => 'Sertifikat kompetensi dari BNSP, vendor IT global (Cisco, Oracle, Google, AWS, Microsoft), atau sertifikasi bahasa (TOEFL/IELTS).',
            'icon'      => 'fa fa-certificate',
            'color'     => '#ef4444',
            'badge'     => 'danger'
        ],
        6 => [
            'id'        => 6,
            'nama'      => 'Magang dan Praktik Kerja (PKL)',
            'deskripsi' => 'Pengalaman kerja praktik lapangan, program magang industri bersertifikat, atau kerja profesi di institusi mitra.',
            'icon'      => 'fa fa-briefcase',
            'color'     => '#8b5cf6',
            'badge'     => 'purple'
        ],
        7 => [
            'id'        => 7,
            'nama'      => 'Penelitian dan Publikasi Ilmiah',
            'deskripsi' => 'Karya tulis ilmiah pada jurnal terindeks, pemakalah prosiding seminar, atau asisten peneliti pada riset dosen.',
            'icon'      => 'fa fa-book',
            'color'     => '#06b6d4',
            'badge'     => 'cyan'
        ],
        8 => [
            'id'        => 8,
            'nama'      => 'Pengabdian Kepada Masyarakat',
            'deskripsi' => 'Partisipasi dalam Kuliah Kerja Nyata (KKN), bakti sosial terstruktur, program pendampingan UMKM, atau relawan kemanusiaan.',
            'icon'      => 'fa fa-handshake-o',
            'color'     => '#14b8a6',
            'badge'     => 'teal'
        ],
        9 => [
            'id'        => 9,
            'nama'      => 'Karya Inovasi dan HAKI',
            'deskripsi' => 'Hak Cipta (HAKI) aplikasi, paten teknologi, desain industri, atau produk inovasi yang telah terdaftar resmi.',
            'icon'      => 'fa fa-lightbulb-o',
            'color'     => '#f97316',
            'badge'     => 'orange'
        ],
        10 => [
            'id'        => 10,
            'nama'      => 'Program MBKM (Merdeka Belajar)',
            'deskripsi' => 'Program Merdeka Belajar Kampus Merdeka: Studi Independen, Kampus Mengajar, Pertukaran Mahasiswa Merdeka, atau Wirausaha Merdeka.',
            'icon'      => 'fa fa-rocket',
            'color'     => '#ec4899',
            'badge'     => 'pink'
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Dapatkan daftar 10 kategori SKPI
     */
    public function get_kategori_list()
    {
        return $this->kategori;
    }

    /**
     * Dapatkan detail kategori berdasarkan ID
     */
    public function get_kategori_by_id($id)
    {
        $id = (int)$id;
        return $this->kategori[$id] ?? null;
    }

    /**
     * Hitung ringkasan status pengajuan mahasiswa
     */
    public function get_summary($mahasiswa_id)
    {
        $mahasiswa_id = (int)$mahasiswa_id;
        $q = $this->db->select("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'WAITING' THEN 1 ELSE 0 END) as waiting,
            SUM(CASE WHEN status = 'YES' THEN 1 ELSE 0 END) as valid,
            SUM(CASE WHEN status = 'NO' THEN 1 ELSE 0 END) as invalid
        ")
        ->where('mahasiswa_id', $mahasiswa_id)
        ->get($this->table)
        ->row();

        return [
            'total'   => (int)($q->total ?? 0),
            'waiting' => (int)($q->waiting ?? 0),
            'valid'   => (int)($q->valid ?? 0),
            'invalid' => (int)($q->invalid ?? 0),
        ];
    }

    /**
     * Ambil data pengajuan mahasiswa yang dikelompokkan per kategori (1-10)
     */
    public function get_grouped_by_kategori($mahasiswa_id, $filter = [])
    {
        $mahasiswa_id = (int)$mahasiswa_id;

        $this->db->where('mahasiswa_id', $mahasiswa_id);

        if (!empty($filter['nama'])) {
            $this->db->group_start();
            $this->db->like('nama_kegiatan', $filter['nama']);
            $this->db->or_like('penyelenggara', $filter['nama']);
            $this->db->or_like('no_sertifikat', $filter['nama']);
            $this->db->group_end();
        }

        if (!empty($filter['status']) && in_array($filter['status'], ['WAITING', 'YES', 'NO'], true)) {
            $this->db->where('status', $filter['status']);
        }

        $this->db->order_by('tanggal_kegiatan', 'DESC');
        $this->db->order_by('id', 'DESC');
        $rows = $this->db->get($this->table)->result();

        // Inisialisasi struktur 10 kategori
        $result = [];
        foreach ($this->kategori as $kat_id => $kat) {
            $result[$kat_id] = [
                'info'    => $kat,
                'items'   => [],
                'count'   => 0,
                'waiting' => 0,
                'valid'   => 0,
                'invalid' => 0
            ];
        }

        foreach ($rows as $r) {
            $k_id = (int)$r->kategori_id;
            if (isset($result[$k_id])) {
                $result[$k_id]['items'][] = $r;
                $result[$k_id]['count']++;
                if ($r->status === 'WAITING') $result[$k_id]['waiting']++;
                elseif ($r->status === 'YES') $result[$k_id]['valid']++;
                elseif ($r->status === 'NO')  $result[$k_id]['invalid']++;
            }
        }

        return $result;
    }

    /**
     * Ambil detail satu pengajuan
     */
    public function get_detail($id, $mahasiswa_id = null)
    {
        $this->db->where('id', (int)$id);
        if ($mahasiswa_id !== null) {
            $this->db->where('mahasiswa_id', (int)$mahasiswa_id);
        }
        return $this->db->get($this->table)->row();
    }

    /**
     * Simpan pengajuan baru atau update yang ada
     */
    public function simpan($data, $id = null)
    {
        // Pastikan nama kategori sesuai ID
        $kat_id = (int)($data['kategori_id'] ?? 1);
        if (isset($this->kategori[$kat_id])) {
            $data['kategori_nama'] = $this->kategori[$kat_id]['nama'];
        }

        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', (int)$id);
            return $this->db->update($this->table, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status'] = 'WAITING';
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Hapus pengajuan (hanya milik mahasiswa bersangkutan)
     */
    public function hapus($id, $mahasiswa_id)
    {
        $item = $this->get_detail($id, $mahasiswa_id);
        if (!$item) {
            return false;
        }

        // Hapus file fisik jika ada
        if (!empty($item->file_bukti)) {
            $file_path = FCPATH . 'uploads/skpi/' . $item->file_bukti;
            if (file_exists($file_path) && is_file($file_path)) {
                @unlink($file_path);
            }
        }

        $this->db->where('id', (int)$id);
        $this->db->where('mahasiswa_id', (int)$mahasiswa_id);
        return $this->db->delete($this->table);
    }

    /**
     * Hitung ringkasan status pengajuan seluruh mahasiswa (untuk Admin Akademik & Super Admin)
     */
    public function get_admin_summary()
    {
        $q = $this->db->select("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'WAITING' THEN 1 ELSE 0 END) as waiting,
            SUM(CASE WHEN status = 'YES' THEN 1 ELSE 0 END) as valid,
            SUM(CASE WHEN status = 'NO' THEN 1 ELSE 0 END) as invalid,
            COUNT(DISTINCT mahasiswa_id) as total_mahasiswa
        ")
        ->get($this->table)
        ->row();

        return [
            'total'           => (int)($q->total ?? 0),
            'waiting'         => (int)($q->waiting ?? 0),
            'valid'           => (int)($q->valid ?? 0),
            'invalid'         => (int)($q->invalid ?? 0),
            'total_mahasiswa' => (int)($q->total_mahasiswa ?? 0),
        ];
    }

    /**
     * Ambil rekap data per mahasiswa: nama mahasiswa, jumlah yang sudah divalidasi, dan jumlah yang belum divalidasi
     */
    public function get_mahasiswa_rekap($filter = [])
    {
        $this->db->select("
            m.id as mahasiswa_id,
            m.nim,
            m.nama_lengkap as nama_mahasiswa,
            m.prodi,
            m.semester,
            COUNT(p.id) as total_pengajuan,
            SUM(CASE WHEN p.status = 'YES' THEN 1 ELSE 0 END) as total_valid,
            SUM(CASE WHEN p.status = 'WAITING' THEN 1 ELSE 0 END) as total_waiting,
            SUM(CASE WHEN p.status = 'NO' THEN 1 ELSE 0 END) as total_invalid
        ");
        $this->db->from('akun_mahasiswa m');
        $this->db->join($this->table . ' p', 'p.mahasiswa_id = m.id', 'inner');

        if (!empty($filter['keyword'])) {
            $this->db->group_start();
            $this->db->like('m.nama_lengkap', $filter['keyword']);
            $this->db->or_like('m.nim', $filter['keyword']);
            $this->db->or_like('m.prodi', $filter['keyword']);
            $this->db->group_end();
        }

        if (!empty($filter['prodi'])) {
            $this->db->where('m.prodi', $filter['prodi']);
        }

        $this->db->group_by('m.id, m.nim, m.nama_lengkap, m.prodi, m.semester');

        if (!empty($filter['status'])) {
            if ($filter['status'] === 'WAITING') {
                $this->db->having("SUM(CASE WHEN p.status = 'WAITING' THEN 1 ELSE 0 END) > 0");
            } elseif ($filter['status'] === 'YES') {
                $this->db->having("SUM(CASE WHEN p.status = 'YES' THEN 1 ELSE 0 END) > 0 AND SUM(CASE WHEN p.status = 'WAITING' THEN 1 ELSE 0 END) = 0");
            }
        }

        $this->db->order_by("SUM(CASE WHEN p.status = 'WAITING' THEN 1 ELSE 0 END)", "DESC");
        $this->db->order_by('m.nama_lengkap', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Dapatkan info biodata satu mahasiswa
     */
    public function get_mahasiswa_info($mahasiswa_id)
    {
        return $this->db->where('id', (int)$mahasiswa_id)->get('akun_mahasiswa')->row();
    }

    /**
     * Ambil seluruh pengajuan SKPI beserta data mahasiswa (untuk Admin Akademik & Super Admin)
     */
    public function get_all_pengajuan_admin($filter = [])
    {
        $this->db->select('
            p.*,
            m.nim,
            m.nama_lengkap as nama_mahasiswa,
            m.prodi,
            m.semester,
            b.nama_lengkap as nama_verifikator
        ');
        $this->db->from($this->table . ' p');
        $this->db->join('akun_mahasiswa m', 'm.id = p.mahasiswa_id', 'left');
        $this->db->join('akun_biro b', 'b.id = p.divalidasi_oleh', 'left');

        if (!empty($filter['keyword'])) {
            $this->db->group_start();
            $this->db->like('p.nama_kegiatan', $filter['keyword']);
            $this->db->or_like('p.penyelenggara', $filter['keyword']);
            $this->db->or_like('p.no_sertifikat', $filter['keyword']);
            $this->db->or_like('m.nama_lengkap', $filter['keyword']);
            $this->db->or_like('m.nim', $filter['keyword']);
            $this->db->group_end();
        }

        if (!empty($filter['status']) && in_array($filter['status'], ['WAITING', 'YES', 'NO'], true)) {
            $this->db->where('p.status', $filter['status']);
        }

        if (!empty($filter['kategori_id']) && (int)$filter['kategori_id'] > 0) {
            $this->db->where('p.kategori_id', (int)$filter['kategori_id']);
        }

        if (!empty($filter['prodi'])) {
            $this->db->where('m.prodi', $filter['prodi']);
        }

        $this->db->order_by("CASE WHEN p.status = 'WAITING' THEN 1 WHEN p.status = 'YES' THEN 2 ELSE 3 END", "ASC");
        $this->db->order_by('p.tanggal_kegiatan', 'DESC');
        $this->db->order_by('p.id', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Ubah status validasi pengajuan SKPI (Oleh Admin Akademik / Super Admin)
     */
    public function update_status($id, $status, $catatan_validasi = '', $admin_id = null)
    {
        if (!in_array($status, ['WAITING', 'YES', 'NO'], true)) {
            return false;
        }

        $update_data = [
            'status'           => $status,
            'catatan_validasi' => trim($catatan_validasi ?: ''),
            'divalidasi_oleh'  => $admin_id ? (int)$admin_id : null,
            'tanggal_validasi' => ($status !== 'WAITING') ? date('Y-m-d H:i:s') : null,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $this->db->where('id', (int)$id);
        return $this->db->update($this->table, $update_data);
    }
}
