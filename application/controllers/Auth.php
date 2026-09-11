<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    /**
     * Halaman Form Login
     */
    public function index()
    {
        // Jika sudah login, langsung ke beranda
        if ($this->session->userdata('is_logged_in')) {
            redirect('beranda');
        }

        $data['title'] = 'Login - Smart Campus';
        $this->load->view('auth/login', $data);
    }

    /**
     * Proses Validasi & Autentikasi Login
     */
    public function process()
    {
        // Jika sudah login, langsung ke beranda
        if ($this->session->userdata('is_logged_in')) {
            redirect('beranda');
        }

        // Aturan validasi
        $this->form_validation->set_rules('nim', 'NIM / Email', 'trim|required', [
            'required' => 'NIM atau Email wajib diisi.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', [
            'required' => 'Password wajib diisi.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Login - Smart Campus';
            $this->load->view('auth/login', $data);
            return;
        }

        $identifier = $this->input->post('nim', true);
        $password   = $this->input->post('password', true);

        // Cari user aktif (deleted_at IS NULL)
        $user = $this->M_auth->get_active_user($identifier);

        if ($user) {
            // Cek password hash
            if (password_verify($password, $user->password)) {
                // Buat data session
                $session_data = [
                    'id'           => $user->id,
                    'nim'          => $user->nim,
                    'nama_lengkap' => $user->nama_lengkap,
                    'email'        => $user->email,
                    'role'         => (int)$user->role, // 1: Super Admin, 2: Admin, 3: User
                    'role_name'    => M_auth::role_label($user->role),
                    'foto'         => !empty($user->foto) ? $user->foto : 'avatar-4.png',
                    'is_logged_in' => TRUE
                ];

                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Selamat datang kembali, ' . $user->nama_lengkap . '!');
                
                redirect('beranda');
            } else {
                $this->session->set_flashdata('error', 'Password yang Anda masukkan salah.');
                redirect('auth');
            }
        } else {
            // Cek apakah akun terdaftar namun berstatus soft deleted
            $deleted_user = $this->M_auth->is_soft_deleted($identifier);
            if ($deleted_user) {
                $this->session->set_flashdata('error', 'Akun dengan NIM ini telah dinonaktifkan (soft deleted). Hubungi administrator.');
            } else {
                $this->session->set_flashdata('error', 'NIM atau Email tidak terdaftar dalam sistem Smart Campus.');
            }
            redirect('auth');
        }
    }

    /**
     * Proses Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('info', 'Anda telah berhasil logout.');
        redirect('auth');
    }
}
