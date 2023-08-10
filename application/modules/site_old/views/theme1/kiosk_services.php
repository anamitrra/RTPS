<?php
$lang = $this->lang;
// pre($kiosk_type);
?>
<style>
    @media all and (max-width: 400px) {
        .new-artps-btn:first-child {
            margin-bottom: 0.3em;
        }
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>" defer></script>
<script src="<?= base_url('assets/site/' . $theme . '/js/kiosk_services.js') ?>" defer></script>

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

            </ol>
        </nav>

        <h2 class="text-center pt-3">
            <?= $settings->heading->$lang  ?> <sup><span class="fs-5"><mark><?= $settings->$kiosk_type->$lang ?></mark> </span></sup>

            <?php if ($kiosk_type == 'pfc') : ?>
                <div class="mt-3">
                    <a class="fw-bolder btn mb-2 text-capitalize new-artps-btn" href="<?= base_url('/iservices/') ?>" role="button">
                        <?= $settings->login_btn->transport_noc->$lang ?>

                    </a>
                    <a class="fw-bolder btn mb-2 text-capitalize new-artps-btn servicePluslogin" href="#" role="button">
                        <?= $settings->login_btn->other->$lang ?>
                    </a>
                    <a class="fw-bolder btn mb-2 text-capitalize new-artps-btn" href="<?= base_url('site/pfc_locations') ?>" role="button">
                        <?= $settings->login_btn->pfc_loc->$lang ?>
                    </a>

                </div>
            <?php endif; ?>

        </h2>
        <hr>

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
                        <td><?= $val->dept->department_name->$lang ?></td>
                        <td><?= $val->service_name->$lang ?></td>
                        <td>
                            <a class="btn rtps-btn" role="button" href="<?= isset($val->seo_url) ? base_url('site/service-apply/' . $val->seo_url) : '#'  ?>">

                                <?= $settings->apply_btn->$lang ?>

                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</main>

<script>
    var langPath = "<?= base_url('assets/site/' . $theme . '/plugins/datatables/language/dt-' . $lang . '.json') ?>";
</script>