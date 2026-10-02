<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    protected $table = 'akun';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_biro_column();
    }

    private function ensure_biro_column()
    {
        if (!$this->db->table_exists($this->table)) {
            return;
        }

        $fields = $this->db->list_fields($this->table);
        if (!in_array('biro', $fields, true)) {
            $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `biro` VARCHAR(50) NOT NULL DEFAULT 'keuangan' AFTER `role`");
        }
    }

    public static function normalize_biro($biro)
    {
        $biro = strtolower(trim((string)$biro));
        $allowed = ['keuangan', 'akademik', 'kemahasiswaan', 'perpustakaan', 'sarana_prasarana', 'penjaminan_mutu'];
        return in_array($biro, $allowed, true) ? $biro : 'keuangan';
    }

    public static function biro_label($biro)
    {
        $labels = [
            'keuangan' => 'Admin Keuangan',
            'akademik' => 'Admin Akademik',
            'kemahasiswaan' => 'Admin Kemahasiswaan',
            'perpustakaan' => 'Admin Perpustakaan',
            'sarana_prasarana' => 'Admin Sarana Prasarana',
            'penjaminan_mutu' => 'Admin Penjaminan Mutu'
        ];

        $biro = self::normalize_biro($biro);
        return $labels[$biro];
    }

    /**
     * Ambil akun aktif berdasarkan NIM atau Email (Soft Delete Check: deleted_at IS NULL)
     */
    public function get_active_user($identifier)
    {
        $this->db->group_start();
        $this->db->where('nim', $identifier);
        $this->db->or_where('email', $identifier);
        $this->db->group_end();
        $this->db->where('deleted_at IS NULL', null, false);
        
        return $this->db->get($this->table)->row();
    }

    /**
     * Cek apakah akun ada tapi dalam kondisi soft deleted
     */
    public function is_soft_deleted($identifier)
    {
        $this->db->group_start();
        $this->db->where('nim', $identifier);
        $this->db->or_where('email', $identifier);
        $this->db->group_end();
        $this->db->where('deleted_at IS NOT NULL', null, false);
        
        return $this->db->get($this->table)->row();
    }

    /**
     * Ambil user aktif berdasarkan ID
     */
    public function get_user_by_id($id)
    {
        return $this->db->get_where($this->table, [
            'id' => $id,
            'deleted_at' => null
        ])->row();
    }

    /**
     * Soft Delete user (isi deleted_at dengan tanggal sekarang)
     */
    public function soft_delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Restore user yang di-soft delete
     */
    public function restore($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, [
            'deleted_at' => null
        ]);
    }

    /**
     * Insert user baru (dengan otomatis hashing password)
     */
    public function insert_user($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $info = password_get_info($data['password']);
            if ($info['algo'] === 0) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['deleted_at'] = null;
        $data['biro'] = ((int)($data['role'] ?? 3) === 2)
            ? self::normalize_biro($data['biro'] ?? 'keuangan')
            : (((int)($data['role'] ?? 3) === 1) ? 'semua' : (((int)($data['role'] ?? 3) === 4) ? 'dosen' : 'mahasiswa'));
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update foto profil user
     */
    public function update_foto($id, $foto_name)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, [
            'foto'       => $foto_name,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update password user
     */
    public function update_password($id, $hashed_password)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, [
            'password'   => $hashed_password,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Helper nama role berdasarkan angka (1: Super Admin, 2: Admin, 3: User)
     */
    public static function role_label($role_id)
    {
        switch ((int)$role_id) {
            case 1:
                return 'Super Admin';
            case 2:
                return 'Admin';
            case 3:
                return 'Mahasiswa';
            case 4:
                return 'Dosen';
            default:
                return 'User';
        }
    }
}
