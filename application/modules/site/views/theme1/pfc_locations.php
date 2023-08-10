<?php
$lang = $this->lang;
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
        <div class="container extra-margin-bottom">
            <div class="row g-5">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

                    <!-- BODY content  -->

                    <h2 class="text-center pt-3">
                        <?= $settings->heading->$lang ?>
                    </h2>
                    <hr>
                    <select class="mb-4 d-block mx-auto w-auto form-select" aria-label="District select box" id="districtSelectBox">
                        <option selected><?= $settings->select_box->$lang ?></option>
                        <?php foreach ($districts[0]->values as $val) : ?>
                            <option value="<?= $val ?>"><?= $val ?></option>
                        <?php endforeach; ?>

                    </select>

                    <table id="example" class="table table-bordered table-striped display" style="width:100%;">
                        <thead>
                            <tr>
                                <th><?= $settings->table_col_1->$lang ?></th>
                                <th><?= $settings->table_col_2->$lang ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pfcs as $val) : ?>
                                <tr>
                                    <td><?= $val->Sanctioned_PFC ?></td>
                                    <td><?= $val->District ?></td>
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
    let langPath = "<?= base_url('assets/site/theme1/plugins/datatables/language/dt-' . $lang . '.json') ?>";
    let selectMsg = "<?= $settings->select_box->$lang ?>";
    let waitMsg = "<?= $settings->loading_msg->$lang ?>";

    // Load PFCs on selecting a District
    document.addEventListener('DOMContentLoaded', function(event) {

        const districtSelect = document.getElementById('districtSelectBox');
        districtSelect.addEventListener('change', function(event) {

            if (districtSelect.selectedIndex > 0) {
                const distValue = districtSelect.value; // Selected district
                const distIndex = districtSelect.selectedIndex; // Selected index

                // URL
                let distURL = "<?= base_url('site/find_pfcs_by_district?d=') ?>" + encodeURIComponent(distValue);

                // Display loader
                districtSelect.options[0].text = waitMsg;
                districtSelect.options[0].selected = true;

                window.fetch(distURL)
                    .then(function(response) {
                        if (response.status == 200) {
                            return response.json();
                        }

                        throw new Error('API error in data fetching');
                    })
                    .then(function(data) {
                        // Refresh datatable with new dataset

                        const table = $('#example').DataTable();
                        table.clear().draw();
                        table.rows.add(data.map(function(value, index) {
                            return [value.Sanctioned_PFC, value.District];
                        })).draw();

                    })
                    .catch(function(error) {
                        window.alert(error);
                    })
                    .finally(function() {
                        districtSelect.options[0].text = selectMsg;
                        districtSelect.options[distIndex].selected = true;
                    });
            }
        });

    });
</script>