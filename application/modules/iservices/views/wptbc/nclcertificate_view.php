<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->applicant_gender;
    $father_name = $dbrow->father_name;
    $mother_name = $dbrow->mother_name;
    $spouse_name = $dbrow->spouse_name;
    $email = $dbrow->email;
    $dob = $dbrow->dob;
    $mobile_number = $dbrow->mobile_number;
    
    $pa_houseno = $dbrow->pa_houseno;
    $pa_landmark = $dbrow->pa_landmark;
    $pa_state = $dbrow->pa_state;
    $pa_district = $dbrow->pa_district;
    $pa_subdivision = $dbrow->pa_subdivision;
    $pa_revenuecircle = $dbrow->pa_revenuecircle;
    $pa_mouza = $dbrow->pa_mouza;
    $pa_village = $dbrow->pa_village;
    $pa_ps = $dbrow->pa_ps;
    $pa_pincode = $dbrow->pa_pincode;
    $pa_po = $dbrow->pa_po;
        
    $caste_name= $dbrow->caste_name;
    $fsearning_sources= $dbrow->financial_status->fsearning_sources;
    $organization_types= $dbrow->financial_status->organization_types;
    $organization_names= $dbrow->financial_status->organization_names;
    $fs_designations= $dbrow->financial_status->fs_designations;

    $isearning_sources= $dbrow->income_status->isearning_sources;
    $annual_salary= $dbrow->income_status->annual_salary;
    $other_income= $dbrow->income_status->other_income;
    $total_income= $dbrow->income_status->total_income;
    $is_remarks= $dbrow->income_status->is_remarks;
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $spouse_name = set_value("spouse_name");
    $email = set_value("email");
    $dob = set_value("dob");
    $mobile_number = $this->session->mobile;//set_value("mobile_number");
    
    $pa_houseno = set_value("pa_houseno");
    $pa_landmark = set_value("pa_landmark");
    $pa_state = set_value("pa_state");
    $pa_district = set_value("pa_district");
    $pa_subdivision = set_value("pa_subdivision");
    $pa_revenuecircle = set_value("pa_revenuecircle");
    $pa_mouza = set_value("pa_mouza");
    $pa_village = set_value("pa_village");
    $pa_ps = set_value("pa_ps");
    $pa_pincode = set_value("pa_pincode");
    $pa_po = set_value("pa_po");    
        
    $caste_name= set_value("caste_name");
    $fsearning_sources= set_value("fsearning_sources");
    $organization_types= set_value("organization_types");
    $organization_names= set_value("organization_names");
    $fs_designations= set_value("fs_designations");

    $isearning_sources= set_value("isearning_sources");
    $annual_salary= set_value("annual_salary");
    $other_income= set_value("other_income");
    $total_income= set_value("total_income");
    $is_remarks= set_value("is_remarks");
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
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
            
        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
        
        $(document).on("click", "#addfstblrow", function(){
            let totRows = $('#financialstatustbl tr').length;
            var trow = `<tr>
                            <td>
                                <select class="form-control" name="fsearning_sources[]">
                                    <option value="">Please Select</option>
                                    <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                    <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                    <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                    <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                </select>
                            </td>
                            <td><input name="organization_types[]" class="form-control" type="text" /></td>
                            <td><input name="organization_names[]" class="form-control" type="text" /></td>
                            <td><input name="fs_designations[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#financialstatustbl tr:last').after(trow);
            }
        });
        
        $(document).on("click", "#addistblrow", function(){
            let totRows = $('#incomestatustbl tr').length;
            var trow = `<tr>
                            <td>
                                <select class="form-control" name="isearning_sources[]">
                                    <option value="">Please Select</option>
                                    <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                    <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                    <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                    <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                </select>
                            </td>
                            <td><input name="annual_salary[]" class="form-control" type="text" /></td>
                            <td><input name="other_income[]" class="form-control" type="text" /></td>
                            <td><input name="total_income[]" class="form-control" type="text" /></td>
                            <td><input name="is_remarks[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#incomestatustbl tr:last').after(trow);
            }            
            $(".dp").datepicker({
                format: 'dd-mm-yyyy',
                endDate: '+0d',
                autoclose: true
            });
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });       
        
        $.getJSON("<?=$apiServer?>district_list.php", function (data) {
            let selectOption = '';
            $.each(data.records, function (key, value) {
                selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
            });
            $('.dists').append(selectOption);
        });
                
        $(document).on("change", "#pa_district", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.district_name = selectedVal;
                $.getJSON("<?=$apiServer?>sub_division_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_subdivision').empty().append('<option value="">Select a sub division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#pa_subdivision').append(selectOption);
                });
            }
        });
        
        $(document).on("change", "#pa_subdivision", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>revenue_circle_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_revenuecircle').empty().append('<option value="">Select a revenue circle</option>');
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#pa_revenuecircle').append(selectOption);
                });
            }
        });
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Once you submitted, you won't able to revert this";
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
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('iservices/wptbc/nclcertificate/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( ননক্ৰিমি প্ৰমাণ পত্ৰৰ বাবে আবেদন ) 
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
                        <strong style="font-size:16px; ">Supporting Document / সহায়ক নথি পত্ৰ</strong>
                        
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 20px">
                            <li>
                                Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice [Mandatory]
                                / স্হায়ী বাসিন্দাৰ পত্ৰ । ২. যদি  স্হায়ী বাসিন্দাৰ প্ৰমান পত্ৰ নাথাকে, তেন্তে ভোটাৰ কাৰ্ড/বিদুৎ বিল/  বেঙ্ক পাছবুক/ পঞ্জীকৃত মাটিৰ নথি/ জমাবন্দী ( বাধ্যতামূলক )
                            </li>
                            <li>
                                OBC / MOBC certificate issued by competent authority [Mandatory]
                                / পিচপৰা/অন্যান্য পিচপৰা জাতিৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                            </li>
                            <li>
                                Income certificate of parents [Mandatory]  
                                / পিতৃ মাতৃৰ আয়ৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                                <ol style="list-style:lower-alpha;  margin-left: 20px; margin-top: 0px">
                                    <li>Issued by the Circle Officer ( if the parents are agriculturist ) or / (ক) চক্ৰ বিষয়াৰ দ্বাৰা ( যদি পিতৃ মাতৃ খেতিয়ক হয় )</li>
                                    <li>Income certificate of parents issued by Controlling Authority / Treasury officer (if the parents are retired salaried person) or / (খ) কোষাগাৰ বিষয়াৰ দ্বাৰা, যদিহে আবেদনকাৰী পেঞ্চনধাৰী হয় ।</li>
                                    <li>Issued by Councillor/Mouzadar (in practice, in case of a non-salaried person) / (গ) কাউন্সিলৰ/মৌজাদাৰৰ দ্বাৰা, যদিহে আবেদনকাৰী দৰমহাবিহীন হয় ।</li>
                                </ol>
                            </li>
                            <li>
                                Other documents as per requirement (Voter card, Bank passbook, etc.) [Optional]  
                                / অন্যান্য নথি যেনে- ভোটাৰ কাৰ্ড, বেঙ্ক পাছবুক 
                            </li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>                        
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                            <li>Statutory charges / স্হায়ী মাচুল : NIL</li>
                            <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) /  ছপা খৰচ -  Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC)  স্কেনিং খৰচ - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ol>                        
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
                                <label>Father&apos;s Name / পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Spouse Name / গৃহিনী/স্বামীৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="spouse_name" value="<?=$spouse_name?>" maxlength="255" />
                                <?= form_error("spouse_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="255" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Birth / জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="dob" value="<?=$dob?>" maxlength="10" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile_number" value="<?=$mobile_number?>" maxlength="10" readonly />
                                <?= form_error("mobile_number") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No/Bylane No/Street Name / ঘৰ নং/ৰাস্তা নং/ৰাস্তাৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_houseno" value="<?=$pa_houseno?>" maxlength="255" />
                                <?= form_error("pa_houseno") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_landmark" value="<?=$pa_landmark?>" maxlength="255" />
                                <?= form_error("pa_landmark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State / ৰাজ্য<span class="text-danger">*</span> </label>
                                <select name="pa_state" class="form-control">
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("pa_state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District (P)/ জিলা<span class="text-danger">*</span> </label>
                                <select name="pa_district" id="pa_district" class="form-control dists">
                                    <option value="<?=$pa_district?>"><?=strlen($pa_district)?$pa_district:'Select'?></option>
                                </select>
                                <?= form_error("pa_district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division (p)/ মহকুমা<span class="text-danger">*</span> </label>
                                <select name="pa_subdivision" id="pa_subdivision" class="form-control">
                                    <option value="<?=$pa_subdivision?>"><?=strlen($pa_subdivision)?$pa_subdivision:'Select'?></option>
                                </select>
                                <?= form_error("pa_subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle(p) / ৰাজহ চক্ৰ<span class="text-danger">*</span> </label>
                                <select name="pa_revenuecircle" id="pa_revenuecircle" class="form-control">
                                    <option value="<?=$pa_revenuecircle?>"><?=strlen($pa_revenuecircle)?$pa_revenuecircle:'Select'?></option>
                                </select>
                                <?= form_error("pa_revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza / মৌজা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_mouza" value="<?=$pa_mouza?>" maxlength="255" />
                                <?= form_error("pa_mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Village/Town (p)/ গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_village" value="<?=$pa_village?>" maxlength="255" />
                                <?= form_error("pa_village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station / থানা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_ps" value="<?=$pa_ps?>" maxlength="255" />
                                <?= form_error("pa_ps") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code / পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_pincode" value="<?=$pa_pincode?>" maxlength="255" />
                                <?= form_error("pa_pincode") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Post Office / ডাকঘৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pa_po" value="<?=$pa_po?>" maxlength="255" />
                                <?= form_error("pa_po") ?>
                            </div>
                        </div>
                    </fieldset>
                                        
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other Details / অন্যান্য তথ্য  </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Name of Caste / জাতি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="caste_name" value="<?=$caste_name?>" maxlength="255" />
                                <?= form_error("caste_name") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered" id="financialstatustbl">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center" style="font-size:16px">
                                                Financial Status of Parents/ Husband/ Wife / পিতৃ-মাতৃ/স্বামী/স্ত্ৰীৰ আৰ্থিক স্হি
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Source Of Earning / উপাৰ্জনৰ উৎস</th>
                                            <th>Type Of Organisation / সংস্হাৰ ধৰণ</th>
                                            <th>Name Of Organisation/Department / বিভাগ/সংস্হাৰ নাম</th>
                                            <th>Designation/Post Held / পদবী</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $fsearningSources = (isset($fsearning_sources) && is_array($fsearning_sources)) ? count($fsearning_sources) : 0;
                                        if ($fsearningSources > 0) {
                                            for ($i = 0; $i < $fsearningSources; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addfstblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td>
                                                        <select class="form-control" name="fsearning_sources[]">
                                                            <option value="">Please Select<?=$fsearning_sources[$i]?></option>
                                                            <option value="Mother/মাতৃ" <?=($fsearning_sources[$i] === "Mother/মাতৃ")?'selected':''?>> Mother/মাতৃ</option>
                                                            <option value="Father/পিতৃ" <?=($fsearning_sources[$i] === "Father/পিতৃ")?'selected':''?>> Father/পিতৃ</option>
                                                            <option value="Husband/স্বামী" <?=($fsearning_sources[$i] === "Husband/স্বামী")?'selected':''?>> Husband/স্বামী</option>
                                                            <option value="Wife/পত্নী" <?=($fsearning_sources[$i] === "Wife/পত্নী")?'selected':''?>> Wife/পত্নী</option>
                                                        </select>
                                                    </td>
                                                    <td><input name="organization_types[]" value="<?= $organization_types[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="organization_names[]" value="<?= $organization_names[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="fs_designations[]" value="<?= $fs_designations[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td>
                                                    <select class="form-control" name="fsearning_sources[]">
                                                        <option value="">Please Select</option>
                                                        <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                                        <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                                        <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                                        <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                                    </select>
                                                </td>
                                                <td><input name="organization_types[]" class="form-control" type="text" /></td>
                                                <td><input name="organization_names[]" class="form-control" type="text" /></td>
                                                <td><input name="fs_designations[]" class="form-control" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="addfstblrow" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php }//End of if else  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered" id="incomestatustbl">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center" style="font-size:16px">
                                                Income Status / আয়ৰ স্হিতি
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Source Of Earning / আয়ৰ উৎস</th>
                                            <th>Gross Annual Salary/Amount মুঠ বাৰ্ষিক দৰমহা</th>
                                            <th>Income From Other Source / অন্য উৎসৰ পৰা</th>
                                            <th>Total / মুঠ</th>
                                            <th>Remarks / মন্তব্য</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $isearningSources = (isset($isearning_sources) && is_array($isearning_sources)) ? count($isearning_sources) : 0;
                                        if ($isearningSources > 0) {
                                            for ($i = 0; $i < $isearningSources; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addistblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td>
                                                        <select class="form-control" name="isearning_sources[]">
                                                            <option value="">Please Select</option>
                                                            <option value="Mother/মাতৃ" <?=($isearning_sources[$i] === "Mother/মাতৃ")?'selected':''?>> Mother/মাতৃ</option>
                                                            <option value="Father/পিতৃ" <?=($isearning_sources[$i] === "Father/পিতৃ")?'selected':''?>> Father/পিতৃ</option>
                                                            <option value="Husband/স্বামী" <?=($isearning_sources[$i] === "Husband/স্বামী")?'selected':''?>> Husband/স্বামী</option>
                                                            <option value="Wife/পত্নী" <?=($isearning_sources[$i] === "Wife/পত্নী")?'selected':''?>> Wife/পত্নী</option>
                                                        </select>
                                                    </td>
                                                    <td><input name="annual_salary[]" value="<?= $annual_salary[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="other_income[]" value="<?= $other_income[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="total_income[]" value="<?= $total_income[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="is_remarks[]" value="<?= $is_remarks[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td>
                                                    <select class="form-control" name="isearning_sources[]">
                                                        <option value="">Please Select</option>
                                                        <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                                        <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                                        <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                                        <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                                    </select>
                                                </td>
                                                <td><input name="annual_salary[]" class="form-control" type="text" /></td>
                                                <td><input name="other_income[]" class="form-control" type="text" /></td>
                                                <td><input name="total_income[]" class="form-control" type="text" /></td>
                                                <td><input name="is_remarks[]" class="form-control" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="addistblrow" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php }//End of if else  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                                
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> <!-- End of .row --> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>