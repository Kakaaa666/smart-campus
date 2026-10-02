            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <!-- Logo dan Tulisan Smart Campus di Tengah Header -->
                    <div class="header-center-brand">
                        <a href="<?= base_url('beranda') ?>" class="header-brand-link">
                            <i class="bi bi-bank2 brand-icon"></i>
                            <span class="brand-text">SMART CAMPUS</span>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <div class="sidebar_toggle">
                                    <a href="javascript:void(0)" id="mobile-collapse" class="header-icon-btn waves-effect waves-light" title="Buka/Tutup Menu">
                                        <i class="ti-menu"></i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="header-icon-btn waves-effect waves-light" title="Mode Layar Penuh">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="header-notification nav-item-bell">
                                <a href="#!" class="bell-btn waves-effect waves-light" title="Notifikasi">
                                    <i class="ti-bell"></i>
                                    <span class="badge bg-c-red"></span>
                                </a>
                                <ul class="show-notification">
                                    <li>
                                        <h6>Pemberitahuan</h6>
                                        <label class="label label-danger">Baru</label>
                                    </li>
                                    <li class="waves-effect waves-light">
                                        <div class="media">
                                            <img class="d-flex align-self-center img-radius" src="<?= base_url('assets/images/avatar-2.jpg') ?>" alt="Avatar">
                                            <div class="media-body">
                                                <h5 class="notification-user">Akademik</h5>
                                                <p class="notification-msg">Jadwal semester ganjil telah diterbitkan.</p>
                                                <span class="notification-time">30 menit yang lalu</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <?php
                                $current_role_id = (int)$this->session->userdata('role');
                                $userName = $this->session->userdata('nama_lengkap') ? $this->session->userdata('nama_lengkap') : ($current_role_id === 3 ? 'Muhammad Eka' : 'Admin Smart Campus');
                                $userNim  = $this->session->userdata('nim');
                                $userRole = $this->session->userdata('role_name');
                                $userBiro = $this->session->userdata('biro');
                                $userFoto = $this->session->userdata('foto') ? $this->session->userdata('foto') : 'avatar-4.png';

                                if ($current_role_id === 3) {
                                    $subTitle = $userNim ? 'NIM: ' . $userNim : 'Mahasiswa Aktif';
                                    $roleBadgeName = 'Mahasiswa';
                                } elseif ($current_role_id === 2) {
                                    $subTitle = $userRole ? $userRole : ($userBiro ? 'Admin ' . ucfirst($userBiro) : 'Admin Biro');
                                    $roleBadgeName = $subTitle;
                                } elseif ($current_role_id === 1) {
                                    $subTitle = 'Super Administrator';
                                    $roleBadgeName = 'Super Admin';
                                } elseif ($current_role_id === 4) {
                                    $subTitle = 'Dosen Pengampu';
                                    $roleBadgeName = 'Dosen';
                                } else {
                                    $subTitle = $userRole ? $userRole : 'Pengguna';
                                    $roleBadgeName = $subTitle;
                                }
                            ?>
                            <li class="user-profile header-notification">
                                <a href="#!" class="user-profile-badge waves-effect waves-light">
                                    <div class="user-avatar-wrapper">
                                        <img src="<?= base_url('assets/images/' . $userFoto) ?>" class="user-avatar-img" alt="Foto Profil">
                                        <span class="user-status-dot"></span>
                                    </div>
                                    <div class="user-info-wrapper">
                                        <span class="user-name" title="<?= htmlspecialchars($userName) ?>"><?= htmlspecialchars($userName) ?></span>
                                        <span class="user-role" title="<?= htmlspecialchars($subTitle) ?>"><?= htmlspecialchars($subTitle) ?></span>
                                    </div>
                                    <i class="ti-angle-down profile-arrow"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li class="waves-effect waves-light">
                                        <a href="<?= base_url('profil') ?>">
                                            <i class="ti-user"></i> Profil (<?= htmlspecialchars($roleBadgeName) ?>)
                                        </a>
                                    </li>
                                    <li class="waves-effect waves-light">
                                        <a href="<?= base_url('auth/logout') ?>">
                                            <i class="ti-layout-sidebar-left"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
