<?php
$lang = $this->lang;
//pre($screen);

?>


<!-- Breadcrumb Start -->
<div class="container-fluid bg-primary mb-5 page-header">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 text-start">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                        <?php foreach ($screen->nav as $key => $link) : ?>

                            <li class="breadcrumb-item text-white <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                                <?php if (isset($link->url)) : ?>
                                    <a class="text-white" href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                                <?php else : ?>
                                    <?= $link->$lang ?>


                                <?php endif; ?>

                            </li>
                        <?php endforeach; ?>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Ends here -->


<!-- Main Content -->
<main id="main-contenet">

    <div class="container-xxl py-3">
        <div class="container extra-margin-bottom">
            <div class="row g-5">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

                    <div class="row mt-4">

                        <div class="col-12 col-lg-8">


                            <div class="field-content">


                                <p><?= $screen->content->$lang ?></p>
                                <p><?= $screen->next_cnt->$lang ?></p>

                                <button id="screen-btn" data-bs-toggle="modal" type="submit" class="btn rtps-btn btn-lg" data-bs-target="#screenextra">

                                    <i class="fa fa-download me-2 fa-2x"></i>
                                    <?= $screen->screen_btn->{$lang} ?>

                                </button>

                                <div class="modal fade" id="screenextra" tabindex="-1" aria-labelledby="screenextraLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" id="track-mod">
                                            <div class="modal-header">

                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?= $screen->modal_body->$lang ?>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn" id="cancel" data-bs-dismiss="modal"><?= $screen->modal_can->$lang ?></button>

                                                <a href="https://www.nvaccess.org/download/" id="ext-link" rel="noopener noreferrer" target="_blank" class="btn btn-rtps" tabindex="-1" role="button" aria-disabled="true"><?= $screen->modal_pro->$lang ?></a>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mt-5 mt-lg-0">
                            <div class="listpage-wrapper">
                                <div class="listpage-info">
                                    <img class="img-fluid" src="<?= base_url('assets/site/theme1/images/screenreader.png') ?>" alt="screen reader img">
                                    <div class="Listdetails_info mt-3">
                                        <h2><?= $screen->heading->$lang ?></h2>
                                        <p><?= $screen->short_cnt->$lang ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</main>