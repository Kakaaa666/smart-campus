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
    <!-- Smart Campus Sidebar Style -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/smart-campus-sidebar.css') ?>">
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
