<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>

 
<style>
    .mandatory {
        color:red
    }
.wrapper{
    border:2px solid #808080;
    height:100%
}
.topbox{
    margin:0 auto;
    padding:40px 0 0px 0;
    /* border-bottom: 2px solid #eee; */
}
.main{
    border-top:2px solid #9a9a9a;
    margin-top:20px;
    padding-top:20px;
    padding:20px;
    font-size:14px;
}
</style>
<div class="content-wrapper">
<div class="container mt-3">
    <div class="card shadow bg-info">
        <div class="card-body">
            <table class="table table-bordered table-sm" id="application_list_table">
                <!-- <thead> -->
                    <?php foreach($data as $value) { 
                        foreach($task_data as $td) {?>
                    <tr>
                        <th>Service Name</th>
                        <td><?php echo $value->service_name; ?></td>
                    </tr>
                    <tr>
                        <th>Application Ref. No</th>
                        <td><?php echo $value->rtps_trans_id; ?></td>
                    </tr>
                    <tr>
                        <th>Application Received Date</th>
                        <td><?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at))); ?></td>
                    </tr>
                    <tr>
                        <th>Task Name</th>
                        <td class="text-white"><b>
                            <?php  echo $td->task_name;?></b></td>
                    </tr>
                <?php } }?>
            </table>
        </div>
    </div>
    <div id="accordion">
    <div class="card shadow ">
    <div class="card-header bg-warning"><b><a class="card-link text-dark btn-block" data-toggle="collapse" href="#collapseOne">Process History</b></a></div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm" id="application_list_table">
                <thead >
                    <tr>
                        <th width="8%">SL. No</th>
                        <th>Official</th>
                        <th>Action</th>
                        <th>Remarks</th>
                        <th width="15%">Report File</th>
                    </tr>
                </thead>
                     <?php $i=1; 
                     foreach($remarks as $res) {  ?>
                    <tr>
                        <td><b><?php echo $i++; ?></b></td>
                        <td><b><?php echo $res->task_details->user_name; ?></b></td>
                        <td><b><?php echo $res->official_form_details->action; ?></b></td>
                        <td><b><?php echo $res->official_form_details->remarks; ?></b></td>
                        <td><?php 
                        if(!empty($res->official_form_details->documents)){ ?>
                        <a href="<?= base_url($res->official_form_details->documents); ?>" class="btn btn-xs btn-danger"><i class="fa fa-eye"></i> View Report</a><?php } ?></td>
                    </tr>
                <?php }  ?>
            </table>
        </div>
                        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header bg-primary">Action Form</div>
        <div class="card-body">
            <!-- action number 1 -->
        <?php if(($value->task_no == '1') && ($this->session->userdata("role")->slug == "DPS")){?>
        <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST">
        <input type="hidden" value="<?php echo base64_encode('1') ?>" name="task_no">
        <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">   
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="R">Reject</option>
                            <option value="F">Forward to DA</option>
                            <option value="RA">Revert back to Applicant</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2 user_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">User  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <!-- <div id="userlist_div"> -->
                        <select class="form-control select2 userlist_div" name="users[]" id="user_list" multiple style="width:100%">
                        </select>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                
                <div class="row mt-2 query_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">Feedback/Remark to Applicant <br>Query to Applicant  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                    <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control"></textarea>

                    </div>
                </div>
                <input type="hidden" name="receive_date" value="<?php echo $value->created_at; ?>">

                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <!-- action number 2 -->
            <?php }
            elseif(($value->task_no == '2') && ($this->session->userdata("role")->slug == "DA")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST">
            <input type="hidden" value="<?php echo base64_encode('2') ?>" name="task_no">    
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">  
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type_2" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to CO</option>
                            <option value="RO">Revert to DPS</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mt-2 user_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">User  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control select2 userlist_div" name="users[]" id="user_list" multiple style="width:100%">
                        </select>
                        <!-- <div id="userlist_div"></div> -->
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <!-- action number 3 -->
            <?php } 
             elseif(($value->task_no == '3') && ($this->session->userdata("role")->slug == "CO")){?>
        <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST">
        <input type="hidden" value="<?php echo base64_encode('3') ?>" name="task_no">    
        <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">  
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type_3" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to SK</option>
                            <option value="RA">Revert to Application</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mt-2 user_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">User  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control select2 userlist_div" name="users[]" id="user_list" multiple style="width:100%">
                        </select>
                        <!-- <div id="userlist_div"></div> -->
                    </div>
                </div>
                <div class="row mt-2 query_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">Feedback/Remark to Applicant <br>Query to Applicant  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                    <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php }
            elseif(($value->task_no == '4') && ($this->session->userdata("role")->slug == "SK")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST">
            <input type="hidden" value="<?php echo base64_encode('4') ?>" name="task_no">    
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">  
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type_4" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to LM</option>
                            <option value="RO">Revert to CO</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mt-2 user_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">User  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                    <select class="form-control select2 userlist_div" name="users[]" id="user_list" multiple style="width:100%">
                        </select>
                        <!-- <div id="userlist_div"></div> -->
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php } 
            elseif(($value->task_no == '5') && ($this->session->userdata("role")->slug == "LM")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo base64_encode('5') ?>" name="task_no">  
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">    
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type_5" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to SK</option>
                            <option value="RO">Revert to SK</option>
                            <option value="RA">Revert to Application</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mt-2 query_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">Feedback/Remark to Applicant <br>Query to Applicant  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                    <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row mt-2 file_upload_lm" style="display:none">
                    <div class="col-md-6">
                        <label for="">Upload Report <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="file" class="form-control-file" id="report_file" name="report_file" />
                    </div>
                </div>

                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php } 
            elseif(($value->task_no == '6') && ($this->session->userdata("role")->slug == "SK")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo base64_encode('6') ?>" name="task_no">  
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">    
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to CO</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php } 
            elseif(($value->task_no == '7') && ($this->session->userdata("role")->slug == "CO")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo base64_encode('7') ?>" name="task_no"> 
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">     
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to DA</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php } 
            elseif(($value->task_no == '8') && ($this->session->userdata("role")->slug == "DA")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo base64_encode('8') ?>" name="task_no">    
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">  
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="F">Forward to DPS</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
            <?php } 
            elseif(($value->task_no == '9') && ($this->session->userdata("role")->slug == "DPS")) {?>
            <form id="action_form_1" action="<?= base_url("iservices/superadmin/save_action") ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo base64_encode('9') ?>" name="task_no">   
            <input type="hidden" value="<?php echo base64_encode($value->service_id); ?>" name="service">   
            <div class="row">
                    <div class="col-md-6">
                        <label for="">Action <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <select name="action_type" id="action_type_9" class="form-control" required>
                            <option value="" selected disabled>Select action</option>
                            <option value="D">Deliver</option>
                            <option value="R">Reject</option>
                            <option value="RA">Revert Back to Applicant</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label for="">Remarks <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mt-2 query_div" style="display:none">
                    <div class="col-md-6">
                        <label for="">Feedback/Remark to Applicant <br>Query to Applicant  <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-md-6">
                    <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control"></textarea>

                    </div>
                </div>
                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                <div class="d-flex justify-content-center" style="margin-top:20px">
                <?php if($value->task_no == '9'){
                    echo '<button class="btn btn-success btn-sm " name="save"><i class="fa fa-edit"></i> Proceed to sign</button>';
                }else{
                    echo '<button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>';
                } ?>
                    
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header bg-dark">Applicant Data for Output Certificate</div>
        <div class="card-body">
            <table class="table table-bordered" id="application_list_table">
                    <?php foreach($data as $value) { ?>
                        <tr>
                            <td width="25%"><b>Name of the Applicant/আবেদনকাৰীৰ নাম</b></td>
                            <td width="25%"><?php echo $value->applicant_name; ?></td>
                            <td width="25%"><b>Father's Name / পিতৃৰ নাম</b></td>
                            <td width="25%"><?php echo $value->father_name; ?></td>
                        </tr>
                        <tr>
                            <td><b>Name of Caste / জাতি</b></td>
                            <td><?php echo $value->sub_caste; ?></td>
                            <td><b>Village / গাওঁ</b></td>
                            <td><?php echo $value->pa_village; ?></td>
                        </tr>
                        <tr>
                            <td><b>District</b></td>
                            <td><?php echo $value->pa_district; ?></td>
                            <td><b>Mouza / মৌজা</b></td>
                            <td><?php echo $value->pa_mouza; ?></td>
                        </tr>
                        <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->rtps_trans_id); ?>">
                <?php } ?>
            </table>
        </div>
    </div>
    <!-- <div class="d-flex justify-content-center" style="padding-bottom:20px;">
        <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
    </div> -->
    <!-- </form> -->
</div>
</div>
</div>
    <?PHP $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>

    <input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    <script type="text/javascript">        
    $(document).ready(function(){
        $("#report_file").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
        $('.select2').select2({
            placeholder: "Choose one",
            //allowClear: true
        });
        let district = '<?php echo $this->session->userdata('district'); ?>';
        // user_div
      $('#action_type').change(function() {
        let action = $(this).val();
        if(action =='F'){
            $('.query_div').hide();
            $('.task_div').show();
            $('.user_div').show();
            $('#feedback').val('');
            $.ajax({
                url:'<?php echo site_url("iservices/superadmin/applications/get_da") ?>',
                method: 'post',
                data: {dist: '<?php echo $this->session->userdata('district') ?>'},
                dataType: 'json',
                success: function(response){
                    let users ='';
                    response.data.forEach((res)=>{
                        users +='<option value="'+res._id.$id+'">'+res.name+'</option>';
                    })
                    $('#user_list').html(users);
                }
            })
        }
        else if(action =='RA'){
            $('.query_div').show();
            $('.user_div').hide();
        }
        else{
            $('.task_div').hide();
            $('.user_div').hide();
            $('.query_div').hide();
            $('input[name="task_option"]').prop('checked', false);
            $('input:checkbox').prop('checked', false);
            $('#feedback').val('');
        }
      })
    // $(function(){
    //     $('#user_list').multiselect();
    // });
    
    // $('.task_option').on('click', function () {
    //     let value = $("input[name=task_option]:checked").val()
    //     if(value == 'RBTAFD') {
    //         $('.query_div').show();
    //         $('.user_div').hide();
    //         $('input:checkbox').prop('checked', false);
    //     }
    //     else if(value == 'DAART') {
    //         $('.query_div').hide();
    //         $('.user_div').show();
    //         $('#feedback').val('');
    //         $.ajax({
    //             url:'<?php echo site_url("iservices/superadmin/applications/get_da") ?>',
    //             method: 'post',
    //             data: {dist: '1'},
    //             dataType: 'json',
    //             success: function(response){
    //                 console.log(response.data);
    //                 console.log(response.data[0]._id)
    //                 let user_div = '';
    //                 response.data.forEach((res)=>{
    //                     user_div +=' <div class="form-check"><input type="checkbox" class="form-check-input" id="check2" name="users[]" value="'+res._id.$id+'"><label class="form-check-label" for="check2">'+res.name+'</label></div>'
    //                 })
    //                 $('#userlist_div').html(user_div) 
    //             }
    //         })
    //     }
    //     else {
    //         $('.query_div').hide();
    //         $('.user_div').hide();
    //         $('#feedback').val('');
    //     }
    // })
    // action_type_2
    $('#action_type_2').on('change', function () {
        let value= $(this).val();
        if(value == 'F'){
            $('.user_div').show()
            $.ajax({
                url:'<?php echo site_url("iservices/superadmin/applications/get_co_list") ?>',
                method: 'post',
                data: {dist: district},
                dataType: 'json',
                success: function(response){
                    console.log(response.data);
                    console.log(response.data[0]._id)
                    let users ='';
                    response.data.forEach((res)=>{
                        users +='<option value="'+res._id.$id+'">'+res.name+'</option>';
                    })
                    $('#user_list').html(users);
                }
            })
        }
        else{
            $('.user_div').hide()
        }
    })
        // action_type_3
    $('#action_type_3').on('change', function () {
        let value= $(this).val();
        if(value == 'F'){
            $('.user_div').show()
            $('.query_div').hide();
            $.ajax({
                url:'<?php echo site_url("iservices/superadmin/applications/get_sk_list") ?>',
                method: 'post',
                data: {dist: district},
                dataType: 'json',
                success: function(response){
                    console.log(response.data);
                    console.log(response.data[0]._id)
                    let users ='';
                    response.data.forEach((res)=>{
                        users +='<option value="'+res._id.$id+'">'+res.name+'</option>';
                    })
                    $('#user_list').html(users);
                }
            })
        }
        else if(value =='RA'){
            $('.query_div').show();
            $('.user_div').hide();
        }
    })
    
            // action_type_4
    $('#action_type_4').on('change', function () {
        let value= $(this).val();
        if(value == 'F'){
            $('.user_div').show()
            $.ajax({
                url:'<?php echo site_url("iservices/superadmin/applications/get_lm_list") ?>',
                method: 'post',
                data: {dist: district},
                dataType: 'json',
                success: function(response){
                    console.log(response.data);
                    console.log(response.data[0]._id)
                    let users ='';
                    response.data.forEach((res)=>{
                        users +='<option value="'+res._id.$id+'">'+res.name+'</option>';
                    })
                    $('#user_list').html(users);
                }
            })
        }
        else if(value == 'RO'){
            $('.user_div').hide()
        }
    })

    // action_type_5
        $('#action_type_5').on('change', function () {
        let value= $(this).val();
        if(value == 'F'){
            $('.file_upload_lm').show()
            $('.query_div').hide();
        }
        else if(value =='RO'){
            $('.file_upload_lm').hide();
            $('.query_div').hide();
        }
        else if(value =='RA'){
            $('.file_upload_lm').hide()
            $('.query_div').show();
        }
    })
        // action_type_9
        
        $('#action_type_9').on('change', function () {
        let value= $(this).val();
        if(value == 'D') { 
            $('.query_div').hide();
        }
        else if(value == 'RA'){
            $('.query_div').show()
        }
        else{
            $('.query_div').hide();
        }
    })
    })
</script>