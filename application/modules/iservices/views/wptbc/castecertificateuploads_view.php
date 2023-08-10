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
    $(document).ready(function () {           
        $("#caste_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        }); 
        $("#gaonbura_report").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });      
        
        $("#prc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });    
        
        $("#nrc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
        
        $("#ajp").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
        
        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
        
        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('iservices/wptbc/castecertificate/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input value="<?=$rtps_trans_id?>" name="rtps_trans_id" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                        Application for Issuance Of Scheduled Caste Certificate<br>
                        ( অনুসুচিত জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন ) 
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
                                        <tr>
                                            <td>Caste certificate of father or any supporting proof of caste status.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="caste_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Caste certificate of father or any supporting proof of caste status">Caste certificate of father or any supporting proof of caste status</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="caste_certificate" name="caste_certificate" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Report of Gaonburah in case of rural areas / Ward Commissioner in case of urban areas.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="gaonbura_report_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Report of Gaonburah in case of rural areas/Ward Commissioner in case of urban areas">Report of Gaonburah in case of rural areas/Ward Commissioner in case of urban areas</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="gaonbura_report" name="gaonbura_report" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Permanent Resident Certificate (PRC)<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Resident Certificate(PRC)">Permanent Resident Certificate(PRC)</option>
                                                    <option value="Voter ID Card (In case PRC is not available)">Voter ID Card (In case PRC is not available)</option>
                                                    <option value="Electricity Bill (In case PRC is not available)">Electricity Bill (In case PRC is not available)</option>
                                                    <option value="Bank Passbook (In case PRC is not available)">Bank Passbook (In case PRC is not available)</option>
                                                    <option value="Jamabandi (In case PRC is not available)">Jamabandi (In case PRC is not available)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc" name="prc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="nrc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971">Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="nrc" name="nrc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Recommendation of President / Secretary of District Anuhushit Jati Parishad<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ajp_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Recommendation of President/Secretary of District Anuhushit Jati Parishad">Recommendation of President/Secretary of District Anuhushit Jati Parishad</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ajp" name="ajp" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Other document as per requirement</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other document as per requirement">Other document as per requirement</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload the Soft copy of the applicant form</td>
                                            <td>
                                                <select name="soft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload the Soft copy of the applicant form">Upload the Soft copy of the applicant form</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="soft_copy" name="soft_copy" type="file" />
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
                    <a href="<?=base_url('iservices/wptbc/castecertificate/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>