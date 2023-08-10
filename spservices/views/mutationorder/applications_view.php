<?php
$retry_payment_time = ($this->session->flashdata('retry_payment_time'))?(int)($this->session->flashdata('retry_payment_time')):0
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style type="text/css">
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
        width: 100% !important;
        margin-bottom: 3px;
    }
    .mybtn {
        float:right; 
        font-size: 14px; 
        font-style: italic; 
        font-weight: bold; 
        text-transform: uppercase;
        border: 2px dotted #F40080; 
        border-radius: 5px; 
        padding: 2px 5px;
        background: #F40080;
        color: #fff;
    }
</style>
<script type='text/javascript'>
    $(document).ready(function () {
        var timeInSeconds = parseInt('<?=$retry_payment_time?>');
        var doUpdate = function() {
            var minutes = Math.floor(timeInSeconds/60);
            var seconds = timeInSeconds-minutes * 60;
            $(".timer").html('PLEASE TRY AFTER '+minutes+' : '+seconds);   
            $(".make-payment").hide();          
            if(timeInSeconds > 0) {
                timeInSeconds--;
            } else {
                $(".timer").hide();   
                $(".make-payment").show();
            }//End of if else
        };
        setInterval(doUpdate, 1000);
    });
</script>
<div class="row">
    <div class="col-sm-12 mx-auto">
        <div class="card my-4">
            <div class="card-body">
                <h4>
                    Issuance of Certified Copy of Mutation Order 
                    <a href="<?=base_url('spservices/mutationorder')?>" class="mybtn" target="_blnk">
                        NEW APPLICATION <i class="fa fa-plus"></i>
                    </a>
                </h4>
                <?php if ($this->session->flashdata('pay_message') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong></strong> <?= $this->session->flashdata('pay_message') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }
                if ($mutationorders): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Application Ref No</th>
                                <th>Service Name</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="">
                            <?php
                            foreach ($mutationorders as $key => $row):
                                $obj_id = $row->_id->{'$id'};
                                $certificate = $row->form_data->certificate ?? '';
                                $payment_status = $row->form_data->payment_status??'';
                                $ppr_grn = isset($row->form_data->pfc_payment_response->GRN)?$row->form_data->pfc_payment_response->GRN:'';
                                $ppr_status = isset($row->form_data->pfc_payment_response->STATUS)?$row->form_data->pfc_payment_response->STATUS:'';
                                $appl_status = $row->service_data->appl_status;
                                $certificatePath = (strlen($certificate) ? base_url($certificate) : '#'); ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td><?= $row->service_data->appl_ref_no ?></td>
                                    <td><?= isset($row->service_data->service_name) ? $row->service_data->service_name : "" ?></td>
                                    <td><?= isset($row->service_data->submission_date) ? format_mongo_date($row->service_data->submission_date) : "" ?></td>
                                    <td><?= ($appl_status=='AST')?'Application forwarded':getstatusname($appl_status)?></td>
                                    <td>
                                        <?php if ($payment_status == "PAID") { ?>
                                            <a href="<?= base_url("spservices/mutationorder/registration/acknowledgement/") . $obj_id ?>" class="btn btn-success btn-sm mbtn" target="_blank">Acknowledgement</a>
                                            <a href="<?= base_url("spservices/mutationorder/registration/preview/") . $obj_id ?>" class="btn btn-info btn-sm mbtn"  target="_blank">Application Preview</a>
                                        <?php } if (strtolower($appl_status) == "DRAFT") { ?>
                                            <a href="<?= base_url('spservices/mutationorder/registration/index/'.$obj_id)?>" class="btn btn-primary btn-sm mbtn">Complete Your Application</a>
                                        <?php } elseif ($appl_status == "QS") { ?>
                                            <a href="<?= base_url('spservices/mutationorder/query/index/'.$obj_id) ?>" class="btn btn-warning btn-sm mbtn" >Reply to query</a>
                                        <?php }  elseif ($appl_status == "D") { ?>
                                            <a href="<?= $certificatePath ?>" target="_blank" class="btn btn-warning btn-sm mbtn" >Download/View</a>
                                        <?php } elseif ($payment_status !== "PAID") {
                                            if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                                <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$row->form_data->pfc_payment_response->DEPARTMENT_ID??''?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$row->form_data->payment_params->OFFICE_CODE??''?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$row->form_data->pfc_payment_response->AMOUNT??''?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/mutationorder/paymentresponse/cin_response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                </form>
                                                <?php } else { ?>
                                                    <span class="timer btn btn-light btn-sm mbtn" style="display:<?=($retry_payment_time)?'block':'none'?>">00:00</span>
                                                    <span class="make-payment" style="display:<?=($retry_payment_time)?'none':'block'?>">
                                                        <a href="<?= base_url('spservices/mutationorder/payment/initiate/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn">Make payment</a>
                                                    </span>
                                                <?php }//End of if else ?>
                                        <?php } else { ?>
                                            <a href="<?=base_url('spservices/mutationorder/registration/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn"  target="_blank">Track status</a>
                                        <?php } //End of if else ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No Application Found<p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>