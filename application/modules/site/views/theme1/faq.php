<?php
$lang = $this->lang;
$data = $faq;
// pre($data);
?>


<!-- Breadcrumb Start -->
<div class="container-fluid bg-primary mb-5 page-header">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 text-start">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                        <?php foreach ($data->nav as $key => $link) : ?>

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

                    <h1 class="pb-2 border-4 border-bottom mb-3" style="letter-spacing: 0.1em;"><?= $data->heading->$lang ?></h1>

                    <div class="accordion accordion-flush">

                        <?php foreach ($data->content as $key => $val) : ?>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-heading<?= $key ?>">
                                    <button class="accordion-button <?= ($key > 0) ? 'collapsed' : ''  ?>" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?= $key ?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse<?= $key ?>">
                                        <strong><?= $val->question->$lang ?></strong>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse<?= $key ?>" class="accordion-collapse collapse <?= ($key == 0) ? 'show' : '' ?>" aria-labelledby="panelsStayOpen-heading<?= $key ?>">
                                    <div class="accordion-body">
                                        <p><i class="fa fa-check-circle me-2" style="color: yellowgreen; font-size: 1.6em;" aria-hidden="true"></i> <?= $val->ans->$lang ?></p>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>