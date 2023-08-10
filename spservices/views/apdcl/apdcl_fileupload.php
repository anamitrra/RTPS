<?php
    $identity_attach_frm = set_value("identity_attach");
    $address_attach_frm = set_value("address_attach");
    $land_attach_frm = set_value("land_attach");

    $uploadedFiles = $this->session->flashdata('uploaded_files');

    $identityFile_frm = $uploadedFiles['identityFile_old']??null;
    $addressFile_frm = $uploadedFiles['addressFile_old']??null;
    $selffAttestedFile_frm = $uploadedFiles['selffAttestedFile_old']??null;
    $testReportFile_frm = $uploadedFiles['testReportFile_old']??null;
    $scannedPhoto_frm = $uploadedFiles['scannedPhoto_old']??null;
    $gmcFile_frm = $uploadedFiles['gmcFile_old']??null;
    $nocFile_frm = $uploadedFiles['nocFile_old']??null;

    $identity_attach_db = $dbrow->form_data->identity_attach??null;
    $address_attach_db = $dbrow->form_data->address_attach??null;
    $land_attach_db = $dbrow->form_data->land_attach??null;
    $identityFile_db = $dbrow->form_data->identityFile??null;
    $addressFile_db = $dbrow->form_data->addressFile??null;
    $selffAttestedFile_db = $dbrow->form_data->selffAttestedFile??null;
    $testReportFile_db = $dbrow->form_data->testReportFile??null;
    $scannedPhoto_db = $dbrow->form_data->scannedPhoto??null;
    $gmcFile_db = $dbrow->form_data->gmcFile??null;
    $nocFile_db = $dbrow->form_data->nocFile??null;

    $identity_attach = strlen($identity_attach_frm) ? $identity_attach_frm : $identity_attach_db;
    $address_attach = strlen($address_attach_frm) ? $address_attach_frm : $address_attach_db;
    $land_attach = strlen($land_attach_frm) ? $land_attach_frm : $land_attach_db;
    $identityFile = strlen($identityFile_frm) ? $identityFile_frm : $identityFile_db;
    $addressFile = strlen($addressFile_frm) ? $addressFile_frm : $addressFile_db;
    $selffAttestedFile = strlen($selffAttestedFile_frm) ? $selffAttestedFile_frm : $selffAttestedFile_db;
    $testReportFile = strlen($testReportFile_frm) ? $testReportFile_frm : $testReportFile_db;
    $scannedPhoto = strlen($scannedPhoto_frm) ? $scannedPhoto_frm : $scannedPhoto_db;
    $gmcFile = strlen($gmcFile_frm) ? $gmcFile_frm : $gmcFile_db;
    $nocFile = strlen($nocFile_frm) ? $nocFile_frm : $nocFile_db;

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
<script>
 $(document).ready(function(){
    var identityF = parseInt(<?=strlen($identityFile)?1:0?>);
    $("#identityFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: identityF ? false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });

    var addressF = parseInt(<?=strlen($addressFile)?1:0?>);
    $("#addressFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: addressF ? false:true,
            maxFileSize: 10485760,
            allowedFileExtensions: ["pdf","jpg","jpeg","png"]
    });

    var selffAttestedF = parseInt(<?=strlen($selffAttestedFile)?1:0?>);
    $("#selffAttestedFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: selffAttestedF ? false:true,
            maxFileSize: 10485760,
            allowedFileExtensions: ["pdf","jpg","jpeg","png"]
    });

    var testReportF = parseInt(<?=strlen($testReportFile)?1:0?>);
    $("#testReportFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: testReportF ? false:true,
            maxFileSize: 10485760,
            allowedFileExtensions: ["pdf","jpg","jpeg","png"]
    });

    var scannedPhotoF = parseInt(<?=strlen($scannedPhoto)?1:0?>);
    $("#scannedPhoto").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: scannedPhotoF ? false:true,
            maxFileSize: 10485760,
            allowedFileExtensions: ["jpg","jpeg","png"]
    });
    $("#gmcFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 10485760,
            allowedFileExtensions: ["pdf","jpg","jpeg","png"]
    });
    $("#nocFile").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 10485760,
            allowedFileExtensions: ["pdf","jpg","jpeg","png"]
    });
 });
</script>
        
<main class="rtps-container">
    <div class="container-fluid my-2" style="width:90%">
        <form method="POST" action="<?= base_url('spservices/apdcl/registration/submitFiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="identityFile_old" value="<?=$identityFile?>" type="hidden" />
            <input name="addressFile_old" value="<?=$addressFile?>" type="hidden" />
            <input name="selffAttestedFile_old" value="<?=$selffAttestedFile?>" type="hidden" />
            <input name="testReportFile_old" value="<?=$testReportFile?>" type="hidden" />
            <input name="scannedPhoto_old" value="<?=$scannedPhoto?>" type="hidden" />
            <input name="gmcFile_old" value="<?=$gmcFile?>" type="hidden" />
            <input name="nocFile_old" value="<?=$nocFile?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                      
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
                                        
                    <fieldset class="border border-success" style="margin-top:30px">
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
                        <h6 class="text-danger">Upload Files (Max. file size allowed: 10MB) / নথিপত্ৰ আপলোড কৰক ( নথিপত্ৰৰ সৰ্বাধিক অনুমোদিত আকাৰ: 10MB )<h6>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 40%">Enclosure Name</th>    
                                            <th class="text-center" style="width: 30%">Type of Enclosure</th>                                           
                                            <th class="text-center" style="width: 30%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Proof of Identity / পৰিচয়ৰ প্ৰমাণ:</td>
                                            <td>
                                                <select name="identity_attach" id="identity_attach" class="form-control">
                                                    <option value="">--Select Option--</option>
                                                    <option value="voter" <?=($identity_attach === "voter")?'selected':''?>>Voter ID Card</option>
                                                    <option value="passport" <?=($identity_attach === "passport")?'selected':''?>>Passport</option>
                                                    <option value="driving" <?=($identity_attach === "driving")?'selected':''?>>Driving License</option>
                                                    <option value="ration" <?=($identity_attach === "ration")?'selected':''?>>Ration Card</option>
                                                    <option value="bpl" <?=($identity_attach === "bpl")?'selected':''?>>BPL Card</option>
                                                    <option value="pan" <?=($identity_attach === "pan")?'selected':''?>>PAN Card</option>
                                                    <option value="aadhar" <?=($identity_attach === "aadhar")?'selected':''?>>AADHAR Card</option>
                                                    <option value="photoId" <?=($identity_attach === "photoId")?'selected':''?>>Photo identity Certificate from local competent authority</option>
                                                </select>
                                                <?= form_error("identity_attach") ?>
                                            </td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="identityFile" id="identityFile">
                                                    
                                                    <?php if(strlen($identityFile)){ ?>
                                                        <a href="<?=base_url($identityFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Residential Address Proof / আৱাসিক ঠিকনাৰ প্ৰমাণ:</td>
                                            <td>
                                            <select name="address_attach" id="address_attach" class="form-control" >
                                                    <option value="">--Select Option--</option>
                                                    <option value="passbook" <?=($address_attach === "passbook")?'selected':''?>>Bank Passbook</option>
                                                    <option value="telephone" <?=($address_attach === "telephone")?'selected':''?>>Telephone Bill</option>
                                                    <option value="driving" <?=($address_attach === "driving")?'selected':''?>>Driving License</option>
                                                    <option value="ration" <?=($address_attach === "ration")?'selected':''?>>Ration Card</option>
                                                    <option value="bpl" <?=($address_attach === "bpl")?'selected':''?>>BPL Card</option>
                                                    <option value="existingBill" <?=($address_attach === "existingBill")?'selected':''?>>Existing Electricity Bill</option>
                                                    <option value="aadhar" <?=($address_attach === "aadhar")?'selected':''?>>AADHAR Card</option>
                                                    <option value="photoId" <?=($address_attach === "photoId")?'selected':''?>>Photo identity Certificate from local competent authority</option>
                                            </select>
                                                <?= form_error("address_attach") ?>
                                            </td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="addressFile" id="addressFile">
                                                    
                                                    <?php if(strlen($addressFile)){ ?>
                                                        <a href="<?=base_url($addressFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php }//End of if ?>
                                                </div>                                                   
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Proof of the legal occupation of the premises / চৌহদৰ আইনী দখলৰ প্ৰমাণ:</td>
                                            <td>
                                            <select name="land_attach" id="land_attach" class="form-control" >
                                                <option value="">--Select Option--</option>
                                                <option value="holdingNo" <?=($land_attach === "holdingNo")?'selected':''?>>Holding No</option>
                                                <option value="leaseAgreement" <?=($land_attach === "leaseAgreement")?'selected':''?>>Lease Agreement</option>
                                                <option value="rentAgreement" <?=($land_attach === "rentAgreement")?'selected':''?>>Rent Agreement</option>
                                                <option value="sale" <?=($land_attach === "sale")?'selected':''?>>Sale Deed</option>
                                            </select>
                                                <?= form_error("land_attach") ?>
                                            </td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="selffAttestedFile" id="selffAttestedFile">
                                                    
                                                    <?php if(strlen($selffAttestedFile)){ ?>
                                                        <a href="<?=base_url($selffAttestedFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Test report from authorized electrical contractor/supervisor / কৰ্তৃত্বপ্ৰাপ্ত বৈদ্যুতিক ঠিকাদাৰ / চুপাৰভাইজাৰৰ পৰা পৰীক্ষা প্ৰতিবেদন:</td>
                                            <td></td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="testReportFile" id="testReportFile">
                                                    
                                                    <?php if(strlen($testReportFile)){ ?>
                                                        <a href="<?=base_url($testReportFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Latest Passport Size Photo of the Applicant (in jpeg or png format)/ আবেদনকাৰীৰ শেহতীয়া পাছপোৰ্ট আকাৰৰ ফটো :</td>
                                            <td></td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="scannedPhoto" id="scannedPhoto">
                                                    
                                                    <?php if(strlen($scannedPhoto)){ ?>
                                                        <a href="<?=base_url($scannedPhoto)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Affidavit/NOC/Self declaration by the consumer if not having Assessment Id / মূল্যায়ন আইডি নাথাকিলে গ্ৰাহকৰ দ্বাৰা শপতনামা/এন.ও.চি./স্ব-ঘোষণা:</td>
                                            <td></td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="gmcFile" id="gmcFile">
                                                    
                                                    <?php if(strlen($gmcFile)){ ?>
                                                        <a href="<?=base_url($gmcFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-size: 14px"><span class="text-danger">*</span> Affidavit from land owner indicating no objection to give connection to the 
																				applicant and indemnity bond (Optional) from the owner, if the applicant is not the owner of the land 
                                                                                / ভূমিমালিকৰ পৰা শপতনামা যিয়ে সংযোগ দিবলৈ কোনো আপত্তি নাই সূচায় 
																				আবেদনকাৰী আৰু মালিকৰ পৰা ক্ষতিপূৰণ বন্ধন (বৈকল্পিক), যদি আবেদনকাৰী ভূমিৰ গৰাকী নহয়:</td>
                                            <td></td>
                                            <td>
                                                <div class="file-uploading">
                                                    <input type="file" class="form-control" name="nocFile" id="nocFile">
                                                    
                                                    <?php if(strlen($nocFile)){ ?>
                                                        <a href="<?=base_url($nocFile)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } ?>
                                                </div>                                                   
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/apdcl_form/'.$obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Save &amp Next
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>

<script>
 $(document).ready(function(){
        $('#identityFile').blur(function() {
				var POIfile = $('#identityFile').val().length;
				if(POIfile == 0)
				{
					$('.poi').html("");
				} else {
					var file_size = $('#identityFile')[0].files[0].size;
					if(file_size > 10485760)
					{
						$('.poi').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.poi').html("");
						return true;						
					}
				}
		});
		$('#addressFile').blur(function() {
				var POAfile = $('#addressFile').val().length;
				if(POAfile == 0)
				{
					$('.poa').html("");
				} else {
					var file_size2 = $('#addressFile')[0].files[0].size;
					if(file_size2 > 10485760)
					{
						$('.poa').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.poa').html("");
						return true;						
					}
				}
		});
		$('#selffAttestedFile').blur(function() {
				var POLOfile = $('#selffAttestedFile').val().length;
				if(POLOfile == 0)
				{
					$('.selfAttest').html("");
				} else {
					var file_size3 = $('#selffAttestedFile')[0].files[0].size;
					if(file_size3 > 10485760)
					{
						$('.selfAttest').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.selfAttest').html("");
						return true;						
					}
				}
		});
		$('#testReportFile').blur(function() {
				var AECSfile = $('#testReportFile').val().length;
				if(AECSfile == 0)
				{
					$('.testReport').html("");
				} else {
					var file_size4 = $('#testReportFile')[0].files[0].size;
					if(file_size4 > 10485760)
					{
						$('.testReport').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.testReport').html("");
						return true;						
					}
				}
		});
		$('#scannedPhoto').blur(function() {
				var PPhoto = $('#scannedPhoto').val().length;
				if(PPhoto == 0)
				{
					$('.photo').html("");
				} else {
					var file_size5 = $('#scannedPhoto')[0].files[0].size;
					if(file_size5 > 10485760)
					{
						$('.photo').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else 
					{
						var f_name = $('#scannedPhoto')[0].files[0].name;
						var f_extension = f_name.substring(f_name.lastIndexOf('.')+1);
						var validExtensions = ["jpg","jpeg","png"];
							if ($.inArray(f_extension, validExtensions) == -1){
									$('.photo').html("Only jpg or png files are allowed!").css('color','red').css('font-size','12px');
									return false;
							} else{
									$('.photo').html("");
									return true;
							}
					}
				}
		});
		$('#nocFile').blur(function() {
				var AFfile = $('#nocFile').val().length;
				if(AFfile == 0)
				{
					$('.noc').html("");
				} else {
					var file_size6 = $('#nocFile')[0].files[0].size;
					if(file_size6 > 10485760)
					{
						$('.noc').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.noc').html("");
						return true;						
					}
				}
		});
		$('#gmcFile').blur(function() {
				var gmcFfile = $('#gmcFile').val().length;
				if(gmcFfile == 0)
				{
					$('.gmc').html("");
				} else {
					var file_size7 = $('#gmcFile')[0].files[0].size;
					if(file_size7 > 10485760)
					{
						$('.gmc').html('File size is greater than 10 MB').css('color','red').css('font-size','12px');
						return false;
					}
					else {
						$('.gmc').html("");
						return true;
						
					}
				}
		});	
 });
</script>