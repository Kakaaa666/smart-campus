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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="smart campus, academic, student portal" />
    <meta name="author" content="Smart Campus" />
    <!-- Favicon icon -->
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
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
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css') ?>">
    <!-- Smart Campus Sidebar Style (dengan cache buster otomatis) -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/smart-campus-sidebar.css?v=' . time()) ?>">
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

        /* Submenu Dropdowns */
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu {
            background-color: #f8fafc !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a {
            color: #4b5563 !important;
            background: transparent !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-mtext {
            color: #4b5563 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a .pcoded-micon {
            color: #94a3b8 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a {
            color: #1565c0 !important;
            background: #e8f0fe !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li > a:hover .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li:hover > a .pcoded-mtext {
            color: #1565c0 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a {
            color: #1565c0 !important;
            background: #e3f2fd !important;
            font-weight: 700 !important;
            border-left: 3px solid #1976d2 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-mtext,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-mtext {
            color: #1565c0 !important;
            font-weight: 700 !important;
        }
        .pcoded .pcoded-navbar .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-micon,
        .pcoded .pcoded-navbar[active-item-theme] .pcoded-item .pcoded-hasmenu .pcoded-submenu li.active > a .pcoded-micon {
            color: #1976d2 !important;
        }
    </style>
</head>

<body>
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
