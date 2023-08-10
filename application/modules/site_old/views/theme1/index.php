<?php
$lang = $this->lang;

// pre($all_depts);
?>

<link rel="stylesheet" href="<?= base_url('assets/site/'.$theme.'/css/index.css') ?>">

<script src="<?= base_url('assets/site/'.$theme.'/js/index.js') ?>" defer></script>


<main>

    <!-- Banners -->
    <div id="carouselExampleDark" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover" data-bs-touch="true">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            
        <?php foreach ($settings->banners as $key => $banner): ?>

            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?=  $key ?>" class="<?= ($key == 0) ? 'active': ''  ?>" aria-current="true" aria-label="Banner <?= $key+1 ?>"></button>
            
        <?php endforeach; ?>

        </ol>

        <!-- Inner -->
        <div class="carousel-inner">

        <?php foreach ($settings->banners as $key => $banner): ?>
            
            <div class="carousel-item <?= ($key == 0) ? 'active': '' ?>">
                <picture>
                    <source type="image/webp" srcset="<?= base_url($banner->webp) ?>">

                    <img class="d-block w-100" alt="Banner <?= $key+1 ?>" src="<?= base_url($banner->jpg) ?>">
                </picture>
            </div>

        <?php endforeach; ?>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"  data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"  data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>



    <!-- RTPS Dashboard  -->
    <section class="container mb-3" id="main-contenet">
        <section class="dashboard-data d-flex justify-content-center align-items-stretch">

            <div class="card rcv text-center shadow" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="<span><?= $settings->genders->m->{$lang}  ?> :  [m]</span><br><span><?= $settings->genders->f->{$lang}  ?> : [f]</span><br><span><?= $settings->genders->o->{$lang}  ?> : [o]</span><br><span><?= $settings->genders->na->{$lang}  ?> : [na]</span>">

                <div class="card-header">
                    <i class="fas fa-copy fa-3x"></i>
                </div>

                <div class="card-body">
                    <h4 class="card-title figure">
                    
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    
                    </h4>
                    <p class="card-text"><?= $settings->top_grid->grid_1->$lang ?></p>
                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>


            <div class="card pen text-center shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <i class="fas fa-clock fa-3x"></i>
                </div>
                <div class="card-body">
                    <h4 class="card-title figure">
                    
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    
                    </h4>
                    <p class="card-text"><?= $settings->top_grid->grid_2->$lang ?></p>
                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>

            <div class="card pentime text-center shadow"  data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <i class="fas fa-hourglass-half fa-3x"></i>
                </div>
                <div class="card-body">
                    <h4 class="card-title figure">
                    
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    
                    </h4>
                    <p class="card-text"><?= $settings->top_grid->grid_3->$lang ?></p>
                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>
            <div class="card dis text-center shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <i class="fas fa-check fa-3x"></i>
                </div>
                <div class="card-body">
                    <h4 class="card-title figure">

                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    
                    </h4>
                    <p class="card-text"><?= $settings->top_grid->grid_4->$lang ?></p>
                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
            </div>
            <div class="card del text-center shadow"  data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->dashbord_card_tooltip->$lang ?>">
                <div class="card-header">
                    <i class="fas fa-trophy fa-3x"></i>
                </div>
                <div class="card-body">
                    <h4 class="card-title figure">
                    
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    
                    </h4>
                    <p class="card-text"><?= $settings->top_grid->grid_5->$lang ?></p>
                    <!-- To make the whole card clickable -->
                    <a href="<?= base_url("site/service-wise-data") ?>" class="stretched-link"></a>
                </div>
               
            </div>
        </section>
        <section class="disclaimer text-center">
            <p class="d-inline-block lead fs-6">
            <span class="badge rounded-pill bg-danger"> <?= $settings->note->$lang ?></span>
                <?= $settings->notes->$lang ?>
            </p>
        </section>
    </section>
    <!-- Services & Login Panel Section -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs navs -->
                        <ul class="nav nav-tabs" id="ex1" role="tablist">
                            <li class="nav-item" role="role1">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#available-services" type="button" role="tab" aria-controls="home" aria-selected="true">
                                    <?= $settings->service_lists[0]->{$lang}  ?>
                                </a>
                            </li>
                            <li class="nav-item" role="role2">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#service-by-department" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                    <?= $settings->service_lists[1]->{$lang}  ?>
                                </a>
                            </li>
                        </ul>
                        <!-- Tabs navs -->
                        <!-- Tabs content -->
                        <div class="tab-content" id="ex2-content">
                            <!-- Avaliable Services -->
                            <div class="tab-pane fade show active" id="available-services" role="tabpanel" aria-labelledby="ex2-tab-1">
                                <ul class="list-group list-group-flush available-services">
                                    <?php foreach ($all_services as $service) : ?>
                                        <li class="list-group-item">
                                            <a href="<?= base_url(isset($service->seo_url) ? "site/service-apply/" . $service->seo_url : "") ?>" class="stretched-link">
                                                <i class="fas fa-square fa-xs"></i>
                                                <?= $service->service_name->$lang ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <!-- Services By Depts -->
                            <div class="tab-pane fade services-by-dept" id="service-by-department" role="tabpanel" aria-labelledby="ex2-tab-2">
                                <div class="accordion accordion-flush" id="accordionDept">
                                    <?php foreach ($all_depts as $department) : ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-heading
                                                    <?= $department->department_id ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $department->department_id ?>" aria-expanded="false" aria-controls="flush-collapse<?= $department->department_id ?>">
                                                    <i class="fas <?= $department->icon ?> fa-lg"></i>
                                                    <?= $department->department_name->$lang ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapse<?= $department->department_id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $department->department_id ?>" data-bs-parent="#accordionDept">
                                                <div class="accordion-body">
                                                    <div class="list-group list-group-flush">
                                                        <?php foreach ($department->services as $service_obj) : ?>
                                                            <a href="<?= base_url(isset($service_obj->seo_url) ? "site/service-apply/" . $service_obj->seo_url : "") ?>" class="list-group-item list-group-item-action">
                                                                <i class="fas fa-square fa-xs"></i>
                                                                <?= !empty($service_obj->service_name->$lang) ? $service_obj->service_name->$lang : "" ?>
                                                            </a>

                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Tabs content -->
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row gy-5 align-items-start">
                    <!-- Login Panel -->
                    <div class="col-md col-lg-12 order-md-1 order-lg-0">
                        <div class="card p-1">
                            <div class="card-body d-flex flex-column btn-panel">
                                
                                <button type="button" class="btn btn-lg my-2 rtps-btn login">
                                    <i class="fas fa-user fa-lg"></i>
                                    <?= $settings->right_menu_1->login->$lang ?>
                                </button>

                                <button type="button" class="btn btn-lg my-2 rtps-btn" data-bs-toggle="modal" data-bs-target="#trackModal">
                                    <i class="fas fa-paste fa-lg"></i>
                                    <?= $settings->right_menu_1->track_application->$lang ?>
                                </button>


                                <a class="btn btn-lg my-2 rtps-btn" target="_blank" href="<?= $settings->right_menu_1->grievance->url ?>" role="button" rel="noopener noreferrer">
                                    <i class="fas fa-file-alt fa-lg"></i>
                                    <?= $settings->right_menu_1->grievance->{$lang} ?>

                                </a>

                                <a class="btn btn-lg my-2 rtps-btn" href="<?= $settings->right_menu_1->appeal->url ?>" role="button">
                                    <i class="fas fa-book fa-lg"></i>
                                    <?= $settings->right_menu_1->appeal->{$lang} ?>
                                
                                </a>

                            </div>
                        </div>
                    </div>
                    <!-- GuideLines Panel -->
                    <div class="col-md col-lg-12 order-md-0 order-lg-1">
                        <div class="card p-1 guide-lines">
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php
                                    if (!empty($settings->right_menu_2)) {
                                        foreach ($settings->right_menu_2 as $menu) { ?>
                                            <li class="list-group-item p-3">


                                            <?php if (stristr($menu->link, 'storage')): ?>
                                                
                                                <a href="<?= site_url() . $menu->link ?>" target="_blank" rel="noopener noreferrer" class="stretched-link">
                                                    <i class="<?= $menu->icon ?>"></i>
                                                    <?= $menu->name->$lang ?></a>
                                            
                                            <?php else: ?>
                                            
                                                <a href="<?= site_url() . $menu->link ?>" class="stretched-link">
                                                    <i class="<?= $menu->icon ?>"></i>
                                                    <?= $menu->name->$lang ?></a>

                                            <?php endif; ?>

                                            </li>
                                    <?php }
                                    }
                                    ?>
                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Services -->
                    <div class="col-md col-lg-12 order-md-2 order-lg-2">
                        <div class="card p-1">
                            <div class="card-body">
                                <ul class="list-group list-group-flush recent-services">
                                    <li class="list-group-item text-center sticky-top pb-3 text-uppercase fw-bold fs-6">
                                        <?= $settings->service_lists[2]->{$lang}  ?>
                                    </li>
                                    <?php foreach ($recent_services as $service) : ?>
                                        <li class="list-group-item">
                                            <a href="<?= base_url(isset($service->seo_url) ? "site/service-apply/" . $service->seo_url : "") ?>" class="stretched-link">
                                                <img src="<?= base_url('assets/site/'.$theme.'/images/service.png') ?>" alt="service icon" width="30" class="d-inline-block">
                                                <?= $service->service_name->$lang ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
