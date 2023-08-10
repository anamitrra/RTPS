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
        $("#applicant_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        $("#proof_dob").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        $("#proof_res").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });

        $("#caste_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });

        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });

        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/bhumiputra/registration/submitfiles') ?>" enctype="multipart/form-data">

            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input value="<?= $rtps_trans_id ?>" name="rtps_trans_id" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Caste Certificate<br>
                    (জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন )
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
                                            <td>Applicant's Photo <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="applicant_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant's Photo">Applicant's Photo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div>
                                                    <input name="applicant_photo" id="applicant_photo" type="file" />
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Date of Birth(One of Birth Certificate/Aadhar Card/PAN/Admit Card issued by any recognized Board of Applicant<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_dob_type" class="form-control">
                                                    <option value="proof_dob">Select</option>
                                                    <option value="One of Birth Certificate">Birth Certificate</option>
                                                    <option value="Aadhar Card">Aadhar Card</option>
                                                    <option value="PAN">PAN</option>
                                                    <option value="Admit Card issued by any recognized Board of Applicant">Admit Card issued by any recognized Board of Applicant </option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_dob" name="proof_dob" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Proof of Residence(One of Permanent Resident Certificate/Aadhar Card/EPIC/Land Document/Electricity Bill,Ration Card of Applicant or Parent<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_res_type" class="form-control">
                                                    <option value="proof_res">Select</option>
                                                    <option value="PRC">Permanent Resident Certificate</option>
                                                    <option value="Aadhar Card">Aadhar Card</option>
                                                    <option value="EPIC">EPIC</option>
                                                    <option value="Land Document">Land Document</option>
                                                    <option value="Electricity Bill,Ration Card of Applicant or Parent">Electricity Bill,Ration Card of Applicant or Parent </option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_res" name="proof_res" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Caste certificate of father or Recommendation of authorized caste/tribe/community organization notified by State Government
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="caste_certificate_type" class="form-control">
                                                    <option value="caste_certificate">Select</option>
                                                    <option value="Caste Certificate of Father">Caste Certificate of Father</option>
                                                    <option value="Recommendation of authorized caste/tribe/community organization notified by State Government  ">Recommendation of authorized caste/tribe/community organization notified by State Government
                                                    </option>

                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="caste_certificate" name="caste_certificate" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>

                                        <tr>
                                            <td>Any other document(Voter List,Affidavit,Existing Caste Certificate etc)</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other document as per requirement">Any other document</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload the Scanned copy of the applicantion form<span class="text-danger">*</span></td>
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
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/bhumiputra/registration/index/' . $obj_id) ?>" class="btn btn-primary">
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