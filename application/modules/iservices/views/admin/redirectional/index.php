

<?php 
$service_id = set_value("service_id");
$applicant_name = set_value("applicant_name");
$mobile = set_value("mobile");
$address1 = set_value("address1");
$address2 = set_value("address2");
$address3 = set_value("address3");
$pin_code = set_value("pin_code");
$amount = set_value("amount");
$no_printing_page = set_value("no_printing_page");
$no_scanning_page = set_value("no_scanning_page");
$service_name = set_value("service_name");
$district = set_value("district");
$office = set_value("office");
$app_ref_no = set_value("app_ref_no");

?>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on("change", "#service_id", function() {
            $("#service_name").val($(this).find("option:selected").text());

        });
        $(document).on("change", "#district", function() {
            // $("#district").val($(this).find("option:selected").text());
            let district_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?=base_url('iservices/admin/redirectional_payment/get_office')?>",
                data: { "district":district_id},
                beforeSend:function(){
                    $("#office_div").html("Loading");
                },
                success:function(res){
                    $("#office_div").html(res);
                }
            });
        });


    })
</script>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4">
                    <div class="card-body">
                        <h2 class="transport-appl-header">Payment for re-directional service</h2>
                        <form action="<?=base_url("iservices/admin/Redirectional_payment/initiate")?>" method="post">
                            <hr />
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Service Name<span class="text-danger">*</span></label>
                                    <select name="service_id" id="service_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php if (!empty($services)) {
                                            foreach ($services as $ser) { ?>
                                                <option <?=$service_id == $ser->service_id ? "selected":'' ?> value="<?= $ser->service_id ?>"><?= $ser->service_name->en ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <?= form_error("service_id") ?>
                                </div>
                                <input type="hidden" name="service_name" id="service_name" value="<?=$service_name?>" />
                            </div>
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Applicant Name. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder=""   value="<?=$applicant_name?>"/>
                                    <?= form_error("applicant_name") ?>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label for="user_mobile">Applicant Mobile. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="<?=$mobile?>" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$"  />
                                    <?= form_error("mobile") ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Application Ref No. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="app_ref_no" id="app_ref_no" placeholder=""   value="<?=$app_ref_no?>"/>
                                    <?= form_error("app_ref_no") ?>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">No. of Printing Page <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_printing_page" id="no_printing_page"  value="<?=$no_printing_page?>"  placeholder=""  />
                                    <?= form_error("no_printing_page") ?>
                                </div>
                               
                            </div>
                            <div class="row">
                               
                               

                               <div class="form-group  col-sm-6">
                                   <label for="applicant_name">No. of Scanning Page </label>
                                   <input type="text" class="form-control" name="no_scanning_page" id="no_scanning_page"  value="<?=$no_scanning_page?>" placeholder="" />
                                   <?= form_error("no_scanning_page") ?>
                               </div>

                           </div>

                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address1" id="address1" placeholder="" value="<?=$address1?>"   />
                                    <?= form_error("address1") ?>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Address Line 2. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address2" id="address2" value="<?=$address2?>"   placeholder=""  />
                                    <?= form_error("address2") ?>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">District </label>
                                    <select name="district" id="district" class="form-control">
                                        <option value="">Select</option>
                                        <?php if (!empty($district_list)) {
                                            foreach ($district_list as $dist) { ?>
                                                <option <?=$district == $dist ? "selected":'' ?> value="<?= $dist ?>"><?= $dist ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <?= form_error("district") ?>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Office </label>
                                    <div id="office_div">
                                        <select name="office" id="office" class="form-control">
                                            <option value="<?=$office?>"><?=strlen($office)?$office:'Select'?></option>
                                        </select>
                                     </div>
                                    <?= form_error("office") ?>
                                </div>
                            </div>

                            <div class="row">
                             
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Pin Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pin_code" id="pin_code" placeholder=""  value="<?=$pin_code?>"    pattern="\d{6}" minlength="6" maxlength="6"  />
                                    <?= form_error("pin_code") ?>
                                </div>
                              
                            </div>


                          

  


                            <button type="submit" id="procced" class="btn  btn-primary">
                                <span id="btnProccedTxt">Proceed</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

          
        </div>
    </div>
</div>