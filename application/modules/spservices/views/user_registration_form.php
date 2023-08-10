<?php
$depts = $this->depts_model->get_rows(array("status"=>1));
$empCode = $this->config->item('emp_exc_code');
$conRegCodes = $this->config->item('con_reg_codes');
$services = $this->services_model->get_rows(array("status" => 1));
$districts = $this->districts_model->get_rows(array("country_id" => 1));

$title = "New User Registration Form";
$obj_id = null;
$user_services = set_value("user_services");
$service_code = '';
$dept_info = set_value("dept_info");
$deptInfo = json_decode(htmlspecialchars_decode(html_entity_decode($dept_info)));
$dept_code = $deptInfo->dept_code ?? '';

$zone_info = set_value("zone_info");
$zoneInfo = json_decode(htmlspecialchars_decode(html_entity_decode($zone_info)));
$zone_name = $zoneInfo->zone_name ?? '';

$zone_circle = set_value("zone_circle");

$user_roles = array();
$role_code = '';
$role_name = 'Select a role';
$district_id = '';
$user_fullname = set_value("user_fullname");
$mobile_number = set_value("mobile_number");
$email_id = set_value("email_id");
$login_username = set_value("login_username");
$status = null;
?>
<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<script src="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js") ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#user_services').multiselect({
            columns: 1,
            search:true,
            texts: {
                placeholder: 'Please select service(s)'
            },
            onControlClose: function(element){
                var userServices = element.value;
                if(userServices.length === 0) {
                    //alert("Please select a service");
                } else {
                    var user_services = $.parseJSON(userServices);
                    getRoles(user_services.service_code);

                    var deptInfo = $("#dept_info").val();
                    var dept_code = $.parseJSON(deptInfo).dept_code;
                    var conRegCodes = '<?=json_encode($conRegCodes)?>';//console.log(conRegCodes);
                    if(conRegCodes.includes(user_services.service_code)) {
                        $("#zones_div").show();
                        getZones(dept_code);
                    } else {
                         $("#zones_div").hide();
                    }//End of if else                    
                }//End of if else
            }//End of onControlClose()
        });//End of multiselect #user_services
        
        $(document).on("change", "#dept_info", function(){
            var deptInfo = $(this).val();
            if(deptInfo.length > 10) {
                var dept_code = $.parseJSON(deptInfo).dept_code;
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/userregistration/get_services')?>",
                    data: {"dept_code": dept_code},
                    dataType:'json',
                    beforeSend:function(){
                        //$("#services_dropdown").html('<select id="" name="" class="form-control"><option value="">Please wait...</option></select>');
                    },
                    success:function(res){
                        if(res.length) {
                            $("#user_services").empty();
                            $.each(res, function (index, item) {
                                $("#user_services").append($('<option></option>').val(item.service_obj).html(item.service_name));
                            });
                            $('select[multiple]').multiselect('reload');
                        } else {
                            alert("No records found");
                        }//End of if else
                    }
                });
            } else {
               //alert("Please select a valid department");
            }//End of if else
        });//End of onChange #dept_info
        
        $(document).on("change", "#zone_info", function(){
            var zoneInfo = $(this).val();
            if(zoneInfo.length > 10) {
                var zone_code = $.parseJSON(zoneInfo).zone_code;
                $("#zonecircles_div").show();           
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/userregistration/get_zonecircles')?>",
                    data: {"zone_code": zone_code},
                    beforeSend:function(){
                        $("#zonecircles_dropdown").html('<select id="zone_info" name="zone_info" class="form-control"><option value="">Please wait...</option></select>');
                    },
                    success:function(res){
                        $("#zonecircles_dropdown").html(res);
                    }
                });
            } else {
                $("#zonecircles_div").hide(); 
            }//End of if else
        });//End of onChange #zone_info
        
        var getRoles = function(service_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/userregistration/get_roles')?>",
                data: {"service_code": service_code},
                beforeSend:function(){
                    $("#roles_div").html("Loading...");
                },
                success:function(res){
                    $("#roles_div").html(res);
                }
            });
        }; //End of getRoles()
        
        var getZones = function(dept_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/userregistration/get_zones')?>",
                data: {"dept_code": dept_code},
                beforeSend:function(){
                    $("#zones_dropdown").html('<select id="zone_info" name="zone_info" class="form-control"><option value="">Please wait...</option></select>');
                },
                success:function(res){
                    $("#zones_dropdown").html(res);
                }
            });
        }; //End of getZones()
    });
</script>

<div class="container content-wrapper mt-2 py-3">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php }
                if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php } ?>
                <form method="POST" action="<?= base_url('spservices/userregistration/submit') ?>">
                    <input name="obj_id" value="<?= $obj_id ?>" type="hidden" />
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title m-0 p-0 text-center text-white"><?= $title ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Department<span class="text-danger">*</span> </label>
                                    <select name="dept_info" id="dept_info" class="form-control">
                                        <option value="">Select a department</option>
                                        <option value='{"dept_code":"PHED", "dept_name":"Public Health Engineering Department"}' <?= ($dept_code === 'PHED') ? 'selected' : '' ?>>Public Health Engineering Department</option>
                                        <option value='{"dept_code":"PWDB", "dept_name":"Assam Public Works (Building) Department"}' <?= ($dept_code === 'PWDB') ? 'selected' : '' ?>>Assam Public Works (Building) Department</option>
                                        <option value='{"dept_code":"WRD", "dept_name":"Water Resources  Department"}' <?= ($dept_code === 'WRD') ? 'selected' : '' ?>>Water Resources Department</option>
                                        <?php /*if($depts) {
                                            foreach($depts as $dept) {
                                                $isDeptSel = ($dept->dept_code === $dept_code)?'selected':'';
                                                $deptObj = json_encode(array("dept_code"=>$dept->dept_code, "dept_name" => $dept->dept_name));
                                                echo "<option value='{$deptObj}' {$isDeptSel}>{$dept->dept_name}</option>";
                                            }//End of foreach()
                                        }//End of if*/ ?>
                                    </select>
                                    <?= form_error("dept_info") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Service<span class="text-danger">*</span> </label>
                                    <select name="user_services[]" id="user_services" class="form-control" multiple='multiple'>
                                        <?php if ($services) {
                                            foreach ($services as $service) {
                                                $isSel = in_array($service->service_code, $serviceCodes) ? 'selected' : ''; //($service->service_code===$service_code)?'selected':'';
                                                $serviceObj = json_encode(array("service_code" => $service->service_code, "service_name" => $service->service_name)); ?>
                                                <option value='<?= $serviceObj ?>' <?= $isSel ?>><?= $service->service_name ?></option>
                                        <?php } //End of foreach()
                                        } //End of if 
                                        ?>
                                    </select>
                                    <?= form_error("user_services") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Role<span class="text-danger">*</span> </label>
                                    <div id="roles_div">
                                        <select name="user_roles" id="user_roles" class="form-control">
                                            <option value='<?= json_encode($user_roles) ?>'><?= $role_name ?></option>
                                        </select>
                                    </div>
                                    <?= form_error("user_roles") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input class="form-control" name="user_fullname" value="<?= $user_fullname ?>" maxlength="100" type="text" />
                                    <?= form_error("user_fullname") ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 form-group">
                                    <label>Mobile number<span class="text-danger">*</span></label>
                                    <input class="form-control" name="mobile_number" value="<?= $mobile_number ?>" maxlength="10" type="text" />
                                    <?= form_error("mobile_number") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email id<span class="text-danger">*</span></label>
                                    <input class="form-control" name="email_id" value="<?= $email_id ?>" maxlength="100" type="text" />
                                    <?= form_error("email_id") ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 form-group" id="zones_div" style="display:<?= strlen($zone_name) ? '' : 'none' ?>">
                                    <label>Zone<span class="text-danger">*</span> </label>
                                    <div id="zones_dropdown">
                                        <select id="zone_info" name="zone_info" class="form-control">
                                            <option value='<?= json_encode($zone_info) ?>'><?= strlen($zone_name) ? $zone_name : 'Select' ?></option>
                                        </select>
                                    </div>
                                    <?= form_error("zone_info") ?>
                                </div>
                                <div class="col-md-6 form-group" id="zonecircles_div" style="display:<?= strlen($zone_circle) ? '' : 'none' ?>">
                                    <label>Circle</label>
                                    <div id="zonecircles_dropdown">
                                        <select id="zone_circle" name="zone_circle" class="form-control">
                                            <option value="<?= $zone_circle ?>"><?= strlen($zone_circle) ? $zone_circle : 'Select a circle' ?></option>
                                        </select>
                                    </div>
                                    <?= form_error("zone_circle") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Login User ID<span class="text-danger">*</span></label>
                                    <input class="form-control" name="login_username" value="<?= $login_username ?>" maxlength="100" type="text" />
                                    <?= form_error("login_username") ?>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Login password<span class="text-danger">*</span></label>
                                    <input class="form-control" name="login_password" value="" maxlength="100" type="password" />
                                    <?= form_error("login_password") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Confirm login password<span class="text-danger">*</span></label>
                                    <input class="form-control" name="login_password_conf" value="" maxlength="100" type="password" />
                                    <?= form_error("login_password_conf") ?>
                                </div>
                            </div>
                        </div>
                        <!--End of .card-body-->

                        <div class="card-footer text-center">
                            <button class="btn btn-danger" type="reset">
                                <i class="fa fa-refresh"></i> RESET
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-angle-double-right"></i> SUBMIT
                            </button>
                        </div>
                        <!--End of .card-footer-->
                    </div>
                    <!--End of .card-->
                </form>
            </div>
            <!--End of .col-md-12-->
        </div>
        <!--End of .row-->
    </section>
</div>