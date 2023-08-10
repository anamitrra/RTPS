<?php
$lang = $this->lang;

// pre($data);
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" defer></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>" defer></script>

<script src="<?= base_url('assets/site/' . $theme . '/js/artps_services.js') ?>" defer></script>


<main id="main-contenet">

    <div class="container">
        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
            <ol class="breadcrumb m-0">

                <?php foreach ($data->nav as $key => $link) : ?>

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

        <h2 class="text-center pt-3">
            <?= $data->heading->$lang  ?>
            <div class="mt-3">
                <a class="fw-bolder mb-2 btn text-capitalize new-artps-btn" href="<?= base_url('/iservices/') ?>" role="button">
                    <?= $data->t_btn->$lang  ?>
                </a>
                <a class="fw-bolder mb-2 btn text-capitalize new-artps-btn servicePluslogin" href="#" role="button">
                    <?= $data->o_btn->$lang  ?>
                </a>
            </div>
        </h2>

        <hr>


        <!-- Modal -->
        <div class="modal fade" id="allServicesModal" tabindex="-1" aria-labelledby="allServicesModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">

            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn rtps-btn" data-bs-dismiss="modal">
                            <?= $data->cls_btn->$lang  ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <!-- Modal Ends -->


        <table id="example" class="table table-bordered table-striped" style="width:100%; font-size: 0.85rem">
            <thead>
                <tr>
                    <th>Area Covered</th>
                    <th>Department</th>
                    <th>RTPS Service</th>
                    <th>Notification No.</th>
                    <th>Date of Notification</th>
                    <th>Designated Public Servant(DPS)</th>
                    <th>Appellate Authority(AA)</th>
                    <th>Stipulated Timeline</th>
                    <th>Documents Required</th>
                    <th>User Charge(in Rupees)</th>
                    <th>Information</th>
                </tr>
            </thead>
        </table>

    </div>

</main>

<script>
    var url = "<?= base_url('site/artps_services_api') ?>";
    var langPath = "<?= base_url('assets/site/' . $theme . '/plugins/datatables/language/dt-' . $lang . '.json') ?>";
    var docBtn = "<?= $data->doc_btn->$lang  ?>";
    var chargeBtn = "<?= $data->charge_btn->$lang  ?>";
    var naMsg = "<?= $data->na->$lang  ?>";
</script>