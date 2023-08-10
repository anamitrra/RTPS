<?php
if ($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $service_name = $dbrow->service_name ?? '';
    $service_id = $dbrow->service_id;
    $doctype = $dbrow->doctype;
    $description = $dbrow->description;
} else {
    $obj_id = null;
    $service_name = set_value("service_name");
    $service_id = set_value("service_id");
    $doctype = set_value("doctype");
    $description = set_value("description");
}
?>
<link href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#dtbl").DataTable({
            "lengthMenu": [
                [10, 50, 100, 200, 500],
                [10, 50, 100, 200, 500]
            ]
        });
    });
</script>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }

    li {
        font-size: 14px;
        line-height: 24px;
    }

    div.dataTables_filter input {
        width: 88% !important;
        vertical-align: middle !important;
        text-align: right !important;
    }
</style>
<main class="rtps-container">
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Circle offices</div>
            <div class="card-body">
                <?php
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
                <form id="myfrm" method="POST" action="<?= base_url('spservices/digilockermaster/service_mapping/submit') ?>">
                    <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5">Service mapping form</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Service Name<span class="text-danger">*</span></label>
                                <input name="service_name" value="<?= $service_name ?>" class="form-control" type="text" required />
                                <?= form_error("service_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Service ID<span class="text-danger">*</span></label>
                                <input name="service_id" value="<?= $service_id ?>" class="form-control" type="text" required />
                                <?= form_error("service_id") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>DocType <span class="text-danger">*</span> <small class="text-danger">(as per digilocker)</small></label>
                                <input name="doctype" value="<?= $doctype ?>" class="form-control" type="text" required />
                                <?= form_error("doctype") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>DocType Description <span class="text-danger">*</span> <small class="text-danger">(as per digilocker)</small></label>
                                <input name="description" value="<?= $description ?>" class="form-control" type="text" required />
                                <?= form_error("description") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success frmbtn" type="submit">
                                    <i class="fa fa-check"></i> Submit
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>

                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Digilocker scheduler service list</legend>
                    <div class="row">
                        <div class="col-md-12 table-responsive pt-2">
                            <table id="dtbl" class="table table-bordered table-striped">
                                <thead class="table-info">
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Service Id</th>
                                        <th>Doc Type</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($mapping_data) {
                                        $i = 1;
                                        foreach ($mapping_data as $rows) { ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $rows->service_name ?? '' ?></td>
                                                <td><?= $rows->service_id ?></td>
                                                <td><?= $rows->doctype ?></td>
                                                <td><?= $rows->description ?></td>
                                                <td>
                                                    <a class="btn btn-warning btn-sm btn-block" href="<?= base_url('spservices/digilockermaster/service_mapping/index/' . $rows->_id->{'$id'}) ?>"><i class="fa fa-edit"></i> Edit</a>
                                                    <a class="btn btn-danger btn-sm btn-block" href="<?= base_url('spservices/digilockermaster/service_mapping/delete/' . $rows->_id->{'$id'}) ?>">Delete</a>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</main>