<?php

$startYear = date('Y') - 10;
$endYear =  date('Y');

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;    

    $candidate_sign = $dbrow->form_data->candidate_sign ?? '';
    $photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate ?? '';
    $hs_marksheet = $dbrow->form_data->hs_marksheet ?? '';
    $hs_reg_card = $dbrow->form_data->hs_reg_card ?? '';
    
    $candidate_sign_type = $dbrow->form_data->candidate_sign_type ?? '';
    $photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type ?? '';
    // $hs_marksheet_type = $dbrow->form_data->hs_marksheet_type ?? '';
    $hs_reg_card_type = $dbrow->form_data->hs_reg_card_type ?? '';   

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
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>

<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    
    $(document).ready(function () {     
   
        var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
        $("#photo_of_the_candidate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            required: photoOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

        var candidateSign = parseInt(<?=strlen($candidate_sign)?1:0?>);
        $("#candidate_sign").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            required: candidateSign?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
       
   
        // var hs_marksheet = parseInt(<?=strlen($hs_marksheet)?1:0?>);
        // $("#hs_marksheet").fileinput({
        //     dropZoneEnabled: false,
        //     showUpload: false,
        //     showRemove: false,
        //     required: false,
        //     maxFileSize: 1024,
        //     allowedFileExtensions: ["jpg", "jpeg", "png","pdf"]
        // }); 
        var hs_reg_card = parseInt(<?=strlen($hs_reg_card)?1:0?>);
        $("#hs_reg_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png","pdf"]
        });
        

    });
</script>
        
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/gap-permission-certificate-ahsec/registration/submitfiles') ?>"
            enctype="multipart/form-data">
             <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?><br>
                    ( <?php echo $PageTiteAssamese ?> )
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
                        <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                            <td>Photo of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="Photo of the Candidate" <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($photo_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($photo_of_the_candidate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>

                                               
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Signature of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="candidate_sign_type" class="form-control">
                                                    <option value="Signature of the Candidate" <?=($candidate_sign_type === 'Signature of the Candidate')?'selected':''?>>Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("candidate_sign_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="candidate_sign" name="candidate_sign" type="file" />
                                                </div>
                                                <?php if(strlen($candidate_sign)){ ?>
                                                    <a href="<?=base_url($candidate_sign)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>H.S. Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_reg_card_type" class="form-control">
                                                    
                                                <option value="H.S. Registration Card" >H.S. Registration Card</option>
                                                </select>
                                                <?= form_error("hs_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_reg_card" name="hs_reg_card" type="file" />
                                                </div>
                                                 <?php if(strlen($hs_reg_card)){ ?>
                                                    <a href="<?=base_url($hs_reg_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?> 
                                                <?= form_error("hs_reg_card") ?>
                                                
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td>H.S. 2nd Year Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_marksheet_type" class="form-control">
                                                    <option value="H.S. 2nd Year Marksheet" >H.S. 2nd Year Marksheet</option>
                                                </select>
                                                <?= form_error("hs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_marksheet" name="hs_marksheet" type="file" />
                                                </div>
                                                 <?php if(strlen($hs_marksheet)){ ?>
                                                    <a href="<?=base_url($hs_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php } ?> 
                                                <?= form_error("hs_marksheet") ?>

                                            </td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                    

                   

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <a href="<?=site_url('spservices/migrationcertificateahsec/registration/sectionTwo/'.$obj_id)?>" class="btn btn-primary">
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