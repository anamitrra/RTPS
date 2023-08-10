<?php
$lang = $this->lang;
// pre($header);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        // || (window.location.origin.indexOf('rtps.assam.gov.in') > -1)
        if ((window.location.href.indexOf('www') > -1)) {

            window.location = "https://sewasetu.assam.gov.in/";
        }
    </script>
    <meta charset="utf-8">
    <title><?= $header->page_title->$lang ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="SewaSetu, SewaSetu Assam, Sewa Set, Sewa Setu Assam, Right to Public Service Portal, Assam Right to Public Services Portal" name="keywords">
    <meta content="SewaSetu is an initiative of the Government of Assam to deliver the Government services at the doorsteps of the citizens." name="description">
    <meta content="National Informatics Centre, Assam" name="author">
    <!-- Social Media-->
    <meta property="og:title" content="SewaSetu Assam">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sewasetu.assam.gov.in/">
    <meta property="og:image" content="<?= base_url('assets/site/theme1/images/xohari.png') ?>">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:height" content="200">
    <meta property="og:image:width" content="200">
    <meta property="og:description" content="SewaSetu is an initiative of the Government of Assam to deliver the Government services at the doorsteps of the citizens.">

    <meta name="twitter:title" content="SewaSetu Assam ">
    <meta name="twitter:description" content="SewaSetu, Assam.">
    <meta name="twitter:image" content="<?= base_url('assets/site/theme1/images/xohari.png') ?>">
    <meta name="twitter:site" content="@accsdp_assam">
    <meta name="twitter:card" content="summary_large_image">
    <!-- Favicon -->
    <link href="<?= base_url('assets/site/sewasetu/assets/images/favicon.png') ?>" rel="icon">

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
    <!-- Customized Stylesheet -->
    <link href="<?= base_url('assets/site/sewasetu/assets/css/style.css') ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/chatbot/css/bot.css') ?>">

</head>

<body>


    <!-- Checking JS support in the Browser -->
    <noscript>
        <h1 class="display-6 alert alert-danger">Please Enable Javascript to Use this Website.</h1>
    </noscript>

    <!-- Topbar Start -->
    <div class="container-fluid top-panel">
        <section class="container p-0 px-md-3 d-flex justify-content-between align-items-stretch flex-wrap">

            <div class="py-1 govt-text">
                <img class="d-inline-block me-1" src="<?= base_url('assets/site/theme1/images/govtofindia.png') ?>" alt="indian flag" width="16">
                <span class="small text-uppercase govt-title"><?= $header->top_bar[0]->$lang ?></span>
            </div>

            <!-- GIGW Modifications -->
            <section class="top-align d-none d-md-flex align-items-stretch">

                <div class="d-flex justify-content-between">

                    <div class="top1 dropdown language me-1">
                        <button class="btn btn-sm dropdown-toggle pb-0 pt-2 small text-uppercase fw-bold topbt2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $header->top_bar[1]->$lang ?> </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="min-width: auto;">
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/en') ?>">English</a></li>
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/as') ?>">অসমীয়া</a></li>
                            <li><a class="dropdown-item small" href="<?= base_url('site/lang/bn') ?>">বাংলা</a></li>
                        </ul>
                    </div>

                    <a class="top2 sitemap p-2 me-1" href="<?= base_url('site/sitemap') ?>" title="<?= $header->top_bar[2]->$lang ?>">
                        <i class="bi bi-diagram-3-fill"></i>
                    </a>

                    <div class="top3 dropdown text-settings me-1" title="Font settings">
                        <button class="btn btn-sm dropdown-toggle pb-0 pt-2 topbt1" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-text-height" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li title="Increase font"><a class="dropdown-item small" href="#" data-font="+">
                                    <?= $header->top_bar[4]->$lang ?> <i class="fa fa-plus"></i>
                                </a></li>
                            <li title="Reset font"><a class="dropdown-item small" href="#" data-font="0">
                                    <?= $header->top_bar[4]->$lang ?> </a></li>
                            <li title="Decrease font"><a class="dropdown-item small" href="#" data-font="-">
                                    <?= $header->top_bar[4]->$lang ?> <i class="fa fa-minus"></i>
                                </a></li>
                            <li title="Change contrast"><a class="dropdown-item small contrast" href="#"><i class="fa fa-adjust"></i> </a></li>

                            <!-- Font change setting -->
                            <div class="font-menu position-relative" title="Font style">
                                <button type="button" class="btn btn-sm ms-1">
                                    <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/fontchange.png') ?>" alt="Font change icon" width="24">
                                </button>
                                <ul class="font-menu__content bg-light p-3">
                                    <li><a class="small" href="#" data-fontname="Nunito">
                                            <?= $header->top_bar[9]->font_family[0]->$lang ?> </a></li>
                                    <li><a class="small" href="#" data-fontname="monospace">
                                            <?= $header->top_bar[9]->font_family[1]->$lang ?> </a></li>
                                    <li><a class="small" href="#" data-fontname="serif">
                                            <?= $header->top_bar[9]->font_family[2]->$lang ?> </a></li>
                                    <li><a class="small" href="#" data-fontname="cursive">
                                            <?= $header->top_bar[9]->font_family[3]->$lang ?> </a></li>
                                </ul>
                            </div>

                        </ul>
                    </div>

                    <a class="screen-reader p-2 me-1" href="<?= base_url('site/screen_reader') ?>" title="<?= $header->top_bar[5]->$lang ?>">
                        <i class="bi bi-eyeglasses"></i>
                    </a>

                </div>

                <div class="d-flex justify-content-between">

                    <!-- GIGW Modifications -->
                    <a class="track p-2 small text-uppercase fw-bold skip" href="#main-contenet">
                        <?= $header->top_bar[8]->$lang ?> </a>

                    <!-- New Iservices Login -->
                    <a target="_blank" rel="noopener noreferrer" class="track p-2 small text-uppercase fw-bold " href="<?= base_url('iservices/login') ?>">
                        <i class="fa fa-user-plus fa-lg" aria-hidden="true"></i>
                        <?= $header->top_bar[6]->$lang ?>

                    </a>

                    <a class="track p-2 small text-uppercase fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#trackModal">
                        <i class="fa fa-subway" aria-hidden="true"></i>

                        <?= $header->top_bar[7]->$lang ?> </a>

                </div>

            </section>


            <!-- ////TO DO  -->
            <!-- Icons for mobile view -->
            <section class="w-100 border-top d-flex d-md-none flex-wrap justify-content-between">

                <div class="dropdown language py-1">
                    <button class="btn btn-sm dropdown-toggle small" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                        <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/lang-icon.png') ?>" alt="language icon" width="20">

                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="min-width: auto;">
                        <li><a class="dropdown-item small" href="<?= base_url('site/lang/en') ?>">English</a></li>
                        <li><a class="dropdown-item small" href="<?= base_url('site/lang/as') ?>">অসমীয়া</a></li>
                        <li><a class="dropdown-item small" href="<?= base_url('site/lang/bn') ?>">বাংলা</a></li>
                    </ul>
                </div>

                <!-- New Iservices Login -->
                <a target="_blank" rel="noopener noreferrer" class="track p-2 small text-uppercase fw-bold " href="<?= base_url('iservices/login') ?>">
                    <i class="fa fa-user-plus fa-lg" aria-hidden="true"></i>

                </a>


                <a class="track p-2 small" href="#" data-bs-toggle="modal" data-bs-target="#trackModal">
                    <i class="fa fa-subway fa-lg" aria-hidden="true"></i>

                </a>
                <a class="sitemap p-1" href="<?= base_url('site/sitemap') ?>" title="Site map">
                    <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/sitemap.png') ?>" alt="sitemap" width="16">
                </a>

                <div class="dropdown text-settings" title="Font settings">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url('assets/site/theme1/images/text_settings.png') ?>" alt="text settings" width="16">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li title="Increase font"><a class="dropdown-item small" href="#" data-font="+">
                                A <i class="fa fa-plus"></i>
                            </a></li>
                        <li title="Reset font"><a class="dropdown-item small" href="#" data-font="0">
                                A </a></li>
                        <li title="Decrease font"><a class="dropdown-item small" href="#" data-font="-">
                                A <i class="fa fa-minus"></i>
                            </a></li>
                        <li title="Change contrast"><a class="dropdown-item small contrast" href="#"><i class="fa fa-adjust"></i> </a></li>

                        <!-- Font change setting -->
                        <div class="font-menu position-relative" title="Font style" tabindex="0">
                            <button type="button" class="btn btn-sm ms-1">
                                <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/fontchange.png') ?>" alt="Font change icon" width="24">
                            </button>
                            <ul class="font-menu__content bg-light p-3">
                                <li><a class="small" href="#" data-fontname="Roboto">
                                        Default </a></li>
                                <li><a class="small" href="#" data-fontname="monospace">
                                        Monospace </a></li>
                                <li><a class="small" href="#" data-fontname="serif">
                                        Serif </a></li>
                                <li><a class="small" href="#" data-fontname="cursive">
                                        Cursive </a></li>
                            </ul>
                        </div>

                    </ul>
                </div>

                <a class="screen-reader px-1 py-1" href="<?= base_url('site/screen_reader') ?>" title="Screen reader">
                    <i class="bi bi-eyeglasses"></i>
                </a>

                <!-- GIGW Modifications -->
                <a class="track p-2 small text-uppercase fw-bold" href="#main-contenet" style="border-right: 0;">
                    Skip to Main Content </a>
            </section>


        </section>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-rim navbar-light shadow sticky-top p-0">
        <div class="d-flex justify-content-md-between position-relative">
            <img class="d-md-inline-block" src="<?= base_url('assets/site/sewasetu/assets/images/indiaemblem.png') ?>" id="emblem" alt="Emblem" height="65">

            <div class="heading-text">
                <h2 class="emb fw-bold mb-0"> <?= $header->brand_logos->h2->$lang ?> </h2>
                <h6 class="mb-0 fst-italic">
                    <?= $header->brand_logos->h5->$lang ?> </h6>
            </div>

            <a href="<?= base_url('site/') ?>" class="stretched-link"></a>

        </div>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="<?= base_url('site') ?>" class="nav-item nav-link active">
                    <?= $header->nav_links[0]->$lang ?>
                </a>
                <a href="<?= base_url('site/about') ?>" class="nav-item nav-link">
                    <?= $header->nav_links[1]->$lang ?>
                </a>

                <!-- Services -->
                <div class="nav-item dropdown dropdown-mega position-static">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <?= $header->nav_links[2]->$lang ?>
                    </a>

                    <!-- Mega Menu -->
                    <div class="dropdown-menu fade-down m-0 shadow">
                        <div class="mega-content px-4">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-sm-4 col-md-3 py-4">
                                        <h5><?= $header->nav_links[2]->menu_headings[0]->title->$lang ?></h5>
                                        <div class="list-group">
                                            <a class="list-group-item" href="<?= base_url('site/artps_services') ?>">
                                                <?= $header->nav_links[2]->sub_links[2]->$lang ?>
                                            </a>
                                            <a class="list-group-item" href="<?= base_url('site/online') ?>">
                                                <?= $header->nav_links[2]->sub_links[3]->$lang ?>
                                            </a>
                                            <a class="list-group-item" href="<?= base_url('site/artps_services/pfc') ?>">
                                                <?= $header->nav_links[2]->sub_links[0]->$lang ?>
                                            </a>
                                            <a class="list-group-item" href="<?= base_url('site/artps_services/csc') ?>">
                                                <?= $header->nav_links[2]->sub_links[1]->$lang ?>
                                            </a>

                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-3 py-4">
                                        <h5><?= $header->nav_links[2]->menu_headings[1]->title->$lang ?></h5>
                                        <div class="card">
                                            <img src="<?= base_url('assets/site/sewasetu/assets/images/rim-menuimage1.jpg') ?>" class="img-fluid" alt="image">
                                            <div class="card-body">
                                                <!-- <p class="card-text">
                                                    <a class="text-decoration-none" href="<?= base_url('site/online/basundhara') ?>">
                                                        <?= $header->nav_links[2]->menu_headings[1]->content->$lang ?>
                                                    </a>
                                                </p> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-3 py-4">
                                        <h5><?= $header->nav_links[2]->menu_headings[2]->title->$lang ?></h5>
                                        <div class="list-group popular_services">
                                            <div class="spinner-border" role="status" id="loading">
                                                <span class="sr-only">Loading...</span>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-3 py-4">
                                        <h5><?= $header->nav_links[2]->menu_headings[3]->title->$lang ?></h5>
                                        <div class="list-group">
                                            <a class="list-group-item" href="<?= base_url('site/online') ?>">
                                                <?= $header->nav_links[2]->menu_headings[3]->content[0]->$lang ?>
                                            </a>
                                            <a class="list-group-item" href="<?= base_url('site/online') ?>">
                                                <?= $header->nav_links[2]->menu_headings[3]->content[1]->$lang ?>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Documents -->
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <?= $header->nav_links[3]->$lang ?>
                    </a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="<?= base_url('site/docs') ?>" class="dropdown-item">
                            <?= $header->nav_links[3]->sub_links[0]->$lang ?>
                        </a>
                        <a href="<?= base_url('site/docs') ?>" class="dropdown-item"> <?= $header->nav_links[3]->sub_links[1]->$lang ?></a>
                        <a href="<?= base_url('site/docs') ?>" class="dropdown-item"> <?= $header->nav_links[3]->sub_links[2]->$lang ?></a>
                        <a href="<?= base_url('site/docs') ?>" class="dropdown-item"> <?= $header->nav_links[3]->sub_links[3]->$lang ?></a>
                        <a href="<?= base_url('site/docs') ?>" class="dropdown-item"> <?= $header->nav_links[3]->sub_links[4]->$lang ?></a>
                    </div>
                </div> -->

                <a href="<?= base_url('site/docs') ?>" class="nav-item nav-link">
                    <?= $header->nav_links[3]->$lang ?>
                </a>

                <!-- RTPS Dashboard Link -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $header->nav_links[5]->$lang ?> </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard') ?>">
                            <?= $header->nav_links[5]->sub_links[0]->$lang ?> </a> </a>
                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard/login') ?>">
                            <?= $header->nav_links[5]->sub_links[1]->$lang ?> </a>
                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard/login') ?>">
                            <?= $header->nav_links[5]->sub_links[2]->$lang ?> </a>

                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard/dlogin') ?>">
                            <?= $header->nav_links[5]->sub_links[3]->$lang ?> </a>

                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard/login') ?>">
                            <?= $header->nav_links[5]->sub_links[4]->$lang ?> </a>

                        <a target="_blank" rel="noopener noreferrer" class="dropdown-item text-capitalize dash" href="<?= base_url('dashboard/login') ?>">
                            <?= $header->nav_links[5]->sub_links[5]->$lang ?>
                        </a>

                    </div>
                </div>

                <a href="<?= base_url('site/contact') ?>" class="nav-item nav-link">
                    <?= $header->nav_links[4]->$lang ?>
                </a>
            </div>
            <div class="rim-parallel"><a href="<?= base_url('site/') ?>" class="second-rim-logo px-lg-5 d-none d-lg-block"><img src="<?= base_url('assets/site/sewasetu/assets/images/SS_LOGO.png') ?>" alt="Emblem" style="height:65px;"></a></div>
        </div>
    </nav>
    <!-- Navbar End -->




    <!-- Website content search -->
    <section class="container-fluid my-2" id="el-search-form" style="display:none !important">

        <form action="<?= base_url('site/elk_search') ?>" method="get" class="d-flex justify-content-md-end align-items-baseline container">

            <label for="el-search" class="d-none d-md-inline-block">
                <img src="<?= base_url('assets/site/theme1/images/esearch.png') ?>" alt="Serices icon" height="20">
            </label>

            <input autocomplete="off" class="w-50" type="search" name="query" id="el-search" placeholder="<?= $header->search_bar->$lang ?>" required title="Enter anything to search">

            <button type="submit" class="btn d-inline-block d-md-none">
                <i class="fas fa-search" style="color: #ffd303;"></i>
            </button>

        </form>
    </section>
    <!-- Elastic search -->


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
                    <a href="<?= base_url('site/trackappealstatus') ?>" class="btn rtps-btn-alt">
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
        <div class="modal-dialog modal-dialog-scrollable modal-sm">
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
    <!-- Modal Ends -->

    <!-- New Login Popup -->
    <div class="modal fade" id="newLoginModel" tabindex="-1" aria-labelledby="newLoginModel" aria-hidden="true" data-bs-keyboard="true">

        <div class="modal-dialog">
            <div class="modal-content logmodal">
                <div class="modal-header">
                    <h6 class="modal-title"><?= $header->new_login_model->header_text->$lang ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- New Login Tabs -->
                <div class="modal-body">

                    <!-- Official Logins -->
                    <details id="nav-official" open>
                        <summary class="bg-info fw-bold p-2 rounded text-center teal my-0">
                            <?= $header->new_login_model->official_login->$lang ?>
                        </summary>

                        <ul>
                            <?php foreach ($header->new_login_model->offcial_list  as $key => $item) : ?>

                                <li class="list-unstyled my-2">
                                    <input type="radio" class="form-check-input me-2" name="officeLogin" id="offL<?= $key + 1 ?>">
                                    <label class="form-check-label" for="offL<?= $key + 1 ?>"><?= $item->$lang ?></label>
                                </li>

                            <?php endforeach; ?>
                        </ul>

                        <button style="font-size: smaller;" type="button" id="officialLogin" class="btn rtps-btn-alt text-uppercase d-inline-block mt-2"><?= $header->new_login_model->p_btn->$lang ?></button>

                    </details>



                </div>

            </div>
        </div>

    </div>
    <!-- Modal Ends -->