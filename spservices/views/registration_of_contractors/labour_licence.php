
<?php
$status = null;
if($this->session->userdata("dataArr"))
{
    $data = json_decode($this->session->userdata("dataArr"));
    $status = $data->data->initiated_data->appl_status ?? '';
    $appl_name = $data->data->initiated_data->user_name ?? '';
    $appl_ref_no = $data->data->initiated_data->appl_ref_no ?? '';

    $submission_mode = $data->data->initiated_data->submission_mode ?? '';
    $submission_location = $data->data->initiated_data->submission_location ?? '';
    $payment_date = $data->data->initiated_data->payment_date ?? '';
    $reference_no = $data->data->initiated_data->reference_no ?? '';
    //pre($data);

    if(!$data->data) {
        ?>
        <script>
            alert("No record found!");
        </script>
        <?php
    }
  
}


$this->session->unset_userdata("dataArr");
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

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }

        var appl_status = '<?= $status?>';
        if(appl_status == 'D') {
        $("#resultModal").modal("show");
        }

    });
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/validateapi/submit_labour_licence') ?>" enctype="multipart/form-data">

            <div class="card shadow-sm">
                <!-- <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Search for Labour Licence
                </div> -->
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

                    <h5 class="text-center mt-3 text-success"><u><strong>Labour Licence</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h6">Verification of Labour Licence</legend>
                        <div class="row form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <label>Registration Number (e.g. CLL/2020/00004)</label>
                            <input type="text" class="form-control" name="reg_no" id="reg_no" value="" required/>
                            <?= form_error("reg_no") ?>
                        </div>
                        <div class="col-md-3"></div>
                        </div>

                    </fieldset>
                   
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Search
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->

    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/validateapi/submit_caste_cert') ?>" enctype="multipart/form-data">

            <div class="card shadow-sm">
                <!-- <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Search for Labour Licence
                </div> -->
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

                    <h5 class="text-center mt-3 text-success"><u><strong>Caste Certificate</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h6">Verification of Caste Certificate</legend>
                        <div class="row form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <label>Certificate Number (e.g. 20220622-001-00480836)</label>
                            <input type="text" class="form-control" name="caste_cert_no" id="caste_cert_no" value="" required/>
                            <?= form_error("caste_cert_no") ?>
                        </div>
                        <div class="col-md-3"></div>
                        </div>

                    </fieldset>
                   
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Search
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->

    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/validateapi/submit_gst_cert') ?>" enctype="multipart/form-data">

            <div class="card shadow-sm">
                <!-- <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Search for Labour Licence
                </div> -->
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

                    <h5 class="text-center mt-3 text-success"><u><strong>GST Certificate</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h6">Verification of GST Certificate</legend>
                        <div class="row form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <label>GST Number (e.g. 29AAICA3918J1ZE)</label>
                            <input type="text" class="form-control" name="gst_cert_no" id="gst_cert_no" value="" required/>
                            <?= form_error("gst_cert_no") ?>
                        </div>
                        <div class="col-md-3"></div>
                        </div>

                    </fieldset>
                   
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Search
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
    </main>

    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="margin:5% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
               Result
            </div>
            <div class="modal-body print-content" id="result_view" style="padding: 5px 15px;">
            <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                        <tr>
                                <td style="width:50%">Name <strong> : <?=$appl_name ?></strong> </td>
                                <td style="width:50%">Ref. No.<strong> : <?=$appl_ref_no?></strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Mode <strong> : <?=$submission_mode ?></strong> </td>
                                <td style="width:50%">Location <strong> : <?=$submission_location?></strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Payment Date <strong> : <?=$payment_date ?></strong> </td>
                                <td style="width:50%">Payment Ref No. <strong> : <?=$reference_no?></strong> </td>
                            </tr>
                        </tbody>
            </table>
               
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" class="btn btn-success" data-dismiss="modal">
                <i class="fa fa-check">OK</i>
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #resultModal-->

