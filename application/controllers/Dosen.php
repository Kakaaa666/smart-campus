<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Role_Controller.php';

class Dosen extends MY_Role_Controller
{
    protected $allowed_roles = [4];

    public function index()
    {
        $this->render_module('Dashboard Dosen', 'Ringkasan aktivitas pengajaran dan akademik dosen.', 'Akademik');
    }

    public function isi_nilai()
    {
        $this->render_module('Isi Nilai', 'Kelola nilai mahasiswa pada mata kuliah yang diampu.', 'Akademik');
    }

    public function revisi_nilai()
    {
        $this->render_module('Revisi Nilai', 'Ajukan dan pantau revisi nilai mahasiswa.', 'Akademik');
    }

    public function mahasiswa_bimbingan()
    {
        $this->render_module('Mahasiswa Bimbingan', 'Lihat daftar mahasiswa bimbingan akademik.', 'Akademik');
    }

    public function akademik()
    {
        $this->render_module('Akademik', 'Akses informasi akademik dosen.', 'Akademik');
    }

    public function perkuliahan()
    {
        $this->render_module('Perkuliahan', 'Lihat jadwal dan aktivitas perkuliahan.', 'Akademik');
    }

    public function profil()
    {
        redirect('profil');
    }
}
