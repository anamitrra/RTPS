<?php if(isset($service_data)) {
    $department_name = $service_data[0]->department_name??'';
    $major_head = $service_data[0]->MAJOR_HEAD??'';
    $payment_type = $service_data[0]->PAYMENT_TYPE??'';
    $head_of_account = $service_data[0]->HEAD_OF_ACCOUNT_COUNT??'';
    $hoa1 = $service_data[0]->HOA1??'';
    $hoa2 = $service_data[0]->HOA2??'';
    $hoa3 = $service_data[0]->HOA3??'';
    $non_treasury_payment_type = $service_data[0]->NON_TREASURY_PAYMENT_TYPE??'';
    $non_treasury_account = $service_data[0]->NON_TREASURY_ACCOUNT_COUNT??'';
    $amount3 = $service_data[0]->AMOUNT3??'';
    $account1 = $service_data[0]->ACCOUNT1??'';
    $account2 = $service_data[0]->ACCOUNT2??'';
    $dept_code = $service_data[0]->DEPT_CODE??'';
    $stipulated_timeline = $service_data[0]->stipulated_timeline??'';
            
    $registration_url = $service_data[0]->registration_url??'';
    $edit_url = $service_data[0]->edit_url??'';
    $query_reply_url = $service_data[0]->query_reply_url??'';
    $preview_url = $service_data[0]->preview_url??'';
    $acknowledgement_url = $service_data[0]->acknowledgement_url??'';
    $track_status_url = $service_data[0]->track_status_url??'';
    $make_payment_url = $service_data[0]->make_payment_url??'';
    $verify_payment_url = $service_data[0]->verify_payment_url??'';
    $query_payment_url = $service_data[0]->query_payment_url??'';
    $delivered_url = $service_data[0]->delivered_url??'';
} else {
    $department_name = set_value('department_name');
    $major_head = set_value('MAJOR_HEAD');
    $payment_type = set_value('PAYMENT_TYPE');
    $head_of_account = set_value('HEAD_OF_ACCOUNT_COUNT');
    $hoa1 = set_value('HOA1');
    $hoa2 = set_value('HOA2');
    $hoa3 = set_value('HOA3');
    $non_treasury_payment_type = set_value('NON_TREASURY_PAYMENT_TYPE');
    $non_treasury_account = set_value('NON_TREASURY_ACCOUNT_COUNT');
    $amount3 = set_value('AMOUNT3');
    $account1 = set_value('ACCOUNT1');
    $account2 = set_value('ACCOUNT2');
    $dept_code = set_value('DEPT_CODE');
    $stipulated_timeline = set_value('stipulated_timeline');
    
    $registration_url = set_value('registration_url');
    $edit_url = set_value('edit_url');
    $query_reply_url = set_value('query_reply_url');
    $preview_url = set_value('preview_url');
    $acknowledgement_url = set_value('acknowledgement_url');
    $track_status_url = set_value('track_status_url');
    $make_payment_url = set_value('make_payment_url');
    $verify_payment_url = set_value('verify_payment_url');
    $query_payment_url = set_value('query_payment_url');
    $delivered_url = set_value('delivered_url');
    
}//End of if else ?>
<main class="rtps-container">
    <div class="container my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <?php echo $title; ?>
                <a class="pull-right btn btn-sm btn-info" href="<?=base_url('spservices/spservice')?>">Back to service list</a>
            </div>
            <form action="<?= $action ?>" method="post">
                <div class="card-body">
                    <?php if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Service Name <span class="text-danger">*</span></label>
                                <input type="text" name="service_name" class="form-control" 
                                value="<?= (isset($service_data)) ? $service_data[0]->service_name :  '' ?>" />
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= (isset($id)) ? $id :  '' ?>" />
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Service ID <span class="text-danger">*</span></label>
                                <input type="text" name="service_id" class="form-control" value="<?= (isset($service_data)) ? $service_data[0]->service_id :  '' ?>" <?= (isset($service_data)) ? 'readonly' :  '' ?>>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Stipulated timeline <span class="text-danger">*</span></label>
                                <input type="text" name="stipulated_timeline" class="form-control" value="<?=$stipulated_timeline?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Department Name <span class="text-danger">*</span></label>
                                <input type="text" name="department_name" class="form-control" value="<?=$department_name?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Department Code <span class="text-danger">*</span></label>
                                <input type="text" name="dept_code" class="form-control" value="<?= (isset($service_data)) ? $service_data[0]->DEPT_CODE :  '' ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Payment Type <span class="text-danger">*</span></label>
                                <input type="text" name="payment_type" class="form-control" value="<?=$payment_type?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Major Head <span class="text-danger">*</span></label>
                                <input type="text" name="major_head" class="form-control" value="<?=$major_head?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Head of Account <span class="text-danger">*</span></label>
                                <input type="text" name="head_of_account" class="form-control" value="<?=$head_of_account?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">HOA1 <span class="text-danger">*</span></label>
                                <input type="text" name="hoa1" class="form-control" value="<?=$hoa1?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">HOA2 <span class="text-danger">*</span></label>
                                <input type="text" name="hoa2" class="form-control" value="<?=$hoa2?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">HOA3 <span class="text-danger">*</span></label>
                                <input type="text" name="hoa3" class="form-control" value="<?=$hoa3?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Non Treasury Payment Type <span class="text-danger">*</span></label>
                                <input type="text" name="non_treasury_payment_type" class="form-control" value="<?=$non_treasury_payment_type?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Non Treasury Account <span class="text-danger">*</span></label>
                                <input type="text" name="non_treasury_account" class="form-control" value="<?=$non_treasury_account?>">
                            </div>
                        </div>                    
                        <!-- <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Non Treasury Payment Type <span class="text-danger">*</span></label>
                                <input type="text" name="non_treasury_payment_type" class="form-control">
                            </div>
                        </div> -->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Amount3 <span class="text-danger">*</span></label>
                                <input type="text" name="amount3" class="form-control" value="<?=$amount3?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Account 1 <span class="text-danger">*</span></label>
                                <input type="text" name="account1" class="form-control" value="<?=$account1?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Account 2 <span class="text-danger">*</span></label>
                                <input type="text" name="account2" class="form-control" value="<?=$account2?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="">01. Registration url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="registration_url" value="<?=$registration_url?>" class="form-control" type="text" autocomplete="off" />
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">02. Edit url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="edit_url" value="<?=$edit_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">03. Query reply url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="query_reply_url" value="<?=$query_reply_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">04. Preview url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="preview_url" value="<?=$preview_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">05. Acknowledgement url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="acknowledgement_url" value="<?=$acknowledgement_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">06. Track status url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="track_status_url" value="<?=$track_status_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">07. Make payment url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="make_payment_url" value="<?=$make_payment_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">08. Verify payment url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="verify_payment_url" value="<?=$verify_payment_url?>" class="form-control" type="text" autocomplete="off" />
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">09. Query payment url<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="query_payment_url" value="<?=$query_payment_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">10. Delivered url (If any custom delivered page design)<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text"><?=base_url('spservices/')?></span></div>
                                <input name="delivered_url" value="<?=$delivered_url?>" class="form-control" type="text" autocomplete="off" />
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2">/$obj_id</span></div>
                            </div>
                        </div>
                    </div><!--End of .row-->
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <button class="btn btn-sm btn-success"><i class="fa fa-save"></i> <?= $btn ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>