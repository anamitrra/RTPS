<?php
$lang = $this->lang;

// pre($settings);

?>


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


          <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">


            <?php foreach ($settings->categories as $key => $category) : ?>

              <?php $cat_id = $category->short_name  ?>

              <li class="nav-item" role="presentation">

                <button class="nav-link <?= ($key == 0) ? 'active' : '' ?>" id="<?= $cat_id ?>-tab" data-bs-toggle="pill" data-bs-target="#<?= $cat_id ?>" type="button" role="tab" aria-controls="<?= $cat_id ?>" aria-selected="true">

                  <?= $category->title->$lang ?>

                </button>

              </li>

            <?php endforeach; ?>

          </ul>

          <div class="tab-content" id="pills-tabContent">

            <?php foreach ($settings->categories as $key => $category) : ?>

              <div class="tab-pane fade <?= ($key == 0) ? 'show active' : '' ?> " id="<?= $category->short_name ?>" role="tabpanel" aria-labelledby="<?= $category->short_name ?>-tab">


                <div class="row row-cols-1 row-cols-md-3 g-4">

                  <?php foreach ($category->videos as $item) : ?>

                    <div class="col">
                      <div class="card shadow-sm h-100">

                        <div class="card-header">

                          <iframe width="100%" height="300" src="<?= $item->url ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                        </div>

                        <div class="card-body">
                          <h5 class="card-title">
                            <?= $item->name->$lang ?>
                          </h5>

                        </div>
                      </div>
                    </div>

                  <?php endforeach; ?>

                </div>


              </div>

            <?php endforeach; ?>

          </div>

        </div>
      </div>
    </div>
  </div>


</main>