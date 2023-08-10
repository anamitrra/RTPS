<div class="row">
    <div class="col-md-5"><label>Application Ref. Number</label></div>
    <div class="col-md-7"><?=$data->appl_ref_no?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Application ID</label></div>
    <div class="col-md-7"><?=$data->appl_id?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Applied By</label></div>
    <div class="col-md-7"><?=$data->applied_by?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Submission Date</label></div>
    <div class="col-md-7"><?=$this->mongo_db->getDateTime($data->submission_date);?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Submission Location</label></div>
    <div class="col-md-7"><?=$data->submission_location?></div>
</div>



<ol class="breadcrumb mb-4 col-md-12">
    <li class="breadcrumb-item active">Attribute Details</li>
</ol>
<?php if($data->base_service_id == '1205') { 
     $this->load->view("applications/attribute_for_marriage_registration",array("attribute_details"=>$data->attribute_details));
    }elseif($data->base_service_id == '1396') {
        $this->load->view("applications/attribute_for_non_encumbrance_certificate",array("attribute_details"=>$data->attribute_details));
    }elseif($data->base_service_id == '1104') {
        $this->load->view("applications/attribute_for_registered_deed",array("attribute_details"=>$data->attribute_details));
    }elseif($data->base_service_id == '977') {
        $this->load->view("applications/attribute_for_deed_marriage_appointment",array("attribute_details"=>$data->attribute_details));
    }elseif($data->base_service_id == '1054') {
        $this->load->view("applications/attribute_for_jamabandhi",array("attribute_details"=>$data->attribute_details));
    // }elseif($data->base_service_id == '1207') {
    //     $this->load->view("applications/attribute_for_office_mutation",array("attribute_details"=>$data->attribute_details));
    }else {
        $this->load->view("applications/attributedetails",array("attribute_details"=>$data->attribute_details));
    }?>

<ol class="breadcrumb mb-4 col-md-12">
    <li class="breadcrumb-item active">Application Processing Details</li>
</ol>
<table class="table text-font10">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Task Name</th>
            <th scope="col">User Name</th>
            <th scope="col">Received Time</th>
            <th scope="col">Executed Time</th>
            
            <th scope="col">Action</th>
            <th scope="col">Remarks</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach ($execution_data as $key => $val) {
    ?>
 <tr>
            <td scope="col"><?=($key + 1)?></td>

            <td scope="col"><?=isset($val->task_details->task_name)?$val->task_details->task_name:""?></td>

            <td scope="col"><?=isset($val->task_details->user_name)?$val->task_details->user_name:"" ?></td>

            <td scope="col"><?=isset($val->task_details->received_time)?format_mongo_date($val->task_details->received_time):""?></td>

            <td scope="col"><?=isset($val->task_details->executed_time)?format_mongo_date($val->task_details->executed_time):""?></td>
            
            <td scope="col"><?=clean_data($val->official_form_details->action ?? 'N/A')?></td>

            <td scope="col"><?=$val->official_form_details->remarks ?? 'N/A'?></td>
        </tr>
        <?php }
?>
    </tbody>
</table>
