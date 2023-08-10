
<div class="card my-4">
                <div class="card-body table-container">
                  <h4>NOC Services </h4>
                  <?php if (!empty($intermediate_ids['noc_services'])): ?>

                        <table class="table">
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
                            <?php foreach ($intermediate_ids['noc_services'] as $key => $value):
                            $is_payment_rtps_end= (property_exists($value,'payment_rtps_end') && $value->payment_rtps_end) ? true : false;
                            $app_status='';
                               ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->PurposeDescription) ? $value->PurposeDescription : (isset($value->service_name) ? $value->service_name:'' )?></td>
                              <td><?=isset($value->app_ref_no) ? $value->app_ref_no : (isset($value->vahan_app_no) ? $value->vahan_app_no:'' )?></td>
                              <td><?=format_mongo_date($value->createdDtm)?></td>
                              <td><?=isset($value->submission_date)? $value->submission_date: ""?></td>

                              <td>
                                <?php // TODO: status should be check with payment status and application status ?>
                                <?php if (isset($value->payment)): ?>
                                      <?= isset($value->payment) ? get_status($value->payment->STATUS) : "Pending"?>
                                <?php else: ?>
                                    <?= $value->status? get_status($value->status): "Pending" ?>
                                <?php endif; ?>

                              </td>
                                <?php if($is_payment_rtps_end){ ?>
                                  <td>
                                            <!-- this is for other service then vahan -->
                                            <?php if($value->status === "S"){  ?>
                                                  <!-- check for pfc payment status -->
                                                  <?php if(isset($value->pfc_payment_status)){ ?>
                                                    <?php  if (!empty($value->pfc_payment_status) && $value->pfc_payment_status === "Y"): ?>
                                                            <?php if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                                              <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                                            <?php endif; ?>

                                                              <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/admin/get-acknowledgement?app_ref_no=").$value->department_id?>" >Acknowledgement</a>
                                                          <?php else: ?>
                                                            <?php if(isset($value->pfc_payment_status) && ($value->pfc_payment_status === "N" || $value->pfc_payment_status === "A")){ ?>
                                                              <a class="btn btn-info btn-sm mbtn" href="<?=base_url('iservices/rtpspayment/payment/'.$value->rtps_trans_id)?>" >Pay or Verify Payment</a>
                                                                <!-- <a href="base_url('iservices/admin/retry-pfc-payment/').$value->rtps_trans_id" class="btn btn-sm btn-warning mbtn white">Retry Payment</a> -->
                                                          <?php  }else { ?>
                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->GRN === ""): ?>
                                                              <a class="btn btn-info btn-sm mbtn" href="<?=base_url('iservices/rtpspayment/payment/'.$value->rtps_trans_id)?>" >Pay or Verify Payment</a>
                                                              <!-- <a href="base_url('iservices/admin/check-grn-status/').$value->pfc_payment_response->DEPARTMENT_ID" class="btn btn-sm btn-primary mbtn">Verify Payment</a> -->
                                                            <?php endif; ?>
                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->BANKCIN === ""): ?>
                                                              <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/admin/get/cin-response')?>" />
                                                                <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                              </form>
                                                            <?php endif; ?>

                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->STATUS==="P" ): ?>
                                                              <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/admin/get/cin-response')?>" />
                                                                <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                              </form>
                                                            <?php endif; ?>
                                                            <?php } ?>


                                                    <?php endif; ?>
                                                  <?php }else { // initiate a payment... ?>
                                                            <a class="btn btn-info btn-sm mbtn" href="<?=base_url('iservices/rtpspayment/payment/'.$value->rtps_trans_id)?>" >Pay or Verify Payment</a>
                                                  <?php }?>


                                            <?php } else { //if application is not success  ?>
                                                      <?php if ($value->status ==="F"): ?>
                                                              <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/admin/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                                      <?php elseif($value->status ==="P"): ?>
                                                                <a href="<?=base_url("iservices/admin/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Complete Your Application</button>
                                                      <?php elseif($value->status ===""): ?>
                                                                <a href="<?=base_url("iservices/admin/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Complete Your Application</button>
                                                          <?php else: ?>
                                                      <?php endif ?>
                                            <?php } ?>

                                          </td>
                                <?php }else{ ?>
                                              <td>
                                            <!-- this is for other service then vahan -->
                                            <?php if($value->status === "S"){  ?>

                                              <?php if(property_exists($value,"has_payment_issue") && $value->has_payment_issue){?>
                                                   <a class="btn btn-danger btn-sm mbtn" href="<?=base_url("iservices/Paymentcorrection/details/").$value->rtps_trans_id?>" >Pay Due Amount </a>
                                              <?php }?>
                                                  <!-- check for pfc payment status -->
                                                  <?php if(isset($value->pfc_payment_status)){ ?>
                                                    <?php  if (!empty($value->pfc_payment_status) && $value->pfc_payment_status === "Y"): ?>
                                                            <?php if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                                              <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                                            <?php endif; ?>

                                                              <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/admin/get-acknowledgement?app_ref_no=").$value->department_id?>" >Acknowledgement</a>
                                                          <?php else: ?>
                                                            <?php if(isset($value->pfc_payment_status) && ($value->pfc_payment_status === "N" || $value->pfc_payment_status === "A")){ ?>
                                                              <a class="btn btn-info btn-sm mbtn" href="<?=base_url("iservices/admin/pfc-payment/").$value->rtps_trans_id?>" >Pay or Verify Payment</a>
                                                                <!-- <a href="base_url('iservices/admin/retry-pfc-payment/').$value->rtps_trans_id" class="btn btn-sm btn-warning mbtn white">Retry Payment</a> -->
                                                          <?php  }else { ?>
                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->GRN === ""): ?>
                                                              <a class="btn btn-info btn-sm mbtn" href="<?=base_url("iservices/admin/pfc-payment/").$value->rtps_trans_id?>" >Pay or Verify Payment</a>
                                                              <!-- <a href="base_url('iservices/admin/check-grn-status/').$value->pfc_payment_response->DEPARTMENT_ID" class="btn btn-sm btn-primary mbtn">Verify Payment</a> -->
                                                            <?php endif; ?>
                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->BANKCIN === ""): ?>
                                                              <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/admin/get/cin-response')?>" />
                                                                <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                              </form>
                                                            <?php endif; ?>

                                                            <?php if(isset($value->pfc_payment_response) && $value->pfc_payment_response->STATUS==="P" ): ?>
                                                              <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                                <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                                <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                                <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                                <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                                <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/admin/get/cin-response')?>" />
                                                                <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                              </form>
                                                            <?php endif; ?>
                                                            <?php } ?>


                                                    <?php endif; ?>
                                                  <?php }else { // initiate a payment... ?>
                                                            <a class="btn btn-info btn-sm mbtn" href="<?=base_url("iservices/admin/pfc-payment/").$value->rtps_trans_id?>" >Pay or Verify Payment</a>
                                                  <?php }?>


                                            <?php } else { //if application is not success  ?>
                                                      <?php if ($value->status ==="F"): ?>
                                                              <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/admin/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                                      <?php elseif($value->status ==="P"): ?>
                                                                <a href="<?=base_url("iservices/admin/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Re-try</button>
                                                      <?php elseif($value->status ===""): ?>
                                                                <a href="<?=base_url("iservices/admin/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Re-try</button>
                                                          <?php else: ?>
                                                      <?php endif ?>
                                            <?php } ?>

                                          </td>
                                <?php }?>
                                
                              
                              </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>

                  <?php else: ?>
                      <p>No Transactions Found<p>
                  <?php endif; ?>

                </div>
            </div>