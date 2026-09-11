<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                
                <style>
                /* Custom Styling: Card putih, pojok melengkung (tidak lancip), dan jarak antar card */
                .custom-card-white {
                    background-color: #ffffff !important;
                    background: #ffffff !important;
                    border-radius: 16px !important;
                    -webkit-border-radius: 16px !important;
                    -moz-border-radius: 16px !important;
                    border: 1px solid #edf2f7 !important;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
                    overflow: hidden !important;
                    margin-bottom: 28px !important;
                    transition: all 0.25s ease-in-out;
                }

                .custom-card-header {
                    background-color: #ffffff !important;
                    border-bottom: 1px solid #f1f5f9 !important;
                    border-top-left-radius: 16px !important;
                    border-top-right-radius: 16px !important;
                    padding: 18px 24px !important;
                }

                .custom-card-header h5 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 700;
                    color: #1565c0;
                }

                .user-profile-avatar-circle {
                    width: 110px;
                    height: 110px;
                    border-radius: 50% !important;
                    object-fit: cover;
                    border: 3px solid #e2e8f0;
                    padding: 3px;
                    background: #ffffff;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                }

                /* Password Input Group: Ukuran logo/addon kiri & tombol kanan presisi sama tinggi dengan textfield */
                .password-input-group {
                    display: flex !important;
                    align-items: stretch !important;
                    width: 100% !important;
                    position: relative !important;
                }

                .password-input-group .input-group-prepend,
                .password-input-group .input-group-append {
                    display: flex !important;
                    margin: 0 !important;
                }

                .password-input-group .input-group-text {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    height: 46px !important;
                    min-height: 46px !important;
                    max-height: 46px !important;
                    width: 48px !important;
                    min-width: 48px !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    font-size: 19px !important;
                    color: #1565c0 !important;
                    background-color: #f8fafc !important;
                    border: 1.5px solid #d0d7de !important;
                    border-right: none !important;
                    border-top-left-radius: 8px !important;
                    border-bottom-left-radius: 8px !important;
                    border-top-right-radius: 0 !important;
                    border-bottom-right-radius: 0 !important;
                    box-sizing: border-box !important;
                }

                .password-input-group .form-control {
                    display: block !important;
                    height: 46px !important;
                    min-height: 46px !important;
                    max-height: 46px !important;
                    font-size: 14px !important;
                    color: #1e293b !important;
                    background-color: #ffffff !important;
                    border: 1.5px solid #d0d7de !important;
                    border-radius: 0 !important;
                    padding: 10px 14px !important;
                    margin: 0 !important;
                    box-sizing: border-box !important;
                    flex: 1 1 auto !important;
                    width: 1% !important;
                    transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
                }

                .password-input-group .form-control:focus {
                    border-color: #1976d2 !important;
                    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.12) !important;
                    z-index: 3 !important;
                }

                .password-input-group .toggle-pass-btn {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    height: 46px !important;
                    min-height: 46px !important;
                    max-height: 46px !important;
                    width: 48px !important;
                    min-width: 48px !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    font-size: 17px !important;
                    color: #64748b !important;
                    background-color: #ffffff !important;
                    border: 1.5px solid #d0d7de !important;
                    border-left: none !important;
                    border-top-right-radius: 8px !important;
                    border-bottom-right-radius: 8px !important;
                    border-top-left-radius: 0 !important;
                    border-bottom-left-radius: 0 !important;
                    box-sizing: border-box !important;
                    cursor: pointer !important;
                    transition: background-color 0.2s ease, color 0.2s ease !important;
                }

                .password-input-group .toggle-pass-btn:hover {
                    background-color: #f1f5f9 !important;
                    color: #1e293b !important;
                }
                </style>

                <!-- Page Header: Putih Polos, Melengkung, & Berjarak -->
                <div class="card custom-card-white">
                    <div class="card-block" style="padding: 22px 28px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="m-b-5" style="font-size: 20px; font-weight: 700; color: #1e293b;">Pengaturan Profil Pengguna</h4>
                                <p class="m-b-0" style="color: #64748b; font-size: 13.5px;">Kelola informasi identitas, foto profil, dan keamanan password akun Anda.</p>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <ul style="display: inline-flex; align-items: center; list-style: none; margin: 0; padding: 0; font-size: 13.5px;">
                                    <li>
                                        <a href="<?= base_url('beranda') ?>" style="color: #1565c0; text-decoration: none; font-weight: 500;">
                                            <i class="fa fa-home mr-1"></i> Beranda
                                        </a>
                                    </li>
                                    <li style="color: #94a3b8; margin: 0 8px;">/</li>
                                    <li style="color: #64748b; font-weight: 600;">Profil</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-body">
                    <div class="row">
                        
                        <!-- Kolom Kiri: Kartu Ringkasan Profil Pengguna (Card Putih Biasa & Pojok Melengkung) -->
                        <div class="col-xl-4 col-lg-5 col-md-12">
                            <div class="card custom-card-white">
                                <div class="card-block text-center" style="padding: 32px 24px 28px;">
                                    <div class="user-image mb-3" style="position: relative; display: inline-block;">
                                        <img src="<?= base_url('assets/images/' . (!empty($user->foto) ? $user->foto : 'avatar-4.png')) ?>" 
                                             class="user-profile-avatar-circle" 
                                             id="currentAvatarPreview"
                                             alt="Foto Profil">
                                        <span style="position: absolute; bottom: 6px; right: 6px; width: 16px; height: 16px; background-color: #2ed8b6; border: 2.5px solid #fff; border-radius: 50%;"></span>
                                    </div>
                                    <h4 class="m-t-10 m-b-5" style="font-weight: 700; font-size: 18px; color: #2c3e50;"><?= htmlspecialchars($user->nama_lengkap) ?></h4>
                                    <p class="text-muted mb-3" style="font-size: 14px; font-weight: 600; color: #1565c0 !important;">
                                        <i class="bi bi-person-badge mr-1"></i> NIM: <?= htmlspecialchars($user->nim) ?>
                                    </p>

                                    <hr style="border-color: #f1f3f5; margin: 18px 0;">

                                    <div class="text-left" style="font-size: 13.5px; color: #555;">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <i class="bi bi-envelope-fill mr-2 text-primary" style="font-size: 15px;"></i>
                                            <span class="text-truncate"><?= htmlspecialchars($user->email) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <i class="bi bi-calendar-check-fill mr-2 text-success" style="font-size: 15px;"></i>
                                            <span>Bergabung: <?= date('d M Y', strtotime($user->created_at)) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock-history mr-2 text-info" style="font-size: 15px;"></i>
                                            <span>Diperbarui: <?= date('d M Y, H:i', strtotime($user->updated_at)) ?> WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Form Ubah Foto & Form Ubah Password -->
                        <div class="col-xl-8 col-lg-7 col-md-12">
                            
                            <!-- Kartu 1: Ubah Foto Profil (Card Putih, Pojok Melengkung, & Jarak ke Card Bawah) -->
                            <div class="card custom-card-white">
                                <div class="card-header custom-card-header">
                                    <h5>
                                        <i class="bi bi-camera-fill mr-2"></i> Ubah Foto Profil
                                    </h5>
                                </div>
                                <div class="card-block" style="padding: 24px;">
                                    <!-- Alert Khusus Foto -->
                                    <?php if ($this->session->flashdata('error_foto')): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                                            <i class="bi bi-exclamation-triangle-fill mr-2"></i> <?= $this->session->flashdata('error_foto') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('success_foto')): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                                            <i class="bi bi-check-circle-fill mr-2"></i> <?= $this->session->flashdata('success_foto') ?>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open_multipart('profil/update_foto') ?>
                                        <div class="row align-items-center">
                                            <div class="col-sm-3 text-center mb-3 mb-sm-0">
                                                <div style="position: relative; display: inline-block;">
                                                    <img id="liveUploadPreview" 
                                                         src="<?= base_url('assets/images/' . (!empty($user->foto) ? $user->foto : 'avatar-4.png')) ?>" 
                                                         alt="Preview Foto Baru" 
                                                         style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #1976d2; box-shadow: 0 3px 8px rgba(0,0,0,0.15);">
                                                    <span class="badge badge-primary px-2 py-1" style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); font-size: 10px; border-radius: 10px; white-space: nowrap;">
                                                        Preview
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="form-group mb-2">
                                                    <label for="foto_profil" class="font-weight-bold" style="font-size: 13.5px;">Pilih Berkas Foto Baru</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="foto_profil" class="custom-file-input" id="foto_profil" accept="image/png, image/jpeg, image/jpg" required>
                                                        <label class="custom-file-label" for="foto_profil" id="customFileLabel" style="border-radius: 8px;">Pilih foto (JPG, JPEG, PNG)</label>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="bi bi-info-circle mr-1"></i> Format didukung: JPG, JPEG, PNG. Maksimal ukuran 3MB. Disarankan foto rasio 1:1.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-primary px-4 mt-2" style="border-radius: 8px; font-weight: 600;">
                                                    <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Unggah & Simpan Foto
                                                </button>
                                            </div>
                                        </div>
                                    <?= form_close() ?>
                                </div>
                            </div>

                            <!-- Kartu 2: Ubah Password Akun (Berjarak dengan Card di atasnya, Card Putih, Pojok Melengkung) -->
                            <div class="card custom-card-white" id="card-password">
                                <div class="card-header custom-card-header">
                                    <h5>
                                        <i class="bi bi-key-fill mr-2"></i> Ubah Password Akun
                                    </h5>
                                </div>
                                <div class="card-block" style="padding: 24px;">
                                    <!-- Alert Khusus Password -->
                                    <?php if ($this->session->flashdata('error_password')): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                                            <i class="bi bi-exclamation-triangle-fill mr-2"></i> <?= $this->session->flashdata('error_password') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('success_password')): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                                            <i class="bi bi-check-circle-fill mr-2"></i> <?= $this->session->flashdata('success_password') ?>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open('profil/update_password') ?>
                                        <div class="form-group">
                                            <label for="password_baru" class="font-weight-bold" style="font-size: 13.5px;">Password Baru</label>
                                            <div class="input-group password-input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                                                </div>
                                                <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                                                <div class="input-group-append">
                                                    <button class="btn toggle-pass-btn" type="button" data-target="password_baru">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-info-circle mr-1"></i> Gunakan kombinasi huruf dan angka minimal 6 karakter.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="konfirmasi_password" class="font-weight-bold" style="font-size: 13.5px;">Ulangi Password Baru</label>
                                            <div class="input-group password-input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                                </div>
                                                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Ketik ulang password baru Anda" required>
                                                <div class="input-group-append">
                                                    <button class="btn toggle-pass-btn" type="button" data-target="konfirmasi_password">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                                            <i class="bi bi-check2-circle mr-1"></i> Perbarui Password
                                        </button>
                                    <?= form_close() ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script Live Preview & Toggle Password -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Live Image Preview saat memilih file foto
    const fotoInput = document.getElementById('foto_profil');
    const livePreview = document.getElementById('liveUploadPreview');
    const customLabel = document.getElementById('customFileLabel');

    if (fotoInput && livePreview) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (customLabel) customLabel.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(event) {
                    livePreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 2. Toggle Show/Hide Password
    const toggleButtons = document.querySelectorAll('.toggle-pass-btn');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (targetInput) {
                const isPass = targetInput.type === 'password';
                targetInput.type = isPass ? 'text' : 'password';
                if (icon) {
                    icon.classList.toggle('bi-eye-slash', !isPass);
                    icon.classList.toggle('bi-eye', isPass);
                }
            }
        });
    });
});
</script>
