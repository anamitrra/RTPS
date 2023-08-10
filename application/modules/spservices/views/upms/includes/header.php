<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?=$header_title??'Users and Processes Management System'?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= base_url('assets/frontend/images/logo_artps.png') ?>" type="image/x-icon">
        <!-- Font Awesome -->
        <link href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url("assets/dist/css/adminlte.min.css");?>" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/custom.css"); ?>">
        <link rel="stylesheet" href="<?= base_url("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"); ?>">

        <link rel="stylesheet" href="<?= base_url("assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css"); ?>">
        <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <script src="<?= base_url("assets/plugins/jquery/jquery.min.js"); ?>"></script>
        <script src="<?= base_url("assets/plugins/jquery-ui/jquery-ui.min.js"); ?>"></script>
        <script src="<?= base_url("assets/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
        <script src="<?= base_url("assets/plugins/moment/moment.min.js"); ?>"></script>
        <script src="<?= base_url("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"); ?>"></script>
        <script src="<?= base_url("assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"); ?>"></script>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php $this->load->view("upms/includes/topbar") ?>
            <?php $this->load->view("upms/includes/sidebar") ?>