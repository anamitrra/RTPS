<?php
if(!empty($this->session->userdata('role'))){
    $user_type = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
} else {
    $user_type = "user";
}//End of if else
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;    
    $marriage_type = $dbrow->marriage_type;
    $mt_id = $marriage_type->mt_id;
    $marriage_act = $dbrow->marriage_act;
    $ma_id = $marriage_act->ma_id;
    $applicant_prefix = $dbrow->applicant_prefix;
    $applicant_first_name = $dbrow->applicant_first_name;
    $applicant_middle_name = $dbrow->applicant_middle_name;
    $applicant_last_name = $dbrow->applicant_last_name;
    $applicant_gender = $dbrow->applicant_gender;
    $applicant_mobile_number = $dbrow->applicant_mobile_number;
    $applicant_email_id = $dbrow->applicant_email_id;
    $office_district = $dbrow->office_district;
    $district_name = $dbrow->district_name;
    $sro_code = $dbrow->sro_code;
    $office_name = $dbrow->office_name;
    $relationship_before = $dbrow->relationship_before;
    $ceremony_date = $dbrow->ceremony_date;    
    $remarks = $dbrow->remarks??'';  
    $query_time = isset($dbrow->query_time)?date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->query_time))):'';
    $status = $dbrow->status??''; 
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    
    $marriage_type = set_value("marriage_type");
    if(strlen($marriage_type)) {
        $marriageType = json_decode(html_entity_decode($marriage_type));
        $mt_id = $marriageType->mt_id;
    } else {
        $mt_id = '';
    }//End of if else
    $marriage_act = set_value("marriage_act");    
    if(strlen($marriage_act)) {
        $marriageType = json_decode(html_entity_decode($marriage_act));
        $ma_id = $marriageType->ma_id;
    } else {
        $ma_id = '';
    }//End of if else   
    
    $applicant_prefix = set_value("applicant_prefix");
    $applicant_first_name = set_value("applicant_first_name");
    $applicant_middle_name = set_value("applicant_middle_name");
    $applicant_last_name = set_value("applicant_last_name");
    $applicant_gender = set_value("applicant_gender");
    $applicant_mobile_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("applicant_mobile_number");
    $applicant_email_id = set_value("applicant_email_id");

    $office_district = set_value("office_district");
    $district_name = set_value("district_name");
    $sro_code = set_value("sro_code");
    $office_name = set_value("office_name");
    $relationship_before = set_value("relationship_before");
    $ceremony_date = set_value("ceremony_date");
    $remarks = '';  
    $query_time = '';
    $status = ''; 
}//End of if else
//die(" mt_id : ".$mt_id);
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {        
                
        $(document).on("change", "#marriage_type", function(){            
            var mrgType = $(this).val(); //alert(mrgType);
            if(mrgType.length === 0) {
                alert("Please select a type");
            } else {
                var marriage_type = $.parseJSON(mrgType);
                var mtId = parseInt(marriage_type.mt_id); //alert(mtId);  
                if(mtId == 2) {
                    $("#already_performed_marriage").show();
                } else {
                    $("#already_performed_marriage").hide();
                }
            }
                
        });
        
        $(document).on("change", "#office_district", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var distName = $(this).find("option:selected").text(); 
                var distNameArr = distName.split(' DISTRICT - ');
                var district_name = distNameArr[1].slice(0,-1); //alert(district_name);
                $("#district_name").val(district_name);
            
                $.getJSON("<?=base_url('spservices/necertificate/getlocation/')?>"+selectedVal, function (data) {
                let selectOption = '';
                $('#sro_code').empty().append('<option value="">Select an office</option>');
                $.each(data, function (key, value) {
                    selectOption += '<option value="'+value.org_unit_code+'">'+value.org_unit_name+'</option>';
                });
                $('#sro_code').append(selectOption);
            });
            }
        });
        
        $(document).on("change", "#sro_code", function(){               
            let sroCode = $(this).val();
            $("#office_name").val($(this).find("option:selected").text());
            getCircles(sroCode);
        });
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE_NEXT') {
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
                if (result.value) { //alert(clickedBtn+" : "+result.value);
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE_NEXT')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });        
        
        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            //endDate: '+0d',
            autoclose: true
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/marriageregistration_landhub/applicantdetails/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="district_name" name="district_name" value="<?=$district_name?>" type="hidden" />
            <input id="office_name" name="office_name" value="<?=$office_name?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                       <?=$service_name?> 
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
                        <legend class="h5">Guidelines for Filling Up the Form/প্ৰপত্ৰ ভৰ্তিকৰণৰ নিৰ্দেশনা </legend>
                        
                        <strong style="font-size:16px;">Documents to be enclosed with the application :</strong>                        
                        <ul style="margin-left: 10px; margin-bottom: 30px">
                            <li>   1. Copy of the Applicant's ID proof(Bride/Groom) to be uploaded in the annexure section.</li>
                            <li>১. আবেদনকাৰিৰ পৰিচয় পত্ৰৰ প্ৰতিলিপি (দৰা-কইনা) সন্নিবিষ্ট শাখাত আপলোড কৰিব লাগিব</li>
                            <li style="height:20px"></li>
                            
                            <li>   2. Copy of the Address proof (Voter ID/Passport/Bank Passbook/Aadhaar Card etc) of Bride/Groom.</li>
                            <li>২. আবেদনকাৰিৰ ঠিকনাৰ প্ৰমাণ পত্ৰ (ভোটাৰ পৰিচয় পত্ৰ/প্ৰৱেশিকা পৰীক্ষাৰ প্ৰৱেশ পত্ৰ/পাচপোর্ট/বেংক পাচবুক/আধাৰ কাৰ্ড) দৰা-কইনা উভয়ৰে</li>
                            <li style="height:20px"></li>
                            
                            <li>   3. Copy of Age proof(Birth certificate/HSLC Admit) of Bride/Groom.</li>
                            <li>৩. বয়সৰ প্ৰমাণ পত্ৰ(জন্মৰ প্ৰমাণ পত্ৰ/প্ৰৱেশিকা পৰীক্ষাৰ প্ৰৱেশ পত্ৰ)</li>
                            <li style="height:20px"></li>
                            
                            <li>   
                                4. In case of Intended Marriage,download the Marriage Notice Template 
                                (<a href="<?=base_url('storage/docs/intended-marriage.pdf')?>" target="_blank" style="font-size:18px">Click here to download</a>)
                                and Upload it in the annexure section along with the signatures of both bride and groom.
                            </li>
                            <li>৪.ইপ্সিত বিবাহৰ ক্ষেত্ৰত Marriage Notice Template ডাউনলোড কৰি দৰা-কইনা উভয়ৰে চহীৰ সৈতে সন্নিবিষ্ট শাখাত  আপলোড কৰিব</li>
                            <li style="height:20px"></li>
                            
                            <li>5. In case of Solemnised Marriage, Signature of both Wife and Husband are required to be uploaded in the annexure section.</li>
                            <li> ৫.আনুষ্ঠানিক বিবাহৰ ক্ষেত্ৰত দৰা-কইনাৰ চহী সন্নিবিষ্ট শাখাত আপলোড কৰিব</li>
                        </ul>
                        
                        <strong style="font-size:16px;">Stipulated time limit for delivery/প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা :</strong>   
                        <p style="margin-left:10px;  margin-bottom: 30px">
                            After 30 days and before 90 days from the date of publication of notice for intended marriage/application for registration of marriage./ইপ্সিত বিবাহৰ আবেদন পঞ্জীয়ন কৰণৰ‌ বাবে জাননী জাৰী কৰাৰ পৰা ৩০ দিনৰ পৰা ৯০‌ দিনৰ ভিতৰত
                        </p>
                        
                        <strong style="font-size:16px;">Eligibility criteria to obtain the certificate/গ্ৰহনযোগ্যতা নিৰ্ণায়ক :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li>Age/বয়স - Bride-not less than 18 years/Groom- not less than 21 years in case of Intended Marriage/ইপ্সিত বিবাহৰ ক্ষেত্ৰত কইনাৰ‌ নিম্নতম ১৮ বছৰ / দৰাৰ ২১ বছৰ</li>
                            <li>Both Bride and Groom - not less than 21 years in case of Solemnised Marriage. আনুষ্ঠানিক বিবাহৰ ক্ষেত্ৰত দৰা-কইনা উভয়ৰে নিম্নতম ২১ বছৰ</li>
                        </ul>
                        
                        <strong style="font-size:16px;  margin-top: 30px">Fees/Charges/মাছুল :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li> 1.  Rs. 50/- notice fee./জাননী মাছুল</li>
                            <li>2. RTPS fee of rupees 200/- per appilcation./প্ৰতিখন আবেদনৰ বাবত ২০০ টকা Rtps ফিছ</li>
                        </ul>
                        
                        <strong style="font-size:16px;  margin-top: 30px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li>1. All the * marked fileds are mandatory and need to be filled up.</li>
                            <li>১.  * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব।</li>
                            <li style="height:20px"></li>
                            
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            <li style="height:20px"></li>
                            
                            <li>3. In case of Solemnised Marriage,the signature type should be in JPEG format.</li>
                            <li>৩. আনুষ্ঠানিক বিবাহৰ ক্ষেত্ৰত স্বাক্ষৰ পদ্ধতি jpeg formatত হ'ব লাগিব।</li>
                        </ul>
                    </fieldset>
                    
                    <?php if(($status === 'QS') && (strlen($remarks))) { ?>
                        <fieldset class="border border-danger" style="margin-top:40px">
                            <legend class="h5">Query details</legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <p>The following query is made by the <strong>Department</strong> on <strong><?=$query_time?></strong></p>
                                    <?=$remarks?>
                                </div>
                            </div>
                        </fieldset>
                    <?php }//End of if ?>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Apply for Marriage Registration/বিবাহ পঞ্জীয়নৰ বাবে আবেদন
                        </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Marriage type/বিবাহৰ প্ৰকাৰ <span class="text-danger">*</span> </label>
                                <select name="marriage_type" id="marriage_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value='<?=json_encode(array("mt_id"=>1, "mt_name" => "Intended Marriage"))?>' <?=($mt_id == 1)?'selected':''?>>Intended Marriage</option>
                                    <option value='<?=json_encode(array("mt_id"=>2, "mt_name" => "Solemnised Marriage(Marriage already Performed)"))?>' <?=($mt_id == 2)?'selected':''?>>Solemnised Marriage(Marriage already Performed)</option>
                                </select>
                                <?= form_error("marriage_type") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Marriage Act/ বিবাহ আইন <span class="text-danger">*</span> </label>
                                <select name="marriage_act" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value='<?=json_encode(array("ma_id"=>1, "ma_name" => "The Special Marriage Act, 1954"))?>' <?=($ma_id == 1)?'selected':''?>>The Special Marriage Act, 1954</option>
                                    <option value='<?=json_encode(array("ma_id"=>2, "ma_name" => "The Hindu Marriage Act, 1955"))?>' <?=($ma_id == 2)?'selected':''?>>The Hindu Marriage Act, 1955</option>
                                </select>
                                <?= form_error("marriage_act") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="applicant_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Mr." <?=($applicant_prefix == 'Mr.')?'selected':''?>>Mr.</option>
                                    <option value="Mrs." <?=($applicant_prefix == 'Mrs.')?'selected':''?>>Mrs.</option>
                                    <option value="Miss" <?=($applicant_prefix == 'Miss')?'selected':''?>>Miss</option>
                                </select>
                                <?= form_error("applicant_prefix") ?>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>First Name/ প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_first_name" id="father_name" value="<?=$applicant_first_name?>" maxlength="255" />
                                <?= form_error("applicant_first_name") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Middle Name/ মধ্যনাম</label>
                                <input type="text" class="form-control" name="applicant_middle_name" value="<?=$applicant_middle_name?>" maxlength="255" />
                                <?= form_error("applicant_middle_name") ?>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Last Name/ অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_last_name" value="<?=$applicant_last_name?>" maxlength="255" />
                                <?= form_error("applicant_last_name") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Gender / লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender == 'Male')?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender == 'Female')?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender == 'Others')?'selected':''?>>Others</option>
                                </select>
                                <?=form_error('applicant_gender')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input name="applicant_mobile_number" value="<?=$applicant_mobile_number?>" maxlength="10" <?=($user_type == "user")?'readonly':''?> class="form-control" type="text" />
                                <?= form_error("applicant_mobile_number") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="applicant_email_id" value="<?=$applicant_email_id?>" maxlength="100" />
                                <?= form_error("applicant_email_id") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applied To/আবেদন কৰা কাৰ্য্যালয়</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District / জিলা নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="office_district" id="office_district" class="form-control dists">
                                    <option value="">Select</option>
                                    <?php if($sro_dist_list){
                                        foreach($sro_dist_list as $item){ ?>
                                            <option value="<?=$item->parent_org_unit_code?>" <?= ($office_district == $item->parent_org_unit_code) ? "selected":"" ?>><?=$item->org_unit_name_2?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <?= form_error("office_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select office/কাৰ্য্যালয় নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="sro_code" id="sro_code" class="form-control dists">
                                    <option value="<?=$sro_code?>"><?=(strlen($sro_code))?$office_name:'Select'?></option>
                                </select>
                                <?= form_error("sro_code") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="already_performed_marriage" class="border border-success" style="margin-top:40px; display: <?=($mt_id == 2)?'block':'none'?>">
                        <legend class="h5">(In case Marriage Type is selected as "Marriage already performed")</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relationship of Parties before Marriage (if any)/ বিবাহৰ আগতে পাৰ্টিৰ সম্পৰ্ক <span class="text-danger">*</span> </label>
                                <select name="relationship_before" id="relationship_before" class="form-control dists">
                                    <option value="">Select</option>
                                    <option value="YES" <?=($relationship_before=="YES")?'selected':''?>>YES</option>
                                    <option value="NO" <?=($relationship_before=="NO")?'selected':''?>>NO</option>
                                </select>
                                <?= form_error("relationship_before") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Marriage Ceremony/ বিবাহ অনুষ্ঠানৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input name="ceremony_date" id="bridegroom_dob" value="<?=$ceremony_date?>" class="form-control dp" type="text" autocomplete="off" />
                                <?= form_error("ceremony_date") ?>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE_NEXT" type="button">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>