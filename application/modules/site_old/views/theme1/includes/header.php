<?php
$lang = $this->lang;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This is ARTPS offical state site.">
    <title><?= $header_data->page_title->{$lang} ?></title>
    <link rel="shortcut icon" href="<?= base_url('assets/site/'.$theme.'/images/favicon.ico') ?>" type="image/x-icon">

    <!-- Preconnet to external Domains First -->
    <!-- <link href="https://cdnjs.cloudflare.com" rel="preconnect" crossorigin>
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin> -->


    <!-- Common Stylesheets -->
    <link href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/site/'.$theme.'/dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/'.$theme.'/css/common.css') ?>">
    
    <!-- Common JS Files -->
    <script src="<?= base_url("assets/site/".$theme."/plugins/jquery/jquery-3.5.1.min.js"); ?>" defer></script>
    <script src="<?= base_url('assets/site/'.$theme.'/dist/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/site/'.$theme.'/js/common.js') ?>" defer></script>
    <script src="<?= base_url('assets/site/'.$theme.'/js/font-resize.js') ?>" defer></script>
    <script src="<?= base_url('assets/site/'.$theme.'/js/contrast.js') ?>" defer></script>
</head>

<body>
    <!-- Page Loader -->
    <div class="loader-container">
        <div class="bubblingG">
            <span id="bubblingG_1">
            </span>
            <span id="bubblingG_2">
            </span>
            <span id="bubblingG_3">
            </span>
        </div>
    </div>
    
    <header>
        <section class="top-panel container d-flex justify-content-between align-items-baseline flex-wrap">
            <div>
                <a href="#" class="login">
                    <i class="fas fa-user"></i>
                    <?= $header_data->top_links[0]->{$lang} ?>
                </a>
                <a href="<?= base_url('site/citizen_registration') ?>">
                    <i class="fas fa-user-plus"></i>
                    <?= $header_data->top_links[1]->{$lang} ?>
                </a>
                <a href="#" class="rtps-track" data-bs-toggle="modal" data-bs-target="#trackModal">
                    <i class="fas fa-paste"></i>
                    <?= $header_data->top_links[2]->{$lang} ?>
                </a>
                <a href="<?= base_url('site/contact') ?>">
                    <i class="fas fa-phone-square"></i>
                    <?= $header_data->top_links[3]->{$lang} ?>
                </a>
            </div>
            <!-- Settings -->
            <div>
                <a href="#main-contenet">
                    <?= $header_data->top_links[4]->{$lang} ?>
                </a>
                <a href="#" title="<?= $header_data->top_links[5]->title[0]->{$lang} ?>" data-font="+">
                    <?= $header_data->top_links[5]->{$lang} ?>
                    <i class="fas fa-plus"></i>
                </a>
                <a href="#" title="<?= $header_data->top_links[5]->title[1]->{$lang} ?>" data-font="0">
                    <?= $header_data->top_links[5]->{$lang} ?>
                </a>
                <a href="#" title="<?= $header_data->top_links[5]->title[2]->{$lang} ?>" data-font="-">
                    <?= $header_data->top_links[5]->{$lang} ?>
                    <i class="fas fa-minus"></i>
                </a>
                
                <a href="#" id="contrast" title="<?= $header_data->top_links[6]->{$lang} ?>">
                    <i class="fas fa-adjust"></i>
                </a>

                <div class="dropdown d-inline" title="<?= $header_data->top_links[7]->{$lang} ?>">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo $this->config->item($lang);
                        ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item  <?= $lang == 'en' ? 'active' : '' ?> " href="<?= base_url('site/lang/en') ?>">
                                <?= $this->config->item('en'); ?>
                            </a></li>
                        <li>
                            <a class="dropdown-item <?= $lang == 'as' ? 'active' : '' ?>" href="<?= base_url('site/lang/as') ?>">
                                <?= $this->config->item('as'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= $lang == 'bn' ? 'active' : '' ?>" href="<?= base_url('site/lang/bn') ?>">
                                <?= $this->config->item('bn'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand" href="<?= site_url('site') ?>">
                    <img src="<?= base_url($header_data->left_logo_path) ?>" width="500" alt="ARTPS Brand Logo" loading="lazy">
                </a>
                
                <!-- Toggle button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars text-white"></i>
                </button>
                <div style="flex-direction: column;">
                <div style="flex-direction: row;display: flex;justify-content: flex-end;">

                    <div class="search" style="padding-top: 14px;">
                    
                        <form id="rtps-search-form" action=<?php echo base_url('site/search') ?> method="POST">

                            <input type="search" id="rtps-search-field" list="services" autocomplete="off" size="30" name="service_name" placeholder="<?= $header_data->search->placeholder->$lang ?>" required  pattern=".{3,}" title="<?= $header_data->search->title->$lang ?>">

                        </form>


                        <datalist id="services">

                        <?php foreach ($services_list as $service): ?>
                            
                            <option value="<?= $service->service_name->$lang ?>">

                        <?php endforeach; ?>

                        </datalist>

                    </div>

                    <div style="padding-left: 10px;padding-right: 15px">
                        <!-- ARTPS Logo -->
                     <img src="<?= base_url($header_data->right_logo_path) ?>" class="nav-item" height="57" alt="ARTPS Logo" loading="lazy">
                    </div>

                </div>
               
                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                    <!-- Left links -->
                    <div class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <a class="nav-link active" aria-current="page" href="<?php echo site_url('site/index') ?>">
                        <?= $header_data->nav_links[0]->{$lang} ?>
                        </a>
                        <a class="nav-link" href="<?= base_url('site/artps_services') ?>">
                        <?= $header_data->nav_links[1]->{$lang} ?>
                        </a>
                        <a class="nav-link" href="<?= base_url("site/services") ?>">
                        <?= $header_data->nav_links[2]->{$lang} ?>
                        </a>
                        <a class="nav-link" href="<?php echo site_url('site/docs') ?>">
                        <?= $header_data->nav_links[3]->{$lang} ?>
                        </a>
                        <a class="nav-link" href="<?php echo site_url('site/about') ?>">
                        <?= $header_data->nav_links[4]->{$lang} ?>
                        </a>
                        <!-- Serach Services Icon -->
                        <!-- <a class="nav-link" href="#">
                            <span class="fas fa-search"></span>
                        </a> -->

                       
                       
                    </div>
                </div>
                </div>
                
            </div>
        </nav>
    </header>

    
<!-- Track Model -->

<div class="modal top fade" id="trackModal" tabindex="-1" aria-labelledby="trackModal" aria-hidden="true" data-bs-keyboard="true">

        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3><?= $header_data->track_model->text->{$lang} ?></h3>
            </div>
            <div class="modal-footer">
                <a href="<?= base_url('site/transport_track_page') ?>" class="btn rtps-btn btn-lg">
                <?= $header_data->track_model->t_btn->{$lang} ?>
                </a>
                <a href="<?= base_url('site/citizen_track') ?>" class="btn rtps-btn btn-lg">
                <?= $header_data->track_model->o_btn->{$lang} ?>
                </a>
            </div>
            </div>
        </div>

</div>
<!-- Modal Ends -->


<!-- Login Model -->

<div class="modal top fade" id="loginModel" tabindex="-1" aria-labelledby="loginModel" aria-hidden="true" data-bs-keyboard="true">

    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?=  $header_data->login_model->header_text->{$lang} ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn rtps-btn" data-bs-dismiss="modal">
            <?=  $header_data->login_model->c_btn->{$lang} ?>
            </button>
        </div>
        </div>
    </div>

</div>
<!-- Modal Ends -->
