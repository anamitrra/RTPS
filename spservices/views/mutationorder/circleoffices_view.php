<?php
$filter = array("circle_name"=>array('$ne'=>""));
$circleoffices = $this->circleoffices_model->get_rows($filter); //pre($dbrow);
$districts = $this->district_model->get_rows(array('state_id'=>1));
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $circle_code = $dbrow->circle_code; 
    $circle_name = $dbrow->circle_name;
    $district_code = $dbrow->district_code;
    $treasury_code = $dbrow->treasury_code;
    $office_code = $dbrow->office_code;
    $remarks = $dbrow->remarks??'';
} else {
    $obj_id = null; 
    $circle_code = set_value("circle_code"); 
    $circle_name = set_value("circle_name");    
    $district_code = set_value("district_code");
    $treasury_code = set_value("treasury_code");
    $office_code = set_value("office_code");
    $remarks = set_value("remarks");
}
?>
<link href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script> 
<script type="text/javascript">
    $(document).ready(function () {                
        $("#dtbl").DataTable({
            "order": [[1, 'asc']],
            "lengthMenu": [[20, 50, 100, 200, 500], [20, 50, 100, 200, 500]]
        });
    });
</script>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
    div.dataTables_filter input {
        width: 88% !important;
        vertical-align: middle !important;
        text-align: right !important;
    }
</style>
<main class="rtps-container">
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Circle offices</div>
            <div class="card-body">
                <?php 
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
                <form id="myfrm" method="POST" action="<?=base_url('spservices/circleoffices/submit')?>">
                    <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5">Circle office details</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Circle name<span class="text-danger">*</span></label>
                                <input name="circle_name" value="<?=$circle_name?>" class="form-control" type="text" />
                                <?= form_error("circle_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Circle code<span class="text-danger">*</span></label>
                                <input name="circle_code" value="<?=$circle_code?>" class="form-control" type="text" />
                                <?= form_error("circle_code") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Office code<span class="text-danger">*</span></label>
                                <input name="office_code" value="<?=$office_code?>" class="form-control" type="text" />
                                <?= form_error("office_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Treasury code<span class="text-danger">*</span></label>
                                <input name="treasury_code" value="<?=$treasury_code?>" class="form-control" type="text" />
                                <?= form_error("treasury_code") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>District<span class="text-danger">*</span></label>
                                <select name="district_code" class="form-control">
                                    <option value="">Select</option>
                                    <?php if($districts){
                                        foreach($districts as $dist){ ?>
                                            <option value='<?=$dist->district_code?>' <?=($district_code == $dist->district_code) ? "selected":"" ?>><?=$dist->district_name?></option>
                                        <?php }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("district_code") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Remarks (If any)</label>
                                <input name="remarks" value="<?=$remarks?>" class="form-control" type="text" />
                                <?= form_error("remarks") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-danger frmbtn" type="reset">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                                <button class="btn btn-success frmbtn" type="submit">
                                    <i class="fa fa-check"></i> Submit
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                
                <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Circle offices list</legend>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table id="dtbl" class="table table-bordered table-striped">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Circle code</th>
                                            <th>Circle name</th>
                                            <th>District name</th>
                                            <th>District code</th>
                                            <th>Office code</th>
                                            <th>Treasury code</th>                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($circleoffices) {
                                            foreach($circleoffices as $rows) {
                                                $district = $this->district_model->get_row(array("district_code" => $rows->district_code)); ?>
                                                <tr>
                                                    <td><?=$rows->circle_code?></td>
                                                    <td>
                                                        <a href="<?=base_url('spservices/circleoffices/index/'.$rows->circle_code)?>"><?=$rows->circle_name?></a>
                                                    </td>
                                                    <td><?=$district?$district->district_name:'Not found'?></td>
                                                    <td><?=$rows->district_code?></td>
                                                    <td><?=$rows->office_code?></td>   
                                                    <td><?=$rows->treasury_code?></td>                               
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </fieldset>             
            </div>
        </div>
    </div>
</main>

