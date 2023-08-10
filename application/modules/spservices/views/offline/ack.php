<style>
  /* body {visibility:hidden;} */
  .print {
    visibility: visible;
  }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">

<div class="content-wrapper">
  <div class="container">
    <div class="row " id="print">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100" />
            </div>

            <div class="row">
              <h3 class="h2"><u>Application Acknowledgement</u></h3>
            </div>
            <div class="row akno">
              <span><u>Acknowledgement no: </u><?= isset($response->service_data->appl_ref_no) ? $response->service_data->appl_ref_no : '' ?></span>
              <span style="float: right;"><u>Date:</u><?= isset($response->service_data->submission_date) ? $response->service_data->submission_date : '' ?></span>
            </div>
            <div class="row">
              <p>
              Dear <?= ( isset($response->form_data->applicant_name)) ? $response->form_data->applicant_name : 'applicant' ?>,

                <br />
                <br />
                Your application for <?= isset($response->service_data->service_name) ? $response->service_data->service_name : '' ?> has been submitted successfully on <?= isset($response->service_data->submission_date) ? $response->service_data->submission_date : '' ?> and
                your Acknowledgement No. is <?= isset($response->service_data->appl_ref_no) ? $response->service_data->appl_ref_no : '' ?>. Please use this Acknowledgement
                number for tracking the application and for any future communication related to this application.
                If the application has been accepted by the <?= isset($response->service_data->department_name) ? $response->service_data->department_name : '' ?> department the service shall be provided within
                <?= isset($response->service_data->service_timeline) ? $response->service_data->service_timeline : '' ?> days . You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the  <?= isset($response->service_data->service_timeline) ? $response->service_data->service_timeline : '' ?> days.
                You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

              </p>
             
            </div>
           

            <!-- fee description -->
            <?php if (!empty($response->form_data->amount) ) { ?>

              <div class="row">
                The following fees has been collected for this application:

              </div>
              <br />
              <br />
              <div class="row">
                <table>
                  <?php if (!empty($response->form_data->amount)) : ?>
                    <tr>
                      <td><b>Application Fee:</b></td>
                      <td><?=sprintf("%.2f",$response->form_data->amount)  ?></td>
                    </tr>
                  <?php endif; ?>

                
                  <tr>
                    <td>================</td>
                    <td>========</td>
                  </tr>

                  <tr>
                    <td><b>Total:</b></td>
                    <td><?= $response->form_data->amount?></td>
                  </tr>
                </table>
              </div>

            <?php } ?>

            <!-- end of fee description -->

            <br />
            <br />
            <div class="row">
              <b>Thank You.</b>
            </div>
            <div class="row">
              <b>ARTPS</b>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row" id="printAssamese">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100" />
            </div>

            <div class="row">
              <h3 class="h2"><u>আবেদন স্বীকৃতি</u></h3>
            </div>
            <div class="row akno">
              <span><u>স্বীকৃতি নম্বৰ: </u><?= isset($response->service_data->appl_ref_no) ? $response->service_data->appl_ref_no : '' ?></span>
              <span style="float: right;"><u>তাৰিখ:</u><?= isset($response->service_data->submission_date) ? $response->service_data->submission_date : '' ?></span>
            </div>
            <div class="row">
              <p>
                প্ৰিয়  <?= ( isset($response->form_data->applicant_name)) ? $response->form_data->applicant_name : 'applicant' ?>,

                <br />
                <br />

                <?= isset($response->service_data->service_name) ? $response->service_data->service_name : '' ?>  বাবে দাখিল কৰা  আপোনাৰ আবেদনপত্রখন <?= isset($response->service_data->submission_date) ? $response->service_data->submission_date : '' ?> তাৰিখে সফলতাৰে সম্পূর্ণ হৈছে আৰু আপোনাৰ স্বীকৃতি সংখ্যা হৈছে, <?= isset($response->service_data->appl_ref_no) ? $response->service_data->appl_ref_no : '' ?> । ভৱিষ্যতে আবেদন সম্পর্কীয় যিকোনো যোগাযোগৰ বাবে আৰু আবেদনৰ স্থিতি নিৰীক্ষণ কৰিবলৈ, অনুগ্ৰহ কৰি এই স্বীকৃতি নম্বৰটো ব্যৱহাৰ কৰক। যদি আবেদনপত্ৰখন <?= isset($response->service_data->department_name) ? $response->service_data->department_name : '' ?> বিভাগৰ দ্বাৰা গ্ৰহণ কৰা হৈছে, তেন্তে <?= isset($response->service_data->service_timeline) ? $response->service_data->service_timeline : '' ?>  দিনৰ ভিতৰত সেৱা প্ৰদান কৰিব লাগিব। যদি <?= isset($response->service_data->service_timeline) ? $response->service_data->service_timeline : '' ?>  দিনৰ ভিতৰত সেৱা আগবঢ়োৱা নহয়, তেনেহলে আবেদনকাৰীয়ে http://rtps.assam.gov.in/ ত আবেদন কৰিব পাৰে। আপুনি আমাক ১৮০০-৩৪৫-৩৫৭৪ নম্বৰত  প্ৰতি সোমবাৰৰ পৰা শনিবাৰলৈ, পুৱা ৮ বজাৰ পৰা নিশা ৮ বজালৈ ফোন কৰিব পাৰে অথবা 
কোনো ধৰণৰ মতামত বা অভিযোগৰ বাবে  rtps-assam@assam.gov.in লৈ ইমেইল প্ৰেৰণ কৰিব পাৰে। 


              </p>
         
            </div>
          

            <!-- fee description -->
            <?php if (!empty($response->form_data->amount) ) { ?>

              <div class="row">
              এই আবেদনৰ বাবে নিম্নলিখিত মাচুল সংগ্ৰহ কৰা হৈছে:

              </div>
              <br />
              <br />
              <div class="row">
                <table>
                  <?php if (!empty($response->form_data->amount)) : ?>
                    <tr>
                      <td><b>আবেদন মাচুল:</b></td>
                      <td><?=sprintf("%.2f",$response->form_data->amount)  ?></td>
                    </tr>
                  <?php endif; ?>

                  <tr>
                    <td><b>মুঠ:</b></td>
                    <td><?= $response->form_data->amount ?></td>
                  </tr>
                </table>
              </div>

            <?php } ?>

            <!-- end of fee description -->

            <br />
            <br />
            <div class="row">
              <b>ধন্যবাদ</b>
            </div>
            <div class="row">
              <b>এআৰটিপিএছ</b>
            </div>
          </div>
        </div>
      </div>
    </div>


  
  <button type="button" id="btnPrint" class="btn btn-primary mb-2">
    Print English Acknowledgement
  </button>
  <button type="button" id="btnPrintAssamese" class="btn btn-success mb-2">
    প্ৰিণ্ট অসমস্বীকৃতি
  </button>
  <?= !empty($back_to_dasboard) ? $back_to_dasboard : "" ?>
  <!-- <button type="button" id="" class="btn btn-danger mb-2" >
      Exit
    </button> -->
</div>
</div>
<script>
  $(function() {
    $("#btnPrint").on('click', function() {
      var prtContent = document.getElementById("print");
      var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
      WinPrint.document.write(prtContent.innerHTML);
      WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    })

    $("#btnPrintAssamese").on('click', function() {
      var prtContent = document.getElementById("printAssamese");
      var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
      WinPrint.document.write(prtContent.innerHTML);
      WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    })
  })
</script>
<?php
function calculateTotal($response)
{
  $total = 0;
  if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y") {

    if (!empty($response->no_printing_page)) {
      $total  +=  ($response->no_printing_page) * ($response->printing_charge_per_page);
    }

    if (!empty($response->no_scanning_page)) {
      $total +=  ($response->no_scanning_page) * ($response->scanning_charge_per_page);
    }

    if (!empty($response->service_charge)) {
      $total += $response->service_charge;
    }

    if (property_exists($response, 'amountmut') && !empty($response->amountmut)) {
      $total += floatval($response->amountmut);
    }
    if (property_exists($response, 'amountpart') && !empty($response->amountpart)) {
      $total += floatval($response->amountpart);
    }
    if (!empty($response->amount)) {
      $total += floatval($response->amount);
    }
    if (!empty($response->rtps_convenience_fee)) {
      $total += floatval($response->rtps_convenience_fee);
    }
  } else {
    if (!empty($response->amount)) {
      $total += $response->amount;
    }
    if (property_exists($response, 'amountmut') && !empty($response->amountmut)) {
      $total += floatval($response->amountmut);
    }
    if (property_exists($response, 'amountpart') && !empty($response->amountpart)) {
      $total += floatval($response->amountpart);
    }
  }
  return $total . ".00";
}
?>