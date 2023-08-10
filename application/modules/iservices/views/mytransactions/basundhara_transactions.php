<?php 
$user=$this->session->userdata();
$mb=["243","244","245","246","247","248"];
$PATTA=['243','244','245','246','247','248' ];
$ORDER=['243','244','245','246','247','248'];
$EKHAJANA=['249'];
$ROR=['242'];
$role="user";
if(isset($user['role']) && !empty($user['role'])){
  $role="admin";
}else{
  $role="user";
}
?>
<div class="card my-4">
                <div class="card-body table-container">
                  <h4>Basundhara Services </h4>
                  <?php if (!empty($intermediate_ids['basundhara_services'])): ?>

                       <table class="table table-striped table-hover m-1" id="example">
                          <thead>
                              <tr>
                                  <th width="5%">#</th>
                                  <th>Transaction Id</th>
                                  <th>Service Name</th>
                                  <th>Application No</th>
                                  <th>Initiate Date</th>
                                  <th>Submission Date</th>
                                  <th>Status</th>
                                  <!-- <th>Payment Status</th> -->
                                  <th>Action</th>
                                  <!-- <th>Payment Data</th> -->
                              </tr>
                          </thead>
                          <tbody id="">
                            <?php foreach ($intermediate_ids['basundhara_services'] as $key => $value): ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->service_name) ? $value->service_name:'' ?></td>
                              <td><?=isset($value->app_ref_no) ? $value->app_ref_no : (isset($value->vahan_app_no) ? $value->vahan_app_no:'' )?></td>
                              <td><?=format_mongo_date($value->createdDtm)?></td>
                              <td><?=isset($value->submission_date)? $value->submission_date: ""?></td>

                              <td>
                                <?php
                                if(!empty($value->payment_config)){
                                      if(isset($value->pfc_payment_status)){ ?>
                                        <?= isset($value->pfc_payment_status) ? get_status($value->pfc_payment_response->STATUS) : "Pending"?>
                                      <?php }else{
                                        echo "Pending";
                                      }
                                }else{ ?>
                                  <?= !empty($value->status) ?  get_status($value->status) : "Pending"?>
                                 <?php } 
                                ?>


                              </td>

                              <td>
                              <?php if(isset($value->applied_by) && $role==="user" ){ ?>
                                          <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                           
                                            <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                          <?php endif; ?>
                                          <?php 
                                          if(isset($value->app_ref_no)){?>
                                                   <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track / Download</a>
                                                   <a class="btn btn-success btn-sm mbtn" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Refresh</a>
                                          <?php }
                                          ?>
                                         
                                    <?php }else {
                                  if($value->status === "S"){

                                    if($role==="user"){
                                         //application status is success need to check for payment status
                                                    if( !empty($value->rtps_con_fee_required) || !empty($value->payment_config)){
                                                      if(!property_exists($value,'pfc_payment_status')){ ?>
                                                      <?php if(!in_array($value->service_id,$mb)){ ?>
                                                        <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Pay Or Verify Payment</a>
                                                      <?php } ?>
                                                       
                                                    <?php }elseif( property_exists($value,'pfc_payment_status') && $value->pfc_payment_status !="Y"){  ?>
                                                        
                                                      <?php if(isset($value->pfc_payment_status) && ($value->pfc_payment_status === "N" || $value->pfc_payment_status === "A")){ ?>
                                                            <!-- <a href="base_url('iservices/basundhara/retry-pfc-payment/').$value->rtps_trans_id?>" class="btn btn-sm btn-warning mbtn white">Retry Payment</a> -->
                                                            <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Pay Or Verify Payment</a>
                                                      <?php  }else { 
                                                                        if(isset($value->pfc_payment_response) && $value->pfc_payment_response->GRN === ""){?>
                                                                            <a href="<?=base_url('iservices/basundhara/check-grn-status/').$value->pfc_payment_response->DEPARTMENT_ID?>" class="btn btn-sm btn-primary mbtn">Verify Payment</a>
                                                                      <?php }elseif(isset($value->pfc_payment_response) && $value->pfc_payment_response->BANKCIN === ""){ ?>
                                                                        <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                          <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                          <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                          <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                          <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                          <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/basundhara/get/cin-response')?>" />
                                                                          <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                                        </form>
                                                                        <?php }elseif(isset($value->pfc_payment_response) && $value->pfc_payment_response->STATUS==="P"){ ?>
                                                                          <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                          <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                          <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                          <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                          <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                          <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/basundhara/get/cin-response')?>" />
                                                                          <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                                        </form>
                                                                        <?php } 
                                                                    }
                                                    }
                                                          
                                                    else{ ?>
                                                      <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                                      <?php if(in_array($value->service_id,$PATTA)){ ?>
                                                        <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/basundhara/download/").$value->rtps_trans_id?>?type=PATTA" >Download Patta Certificate</a>
                                                      <?php } ?>
                                                      <?php if(in_array($value->service_id,$ORDER)){ ?>
                                                        <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/basundhara/download/").$value->rtps_trans_id?>?type=ORDER" >Download Order Certificate</a>
                                                      <?php } ?>
                                                      <?php if(in_array($value->service_id,$ROR)){ ?>
                                                        <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/basundhara/download/").$value->rtps_trans_id?>?type=ROR" >Download Certificate</a>
                                                      <?php } ?> 
                                                      <?php if(in_array($value->service_id,$EKHAJANA)){ ?>
                                                        <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/basundhara/download/").$value->rtps_trans_id?>?type=EKHAJANA" >Download Certificate</a>
                                                      <?php } ?>
                                                      <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track / Download</a>
                                                      <a class="btn btn-success btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Refresh</a>
                                                    <?php }
                                              }else{
                                                ?>
                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                                <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track / Download</a>
                                                <a class="btn btn-success btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Refresh</a>
                                              <?php 
                                              }


                                              //query payment
                                              if(property_exists($value,"query_status") &&  $value->query_status === "PQ"){ ?>
                                                   <a class="btn btn-warning btn-sm mbtn" title="Query payment made by department"  href="<?=base_url()?>iservices/basundhara/query_payment/payment/<?=$value->rtps_trans_id?>" >Make Payment</a>
                                              <?php }
                                             
                                    }else{
                                      // for addmin
                                       //query payment
                                       if(property_exists($value,"query_status") &&  $value->query_status === "PQ"){ ?>
                                        <a class="btn btn-warning btn-sm mbtn" title="Query payment made by department"  href="<?=base_url()?>iservices/basundhara/query_payment/payment/<?=$value->rtps_trans_id?>" >Make Payment</a>

                                                  <?php if(isset($value->query_payment_response) && (!empty($value->query_payment_response->GRN)) && empty($value->query_payment_response->STATUS)){ ?>
                                                                  <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                  <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->query_payment_response->DEPARTMENT_ID?>" />
                                                                  <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->query_payment_params->OFFICE_CODE?>" />
                                                                  <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->query_payment_response->AMOUNT?>" />
                                                                  <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                  <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/basundhara/query_payment_response/cin_response')?>" />
                                                                  <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-primary mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                                </form>
                                                      <?php }  ?>
                                        <?php }
                                              if(!property_exists($value,'pfc_payment_status')){ ?>
                                                <?php if(!in_array($value->service_id,$mb)){ ?>
                                                  <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Pay Or Verify Payment</a>
                                                <?php } ?>
                                            <?php }elseif( property_exists($value,'pfc_payment_status') && $value->pfc_payment_status !="Y"){  ?>
                                                
                                              <?php if(isset($value->pfc_payment_status) && ($value->pfc_payment_status === "N" || $value->pfc_payment_status === "A")){ ?>
                                                    <!-- <a href="<?=base_url('iservices/basundhara/retry-pfc-payment/').$value->rtps_trans_id?>" class="btn btn-sm btn-warning mbtn white">Retry Payment</a> -->
                                                    <?php if(!in_array($value->service_id,$mb)){ ?>
                                                      <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Pay Or Verify Payment</a>
                                                    <?php } ?>
                                                    
                                              <?php  }else { 
                                                                if(isset($value->pfc_payment_response) && $value->pfc_payment_response->GRN === ""){?>
                                                                    <!-- <a href="<?=base_url('iservices/basundhara/check-grn-status/').$value->pfc_payment_response->DEPARTMENT_ID?>" class="btn btn-sm btn-primary mbtn">Verify Payment</a> -->
                                                                    <?php if(!in_array($value->service_id,$mb)){ ?>
                                                                      <a class="btn btn-primary  btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Pay Or Verify Payment</a>
                                                                    <?php } ?>
                                                              <?php }elseif(isset($value->pfc_payment_response) && $value->pfc_payment_response->BANKCIN === ""){ ?>
                                                                <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                  <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                  <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                  <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                  <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                  <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/basundhara/get/cin-response')?>" />
                                                                  <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                                </form>
                                                                <?php }elseif(isset($value->pfc_payment_response) && (!empty($value->pfc_payment_response->GRN)) && ($value->pfc_payment_response->STATUS==="P")){ ?>
                                                                  <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                  <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                  <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                  <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                  <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                  <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/basundhara/get/cin-response')?>" />
                                                                  <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                                </form>
                                                                <?php } 
                                                            }
                                            }
                                                  
                                            else{ ?>
                                              <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                              <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track / Download</a>
                                              <a class="btn btn-success btn-sm mbtn" target="_blank" href="<?=base_url()?>iservices/basundhara/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Refresh</a>
                                            <?php }

                                              
                                      
                                    }
                                   
                                  }else{
                                            if ($value->status === "F"){?>
                                              <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/basundhara/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                            <?php }else{
                                              //pending cases 
                                              ?>
                                              <a href="<?=base_url("iservices/basundhara/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Complete Your Application</button>
                                              <?php if(empty($value->app_ref_no)){ ?>
                                                <!-- <a onclick="return confirm('Are you sure?')" href="<?=base_url("iservices/application/archive/").$value->rtps_trans_id?>" class="btn btn-sm btn-warning mbtn">Archive Application</button> -->
                                              <?php }?>
                                              
                                            <?php }
                                  }
                                 } ?>
                              </td>
                              <!-- <td>
                                    <?php if(isset($value->applied_by)){ ?>
                                          <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                          <?php endif; ?>
                                    <?php }else {
                                         if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                              <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <?php endif; ?>
                                          <?php if ($value->status ==="F"): ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/basundhara/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                          <?php endif; ?>
                                            <?php if ($value->status ==="" || $value->status ==="P"): ?>
                                              <a href="<?=base_url("iservices/basundhara/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary">Re-try</button>
                                            <?php endif; ?>
                                            <?php if ($value->status === "S"):
                                               if(!property_exists($value,'pfc_payment_status')){ ?>
                                                      <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Initiate Payment</a>
                                               <?php }elseif( property_exists($value,'pfc_payment_status') && $value->pfc_payment_status !="Y"){ ?>
                                                     <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/basundhara/payment/").$value->rtps_trans_id?>" >Initiate Payment</a>
                                               <?php }
                                               else{ ?>
                                                 <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                               <?php }
                                              ?>
                                              
                                            <?php endif; ?>
                                        <?php }
                                      
                                      ?>
                                </td> -->
                              
                              </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>

                  <?php else: ?>
                      <p>No Transactions Found<p>
                  <?php endif; ?>

                </div>
            </div>