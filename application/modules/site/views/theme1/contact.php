<?php
$lang = $this->lang;

$data = $contact;
?>

<link rel="stylesheet" href="<?= base_url('assets/site/theme1/mapview/css/esri.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/site/theme1/mapview/css/dijit.css'); ?>">


<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/theme1/mapview/APIs/agsjs/css/agsjs.css'); ?>">
<link href="<?= base_url('assets/site/theme1/mapview/css/smooth_jq.css'); ?>" rel="stylesheet" type="text/css" />



<!-- <script src="js/D2Map.js"></script> -->
<input type="hidden" id="base" value="<?php echo base_url() ?>">


<style>
  #mapid {
    /* margin: auto; */
    height: 500px;
    width: 100%;
    border: none;
    background-color: lightblue;
    border-radius: 0.5em;
    /* align-content: center; */
  }

  #elecdet {
    position: absolute;
    top: 0;
    left: 30%;
    background-color: black;
    color: wheat;
  }
</style>


<!-- <script src="<!?php echo base_url('assets/site/theme1/mapview/js/TOC.js'); ?>"></script> -->

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
          
         <?= html_entity_decode(htmlspecialchars_decode($data->content->$lang)) ?>
               
             

          <section id="rtps_map">
            <div style="overflow-y:auto; position:relative" id="mapid" class="claro">
              <div style="height: 100%; padding:0px;" class="container-fluid">
                <div class="container-fluid">

                </div>
                <div class="container-fluid" style="height: 100%">
                  <div class="row" style="height: 100%">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:100%; padding:0; width:100%; position: relative;" id="mapDiv">
                      <div style="position:absolute; right:20px; bottom:10px; z-Index:1000;background:white">
                        <div id="basemappane"></div>
                      </div>

                    </div>
                  </div>
                </div>

                <div id="elecdet" class="calcite" style="display:none" onclick="myFunction()">
                  <button id="elecdetbtn"><i class="fas fa-times"></i></button>
                  <div class="dialogbody" id="identifydialog" style="padding: 0px 15px 15px 15px;"></div>
                </div>

              </div>

            </div>
          </section>

        </div>
      </div>
    </div>
  </div>



</main>




<div id="bodyloadimg" align="center" style="position: absolute; z-index:1000;right: 50%; top: 50%">
  <img alt="Loading.." src="<?php echo base_url('assets/site/theme1/mapview/Images/loading.gif'); ?>" width="80" />
</div>





<script src="<?php echo base_url('assets/site/theme1/mapview/js/config.js'); ?>" defer></script>
<!-- <script src="<!?php echo base_url('assets/site/theme1/mapview/js/jsonvar.js'); ?>"></script>  -->
<script type="text/javascript" src="<?php echo base_url('assets/site/theme1/mapview/APIs/jQuery_dialogboxextend.js'); ?>" defer></script>

<script type="text/javascript">
  var dojoConfig = {
    paths: {
      // agsjs: '/mapview/APIs/agsjs'
      // agsjs: '/rtps/assets/site/theme1/mapview/APIs/agsjs'
      agsjs: document.getElementById('base').value + 'assets/site/theme1/mapview/APIs/agsjs'

    }

  };
</script>
<script src="<?= base_url('assets/site/theme1/mapview/js/arc.js'); ?>" defer></script>
<script src="<?php echo base_url('assets/site/theme1/mapview/js/map_lat.js'); ?> " defer></script>
<script src="<?php echo base_url('assets/site/theme1/mapview/js/allscripts.js'); ?> " defer></script>