<?php
$mobile_no = $this->session->userdata('mobile');
if(strlen($mobile_no) != 10)
{
    ?>
    <script type="text/javascript">
        alert('Your mobile no. not found!!!');
        location.href='<?= base_url('iservices/transactions') ?>';
        return;
    </script>
    <?php
}

if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'} ?? set_value("obj_id");
   
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

    .ro {
        pointer-events: none;
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

    });

</script>
<main class="rtps-container" style="margin-bottom: 220px;">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/upgradation/search_details') ?>" enctype="multipart/form-data">
    
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Upgradation of Contractors
                </div>
                <div class="card-body" style="padding:5px">
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error_valid') != null) { ?>
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
                    <?php } 
                    if(isset($_SESSION['fail'])){
                        unset($_SESSION['fail']);
                    }
                    ?>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5"></legend>

                        <div class="row form-group ind_div">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Registration No." name="regs_no" id="regs_no" value="<?= 'REG-CON/PWDB/2023/9684569'?>" maxlength="50" autocomplete="off"/>
                            <?= form_error("regs_no") ?>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="PAN" name="pan_card" id="pan_card" value="<?= 'FGRPM3456Y'?>" maxlength="10" autocomplete="off"/>
                            <?= form_error("pan_card") ?>
                        </div>
                        <div class="col-md-2"></div>
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
