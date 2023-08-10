<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?php echo SITE_TITLE; ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/fontawesome-free/css/all.min.css"> <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url("assets/"); ?>dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url("assets/css/"); ?>custom.css">
  <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- PAGE LEVEL STYLES-->
  <!-- jQuery -->
  <script src="<?= base_url("assets/"); ?>plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url("assets/"); ?>plugins/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?= base_url("assets/"); ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
  <script src="<?= base_url("assets/"); ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="<?= base_url("assets/"); ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <style>
    [class*='sidebar-dark-'] {
      background-color: #1a4066 !important;
      /* background-color: #343a40; */
    }

    .breadcrumb {
      padding: 5px 20px;
      margin-bottom: 10px;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php $this->load->view("includes/office_includes/topbar");
    if (!empty($this->session->userdata('role_slug'))) {
      $slug = $this->session->userdata('role_slug');
    } else {
      $role = $this->session->userdata("role");
      $slug = isset($role->slug) ? $role->slug : "";
    }
    if ($slug == 'DPS') {  ?>
      <p class="text-right m-0 p-1 "><a href="https://rtps.assam.gov.in/site/dsc" target="_blank" class="text-danger">Click here for DSC installation guidelines.</a></p>
    <?php } 
    $this->load->view("includes/office_includes/sidebar") ?>
    <!-- /.navbar -->