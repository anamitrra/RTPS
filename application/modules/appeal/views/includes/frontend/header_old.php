<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appeal Management System</title>

    <!--Bootstrap 4.5-->
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/hover.css') ?>">
    <!--Fontawesome-->
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
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

    <!--Jquery-->
    <script type="text/javascript"
            src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!--Popper-->
    <script type="text/javascript"
            src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/popper.min.js') ?>"></script>
    <!--Bootstrap 4.5-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<!--Header-->
<div class="loader"><img src="<?= base_url('assets/frontend/images/loader.gif') ?>"></div>

<div class="loader"></div>

<!--Navigation Bar-->
<section>
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

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                   <?php
                    if($this->session->userdata('opt_status')){
                   ?>
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4">
                        <a class="nav-link text-light text-center" href="<?=base_url('appeal/logout')?>"><span>LOGOUT</span></a>
                    </li>
                    <?php
                    }
                   ?>

                </ul>
            </div>
            <span class="d-none d-md-inline navbar-brand text-dark font-weight-bold">
                        <img id="rightlogo" title="ARTPS Logo" class="img-fluid d-inline-block ml-5" alt="Logo"
                             src="<?= base_url('assets/frontend/images/logo_artps.png') ?>">
                    </span>
        </div>
    </nav>
</section>
<!--HEADER END-->