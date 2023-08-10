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


<!-- <script src="<?php echo base_url('assets/site/theme1/mapview/js/TOC.js'); ?>"></script> -->

<!-Header File Ends-!>


  <main id="main-contenet">
    <section class="container rtps-contact">
      <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-2 mb-md-0">
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


          <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
        </ol>
      </nav>

      <?= html_entity_decode(htmlspecialchars_decode($data->content->$lang)) ?>


      <section id="rtps_map">
        <div style="overflow-y:auto; position:relative" id="mapid" class="claro">
          <div style="height: 100%; padding:0px;" class="container-fluid">
            <div class="container-fluid">
              <!--<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style=" height:12vh; background-color:#4382b9;">
                    <img src="Images/bb.png" />
                </div>
            </div>-->
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

    </section>
  </main>




  <div id="bodyloadimg" align="center" style="position: absolute; z-index:1000;right: 50%; top: 50%">
    <img alt="Loading.." src="<?php echo base_url('assets/site/theme1/mapview/Images/loading.gif'); ?>" width="80" />
  </div>
  <script>
    function myFunction() {
      var x = document.getElementById("elecdet");

      x.style.display = "none";

    }
  </script>
  <script>
    function opendialogbox() {
      if ($('#elecdet').css('display') == 'none') {
        $('#elecdet').css('display', "block");
      }


      // if ($('#elecdetbtn').css('display') == 'none') {
      //  $('#elecdet').css('display', "none");
      // }
    }

    function opendialogboxforchart() {
      if ($('#chartcontainer').css('display') == 'none') {
        $('#chartcontainer').css('display', "block");
      }
    }

    $(function() {
      $("#elecdet").draggable();
    });
  </script>




  <script src="<?php echo base_url('assets/site/theme1/mapview/js/config.js'); ?>" defer></script>
  <!-- <script src="<?php echo base_url('assets/site/theme1/mapview/js/jsonvar.js'); ?>"></script>  -->
  <script type="text/javascript" src="<?php echo base_url('assets/site/theme1/mapview/APIs/jQuery_dialogboxextend.js'); ?>"></script>

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