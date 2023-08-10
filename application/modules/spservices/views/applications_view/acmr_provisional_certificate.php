<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
.parsley-errors-list {
    color: red;
}

.mbtn {
    width: 100% !important;
    margin-bottom: 3px;
}
</style>
<?php 
$applications = !empty($acmrprovisionalcertificate) ? $acmrprovisionalcertificate : array();

//pre($applications);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4>PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR</h4>
                    <?php if (!empty($applications)): ?>

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
                            <?php foreach ($applications as $key => $value):
                            $obj_id=$value->_id->{'$id'};
                            $ppr_grn = isset($value->form_data->pfc_payment_response->GRN)?$value->form_data->pfc_payment_response->GRN:'';
                            $ppr_status = isset($value->form_data->pfc_payment_response->STATUS)?$value->form_data->pfc_payment_response->STATUS:'';
                            ?>
                            <tr>
                                <td><?=($key+1)?></td>
                                <td><a href="<?=base_url("spservices/acmr_provisional_certificate/registration/applicationpreview/").$obj_id ?>"
                                        target="_blank"><?= $value->service_data->appl_ref_no ?></a></td>
                                <td><?=isset($value->service_data->service_name) ? $value->service_data->service_name : ""?>
                                </td>
                                <td><?=(!empty($value->service_data->submission_date))? format_mongo_date($value->service_data->submission_date): ""?>
                                </td>
                                <td>
                                    <?=($value->service_data->appl_status == "F")? "Forwarded" : getstatusname($value->service_data->appl_status);?>
                                </td>
                                <td>
                                    <?php if($value->service_data->appl_status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn"
                                        href="<?=base_url("spservices/acmr_provisional_certificate/registration/index/").$obj_id ?>">Complete
                                        Your Application</a>
                                    <?php }
                                   elseif($value->service_data->appl_status == "submitted"){?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <?php } elseif($value->service_data->appl_status == "QS"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/acmr_provisional_certificate/registration/queryform/'.$obj_id)?>">Reply
                                        to query</a>
                                    <?php }
                                  elseif($value->service_data->appl_status == "payment_initiated"){ 
                                    if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                    <form method="post" name="getGRN" id="getGRN"
                                        action="<?=$this->config->item('egras_grn_cin_url')?>">
                                        <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID"
                                            value="<?=$value->form_data->pfc_payment_response->DEPARTMENT_ID?>" />
                                        <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE"
                                            value="<?=$value->form_data->payment_params->OFFICE_CODE?>" />
                                        <input type="hidden" id="AMOUNT" name="AMOUNT"
                                            value="<?=$value->form_data->pfc_payment_response->AMOUNT?>" />
                                        <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN"
                                            readonly />
                                        <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM"
                                            value="ARTPS-SP|<?=base_url('spservices/acmr_provisional_certificate/PaymentResponse/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment" />
                                    </form>
                                   <?php } else if (isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")) {?>
                                    <?php if ($value->form_data->service_id === "ACMRPRCMD") { ?>
                                        <a class="btn btn-warning btn-sm mbtn"
                                            href="<?= base_url('spservices/acmr_provisional_certificate/Registration/finalsubmition/'.$obj_id)?>">Verify Payment</a>
                                    <?php } // End of if else ?> 

                                    <?php } else{?>
                                        <a class="btn btn-warning btn-sm mbtn"
                                            href="<?= base_url('spservices/acmr_provisional_certificate/Payment/initiate/'.$obj_id)?>">Make/Verify Payment</a>
                                    </form>
                                    <?php }//End of if else ?>
                                    <?php 
                                      }elseif($value->service_data->appl_status == "FRS"){ 
                                     $qpr_grn = isset($value->form_data->query_payment_response->GRN)?$value->form_data->query_payment_response->GRN:'';
                                     $qpr_status = isset($value->form_data->query_payment_response->STATUS)?$value->form_data->query_payment_response->STATUS:'';
                                  
                                    if(((strlen($qpr_grn)) && ($qpr_status === "P")) || ((strlen($qpr_grn)) && ($qpr_status === ""))){ ?>
                                    <form method="post" name="getGRN" id="getGRN"
                                        action="<?=$this->config->item('egras_grn_cin_url')?>">
                                        <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID"
                                            value="<?=$value->form_data->pfc_payment_response->DEPARTMENT_ID?>" />
                                        <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE"
                                            value="<?=$value->form_data->payment_params->OFFICE_CODE?>" />
                                        <input type="hidden" id="AMOUNT" name="AMOUNT"
                                            value="<?=$value->form_data->pfc_payment_response->AMOUNT?>" />
                                        <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN"
                                            readonly />
                                        <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM"
                                            value="ARTPS-SP|<?=base_url('spservices/acmr_provisional_certificate/Payment_Response/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment2" />
                                    </form>
                                    <?php } else if(isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")){?>
                                        <?php if ($value->form_data->service_id === "ACMRPRCMD") {?>
                                            <a class="btn btn-warning btn-sm mbtn"
                                                href="<?= base_url('spservices/acmr_provisional_certificate/Registration/finalsubmition/'.$obj_id)?>">Verify Payment</a>
                                        <?php }//End of if else ?>
                                    <?php } else{?>
                                        <a class="btn btn-warning btn-sm mbtn"
                                            href="<?= base_url('spservices/acmr_provisional_certificate/registration/querypaymentsubmit/'.$obj_id)?>">Make/Verify Payment</a>
                                    </form>
                                    <?php }//End of if else ?>
                                <?php 
                                  }elseif($value->service_data->appl_status == "QA"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <?php if(isset($value->form_data->query_payment_status) == true){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?= base_url('spservices/acmr_provisional_certificate/registration/payment_ack/'.$obj_id)?>">Payment Acknowledgement</a>
                                    <?php } ?>
                                <?php }elseif($value->service_data->appl_status == "S" || $value->service_data->appl_status == "QA" || $value->service_data->appl_status == "PR" || $value->service_data->appl_status == "R" || $value->service_data->appl_status == "AF" || $value->service_data->appl_status == "AA"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>"
                                        target="_blank">Acknowledgement</a>
                                <?php } 
                                   elseif($value->service_data->appl_status == "D"){ ?>
                                     <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <?php if(isset($value->form_data->query_payment_status) == true){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?= base_url('spservices/acmr_provisional_certificate/registration/payment_ack/'.$obj_id)?>">Payment Acknowledgement</a>
                                    <?php } ?>
                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?=base_url($value->form_data->certificate)?>">Download Certificate</a>
                                <?php }
                                  ?>
                                    <a href="<?=base_url('spservices/acmr_provisional_certificate/registration/track/'.$obj_id)?>"
                                        class="btn btn-secondary btn-sm mbtn">Track</a>
                                    <a href="<?=base_url('spservices/acmr_provisional_certificate/registration/track/'.$obj_id)?>"
                                        class="btn btn-secondary btn-sm mbtn"><i class="fa fa-refresh"></i> Refresh
                                        Status</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>No Application Found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
