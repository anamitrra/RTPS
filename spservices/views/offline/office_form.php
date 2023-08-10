<?php
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $office_name = $dbrow->office_name;
}else{
    $obj_id =null;
    $office_name = set_value("office_name");
}
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3">
            <div class="col-sm-12 mx-auto">
                <div class="card">
                    <div class="card-header">Office Form</div>
                    <div class="card-body py-1">
                        <form action="<?= base_url('spservices/offline/office_list/action') ?>" method="post">
                            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Office Name<span class="text-danger">*</span></label>
                                    <input name="office_name" id="office_name" value="<?= $office_name ?>" class="form-control" type="text" />
                                    <?= form_error("office_name") ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <p><button class="btn btn-success btn-sm filter_btn"> <?=$action_btn?></button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>