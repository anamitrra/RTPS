<?php
$lang = $this->lang;

// pre($settings);

?>

<main id="main-contenet">
  <div class="container">

    <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-5">
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


    <section class="bg-light border border-1 rounded-1 p-4 error-404">
      <h1 class="display-6 mb-5"><?= $settings->msg->$lang ?></h1>

      <a role="button" class="btn rtps-btn btn-lg" href="<?=base_url('site')?>"><?= $settings->home_link->$lang ?></a>

    </section>

  </div>
</main>