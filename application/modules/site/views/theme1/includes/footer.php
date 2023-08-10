<?php
$lang = $this->lang;
//pre($footer->site_alert_model);

$has_new_noti = false;
foreach ($footer->notice->notices as $key => $value) {
    if (!empty($value->newly_launched)) {
        $has_new_noti = true;
    }
}

$total_noti = sizeof($footer->notice->notices);

// include("external_file.php");    // warning
// echo $b;      // notice
// // echo ("Green"]      // parser/syntax
// div();             // fatal error
// throw new Exception('Testing exception');     //exception
?>

<!-- Footer Slider -->
<div class="container-fluid footer-dark">
    <div class="container py-5">
        <h2 class="d-none">Footer Slider</h2>
        <section class="owl-carousel rimfooter-carousel position-relative">
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/Aadhaar_Large.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/DigiLocker_S.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/Digital_India-Black.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/RTI-L.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/Swach-Bharat-Large.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/Incredible-india_S.png') ?>"></div>
            <div class="border border-3 py-2 me-2"><img src="<?= base_url('assets/site/sewasetu/assets/images/footer-slider/Digital_India-Black.png') ?>"></div>

        </section>
    </div>
</div>

<footer>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3"><?= $footer->help_deks->title->$lang ?></h4>
                    <p class="mb-2"> <i class="bi bi-telephone-fill"></i> <small> <?= $footer->help_deks->content[0]->contact->$lang ?> (<?= $footer->help_deks->content[0]->text->$lang ?>)</small></p>
                    <p class="mb-2"><i class="bi bi-envelope-fill"></i> <small> rtps-assam[at]assam[dot]gov[dot]in</small></p>
                    <div class="align-items-center mx-n2 mb-3">
                        <span><?= $footer->help_deks->social_links->title->$lang ?></span>
                        <a class="px-2" href="https://www.facebook.com/accsdp.assam" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                        <a class="px-2" href="https://twitter.com/accsdp_assam?s=08" target="_blank" rel="noopener noreferrer"><i class="bi bi-twitter"></i></a>
                        <a class="px-2" href="https://www.youtube.com/channel/UCumAwJZJlkmc1BHjqg1CPCA" target="_blank" rel="noopener noreferrer"><i class="bi bi-youtube"></i></a>
                        <a class="px-2" href="https://www.instagram.com/accsdp__assam/?igshid=8mxmsmaq019b" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                    </div>
                    <a class="footer-rim-button" href="<?= base_url('site/faq') ?>"><small>
                            <?= $footer->help_deks->faq->$lang ?>
                        </small></a>
                    <a class="footer-rim-button mx-2 mb-2 d-inline-block" href="<?= base_url('site/video') ?>"><small>
                            <?= $footer->help_deks->video_tuts->$lang ?>
                        </small></a>
                    <a class="footer-rim-button" href="<?= base_url('storage/PORTAL/2021/04/03/user_manual.pdf') ?>"><small>
                            <?= $footer->help_deks->user_manual->$lang ?>
                        </small></a>

                </div>
                <div class="col-lg-3 col-md-6 rtps-footer-links">
                    <h4 class="text-white mb-3"><?= $footer->rtps->title->$lang ?></h4>

                    <a class="btn btn-link" href="<?= base_url('site/about') ?>"><?= $footer->rtps->links[0]->$lang ?></a>
                    <a class="btn btn-link" href="<?= base_url('site/faq') ?>"><?= $footer->rtps->links[1]->$lang ?></a>
                    <a class="btn btn-link" href="<?= base_url('site/all_policies') ?>"><?= $footer->rtps->links[2]->$lang ?></a>
                    <a class="btn btn-link" href="<?= base_url('site/contact') ?>"><?= $footer->rtps->links[3]->$lang ?></a>

                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3"><?= $footer->imp_links->title->$lang ?></h4>
                    <a class="btn btn-link" href="https://assam.gov.in/" target="_blank" rel="noopener noreferrer"><?= $footer->imp_links->links[0]->$lang ?></a>
                    <a class="btn btn-link" href="https://cm.assam.gov.in/" target="_blank" rel="noopener noreferrer"><?= $footer->imp_links->links[4]->$lang ?></a>
                    <a class="btn btn-link" href="https://assam.mygov.in/" target="_blank" rel="noopener noreferrer"><?= $footer->imp_links->links[1]->$lang ?></a>
                    <a class="btn btn-link" href="https://www.india.gov.in/" target="_blank" rel="noopener noreferrer"><?= $footer->imp_links->links[3]->$lang ?></a>


                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3"></h4>

                    <p class="fw-bold order-1 order-md-0 text-white">
                        <?= $footer->imp_links->visitor->$lang ?>: <span><?= $footer->imp_links->visitor->count ?></span>
                    </p>
                    <p class="dept-text text-white">
                        <?= $footer->last_update->$lang ?> <span><?= $footer->last_update->date ?></span>

                    </p>
                    <hr>
                    <p class="dept-text text-white small">
                        <?= $footer->copyright->t1->$lang ?> &copy; <span> <?= date("Y") ?> </span> |
                        <?= $footer->copyright->t2->$lang ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                        <?= $footer->agency->title->$lang ?>
                        <a href="<?= $footer->agency->link->url ?>" target="_blank" rel="noopener noreferrer"> <br>
                            <?= $footer->agency->link->$lang ?>
                        </a>

                    </div>
                    <div class="col-md-6 text-center mb-3 mb-md-0">
                        <?= $footer->nodal->title->$lang ?>
                        <a href="<?= $footer->nodal->link->url ?>" target="_blank" rel="noopener noreferrer"> <br>
                            <?= $footer->nodal->link->$lang ?>
                        </a>
                    </div>
                    <div class="col-md-3 text-center text-md-end">
                        <?= $footer->nic->title->$lang ?>
                        <a href="<?= $footer->nic->link->url ?>" target="_blank" rel="noopener noreferrer"> <br>
                            <?= $footer->nic->link->$lang ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" style="border-radius: 0;" class="btn btn-lg btn-primary back-to-top border-0"><i class="bi bi-arrow-up"></i></a>


    <!-- ChatBot Button -->
    <button type="button" class="btn chatbot-btn"></button>

    <!-- Chat Window -->
    <section id="chatbot">
        <div class="chatwindow rounded shadow-lg border">
            <div class="panel-heading py-2 px-3 rounded-top d-flex justify-content-between align-content-start">

                <h3 class="panel-title"> Xohari <i class="fa fa-comments text-success fs-1" aria-hidden="true"></i>
                </h3>

                <i class="fa fa-times text-success" id="chat-close" aria-hidden="true"></i>

            </div>
            <div class="panel-body msg_container_base rounded">
                <!-- Conversations -->
                <div class="frame">
                    <ul class="m-0 align-items-start d-flex flex-column gap-3 m-0">
                        <li class="bot-response p-2">
                            Hello! Welcome to SewaSetu Portal👋
                        </li>
                        <li class="bot-response p-2">
                            Choose your language
                            <div class="mt-3 lang-btns">
                                <button type="button" class="me-2 btn btn-sm btn-outline-secondary">English</button>
                                <button type="button" class="me-2 btn btn-sm btn-outline-secondary">Assamese</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Bangla</button>
                            </div>

                        </li>

                    </ul>
                </div>

                <!-- Chat box -->
                <div class="chatbox rounded-bottom p-3  p-3 d-flex justify-content-center align-items-center">
                    <!-- <input type="text" class="p-3 border-0 w-100" placeholder="Type a message...">
                    <i class="fa fa-paper-plane position-absolute text-muted fs-6" aria-hidden="true"></i> -->

                    <button type="button" class="btn rtps-btn">Start Over
                        <i class="ms-2 fa fa-refresh" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

    </section>


    <!-- Notification Button -->
    <button type="button" class="btn noti-btn rounded-circle">
        <i class="fa fa-2x fa-bell" aria-hidden="true"></i>

        <!-- Display it when new notication is added and user hasn't seen it yet -->

        <span class="fw-bold position-absolute bg-success rounded-pill p-1 d-none" style="top: -10px;right: 5px;font-size: 0.7em;">
            New
        </span>

    </button>

    <!-- Notifications area -->
    <section id="noti-area" class="border-1 rounded-1 p-3">

        <div class="noti-heading border-bottom mb-3">

            <h4 class=""><i class="fa fa-bullhorn text-danger me-2" aria-hidden="true"></i><?= $footer->notice->title->$lang ?></h4>

        </div>
        <div class="noti-body">
            <ul class="list-group">

                <?php foreach ($footer->notice->notices as $key => $val) : ?>

                    <li class="list-group-item py-3">
                        <a href="<?= base_url($val->link->url) ?>" class="text-decoration-none">
                            <i class="fa fa-arrow-circle-right text-info me-1" aria-hidden="true"></i>
                            <?= $val->title->$lang ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>

        <!-- Closed button -->
        <i class="fa fa-close text-muted noti-cls-btn" aria-hidden="true"></i>


    </section>



</footer>

<script>
    var siteAlertTitle = "<?= $footer->site_alert_model->header->$lang ?? '' ?>";
    var siteAlertMsg = "<?= $footer->site_alert_model->body->$lang ?? '' ?>";
    var siteAlertFlag = "<?= $footer->site_alert_model->enable ?? '' ?>";
    var siteLanguage = "<?= $this->lang ?>";
    var baseURL = "<?= base_url() ?>";
    var notificationCount = "<?= sizeof($footer->notice->notices); ?>";
</script>


<!-- JS Libraries -->
<?php
// Load old jqueries only for Bharat MAP
$method = $this->router->fetch_method();

if ($method == 'contact') { ?>
    <script src="<?= base_url("assets/site/theme1/mapview/js/jq2.js"); ?>"></script>
    <script src="<?= base_url("assets/site/theme1/mapview/js/jq1.js"); ?>"></script>
<?php } else { ?>

    <script src="<?= base_url('assets/site/sewasetu/node_modules/jquery/dist/jquery.min.js') ?>"></script>

<?php }
?>

<script src="<?= base_url('assets/site/sewasetu/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/assets/lib/wow/wow.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/node_modules/jquery.easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/node_modules/waypoints/lib/jquery.waypoints.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/assets/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/assets/js/counterup.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/node_modules/lightbox/js/lightbox.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/assets/lib/fxss-rate/rate.min.js') ?>"></script>
<script src="<?= base_url('assets/site/sewasetu/assets/lib/fxss-rate/need/iconfont.js') ?>"></script>
<script src="<?= base_url('assets/site/theme1/plugins/jquery-lazy/jquery.lazy.min.js') ?>"></script>

<!-- JS -->
<script src="<?= base_url('assets/site/sewasetu/assets/js/main.js') ?>"></script>
<script src="<?= base_url("assets/site/theme1/js/common.js"); ?>"></script>
<script src="<?= base_url("assets/site/theme1/js/dark.js"); ?>"></script>
<script src="<?= base_url("assets/site/theme1/js/font-resize.js"); ?>"></script>

<!--Popular Services JS-->
<script src="<?= base_url('assets/site/theme1/js/popular.js') ?>"></script>

<!-- Chatbot -->
<script src="<?= base_url('assets/site/theme1/chatbot/js/bot.js') ?>"></script>

<script>
    const hasNewNotiServer = Boolean("<?= $has_new_noti ?>");
    const totalNotiServer = Number("<?= $total_noti ?>");
</script>



</body>

</html>