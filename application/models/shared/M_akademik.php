<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akademik extends CI_Model
{
    const MAX_SKS_PER_SEMESTER = 24;
    const MIN_SKS_PER_SEMESTER = 12;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema();
    }

    public function module_name()
    {
        return 'Akademik';
    }

    public function ensure_schema()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `penawaran_matakuliah` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `kode_mk` VARCHAR(32) NOT NULL,
            `nama_mk` VARCHAR(150) NOT NULL,
            `prodi` VARCHAR(100) NOT NULL,
            `semester` TINYINT(2) NOT NULL,
            `tahun_akademik` VARCHAR(9) NOT NULL,
            `semester_akademik` ENUM('Ganjil','Genap') NOT NULL,
            `sks` TINYINT(2) NOT NULL,
            `kelas` VARCHAR(20) NOT NULL,
            `hari` ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
            `waktu_mulai` TIME NOT NULL,
            `waktu_selesai` TIME NOT NULL,
            `ruangan` VARCHAR(100) NOT NULL,
            `dosen_id` INT(11) NOT NULL,
            `aktif` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_penawaran_periode` (`tahun_akademik`,`semester_akademik`,`prodi`,`semester`,`aktif`),
            KEY `idx_penawaran_kode` (`kode_mk`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `dosen_wali` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `mahasiswa_id` INT(11) NOT NULL,
            `dosen_id` INT(11) NOT NULL,
            `ditetapkan_oleh` INT(11) NOT NULL,
            `ditetapkan_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_dosen_wali_mahasiswa` (`mahasiswa_id`),
            KEY `idx_dosen_wali_dosen` (`dosen_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `krs` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `akun_id` INT(11) NOT NULL,
            `tahun_akademik` VARCHAR(9) NOT NULL,
            `semester_akademik` ENUM('Ganjil','Genap') NOT NULL,
            `status` ENUM('DRAFT','MENUNGGU','DISETUJUI','DITOLAK') NOT NULL DEFAULT 'DRAFT',
            `total_sks` TINYINT(3) NOT NULL DEFAULT 0,
            `catatan` TEXT NULL,
            `diajukan_at` DATETIME NULL,
            `diproses_oleh` INT(11) NULL,
            `diproses_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_krs_mahasiswa_periode` (`akun_id`,`tahun_akademik`,`semester_akademik`),
            KEY `idx_krs_status` (`status`,`tahun_akademik`,`semester_akademik`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `krs_detail` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `krs_id` INT(11) NOT NULL,
            `penawaran_id` INT(11) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_krs_detail_offering` (`krs_id`,`penawaran_id`),
            KEY `idx_krs_detail_penawaran` (`penawaran_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function get_active_period()
    {
        $month = (int)date('n');
        $year = (int)date('Y');
        $semester = ($month >= 7 && $month <= 12) ? 'Ganjil' : 'Genap';
        $academic_year = $semester === 'Ganjil'
            ? $year . '/' . ($year + 1)
            : ($year - 1) . '/' . $year;

        return [
            'tahun_akademik' => $academic_year,
            'semester_akademik' => $semester,
        ];
    }

    public function get_offerings($prodi, $semester, $period)
    {
        return $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                        ->from('penawaran_matakuliah')
                        ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                        ->where('penawaran_matakuliah.prodi', $prodi)
                        ->where('penawaran_matakuliah.semester', (int)$semester)
                        ->where('penawaran_matakuliah.tahun_akademik', $period['tahun_akademik'])
                        ->where('penawaran_matakuliah.semester_akademik', $period['semester_akademik'])
                        ->where('penawaran_matakuliah.aktif', 1)
                        ->order_by("FIELD(penawaran_matakuliah.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')", '', false)
                        ->order_by('penawaran_matakuliah.waktu_mulai', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_krs($akun_id, $period)
    {
        $krs = $this->db->select('krs.*, dosen.nama_lengkap AS nama_dosen_wali')
                        ->from('krs')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'left')
                        ->join('akun AS dosen', 'dosen.id = dosen_wali.dosen_id', 'left')
                        ->where('krs.akun_id', (int)$akun_id)
                        ->where('krs.tahun_akademik', $period['tahun_akademik'])
                        ->where('krs.semester_akademik', $period['semester_akademik'])
                        ->get()
                        ->row();

        if (!$krs) {
            return null;
        }

        $krs->mata_kuliah = $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                                     ->from('krs_detail')
                                     ->join('penawaran_matakuliah', 'penawaran_matakuliah.id = krs_detail.penawaran_id', 'inner')
                                     ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                                     ->where('krs_detail.krs_id', (int)$krs->id)
                                     ->order_by("FIELD(penawaran_matakuliah.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')", '', false)
                                     ->order_by('penawaran_matakuliah.waktu_mulai', 'ASC')
                                     ->get()
                                     ->result();

        return $krs;
    }

    public function get_account($akun_id)
    {
        return $this->db->select('id, nim, nama_lengkap, fakultas, prodi, semester, role')
                        ->where('id', (int)$akun_id)
                        ->where('role', 3)
                        ->where('deleted_at IS NULL', null, false)
                        ->get('akun')
                        ->row();
    }

    public function get_advisor_for_student($akun_id)
    {
        return $this->db->select('akun.id, akun.nim, akun.nama_lengkap')
                        ->from('dosen_wali')
                        ->join('akun', 'akun.id = dosen_wali.dosen_id', 'inner')
                        ->where('dosen_wali.mahasiswa_id', (int)$akun_id)
                        ->where('akun.role', 4)
                        ->where('akun.deleted_at IS NULL', null, false)
                        ->get()
                        ->row();
    }

    public function get_admin_offerings()
    {
        return $this->db->select('penawaran_matakuliah.*, akun.nama_lengkap AS nama_dosen')
                        ->from('penawaran_matakuliah')
                        ->join('akun', 'akun.id = penawaran_matakuliah.dosen_id', 'left')
                        ->order_by('penawaran_matakuliah.tahun_akademik', 'DESC')
                        ->order_by('penawaran_matakuliah.semester_akademik', 'ASC')
                        ->order_by('penawaran_matakuliah.prodi', 'ASC')
                        ->order_by('penawaran_matakuliah.semester', 'ASC')
                        ->order_by('penawaran_matakuliah.kode_mk', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_period_overview($period)
    {
        $offerings = $this->db->where('tahun_akademik', $period['tahun_akademik'])
                              ->where('semester_akademik', $period['semester_akademik'])
                              ->where('aktif', 1)
                              ->count_all_results('penawaran_matakuliah');
        $krs_status = $this->db->select('status, COUNT(*) AS jumlah')
                               ->where('tahun_akademik', $period['tahun_akademik'])
                               ->where('semester_akademik', $period['semester_akademik'])
                               ->group_by('status')
                               ->get('krs')
                               ->result();
        $counts = ['DRAFT' => 0, 'MENUNGGU' => 0, 'DISETUJUI' => 0, 'DITOLAK' => 0];
        foreach ($krs_status as $status) {
            $counts[$status->status] = (int)$status->jumlah;
        }

        return [
            'penawaran_aktif' => (int)$offerings,
            'krs' => $counts,
        ];
    }

    public function get_students()
    {
        return $this->db->select('akun.id, akun.nim, akun.nama_lengkap, akun.prodi, akun.semester, dosen_wali.dosen_id')
                        ->from('akun')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = akun.id', 'left')
                        ->where('akun.role', 3)
                        ->where('akun.deleted_at IS NULL', null, false)
                        ->order_by('akun.prodi', 'ASC')
                        ->order_by('akun.nim', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_lecturers()
    {
        return $this->db->select('id, nim, nama_lengkap')
                        ->where('role', 4)
                        ->where('deleted_at IS NULL', null, false)
                        ->order_by('nama_lengkap', 'ASC')
                        ->get('akun')
                        ->result();
    }

    public function create_offering(array $offering)
    {
        $lecturer = $this->db->where('id', (int)$offering['dosen_id'])
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->count_all_results('akun');
        $duplicate = $this->db->where('kode_mk', $offering['kode_mk'])
                              ->where('kelas', $offering['kelas'])
                              ->where('prodi', $offering['prodi'])
                              ->where('semester', (int)$offering['semester'])
                              ->where('tahun_akademik', $offering['tahun_akademik'])
                              ->where('semester_akademik', $offering['semester_akademik'])
                              ->count_all_results('penawaran_matakuliah');
        if ($lecturer !== 1 || $duplicate > 0) {
            return false;
        }

        $schedule_conflict = $this->db->where('tahun_akademik', $offering['tahun_akademik'])
                                      ->where('semester_akademik', $offering['semester_akademik'])
                                      ->where('hari', $offering['hari'])
                                      ->where('aktif', 1)
                                      ->group_start()
                                          ->where('dosen_id', (int)$offering['dosen_id'])
                                          ->or_where('ruangan', $offering['ruangan'])
                                      ->group_end()
                                      ->group_start()
                                          ->where('waktu_mulai <', $offering['waktu_selesai'])
                                          ->where('waktu_selesai >', $offering['waktu_mulai'])
                                      ->group_end()
                                      ->count_all_results('penawaran_matakuliah');
        if ($schedule_conflict > 0) {
            return false;
        }

        return $this->db->insert('penawaran_matakuliah', $offering);
    }

    public function set_offering_active($offering_id, $active)
    {
        $exists = $this->db->where('id', (int)$offering_id)->count_all_results('penawaran_matakuliah');
        if ($exists !== 1) {
            return false;
        }
        return $this->db->where('id', (int)$offering_id)
                        ->update('penawaran_matakuliah', ['aktif' => $active ? 1 : 0]);
    }

    public function assign_advisor($student_id, $lecturer_id, $admin_id)
    {
        $student = $this->db->where('id', (int)$student_id)
                            ->where('role', 3)
                            ->where('deleted_at IS NULL', null, false)
                            ->count_all_results('akun');
        $lecturer = $this->db->where('id', (int)$lecturer_id)
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->count_all_results('akun');
        if ($student !== 1 || $lecturer !== 1) {
            return false;
        }

        $assignment = $this->db->get_where('dosen_wali', ['mahasiswa_id' => (int)$student_id])->row();
        $data = [
            'mahasiswa_id' => (int)$student_id,
            'dosen_id' => (int)$lecturer_id,
            'ditetapkan_oleh' => (int)$admin_id,
        ];

        if ($assignment) {
            return $this->db->where('id', (int)$assignment->id)->update('dosen_wali', $data);
        }

        return $this->db->insert('dosen_wali', $data);
    }

    public function save_student_krs($akun_id, $offering_ids, $submit = false)
    {
        if (!is_array($offering_ids)) {
            return ['success' => false, 'message' => 'Daftar mata kuliah tidak valid.'];
        }

        $ids = [];
        foreach ($offering_ids as $offering_id) {
            if (!is_scalar($offering_id) || !ctype_digit((string)$offering_id) || (int)$offering_id < 1) {
                return ['success' => false, 'message' => 'Pilihan mata kuliah tidak valid.'];
            }
            $ids[] = (int)$offering_id;
        }
        $ids = array_values(array_unique($ids));

        $student = $this->get_account($akun_id);
        if (!$student) {
            return ['success' => false, 'message' => 'Data mahasiswa aktif tidak ditemukan.'];
        }

        $period = $this->get_active_period();
        $offerings = [];
        if ($ids) {
            $offerings = $this->db->where_in('id', $ids)
                                  ->where('prodi', $student->prodi)
                                  ->where('semester', (int)$student->semester)
                                  ->where('tahun_akademik', $period['tahun_akademik'])
                                  ->where('semester_akademik', $period['semester_akademik'])
                                  ->where('aktif', 1)
                                  ->get('penawaran_matakuliah')
                                  ->result();
            if (count($offerings) !== count($ids)) {
                return ['success' => false, 'message' => 'Satu atau lebih mata kuliah sudah tidak tersedia untuk mahasiswa ini. Muat ulang halaman dan periksa pilihan Anda.'];
            }
        }

        $total_sks = 0;
        $schedule = [];
        foreach ($offerings as $offering) {
            $total_sks += (int)$offering->sks;
            foreach ($schedule as $scheduled) {
                if ($offering->hari === $scheduled->hari
                    && $offering->waktu_mulai < $scheduled->waktu_selesai
                    && $offering->waktu_selesai > $scheduled->waktu_mulai) {
                    return ['success' => false, 'message' => 'Jadwal ' . $offering->kode_mk . ' bertabrakan dengan mata kuliah ' . $scheduled->kode_mk . '.'];
                }
            }
            $schedule[] = $offering;
        }

        if ($total_sks > self::MAX_SKS_PER_SEMESTER) {
            return ['success' => false, 'message' => 'Total beban studi melebihi batas maksimal ' . self::MAX_SKS_PER_SEMESTER . ' SKS.'];
        }
        if ($submit && !$ids) {
            return ['success' => false, 'message' => 'Pilih minimal satu mata kuliah sebelum mengajukan KRS.'];
        }
        if ($submit && $total_sks < self::MIN_SKS_PER_SEMESTER) {
            return ['success' => false, 'message' => 'Total beban studi minimal ' . self::MIN_SKS_PER_SEMESTER . ' SKS untuk mengajukan KRS.'];
        }
        if ($submit) {
            $advisor = $this->get_advisor_for_student($akun_id);
            if (!$advisor) {
                return ['success' => false, 'message' => 'Pengajuan belum dapat dikirim karena Dosen Wali belum ditetapkan oleh Admin Akademik.'];
            }
        }

        $this->db->trans_begin();
        $krs = $this->db->where('akun_id', (int)$akun_id)
                        ->where('tahun_akademik', $period['tahun_akademik'])
                        ->where('semester_akademik', $period['semester_akademik'])
                        ->get('krs')
                        ->row();

        if ($krs && !in_array($krs->status, ['DRAFT', 'DITOLAK'], true)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'KRS berstatus ' . $krs->status . ' dan tidak dapat diubah saat ini.'];
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            'akun_id' => (int)$akun_id,
            'tahun_akademik' => $period['tahun_akademik'],
            'semester_akademik' => $period['semester_akademik'],
            'status' => $submit ? 'MENUNGGU' : 'DRAFT',
            'total_sks' => $total_sks,
            'catatan' => null,
            'diajukan_at' => $submit ? $now : null,
            'diproses_oleh' => null,
            'diproses_at' => null,
        ];

        if ($krs) {
            $this->db->where('id', (int)$krs->id)->update('krs', $data);
            $krs_id = (int)$krs->id;
            $this->db->where('krs_id', $krs_id)->delete('krs_detail');
        } else {
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $this->db->insert('krs', $data);
            $krs_id = (int)$this->db->insert_id();
        }

        foreach ($ids as $offering_id) {
            $this->db->insert('krs_detail', [
                'krs_id' => $krs_id,
                'penawaran_id' => $offering_id,
            ]);
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'KRS gagal disimpan karena terjadi kesalahan basis data.'];
        }

        $this->db->trans_commit();
        return [
            'success' => true,
            'message' => $submit
                ? 'KRS berhasil diajukan kepada Dosen Wali.'
                : 'Pilihan mata kuliah berhasil disimpan sebagai draf.',
        ];
    }

    public function get_pending_advisor_krs($lecturer_id)
    {
        return $this->db->select('krs.*, mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.prodi, mahasiswa.semester')
                        ->from('krs')
                        ->join('akun AS mahasiswa', 'mahasiswa.id = krs.akun_id', 'inner')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'inner')
                        ->where('dosen_wali.dosen_id', (int)$lecturer_id)
                        ->where('mahasiswa.role', 3)
                        ->where('mahasiswa.deleted_at IS NULL', null, false)
                        ->where('krs.status', 'MENUNGGU')
                        ->order_by('krs.diajukan_at', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_advisor_students($lecturer_id)
    {
        $period = $this->get_active_period();
        return $this->db->select('mahasiswa.id, mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.prodi, mahasiswa.semester, krs.status AS status_krs, krs.total_sks, krs.tahun_akademik, krs.semester_akademik')
                        ->from('dosen_wali')
                        ->join('akun AS mahasiswa', 'mahasiswa.id = dosen_wali.mahasiswa_id', 'inner')
                        ->join(
                            'krs',
                            'krs.akun_id = mahasiswa.id'
                                . ' AND krs.tahun_akademik = ' . $this->db->escape($period['tahun_akademik'])
                                . ' AND krs.semester_akademik = ' . $this->db->escape($period['semester_akademik']),
                            'left',
                            false
                        )
                        ->where('dosen_wali.dosen_id', (int)$lecturer_id)
                        ->where('mahasiswa.role', 3)
                        ->where('mahasiswa.deleted_at IS NULL', null, false)
                        ->order_by('mahasiswa.nama_lengkap', 'ASC')
                        ->get()
                        ->result();
    }

    public function decide_krs($krs_id, $lecturer_id, $approve, $note = null)
    {
        $this->db->trans_begin();
        $krs = $this->db->select('krs.id, krs.akun_id, krs.status')
                        ->from('krs')
                        ->join('dosen_wali', 'dosen_wali.mahasiswa_id = krs.akun_id', 'inner')
                        ->join('akun AS mahasiswa', 'mahasiswa.id = krs.akun_id', 'inner')
                        ->where('krs.id', (int)$krs_id)
                        ->where('krs.status', 'MENUNGGU')
                        ->where('dosen_wali.dosen_id', (int)$lecturer_id)
                        ->where('mahasiswa.role', 3)
                        ->where('mahasiswa.deleted_at IS NULL', null, false)
                        ->get()
                        ->row();

        if (!$krs) {
            $this->db->trans_rollback();
            return false;
        }

        $has_courses = $this->db->where('krs_id', (int)$krs_id)->count_all_results('krs_detail');
        if ($has_courses < 1) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->where('id', (int)$krs_id)
                 ->where('status', 'MENUNGGU')
                 ->update('krs', [
                     'status' => $approve ? 'DISETUJUI' : 'DITOLAK',
                     'catatan' => $approve ? null : trim((string)$note),
                     'diproses_oleh' => (int)$lecturer_id,
                     'diproses_at' => date('Y-m-d H:i:s'),
                 ]);

        if (!$this->db->trans_status() || $this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
}
