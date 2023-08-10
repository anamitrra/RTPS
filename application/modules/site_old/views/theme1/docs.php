<?php $lang = $this->lang;
$categories = $data->categories;

// pre($data);

?>
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

        <div class="row mt-4">
            <div class="col-3 d-none d-md-block">
                <!-- Tab navs -->
                <div class="d-flex align-items-start">
                    <!-- Tab navs -->
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php if (!empty($categories)) {
                            foreach ($categories as $key => $val) {
                                //pre($val);
                                if ($key == 0) { ?>
                                    <a class="nav-link active" id="docs-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#docs-<?= $key ?>" href="#docs-<?= $val->short_name ?>" role="tab" aria-controls="docs-<?= $key ?>" aria-selected="true"><?= $val->title->$lang ?></a>
                                <?php } else { ?>
                                    <a class="nav-link" id="docs-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#docs-<?= $key ?>" href="#docs-<?= $val->short_name ?>" role="tab" aria-controls="docs-<?= $key ?>" aria-selected="false"><?= $val->title->$lang ?></a>
                        <?php }
                            }
                        } ?>
                    </div>
                    <!-- Tab navs -->
                </div>
            </div>
            <div class="col-9 d-none d-md-block">
                <!-- Tab content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <?php if (isset($categories) && !empty($categories)) {
                        foreach ($categories as $key => $cat) {
                            if ($key == 0) {
                    ?>
                                <div class="tab-pane fade show active" id="docs-<?= $key ?>" role="tabpanel" aria-labelledby="docs-<?= $key ?>-tab">
                                    <ul class="list-group"><?php foreach ($cat->docs as $doc) {
                                                            ?>
                                            <li class="list-service list-group-item d-flex justify-content-between align-items-center"><?= $doc->name->$lang ?>
                                                <a class="btn rtps-btn ms-1" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= $doc->type ?>, <?= $doc->size ?>" target="_blank" rel="noopener noreferrer" href="<?= base_url($doc->path) ?>">

                                                    <!-- download -->


                                                    <?= $data->download_btn->{$lang}  ?>

                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <div class="tab-pane fade " id="docs-<?= $key ?>" role="tabpanel" aria-labelledby="docs-<?= $key ?>-tab">
                                    <ul class="list-group"> <?php if (isset($cat->docs) && count($cat->docs) > 0) : foreach ($cat->docs as $doc) {
                                                            ?>
                                                <li class="list-service list-group-item d-flex justify-content-between align-items-center"><?= $doc->name->$lang ?><a class="btn ms-1 rtps-btn" target="_blank" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= $doc->type ?>, <?= $doc->size ?>" rel="noopener noreferrer" href="<?= base_url($doc->path) ?>">

                                                        <?= $data->download_btn->{$lang}  ?>

                                                    </a></li>
                                        <?php
                                                                }
                                                            endif ?>
                                    </ul>
                                </div>
                    <?php }
                        }
                    }
                    ?>
                </div>
                <!-- Tab content -->
            </div>

            <!-- For mobile screens -->
            <div class="accordion accordion-flush d-block d-md-none" id="accordionPanelsStayOpenExample">

                <?php foreach ($categories as $key => $value) : ?>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-heading-<?= $key ?>">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-<?= $key ?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse-<?= $key ?>">
                                <i class="far fa-file-alt fa-lg me-3"></i>
                                <?= $value->title->$lang ?>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapse-<?= $key ?>" class="accordion-collapse collapse <?= $key == 0 ? 'show' : '' ?> " aria-labelledby="panelsStayOpen-heading-<?= $key ?>">
                            <div class="accordion-body">

                                <ul class="list-group">
                                    <?php foreach ($value->docs as $doc) { ?>
                                        <li class="list-service list-group-item d-flex flex-column justify-content-between align-items-center">

                                            <span class="mb-3"><?= $doc->name->$lang ?></span>

                                            <a class="btn rtps-btn ms-1" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= $doc->type ?>, <?= $doc->size ?>" target="_blank" rel="noopener noreferrer" href="<?= base_url($doc->path) ?>">


                                                <?= $data->download_btn->{$lang}  ?>

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
</main>