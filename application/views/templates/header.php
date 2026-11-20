<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= isset($title) ? $title : 'Smart Campus' ?></title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="smart campus, academic, student portal" />
    <meta name="author" content="Smart Campus" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/favicon.svg?v=' . time()) ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png?v=' . time()) ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon-16x16.png?v=' . time()) ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico?v=' . time()) ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/favicon.png?v=' . time()) ?>">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <!-- waves.css -->
    <link rel="stylesheet" href="<?= base_url('assets/pages/waves/css/waves.min.css') ?>" type="text/css" media="all">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap/css/bootstrap.min.css') ?>">
    <!-- themify icon -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/icon/themify-icons/themify-icons.css') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- font-awesome-n -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/font-awesome-n.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
    <!-- scrollbar.css -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.mCustomScrollbar.css') ?>">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">
    <!-- Smart Campus Sidebar Style (dengan cache buster otomatis) -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/smart-campus-sidebar.css?v=' . time()) ?>">
    <!-- Keuangan Modern UI System -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/keuangan-modern.css?v=' . time()) ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/smart-campus-dialogs.css?v=' . time()) ?>">
    <!-- Smart Campus Dialog System (Early Init) -->
    <script src="<?= base_url('assets/js/smart-campus-dialogs.js?v=' . time()) ?>"></script>
    <!-- Inline White Sidebar Style (Menjamin langsung putih tanpa terhalang cache browser) -->
    <style id="smart-campus-white-sidebar-style">
        /* Sidebar Container Putih */
        .pcoded .pcoded-navbar,
        .pcoded .pcoded-navbar .main-menu,
        .pcoded[theme-layout="vertical"] .pcoded-navbar,
        .pcoded .pcoded-navbar[navbar-theme="theme1"],
        .pcoded .pcoded-navbar[navbar-theme="themelight1"],
        .pcoded .pcoded-navbar .pcoded-inner-navbar {
            background-color: #ffffff !important;
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0 !important;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.04) !important;
        }

        /* Default Item Menu (Tidak aktif) */
        .pcoded .pcoded-navbar .pcoded-item > li > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a {
            color: #374151 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            border-left: 4px solid transparent !important;
            background: transparent !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a .pcoded-mtext {
            color: #374151 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a .pcoded-micon {
            color: #5a6a85 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a .pcoded-micon svg {
            fill: currentColor !important;
        }

        /* Hover State - Soft Blue Background & Deep Blue Text/Icon */
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a:hover,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a {
            background: #e8f0fe !important;
            background-color: #e8f0fe !important;
            color: #1565c0 !important;
            border-left: 4px solid #1976d2 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a:hover .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a .pcoded-mtext {
            color: #1565c0 !important;
            font-weight: 700 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li > a:hover .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a .pcoded-micon,
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover .pcoded-micon i,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a .pcoded-micon i,
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover .pcoded-micon svg,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a .pcoded-micon svg {
            color: #1565c0 !important;
            fill: #1565c0 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li > a:hover:after,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li:hover > a:after {
            color: #1565c0 !important;
        }

        /* Active State - Solid Blue Background & White Text/Icon (Super Kontras & Tajam) */
        .pcoded .pcoded-navbar .pcoded-item > li.active > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item li.active > a,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a {
            background: #1976d2 !important;
            background-color: #1976d2 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-left: 4px solid #0d47a1 !important;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.28) !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li.active > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item li.active > a .pcoded-mtext,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-mtext {
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item li.active > a .pcoded-micon,
        .pcoded .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon i,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon i,
        .pcoded .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon svg,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon svg,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon i,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon i,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon svg,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon svg {
            color: #ffffff !important;
            fill: #ffffff !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li.active > a:after,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a:after,
        .pcoded .pcoded-navbar .pcoded-item > li.pcoded-trigger > a:after,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a:after {
            color: #ffffff !important;
        }
        .pcoded .pcoded-navbar .pcoded-item > li.active > a:hover,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item > li.active:hover > a {
            background: #1565c0 !important;
            color: #ffffff !important;
        }

        /* Saat Sidebar Di-minimize (Collapsed Mode): Icon Terpilih (Active) Tetap Jelas Kelihatan */
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.pcoded-trigger > a,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a {
            background: #1976d2 !important;
            background-color: #1976d2 !important;
            border-left: 4px solid #0d47a1 !important;
            justify-content: center !important;
            padding: 12px 0 !important;
        }

        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon {
            background-color: #ffffff !important;
            color: #1976d2 !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.22) !important;
            margin: 0 auto !important;
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transform: scale(1.05) !important;
        }

        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a .pcoded-micon svg,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.active > a .pcoded-micon svg,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.pcoded-trigger > a .pcoded-micon svg,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li.pcoded-trigger > a .pcoded-micon svg,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a:hover .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li.active > a:hover .pcoded-micon svg {
            color: #1976d2 !important;
            fill: #1976d2 !important;
            font-size: 19px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Hover pada icon saat sidebar di-minimize */
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li:not(.active) > a:hover .pcoded-micon,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li:not(.active) > a:hover .pcoded-micon {
            background-color: #e8f0fe !important;
            color: #1976d2 !important;
            border-radius: 8px !important;
        }

        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li:not(.active) > a:hover .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li:not(.active) > a:hover .pcoded-micon i,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li:not(.active) > a:hover .pcoded-micon svg,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar[active-item-theme] .pcoded-item > li:not(.active) > a:hover .pcoded-micon svg {
            color: #1976d2 !important;
            fill: #1976d2 !important;
        }

        /* Badge Notifikasi pada Sidebar Collapsed / Minimized */
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li > a {
            position: relative !important;
        }

        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li > a .badge,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li > a .payment-verification-count,
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > li > a .sidebar-badge-notify {
            position: absolute !important;
            top: 4px !important;
            right: 4px !important;
            z-index: 10 !important;
            min-width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            padding: 0 4.5px !important;
            border-radius: 999px !important;
            border: 2px solid var(--sc-ink, #0e382c) !important;
            font-size: 9.5px !important;
            font-weight: 800 !important;
            line-height: 14px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.45) !important;
            box-sizing: border-box !important;
            pointer-events: none !important;
            white-space: nowrap !important;
        }

        /* Submenu Dropdowns (Neutralize glitch vertical lines, bullets & pseudo chevrons) */
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu:before,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu:before,
        .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu:before,
        .pcoded .pcoded-navbar .pcoded-submenu:before,
        .pcoded-submenu:before,
        .pcoded-submenu:after,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li>a>.pcoded-mtext:before,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu li>a>.pcoded-mtext:before,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu>li>a>.pcoded-mtext:after,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu>li.active>a>.pcoded-mtext:after,
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu[subitem-icon] .pcoded-submenu>li:hover>a>.pcoded-mtext:after {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Submenu Dropdowns - Expanded Mode (Sidebar Terbuka / Lebar) */
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu {
            background-color: #f8fafc !important;
            border-top: 1px solid #eef2f6 !important;
            border-bottom: 1px solid #eef2f6 !important;
            padding: 6px 8px !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a {
            color: #475569 !important;
            background: transparent !important;
            border-left: 3px solid transparent !important;
            border-radius: 8px !important;
            margin: 2px 4px !important;
            padding: 8px 12px 8px 20px !important;
            display: flex !important;
            align-items: center !important;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-mtext,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-mtext {
            color: inherit !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            flex: 1 !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon {
            font-size: 11px !important;
            font-weight: 700 !important;
            width: 26px !important;
            min-width: 26px !important;
            height: 26px !important;
            border-radius: 7px !important;
            background: rgba(2, 132, 199, 0.08) !important;
            border: 1px solid rgba(2, 132, 199, 0.2) !important;
            margin-right: 11px !important;
            color: #0284c7 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon i,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon i {
            transition: transform 0.18s ease !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a {
            color: #0284c7 !important;
            background: #f0f9ff !important;
            border-left-color: #38bdf8 !important;
            transform: translateX(3px) !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover .pcoded-mtext,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a .pcoded-mtext {
            color: #0284c7 !important;
            font-weight: 600 !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover .pcoded-micon,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a .pcoded-micon {
            background: #0284c7 !important;
            border-color: #0284c7 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4) !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover .pcoded-micon i,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a .pcoded-micon i {
            transform: translateX(2px) !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a {
            color: #0284c7 !important;
            background: #e0f2fe !important;
            font-weight: 600 !important;
            border-left: 3px solid #0284c7 !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-mtext,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-mtext {
            color: #0284c7 !important;
            font-weight: 600 !important;
        }
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-micon,
        .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-micon {
            color: #ffffff !important;
            background: #0284c7 !important;
            border-color: #0284c7 !important;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4) !important;
        }

        /* Submenu Dropdowns - Collapsed Flyout Mode (Sidebar Ciut / Minimized) */
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: fixed !important;
            left: 78px !important;
            width: 242px !important;
            height: auto !important;
            max-height: calc(100vh - 30px) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            z-index: 99999 !important;
            padding: 8px 6px !important;
            margin: 0 !important;
            list-style: none !important;
            background: linear-gradient(165deg, rgba(13, 37, 66, 0.96) 0%, rgba(9, 23, 44, 0.98) 100%) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border: 1px solid rgba(56, 189, 248, 0.28) !important;
            border-radius: 14px !important;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.7), 0 0 25px rgba(56, 189, 248, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.12) !important;
            pointer-events: auto !important;
            animation: scFlyoutIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }
        @keyframes scFlyoutIn {
            from {
                opacity: 0;
                transform: translateX(-8px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li {
            display: block !important;
            width: 100% !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a {
            display: flex !important;
            align-items: center !important;
            padding: 8px 12px !important;
            margin: 3px 4px !important;
            border-radius: 9px !important;
            width: auto !important;
            text-align: left !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #cbd5e1 !important;
            background: transparent !important;
            border: 1px solid transparent !important;
            cursor: pointer !important;
            pointer-events: auto !important;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
            position: relative !important;
        }
        /* Squircle Badge Chevron */
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a .pcoded-micon {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            min-width: 28px !important;
            height: 28px !important;
            margin-right: 12px !important;
            border-radius: 9px !important;
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12), 0 2px 4px rgba(0, 0, 0, 0.18) !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            flex-shrink: 0 !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a .pcoded-micon i {
            color: #ffffff !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            display: inline-block !important;
            transition: transform 0.18s ease !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a .pcoded-mtext {
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
            position: static !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            line-height: 1.4 !important;
            white-space: normal !important;
            color: #f1f5f9 !important;
            flex: 1 !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a:hover {
            color: #ffffff !important;
            background: rgba(56, 189, 248, 0.12) !important;
            border-color: rgba(56, 189, 248, 0.3) !important;
            transform: translateX(4px) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a:hover .pcoded-micon {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.45) 0%, rgba(56, 189, 248, 0.35) 100%) !important;
            border-color: #38bdf8 !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li > a:hover .pcoded-micon i {
            transform: translateX(2px) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li.active > a {
            color: #38bdf8 !important;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.28) 0%, rgba(2, 132, 199, 0.18) 100%) !important;
            border-color: rgba(56, 189, 248, 0.45) !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 12px rgba(56, 189, 248, 0.25) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li.active > a .pcoded-micon {
            background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%) !important;
            border-color: #38bdf8 !important;
            color: #ffffff !important;
            box-shadow: 0 0 14px rgba(56, 189, 248, 0.6) !important;
        }
        .pcoded[theme-layout="vertical"][vertical-nav-type="collapsed"] .pcoded-navbar .pcoded-item > .pcoded-hasmenu.pcoded-trigger > .pcoded-submenu li.active > a:after {
            content: "" !important;
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background: #38bdf8 !important;
            box-shadow: 0 0 8px #38bdf8 !important;
            margin-left: auto !important;
            display: inline-block !important;
        }

        /* Menjamin konten halaman tidak tertindih oleh sidebar yang fixed baik saat terbuka maupun ciut */
        @media only screen and (min-width: 769px) {
            .pcoded[theme-layout="vertical"] .pcoded-content,
            .pcoded[theme-layout="vertical"][vertical-placement="left"] .pcoded-content,
            .pcoded[theme-layout="vertical"][vertical-placement="left"][vertical-nav-type="expanded"] .pcoded-content,
            .pcoded[theme-layout="vertical"]:not([vertical-nav-type="collapsed"]) .pcoded-content,
            .pcoded:not([vertical-nav-type="collapsed"]) .pcoded-wrapper > .pcoded-inner-content {
                margin-left: 280px !important;
                margin-right: 0 !important;
                width: calc(100% - 280px) !important;
                max-width: calc(100% - 280px) !important;
                box-sizing: border-box !important;
                left: auto !important;
                right: auto !important;
                float: none !important;
                transition: margin-left 0.25s ease-in-out, width 0.25s ease-in-out;
            }
            .pcoded[theme-layout="vertical"][vertical-placement="left"][vertical-nav-type="collapsed"] .pcoded-content,
            .pcoded[vertical-nav-type="collapsed"] .pcoded-wrapper > .pcoded-inner-content {
                margin-left: 70px !important;
                margin-right: 0 !important;
                width: calc(100% - 70px) !important;
                max-width: calc(100% - 70px) !important;
                box-sizing: border-box !important;
                left: auto !important;
                right: auto !important;
                float: none !important;
                transition: margin-left 0.25s ease-in-out, width 0.25s ease-in-out;
            }
            .pcoded[theme-layout="vertical"][vertical-placement="left"][vertical-nav-type="offcanvas"] .pcoded-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        @media only screen and (max-width: 768px) {
            .pcoded[theme-layout="vertical"] .pcoded-content,
            .pcoded[theme-layout="vertical"][vertical-placement="left"] .pcoded-content,
            .pcoded[theme-layout="vertical"][vertical-placement="left"][vertical-nav-type="expanded"] .pcoded-content,
            .pcoded .pcoded-wrapper > .pcoded-inner-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        /* Utility class untuk spasi ikon */
        .mr-1\.5, .mr-1-5 {
            margin-right: 0.375rem !important;
        }
        .btn i, a.btn i, button i {
            vertical-align: middle;
        }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/smart-campus-themes.css?v=' . time()) ?>">
</head>

<?php
    $theme_role = (int)$this->session->userdata('role');
    $theme_class = $theme_role === 1 ? 'theme-superadmin' : ($theme_role === 2 ? 'theme-admin' : ($theme_role === 4 ? 'theme-dosen' : 'theme-mahasiswa'));
?>
<body class="<?= $theme_class ?>">
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
