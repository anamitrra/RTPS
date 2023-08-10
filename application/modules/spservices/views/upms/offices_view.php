<?php
$services = $this->services_model->get_rows(array("status"=>1));
$offices = $this->offices_model->get_rows(array("status"=>1));
$serviceCodes = array();
if(count((array)$dbrow)) {
    $title = "Edit existing office";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $office_name = $dbrow->office_name;
    $office_code = $dbrow->office_code;
    $services_mapped = $dbrow->services_mapped;
    if(is_object($services_mapped)) {
        $service_code = $services_mapped->service_code??'';
    } else {
        foreach($services_mapped as $user_service) {
            $serviceCodes[] = $user_service->service_code??'';
        }//End of foreach()
        $service_code = $services_mapped[0]->service_code??'';
    }//End of if else
    $office_address = $dbrow->office_address;
    $office_description = $dbrow->office_description;
    $status = $dbrow->status;
} else {
    $title = "New office registration";
    $obj_id = null;
    $office_code = null;
    $office_name = set_value("office_name");
    $services_mapped = set_value("services_mapped");
    $service_code = '';
    $office_address = set_value("office_address");
    $office_description = set_value("office_description");
    $status = null;
}//End of if else ?>
<link rel="stylesheet" href="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css")?>" type="text/css">
<script src="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js")?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#services_mapped').multiselect({
            columns: 1,
            texts: {
                placeholder: 'Please select service(s)'
            }
        });
    });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" office="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/offices/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <input name="office_code" value="<?=$office_code?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Office name <span class="text-danger">*</span></label>
                        <input class="form-control" name="office_name" value="<?=$office_name?>" maxlength="100" autocomplete="off" type="text" />
                        <?= form_error("office_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Service(s) mapped <span class="text-danger">*</span></label>
                        <select name="services_mapped[]" id="services_mapped" class="form-control" multiple='multiple'>
                            <?php if($services) { 
                                foreach($services as $service) {
                                    $isSel = in_array($service->service_code, $serviceCodes)?'selected':'';//($service->service_code===$service_code)?'selected':'';
                                    $serviceObj = json_encode(array("service_code"=>$service->service_code, "service_name" => $service->service_name)); ?>
                                    <option value='<?=$serviceObj?>' <?=$isSel?>><?=$service->service_name?></option>
                                <?php }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("services_mapped") ?>
                    </div>
                </div>             
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Office address</label>
                        <input class="form-control" name="office_address" value="<?=$office_address?>" maxlength="30" autocomplete="off" type="text" />
                        <?= form_error("office_address") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Office details/description</label>
                        <input class="form-control" name="office_description" value="<?=$office_description?>" maxlength="100" type="text" />
                        <?= form_error("office_description") ?>
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
    
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">Registered offices</span>
        </div>
        <div class="card-body">                
            <?php if ($offices): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Office name</th>
                            <th>Service(s) mapped</th>
                            <th>Office address</th>
                            <th style="width: 60px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($offices as $key => $row):
                            $servicesMapped = '';
                            if(isset($row->services_mapped) && is_array($row->services_mapped)) {
                                foreach($row->services_mapped as $services) {
                                    $servicesMapped = $servicesMapped.',<br>'.$services->service_name;
                                }
                            }
                            $status = $row->status;
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?=$row->office_name ?></td>
                                <td><?=trim($servicesMapped, ',<br>')?></td>
                                <td><?=$row->office_address?></td>
                                <td style="text-align:center">
                                    <a href="<?=base_url('spservices/upms/offices/index/'.$obj_id)?>" class="btn btn-warning btn-sm" >Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found<p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $('#dtbl').DataTable();
});
</script>