# 🎓 Smart Campus - Single Sign-On (SSO) Portal

**Smart Campus Single Sign-On (SSO)** adalah platform portal akademik terpadu yang berfungsi sebagai gerbang autentikasi terpusat bagi seluruh sivitas akademika perguruan tinggi. Dengan arsitektur Single Sign-On, pengguna hanya perlu memiliki satu set kredensial (NIM / Email dan Password) untuk mengakses seluruh ekosistem layanan digital kampus secara aman, efisien, dan terintegrasi.


## 📄 Lisensi & Hak Cipta

Dikembangkan untuk keperluan sistem informasi akademik **Smart Campus**. Dikelola dan didistribusikan di bawah pengawasan tim pengembang Smart Campus.

## Struktur Berdasarkan Role

Controller dan model yang spesifik role berada di folder berikut:

- `application/controllers/admin/` dan `application/models/admin/` untuk Admin (role `2`)
- `application/controllers/mahasiswa/` dan `application/models/mahasiswa/` untuk Mahasiswa (role `3`)
- `application/controllers/superadmin/` dan `application/models/superadmin/` untuk Superadmin (role `1`)
- `application/controllers/shared/` dan `application/models/` untuk logika bersama

Controller keuangan per role mewarisi `shared/Keuangan_core.php`. Model keuangan role tidak saling mewarisi dan tidak menggandakan method; ketiganya mendelegasikan implementasi tunggal ke `models/shared/M_keuangan_shared.php`. URL lama seperti `keuangan/*`, `akademik/*`, dan `perpustakaan` tetap tersedia melalui `application/config/routes.php`.

Peta seluruh menu dan relasi biro berada di `application/config/role_menus.php`. Controller baru memakai `application/core/MY_Role_Controller.php` sebagai guard akses dan renderer bersama. Dengan pola ini, kode operasional dapat dikembangkan di folder role tanpa mencampur hak akses:

- Mahasiswa: layanan akademik, kehadiran, perwalian, keuangan, kemahasiswaan, perpustakaan, verifikasi ijazah, dan profil.
- Admin: hanya melihat dan mengelola satu biro sesuai field `akun.biro`: keuangan, akademik, kemahasiswaan, perpustakaan, sarana prasarana, atau penjaminan mutu.
- Superadmin: pusat kendali seluruh biro dan manajemen pengguna.

Field `akun.biro` dibuat otomatis oleh `M_auth` dengan default `keuangan` untuk menjaga kompatibilitas akun Admin lama. Superadmin tetap dapat melihat seluruh biro, sedangkan akses URL Admin juga diperiksa oleh guard controller, bukan hanya disembunyikan dari sidebar.

## Tema Interface

Shell aplikasi memakai tema role di `assets/css/smart-campus-themes.css`:

- Mahasiswa: navy, biru laut, cyan, dan putih.
- Admin: hijau hutan dengan permukaan putih hangat.
- Superadmin: putih, hitam, dan abu-abu minimal.

Class tema dipasang otomatis oleh `application/views/templates/header.php` berdasarkan session role.