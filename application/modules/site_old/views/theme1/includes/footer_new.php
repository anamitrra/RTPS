<?php
$lang = $this->lang;
//pre($footer);
//pre($footer->last_update);
?>

<footer class="mt-5">

    <section class="links container-fluid py-5">

        <div class="container">
            <div class="row gy-4 g-lg-0">

                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-md-0">

                    <h6 class="mb-3"><?= $footer->help_deks->title->$lang ?></h6>

                    <section class="recent-updates mb-2">

                        <article class="py-1 mb-1">
                            <div>
                                <img width="16" src="<?= base_url('assets/site/theme1/images/phone.png') ?>" alt="Phone icon">
                                <span class="dept-text text-white fw-bold small">
                                    <?= $footer->help_deks->content[0]->title->$lang ?>
                                </span>
                            </div>
                            <p class="dept-text small my-1 text-warning">
                                <?= $footer->help_deks->content[0]->contact->$lang ?>
                            </p>
                            <p class="dept-text small m-0 text-warning">
                                <?= $footer->help_deks->content[0]->text->$lang ?>
                            </p>
                        </article>

                        <article class="py-1">
                            <div>
                                <img width="16" src="<?= base_url('assets/site/theme1/images/email.png') ?>" alt="Email icon">
                                <span class="dept-text text-white fw-bold small">
                                    <?= $footer->help_deks->content[1]->title->$lang ?>
                                </span>
                            </div>
                            <span class="dept-text  d-block text-truncate small mt-1 text-warning">
                                <?= $footer->help_deks->content[1]->contact->$lang ?>
                            </span>

                        </article>

                    </section>

                    <section class="social-links mt-2">
                        <p class="dept-text text-white fw-bold small">
                            <?= $footer->help_deks->social_links->title->$lang ?>
                        </p>

                        <?php foreach ($footer->help_deks->social_links->links as $link) : ?>

                            <a href="<?= $link->link ?>" title="<?= $link->title ?>" class="d-inline-block me-1" target="_blank" rel="noopener noreferrer">
                                <img width="25" src="<?= base_url($link->img_path) ?>" alt="social icon">
                            </a>

                        <?php endforeach; ?>

                    </section>

                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h6 class="mb-3"><?= $footer->rtps->title->$lang ?></h6>

                    <ul class="list-unstyled rtps-footer-links">

                        <?php foreach ($footer->rtps->links as $link) : ?>

                            <?php if (isset($link->content)) : ?>

                                <li class="py-1">
                                    <a href="<?= isset($link->url) ? base_url($link->url) : '#' ?>" class="dept-text small text-decoration-none">
                                        <?= $link->$lang ?>
                                    </a>
                                </li>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </ul>

                    <div class="w-75 visitors d-none border border-light border-1 rounded-0 p-2 d-flex justify-content-start align-items-start">

                        <img width="30" src="<?= base_url('assets/site/theme1/images/feedbackicon.png') ?>" alt="visitor icon">

                        <?= $footer->rtps->feedback->$lang ?>

                        <a href="<?= base_url('site/feedback') ?>" class="visitors ms-3 fw-bold text-decoration-none"><?= $footer->rtps->feedback->$lang ?></a>
                    </div>

                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h6 class="imp mb-3"><?= $footer->imp_links->title->$lang ?></h6>

                    <ul class="list-unstyled">

                        <?php foreach ($footer->imp_links->links as $link) : ?>

                            <li class="py-1">
                                <a href="<?= $link->url ?>" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    <?= $link->$lang ?>
                                </a>

                            </li>

                        <?php endforeach; ?>

                    </ul>



                </div>
                <div class="col-12 col-md-6 col-lg-3">

                    <h6 class="nodal mb-3"><?= $footer->nodal->title->$lang ?></h6>
                    <a href="<?= $footer->nodal->link->url ?>" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                        <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                        <?= $footer->nodal->link->$lang ?>
                    </a>

                    <h6 class="my-3"><?= $footer->agency->title->$lang ?></h6>
                    <a href="<?= $footer->agency->link->url ?>" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                        <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                        <?= $footer->agency->link->$lang ?>
                    </a>

                    <h6 class="my-3"><?= $footer->nic->title->$lang ?></h6>
                    <a href="<?= $footer->nic->link->url ?>" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                        <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                        <?= $footer->nic->link->$lang ?>
                    </a>

                    <img class="d-block" style="transform: translateX(-10px);" width="188" src="<?= base_url('assets/site/theme1/images/nic.png') ?>" alt="NIC logo">

                </div>
            </div>
        </div>

    </section>

    <section class="copyright container-fluid pt-2 pb-0">

        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-baseline flex-wrap">
            <p class="dept-text text-white small">
                <?= $footer->last_update->$lang ?> <span><?= $footer->last_update->date ?></span>

            </p>

            <p class="fw-bold order-1 order-md-0 text-white">
                <?= $footer->imp_links->visitor->$lang ?>:
                <span><?= $footer->imp_links->visitor->count ?></span>
            </p>

            <p class="dept-text text-white small">
                <?= $footer->copyright->t1->$lang ?> &copy; <span> <?= date("Y") ?> </span> |
                <?= $footer->copyright->t2->$lang ?>
            </p>
        </div>

    </section>


    <!-- Scroll to top button -->
    <button class="scrollToTopBtn"><i class="fas fa-chevron-up"></i></button>


</footer>


<script>
    var privacyTitle = "<?= $footer->rtps->links[1]->$lang ?>";
    var privacyContent = "<?= $footer->rtps->links[1]->content->$lang ?>";
    var tncTitle = "<?= $footer->rtps->links[0]->$lang ?>";
    var tncContent = "<?= $footer->rtps->links[0]->content->$lang ?>";
    var copyrightTitle = "<?= $footer->rtps->links[2]->$lang ?>";
    var copyrightContent = "<?= $footer->rtps->links[2]->content->$lang ?>";
    var rncTitle = "<?= $footer->rtps->links[3]->$lang ?>";
    var rncContent = "<?= $footer->rtps->links[3]->content->$lang ?>";

    var siteAlertTitle = "<?= $footer->site_alert_model->header->$lang ?>";
    var siteAlertMsg = "<?= $footer->site_alert_model->body->$lang ?>";
    var siteAlertFlag = "<?= $footer->site_alert_model->enable ?>";
</script>

</body>

</html>