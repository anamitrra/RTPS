<?php 
$lang = $this->lang; 
$track_by = set_value("track_by"); 
?>
<script src="<?= base_url('assets/site/'.$theme.'/js/page_loader.js') ?>" defer></script>
<script src="<?= base_url('assets/site/'.$theme.'/js/trackstatus_view.js') ?>" defer></script>
<style type="text/css">
    .loading {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 100px;
        height: 100px;
        margin: 10px auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    td {
        padding: 10px auto !important;
    }
</style>
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

<main id="main-contenet">
    <section class="container citizen-iframe">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-2">
            <ol class="breadcrumb">
                <?php foreach ($data->nav as $key => $link): ?>
                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?> >
                        <?php if (isset($link->url)): ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>
                        <?php else: ?>
                            <?= $link->$lang ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white">Track Status</span>
            </div>
            <div class="card-body">
                <form id="trackfrm" action="<?=base_url('site/trackstatus')?>" method="post">
                    <div id="refno_div" class="row mt-4">
                        <div class="col-md-6">
                            <label for="reference_number" class="col-form-label">Application Reference Number <span class="text-danger">*</span> </label>
                        </div>
                        <div class="col-md-6">
                            <input id="reference_number" name="reference_number" value="" class="form-control" type="text" />
                        </div>
                    </div>

                    <div class="row mt-5 mb-2">
                        <div class="col-md-12 text-center">
                            <button type="button" id="track_status_btn" class="btn btn-outline-success mr-2 rounded-2">
                                <i class="fa fa-check"></i> TRACK 
                            </button>
                            <button type="button" class="btn btn-outline-danger mr-2 rounded-2">
                                <i class="fa fa-user-times"></i> RESET 
                            </button>
                        </div>

                    </div>
                </form>
                <div class="row table-responsive" id="details_div"></div>
            </div>
        </div><!--End of .card-->
    </section>
</main>