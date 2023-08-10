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
$applications = !empty($ahsecregcardduplicatecertificate) ? $ahsecregcardduplicatecertificate : array();

//pre($applications);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body table-container">
                    <h4>AHSEC - Duplicate Registration Card </h4>
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
                                 ?>
                            <tr>
                                <td><?=($key+1)?></td>
                                <td><a href="<?=base_url("spservices/duplicatecertificateahsec/registration/applicationpreview/").$obj_id ?>"
                                        target="_blank"><?= $value->service_data->appl_ref_no ?></a></td>
                                <td><?=isset($value->service_data->service_name) ? $value->service_data->service_name : ""?>
                                </td>
                                <td><?=(!empty($value->service_data->submission_date))? format_mongo_date($value->service_data->submission_date): ""?>
                                </td>
                                <td>
                                    <?= 
                                  ($value->service_data->appl_status == "F")? "Forwarded" : getstatusname($value->service_data->appl_status);
                                ?>
                                </td>
                                <td>
                                    <?php if($value->service_data->appl_status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn"
                                        href="<?=base_url("spservices/duplicatecertificateahsec/registration/index/").$obj_id ?>">Complete
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
                                        href="<?= base_url('spservices/duplicatecertificateahsec/registration/queryform/'.$obj_id)?>">Reply
                                        to query</a>
                                    <?php 
                                  }elseif($value->service_data->appl_status == "FRS"){ 
                                    if(property_exists($value,'query')){
                                     if( property_exists($value,'query_payment_status') &&  $value->query_payment_status != 'Y' ){ ?>
                                    <a class="btn btn-success btn-sm mbtn" title="Query Payment"
                                        href="<?=base_url("spservices/nextofkin/payment/querypayment/").$obj_id ?>">Make/Verify
                                        Payment</a>
                                    <?php }elseif(!property_exists($value,'query_payment_status') || !property_exists($value,'query_payment_response')){ ?>
                                    <a class="btn btn-success btn-sm mbtn" title="Query Payment"
                                        href="<?=base_url("spservices/nextofkin/payment/querypayment/").$obj_id ?>">Make/Verify
                                        Payment</a>
                                    <?php }elseif( (property_exists($value,'query_payment_response') && $value->query_payment_response->BANKCIN === "")){ ?>
                                    <form method="post" name="getGRN" id="getGRN"
                                        action="<?=$this->config->item('egras_grn_cin_url')?>">
                                        <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID"
                                            value="<?=$value->query_payment_response->DEPARTMENT_ID?>" />
                                        <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE"
                                            value="<?=$value->query_payment_params->OFFICE_CODE?>" />
                                        <input type="hidden" id="AMOUNT" name="AMOUNT"
                                            value="<?=$value->query_payment_response->AMOUNT?>" />
                                        <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN"
                                            readonly />
                                        <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM"
                                            value="ARTPS-SP|<?=base_url('spservices/get/query/cin-response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment" />
                                    </form>
                                    <?php }


                                      if( property_exists($value,'query_payment_status') &&  $value->query_payment_status == 'Y' ){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/query_payment_response/update_payment_status/").$value->query_department_id ?>">Refresh
                                        Status</a>
                                    <?php } 
                                            
                                      }
                                  }elseif($value->service_data->appl_status == "payment_initiated"){ ?>

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
                                            value="ARTPS-SP|<?=base_url('spservices/duplicatecertificateahsec/payment_response/cin_response')?>" />
                                        <input type="submit" style="margin-top: 3px;color:white" id="submit"
                                            class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK"
                                            value="Verify Payment1" />
                                    </form>
                                    <?php
                                          }elseif(isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")){
                                            ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/duplicatecertificateahsec/registration/submit_to_backend/'.$obj_id)?>">Verify
                                        Payment</a>
                                    <?php
                                          }else{  ?>
                                    <a class="btn btn-warning btn-sm mbtn"
                                        href="<?= base_url('spservices/duplicatecertificateahsec/payment/verify/'.$obj_id)?>">Make/Verify
                                        Payment</a>
                                    <?php }
                                          ?>
                                    <?php }elseif($value->service_data->appl_status == "S" || $value->service_data->appl_status == "QA" || $value->service_data->appl_status == "PR" || $value->service_data->appl_status == "R" || $value->service_data->appl_status == "AF" ){ ?>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>"
                                        target="_blank">Acknowledgement</a>
                                    <?php } 
                                   elseif($value->service_data->appl_status == "D"){ ?>
                                    <a class="btn btn-danger btn-sm mbtn"
                                        href="<?= base_url('spservices/duplicatecertificateahsec/registration/request_for_recorrection/'.$obj_id)?>">Request
                                        for re-correction</a>
                                    <a class="btn btn-success btn-sm mbtn"
                                        href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>">Acknowledgement</a>
                                    <?php if($value->service_data->service_id=='AHSECDRC') { ?>

                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?= base_url('spservices/duplicatecertificateahsec/registration/download_certificate/'.$obj_id)?>">
                                        Download Certificate</a>
                                    <?php } else { ?>
                                    <a class="btn btn-primary btn-sm mbtn" target="_blank"
                                        href="<?=base_url($value->form_data->certificate)?>">Download Certificate</a>

                                    <?php } ?>
                                    <?php }
                                  ?>
                                    <a target="_blank"
                                        href="<?=base_url('spservices/duplicatecertificateahsec/registration/track/'.$obj_id)?>"
                                        class="btn btn-secondary btn-sm mbtn">Track</a>
                                    <a href="<?=base_url('spservices/duplicatecertificateahsec/registration/track/'.$obj_id)?>"
                                        class="btn btn-secondary btn-sm mbtn"><i class="fa fa-refresh"></i> Refresh
                                        Status</a>
                                </td>

                            </tr>
                            <?php endforeach; ?>
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