<?php
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $service_name = $dbrow->service_name;
    $timeline = $dbrow->service_timeline;
}else{
    $obj_id =null;
    $service_name = set_value("service_name");
    $timeline = set_value("timeline");
}
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3">
            <div class="col-sm-12 mx-auto">
                <div class="card">
                    <div class="card-header">Service Form</div>
                    <div class="card-body py-1">
                        <form action="<?= base_url('spservices/offline/servicelist/action') ?>" method="post">
                            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Service Name<span class="text-danger">*</span></label>
                                    <input name="service_name" id="service_name" value="<?= $service_name ?>" class="form-control" type="text" />
                                    <?= form_error("service_name") ?>
                                </div>
                               
                                <div class="col-md-6 form-group">
                                    <label>Stipulated Timeline for service delivery(days)<span class="text-danger">*</span></label>
                                    <input name="timeline" id="timeline" value="<?= $timeline ?>" class="form-control" type="text" />
                                    <?= form_error("timeline") ?>
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