<?php
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;

    $board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
    $course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
    $state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
    $reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
    
    $condi_of_doc = $dbrow->form_data->condi_of_doc ?? '';  

    $postal = $dbrow->form_data->postal ?? '';  
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");
    $pageTitle = "";
    $pageTitleId = "";
   
    $board_seaking_adm = set_value("board_seaking_adm");
    $course_seaking_adm = set_value("course_seaking_adm");
    $state_seaking_adm = set_value("state_seaking_adm");
    $reason_seaking_adm = set_value("reason_seaking_adm");

    $condi_of_doc = set_value("condi_of_doc");

    $postal = set_value("postal");
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<!-- <script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script> -->

<script type="text/javascript">
$(document).ready(function() {
    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
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


    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });

});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/duplicate-certificate-ahsec') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input id="step" name="step" value="2" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?><br>
                    <?php 
                    switch ($pageTitleId) {
                        case "AHSECDRC": 
                            echo '( ডুপ্লিকেট পঞ্জীয়ন কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDADM":
                            echo '( ডুপ্লিকেট এডমিট কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDMRK":
                            echo '( ডুপ্লিকেট মাৰ্কশ্বীটত প্ৰদানৰ বাবে আবেদন )';
                                break;
                        case "AHSECDPC":
                            echo '( ডুপ্লিকেট উত্তীৰ্ণ প্ৰমাণপত্ৰ প্ৰদানৰ বাবে আবেদন )';
                                break;
                    }
                    ?>
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
                        <legend class="h5">Condition of the document's / নথিপত্ৰৰ অৱস্থা </legend>
                        <div class="row ">
                            <div class="form-group  col-md-12">
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='PARTIALLY DAMAGED') echo "checked='checked'"; ?>
                                    value="PARTIALLY DAMAGED" /> PARTIALLY DAMAGED
                                <br/>
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='FULLY DAMAGED') echo "checked='checked'"; ?>
                                    value="FULLY DAMAGED" /> FULLY DAMAGED
                                    <br/>
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='LOST') echo "checked='checked'"; ?>
                                    value="LOST" /> LOST
                                <?= form_error("condi_of_doc") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Application Details / আবেদনৰ বিৱৰণ </legend>
                        <div class="row ">
                            <div class="form-group  col-md-6">
                                <label>University/Board where seeking admission / বিশ্ববিদ্যালয়/বৰ্ড য'ত নামভৰ্তি
                                    বিচাৰিছে<span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="board_seaking_adm" id="board_seaking_adm"
                                    value="<?=$board_seaking_adm?>" />

                                <?= form_error("board_seaking_adm") ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Course name where seeking admission/পাঠ্যক্ৰমৰ নাম য'ত নামভৰ্তি বিচাৰিছে <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="course_seaking_adm"
                                    id="course_seaking_adm" value="<?=$course_seaking_adm?>" />
                                <?= form_error("course_seaking_adm") ?>
                            </div>


                            <div class="form-group col-md-6">
                                <label>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="state_seaking_adm" id="state_seaking_adm"
                                    value="<?=$state_seaking_adm?>" maxlength="20" />
                                <?= form_error("state_seaking_adm") ?>
                            </div>

                            <div class="form-group col-md-6">

                                <label>Describe Reason for Seeking Migration/ প্ৰব্ৰজন বিচৰাৰ কাৰণ বৰ্ণনা কৰা <span
                                        class="text-danger">*</span></label>

                                <textarea id="reason_seaking_adm" class="form-control" name="reason_seaking_adm"
                                    rows="4" cols="50"><?=$reason_seaking_adm?></textarea>

                                <?= form_error("reason_seaking_adm") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ </legend>
                        <div class="row ">
                            <div class="form-group  col-md-12">
                                <label>How would you want to receive your migration certificate ? / আপুনি আপোনাৰ
                                    প্ৰব্ৰজন প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<span
                                        class="text-danger">*</span></label>
                                <br/>
                                <input id="postal" name="postal" type="radio" class=""
                                    <?php if($postal=='FROM AHSEC COUNTER') echo "checked='checked'"; ?>
                                    value="FROM AHSEC COUNTER" /> FROM AHSEC COUNTER
                                <!-- <label for="postal" class="">FROM AHSEC COUNTER</label> -->
                                <br/>
                                <input id="postal" name="postal" type="radio" class=""
                                    <?php if($postal=='IN MY POSTAL ADDRESS') echo "checked='checked'"; ?>
                                    value="IN MY POSTAL ADDRESS"
                                    <?php echo $this->form_validation->set_radio('postal', 'IN MY POSTAL ADDRESS'); ?> /> IN MY POSTAL ADDRESS
                                <!-- <label for="postal" class="">IN MY POSTAL ADDRESS</label> -->
                                <?= form_error("postal") ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">

                    <a href="<?=site_url('spservices/duplicatecertificateahsec/registration/index/'.$obj_id)?>"
                        class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
                <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>