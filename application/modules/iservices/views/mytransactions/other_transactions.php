
<div class="card my-4">
                <div class="card-body table-container">
                  <h4>Other Services </h4>
                  <?php if (!empty($intermediate_ids['other_services'])): ?>

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
                            <?php foreach ($intermediate_ids['other_services'] as $key => $value): ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->PurposeDescription) ? $value->PurposeDescription : (isset($value->service_name) ? $value->service_name:'' )?></td>
                              <td><?=isset($value->app_ref_no) ? $value->app_ref_no : (isset($value->vahan_app_no) ? $value->vahan_app_no:'' )?></td>
                              
                              <td><?=isset($value->submission_date)? $value->submission_date: ""?></td>

                              <td>
                                <?= $value->status? get_status($value->status): "Pending" ?>

                              </td>
                              <td>
                                    <?php if(isset($value->applied_by)){ ?>
                                          <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/check-status?app_ref_no=<?=$value->app_ref_no?>" >Track</a>
                                            <a class=" btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
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
                                              
                                            <?php
                                            if($value->portal_no === "8" || $value->portal_no === 8 ){
                                              if (isset($value->pfc_payment_status) && $value->pfc_payment_status === "Y"){ ?>
                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                              <?php }else if($value->status ==="S"){ ?>
                                                <a class="btn btn-primary btn-sm mbtn" href="<?=base_url('iservices/rtpspayment/convenience_fee/'.$value->rtps_trans_id)?>" >Pay Or Verify Payment</a>
                                               <?php }
                                            }else{ ?>
                                              <?php if ($value->status === "S"): ?>
                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                              
                                              <?php endif; ?>
                                            <?php } ?>                                           


                                           
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