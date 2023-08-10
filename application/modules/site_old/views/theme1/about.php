<?php
$lang = $this->lang;

$data = $about;
//pre($data);
?>

<main id="main-contenet">
  <div class="container">

    <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-4">
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

    <div class="abt">

      <?= html_entity_decode(htmlspecialchars_decode($data->content->$lang)) ?>
    </div>
  </div>

</main>