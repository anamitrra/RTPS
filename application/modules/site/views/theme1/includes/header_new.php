<?php
$lang = $this->lang;
// $theme = $this->theme;
// pre($header);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        var u = new URL(window.location.href);
        if (window.location.href.indexOf("www") > -1) {
            //alert("your url contains the www");
            window.location = "https://rtps.assam.gov.in/";
        }
    </script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This is ARTPS offical state site.">
    <link rel="shortcut icon" href="<?= base_url('assets/site/theme1/images/favicon.ico') ?>" type="image/x-icon">

    <title><?= $header->page_title->$lang ?></title>

    <meta property="og:title" content="RTPS Assam" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://rtps.assam.gov.in/" />
    <meta property="og:image" content="https://rtps.assam.gov.in/assets/site/theme1/images/xohari.png" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:height" content="200">
    <meta property="og:image:width" content="200">
    <meta property="og:description" content="Right To Public Services, Assam" />

    <meta name="twitter:title" content="RTPS Assam ">
    <meta name="twitter:description" content="Right To Public Services, Assam.">
    <meta name="twitter:image" content="https://rtps.assam.gov.in/assets/site/theme1/images/xohari.png">
    <meta name="twitter:site" content="@accsdp_assam">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Preloading critical resources -->
    <link rel="preload" href="<?= base_url('assets/site/theme1/font/roboto/Roboto-Regular.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/theme1/font/roboto/Roboto-Italic.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/theme1/font/roboto/Roboto-Bold.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/site/theme1/font/roboto/Roboto-Medium.ttf') ?>" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?= base_url('assets/plugins/fontawesome-free/webfonts/fa-solid-900.woff2') ?>" as="font" type="font/woff2" crossorigin>


    <link href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url("assets/site/theme1/css/style.css") ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/css/rwd.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/chatbot/css/bot.css') ?>">

    <?php
    $method = $this->router->fetch_method();
    // print_r($method);die('hi');

    if ($method == 'contact') { ?>
        <script src="<?= base_url("assets/site/theme1/mapview/js/jq2.js"); ?>"></script>
        <script src="<?= base_url("assets/site/theme1/mapview/js/jq1.js"); ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.6.0.min.js"); ?>" defer></script>
    <?php }
    ?>

    <!-- <script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.6.0.min.js"); ?>" defer></script> -->
    <script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/dark.js"); ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/font-resize.js"); ?>" defer></script>

    <!-- Chatbot -->
    <script src="<?= base_url('assets/site/theme1/chatbot/js/bot.js') ?>" defer></script>

</head>

<body>

    <header>

        <!-- Checking JS support in the Browser -->
        <noscript>
            <h1 class="display-6 alert alert-danger">Please Enable Javascript to Use this Website.</h1>
        </noscript>

        <!-- Topmost Panel -->
        <div class="container-fluid top-panel">
            <section class="container p-0 px-md-3 d-flex justify-content-between align-items-stretch flex-wrap">

                <div class="py-1 govt-text">
                    <img class="d-inline-block me-1" src="<?= base_url('assets/site/theme1/images/govtofindia.png') ?>" alt="indian flag" width="16" height="16">
                    <span class="small text-uppercase govt-title"><?= $header->top_bar[0]->$lang ?></span>
                </div>

                <!-- GIGW Modifications -->
                <section class="top-align w-50 d-none d-md-flex align-items-stretch">

                    <div class="d-flex justify-content-between">

                        <div class="dropdown language me-1">
                            <button class="btn btn-sm dropdown-toggle pb-0 pt-2 small text-uppercase fw-bold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $header->top_bar[1]->$lang ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="min-width: auto;">
                                <li><a class="dropdown-item small" href="<?= base_url('site/lang/en') ?>">English</a></li>
                                <li><a class="dropdown-item small" href="<?= base_url('site/lang/as') ?>">অসমীয়া</a></li>
                                <li><a class="dropdown-item small" href="<?= base_url('site/lang/bn') ?>">বাংলা</a></li>
                            </ul>
                        </div>

                        <a class="sitemap p-2 me-1" href="<?= base_url('site/sitemap') ?>" title="<?= $header->top_bar[2]->$lang ?>">
                            <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/sitemap.png') ?>" alt="sitemap" height="16" width="16">
                        </a>

                        <div class="dropdown text-settings me-1" title="<?= $header->top_bar[3]->$lang ?>">
                            <button class="btn btn-sm dropdown-toggle pb-0 pt-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= base_url('assets/site/theme1/images/text_settings.png') ?>" alt="text settings" height="16" width="16">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li title="<?= $header->top_bar[4]->title[0]->$lang ?>"><a class="dropdown-item small" href="#" data-font="+">
                                        <?= $header->top_bar[4]->$lang ?>
                                        <i class="fas fa-plus"></i>
                                    </a></li>
                                <li title="<?= $header->top_bar[4]->title[1]->$lang ?>"><a class="dropdown-item small" href="#" data-font="0">
                                        <?= $header->top_bar[4]->$lang ?>
                                    </a></li>
                                <li title="<?= $header->top_bar[4]->title[2]->$lang ?>"><a class="dropdown-item small" href="#" data-font="-">
                                        <?= $header->top_bar[4]->$lang ?>
                                        <i class="fas fa-minus"></i>
                                    </a></li>
                                <li title="<?= $header->top_bar[4]->title[3]->$lang ?>"><a class="dropdown-item small contrast" href="#"><i class="fas fa-adjust"></i> </a></li>

                                <!-- Font change setting -->
                                <div class="font-menu position-relative" title="Font style">
                                    <button type="button" class="btn btn-sm ms-1">
                                        <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/fontchange.png') ?>" alt="Font change icon" width="24" height="24">
                                    </button>
                                    <ul class="font-menu__content bg-light p-3">
                                        <li><a class="small" href="#" data-fontname="Roboto">
                                                <?= $header->top_bar[9]->font_family[0]->$lang ?>
                                            </a></li>
                                        <li><a class="small" href="#" data-fontname="monospace">
                                                <?= $header->top_bar[9]->font_family[1]->$lang ?>
                                            </a></li>
                                        <li><a class="small" href="#" data-fontname="serif">
                                                <?= $header->top_bar[9]->font_family[2]->$lang ?>
                                            </a></li>
                                        <li><a class="small" href="#" data-fontname="cursive">
                                                <?= $header->top_bar[9]->font_family[3]->$lang ?>
                                            </a></li>
                                    </ul>
                                </div>

                            </ul>
                        </div>

                        <a class="screen-reader p-2 me-1" href="<?= base_url('site/screen_reader') ?>" title="<?= $header->top_bar[5]->$lang ?>">
                            <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/screen_reader.png') ?>" alt="sitemap" width="16" height="16">
                        </a>

                    </div>

                    <div class="d-flex justify-content-between">

                        <!-- GIGW Modifications -->
                        <a class="track p-2 small text-uppercase fw-bold skip" href="#main-contenet">
                            <?= $header->top_bar[8]->$lang ?>
                        </a>

                        <a class="login p-2 me-1 small text-uppercase fw-bold new-login" href="#">
                            <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/login.png') ?>" alt="sitemap" height="16" width="16">

                            <?= $header->top_bar[6]->$lang ?>

                        </a>
                        <a class="track p-2 small text-uppercase fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#trackModal">
                            <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/track.png') ?>" alt="sitemap" height="16" width="16">

                            <?= $header->top_bar[7]->$lang ?>
                        </a>

                    </div>

                </section>

                <!-- Icons for mobile view -->
                <section class="w-100 border-top d-flex d-md-none flex-wrap justify-content-between">

                    <div class="dropdown language py-1">
                        <button class="btn btn-sm dropdown-toggle small" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                            <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/lang-icon.png') ?>" alt="language icon" width="20" height="20">

                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="min-width: auto;">
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/en') ?>">English</a></li>
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/as') ?>">অসমীয়া</a></li>
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/bn') ?>">বাংলা</a></li>
                        </ul>
                    </div>

                    <a class="login p-2 small new-login" href="#">
                        <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/login.png') ?>" alt="sitemap" height="16" width="16">
                    </a>
                    <a class="track p-2 small" href="#" data-bs-toggle="modal" data-bs-target="#trackModal">
                        <img height="16" class="d-inline-block" src="<?= base_url('assets/site/theme1/images/track.png') ?>" alt="sitemap" width="16">

                    </a>
                    <a class="sitemap p-1" href="<?= base_url('site/sitemap') ?>" title="<?= $header->top_bar[2]->$lang ?>">
                        <img height="16" class="d-inline-block" src="<?= base_url('assets/site/theme1/images/sitemap.png') ?>" alt="sitemap" width="16">
                    </a>

                    <div class="dropdown text-settings" title="<?= $header->top_bar[3]->$lang ?>">
                        <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img height="16" src="<?= base_url('assets/site/theme1/images/text_settings.png') ?>" alt="text settings" width="16">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li title="<?= $header->top_bar[4]->title[0]->$lang ?>"><a class="dropdown-item small" href="#" data-font="+">
                                    <?= $header->top_bar[4]->$lang ?>
                                    <i class="fas fa-plus"></i>
                                </a></li>
                            <li title="<?= $header->top_bar[4]->title[1]->$lang ?>"><a class="dropdown-item small" href="#" data-font="0">
                                    <?= $header->top_bar[4]->$lang ?>
                                </a></li>
                            <li title="<?= $header->top_bar[4]->title[2]->$lang ?>"><a class="dropdown-item small" href="#" data-font="-">
                                    <?= $header->top_bar[4]->$lang ?>
                                    <i class="fas fa-minus"></i>
                                </a></li>
                            <li title="<?= $header->top_bar[4]->title[3]->$lang ?>"><a class="dropdown-item small contrast" href="#"><i class="fas fa-adjust"></i> </a></li>

                            <!-- Font change setting -->
                            <div class="font-menu position-relative" title="Font style" tabindex="0">
                                <button type="button" class="btn btn-sm ms-1">
                                    <img height="24" class="d-inline-block" src="<?= base_url('assets/site/theme1/images/fontchange.png') ?>" alt="Font change icon" width="24">
                                </button>
                                <ul class="font-menu__content bg-light p-3">
                                    <li><a class="small" href="#" data-fontname="Roboto">
                                            <?= $header->top_bar[9]->font_family[0]->$lang ?>
                                        </a></li>
                                    <li><a class="small" href="#" data-fontname="monospace">
                                            <?= $header->top_bar[9]->font_family[1]->$lang ?>
                                        </a></li>
                                    <li><a class="small" href="#" data-fontname="serif">
                                            <?= $header->top_bar[9]->font_family[2]->$lang ?>
                                        </a></li>
                                    <li><a class="small" href="#" data-fontname="cursive">
                                            <?= $header->top_bar[9]->font_family[3]->$lang ?>
                                        </a></li>
                                </ul>
                            </div>

                        </ul>
                    </div>

                    <a class="screen-reader px-1 py-1" href="<?= base_url('site/screen_reader') ?>" title="<?= $header->top_bar[5]->$lang ?>">
                        <img height="16" class="d-inline-block" src="<?= base_url('assets/site/theme1/images/screen_reader.png') ?>" alt="sitemap" width="16">
                    </a>

                    <!-- GIGW Modifications -->
                    <a class="track p-2 small text-uppercase fw-bold" href="#main-contenet" style="border-right: 0;">
                        <?= $header->top_bar[8]->$lang ?>
                    </a>

                    <!-- <a class="nav-link search text-white" style="margin-top: -5px;" href="#"> <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/search.png') ?>" alt="search" width="16"></a> -->
                </section>


            </section>
        </div>

        <!-- Brand Logos -->
        <section class="container-fluid py-2">
            <div class="container d-flex justify-content-between align-items-center">

                <div class="d-flex justify-content-md-between position-relative">
                    <img width="64" class="d-md-inline-block" src="<?= base_url('assets/site/theme1/images/emblem.png') ?>" style="transform: translateX(-10px);" id="emblem" alt="Emblem" height="80">

                    <div class="heading-text">
                        <h2 class="emb fw-bold mb-0 text-uppercase"> <?= $header->brand_logos->h2->$lang ?> </h2>
                        <!-- <h3 class="emb fw-bold"><?= $header->brand_logos->h3->$lang ?></h3> -->
                        <h5 class="mb-0 fst-italic fw-bolder">
                            <?= $header->brand_logos->h5->$lang ?>
                        </h5>
                        <p class="mb-0 fw-light">
                            <?= $header->brand_logos->p->$lang ?>
                        </p>
                    </div>

                </div>

                <img class="img-fluid" src="<?= base_url('assets/site/theme1/images/xohari.webp') ?>" id="xohari" alt="Xohari" width="64" height="64">

            </div>
        </section>


        <!-- Main Navbar -->
        <nav class="navbar navbar-expand-md container-fluid py-0 toprimbar">
            <div class="container">

                <a class="navbar-brand p-2" href="<?= base_url('site') ?>">
                    <img src="<?= base_url('assets/site/theme1/images/home.png') ?>" alt="Home" width="16" height="16">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" style="color: #ffd303;"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav text-uppercase">

                        <a class="nav-link text-white me-md-2" href="<?= base_url('site/about') ?>">
                            <?= $header->nav_links[0]->$lang ?>
                        </a>

                        <!-- RTPS Services Links -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $header->nav_links[1]->$lang ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/artps_services') ?>">
                                        <?= $header->nav_links[1]->sub_links[2]->$lang ?>

                                    </a>
                                </li>
                                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/online') ?>">
                                        <?= $header->nav_links[1]->sub_links[3]->$lang ?>
                                    </a>
                                </li>

                                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/artps_services/pfc') ?>">
                                        <?= $header->nav_links[1]->sub_links[0]->$lang ?>
                                    </a>
                                </li>
                                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/artps_services/csc') ?>">
                                        <?= $header->nav_links[1]->sub_links[1]->$lang ?>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <!-- RTPS Dashboard Link -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $header->nav_links[4]->$lang ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard') ?>">
                                        <?= $header->nav_links[4]->sub_links[0]->$lang ?>
                                    </a>
                                </li>
                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                                        <?= $header->nav_links[4]->sub_links[1]->$lang ?>
                                    </a></li>
                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                                        <?= $header->nav_links[4]->sub_links[2]->$lang ?>
                                    </a></li>

                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard/dlogin') ?>">
                                        <?= $header->nav_links[4]->sub_links[3]->$lang ?>
                                    </a></li>

                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                                        <?= $header->nav_links[4]->sub_links[4]->$lang ?>
                                    </a></li>

                                <li><a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                                        <?= $header->nav_links[4]->sub_links[5]->$lang ?>

                                    </a></li>

                            </ul>
                        </li>

                        <a class="nav-link text-white me-md-2" href="<?= base_url('site/docs') ?>">
                            <?= $header->nav_links[2]->$lang ?>
                        </a>
                        <a class="nav-link text-white me-md-2" href="<?= base_url('site/contact') ?>">
                            <?= $header->nav_links[3]->$lang ?>
                        </a>

                        <!-- <a class="nav-link p-2 text-white search d-none d-md-inline-block" href="#"> <img src="<?= base_url('assets/site/theme1/images/search.png') ?>" alt="search" width="16"></a> -->

                    </div>
                </div>

            </div>
        </nav>

    </header>


    <!-- Website content search -->
    <section class="container-fluid my-2" id="el-search-form" style="display:none !important">

        <form action="<?= base_url('site/elk_search') ?>" method="get" class="d-flex justify-content-md-end align-items-baseline container">

            <label for="el-search" class="d-none d-md-inline-block">
                <img src="<?= base_url('assets/site/theme1/images/esearch.png') ?>" alt="Serices icon" height="20" width="20">
            </label>

            <input autocomplete="off" class="w-50" type="search" name="query" id="el-search" placeholder="<?= $header->search_bar->$lang ?>" required title="Enter anything to search">

            <button type="submit" class="btn d-inline-block d-md-none">
                <i class="fas fa-search" style="color: #ffd303;"></i>
            </button>

        </form>
    </section>



    <!-- Track Model -->

    <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModal" aria-hidden="true" data-bs-keyboard="true">

        <div class="modal-dialog">
            <div class="track-dark modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3><?= $header->track_model->text->{$lang} ?></h3>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url('site/trackappstatus') ?>" class="btn btn-rtps">
                        <?= $header->track_model->o_btn->{$lang} ?>
                    </a>
                    <a href="<?= base_url('site/trackappealstatus') ?>" class="btn btn-rtps">
                        <?= $header->track_model->ap_btn->{$lang} ?>
                    </a>
                    <a href="<?= base_url('grm/trackstatus') ?>" class="btn btn-rtps">
                        <?= $header->track_model->gr_btn->{$lang} ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal Ends -->


    <!-- Login Model -->

    <div class="modal fade" id="loginModel" tabindex="-1" aria-labelledby="loginModel" aria-hidden="true" data-bs-keyboard="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="login-model modal-header">
                    <h5 class="modal-title"><?= $header->login_model->header_text->{$lang} ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="login-model modal-body p-0"></div>
                <div class="login-model modal-footer">
                    <button type="button" class="btn btn-rtps" data-bs-dismiss="modal">
                        <?= $header->login_model->c_btn->{$lang} ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal Ends -->


    <!-- Generic Model -->
    <div class="modal fade" id="footerModal" tabindex="-1" aria-labelledby="footerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="footerModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rtps" data-bs-dismiss="modal">
                        <?= $header->login_model->c_btn->$lang ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Login Popup -->
    <div class="modal fade" id="newLoginModel" tabindex="-1" aria-labelledby="newLoginModel" aria-hidden="true" data-bs-keyboard="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><?= $header->new_login_model->header_text->$lang ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-between py-4 gap-3">
                    <a href="<?= base_url('iservices') ?>" class="btn rtps-btn fw-bold">
                        <?= $header->new_login_model->t_btn->$lang ?>
                    </a>
                    <button type="button" class="btn rtps-btn fw-bold servicePluslogin" data-bs-dismiss="modal">
                        <?= $header->new_login_model->o_btn->$lang ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal Ends -->