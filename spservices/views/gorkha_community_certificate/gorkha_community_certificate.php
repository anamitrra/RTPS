<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');
// if ($dbrow) {
//     $title = "Edit Existing Information";
//     $obj_id = $dbrow->{'_id'}->{'$id'};
//     $appl_ref_no = $dbrow->service_data->appl_ref_no;

//     $state = $dbrow->form_data->state;
//     $district = $dbrow->form_data->district;
//     $sub_division = $dbrow->form_data->sub_division;
//     $circle_office = $dbrow->form_data->circle_office;
//     $applicant_name = $dbrow->form_data->applicant_name;
//     $applicant_gender = $dbrow->form_data->applicant_gender;
//     $mobile = $dbrow->form_data->mobile;
//     $email = $dbrow->form_data->email;
//     $epic = $dbrow->form_data->epic;
//     $pan_no = $dbrow->form_data->pan_no;
//     $adhar_no = $dbrow->form_data->adhar_no;
//     $csc_id = $dbrow->form_data->cscid;
//     $csc_office = $dbrow->form_data->adhar_no;
//     $revert = $dbrow->form_data->revert;
//     $fill_up_language = $dbrow->form_data->fill_up_language;
//     $applicant_caste = $dbrow->form_data->fill_up_language;
//     $fill_up_language = $dbrow->form_data->fill_up_language;
//     $father_name = $dbrow->form_data->father_name;
//     $mother_name = $dbrow->form_data->mother_name;
//     $address_line_1 = $dbrow->form_data->address_line_1;
//     $address_line_2 = $dbrow->form_data->address_line_2;
//     $mouza = $dbrow->form_data->mouza;
//     $village = $dbrow->form_data->village;
//     $police_station = $dbrow->form_data->police_station;
//     $post_office = $dbrow->form_data->post_office;
//     $pin_code = $dbrow->form_data->pin_code;



//     $passport_photo_type = isset($dbrow->form_data->passport_photo_type) ? $dbrow->form_data->passport_photo_type : '';
//     $passport_photo = isset($dbrow->form_data->passport_photo) ? $dbrow->form_data->passport_photo : '';
//     $signature_type = isset($dbrow->form_data->signature_type) ? $dbrow->form_data->signature_type : '';
//     $signature = isset($dbrow->form_data->signature) ? $dbrow->form_data->signature : '';

//     $ug_pg_diploma_type = isset($dbrow->form_data->ug_pg_diploma_type) ? $dbrow->form_data->ug_pg_diploma_type : '';
//     $ug_pg_diploma = isset($dbrow->form_data->ug_pg_diploma) ? $dbrow->form_data->ug_pg_diploma : '';
//     $prc_type = isset($dbrow->form_data->prc_type) ? $dbrow->form_data->prc_type : '';
//     $prc = isset($dbrow->form_data->prc) ? $dbrow->form_data->prc : '';
//     $mbbs_certificate_type = isset($dbrow->form_data->mbbs_certificate_type) ? $dbrow->form_data->mbbs_certificate_type : '';
//     $mbbs_certificate = isset($dbrow->form_data->mbbs_certificate) ? $dbrow->form_data->mbbs_certificate : '';
//     $noc_dme_type = isset($dbrow->form_data->noc_dme_type) ? $dbrow->form_data->noc_dme_type : '';
//     $noc_dme = isset($dbrow->form_data->noc_dme) ? $dbrow->form_data->noc_dme : '';
//     $seat_allt_letter_type = isset($dbrow->form_data->seat_allt_letter_type) ? $dbrow->form_data->seat_allt_letter_type : '';
//     $seat_allt_letter = isset($dbrow->form_data->seat_allt_letter) ? $dbrow->form_data->seat_allt_letter : '';
// } else {
//     $title = "New Applicant Registration";
//     $obj_id = NULL;
//     $appl_ref_no = NULL; //set_value("rtps_trans_id");
    
//     $state = $dbrow->form_data->state;
//     $district = $dbrow->form_data->district;
//     $sub_division = $dbrow->form_data->sub_division;
//     $circle_office = $dbrow->form_data->circle_office;
//     $epic = $dbrow->form_data->epic;
//     $adhar_no = $dbrow->form_data->adhar_no;
//     $csc_id = $dbrow->form_data->cscid;
//     $csc_office = $dbrow->form_data->adhar_no;
//     $revert = $dbrow->form_data->revert;
//     $fill_up_language = $dbrow->form_data->fill_up_language;
//     $applicant_caste = $dbrow->form_data->fill_up_language;
//     $fill_up_language = $dbrow->form_data->fill_up_language;
 
//     $mother_name = $dbrow->form_data->mother_name;
//     $address_line_1 = $dbrow->form_data->address_line_1;
//     $address_line_2 = $dbrow->form_data->address_line_2;
//     $mouza = $dbrow->form_data->mouza;
//     $village = $dbrow->form_data->village;
//     $police_station = $dbrow->form_data->police_station;
//     $post_office = $dbrow->form_data->post_office;
//     $pin_code = $dbrow->form_data->pin_code;


//     $applicant_name = set_value("applicant_name");
//     $applicant_gender = set_value("applicant_gender");
//     $father_name = set_value("father_name");
//     $dob = set_value("dob");
//     $mobile = $this->session->mobile; //set_value("mobile_number");
//     $email = set_value("email");
//     $pan_no = set_value("pan_no");

//     $permanent_address = set_value("permanent_address");
//     $correspondence_address = set_value("correspondence_address");
//     $permanent_reg_no = set_value("permanent_reg_no");
//     $permanent_reg_date = set_value("permanent_reg_date");
//     $additional_degree_reg_no = set_value("additional_degree_reg_no");
//     $additional_degree_reg_date = set_value("additional_degree_reg_date");
//     $registering_smc = set_value("registering_smc");
//     $relocating_reason = set_value("relocating_reason");
//     $working_place_add = set_value("working_place_add");

//     $passport_photo_type = "";
//     $passport_photo = "";
//     $signature_type = "";
//     $signature = "";
//     $ug_pg_diploma_type = "";
//     $ug_pg_diploma = "";
//     $prc_type = "";
//     $prc = "";
//     $mbbs_certificate_type = "";
//     $mbbs_certificate  = "";
//     $noc_dme_type = "";
//     $noc_dme = "";
//     $seat_allt_letter_type = "";
//     $seat_allt_letter = "";
// } //End of if else
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

<link rel="stylesheet" href=" ">
<link href=" " media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href=" ">

<script src=" "></script>
<script src=" "></script>
<script src=" " type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-365d',
            autoclose: true,
        });
        $(".address").on('keyup change', function() {
            var $th = $(this);
            $th.val($th.val().replace(/[^ a-zA-Z0-9]/g, function(str) {
                alert('Input Error - Please use only letters and numbers.');
                return '';
            }));

        });

        $.post(" ", function(res) {
            $("#captchadiv").html(res);
        });

        $(document).on("click", "#reloadcaptcha", function() {
            $.post(" ", function(res) {
                $("#captchadiv").html(res);
            });
        }); //End of onChange #reloadcaptcha

        $.getJSON(" district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);

        });

        $(document).on("change", "#district", function() {
            $('#revenuecircle').empty().append('<option value="">Please select</option>')
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"district_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON(" ?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#subdivision').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.subdiv_name + '/' + value.subdiv_id + '">' + value.subdiv_name + '</option>';
                    });
                    $('.subdiv').append(selectOption);
                });
            }
        });

        $(document).on("change", "#subdivision", function() {
            $('#revenuecircle').empty().append('<option value="">Please select</option>')
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"subdiv_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON(" ?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#revenuecircle').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.circle_name + '/' + value.circle_id + '">' + value.circle_name + '</option>';
                    });
                    $('.circle').append(selectOption);
                });
            }
        });

        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if (clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            } //End of if else            
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
                    if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action=" " enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value=" " type="hidden" />
            <input name="appl_ref_no" value=" " type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="passport_photo_type" value=" " type="hidden" />
            <input name="passport_photo" value=" " type="hidden" />
            <input name="signature_type" value=" " type="hidden" />
            <input name="signature" value=" " type="hidden" />
            <input name="ug_pg_diploma_type" value=" " type="hidden" />
            <input name="ug_pg_diploma" value=" " type="hidden" />
            <input name="prc_type" value=" " type="hidden" />
            <input name="prc" value=" " type="hidden" />
            <?php if (!empty($mbbs_certificate_type)) { ?>
                <input name="mbbs_certificate_type" value=" " type="hidden" />
                <input name="mbbs_certificate" value=" " type="hidden" />
            <?php } ?>
            <?php if (!empty($noc_dme_type)) { ?>
                <input name="noc_dme_type" value=" " type="hidden" />
                <input name="noc_dme" value=" " type="hidden" />
            <?php } ?>
            <?php if (!empty($seat_allt_letter_type)) { ?>
                <input name="seat_allt_letter_type" value=" " type="hidden" />
                <input name="seat_allt_letter" value=" " type="hidden" />
            <?php } ?>
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application Form For Gorkha Commmunity Certificate <b></h4>

                </div>
                <div class="card-body" style="padding:5px">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>  
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>  
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong>  
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 07 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ 07 দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                        </ol>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমূহ বাধ্য়তামুলক আৰু স্থানসমূহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            <li>3. Applicant's photo should be in JPEG format.</li>
                            <li>৩. আবেদনকাৰীৰ ফটো jpeg formatত হ’ব লাগিব।</li>
                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value=" " maxlength="255" />
                                 
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male"  >Male</option>
                                    <option value="Female"  >Female</option>
                                    <option value="Transgender"  >Transgender</option>
                                </select>
                                 
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value=" " maxlength="255" />
                                 
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value=" " maxlength="10" autocomplete="off" type="text" />
                                 
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ("user" === "user") { ?>
                                    <input type="text" class="form-control" name="mobile" value=" " readonly maxlength="10" />
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="mobile" value=" " maxlength="10" />
                                <?php } ?>

                                 
                            </div>
                            <div class="col-md-3">
                                <label>E-Mail/ ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value=" " maxlength="100" />
                                 
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value=" " maxlength="10" />
                                 
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6" id="address_line_1">
                                <label>Address Line 1 <span class="text-danger">*</span></label>
                                <textarea class="form-control address" name="address_line_1" id="address_line_1"> </textarea>
                                 
                            </div>
                            <div class="col-md-6" id="address_line_1">
                                <label>Address Line 2 <span class="text-danger">*</span></label>
                                <textarea class="form-control address" name="address_line_2" id="address_line_2"> </textarea>
                                 
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other details/ অন্যান্য সবিশেষ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State <spastaten class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="state" id="state" value=" " maxlength="255" />
                                 
                            </div>
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="district" id="district" value=" " maxlength="10" />
                                 
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division</label>
                                <input type="text" class="form-control" name="sub_division" id="sub_division" value=" " maxlength="255" />
                                 
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office </label>
                                <input type="text" class="form-control dp" name="circle_office" id="circle_office" value=" " maxlength="10" />
                                 
                            </div>
                        </div>
                        <!-- <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Relocating State Medical Council/ স্থানান্তৰিত ৰাজ্যিক চিকিৎসা পৰিষদৰ নাম <span class="text-danger">*</span></label>
                                <select name="registering_smc" id="registering_smc" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Andhra Pradesh Medical Council"  >Andhra Pradesh Medical Council</option>
                                    <option value="Arunachal Pradesh Medical Council"  >Arunachal Pradesh Medical Council</option>
                                    <option value="Assam Medical Council"  >Assam Medical Council</option>
                                    <option value="Bihar Medical Council"  >Bihar Medical Council</option>
                                    <option value="Chhattisgarh Medical Council"  >Chhattisgarh Medical Council</option>
                                    <option value="Delhi Medical Council"  >Delhi Medical Council</option>
                                    <option value="Goa Medical Council"  >Goa Medical Council</option>
                                    <option value="Gujarat Medical Council"  >Gujarat Medical Council</option>
                                    <option value="Haryana Medical Council"  >Haryana Medical Council</option>
                                    <option value="Himachal Pradesh Medical Council"  >Himachal Pradesh Medical Council</option>
                                    <option value="Jammu & Kashmir Medical Council"  >Jammu & Kashmir Medical Council</option>
                                    <option value="Jharkhand Medical Council"  >Jharkhand Medical Council</option>
                                    <option value="Karnataka Medical Council"  >Karnataka Medical Council</option>
                                    <option value="Madhya Pradesh Medical Council"  >Madhya Pradesh Medical Council</option>
                                    <option value="Maharashtra Medical Council"  >Maharashtra Medical Council</option>
                                    <option value="Manipur Medical Council"  >Manipur Medical Council</option>
                                    <option value="Mizoram Medical Council"  >Mizoram Medical Council</option>
                                    <option value="Nagaland Medical Council"  >Nagaland Medical Council</option>
                                    <option value="Orissa Council of Medical Registration"  >Orissa Council of Medical Registration</option>
                                    <option value="Punjab Medical Council"  >Punjab Medical Council</option>
                                    <option value="Rajasthan Medical Council"  >Rajasthan Medical Council</option>
                                    <option value="Sikkim Medical Council"  >Sikkim Medical Council</option>
                                    <option value="Tamil Nadu Medical Council"  >Tamil Nadu Medical Council</option>
                                    <option value="Telangana State Medical Council"  >Telangana State Medical Council</option>
                                    <option value="Travancore Cochin Medical Council, Trivandrum"  >Travancore Cochin Medical Council, Trivandrum</option>
                                    <option value="Tripura State Medical Council"  >Tripura State Medical Council</option>
                                    <option value="Uttarakhand Medical Council"  >Uttarakhand Medical Council</option>
                                    <option value="Uttar Pradesh Medical Council"  >Uttar Pradesh Medical Council</option>
                                    <option value="West Bengal Medical Council"  >West Bengal Medical Council</option>
                                </select>
                                 
                            </div> -->
                            <!-- <div class="col-md-6">
                                <label>Relocation Reason/ স্থানান্তৰৰ কাৰণ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="relocating_reason" id="relocating_reason" value=" " maxlength="255" />
                                 
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Working Place Address/ কৰ্মস্থলীৰ ঠিকনা </label>
                                <textarea class="form-control address" name="working_place_add" id="working_place_add"> </textarea>
                                 
                            </div>
                        </div> -->
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>
