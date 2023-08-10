<?php
$lang = $this->lang;
// pre($settings);
// pre($services);
?>

<main id="main-contenet">

    <div class="container">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
            <ol class="breadcrumb m-0">

                <?php foreach ($settings->nav as $key => $link) : ?>

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

        <?php if (count($services) > 0) : ?>

            <section class="my-3 text-center">
                <h3>

                    <?= str_ireplace('[n]', $services[0]->categ[0]->cat_name->$lang, $settings->heading->$lang) ?>

                </h3>
            </section>

            <ul class="list-group my-4">

                <?php foreach ($services as $serv) : ?>

                    <li class="service-cat list-group-item d-flex flex-column flex-lg-row justify-content-lg-between align-items-center">

                        <table class="service-text table table-borderless table-sm">

                            <tbody>

                                <tr>
                                    <th scope="row" style="width: 30%"><?= $settings->service->$lang ?></th>
                                    <td><?= $serv->service_name->$lang ?></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 30%"><?= $settings->dept->$lang ?></th>
                                    <td> <?= $serv->dept[0]->department_name->$lang  ?></td>
                                </tr>

                            </tbody>

                        </table>

                        <a class="btn btn-rtps" role="button" href="<?= base_url('site/service-apply/' . $serv->seo_url) ?>"><?= $settings->apply_btn->$lang ?></a>

                    </li>

                <?php endforeach; ?>

            </ul>

        <?php else : ?>

            <img class="img-fluid rounded mx-auto d-block" src="<?= base_url('assets/site/theme1/images/nf.png') ?>" alt="result not found">

            <p class="text-center text-muted"><?= $header->not_found->$lang ?></p>

        <?php endif; ?>



    </div>

</main>
