<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Capaian extends MY_Role_Controller
{
    // Memperbolehkan Mahasiswa (3), Superadmin (1), Admin (2), dan Dosen (4)
    protected $allowed_roles = [1, 2, 3, 4];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_obe');
        $this->load->helper(['url', 'html']);
    }

    /**
     * Halaman Transkrip Ketercapaian Outcome-Based Education (OBE) Mahasiswa
     * Sekarang disatukan dalam akademik/transkrip?tab=obe
     */
    public function index()
    {
        $params = ['tab' => 'obe'];
        $req_student_id = (int)$this->input->get('student_id');
        $semester_filter = $this->input->get('semester', TRUE);

        if ($req_student_id > 0) {
            $params['student_id'] = $req_student_id;
        }
        if (!empty($semester_filter)) {
            $params['semester_obe'] = $semester_filter;
        }

        redirect('akademik/transkrip?' . http_build_query($params));
    }
}
