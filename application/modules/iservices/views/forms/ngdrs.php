<?php

if(isset($dbrow)) {
    $title = "Edit Existing Information";
    
} else {
    $title = "New Applicant Registration";
    
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
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
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
        <form id="myfrm" method="POST" action="<?= base_url('iservices/application/procced') ?>" enctype="multipart/form-data">
            <input id="portal_no" name="portal_no" value="<?=$portal_no?>" type="hidden" />
            <input name="service_id" value="<?=$service_id?>" type="hidden" />
            <input name="url" value="<?=$url?>" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                     <?=$service_name?><br>
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
                        <legend class="h5">Applicant&apos;s Details </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>First Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="contact_fname" id="contact_fname" maxlength="255" />
                                <?= form_error("contact_fname") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Middle Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="contact_mname" id="contact_mname" maxlength="255" />
                                <?= form_error("contact_mname") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Last Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="contact_lname" id="contact_lname" maxlength="255" />
                                <?= form_error("contact_lname") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Building<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="building" id="building" maxlength="255" />
                                <?= form_error("building") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Street<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="street" id="street" />
                                <?= form_error("street") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Street<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="city" id="city" />
                                <?= form_error("city") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pincode<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pincode" id="pincode" maxlength="6"/>
                                <?= form_error("pincode") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State id<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="state_id" id="state_id" />
                                <?= form_error("state_id") ?>
                            </div>

                            <div class="col-md-6">
                                <label>District id<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="district_id" id="district_id" />
                                <?= form_error("district_id") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Taluka id<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="taluka_id" id="taluka_id" />
                                <?= form_error("taluka_id") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Email id<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email_id" id="email_id" />
                                <?= form_error("email_id") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?=$mobile?>" />
                                <?= form_error("mobile_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pan No<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" />
                                <?= form_error("pan_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>UID<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="uid" id="uid" />
                                <?= form_error("uid") ?>
                            </div>
                        </div>
                    
                        <!-- <div class="row form-group">
                           
                        </div>
                    
                    -->
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                   <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Proceed
                    </button>
                  
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>