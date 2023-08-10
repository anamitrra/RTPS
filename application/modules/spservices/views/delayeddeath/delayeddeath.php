<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
//$startYear = date('Y') - 10;
//$endYear =  date('Y');

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $pan_no = isset($dbrow->form_data->pan_no)? $dbrow->form_data->pan_no: "";
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "";
    $aadhar_no = isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "";
    $father_name = $dbrow->form_data->father_name;
    $relation_with_deceased = $dbrow->form_data->relation_with_deceased;
    $other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "";

    $name_of_deceased = $dbrow->form_data->name_of_deceased;
    $deceased_gender = $dbrow->form_data->deceased_gender;
    $deceased_dod = $dbrow->form_data->deceased_dod;
    $age_of_deceased = $dbrow->form_data->age_of_deceased;
    $place_of_death = $dbrow->form_data->place_of_death;
    $address_of_hospital_home = isset($dbrow->form_data->address_of_hospital_home)? $dbrow->form_data->address_of_hospital_home: "";
    $other_place_of_death = isset($dbrow->form_data->other_place_of_death)? $dbrow->form_data->other_place_of_death: "";
    $reason_for_late = $dbrow->form_data->reason_for_late;
    $father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
    $mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;

    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $district_id = $dbrow->form_data->district_id;
    $sub_division = $dbrow->form_data->sub_division;
    $sub_division_id = $dbrow->form_data->sub_division_id;
    $revenue_circle = $dbrow->form_data->revenue_circle;
    $revenue_circle_id = $dbrow->form_data->revenue_circle_id;
    $village_town = $dbrow->form_data->village_town;
    $pin_code = $dbrow->form_data->pin_code;


    $affidavit_type = isset($dbrow->form_data->affidavit_type)? $dbrow->form_data->affidavit_type: ""; 
    $affidavit = isset($dbrow->form_data->affidavit)? $dbrow->form_data->affidavit: ""; 
    $others_type = isset($dbrow->form_data->others_type)? $dbrow->form_data->others_type: ""; 
    $others = isset($dbrow->form_data->others)? $dbrow->form_data->others: "";
    $doctor_certificate_type = isset($dbrow->form_data->doctor_certificate_type)? $dbrow->form_data->doctor_certificate_type: ""; 
    $doctor_certificate = isset($dbrow->form_data->doctor_certificate)? $dbrow->form_data->doctor_certificate: "";
    $proof_residence_type = isset($dbrow->form_data->proof_residence_type)? $dbrow->form_data->proof_residence_type: ""; 
    $proof_residence = isset($dbrow->form_data->proof_residence)? $dbrow->form_data->proof_residence: "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $relation_with_deceased = set_value("relation_with_deceased");
    $other_relation = set_value("other_relation");
    $father_name = set_value("father_name");

    $name_of_deceased = set_value("name_of_deceased");
    $deceased_gender = set_value("deceased_gender");
    $deceased_dod = set_value("deceased_dod");
    $age_of_deceased = set_value("age_of_deceased");
    $place_of_death = set_value("place_of_death");
    $address_of_hospital_home = set_value("address_of_hospital_home");
    $other_place_of_death = set_value("other_place_of_death");
    $reason_for_late = set_value("reason_for_late");
    $father_name_of_deceased = set_value("father_name_of_deceased");
    $mother_name_of_deceased = set_value("mother_name_of_deceased");

    $state = set_value("state");
    $district = "";
    $district_id = set_value("district_id");
    $sub_division = "";
    $sub_division_id = set_value("sub_division_id");
    $revenue_circle = "";
    $revenue_circle_id = set_value("revenue_circle_id");
    $village_town = set_value("village_town");
    $pin_code = set_value("pin_code");

    $affidavit_type = "";
    $affidavit = "";
    $others_type = "";
    $others = "";
    $doctor_certificate_type = ""; 
    $doctor_certificate = "";
    $proof_residence_type = ""; 
    $proof_residence = "";
    $soft_copy_type = ""; 
    $soft_copy = "";
}//End of if else
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
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {

        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
     
        // $.getJSON("<?=$apiServer?>district_list.php", function (data) {
        //     let selectOption = '';
        //     $.each(data.records, function (key, value) {
        //         selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
        //     });
        //     $('.dists').append(selectOption);
        // });        
       
        $.getJSON("<?=$apiServer."district_list.php"?>", function (data) {
            let selectOption = '';
            $('.district').empty().append('<option value="">Select District</option>');
            let selectedDistrict = "<?php print $district; ?>"
            $.each(data.ListOfDistricts, function (key, value) {
                if(selectedDistrict == value.DistrictName)
                    selectOption += '<option value="'+value.DistrictName +'/' + value.DistrictId + '" selected>'+value.DistrictName+'</option>';
                else
                    selectOption += '<option value="'+value.DistrictName +'/' + value.DistrictId + '">'+value.DistrictName+'</option>';
            });
            $('.district').append(selectOption);
        });
                
        $(document).on("change", "#district", function(){  
            $('#revenue_circle').empty().append('<option value="">Please select</option>')       
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];
            json_body = '{"district_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'/' + value.subdiv_id + '">'+value.subdiv_name+'</option>';
                    });
                    $('#sub_division').append(selectOption);
                });
            }
        });


        $(document).on("change", "#sub_division", function(){  
            $('#revenue_circle').empty().append('<option value="">Please select</option>')               
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"subdiv_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'/' + value.circle_id + '">'+value.circle_name+'</option>';
                    });
                    $('#revenue_circle').append(selectOption);
                });
            }
        });


        $(document).on("click", ".frmbtn", function(){ 

            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });    
        
        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        $('#aadhar_no').keyup(function () {    
            if($("#aadhar_no").val().length > 12) {
                $("#aadhar_no").val("");
                alert("Please! Enter upto only 12 digit"); 
            }                        
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

        var relation_with_deceased = "<?=$other_relation?>";
        if (relation_with_deceased == "Other")
            $("#other_relation").prop('disabled', false);
        else
            $("#other_relation").prop('disabled', true);

        $(document).on("change", "#relation_with_deceased", function(){               
            $('#other_relation').val("");
            var relation_with_deceased = $("#relation_with_deceased").val();
            if (relation_with_deceased == "Other")
                $("#other_relation").prop('disabled', false);
            else
                $("#other_relation").prop('disabled', true);
        });

        var relation_with_deceased = $("#relation_with_deceased").val();
         if (relation_with_deceased == "Other")
            $("#other_relation").prop('disabled', false);
        else
            $("#other_relation").prop('disabled', true);


        var place_of_death = "<?=$place_of_death?>";
        if ((place_of_death == "Hospital") || (place_of_death == "House")){
            $("#other_place_of_death").prop('disabled', true);
            $("#address_of_hospital_home").prop('disabled', false);
        }
        else if (place_of_death == "Other"){
            $("#other_place_of_death").prop('disabled', false);
            $("#address_of_hospital_home").prop('disabled', true);
        }   
        else{
            $("#other_place_of_death").prop('disabled', true);
            $("#address_of_hospital_home").prop('disabled', true);
        }
        $(document).on("change", "#place_of_death", function(){               
            $('#other_place_of_death').val("");
            $('#address_of_hospital_home').val("");
            var place_of_death = $("#place_of_death").val();
            if ((place_of_death == "Hospital") || (place_of_death == "House")){
                $("#other_place_of_death").prop('disabled', true);
                $("#address_of_hospital_home").prop('disabled', false);
            }
            else if (place_of_death == "Other"){
                $("#other_place_of_death").prop('disabled', false);
                $("#address_of_hospital_home").prop('disabled', true);
            }   
            else{
                $("#other_place_of_death").prop('disabled', true);
                $("#address_of_hospital_home").prop('disabled', true);
            }
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/delayed-death') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="affidavit_type" value="<?=$affidavit_type?>" type="hidden" />
            <input name="affidavit" value="<?=$affidavit?>" type="hidden" />
            <input name="doctor_certificate_type" value="<?=$doctor_certificate_type?>" type="hidden" />
            <input name="doctor_certificate" value="<?=$doctor_certificate?>" type="hidden" />
            <input name="proof_residence_type" value="<?=$proof_residence_type?>" type="hidden" />
            <input name="proof_residence" value="<?=$proof_residence?>" type="hidden" />
            <?php if(!empty($others_type)){ ?>
            <input name="others_type" value="<?=$others_type?>" type="hidden" />
            <input name="others" value="<?=$others?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application Form for Permission for Delayed Death Certificate<br>
                        ( বিলম্বিত মৃত্যু প্ৰমাণপত্ৰৰ বাবে অনুমতিৰ বাবে আবেদন পত্ৰ ) 
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 10 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ  ১0 দিনৰ ভিতৰত(সাধাৰণ) অথবা ৩ দিনৰ ভিতৰত(জৰুৰী) প্ৰদান কৰা হ'ব</li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  Rs. 5/- Per page(General Delivery) Rs. 10/- Per page(Urgent Delivery).</li>
                            <li>১. প্ৰতিটো পৃষ্ঠাৰ বাবে ৫ টকাকৈ ( সাধাৰণ ) /  ১0 টকাকৈ (জৰুৰীকালীন)</li>
                            <li>2. RTPS fee of rupees 20/- per appilcation.</li>
                            <li>২. প্ৰতিখন আবেদনৰ বাবত ২০ টকা Rtps ফিছ</li>
                        </ul>   
                        
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul>   

                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div> 

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" value="<?=$father_name?>" maxlength="100" id="father_name" type="text" />
                                <?= form_error("father_name") ?>
                            </div> 
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                        </div>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Relation with Deceased/ মৃতকৰ সৈতে সম্পৰ্ক <span class="text-danger">*</span> </label>
                                <select name="relation_with_deceased" class="form-control" id="relation_with_deceased">
                                    <option value="">Please Select</option>
                                    <option value="Father" <?=($relation_with_deceased === "Father")?'selected':''?>>Father</option>
                                    <option value="Mother" <?=($relation_with_deceased === "Mother")?'selected':''?>>Mother</option>
                                    <option value="Brother" <?=($relation_with_deceased === "Brother")?'selected':''?>>Brother</option>
                                    <option value="Son" <?=($relation_with_deceased === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($relation_with_deceased === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Wife" <?=($relation_with_deceased === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Husband" <?=($relation_with_deceased === "Husband")?'selected':''?>>Husband</option>
                                    <option value="Other" <?=($relation_with_deceased === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("relation_with_deceased") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Enter Other Relation (if any)/ অন্য সম্পৰ্ক প্ৰবিষ্ট কৰক (যদি থাকে)<span class="text-danger">*</span> </label>
                                <input class="form-control" name="other_relation" value="<?=$other_relation?>" maxlength="100" id="other_relation" type="text" id="other_relation" disabled/>
                                <?= form_error("other_relation") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Deceased Person&apos;s Information / মৃতকৰ তথ্য </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Name of the Deceased/ মৃত ব্যক্তিৰ নাম<span class="text-danger">*</span></label>
                                <input class="form-control" name="name_of_deceased" value="<?=$name_of_deceased?>" maxlength="100" id="name_of_deceased" type="text" />
                                <?= form_error("name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Deceased Gender/ মৃতকৰ লিংগ<span class="text-danger">*</span> </label>
                                <select name="deceased_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($deceased_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($deceased_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("deceased_gender") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Date of Death/ মৃত্যুৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dod" id="deceased_dod" value="<?=$deceased_dod?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("deceased_dod") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Age of the Deceased (in years)/ মৃতকৰ বয়স (বছৰত)<span class="text-danger">*</span></label>
                                <input class="form-control number_input" name="age_of_deceased" value="<?=$age_of_deceased?>" maxlength="100" id="age_of_deceased" type="text" />
                                <?= form_error("age_of_deceased") ?>
                            </div> 
                        </div>

                        <div class="row"> 
                            <div class="col-md-6">
                                <label>Place of Death/ মৃত্যুৰ ঠাই <span class="text-danger">*</span> </label>
                                <select name="place_of_death" class="form-control" id="place_of_death">
                                    <option value="">Please Select</option>
                                    <option value="Hospital" <?=($place_of_death === "Hospital")?'selected':''?>>Hospital</option>
                                    <option value="House" <?=($place_of_death === "House")?'selected':''?>>House</option>
                                    <option value="Other" <?=($place_of_death === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("place_of_death") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Address of Home/Hospital/ গৃহ/চিকিৎসালয়ৰ ঠিকনা<span class="text-danger">*</span></label>
                                <input class="form-control" name="address_of_hospital_home" value="<?=$address_of_hospital_home?>" maxlength="100" id="address_of_hospital_home" type="text" disabled/>
                                <?= form_error("address_of_hospital_home") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) <span class="text-danger">*</span></label>
                                <input class="form-control" name="other_place_of_death" value="<?=$other_place_of_death?>" maxlength="100" id="other_place_of_death" type="text" disabled/>
                                <?= form_error("other_place_of_death") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Reason for Being Late/ পলম হোৱাৰ কাৰণ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="reason_for_late" value="<?=$reason_for_late?>" maxlength="100" id="reason_for_late" type="text" />
                                <?= form_error("reason_for_late") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Father's Name of the Deceased/ মৃতকৰ পিতৃৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="father_name_of_deceased" value="<?=$father_name_of_deceased?>" maxlength="100" id="father_name_of_deceased" type="text" />
                                <?= form_error("father_name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Mother Name of the Deceased/ মৃতকৰ মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="mother_name_of_deceased" value="<?=$mother_name_of_deceased?>" maxlength="100" id="mother_name_of_deceased" type="text" />
                                <?= form_error("mother_name_of_deceased") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control">
                                    <option>Please Select</option>
                                    <option value="Assam" selected>Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control district" id="district">
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control" id="sub_division">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($sub_division)){ ?>
                                        <option value="<?php print $sub_division.'/'.$sub_division_id; ?>" selected><?php print $sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenue_circle" class="form-control" id="revenue_circle">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($revenue_circle)){ ?>
                                        <option value="<?php print $revenue_circle.'/'.$revenue_circle_id; ?>" selected><?php print $revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("revenue_circle") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Village/ Town/ গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="village_town" value="<?=$village_town?>" maxlength="100" id="village_town" type="text" />
                                <?= form_error("village_town") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx)<span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pin_code" value="<?=$pin_code?>" maxlength="6" type="text" />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div> 
                    </fieldset>
                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> -->
                     <!-- End of .row --> 
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>