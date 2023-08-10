
<div class="card my-4">
                <div class="card-body">
                  <h4>RTPS Services </h4>
                  <?php if (!empty($intermediate_ids['rtps_services'])): ?>

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
                            <?php foreach ($intermediate_ids['rtps_services'] as $key => $value): ?>
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
                              <td>
                                    <?php if(isset($value->applied_by)){ ?>
                                          <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/wptbc/payment_response/acknowledgement?app_ref_no=").$value->department_id?>" >Acknowledgement</a>
                                          <?php endif; ?>
                                    <?php }else { ?>
                                            <?php if (!empty($value->app_ref_no) && $value->status !="F"): ?>
                                              <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <?php endif; ?>
                                          <?php if ($value->status ==="F"): ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/guidelines/").$value->service_id."/".$value->portal_no?>" >Apply Again</a>
                                          <?php endif; ?>
                                            <?php if ($value->status ==="" || $value->status ==="P"): ?>
                                              <a href="<?=base_url("iservices/retry/").$value->rtps_trans_id."/".$value->service_id."/".$value->portal_no?>" class="btn btn-sm btn-primary">Re-try</button>
                                            <?php endif; ?>
                                            <?php if ($value->status === "S"): ?>
                                                <?php if(isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"){?>
                                                            <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/wptbc/payment_response/acknowledgement?app_ref_no=").$value->department_id?>" >Acknowledgement</a>
                                                    <?php }else{?>
                                                        <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/wptbc/payments/payment/").$value->rtps_trans_id?>" >Initite Payment</a>
                                                    <?php }?>
                                             
                                            <?php endif; ?>
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