
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
                                  <th>Submission Date</th>
                                  <th>Status</th>
                                  <!-- <th>Payment Status</th> -->
                                  <th>Action</th>
                                  <!-- <th>Payment Data</th> -->
                              </tr>
                          </thead>
                          <tbody id="">
                            <?php foreach ($intermediate_ids['noc_services'] as $key => $value): 
                                $is_appllied_pfc= (property_exists($value,'applied_by') && $value->applied_by) ? true : false;
                                $is_payment_rtps_end= (property_exists($value,'payment_rtps_end') && $value->payment_rtps_end) ? true : false;
                                $app_status='';
                                if($is_payment_rtps_end){
                                    if($value->status === "S"){
                                      if(property_exists($value,'pfc_payment_status') && $value->pfc_payment_status === "Y")
                                      $app_status="Success";
                                      else
                                      $app_status="Payment Pending";
                                    }else{
                                      $app_status="Pending";
                                    }
                                    
                                }else{
                                  $app_status = $value->status ? get_status($value->status): "Pending";
                                }
                              ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->PurposeDescription) ? $value->PurposeDescription : (isset($value->service_name) ? $value->service_name:'' )?></td>
                              <td><?=isset($value->app_ref_no) ? $value->app_ref_no : (isset($value->vahan_app_no) ? $value->vahan_app_no:'' )?></td>
                              <!-- <td><?=format_mongo_date($value->createdDtm)?></td> -->
                              <td><?=isset($value->submission_date)? $value->submission_date: ""?></td>

                              <td>
                              <?= $app_status  ?>
                              </td>
                              <td>
                                    <?php if(isset($value->applied_by)){ ?>
                                          <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                          <?php endif; ?>
                                    <?php }else {
                                                  if($is_payment_rtps_end){
                                                        // for  new Applications where rtps is responsible for Payment
                                                        ?>
                                                              <?php if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                                                <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                                              <?php endif; ?>
                                                              <?php if ($value->status ==="F"): ?>
                                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                                              <?php endif; ?>
                                                              <?php if ($value->status ==="" || $value->status ==="P"): ?>
                                                                <a href="<?=base_url("iservices/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary mbtn">Complete Your Application</button>
                                                              <?php endif; ?>

                                                              <?php if (isset($value->pfc_payment_status) && $value->pfc_payment_status === "Y"){ ?>
                                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                                              <?php }else if($value->status ==="S"){ ?>
                                                                <a class="btn btn-primary btn-sm mbtn" href="<?=base_url('iservices/rtpspayment/payment/'.$value->rtps_trans_id)?>" >Pay Or Verify Payment</a>
                                                              <?php  } ?>
                                                        <?php 
                                                  }else{ ?>
                                                              <?php if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                                                <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                                              <?php endif; ?>
                                                              <?php if ($value->status ==="F"): ?>
                                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                                              <?php endif; ?>
                                                              <?php if ($value->status ==="" || $value->status ==="P"): ?>
                                                                <a href="<?=base_url("iservices/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary">Complete Your Application</button>
                                                              <?php endif; ?>
                                                              <?php if ($value->status === "S"): ?>

                                                                <?php if(property_exists($value,"has_payment_issue") && $value->has_payment_issue){?>
                                                                  <a class="btn btn-danger btn-sm mbtn" href="<?=base_url("iservices/Paymentcorrection/details/").$value->rtps_trans_id?>" >Pay Due Amount </a>
                                                              <?php }?>
                                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                                              <?php endif; ?>
                                                  <?php }  ?>
                                           
                                    <?php } ?>



                                </td>
                              
                              </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>

                  <?php else: ?>
                      <p>No Transactions Found<p>
                  <?php endif; ?>

                </div>
            </div>