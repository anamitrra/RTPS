<?php
$lang = $this->lang;
?>

<!-- Breadcrumb Start -->
<div class="container-fluid bg-primary mb-5 page-header">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 text-start">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                        <?php foreach ($settings->nav as $key => $link) : ?>

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


<main id="main-contenet">

    <div class="container-xxl py-3">
        <div class="container extra-margin-bottom">
            <div class="row g-5">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

                    <h3 class="mt-3 mb-0 pb-1 sitemap-title" style="border-bottom: 3px solid #362f2d;"><?= $settings->heading->$lang ?></h3>

                    <div class="row">
                        <div class="col-12">
                            <section class="sitemap-content py-2">

                                <ul>
                                    <li>
                                        <a href="<?= base_url('site/index') ?>"><?= $settings->home->$lang ?></a>
                                        <ul>
                                            <li>
                                                <span> <a href="<?= base_url('site/about') ?>"><?= $settings->about->$lang ?></a></span>
                                            </li>
                                            <li>
                                                <span><a href="<?= base_url('site/artps_services') ?>"><?= $settings->services->$lang ?></a></span>
                                            </li>
                                            <li>
                                                <span class="fw-bold"><?= $settings->dashboard->$lang ?></span>
                                                <ul>

                                                    <?php foreach ($settings->dashboard->sub_links as $link) : ?>

                                                        <li><span><a href="<?= base_url("{$link->link}") ?>" target="_blank" rel="noopener noreferrer"><?= $link->$lang ?></a></span></li>

                                                    <?php endforeach; ?>

                                                </ul>
                                            </li>
                                            <li>
                                                <span class="fw-bold"><?= $settings->documents->$lang ?></span>
                                                <ul>

                                                    <?php foreach ($docs->categories as $doc_cat) : ?>

                                                        <li><span><a href="<?= base_url('site/docs') ?>"><?= $doc_cat->title->$lang ?></a></span></li>

                                                    <?php endforeach; ?>

                                                </ul>
                                            </li>
                                            <li>
                                                <span><a href="<?= base_url('site/contact') ?>"><?= $settings->contact->$lang ?></a></span>
                                            </li>
                                            <li>
                                                <span><a href="<?= base_url('site/service-wise-data') ?>"><?= $settings->applications->$lang ?></a></span>
                                            </li>
                                            <li>
                                                <span><a href="<?= base_url('site/citizen_registration') ?>"><?= $settings->register->$lang ?></a></span>
                                            </li>
                                            <li>
                                                <span class="fw-bold"><?= $settings->track->$lang ?></span>
                                                <ul>

                                                    <?php foreach ($settings->track->links as $link) : ?>

                                                        <li><span><a href="<?= base_url("{$link->url}") ?>">
                                                                    <?= $link->$lang ?></a></span>
                                                        </li>

                                                    <?php endforeach; ?>

                                                </ul>

                                            </li>
                                            <li><span><a href="<?= base_url('appeal') ?>" target="_blank" rel="noopener noreferrer"><?= $settings->appeal->$lang ?></a></span></li>
                                            <li><span><a href="<?= base_url('grm') ?>" target="_blank" rel="noopener noreferrer"><?= $settings->grievance->$lang ?></a></span></li>
                                            <li><span class="fw-bold"><?= $settings->service_cat->$lang ?></span>
                                                <ul>
                                                    <?php foreach ($categories as $serv_cat) : ?>

                                                        <li><span><a href="<?= base_url('site/service_cat/' . $serv_cat->cat_id) ?>"><?= $serv_cat->cat_name->$lang ?></a></span></li>

                                                    <?php endforeach; ?>

                                                </ul>

                                            </li>
                                            <li><span class="fw-bold"><?= $settings->deptwise->$lang ?></span>
                                                <ul>

                                                    <?php foreach ($depts as $dept) : ?>
                                                        <li><span><a href="<?= base_url('site/online/' . $dept->department_short_name) ?>"><?= $dept->department_name->$lang ?></a></span></li>
                                                    <?php endforeach; ?>

                                                    <li><span><a href="<?= base_url('site/online') ?>"><?= $settings->view_all_dept->$lang ?></a></span></li>

                                                </ul>
                                            </li>
                                            <li><span class="fw-bold"><?= $settings->councilwise->$lang ?></span>
                                                <ul>

                                                    <?php foreach ($ac as $dept) : ?>
                                                        <li><span><a href="<?= base_url('site/online/' . $dept->department_short_name) ?>"><?= $dept->department_name->$lang ?></a></span></li>
                                                    <?php endforeach; ?>


                                                </ul>
                                            </li>
                                            <li><span class="fw-bold"><?= $settings->query->$lang ?></a></span>
                                                <ul>
                                                    <li><span><a href="<?= base_url('site/faq') ?>"><?= $settings->faq->$lang ?></a></span></li>
                                                    <li><span><a href="<?= base_url('site/contact') ?>"><?= $settings->contact_us->$lang ?></a></span></li>
                                                </ul>
                                            </li>
                                            <li><span><a href="<?= base_url('site/about') ?>"><?= $settings->portal->$lang ?></a></span></li>
                                            <li><span class="fw-bold"><?= $settings->acts_n_rules->$lang ?></span>
                                                <ul>
                                                    <li><span><a href="<?= base_url('storage/PORTAL/2021/03/25/e6fcca32d8edc30f7d00b8019cc750db.pdf') ?>"><?= $settings->act19->$lang ?></a></span></li>
                                                    <li><span><a href="<?= base_url('storage/PORTAL/2021/04/03/bb7511928a85341659bd23e5155373db.pdf') ?>"><?= $settings->act12->$lang ?></a></span></li>
                                                    <li><span><a href="<?= base_url('storage/PORTAL/2021/03/25/44eb5b5aace93ff3fdeaac7d746be105.pdf') ?>"><?= $settings->rule12->$lang ?></a></span></li>

                                                </ul>
                                            </li>
                                            <li><span class="fw-bold"><?= $settings->support->$lang ?></span>
                                                <ul>
                                                    <li><span><a href="<?= base_url('site/video') ?>"><?= $settings->video->$lang ?></a></span></li>
                                                    <li><span><a href="<?= base_url('storage/PORTAL/2021/04/03/user_manual.pdf') ?>"><?= $settings->manual->$lang ?></a></span></li>
                                                </ul>
                                            </li>

                                        </ul>
                                    </li>
                                </ul>

                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



</main>