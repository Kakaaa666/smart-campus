<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengguna extends CI_Model
{
    protected $bureau = 'Rektorat';

    public function module_name()
    {
        return $this->bureau;
    }

    public function get_students_for_short_semester_approval()
    {
        return $this->db->select('id, nim, nama_lengkap, fakultas, prodi, semester, ambil_semester_pendek, status_semester_pendek, semester_pendek_diproses_at')
                        ->from('akun_mahasiswa')
                        ->where('deleted_at IS NULL', null, false)
                        ->order_by('nama_lengkap', 'ASC')
                        ->get()
                        ->result();
    }
}
