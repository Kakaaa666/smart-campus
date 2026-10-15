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

        // Pastikan folder tujuan ada dan memiliki izin tulis
        $upload_path = FCPATH . 'assets/images/';
        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0777, true);
        }
        @chmod($upload_path, 0777);

        // Bersihkan identifier NIM / ID agar nama file aman
        $safe_identifier = !empty($user->nim) ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $user->nim) : 'user_' . $userId;

        // Konfigurasi Upload
        $config['upload_path']      = $upload_path;
        $config['allowed_types']    = 'gif|jpg|jpeg|png|webp|JPG|JPEG|PNG|WEBP';
        $config['file_ext_tolower'] = TRUE;
        $config['max_size']         = 10240; // 10MB
        $config['file_name']        = 'avatar_' . $safe_identifier . '_' . time();
        $config['overwrite']        = FALSE;
        $config['remove_spaces']    = TRUE;

        // Load ulang library upload dengan konfigurasi baru
        $this->load->library('upload');
        $this->upload->initialize($config, TRUE);

        if (!$this->upload->do_upload('foto_profil')) {
            $error = $this->upload->display_errors('', '');
            if (empty($error)) {
                $error = 'Berkas tidak dapat diproses. Pastikan format berkas berupa gambar (JPG, JPEG, PNG, WEBP) dan ukuran maksimal 10MB.';
            }
            $this->session->set_flashdata('error_foto', 'Gagal mengunggah foto: ' . $error);
        } else {
            $uploadData = $this->upload->data();
            $newFileName = $uploadData['file_name'];

            // Optimasi / Resize foto jika ukurannya terlalu besar (maksimal 800x800 px)
            if (!empty($uploadData['is_image']) && ($uploadData['image_width'] > 800 || $uploadData['image_height'] > 800)) {
                $resize_config = [
                    'image_library'  => 'gd2',
                    'source_image'   => $uploadData['full_path'],
                    'maintain_ratio' => TRUE,
                    'width'          => 800,
                    'height'         => 800,
                    'quality'        => '90%',
                ];
                $this->load->library('image_lib', $resize_config);
                @$this->image_lib->resize();
                $this->image_lib->clear();
            }

            // Hapus file avatar lama jika bukan avatar bawaan
            $default_avatars = ['avatar-1.jpg', 'avatar-2.jpg', 'avatar-3.jpg', 'avatar-4.png', 'avatar-4.jpg', 'avatar-5.jpg', 'user-profile.png'];
            if (!empty($user->foto) && !in_array($user->foto, $default_avatars, true)) {
                $old_file = $upload_path . $user->foto;
                if (file_exists($old_file)) {
                    @unlink($old_file);
                }
            }

            // Update di database
            $this->M_auth->update_foto($userId, $newFileName);

            // Perbarui data foto di session agar topbar & navbar langsung berganti
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
