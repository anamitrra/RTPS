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
$applications = !empty($buildingpermissioncertificate) ? $buildingpermissioncertificate : array();
$spbuildingpermissioncertificate = !empty($spbuildingpermissioncertificate) ? $spbuildingpermissioncertificate : array();
//pre($applications);
?>


<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3">
            <?php if ($this->session->userdata('message') <> '') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success</strong>
                <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <?php if ($this->session->userdata('errmessage') <> '') { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Alert:</strong>
                <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body table-container">
                    <h4>GMDA Building cum Planning Permit </h4>
                    <?php if ((!empty($applications)) || (!empty($spbuildingpermissioncertificate))): ?>

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
                        <tbody>

                            <?php $idkey = 0; if (!empty($spbuildingpermissioncertificate)) { foreach ($spbuildingpermissioncertificate as $key => $value):
                                 ?>
                            <tr>
                                <td><?=($idkey+1)?></td>
                                <td><?= $value->appl_ref_no ?></a></td>
                                <td>Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman
                                    Achoni</td>
                                <td>
                                    <?= $value->submission_date ?>
                                </td>
                                <td>
                                    <?php
                                if (($value->appl_status == "F") || ($value->appl_status == "S"))
                                  print "Pending";
                                if ($value->appl_status == "D")
                                  print "Delivered";
                                if ($value->appl_status == "R")
                                  print "Rejected";
                                ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mbtn" onclick="showSPLoginModal();"
                                        target="_blank">Proceed</a>
                                </td>

                            </tr>
                            <?php endforeach; }?>


                            <?php if (!empty($applications)) { foreach ($applications as $key => $value):
                            $obj_id=$value->_id->{'$id'};
                                 ?>
                            <tr>
                                <td><?=($key+1)?></td>
                                <td><a href="<?=base_url("spservices/buildingpermission/registration/applicationpreview/").$obj_id ?>"
                                        target="_blank"><?= $value->service_data->appl_ref_no ?></a></td>
                                <td><?=isset($value->service_data->service_name) ? $value->service_data->service_name : ""?>
                                </td>
                                <td>
                                    <?php if(gettype($value->service_data->submission_date) == "string"){ ?>
                                    <?= $value->service_data->submission_date ?>
                                    <?php } else { ?>
                                    <?=(!empty($value->service_data->submission_date))? format_mongo_date($value->service_data->submission_date): ""?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= 
                                  ($value->service_data->appl_status == "F")? "Forwarded" : getstatusname($value->service_data->appl_status);
                                ?>
                                </td>
                                <td>
                                    <?php if($value->service_data->appl_status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn"
                                        href="<?=base_url("spservices/buildingpermission/registration/index/").$obj_id ?>">Complete
                                        Your Application</a>
                                    <?php }
                                   elseif($value->service_data->appl_status == "submitted"){?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <?php } elseif($value->service_data->appl_status == "QS"){
                                    ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/queryform/'.$obj_id)?>">Reply
                                        to query</a>
                                    <?php 
                                  }elseif($value->service_data->appl_status == "FRS"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>

                                    <?php if(($value->service_data->appl_status == "FRS") && (isset($value->form_data->frs_request->ppPaymentTypeStatus) && ($value->form_data->frs_request->ppPaymentTypeStatus == true)) && (isset($value->form_data->frs_request->bpPaymentTypeStatus) && ($value->form_data->frs_request->bpPaymentTypeStatus == true))){ ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/querypaymentsubmit/'.$obj_id)?>">Verify
                                        All Payment</a>
                                    <?php } ?>

                                    <?php if(isset($value->form_data->frs_request->bpPaymentTypeStatus) && ($value->form_data->frs_request->bpPaymentTypeStatus == false)){ if(isset($value->form_data->query_bp_payment_response) && !empty($value->form_data->query_bp_payment_response->GRN) && ($value->form_data->query_bp_payment_response->STATUS === "P" || $value->form_data->query_bp_payment_response->STATUS === "")){
										?>
                                    <form method="post" name="getGRN" id="getGRN"
                                        action="<?=$this->config->item('egras_grn_cin_url')?>">
                                        <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID"
                                            value="<?=$value->form_data->query_bp_payment_response->DEPARTMENT_ID?>" />
                                        <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE"
                                            value="<?=$value->form_data->query_bp_payment_params->OFFICE_CODE?>" />
                                        <input type="hidden" id="AMOUNT" name="AMOUNT"
                                            value="<?=$value->form_data->query_bp_payment_response->AMOUNT?>" />
                                        <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN"
                                            readonly />
                                        <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM"
                                            value="ARTPS-SP|<?=base_url('spservices/buildingpermission/payment_bp_query_response/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment for Building Permit" />
                                    </form>
                                    <?php
									}elseif(isset($value->form_data->query_bp_payment_response) && !empty($value->form_data->query_bp_payment_response->GRN) && ($value->form_data->query_bp_payment_response->STATUS === "Y")){
									  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/querypaymentsubmit/'.$obj_id.'/bp')?>">Verify
                                        Payment for
                                        Building Permit</a>
                                    <?php
									}else{  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/query_bp_payment_break_down/'.$obj_id)?>">Make
                                        Payment for Building Permit</a>
                                    <?php } }?>

                                    <?php if(isset($value->form_data->frs_request->ppPaymentTypeStatus) && ($value->form_data->frs_request->ppPaymentTypeStatus == false)){ if(isset($value->form_data->query_pp_payment_response) && !empty($value->form_data->query_pp_payment_response->GRN) && ($value->form_data->query_pp_payment_response->STATUS === "P" || $value->form_data->query_pp_payment_response->STATUS === "")){
										?>
                                    <form method="post" name="getGRN" id="getGRN"
                                        action="<?=$this->config->item('egras_grn_cin_url')?>">
                                        <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID"
                                            value="<?=$value->form_data->query_pp_payment_response->DEPARTMENT_ID?>" />
                                        <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE"
                                            value="<?=$value->form_data->query_pp_payment_params->OFFICE_CODE?>" />
                                        <input type="hidden" id="AMOUNT" name="AMOUNT"
                                            value="<?=$value->form_data->query_pp_payment_response->AMOUNT?>" />
                                        <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN"
                                            readonly />
                                        <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM"
                                            value="ARTPS-SP|<?=base_url('spservices/buildingpermission/payment_pp_query_response/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment for Planning Permit" />
                                    </form>
                                    <?php
									}elseif(isset($value->form_data->query_pp_payment_response) && !empty($value->form_data->query_pp_payment_response->GRN) && ($value->form_data->query_pp_payment_response->STATUS === "Y")){
									  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/querypaymentsubmit/'.$obj_id.'/pp')?>">Verify
                                        Payment for
                                        Planning Permit</a>
                                    <?php
									}else{  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/query_pp_payment_break_down/'.$obj_id)?>">Make
                                        Payment for Planning Permit</a>
                                    <?php } }?>

                                    <?php }elseif($value->service_data->appl_status == "payment_initiated"){ ?>

                                    <?php if(isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "P" || $value->form_data->pfc_payment_response->STATUS === "")){
                                              ?>
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
                                            value="ARTPS-SP|<?=base_url('spservices/buildingpermission/payment_response/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment1" />
                                    </form>
                                    <?php
                                          }elseif(isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")){
                                            ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/submit_to_backend/'.$obj_id)?>">Verify
                                        Payment</a>
                                    <?php
                                          }else{  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/payment/verify/'.$obj_id)?>">Make/Verify
                                        Payment</a>
                                    <?php }
                                          ?>
                                    <?php }elseif($value->service_data->appl_status == "S" || $value->service_data->appl_status == "QA" || $value->service_data->appl_status == "PR" || $value->service_data->appl_status == "R" || $value->service_data->appl_status == "F" ){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>"
                                        target="_blank">Acknowledgement</a>
                                    <?php if((isset($value->form_data->frs_request->bpPaymentTypeStatus) && !empty($value->form_data->frs_request->bpPaymentTypeStatus) == true) && (isset($value->form_data->frs_request->ppPaymentTypeStatus) && !empty($value->form_data->frs_request->ppPaymentTypeStatus) == true)){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/payment_acknowledgement/'.$obj_id)?>">Payment
                                        Acknowledgement</a>
                                    <?php } ?>
                                    <?php } 
                                   elseif($value->service_data->appl_status == "D"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>

                                    <?php if((isset($value->form_data->frs_request->bpPaymentTypeStatus) && !empty($value->form_data->frs_request->bpPaymentTypeStatus) == true) && (isset($value->form_data->frs_request->ppPaymentTypeStatus) && !empty($value->form_data->frs_request->ppPaymentTypeStatus) == true)){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?= base_url('spservices/buildingpermission/registration/payment_acknowledgement/'.$obj_id)?>">Payment
                                        Acknowledgement</a>
                                    <?php } ?>

                                    <?php if(!empty($value->form_data->certificates->drawingDoc)){ ?>
                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?=base_url($value->form_data->certificates->drawingDoc)?>">Download
                                        Drawing Document</a>
                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?=base_url($value->form_data->certificates->palnningPermitDoc)?>">Download
                                        Planning Permit</a>
                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?=base_url($value->form_data->certificates->buildingPermitDoc)?>">Download
                                        Building Permit</a>
                                    <?php } ?>
                                    <?php if((isset($value->form_data->app_record_type)) && (!empty($value->form_data->app_record_type) != "reapply_done") && $value->service_data->appl_status == "D"){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url('spservices/buildingpermission/registration/cancel_form/'.$obj_id)?>">Proceed
                                        for Permit Cancellation
                                    </a>
                                    <?php } ?>
                                    <?php }
                                  ?>

                                    <a href="<?=base_url('spservices/buildingpermission/registration/track/'.$obj_id)?>"
                                        class="btn btn-secondary btn-sm mbtn">Track</a>
                                </td>

                            </tr>
                            <?php endforeach; }?>
                        </tbody>
                    </table>

                    <?php else: ?>
                    <p>No Application Found
                    <p>
                        <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
/* Login Model */
function showSPLoginModal() {

    const domain = `${window.location.origin}/services`;
    const sendurl = domain + "/directApply.do?serviceId=1886";
    const frame = '<iframe is="x-frame-bypass" id="iframeIdLogin" src="' + sendurl +
        '" style="width: 100%; height: 430px; border: none;"></iframe>';


    Swal.fire({
        html: frame,
        showCloseButton: true,
        confirmButtonText: 'Close',
        confirmButtonAriaLabel: 'Close',

    });




    // const iFrame = document.getElementById('iframeIdLogin');
    // console.log(iFrame);
    // iFrame.addEventListener('load', function (event) {

    //     const preTag = iFrame.contentWindow.document.querySelector('pre');
    //     // console.log(preTag);

    //     if (preTag !== null) {
    //         iFrame.contentWindow.document.body.innerHTML = `
    //         <p> Your Session may not be Closed Properly.</p>
    //         <a href="${domain}" target="_top">Please Click Here to Re-login</a>
    //         `;

    //         // console.log('Do the Work');
    //     }

    // });

}
</script>