<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This is ARTPS offical state site.">
    <link rel="shortcut icon" href="<?= base_url('assets/site/theme1/images/favicon.ico') ?>" type="image/x-icon">

    <title>ARTPS | Government of Assam</title>

    <meta property="og:title" content="RTPS Assam" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://rtps.assam.gov.in/apps/site/" />
    <meta property="og:image" content="https://rtps.assam.gov.in/apps/assets/site/theme1/images/xohari.png" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:height" content="200">
    <meta property="og:image:width" content="200">
    <meta property="og:description" content="Right To Public Services, Assam" />

    <meta name="twitter:title" content="RTPS Assam ">
    <meta name="twitter:description" content="Right To Public Services, Assam.">
    <meta name="twitter:image" content="https://rtps.assam.gov.in/apps/assets/site/theme1/images/xohari.png">
    <meta name="twitter:site" content="@accsdp_assam">
    <meta name="twitter:card" content="summary_large_image">

   

    <!--Bootstrap 4.5-->
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/css/bootstrap.min.css') ?>">
          <!-- <link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/css/bootstrap.min.css') ?>"> -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/hover.css') ?>">
    <!--Fontawesome-->
    <link href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_50NeAi7JFrdZIQ4-8SzJGvFZILwe8ujnNw-BtlD8uFk.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_7sFRun3KMLmgqxmwZkZmgWA4IBYgF3fW1AeYVm5Vn3M.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_xE-rWrJf-fncB6ztZfd2huxqgxu4WO-qwma6Xer30m4.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_ZbgrQ2AkdXebvD2F_kVnXdm4EZhJTHEMbaiPUq-uvgA.css') ?>">
    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/iservice_style.css') ?>">
    <!-- <link rel="stylesheet" href="<?= base_url('assets/site/theme1/css/style.css') ?>"> -->
    <!--Jquery-->
        <script type="text/javascript"
                src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!--Popper-->
    <script type="text/javascript"
            src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/popper.min.js') ?>"></script>
    <!--Bootstrap 4.5-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>
    <!-- <script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/dark.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/font-resize.js"); ?>" defer></script> -->


</head>
<body>
<!--Header-->
<!-- <div class="loader"><img src="<?= base_url('assets/frontend/images/loader.gif') ?>"></div> -->

<div class="loader"></div>

<!--Navigation Bar-->
<!-- <section>
    <nav class="navbar navbar-expand-md navbar-light nav-head">
        <div class="container-fluid header-sec" style="width:1300px !important">

            <a class="navbar-brand text-light font-weight-bold" href="/">
                <img class="img-fluid leftlogo" alt="Logo"
                     src="<?= base_url('assets/frontend/images/header_text.png') ?>">
            </a>

            <button class="navbar-toggler btn-light navbar-right bg-light btn-sm tog-btn" type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon small"></span>
            </button>

            
            <span class="d-none d-md-inline navbar-brand text-dark font-weight-bold">
                        <img id="rightlogo" title="ARTPS Logo" class="img-fluid d-inline-block ml-5" alt="Logo"
                             src="<?= base_url('assets/frontend/images/logo_artps.png') ?>">
                    </span>
        </div>
    </nav>
</section> -->
   <!-- Topmost Panel -->
   <!-- <div class="container-fluid top-panel">
            <section class="container d-flex justify-content-between align-items-stretch flex-wrap" style="padding-bottom: 5px;padding-top: 5px;">

                <div class="py-1">
                    <img class="d-inline-block me-1" src="<?= base_url('assets/site/theme1/images/govtofindia.png') ?>" alt="indian flag" width="16">
                    <span class="small text-uppercase text-white"> Government of assam | administrative reforms and training department</span>
                </div>
                <section class="top-align w-50 d-none d-md-flex justify-content-between align-items-stretch flex-wrap">
                    <div class="d-flex justify-content-between flex-wrap">
                    </div>
                </section>

                Icons for mobile view
                <section class="w-100 d-flex d-md-none flex-wrap">
                    <a class="login p-2 small me-3" href="#">
                        <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/login.png') ?>" alt="sitemap" width="16">
                    </a>
                    <a class="nav-link search p-1 ml-1 text-white " href="#"> <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/search.png') ?>" alt="search" width="16"></a>
                </section>
            </section>
        </div> -->

<!-- <section class="container-fluid py-2">
            <div class="container d-flex justify-content-between flex-wrap align-items-center">

                <div class="d-flex justify-content-between">
                    <img class="d-none d-md-inline-block" src="<?= base_url('assets/site/theme1/images/emblem.png') ?>" style="transform: translateX(-10px);height:70px" id="emblem" alt="Emblem" height="70">

                    <div class="heading-text">
                        <h2 class="emb fw-bold">Right to Public Services </h2>
                        <h3 class="emb fw-bold">Assam</h3>
                    </div>
                </div>

                <img class="img-fluid" src="<?= base_url('assets/site/theme1/images/xohari.png') ?>" id="xohari" alt="Xohari" width="64">

            </div>
</section> -->

<!--HEADER END-->