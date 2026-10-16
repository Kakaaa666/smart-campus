# Laporan Teknis Modul Akademik

## Status

Audit source code, fondasi skema, katalog dasar, pengelolaan penugasan dosen ke program studi, serta penetapan kurikulum mahasiswa dengan histori sudah dikerjakan. Pratinjau rekonsiliasi penawaran lama hanya menampilkan kandidat kode persis dan tidak mengubah data. Profil dosen akademik terpisah, pemetaan manual penawaran yang telah ditinjau, dan mesin validasi dinamis belum selesai. Jangan menganggap modul akademik lengkap atau menjalankan aturan baru sebelum data serta kebijakan kampus diverifikasi.

## Hasil audit

- Framework: CodeIgniter 3.1.13.
- Runtime yang terdeteksi: PHP CLI 7.4.33; konfigurasi aplikasi memakai driver `mysqli`; database lokal terverifikasi MariaDB 10.4.32.
- Migrasi CI tersedia tetapi dinonaktifkan (`application/config/migration.php`); fondasi akademik baru dikelola dengan migrasi SQL bernomor dan dijalankan manual setelah backup.
- Autentikasi memisahkan `akun_mahasiswa` dengan `akun_biro`. Mahasiswa tidak mempunyai kolom `role`; role staf/dosen berada pada `akun_biro`.
- Otorisasi memakai session dan `MY_Role_Controller`. Role yang ditemukan dalam alur terkait: superadmin (1), admin biro (2), mahasiswa (3), dosen (4). Belum ada role Kaprodi yang teridentifikasi.
- `M_akademik` membuat tabel operasional saat model dimuat dan juga memiliki seeder otomatis. Pola ini berisiko untuk pengelolaan skema/data produksi dan perlu dipisahkan dalam tahap lanjutan.
- UI yang tersedia mencakup operasional penawaran, dosen wali, KRS mahasiswa, persetujuan KRS, dan KHS.
- Pemeriksaan menemukan data prodi disimpan sebagai teks pada mahasiswa dan penawaran. `penawaran_matakuliah` sekaligus memuat identitas mata kuliah, semester rekomendasi, periode, kelas, jadwal, dosen, kapasitas, dan prasyarat teks.
- Periode aktif dihitung dari bulan kalender pada `M_akademik`; batas SKS minimum/maksimum menggunakan konstanta model. Kebijakan kampus yang mengesahkan nilai-nilai tersebut belum ditemukan.
- Tabel KRS/KHS yang ditemukan/dibuat aplikasi adalah `krs`, `krs_detail`, dan `khs_nilai`. Tabel operasional yang dibuat model belum mendefinisikan foreign key relasional untuk seluruh relasi tersebut.
- Tidak ditemukan konfigurasi Composer/PHPUnit atau suite tes otomatis pada root proyek. Database dibaca dan migrasi fondasi dijalankan setelah persetujuan eksplisit, sesudah backup penuh.

## Peta data saat ini

| Entitas | Sumber saat ini | Catatan |
|---|---|---|
| Mahasiswa | `akun_mahasiswa` | Identitas dan atribut akademik tercampur; prodi masih berupa teks. |
| Dosen/staf | `akun_biro` | Dosen dikenali dengan role 4; akun login dan profil dosen menyatu. |
| Dosen wali | `dosen_wali` | Menyimpan mahasiswa, dosen, dan pelaku penetapan. |
| Penawaran/kursus/kelas | `penawaran_matakuliah` | Belum dipisahkan menjadi master mata kuliah, kurikulum, penawaran, dan kelas. |
| KRS | `krs`, `krs_detail` | Status yang digunakan saat ini: `DRAFT`, `MENUNGGU`, `DISETUJUI`, `DITOLAK`. |
| Nilai/KHS | `khs_nilai` | Nilai disimpan dengan kode/nama mata kuliah, tanpa relasi master mata kuliah yang teridentifikasi. |
| Aturan akademik | Kode dan kolom penawaran | Belum ditemukan penyimpanan terpusat dengan versi dan tanggal berlaku. |

## Fondasi skema yang ditambahkan

Skema final menggunakan nama tabel dan kolom berbahasa Indonesia. [Migrasi fondasi 001](../database/migrations/20261016_001_academic_foundation.sql) membuat tabel fondasi, lalu [migrasi penamaan 002](../database/migrations/20261016_002_nama_skema_akademik_indonesia.sql) mengganti nama tabel dan kolom tanpa menghapus data:

- `program_studi`, `penugasan_dosen_prodi`
- `kurikulum`, `mata_kuliah`, `kurikulum_mata_kuliah`, `prasyarat_mata_kuliah`
- `kurikulum_mahasiswa`, `pemetaan_penawaran_mata_kuliah`
- `aturan_akademik`, `histori_aturan_akademik`, `histori_katalog_akademik`
- `histori_validasi_krs`, `snapshot_detail_krs`

ERD fondasi:

```text
program_studi 1--* kurikulum 1--* kurikulum_mata_kuliah *--1 mata_kuliah
      |                         |
      * penugasan_dosen_prodi    *--* prasyarat_mata_kuliah
      |                         |
      + (penugasan akun dosen)   +-- kurikulum_mahasiswa -- akun_mahasiswa
                                |
penawaran_matakuliah -- pemetaan_penawaran_mata_kuliah -- mata_kuliah
krs -- krs_detail -- snapshot_detail_krs
krs -- histori_validasi_krs -- aturan_akademik -- histori_aturan_akademik
```

Relasi bertipe polimorfik pada `aturan_akademik.jenis_cakupan/cakupan_id` sengaja belum menggunakan foreign key; aturan validasi harus memastikan scope yang dimaksud ada dan sah.

## Implementasi aplikasi tahap katalog

- [Katalog_akademik.php](../application/controllers/admin/Katalog_akademik.php) menyediakan halaman serta operasi tulis yang dibatasi ke superadmin dan admin biro akademik.
- [M_katalog_akademik_admin.php](../application/models/admin/M_katalog_akademik_admin.php) membaca dan menyimpan katalog melalui Query Builder, transaksi, dan histori perubahan.
- [katalog.php](../application/views/akademik/katalog.php) menyediakan pengelolaan program studi, master mata kuliah, kurikulum, pemetaan mata kuliah, penugasan dosen, dan histori penetapan kurikulum mahasiswa; daftar dibatasi 50 baris per halaman dan pencarian pilihan dibatasi 20 hasil.
- Route ditambahkan untuk `/admin/katalog-akademik`; navigasi biro akademik dan superadmin ditautkan ke halaman yang sama.
- Data baru berstatus tinjauan/draf, kecuali pemetaan kurikulum yang berstatus aktif ketika dibuat. Admin harus memverifikasi data sebelum mengaktifkan katalog/kurikulum.
- Tiap perubahan mensyaratkan alasan dan mencatat snapshot sebelum/sesudah ke `histori_katalog_akademik`. Data tidak dihapus dari halaman ini.
- Penugasan dosen ke program studi hanya menerima akun role dosen aktif dan program studi aktif. Periode yang bertabrakan untuk dosen/program/jenis penugasan yang sama ditolak; penonaktifan mempertahankan record dan menulis histori audit.
- Admin dapat menetapkan kurikulum aktif kepada mahasiswa jika nama prodi lama mahasiswa cocok persis (tidak peka kapitalisasi) dengan nama program di katalog. Penetapan tidak tumpang tindih; penetapan baru setelah assignment tanpa tanggal akhir menutup assignment sebelumnya sehari sebelum tanggal baru, dengan snapshot audit untuk kedua perubahan dalam satu transaksi.
- Panel rekonsiliasi membandingkan kode penawaran lama dan kode master secara persis (tidak peka kapitalisasi). Kecocokan hanya kandidat, bukan tautan; tidak ada backfill/tautan otomatis.

## Menjalankan migrasi

1. Untuk database pengembangan lain, ambil backup dan uji pemulihannya. Database lokal `smartcampus` pada sesi ini sudah dimigrasikan.
2. Pastikan aplikasi pernah menginisialisasi tabel `akun_mahasiswa`, `akun_biro`, `penawaran_matakuliah`, `krs`, dan `krs_detail`. DDL foreign key akan gagal jika tabel prasyarat belum tersedia; jangan abaikan error migrasi.
3. Pilih basis data pengembangan yang benar pada klien MySQL dan jalankan kedua migrasi berurutan:

   ```text
   mysql -u <user> -p <database> < database/migrations/20261016_001_academic_foundation.sql
   mysql -u <user> -p <database> < database/migrations/20261016_002_nama_skema_akademik_indonesia.sql
   ```

4. Verifikasi tabel baru dan foreign key sebelum mulai mengisi katalog.

Migrasi 001 memakai `CREATE TABLE IF NOT EXISTS`; migrasi 002 memakai `RENAME TABLE` dan `CHANGE COLUMN` untuk mempertahankan tabel serta data. Pada 16 Oktober 2026, fondasi dijalankan pada database MariaDB lokal `smartcampus` dan menghasilkan 13 tabel serta 27 foreign key. Migrasi 002 mengganti nama skema menjadi bahasa Indonesia setelah backup penuh dibuat. Nama database fisik tetap `smartcampus`; yang diterjemahkan adalah nama tabel dan kolom akademik. Struktur final beserta 102 nama kolom dan query aplikasi diverifikasi pada MariaDB 10.4.32. Migrasi produksi tetap memerlukan backup, pengujian pemulihan, dan persetujuan operasional.

## Aturan dan integrasi yang belum ditetapkan

- Kode resmi prodi/mata kuliah, daftar kurikulum, pemetaan angkatan, dan data dosen penugasan belum tersedia untuk backfill. Migrasi sengaja tidak menyalin atau mengarang katalog.
- Rentang IPS/batas SKS, prasyarat dan nilai minimum, semester rekomendasi/pengecualian, kapasitas, tanggal periode KRS, pengulangan, serta konflik aturan harus dikonfirmasi kampus. Tidak ada aturan baru yang diaktifkan otomatis.
- Alur persetujuan lintas prodi/Kaprodi belum dapat diselesaikan tanpa role/permission dan penugasan Kaprodi yang sah.
- CRUD program studi, mata kuliah, kurikulum, dan pemetaan telah tersedia setelah kedua migrasi dijalankan. Form belum menyediakan pengelolaan tanggal berlaku program/kurikulum secara historis lengkap di semua proses perubahan.
- Profil dosen akademik terpisah dan pemetaan manual penawaran lama setelah tinjauan belum diimplementasikan. Prasyarat UI; aturan akademik CRUD/versioning; pencatatan histori saat perubahan KRS; dan integrasi pembacaan skema baru ke KRS juga belum diimplementasikan.
- Database test development sudah menjalankan migrasi, memverifikasi tabel dan foreign key, lalu menguji pembuatan penugasan dosen, deteksi bentrok, penonaktifan, audit, penetapan kurikulum berurutan, penutupan periode terdahulu, serta query rekonsiliasi dalam transaksi DML yang di-rollback. Tidak ada record data uji yang tertinggal; pemetaan penawaran lama masih pratinjau dan tidak mengubah data.

## Tahapan berikutnya

1. Verifikasi database pengembangan, daftar prodi/kode mata kuliah resmi, dan kebijakan akademik yang berlaku.
2. Tinjau kandidat rekonsiliasi kode, lalu bangun pemetaan manual yang disertai histori setelah data master divalidasi.
3. Selesaikan profil dosen akademik terpisah dan verifikasi penetapan kurikulum mahasiswa dengan kasus data nyata yang disetujui.
4. Implementasikan UI versioning aturan dan service validasi KRS yang membaca aturan aktif, menjalankan validasi server-side, menyimpan snapshot, dan mencatat histori.
5. Integrasikan persetujuan lintas prodi sesuai matriks kewenangan kampus; lanjutkan impor/ekspor, laporan, dan regression/security test.
