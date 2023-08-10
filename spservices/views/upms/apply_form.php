<?php
$services = $this->services_model->get_rows(array("status"=>1));
$districts = $this->districts_model->get_rows(array("country_id"=>1));
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $mobile_number = $dbrow->form_data->mobile_number;
    $email_id = $dbrow->email_id;
    $service_code = $dbrow->service_code??'';
    $status = $dbrow->status;
} else {
    $obj_id = null;
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("mobile_number");
    $email_id = set_value("email_id");
    $serviceDetails = json_decode(html_entity_decode(set_value("service_details")));
    $service_code = $serviceDetails->service_code??'';
    $status = null;
}//End of if else ?>
<style type="text/css">
    .radio-container {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 7px 10px;
        background: #fff;
    }
</style>
<div class="container my-2">
    <?php if ($this->session->flashdata('flash_msg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flash_msg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } ?>            
    <form method="POST" action="<?=base_url('spservices/upms/site/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white">Test application form</span>
                <a href="<?=base_url('spservices/upms/login')?>" class="pull-right font-weight-bold">OFFICIAL LOGIN</a>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name of the applicant <span class="text-danger">*</span></label>
                        <input class="form-control" name="applicant_name" value="<?=$applicant_name?>" maxlength="100" autocomplete="off" type="text" />
                        <?= form_error("applicant_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label for="applicant_gender">Gender </label>
                        <div class="radio-container">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="applicant_gender" id="Male" value="Male" <?= ($applicant_gender === 'Male') ? 'checked' : '' ?> />
                                <label class="form-check-label" for="Male">
                                    Male
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="applicant_gender" id="Female" value="Female" <?= ($applicant_gender === 'Female') ? 'checked' : '' ?> />
                                <label class="form-check-label" for="Female">
                                    Female
                                </label>
                            </div>
                        </div><!--End of .radio-container -->
                        <?= form_error("applicant_gender") ?>
                    </div>
                </div>               
                
                <div class="row mt-2">     
                    <div class="col-md-6 form-group">
                        <label>Mobile number</label>
                        <input class="form-control" name="mobile_number" value="<?=$mobile_number?>" maxlength="30" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <?= form_error("mobile_number") ?>
                    </div>   
                    <div class="col-md-6 form-group">
                        <label>Email id</label>
                        <input class="form-control" name="email_id" value="<?=$email_id?>" maxlength="30" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <?= form_error("email_id") ?>
                    </div> 
                </div>
                
                <div class="row mt-2">                  
                    <div class="col-md-12 form-group">
                        <label>Service<span class="text-danger">*</span> </label>
                        <select name="service_details" id="service_details" class="form-control">
                            <option value="">Please Select</option>
                            <?php if($services) { 
                                foreach($services as $service) {
                                    $serviceObj = json_encode(array("service_code"=>$service->service_code, "service_name" => $service->service_name)); ?>
                                    <option value='<?=$serviceObj?>' <?=($service->service_code===$service_code)?'selected':''?>><?=$service->service_name?></option>
                                <?php }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("service_details") ?>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-danger" type="reset">
                    <i class="fa fa-refresh"></i> RESET
                </button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-angle-double-right"></i> SUBMIT
                </button>
            </div><!--End of .card-footer-->
        </div>
    </form>
</div>