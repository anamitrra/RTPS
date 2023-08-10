<?php
$lang = $this->lang;
//pre($screen);

?>

<main id="main-contenet">
    <div class="container">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
            <ol class="breadcrumb m-0">

                <?php foreach ($screen->nav as $key => $link) : ?>

                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url)  ?>"><?= $link->$lang ?></a>

                        <?php else : ?>
                            <?= $link->$lang ?>


                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>


                <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
            </ol>
        </nav>



        <div class="row mt-4">
            <!-- <div class="col-lg-12">
                <div class="innerpage_section_title">
                    <h3><u><b><?= $screen->heading->$lang ?></u></b></h3>
                </div>
            </div> -->
            <div class="col-12 col-lg-8">


                <div class="field-content">


                    <p><?= $screen->content->$lang ?></p>
                    <p><?= $screen->next_cnt->$lang ?></p>

                    <button id="screen-btn" data-bs-toggle="modal" type="submit" class="btn rtps-btn btn-lg" data-bs-target="#screenextra">

                        <i class="fas fa-download me-2"></i>
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
</main>