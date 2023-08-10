<?php
$lang = $this->lang;
?>
<main id="main-contenet">
    <div class="container">
    <div class="row mt-5">
        <div class="col-4 department-column text-end"><span class="department-name">Departments</span></div>
        <div class="col-8 text-start"><span class="services-name">Services</span></div>
        </div>
        <div class="row mt-2">
       
            <div class="col-4 department-column">
                <div class="d-flex align-items-start border-right-tab">
                    <!-- Tab navs -->
                    <div class="nav services-nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php if (!empty($service_by_dept)) {
                            foreach ($service_by_dept as $key => $dept) {
                                if ($key == 0) { ?>
                                    <a href="#<?= $dept->department_short_name ?>" class="department-name-links nav-link active flat-btn" id="dept-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#dept-<?= $key ?>" role="tab" aria-controls="dept-<?= $key ?>" aria-selected="true"><?= $dept->department_name->$lang ?></a>
                                <?php } else { ?>
                                    <a href="#<?= $dept->department_short_name ?>" class="department-name-links nav-link flat-btn" id="dept-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#dept-<?= $key ?>" role="tab" aria-controls="dept-<?= $key ?>" aria-selected="false"><?= $dept->department_name->$lang ?></a>
                        <?php }
                            }
                        } ?>
                    </div>
                </div>
                <!-- Tab navs -->
            </div>
            <div class="col-8" id="services-content">
                <!-- Tab content -->
                <div class="tab-content" id="tabContent">
                    <?php if (!empty($service_by_dept)) {
                        foreach ($service_by_dept as $key => $dept) {
                            if ($key == 0) {
                    ?>
                                <div class="tab-pane fade show active" id="dept-<?= $key ?>" role="tabpanel" aria-labelledby="dept-<?= $key ?>-tab">
                                    <ul class="list-group list-group-flush"><?php foreach ($dept->services as $services) {
                                                                                if (isset($services->seo_url)) {
                                                                                    $url = $services->seo_url;
                                                                                } else {
                                                                                    $url = $services->service_id;
                                                                                }
                                                                            ?>
                                            <a href="#!" class="list-group-item list-group-item-action list-item-service" data-seo="<?= $url ?>">
                                                <div class="d-flex w-100 justify-content-between"><?= $services->service_name->$lang ?>
                                                </div>
                                                <small>Click here to apply</small>
                                                <div class="display-none align-items-center loader">
                                                    <strong>Loading...</strong>
                                                    <div class="spinner-border ms-auto text-primary" role="status" aria-hidden="true"></div>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <div class="tab-pane fade " id="dept-<?= $key ?>" role="tabpanel" aria-labelledby="dept-<?= $key ?>-tab">
                                    <ul class="list-group list-group-flush"> <?php foreach ($dept->services as $services) {
                                                                                    if (isset($services->seo_url)) {
                                                                                        $url = $services->seo_url;
                                                                                    } else {
                                                                                        $url = $services->service_id;
                                                                                    }
                                                                                ?>
                                            <a href="#!" class="list-group-item list-group-item-action list-item-service" data-seo="<?= $url ?>">
                                                <div class="d-flex w-100 justify-content-between"><?= $services->service_name->$lang ?>
                                                </div>
                                                <small>Click here to apply</small>
                                                <div class="display-none align-items-center loader">
                                                    <strong>Loading...</strong>
                                                    <div class="spinner-border ms-auto text-primary" role="status" aria-hidden="true"></div>
                                                </div>
                                            </a>
                                        <?php
                                                                                } ?>
                                    </ul>
                                </div>
                    <?php }
                        }
                    } ?>
                </div>
                <div id="service_data" class="display-none">
                </div>
                <!-- Tab content -->
            </div>
        </div>
    </div>
</main>
<script type="text/javascript" src="<?=base_url('assets/site/theme1/js/online_services2.js')?>" defer></script>