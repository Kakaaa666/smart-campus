<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Obe extends MY_Role_Controller
{
    protected $allowed_roles = [1, 2]; // Super Admin (1) & Admin Biro Penjaminan Mutu (2)
    protected $allowed_bureaus = ['penjaminan_mutu', 'semua'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_obe');
        $this->load->helper(['url', 'html']);
    }

    /**
     * Dashboard Utama Penjaminan Mutu OBE
     */
    public function index()
    {
        $this->monitoring_cpl();
    }

    /**
     * Monitoring CPL: Agregasi ketercapaian CPL per Prodi dan indikator CPL < 70 (Evaluasi)
     */
    public function monitoring_cpl()
    {
        $prodi_id = $this->input->get('prodi_id');
        $prodi_id = !empty($prodi_id) ? (int)$prodi_id : null;

        $cpl_data = $this->M_obe->get_cpl_monitoring_aggregate($prodi_id);
        $rps_data = $this->M_obe->get_rps_audit_data($prodi_id);

        $data = [
            'title'           => 'Monitoring & Audit Mutu OBE - Smart Campus',
            'page_title'      => 'Monitoring & Audit Mutu OBE',
            'page_desc'       => 'Sistem Penjaminan Mutu Internal (SPMI): Evaluasi ketercapaian CPL institusi dan audit kepatuhan RPS dosen.',
            'card_subtitle'   => 'Biro Penjaminan Mutu',
            'active_tab'      => 'monitoring',
            'prodi_id'        => $prodi_id,
            'cpl_monitoring'  => $cpl_data,
            'rps_audit'       => $rps_data,
            'prodi_list'      => $cpl_data['prodi_list'],
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('penjaminan_mutu/obe_monitoring', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Audit RPS: Rekapitulasi kelas yang sudah 100% vs yang belum diatur
     */
    public function audit_rps()
    {
        $prodi_id = $this->input->get('prodi_id');
        $prodi_id = !empty($prodi_id) ? (int)$prodi_id : null;

        $cpl_data = $this->M_obe->get_cpl_monitoring_aggregate($prodi_id);
        $rps_data = $this->M_obe->get_rps_audit_data($prodi_id);

        $data = [
            'title'           => 'Audit Kepatuhan RPS Dosen - Smart Campus',
            'page_title'      => 'Audit Kepatuhan RPS & Asesmen',
            'page_desc'       => 'Rekapitulasi kepatuhan RPS dosen pengampu perkuliahan terhadap standar bobot asesmen OBE 100%.',
            'card_subtitle'   => 'Biro Penjaminan Mutu',
            'active_tab'      => 'audit',
            'prodi_id'        => $prodi_id,
            'cpl_monitoring'  => $cpl_data,
            'rps_audit'       => $rps_data,
            'prodi_list'      => $rps_data['prodi_list'],
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('penjaminan_mutu/obe_monitoring', $data);
        $this->load->view('templates/footer', $data);
    }
}
