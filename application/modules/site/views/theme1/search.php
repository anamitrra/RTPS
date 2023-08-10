<?php
$lang = $this->lang;
// pre($settings);
// pre($search);

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

                    <!-- BODY content  -->


                    <section class="text-center py-2">
                        <p>
                            <span class="text-uppercase fw-normal"><?= $settings->heading->$lang  ?></span>
                            <span class="fs-4 search1 mark">"<?= $this->session->userdata("user_query") ?? ""  ?>"</span>
                        </p>

                        <span class="text-muted"><?= str_ireplace('[n]', '<mark>' . strval(count($search)) . '</mark>', $settings->result_count_msg->$lang); ?></span>

                    </section>

                    <section class="mt-3">

                        <?php if (count($search) > 0) : ?>

                            <ul class="list-group my-4">

                                <?php foreach ($search as $service) : ?>

                                    <li class="service-cat list-group-item d-flex flex-column flex-lg-row justify-content-lg-between align-items-center">

                                        <table class="search2 table table-borderless table-sm">

                                            <tbody>

                                                <tr>
                                                    <th scope="row" style="width: 30%">
                                                        <?= $settings->result->service->$lang ?>
                                                    </th>
                                                    <td><?= $service->service_name->$lang ?></td>
                                                </tr>

                                                <!-- Only dispaly known categories -->

                                                <?php if ($service->categ[0]->cat_name->en <> 'Unknown') : ?>

                                                    <tr>
                                                        <th scope="row" style="width: 30%">
                                                            <?= $settings->result->category->$lang ?>
                                                        </th>
                                                        <td><?= $service->categ[0]->cat_name->$lang ?></td>
                                                    </tr>

                                                <?php endif; ?>

                                                <tr>
                                                    <th scope="row" style="width: 30%">
                                                        <?= $settings->result->dept->$lang ?>
                                                    </th>
                                                    <td> <?= $service->dept[0]->department_name->$lang  ?></td>
                                                </tr>

                                            </tbody>

                                        </table>

                                        <a class="btn rtps-btn" role="button" href="<?= isset($service->seo_url) ? base_url('site/service-apply/' . $service->seo_url) : '#'  ?>">

                                            <?= $settings->apply_btn->$lang ?>

                                        </a>

                                    </li>

                                <?php endforeach; ?>

                            </ul>



                        <?php else : ?>

                            <img class="img-fluid rounded mx-auto d-block" src="<?= base_url('assets/site/theme1/images/nf.png') ?>" alt="result not found">

                        <?php endif; ?>

                    </section>

                </div>
            </div>
        </div>
    </div>

</main>