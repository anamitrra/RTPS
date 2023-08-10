<?php

if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $name_of_the_house_apartment_p = isset($dbrow->form_data->name_of_the_house_apartment_p) ? $dbrow->form_data->name_of_the_house_apartment_p : set_value("name_of_the_house_apartment_p");
    $house_no_apartment_no_p = isset($dbrow->form_data->house_no_apartment_no_p) ? $dbrow->form_data->house_no_apartment_no_p : set_value("house_no_apartment_no_p");
    $building_no_block_no_p = isset($dbrow->form_data->building_no_block_no__p) ? $dbrow->form_data->building_no_block_no__p : set_value("building_no_block_no_p");
    $address_locality_street_etc_p = isset($dbrow->form_data->address__locality_street_etc___p) ? $dbrow->form_data->address__locality_street_etc___p : set_value("address_locality_street_etc_p");
    $vill_town_ward_city_p = isset($dbrow->form_data->vill_town_ward_city_p) ? $dbrow->form_data->vill_town_ward_city_p : set_value("vill_town_ward_city_p");
    $post_office_p = isset($dbrow->form_data->post_office_p) ? $dbrow->form_data->post_office_p : set_value("post_office_p");
    $police_station_p = isset($dbrow->form_data->police_station_p) ? $dbrow->form_data->police_station_p : set_value("police_station_p");
    $pin_code_p = isset($dbrow->form_data->pin_code_p) ? $dbrow->form_data->pin_code_p : set_value("pin_code_p");
    $district_p = isset($dbrow->form_data->district_p) ? $dbrow->form_data->district_p : set_value("district_p");
    $sub_division = isset($dbrow->form_data->{'sub-division'}) ? $dbrow->form_data->{'sub-division'} : set_value("sub_division");
    $revenue_circle = isset($dbrow->form_data->revenue_circle) ? $dbrow->form_data->revenue_circle : set_value("revenue_circle");
    $residence = isset($dbrow->form_data->residence) ? $dbrow->form_data->residence : set_value("residence");
    $same_as_permanent_address = isset($dbrow->form_data->same_as_permanent_address) ? $dbrow->form_data->same_as_permanent_address : set_value("same_as_permanent_address");
    $name_of_the_house_apartment = isset($dbrow->form_data->name_of_the_house_apartment) ? $dbrow->form_data->name_of_the_house_apartment : set_value("name_of_the_house_apartment");
    $house_no_apartment_no = isset($dbrow->form_data->house_no_apartment_no) ? $dbrow->form_data->house_no_apartment_no : set_value("house_no_apartment_no");
    $building_no_block_no = isset($dbrow->form_data->building_no_block_no) ? $dbrow->form_data->building_no_block_no : set_value("building_no_block_no");
    $address_locality_street_etc = isset($dbrow->form_data->address__locality_street_etc) ? $dbrow->form_data->address__locality_street_etc : set_value("address_locality_street_etc");
    $vill_town_ward_city = isset($dbrow->form_data->vill_town_ward_city) ? $dbrow->form_data->vill_town_ward_city : set_value("vill_town_ward_city");
    $post_office = isset($dbrow->form_data->post_office) ? $dbrow->form_data->post_office : set_value("post_office");
    $police_station = isset($dbrow->form_data->police_station) ? $dbrow->form_data->police_station : set_value("police_station");
    $pin_code = isset($dbrow->form_data->pin_code) ? $dbrow->form_data->pin_code : set_value("pin_code");
    $district = isset($dbrow->form_data->district) ? $dbrow->form_data->district : set_value("district");

    $type_of_re_reg = isset($dbrow->form_data->type_of_re_reg) && !empty($dbrow->form_data->type_of_re_reg) ? $dbrow->form_data->type_of_re_reg : '';
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
        $('#district_p').on('change', function() {
            let value = $(this).val();
            $('#sub_division').html('<option value ="">Please Select</option>');
            $.ajax({
                url: '<?= base_url("spservices/employment_aadhaar_based/registration/get_subdivision") ?>',
                method: 'post',
                data: {
                    districtName: value,
                },
                dataType: 'json',
                success: function(response) {
                    let subDivisions = '<option value ="">Please Select</option>';
                    if (response.length > 0) {
                        response.forEach((res) => {
                            subDivisions += '<option value="' + res.subdivision + '">' + res.subdivision + '</option>';
                        })
                    }
                    $('#sub_division').html(subDivisions);
                }
            })
        })

        $('#sub_division').on('change', function() {
            let value = $(this).val();
            $('#revenue_circle').html('<option value ="">Please Select</option>');
            $.ajax({
                url: '<?= base_url("spservices/employment_aadhaar_based/registration/get_revenuecircle") ?>',
                method: 'post',
                data: {
                    subDivision: value,
                },
                dataType: 'json',
                success: function(response) {
                    let revenueCircles = '<option value ="">Please Select</option>';
                    if (response.length > 0) {
                        response.forEach((res) => {
                            revenueCircles += '<option value="' + res.revenue_circle + '">' + res.revenue_circle + '</option>';
                        })
                    }
                    $('#revenue_circle').html(revenueCircles);
                }
            })
        })

        $(document).on("change", ".address_same", function() {
            let checkedVal = $(this).val();
            if (checkedVal === "Yes") {
                $("#name_of_the_house_apartment").val($("#name_of_the_house_apartment_p").val());
                $("#name_of_the_house_apartment").prop("readonly", true);

                $("#house_no_apartment_no").val($("#house_no_apartment_no_p").val());
                $("#house_no_apartment_no").prop("readonly", true);

                $("#building_no_block_no").val($("#building_no_block_no_p").val());
                $("#building_no_block_no").prop("readonly", true);

                $("#address_locality_street_etc").val($("#address_locality_street_etc_p").val());
                $("#address_locality_street_etc").prop("readonly", true);

                $("#vill_town_ward_city").val($("#vill_town_ward_city_p").val());
                $("#vill_town_ward_city").prop("readonly", true);

                $("#post_office").val($("#post_office_p").val());
                $("#post_office").prop("readonly", true);

                $("#police_station").val($("#police_station_p").val());
                $("#police_station").prop("readonly", true);

                $("#pin_code").val($("#pin_code_p").val());
                $("#pin_code").prop("readonly", true);

                // $("#district").val($("#district_p").val());
                $("#district").val($("#district_p").val());
                $('#district').addClass('ro');
                $("#district").attr('readonly', true);
                // $('#district').attr("disabled", true);
            } else {
                $("#name_of_the_house_apartment").val("");
                $("#name_of_the_house_apartment").prop("readonly", false);

                $("#house_no_apartment_no").val("");
                $("#house_no_apartment_no").prop("readonly", false);

                $("#building_no_block_no").val("");
                $("#building_no_block_no").prop("readonly", false);

                $("#address_locality_street_etc").val("");
                $("#address_locality_street_etc").prop("readonly", false);

                $("#vill_town_ward_city").val("");
                $("#vill_town_ward_city").prop("readonly", false);

                $("#post_office").val("");
                $("#post_office").prop("readonly", false);

                $("#police_station").val("");
                $("#police_station").prop("readonly", false);

                $("#pin_code").val("");
                $("#pin_code").prop("readonly", false);
                // $("#district").html("<option value=''>Please Select</option>");
                $("#district").val("");
                $('#district').removeClass('ro');
                $("#district").removeAttr('readonly');
                // $('#district').attr("disabled", false);
            } //End of if else
        }); //End of onChange .address_same


    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/reregistration/submit_address') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Re-registration of employment seeker in Employment Exchange
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
                    <h5 class="text-center mt-3 text-success"><u><strong>ADDRESS SECTION</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the House/Apartment </label>
                                <input type="text" class="form-control" name="name_of_the_house_apartment_p" id="name_of_the_house_apartment_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $name_of_the_house_apartment_p ?>" />
                                <?= form_error("name_of_the_house_apartment_p") ?>
                            </div>
                            <div class="col-md-6">
                                <label>House No/Apartment No </label>
                                <input type="text" class="form-control" name="house_no_apartment_no_p" id="house_no_apartment_no_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $house_no_apartment_no_p ?>" />
                                <?= form_error("house_no_apartment_no_p") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Building No/Block No. </label>
                                <input type="text" class="form-control" name="building_no_block_no_p" id="building_no_block_no_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $building_no_block_no_p ?>" />
                                <?= form_error("building_no_block_no_p") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address (Locality/Street/etc.) </label>
                                <input type="text" class="form-control" name="address_locality_street_etc_p" id="address_locality_street_etc_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $address_locality_street_etc_p ?>" />
                                <?= form_error("address_locality_street_etc_p") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Vill/Town/Ward/City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="vill_town_ward_city_p" id="vill_town_ward_city_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $vill_town_ward_city_p ?>" />
                                <?= form_error("vill_town_ward_city_p") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post Office <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="post_office_p" id="post_office_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $post_office_p ?>" />
                                <?= form_error("post_office_p") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="police_station_p" id="police_station_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $police_station_p ?>" />
                                <?= form_error("police_station_p") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pin_code_p" id="pin_code_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?> value="<?= $pin_code_p ?>" maxlength="6" />
                                <?= form_error("pin_code_p") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <select name="district_p" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" id="district_p" <?= ($type_of_re_reg=='4')?'readonly':'' ?>>
                                    <option value="">Please Select</option>
                                    <?php foreach ($districts as $district_list) { ?>
                                        <option value="<?= $district_list->district ?>" <?= ($district_p == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                    <?php  } ?>
                                </select>
                                <?= form_error("district_p") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Division <span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" id="sub_division" <?= ($type_of_re_reg=='4')?'readonly':'' ?>>
                                    <option value="">Please Select</option>
                                    <?php if (strlen($sub_division)) {
                                        echo '<option value=' . $sub_division . ' selected>' . $sub_division . '</option>';
                                    } ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Revenue Circle <span class="text-danger">*</span> </label>
                                <select name="revenue_circle" id="revenue_circle" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" <?= ($type_of_re_reg=='4')?'readonly':'' ?>>
                                    <option value="">Please Select</option>
                                    <?php if (strlen($revenue_circle)) {
                                        echo '<option value=' . $revenue_circle . ' selected>' . $revenue_circle . '</option>';
                                    } ?>
                                </select>
                                <?= form_error("revenue_circle") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Residence <span class="text-danger">*</span> </label>
                                <select name="residence" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" <?= ($type_of_re_reg=='4')?'readonly':'' ?>>
                                    <option value="">Please Select</option>
                                    <option value="Urban" <?= ($residence === "Urban") ? 'selected' : '' ?>>Urban</option>
                                    <option value="Rural" <?= ($residence === "Rural") ? 'selected' : '' ?>>Rural</option>
                                </select>
                                <?= form_error("residence") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Communication Address </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Same as permanent address </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same <?= ($type_of_re_reg=='4')?'ro':'' ?>" type="radio" name="same_as_permanent_address" id="dcsYes" value="Yes" <?= ($same_as_permanent_address === 'Yes') ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same <?= ($type_of_re_reg=='4')?'ro':'' ?>" type="radio" name="same_as_permanent_address" id="dcsNo" value="No" <?= ($same_as_permanent_address === 'No') ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?= form_error("same_as_permanent_address") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the House/Apartment </label>
                                <input type="text" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" name="name_of_the_house_apartment" id="name_of_the_house_apartment" value="<?= $name_of_the_house_apartment ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("name_of_the_house_apartment") ?>
                            </div>
                            <div class="col-md-6">
                                <label>House No/Apartment No </label>
                                <input type="text" class="form-control <?= ($type_of_re_reg=='4')?'ro':'' ?>" name="house_no_apartment_no" id="house_no_apartment_no" value="<?= $house_no_apartment_no ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?> />
                                <?= form_error("house_no_apartment_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Building No/Block No. </label>
                                <input type="text" class="form-control" name="building_no_block_no" id="building_no_block_no" value="<?= $building_no_block_no ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("building_no_block_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address (Locality/Street/etc.) </label>
                                <input type="text" class="form-control" name="address_locality_street_etc" id="address_locality_street_etc" value="<?= $address_locality_street_etc ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("address_locality_street_etc") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Vill/Town/Ward/City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="vill_town_ward_city" id="vill_town_ward_city" value="<?= $vill_town_ward_city ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("vill_town_ward_city") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post Office <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("post_office") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="police_station" id="police_station" value="<?= $police_station ?>" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("police_station") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code ?>" maxlength="6" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>/>
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control <?= ($same_as_permanent_address === 'Yes')?'ro':'' ?>" id="district" <?= ($same_as_permanent_address === 'Yes') ? 'readonly' : '' ?>>
                                    <option value="">Please Select</option>
                                    <?php foreach ($districts as $district_list) { ?>
                                        <option value="<?= $district_list->district ?>" <?= ($district == $district_list->district) ? "selected" : "" ?>><?= $district_list->district ?></option>
                                    <?php  } ?>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-re-registration/getOldData/'.$obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>