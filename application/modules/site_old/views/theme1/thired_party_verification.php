<?php
$lang = $this->lang;
// pre($lang);
?>

<script src="<?= base_url('assets/site/' . $theme . '/js/page_loader.js') ?>" defer></script>
<script src="<?= base_url('assets/site/' . $theme . '/js/trackstatus_view.js') ?>" defer></script>
<style type="text/css">
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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
                <?php foreach ($settings->nav as $key => $link) : ?>
                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>
                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>
                        <?php else : ?>
                            <?= $link->$lang ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?= $settings->card_header->$lang ?></span>
            </div>
            <div class="card-body px-0 py-0">

                <div class="accordion" id="accordionExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bolder" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?= $settings->form_label_heading[0]->$lang ?> </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">

                                <form action="#" method="post">
                                    <input type="hidden" name="db" value="mis">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="reference_number" class="col-form-label"><?= $settings->form_label[0]->$lang ?><span class="text-danger">*</span> </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="reference_number" name="reference_number" value="" class="form-control" type="text" required minlength="3" autofocus autocomplete="on" />
                                        </div>
                                    </div>

                                    <div class="row mt-5 mb-2">
                                        <div class="text-center">

                                            <!-- Loader -->
                                            <button class="btn btn-outline-primary btn-lg d-none" type="button" disabled>
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                <?= $settings->btn[3]->$lang ?>
                                            </button>

                                            <div class="form_action">
                                                <button type="submit" class="btn btn-outline-success me-2 rounded-2">
                                                    <i class="fa fa-check"></i> <?= $settings->btn[0]->$lang ?>
                                                </button>
                                                <button type="reset" class="btn btn-outline-danger rounded-2">
                                                    <i class="fa fa-user-times"></i> <?= $settings->btn[1]->$lang ?>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bolder" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?= $settings->form_label_heading[1]->$lang ?>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">

                                <form action="#" method="post">
                                    <input type="hidden" name="db" value="iservices">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="consumer_no_water" class="col-form-label"><?= $settings->form_label[1]->$lang ?><span class="text-danger">*</span> </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="consumer_no_water" name="consumer_no_water" value="" class="form-control" type="text" required minlength="3" autofocus autocomplete="on" />
                                        </div>
                                    </div>

                                    <div class="row mt-5 mb-2">
                                        <div class="text-center">

                                            <!-- Loader -->
                                            <button class="btn btn-outline-primary btn-lg d-none" type="button" disabled>
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                <?= $settings->btn[3]->$lang ?>
                                            </button>

                                            <div class="form_action">
                                                <button type="submit" class="btn btn-outline-success me-2 rounded-2">
                                                    <i class="fa fa-check"></i> <?= $settings->btn[0]->$lang ?>
                                                </button>
                                                <button type="reset" class="btn btn-outline-danger rounded-2">
                                                    <i class="fa fa-user-times"></i> <?= $settings->btn[1]->$lang ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bolder" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?= $settings->form_label_heading[2]->$lang ?>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">

                                <form action="#" method="post">
                                    <input type="hidden" name="db" value="ext">
                                    <input type="hidden" name="service" value="apdcl1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="consumer_no_apdcl" class="col-form-label"><?= $settings->form_label[2]->$lang ?><span class="text-danger">*</span> </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="consumer_no_apdcl" name="consumer_no_apdcl" value="" class="form-control" type="text" required minlength="3" autofocus autocomplete="on" />
                                        </div>
                                    </div>

                                    <div class="row mt-5 mb-2">
                                        <div class="text-center">

                                            <!-- Loader -->
                                            <button class="btn btn-outline-primary btn-lg d-none" type="button" disabled>
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                <?= $settings->btn[3]->$lang ?>
                                            </button>

                                            <div class="form_action">
                                                <button type="submit" id="track_status_btn_3" class="btn btn-outline-success me-2 rounded-2">
                                                    <i class="fa fa-check"></i> <?= $settings->btn[0]->$lang ?>
                                                </button>
                                                <button type="reset" class="btn btn-outline-danger rounded-2">
                                                    <i class="fa fa-user-times"></i> <?= $settings->btn[1]->$lang ?>
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--End of .card-->

        <!-- Model to display Application data -->
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="thirdPartyVerModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0">
                        <div class="row table-responsive" id="details_div"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= $settings->btn[2]->$lang ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>

<script>
    var notFound = "<?= $settings->not_found->$lang ?>";
    var invalidInput = "<?= $settings->invalid_input->$lang ?>";
    var baseURL = "<?= base_url('site/') ?>";
</script>