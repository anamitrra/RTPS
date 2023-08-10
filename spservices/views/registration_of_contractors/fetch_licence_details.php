<?php
//pre($this->session->userdata('applicant_type'));
$applicant_type = $this->session->userdata('applicant_type');
$obj_id = null;
$deptt_name = null;
$category = null;
$applicant_type = null;
$applicant_gender = null;
$caste = null;
$religion = null;
$zone = null;
$circle = null;
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
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#get_data", function() {
            let panNumber = $("#pan_no").val();
            let licenceNo = $("#licence_no").val();
            if (panNumber != '' && licenceNo != '') {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/registration_of_contractors/renewal/get_old_data') ?>",
                    data: {
                        "licence_no": licenceNo,
                        "pan_number": panNumber,
                    },
                    beforeSend: function() {
                        $('.response_div').html('<p class="text-center mt-2"><strong>Please wait... while we are checking your details.</strong></p>')
                    },
                    success: function(res) {
                        if (res.status) {
                            $('.response_div').html(res.msg)
                        }
                    }
                });
            } else {
                alert('empty');
            }
        })
    });

    function saveAndProceed(element) {
        location.href = "<?= base_url('spservices/registration_of_contractors/renewal/save_old_data/') ?>" + element.value;
    }
</script>
<main class="rtps-container">
    <div class="container my-2">
        <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for Renewal of Registration of Contractors
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
                <h5 class="text-center mt-3 mb-3 text-success"><u><strong>Previous Registration Details</strong></u></h5>
                <fieldset class="border border-success" style="margin-top:0px">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label>Licence Number <span class="text-danger">*</span> </label>
                            <input type="text" name="licence_no" id="licence_no" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>PAN Number <span class="text-danger">*</span> </label>
                            <input type="text" name="pan_no" id="pan_no" class="form-control">
                        </div>
                    </div>
                </fieldset>
                <div class="text-center mb-2">
                    <button class="btn btn-success" id="get_data" type="button">
                        <i class="fa fa-search"></i> Get Data
                    </button>
                </div>
                <div class="response_div"></div>
            </div><!--End of .card-body -->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>