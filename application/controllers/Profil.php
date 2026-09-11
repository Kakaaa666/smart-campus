<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);
        $this->load->model('M_auth');

        // Wajib login untuk mengakses profil
        if (!$this->session->userdata('is_logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses profil.');
            redirect('auth');
        }
    }

    /**
     * Halaman Utama Profil Pengguna
     */
    public function index()
    {
        $userId = $this->session->userdata('id');
        $user   = $this->M_auth->get_user_by_id($userId);

        if (!$user) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
            redirect('auth');
        }

        $data['title'] = 'Profil Pengguna - Smart Campus';
        $data['user']  = $user;
        $data['role_name'] = M_auth::role_label($user->role);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('profil/profil', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Proses Ubah Foto Profil
     */
    public function update_foto()
    {
        $userId = $this->session->userdata('id');
        $user   = $this->M_auth->get_user_by_id($userId);

        if (!$user) {
            redirect('auth');
        }

        // Konfigurasi Upload
        $config['upload_path']   = './assets/images/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 3072; // 3MB
        $config['file_name']     = 'avatar_' . $user->nim . '_' . time();
        $config['overwrite']     = false;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto_profil')) {
            $error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error_foto', 'Gagal mengunggah foto: ' . $error);
        } else {
            $uploadData = $this->upload->data();
            $newFileName = $uploadData['file_name'];

            // Update di database
            $this->M_auth->update_foto($userId, $newFileName);

            // Perbarui data foto di session agar navbar langsung berganti
            $this->session->set_userdata('foto', $newFileName);

            $this->session->set_flashdata('success_foto', 'Foto profil berhasil diperbarui!');
        }

        redirect('profil');
    }

    /**
     * Proses Ubah Password
     */
    public function update_password()
    {
        $userId = $this->session->userdata('id');
        $user   = $this->M_auth->get_user_by_id($userId);

        if (!$user) {
            redirect('auth');
        }

        // Aturan validasi (Hanya password baru dan konfirmasi password)
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'trim|required|min_length[6]', [
            'required'   => 'Password baru wajib diisi.',
            'min_length' => 'Password baru minimal harus 6 karakter.'
        ]);
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password Baru', 'trim|required|matches[password_baru]', [
            'required' => 'Konfirmasi password baru wajib diisi.',
            'matches'  => 'Konfirmasi password tidak cocok dengan password baru.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_password', validation_errors('<p class="mb-1">', '</p>'));
            redirect('profil');
        }

        $passwordBaru = $this->input->post('password_baru', true);

        // Hash password baru & simpan ke database
        $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $this->M_auth->update_password($userId, $hashedPassword);

        $this->session->set_flashdata('success_password', 'Password Anda berhasil diperbarui dengan aman!');
        redirect('profil');
    }
}
