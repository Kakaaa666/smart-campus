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
        $this->config->load('role_menus');

        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
            return;
        }

        $role = (int)$this->session->userdata('role');
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
}
