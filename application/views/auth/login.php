<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Sistem Informasi Smart Campus Terpadu" />
    <meta name="author" content="Smart Campus" />
    <title><?= isset($title) ? $title : 'Login - Smart Campus' ?></title>
    
    <!-- Favicon icon -->
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap/css/bootstrap.min.css') ?>">
    <!-- Themify & Bootstrap Icons -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/icon/themify-icons/themify-icons.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 40%, #1e88e5 80%, #42a5f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ornamen background bulat estetik */
        .bg-circle-1 {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }
        .bg-circle-2 {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        .login-card-container {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.28);
            overflow: hidden;
            position: relative;
            z-index: 10;
            animation: fadeInCard 0.4s ease-out forwards;
        }

        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
            padding: 30px 24px 25px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .brand-icon-wrapper {
            width: 58px;
            height: 58px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .brand-icon-wrapper i {
            font-size: 28px;
            color: #ffffff;
        }

        .login-header h2 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.88);
            margin: 0;
        }

        .login-body {
            padding: 28px 28px 20px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 7px;
            display: block;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            font-size: 16px;
            color: #1976d2;
            pointer-events: none;
            z-index: 2;
        }

        .input-group-custom input {
            width: 100%;
            height: 46px;
            padding: 10px 42px 10px 42px;
            font-size: 14px;
            border: 1.5px solid #d0d7de;
            border-radius: 8px;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-group-custom input:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.18);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            cursor: pointer;
            color: #6c757d;
            font-size: 16px;
            z-index: 2;
            transition: color 0.2s ease;
        }
        .toggle-password:hover {
            color: #1976d2;
        }

        .btn-login {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(21, 101, 192, 0.35);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            box-shadow: 0 6px 16px rgba(21, 101, 192, 0.45);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* Alert styling */
        .alert-custom {
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-custom-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .alert-custom-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-custom-info {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }

        .footer-text {
            text-align: center;
            padding: 16px 20px;
            font-size: 12px;
            color: #868e96;
            background: #fdfdfd;
            border-top: 1px solid #f1f3f5;
        }
    </style>
</head>
<body>

    <div class="bg-circle-1"></div>
    <div class="bg-circle-2"></div>

    <div class="login-card-container">
        <!-- Header -->
        <div class="login-header">
            <div class="brand-icon-wrapper">
                <i class="bi bi-bank2"></i>
            </div>
            <h2>SMART CAMPUS</h2>
            <p>Sistem Informasi Terpadu Mahasiswa & Staf</p>
        </div>

        <!-- Body Form -->
        <div class="login-body">
            <!-- Flash Alert -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-custom alert-custom-danger">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px;"></i>
                    <div><?= $this->session->flashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-custom alert-custom-success">
                    <i class="bi bi-check-circle-fill" style="font-size: 18px;"></i>
                    <div><?= $this->session->flashdata('success') ?></div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('info')): ?>
                <div class="alert-custom alert-custom-info">
                    <i class="bi bi-info-circle-fill" style="font-size: 18px;"></i>
                    <div><?= $this->session->flashdata('info') ?></div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <?= form_open('auth/process') ?>
                <div class="form-group">
                    <label for="nim">NIM / Username / Email</label>
                    <div class="input-group-custom">
                        <i class="bi bi-person-badge input-icon"></i>
                        <input type="text" name="nim" id="nim" class="form-control" placeholder="Masukkan NIM atau Email" value="<?= set_value('nim') ?>" required autofocus>
                    </div>
                    <?= form_error('nim', '<small class="text-danger font-weight-bold mt-1 d-block">', '</small>') ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
                        <i class="bi bi-eye-slash toggle-password" id="togglePasswordBtn"></i>
                    </div>
                    <?= form_error('password', '<small class="text-danger font-weight-bold mt-1 d-block">', '</small>') ?>
                </div>

                <button type="submit" class="btn-login">
                    <span>Masuk ke Akun</span>
                    <i class="bi bi-arrow-right-short" style="font-size: 20px;"></i>
                </button>
            <?= form_close() ?>
        </div>

        <!-- Footer -->
        <div class="footer-text">
            &copy; <?= date('Y') ?> Smart Campus. Hak Cipta Dilindungi.
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Show / Hide Password
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.classList.toggle('bi-eye-slash', !isPassword);
                this.classList.toggle('bi-eye', isPassword);
            });
        }
    </script>
</body>
</html>
