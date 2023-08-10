<?php
$rights = $this->rights_model->get_rows(array("status"=>1));
if(count((array)$dbrow)) {
    $title = "Edit existing right";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $right_name = $dbrow->right_name;
    $right_code = $dbrow->right_code??'';
    $right_description = $dbrow->right_description;
    $status = $dbrow->status;
} else {
    $title = "New right registration";
    $obj_id = null;
    $right_name = set_value("right_name");
    $right_code = set_value("right_code");
    $right_description = set_value("right_description");
    $status = null;
}//End of if else ?>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/rights/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name</label>
                        <input list="right_names" class="form-control" name="right_name" value="<?=$right_name?>" maxlength="100" autocomplete="off" type="text" />
                        <datalist id="right_names">
                            <option value="Remarks" />
                            <option value="Reject" />
                            <option value="Query" />
                            <option value="Forward" />
                            <option value="Backward" />
                            <option value="Approve" />
                            <option value="File Upload" />
                            <option value="Generate Cerificate" />
                            <option value="Create User" />
                        </datalist>
                        <?= form_error("right_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Code</label>
                        <input list="right_codes" class="form-control" name="right_code" value="<?=$right_code?>" maxlength="20" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <datalist id="right_codes">
                            <option value="REMARKS" />
                            <option value="REJECT" />
                            <option value="QUERY" />
                            <option value="FORWARD" />
                            <option value="BACKWARD" />
                            <option value="APPROVE" />
                            <option value="FILE_UPLOAD" />
                            <option value="GENERATE_CERTIFICATE" />
                            <option value="CREATE_USER" />
                        </datalist>
                        <?= form_error("right_code") ?>
                    </div>
                </div>                
                <div class="row mt-2">  
                    <div class="col-md-12 form-group">
                        <label>Description</label>
                        <input class="form-control" name="right_description" value="<?=$right_description?>" maxlength="100" type="text" />
                        <?= form_error("right_description") ?>
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
            <span class="h5">Registered rights</span>
        </div>
        <div class="card-body">                
            <?php if ($rights): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Right name</th>
                            <th>Right code</th>
                            <th>Right description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($rights as $key => $row):
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?=$row->right_name?></td>
                                <td><?=$row->right_code??''?></td>
                                <td><?=$row->right_description ?></td>
                                <td>
                                    <a href="<?=base_url('spservices/upms/rights/index/'.$obj_id)?>" class="btn btn-warning btn-sm" >Edit</a>
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