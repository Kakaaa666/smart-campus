<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$ci_base_url = '/';
if (function_exists('config_item') && config_item('base_url')) {
    $ci_base_url = config_item('base_url');
} elseif (!empty($_SERVER['SCRIPT_NAME'])) {
    $ci_base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>404 - Halaman Tidak Ditemukan | Smart Campus</title>
    <link rel="icon" href="<?= $ci_base_url ?>assets/images/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f4f7fb;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Brand Header (Logo Berada Persis di Tengah) */
        .error-topbar {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 50%, #1e88e5 100%);
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .error-brand {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: #ffffff;
            white-space: nowrap;
        }
        .error-brand svg {
            width: 24px;
            height: 24px;
            fill: #ffffff;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
        }
        .error-brand-text {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .error-top-link {
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            transition: all 0.2s ease;
        }
        .error-top-link:hover {
            background: rgba(255, 255, 255, 0.28);
            color: #ffffff;
            transform: translateY(-51%);
        }

        /* Container & Card */
        .error-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .error-card {
            background: #ffffff;
            max-width: 580px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(226, 232, 240, 0.8);
            padding: 48px 36px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: cardAppear 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Decorative top accent gradient line */
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1976d2, #42a5f5, #2ed8b6);
        }

        /* 404 Besar di Atas */
        .error-graphic {
            display: block;
            margin: 0 auto 10px;
            text-align: center;
        }
        .error-number {
            font-size: 100px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -3px;
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 60%, #42a5f5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            user-select: none;
            display: inline-block;
        }

        /* Badge Error 404 • Not Found di Bawah Angka 404 */
        .error-badge-wrapper {
            display: block;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 16px;
            background: #eff6ff;
            color: #1976d2;
            border: 1px solid #bfdbfe;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .error-badge-dot {
            width: 7px;
            height: 7px;
            background: #1976d2;
            border-radius: 50%;
            display: inline-block;
        }

        /* Typography */
        .error-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .error-desc {
            font-size: 14.5px;
            color: #64748b;
            margin-bottom: 30px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        .error-custom-msg {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 13.5px;
            color: #475569;
            margin-bottom: 24px;
            display: inline-block;
        }

        /* Action Buttons */
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 0;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 26px;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(25, 118, 210, 0.28);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            box-shadow: 0 6px 18px rgba(25, 118, 210, 0.38);
            transform: translateY(-1px);
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #ffffff;
            color: #475569 !important;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #1e293b !important;
            transform: translateY(-1px);
        }

        /* Footer */
        .error-footer {
            text-align: center;
            padding: 18px;
            font-size: 12.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        @media (max-width: 600px) {
            .error-card {
                padding: 36px 20px 28px;
                border-radius: 16px;
            }
            .error-number {
                font-size: 80px;
            }
            .error-title {
                font-size: 20px;
            }
            .error-actions {
                flex-direction: column;
                width: 100%;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
            }
            .error-top-link {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Header Topbar Smart Campus (Logo Berada di Tengah) -->
    <header class="error-topbar">
        <a href="<?= $ci_base_url ?>" class="error-brand">
            <!-- Icon Smart Campus Bank/Academic -->
            <svg viewBox="0 0 16 16">
                <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.382l.5 2a.498.498 0 0 1-.485.618H.5a.5.5 0 0 1-.485-.618l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zm3 0v7h1V6zM1 4v1h14V4zm-.5 10v1h15v-1z"/>
            </svg>
            <span class="error-brand-text">SMART CAMPUS</span>
        </a>
        <a href="<?= $ci_base_url ?>" class="error-top-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Beranda Portal</span>
        </a>
    </header>

    <!-- Main Content Container -->
    <main class="error-container">
        <div class="error-card">
            <!-- 1. Angka 404 Besar Berada di Atas -->
            <div class="error-graphic">
                <div class="error-number">404</div>
            </div>

            <!-- 2. Badge Error 404 • Not Found Berada di Bawah 404 Besar -->
            <div class="error-badge-wrapper">
                <div class="error-badge">
                    <span class="error-badge-dot"></span>
                    <span>Error 404 • Not Found</span>
                </div>
            </div>

            <h1 class="error-title">
                <?= !empty($heading) && $heading !== '404 Page Not Found' ? htmlspecialchars($heading) : 'Halaman Tidak Ditemukan' ?>
            </h1>

            <p class="error-desc">
                Mohon maaf, halaman yang Anda tuju tidak dapat ditemukan atau telah dipindahkan ke alamat lain. Silakan periksa kembali tautan yang Anda masukkan.
            </p>

            <?php if (!empty($message) && trim(strip_tags($message)) !== '' && strpos($message, 'The page you requested was not found') === false): ?>
                <div class="error-custom-msg">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="error-actions">
                <a href="<?= $ci_base_url ?>" class="btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
                <button onclick="window.history.length > 1 ? window.history.back() : window.location.href = '<?= $ci_base_url ?>';" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="error-footer">
        &copy; <?= date('Y') ?> Smart Campus &bull; Sistem Informasi Akademik Terpadu
    </footer>

</body>
</html>