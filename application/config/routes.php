<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'beranda';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['login'] = 'auth';
$route['logout'] = 'auth/logout';
$route['pengaturan/profil'] = 'profil';
$route['pengaturan/ubah-password'] = 'profil';
$route['dosen'] = 'dosen';
$route['dosen/(:any)'] = 'dosen/$1';

// Alias URL lama ke controller yang sudah dipisah berdasarkan role.
$route['keuangan'] = 'shared/keuangan_core';
$route['keuangan/(:any)'] = 'shared/keuangan_core/$1';
$route['admin/keuangan'] = 'admin/keuangan';
$route['admin/keuangan/(:any)'] = 'admin/keuangan/$1';
$route['mahasiswa/keuangan'] = 'mahasiswa/keuangan';
$route['mahasiswa/keuangan/(:any)'] = 'mahasiswa/keuangan/$1';
$route['superadmin/keuangan'] = 'superadmin/keuangan';
$route['superadmin/keuangan/(:any)'] = 'superadmin/keuangan/$1';
$route['admin/akademik'] = 'admin/akademik';
$route['admin/akademik/(:any)'] = 'admin/akademik/$1';
$route['admin/kemahasiswaan'] = 'admin/kemahasiswaan';
$route['admin/kemahasiswaan/(:any)'] = 'admin/kemahasiswaan/$1';
$route['admin/perpustakaan'] = 'admin/perpustakaan';
$route['admin/perpustakaan/(:any)'] = 'admin/perpustakaan/$1';
$route['admin/pengguna'] = 'admin/pengguna';
$route['admin/pengguna/(:any)'] = 'admin/pengguna/$1';
$route['admin/sarana-prasarana'] = 'admin/sarana_prasarana';
$route['admin/sarana-prasarana/(:any)'] = 'admin/sarana_prasarana/$1';
$route['admin/penjaminan-mutu'] = 'admin/penjaminan_mutu';
$route['admin/penjaminan-mutu/(:any)'] = 'admin/penjaminan_mutu/$1';
$route['superadmin/akademik'] = 'superadmin/akademik';
$route['superadmin/akademik/(:any)'] = 'superadmin/akademik/$1';
$route['superadmin/kemahasiswaan'] = 'superadmin/kemahasiswaan';
$route['superadmin/kemahasiswaan/(:any)'] = 'superadmin/kemahasiswaan/$1';
$route['superadmin/perpustakaan'] = 'superadmin/perpustakaan';
$route['superadmin/perpustakaan/(:any)'] = 'superadmin/perpustakaan/$1';
$route['superadmin/pengguna'] = 'superadmin/pengguna';
$route['superadmin/pengguna/(:any)'] = 'superadmin/pengguna/$1';
$route['superadmin/sarana-prasarana'] = 'superadmin/sarana_prasarana';
$route['superadmin/penjaminan-mutu'] = 'superadmin/penjaminan_mutu';
$route['akademik'] = 'mahasiswa/akademik';
$route['akademik/(:any)'] = 'mahasiswa/akademik/$1';
$route['kehadiran'] = 'mahasiswa/kehadiran';
$route['kuisioner'] = 'mahasiswa/kuisioner';
$route['merdeka_belajar'] = 'mahasiswa/merdeka_belajar';
$route['merdeka-belajar'] = 'mahasiswa/merdeka_belajar';
$route['ringkasan'] = 'mahasiswa/ringkasan';
$route['skpi'] = 'mahasiswa/skpi';
$route['mahasiswa/perpustakaan'] = 'mahasiswa/perpustakaan';
$route['mahasiswa/perpustakaan/(:any)'] = 'mahasiswa/perpustakaan/$1';
$route['verifikasi'] = 'mahasiswa/verifikasi';
$route['mahasiswa/verifikasi'] = 'mahasiswa/verifikasi';
$route['mahasiswa/pengajuan'] = 'mahasiswa/pengajuan';
$route['kemahasiswaan'] = 'superadmin/kemahasiswaan';
$route['perpustakaan'] = 'superadmin/perpustakaan';
