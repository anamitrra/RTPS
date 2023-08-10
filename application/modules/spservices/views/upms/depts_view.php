<?php
$depts = $this->depts_model->get_rows(array("status"=>1));
if(count((array)$dbrow)) {
    $title = "Edit existing department";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $dept_name = $dbrow->dept_name;
    $dept_code = $dbrow->dept_code;
    $dept_description = $dbrow->dept_description;
    $status = $dbrow->status;
} else {
    $title = "New department registration";
    $obj_id = null;
    $dept_name = set_value("dept_name");
    $dept_code = set_value("dept_code");
    $dept_description = set_value("dept_description");
    $status = null;
}//End of if else ?>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/depts/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Department name<span class="text-danger">*</span></label>
                        <input class="form-control" name="dept_name" value="<?=$dept_name?>" maxlength="100" autocomplete="off" type="text" />
                        <?= form_error("dept_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Department code<span class="text-danger">*</span></label>
                        <input class="form-control" name="dept_code" value="<?=$dept_code?>" maxlength="30" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <?= form_error("dept_code") ?>
                    </div>
                </div>             
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Department description</label>
                        <input class="form-control" name="dept_description" value="<?=$dept_description?>" maxlength="100" type="text" />
                        <?= form_error("dept_description") ?>
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
            <span class="h5 text-dark">Registered departments</span>
        </div>
        <div class="card-body">                
            <?php if ($depts): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Department name</th>
                            <th>Department code</th>
                            <th>Department description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($depts as $key => $row):
                            $status = $row->status;
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?=$row->dept_name ?></td>
                                <td><?=$row->dept_code?></td>
                                <td><?=$row->dept_description ?></td>
                                <td>
                                    <a href="<?=base_url('spservices/upms/depts/index/'.$obj_id)?>" class="btn btn-warning btn-sm" >Edit</a>
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