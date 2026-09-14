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
                                <div class="sidebar_toggle"><a href="javascript:void(0)" id="mobile-collapse"><i class="ti-menu"></i></a></div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="header-notification nav-item-bell">
                                <a href="#!" class="bell-btn waves-effect waves-light">
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
                                $userName = $this->session->userdata('nama_lengkap') ? $this->session->userdata('nama_lengkap') : 'Muhammad Eka';
                                $userNim  = $this->session->userdata('nim') ? $this->session->userdata('nim') : '210101001';
                                $userRole = $this->session->userdata('role_name') ? $this->session->userdata('role_name') : 'Mahasiswa';
                                $userFoto = $this->session->userdata('foto') ? $this->session->userdata('foto') : 'avatar-4.png';
                            ?>
                            <li class="user-profile header-notification">
                                <a href="#!" class="user-profile-badge waves-effect waves-light">
                                    <div class="user-avatar-wrapper">
                                        <img src="<?= base_url('assets/images/' . $userFoto) ?>" class="user-avatar-img" alt="Foto Profil">
                                        <span class="user-status-dot"></span>
                                    </div>
                                    <div class="user-info-wrapper">
                                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                                        <span class="user-role"><?= htmlspecialchars($userNim) ?></span>
                                    </div>
                                    <i class="ti-angle-down profile-arrow"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li class="waves-effect waves-light">
                                        <a href="<?= base_url('profil') ?>">
                                            <i class="ti-user"></i> Profil (<?= htmlspecialchars($userRole) ?>)
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
