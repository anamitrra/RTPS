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
        $("#residential_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });      
        
        $("#obc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });    
        
        $("#income_certificate").fileinput({
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
        <form method="POST" action="<?= base_url('iservices/wptbc/nclcertificate/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input value="<?=$rtps_trans_id?>" name="rtps_trans_id" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( ননক্ৰিমি প্ৰমাণ পত্ৰৰ বাবে আবেদন )
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
                                            <th>Enclosure Documen</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Residential Proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="residential_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Electricity Bill">Electricity Bill</option>
                                                    <option value="Registered Land Document">Registered Land Document</option>
                                                    <option value="Voter ID Card">Voter ID Card</option>
                                                    <option value="Jamabandi Copy">Jamabandi Copy</option>
                                                    <option value="Permanent Resident Certificate (PRC)">Permanent Resident Certificate (PRC)</option>
                                                    <option value="Bank Passbook first page with photo">Bank Passbook first page with photo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="residential_proof" name="residential_proof" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>OBC / MOBC certificate issued by competent authority.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="obc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="OBC/MOBC certificate issued by competent authority">OBC/MOBC certificate issued by competent authority</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="obc" name="obc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Income certificate of parents.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="income_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Income certificate of parents">Income certificate of parents</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="income_certificate" name="income_certificate" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Others</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload the Soft Copy of Application Form</td>
                                            <td>
                                                <select name="soft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload the Soft Copy of Application Form">Upload the Soft Copy of Application Form</option>
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
                    <a href="<?=base_url('iservices/wptbc/nclcertificate/index/'.$obj_id)?>" class="btn btn-primary">
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