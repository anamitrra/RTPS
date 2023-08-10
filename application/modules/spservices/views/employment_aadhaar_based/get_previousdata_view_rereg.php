<?php
if ($dbrow) {
    // $aadhaar_number_virtual_id = $dbrow->aadhaar_number___virtual_id;
    $obj_id = $dbrow->_id->{'$id'};

}
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

    #instruction_modal ul li {
        margin-bottom: 10px;
        font-weight: 550;
        text-align: justify
    }

    #loader {
        text-align: center
    }

    .ro {
        pointer-events: none;
    }
    
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var obj_id = '<?= $obj_id ?>';
        if(obj_id !='') {
        getSavedData(obj_id);
        }
        $(document).on("click", "#get_data", function() {
            var registrationNo = $('#registration_no').val();
            var registrationDate = $('#registration_date').val();
            // if (registrationNo.length == 0) {
            //     alertMsg('error', 'Please enter registration number.')
            // } else if (registrationDate.length == 0) {
            //     alertMsg('error', 'Please enter date of registration.')
            // } else {
            //     getPreviousData(registrationNo, registrationDate);
            // }
            getPreviousData(registrationNo, registrationDate);

        });

        function getPreviousData(registrationNo, registrationDate) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= base_url('spservices/employment_aadhaar_based/reregistration/get_reg_data') ?>",
                data: {
                    "reg_no": registrationNo,
                    "reg_date": registrationDate
                },
                beforeSend: function() {
                    $('.application_details_div').removeClass('d-none');
                    $('.no_data').addClass('d-none');
                    $('.has_data').addClass('d-none');
                    $('#loader').removeClass('d-none');
                },
                success: function(res) {
                    // console.log(res.data.initiated_data)
                    // var array = $.map(res.data, function(value, index) {
                    //     return [value];
                    // });
                    // $('#p').text(array);
                    // console.log(array);

                    $('#loader').addClass('d-none')
                    const response = res.data;
                    if (typeof response === "string") {
                        //$('.application_details_div').addClass('d-none');
                        $('.personal_details_div').addClass('d-none');
                        $('.physical_details_div').addClass('d-none');
                        $('.no_data').removeClass('d-none');
                        $('.no_data').text(res.data);
                        
                    } else {
                        $('.personal_details_div').removeClass('d-none');
                        $('.physical_details_div').removeClass('d-none');
                        $('.has_data').removeClass('d-none');
                        var initiatedData = response.initiated_data;
                        $('#applicant_name').val(initiatedData.attribute_details.applicant_name);
                        $('#mobile_number').val(initiatedData.attribute_details.mobile_number);
                        $('#employment_office').val(initiatedData.attribute_details.employment_exchange);
                        //$('#rtps_ref_no').val(initiatedData.appl_ref_no);
                        $('#fathers_name').val(initiatedData.attribute_details.fathers_name);
                        $('#gender').val(initiatedData.attribute_details.gender_jobseeker);
                        $('#e_mail').val(initiatedData.attribute_details.email);
                        $('#fathers_name_guardians_name').val(initiatedData.attribute_details.fathers_name__guardians_name);
                        $('#mothers_name').val(initiatedData.attribute_details.mothers_name);
                        $('#caste').val(initiatedData.attribute_details.caste);
                        if (initiatedData.caste == 'General') {
                            $('.ews_div').fadeIn('slow');
                        } else {
                            $('.ews_div').fadeOut(300);
                        }
                
                        if (initiatedData.attribute_details.economically_weaker_section == 'Yes') {
                            $('#ews_yes').attr('checked', 'checked');
                        } else {
                            $('#ews_no').attr('checked', 'checked');
                        }
                        $('#date_of_birth').val(initiatedData.attribute_details.date_of_birth);
                        $('#husbands_name').val(initiatedData.attribute_details.husbands_name);

                        $('#ex_servicemen_category').val(initiatedData.attribute_details['category_of_ex-servicemen']);
                        if (initiatedData.attribute_details['whether_ex-servicemen'] == 'Yes') {
                            $('.exservice_category').removeClass('d-none');
                            $("#ex_service_yes").attr('checked', 'checked');
                        } else {
                            $('.exservice_category').addClass('d-none');
                            $("#ex_service_no").attr('checked', 'checked');
                        }

                        $('#religion').val(initiatedData.attribute_details.religion);
                        $('#marital_status').val(initiatedData.attribute_details.marital_status);
                        $('#occupation').val(initiatedData.attribute_details.occupation);
                        if (initiatedData.attribute_details.occupation == 'Other') {
                            $('.occupationType').removeClass('d-none');
                            $('#occupation_type').val(initiatedData.attribute_details.occupation_type);
                        } else {
                            $('.occupationType').addClass('d-none');
                        }
                        $('#occupation_type').val(initiatedData.attribute_details.occupation_type);
                        $('#unique_identification_type').val(initiatedData.attribute_details.unique_identification_type);
                        $('#unique_identification_no').val(initiatedData.attribute_details.unique_identification_no);
                        $('#prominent_identification_mark').val(initiatedData.attribute_details.prominent_identification_mark);

                        $('#height_in_cm').val(initiatedData.attribute_details.height__in_cm);
                        $('#weight_kgs').val(initiatedData.attribute_details.weight__kgs);
                        $('#eye_sight').val(initiatedData.attribute_details.eye_sight);
                        $('#chest_inch').val(initiatedData.attribute_details.chest__inch);
                        $('#are_you_differently_abled_pwd').val(initiatedData.attribute_details.are_you_differently_abled__pwd);

                        if (initiatedData.attribute_details.are_you_differently_abled__pwd == 'Yes') {
                            $('.disablity_cat').removeClass('d-none')
                            $('.additional_dis').removeClass('d-none')
                        } else {
                            $('.disablity_cat').addClass('d-none')
                            $('.additional_dis').addClass('d-none')
                        }
                        $('#disability_category').val(initiatedData.attribute_details.disability_category);
                        $('#additional_disability_type').val(initiatedData.attribute_details.additional_disability_type);
                        $('#disbility_percentage').val(initiatedData.attribute_details.disbility_percentage);

                        $("#SAVE").removeAttr('disabled');
                    }
                    // if (response.includes('No record found')) {
                    //     $('.no_data').removeClass('d-none')
                    //     $('.no_data').text(res.data)
                    // } else {
                    //     console.log(res)
                    //     // $('.has_data').removeClass('d-none')
                    // }
                    // console.log("res : " + JSON.stringify(res));
                }
            });
        }; //End of onclick #send_mobile_otp


        function getSavedData(obj_id) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= base_url('spservices/employment_aadhaar_based/reregistration/get_saved_data') ?>",
                data: {
                    "obj_id": obj_id,
                },
                beforeSend: function() {
                    $('.application_details_div').removeClass('d-none');
                    $('.no_data').addClass('d-none');
                    $('.has_data').addClass('d-none');
                    $('#loader').removeClass('d-none');
                },
                success: function(res) {

                    $('#loader').addClass('d-none')
                    const response = res.dbrow;
                    if (typeof response === "string") {
                        //$('.application_details_div').addClass('d-none');
                        $('.personal_details_div').addClass('d-none');
                        $('.physical_details_div').addClass('d-none');
                        $('.no_data').removeClass('d-none');
                        $('.no_data').text(res.data);
                        
                    } else {
                        $('.personal_details_div').removeClass('d-none');
                        $('.physical_details_div').removeClass('d-none');
                        $('.has_data').removeClass('d-none');
                        var initiatedData = response.form_data;
                        $('#applicant_name').val(initiatedData.applicant_name);
                        $('#mobile_number').val(initiatedData.mobile_number);
                        $('#employment_office').val(initiatedData.employment_exchange);
                        //$('#rtps_ref_no').val(initiatedData.appl_ref_no);
                        $('#fathers_name').val(initiatedData.fathers_name);
                        $('#gender').val(initiatedData.applicant_gender);
                        $('#e_mail').val(initiatedData['e-mail']);
                        $('#fathers_name_guardians_name').val(initiatedData.fathers_name__guardians_name);
                        $('#mothers_name').val(initiatedData.mothers_name);
                        $('#caste').val(initiatedData.caste);
                        if (initiatedData.caste == 'General') {
                            $('.ews_div').fadeIn('slow');
                        } else {
                            $('.ews_div').fadeOut(300);
                        }
                
                        if (initiatedData.economically_weaker_section == 'Yes') {
                            $('#ews_yes').attr('checked', 'checked');
                        } else {
                            $('#ews_no').attr('checked', 'checked');
                        }
                        $('#date_of_birth').val(initiatedData.date_of_birth);
                        $('#husbands_name').val(initiatedData.husbands_name);

                        $('#ex_servicemen_category').val(initiatedData['category_of_ex-servicemen']);
                        if (initiatedData['whether_ex-servicemen'] == 'Yes') {
                            $('.exservice_category').removeClass('d-none');
                            $("#ex_service_yes").attr('checked', 'checked');
                        } else {
                            $('.exservice_category').addClass('d-none');
                            $("#ex_service_no").attr('checked', 'checked');
                        }

                        $('#religion').val(initiatedData.religion);
                        $('#marital_status').val(initiatedData.marital_status);
                        $('#occupation').val(initiatedData.occupation);
                        if (initiatedData.occupation == 'Other') {
                            $('.occupationType').removeClass('d-none');
                            $('#occupation_type').val(initiatedData.occupation_type);
                        } else {
                            $('.occupationType').addClass('d-none');
                        }
                        $('#occupation_type').val(initiatedData.occupation_type);
                        $('#unique_identification_type').val(initiatedData.unique_identification_type);
                        $('#unique_identification_no').val(initiatedData.unique_identification_no);
                        $('#prominent_identification_mark').val(initiatedData.prominent_identification_mark);

                        $('#height_in_cm').val(initiatedData.height__in_cm);
                        $('#weight_kgs').val(initiatedData.weight__kgs);
                        $('#eye_sight').val(initiatedData.eye_sight);
                        $('#chest_inch').val(initiatedData.chest__inch);
                        $('#are_you_differently_abled_pwd').val(initiatedData.are_you_differently_abled__pwd);

                        if (initiatedData.are_you_differently_abled__pwd == 'Yes') {
                            $('.disablity_cat').removeClass('d-none')
                            $('.additional_dis').removeClass('d-none')
                        } else {
                            $('.disablity_cat').addClass('d-none')
                            $('.additional_dis').addClass('d-none')
                        }
                        $('#disability_category').val(initiatedData.disability_category);
                        $('#additional_disability_type').val(initiatedData.additional_disability_type);
                        $('#disbility_percentage').val(initiatedData.disbility_percentage);

                        $("#SAVE").removeAttr('disabled');
                    }

                }
            });
        }; //End


        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }

        $("#registration_date").datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true,
            todayHighlight: true,
            
        });
        
        // caste
        $(document).on("change", "#caste", function() {
            let casteCategory = $(this).val();
            if (casteCategory == 'General') {
                $('.ews_div').fadeIn('slow');
            } else {
                $('.ews_div').fadeOut(300);
            }
        });
        $(document).on("change", "#are_you_differently_abled_pwd", function() {
            let pwdCategory = $(this).val();
            if (pwdCategory == 'Yes') {
                $("#disability_category").val("");
                $("#additional_disability_type").val("");
                $("#disbility_percentage").val("");
                $('.disablity_cat').removeClass('d-none')
                $('.additional_dis').removeClass('d-none')
            } else {
                $('.disablity_cat').addClass('d-none')
                $('.additional_dis').addClass('d-none')
            }
        });
        $(document).on("change", ".ex_serviceman", function() {
            let exService = $(this).val();
            if (exService == 'Yes') {
                $("#ex_servicemen_category").val("");
                $('.exservice_category').removeClass('d-none')
            } else {
                $('.exservice_category').addClass('d-none')
            }
        });
        $(document).on("change", "#occupation", function() {
            let occupation = $(this).val();
            if (occupation == 'Other') {
                $('.occupationType').removeClass('d-none')
            } else {
                $('.occupationType').addClass('d-none')
            }
        });
        $(document).on("change", "#types_re_reg", function() {
            let types_re_reg = $(this).val();
            const exc_fields = ["registration_no","registration_date","types_re_reg"];
           

            if(types_re_reg == '3'){

                $('#myfrm input, #myfrm select').each(
                    function(index){
                        if(!exc_fields.includes($(this).attr('name'))) {  
                        var input = $(this);
                        input.removeClass('ro');
                        input.removeAttr('readonly');
                        }
                    }
                );
            }
            else {

                $('#myfrm input, #myfrm select').each(
                    function(index){
                        if(!exc_fields.includes($(this).attr('name'))) {  
                        var input = $(this);
                        input.addClass('ro');
                        input.attr('readonly', true);
                        }
                    }
                );
            }
            if(types_re_reg == '3'){

                $(".dp").datepicker({
                format: 'dd-mm-yyyy',
                endDate: '+0d',
                autoclose: true,
                //endDate: '-18y',
                });
            }
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm mb-5">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for Re-registration of employment seeker in Employment Exchange
            </div>
            <div class="card-body" style="padding:5px;">
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
                <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/reregistration/save_previous_data') ?>" enctype="multipart/form-data">
                <input type="hidden" name="obj_id" value="<?= $obj_id ?>"/>
                <?php if(!isset($obj_id)) {?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Search Registration Number</legend>
                        <h6 class="text-center"><b>Please enter Registration number and Registration Date correctly. </b></h6><br>
                        <div class="row form-group aadhaar_input">
                            <div class="col-md-6">
                                <label>Registration No. <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" name="registration_no" type="text" id="registration_no" value=""/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Registration <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" name="registration_date" id="registration_date" value="" type="text" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-md btn-info" id="get_data"><i class="fa fa-search"></i> Get Data</button>
                        </div>
                    </fieldset>
                    <?php } ?>
                    <fieldset class="border border-success application_details_div d-none" style="margin-top:40px">
                        <legend class="h5">Applicant Details</legend>
                        <div id="loader" class="d-none">
                            <img src="https://www.w3schools.com/jquery/demo_wait.gif" width="64" height="64">
                        </div>
                        <div id="p"></div>
                        <div class="no_data d-none text-danger py-3"></div>
                        <div class="has_data">
                        <div class="row form-group">
                                <div class="col-md-6">
                                <label>Types of Re-registration<span class="text-danger">*</span> </label>
                                <select name="types_re_reg" id="types_re_reg" class="form-control">
                                <option value="" >Please Select</option>
                                <option value="2" >Inter District Transfer (Permanent Address Change Documents Required)</option>
                                <option value="3" >Application details Update/Correction</option>
                                <option value="4" >Qualification Upgrade</option>
                                </select>
                             </div>
                             <div class="col-md-6">
                                    <label>Employment Office<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input class="form-control" name="employment_office" id="employment_office" value="" type="text" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Applicant Name<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input class="form-control" name="applicant_name" type="text" id="applicant_name" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label>Gender <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" id="gender" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                             </div>
                            </div>
                            <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Mobile Number<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input class="form-control" name="mobile_number" id="mobile_number" value="" type="text" readonly />
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail</label>
                                <input type="email" class="form-control" name="e_mail" id="e_mail" value="" readonly>
                            </div>
                            </div>
                            <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="" readonly>
                             </div>
                             
                            </div>

                        </div>
                    </fieldset>
                    <fieldset class="border border-success personal_details_div d-none" style="margin-top:40px">
                        <legend class="h5">Personal Information of Applicant</legend>
                        <div class="has_data">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ Guardian's Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name_guardians_name" id="fathers_name_guardians_name" value="" maxlength="255" readonly/>
                                
                            </div>
                            <div class="col-md-6">
                                <label>Mother Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mothers_name" id="mothers_name" value="" maxlength="255" readonly/>
                                
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Birth<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="date_of_birth" id="date_of_birth" value="" maxlength="255" readonly/>
                                
                            </div>
                            <div class="col-md-6">
                                <label> Caste <span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control ro" id="caste" readonly>
                                    <option value="">Select Caste</option>
                                    <option value="General" >General</option>
                                    <option value="OBC/MOBC" >OBC/MOBC</option>
                                    <option value="ST" >ST</option>
                                    <option value="SC" >SC</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="row form-group ews_div">
                            <div class="col-md-6">
                                <label>Economically Weaker Section <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ro" type="radio" name="economically_weaker_section" id="ews_yes" value="Yes" >
                                    <label class="form-check-label" for="ews_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ro" type="radio" name="economically_weaker_section" id="ews_no" value="No" >
                                    <label class="form-check-label" for="ews_no">No</label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Husband Name/ স্বামীৰ নাম </label>
                                <input type="text" class="form-control" name="husbands_name" id="husbands_name" value="" maxlength="255" readonly/>
                                
                            </div>
                            <div class="col-md-6">
                                <label> Whether Ex-Servicemen <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ex_serviceman ro" type="radio" name="whether_ex_servicemen" id="ex_service_yes" value="Yes">
                                    <label class="form-check-label" for="ex_service_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ex_serviceman ro" type="radio" name="whether_ex_servicemen" id="ex_service_no" value="No">
                                    <label class="form-check-label" for="ex_service_no">No</label>
                                </div>
                                
                            </div>
                            <div class="col-md-6 mt-3 exservice_category d-none">
                                <label> Category of ex-servicemen </label>
                                <select name="ex_servicemen_category" id="ex_servicemen_category" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Airforce" >Airforce</option>
                                    <option value="Army" >Army</option>
                                    <option value="Navy" >Navy</option>
                                </select>
                                
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Religion/ ধৰ্ম<span class="text-danger">*</span> </label>
                                <select name="religion" id="religion" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Buddhism" >Buddhism</option>
                                    <option value="Christianity" >Christianity</option>
                                    <option value="Hinduism" >Hinduism</option>
                                    <option value="Islam" >Islam</option>
                                    <option value="Jainism" >Jainism</option>
                                    <option value="Sikhism" >Sikhism</option>
                                    <option value="Others/Not Specified" >Others/ Not Specified</option>
                                </select>
                                
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Marital Status <span class="text-danger">*</span> </label>
                                <select name="marital_status" id="marital_status" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Divorcee" >Divorcee</option>
                                    <option value="Married" >Married</option>
                                    <option value="Single" >Single</option>
                                    <option value="Widow" >Widow</option>
                                </select>
                                
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Occupation <span class="text-danger">*</span> </label>
                                <select name="occupation" id="occupation" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Business" >Business</option>
                                    <option value="Clerks" >Clerks</option>
                                    <option value="Consultant" >Consultant</option>
                                    <option value="Student" >Student</option>
                                    <option value="Other" >Other</option>
                                </select>
                                
                            </div>
                            <div class="col-md-6 mt-3 occupationType d-none">
                                <label>Occupation Type <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation_type" id="occupation_type" value="" maxlength="255" readonly/>
                                
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification Type</label>
                                <select name="unique_identification_type" id="unique_identification_type" class="form-control unique_identification_type ro" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Driving Licence" >Driving Licence</option>
                                    <option value="Passport" >Passport</option>
                                    <option value="Voter's Identity Card" >Voter's Identity Card</option>
                                </select>
                                
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification No </label>
                                <input type="text" class="form-control" name="unique_identification_no" id="unique_identification_no" value="" readonly/>
                                
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Prominent Identification Mark <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="prominent_identification_mark" id="prominent_identification_mark" value="" readonly/>
                                
                            </div>
                        </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success physical_details_div d-none" style="margin-top:40px">
                        <legend class="h5">Physical Attributes </legend>
                        <div class="has_data">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Height (in cm) </label>
                                <input type="text" class="form-control" name="height_in_cm" id="height_in_cm" value="" readonly/>

                            </div>
                            <div class="col-md-6">
                                <label>Weight (Kgs) </label>
                                <input type="text" class="form-control" name="weight_kgs" id="weight_kgs" value="" readonly/>

                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Eye Sight </label>
                                <input type="text" class="form-control" name="eye_sight" id="eye_sight" value="" readonly/>

                            </div>
                            <div class="col-md-6">
                                <label>Chest (Inch) </label>
                                <input type="text" class="form-control" name="chest_inch" id="chest_inch" value="" readonly/>

                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Are you Differently abled (PwD)? <span class="text-danger">*</span> </label>
                                <select name="are_you_differently_abled_pwd" class="form-control ro" id="are_you_differently_abled_pwd" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>

                            </div>

                            <div class="col-md-6 disablity_cat d-none">
                                <label>Disability Category</label>
                                <select name="disability_category" id="disability_category" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_categories as $dc) {
                                            echo '<option value="' . $dc->disability_category . '">' . $dc->disability_category . '</option>';
                                    } ?>
                                </select>

                            </div>
                            <div class="col-md-6 mt-3 disablity_cat d-none">
                                <label>Additional Disability Type </label>
                                <select name="additional_disability_type" id="additional_disability_type" class="form-control ro" readonly>
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_types as $disability_type) {
                                            echo '<option value="' . $disability_type->additional_disability_type . '">' . $disability_type->additional_disability_type . '</option>';
                                        }
                                    ?>
                                </select>

                            </div>
                            <div class="col-md-6 mt-3 disablity_cat d-none">
                                <label>Disbility Percentage </label>
                                <select name="disbility_percentage" id="disbility_percentage" class="form-control ro" readonly>
                                    <option value="" autocomplete="off">Please Select</option>
                                    <option value="1" >40%-60%</option>
                                    <option value="2" >61% &amp; above</option>
                                </select>
                            </div>

                        </div>
                        </div>
                    </fieldset>
                    <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit" disabled>
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
                </form>

                <!-- Modal -->
                <div class="modal fade" id="instruction_modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header text-center bg-info text-white">
                                <h5 class="modal-title" id="staticBackdropLabel">Guidelines for Registration in Employment Exchanges of Assam </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li>1. All citizens of India above the age of 14 years who are permanent residents of Assam are eligible to register their names in the Employment Exchange of the State of Assam.</li>
                                    <li>2. The candidates are eligible for Registration in one Employment Exchange only in the state, where they are permanent residents within the jurisdiction of the District.</li>
                                    <li>3. Applicants who are already employed and seeking better employment have to be registered only after production of a “No Objection Certificate” issued by the employer.</li>
                                    <li>4. The following documents are to be submitted:- <br>
                                        - Age Proof :- Birth Certificate/ HSLC Admit card/ School Certificate ( Any one of these three documents) <br>
                                        - Proof of Residency:- A) Applicants having AADHAAR Card with permanent address within the state of Assam will be allowed to Register Online without visiting the Employment Exchange as per Office Memorandum issued vide no. SKE. 42/2021/32 Dated Dispur the 19th July 2021. (Only for verification of the state of ASSAM) B) Driving License (either self or parents),Copy of Chitha/Jamabandi (either self or parents),Copy of Passport (either self or parents),Certified Copy of Electoral Roll/EPIC (either self or parents) ( Any one of these documents) <br>
                                        - Educational Qualification Certificate :- Pass Certificate (S) and Mark Sheet(S) <br>
                                        - Caste Certificate in cases of SC/ST/OBC/MOBC/EWS applicants <br>
                                        - In case of P.W.D ( Persons With Disability) candidate –Disability certificate issued by competent authority. <br>
                                        - Additional Qualification Certificate ( if any) <br>
                                        - Experience Certificate ( If any) <br>
                                        - Non- Creamy Layer Certificate. <br>
                                        - AADHAAR Card (non mandatory)</li>
                                    <li>5. All text box with asterisk(*) symbol is mandatory to filled up.</li>
                                    <li>6. Please scan all Educational Qualification (Pass Certificate & Mark sheets in chronological order from lowest level to highest level) into a single PDF and upload as one single PDF.</li>
                                    <li>7. After successfully submission of application Employment Exchange Card (X 10) will be issued to his/her registered Email.</li>
                                    <li>8. Every registrant shall renew his /her registration once in three (3) years in the due month as indicated on his/her Registration card .</li>
                                    <li>9. Failure to renew the registration even after lapse of grace period, will lead to cancellation of registration and subsequent removal from Live Register maintained in the Employment Exchange.</li>
                                    <li>10. No request for renewal of registration after the expiry of the due month and grace period shall be entertained under any circumstances.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--End of .card-body -->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>