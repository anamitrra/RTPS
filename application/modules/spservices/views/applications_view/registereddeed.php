<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
      width: 100% !important;
      margin-bottom: 3px;
    }
</style>
<?php 
$applications = !empty($certifiedcopies) ? $certifiedcopies : array()
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h4>Register deed </h4>
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
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->service_name) ? $value->service_name : ""?></td>
                              <td><?=isset($value->submission_date)? format_mongo_date($value->submission_date): ""?></td>
                              <td><?= getstatusname($value->status)?></td>  
                              <td>
                                  <?php if($value->status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url("spservices/registereddeed/index/").$obj_id ?>" >Complete Your Application</a>
                                  <?php }
                                   elseif($value->status == "submitted"){?>
                                        <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>" >Acknowledgement</a>
                                  <?php } elseif($value->status == "QS"){
                                    ?>
                                    <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/registereddeed/query/'.$obj_id)?>" >Reply to query</a>
                                    <?php 
                                  }elseif($value->status == "FRS"){ 
                                    if(property_exists($value,'query')){
                                     if( property_exists($value,'query_payment_status') &&  $value->query_payment_status != 'Y' ){ ?>
                                      <a class="btn btn-success btn-sm mbtn" title="Query Payment" href="<?=base_url("spservices/payment/querypayment/").$obj_id ?>" >Make/Verify Payment</a>
                                     <?php }elseif(!property_exists($value,'query_payment_status') || !property_exists($value,'query_payment_response')){ ?>
                                      <a class="btn btn-success btn-sm mbtn" title="Query Payment" href="<?=base_url("spservices/payment/querypayment/").$obj_id ?>" >Make/Verify Payment</a>
                                     <?php }elseif( (property_exists($value,'query_payment_response') && $value->query_payment_response->BANKCIN === "")){ ?>
                                      <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->query_payment_response->DEPARTMENT_ID?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->query_payment_params->OFFICE_CODE?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->query_payment_response->AMOUNT?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/get/query/cin-response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                  </form>
                                     <?php }


                                      if( property_exists($value,'query_payment_status') &&  $value->query_payment_status == 'Y' ){ ?>
                                        <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/query_payment_response/update_payment_status/").$value->query_department_id ?>" >Refresh Status</a>
                                      <?php } 
                                            
                                      }
                                  }elseif($value->status == "payment_initiated"){ ?>
                                         
                                          <?php if(isset($value->pfc_payment_response) && !empty($value->pfc_payment_response->GRN) && ($value->pfc_payment_response->STATUS === "P" || $value->pfc_payment_response->STATUS === "")){
                                              ?>
                                               <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/get/cin-response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment1" />
                                                  </form>
                                              <?php
                                          }else{  ?>
                                              <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/payment/verify/'.$obj_id)?>" >Make/Verify Payment</a>
                                          <?php }
                                          ?>
                                  <?php }elseif($value->status == "S" || $value->status == "QA" || $value->status == "PR" || $value->status == "R" ){ ?>
                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>" >Acknowledgement</a>
                                  <?php } 
                                   elseif($value->status == "D"){ ?>
                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>" >Acknowledgement</a>
                                    <a class="btn btn-primary btn-sm mbtn"  target="_blank" href="<?=base_url($value->certificate_path)?>" >Download Certificate</a>
                                  <?php }
                                  ?>
                                 
                                   <a href="<?=base_url('spservices/necertificate/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn" >Track</a>
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
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
