<?php
$lang = $this->lang;

// pre($services_list);
?>


<main id="main-contenet">

    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div class="owl-carousel header-carousel position-relative">

            <?php foreach ($settings->banners->$lang as $key => $url) : ?>

                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="<?= base_url($url) ?>" alt="Banner <?= $key ?>">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
    <!-- Carousel End -->

    <!-- Quick Links Start -->
    <div class="container-fluid py-4 rim-quick-access rim-services mb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="<?= base_url('site/online/citizen') ?>" title="<?= $settings->quick_links[0]->title->$lang ?>">
                        <div class="service-item text-center">
                            <!-- <div class="p-4 d-flex">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/citizen.png') ?>" alt="citizen icon">
                                <h5 class="mb-3"><?= $settings->quick_links[0]->$lang ?></h5>
                            </div> -->

                            <div class="d-flex p-3">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/citizen.png') ?>" alt="citizen icon">
                                <div class="flex-grow-1 text-start" style="border-left: 1px solid #ccc;">
                                    <span class="d-inline-block mb-1 ms-3 w-100 counter"><?= $total_citizen ?></span>
                                    <h4 class="ms-3" style="color: black;"><?= $settings->quick_links[0]->$lang ?></h4>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <a href="<?= base_url('site/online/eodb') ?>" title="<?= $settings->quick_links[1]->title->$lang ?>">
                        <div class="service-item text-center">
                            <!-- <div class="p-4 d-flex">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/business.png') ?>" alt="business icon">
                                <h5 class="mb-3"><?= $settings->quick_links[1]->$lang ?></h5>
                            </div> -->

                            <div class="d-flex p-3">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/business.png') ?>" alt="business icon">
                                <div class="flex-grow-1 text-start" style="border-left: 1px solid #ccc;">
                                    <span class="d-inline-block mb-1 ms-3 w-100 counter"><?= $total_business ?></span>
                                    <h4 class="ms-3" style="color: black;"><?= $settings->quick_links[1]->$lang ?></h4>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <a href="<?= base_url('site/online/utility') ?>" title="<?= $settings->quick_links[2]->title->$lang ?>">
                        <div class="service-item text-center">
                            <!-- <div class="p-4 d-flex">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/utility.png') ?>" alt="utility icon">
                                <h5 class="mb-3"><?= $settings->quick_links[2]->$lang ?></h5>
                            </div> -->

                            <div class="d-flex p-3">
                                <img src="<?= base_url('assets/site/sewasetu/assets/images/utility.png') ?>" alt="utility icon">
                                <div class="flex-grow-1 text-start" style="border-left: 1px solid #ccc;">
                                    <span class="d-inline-block mb-1 ms-3 w-100 counter"><?= $total_utility ?></span>
                                    <h4 class="ms-3" style="color: black;"><?= $settings->quick_links[2]->$lang ?></h4>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Links End -->



    <!--///////// New SewaSetu Design -->

    <!-- Login Track Area-->
    <div class="container-fluid py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInUp mb-3" data-wow-delay="0.4s">
                    <div>
                        <div class="search_box">
                            <div class="search">
                                <div class="select_area">
                                    <i class="fa fa-list map_icon"></i>
                                    <div class="text"></div>
                                </div>

                                <div class="line"></div>

                                <form action="<?= base_url('site/search') ?>" method="get" id="service-search" class="w-100">

                                    <div class="text_and-icon w-100">
                                        <input autocomplete="off" type="search" list="services" class="flex-grow-1 search_text" id="search_text" name="service_name" placeholder=" <?= $settings->service_section->place_holder->$lang ?>">
                                        <button class="btn me-3 border btn me-3 rounded-pill" type="submit" style="border-color: #6225E6 !important; background-color: #6225E6;"><i class="fa fa-search search_icon"></i></button>

                                        <datalist id="services">
                                            <?php foreach ($services_list as $service) : ?>

                                                <option value="<?= $service->service_name->$lang ?>">

                                                <?php endforeach; ?>
                                        </datalist>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp mb-3" data-wow-delay="0.6s">
                    <div class="wrapper">
                        <a class="rimdesg" href="<?= base_url('site/online') ?>">
                            <span><?= $settings->services_offered_btn->$lang ?> &nbsp;</span>
                            <span>
                                <svg width="33px" height="23px" viewBox="0 0 66 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="arrow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <path class="one" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                        <path class="two" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                        <path class="three" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
                                    </g>
                                </svg>
                            </span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--Search bar-->
    <!-- About Start -->
    <div class="rim-quicklinks  mb-5 fadeInUp" data-wow-delay="0.4s">
        <div class="row1-container align-items-stretch">
            <a target="_blank" rel="noopener noreferrer" href="<?= base_url('iservices/login') ?>" class="box red rim-login wow fadeInUp mb-3" data-wow-delay="0.1s">
                <h2><?= $settings->service_section->login_btn->$lang ?></h2>
                <p><?= $settings->service_section->login_btn->desc->$lang ?></p>
                <img src="<?= base_url('assets/site/sewasetu/assets/images/login.png') ?>" alt="Login Image">

            </a>

            <a href="#" data-bs-toggle="modal" data-bs-target="#trackModal" class="box purple  wow fadeInUp mb-3" data-wow-delay="0.2s">
                <h2><?= $settings->service_section->track_btn->$lang ?></h2>
                <p><?= $settings->service_section->track_btn->desc->$lang ?></p>
                <img src="<?= base_url('assets/site/sewasetu/assets/images/track.png') ?>" alt="Track Image">
            </a>
        </div>

        <div class="row2-container align-items-stretch">
            <a href="<?= base_url('grm') ?>" class="box cyan wow fadeInUp mb-3" data-wow-delay="0.3s" target="_blank" rel="noopener noreferrer">
                <h2><?= $settings->service_section->grievance_btn->$lang ?></h2>
                <p><?= $settings->service_section->grievance_btn->desc->$lang ?></p>
                <img src="<?= base_url('assets/site/sewasetu/assets/images/grievance.png') ?>" alt="Grievance Image">
            </a>

            <a href="<?= base_url('appeal') ?>" class="box blue  wow fadeInUp mb-3" data-wow-delay="0.4s" target="_blank" rel="noopener noreferrer">
                <h2><?= $settings->service_section->appeal_btn->$lang ?></h2>
                <p><?= $settings->service_section->appeal_btn->desc->$lang ?></p>
                <img src="<?= base_url('assets/site/sewasetu/assets/images/appeal.png') ?>" alt="Appeal Image">
            </a>
            <a href="<?= base_url('site/pfc_locations') ?>" class="box orange  wow fadeInUp mb-3" data-wow-delay="0.5s">
                <h2><?= $settings->service_section->pfc_loc_btn->$lang ?></h2>
                <p><?= $settings->service_section->pfc_loc_btn->desc->$lang ?></p>
                <img src="<?= base_url('assets/site/sewasetu/assets/images/pfclocate.png') ?>" alt="PFC Image">
            </a>
        </div>
    </div>

    <!-- New SewaSetu Design Ends /////////-->

</main>


<script>
    var loginError = "<?= $login_error ?? '' ?>";
    var loginErrorTitle = "<?= $settings->login_error_title->$lang ?? '' ?>";
    var loginErrorMsg = "<?= $settings->login_error_msg->$lang ?? '' ?>";

    const url_allServices = "https://rtps.assam.gov.in/mis/api/get/status";
    const url_genderData = "https://rtps.assam.gov.in/mis/api/get/status/genderwise";
    // const url_feedback = 'https://rtps.assam.gov.in/dashboard/feedback/average';

    // Show Popup Model?
    const showModel = "<?= is_new_visitor() ?>";
</script>


<script src="<?= base_url('assets/site/theme1/js/home.js') ?>" defer></script>