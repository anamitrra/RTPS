<style>
    .cd {
        color: darkcyan;
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".frmbtn", function(e) {
            e.preventDefault();
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to reinitiate the application ?";
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
                        $(".alert").fadeOut("slow");
                        $("#myfrm").submit();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>
<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">



            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Manually Initiate Edistrict Application</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title cd">Edistrict Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="padding:5px">
                        <?php if ($this->session->flashdata('success') != null) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <?php if ($this->session->flashdata('error') != null) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        <form id="myfrm" action="<?= base_url("iservices/admin/edistrict/manually-initiate") ?>" method="post">
                            <div class="row">
                                <div class="col-lg-3 col-3">
                                    <select name="service_id" class="form-control">
                                        <option value="">Select Service</option>
                                        <option value="CASTE">Caste Certificate</option>
                                        <option value="INC">Income Certificate</option>
                                        <option value="SCC">Senior Citizen Certificate</option>
                                        <option value="NOK">Next of Kin Certificate</option>
                                        <option value="PDBR">Delayed Birth Certificate</option>
                                        <option value="PDDR">Delayed Death Certificate</option>
                                        <option value="BAK">Bakijai Certificate</option>
                                        <option value="PRC">Permanent Resident Certificate</option>
                                        <option value="NCL">Non Creamy Layer Certificate</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-3">
                                    <input type="text" placeholder="eDistrict Application Ref. No" class="form-control" name="edistrict_ref_no" />
                                </div>
                                <div class="col-lg-3 col-3">
                                    <select name="submission_type" class="form-control">
                                        <option value="">Select Submission Type</option>
                                        <option value="submitted">Initial Form Submission</option>
                                        <option value="QA">Intermediate Query Submission</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-3">
                                    <button class="btn btn-sm btn-primary frmbtn" id="SAVE">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>