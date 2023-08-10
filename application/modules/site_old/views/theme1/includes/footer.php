<?php 
$lang = $this->lang;
?>

<!-- Privacy Policy Model -->

<div class="modal top fade" id="privacyModel" tabindex="-1" aria-labelledby="privacyModel" aria-hidden="true" data-bs-keyboard="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
        <h5 class="modal-title"><?= $footer_data->help_deks->privacy->title->$lang ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
        
            <p class="lead"><?= $footer_data->help_deks->privacy->content->$lang ?></p>
        
        </div>
            
        </div>
    </div>

</div>
<!-- Modal Ends -->


<footer class="mt-5 pt-4">

<!-- External Links -->
<section class="container imp-links border-bottom border-light pb-4">
    <div class="row">

        <div class="col-md mb-4 mb-md-0">

            <h6 class="text-uppercase pb-2">
            <?= $footer_data->imp_links->title->{$lang} ?>
            </h6>

            <ul class="list-unstyled mb-0 fw-light">

            <?php foreach ($footer_data->imp_links->links as $link): ?>
                
                <li>
                    <a href="<?= $link->url ?>" target="_blank" rel="noopener noreferrer">
                        <?= $link->{$lang} ?>
                    </a>
                </li>
            
            <?php endforeach; ?>

            </ul>
        </div>

        <div class="col-md mb-4 mb-md-0">

            <h6 class="text-uppercase pb-2">
                <?= $footer_data->useful_links->title->{$lang} ?>
            </h6>

            <ul class="list-unstyled mb-0 fw-light">

            <?php foreach ($footer_data->useful_links->links as $link): ?>
                
                <li>
                    <a href="<?= $link->url ?>" target="_blank" rel="noopener noreferrer">
                        <?= $link->{$lang} ?>
                    </a>
                </li>
            
            <?php endforeach; ?>
            
            </ul>
        </div>

        <div class="col-md mb-4 mb-md-0">

            <h6 class="text-uppercase pb-2">
                <?= $footer_data->help_deks->title->{$lang} ?>
            </h6>

            <ul class="list-unstyled mb-0 fw-light">

                <?php foreach ($footer_data->help_deks->content as $item): ?>
                    
                    <li>
                        <strong><?= $item->title->{$lang} ?> : </strong> <span><?= $item->contact->{$lang} ?></span>

                        <?php if ( isset($item->text) ): ?>
                            
                            <p class=""><?= $item->text->{$lang}  ?></p>

                        <?php endif; ?>

                    </li>
                
                <?php endforeach; ?>

                <li class="social-links mt-3 d-flex align-items-center">

                    <span class="me-3"><?= $footer_data->help_deks->social_links->title->$lang ?></span class="me-2">

                    <?php foreach ($footer_data->help_deks->social_links->links as $item): ?>

                        <a class="me-2 d-inline-block" href="<?= $item->link ?>" title="<?= $item->title ?>" target="_blank" rel="noopener noreferrer">
                                            
                            <i class="<?= $item->icon ?>" aria-hidden="true"></i>
                                    
                        </a>

                    <?php endforeach; ?>
                 
                </li>

                <li class="mt-2">
                    <a href="#" id="privacyPolicy"><?= $footer_data->help_deks->privacy->title->$lang ?></a>

                </li>
               
            </ul> 
        </div>
    </div>
</section>

<!-- NIC logo & Govt. Icons -->
<section class="container-fluid">
    <div class="row">

        <div class="col-12">

            <div class="row py-3">
                <div class="col-lg-3 text-center">

                    <a href="https://dispur.nic.in/" target="_blank" rel="noopener noreferrer" title="NIC, Dispur">
                        <img src="<?=base_url('assets/site/'.$theme.'/images/footer/nic-footer-logo.png')?>"
                            alt="NIC logo" width="130">
                    </a>

                </div>
                <div class="col-lg-9">
                    <p class="fw-light text-start d-none d-md-block rtps-footer-text pe-3">
                        <?= $footer_data->footer_text->{$lang}  ?>
                    </p>
                </div>
            </div>

        </div>

        <div class="col-12 d-flex justify-content-center align-items-center flex-wrap">

            <a href="https://transformingindia.mygov.in/" target="_blank" rel="noopener noreferrer"
                title="Transforming India">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/Transforming-india-logo.png')?>"
                    alt="Transforming India logo" width="100">
            </a>

            <a href="https://innovate.mygov.in/" target="_blank" rel="noopener noreferrer" title="MyGov Innovate">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/innovation-logo.png')?>"
                    alt="MyGov Innovate logo" width="100">
            </a>

            <a href="https://swachhbharat.mygov.in/" target="_blank" rel="noopener noreferrer" title="Swachhbharat">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/swachh-bharat.png')?>"
                    alt="Swachhbharat logo" width="100">
            </a>

            <a href="https://quiz.mygov.in/" target="_blank" rel="noopener noreferrer" title="MyGov Quiz">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/mygov_quiz.png')?>" alt="MyGov Quiz logo" width="100">
            </a>

            <a href="https://blog.mygov.in/" target="_blank" rel="noopener noreferrer" title="MyGov Blog">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/blog-logo.png')?>" alt="MyGov Blog logo" width="100">
            </a>

            <a href="https://self4society.mygov.in/" target="_blank" rel="noopener noreferrer" title="Self4Society">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/itowe-logo.png')?>" alt="Self4Society Logo" width="100">
            </a>

            <a href="https://egreetings.gov.in/" target="_blank" rel="noopener noreferrer" title="e-Greetings">

                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/e-greating.png')?>" alt="e-Greetings logo" width="100">
            </a>

        </div>

        <div class="col-12 d-flex justify-content-center flex-wrap align-items-center">

            <a href="https://www.digitalindia.gov.in/" target="_blank" rel="noopener noreferrer" title="Digital India">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/digital-india-logo.png')?>"
                    alt="Digital India logo" width="100">
            </a>

            <a href="https://data.gov.in/" target="_blank" rel="noopener noreferrer" title="Data site">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/data-gov-logo.png')?>"
                    alt="Data site logo" width="100">
            </a>

            <a href="https://www.india.gov.in/" target="_blank" rel="noopener noreferrer" title="National site of India">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/india-gov-logo.png')?>"
                    alt="National site of India logo" width="100">
            </a>

            <a href="https://www.mygov.in/" target="_blank" rel="noopener noreferrer" title="MyGov">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/mygov-footer-logo.png')?>" alt="MyGov logo" width="100">
            </a>

            <a href="https://www.meity.gov.in/" target="_blank" rel="noopener noreferrer" title="Meity">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/Meity_logo.png')?>" alt="Meity logo" width="100">
            </a>

            <a href="https://www.pmindia.gov.in/" target="_blank" rel="noopener noreferrer" title="PMINDIA">
                <img src="<?=base_url('assets/site/'.$theme.'/images/footer/itowe-logo.png')?>" alt="PMINDIA logo" width="100">
            </a>

        </div>

    </div>
</section>

 <?php 
 
//  $this->load->view("chatbot/index");
 
 ?> 

</footer>

</html>