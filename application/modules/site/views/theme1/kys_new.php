<?php
$lang = $this->lang;
// var_dump($kys);die;
// pre($settings);
$common_login = base_url('spservices/commonapplication/apply/');
$url_for_pfc = '';

?>


<script src="<?= base_url('assets/site/theme1/js/kys.js') ?>" defer></script>

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

               <?php if (!empty($kys)) : ?>

                  <section class="text-center my-3 kys-header pb-2 border-bottom">
                     <p class="fw-bolder d-none d-md-block">
                        <span style="color: darkblue; border-bottom: 3px solid red; padding-bottom: 0.2em;">
                           <?= $settings->kys_title->$lang ?></span>
                     </p>
                     <h3 class="fs-3 fw-bold"><?= $kys->service_name->$lang  ?></h3>
                  </section>

                  <div class="py-3 text-start text-md-end kys-panel">

                     <!-- Service disabled message -->
                     <?php if (empty($kys->enabled)) : ?>

                        <div class="alert alert-danger d-flex justify-content-center fw-bold" role="alert">

                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Info:">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                           </svg>
                           <div>
                              <?= $settings->model_body->$lang ?>
                           </div>
                        </div>

                     <?php endif; ?>

                     <!-- Service Apply Link -->
                     <?php if (!empty($kys->enabled) && !empty($kys->online)) : ?>

                        <!-- Case 1:  spservice/iservices -->
                        <?php if (!empty($kys->service_url) &&  preg_match('/(spservices)|(iservices)/imu', $kys->service_url)) : ?>

                           <?php
                           $path = trim(parse_url($kys->service_url)['path'], " \/\n\t") ?? '';
                           $url = $url_for_pfc = base_url("{$path}");
                           ?>

                           <!-- For skill services with aadhar and non-aadhar -->
                           <?php if ($kys->department_id == 2193) : ?>

                              <!-- Aadhar based -->
                              <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode($url) ?>">
                                 <?= $settings->apply_aadhar->$lang ?>
                              </a>


                              <!-- Non-Aadhar based -->
                              <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode(base_url("services/directApply.do?serviceId={$kys->service_id}")) ?>">

                                 <?= $settings->apply_nonaadhar->$lang ?>

                              </a>

                           <?php else : ?>

                              <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode($url) ?>">
                                 <?= $settings->apply_btn->$lang ?>
                              </a>

                           <?php endif; ?>


                           <!-- Case 2:  3rd party services -->
                        <?php elseif (!empty($kys->service_url) && !preg_match('/(spservices)|(iservices)/imu', $kys->service_url)) : ?>

                           <?php
                           $url_for_pfc = $kys->service_url;
                           ?>

                           <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode($kys->service_url) ?>">
                              <?= $settings->apply_btn->$lang ?>
                           </a>

                           <!-- no reg link -->


                           <!-- Case 3: RTPS/EODB sp login -->
                        <?php elseif (empty($kys->service_url) &&  preg_match('/(RTPS)|(EODB)/imu', $kys->service_type)) : ?>

                           <?php if ($kys->service_type == 'EODB') : ?>

                              <?php
                              $url_for_pfc = base_url("services/directApply.do?serviceId=0000");
                              ?>


                              <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode(base_url("services/directApply.do?serviceId=0000")) ?>">
                                 <?= $settings->apply_btn->$lang ?>
                              </a>



                           <?php else : ?>

                              <?php
                              $url_for_pfc = base_url("services/directApply.do?serviceId={$kys->service_id}");
                              ?>

                              <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode(base_url("services/directApply.do?serviceId={$kys->service_id}")) ?>">
                                 <?= $settings->apply_btn->$lang ?>
                              </a>


                           <?php endif; ?>



                           <!-- Case 4: EODB redirectional services -->

                        <?php elseif ($kys->service_type === 'NA' && ($kys->ext_service_type ?? '') === 'EODB') : ?>

                           <?php
                           $service_id = base64_encode($kys->service_id);
                           $url = $url_for_pfc = "https://eodb.assam.gov.in/resources/homePage/18/eodbnew/rtps-login/index.html?service-id={$service_id}";


                           ?>

                           <a class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $settings->tooltip->$lang ?>" target="_blank" rel="noopener noreferrer" href="<?= $common_login . base64url_encode($url) ?>">
                              <?= $settings->apply_btn->$lang ?>
                           </a>


                           <a class="btn rtps-btn-alt" target="_blank" rel="noopener noreferrer" href="https://eodb.assam.gov.in/resources/homePage/18/eodbnew/register.html"><?= $settings->reg_btn->$lang ?></a>


                        <?php endif; ?>



                     <?php endif; ?>



                  </div>

                  <!-- Tabs  -->

                  <ul class="nav nav-tabs justify-content-start" id="myTab" role="tablist">

                     <!-- Guidelines -->
                     <li class="nav-item" role="presentation">

                        <a class="nav-link active" id="service-guidelines-tab" data-bs-toggle="tab" data-bs-target="#service-guidelines" type="button" role="tab" aria-controls="service-guidelines" aria-selected="true">
                           <?= $settings->tab_1->$lang ?>
                        </a>

                     </li>

                     <!-- Requirements -->
                     <li class="nav-item" role="presentation">

                        <a class="nav-link" id="service-requirments-tab" data-bs-toggle="tab" data-bs-target="#service-requirments" type="button" role="tab" aria-controls="service-requirments" aria-selected="true">
                           <?= $settings->tab_2->$lang ?>
                        </a>


                     </li>

                     <!-- Important Documents, if any -->
                     <?php if (!empty($kys->documents)) : ?>

                        <li class="nav-item" role="presentation">

                           <a class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab" aria-controls="docs" aria-selected="true">
                              <?= $settings->tab_3->$lang ?>
                           </a>

                        </li>


                     <?php endif; ?>

                     <!-- Service Related Notice, if any -->
                     <?php if (!empty($kys->notice)) : ?>

                        <li class="nav-item" role="presentation">

                           <a class="nav-link" id="notice-board-tab" data-bs-toggle="tab" data-bs-target="#notice-board" type="button" role="tab" aria-controls="notice-board" aria-selected="true">
                              <?= $settings->tab_4->$lang ?>
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

                           <ul class="">
                              <?php foreach ($kys->documents as $doc) : ?>

                                 <li class="fw-bold fs-6 mb-2">
                                    <a href="<?= base_url($doc->path) ?>" target="_blank" rel="noopener noreferrer" class="text-capitalize text-decoration-none">
                                       <i class="fas fa-file-alt fa-lg me-2"></i>
                                       <?= $doc->name->$lang ?>
                                    </a>
                                 </li>

                              <?php endforeach; ?>
                           </ul>

                        </div>

                     <?php endif; ?>


                     <?php if (isset($kys->notice)) : ?>
                        <div class="tab-pane fade py-3" id="notice-board" role="tabpanel" aria-labelledby="notice-board-tab">


                           <?= !empty($kys->notice->$lang) ? html_entity_decode(htmlspecialchars_decode($kys->notice->$lang)) : "" ?>

                        </div>
                     <?php endif; ?>

                  </div>

               <?php else : ?>

                  <img class="img-fluid rounded mx-auto d-block" src="<?= base_url('assets/site/theme1/images/nf.png') ?>" alt="result not found">

                  <p class="text-center text-muted"><?= $header->not_found->$lang ?></p>

               <?php endif; ?>

            </div>
         </div>
      </div>
   </div>

</main>

<script>
   var kysTitle = "<?= $settings->kys_ask->title->$lang ?>";
   var citiApply = "<?= $settings->kys_ask->options[0]->$lang ?>";
   var pfcApply = "<?= $settings->kys_ask->options[1]->$lang ?>";
   var cscApply = "<?= $settings->kys_ask->options[2]->$lang ?>";
   var serviceApplyLink = "<?= $url_for_pfc ?>"
</script>