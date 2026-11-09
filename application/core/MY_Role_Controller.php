<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Role_Controller extends CI_Controller
{
    protected $allowed_roles = [];
    protected $allowed_bureaus = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'html']);
        $this->load->model('M_auth');
        $this->config->load('role_menus');

        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
            return;
        }

        $role = (int)$this->session->userdata('role');
        if ($role === 0 && $this->session->userdata('account_type') === 'mahasiswa') {
            $role = 3;
        }

        if (!in_array($role, $this->allowed_roles, true)) {
            show_error('Anda tidak memiliki hak akses ke modul ini.', 403);
        }

        if ($role === 2 && $this->allowed_bureaus) {
            $biro = strtolower((string)$this->session->userdata('biro')) ?: 'keuangan';
            if (!in_array($biro, $this->allowed_bureaus, true)) {
                show_error('Admin ini tidak memiliki kewenangan untuk biro tersebut.', 403);
            }
        }
    }

    protected function render_module($title, $description, $bureau)
    {
        $data = [
            'title' => $title . ' - Smart Campus',
            'page_title' => $title,
            'page_desc' => $description,
            'card_subtitle' => 'Biro ' . $bureau,
            'module_bureau' => $bureau,
            'role_name' => $this->session->userdata('role_name'),
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/content_page', $data);
        $this->load->view('templates/footer', $data);
    }

    protected function require_student_financial_clearance($page_title)
    {
        $role = $this->session->userdata('role');
        $is_student = $this->session->userdata('account_type') === 'mahasiswa'
            || $role === null
            || (int)$role === 3;

        if (!$is_student) {
            return true;
        }

        $this->load->model('mahasiswa/M_akademik_mahasiswa', 'M_akademik_access');
        $this->load->model('mahasiswa/M_keuangan_mahasiswa', 'M_keuangan_access');
        $period = $this->M_akademik_access->get_active_period();
        $status = $this->M_keuangan_access->cek_status_krs((int)$this->session->userdata('id'), $period);

        if (!empty($status['buka_krs'])) {
            return true;
        }

        $data = [
            'title' => $page_title . ' - Smart Campus',
            'page_title' => $page_title,
            'page_desc' => 'Akses akademik semester aktif dibatasi sampai tagihan wajib UKT/SPP terverifikasi lunas.',
            'card_subtitle' => 'Verifikasi pembayaran semester',
            'breadcrumb_parent' => 'Akademik',
            'status_krs' => $status
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('akademik/akses_terkunci', $data);
        $this->load->view('templates/footer', $data);

        return false;
    }
}
