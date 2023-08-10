<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
        width: 100% !important;
        margin-bottom: 3px;
    }
</style>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4>Permanent Residence Certificate </h4>
                    <?php if ($this->session->flashdata('pay_message') != null) { ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong></strong> <?= $this->session->flashdata('pay_message') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($prc): ?>
                          <table class="table table-striped table-hover m-1" id="example">
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
                                foreach ($prc as $key => $value):
                                    $obj_id = $value->_id->{'$id'};
                                    $certificate = $value->form_data->certificate??'';
                                    $payment_status = $value->form_data->payment_status??'';
                                    $ppr_grn = isset($value->form_data->pfc_payment_response->GRN)?$value->form_data->pfc_payment_response->GRN:'';
                                    $ppr_status = isset($value->form_data->pfc_payment_response->STATUS)?$value->form_data->pfc_payment_response->STATUS:'';
                                    $certificatePath = (strlen($certificate)?base_url($certificate):'#')?>

                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><a href="<?= base_url('spservices/prc/Application/view/'.$obj_id)?>"><?= $value->form_data->rtps_trans_id ?></a></td>
                                        <td><?= isset($value->form_data->service_name) ? $value->form_data->service_name : "" ?></td>
                                        <td><?= isset($value->service_data->submission_date) ? format_mongo_date($value->service_data->submission_date) : "" ?></td>
                                        <td><?= $value->service_data->appl_status === "F" ? "Forwarded" :  getstatusname($value->service_data->appl_status) ?>
                                        </td>
                                        <td>
                                            <?php if ($value->service_data->appl_status =="submitted" || $value->service_data->appl_status == "D" || $value->service_data->appl_status == "QS") { ?>
                                                <a href="<?= base_url("spservices/prc/Acknowledgement/acknowledgement/") . $obj_id ?>" class="btn btn-success btn-sm mbtn" >Acknowledgement</a>
                                            <?php } if (strtolower($value->service_data->appl_status) == "draft"|| $payment_status =="INITIATED") { ?>
                                                <a href="<?= base_url('spservices/prc/Application/index/'.$obj_id)?>" class="btn btn-primary btn-sm mbtn" >Complete Your Application</a>
                                            <?php } elseif ($value->service_data->appl_status == "QS") { ?>
                                                <a href="<?= base_url('spservices/prc/Application/query/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Reply to query</a>
                                            <?php } elseif ($value->service_data->appl_status == "D") { ?>
                                                <a href="<?=$certificatePath?>" target="_blank" class="btn btn-success btn-sm mbtn" >Download/View</a>
                                            <?php } elseif ($value->service_data->appl_status == "PAID" || $value->service_data->appl_status == "payment_initiated") {
                                                if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                                    <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                        <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->form_data->pfc_payment_response->DEPARTMENT_ID?>" />
                                                        <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->form_data->payment_params->OFFICE_CODE?>" />
                                                        <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->form_data->pfc_payment_response->AMOUNT?>" />
                                                        <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                        <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/prc/PaymentResponse/cin_response')?>" />
                                                        <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                    </form>
                                                    <?php } elseif(isset($value->form_data->pfc_payment_response) && !empty($ppr_grn) && ($ppr_status === "Y")){?>
                                                     <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/prc/Application/finalsubmition/'.$obj_id)?>" >Verify Payment</a>
                                            <?php }else { ?>
                                                       <a href="<?= base_url('spservices/prc/Payment/verify/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Verify/Make payment</a> 
                                                    <?php }//End of if else ?>
                                                
                                            <?php }else if( ($value->service_data->appl_status=="payment_initiated"|| $value->service_data->appl_status=="PAYMENT_INITIATED") && $value->form_data->user_type !=="user"){?>
                                                      <a href="<?= base_url('spservices/prc/Payment/verify/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Verify/Make payment</a> 
                                            <?php }else if( ($value->service_data->appl_status=="payment_initiated"|| $value->service_data->appl_status=="PAYMENT_INITIATED") && $value->form_data->user_type==="user"){?>
                                                      <a href="<?= base_url('spservices/prc/Payment/verify/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Verify/Make payment</a>
                                            <?php } else { ?>
                                                <a href="<?=base_url('spservices/prc/Application/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn" >Track status</a>
                                            <?php } //End of if else ?>

                                            <a href="<?=base_url('spservices/prc/Application/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn" ><i class="fa fa-refresh"></i> Refresh Status</a>
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
