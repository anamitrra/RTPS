<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
$title = "New Applicant Registration";

$ahsec_reg_session = set_value("ahsec_reg_session");
$ahsec_reg_no = set_value("ahsec_reg_no");
$ahsec_admit_roll = set_value("ahsec_admit_roll");
$ahsec_admit_no = set_value("ahsec_admit_no");
$ahsec_yearofappearing = set_value("ahsec_yearofappearing");
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

    .blink {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .message {
        color: red;
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
    $(document).ready(function() {

        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg =
                    "You want to save in Draft mode that will allows you to edit and can submit later";
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

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });


        $('.number_input').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

        let delivery_mode = 'SHOW_MESSAGE';
        showMessage(delivery_mode);

    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/ahsec-correction-verifydata') ?>" enctype="multipart/form-data">
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <?php switch ($pageTitleId) {
                                case "AHSECCRC":
                                    echo '( পঞ্জীয়ন কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCADM":
                                    echo '( এডমিট কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCMRK":
                                    echo '( মাৰ্কশ্বীটত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCPC":
                                    echo '( উত্তীৰ্ণ প্ৰমাণপত্ৰত সংশোধনৰ বাবে আবেদন )';
                                    break;
                            }
                            ?><b></h4>
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

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">PLEASE ENTER BELOW ACADEMIC DETAILS</legend>
                        <p id="message" class="message"></p>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>AHSEC Registration Session/ AHSEC পঞ্জীয়ন বৰ্ষ <span class="text-danger">*</span> </label>
                                <select name="ahsec_reg_session" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach ($sessions as $session) { ?>
                                        <option value="<?php echo $session ?>" <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>><?php echo $session ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_reg_session") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid AHSEC Registration Number / AHSEC ৰ বৈধ পঞ্জীয়ন নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_reg_no" id="ahsec_reg_no" value="<?= $ahsec_reg_no ?>" maxlength="255" />
                                <?= form_error("ahsec_reg_no") ?>
                            </div>
                        </div>
                        <!-- <?php //if (//$pageTitleId === "AHSECCRC") { 
                                ?> -->
                        <div class="row form-group">
                            <div class="col-md-6" id="ahsec_admit_roll_container">
                                <label>Valid H.S Final Examination Roll/ বৈধ H.S চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ ৰোল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll" value="<?= $ahsec_admit_roll ?>" maxlength="255" />
                                <?= form_error("ahsec_admit_roll") ?>
                            </div>
                            <div class="col-md-6" id="ahsec_admit_no_container">
                                <label>Valid H.S Final Examination Number/ বৈধ H.S চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no" value="<?= $ahsec_admit_no ?>" maxlength="255" />
                                <?= form_error("ahsec_admit_no") ?>
                            </div>
                        </div>
                        <?php //} 
                        ?>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>
                                    <?php if ($pageTitleId == "AHSECCPC") { ?>Year of Passing in H.S Final Examination/ H.S চূড়ান্ত পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ বছৰ <?php } else { ?>Year of Appearing in HS Final Examination/ H.S চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱাৰ বছৰ <?php } ?>
                                </label>
                                <select name="ahsec_yearofappearing" id="ahsec_yearofappearing" class="form-control" onchange="checkDropdownValue()">
                                    <option value="">Select Year</option>
                                    <?php if ($pageTitleId == "AHSECCRC") { ?>
                                        <option value="<?php echo 'Not yet appeared'; ?>" <?= ($ahsec_yearofappearing === "Not yet appeared") ? 'selected' : '' ?>>Not yet appeared</option>
                                    <?php } ?>
                                    <?php foreach ($years as $year) { echo $ahsec_yearofappearing; ?>
                                        <option value="<?php echo $year ?>" <?= ($ahsec_yearofappearing == $year) ? 'selected' : '' ?>><?php echo $year ?></option>

                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_yearofappearing") ?>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> RESET
                    </button>
                    <button class="btn btn-primary frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> VERIFY & PROCEED
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>
<script>
    function showMessage(value) {
        var messageElement = document.getElementById("message");

        if (value === "SHOW_MESSAGE") {
            messageElement.innerHTML = "<i class='icon fas fa-hand-point-right'></i> PLEASE PROVIDE BELOW ACADEMIC DETAILS CAREFULLY & ACCURATELY, AS BASED ON THESE DETAILS APPLICATIONS WILL BE FURTHER PROCESSED";
            messageElement.classList.add("blink");
        } else {
            messageElement.innerHTML = "";
            messageElement.classList.remove("blink");
        }
    }



    function checkDropdownValue() { 
        <?php if ($pageTitleId === "AHSECCRC"): ?>
        var dropdown = document.getElementById("ahsec_yearofappearing");
        var selectedValue = dropdown.value;
        var ahsecAdmitRollContainer = document.getElementById("ahsec_admit_roll_container");
        var ahsecAdmitNoContainer = document.getElementById("ahsec_admit_no_container");
        var ahsecAdmitRoll = document.getElementById("ahsec_admit_roll");
        var ahsecAdmitNo = document.getElementById("ahsec_admit_no");
        if (selectedValue === "Not yet appeared") {
            ahsecAdmitRollContainer.style.display = "none";
            ahsecAdmitNoContainer.style.display = "none";

            ahsecAdmitRoll.value = "";
            ahsecAdmitNo.value = "";
        } else if (selectedValue === "") {
            ahsecAdmitRollContainer.style.display = "none";
            ahsecAdmitNoContainer.style.display = "none";

            ahsecAdmitRoll.value = "";
            ahsecAdmitNo.value = "";
        } else {
            ahsecAdmitRollContainer.style.display = "block";
            ahsecAdmitNoContainer.style.display = "block";

            ahsecAdmitRoll.value = "";
            ahsecAdmitNo.value = "";
        }
        <?php endif; ?>
    }

    // Initial check when the page loads to handle the initial state of the dropdown
    checkDropdownValue();
</script>