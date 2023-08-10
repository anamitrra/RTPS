
<style>
  /* body {visibility:hidden;} */
  .print {
    visibility: visible;
  }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<?php
if (isset($response->applicant_name) && strlen($response->applicant_name)) {
  $applicant_name = $response->applicant_name;
} elseif (isset($response->applicant_prefix) && strlen($response->applicant_prefix)) {
  $applicant_name = $response->applicant_prefix;
} else {
  $applicant_name = '';
} //End of if else

if (isset($response->applicant_first_name) && strlen($response->applicant_first_name)) {
  $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_first_name;
}
if (isset($response->applicant_middle_name) && strlen($response->applicant_middle_name)) {
  $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_middle_name;
}
if (isset($response->applicant_last_name) && strlen($response->applicant_last_name)) {
  $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_last_name;
}
?>
<div class="content-wrapper">
  <div class="container">
    <div class="row " id="print">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>">
            </div>

            <div class="row">
              <h3 class="h2"><u>Application Acknowledgement</u></h3>
            </div>
            <div class="row akno">
              <span><u>Acknowledgement no: </u><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></span>
              <span style="float: right;"><u>Date:</u><?= isset($response->submission_date) ? format_mongo_date($response->submission_date) : '' ?></span>

            
            </div>
            <div class="row">
              <p style="text-align:justify; line-height: 24px">
                Dear <strong><?= $applicant_name ?></strong>,
                <br />
                <br />
                Your application for <strong> Migartion Certificate</strong> has been submitted successfully on <strong><?= isset($response->submission_date) ? format_mongo_date($response->submission_date) : '' ?></strong> and
                your Acknowledgement No. is <strong><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></strong>. Please use this Acknowledgement
                number for tracking the application and for any future communication related to this application.
                If the application has been accepted by the <strong>Assam Higher Secondary Education Council, AHSEC</strong> ,the service shall be provided within
                stipulated timeline . You may raise an appeal at http://https://sewasetu.assam.gov.in/ if the service is not delivered within  <strong><?= isset($response->service_timeline) ? $response->service_timeline  : '' ?> Days</strong> .
                You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

              </p>


            </div>

            <!-- fee description -->
            <?php if (!empty($response->amount) || (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y")) { ?>

              <div class="row">
                The following fees has been collected for this application:

              </div>
              <br />
              <br />
              <div class="row">
                <table>
                  <?php if (!empty($response->amount)) : ?>
                    <tr>
                      <td><b>Application Fee:</b></td>
                      <td><?= $response->amount ?></td>
                    </tr>
                  <?php endif; ?>


                  <?php if (!empty($response->rtps_convenience_fee)) : ?>
                    <tr>
                      <td><b>Convenience fee:</b></td>
                      <td><?= $response->rtps_convenience_fee ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->service_charge)) : ?>
                    <tr>
                      <td><b>Service Charge:</b></td>
                      <td><?= $response->service_charge ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (!empty($response->no_printing_page)) : ?>
                    <tr>
                      <td><b>Printing Fee:</b></td>
                      <td><?= ($response->no_printing_page) * ($response->printing_charge_per_page) . ".00" ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (!empty($response->no_scanning_page)) : ?>
                    <tr>
                      <td><b>Scanning Fee:</b></td>
                      <td><?= ($response->no_scanning_page) * ($response->scanning_charge_per_page) . ".00" ?></td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <td>================</td>
                    <td>========</td>
                  </tr>

                  <tr>
                    <td><b>Total:</b></td>
                    <td><?= calculateTotal($response) ?></td>
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




    <div class="row " id="printAssamese">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>">
            </div>

            <div class="row">
              <h3 class="h2"><u>আবেদন স্বীকৃতি</u></h3>
            </div>
            <div class="row akno">
              <span><u>স্বীকৃতি নম্বৰ: </u><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></span>
              <span style="float: right;"><u>তাৰিখ:</u><?= isset($response->submission_date) ? format_mongo_date($response->submission_date) : '' ?></span>
            </div>
            <div class="row">
              <p style="text-align:justify; line-height: 24px">
                প্ৰিয় <strong><?= $applicant_name ?></strong>,
                <br />
                <br />


                <strong> প্ৰব্ৰজন প্ৰমাণপত্ৰৰ</strong> বাবে দাখিল কৰা আপোনাৰ আবেদনপত্রখন <strong><?= isset($response->submission_date) ? format_mongo_date($response->submission_date) : '' ?></strong> তাৰিখে সফলতাৰে সম্পূর্ণ হৈছে আৰু আপোনাৰ স্বীকৃতি সংখ্যা হৈছে, <strong><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></strong> । ভৱিষ্যতে আবেদন সম্পর্কীয় যিকোনো যোগাযোগৰ বাবে আৰু আবেদনৰ স্থিতি নিৰীক্ষণ কৰিবলৈ, অনুগ্ৰহ কৰি এই স্বীকৃতি নম্বৰটো ব্যৱহাৰ কৰক। যদি আবেদনপত্ৰখন <strong><?= isset($service_row->department_name) ? $service_row->department_name : '' ?></strong> বিভাগৰ দ্বাৰা গ্ৰহণ কৰা হৈছে, তেন্তে <strong><?= isset($response->service_timeline) ? $response->service_timeline  : '' ?></strong> দিনৰ ভিতৰত সেৱা প্ৰদান কৰিব লাগিব। যদি <strong><?= isset($response->service_timeline) ? $response->service_timeline  : '' ?></strong> দিনৰ ভিতৰত সেৱা আগবঢ়োৱা নহয়, তেনেহলে আবেদনকাৰীয়ে http://sewasetu.assam.gov.in/ ত আবেদন কৰিব পাৰে। আপুনি আমাক ১৮০০-৩৪৫-৩৫৭৪ নম্বৰত প্ৰতি সোমবাৰৰ পৰা শনিবাৰলৈ, পুৱা ৮ বজাৰ পৰা নিশা ৮ বজালৈ ফোন কৰিব পাৰে অথবা
                কোনো ধৰণৰ মতামত বা অভিযোগৰ বাবে rtps-assam@assam.gov.in লৈ ইমেইল প্ৰেৰণ কৰিব পাৰে।
              </p>


            </div>

            <!-- fee description -->
            <?php if (!empty($response->amount) || (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y")) { ?>

              <div class="row">
                এই আবেদনৰ বাবে নিম্নলিখিত মাচুল সংগ্ৰহ কৰা হৈছে:

              </div>
              <br />
              <br />
              <div class="row">
                <table>
                  <?php if (!empty($response->amount)) : ?>
                    <tr>
                      <td><b>আবেদন মাচুল:</b></td>
                      <td><?= sprintf("%.2f", $response->amount) ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (strpos($response->rtps_trans_id, "PROMD")) : ?>
                    <tr>
                      <td><b>GST(জিএছটি):</b></td>
                      <td>900.00</td>
                    </tr>
                  <?php endif; ?>

                  <?php if (strpos($response->rtps_trans_id, "ACMRPRCMD")) : ?>
                    <tr>
                      <td><b>GST:</b></td>
                      <td>360.00</td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->rtps_convenience_fee)) : ?>
                    <tr>
                      <td><b>সুবিধা মাচুল:</b></td>
                      <td><?= $response->rtps_convenience_fee ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->service_charge)) : ?>
                    <tr>
                      <td><b>সেৱা মাচুল:</b></td>
                      <td><?= $response->service_charge ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (!empty($response->no_printing_page)) : ?>
                    <tr>
                      <td><b>প্ৰিন্ট কৰা মাচুল:</b></td>
                      <td><?= ($response->no_printing_page) * ($response->printing_charge_per_page) . ".00" ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (!empty($response->no_scanning_page)) : ?>
                    <tr>
                      <td><b>স্কেনিং মাচুল:</b></td>
                      <td><?= ($response->no_scanning_page) * ($response->scanning_charge_per_page) . ".00" ?></td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <td>================</td>
                    <td>========</td>
                  </tr>

                  <tr>
                    <td><b>মুঠ:</b></td>
                    <td><?= calculateTotal($response) ?></td>
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
      <i class="fa fa-print"></i> Print English Acknowledgement
    </button>
    <button type="button" id="btnPrintAssamese" class="btn btn-success mb-2">
      প্ৰিণ্ট অসমস্বীকৃতি
    </button>
    <?php if ($this->session->role) { ?>
      <a href="<?= base_url('iservices/admin/my-transactions') ?>" class="btn btn-primary mb-2 pull-right">
        <i class="fa fa-angle-double-left"></i> Back
      </a>
    <?php } else { ?>
      <a href="<?= base_url('iservices/transactions') ?>" class="btn btn-primary mb-2 pull-right">
        <i class="fa fa-angle-double-left"></i> Back
      </a>
    <?php } //End of if else 
    ?>
  </div>
</div>
<script>
  $(document).ready(function() {

    $("#btnPrint").on('click', function() {
      printDiv('print');

    });


    $("#btnPrintAssamese").on('click', function() {

      printDiv('printAssamese');

    });


    // $("#btnPrint").on('click',function(){
    //     var prtContent = document.getElementById("print");
    // var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    // WinPrint.document.write(prtContent.innerHTML);
    // WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
    // WinPrint.document.close();
    // WinPrint.focus();
    // WinPrint.print();
    // WinPrint.close();
    //   });

    function printDiv(divname) {
      var divContents = document.getElementById(divname).innerHTML;
      var a = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
      a.document.write('<html>');
      a.document.write('<body >  <br>');
      a.document.write(divContents);
      a.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
      a.document.write('</body></html>');
      a.focus();
      a.print();
      a.document.close();

    }

    

  });
</script>
<?php
function calculateTotal($response)
{
  $total = 0;
  if (strpos($response->rtps_trans_id, "ACMRPRCMD") !== false) {
    $total += 360; // Add 360 to the total if "ACMRPRCMD" is found in rtps_trans_id
  }
  if (strpos($response->rtps_trans_id, "PROMD") !== false) {
    $total += 900; // Add 900 to the total if "PROMD" is found in rtps_trans_id
  }


  if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y") {

    if (!empty($response->no_printing_page)) {
      $total  +=  ($response->no_printing_page) * ($response->printing_charge_per_page);
    }

    if (!empty($response->no_scanning_page)) {
      $total +=  ($response->no_scanning_page) * ($response->scanning_charge_per_page);
    }
    if (!empty($response->rtps_convenience_fee)) {
      $total += $response->rtps_convenience_fee;
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
  } else {
    if (!empty($response->amount)) {
      $total += $response->amount;
    }
  }
  return $total . ".00";
}
?>