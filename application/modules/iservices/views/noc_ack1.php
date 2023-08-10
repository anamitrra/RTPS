<style>
  /* body {visibility:hidden;} */
  .print {
    visibility: visible;
  }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<?php
$dept = substr($response->app_ref_no, 0, 3);
?>
<div class="content-wrapper">
  <div class="container">
    <div class="row " id="print">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" style="width:auto ;" width="100" height="100" />
            </div>

            <div class="row">
              <h3 class="h2"><u>Application Acknowledgement</u></h3>
            </div>
            <div class="row akno">
              <span><u>Acknowledgement no: </u><?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?></span>
              <span style="float: right;"><u>Date:</u><?= isset($response->submission_date) ? $response->submission_date : '' ?></span>
            </div>
            <div class="row">
              <p>
                <?php
                if ($response->portal_no === "5" || $response->portal_no === 5) { ?>
                  Dear Applicant,
                <?php  } else { ?>
                  Dear <?= (is_array($response->applicant_details) && isset($response->applicant_details)) ? $response->applicant_details[0]->applicant_name : '' ?>,
                <?php  }
                ?>

                <br />
                <br />
                Your application for <?= isset($service_name) ? $service_name : '' ?> has been submitted successfully on <?= isset($response->submission_date) ? $response->submission_date : '' ?> and
                your Acknowledgement No. is <?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?>. Please use this Acknowledgement
                number for tracking the application and for any future communication related to this application.
                If the application has been accepted by the <?= isset($department_name) ? $department_name : '' ?> department the service shall be provided within
                <?= isset($timeline_days) ? $timeline_days : '' ?> days <?= ($response->service_id == "14" || $response->service_id == 14) ? "(excluding the time taken between issue of NoC and presentation of Sale
Deed before jurisdictional Sub Registrar Office)" : "" ?>. You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the <?= isset($timeline_days) ? $timeline_days : '' ?> days.
                You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

              </p>
              <?php if ($response->service_id == "14" || $response->service_id == 14) { ?>
                <p>Notice will be generated at time of auto mutation which will be sent to all pattadar</p>
              <?php } ?>

            </div>
            <?php if (strval($response->portal_no) === "1") { ?>
              <div class="row">
                Application Details:
                <br />
                <br />
              </div>
              <div class="row">
                <table>
                  <?php if (isset($response->application_details->slno)) : ?>
                    <tr>
                      <td><b>Sl No:</b></td>
                      <td><?= isset($response->application_details->slno) ? $response->application_details->slno : '' ?></td>
                    </tr>
                  <?php endif; ?>

                  <tr>
                    <td><b>Application No:</b></td>
                    <td><?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?></td>
                  </tr>
                  <tr>
                    <td><b>Application Date:</b></td>
                    <td><?= isset($response->submission_date) ? $response->submission_date : '' ?></td>
                  </tr>
                  <?php if ($response->applicant_details) : ?>
                    <?php foreach ($response->applicant_details as $key => $applicant) : ?>
                      <tr>
                        <td><b>Name of the Applicant:</b></td>
                        <td><?= isset($applicant->applicant_name) ? $applicant->applicant_name : '' ?></td>
                      </tr>
                      <tr>
                        <td><b>Address of Applicant:</b></td>
                        <td>
                          <?= isset($applicant->address_line_1) ? $applicant->address_line_1 . ", " : '' ?>
                          <?= isset($applicant->address_line_2) ? $applicant->address_line_2 . ", " : '' ?>
                          <?= isset($applicant->district) ? $applicant->district : '' ?>
                          <?= isset($applicant->pin_code) ? $applicant->pin_code : '' ?>
                          <?= isset($applicant->state) ? $applicant->state : '' ?>,
                          <?= isset($applicant->country) ? $applicant->country : '' ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Mobile No:</b></td>
                        <td><?= isset($applicant->mobile_number) ? $applicant->mobile_number : '' ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>

                  <?php if (isset($expected_dt)) : ?>
                    <tr>
                      <td><b>Expected Date of Issue of Permission: </b></td>
                      <td><?= isset($expected_dt) ? $expected_dt : '' ?></td>
                    </tr>
                  <?php endif; ?>


                </table>
              </div>
            <?php } ?>

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
                      <td><?=sprintf("%.2f",$response->amount)  ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->payment_config->AC3_AMOUNT)) : ?>
                    <tr>
                      <td><b>Land Revenue:</b></td>
                      <td><?=sprintf("%.2f",calculateTotalRevenu($response))  ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (property_exists($response, 'amountmut') && floatval($response->amountmut) > 0) : ?>
                    <tr>
                      <td><b>Auto Mutation Amount:</b></td>
                      <td><?= $response->amountmut ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (property_exists($response, 'amountpart') &&  floatval($response->amountpart) > 0) : ?>
                    <tr>
                      <td><b>Auto Partion Amount:</b></td>
                      <td><?= $response->amountpart ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->service_charge)) : ?>
                    <tr>
                      <td><b>Service Charge:</b></td>
                      <td><?=sprintf("%.2f",$response->service_charge)  ?></td>
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
                  <?php if (!empty($response->rtps_convenience_fee)) : ?>
                    <tr>
                      <td><b>Convenience Fee:</b></td>
                      <td><?= $response->rtps_convenience_fee . ".00" ?></td>
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


    <div class="row" id="printAssamese">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
              <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" style="width:auto ;" width="100" height="100" />
            </div>

            <div class="row">
              <h3 class="h2"><u>আবেদন স্বীকৃতি</u></h3>
            </div>
            <div class="row akno">
              <span><u>স্বীকৃতি নম্বৰ: </u><?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?></span>
              <span style="float: right;"><u>তাৰিখ:</u><?= isset($response->submission_date) ? $response->submission_date : '' ?></span>
            </div>
            <div class="row">
              <p>
                <?php
                if ($response->portal_no === "5" || $response->portal_no === 5) { ?>
                  প্ৰিয়  আবেদনকাৰী,
                <?php  } else { ?>
                  প্ৰিয়  <?= (is_array($response->applicant_details) && isset($response->applicant_details)) ? $response->applicant_details[0]->applicant_name : '' ?>,
                <?php  }
                ?>

                <br />
                <br />

                <?= isset($service_name) ? $service_name : '' ?>  বাবে দাখিল কৰা  আপোনাৰ আবেদনপত্রখন <?= isset($response->submission_date) ? $response->submission_date : '' ?> তাৰিখে সফলতাৰে সম্পূর্ণ হৈছে আৰু আপোনাৰ স্বীকৃতি সংখ্যা হৈছে, <?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?> । ভৱিষ্যতে আবেদন সম্পর্কীয় যিকোনো যোগাযোগৰ বাবে আৰু আবেদনৰ স্থিতি নিৰীক্ষণ কৰিবলৈ, অনুগ্ৰহ কৰি এই স্বীকৃতি নম্বৰটো ব্যৱহাৰ কৰক। যদি আবেদনপত্ৰখন <?= isset($department_name) ? $department_name : '' ?> বিভাগৰ দ্বাৰা গ্ৰহণ কৰা হৈছে, তেন্তে <?= isset($timeline_days) ? $timeline_days : '' ?>  <?= ($response->service_id == "14" || $response->service_id == 14) ? "(এন.ও.চি. জাৰী কৰা আৰু বিক্ৰী উপস্থাপনৰ মাজত লোৱা সময় বাদ দি
অধিকাৰ ক্ষেত্ৰীয় উপ পঞ্জীয়ক কাৰ্যালয়ৰ আগত কাম)" : "" ?>  দিনৰ ভিতৰত সেৱা প্ৰদান কৰিব লাগিব। যদি <?= isset($timeline_days) ? $timeline_days : '' ?> দিনৰ ভিতৰত সেৱা আগবঢ়োৱা নহয়, তেনেহলে আবেদনকাৰীয়ে http://rtps.assam.gov.in/ ত আবেদন কৰিব পাৰে। আপুনি আমাক ১৮০০-৩৪৫-৩৫৭৪ নম্বৰত  প্ৰতি সোমবাৰৰ পৰা শনিবাৰলৈ, পুৱা ৮ বজাৰ পৰা নিশা ৮ বজালৈ ফোন কৰিব পাৰে অথবা 
কোনো ধৰণৰ মতামত বা অভিযোগৰ বাবে  rtps-assam@assam.gov.in লৈ ইমেইল প্ৰেৰণ কৰিব পাৰে। 


              </p>
              <?php if ($response->service_id == "14" || $response->service_id == 14) { ?>
                <p>অটো মিউটেশ্যনৰ সময়ত জাননী সৃষ্টি কৰা হ'ব আৰু সকলো পট্টাদাৰলৈ প্ৰেৰণ কৰা হ'ব</p>
              <?php } ?>

            </div>
            <?php if (strval($response->portal_no) === "1") { ?>
              <div class="row">
                আবেদনৰ বিৱৰণ:
                <br />
                <br />
              </div>
              <div class="row">
                <table>
                  <?php if (isset($response->application_details->slno)) : ?>
                    <tr>
                      <td><b>ক্ৰমিক নম্বৰ:</b></td>
                      <td><?= isset($response->application_details->slno) ? $response->application_details->slno : '' ?></td>
                    </tr>
                  <?php endif; ?>

                  <tr>
                    <td><b>আবেদন সংখ্যা:</b></td>
                    <td><?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?></td>
                  </tr>
                  <tr>
                    <td><b>আবেদনৰ তাৰিখ:</b></td>
                    <td><?= isset($response->submission_date) ? $response->submission_date : '' ?></td>
                  </tr>
                  <?php if ($response->applicant_details) : ?>
                    <?php foreach ($response->applicant_details as $key => $applicant) : ?>
                      <tr>
                        <td><b>আবেদনকাৰীৰ নাম:</b></td>
                        <td><?= isset($applicant->applicant_name) ? $applicant->applicant_name : '' ?></td>
                      </tr>
                      <tr>
                        <td><b>আবেদনকাৰীৰ ঠিকনা:</b></td>
                        <td>
                          <?= isset($applicant->address_line_1) ? $applicant->address_line_1 . ", " : '' ?>
                          <?= isset($applicant->address_line_2) ? $applicant->address_line_2 . ", " : '' ?>
                          <?= isset($applicant->district) ? $applicant->district : '' ?>
                          <?= isset($applicant->pin_code) ? $applicant->pin_code : '' ?>
                          <?= isset($applicant->state) ? $applicant->state : '' ?>,
                          <?= isset($applicant->country) ? $applicant->country : '' ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>ম'বাইল নম্বৰ:</b></td>
                        <td><?= isset($applicant->mobile_number) ? $applicant->mobile_number : '' ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>

                  <?php if (isset($expected_dt)) : ?>
                    <tr>
                      <td><b>অনুমতি জাৰী কৰাৰ প্ৰত্যাশিত তাৰিখ: </b></td>
                      <td><?= isset($expected_dt) ? $expected_dt : '' ?></td>
                    </tr>
                  <?php endif; ?>


                </table>
              </div>
            <?php } ?>

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
                      <td><?=sprintf("%.2f",$response->amount)  ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->payment_config->AC3_AMOUNT)) : ?>
                    <tr>
                      <td><b>ভূমি ৰাজহ:</b></td>
                      <td><?=sprintf("%.2f",calculateTotalRevenu($response))  ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (property_exists($response, 'amountmut') && floatval($response->amountmut) > 0) : ?>
                    <tr>
                      <td><b>স্বয়ং পৰিৱৰ্তনৰ পৰিমাণ:</b></td>
                      <td><?= $response->amountmut ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if (property_exists($response, 'amountpart') &&  floatval($response->amountpart) > 0) : ?>
                    <tr>
                      <td><b>কাৰ পেট্ৰ'লৰ পৰিমাণ:</b></td>
                      <td><?= $response->amountpart ?></td>
                    </tr>
                  <?php endif; ?>

                  <?php if (!empty($response->service_charge)) : ?>
                    <tr>
                      <td><b>সেৱা মাচুল:</b></td>
                      <td><?=sprintf("%.2f",$response->service_charge)  ?></td>
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
                  <?php if (!empty($response->rtps_convenience_fee)) : ?>
                    <tr>
                      <td><b>সুবিধা মাচুল:</b></td>
                      <td><?= $response->rtps_convenience_fee . ".00" ?></td>
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

  if(!empty($response->payment_config->AC3_AMOUNT)){
    $total +=floatval($response->payment_config->AC3_AMOUNT);
  }
  if(!empty($response->payment_config->AC4_AMOUNT)){
    $total +=floatval($response->payment_config->AC4_AMOUNT);
  }
  if(!empty($response->payment_config->AC5_AMOUNT)){
    $total +=floatval($response->payment_config->AC5_AMOUNT);
  }
  if(!empty($response->payment_config->AC6_AMOUNT)){
    $total +=floatval($response->payment_config->AC6_AMOUNT);
  }
  if(!empty($response->payment_config->AC7_AMOUNT)){
    $total +=floatval($response->payment_config->AC7_AMOUNT);
  }
  if(!empty($response->payment_config->AC8_AMOUNT)){
    $total +=floatval($response->payment_config->AC8_AMOUNT);
  }

  return $total . ".00";
}
function calculateTotalRevenu($response){
  $revenu=0;
    if(!empty($response->payment_config->AC3_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC3_AMOUNT);
    }
    if(!empty($response->payment_config->AC4_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC4_AMOUNT);
    }
    if(!empty($response->payment_config->AC5_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC5_AMOUNT);
    }
    if(!empty($response->payment_config->AC6_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC6_AMOUNT);
    }
    if(!empty($response->payment_config->AC7_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC7_AMOUNT);
    }
    if(!empty($response->payment_config->AC8_AMOUNT)){
      $revenu +=floatval($response->payment_config->AC8_AMOUNT);
    }
    return $revenu;
}
?>