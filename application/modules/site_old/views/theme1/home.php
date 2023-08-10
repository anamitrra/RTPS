<?php
$lang = $this->lang;
//pre($last_update);
// pre($recent_services);
// pre($all_depts);
?>

<style>
    footer.mt-5 {
        margin-top: 0 !important;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/owl-carousel/css/owl.carousel.min.css') ?>">

<script src="<?= base_url('assets/site/theme1/plugins/jquery-marquee/jm.min.js') ?>" defer></script>
<script src="<?= base_url('assets/site/theme1/plugins/owl-carousel/owl.carousel.min.js') ?>" defer></script>
<script type="text/javascript" src="<?= base_url('assets/site/theme1/plugins/jquery-lazy/jquery.lazy.min.js') ?>" defer></script>
<script src="<?= base_url('assets/site/theme1/js/home.js') ?>" defer></script>


<main>

    <!-- Image slider -->
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade d-block" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover" data-bs-touch="true">
        <div class="carousel-indicators">

            <?php foreach ($settings->banners->$lang as $key => $banner) : ?>

                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $key ?>" class="<?= ($key == 0) ? 'active' : ''  ?>" aria-label="Banner <?= $key + 1 ?>"></button>

            <?php endforeach; ?>

        </div>

        <div class="carousel-inner">


            <?php foreach ($settings->banners->$lang as $key => $url) : ?>

                <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                    <img class="d-block w-100" alt="Banner <?= $key + 1 ?>" src="<?= base_url($url) ?>">
                </div>

            <?php endforeach; ?>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>

    <!-- Dashboard -->
    <section class="dashboard container-fluid py-0">
        <div class="container d-flex justify-content-between align-items-stretch flex-nowrap pb-1">

            <div class="card rcv text-center bg-transparent rounded-0 position-relative" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-html="true" title="<span><?= $settings->genders->m->{$lang}  ?> :  [m]</span><br><span><?= $settings->genders->f->{$lang}  ?> : [f]</span><br><span><?= $settings->genders->o->{$lang}  ?> : [o]</span><br><span><?= $settings->genders->na->{$lang}  ?> : [na]</span>">

                <div class="card-header">
                    <img src="<?= base_url('assets/site/theme1/images/applicationsrecieved.png') ?>" class="card-status" alt="applications recieved" height="30">
                </div>

                <div class="card-body p-0">
                    <h4 class="card-title figures">

                        <div class="spinner-grow text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </h4>
                    <p class="card-text text-white text-uppercase small">
                        <?= $settings->dashboard->grid_1->$lang ?>
                    </p>

                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>

            <div class="card pen text-center bg-transparent rounded-0 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <img src="<?= base_url('assets/site/theme1/images/applicationspending.png') ?>" class="card-status" alt="applications recieved" height="30">
                </div>

                <div class="card-body p-0">
                    <h4 class="card-title figures">

                        <div class="spinner-grow text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </h4>
                    <p class="card-text text-white text-uppercase small">
                        <?= $settings->dashboard->grid_2->$lang ?>

                    </p>

                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>
            <div class="card pentime text-center bg-transparent rounded-0 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <img src="<?= base_url('assets/site/theme1/images/applicationspendingwithintime.png') ?>" class="card-status" alt="applications recieved" height="30">
                </div>

                <div class="card-body p-0">
                    <h4 class="card-title figures">

                        <div class="spinner-grow text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </h4>
                    <p class="card-text text-white text-uppercase small">

                        <?= $settings->dashboard->grid_3->$lang ?>

                    </p>

                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>
            <div class="card dis text-center bg-transparent rounded-0 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <img src="<?= base_url('assets/site/theme1/images/applicationsdisposed.png') ?>" class="card-status" alt="applications recieved" height="30">
                </div>

                <div class="card-body p-0">
                    <h4 class="card-title figures">

                        <div class="spinner-grow text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </h4>
                    <p class="card-text text-white text-uppercase small">
                        <?= $settings->dashboard->grid_4->$lang ?>
                    </p>

                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>
            <div class="card del text-center bg-transparent rounded-0 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <img src="<?= base_url('assets/site/theme1/images/applicationstimelydelivered.png') ?>" class="card-status" alt="applications recieved" height="30">
                </div>

                <div class="card-body p-0">
                    <h4 class="card-title figures">

                        <div class="spinner-grow text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </h4>
                    <p class="card-text text-white text-uppercase small">
                        <?= $settings->dashboard->grid_5->$lang ?>
                    </p>

                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>

        </div>
    </section>


    <!-- Newly Launched -->
    <section class="bg-dark position-relative pt-2 invisible">

        <span class="fst-italic text-white small newly-launched position-absolute top-0 start-0 bottom-0 py-0 py-lg-2 ps-1">
            <?= $settings->newly_launched->title->$lang ?>
        </span>

        <div class="marquee d-inline-block w-100 overflow-hidden">

            <?php foreach ($recent_services as $key => $value) : ?>


                <a href="<?= base_url('site/service-apply/' . $value->seo_url) ?>" class="text-white text-decoration-none me-5 small">

                    <?= $value->service_name->$lang ?>

                    <sup class="bg-success text-white badge text-uppercase small">
                        <?= $settings->newly_launched->tag->$lang ?>
                    </sup>

                </a>


            <?php endforeach; ?>

        </div>

    </section>




    <!-- Search Service, Login panel -->
    <div class="container py-4">
        <div class="row">

            <!--
   <div class="col-12 d-flex justify-content-start justify-content-md-center d-none">

                    <div class="d-flex justify-content-start">
                        <img src="<?= base_url('assets/site/theme1/images/servicesicon.png') ?>" alt="Serices icon" class="service-icons" height="80">

                        <div class="ms-3 services-icon">
                            <h3 class="fw-bold"><?= $settings->service_section->title->$lang ?></h3>
                            <p class="service-p small my-0"><?= $settings->service_section->p1->$lang ?></p>
                            <p class="service-p small"><?= $settings->service_section->p2->$lang ?></p>
                        </div>
                    </div>
                </div>

-->
            <div class="col-12 d-flex justify-content-center align-items-baseline flex-wrap my-3" style="row-gap: 0.4em;">

                <a class="service-btn btn px-md-4 py-md-2 new-artps-btn text-uppercase me-2 mb-2 mb-md-0 me-md-3" href="#" role="button">
                    <?= $settings->service_section->login_btn->$lang ?>
                </a>
                <a class="service-btn btn px-md-4 py-md-2 new-artps-btn text-uppercase me-2 mb-2 mb-md-0 me-md-3" href="<?= base_url('site/citizen_registration') ?>" role="button">
                    <?= $settings->service_section->reg_btn->$lang ?>
                </a>
                <a class="service-btn btn px-md-4 py-md-2 new-artps-btn text-uppercase me-2 mb-2 mb-md-0 me-md-3" href="#" data-bs-toggle="modal" data-bs-target="#trackModal" role="button">

                    <?= $settings->service_section->track_btn->$lang ?>
                </a>
                <a class="service-btn btn px-md-4 py-md-2 new-artps-btn text-uppercase me-2 mb-2 mb-md-0 me-md-3" href="<?= $settings->service_section->appeal_btn->url ?>" target="_blank" rel="noopener noreferrer" role="button">
                    <?= $settings->service_section->appeal_btn->$lang ?>
                </a>
                <a class="service-btn btn px-md-4 py-md-2 new-artps-btn text-uppercase" href="<?= $settings->service_section->grievance_btn->url ?>" target="_blank" rel="noopener noreferrer" role="button">
                    <?= $settings->service_section->grievance_btn->$lang ?>
                </a>


            </div>
            <div class="col-12 my-4">

                <form action="<?= base_url('site/search') ?>" method="get" id="service-search" class="d-flex flex-column flex-md-row justify-content-md-center align-items-baseline">

                    <label for="search" class="search-icons d-none d-md-inline">
                        <img src="<?= base_url('assets/site/theme1/images/esearch.png') ?>" alt="Serices icon" height="20">
                    </label>

                    <input class="mb-2 mb-md-0" autocomplete="off" type="search" name="service_name" list="services" id="search" placeholder="<?= $settings->service_section->place_holder->$lang ?>" required title="<?= $settings->service_section->search_title->$lang ?>">

                    <button class="btn fw-bold align-self-stretch service-serach-btn" type="submit">
                        <?= $settings->service_section->search_btn->$lang ?>
                    </button>
                </form>

                <datalist id="services">
                    <?php foreach ($services_list as $service) : ?>

                        <option value="<?= $service->service_name->$lang ?>">

                        <?php endforeach; ?>
                </datalist>

            </div>
        </div>
    </div>

    <!-- Service Categories -->
    <div class="container">
        <div class="card-row row gy-5 g-md-5">

            <?php foreach ($categories as $key => $value) : ?>

                <div class="col-6 col-md-6 col-lg-4 d-flex justify-content-center align-items-stretch p-md-4 rounded-1 card-effect">

                    <div class="card flex-fill service-cat rounded-0 border-0 ">

                        <img data-src="<?= base_url($value->img_path) ?>" class="card-img-top img-fluid lazy" alt="<?= $value->cat_name->$lang ?>">

                        <div class="card-body">
                            <h5 class="card-title"><?= $value->cat_name->$lang ?></h5>
                            <p class="small card-text"><?= $value->tag->$lang ?></p>

                            <!-- To make the whole card clickable -->
                            <a href="<?= base_url('site/service_cat/' . $value->cat_id) ?>" class="stretched-link"></a>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </div>

    <!-- Service support -->
    <section class="container-fluid support my-5 py-4">
        <div class="container">
            <div class="row gy-3">
                <div class="col-12 col-lg-4">

                    <h4 class="fw-bold rtps-header"><?= $settings->support->col_1->title->$lang ?></h4>

                    <ul class="mt-4 mb-0 dept-list">

                        <?php foreach ($all_depts as $key => $value) : ?>

                            <li>
                                <a class="dept-text p-2 d-block small" href="<?= base_url('site/online/' . $value->department_short_name) ?>">
                                    <?= $value->department_name->$lang ?>
                                </a>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                    <p class="text-end mb-0 mt-2" style="font-size: .75em;">
                        <a class="d-inline text-decoration-none read-more-link pb-1 position-relative" href="<?= base_url('site/online') ?>">
                            <?= $settings->support->col_1->link->$lang ?>
                            <i class="fas fa-caret-right fa-sm ms-1"></i>
                        </a>

                    </p>
                </div>

                <div class="col-12 col-lg-4">

                    <h4 class="fw-bold rtps-header"><?= $settings->support->col_4->title->$lang ?></h4>

                    <ul class="mt-4 mb-0 dept-list">

                        <?php foreach ($ac as $key => $value) : ?>

                            <li>
                                <a class="dept-text p-2 d-block small" href="<?= base_url('site/online/' . $value->department_short_name) ?>">
                                    <?= $value->department_name->$lang ?>
                                </a>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

                <div class="col-12 col-lg-4">

                    <?php if (! empty($popular_services)) : ?>

                        <h4 class="fw-bold rtps-header"><?= $settings->support->col_3->title->$lang ?></h4>

                        <section class="popular-services d-flex justify-content-start flex-wrap mb-3">

                            <?php foreach ($popular_services as $key => $value) : ?>

                                <a href="<?= base_url('site/service-apply/' . $value->seo_url) ?>" class="me-1 mb-2 d-block text-truncate" data-bs-toggle="tooltip" title="<?= $value->service_name->$lang ?>">
                                    <img width="16" class="me-1" src="<?= base_url('assets/site/theme1/images/tag.png') ?>" alt="Popular service">
                                    <?= $value->service_name->$lang ?>
                                </a>

                            <?php endforeach;  ?>

                        </section>

                    <?php endif; ?>
 

                    <section class="query p-3">
                        <h6 class="fw-bold text-center mb-3"><?= $settings->support->col_3->query->$lang ?></h6>

                        <div class="d-flex justify-content-evenly align-items-baseline flex-wrap">
                            <a class="btn fw-bold px-4 py-2 mb-2 supportfaq-btn" href="<?= base_url('site/faq') ?>" role="button">
                                <?= $settings->support->col_3->faq->$lang ?>
                            </a>
                            <a class="btn fw-bold px-4 py-2 mb-2" href="<?= base_url('site/contact') ?>" role="button">
                                <?= $settings->support->col_3->contact->$lang ?>
                            </a>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </section>

    <!-- About Portal -->
    <section class="container about py-4">
        <div class="row gy-3 g-md-x-3">
            <div class="col-12 col-lg-4">

                <h4 class="fw-bold rtps-header"><?= $settings->about->portal->title->$lang ?></h4>

                <article class="mt-4" style="width:93%;">
                    <p class="dept-text small mb-2" style="text-align: justify">
                        <?= $settings->about->portal->text->$lang ?>
                    </p>

                    <p class="dept-text small" style="text-align: justify">
                        <?= $settings->about->portal->text2->$lang ?>
                    </p>

                    <p class="text-end mb-0 mt-2" style="font-size: .75em;">
                        <a class="d-inline text-decoration-none read-more-link pb-1 position-relative" href="<?= base_url('site/about') ?>">
                            <?= $settings->about->portal->link->$lang ?>
                            <i class="fas fa-caret-right fa-sm ms-1"></i>
                        </a>

                    </p>
                </article>

            </div>
            <div class="col-12 col-lg-5">

                <h4 class="fw-bold rtps-header"><?= $settings->about->rtps->title->$lang ?></h4>

                <!-- Accordion Starts -->
                <div class="accordion my-4" id="accordionPanelsStayOpenExample">

                    <?php foreach ($settings->about->rtps->downloads as $key => $value) : ?>

                        <div class="accordion-item">

                            <h6 class="accordion-header" id="panelsStayOpen-heading<?= $key + 1 ?>">
                                <button class="accordion-button text-uppercase fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?= $key + 1 ?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse<?= $key + 1 ?>">

                                    <img class="me-2" src="<?= base_url('assets/site/theme1/images/rule-book.svg') ?>" alt="rule book icon" width="24">

                                    <?= $value->heading->$lang ?>

                                </button>
                            </h6>
                            <div id="panelsStayOpen-collapse<?= $key + 1 ?>" class="accordion-collapse collapse <?= $key == 0 ? 'show' : '' ?>" aria-labelledby="panelsStayOpen-heading<?= $key + 1 ?>">
                                <div class="accordion-body">

                                    <p class="fst-italic mb-4"><?= $value->text->$lang ?></p>

                                    <a href="<?= base_url($value->link->url) ?>" class="text-uppercase text-decoration-none" target="_blank" rel="noopener noreferrer">
                                        <img class="me-2" src="<?= base_url('assets/site/theme1/images/download-icon.svg') ?>" alt="download icon" width="16">
                                        <?= $value->link->$lang ?>
                                    </a>

                                </div>
                            </div>

                        </div>


                    <?php endforeach ?>



                </div>
                <!-- Accordion End -->

            </div>
            <div class="col-12 col-lg-3">

                <h4 class="fw-bold rtps-header"><?= $settings->support->col_2->title->$lang ?></h4>

                <div class="mb-4 video-guide position-relative">
                    <a class="video-thumbnail" href="<?= base_url('site/video') ?>">

                        <img class="img-fluid rounded d-inline-block" src="<?= base_url('assets/site/theme1/images/' . $lang . '_video.png') ?>" alt="Video Tutorial" style="min-height: 260px;">

                        <img class="video-play-btn img-fluid position-absolute" src="<?= base_url('assets/site/theme1/images/play-button.png') ?>" alt="Play button">
                    </a>
                </div>

                <p class="text-center tutorials">
                    <a class="d-inline text-decoration-none" href="<?= base_url('site/video') ?>">
                        <?= $settings->support->col_2->link_1->$lang ?>
                    </a> |
                    <a class="d-inline text-decoration-none" target="_blank" rel="noopener noreferrer" href="<?= base_url('storage/PORTAL/2021/04/03/user_manual.pdf') ?>">
                        <?= $settings->support->col_2->link_2->$lang ?>
                    </a>

                </p>

            </div>
        </div>
    </section>

    <!-- Brand Logos -->
    <section class="container-fluid brand-logos">

        <div class="container">
            <div class="owl-carousel">

                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/Aadhaar_Large.png') ?>" alt="brand logo">
                </div>
                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/DigiLocker_S.png') ?>" alt="brand logo">
                </div>
                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/Digital_India-Black.png') ?>" alt="brand logo">
                </div>
                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/RTI-L.png') ?>" alt="brand logo">
                </div>
                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/Swach-Bharat-Large.png') ?>" alt="brand logo">
                </div>
                <div class="border border-3 py-2 me-2">
                    <img class="d-inline-block" width="280" height="150" src="<?= base_url('assets/site/theme1/images/footer/Incredible-india_S.png') ?>" alt="brand logo">
                </div>

            </div>
        </div>

    </section>

</main>

<script>
    var loginError = "<?= $login_error ?>";
    var loginErrorTitle = "<?= $settings->login_error_title->$lang ?>";
    var loginErrorMsg = "<?= $settings->login_error_msg->$lang ?>";
    const url_allServices = "<?= base_url('mis/api/get/status') ?>";
    const url_genderData = "<?= base_url('mis/api/get/status/genderwise') ?>";

</script>




