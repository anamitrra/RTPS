<?php
$lang = $this->lang;
// pre($kys);

?>

<script src="<?= base_url('assets/site/' . $theme . '/js/demo.js') ?>" defer></script>

<main id="main-contenet">
   <div class="container">
      <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-end align-items-baseline">
         <ol class="breadcrumb m-0">

            <?php foreach ($settings->nav as $key => $link) : ?>

               <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                  <?php if (isset($link->url)) : ?>
                     <a href="<?= base_url($link->url)  ?>"><?= $link->$lang ?></a>

                  <?php else : ?>
                     <?= $link->$lang ?>


                  <?php endif; ?>

               </li>
            <?php endforeach; ?>


            <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
         </ol>
      </nav>

      <?php if (!empty($kys)) : ?>

         <section class="text-center my-3 kys-header pb-2 border-bottom">
            <p class="fw-bolder d-none d-md-block">
               <span style="color: darkblue; border-bottom: 3px solid red; padding-bottom: 0.2em;">
                  <?= $settings->kys_title->$lang ?></span>
            </p>
            <h3 class="fs-3 fw-bold"><?= $kys->service_name->$lang  ?></h3>
         </section>

         <div class="py-3 text-start text-md-end kys-panel">

            <!-- check if service URL exists -->
            <?php if (!empty($kys->service_url)) : ?>

               <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" class="btn rtps-btn me-2" target="_blank" rel="noopener noreferrer" href="<?= $kys->service_url ?>">
                  <?= $settings->apply_btn->$lang ?>
               </a>
               <!-- For skill services with aadhar and non-aadhar -->
            <?php elseif ($kys->department_id == 2193) : ?>

               <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" class="btn rtps-btn skill_apply_btn_aadhar me-2" data-service-id="<?= $kys->service_id_aadhar  ?>">
                  <?= $settings->apply_aadhar->$lang ?>
               </button>

               <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" class="btn rtps-btn my-2 my-md-0 skill_apply_btn me-2" data-service-id="<?= $kys->service_id  ?>">
                  <?= $settings->apply_nonaadhar->$lang ?>
               </button>

            <?php else : ?>

               <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" class="btn rtps-btn me-2" id="service-apply-btn" data-service-id="<?= $kys->service_id  ?>">
                  <?= $settings->apply_btn->$lang ?>
               </button>

            <?php endif; ?>

            <a class="btn rtps-btn" href="<?= base_url('site/citizen_registration') ?>"><?= $settings->reg_btn->$lang ?></a>
         </div>

         <!-- Tabs  -->

         <ul class="nav nav-tabs justify-content-start" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">

               <a class="nav-link active" id="service-guidelines-tab" data-bs-toggle="tab" data-bs-target="#service-guidelines" type="button" role="tab" aria-controls="service-guidelines" aria-selected="true">
                  <?= $settings->tab_1->$lang ?>
               </a>

            </li>
            <li class="nav-item" role="presentation">

               <a class="nav-link" id="service-requirments-tab" data-bs-toggle="tab" data-bs-target="#service-requirments" type="button" role="tab" aria-controls="service-requirments" aria-selected="true">
                  <?= $settings->tab_2->$lang ?>
               </a>


            </li>

            <?php if (!empty($kys->documents)) : ?>

               <li class="nav-item" role="presentation">

                  <a class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab" aria-controls="docs" aria-selected="true">
                     <?= $settings->tab_3->$lang ?>
                  </a>

               </li>


            <?php endif; ?>

         </ul>
         <div class="kys-tab tab-content" id="myTabContent">

            <div class="tab-pane fade show active py-3" id="service-guidelines" role="tabpanel" aria-labelledby="service-guidelines-tab">


               <?= !empty($kys->guide_lines->$lang) ? html_entity_decode(htmlspecialchars_decode($kys->guide_lines->$lang)) : "" ?>

            </div>

            <div class="tab-pane fade py-3" id="service-requirments" role="tabpanel" aria-labelledby="service-requirments-tab">


               <?= !empty($kys->requirements->$lang) ? html_entity_decode(htmlspecialchars_decode($kys->requirements->$lang)) : "" ?>

            </div>

            <?php if (isset($kys->documents)) : ?>

               <div class="tab-pane fade py-3" id="docs" role="tabpanel" aria-labelledby="docs-tab">

                  <ul>
                     <?php foreach ($kys->documents as $doc) : ?>

                        <li class="fw-bold fs-6">
                           <a href="<?= base_url($doc->path) ?>" target="_blank" rel="noopener noreferrer">
                              <i class="fas fa-file-alt fa-lg"></i>
                              <?= $doc->name->$lang ?>
                           </a>
                        </li>

                     <?php endforeach; ?>
                  </ul>

               </div>

            <?php endif; ?>


         </div>

   </div>

<?php else : ?>

   <img class="img-fluid rounded mx-auto d-block" src="<?= base_url('assets/site/' . $theme . '/images/nf.png') ?>" alt="result not found">

   <p class="text-center text-muted"><?= $header->not_found->$lang ?></p>

<?php endif; ?>

</main>