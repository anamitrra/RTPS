<?php

if ($dbrow) {
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$appl_status = $dbrow->service_data->appl_status;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_title = $dbrow->form_data->applicant_title;
$first_name = $dbrow->form_data->first_name;
$middle_name =$dbrow->form_data->middle_name;
$last_name = $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$caste = $dbrow->form_data->caste;
$father_title = $dbrow->form_data->father_title;
$father_name = $dbrow->form_data->father_name;
$aadhar_no =  $dbrow->form_data->aadhar_no;
$mobile = $this->session->mobile; 
$email = $dbrow->form_data->email;


$district =  $dbrow->form_data->district;
$police_station = $dbrow->form_data->police_station;
$post_office = $dbrow->form_data->post_office;

$bank_account_no=$dbrow->form_data->bank_account_no;
$bank_name= $dbrow->form_data->bank_name;
$bank_branch=$dbrow->form_data->bank_branch;
$ifsc_code= $dbrow->form_data->ifsc_code;


$land_district =  $dbrow->form_data->land_district;
$sub_division =  $dbrow->form_data->sub_division;
$circle_office =  $dbrow->form_data->circle_office;
$mouza_name =  $dbrow->form_data->mouza_name;
$revenue_village = $dbrow->form_data->revenue_village;

$patta_type = $dbrow->form_data->patta_type;
$dag_no =  $dbrow->form_data->dag_no;
$patta_no = $dbrow->form_data->patta_no;
$name_of_pattadar =$dbrow->form_data->name_of_pattadar;
$pattadar_father_name = $dbrow->form_data->pattadar_father_name;
$relationship_with_pattadar = $dbrow->form_data->relationship_with_pattadar;
$land_category = $dbrow->form_data->land_category;
$cultivated_land = $dbrow->form_data->cultivated_land;
$production = $dbrow->form_data->production;
$crop_variety = $dbrow->form_data->crop_variety;
$surplus_production =$dbrow->form_data->surplus_production;
$cultivator_type = $dbrow->form_data->cultivator_type;

$bigha = $dbrow->form_data->bigha;
$kotha = $dbrow->form_data->kotha;
$loosa =  $dbrow->form_data->loosa;
$land_area = $dbrow->form_data->land_area;

$ado_circle_office = $dbrow->form_data->ado_circle_office;



$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$land_owner_signature = $dbrow->form_data->land_owner_signature ?? '';
$land_owner_signature_type = $dbrow->form_data->land_owner_signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';

$bank_passbook = $dbrow->form_data->bank_passbook ?? '';
$bank_passbook_type = $dbrow->form_data->bank_passbook_type ?? '';   

$aadhaar_card = $dbrow->form_data->aadhaar_card ?? '';
$aadhaar_card_type = $dbrow->form_data->aadhaar_card_type ?? '';   


$land_records = $dbrow->form_data->land_records ?? '';
$land_records_type = $dbrow->form_data->land_records_type ?? '';   

$land_document = $dbrow->form_data->land_document ?? '';
$land_document_type = $dbrow->form_data->land_document_type ?? '';   

$agreement_copy = $dbrow->form_data->agreement_copy ?? '';
$agreement_copy_type = $dbrow->form_data->agreement_copy_type ?? '';   

$ncla_certificate = $dbrow->form_data->ncla_certificate ?? '';
$ncla_certificate_type = $dbrow->form_data->ncla_certificate_type ?? '';   

$status = $dbrow->service_data->appl_status ?? '';

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
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {


        
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on("click", "#open_camera", function() {
        $("#live_photo_div").show();
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        $("#open_camera").hide();
    });

    $(document).on("click", "#capture_photo", function() {
        Webcam.snap(function(data_uri) { //alert(data_uri);
            $("#captured_photo").attr("src", data_uri);
            $("#photo_data").val(data_uri);
        });
    });


    $("#photo").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    $("#signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    
    $("#land_owner_signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    
    $("#bank_passbook").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#aadhaar_card").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#ncla_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#land_records").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#land_document").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#agreement_copy").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });




        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id");
            $("#submit_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced?";
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac_farmer/registration/querysubmit') ?>" enctype="multipart/form-data">

            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
           
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <b></h4>
                </div>
                <div class="card-body" style="padding:5px">
                <?php if ($this->session->flashdata('fail') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
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
                    <?php } if($status === 'QS') { ?>
                    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                        <legend class="h5">QUERY DETAILS </legend>
                        <div class="row">
                            <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                <?=(end($dbrow->processing_history)->remarks)??''?>
                            </div>
                        </div>
                        <span style="float:right; font-size: 12px">
                            Query time :
                            <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                        </span>
                    </fieldset> 
                    <?php }//End of if ?>

                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Farmer's Basic Details / কৃষকৰ মৌলিক বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant&apos;s  Name/ আবেদনকাৰীৰ  নাম<br><strong><?= $applicant_name  ?></strong> </td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ  <br><strong><?= $applicant_gender ?></strong> </td>
                                <td>Applicant&apos;s Caste/ আবেদনকাৰীৰ লিংগ <br><strong><?= $caste ?></strong></td>
                            </tr>

                            <tr>
                                <td>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<br><strong><?= $father_title.' '.$father_name ?></strong></td>
                                <td>Aadhar Number / আধাৰ নম্বৰ<br><strong><?= $aadhar_no ?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                            </tr>
                            <tr>
                                 <td>Mobile Number / দুৰভাষ (মবাইল)<br><strong><?= $mobile ?></strong> </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                            <tr>
                                <td>District/জিলা <br><strong><?= $district ?></strong></td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $post_office ?></strong></td>
                                <td>Police Station/ থানা <br><strong><?= $police_station ?></strong></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Farmer's Bank Details/ কৃষক বেংকৰ সবিশেষ </legend>

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bank Account No./ বেংক একাউণ্ট নং<br><strong><?= $bank_account_no ?></strong> </td>
                                <td>Bank Name/বেংকৰ নাম<br><strong><?= $bank_name ?></strong> </td>
                                <td>Bank Branch/ বেংক শাখা <br><strong><?= $bank_branch ?></strong></td>
                                <td>IFSC Code/আই এফ এছ চি ক'ড  <br><strong><?= $ifsc_code ?></strong></td>
                                
                            </tr>        
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Land details/ ভূমিৰ সবিশেষ </legend>

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>District / জিলা <br><strong><?= $land_district ?></strong> </td>
                                <td>Sub-Division / মহকুমা<br><strong><?= $land_district ?></strong> </td>
                                <td>Circle/ চক্ৰ <br><strong><?= $circle_office ?></strong></td>
                                
                            </tr>        
                            <tr>
                                <td>Mouza/ মৌজা<br><strong><?= $mouza_name ?></strong> </td>
                                <td>Revenue Village/ ৰাজহ গাঁও<br><strong><?= $revenue_village ?></strong> </td>                                
                            </tr>    
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Patta Type/ পট্টা টাইপ<br><strong><?= $patta_type ?></strong> </td>
                                <td>Dag No. / ডাগ নং<br><strong><?= $dag_no ?></strong> </td>
                                <td>Patta No. / ফ্লেপ নং<br><strong><?= $patta_no ?></strong></td>
                                
                            </tr>        
                            <tr>
                                <td>Name of Pattadar / নাম পট্টাদৰ<br><strong><?= $name_of_pattadar ?></strong> </td>
                                <td>Pattadar Father Name /পট্টাদাৰ পিতৃৰ নাম<br><strong><?= $pattadar_father_name ?></strong> </td>                                
                                <td>Relationship with pattadar/ পট্টাদাৰৰ সৈতে সম্পৰ্ক<br><strong><?= $relationship_with_pattadar ?></strong> </td>                                
                            </tr>   
                            <tr>
                                <td>Land Category / ভূমিৰ শ্ৰেণী<br><strong><?= $land_category ?></strong> </td>
                                <td>Cultivated Land (In Bigha Only) / খেতি কৰা ভূমি (কেৱল বিঘাত)<br><strong><?= $cultivated_land ?></strong> </td>                                
                                <td>Production (In Quintals Only)/ উৎপাদন (কেৱল কুইণ্টালত)<br><strong><?= $production ?></strong> </td>                                
                            </tr>   
                            <tr>
                                <td>Crop Variety/ শস্যৰ জাত<br><strong><?= $crop_variety ?></strong> </td>
                                <td>Surplus Production / উদ্বৃত্ত উৎপাদন<br><strong><?= $surplus_production ?></strong> </td>                                
                                <td>Cultivator Type/ খেতিয়কৰ প্ৰকাৰ<br><strong><?= $cultivator_type ?></strong> </td>                                
                            </tr>   
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bigha / বিঘা  <br><strong><?= $bigha ?></strong> </td>
                                <td>Kotha / কঠা<br><strong><?= $kotha ?></strong> </td>
                                <td>Loosa / লেচা<br><strong><?= $loosa ?></strong></td>
                                <td>Land Area / ভূমিৰ আয়তন<br><strong><?= $land_area ?></strong></td>                                
                            </tr>                                      
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5">Submission Location/ জমা দিয়াৰ স্থান </legend>
                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>ADO Circle Office/ এ ডি অ’ চাৰ্কল অফিচ - <strong><?= $ado_circle_office ?></strong></td>                                
                            </tr>                                      
                        </tbody>
                    </table>
                </fieldset>

                
                   

                    
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
                                            <td>Upload Farmer's Passport Photo <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_type" class="form-control">
                                                    <option value="Passport size photograph"
                                                        <?=($photo_type === 'Passport size photograph')?'selected':''?>>
                                                        Passport size photograph</option>
                                                </select>
                                                <?= form_error("photo_type") ?>
                                            </td>
                                            <td>
                                                <input name="photo_old" value="<?=$photo?>" type="hidden" />
                                                <div class="file-loading">
                                                    <input id="photo" name="photo" type="file" />
                                                </div>

                                                <div class="row mt-1">

                                                    <div class="col-sm-4">
                                                        <?php if(strlen($photo)){ ?>
                                                        <a href="<?=base_url($photo)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                        <?php }//End of if ?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div id="live_photo_div" class="row text-center mt-"
                                                            style="display:none;">
                                                            <div id="my_camera" class="col-md-4 text-center"></div>
                                                            <div class="col-md-4 text-center">
                                                                <img id="captured_photo" src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>" style="width: 320px; height: 240px;" />
                                                            </div>
                                                            <input id="photo_data" name="photo_data" value=""
                                                                type="hidden" />
                                                            <button id="capture_photo" class="btn btn-warning"
                                                                style="margin:2px auto" type="button">Capture
                                                                Photo</button>
                                                        </div>
                                                        <div style="text-align:right">
                                                            <span id="open_camera"
                                                                class="btn btn-sm btn-success text-white"> Capture <img
                                                                    src="<?=base_url('assets/plugins/webcamjs/camera.png')?>"
                                                                    style="width:25px; height: 25px; cursor: pointer" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload Farmer's Signature/Thumb Impression<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="Signature"
                                                        <?=($signature === 'Signature')?'selected':''?>>
                                                        Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                <a href="<?=base_url($signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Land Owner Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_owner_signature_type" class="form-control">
                                                    <option value="land_owner_signature"
                                                        <?=($land_owner_signature === 'Land Owner Signature')?'selected':''?>>
                                                        Land Owner Signature</option>
                                                </select>
                                                <?= form_error("land_owner_signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_owner_signature" name="land_owner_signature" type="file" />
                                                </div>
                                                <?php if(strlen($land_owner_signature)){ ?>
                                                <a href="<?=base_url($land_owner_signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Photocopy of Bank Passbook of Applicant<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bank_passbook_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of Bank Passbook"
                                                        <?=($bank_passbook_type === 'Copy of Bank Passbook')?'selected':''?>>
                                                        Copy of Bank Passbook</option>
                                                   
                                                </select>
                                                <?= form_error("bank_passbook_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bank_passbook" name="bank_passbook" type="file" />
                                                </div>
                                                <?php if(strlen($bank_passbook)){ ?>
                                                <a href="<?=base_url($bank_passbook)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="bank_passbook_old" value="<?=$bank_passbook?>"
                                                    type="hidden" />
                                                <input class="bank_passbook" type="hidden" name="bank_passbook_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('bank_passbook'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aadhar Card<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="aadhaar_card_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Aadhaar Card"
                                                        <?=($aadhaar_card_type === 'Aadhaar Card')?'selected':''?>>
                                                        Aadhaar Card</option>
                                                    
                                                </select>
                                                <?= form_error("aadhaar_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="aadhaar_card" name="aadhaar_card" type="file" />
                                                </div>
                                                <?php if(strlen($aadhaar_card)){ ?>
                                                <a href="<?=base_url($aadhaar_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="aadhaar_card_old" value="<?=$aadhaar_card?>"
                                                    type="hidden" />
                                                <input class="aadhaar_card" type="hidden" name="aadhaar_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('aadhaar_card'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of Non-Cadastral Land Area<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="ncla_certificate_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Certificate of Non-Cadastral Land Area"
                                                        <?=($ncla_certificate_type === 'Certificate of Non-Cadastral Land Area')?'selected':''?>>
                                                        Certificate of Non-Cadastral Land Area</option>
                                                    
                                                </select>
                                                <?= form_error("ncla_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ncla_certificate" name="ncla_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($ncla_certificate)){ ?>
                                                <a href="<?=base_url($ncla_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="ncla_certificate_old" value="<?=$ncla_certificate?>"
                                                    type="hidden" />
                                                <input class="ncla_certificate" type="hidden" name="ncla_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('ncla_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of Non-digitization/Non-integration of Land Records <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_records_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Certificate of Non-digitization/Non-integration of Land Records"
                                                        <?=($land_records_type === 'Certificate of Non-digitization/Non-integration of Land Records')?'selected':''?>>
                                                        Certificate of Non-digitization/Non-integration of Land Records</option>
                                                </select>
                                                <?= form_error("land_records_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_records" name="land_records"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($land_records)){ ?>
                                                <a href="<?=base_url($land_records)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="land_records_old" value="<?=$land_records?>"
                                                    type="hidden" />
                                                <input class="land_records" type="hidden"
                                                    name="land_records_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('land_records'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Land Document
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="land_document_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of Land Patta (In case of Patta Holder)"
                                                        <?=($land_document_type === 'Copy of Land Patta (In case of Patta Holder)')?'selected':''?>>
                                                        Copy of Land Patta (In case of Patta Holder)</option>
                                                </select>
                                                <?= form_error("land_document_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_document" name="land_document"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($land_document)){ ?>
                                                <a href="<?=base_url($land_document)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="land_document_old" value="<?=$land_document?>"
                                                    type="hidden" />
                                                <input class="land_document" type="hidden"
                                                    name="land_document_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('land_document'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Agreement Copy (In case of contract farming)
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="agreement_copy_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Agreement Copy (In case of contract farming)"
                                                        <?=($agreement_copy_type === 'Agreement Copy (In case of contract farming)')?'selected':''?>>
                                                        Agreement Copy (In case of contract farming)</option>
                                                </select>
                                                <?= form_error("agreement_copy_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="agreement_copy" name="agreement_copy"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($agreement_copy)){ ?>
                                                <a href="<?=base_url($agreement_copy)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="agreement_copy_old"
                                                    value="<?=$agreement_copy?>" type="hidden" />
                                                <input class="agreement_copy" type="hidden"
                                                    name="agreement_copy_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('agreement_copy'); ?>
                                            </td>
                                        </tr>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                   
                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($dbrow->processing_history)) {
                                    foreach ($dbrow->processing_history as $key => $rows) {
                                        $query_attachment = $rows->query_attachment ?? ''; ?>
                                        <tr>
                                            <td><?= sprintf("%02d", $key + 1) ?></td>
                                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?></td>
                                            <td><?= $rows->action_taken ??'' ?></td>
                                            <td><?= $rows->remarks ?></td>
                                        </tr>
                                <?php } //End of foreach()
                                } //End of if else 
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>