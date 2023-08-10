<?php
$services = $this->services_model->get_rows(array("status"=>1));
$obj_id = $dbrow->{'_id'}->{'$id'};
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile_number = $dbrow->form_data->mobile_number;
$email_id = $dbrow->form_data->email_id;
$service_code = $dbrow->service_data->service_id??'';
$service = $this->services_model->get_row(array("service_code"=>$service_code));
$status = $dbrow->service_data->appl_status;
?>
<style type="text/css">
    label {
        display: block;
        margin-bottom: 2px;
    }
</style>
<div class="card shadow-sm">
    <div class="card-header bg-dark">
        <span class="h5 text-white">Application form view</span>
    </div>
    <div class="card-body">                
        <div class="row">
            <div class="col-md-6">
                <label>Name of the applicant </label>
                <strong><?=$applicant_name?></strong>
            </div>  
            <div class="col-md-6">
                <label for="applicant_gender">Gender </label>
                <strong><?=$applicant_gender?></strong>
            </div>
        </div>               

        <div class="row mt-4">     
            <div class="col-md-6">
                <label>Mobile number</label>
                <strong><?=$mobile_number?></strong>
            </div>   
            <div class="col-md-6">
                <label>Email id</label>
                <strong><?=$email_id?></strong>
            </div> 
        </div>

        <div class="row mt-4">                  
            <div class="col-md-12">
                <label>Service </label>
                <strong><?=$service?$service->service_name:'No records found'?></strong>
            </div>
        </div>
    </div>
</div>