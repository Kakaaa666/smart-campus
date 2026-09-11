<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Cek apakah user sudah login, jika belum redirect ke halaman login
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            redirect('auth');
        }
    }

    public function index() {
        $data['title'] = 'Beranda - Smart Campus';
        $data['user'] = [
            'id'           => $this->session->userdata('id'),
            'nim'          => $this->session->userdata('nim'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'role'         => $this->session->userdata('role'),
            'role_name'    => $this->session->userdata('role_name'),
            'foto'         => $this->session->userdata('foto')
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('beranda/beranda', $data);
        $this->load->view('templates/footer', $data);
    }
}
