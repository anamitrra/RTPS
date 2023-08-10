<?php
$lang = $this->lang;

// pre($settings);

?>


<main id="main-contenet">

<div class="container videos">
<nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-2">
  <ol class="breadcrumb">

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

  <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">


    <?php foreach ($settings->categories as $key => $category): ?>

      <?php $cat_id = $category->short_name  ?>

      <li class="nav-item" role="presentation">

        <button class="nav-link <?= ($key == 0) ? 'active' : '' ?>" id="<?= $cat_id ?>-tab" data-bs-toggle="pill" data-bs-target="#<?= $cat_id ?>" type="button" role="tab" aria-controls="<?= $cat_id ?>" aria-selected="true">
        
        <?= $category->title->$lang ?>
        
        </button>

      </li>

    <?php endforeach; ?>
   
  </ul>

  <div class="tab-content" id="pills-tabContent">

    <?php foreach ($settings->categories as $key => $category): ?>

        <div class="tab-pane fade <?= ($key == 0) ? 'show active' : '' ?> " id="<?= $category->short_name ?>" role="tabpanel" aria-labelledby="<?=$category->short_name?>-tab">
        
       
              <div class="row row-cols-1 row-cols-md-3 g-4">

                <?php foreach ($category->videos as $item): ?>

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


</main>
