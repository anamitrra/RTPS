<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn, .rejectBtn{
      width: 100% !important;
      margin-bottom: 3px;
    }
</style>
<?php 
$applications = !empty($apdcl) ? $apdcl : array();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h4>APDCL Services </h4>
                  <?php if (!empty($applications)): ?>

                        <table class="table">
                          <thead>
                              <tr>
                                  <th width="5%">#</th>
                                  <th>Application Ref No</th>
                                  <th>Service Name</th>
                                  <th>Application No</th>
                                  <th>Submission Date</th>
                                  <th>Status</th>
                                  <th class="text-center">Action</th>
                              </tr>
                          </thead>
                          <tbody id="">
                            <?php foreach ($applications as $key => $value):
                            $obj_id=$value->_id->{'$id'};
                                 ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <td><?= $value->service_data->appl_ref_no ?></td>
                              <td><?=isset($value->service_data->service_name) ? $value->service_data->service_name : ""?></td>
                              <td><?=isset($value->form_data->application_no) ? $value->form_data->application_no : ""?></td>
                              <td><?=isset($value->service_data->submission_date)? format_mongo_date($value->service_data->submission_date): ""?></td>
                              <td><?php if($value->form_data->message == "success") { ?>
                                  <?= getstatusname($value->service_data->appl_status)?>
                                  <?php } elseif($value->form_data->message == "rejected") { ?> Rejected 
                                  <?php } elseif($value->form_data->message == ""){ ?>
                                          <?php if($value->service_data->appl_status == "payment_initiated"){ ?>Payment Initiated 
                                          <?php } elseif($value->service_data->appl_status == "PR" && $value->form_data->message == ""){ ?>Payment Received 
                                          <?php } else { echo $value->service_data->appl_status; } 
                                        }?>
                              </td>
                              <td>
                                  <?php if($value->service_data->appl_status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url("spservices/apdcl/registration/index/").$obj_id ?>" >Complete Your Application</a>
                                  <?php }
                                   elseif($value->service_data->appl_status == "submitted"){?>
                                        <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>" >Acknowledgement</a>
                                  <?php 
                                  }elseif($value->service_data->appl_status == "FRS"){ 
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
                                  }elseif($value->service_data->appl_status == "payment_initiated"){ ?>
                                         
                                          <?php if(isset($value->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "P" || $value->pfc_payment_response->STATUS === "")){
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
                                              <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/apdcl/payment/verify/'.$obj_id)?>" >Make/Verify Payment</a>
                                          <?php }
                                          ?>
                                  <?php }elseif( $value->service_data->appl_status == "PR"){ ?>
                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url("spservices/apdcl/registration/index/").$obj_id ?>" >Complete Your Application</a>
                                  <?php } 
                                   elseif($value->service_data->appl_status == "D"){ ?>
                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/applications/acknowledgement/").$obj_id ?>" >Acknowledgement</a>
                                    <!-- <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?=base_url($value->form_data->certificate_path)?>" >Download Certificate</a> -->
                                  <?php }
                                  ?>
                                 
                                   <a href="<?=base_url('spservices/apdcl/registration/trackAPI/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn" >Track/Download</a>
                                   
                                   <?php if($value->form_data->message == "rejected") { ?>
                                   <a class="btn btn-info btn-sm rejectBtn text-white">Reason of Rejection</a>
                                   <?php } ?>
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
<script>
  $(document).ready(function(){
    $('.rejectBtn').click(function(){
            Swal.fire({
                  title: 'Attention Please !',
                  text: 'Your application for new electricity connection is rejected. You have outstanding due in your premises. Please contact your nearest sub-division',
                  
              })
    });
  });
</script>
