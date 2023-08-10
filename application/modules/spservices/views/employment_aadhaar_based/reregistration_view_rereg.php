<?php
if ($dbrow) {
    // $aadhaar_number_virtual_id = $dbrow->aadhaar_number___virtual_id;
} else {
    $title = "New Applicant Registration";
    $aadhaar_number_virtual_id = set_value("aadhaar_number_virtual_id");
    $full_name = set_value("full_name");
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
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // instruction_modal
        $('#instruction_modal').modal();

        // $(document).on("click", ".verify_otp", function() {
        //     let otp = $('#otp_no').val();
        //     if (otp.length) {
        //         // alert('1');
        //         window.location = '<?= base_url('spservices/employment_aadhaar_based/registration/first_reg/123') ?>'

        //         // window.location = '<?= base_url('spservices/employment-registration/personal-details/123') ?>'
        //     } else {
        //         alert('Please enter otp')
        //     }
        // });
        $('input[type="checkbox"]').click(function() {
            if ($(this).prop("checked") == true) {
                $('.aadhaar_input').fadeIn('slow');
            } else if ($(this).prop("checked") == false) {
                $('.aadhaar_input').fadeOut(300);
            }
        });
        $(document).on("click", "#aadhaar_modal", function() {
            var aadhaarNo = $('#aadhaar_number_virtual_id').val();
            var fullName = $('#full_name').val();
            if (aadhaarNo.length < 2) {
                alertMsg('error', 'Please enter valid Aadhaar Number.')
            } else if (!aadhaarNo.match(/^\d{12}$/)) {
                alertMsg('error', 'Invalid aadhaar number format.')
            } else if (fullName.length == 0) {
                alertMsg('error', 'Please enter Full Name.')
            } else {
                getOtp(aadhaarNo, fullName);
            }
        });

        function getOtp(aadhaar, name) {
            $('#verify_modal').modal("show")
            $("#otp_no").val("");
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= base_url('spservices/employment_aadhaar_based/reregistration/otpsend') ?>",
                data: {
                    "aadhaar_number": aadhaar
                },
                beforeSend: function() {
                    $("#otp_no").attr("placeholder", "Sending OTP... Please wait");
                },
                success: function(res) {
                    if (res.status) {
                        $(".verify_btn").attr("id", "verify_mobile_otp");
                        $("#otp_no").attr("placeholder", "Enter your OTP");
                    } else {
                        alert(res.msg);
                    } //End of if else
                }
            });

        }; //End of onclick #send_mobile_otp
    
        $(document).on("click", ".verify_otp", function() {
            var otpNo = $("#otp_no").val();
            var aadhaarNo = $('#aadhaar_number_virtual_id').val();
            var fullName = $('#full_name').val();
            var state = 'Assam';
            if (/^\d{6}$/.test(otpNo)) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/employment_aadhaar_based/reregistration/otpverify') ?>",
                    data: {
                        "aadhaar_number": aadhaarNo,
                        "name": fullName,
                        "state": state,
                        "otp": otpNo
                    },
                    beforeSend: function() {
                        $("#otp_no").val("");
                        $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
                    },
                    success: function(res) { //alert(JSON.stringify(res));
                        // console.log("res : " + JSON.stringify(res));
                        // console.log(res.obj_id)
                        if (Object.values(res.ret)[0] === 'y') {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Aadhaar verified successfully !!!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#verify_modal').modal("hide")
                            window.location = '<?= base_url('spservices/employment-re-registration/getOldData') ?>'
                            // window.location = '<?= base_url('spservices/employment-registration/personal-details/') ?>'+res.obj_id
                        } else {
                            // alertMsg('error', res.msg)

                            alert(res.msg);
                            $('#verify_modal').modal("show")
                            $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                        } //End of if else
                    }
                });
            } else {
                alertMsg('error', 'OTP is invalid. Please enter a valid 6-digit number')
                // alert("OTP is invalid. Please enter a valid 6-digit number");
                $("#otp_no").val();
                $("#otp_no").focus();
                return false;
            }
        }); //End of onClick #verify_mobile_otp
        

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="#" enctype="multipart/form-data">
            <div class="card shadow-sm mb-5">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Re-registration of employment seeker in Employment Exchange
                </div>
                <div class="card-body" style="padding:5px;">
                <?php
                if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } 
                ?>
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#instruction_modal">
                            Instructions
                        </button>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>

                        <ol style="margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 30 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ ৩০ দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                        </ol>


                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ul>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">AADHAAR Details of the Applicant</legend>
                        <h6 class="text-center"><b><u>AADHAAR HOLDER CONSENT</u></b></h6><br>
                        <p><i>I hereby state that I have no objection in authenticating myself with Aadhaar based authentication system and consent to providing my Aadhaar number. I understand that the Aadhaar number shall be used only for the below mentioned purpose:
                                'I, hereby give my consent to Directorate of Employment and Craftsman Training (DECT),Assam to obtain my Aadhaar No for the purpose of authentication with UIDAI for Online Employment Registration process and DECT,Assam has also informed me that my biometrics will not be stored /shared and will be submitted to CIDR only for the purpose of authentication.'</i></p>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="consent">
                                    <label class="form-check-label" for="consent">
                                        I hereby declare that I have read all the terms and conditions and I have no objection in authenticating myself.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group aadhaar_input" style="display:none">
                            <div class="col-md-6">
                                <label>AADHAAR Number / Virtual Id <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" name="aadhaar_number_virtual_id" value="<?= $aadhaar_number_virtual_id ?>" maxlength="12" type="text" id="aadhaar_number_virtual_id" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>State (Only Domicile of Assam can apply) <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" name="state" value="Assam" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Full Name as in AADHAAR Card <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" name="full_name" value="<?= $full_name ?>" type="text" id="full_name" />
                                </div>
                            </div>
                            <div class="col-auto text-center d-flex">
                                <button type="button" class="btn btn-md btn-info align-self-end" aria-pressed="true" id="aadhaar_modal"><i class="fa fa-check"></i> Verify Aadhaar</button>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="verify_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-center bg-info text-white">
                                        <h5 class="modal-title" id="staticBackdropLabel">Verify Aadhaar</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Please enter OTP received on your aadhaar registered mobile number to verify your details.</p>
                                        <div class="row mt-2">
                                            <div class="col-md-8 mx-auto">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="otp_no" placeholder="Enter OTP">
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="button" class="btn btn-md btn-success verify_otp">Verify</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
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
        </form>
    </div><!--End of .container-->
</main>