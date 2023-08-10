<?php
$lang = $this->lang;
// pre($services);
?>
 
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>" defer></script>
<script src="<?= base_url('assets/site/theme1/js/kiosk_services.js') ?>" defer></script>

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
        <div class="container extra-margin-bottom service-categories">
            <div class="row g-5">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

                    <!-- BODY content  -->

                    <h2 class="text-center pt-3">
                        <?= $settings->heading->$lang ?>
                    </h2>
                    <hr>

                    <section class="sub-cat-filters mb-5 d-flex gap-3 p-3">
                        <?php foreach ($sub_categs->sub_categories as $key => $val) : ?>
                            <button type="button" class="btn service-cat-btn d-flex flex-column align-items-center justify-content-around gap-2 <?= $key === 0 ? 'service-cat-btn__focus' : '' ?>" data-subcat="<?= $val->en ?>" data-categ="4">

                                <img class="w-50" src="<?= base_url($val->img_path) ?>" alt="cat icon">

                                <?= ucwords($val->$lang) ?>

                            </button>
                        <?php endforeach; ?>
                    </section>

                    <table id="example" class="table table-bordered table-striped display" style="width:100%;">
                        <thead>
                            <tr>
                                <th><?= $settings->table_col_1->$lang ?></th>
                                <th><?= $settings->table_col_2->$lang ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $val) : ?>
                                <tr>
                                    <td><?= $val->service_name->$lang ?></td>
                                    <td><?= $val->dept->department_name->$lang ?></td>
                                    <td>
                                        <a class="btn rtps-btn" role="button" href="<?= base_url('site/service-apply/' . $val->seo_url)  ?>"><?= $settings->table_col_3->$lang ?></a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</main>

<script>
    var langPath = "<?= base_url('assets/site/theme1/plugins/datatables/language/dt-' . $lang . '.json') ?>";
</script>