<?php
 $reg_no = "";
    $reg_date ="";
    $mobile_verify_status = "";

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
        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }

         $(".dp").datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/renewal/preview') ?>" enctype="multipart/form-data">
            <div class="card shadow-sm mb-5">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Renewal of Registration Card of employment seeker in Employment Exchange
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
                    <h5 class="text-center mt-3 text-success"><u><strong>REGISTRATION DETAILS</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Registration Number<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="reg_no" id="reg_no" value="<?= $reg_no ?>" maxlength="255" />
                                <?= form_error("reg_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Registration Date<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="reg_date" id="reg_date" value="<?= $reg_date ?>" maxlength="255" />
                                <?= form_error("reg_date") ?>
                            </div>
                        </div>
                    
                    </fieldset>
                     <div class="card-footer text-center">
                    <!-- <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-registration-aadhaar-based') ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->

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