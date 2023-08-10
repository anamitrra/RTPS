<?php
$roles = $this->roles_model->get_rows(array("status"=>1));
if(count((array)$dbrow)) {
    $title = "Edit existing role";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $role_name = $dbrow->role_name;
    $role_code = $dbrow->role_code;
    $role_description = $dbrow->role_description;
    $status = $dbrow->status;
} else {
    $title = "New role registration";
    $obj_id = null;
    $role_name = set_value("role_name");
    $role_code = set_value("role_code");
    $role_description = set_value("role_description");
    $status = null;
}//End of if else ?>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/roles/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Role name</label>
                        <input class="form-control" name="role_name" value="<?=$role_name?>" maxlength="100" autocomplete="off" type="text" />
                        <?= form_error("role_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Role code</label>
                        <input class="form-control" name="role_code" value="<?=$role_code?>" maxlength="30" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <?= form_error("role_code") ?>
                    </div>
                </div>             
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Role description</label>
                        <input class="form-control" name="role_description" value="<?=$role_description?>" maxlength="100" type="text" />
                        <?= form_error("role_description") ?>
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
            <span class="h5 text-dark">Registered roles</span>
        </div>
        <div class="card-body">                
            <?php if ($roles): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Role name</th>
                            <th>Role code</th>
                            <th>Role description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($roles as $key => $row):
                            $status = $row->status;
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?=$row->role_name ?></td>
                                <td><?=$row->role_code?></td>
                                <td><?=$row->role_description ?></td>
                                <td>
                                    <a href="<?=base_url('spservices/upms/roles/index/'.$obj_id)?>" class="btn btn-warning btn-sm" >Edit</a>
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