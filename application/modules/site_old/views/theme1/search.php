<?php
$lang = $this->lang;
// pre($settings);
// pre($search);

?>

<main id="main-contenet">

    <div class="container">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
            <ol class="breadcrumb m-0">

                <?php foreach ($settings->nav as $key => $link) : ?>

                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                        <?php else : ?>
                            <?= $link->$lang ?>


                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>


                <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
            </ol>
        </nav>


        <section class="text-center py-2">
            <p>
                <span class="text-uppercase fw-normal"><?= $settings->heading->$lang  ?></span>
                <span class="fs-4 mark">"<?= $this->session->userdata("user_query") ?? ""  ?>"</span>
            </p>

            <span class="text-muted"><?= str_ireplace('[n]', '<mark>' . strval(count($search)) . '</mark>', $settings->result_count_msg->$lang); ?></span>

        </section>

        <section class="mt-3">

            <?php if (count($search) > 0) : ?>

                <ul class="list-group my-4">

                    <?php foreach ($search as $service) : ?>

                        <li class="service-cat list-group-item d-flex flex-column flex-lg-row justify-content-lg-between align-items-center">

                            <table class="table table-borderless table-sm">

                                <tbody>

                                    <tr>
                                        <th scope="row" style="width: 30%">
                                            <?= $settings->result->service->$lang ?>
                                        </th>
                                        <td><?= $service->service_name->$lang ?></td>
                                    </tr>

                                    <!-- Only dispaly known categories -->

                                    <?php if ($service->categ[0]->cat_name->en <> 'Unknown'): ?>

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

                <img class="img-fluid rounded mx-auto d-block" src="<?= base_url('assets/site/' . $theme . '/images/nf.png') ?>" alt="result not found">

            <?php endif; ?>

        </section>

    </div>

</main>