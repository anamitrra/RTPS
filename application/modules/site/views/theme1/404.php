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


<!-- Main Content -->
<main id="main-contenet">

  <div class="container-xxl py-3">
    <div class="container extra-margin-bottom">
      <div class="row g-5">
        <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

          <section class="bg-light border border-1 rounded-1 p-4 error-404">

            <h1 class="display-6 mb-5"><?= $settings->msg->$lang ?></h1>

            <a role="button" class="btn rtps-btn-alt btn-lg" href="<?= base_url('site') ?>"><?= $settings->home_link->$lang ?></a>
          </section>


        </div>
      </div>
    </div>
  </div>


</main>
