
<div class="card my-4">
                <div class="card-body table-container">
                  <h4>Vahan Services </h4>
                  <?php if (!empty($intermediate_ids['vahan_services'])): ?>

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
                            <?php foreach ($intermediate_ids['vahan_services'] as $key => $value): ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <!-- <td><?=isset($value->PurposeDescription) ? $value->PurposeDescription : (isset($value->service_name) ? $value->service_name:'' )?></td> -->
                              <td><?=isset($value->service_name) ? $value->service_name : '' ?></td>
                              <td><?=isset($value->app_ref_no) ? $value->app_ref_no : (isset($value->vahan_app_no) ? $value->vahan_app_no:'' )?></td>
                              <!-- <td><?=format_mongo_date($value->createdDtm)?></td> -->
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
                                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/v-check-status?vahan_app_no=<?=$value->vahan_app_no?>" >Track</a>
                                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/v-acknowledgement/").$value->vahan_app_no?>" >Acknowledgement</a>
                                                  <?php endif; ?>
                                  <?php }else { ?>

                                                 <?php if ($value->status ==="S"): ?>
                                                            <?php if (isset($value->convenience_fee_required) && $value->convenience_fee_required): ?>
                                                                      <?php if (isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                                                          <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/v-acknowledgement/").$value->vahan_app_no?>" >Acknowledgement</a>
                                                                          <?php if (!empty($value->vahan_app_no) && $value->status !="F"): ?>
                                                                            <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/v-check-status?vahan_app_no=<?=$value->vahan_app_no?>" >Track</a>
                                                                          <?php endif; ?>
                                                                      <?php else: ?>
                                                                          <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/convenience_fee/payment/<?=$value->rtps_trans_id?>" >Pay convenience fee</a>
                                                                      <?php endif; ?>
                                                            <?php else: ?>
                                                                  <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/v-acknowledgement/").$value->vahan_app_no?>" >Acknowledgement</a>
                                                                  <?php if (!empty($value->vahan_app_no) && $value->status !="F"): ?>
                                                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/v-check-status?vahan_app_no=<?=$value->vahan_app_no?>" >Track</a>
                                                                  <?php endif; ?>
                                                            <?php endif; ?>
                                                        
                                                  <?php else: ?>
                                                              <?php if ($value->status ==="R"): ?>
                                                                <a href="<?=base_url("iservices/vahan-service/retry/").$value->rtps_trans_id?>" class="btn btn-sm btn-primary">Complete Your Application</button>
                                                              <?php elseif($value->status ===""): ?>
                                                                <a class="btn btn-sm btn-primary mbtn" href="<?=base_url("iservices/vahan-service/retry/").$value->rtps_trans_id?>" >Complete Your Application</a>
                                                              <?php else: ?>
                                                              <?php endif ?>
                                                              <?php if ($value->status ==="F" || $value->status ==="N"): ?>
                                                                <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/vahan/").$value->service_id?>" >Apply Again</a>
                                                              <?php endif; ?>
                                                  <?php endif ?>  
                                                  
                                                

                                                  
                                  <?php }?>

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