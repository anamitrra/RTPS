<?php
$obj_id = $dbrow->{'_id'}->{'$id'};        
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$aadhaar_verify_status = $dbrow->form_data->aadhaar_verify_status??0;
$mobile_verify_status = $dbrow->form_data->mobile_verify_status;

$applicant_name = $dbrow->form_data->applicant_name;
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$mobile_number = $dbrow->form_data->mobile_number;
$email_id = $dbrow->form_data->email_id;
$dob = $dbrow->form_data->dob;
$applicant_gender = $dbrow->form_data->applicant_gender;
$community = $dbrow->form_data->community;

$pa_house_no = $dbrow->form_data->pa_house_no;
$pa_street = $dbrow->form_data->pa_street;
$pa_village = $dbrow->form_data->pa_village;
$pa_post_office = $dbrow->form_data->pa_post_office;
$pa_pin_code = $dbrow->form_data->pa_pin_code;
$pa_state = $dbrow->form_data->pa_state;
$pa_district_name = $dbrow->form_data->pa_district_name;
$pa_circle = $dbrow->form_data->pa_circle;
$pa_police_station = $dbrow->form_data->pa_police_station;

$address_same = $dbrow->form_data->address_same??'NO';
$ca_house_no = $dbrow->form_data->ca_house_no;
$ca_street = $dbrow->form_data->ca_street;
$ca_village = $dbrow->form_data->ca_village;
$ca_post_office = $dbrow->form_data->ca_post_office;
$ca_pin_code = $dbrow->form_data->ca_pin_code;
$ca_state = $dbrow->form_data->ca_state;
$ca_district_name = $dbrow->form_data->ca_district_name;
$ca_circle = $dbrow->form_data->ca_circle;
$ca_police_station = $dbrow->form_data->ca_police_station;

$id_proof_type = $dbrow->form_data->id_proof_type;
$id_proof = $dbrow->form_data->id_proof;
$address_proof_type = $dbrow->form_data->address_proof_type;
$address_proof = $dbrow->form_data->address_proof;
$age_proof_type = $dbrow->form_data->age_proof_type;
$age_proof = $dbrow->form_data->age_proof;
$passport_photo_type = $dbrow->form_data->passport_photo_type;
$passport_photo = $dbrow->form_data->passport_photo;
$query_doc = $dbrow->form_data->query_doc??'';
$query_asked = $dbrow->form_data->query_asked??'';
$query_answered = $dbrow->form_data->query_answered??'';
$status = $dbrow->service_data->appl_status;
$createdAt = isset($dbrow->form_data->created_at)?date("d-m-Y", strtotime($this->mongo_db->getDateTime($dbrow->form_data->created_at))):'';
$payment_status = $dbrow->form_data->payment_status??"UNPAID";
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    ol li {
        font-size: 14px;
        font-weight: bold;
    }
    .table td, .table th {
        font-size: 14px;
        padding: 2px;        
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">   
    $(document).ready(function () {      
        
        var txn, clickedBtnId;
        $(document).on("click", ".frmbtn", function(){  
            clickedBtnId = $(this).attr("id");            
            /*let aadhaarNo = $("#aadhaar_number").val();
            if (/^\d{12}$/.test(aadhaarNo)) {
                $("#otpModal").modal("show");
                $("#otp_no").val("");                
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/aadhaar/otpsend')?>",
                    data: {"aadhaar_number":aadhaarNo},
                    beforeSend:function(){
                        $("#otp_no").attr("placeholder", "Sending OTP... Please wait.");
                    },
                    success:function(res){
                        console.log("res : "+JSON.stringify(res));
                        if(Object.values(res.ret)[0] === 'y') {
                            txn = Object.values(res.txn_no)[0];                            
                            $(".verify_btn").attr("id", "verify_aadhaar_otp");
                            $("#otp_no").attr("placeholder", "Enter your OTP");
                        } else {
                            alert(res.msg);
                        }//End of if else
                    }
                });
            } else {
                alert("Aadhaar number is invalid. Please enter a valid 12-digit number");
                $("#otpModal").modal("hide");
                $("#aadhaar_number").val();
                $("#aadhaar_number").focus();
                return false;
            }//End of if else*/
            makePayment();
        }); //End of onclick .frmbtn
        
        $(document).on("click", "#verify_aadhaar_otp", function(){         
            let aadhaarNo = $("#aadhaar_number").val();
            let otpNo = $("#otp_no").val();
            if (/^\d{6}$/.test(otpNo)) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/aadhaar/otpverify')?>",
                    data: {
                        "aadhaar_number":aadhaarNo,
                        "txn":txn,
                        "otp": otpNo,
                        "name": "<?=$applicant_name?>",
                        "state": "<?=$pa_state?>",
                        "obj_id": "<?=$obj_id?>"
                    },
                    beforeSend:function(){
                        $("#otp_no").val("");
                        $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
                    },
                    success:function(res){
                        console.log("res : "+JSON.stringify(res));
                        if(Object.values(res.ret)[0] === 'y') {
                            $("#otpModal").modal("hide");
                            makePayment();
                        } else {
                            alert(res.msg);
                            $("#otpModal").modal("show");
                            $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                        }//End of if else
                    }
                });
            } else {
                alert("OTP is invalid. Please enter a valid 6-digit number");
                $("#otp_no").val();
                $("#otp_no").focus();
                return false;
            }//End of if else
        });//End of onClick #verify_aadhaar_otp
        
        var makePayment = function () {
            if(clickedBtnId === 'QUERY_SUBMIT') {
                var wanrTitle = "Aadhaar number verified successfully.";
                var wanrMgs = "Once you submit the response, you will not be able to revert this action.";
                var redirectUrl = "<?=base_url('spservices/minority-certificate-query-submit/'.$obj_id)?>";
            } else {
                var wanrTitle = "Aadhaar number verified successfully.";
                var wanrMgs = "Note that once you click Make Payment you will not be able to revert this action.";
                var redirectUrl = "<?=base_url('spservices/minority-certificate-payment/'.$obj_id)?>";
            }//End of if else
            
            Swal.fire({
                title: "Submission confirmation",
                text: wanrMgs,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Proceed'
            }).then((result) => {
                if (result.value) {
                    window.location.href = redirectUrl;
                }
            });
        };
        
        $(document).on("click", "#printBtn", function(){
            $("#printDiv").print({
                addGlobalStyles : true,
                stylesheet : null,
                rejectWindow : true,
                noPrintSelector : ".no-print",
                iframe : true,
                append : null,
                prepend : null
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="text-align: center; font-size: 24px; color: #000; font-family: georgia,serif; font-weight: bold">
                   Preview of <?=$service_name?> 
            </div>
            <div class="card-body" style="padding:5px">

                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="text-align: left; width: 25%">
                                <img src="<?=base_url('assets/frontend/images/assam_logo.png')?>" style="width: 80px; height: 100px">
                            </td>
                            <td class="text-center">
                                <h1 style="font-size: 22px; padding: 0px; margin: 0px; line-height: 33px; font-weight: bold; color: #00346c">
                                    Minority Community Certificate
                                </h1>
                                <h1 style="font-size: 16px; padding: 0px; margin: 0px; line-height: 24px; font-weight: bold; color: #07269f">
                                    For Minority Certificate under Sec.2 (c ) of the National Commission of Minority Act –(19) 1992
                                </h1>
                                <p style="font-size: 14px; font-weight: normal; color:#222; margin: 0px; font-style: italic; color: #07269f">
                                    {Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992)}
                                </p>
                            </td>
                            <td style="text-align: right; width: 25%">
                                <img src="<?=base_url($passport_photo)?>" style="width: 100px; height: 100px">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <span style="float:left; font-size: 12px;">Ref no. : <?=$appl_ref_no?></span>
                                <span style="float:right; font-size: 12px;">Date : <?=$createdAt?></span>
                            </td>                                
                        </tr>
                    </tbody>
                </table>
                
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Name of the Applicant (as mentioned in Aadhaar Card)<strong> : <?=$applicant_name?></strong> </td>
                                <td>Father's/Spouse name<strong> : <?=$father_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's name<strong> : <?=$mother_name?></strong> </td>
                                <td>Contact number<strong> : <?=$mobile_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>Email id<strong> : <?=$email_id?></strong> </td>
                                <td>Date of Birth<strong> : <?=$dob?></strong> </td>
                            </tr>
                            <tr>
                                <td>Gender/ লিংগ<strong> : <?=$applicant_gender?></strong> </td>
                                <td>Community<strong> : <?=$community?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Current Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House no. /Flat no.<strong> : <?=$ca_house_no?></strong> </td>
                                <td>Street / Locality<strong> : <?=$ca_street?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town<strong> : <?=$ca_village?></strong> </td>
                                <td>Post Office<strong> : <?=$ca_post_office?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pin Code<strong> : <?=$ca_pin_code?></strong> </td>
                                <td>State<strong> : <?=$ca_state?></strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : <?=$ca_district_name?></strong> </td>
                                <td>Circle<strong> : <?=$ca_circle?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police station<strong> : <?=$ca_police_station?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Permanent Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House no. /Flat no.<strong> : <?=$pa_house_no?></strong> </td>
                                <td>Street / Locality<strong> : <?=$pa_street?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town<strong> : <?=$pa_village?></strong> </td>
                                <td>Post Office<strong> : <?=$pa_post_office?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pin Code<strong> : <?=$pa_pin_code?></strong> </td>
                                <td>State<strong> : <?=$pa_state?></strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : <?=$pa_district_name?></strong> </td>
                                <td>Circle<strong> : <?=$pa_circle?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police station<strong> : <?=$pa_police_station?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">ATTACHED ENCLOSURE(S) </legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type of Enclosure</th>
                                <th>Enclosure Document</th>
                                <th>File/Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ID proof</td>
                                <td style="font-weight:bold"><?=$id_proof_type?></td>
                                <td>
                                    <?php if(strlen($id_proof)){ ?>
                                        <a href="<?=base_url($id_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Address proof</td>
                                <td style="font-weight:bold"><?=$address_proof_type?></td>
                                <td>
                                    <?php if(strlen($address_proof)){ ?>
                                        <a href="<?=base_url($address_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Age proof</td>
                                <td style="font-weight:bold"><?=$age_proof_type?></td>
                                <td>
                                    <?php if(strlen($age_proof)){ ?>
                                        <a href="<?=base_url($age_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php if(strlen($query_doc)){ ?>
                                <tr>
                                    <td>Query document</td>
                                    <td style="font-weight:bold">Query document</td>
                                    <td>
                                        <a href="<?=base_url($query_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php }//End of if ?>
                        </tbody>
                    </table>
                </fieldset>
                  
                <!--<div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <label>Aadhaar number<span class="text-danger">*</span></label>
                        <input class="form-control" name="aadhaar_number" id="aadhaar_number" maxlength="12" value="<?=set_value('aadhaar_number')?>" <?=($aadhaar_verify_status === '1')?'readonly':''?> type="text" />                               
                        <?= form_error("aadhaar_number") ?>
                    </div>
                    <div class="col-md-4"></div>
                </div> <!-- End of .row -->
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($status === 'DRAFT') { ?>    
                    <a href="<?=base_url('spservices/minority-certificate/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php }//End of if ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if(($payment_status !== 'PAYMENT_COMPLETED') &&($status !== 'QUERY_ARISE')){
                    if($user_type == 'user'){ ?>
                        <button class="btn btn-warning frmbtn" id="citizen_payment" type="button">
                            <i class="fa fa-angle-double-right"></i> <!--Verify &AMP;--> Make Payment
                        </button>
                    <?php } else{ ?>
                        <button class="btn btn-warning frmbtn" id="cscpfc_payment" type="button">
                            <i class="fa fa-angle-double-right"></i> <!--Verify &AMP;--> Make Payment
                        </button>
                    <?php }//End of if else
                }//End of if ?>
                
                <?php if($status === 'QUERY_ARISE') { ?>    
                    <button class="btn btn-warning frmbtn" id="QUERY_SUBMIT" type="button">
                        <i class="fa fa-angle-double-right"></i> Verify &AMP; Submit
                    </button>
                <?php }//End of if ?>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>


<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 22px !important; text-align: center !important; display: block !important">
                Aadhaar Verification
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    VERIFY
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    CANCEL
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->