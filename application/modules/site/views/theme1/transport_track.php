<?php
$lang = $this->lang;
// pre($transport_track);
?>

<script src="<?= base_url('assets/site/theme1/js/transport_track.js') ?>" defer></script>


<main id="main-contenet">
    <div class="container">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-2">
            <ol class="breadcrumb">

                <?php foreach ($transport_track->nav as $key => $link) : ?>

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

        <div class="row mt-2">
            <div class="col-12 col-lg-7 mx-auto">

                <div class="trans-card card p-3">

                    <div class="card-body">

                        <form>

                            <div class="form-floating mb-3">
                                <input type="search" id="appl-no" class="trans-form form-control" required autofocus>
                                <label for="appl-no" class="text-dark"><?= $transport_track->form_label->{$lang} ?></label>

                                <div class="form-text text-danger d-none"><?= $transport_track->incomplete_input_msg->{$lang} ?></div>
                            </div>

                            <div class="d-flex justify-content-center justify-content-md-end">


                                <button id="track-btn" type="submit" class="btn rtps-btn btn-lg">
                                    <div>
                                        <i class="fas fa-search"></i>
                                        <?= $transport_track->submit_btn->{$lang} ?>
                                    </div>

                                    <div class="d-none">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        <?= $transport_track->waiting_msg->{$lang} ?>

                                    </div>


                                </button>
                            </div>


                        </form>

                        <!-- Track error Messsage -->
                        <div class="alert alert-danger mt-5 d-none" role="alert" data-mdb-color="danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $transport_track->track_error_msg->{$lang} ?>
                        </div>


                        <!-- Track results -->

                        <section class="track-data mt-5 d-none">

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->appl_no->$lang ?></h6>
                                    <span id="applNo"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->appl_date->$lang ?></h6>
                                    <span id="applDate"></span>

                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->reg_no->$lang ?>
                                    </h6>
                                    <span id="regNo"></span>

                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->desc->$lang ?>
                                    </h6>
                                    <span id="desc"></span>

                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->reg_at->$lang ?>
                                    </h6>
                                    <span id="regAt"></span>

                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">

                                    <h6 class="text-capitalize fw-bold">
                                        <?= $transport_track->track_data->cur_status->$lang ?>
                                    </h6>
                                    <span id="curStatus" class="d-inline-flex flex-column flex-nowrap justify-content-between align-items-start"></span>

                                </li>
                            </ul>


                        </section>


                    </div>
                </div>

            </div>
        </div>


</main>


<div class="modal fade" id="externalPortal" tabindex="-1" aria-labelledby="externalPortalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="track-mod">
            <div class="modal-header">
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <?=$transport_track->modal_body->$lang?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" id="cancel"  data-bs-dismiss="modal"><?=$transport_track->modal_can->$lang?></button>

                <a href="" id="ext-link" target="_blank" rel="noopener noreferrer" class="btn btn-rtps" tabindex="-1" role="button" aria-disabled="true"><?=$transport_track->modal_pro->$lang?></a>

            </div>

        </div>
    </div>
</div>
