<?php
//pre($dbrow);
$technical_person_document_type_frm = set_value("technical_person_document_type");
$old_permission_copy_type_frm = set_value("old_permission_copy_type");
$old_drawing_type_frm = set_value("old_drawing_type");
$drawing_type_frm = set_value("drawing_type");
$trace_map_type_frm = set_value("trace_map_type");
$key_plan_type_frm = set_value("key_plan_type");
$site_plan_type_frm = set_value("site_plan_type");
$building_plan_type_frm = set_value("building_plan_type");
$certificate_of_supervision_type_frm = set_value("certificate_of_supervision_type");
$area_statement_type_frm = set_value("area_statement_type");
$amended_byelaws_type_frm = set_value("amended_byelaws_type");
$form_no_six_type_frm = set_value("form_no_six_type");
$indemnity_bond_type_frm = set_value("indemnity_bond_type");
$undertaking_signed_type_frm = set_value("undertaking_signed_type");
$party_applicant_form_type_frm = set_value("party_applicant_form_type");
$date_property_tax_type_frm = set_value("date_property_tax_type");
$service_plan_type_frm = set_value("service_plan_type");
$parking_plan_type_frm = set_value("parking_plan_type");
$ownership_document_of_land_type_frm = set_value("ownership_document_of_land_type");
$any_other_document_type_frm = set_value("any_other_document_type");
$construction_estimate_type_frm = set_value("construction_estimate_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$technical_person_document_frm = $uploadedFiles['technical_person_document_old']??null;
$old_permission_copy_frm = $uploadedFiles['old_permission_copy_old']??null;
$old_drawing_frm = $uploadedFiles['old_drawing_old']??null;
$drawing_frm = $uploadedFiles['drawing_old']??null;
$trace_map_frm = $uploadedFiles['trace_map_old']??null;
$key_plan_frm = $uploadedFiles['key_plan_old']??null;
$site_plan_frm = $uploadedFiles['site_plan_old']??null;
$building_plan_frm = $uploadedFiles['building_plan_old']??null;
$certificate_of_supervision_frm = $uploadedFiles['certificate_of_supervision_old']??null;
$area_statement_frm = $uploadedFiles['area_statement_old']??null;
$amended_byelaws_frm = $uploadedFiles['amended_byelaws_old']??null;
$form_no_six_frm = $uploadedFiles['form_no_six_old']??null;
$indemnity_bond_frm = $uploadedFiles['indemnity_bond_old']??null;
$undertaking_signed_frm = $uploadedFiles['undertaking_signed_old']??null;
$party_applicant_form_frm = $uploadedFiles['party_applicant_form_old']??null;
$date_property_tax_frm = $uploadedFiles['date_property_tax_old']??null;
$service_plan_frm = $uploadedFiles['service_plan_old']??null;
$parking_plan_frm = $uploadedFiles['parking_plan_old']??null;
$ownership_document_of_land_frm = $uploadedFiles['ownership_document_of_land_old']??null;
$any_other_document_frm = $uploadedFiles['any_other_document_old']??null;
$construction_estimate_frm = $uploadedFiles['construction_estimate_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$technical_person_document_type_db = $dbrow->form_data->technical_person_document_type??null;
$technical_person_document_db = $dbrow->form_data->technical_person_document??null;
$old_permission_copy_type_db = $dbrow->form_data->old_permission_copy_type??null;
$old_permission_copy_db = $dbrow->form_data->old_permission_copy??null;
$old_drawing_type_db = $dbrow->form_data->old_drawing_type??null;
$old_drawing_db = $dbrow->form_data->old_drawing??null;
$drawing_type_db = $dbrow->form_data->drawing_type??null;
$drawing_db = $dbrow->form_data->drawing??null;
$trace_map_type_db = $dbrow->form_data->trace_map_type??null;
$trace_map_db = $dbrow->form_data->trace_map??null;
$key_plan_type_db = $dbrow->form_data->key_plan_type??null;
$key_plan_db = $dbrow->form_data->key_plan??null;
$site_plan_type_db = $dbrow->form_data->site_plan_type??null;
$site_plan_db = $dbrow->form_data->site_plan??null;
$building_plan_type_db = $dbrow->form_data->building_plan_type??null;
$building_plan_db = $dbrow->form_data->building_plan??null;
$certificate_of_supervision_type_db = $dbrow->form_data->certificate_of_supervision_type??null;
$certificate_of_supervision_db = $dbrow->form_data->certificate_of_supervision??null;
$area_statement_type_db = $dbrow->form_data->area_statement_type??null;
$area_statement_db = $dbrow->form_data->area_statement??null;
$amended_byelaws_type_db = $dbrow->form_data->amended_byelaws_type??null;
$amended_byelaws_db = $dbrow->form_data->amended_byelaws??null;
$form_no_six_type_db = $dbrow->form_data->form_no_six_type??null;
$form_no_six_db = $dbrow->form_data->form_no_six??null;
$indemnity_bond_type_db = $dbrow->form_data->indemnity_bond_type??null;
$indemnity_bond_db = $dbrow->form_data->indemnity_bond??null;
$undertaking_signed_type_db = $dbrow->form_data->undertaking_signed_type??null;
$undertaking_signed_db = $dbrow->form_data->undertaking_signed??null;
$party_applicant_form_type_db = $dbrow->form_data->party_applicant_form_type??null;
$party_applicant_form_db = $dbrow->form_data->party_applicant_form??null;
$date_property_tax_type_db = $dbrow->form_data->date_property_tax_type??null;
$date_property_tax_db = $dbrow->form_data->date_property_tax??null;
$service_plan_type_db = $dbrow->form_data->service_plan_type??null;
$service_plan_db = $dbrow->form_data->service_plan??null;
$parking_plan_type_db = $dbrow->form_data->parking_plan_type??null;
$parking_plan_db = $dbrow->form_data->parking_plan??null;
$ownership_document_of_land_type_db = $dbrow->form_data->ownership_document_of_land_type??null;
$ownership_document_of_land_db = $dbrow->form_data->ownership_document_of_land??null;
$any_other_document_type_db = $dbrow->form_data->any_other_document_type??null;
$any_other_document_db = $dbrow->form_data->any_other_document??null;
$construction_estimate_type_db = $dbrow->form_data->construction_estimate_type??null;
$construction_estimate_db = $dbrow->form_data->construction_estimate??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$technical_person_document_type = strlen($technical_person_document_type_frm)?$technical_person_document_type_frm:$technical_person_document_type_db;
$old_permission_copy_type = strlen($old_permission_copy_type_frm)?$old_permission_copy_type_frm:$old_permission_copy_type_db;
$old_drawing_type = strlen($old_drawing_type_frm)?$old_drawing_type_frm:$old_drawing_type_db;
$drawing_type = strlen($drawing_type_frm)?$drawing_type_frm:$drawing_type_db;
$trace_map_type = strlen($trace_map_type_frm)?$trace_map_type_frm:$trace_map_type_db;
$key_plan_type = strlen($key_plan_type_frm)?$key_plan_type_frm:$key_plan_type_db;
$site_plan_type = strlen($site_plan_type_frm)?$site_plan_type_frm:$site_plan_type_db;
$building_plan_type = strlen($building_plan_type_frm)?$building_plan_type_frm:$building_plan_type_db;
$certificate_of_supervision_type = strlen($certificate_of_supervision_type_frm)?$certificate_of_supervision_type_frm:$certificate_of_supervision_type_db;
$area_statement_type = strlen($area_statement_type_frm)?$area_statement_type_frm:$area_statement_type_db;
$amended_byelaws_type = strlen($amended_byelaws_type_frm)?$amended_byelaws_type_frm:$amended_byelaws_type_db;
$form_no_six_type = strlen($form_no_six_type_frm)?$form_no_six_type_frm:$form_no_six_type_db;
$indemnity_bond_type = strlen($indemnity_bond_type_frm)?$indemnity_bond_type_frm:$indemnity_bond_type_db;
$undertaking_signed_type = strlen($undertaking_signed_type_frm)?$undertaking_signed_type_frm:$undertaking_signed_type_db;
$party_applicant_form_type = strlen($party_applicant_form_type_frm)?$party_applicant_form_type_frm:$party_applicant_form_type_db;
$date_property_tax_type = strlen($date_property_tax_type_frm)?$date_property_tax_type_frm:$date_property_tax_type_db;
$service_plan_type = strlen($service_plan_type_frm)?$service_plan_type_frm:$service_plan_type_db;
$parking_plan_type = strlen($parking_plan_type_frm)?$parking_plan_type_frm:$parking_plan_type_db;
$ownership_document_of_land_type = strlen($ownership_document_of_land_type_frm)?$ownership_document_of_land_type_frm:$ownership_document_of_land_type_db;
$any_other_document_type = strlen($any_other_document_type_frm)?$any_other_document_type_frm:$any_other_document_type_db;
$construction_estimate_type = strlen($construction_estimate_type_frm)?$construction_estimate_type_frm:$construction_estimate_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;

$technical_person_document = strlen($technical_person_document_frm)?$technical_person_document_frm:$technical_person_document_db;
$old_permission_copy = strlen($old_permission_copy_frm)?$old_permission_copy_frm:$old_permission_copy_db;
$old_drawing = strlen($old_drawing_frm)?$old_drawing_frm:$old_drawing_db;
$drawing = strlen($drawing_frm)?$drawing_frm:$drawing_db;
$trace_map = strlen($trace_map_frm)?$trace_map_frm:$trace_map_db;
$key_plan = strlen($key_plan_frm)?$key_plan_frm:$key_plan_db;
$site_plan = strlen($site_plan_frm)?$site_plan_frm:$site_plan_db;
$building_plan = strlen($building_plan_frm)?$building_plan_frm:$building_plan_db;
$certificate_of_supervision = strlen($certificate_of_supervision_frm)?$certificate_of_supervision_frm:$certificate_of_supervision_db;
$area_statement = strlen($area_statement_frm)?$area_statement_frm:$area_statement_db;
$amended_byelaws = strlen($amended_byelaws_frm)?$amended_byelaws_frm:$amended_byelaws_db;
$form_no_six = strlen($form_no_six_frm)?$form_no_six_frm:$form_no_six_db;
$indemnity_bond = strlen($indemnity_bond_frm)?$indemnity_bond_frm:$indemnity_bond_db;
$undertaking_signed = strlen($undertaking_signed_frm)?$undertaking_signed_frm:$undertaking_signed_db;
$party_applicant_form = strlen($party_applicant_form_frm)?$party_applicant_form_frm:$party_applicant_form_db;
$date_property_tax = strlen($date_property_tax_frm)?$date_property_tax_frm:$date_property_tax_db;
$service_plan = strlen($service_plan_frm)?$service_plan_frm:$service_plan_db;$parking_plan = strlen($parking_plan_frm)?$parking_plan_frm:$parking_plan_db;
$ownership_document_of_land = strlen($ownership_document_of_land_frm)?$ownership_document_of_land_frm:$ownership_document_of_land_db;
$any_other_document = strlen($any_other_document_frm)?$any_other_document_frm:$any_other_document_db;
$construction_estimate = strlen($construction_estimate_frm)?$construction_estimate_frm:$construction_estimate_db;
$soft_copy = strlen($soft_copy_frm)?$soft_copy_frm:$soft_copy_db;
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

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var technicalPersonDocument = parseInt(<?=strlen($technical_person_document)?1:0?>);
        $("#technical_person_document").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: technicalPersonDocument?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var oldPermissionCopy = parseInt(<?=strlen($old_permission_copy)?1:0?>);
        $("#old_permission_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: oldPermissionCopy?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var oldDrawing = parseInt(<?=strlen($old_drawing)?1:0?>);
        $("#old_drawing").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: oldDrawing?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#drawing").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#trace_map").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#key_plan").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#site_plan").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#building_plan").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#certificate_of_supervision").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#area_statement").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#amended_byelaws").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#form_no_six").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#indemnity_bond").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#undertaking_signed").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#party_applicant_form").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#date_property_tax").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#service_plan").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#parking_plan").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#ownership_document_of_land").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#any_other_document").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#construction_estimate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        
        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/buildingpermission/registration/submitfiles') ?>" enctype="multipart/form-data">

            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <?php if($dbrow->form_data->ertp === 'yes') { ?>
            <input name="technical_person_document_old" value="<?=$technical_person_document?>" type="hidden" />
            <?php } ?>
            <?php if(($dbrow->form_data->case_type === '2') && ($dbrow->form_data->any_old_permission === 'yes')) { ?>
            <input name="old_permission_copy_old" value="<?=$old_permission_copy?>" type="hidden" />
            <input name="old_drawing_old" value="<?=$old_drawing?>" type="hidden" />
            <?php } ?>
            <input name="drawing_old" value="<?=$drawing?>" type="hidden" />
            <input name="trace_map_old" value="<?=$trace_map?>" type="hidden" />
            <input name="key_plan_old" value="<?=$key_plan?>" type="hidden" />
            <input name="site_plan_old" value="<?=$site_plan?>" type="hidden" />
            <input name="building_plan_old" value="<?=$building_plan?>" type="hidden" />
            <input name="certificate_of_supervision_old" value="<?=$certificate_of_supervision?>" type="hidden" />
            <input name="area_statement_old" value="<?=$area_statement?>" type="hidden" />
            <input name="amended_byelaws_old" value="<?=$amended_byelaws?>" type="hidden" />
            <input name="form_no_six_old" value="<?=$form_no_six?>" type="hidden" />
            <input name="indemnity_bond_old" value="<?=$indemnity_bond?>" type="hidden" />
            <input name="undertaking_signed_old" value="<?=$undertaking_signed?>" type="hidden" />
            <input name="party_applicant_form_old" value="<?=$party_applicant_form?>" type="hidden" />
            <input name="date_property_tax_old" value="<?=$date_property_tax?>" type="hidden" />
            <input name="service_plan_old" value="<?=$service_plan?>" type="hidden" />
            <input name="parking_plan_old" value="<?=$parking_plan?>" type="hidden" />
            <input name="ownership_document_of_land_old" value="<?=$ownership_document_of_land?>" type="hidden" />
            <input name="any_other_document_old" value="<?=$any_other_document?>" type="hidden" />
            <input name="construction_estimate_old" value="<?=$construction_estimate?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    Application Form for Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni
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
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
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
                                        <?php if($dbrow->form_data->ertp === 'yes') { ?>
                                        <tr>
                                            <td>Upload Technical Person Qualification Document<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="technical_person_document_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload Technical Person Qualification Document" <?=($technical_person_document_type === 'Upload Technical Person Qualification Document')?'selected':''?>>Upload Technical Person Qualification Document</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="technical_person_document" name="technical_person_document" type="file" />
                                                </div>
                                                <?php if(strlen($technical_person_document)){ ?>
                                                    <a href="<?=base_url($technical_person_document)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>   
                                        <?php }//End of if ?> 

                                        <?php if(($dbrow->form_data->case_type === '2') && ($dbrow->form_data->any_old_permission === 'yes')) { ?>
                                        <tr>
                                            <td>Upload Old Permission Copy<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="old_permission_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload Old Permission Copy" <?=($old_permission_copy_type === 'Upload Old Permission Copy')?'selected':''?>>Upload Old Permission Copy</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="old_permission_copy" name="old_permission_copy" type="file" />
                                                </div>
                                                <?php if(strlen($old_permission_copy)){ ?>
                                                    <a href="<?=base_url($old_permission_copy)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Upload Old Drawing<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="old_drawing_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload Old Drawing" <?=($old_drawing_type === 'Upload Old Drawing')?'selected':''?>>Upload Old Drawing</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="old_drawing" name="old_drawing" type="file" />
                                                </div>
                                                <?php if(strlen($old_drawing)){ ?>
                                                    <a href="<?=base_url($old_drawing)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>  
                                        <?php }//End of if ?> 

                                        <?php if(strlen($drawing)){ ?>
                                        <!-- <tr>
                                            <td>Drawing</td>
                                            <td>
                                                <select name="drawing_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Drawing" <?=($drawing_type === 'Drawing')?'selected':''?>>Drawing</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="drawing" name="drawing" type="file" />
                                                </div>
                                                <?php if(strlen($drawing)){ ?>
                                                    <a href="<?=base_url($drawing)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Trace map of the proposed site indicating the Dag no, Patta no, Revenue Village, Mouza and the Town of the concerned District</td>
                                            <td>
                                                <select name="trace_map_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Trace map of the proposed site indicating the Dag no, Patta no, Revenue Village, Mouza and the Town of the concerned District" <?=($trace_map_type === 'Trace map of the proposed site indicating the Dag no, Patta no, Revenue Village, Mouza and the Town of the concerned District')?'selected':''?>>Trace map of the proposed site indicating the Dag no, Patta no, Revenue Village, Mouza and the Town of the concerned District</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="trace_map" name="trace_map" type="file" />
                                                </div>
                                                <?php if(strlen($trace_map)){ ?>
                                                    <a href="<?=base_url($trace_map)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>A key plan of the showing natural channels, drains, roads and landmarks</td>
                                            <td>
                                                <select name="key_plan_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="A key plan of the showing natural channels, drains, roads and landmarks" <?=($key_plan_type === 'A key plan of the showing natural channels, drains, roads and landmarks')?'selected':''?>>A key plan of the showing natural channels, drains, roads and landmarks</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="key_plan" name="key_plan" type="file" />
                                                </div>
                                                <?php if(strlen($key_plan)){ ?>
                                                    <a href="<?=base_url($key_plan)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>A site plan drawn to a minimum scale 1:200 with detailed schedule of the plot</td>
                                            <td>
                                                <select name="site_plan_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="A site plan drawn to a minimum scale 1:200 with detailed schedule of the plot" <?=($site_plan_type === 'A site plan drawn to a minimum scale 1:200 with detailed schedule of the plot')?'selected':''?>>A site plan drawn to a minimum scale 1:200 with detailed schedule of the plot</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="site_plan" name="site_plan" type="file" />
                                                </div>
                                                <?php if(strlen($site_plan)){ ?>
                                                    <a href="<?=base_url($site_plan)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>A building plan accurately drawn in a minimum scale of 1:100 with dimensions in meters </td>
                                            <td>
                                                <select name="building_plan_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="A building plan accurately drawn in a minimum scale of 1:100 with dimensions in meters" <?=($building_plan_type === 'A building plan accurately drawn in a minimum scale of 1:100 with dimensions in meters')?'selected':''?>>A building plan accurately drawn in a minimum scale of 1:100 with dimensions in meters</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="building_plan" name="building_plan" type="file" />
                                                </div>
                                                <?php if(strlen($building_plan)){ ?>
                                                    <a href="<?=base_url($building_plan)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>A Certificate of supervision in form no. 7(A)</td>
                                            <td>
                                                <select name="certificate_of_supervision_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="A Certificate of supervision in form no. 7(A)" <?=($certificate_of_supervision_type === 'A Certificate of supervision in form no. 7(A)')?'selected':''?>>A Certificate of supervision in form no. 7(A)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="certificate_of_supervision" name="certificate_of_supervision" type="file" />
                                                </div>
                                                <?php if(strlen($certificate_of_supervision)){ ?>
                                                    <a href="<?=base_url($certificate_of_supervision)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Area statement in Form no. 22</td>
                                            <td>
                                                <select name="area_statement_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Area statement in Form no. 22" <?=($area_statement_type === 'Area statement in Form no. 22')?'selected':''?>>Area statement in Form no. 22</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="area_statement" name="area_statement" type="file" />
                                                </div>
                                                <?php if(strlen($area_statement)){ ?>
                                                    <a href="<?=base_url($area_statement)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Amended From 23 vide Clause 23 of amended Byelaws, 2020</td>
                                            <td>
                                                <select name="amended_byelaws_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Amended From 23 vide Clause 23 of amended Byelaws, 2020" <?=($amended_byelaws_type === 'Amended From 23 vide Clause 23 of amended Byelaws, 2020')?'selected':''?>>Amended From 23 vide Clause 23 of amended Byelaws, 2020</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="amended_byelaws" name="amended_byelaws" type="file" />
                                                </div>
                                                <?php if(strlen($amended_byelaws)){ ?>
                                                    <a href="<?=base_url($amended_byelaws)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Form no. 6 in case of building located in hilly topography and in slopes above 30%</td>
                                            <td>
                                                <select name="form_no_six_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Form no. 6 in case of building located in hilly topography and in slopes above 30%" <?=($form_no_six_type === 'Form no. 6 in case of building located in hilly topography and in slopes above 30%')?'selected':''?>>Form no. 6 in case of building located in hilly topography and in slopes above 30%</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="form_no_six" name="form_no_six" type="file" />
                                                </div>
                                                <?php if(strlen($form_no_six)){ ?>
                                                    <a href="<?=base_url($form_no_six)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Indemnity Bond in Appendix - IV for basement and retaining wall only</td>
                                            <td>
                                                <select name="indemnity_bond_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Indemnity Bond in Appendix - IV for basement and retaining wall only" <?=($indemnity_bond_type === 'Indemnity Bond in Appendix - IV for basement and retaining wall only')?'selected':''?>>Indemnity Bond in Appendix - IV for basement and retaining wall only</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="indemnity_bond" name="indemnity_bond" type="file" />
                                                </div>
                                                <?php if(strlen($indemnity_bond)){ ?>
                                                    <a href="<?=base_url($indemnity_bond)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>An undertaking signed by the land owner or Power of Attorney Holder or Builder or Promoter or the Applicant as per Appendix - V of the bylaws</td>
                                            <td>
                                                <select name="undertaking_signed_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="An undertaking signed by the land owner or Power of Attorney Holder or Builder or Promoter or the Applicant as per Appendix - V of the bylaws" <?=($undertaking_signed_type === 'An undertaking signed by the land owner or Power of Attorney Holder or Builder or Promoter or the Applicant as per Appendix - V of the bylaws')?'selected':''?>>An undertaking signed by the land owner or Power of Attorney Holder or Builder or Promoter or the Applicant as per Appendix - V of the bylaws</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="undertaking_signed" name="undertaking_signed" type="file" />
                                                </div>
                                                <?php if(strlen($undertaking_signed)){ ?>
                                                    <a href="<?=base_url($undertaking_signed)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>The party/Applicant should submit an affidavit along with the application form</td>
                                            <td>
                                                <select name="party_applicant_form_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="The party/Applicant should submit an affidavit along with the application form" <?=($party_applicant_form_type === 'The party/Applicant should submit an affidavit along with the application form')?'selected':''?>>The party/Applicant should submit an affidavit along with the application form</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="party_applicant_form" name="party_applicant_form" type="file" />
                                                </div>
                                                <?php if(strlen($party_applicant_form)){ ?>
                                                    <a href="<?=base_url($party_applicant_form)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>The up to date property tax paid receipt to be submitted, in case of existing building/structure, if any</td>
                                            <td>
                                                <select name="date_property_tax_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="The up to date property tax paid receipt to be submitted, in case of existing building/structure, if any" <?=($date_property_tax_type === 'The up to date property tax paid receipt to be submitted, in case of existing building/structure, if any')?'selected':''?>>The up to date property tax paid receipt to be submitted, in case of existing building/structure, if any</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="date_property_tax" name="date_property_tax" type="file" />
                                                </div>
                                                <?php if(strlen($date_property_tax)){ ?>
                                                    <a href="<?=base_url($date_property_tax)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Service plan showing provision of all the services as provided in the bylaws</td>
                                            <td>
                                                <select name="service_plan_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Service plan showing provision of all the services as provided in the bylaws" <?=($service_plan_type === 'Service plan showing provision of all the services as provided in the bylaws')?'selected':''?>>Service plan showing provision of all the services as provided in the bylaws</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="service_plan" name="service_plan" type="file" />
                                                </div>
                                                <?php if(strlen($service_plan)){ ?>
                                                    <a href="<?=base_url($service_plan)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Detailed parking plan in appropriate scale where applicable</td>
                                            <td>
                                                <select name="parking_plan_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Detailed parking plan in appropriate scale where applicable" <?=($parking_plan_type === 'Detailed parking plan in appropriate scale where applicable')?'selected':''?>>Detailed parking plan in appropriate scale where applicable</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="parking_plan" name="parking_plan" type="file" />
                                                </div>
                                                <?php if(strlen($parking_plan)){ ?>
                                                    <a href="<?=base_url($parking_plan)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Ownership document of land, sale deed, mutation/jamabandi/patta/power of attorney</td>
                                            <td>
                                                <select name="ownership_document_of_land_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Ownership document of land, sale deed, mutation/jamabandi/patta/power of attorney" <?=($ownership_document_of_land_type === 'Ownership document of land, sale deed, mutation/jamabandi/patta/power of attorney')?'selected':''?>>Ownership document of land, sale deed, mutation/jamabandi/patta/power of attorney</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ownership_document_of_land" name="ownership_document_of_land" type="file" />
                                                </div>
                                                <?php if(strlen($ownership_document_of_land)){ ?>
                                                    <a href="<?=base_url($ownership_document_of_land)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Any other document that the applicant feels necessary for consideration by the Authority</td>
                                            <td>
                                                <select name="any_other_document_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Any other document that the applicant feels necessary for consideration by the Authority" <?=($any_other_document_type === 'Any other document that the applicant feels necessary for consideration by the Authority')?'selected':''?>>Any other document that the applicant feels necessary for consideration by the Authority</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="any_other_document" name="any_other_document" type="file" />
                                                </div>
                                                <?php if(strlen($any_other_document)){ ?>
                                                    <a href="<?=base_url($any_other_document)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Construction Estimate</td>
                                            <td>
                                                <select name="construction_estimate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Construction Estimate" <?=($construction_estimate_type === 'Construction Estimate')?'selected':''?>>Construction Estimate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="construction_estimate" name="construction_estimate" type="file" />
                                                </div>
                                                <?php if(strlen($construction_estimate)){ ?>
                                                    <a href="<?=base_url($construction_estimate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr> -->
                                        <?php }//End of if ?>
                                        <!-- <?php if($this->slug !== 'user') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?=($soft_copy_type === 'Soft copy of the applicant form')?'selected':''?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if(strlen($soft_copy)){ ?>
                                                        <a href="<?=base_url($soft_copy)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php }//End of if ?>
                                                </td>
                                            </tr>
                                        <?php }//End of if ?>  -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/buildingpermission/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger">
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