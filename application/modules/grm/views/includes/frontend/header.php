<?php
$this->load->model('site/settings_model');
$header = $this->settings_model->get_settings('headers');
$lang = $this->rtps_lang;
// $theme = $this->theme;
// pre($header);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= isset($header->page_title->$lang) ? $header->page_title->$lang : 'RTPS' ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="SewaSetu, SewaSetu Assam, Sewa Set, Sewa Setu Assam, Right to Public Service Portal, Assam Right to Public Services Portal" name="keywords">
    <meta content="SewaSetu is an initiative of the Government of Assam to deliver the Government services at the doorsteps of the citizens." name="description">
    <meta content="National Informatics Centre, Assam" name="author">
    <!-- Social Media-->
    <meta property="og:title" content="SewaSetu Assam">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sewasetu.assam.gov.in/">
    <meta property="og:image" content="https://sewasetu.assam.gov.in/assets/site/theme1/images/xohari.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:height" content="200">
    <meta property="og:image:width" content="200">
    <meta property="og:description" content="SewaSetu is an initiative of the Government of Assam to deliver the Government services at the doorsteps of the citizens.">

    <meta name="twitter:title" content="SewaSetu Assam ">
    <meta name="twitter:description" content="SewaSetu, Assam.">
    <meta name="twitter:image" content="https://sewasetu.assam.gov.in/assets/site/theme1/images/xohari.png">
    <meta name="twitter:site" content="@accsdp_assam">
    <meta name="twitter:card" content="summary_large_image">
    <!-- Favicon -->
    <link href="https://sewasetu.assam.gov.in/assets/site/sewasetu/assets/images/favicon.png" rel="icon">



    <!--Bootstrap 4.5-->
    <!-- <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/hover.css') ?>"> -->
    <!-- Preloading critical resources -->
    <!-- Preloading critical resources -->
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-Regular.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-Italic.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-Medium.ttf')  ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-MediumItalic.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-SemiBold.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-SemiBoldItalic.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-Bold.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/sewasetu/assets/font/Nunito/static/Nunito-BoldItalic.ttf') ?>" as="font" type="font/ttf" crossorigin>

    <!-- Icon Stylesheets -->
    <link href="<?= base_url('assets/site/sewasetu/node_modules/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/site/sewasetu/node_modules/bootstrap-icons/font/bootstrap-icons.css') ?>" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/site/sewasetu/node_modules/animate.css/animate.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/site/sewasetu/assets/lib/owlcarousel/assets/owl.carousel.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/site/sewasetu/node_modules/lightbox/css/lightbox.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/site/sewasetu/assets/lib/fxss-rate/rate.min.css') ?>" rel="stylesheet">
    <!-- Framework Stylesheet -->
    <link href="<?= base_url('assets/site/sewasetu/node_modules/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!--Custom CSS-->
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/style.css') ?>"> -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/iservice_style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/site/sewasetu/assets/css/style.css') ?>">
    <!-- <link rel="stylesheet" href="<?= base_url('assets/site/theme1/css/style.css') ?>"> -->
    <!--Jquery-->
    <script type="text/javascript" src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!--Popper-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/popper.min.js') ?>"></script>
    <!--Bootstrap 4.5-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script> -->

    <script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>

    <!-- <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/dark.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/font-resize.js"); ?>" defer></script> -->


</head>

<body>


    <!--Navigation Bar-->

    <!-- Topmost Panel -->
    <div class="container-fluid top-panel">
        <section class="container p-0 d-flex justify-content-between align-items-stretch flex-wrap">

            <div class="py-1 govt-text">
                <img class="d-inline-block me-1" src="https://rtps.assam.gov.in/assets/site/theme1/images/govtofindia.png" alt="indian flag" width="16">
                <span class="small text-uppercase govt-title">
                    <?= isset($header->top_bar[0]->$lang) ? $header->top_bar[0]->$lang : "Government of assam | Administrative Reforms and Training Department" ?>
                </span>
            </div>
            <div class="dropdown language me-1">
                <button class="btn btn-sm dropdown-toggle pb-0 pt-2 small text-uppercase fw-bold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= isset($header->top_bar[1]->$lang) ? $header->top_bar[1]->$lang : "Language" ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="min-width: auto;">
                    <li><a class="dropdown-item small" href="<?= base_url('site/lang/en') ?>">English</a></li>
                    <li><a class="dropdown-item small" href="<?= base_url('site/lang/as') ?>">অসমীয়া</a></li>
                    <li><a class="dropdown-item small" href="<?= base_url('site/lang/bn') ?>">বাংলা</a></li>
                </ul>
            </div>

        </section>
    </div>

    <nav class="navbar navbar-expand-lg bg-rim navbar-light shadow sticky-top p-0">
        <div class="d-flex justify-content-md-between position-relative">
            <img class="d-md-inline-block" src="<?= base_url('assets/site/sewasetu/assets/images/indiaemblem.png') ?>" id="emblem" alt="Emblem" height="65">

            <div class="heading-text">
                <h2 class="emb fw-bold mb-0">
                    <?= isset($header->brand_logos->h2->$lang) ? $header->brand_logos->h2->$lang : 'Right to Public Services' ?>
                </h2>
                <h6 class="mb-0 fst-italic">
                    <?= isset($header->brand_logos->h5->$lang) ? $header->brand_logos->h5->$lang : '' ?>
                </h6>
            </div>

        </div>
        <button type="button" class="navbar-toggler me-4 d-none" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">



            </div>
            <div class="rim-parallel">
                <a href="" class="second-rim-logo px-lg-5 d-none d-lg-block">
                    <img src=<?= base_url("assets/site/sewasetu/assets/images/SS_LOGO.png") ?> alt="Emblem" style="height:65px;">
                </a>
            </div>
        </div>
    </nav>

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-md container-fluid py-0">
        <div class="container">

            <a class="navbar-brand p-2" href="<?= base_url('site') ?>">
                <img src="<?= base_url('assets/site/theme1/images/home.png') ?>" alt="Home" width="16">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars" style="color: #ffd303;"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav text-uppercase">
                    <a class="nav-link text-white me-4 px-md-3" href="<?= base_url('grm') ?>">
                        <?= isset($header->apply->$lang) ? $header->apply->$lang : 'Apply' ?>
                    </a>
                    <a class="nav-link text-white me-4 px-md-3" href="<?= base_url('grm/trackstatus') ?>">
                        <?= isset($header->vs->$lang) ? $header->vs->$lang : 'View Status' ?>
                    </a>
                </div>
            </div>

        </div>
    </nav>