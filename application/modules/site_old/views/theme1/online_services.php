<?php
$lang = $this->lang;
// pre($service_by_dept);
//pre($settings);
// pre($active);

?>
<script src="<?= base_url('assets/site/theme1/js/online_service.js') ?>" defer></script>

<main id="main-contenet">
    <div class="container">
    <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
  <ol class="breadcrumb m-0">

  <?php foreach ($settings->nav as $key => $link): ?>

    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : ''?>" <?= empty($link->url) ? 'aria-current="page"' : ''?> >

    <?php if(isset($link->url)): ?>
        <a href="<?=base_url($link->url) ?>"><?=  $link->$lang?></a>

    <?php else: ?>
        <?=  $link->$lang ?>


    <?php endif; ?>

    </li>
    <?php endforeach; ?>

  </ol>
</nav>
        <div class="row mt-4">

            <div class="col-3 d-none d-md-block">
                <div class="d-flex align-items-start">
                    <!-- Tab navs -->
                    <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php if (!empty($service_by_dept)) {
                            foreach ($service_by_dept as $key => $dept) {

                                if (strlen($active) == 0 && $key == 0) { ?>

                                    <a href="#<?= $dept->department_short_name ?>" class="nav-link active" id="dept-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#dept-<?= $key ?>" role="tab" aria-controls="dept-<?= $key ?>" aria-selected="true"><?= $dept->department_name->$lang ?></a>
                                  

                                <?php } elseif ($active == $dept->department_short_name) { ?>
                                    <a href="#<?= $dept->department_short_name ?>" class="nav-link active" id="dept-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#dept-<?= $key ?>" role="tab" aria-controls="dept-<?= $key ?>" aria-selected="true"><?= $dept->department_name->$lang ?></a>
                                <?php } else { ?>
                                    <a href="#<?= $dept->department_short_name ?>" class="nav-link" id="dept-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#dept-<?= $key ?>" role="tab" aria-controls="dept-<?= $key ?>" aria-selected="false"><?= $dept->department_name->$lang ?></a>
                        <?php }
                            }
                        } ?>
                    </div>
                </div>
                <!-- Tab navs -->
            </div>
            <div class="col-9 d-none d-md-block">
                <!-- Tab content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <?php if (!empty($service_by_dept)) {

                        foreach ($service_by_dept as $key => $dept) {

                            if (strlen($active) == 0 && $key == 0) { ?>

                                <div class="tab-pane fade show active" id="dept-<?= $key ?>" role="tabpanel" aria-labelledby="dept-<?= $key ?>-tab">
                                    <ul class="list-group"><?php foreach ($dept->services as $services) {
                                                                if (isset($services->seo_url)) {
                                                                    $url = $services->seo_url;
                                                                } else {
                                                                    $url = $services->service_id;
                                                                }
                                                            ?>
                                            <li class="list-service list-group-item d-flex justify-content-between align-items-center"><?= $services->service_name->$lang ?>
                                            <a class="btn rtps-btn ms-1" href="<?= base_url("site/service-apply/" . $url) ?>"> <?= $settings->apply_btn->{$lang} ?> </a></li>
                                        <?php } ?>
                                    </ul>
                                </div>

                            <?php } elseif ($active == $dept->department_short_name) { ?>

                                <div class="tab-pane fade show active" id="dept-<?= $key ?>" role="tabpanel" aria-labelledby="dept-<?= $key ?>-tab">
                                    <ul class="list-group"><?php foreach ($dept->services as $services) {
                                                                if (isset($services->seo_url)) {
                                                                    $url = $services->seo_url;
                                                                } else {
                                                                    $url = $services->service_id;
                                                                }
                                                            ?>
                                            <li class="list-service list-group-item d-flex justify-content-between align-items-center"><?= $services->service_name->$lang ?>
                                            <a class="btn rtps-btn ms-1" href="<?= base_url("site/service-apply/" . $url) ?>"> <?= $settings->apply_btn->{$lang} ?> </a></li>
                                        <?php } ?>
                                    </ul>
                                </div>

                            <?php } else { ?>
                                <div class="tab-pane fade " id="dept-<?= $key ?>" role="tabpanel" aria-labelledby="dept-<?= $key ?>-tab">
                                    <ul class="list-group"> <?php foreach ($dept->services as $services) {
                                                                if (isset($services->seo_url)) {
                                                                    $url = $services->seo_url;
                                                                } else {
                                                                    $url = $services->service_id;
                                                                }
                                                            ?>
                                            <li class="list-service list-group-item d-flex justify-content-between align-items-center"><?= $services->service_name->$lang ?>
                                            <a class="btn rtps-btn ms-1" href="<?= base_url("site/service-apply/" . $url) ?>">
                                            
                                            <?= $settings->apply_btn->{$lang} ?>
                                            
                                            </a></li>
                                        <?php
                                                            } ?>
                                    </ul>
                                </div>
                    <?php }
                        }
                    } ?>
                </div>
                <!-- Tab content -->
            </div>

             <!-- For mobile screens -->
          
            <div class="accordion accordion-flush d-block d-md-none" id="accordionPanelsStayOpenExample">
            
            <?php foreach ($service_by_dept as $key => $value): ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-heading-<?=$key?>">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-<?=$key?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse-<?=$key?>">
                                
                                <i class="fas fa-university fa-lg me-3"></i>
                                <?= $value->department_name->$lang ?>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapse-<?=$key?>" class="accordion-collapse collapse 
                        <?php
                        if (strlen($active) == 0 && $key == 0) {
                            echo 'show';
                        }
                        elseif ($active == $value->department_short_name) {
                            echo 'show';

                        }
                        else {
                            echo '';

                        }
                        ?>
                        " aria-labelledby="panelsStayOpen-heading-<?=$key?>">
                            <div class="accordion-body">
                                
                                    <ul class="list-group">
                                    <?php foreach ($value->services as $service) {?>
                                            <li class="list-service list-group-item d-flex flex-column justify-content-between align-items-center">
                                            
                                                <span class="mb-3"><?= $service->service_name->$lang ?></span>

                                                <a class="btn rtps-btn ms-1" href="<?= base_url('site/service-apply/' . $service->seo_url) ?>">
                                                
                                            
                                                <?= $settings->apply_btn->{$lang} ?>

                                                </a>
                                            </li>

                                    <?php } ?>
                                    </ul>

                            </div>
                        </div>
                    </div>
            
            
            <?php endforeach; ?>
            
            </div>

        </div>
    </div>
</main>
