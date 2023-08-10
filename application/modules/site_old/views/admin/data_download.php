<?php
$lang = $this->lang;
//pre($all);
?>
<link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/css/bootstrap.min.css') ?>">
<script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.6.0.min.js"); ?>" defer></script>
<script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Download MIS Data</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Download MIS Data</li>
                    </ol>
                </div>
            </div>

            <!-- Action Success/Fail alert messages  -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error') ?? $error ?? '') : ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error') ?? $error ?? '' ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (!isset($error)) : ?>

                <main>
                    <h6 class="bg-gradient-navy rounded-1 p-2">Download Data for Department Skill, Employment and Entrepreneurship</h6>

                    <!-- Links -->

                    <ol class="list-group list-group-numbered my-4 w-50">

                        <?php foreach ($files as $f) : ?>

                            <!-- d-flex justify-content-between align-items-start -->
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto px-2 d-flex justify-content-between w-100">
                                    <span class="fw-bold"> <?= strtoupper($f) ?> </span>
                                    <a href="<?= base_url('site/admin/download_data/dl_action/' . $f) ?>">Download</a>
                                </div>

                            </li>
                        <?php endforeach; ?>


                    </ol>

                </main>

            <?php endif; ?>

        </div>
    </div>
</div>