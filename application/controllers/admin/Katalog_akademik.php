<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Katalog_akademik extends MY_Role_Controller
{
    protected $allowed_roles = [1, 2];
    protected $allowed_bureaus = ['akademik'];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('admin/M_katalog_akademik_admin', 'M_katalog');
    }

    public function index()
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema Data Center Kurikulum belum tersedia. Jalankan migrasi 20261016_001, 20261016_002, lalu 20261023_003_data_center_kurikulum.sql.', 503);
            return;
        }

        $page = max(1, (int)$this->input->get('page'));
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $old_input = $this->session->flashdata('catalog_old_input') ?: [];
        $old_form = $old_input['catalog_form'] ?? '';
        $mapping_id = (int)($old_form === 'mapping' ? ($old_input['id'] ?? 0) : $this->input->get('edit_mapping'));
        $offering_id = (int)($old_form === 'offering' ? ($old_input['id'] ?? 0) : $this->input->get('edit_offering'));
        $editing_mapping = $this->M_katalog->get_record('kurikulum_mata_kuliah', $mapping_id);
        $editing_offering = $this->M_katalog->get_offering_record($offering_id);
        $editing_program = $this->M_katalog->get_record('program_studi', (int)($old_form === 'program' ? ($old_input['id'] ?? 0) : $this->input->get('edit_program')));
        $editing_course = $this->M_katalog->get_record('mata_kuliah', (int)($old_form === 'course' ? ($old_input['id'] ?? 0) : $this->input->get('edit_course')));
        $editing_curriculum = $this->M_katalog->get_record('kurikulum', (int)($old_form === 'curriculum' ? ($old_input['id'] ?? 0) : $this->input->get('edit_curriculum')));
        $curriculum_courses = $this->M_katalog->get_curriculum_courses($limit, $offset);
        $selected_student_id = (int)($old_input['mahasiswa_id'] ?? 0);
        $mapping_curriculum_id = (int)($old_form === 'mapping'
            ? ($old_input['kurikulum_id'] ?? 0)
            : ($editing_mapping->kurikulum_id ?? 0));
        $relation_mapping_id = (int)($editing_mapping->id ?? 0);
        $current_mapping_relations = $relation_mapping_id > 0
            ? $this->M_katalog->get_curriculum_course_relations([$relation_mapping_id])
            : [];
        $existing_minimum_grades = array_filter(array_map(function ($relation) {
            return $relation->nilai_minimum;
        }, $current_mapping_relations[$relation_mapping_id]['PRASYARAT'] ?? []), function ($grade) {
            return $grade !== null;
        });
        $mapping_minimum_grade = $old_form === 'mapping'
            ? ($old_input['nilai_minimum'] ?? '')
            : (count(array_unique($existing_minimum_grades)) === 1 ? reset($existing_minimum_grades) : '');
        $old_relation_ids = $old_form === 'mapping'
            ? [
                'PRASYARAT' => $old_input['prasyarat_ids'] ?? [],
                'KOREQUISITE' => $old_input['korequisite_ids'] ?? [],
            ]
            : [
                'PRASYARAT' => array_column($current_mapping_relations[$relation_mapping_id]['PRASYARAT'] ?? [], 'source_id'),
                'KOREQUISITE' => array_column($current_mapping_relations[$relation_mapping_id]['KOREQUISITE'] ?? [], 'source_id'),
            ];
        foreach (['PRASYARAT', 'KOREQUISITE'] as $relation_type) {
            if (!is_array($old_relation_ids[$relation_type])) {
                $old_relation_ids[$relation_type] = [];
            }
        }
        $offering_course_id = (int)($old_form === 'offering'
            ? ($old_input['mata_kuliah_id'] ?? 0)
            : ($editing_offering->mata_kuliah_id ?? 0));
        $offering_lecturer_id = (int)($old_form === 'offering'
            ? ($old_input['dosen_id'] ?? 0)
            : ($editing_offering->dosen_id ?? 0));
        $offering_reconciliation_counts = $this->M_katalog->get_offering_reconciliation_counts();
        $data = [
            'title' => 'Data Center Kurikulum - Smart Campus',
            'page_title' => 'Data Center Kurikulum',
            'page_desc' => 'Kelola kurikulum program studi, relasi mata kuliah, kelas, dan dosen pengampu.',
            'card_subtitle' => 'Biro Akademik',
            'page' => $page,
            'limit' => $limit,
            'programs' => $this->M_katalog->get_programs($limit, $offset),
            'courses' => $this->M_katalog->get_courses($limit, $offset),
            'curricula' => $this->M_katalog->get_curricula($limit, $offset),
            'curriculum_courses' => $curriculum_courses,
            'curriculum_course_relations' => $this->M_katalog->get_curriculum_course_relations(
                array_map(function ($item) { return (int)$item->id; }, $curriculum_courses)
            ),
            'curriculum_course_options' => $mapping_curriculum_id > 0
                ? $this->M_katalog->get_curriculum_course_options($mapping_curriculum_id, $relation_mapping_id)
                : [],
            'lecturer_assignments' => $this->M_katalog->get_lecturer_program_assignments($limit, $offset),
            'student_curriculum_assignments' => $this->M_katalog->get_student_curriculum_assignments($limit, $offset),
            'offering_reconciliation' => $this->M_katalog->get_offering_reconciliation($limit, $offset),
            'offerings' => $this->M_katalog->get_offerings($limit, $offset),
            'offering_reconciliation_counts' => $offering_reconciliation_counts,
            'program_options' => $this->M_katalog->get_program_options(),
            'active_program_options' => $this->M_katalog->get_active_program_options(),
            'lecturer_options' => $this->M_katalog->get_lecturer_options('', (int)($old_input['dosen_id'] ?? 0)),
            'offering_lecturer_options' => $this->M_katalog->get_lecturer_options('', $offering_lecturer_id),
            'student_options' => $this->M_katalog->get_student_options('', $selected_student_id),
            'student_curriculum_options' => $selected_student_id > 0 ? $this->M_katalog->get_curricula_for_student($selected_student_id) : [],
            'course_options' => $this->M_katalog->get_course_options('', (int)($old_input['mata_kuliah_id'] ?? ($editing_mapping->mata_kuliah_id ?? 0))),
            'offering_course_options' => $this->M_katalog->get_course_options('', $offering_course_id),
            'curriculum_options' => $this->M_katalog->get_curriculum_options('', (int)($old_input['kurikulum_id'] ?? ($editing_mapping->kurikulum_id ?? 0))),
            'old_relation_ids' => $old_relation_ids,
            'mapping_minimum_grade' => $mapping_minimum_grade,
            'old_input' => $old_input,
            'counts' => [
                'programs' => $this->M_katalog->count_records('program_studi'),
                'courses' => $this->M_katalog->count_records('mata_kuliah'),
                'curricula' => $this->M_katalog->count_records('kurikulum'),
                'curriculum_courses' => $this->M_katalog->count_records('kurikulum_mata_kuliah'),
                'lecturer_assignments' => $this->M_katalog->count_records('penugasan_dosen_prodi'),
                'student_curriculum_assignments' => $this->M_katalog->count_records('kurikulum_mahasiswa'),
                'students' => $this->M_katalog->count_active_students(),
                'offerings' => $offering_reconciliation_counts['total'],
            ],
            'editing' => [
                'program' => $editing_program,
                'course' => $editing_course,
                'curriculum' => $editing_curriculum,
                'curriculum_course' => $editing_mapping,
                'offering' => $editing_offering,
            ],
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/katalog', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save_program()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Program Studi', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('kode', 'Kode Program Studi', 'trim|max_length[32]');
        $this->form_validation->set_rules('nama', 'Nama Program Studi', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('fakultas', 'Fakultas/Unit', 'trim|max_length[150]');
        $this->form_validation->set_rules('jenjang', 'Jenjang', 'trim|max_length[50]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[PENDING_REVIEW,ACTIVE,INACTIVE]');
        $this->form_validation->set_rules('berlaku_mulai', 'Tanggal Mulai Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('berlaku_sampai', 'Tanggal Akhir Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $kode = trim((string)$this->input->post('kode', true));
        $berlaku_mulai = trim((string)$this->input->post('berlaku_mulai', true));
        $berlaku_sampai = trim((string)$this->input->post('berlaku_sampai', true));
        if ($berlaku_mulai !== '' && $berlaku_sampai !== '' && $berlaku_mulai > $berlaku_sampai) {
            $this->validation_failed('Tanggal mulai berlaku tidak boleh setelah tanggal akhir.');
            return;
        }
        $result = $this->M_katalog->save_program((int)$this->input->post('id', true), [
            'kode' => $kode !== '' ? strtoupper($kode) : null,
            'nama' => trim($this->input->post('nama', true)),
            'fakultas' => trim($this->input->post('fakultas', true)) ?: null,
            'jenjang' => trim($this->input->post('jenjang', true)) ?: null,
            'status' => $this->input->post('status', true),
            'berlaku_mulai' => $berlaku_mulai !== '' ? $berlaku_mulai : null,
            'berlaku_sampai' => $berlaku_sampai !== '' ? $berlaku_sampai : null,
        ], (int)$this->session->userdata('id'), trim($this->input->post('reason', true)));
        $this->finish_save($result);
    }

    public function save_course()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Mata Kuliah', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('kode', 'Kode Mata Kuliah', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('nama', 'Nama Mata Kuliah', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|max_length[5000]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[PENDING_REVIEW,ACTIVE,INACTIVE]');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $result = $this->M_katalog->save_course((int)$this->input->post('id', true), [
            'kode' => strtoupper(trim($this->input->post('kode', true))),
            'nama' => trim($this->input->post('nama', true)),
            'deskripsi' => trim($this->input->post('deskripsi', true)) ?: null,
            'status' => $this->input->post('status', true),
        ], (int)$this->session->userdata('id'), trim($this->input->post('reason', true)));
        $this->finish_save($result);
    }

    public function save_curriculum()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Kurikulum', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('program_studi_id', 'Program Studi', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('kode', 'Kode Kurikulum', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('nama', 'Nama Kurikulum', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('angkatan_mulai', 'Angkatan Awal', 'trim|callback_optional_year');
        $this->form_validation->set_rules('angkatan_sampai', 'Angkatan Akhir', 'trim|callback_optional_year');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[DRAFT,ACTIVE,ARCHIVED]');
        $this->form_validation->set_rules('berlaku_mulai', 'Tanggal Mulai Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('berlaku_sampai', 'Tanggal Akhir Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $start_year = trim((string)$this->input->post('angkatan_mulai', true));
        $end_year = trim((string)$this->input->post('angkatan_sampai', true));
        $berlaku_mulai = trim((string)$this->input->post('berlaku_mulai', true));
        $berlaku_sampai = trim((string)$this->input->post('berlaku_sampai', true));
        if ($start_year !== '' && $end_year !== '' && (int)$start_year > (int)$end_year) {
            $this->validation_failed('Angkatan awal tidak boleh lebih besar dari angkatan akhir.');
            return;
        }
        if ($berlaku_mulai !== '' && $berlaku_sampai !== '' && $berlaku_mulai > $berlaku_sampai) {
            $this->validation_failed('Tanggal mulai berlaku tidak boleh setelah tanggal akhir.');
            return;
        }

        $result = $this->M_katalog->save_curriculum((int)$this->input->post('id', true), [
            'program_studi_id' => (int)$this->input->post('program_studi_id', true),
            'kode' => strtoupper(trim($this->input->post('kode', true))),
            'nama' => trim($this->input->post('nama', true)),
            'angkatan_mulai' => $start_year !== '' ? (int)$start_year : null,
            'angkatan_sampai' => $end_year !== '' ? (int)$end_year : null,
            'berlaku_mulai' => $berlaku_mulai !== '' ? $berlaku_mulai : null,
            'berlaku_sampai' => $berlaku_sampai !== '' ? $berlaku_sampai : null,
            'status' => $this->input->post('status', true),
        ], (int)$this->session->userdata('id'), trim($this->input->post('reason', true)));
        $this->finish_save($result);
    }

    public function save_mapping()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Pemetaan', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('kurikulum_id', 'Kurikulum', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('mata_kuliah_id', 'Mata Kuliah', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('semester_rekomendasi', 'Semester Rekomendasi', 'trim|callback_optional_semester');
        $this->form_validation->set_rules('sks', 'SKS Kurikulum', 'required|integer|greater_than[0]|less_than_equal_to[24]');
        $this->form_validation->set_rules('kelompok_mata_kuliah', 'Kelompok Mata Kuliah', 'trim|max_length[50]');
        $this->form_validation->set_rules('pola_semester', 'Pola Semester', 'required|in_list[SEMUA,GANJIL,GENAP]');
        $this->form_validation->set_rules('semester_akhir', 'Semester Akhir', 'required|in_list[0,1]');
        $this->form_validation->set_rules('nilai_minimum', 'Nilai Minimum Prasyarat', 'trim|callback_optional_grade');
        $this->form_validation->set_rules('wajib', 'Status Mata Kuliah', 'required|in_list[0,1]');
        $this->form_validation->set_rules('status', 'Status Pemetaan', 'required|in_list[ACTIVE,INACTIVE]');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $prerequisite_ids = $this->input->post('prasyarat_ids');
        $corequisite_ids = $this->input->post('korequisite_ids');
        if (!$this->valid_relation_ids($prerequisite_ids) || !$this->valid_relation_ids($corequisite_ids)) {
            $this->validation_failed('Pilih relasi mata kuliah yang valid pada kurikulum yang sama.');
            return;
        }
        $semester = trim((string)$this->input->post('semester_rekomendasi', true));
        $result = $this->M_katalog->save_curriculum_course_with_relations((int)$this->input->post('id', true), [
            'kurikulum_id' => (int)$this->input->post('kurikulum_id', true),
            'mata_kuliah_id' => (int)$this->input->post('mata_kuliah_id', true),
            'semester_rekomendasi' => $semester !== '' ? (int)$semester : null,
            'pola_semester' => $this->input->post('pola_semester', true),
            'semester_akhir' => (int)$this->input->post('semester_akhir', true),
            'sks' => (int)$this->input->post('sks', true),
            'kelompok_mata_kuliah' => trim($this->input->post('kelompok_mata_kuliah', true)) ?: null,
            'wajib' => (int)$this->input->post('wajib', true),
            'status' => $this->input->post('status', true),
        ], [
            'PRASYARAT' => $prerequisite_ids ?: [],
            'KOREQUISITE' => $corequisite_ids ?: [],
        ], trim((string)$this->input->post('nilai_minimum', true)) !== ''
            ? (float)$this->input->post('nilai_minimum', true)
            : null, (int)$this->session->userdata('id'), trim($this->input->post('reason', true)));
        $this->finish_save($result);
    }

    public function save_offering()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Kelas', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('mata_kuliah_id', 'Mata Kuliah', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('program_studi_id', 'Program Studi', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('semester', 'Semester Mahasiswa', 'required|integer|greater_than[0]|less_than_equal_to[14]');
        $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'trim|required|regex_match[/^\\d{4}\\/\\d{4}$/]');
        $this->form_validation->set_rules('semester_akademik', 'Semester Akademik', 'required|in_list[Ganjil,Genap]');
        $this->form_validation->set_rules('sks', 'SKS', 'required|integer|greater_than[0]|less_than_equal_to[24]');
        $this->form_validation->set_rules('kelas', 'Kelas', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('hari', 'Hari', 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('ruangan', 'Ruangan', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('dosen_id', 'Dosen Pengampu', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $start = trim((string)$this->input->post('waktu_mulai', true));
        $end = trim((string)$this->input->post('waktu_selesai', true));
        if (!$this->valid_time($start) || !$this->valid_time($end) || $start >= $end) {
            $this->validation_failed('Rentang waktu kuliah tidak valid; waktu selesai harus setelah waktu mulai.');
            return;
        }

        $result = $this->M_katalog->save_offering_center((int)$this->input->post('id', true), [
            'mata_kuliah_id' => (int)$this->input->post('mata_kuliah_id', true),
            'program_studi_id' => (int)$this->input->post('program_studi_id', true),
            'semester' => (int)$this->input->post('semester', true),
            'tahun_akademik' => trim($this->input->post('tahun_akademik', true)),
            'semester_akademik' => $this->input->post('semester_akademik', true),
            'sks' => (int)$this->input->post('sks', true),
            'kelas' => strtoupper(trim($this->input->post('kelas', true))),
            'hari' => $this->input->post('hari', true),
            'waktu_mulai' => $start . ':00',
            'waktu_selesai' => $end . ':00',
            'ruangan' => trim($this->input->post('ruangan', true)),
            'dosen_id' => (int)$this->input->post('dosen_id', true),
        ], (int)$this->session->userdata('id'), trim($this->input->post('reason', true)));
        $this->finish_save($result);
    }

    public function toggle_offering()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Kelas', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('aktif', 'Status Kelas', 'required|in_list[0,1]');
        $this->form_validation->set_rules('reason', 'Alasan perubahan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $result = $this->M_katalog->set_offering_active_center(
            (int)$this->input->post('id', true),
            $this->input->post('aktif', true) === '1',
            (int)$this->session->userdata('id'),
            trim($this->input->post('reason', true))
        );
        $this->finish_save($result);
    }

    public function curriculum_course_options()
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema Data Center Kurikulum belum tersedia.', 503);
            return;
        }
        $curriculum_id = (int)$this->input->get('kurikulum_id', true);
        if ($curriculum_id < 1) {
            $this->output->set_status_header(400)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Pilih kurikulum yang valid.']));
            return;
        }
        $exclude_id = (int)$this->input->get('exclude_id', true);
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($this->M_katalog->get_curriculum_course_options($curriculum_id, $exclude_id)));
    }

    public function save_lecturer_assignment()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('dosen_id', 'Dosen', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('program_studi_id', 'Program Studi', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('jenis_penugasan', 'Jenis Penugasan', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('berlaku_mulai', 'Tanggal Mulai Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('berlaku_sampai', 'Tanggal Akhir Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('reason', 'Alasan penugasan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $berlaku_mulai = trim((string)$this->input->post('berlaku_mulai', true));
        $berlaku_sampai = trim((string)$this->input->post('berlaku_sampai', true));
        if ($berlaku_mulai !== '' && $berlaku_sampai !== '' && $berlaku_mulai > $berlaku_sampai) {
            $this->validation_failed('Tanggal mulai berlaku tidak boleh setelah tanggal akhir.');
            return;
        }
        $result = $this->M_katalog->save_lecturer_program_assignment([
            'dosen_id' => (int)$this->input->post('dosen_id', true),
            'program_studi_id' => (int)$this->input->post('program_studi_id', true),
            'jenis_penugasan' => trim($this->input->post('jenis_penugasan', true)),
            'berlaku_mulai' => $berlaku_mulai !== '' ? $berlaku_mulai : null,
            'berlaku_sampai' => $berlaku_sampai !== '' ? $berlaku_sampai : null,
            'alasan_perubahan' => trim($this->input->post('reason', true)),
        ], (int)$this->session->userdata('id'));
        $this->finish_save($result);
    }

    public function deactivate_lecturer_assignment()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('id', 'ID Penugasan', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('reason', 'Alasan penonaktifan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }
        $result = $this->M_katalog->deactivate_lecturer_program_assignment(
            (int)$this->input->post('id', true),
            (int)$this->session->userdata('id'),
            trim($this->input->post('reason', true))
        );
        $this->finish_save($result);
    }

    public function save_student_curriculum()
    {
        if (!$this->require_post()) return;
        $this->form_validation->set_rules('mahasiswa_id', 'Mahasiswa', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('kurikulum_id', 'Kurikulum', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('berlaku_mulai', 'Tanggal Mulai Berlaku', 'required|callback_optional_date');
        $this->form_validation->set_rules('berlaku_sampai', 'Tanggal Akhir Berlaku', 'trim|callback_optional_date');
        $this->form_validation->set_rules('reason', 'Alasan penetapan', 'trim|required|min_length[5]|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->validation_failed();
            return;
        }

        $berlaku_mulai = trim((string)$this->input->post('berlaku_mulai', true));
        $berlaku_sampai = trim((string)$this->input->post('berlaku_sampai', true));
        if ($berlaku_sampai !== '' && $berlaku_mulai > $berlaku_sampai) {
            $this->validation_failed('Tanggal mulai berlaku tidak boleh setelah tanggal akhir.');
            return;
        }
        $result = $this->M_katalog->save_student_curriculum_assignment(
            (int)$this->input->post('mahasiswa_id', true),
            (int)$this->input->post('kurikulum_id', true),
            $berlaku_mulai,
            $berlaku_sampai !== '' ? $berlaku_sampai : null,
            (int)$this->session->userdata('id'),
            trim($this->input->post('reason', true))
        );
        $this->finish_save($result);
    }

    public function search_courses()
    {
        $this->search_catalog_options('course');
    }

    public function search_curricula()
    {
        $this->search_catalog_options('curriculum');
    }

    public function search_lecturers()
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema katalog akademik belum tersedia.', 503);
            return;
        }
        $term = trim((string)$this->input->get('q', true));
        if (strlen($term) < 2) {
            $this->output->set_status_header(400)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Masukkan minimal dua karakter untuk mencari.']));
            return;
        }
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($this->M_katalog->get_lecturer_options($term)));
    }

    public function search_students()
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema katalog akademik belum tersedia.', 503);
            return;
        }
        $term = trim((string)$this->input->get('q', true));
        if (strlen($term) < 2) {
            $this->output->set_status_header(400)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Masukkan minimal dua karakter untuk mencari.']));
            return;
        }
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($this->M_katalog->get_student_options($term)));
    }

    public function student_curricula_options()
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema katalog akademik belum tersedia.', 503);
            return;
        }
        $mahasiswa_id = (int)$this->input->get('mahasiswa_id', true);
        if ($mahasiswa_id < 1) {
            $this->output->set_status_header(400)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Pilih mahasiswa yang valid.']));
            return;
        }
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($this->M_katalog->get_curricula_for_student($mahasiswa_id)));
    }

    public function optional_year($value)
    {
        return $value === '' || (ctype_digit((string)$value) && (int)$value >= 1900 && (int)$value <= 2200);
    }

    public function optional_semester($value)
    {
        return $value === '' || (ctype_digit((string)$value) && (int)$value >= 1 && (int)$value <= 14);
    }

    public function optional_grade($value)
    {
        return $value === '' || (is_numeric($value) && (float)$value >= 0 && (float)$value <= 4);
    }

    public function optional_date($value)
    {
        if ($value === '') {
            return true;
        }

        $date = DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    private function require_post()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            return true;
        }
        show_error('Metode permintaan tidak diizinkan.', 405);
        return false;
    }

    private function valid_relation_ids($ids)
    {
        if ($ids === null || $ids === '') {
            return true;
        }
        if (!is_array($ids)) {
            return false;
        }
        foreach ($ids as $id) {
            if (!is_scalar($id) || !ctype_digit((string)$id) || (int)$id < 1) {
                return false;
            }
        }
        return true;
    }

    private function valid_time($value)
    {
        $time = DateTime::createFromFormat('!H:i', $value);
        return $time && $time->format('H:i') === $value;
    }

    private function validation_failed($message = null)
    {
        $this->session->set_flashdata('error', $message ?: (validation_errors('', '') ?: 'Periksa kembali data yang dimasukkan.'));
        $old_input = $this->input->post(NULL, true);
        if (!is_array($old_input)) {
            $old_input = [];
        }
        $old_input['catalog_form'] = $this->input->post('catalog_form', true);
        $this->session->set_flashdata('catalog_old_input', $old_input);
        redirect('admin/katalog-akademik');
    }

    private function search_catalog_options($type)
    {
        if (!$this->M_katalog->schema_ready()) {
            show_error('Skema katalog akademik belum tersedia.', 503);
            return;
        }

        $term = trim((string)$this->input->get('q', true));
        if (strlen($term) < 2) {
            $this->output->set_status_header(400)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Masukkan minimal dua karakter untuk mencari.']));
            return;
        }

        $results = $type === 'course'
            ? $this->M_katalog->get_course_options($term)
            : $this->M_katalog->get_curriculum_options($term);
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($results));
    }

    private function finish_save(array $result)
    {
        $this->session->set_flashdata($result['success'] ? 'success' : 'error', $result['message']);
        redirect('admin/katalog-akademik');
    }
}
