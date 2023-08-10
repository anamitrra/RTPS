<?php
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $fathers_name = $dbrow->form_data->fathers_name;
    $mobile_number = $dbrow->form_data->mobile_number;
    $email_id = $dbrow->form_data->email_id;
    $spouse_name = $dbrow->form_data->spouse_name;
    $pan = $dbrow->form_data->pan;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $district_code = $district->district_code;
    $circle = $dbrow->form_data->circle;
    $circle_code = $circle->circle_code;
    $village = $dbrow->form_data->village;
    $village_code = $village->village_code;
    $patta = $dbrow->form_data->patta;
    $patta_no = $patta->patta_no;
    $loc_code = $patta->loc_code;
    $pattadar_name = $dbrow->form_data->pattadar_name;
    $case_no = $dbrow->form_data->case_no;
    $office_district = $dbrow->form_data->office_district;
    $office_district_code = $office_district->office_district_code;
    $office_circle = $dbrow->form_data->office_circle;
    $officeCircle = json_encode($office_circle);
    $office_circle_code = $office_circle->office_circle_code;  
    $office_circle_name = $office_circle->office_circle_name;   
    $mutation_doc_type = $dbrow->form_data->mutation_doc_type??null;
    $revenue_receipt_type = $dbrow->form_data->revenue_receipt_type??null;
    $other_doc_type = $dbrow->form_data->other_doc_type??null;    
    $mutation_doc = $dbrow->form_data->mutation_doc??null;
    $revenue_receipt = $dbrow->form_data->revenue_receipt??null;
    $other_doc = $dbrow->form_data->other_doc??null;

    $status = $dbrow->service_data->appl_status??'';
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $fathers_name = set_value("fathers_name");
    $mobile_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("mobile_number");
    $email_id = set_value("email_id");
    $spouse_name = set_value("spouse_name");
    $pan = set_value("pan");
    $address1 = set_value("address1");
    $address2 = set_value("address2");
    $state = set_value("state");
    $district = set_value("district");    
    if(strlen($district)) {
        $dist = json_decode(html_entity_decode($district));
        $district_code = $dist->district_code;
    } else {
        $district_code = '';
    }//End of if else
    $circle = set_value("circle"); 
    if(strlen($circle)) {
        $cir = json_decode(html_entity_decode($circle));
        $circle_code = $cir->circle_code;
    } else {
        $circle_code = '';
    }//End of if else
    $village = set_value("village"); 
    if(strlen($village)) {
        $vill = json_decode(html_entity_decode($village));
        $village_code = $vill->village_code;
    } else {
        $village_code = '';
    }//End of if else
    $patta = set_value("patta");
    if(strlen($patta)) {
        $pat = json_decode(html_entity_decode($patta));
        $patta_no = $pat->patta_no;
        $loc_code = $pat->loc_code;
    } else {
        $patta_no = '';
        $loc_code = '';
    }//End of if else
    $pattadar_name = set_value("pattadar_name");
    $case_no = set_value("case_no");
    $office_district = set_value("office_district");
    if(strlen($office_district)) {
        $officeDistrict = json_decode(html_entity_decode($office_district));
        $office_district_code = $officeDistrict->office_district_code;
    } else {
        $office_district_code = '';
    }//End of if else
    $office_circle = set_value("office_circle");
    if(strlen($office_circle)) {
        $officeCircle = $office_circle;
        $oc = json_decode(html_entity_decode($office_circle));
        $office_circle_code = $oc->office_circle_code??'';
        $office_circle_name = $oc->office_circle_name??'Select';
    } else {
        $officeCircle = '';
        $office_circle_code = '';
        $office_circle_name = 'Select';
    }//End of if else    
    $mutation_doc_type = set_value("mutation_doc_type");
    $revenue_receipt_type = set_value("revenue_receipt_type");
    $other_doc_type = set_value("other_doc_type");
    
    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $mutation_doc = $uploadedFiles['mutation_doc_old']??null;
    $revenue_receipt = $uploadedFiles['revenue_receipt_old']??null;
    $other_doc = $uploadedFiles['other_doc_old']??null;
    $status = ''; 
}//End of if else
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

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {   
        
        var district_code = '<?=$district_code?>';
        var circle_code = '<?=$circle_code?>';
        var village_code = '<?=$village_code?>';
        var patta_no = '<?=$patta_no?>';
        var loc_code = '<?=$loc_code?>';
        var case_no = '<?=$case_no?>';
        var pattadar_name = '<?=$pattadar_name?>';
        var office_district_code = '<?=$office_district_code?>';
        var office_circle_code = '<?=$office_circle_code?>';
        //alert(pattadar_name+" : "+case_no);
        
        var getDistricts = function(district_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_districts/')?>"+district_code,
                //data: {"sro_code": sroCode},
                beforeSend: function () {
                    $("#districts_div").html('<select name="district" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#districts_div").html(res);
                }
            });
        };//End of getDistricts()
        
        var getCircles = function(district_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_circles/')?>"+circle_code,
                data: {"district_code": district_code},
                beforeSend: function () {
                    $("#circles_div").html('<select name="circle" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#circles_div").html(res);
                }
            });
        };//End of getCircles()
        
        var getVillages = function(circle_code) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_villages/')?>"+village_code,
                data: {"circle_code": circle_code},
                beforeSend: function () {
                    $("#villages_div").html('<select name="village" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#villages_div").html(res);
                }
            });
        };//End of getVillages()
        
        var getPattanos = function(village_code) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_pattanos/')?>"+patta_no,
                data: {"village_code": village_code},
                beforeSend: function () {
                    $("#pattanos_div").html('<select name="patta" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#pattanos_div").html(res);
                }
            });
        };//End of getPattanos()
        
        var getMutationcasenos = function(loc_code, patta_no) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_casenos/')?>",
                data: {
                    "loc_code": loc_code, 
                    "patta_no":patta_no,
                    "case_no" : case_no
                },
                beforeSend: function () {
                    $("#casenos_div").html('<select name="case_no" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#casenos_div").html(res);
                }
            });
        };//End of getMutationcasenos()
        
        var getPattadars = function(loc_code, patta_no) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_pattadars/')?>",
                data: {
                    "loc_code": loc_code, 
                    "patta_no":patta_no, 
                    "pattadar_name":"<?=utf8_encode($pattadar_name)?>"
                },
                beforeSend: function () {
                    $("#pattadars_div").html('<select name="pattadar_name" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#pattadars_div").html(res);
                }
            });
        };//End of getPattadars()
        
        var getOfficecircles = function(district_code, circle_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/mutationorder/api/get_officecircles/')?>",
                data: {
                    "district_code": district_code, 
                    "office_circle_code":circle_code
                },
                beforeSend: function () {
                    $("#officecircles_div").html('<select name="office_circle" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#officecircles_div").html(res);
                }
            });
        };//End of getOfficecircles()
        
        getDistricts(district_code);
        if(district_code.length) {
            getCircles(district_code);
        }//End of if
        
        if(circle_code.length) {
            getVillages(circle_code);
        }//End of if
        
        if(village_code.length) {
            getPattanos(village_code);
        }//End of if
        
        if(loc_code.length && patta_no.length) {
            getMutationcasenos(loc_code, patta_no);
            getPattadars(loc_code, patta_no);
        }//End of if
        
        if(office_district_code.length) {
            getOfficecircles(office_district_code, office_circle_code);
        }//End of if
        
        $(document).on("change", "#district", function(){               
            let dist = $(this).val(); //alert(dist);
            if(dist.length) {
                let district = $.parseJSON(dist); //alert(district.district_code);
                getCircles(district.district_code);
            }//End of if
        });
        
        $(document).on("change", "#circle", function(){               
            let cir = $(this).val(); //alert(cir);
            if(cir.length) {
                let circle = $.parseJSON(cir); //alert(circle.circle_code);
                getVillages(circle.circle_code);
            }//End of if
        });
        
        $(document).on("change", "#village", function(){               
            let vill = $(this).val();
            if(vill.length) {
                let village = $.parseJSON(vill);
                getPattanos(village.village_code);
            }//End of if
        });
        
        $(document).on("change", "#patta", function(){               
            let patta = $(this).val();
            if(patta.length) {
                let pattano = $.parseJSON(patta);
                getMutationcasenos(pattano.loc_code, pattano.patta_no);
                getPattadars(pattano.loc_code, pattano.patta_no);
            }//End of if
        });
        
        $(document).on("change", "#office_district", function(){               
            let selectedVal = $(this).val(); //alert(selectedVal);
            if(selectedVal.length) {
                var office_district = $.parseJSON(selectedVal);
                getOfficecircles(office_district.office_district_code, office_circle_code);
            } else {
                alert('Please select a district');
                $("#office_district").focus();
            }
        });   
        
        var landPatta = parseInt(<?=strlen($mutation_doc)?1:0?>);
        $("#mutation_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: landPatta?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var khajnaReceipt = parseInt(<?=strlen($revenue_receipt)?1:0?>);
        $("#revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: khajnaReceipt?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
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
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/mutationorder/registration/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="mutation_doc_old" value="<?=$mutation_doc?>" type="hidden" />
            <input name="revenue_receipt_old" value="<?=$revenue_receipt?>" type="hidden" />
            <input name="other_doc_old" value="<?=$other_doc?>" type="hidden" />
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
                        
                        <strong style="font-size:16px;  margin-top: 30px">Attachments/Annexures :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li>1. Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought [Mandatory]</li>
                            <li>১. পৰিৱৰ্তন কেছ নং। পৰিৱৰ্তন আদেশ আৰু পক্ষবোৰৰ নামৰ বাবে আবেদনৰ সময়ত সৃষ্টি কৰা হয় যাৰ বাবে পৰিৱৰ্তন (পঞ্জীয়ন) অৰ্ডাৰ/বিবিধ কেছ অৰ্ডাৰৰ প্ৰতিলিপি বিচৰা হয় [বাধ্যতামূলক]</li>
                            <li>2. Up to date Land revenue receipt in respect of the land applied for [Mandatory]</li>
                            <li>২. ভূমিৰ সন্দৰ্ভত আপ টু ডেট ভূমি ৰাজহ প্ৰাপ্তি [বাধ্যতামূলক]</li>
                            <li>3. Court fee Stamp as per the court fee act [Mandatory]</li>
                            <li>৩.আদালতৰ মাচুল আইন অনুসৰি আদালতৰ মাচুল মোহৰ [বাধ্যতামূলক]</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 30px">The Stipulated time limit for delivery/বিতৰণৰ বাবে নিৰ্ধাৰিত সময় সীমা :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li> 05 days if no objection from any person is filed (as per ARTPS citizen charter)</li>
                            <li>যদি কোনো ব্যক্তিৰ পৰা কোনো আপত্তি দাখিল কৰা নহয় তেন্তে 05 দিন (এআৰটিপিএছ নাগৰিক চাৰ্টাৰ অনুসৰি)</li>    
                        </ul>
                        
                        <strong style="font-size:16px;  margin-top: 30px">Fees/Charges/মাচুল/মাচুল :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li> 1. Rs. 20/- will be charged as Application Fees ( Mode of Payment will be Online )</li>
                            <li>১.আৱেদন মাচুল হিচাপে 20/- টকা লোৱা হ'ব (পৰিশোধৰ পদ্ধতি অনলাইন হ'ব)</li>    
                        </ul>
                        
                        <strong style="font-size:16px;  margin-top: 30px">General Instruction/ সাধাৰণ নিৰ্দেশনা :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li> 1. All the * marked fields are mandatory and need to be filled up</li>
                            <li>১.সকলো * চিহ্নিত ক্ষেত্ৰ বাধ্যতামূলক আৰু পূৰণ কৰিব লাগিব</li>
                            <li> 2. The size of documents to be uploaded at the time of Application submission should not exceed <strong>1MB and</strong>format should be<strong>pdf/JPEG</strong>. No other format will be accepted</li>
                            <li> 2.আৱেদন দাখিলৰ সময়ত আপলোড কৰিব লগা নথিপত্ৰৰ আকাৰ 1 এমবিঅতিক্ৰম কৰিব নালাগে আৰু ফৰ্মেট পিডিএফ/জেপিইজি হ'ব লাগে। অন্য কোনো ফৰ্মেট গ্ৰহণ কৰা নহ'ব</li>
                        </ul>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Applicant Name/আবেদনকাৰীৰ নাম<span class="text-danger">*</span></label>
                                <input name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" class="form-control" type="text" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Gender/লিংগ<span class="text-danger">*</span></label>
                                <select name="applicant_gender" id="applicant_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male"  <?=($applicant_gender == 'Male')?'selected':''?>>Male / পুৰুষ</option>
                                    <option value="Female"  <?=($applicant_gender == 'Female')?'selected':''?>>Female / মহিলা</option>
                                    <option value="Others"  <?=($applicant_gender == 'Others')?'selected':''?>>Others / অন্যান্য</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Father's Name/দেউতাৰ নাম<span class="text-danger">*</span></label>
                                <input name="fathers_name" id="fathers_name" value="<?=$fathers_name?>" class="form-control" type="text" />
                                <?= form_error("fathers_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mobile Number/ম'বাইল নম্বৰ<span class="text-danger">*</span></label>
                                <input name="mobile_number" id="mobile_number" value="<?=$mobile_number?>" class="form-control" <?=($user_type == "user")?'readonly':''?> maxlength="10" type="text" />
                                <?= form_error("mobile_number") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>E-Mail/ইমেইল</label>
                                <input name="email_id" id="email_id" value="<?=$email_id?>" class="form-control" style="text-transform:lowercase" type="text" />
                                <?= form_error("email_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Spouse Name/পত্নীৰ নাম</label>
                                <input name="spouse_name" id="spouse_name" value="<?=$spouse_name?>" class="form-control" type="text" />
                                <?= form_error("spouse_name") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pan Number/পেন নম্বৰ</label>
                                <input name="pan" id="pan" value="<?=$pan?>" class="form-control" maxlength="10" style="text-transform:uppercase" type="text" />
                                <?= form_error("pan") ?>
                            </div>
                        </div><!--End of .row -->
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Location Details/ অৱস্থানৰ বিৱৰণ</legend>                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address 1/ঠিকনা ১ <span class="text-danger">*</span></label>
                                <input name="address1" id="address1" value="<?=$address1?>" class="form-control" type="text" />
                                <?= form_error("address1") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Address 2/ঠিকনা ২</label>
                                <input name="address2" id="address2" value="<?=$address2?>" class="form-control" type="text" />
                                <?= form_error("address2") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="state">State/ৰাজ্য<span class="text-danger">*</span></label>
                                <select name="state" id="state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="district">District/জিলা<span class="text-danger">*</span></label>
                                <div id="districts_div">
                                    <select name="district" id="district" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("district") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="circle">Circle/ৰাজহ চক্ৰ<span class="text-danger">*</span></label>
                                <div id="circles_div">
                                    <select name="circle" id="circle" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("circle") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="village">Revenue Village/Town/গাওঁ/নগৰ<span class="text-danger">*</span></label>
                                <div id="villages_div">
                                    <select name="village" id="village" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("village") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Patta No/পাট্টা নম্বৰ<span class="text-danger">*</span></label>
                                <div id="pattanos_div">
                                    <select name="patta" id="patta" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("patta") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="pattadar_name">Mutation order to be issued in the name of/পৰিৱৰ্তনৰ আদেশ ৰ নামত জাৰী কৰিব লাগিব<span class="text-danger">*</span></label>
                                <div id="pattadars_div">
                                    <select name="pattadar_name" id="pattadar_name" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("pattadar_name") ?>
                            </div>
                        </div><!--End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="case_no">Mutation Case No/পৰিৱৰ্তন ৰ ক্ষেত্ৰ নম্বৰ<span class="text-danger">*</span></label>
                                <div id="casenos_div">
                                    <select name="case_no" id="case_no" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("case_no") ?>
                            </div>
                        </div><!--End of .row -->
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Select where Application will be submitted/আৱেদন ক'ত দাখিল কৰা হ'ব বাছনি কৰক</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="office_district">Districts/জিলা<span class="text-danger">*</span></label>
                                <select name="office_district" id="office_district" class="form-control">
                                    <option value="">Select</option>
                                    <?php if($districts){
                                        foreach($districts as $item){ 
                                            $odArr = array(
                                                "office_district_id"=>$item->district_id, 
                                                "office_district_name" =>$item->district_name,
                                                "office_district_code" => $item->district_code
                                            ); ?>
                                            <option value='<?=json_encode($odArr)?>' <?=($office_district_code == $item->district_code) ? "selected":"" ?>><?=$item->district_name?></option>
                                        <?php }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("office_district") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="office_circle">Circle Offices/ৰাজহ চক্ৰ<span class="text-danger">*</span></label>
                                <div id="officecircles_div">
                                    <select name="office_circle" id="office_circle" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <?= form_error("office_circle") ?>
                            </div>
                        </div><!--End of .row -->
                    </fieldset>
                    
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Attach Enclosures </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th style="width:220px">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="mutation_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought" <?=($mutation_doc_type === 'Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought')?'selected':''?>>Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought</option>
                                                </select>
                                                <?= form_error("mutation_doc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mutation_doc" name="mutation_doc" type="file" />
                                                </div>
                                                <?php if(strlen($mutation_doc)){ ?>
                                                    <a href="<?=base_url($mutation_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Land Revenue Receipt<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="revenue_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Revenue Receipt" <?=($revenue_receipt_type === 'Land Revenue Receipt')?'selected':''?>>Land Revenue Receipt</option>
                                                </select>
                                                <?= form_error("revenue_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="revenue_receipt" name="revenue_receipt" type="file" />
                                                </div>
                                                <?php if(strlen($revenue_receipt)){ ?>
                                                    <a href="<?=base_url($revenue_receipt)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Other</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other" <?=($other_doc_type === 'Other')?'selected':''?>>Other</option>
                                                </select>
                                                <?= form_error("other_doc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                                <?php if(strlen($other_doc)){ ?>
                                                    <a href="<?=base_url($other_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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