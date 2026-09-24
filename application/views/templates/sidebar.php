<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$current_role = (int)$this->session->userdata('role');
?>
                    <style>
                        /* Memperbaiki teks menu yang terpotong di sidebar */
                        .pcoded-navbar .pcoded-inner-navbar li > a > .pcoded-mtext {
                            font-size: 13.5px !important;
                            letter-spacing: -0.2px !important;
                            white-space: normal !important;
                            line-height: 1.4 !important;
                        }
                    </style>
                    <nav class="pcoded-navbar">
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">
                            <ul class="pcoded-item pcoded-left-item">

                                <!-- Beranda: tampil untuk semua role -->
                                <li class="<?= ($seg1 == '' || $seg1 == 'beranda') ? 'active' : '' ?>">
                                    <a href="<?= base_url('beranda') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-home icon-blue"></i></span>
                                        <span class="pcoded-mtext">Beranda</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <?php if ($current_role === 1): ?>
                                <!-- Menu utama Super Admin: satu item untuk setiap biro -->
                                <li class="<?= ($seg1 == 'beranda') ? 'active' : '' ?>">
                                    <a href="<?= base_url('beranda') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-shield"></i></span>
                                        <span class="pcoded-mtext">Pusat Kendali</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'keuangan') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="fa fa-money"></i></span><span class="pcoded-mtext">Biro Keuangan</span><span class="pcoded-mcaret"></span></a>
                                    <ul class="pcoded-submenu">
                                        <li><a href="<?= base_url('beranda') ?>"><span class="pcoded-mtext">Kembali ke Pusat Kendali</span></a></li>
                                        <li><a href="<?= base_url('keuangan/admin') ?>"><span class="pcoded-mtext">Dashboard Keuangan</span></a></li>
                                        <li><a href="<?= base_url('keuangan/verifikasi') ?>"><span class="pcoded-mtext">Verifikasi Pembayaran</span></a></li>
                                        <li><a href="<?= base_url('keuangan/kontrol_ta') ?>"><span class="pcoded-mtext">Kontrol Akses Tagihan</span></a></li>
                                        <li><a href="<?= base_url('keuangan/laporan') ?>"><span class="pcoded-mtext">Laporan Rektorat</span></a></li>
                                    </ul>
                                </li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'akademik') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="fa fa-graduation-cap"></i></span><span class="pcoded-mtext">Biro Akademik</span><span class="pcoded-mcaret"></span></a>
                                    <ul class="pcoded-submenu">
                                        <li><a href="<?= base_url('beranda') ?>"><span class="pcoded-mtext">Kembali ke Pusat Kendali</span></a></li>
                                        <li><a href="<?= base_url('akademik/jadwal') ?>"><span class="pcoded-mtext">Jadwal</span></a></li>
                                        <li><a href="<?= base_url('akademik/nilai') ?>"><span class="pcoded-mtext">Nilai</span></a></li>
                                        <li><a href="<?= base_url('akademik/khs') ?>"><span class="pcoded-mtext">KHS</span></a></li>
                                        <li><a href="<?= base_url('akademik/transkrip') ?>"><span class="pcoded-mtext">Transkrip</span></a></li>
                                        <li><a href="<?= base_url('akademik/kurikulum') ?>"><span class="pcoded-mtext">Kurikulum</span></a></li>
                                        <li><a href="<?= base_url('akademik/matakuliah') ?>"><span class="pcoded-mtext">Mata Kuliah</span></a></li>
                                        <li><a href="<?= base_url('akademik/kalender') ?>"><span class="pcoded-mtext">Kalender Akademik</span></a></li>
                                    </ul>
                                </li>
                                <li class="<?= ($seg1 == 'perpustakaan') ? 'active' : '' ?>"><a href="<?= base_url('perpustakaan') ?>" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="fa fa-book"></i></span><span class="pcoded-mtext">Biro Perpustakaan</span><span class="pcoded-mcaret"></span></a></li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'kemahasiswaan') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="fa fa-users"></i></span><span class="pcoded-mtext">Biro Kemahasiswaan</span><span class="pcoded-mcaret"></span></a>
                                    <ul class="pcoded-submenu">
                                        <li><a href="<?= base_url('beranda') ?>"><span class="pcoded-mtext">Kembali ke Pusat Kendali</span></a></li>
                                        <li><a href="<?= base_url('kemahasiswaan') ?>"><span class="pcoded-mtext">Ringkasan Biro</span></a></li>
                                        <li><a href="<?= base_url('kuisioner') ?>"><span class="pcoded-mtext">Kuisioner</span></a></li>
                                        <li><a href="<?= base_url('skpi') ?>"><span class="pcoded-mtext">SKPI</span></a></li>
                                        <li><a href="<?= base_url('merdeka_belajar') ?>"><span class="pcoded-mtext">Merdeka Belajar</span></a></li>
                                    </ul>
                                </li>
                                <li class="<?= ($seg1 == 'keuangan' && $seg2 == '') ? 'active' : '' ?>"><a href="<?= base_url('keuangan') ?>" class="waves-effect waves-dark"><span class="pcoded-micon"><i class="fa fa-user"></i></span><span class="pcoded-mtext">Mode Mahasiswa</span><span class="pcoded-mcaret"></span></a></li>
                                <?php endif; ?>

                                <?php if ($current_role === 2): ?>
                                <!-- =============================================
                                     MENU KHUSUS ADMIN KEUANGAN (role 2)
                                ============================================= -->
                                <?php
                                    $ci =& get_instance();
                                    $ci->load->model('M_keuangan');
                                    $pending_verif_count = $ci->M_keuangan->count_pembayaran_by_status('PENDING');
                                ?>

                                <!-- Dashboard Admin Keuangan -->
                                <li class="<?= ($seg1 == 'keuangan' && ($seg2 == 'admin' || $seg2 == 'dashboard' || $seg2 == '')) ? 'active' : '' ?>">
                                    <a href="<?= base_url('keuangan/admin') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-tachometer"></i></span>
                                        <span class="pcoded-mtext">Dashboard Keuangan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <!-- Verifikasi Pembayaran (Menu Tersendiri) -->
                                <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'verifikasi') ? 'active' : '' ?>">
                                    <a href="<?= base_url('keuangan/verifikasi') ?>" class="waves-effect waves-dark" style="position: relative;">
                                        <span class="pcoded-micon"><i class="fa fa-check-square-o"></i></span>
                                        <span class="pcoded-mtext">Verifikasi Pembayaran</span>
                                        <?php if ($pending_verif_count > 0): ?>
                                            <span class="badge badge-warning text-dark ml-2" style="font-size:11px;padding:3px 8px;border-radius:12px;font-weight:700;"><?= $pending_verif_count ?></span>
                                        <?php endif; ?>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <!-- Kontrol Akses Tagihan (Menu Tersendiri) -->
                                <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'kontrol_ta') ? 'active' : '' ?>">
                                    <a href="<?= base_url('keuangan/kontrol_ta') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-graduation-cap"></i></span>
                                        <span class="pcoded-mtext">Kontrol Akses Tagihan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <!-- Laporan ke Rektorat -->
                                <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'laporan') ? 'active' : '' ?>">
                                    <a href="<?= base_url('keuangan/laporan') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-bar-chart"></i></span>
                                        <span class="pcoded-mtext">Laporan ke Rektorat</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <!-- Pengaturan (Profil) -->
                                <li class="pcoded-hasmenu <?= ($seg1 == 'pengaturan' || $seg1 == 'profil') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-cogs"></i></span>
                                        <span class="pcoded-mtext">Pengaturan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="<?= ($seg1 == 'profil' || ($seg1 == 'pengaturan' && $seg2 == 'profil')) ? 'active' : '' ?>">
                                            <a href="<?= base_url('profil') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Profil</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'pengaturan' && $seg2 == 'ubah-password') ? 'active' : '' ?>">
                                            <a href="<?= base_url('profil#card-password') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Ubah Password</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                  <?php elseif ($current_role === 3): ?>
                                <!-- =============================================
                                      MENU KHUSUS MAHASISWA (role 3)
                                ============================================= -->

                                <li class="<?= ($seg1 == 'ringkasan') ? 'active' : '' ?>">
                                    <a href="<?= base_url('ringkasan') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-line-chart"></i></span>
                                        <span class="pcoded-mtext">Ringkasan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="<?= ($seg1 == 'kuisioner') ? 'active' : '' ?>">
                                    <a href="<?= base_url('kuisioner') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon">
                                            <svg viewBox="0 0 512 512" width="18" height="18" fill="currentColor">
                                                <path d="M504.3 273.6L378.8 32c-9.9-17.1-31.8-23-48.9-13.1L217.3 83.5l140.7 243.6 133.2-76.9c9.4-5.4 15.6-15.2 13.1-26.6zM294.1 320L153.4 76.4 32.6 146.1c-17.1 9.9-23 31.8-13.1 48.9l125.5 217.4c6.2 10.7 17.6 17.3 29.9 17.3h33.8v-67.7c0-23.2 18.8-42 42-42h43.4zm-14.4 72c0-13.3-10.7-24-24-24s-24 10.7-24 24 10.7 24 24 24 24-10.7 24-24zm196.3 34H336v-34h140c11 0 20-9 20-20v-30c0-11-9-20-20-20H320c-11 0-20 9-20 20v118c0 25.4 20.6 46 46 46h130c11 0 20-9 20-20v-40c0-11-9-20-20-20z"/>
                                            </svg>
                                        </span>
                                        <span class="pcoded-mtext">Kuisioner</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <!-- Menu Keuangan -->
                                <?php if ($current_role === 1): ?>
                                <?php
                                    $ci =& get_instance();
                                    $ci->load->model('M_keuangan');
                                    $pending_verif_count = $ci->M_keuangan->count_pembayaran_by_status('PENDING');
                                ?>
                                <!-- Super Admin punya submenu keuangan lengkap -->
                                <li class="pcoded-hasmenu <?= ($seg1 == 'keuangan') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-money"></i></span>
                                        <span class="pcoded-mtext">Keuangan</span>
                                        <?php if ($pending_verif_count > 0): ?>
                                            <span class="badge badge-warning text-dark ml-2" style="font-size:10px;padding:2px 6px;border-radius:10px;font-weight:700;"><?= $pending_verif_count ?></span>
                                        <?php endif; ?>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="<?= ($seg1 == 'keuangan' && $seg2 == '') ? 'active' : '' ?>">
                                            <a href="<?= base_url('keuangan') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Info Mahasiswa</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'keuangan' && ($seg2 == 'admin' || $seg2 == 'dashboard')) ? 'active' : '' ?>">
                                            <a href="<?= base_url('keuangan/admin') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Dashboard Keuangan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'verifikasi') ? 'active' : '' ?>">
                                            <a href="<?= base_url('keuangan/verifikasi') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Verifikasi Pembayaran</span>
                                                <?php if ($pending_verif_count > 0): ?>
                                                    <span class="badge badge-warning text-dark ml-1" style="font-size:10px;padding:2px 6px;border-radius:10px;font-weight:700;"><?= $pending_verif_count ?></span>
                                                <?php endif; ?>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'kontrol_ta') ? 'active' : '' ?>">
                                            <a href="<?= base_url('keuangan/kontrol_ta') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Kontrol Akses Tagihan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'keuangan' && $seg2 == 'laporan') ? 'active' : '' ?>">
                                            <a href="<?= base_url('keuangan/laporan') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan ke Rektorat</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <?php else: ?>
                                <!-- Mahasiswa: link langsung ke halaman keuangan -->
                                <li class="<?= ($seg1 == 'keuangan') ? 'active' : '' ?>">
                                    <a href="<?= base_url('keuangan') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-money"></i></span>
                                        <span class="pcoded-mtext">Keuangan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <li class="<?= ($seg1 == 'kehadiran') ? 'active' : '' ?>">
                                    <a href="<?= base_url('kehadiran') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-bell"></i></span>
                                        <span class="pcoded-mtext">Kehadiran Kuliah</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'akademik') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-id-badge"></i></span>
                                        <span class="pcoded-mtext">Akademik</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'jadwal') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/jadwal') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Jadwal</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'nilai') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/nilai') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Nilai</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'khs') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/khs') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">KHS</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'transkrip') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/transkrip') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Transkrip</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'kurikulum') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/kurikulum') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Kurikulum</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'matakuliah') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/matakuliah') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Matakuliah</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'akademik' && $seg2 == 'kalender') ? 'active' : '' ?>">
                                            <a href="<?= base_url('akademik/kalender') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Kalender</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'perwalian') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-th-large"></i></span>
                                        <span class="pcoded-mtext">Perwalian</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="<?= ($seg1 == 'perwalian' && $seg2 == 'ambil-matakuliah') ? 'active' : '' ?>">
                                            <a href="<?= base_url('perwalian/ambil-matakuliah') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Ambil Matakuliah</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'perwalian' && $seg2 == 'frs') ? 'active' : '' ?>">
                                            <a href="<?= base_url('perwalian/frs') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">FRS</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?= ($seg1 == 'merdeka-belajar') ? 'active' : '' ?>">
                                    <a href="<?= base_url('merdeka-belajar') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-plane"></i></span>
                                        <span class="pcoded-mtext">Merdeka Belajar</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="<?= ($seg1 == 'skpi') ? 'active' : '' ?>">
                                    <a href="<?= base_url('skpi') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i></span>
                                        <span class="pcoded-mtext">Pengajuan SKPI</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="<?= ($seg1 == 'verifikasi') ? 'active' : '' ?>">
                                    <a href="<?= base_url('verifikasi') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-check-square"></i></span>
                                        <span class="pcoded-mtext">Verifikasi Ijazah</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="pcoded-hasmenu <?= ($seg1 == 'pengaturan' || $seg1 == 'profil') ? 'pcoded-trigger active' : '' ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-cogs"></i></span>
                                        <span class="pcoded-mtext">Pengaturan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="<?= ($seg1 == 'profil' || ($seg1 == 'pengaturan' && $seg2 == 'profil')) ? 'active' : '' ?>">
                                            <a href="<?= base_url('profil') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Profil</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="<?= ($seg1 == 'pengaturan' && $seg2 == 'ubah-password') ? 'active' : '' ?>">
                                            <a href="<?= base_url('profil#card-password') ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Ubah Password</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <?php endif; // end else (non-admin-keuangan) ?>

                            </ul>
                        </div>
                    </nav>
