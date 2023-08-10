<?php

    //$obj_id = $dbrow->_id->{'$id'};
    //pre($posted_data);
    $obj_id = null;
    if ($dbrow) {
        $obj_id = $dbrow->_id->{'$id'};
        $applicant_type = $dbrow->form_data->applicant_type;
        if($applicant_type == 'Individual') {
        $house_ward_no = isset($dbrow->form_data->communication_address->house_ward_no) ? $dbrow->form_data->communication_address->house_ward_no : set_value("house_ward_no");
        $lane_road_loc = isset($dbrow->form_data->communication_address->lane_road_loc) ? $dbrow->form_data->communication_address->lane_road_loc : set_value("lane_road_loc");
        $vill_town_city = isset($dbrow->form_data->communication_address->vill_town_city) ? $dbrow->form_data->communication_address->vill_town_city : set_value("vill_town_city");
        $post_office = isset($dbrow->form_data->communication_address->post_office) ? $dbrow->form_data->communication_address->post_office : set_value("post_office");
        $pol_station = isset($dbrow->form_data->communication_address->pol_station) ? $dbrow->form_data->communication_address->pol_station : set_value("pol_station");
        $pin_code = isset($dbrow->form_data->communication_address->pin_code) ? $dbrow->form_data->communication_address->pin_code : set_value("pin_code");
        $district = isset($dbrow->form_data->communication_address->district) ? $dbrow->form_data->communication_address->district : set_value("district");

        $p_house_ward_no = isset($dbrow->form_data->permanent_address->p_house_ward_no) ? $dbrow->form_data->permanent_address->p_house_ward_no : set_value("p_house_ward_no");
        $p_lane_road_loc = isset($dbrow->form_data->permanent_address->p_lane_road_loc) ? $dbrow->form_data->permanent_address->p_lane_road_loc : set_value("p_lane_road_loc");
        $p_vill_town_city = isset($dbrow->form_data->permanent_address->p_vill_town_city) ? $dbrow->form_data->permanent_address->p_vill_town_city : set_value("p_vill_town_city");
        $p_post_office = isset($dbrow->form_data->permanent_address->p_post_office) ? $dbrow->form_data->permanent_address->p_post_office : set_value("p_post_office");
        $p_pol_station = isset($dbrow->form_data->permanent_address->p_pol_station) ? $dbrow->form_data->permanent_address->p_pol_station : set_value("p_pol_station");
        $p_pin_code = isset($dbrow->form_data->permanent_address->p_pin_code) ? $dbrow->form_data->permanent_address->p_pin_code : set_value("p_pin_code");
        $p_district = isset($dbrow->form_data->permanent_address->p_district) ? $dbrow->form_data->permanent_address->p_district : set_value("p_district");
        } 
        else {
        $house_ward_no = isset($dbrow->form_data->authorised_signatory_address->house_ward_no) ? $dbrow->form_data->authorised_signatory_address->house_ward_no : set_value("house_ward_no");
        $lane_road_loc = isset($dbrow->form_data->authorised_signatory_address->lane_road_loc) ? $dbrow->form_data->authorised_signatory_address->lane_road_loc : set_value("lane_road_loc");
        $vill_town_city = isset($dbrow->form_data->authorised_signatory_address->vill_town_city) ? $dbrow->form_data->authorised_signatory_address->vill_town_city : set_value("vill_town_city");
        $post_office = isset($dbrow->form_data->authorised_signatory_address->post_office) ? $dbrow->form_data->authorised_signatory_address->post_office : set_value("post_office");
        $pol_station = isset($dbrow->form_data->authorised_signatory_address->pol_station) ? $dbrow->form_data->authorised_signatory_address->pol_station : set_value("pol_station");
        $pin_code = isset($dbrow->form_data->authorised_signatory_address->pin_code) ? $dbrow->form_data->authorised_signatory_address->pin_code : set_value("pin_code");
        $district = isset($dbrow->form_data->authorised_signatory_address->district) ? $dbrow->form_data->authorised_signatory_address->district : set_value("district");

        $house_ward_no_ro = isset($dbrow->form_data->regd_address->house_ward_no_ro) ? $dbrow->form_data->regd_address->house_ward_no_ro : set_value("house_ward_no_ro");
        $lane_road_loc_ro = isset($dbrow->form_data->regd_address->lane_road_loc_ro) ? $dbrow->form_data->regd_address->lane_road_loc_ro : set_value("lane_road_loc_ro");
        $vill_town_city_ro = isset($dbrow->form_data->regd_address->vill_town_city_ro) ? $dbrow->form_data->regd_address->vill_town_city_ro : set_value("vill_town_city_ro");
        $post_office_ro = isset($dbrow->form_data->regd_address->post_office_ro) ? $dbrow->form_data->regd_address->post_office_ro : set_value("post_office_ro");
        $pol_station_ro = isset($dbrow->form_data->regd_address->pol_station_ro) ? $dbrow->form_data->regd_address->pol_station_ro : set_value("pol_station_ro");
        $pin_code_ro = isset($dbrow->form_data->regd_address->pin_code_ro) ? $dbrow->form_data->regd_address->pin_code_ro : set_value("pin_code_ro");
        $district_ro = isset($dbrow->form_data->regd_address->district_ro) ? $dbrow->form_data->regd_address->district_ro : set_value("district_ro");

        $addresses_of_all_owners = isset($dbrow->form_data->addresses_of_all_owners) ? $dbrow->form_data->addresses_of_all_owners : set_value("");
        }
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

        $(document).on("click", "#add_addresses_row", function() {
            let totRows = $('#add_addresses_tbl tr').length;
            var trow = `<tr>
                        <td><input name="house_no_ownership[]" class="form-control" type="text" /></td>
                        <td><input name="lane_road_ownership[]" class="form-control" type="text" /></td>
                        <td>
                        <input name="vill_town_city_ownership[]" class="form-control" type="text" />
                        </td>
                        <td><input name="post_office_ownership[]" class="form-control" type="text" /></td>
                        <td><input name="police_station_ownership[]" class="form-control" type="text" /></td>
                        <td>
                        <select name="district_ownership[]" class="form-control">
                        <option value="">Please Select</option>
                        <?php 
                        foreach ($districts as $district_list) { ?>
                            <option value="<?= $district_list->district ?>" ><?= $district_list->district ?></option>
                        <?php  } ?>
                        </select>
                        </td>
                        <td><input name="pin_code_ownership[]" class="form-control" type="text" /></td>
                        <td style="text-align:center"><button class="btn btn-danger delete_other_addresses_row" type="button"><i class="fa fa-trash-o"></i></button></td>
                    </tr>`;
            if (totRows <= 5) {
                $('#add_addresses_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 5 records allowed');
            }
        });
        $(document).on("click", ".delete_other_addresses_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }

    $('.address_same').on('change', function() {
        let checkedVal = $(this).val();
        if (checkedVal === "Yes") {
                $("#p_house_ward_no").val($("#house_ward_no").val());
                $("#p_house_ward_no").prop("readonly", true);

                $("#p_lane_road_loc").val($("#lane_road_loc").val());
                $("#p_lane_road_loc").prop("readonly", true);

                $("#p_vill_town_city").val($("#vill_town_city").val());
                $("#p_vill_town_city").prop("readonly", true);

                $("#p_post_office").val($("#post_office").val());
                $("#p_post_office").prop("readonly", true);

                $("#p_pol_station").val($("#pol_station").val());
                $("#p_pol_station").prop("readonly", true);

                $("#p_district").val($("#district").val());
                $("#p_district").prop("readonly", true);

                $("#p_pin_code").val($("#pin_code").val());
                $("#p_pin_code").prop("readonly", true);

            } else {
                $("#p_house_ward_no").val("");
                $("#p_house_ward_no").prop("readonly", false);

                $("#p_lane_road_loc").val("");
                $("#p_lane_road_loc").prop("readonly", false);

                $("#p_vill_town_city").val("");
                $("#p_vill_town_city").prop("readonly", false);

                $("#p_post_office").val("");
                $("#p_post_office").prop("readonly", false);

                $("#p_pol_station").val("");
                $("#p_pol_station").prop("readonly", false);

                $("#p_district").val("");
                $("#p_district").prop("readonly", false);

                $("#p_pin_code").val("");
                $("#p_pin_code").prop("readonly", false);

            } //End of if else
    });

    $('.address_same1').on('change', function() {
        let checkedVal = $(this).val();
        if (checkedVal === "Yes") {
                $("#house_ward_no_ro").val($("#house_ward_no").val());
                $("#house_ward_no_ro").prop("readonly", true);

                $("#lane_road_loc_ro").val($("#lane_road_loc").val());
                $("#lane_road_loc_ro").prop("readonly", true);

                $("#vill_town_city_ro").val($("#vill_town_city").val());
                $("#vill_town_city_ro").prop("readonly", true);

                $("#post_office_ro").val($("#post_office").val());
                $("#post_office_ro").prop("readonly", true);

                $("#pol_station_ro").val($("#pol_station").val());
                $("#pol_station_ro").prop("readonly", true);

                $("#district_ro").val($("#district").val());
                $("#district_ro").prop("readonly", true);

                $("#pin_code_ro").val($("#pin_code").val());
                $("#pin_code_ro").prop("readonly", true);

            } else {
                $("#house_ward_no_ro").val("");
                $("#house_ward_no_ro").prop("readonly", false);

                $("#lane_road_loc_ro").val("");
                $("#lane_road_loc_ro").prop("readonly", false);

                $("#vill_town_city_ro").val("");
                $("#vill_town_city_ro").prop("readonly", false);

                $("#post_office_ro").val("");
                $("#post_office_ro").prop("readonly", false);

                $("#pol_station_ro").val("");
                $("#pol_station_ro").prop("readonly", false);

                $("#district_ro").val("");
                $("#district_ro").prop("readonly", false);

                $("#pin_code_ro").val("");
                $("#pin_code_ro").prop("readonly", false);

            } //End of if else
    });


    });

$(document).ready(function() {

    $('#myfrm input, #myfrm select').each(
        function(index){ 
            var input = $(this);
            input.addClass('ro');
            input.attr('readonly', true);
        }
    );
});
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/upgradation/submit_address_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="applicant_type" name="applicant_type" value="<?= $applicant_type ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Upgradation of Contractors
                </div>
                <div class="card-body" style="padding:5px">
                    <?php 
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <h5 class="text-center mt-3 text-success"><u><strong>ADDRESS</strong></u></h5>
                    <?php if($applicant_type == 'Individual') {
                            $lan_str = '';
                        } else {
                            $lan_str = '(Contact Person/ Authorised Signatory)';
                        } ?>
                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h6">Communication address <?= $lan_str ?></legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No. / Ward No. </label>
                                <input type="text" class="form-control" name="house_ward_no" id="house_ward_no" value="<?= $house_ward_no?>" />
                                <?= form_error("house_ward_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Lane / Road / Locality </label>
                                <input type="text" class="form-control" name="lane_road_loc" id="lane_road_loc" value="<?= $lane_road_loc?>" />
                                <?= form_error("lane_road_loc") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village / Town / City <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="vill_town_city" id="vill_town_city" value="<?= $vill_town_city?>" />
                                <?= form_error("vill_town_city") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post office <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office?>" />
                                <?= form_error("post_office") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police station <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pol_station" id="pol_station" value="<?= $pol_station?>" />
                                <?= form_error("pol_station") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control" id="district">
                                    <option value="">Please Select</option>
                                    <?php foreach ($districts as $district_list) { ?>
                                        <option value="<?= $district_list->district ?>" <?= ($district == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                    <?php  } ?>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code?>" />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <?php //} ?>
                    <?php if($applicant_type == 'Individual') { ?>
                    <fieldset class="border border-success">
                        <legend class="h6">Permanent address</legend>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No. / Ward No. </label>
                                <input type="text" class="form-control" name="p_house_ward_no" id="p_house_ward_no" value="<?= $p_house_ward_no?>" />
                                <?= form_error("p_house_ward_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Lane / Road / Locality </label>
                                <input type="text" class="form-control" name="p_lane_road_loc" id="p_lane_road_loc" value="<?= $p_lane_road_loc?>" />
                                <?= form_error("p_lane_road_loc") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village / Town / City <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_vill_town_city" id="p_vill_town_city" value="<?= $p_vill_town_city?>" />
                                <?= form_error("p_vill_town_city") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post office <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_post_office" id="p_post_office" value="<?= $p_post_office?>" />
                                <?= form_error("p_post_office") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police station <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_pol_station" id="p_pol_station" value="<?= $p_pol_station?>" />
                                <?= form_error("p_pol_station") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <select name="p_district" class="form-control" id="p_district">
                                    <option value="">Please Select</option>
                                    <?php foreach ($districts as $district_list) { ?>
                                        <option value="<?= $district_list->district ?>" <?= ($p_district == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                    <?php  } ?>
                                </select>
                                <?= form_error("p_district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_pin_code" id="p_pin_code" value="<?= $p_pin_code?>" />
                                <?= form_error("p_pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <?php } ?>
                    <?php if($applicant_type != 'Individual') { ?>
                    <fieldset class="border border-success">
                        <legend class="h6">Address of Regd. Office (if a Partnership Firm/Company)</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Same as communication address </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same1" type="radio" name="same_as_communication_address1" id="dcsYes1" value="Yes"/>
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same1" type="radio" name="same_as_communication_address1" id="dcsNo1" value="No"/>
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?= form_error("same_as_communication_address1") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No. / Ward No. </label>
                                <input type="text" class="form-control" name="house_ward_no_ro" id="house_ward_no_ro" value="<?= $house_ward_no_ro?>" />
                                <?= form_error("house_ward_no_ro") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Lane / Road / Locality </label>
                                <input type="text" class="form-control" name="lane_road_loc_ro" id="lane_road_loc_ro" value="<?= $lane_road_loc_ro?>" />
                                <?= form_error("lane_road_loc_ro") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village / Town / City <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="vill_town_city_ro" id="vill_town_city_ro" value="<?= $vill_town_city_ro?>" />
                                <?= form_error("vill_town_city_ro") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post office <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office_ro" id="post_office_ro" value="<?= $post_office_ro?>" />
                                <?= form_error("post_office_ro") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police station <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pol_station_ro" id="pol_station_ro" value="<?= $pol_station_ro?>" />
                                <?= form_error("pol_station_ro") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <select name="district_ro" class="form-control" id="district_ro">
                                    <option value="">Please Select</option>
                                    <?php foreach ($districts as $district_list) { ?>
                                        <option value="<?= $district_list->district ?>" <?= ($district_ro == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                    <?php  } ?>
                                </select>
                                <?= form_error("district_ro") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code_ro" id="pin_code_ro" value="<?= $pin_code_ro?>" />
                                <?= form_error("pin_code_ro") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                        <legend class="h6">Partnership firm/ Company </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <label>(<span class="text-danger">*</span> Include addresses of all owners)</label>
                                <table class="table table-bordered" id="add_addresses_tbl">
                                    <thead>
                                        <tr>
                                            <th>House/ Ward No.</th>
                                            <th>Lane / Road / Locality</th>
                                            <th>Village / Town / City<span class="text-danger">*</span>
                                            <?= form_error("vill_town_city_ownership[]") ?>
                                            </th>
                                            <th>Post office<span class="text-danger">*</span>
                                            <?= form_error("post_office_ownership[]") ?>
                                            </th>
                                            <th>Police station<span class="text-danger">*</span>
                                            <?= form_error("police_station_ownership[]") ?>
                                            </th>
                                            <th>District<span class="text-danger">*</span>
                                            <?= form_error("district_ownership[]") ?>
                                            </th>
                                            <th>Pin code<span class="text-danger">*</span>
                                            <?= form_error("pin_code_ownership[]") ?>
                                            </th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                        <?php
                                        $addressesOfAllOwners = (isset($addresses_of_all_owners) && is_array($addresses_of_all_owners)) ? count($addresses_of_all_owners) : 0;

                                        if ($addressesOfAllOwners > 0) {
                                            for ($i = 0; $i < $addressesOfAllOwners; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_addresses_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_other_addresses_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                            <tr>
                                                <td><input name="house_no_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->house_no_ownership ?>"/></td>
                                                <td><input name="lane_road_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->lane_road_ownership ?>"/></td>
                                                <td>
                                                <input name="vill_town_city_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->vill_town_city_ownership ?>"/>
                                                </td>
                                                <td><input name="post_office_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->post_office_ownership ?>"/></td>
                                                <td><input name="police_station_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->police_station_ownership ?>"/></td>
                                                <td>
                                                <select name="district_ownership[]" class="form-control">
                                                <option value="">Please Select</option>
                                                <?php 
                                                $district_owner = $addresses_of_all_owners[$i]->district_ownership;
                                                foreach ($districts as $district_list) { ?>
                                                    <option value="<?= $district_list->district ?>" <?= ($district_owner == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                                <?php  } ?>
                                                </select>
                                                </td>
                                                <td><input name="pin_code_ownership[]" class="form-control" type="text" value="<?= $addresses_of_all_owners[$i]->pin_code_ownership ?>"/></td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="house_no_ownership[]" class="form-control" type="text" /></td>
                                                <td><input name="lane_road_ownership[]" class="form-control" type="text" /></td>
                                                <td>
                                                <input name="vill_town_city_ownership[]" class="form-control" type="text" />
                                                </td>
                                                <td><input name="post_office_ownership[]" class="form-control" type="text" />
                                                </td>
                                                <td><input name="police_station_ownership[]" class="form-control" type="text" />
                                                </td>
                                                <td>
                                                <select name="district_ownership[]" class="form-control">
                                                <option value="">Please Select</option>
                                                <?php 
                                                foreach ($districts as $district_list) { ?>
                                                    <option value="<?= $district_list->district ?>" ><?= $district_list->district ?></option>
                                                <?php  } ?>
                                                </select>
                                                </td>
                                                <td><input name="pin_code_ownership[]" class="form-control" type="text" />
                                                </td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_addresses_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } //End of if else  
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                    <?php } ?>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/upgradation_of_contractors/personal-details/'. $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>
