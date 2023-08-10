<?php
$obj_id = $response->{'_id'}->{'$id'};
$qrpath= 'storage/temps/'.$obj_id.'.png';

$unformat_date = getDateFormat($response->form_data->submission_date);//dateformat helper
$txts = explode(' ', $unformat_date);
$sub_date = $txts[0];
//$sub_date = isset($response->form_data->submission_date) ? format_mongo_date($response->form_data->submission_date) : '';

$date_renewal = $response->form_data->renewal_date;
//$new_renewal_date = new MongoDB\BSON\UTCDateTime(strtotime($date_renewal)*1000);
$unformat_renewdate = getDateFormat($date_renewal);
$txts = explode(' ', $unformat_renewdate);
$rnew_date = $txts[0];

$oqtabcnt=count($response->form_data->other_qualification_trainings_courses);
$certificate = $response->form_data->output_certificate??'';
$certificatePath = (strlen($certificate)?base_url($certificate):'#'); 

$applicant_name = $response->form_data->applicant_name;
$service_name = isset($response->form_data->service_name) ? $response->form_data->service_name : '';
$department_name = isset($response->service_data->department_name) ? $response->service_data->department_name : '';
$stipulated_timeline = isset($response->service_data->service_timeline) ? $response->service_data->service_timeline: '';
$amount = isset($response->amount) ? $response->amount : 0;
$pfc_payment_status = isset($response->form_data->payment_status) ? $response->form_data->payment_status : '';
//$application_charge = $response->form_data->application_charge;
$rtps_convenience_fee = $response->form_data->convenience_charge;
$service_charge = isset($response->form_data->service_charge) ? $response->form_data->service_charge : 0;
$no_printing_page = isset($response->form_data->no_printing_page)?$response->form_data->no_printing_page:0;
$printing_charge_per_page = isset($response->form_data->printing_charge_per_page)?$response->form_data->printing_charge_per_page:0;
$no_scanning_page = isset($response->form_data->no_scanning_page)?$response->form_data->no_scanning_page:0;
$scanning_charge_per_page = isset($response->form_data->scanning_charge_per_page)?$response->form_data->scanning_charge_per_page:0;
?>


<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">

<div class="content-wrapper">
<div class="container">
        <div class="row " id="output_cert">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4 " style="border: 1px solid;padding:20px">
                <div class="card-body">
                  <div class="row d-flex justify-content-center" style="padding:10px">


                     <img src="<?=base_url("assets/frontend/images/assam_logo.png")?>" style="height:  60px; width: 60px" alt="Govt. of Assam" />
                  </div>

                  <div class="d-flex justify-content-center" style="padding:10px">
                    <h4>GOVERNMENT OF ASSAM</h4>
                  </div>
                  
                  <div class="d-flex justify-content-center" style="padding:10px" >
                  	<h4>SKILL, EMPLOYMENT & ENTREPRENEURSHIP DEPARTMENT</h4>
                  </div>

                  <div class="d-flex justify-content-center" style="padding:10px">
                  	<h5><?=$response->form_data->employment_exchange?></h5>
                  </div>

                  <div class="d-flex justify-content-center" style="padding:10px">
                  	<h5>CATEGORY: <?=$response->form_data->caste?></h5>
                  </div>

                  <div class="d-flex justify-content-center" style="padding:10px">
                  	<h5>IDENTITY CARD</h5>
                  </div>
                  
                  <div class="d-flex justify-content-center" style="padding:10px">
                  	<h6>(Not an Introduction Card for Interview with employer)</h6>
                  </div>

               
                  <div class="row">
                    
                    <table class="table-bordered" style="margin-top:10px; margin-bottom:10px;">
                        <tbody style="border-top: none !important">
                           
                            <tr>
                                <td style="width:30%"><h6> Name</h6></td>
                                <td><h6><strong><?=$response->form_data->applicant_name?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Date of Birth</h6> </td>
                                <td><h6><strong><?=$response->form_data->date_of_birth?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Date of Registration</h6> </td>
                                <td><h6><strong><?=$sub_date?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Registration No.</h6> </td>
                                <td><h6><strong><?=$response->form_data->registration_no?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Permanent Address</h6></td>
                                <td><h6><strong><?=$response->form_data->vill_town_ward_city_p?>, PO:<?=$response->form_data->post_office_p?> , PS: <?=$response->form_data->police_station_p?>, DISTRICT: <?=$response->form_data->district_p?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Qualification</h6></td>
                                <td><h6><strong><?=$response->form_data->highest_examination_passed?></strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Other Qualification</h6></td>
                                <td><h6><strong>
                                    <?php if($oqtabcnt!=0){ ?>
                                	<table class="table table-bordered" style="margin-top:5px; margin-bottom:5px;">
				                        <tbody style="border-top: none !important">
				                            <tr>    
				                                <td><h6><strong>sl no:</strong></h6></td>
				                                <td><h6><strong>Certificate Name</h6></strong></td>
				                                
				                            </tr>
				                            <?php for ($i = 0; $i < $oqtabcnt; $i++){?>
				                            <tr>
				                                <td><strong><?=$i+1?></strong></td>
				                                <td><strong><?=$response->form_data->other_qualification_trainings_courses[$i]->certificate_name?></strong> </td>
				                            </tr>
				                            <?php
				                             }
				                            ?>
				                        </tbody>
                                    </table>
                                <?php } ?>
                                </strong></h6></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><h6> Occupation</h6></td>
                                <td><h6><strong><?=$response->form_data->occupation?></strong></h6></td>
                            </tr>

                            <tr>
                                <td style="width:30%"><h6> Prominent Identification Mark</h6></td>
                                <td><h6><strong><?=$response->form_data->prominent_identification_mark?></strong></h6></td>
                            </tr>
                            
                        </tbody>
                    </table>
                  </div>

                 <div class="row" style="padding:10px">  
                 <h6>Next Renewal Due On :<strong><?=$rnew_date?></strong></h6>
                 </div>
                 <div class="row" style="padding:10px"> 
				 <h6><strong>Employers shall be responsible for verification of Permanent Address of Assam/Educational Qualification/Age proof/ Caste etc.
				 of the applicant.</strong></h6>
                 </div>
                <div style="text-align: left; padding: 0px">
                <img src="<?=base_url($qrpath)?>" style="width: 30mm; height: 30mm" />
                </div>
                 </div>
            </div>     
        </div>
        </div>
 


    <div class="row " id="print">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4 " style="border: 1px solid;padding:20px">
                <div class="card-body">
                  <div class="row" >
                    <img class="img" src="<?=base_url("assets/frontend/images/logo_artps.png")?>" width="100" height="100"/>
                  </div>

                  <div class="row" >
                    <h3 class="h2"><u>Application Acknowledgement</u></h3>
                  </div>
                  <div class="row akno">
                    <span><u>Acknowledgement no: </u><?=isset($response->form_data->rtps_trans_id)?$response->form_data->rtps_trans_id:''?></span>

                     <span style="float: right;"><u>Date: </u><?=$sub_date?></span>
                   </div>
                  <div class="row">
                      <p style="text-align:justify; line-height: 24px">
                        Dear <strong><?=( isset($response->form_data->applicant_name))?$response->form_data->applicant_name:''?></strong>,
                      <br/>
                      <br/>
                      Your <strong> <?=isset($response->form_data->service_name)?$response->form_data->service_name:''?></strong> has been submitted successfully on <strong><?= $sub_date?></strong> and
                      your Acknowledgement No. is <strong><?=isset($response->form_data->rtps_trans_id)?$response->form_data->rtps_trans_id:''?></strong>. Please use this Acknowledgement
                      number for tracking the application and for any future communication related to this application.
                      You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                    </p>
                   
                    
                  </div>
                 

                  <!-- fee description -->
                        <?php if (!empty($response->form_data->payment_status) && $response->form_data->payment_status === "Y") { ?>


                    <div class="row">
                       The following fees has been collected for this application:

                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                          <table>
                            <?php if (!empty($response->amount)): ?>
                              <tr>
                                <td><b>Application Fee:</b></td>
                                <td><?=$response->amount ?></td>
                              </tr>
                            <?php endif; ?>

                          
                            <?php if (!empty($response->form_data->convenience_charge)): ?>
                            <tr>
                              <td><b>Convenience Charge:</b></td>
                              <td><?=$response->form_data->convenience_charge ?></td>
                            </tr>
                              <?php endif; ?>
                            <?php if (!empty($response->form_data->service_charge)): ?>
                            <tr>
                              <td><b>Service Charge:</b></td>
                              <td><?=$response->form_data->service_charge ?></td>
                            </tr>
                              <?php endif; ?>
                            <?php if (!empty($response->form_data->no_printing_page)): ?>
                            <tr>
                              <td><b>Printing Fee:</b></td>
                              <td><?=($response->form_data->no_printing_page) *($response->form_data->printing_charge_per_page).".00"?></td>
                            </tr>
                              <?php endif; ?>
                              <?php if (!empty($response->form_data->no_scanning_page)): ?>
                              <tr>
                                <td><b>Scanning Fee:</b></td>
                                <td><?=($response->form_data->no_scanning_page)*($response->form_data->scanning_charge_per_page).".00"?></td>
                              </tr>
                              <?php endif; ?>
                              <tr>
                                <td>================</td>
                                <td>========</td>
                              </tr>

                              <tr>
                                <td><b>Total:</b></td>
                                <td><?=calculateTotal($response)?></td>
                              </tr>
                          </table>
                    </div>

                    <?php }?>
                   
                  <!-- end of fee description -->

                  <br/>
                  <br/>
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
                        <div class="row" >
                            <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100"/>
                        </div>

                        <div class="row" >
                            <h3 class="h2"><u>আবেদন স্বীকৃতি</u></h3>
                        </div>
                        <div class="row akno">
                            <span><u>স্বীকৃতি নম্বৰ: </u><?=isset($response->form_data->rtps_trans_id)?$response->form_data->rtps_trans_id:''?></span>
                            <span style="float: right;"><u>তাৰিখ: </u><?=$sub_date?></span>
                        </div>
                        <div class="row">
                             <p style="text-align:justify; line-height: 28px">
                                প্ৰিয় <strong><?=$applicant_name?></strong>,
                                <br/>
                                <br/>
                                <strong> <?=$service_name?></strong>  বাবে দাখিল কৰা  আপোনাৰ আবেদনপত্রখন <strong><?=$sub_date?></strong>  তাৰিখে সফলতাৰে সম্পূর্ণ হৈছে আৰু আপোনাৰ স্বীকৃতি সংখ্যা হৈছে,  <strong><?=isset($response->form_data->rtps_trans_id)?$response->form_data->rtps_trans_id:''?></strong> । ভৱিষ্যতে আবেদন সম্পর্কীয় যিকোনো যোগাযোগৰ বাবে আৰু আবেদনৰ স্থিতি নিৰীক্ষণ কৰিবলৈ, অনুগ্ৰহ কৰি এই স্বীকৃতি নম্বৰটো ব্যৱহাৰ কৰক। আপুনি আমাক ১৮০০-৩৪৫-৩৫৭৪ নম্বৰত  প্ৰতি সোমবাৰৰ পৰা শনিবাৰলৈ, পুৱা ৮ বজাৰ পৰা নিশা ৮ বজালৈ ফোন কৰিব পাৰে অথবা 
                                কোনো ধৰণৰ মতামত বা অভিযোগৰ বাবে  rtps-assam@assam.gov.in লৈ ইমেইল প্ৰেৰণ কৰিব পাৰে। 
                            </p>
                        </div>

                        <!-- fee description -->
                        <?php if ($amount || ($pfc_payment_status === "Y")) { ?>

                            <div class="row">
                                এই আবেদনৰ বাবে নিম্নলিখিত মাচুল সংগ্ৰহ কৰা হৈছে:
                            </div>
                            <br/>
                            <br/>
                            <div class="row">
                                <table>
                                    <?php if ($amount): ?>
                                        <tr>
                                            <td><b>আবেদন মাচুল:</b></td>
                                            <td><?= sprintf("%.2f", $amount) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                   
                                    <?php if ($rtps_convenience_fee): ?>
                                        <tr>
                                            <td><b>সুবিধা মাচুল:</b></td>
                                            <td><?= $rtps_convenience_fee ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($service_charge): ?>
                                        <tr>
                                            <td><b>সেৱা মাচুল:</b></td>
                                            <td><?= $service_charge ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_printing_page): ?>
                                        <tr>
                                            <td><b>প্ৰিন্ট কৰা মাচুল:</b></td>
                                            <td><?=sprintf("%0.2f", $no_printing_page*$printing_charge_per_page)?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_scanning_page): ?>
                                        <tr>
                                            <td><b>স্কেনিং মাচুল:</b></td>
                                            <td><?=sprintf("%0.2f", $no_scanning_page*$scanning_charge_per_page)?></td>
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
                        <br/>
                        <br/>
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


    <button type="button" id="btnPrint" class="btn btn-primary mb-2" >
      <i class="fa fa-print"></i> Print Acknowledgement
    </button>
    <button type="button" id="btnPrintAssamese" class="btn btn-primary mb-2">
            প্ৰিণ্ট স্বীকৃতি
    </button>
    <a href="<?=$certificatePath?>" target="_blank" class="btn btn-success mb-2" >Download Certificate/প্ৰমাণপত্ৰ</a>
    <?php if($this->session->role) { ?>
        <a href="<?=base_url('iservices/admin/my-transactions')?>" class="btn btn-primary mb-2 pull-right">
            <i class="fa fa-angle-double-left"></i> Back
        </a>
    <?php } else { ?>
        <a href="<?=base_url('iservices/transactions')?>" class="btn btn-primary mb-2 pull-right">
            <i class="fa fa-angle-double-left"></i> Back
        </a>
    <?php }//End of if else ?>
</div>
</div>
<script>
$(function(){
    $("#btnPrint").on('click',function(){
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
        });
})
</script>
<?php
function calculateTotal($response){
  $total=10;
  if (!empty($response->form_data->payment_status) && $response->form_data->payment_status === "Y"){
    if(!empty($response->form_data->no_printing_page)){
      $total  +=  ($response->form_data->no_printing_page) *10;
    }
    if(!empty($response->form_data->no_scanning_page)){
      $total +=  ($response->form_data->no_scanning_page) *5;
    }
    if(!empty($response->form_data->service_charge)){
      $total +=30;
    }
  }
  return $total.".00";
}
?>