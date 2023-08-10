<?php
$services = $this->services_model->get_rows(array("status"=>1));
$rights = $this->rights_model->get_rows(array("status"=>1));
if($dbrow) {
    $title = "Edit existing right";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $service_name = $dbrow->service_name;
    $service_code = $dbrow->service_code??'';
    $button_levels = isset($dbrow->button_levels)?(array)$dbrow->button_levels:array();
} else {
    $title = "New right registration";
    $obj_id = null;
    $service_name = set_value("service_name");
    $service_code = set_value("service_code");
    $button_levels = array();
    $status = null;
}//End of if else ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("change", "#service_code", function(){
            var serviceCode = $(this).val();
            if(serviceCode.length) {
                window.location.href = "<?=base_url('spservices/upms/buttonlevels/index/')?>"+serviceCode;
            } else {
                //alert("Please select a district");
            }//End of if else
        });//End of onChange #service_code
    });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/buttonlevels/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark" style="padding: .10rem .50rem;">
                <span class="h5 text-white" style="line-height: 34px">Existing button and url texts</span>
                <span class="float-right">
                    <select name="service_code" id="service_code" class="form-control w-auto">
                        <option value="">Default values for all services</option>
                        <?php if($services) { 
                            foreach($services as $service) {
                                $sel = ($service->service_code===$service_code)?'selected':'';
                                echo "<option value='{$service->service_code}' {$sel}>{$service->service_name}</option>";
                             }//End of foreach()
                        }//End of if ?>
                    </select>
                </span>                    
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Right code</th>
                            <th>Right name</th>
                            <th>Button level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($rights) {
                            foreach ($rights as $key => $row):                                
                                $rightCode = strtolower($row->right_code);
                                $lbl = $button_levels[$rightCode]??$row->right_name; ?>
                                <tr>
                                    <td><?=$row->right_code?></td>
                                    <td><?=$row->right_name?></td>
                                    <td>
                                        <input name="<?=strtolower($row->right_code)?>" value="<?=$lbl?>" class="form-control" type="text" />
                                    </td>
                                </tr>
                            <?php endforeach;
                        } else {
                            echo "<h3 class='text-center'>No records found</h3>";
                        }//End of if else ?>                                
                    </tbody>
                </table>
            </div><!--End of .card-body-->
            <div class="card-footer text-center">
                <button class="btn btn-danger" type="reset">
                    <i class="fa fa-trash-restore"></i> RESET
                </button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-angle-double-right"></i> UPDATE
                </button>
            </div><!--End of .card-footer-->
        </div>
    </form>
</div>