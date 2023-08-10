<?php
$lang = $this->lang;
?>

<script src="<?= base_url('assets/site/' . $theme . '/js/page_loader.js') ?>" defer></script>
<script src="<?= base_url('assets/site/' . $theme . '/js/citizen_login.js') ?>" defer></script>

<!-- Page Loader -->
<div class="loader-container">
    <div class="bubblingG">
        <span id="bubblingG_1">
        </span>
        <span id="bubblingG_2">
        </span>
        <span id="bubblingG_3">
        </span>
    </div>
</div>


<main id="main-contenet">

    <section class="container citizen-iframe">

        <!-- <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-2">
            <ol class="breadcrumb">

                <?php foreach ($data->nav as $key => $link) : ?>

                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                        <?php else : ?>
                            <?= $link->$lang ?>


                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>

            </ol>
        </nav> -->



    </section>

</main>


<script>
    // var serviceId = '1205';
    var serviceId = '0000';
</script>
