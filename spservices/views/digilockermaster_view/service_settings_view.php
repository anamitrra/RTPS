<?php
if ($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $service_name = $dbrow->serice_name ?? '';
    $api_key = $dbrow->api_key;
    $doc_type = $dbrow->doctype;
    $db = $dbrow->db;
    $collection = $dbrow->collection;
    $multiServices = isset($dbrow->multi_services) ? (array)$dbrow->multi_services : [];
    $udfs = $dbrow->udfs ? (array)$dbrow->udfs : [];
    $apiUse = $dbrow->useAPI ?? '';
    $api_url = $dbrow->api_url ?? '';
    $apiParameters = isset($dbrow->api_parameters) ? (array)$dbrow->api_parameters : [];

} else {
    $obj_id = null;
    $service_name = set_value("service_name");
    $api_key = set_value("api_key");
    $doc_type = set_value("doc_type");
    $db = set_value("db");
    $collection = set_value("collection");
    $multi_service = set_value("multi_service");
    $multiServices = [];
    $parameter_values = set_value("parameter_values");
    $mis_service_name = set_value("mis_service_name");
    $db_field_name = set_value("db_field_name");
    $digilocker_parameters = set_value("digilocker_parameters");
    $api_use = set_value("api_use");
    $api_url = set_value("api_url");
    $apiUse = '';
    $api_parameter_label = set_value("api_parameter_label");
    $digilocker_api_parameters = set_value("digilocker_api_parameters");
}
?>
<link href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#add_multiple_ser", function() {
            let totRows = $('#multiple_service_mapping tr').length;
            var trow = `<tr>
                            <td><input name="parameter_values[]" class="form-control" type="text" /></td>
                            <td><input name="mis_service_name[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_multiple_service" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#multiple_service_mapping tr:last').after(trow);
            }
        });
        $(document).on("click", ".delete_multiple_service", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_udfs", function() {
            let totRows = $('#udf_tbl tr').length;
            var trow = `<tr>
                            <td><input name="db_field_name[]" class="form-control" type="text" /></td>
                            <td><input name="udf_parameters[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_udf" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#udf_tbl tr:last').after(trow);
            }
        });
        $(document).on("click", ".delete_udf", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_api_parameters", function() {
            let totRows = $('#api_tbl tr').length;
            var trow = `<tr>
                            <td><input name="api_parameter_label[]" class="form-control" type="text" /></td>
                            <td><input name="digilocker_api_parameters[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_api_param" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#api_tbl tr:last').after(trow);
            }
        });
        $(document).on("click", ".delete_api_param", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("change", "#multi_service", function() {
            var value = $(this).val();
            if (value == 'Yes') {
                $('#multiple_service_mapping').removeClass('d-none');
            } else {
                $('#multiple_service_mapping').addClass('d-none');
            }
        });

        $(document).on("change", "#api_use", function() {
            var value = $(this).val();
            if (value == 'Yes') {
                $('.api_url_div').removeClass('d-none');
                $('#api_parameter_tbl').removeClass('d-none');
            } else {
                $('.api_url_div').addClass('d-none');
                $('#api_parameter_tbl').addClass('d-none');
            }
        });

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
            <div class="card-header bg-dark text-white">Digilocker service mapping for digilocker app</div>
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



                <form id="myfrm" method="POST" action="<?= base_url('spservices/digilockermaster/digilocker_service_settings/submit') ?>">
                    <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5">Service mapping form</legend>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Service Name<span class="text-danger">*</span></label>
                                <input name="service_name" value="<?= $service_name ?>" class="form-control" type="text" required />
                                <?= form_error("service_name") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>API Key<span class="text-danger">*</span></label>
                                <input name="api_key" value="<?= $api_key ?>" class="form-control" type="text" required />
                                <?= form_error("api_key") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>DocType<span class="text-danger">*</span></label>
                                <input name="doc_type" value="<?= $doc_type ?>" class="form-control" type="text" required />
                                <?= form_error("doc_type") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Database Name <span class="text-danger">*</span> <small class="text-danger">(as per digilocker)</small></label>
                                <input name="db" value="<?= $db ?>" class="form-control" type="text" required />
                                <?= form_error("db") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Collection Name <span class="text-danger">*</span> <small class="text-danger">(as per digilocker)</small></label>
                                <input name="collection" value="<?= $collection ?>" class="form-control" type="text" required />
                                <?= form_error("collection") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Multiple service<span class="text-danger"></label>
                                <select class="form-control" name="multi_service" id="multi_service">
                                    <option value="">Please Select</option>
                                    <option value="Yes" <?= count($multiServices) ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" <?= (count($multiServices) == 0) ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <table class="table table-bordered <?= count($multiServices) ? '' : 'd-none' ?>" id="multiple_service_mapping">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th colspan="3">Dropdown/multiple service mapping</th>
                                </tr>
                                <tr>
                                    <th>Digilocker parameter value</th>
                                    <th>Mis service Name</th>
                                    <th style="width:65px;text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $dropdown = (isset($multiServices) && is_array($multiServices)) ? count($multiServices) : 0;
                                if ($dropdown > 0) {
                                    $i = 0;
                                    foreach ($multiServices as $k => $v) {

                                        if ($i == 0) {
                                            $btn = '<button class="btn btn-info" id="add_multiple_ser" type="button"><i class="fa fa-plus-circle"></i></button>';
                                        } else {
                                            $btn = '<button class="btn btn-danger delete_multiple_service" type="button"><i class="fa fa-trash-o"></i></button>';
                                        } // End of if else 
                                ?>
                                        <tr>
                                            <td><input name="parameter_values[]" value="<?= $k ?>" class="form-control" type="text" /></td>
                                            <td><input name="mis_service_name[]" value="<?= $v ?>" class="form-control" type="text" /></td>
                                            <td><?= $btn ?></td>
                                        </tr>
                                    <?php $i++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td><input name="parameter_values[]" class="form-control" type="text" /></td>
                                        <td><input name="mis_service_name[]" class="form-control" type="text" /></td>
                                        <td style="text-align:center">
                                            <button class="btn btn-info" id="add_multiple_ser" type="button">
                                                <i class="fa fa-plus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } //End of if else  
                                ?>
                            </tbody>
                        </table>
                        <table class="table table-bordered" id="udf_tbl">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th colspan="3"><b>User Define Parameters (UDFs)</b></th>
                                </tr>
                                <tr>
                                    <th>Mis application field name</th>
                                    <th>Digilocker parameter</th>
                                    <th style="width:65px;text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $udf_count = (isset($udfs) && is_array($udfs)) ? count($udfs) : 0;
                                if ($udf_count > 0) {
                                    $i = 0;
                                    foreach ($udfs as $k => $v) {
                                        if ($i == 0) {
                                            $btn = '<button class="btn btn-info" id="add_udfs" type="button"><i class="fa fa-plus-circle"></i></button>';
                                        } else {
                                            $btn = '<button class="btn btn-danger delete_udf" type="button"><i class="fa fa-trash-o"></i></button>';
                                        } // End of if else 
                                ?>
                                        <tr>
                                            <td><input name="db_field_name[]" value="<?= $k ?>" class="form-control" type="text" /></td>
                                            <td><input name="udf_parameters[]" value="<?= $v ?>" class="form-control" type="text" /></td>
                                            <td><?= $btn ?></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td><input name="db_field_name[]" class="form-control" type="text" /></td>
                                        <td><input name="udf_parameters[]" class="form-control" type="text" /></td>
                                        <td style="text-align:center">
                                            <button class="btn btn-info" id="add_udfs" type="button">
                                                <i class="fa fa-plus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } //End of if else  
                                ?>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>API use ?</label>
                                <select class="form-control" name="api_use" id="api_use">
                                    <option value="">Please Select</option>
                                    <option value="Yes" <?= ($apiUse == true) ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" <?= ($apiUse == false) ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-8 form-group api_url_div <?= ($apiUse == true) ? '' : 'd-none' ?> ">
                                <label>API url</label>
                                <input name="api_url" value="<?= $api_url ?>" class="form-control" type="text" required />
                                <?= form_error("api_url") ?>
                            </div>
                        </div>

                        <table class="table table-bordered <?= ($apiUse == true) ? '' : 'd-none' ?>" id="api_parameter_tbl">
                            <thead>
                                <tr class="bg-warning text-white">
                                    <th colspan="3"><b>API Parameters</b></th>
                                </tr>
                                <tr>
                                    <th>API parameter label/key</th>
                                    <th>Digilocker parameter</th>
                                    <th style="width:65px;text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $apiParamCount = (isset($apiParameters) && is_array($apiParameters)) ? count($apiParameters) : 0;
                                if ($apiParamCount > 0) {
                                    $i = 0;
                                    foreach ($apiParameters as $k => $v) {
                                        if ($i == 0) {
                                            $btn = '<button class="btn btn-info" id="add_api_parameters" type="button"><i class="fa fa-plus-circle"></i></button>';
                                        } else {
                                            $btn = '<button class="btn btn-danger delete_api_param" type="button"><i class="fa fa-trash-o"></i></button>';
                                        } // End of if else 
                                ?>
                                        <tr>
                                            <td><input name="api_parameter_label[]" value="<?= $k ?>" class="form-control" type="text" /></td>
                                            <td><input name="digilocker_api_parameters[]" value="<?= $v ?>" class="form-control" type="text" /></td>
                                            <td><?= $btn ?></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td><input name="api_parameter_label[]" class="form-control" type="text" /></td>
                                        <td><input name="digilocker_api_parameters[]" class="form-control" type="text" /></td>

                                        <td style="text-align:center">
                                            <button class="btn btn-info" id="add_api_parameters" type="button">
                                                <i class="fa fa-plus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } //End of if else  
                                ?>
                            </tbody>
                        </table>

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
                                        <th>Doctype</th>
                                        <th>API Key</th>
                                        <th>DB & Collection</th>
                                        <th>UDFs</th>
                                        <th>Dropdown Values</th>
                                        <th>API</th>
                                        <th>API postfields</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($settings_data) {

                                        $i = 1;
                                        foreach ($settings_data as $rows) { ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $rows->serice_name ?? '' ?></td>
                                                <td><?= $rows->doctype ?></td>
                                                <td><?= $rows->api_key ?></td>
                                                <td><?= $rows->db ?><br>
                                                    <?= $rows->collection ?></td>
                                                <td class=""><b>
                                                        <?php foreach ($rows->udfs as $k => $v) {
                                                            echo $k . ' => ' . $v;
                                                            echo '<br>';
                                                        } ?></b>
                                                </td>
                                                <td class=""><b>
                                                        <?php $multi = $rows->multi_services ?? [];
                                                        foreach ($multi as $k => $v) {
                                                            echo $k . ' => ' . $v;
                                                            echo '<br>';
                                                        } ?></b>
                                                </td>
                                                <td><?= $rows->useAPI ? $rows->api_url : '' ?></td>
                                                <td><b>
                                                        <?php $api_parameter = $rows->api_parameters ?? [];
                                                        foreach ($api_parameter as $k => $v) {
                                                            echo $k . ' => ' . $v;
                                                            echo '<br>';
                                                        } ?></b>
                                                </td>
                                                <td>
                                                    <a class="text-info" href="<?= base_url('spservices/digilockermaster/digilocker_service_settings/index/' . $rows->_id->{'$id'}) ?>"><i class="fa fa-edit"></i></a>
                                                    <a class="text-danger" href="<?= base_url('spservices/digilockermaster/digilocker_service_settings/delete/' . $rows->_id->{'$id'}) ?>"><i class="fas fa-trash"></i></a>
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