<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_katalog_akademik_admin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function schema_ready()
    {
        foreach ([
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
            'penugasan_dosen_prodi',
            'kurikulum_mahasiswa',
            'pemetaan_penawaran_mata_kuliah',
            'histori_katalog_akademik',
        ] as $table) {
            if (!$this->db->table_exists($table)) {
                return false;
            }
        }

        return true;
    }

    public function get_programs($limit, $offset)
    {
        return $this->db->order_by('nama', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get('program_studi')
                        ->result();
    }

    public function get_courses($limit, $offset)
    {
        return $this->db->order_by('kode', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get('mata_kuliah')
                        ->result();
    }

    public function get_curricula($limit, $offset)
    {
        return $this->db->select('kurikulum.*, program_studi.nama AS program_name')
                        ->from('kurikulum')
                        ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                        ->order_by('program_studi.nama', 'ASC')
                        ->order_by('kurikulum.kode', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_curriculum_courses($limit, $offset)
    {
        return $this->db->select('kurikulum_mata_kuliah.*, kurikulum.kode AS curriculum_code, mata_kuliah.kode AS kode_mata_kuliah, mata_kuliah.nama AS nama_mata_kuliah')
                        ->from('kurikulum_mata_kuliah')
                        ->join('kurikulum', 'kurikulum.id = kurikulum_mata_kuliah.kurikulum_id', 'inner')
                        ->join('mata_kuliah', 'mata_kuliah.id = kurikulum_mata_kuliah.mata_kuliah_id', 'inner')
                        ->order_by('kurikulum.kode', 'ASC')
                        ->order_by('kurikulum_mata_kuliah.semester_rekomendasi', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_program_options()
    {
        return $this->db->order_by('nama', 'ASC')->get('program_studi')->result();
    }

    public function get_active_program_options()
    {
        return $this->db->where('status', 'ACTIVE')
                        ->order_by('nama', 'ASC')
                        ->get('program_studi')
                        ->result();
    }

    public function get_lecturer_program_assignments($limit, $offset)
    {
        return $this->db->select('penugasan_dosen_prodi.*, akun_biro.nim AS lecturer_nim, akun_biro.nama_lengkap AS lecturer_name, program_studi.kode AS program_code, program_studi.nama AS program_name')
                        ->from('penugasan_dosen_prodi')
                        ->join('akun_biro', 'akun_biro.id = penugasan_dosen_prodi.dosen_id', 'inner')
                        ->join('program_studi', 'program_studi.id = penugasan_dosen_prodi.program_studi_id', 'inner')
                        ->order_by('penugasan_dosen_prodi.status', 'ASC')
                        ->order_by('penugasan_dosen_prodi.diubah_pada', 'DESC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_lecturer_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, nim, nama_lengkap')
                 ->from('akun_biro')
                 ->where('role', 4)
                 ->where('deleted_at IS NULL', null, false)
                 ->group_start()
                 ->like('nim', $search)
                 ->or_like('nama_lengkap', $search)
                 ->group_end()
                 ->order_by('nama_lengkap', 'ASC')
                 ->limit(20);

        $results = $this->db->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, nim, nama_lengkap')
                                 ->where('id', (int)$selected_id)
                                 ->where('role', 4)
                                 ->where('deleted_at IS NULL', null, false)
                                 ->get('akun_biro')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $lecturer) {
                    if ((int)$lecturer->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }

        return $results;
    }

    public function get_student_curriculum_assignments($limit, $offset)
    {
        return $this->db->select('akun_mahasiswa.id AS mahasiswa_id, akun_mahasiswa.nim, akun_mahasiswa.nama_lengkap, akun_mahasiswa.prodi, kurikulum_mahasiswa.id AS assignment_id, kurikulum_mahasiswa.kurikulum_id, kurikulum_mahasiswa.berlaku_mulai, kurikulum_mahasiswa.berlaku_sampai, kurikulum_mahasiswa.alasan_perubahan, kurikulum_mahasiswa.dibuat_pada AS assigned_at, kurikulum.kode AS curriculum_code, kurikulum.nama AS curriculum_name')
                        ->from('kurikulum_mahasiswa')
                        ->join('akun_mahasiswa', 'akun_mahasiswa.id = kurikulum_mahasiswa.mahasiswa_id', 'inner')
                        ->join('kurikulum', 'kurikulum.id = kurikulum_mahasiswa.kurikulum_id', 'inner')
                        ->order_by('kurikulum_mahasiswa.dibuat_pada', 'DESC')
                        ->order_by('kurikulum_mahasiswa.id', 'DESC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function count_active_students()
    {
        return $this->db->where('deleted_at IS NULL', null, false)
                        ->count_all_results('akun_mahasiswa');
    }

    public function get_student_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, nim, nama_lengkap, prodi, angkatan')
                 ->from('akun_mahasiswa')
                 ->where('deleted_at IS NULL', null, false);
        if ($search !== '') {
            $this->db->group_start()
                     ->like('nim', $search)
                     ->or_like('nama_lengkap', $search)
                     ->or_like('prodi', $search)
                     ->group_end();
        }
        $results = $this->db->order_by('nama_lengkap', 'ASC')->limit(20)->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, nim, nama_lengkap, prodi, angkatan')
                                 ->where('id', (int)$selected_id)
                                 ->where('deleted_at IS NULL', null, false)
                                 ->get('akun_mahasiswa')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $student) {
                    if ((int)$student->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }

        return $results;
    }

    public function get_curricula_for_student($mahasiswa_id)
    {
        $student = $this->db->select('prodi')
                            ->where('id', (int)$mahasiswa_id)
                            ->where('deleted_at IS NULL', null, false)
                            ->get('akun_mahasiswa')
                            ->row();
        if (!$student || trim((string)$student->prodi) === '') {
            return [];
        }

        return $this->db->select('kurikulum.id, kurikulum.kode, kurikulum.nama, program_studi.nama AS program_name')
                        ->from('kurikulum')
                        ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                        ->where('kurikulum.status', 'ACTIVE')
                        ->where('LOWER(TRIM(program_studi.nama)) = LOWER(TRIM(' . $this->db->escape($student->prodi) . '))', null, false)
                        ->order_by('kurikulum.kode', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_offering_reconciliation($limit, $offset)
    {
        return $this->db->select('penawaran_matakuliah.id, penawaran_matakuliah.kode_mk, penawaran_matakuliah.nama_mk, penawaran_matakuliah.prodi, penawaran_matakuliah.tahun_akademik, penawaran_matakuliah.semester_akademik, penawaran_matakuliah.kelas, pemetaan_penawaran_mata_kuliah.mata_kuliah_id, linked_course.kode AS linked_course_code, linked_course.nama AS linked_course_name, exact_course.id AS candidate_course_id, exact_course.kode AS candidate_course_code, exact_course.nama AS candidate_course_name')
                        ->from('penawaran_matakuliah')
                        ->join('pemetaan_penawaran_mata_kuliah', 'pemetaan_penawaran_mata_kuliah.penawaran_id = penawaran_matakuliah.id', 'left')
                        ->join('mata_kuliah AS linked_course', 'linked_course.id = pemetaan_penawaran_mata_kuliah.mata_kuliah_id', 'left')
                        ->join('mata_kuliah AS exact_course', 'UPPER(TRIM(exact_course.kode)) = UPPER(TRIM(penawaran_matakuliah.kode_mk))', 'left', false)
                        ->order_by('penawaran_matakuliah.tahun_akademik', 'DESC')
                        ->order_by('penawaran_matakuliah.prodi', 'ASC')
                        ->order_by('penawaran_matakuliah.kode_mk', 'ASC')
                        ->order_by('penawaran_matakuliah.kelas', 'ASC')
                        ->limit((int)$limit, (int)$offset)
                        ->get()
                        ->result();
    }

    public function get_offering_reconciliation_counts()
    {
        $mapped = $this->db->from('pemetaan_penawaran_mata_kuliah')->count_all_results();
        $total = $this->db->count_all('penawaran_matakuliah');

        return [
            'total' => (int)$total,
            'mapped' => (int)$mapped,
            'unmapped' => max(0, (int)$total - (int)$mapped),
        ];
    }

    public function save_student_curriculum_assignment($mahasiswa_id, $kurikulum_id, $berlaku_mulai, $berlaku_sampai, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }
        $mahasiswa_id = (int)$mahasiswa_id;
        $kurikulum_id = (int)$kurikulum_id;
        $actor_id = (int)$actor_id;

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting student curriculum assignment');
            return ['success' => false, 'message' => 'Transaksi penetapan kurikulum tidak dapat dimulai.'];
        }

        $student_query = $this->db->query(
            'SELECT `id`, `prodi` FROM `akun_mahasiswa` WHERE `id` = ? AND `deleted_at` IS NULL FOR UPDATE',
            [$mahasiswa_id]
        );
        $curriculum_query = $this->db->query(
            'SELECT kurikulum.*, program_studi.nama AS program_name, program_studi.status AS program_status, program_studi.berlaku_mulai AS program_valid_from, program_studi.berlaku_sampai AS program_valid_until
             FROM kurikulum
             INNER JOIN program_studi ON program_studi.id = kurikulum.program_studi_id
             WHERE kurikulum.id = ? FOR UPDATE',
            [$kurikulum_id]
        );
        $student = $student_query ? $student_query->row() : null;
        $curriculum = $curriculum_query ? $curriculum_query->row() : null;
        if (!$student_query || !$curriculum_query) {
            $this->log_database_error('locking student and curriculum');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Data mahasiswa atau kurikulum gagal dibaca.'];
        }
        if (!$student || !$curriculum
            || $curriculum->status !== 'ACTIVE'
            || $curriculum->program_status !== 'ACTIVE'
            || trim((string)$student->prodi) === ''
            || strcasecmp(trim((string)$student->prodi), trim((string)$curriculum->program_name)) !== 0) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Mahasiswa dan kurikulum harus berasal dari program studi yang sama dan masih aktif.'];
        }
        if (!empty($curriculum->berlaku_mulai) && $berlaku_mulai < $curriculum->berlaku_mulai) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada sebelum kurikulum mulai berlaku.'];
        }
        if (!empty($curriculum->berlaku_sampai) && $berlaku_mulai > $curriculum->berlaku_sampai) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada setelah masa berlaku kurikulum.'];
        }
        if (!empty($curriculum->berlaku_sampai)
            && ($berlaku_sampai === null || $berlaku_sampai > $curriculum->berlaku_sampai)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan tidak boleh melewati masa berlaku kurikulum.'];
        }
        if (!empty($curriculum->program_valid_from) && $berlaku_mulai < $curriculum->program_valid_from) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Tanggal penetapan berada sebelum masa berlaku program studi.'];
        }
        if (!empty($curriculum->program_valid_until)
            && ($berlaku_mulai > $curriculum->program_valid_until
                || $berlaku_sampai === null
                || $berlaku_sampai > $curriculum->program_valid_until)) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan tidak boleh melewati masa berlaku program studi.'];
        }

        $overlap_sql = 'SELECT * FROM `kurikulum_mahasiswa`
                        WHERE `mahasiswa_id` = ?
                        AND (`berlaku_sampai` IS NULL OR `berlaku_sampai` >= ?)';
        $overlap_params = [$mahasiswa_id, $berlaku_mulai];
        if ($berlaku_sampai !== null) {
            $overlap_sql .= ' AND `berlaku_mulai` <= ?';
            $overlap_params[] = $berlaku_sampai;
        }
        $overlap_sql .= ' ORDER BY `berlaku_mulai` ASC FOR UPDATE';
        $overlap_query = $this->db->query($overlap_sql, $overlap_params);
        if (!$overlap_query) {
            $this->log_database_error('checking overlapping student curricula');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Histori kurikulum mahasiswa gagal dibaca.'];
        }

        $overlaps = $overlap_query->result();
        $previous_open_assignment = null;
        if (count($overlaps) === 1
            && $overlaps[0]->berlaku_sampai === null
            && $overlaps[0]->berlaku_mulai < $berlaku_mulai) {
            $previous_open_assignment = $overlaps[0];
        } elseif (count($overlaps) > 0) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Periode penetapan bertabrakan dengan histori kurikulum mahasiswa.'];
        }

        if ($previous_open_assignment) {
            $previous_end = date('Y-m-d', strtotime($berlaku_mulai . ' -1 day'));
            $sebelum_json = json_encode($previous_open_assignment, JSON_UNESCAPED_UNICODE);
            $after_previous = (array)$previous_open_assignment;
            $after_previous['berlaku_sampai'] = $previous_end;
            $sesudah_json = json_encode($after_previous, JSON_UNESCAPED_UNICODE);
            if ($sebelum_json === false || $sesudah_json === false
                || !$this->db->where('id', (int)$previous_open_assignment->id)
                             ->update('kurikulum_mahasiswa', ['berlaku_sampai' => $previous_end])) {
                $this->log_database_error('closing previous student curriculum assignment');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Penetapan kurikulum sebelumnya gagal ditutup.'];
            }
            $audit_saved = $this->db->insert('histori_katalog_akademik', [
                'jenis_entitas' => 'STUDENT_CURRICULUM',
                'id_entitas' => (int)$previous_open_assignment->id,
                'aksi' => 'UPDATE',
                'sebelum_json' => $sebelum_json,
                'sesudah_json' => $sesudah_json,
                'alasan_perubahan' => $reason,
                'dilakukan_oleh' => $actor_id,
            ]);
            if (!$audit_saved) {
                $this->log_database_error('auditing previous student curriculum assignment');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Perubahan histori kurikulum sebelumnya gagal dicatat; transaksi dibatalkan.'];
            }
        }

        $assignment = [
            'mahasiswa_id' => $mahasiswa_id,
            'kurikulum_id' => $kurikulum_id,
            'berlaku_mulai' => $berlaku_mulai,
            'berlaku_sampai' => $berlaku_sampai,
            'alasan_perubahan' => $reason,
            'diubah_oleh' => $actor_id,
        ];
        if (!$this->db->insert('kurikulum_mahasiswa', $assignment)) {
            $this->log_database_error('saving student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal disimpan.'];
        }
        $assignment_id = (int)$this->db->insert_id();
        $sesudah_json = json_encode($assignment, JSON_UNESCAPED_UNICODE);
        if ($sesudah_json === false) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot penetapan kurikulum mahasiswa: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal dicatat untuk audit.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'STUDENT_CURRICULUM',
            'id_entitas' => $assignment_id,
            'aksi' => 'CREATE',
            'sebelum_json' => null,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('auditing student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penetapan kurikulum gagal dicatat ke histori audit; seluruh perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing student curriculum assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penetapan kurikulum gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Kurikulum mahasiswa berhasil ditetapkan dan dicatat dalam histori.'];
    }

    public function save_lecturer_program_assignment(array $data, $actor_id)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }

        $dosen_id = (int)$data['dosen_id'];
        $program_studi_id = (int)$data['program_studi_id'];
        $actor_id = (int)$actor_id;
        $lecturer = $this->db->select('id')
                             ->where('id', $dosen_id)
                             ->where('role', 4)
                             ->where('deleted_at IS NULL', null, false)
                             ->get('akun_biro')
                             ->row();
        $program = $this->db->select('id')
                            ->where('id', $program_studi_id)
                            ->where('status', 'ACTIVE')
                            ->get('program_studi')
                            ->row();
        if (!$lecturer || !$program) {
            return ['success' => false, 'message' => 'Pilih akun dosen aktif dan program studi berstatus aktif.'];
        }

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting lecturer program assignment');
            return ['success' => false, 'message' => 'Transaksi penugasan dosen tidak dapat dimulai.'];
        }

        $locked_lecturer = $this->db->query(
            'SELECT `id` FROM `akun_biro` WHERE `id` = ? AND `role` = 4 AND `deleted_at` IS NULL FOR UPDATE',
            [$dosen_id]
        );
        $locked_program = $this->db->query(
            'SELECT `id` FROM `program_studi` WHERE `id` = ? AND `status` = ? FOR UPDATE',
            [$program_studi_id, 'ACTIVE']
        );
        if (!$locked_lecturer || !$locked_lecturer->row() || !$locked_program || !$locked_program->row()) {
            if (!$locked_lecturer || !$locked_program) {
                $this->log_database_error('locking lecturer and program for assignment');
            }
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Dosen atau program studi tidak lagi aktif. Muat ulang data dan coba kembali.'];
        }

        $overlap = $this->db->where('dosen_id', $dosen_id)
                            ->where('program_studi_id', $program_studi_id)
                            ->where('jenis_penugasan', $data['jenis_penugasan'])
                            ->where('status', 'ACTIVE')
                            ->group_start()
                            ->where('berlaku_sampai IS NULL', null, false)
                            ->or_where('berlaku_sampai >=', $data['berlaku_mulai'] ?: '1000-01-01')
                            ->group_end()
                            ->group_start()
                            ->where('berlaku_mulai IS NULL', null, false)
                            ->or_where('berlaku_mulai <=', $data['berlaku_sampai'] ?: '9999-12-31')
                            ->group_end()
                            ->get('penugasan_dosen_prodi')
                            ->row();
        if ($overlap) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Sudah ada penugasan aktif dengan jenis dan periode yang bertabrakan.'];
        }

        $reason = $data['alasan_perubahan'];
        unset($data['alasan_perubahan']);
        $data['ditetapkan_oleh'] = $actor_id;
        $data['status'] = 'ACTIVE';
        $data['dibuat_pada'] = date('Y-m-d H:i:s');
        $saved = $this->db->insert('penugasan_dosen_prodi', $data);
        if (!$saved) {
            $this->log_database_error('saving lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan dosen gagal disimpan.'];
        }

        $assignment_id = (int)$this->db->insert_id();
        $sesudah_json = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($sesudah_json === false) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot penugasan dosen: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Penugasan gagal dicatat untuk audit.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'LECTURER_ASSIGNMENT',
            'id_entitas' => $assignment_id,
            'aksi' => 'CREATE',
            'sebelum_json' => null,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing lecturer program assignment audit');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan gagal dicatat ke histori audit; perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penugasan dosen gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Penugasan dosen berhasil disimpan.'];
    }

    public function deactivate_lecturer_program_assignment($id, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia.'];
        }
        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting lecturer assignment deactivation');
            return ['success' => false, 'message' => 'Transaksi penonaktifan penugasan tidak dapat dimulai.'];
        }

        $query = $this->db->query(
            'SELECT * FROM `penugasan_dosen_prodi` WHERE `id` = ? AND `status` = ? FOR UPDATE',
            [(int)$id, 'ACTIVE']
        );
        $before = $query ? $query->row() : null;
        if (!$query || !$before) {
            if (!$query) {
                $this->log_database_error('locking lecturer assignment for deactivation');
            }
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan aktif tidak ditemukan atau gagal dibaca.'];
        }

        $updated = $this->db->where('id', (int)$id)->update('penugasan_dosen_prodi', ['status' => 'INACTIVE']);
        $after = (array)$before;
        $after['status'] = 'INACTIVE';
        $sebelum_json = json_encode($before, JSON_UNESCAPED_UNICODE);
        $sesudah_json = json_encode($after, JSON_UNESCAPED_UNICODE);
        if (!$updated || $sebelum_json === false || $sesudah_json === false) {
            $this->log_database_error('deactivating lecturer program assignment');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penugasan gagal dinonaktifkan atau snapshot audit gagal dibuat.'];
        }
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => 'LECTURER_ASSIGNMENT',
            'id_entitas' => (int)$id,
            'aksi' => 'STATUS_CHANGE',
            'sebelum_json' => $sebelum_json,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => (int)$actor_id,
        ]);
        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing lecturer assignment deactivation audit');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Penonaktifan gagal dicatat ke histori; perubahan dibatalkan.'];
        }
        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing lecturer assignment deactivation');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi penonaktifan penugasan gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Penugasan dosen berhasil dinonaktifkan.'];
    }

    public function get_course_options($search = '', $selected_id = 0)
    {
        $this->db->select('id, kode, nama')
                 ->from('mata_kuliah')
                 ->order_by('kode', 'ASC');
        if ($search !== '') {
            $this->db->group_start()
                     ->like('kode', $search)
                     ->or_like('nama', $search)
                     ->group_end();
        }
        $query = $this->db->limit(20)->get();
        $results = $query->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('id, kode, nama')
                                 ->where('id', (int)$selected_id)
                                 ->get('mata_kuliah')
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $course) {
                    if ((int)$course->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }
        return $results;
    }

    public function get_curriculum_options($search = '', $selected_id = 0)
    {
        $this->db->select('kurikulum.id, kurikulum.kode, program_studi.nama AS program_name')
                 ->from('kurikulum')
                 ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                 ->order_by('program_studi.nama', 'ASC')
                 ->order_by('kurikulum.kode', 'ASC');
        if ($search !== '') {
            $this->db->group_start()
                     ->like('kurikulum.kode', $search)
                     ->or_like('kurikulum.nama', $search)
                     ->or_like('program_studi.nama', $search)
                     ->group_end();
        }
        $results = $this->db->limit(20)->get()->result();
        if ((int)$selected_id > 0) {
            $selected = $this->db->select('kurikulum.id, kurikulum.kode, program_studi.nama AS program_name')
                                 ->from('kurikulum')
                                 ->join('program_studi', 'program_studi.id = kurikulum.program_studi_id', 'inner')
                                 ->where('kurikulum.id', (int)$selected_id)
                                 ->get()
                                 ->row();
            if ($selected) {
                $found = false;
                foreach ($results as $curriculum) {
                    if ((int)$curriculum->id === (int)$selected->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    array_unshift($results, $selected);
                }
            }
        }
        return $results;
    }

    public function get_record($table, $id)
    {
        $allowed_tables = [
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
        ];
        if (!in_array($table, $allowed_tables, true) || (int)$id < 1) {
            return null;
        }

        return $this->db->where('id', (int)$id)->get($table)->row();
    }

    public function count_records($table)
    {
        $allowed_tables = [
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'kurikulum_mata_kuliah',
            'penugasan_dosen_prodi',
            'kurikulum_mahasiswa',
        ];
        if (!in_array($table, $allowed_tables, true)) {
            return 0;
        }

        return $this->db->count_all($table);
    }

    public function save_program($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('PROGRAM', 'program_studi', $id, $data, $actor_id, $reason);
    }

    public function save_course($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('COURSE', 'mata_kuliah', $id, $data, $actor_id, $reason);
    }

    public function save_curriculum($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('CURRICULUM', 'kurikulum', $id, $data, $actor_id, $reason);
    }

    public function save_curriculum_course($id, array $data, $actor_id, $reason)
    {
        return $this->save_catalog_record('CURRICULUM_COURSE', 'kurikulum_mata_kuliah', $id, $data, $actor_id, $reason);
    }

    private function save_catalog_record($jenis_entitas, $table, $id, array $data, $actor_id, $reason)
    {
        if (!$this->schema_ready()) {
            return ['success' => false, 'message' => 'Skema katalog akademik belum tersedia. Jalankan migrasi akademik terlebih dahulu.'];
        }

        $id = (int)$id;
        $actor_id = (int)$actor_id;
        $now = date('Y-m-d H:i:s');
        if ($table !== 'kurikulum_mata_kuliah') {
            if ($id > 0) {
                $data['diubah_oleh'] = $actor_id;
                $data['diubah_pada'] = $now;
            } else {
                $data['dibuat_oleh'] = $actor_id;
                $data['diubah_oleh'] = $actor_id;
            }
        }
        if ($id === 0) {
            $data['dibuat_pada'] = $now;
            $data['diubah_pada'] = $now;
        }

        if (!$this->db->trans_begin()) {
            $this->log_database_error('starting academic catalog transaction');
            return ['success' => false, 'message' => 'Transaksi katalog akademik tidak dapat dimulai.'];
        }
        $before = null;
        if ($id > 0) {
            $record_query = $this->db->query(
                'SELECT * FROM ' . $this->db->protect_identifiers($table) . ' WHERE `id` = ? FOR UPDATE',
                [$id]
            );
            if (!$record_query) {
                $this->log_database_error('locking academic catalog record');
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Data katalog gagal dibaca untuk pembaruan.'];
            }
            $before = $record_query->row();
            if (!$before) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Data katalog yang akan diperbarui tidak ditemukan.'];
            }
        }

        $sesudah_json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $sebelum_json = $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null;
        if ($sesudah_json === false || ($before && $sebelum_json === false)) {
            $this->db->trans_rollback();
            log_message('error', 'Gagal membentuk snapshot audit katalog akademik: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Perubahan tidak dapat dicatat untuk audit.'];
        }

        if ($id > 0) {
            $saved = $this->db->where('id', $id)->update($table, $data);
            $id_entitas = $id;
        } else {
            $saved = $this->db->insert($table, $data);
            $id_entitas = (int)$this->db->insert_id();
        }

        if (!$saved || $id_entitas < 1) {
            $this->log_database_error('saving academic catalog record');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Data katalog gagal disimpan. Periksa kode duplikat dan relasi data.'];
        }

        $aksi = !$before
            ? 'CREATE'
            : ((isset($before->status) && isset($data['status']) && $before->status !== $data['status']) ? 'STATUS_CHANGE' : 'UPDATE');
        $audit_saved = $this->db->insert('histori_katalog_akademik', [
            'jenis_entitas' => $jenis_entitas,
            'id_entitas' => $id_entitas,
            'aksi' => $aksi,
            'sebelum_json' => $sebelum_json,
            'sesudah_json' => $sesudah_json,
            'alasan_perubahan' => $reason,
            'dilakukan_oleh' => $actor_id,
        ]);

        if (!$audit_saved || !$this->db->trans_status()) {
            $this->log_database_error('writing academic catalog audit history');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Perubahan gagal disimpan ke histori audit; seluruh perubahan dibatalkan.'];
        }

        if (!$this->db->trans_commit()) {
            $this->log_database_error('committing academic catalog transaction');
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Transaksi katalog akademik gagal diselesaikan.'];
        }

        return ['success' => true, 'message' => 'Data katalog berhasil disimpan.'];
    }

    private function log_database_error($operation)
    {
        $error = $this->db->error();
        log_message('error', 'Database error while ' . $operation . ': ' . ($error['message'] ?? 'unknown database error'));
    }
}
