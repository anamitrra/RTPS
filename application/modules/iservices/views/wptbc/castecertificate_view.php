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
    $religion = $dbrow->religion;
    $mobile_number = $dbrow->mobile_number;
    $age = $dbrow->age;
    $sub_caste = $dbrow->sub_caste;
    $email = $dbrow->email;
    $applicant_photo = $dbrow->applicant_photo;
    $ancestor_name = $dbrow->ancestor_name;
    $ancestor_address1 = $dbrow->ancestor_address1;
    $ancestor_address2 = $dbrow->ancestor_address2;
    $ancestor_state = $dbrow->ancestor_state;
    $ancestor_district = $dbrow->ancestor_district;
    $ancestor_subdivision = $dbrow->ancestor_subdivision;
    $ancestor_circleoffice = $dbrow->ancestor_circleoffice;
    $ancestor_mouza = $dbrow->ancestor_mouza;
    $ancestor_village = $dbrow->ancestor_village;
    $ancestor_ps = $dbrow->ancestor_ps;
    $ancestor_po = $dbrow->ancestor_po;
    $ancestor_pin = $dbrow->ancestor_pin;
    $ancestor_relation = $dbrow->ancestor_relation;
    $ancestor_subcaste = $dbrow->ancestor_subcaste;
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
    $address_same = $dbrow->address_same;
    $ra_houseno = $dbrow->ra_houseno;
    $ra_landmark = $dbrow->ra_landmark;
    $ra_state = $dbrow->ra_state;
    $ra_district = $dbrow->ra_district;
    $ra_subdivision = $dbrow->ra_subdivision;
    $ra_revenuecircle = $dbrow->ra_revenuecircle;
    $ra_mouza = $dbrow->ra_mouza;
    $ra_village = $dbrow->ra_village;
    $ra_ps = $dbrow->ra_ps;
    $ra_pincode = $dbrow->ra_pincode;
    $ra_po = $dbrow->ra_po;
    $forefather_occupation = $dbrow->forefather_occupation;
    $certificate_purpose = $dbrow->certificate_purpose;
    $parent_voterlist = $dbrow->parent_voterlist;
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $spouse_name = set_value("spouse_name");
    $religion = set_value("religion");
    $mobile_number = $this->session->mobile;//set_value("mobile_number");
    $age = set_value("age");
    $sub_caste = set_value("sub_caste");
    $email = set_value("email");
    $applicant_photo = null;
    $ancestor_name = set_value("ancestor_name");
    $ancestor_address1 = set_value("ancestor_address1");
    $ancestor_address2 = set_value("ancestor_address2");
    $ancestor_state = set_value("ancestor_state");
    $ancestor_district = set_value("ancestor_district");
    $ancestor_subdivision = set_value("ancestor_subdivision");
    $ancestor_circleoffice = set_value("ancestor_circleoffice");
    $ancestor_mouza = set_value("ancestor_mouza");
    $ancestor_village = set_value("ancestor_village");
    $ancestor_ps = set_value("ancestor_ps");
    $ancestor_po = set_value("ancestor_po");
    $ancestor_pin = set_value("ancestor_pin");
    $ancestor_relation = set_value("ancestor_relation");
    $ancestor_subcaste = set_value("ancestor_subcaste");
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
    $address_same = set_value("address_same");
    $ra_houseno = set_value("ra_houseno");
    $ra_landmark = set_value("ra_landmark");
    $ra_state = set_value("ra_state");
    $ra_district = set_value("ra_district");
    $ra_subdivision = set_value("ra_subdivision");
    $ra_revenuecircle = set_value("ra_revenuecircle");
    $ra_mouza = set_value("ra_mouza");
    $ra_village = set_value("ra_village");
    $ra_ps = set_value("ra_ps");
    $ra_pincode = set_value("ra_pincode");
    $ra_po = set_value("ra_po");
    $forefather_occupation = set_value("forefather_occupation");
    $certificate_purpose = set_value("certificate_purpose");
    $parent_voterlist = set_value("parent_voterlist");
}//End of if else
//die($title." : ".$obj_id);
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
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
        
        $("#applicant_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "png", "gif","jpeg"]
        });
        
        $.getJSON("<?=$apiServer?>district_list.php", function (data) {
            let selectOption = '';
            $.each(data.records, function (key, value) {
                selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
            });
            $('.dists').append(selectOption);
        });        
       
       $(document).on("change", "#ancestor_district", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.district_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>sub_division_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ancestor_subdivision').empty().append('<option value="">Select a sub division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#ancestor_subdivision').append(selectOption);
                });
            }
        });     
       
       $(document).on("change", "#ancestor_subdivision", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>revenue_circle_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ancestor_circleoffice').empty().append('<option value="">Select a circle office</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#ancestor_circleoffice').append(selectOption);
                });
            }
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
                
        $(document).on("change", "#ra_district", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.district_name = selectedVal;
                $.getJSON("<?=$apiServer?>sub_division_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ra_subdivision').empty().append('<option value="">Select a sub division</option>');
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#ra_subdivision').append(selectOption);
                });
            }
        });
        
        $(document).on("change", "#ra_subdivision", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>revenue_circle_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ra_revenuecircle').empty().append('<option value="">Select a revenue circle</option>');
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#ra_revenuecircle').append(selectOption);
                });
            }
        });
                
        $(document).on("change", ".address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "YES") {
                $("#ra_houseno").val($("#pa_houseno").val());
                $("#ra_landmark").val($("#pa_landmark").val());
                $("#ra_state").val($("#pa_state").val());
                $("#ra_district").val($("#pa_district").val());
                
                let subdivision = $("#pa_subdivision").val();
                $('#ra_subdivision').empty().append('<option value="'+subdivision+'">'+subdivision+'</option>');
                let revenuecircle = $("#pa_revenuecircle").val();
                $('#ra_revenuecircle').empty().append('<option value="'+revenuecircle+'">'+revenuecircle+'</option>');
                
                $("#ra_mouza").val($("#pa_mouza").val());
                $("#ra_village").val($("#pa_village").val());
                $("#ra_ps").val($("#pa_ps").val());
                $("#ra_pincode").val($("#pa_pincode").val());
                $("#ra_po").val($("#pa_po").val());
            } else {
                $("#ra_houseno").val("");
                $("#ra_landmark").val("");
                $("#ra_state").val("");
                $("#ra_district").val("");
                $("#ra_subdivision").val("");
                $("#ra_revenuecircle").val("");
                $("#ra_mouza").val("");
                $("#ra_village").val("");
                $("#ra_ps").val("");
                $("#ra_pincode").val("");
                $("#ra_po").val("");
            }//End of if else
        });//End of onChange #reloadcaptcha
        
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
        <form id="myfrm" method="POST" action="<?= base_url('iservices/wptbc/castecertificate/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                        Application for Issuance Of Scheduled Caste Certificate<br>
                        ( অনুসুচিত জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন ) 
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
                            <li>Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971 [Optional] / NRC 1951 মতে লিগেচী তথ্যৰ নকল</li>
                            <li>Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice [Mandatory] / স্হানীয় বাসিন্দাৰ প্ৰমানপত্ৰ</li>
                            <li> Caste certificate of father or any supporting proof of caste status [Mandatory] / পিতৃৰ জাতি প্ৰমানপত্ৰ ( নাইবা পৰিয়ালৰ আন কাৰোবাৰ )</li>
                            <li> Report of Gaonburah in case of rural areas / Ward Commissioner in case of urban areas. [Mandatory] / গাওঁ অঞ্চলৰ বাবে গাওঁবুঢ়়াৰ প্ৰমানপত্ৰ চহৰ অঞ্চলৰ বাবে ৱাৰ্ড আয়ুক্তৰ প্ৰমানপত্ৰ</li>
                            <li>*If the parents of the applicant has already been issued SC certificate, recommendation of the Gaonburah / Ward Commissioner may be skipped Applicant’s photo [Mandatory] / যদি আবেদনকাৰীৰ পিতৃ মাতৃক আগতেই জাতিৰ প্ৰমানপত্ৰ দিয়া হৈছে ।</li>
                            <li>Recommendation of President / Secretary of District Anuhushit Jati Parishad [Mandatory] / পঞ্চায়ত/জাতি উন্নয়ন পৰিষদৰ সভাপতিৰ অনুমোদন</li>
                            <li>Other document as per requirement (Voter List, bank passbook etc.) [Optional] / প্ৰয়োজনীয় অন্য নথি</li>
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
                                <label>Religion / ধৰ্ম  <span class="text-danger">*</span> </label>
                                <select name="religion" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Hindu" <?=($religion === "Hindu")?'selected':''?>>Hindu</option>
                                    <option value="Muslim" <?=($religion === "Muslim")?'selected':''?>>Muslim</option>
                                    <option value="Christian" <?=($religion === "Christian")?'selected':''?>>Christian</option>
                                    <option value="Sikh" <?=($religion === "Sikh")?'selected':''?>>Sikh</option>
                                    <option value="Buddhist" <?=($religion === "Buddhist")?'selected':''?>>Buddhist</option>
                                    <option value="Jain" <?=($religion === "Jain")?'selected':''?>>Jain</option>
                                    <option value="Others" <?=($religion === "Others")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("religion") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile_number" value="<?=$mobile_number?>" maxlength="10" readonly />
                                <?= form_error("mobile_number") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Age / বয়স <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="age" value="<?=$age?>" maxlength="2" />
                                <?= form_error("age") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Caste / উপজাতি <span class="text-danger">*</span> </label>
                                <select name="sub_caste" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Bansphor" <?=($sub_caste === "Bansphor")?'selected':''?>>Bansphor</option>
                                    <option value="Bhuinmali, Mali" <?=($sub_caste === "Bhuinmali, Mali")?'selected':''?>>Bhuinmali, Mali</option>
                                    <option value="Btittial Bania, Bania" <?=($sub_caste === "Btittial Bania, Bania")?'selected':''?>>Btittial Bania, Bania</option>
                                    <option value="Dugla, Dholi" <?=($sub_caste === "Dugla, Dholi")?'selected':''?>>Dugla, Dholi</option>
                                    <option value="Hira" <?=($sub_caste === "Hira")?'selected':''?>>Hira</option>
                                    <option value="Jalkeot" <?=($sub_caste === "Jalkeot")?'selected':''?>>Jalkeot</option>
                                    <option value="Jhalo, Malo, Jhalo-Malo" <?=($sub_caste === "Jhalo, Malo, Jhalo-Malo")?'selected':''?>>Jhalo, Malo, Jhalo-Malo</option>
                                    <option value="Kaibartta, Jaliya" <?=($sub_caste === "Kaibartta, Jaliya")?'selected':''?>>Kaibartta, Jaliya</option>
                                    <option value="Lalbegi" <?=($sub_caste === "Lalbegi")?'selected':''?>>Lalbegi</option>
                                    <option value="Mahara" <?=($sub_caste === "Mahara")?'selected':''?>>Mahara</option>
                                    <option value="Mehtar, Bhangi" <?=($sub_caste === "Mehtar, Bhangi")?'selected':''?>>Mehtar, Bhangi</option>
                                    <option value="Muchi, Rishi" <?=($sub_caste === "Muchi, Rishi")?'selected':''?>>Muchi, Rishi</option>
                                    <option value="Namasudra" <?=($sub_caste === "Namasudra")?'selected':''?>>Namasudra</option>
                                    <option value="Patni" <?=($sub_caste === "Patni")?'selected':''?>>Patni</option>
                                    <option value="Sutradhar" <?=($sub_caste === "Sutradhar")?'selected':''?>>Sutradhar</option>
                                    <option value="Bhupi, Dhobi" <?=($sub_caste === "Bhupi, Dhobi")?'selected':''?>>Bhupi, Dhobi</option>
                                </select>
                                <?= form_error("sub_caste") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row mt-3" id="applicant_photo_row">
                            <div class="col-12">
                                <label for="">Upload Applicant Photo ( Size range 20KB-1024KB) / আবেদনকাৰীৰ ফটো/ছবি আপলোড কৰক <span class="text-danger">*</span></label>
                                <div class="file-loading">
                                    <input id="applicant_photo" name="applicant_photo" type="file" data-parsley-errors-container="#applicant_photoErrorBox" />
                                </div>
                                <span class="text-info small">Maximum file upload size 2 MB</span>
                                <span id="applicant_photoErrorBox"></span>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Father or Ancestral Address / পূৰ্ব পুৰুষৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name (Father or Ancestor) / পিতৃ অথবা পূৰ্ব পুৰুষৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_name" value="<?=$ancestor_name?>" maxlength="255" />
                                <?= form_error("ancestor_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 1 (Father or Ancestor) / ঠিকনা- ১: পিতৃ অথবা পূৰ্ব পুৰুষৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_address1" value="<?=$ancestor_address1?>" maxlength="255" />
                                <?= form_error("ancestor_address1") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 2 (Father or Ancestor) / ঠিকনা- ২: </label>
                                <input type="text" class="form-control" name="ancestor_address2" value="<?=$ancestor_address2?>" maxlength="255" />
                                <?= form_error("ancestor_address2") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State (Father or Ancestor) / ৰাজ্য- পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <select name="ancestor_state" class="form-control">
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("ancestor_state") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District (Father or Ancestor) / জিলাঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <select name="ancestor_district" id="ancestor_district" class="form-control dists">
                                    <option value="<?=$ancestor_district?>"><?=strlen($ancestor_district)?$ancestor_district:'Select'?></option>
                                </select>
                                <?= form_error("ancestor_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Subdivision (Father or Ancestor) / মহকুমাঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <select name="ancestor_subdivision" id="ancestor_subdivision" class="form-control">
                                    <option value="<?=$ancestor_subdivision?>"><?=strlen($ancestor_subdivision)?$ancestor_subdivision:'Select'?></option>
                                </select>
                                <?= form_error("ancestor_subdivision") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Circle Office (Father or Ancestor) / চক্ৰঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <select name="ancestor_circleoffice" id="ancestor_circleoffice" class="form-control">
                                    <option value="<?=$ancestor_circleoffice?>"><?=strlen($ancestor_circleoffice)?$ancestor_circleoffice:'Select'?></option>
                                </select>
                                <?= form_error("ancestor_circleoffice") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mouza (Father or Ancestor) / মৌজাঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_mouza" value="<?=$ancestor_mouza?>" maxlength="255" />
                                <?= form_error("ancestor_mouza") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village (Father or Ancestor) / গাওঁ: পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_village" value="<?=$ancestor_village?>" maxlength="255" />
                                <?= form_error("ancestor_village") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station (Father or Ancestor) / থানাঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_ps" value="<?=$ancestor_ps?>" maxlength="255" />
                                <?= form_error("ancestor_ps") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Post Office (Father or Ancestor) / ডাকঘৰঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_po" value="<?=$ancestor_po?>" maxlength="255" />
                                <?= form_error("ancestor_po") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pincode (Father or Ancestor) / পিনকোডঃ পিতৃ বা পূৰ্ব পুৰুষৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_pin" value="<?=$ancestor_pin?>" maxlength="6" />
                                <?= form_error("ancestor_pin") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relation / সম্পৰ্ক<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_relation" value="<?=$ancestor_relation?>" maxlength="255" />
                                <?= form_error("ancestor_relation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub Caste of ancestors as per NRC 1951 or Caste census of /NRC 1951 মতে পূৰ্ব পুৰুষৰ উপজাতি 1961 & 1971<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ancestor_subcaste" value="<?=$ancestor_subcaste?>" maxlength="255" />
                                <?= form_error("ancestor_subcaste") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No/Bylane No/Street Name / ঘৰ নং/ৰাস্তা নং/ৰাস্তাৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_houseno" name="pa_houseno" value="<?=$pa_houseno?>" maxlength="255" />
                                <?= form_error("pa_houseno") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_landmark" name="pa_landmark" value="<?=$pa_landmark?>" maxlength="255" />
                                <?= form_error("pa_landmark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State / ৰাজ্য<span class="text-danger">*</span> </label>
                                <select name="pa_state" id="pa_state" class="form-control">
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
                                <input type="text" class="form-control" id="pa_mouza" name="pa_mouza" value="<?=$pa_mouza?>" maxlength="255" />
                                <?= form_error("pa_mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Village/Town (p)/ গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_village" name="pa_village" value="<?=$pa_village?>" maxlength="255" />
                                <?= form_error("pa_village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station / থানা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_ps" name="pa_ps" value="<?=$pa_ps?>" maxlength="255" />
                                <?= form_error("pa_ps") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code / পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_pincode" name="pa_pincode" value="<?=$pa_pincode?>" maxlength="6" />
                                <?= form_error("pa_pincode") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Post Office / ডাকঘৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="pa_po" name="pa_po" value="<?=$pa_po?>" maxlength="255" />
                                <?= form_error("pa_po") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Residential Address / বৰ্তমান বসতি ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="address_same">Same as Permanent Address / স্হায়ী ঠিকনাৰ সৈতে একেনে ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsYes" value="YES" <?=($address_same === 'YES')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsNo" value="NO" <?=($address_same === 'NO')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?=form_error("address_same")?>
                            </div>
                        </div>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No/Bylane No/Street Name / ঘৰ নং/ৰাস্তা নং/ৰাস্তাৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_houseno" name="ra_houseno" value="<?=$ra_houseno?>" maxlength="255" />
                                <?= form_error("ra_houseno") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_landmark" name="ra_landmark" value="<?=$ra_landmark?>" maxlength="255" />
                                <?= form_error("ra_landmark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State / ৰাজ্য<span class="text-danger">*</span> </label>
                                <select id="ra_state" name="ra_state" class="form-control">
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("ra_state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District (P)/ জিলা<span class="text-danger">*</span> </label>
                                <select id="ra_district" name="ra_district" class="form-control dists">
                                    <option value="<?=$ra_district?>"><?=strlen($ra_district)?$ra_district:'Select'?></option>
                                </select>
                                <?= form_error("ra_district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division (p)/ মহকুমা<span class="text-danger">*</span> </label>
                                <select id="ra_subdivision" name="ra_subdivision" class="form-control">
                                    <option value="<?=$ra_subdivision?>"><?=strlen($ra_subdivision)?$ra_subdivision:'Select'?></option>
                                </select>
                                <?= form_error("ra_subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle(p) / ৰাজহ চক্ৰ<span class="text-danger">*</span> </label>
                                <select id="ra_revenuecircle" name="ra_revenuecircle" class="form-control">
                                    <option value="<?=$ra_revenuecircle?>"><?=strlen($ra_revenuecircle)?$ra_revenuecircle:'Select'?></option>
                                </select>
                                <?= form_error("ra_revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza / মৌজা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_mouza" name="ra_mouza" value="<?=$ra_mouza?>" maxlength="255" />
                                <?= form_error("ra_mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Village/Town (p)/ গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_village" name="ra_village" value="<?=$ra_village?>" maxlength="255" />
                                <?= form_error("ra_village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station / থানা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_ps" name="ra_ps" value="<?=$ra_ps?>" maxlength="255" />
                                <?= form_error("ra_ps") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code / পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_pincode" name="ra_pincode" value="<?=$ra_pincode?>" maxlength="6" />
                                <?= form_error("ra_pincode") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Post Office / ডাকঘৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="ra_po" name="ra_po" value="<?=$ra_po?>" maxlength="255" />
                                <?= form_error("ra_po") ?>
                            </div>
                        </div>
                    </fieldset>
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other Details / অন্যান্য তথ্য  </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Occupation of Forefather / পূৰ্ব পুৰুষৰ বৃত্তি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="forefather_occupation" value="<?=$forefather_occupation?>" maxlength="255" />
                                <?= form_error("forefather_occupation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Purpose of obtaining certificate / প্ৰমান পত্ৰ লোৱাৰ উদ্দেশ্য<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="certificate_purpose" value="<?=$certificate_purpose?>" maxlength="255" />
                                <?= form_error("certificate_purpose") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Is Father's/Mother's name present in the voter list? / মাক দেউতাকৰ নাম NRC ত আছেনে ? </label>
                                <select name="parent_voterlist" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="YES" <?=($parent_voterlist === "YES")?'selected':''?>>YES</option>
                                    <option value="NO" <?=($parent_voterlist === "NO")?'selected':''?>>NO</option>
                                </select>
                                <?= form_error("parent_voterlist") ?>
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