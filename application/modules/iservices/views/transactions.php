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

<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h4>My Transactions </h4>
                  <?php if (!empty($intermediate_ids)): ?>

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
                            <?php foreach ($intermediate_ids as $key => $value): ?>
                            <tr>
                              <td><?=($key+1)?></td>
                              <!-- <td>$value->_id->{'$id'} </td> -->
                              <td><?= $value->rtps_trans_id ?></td>
                              <td><?=isset($value->PurposeDescription) ? $value->PurposeDescription : (isset($value->service_name) ? $value->service_name:'' )?></td>
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
                              <!-- <td><?=isset($value->payment) ? $value->payment->STATUS : "N/A"?></td> -->
                              <?php if ($value->portal_no === "2" || $value->portal_no === 2): ?>
                                <!-- this is for vahan -->
                                <td>
                                  <?php if(isset($value->applied_by)){ ?>
                                                  <?php if ( isset($value->pfc_payment_status)  && $value->pfc_payment_status ==="Y"): ?>
                                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/v-check-status?vahan_app_no=<?=$value->vahan_app_no?>" >Track</a>
                                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/v-acknowledgement/").$value->vahan_app_no?>" >Acknowledgement</a>
                                                  <?php endif; ?>
                                  <?php }else { ?>
                                                    <?php if (!empty($value->vahan_app_no) && $value->status !="F"): ?>
                                                      <a class="btn btn-primary btn-sm mbtn" href="<?=base_url()?>iservices/status/v-check-status?vahan_app_no=<?=$value->vahan_app_no?>" >Track</a>
                                                    <?php endif; ?>
                                                  <?php if ($value->status ==="R"): ?>
                                                      <a href="<?=base_url("iservices/vahan-service/retry/").$value->rtps_trans_id?>" class="btn btn-sm btn-primary">Re-try</button>
                                                  <?php elseif($value->status ===""): ?>
                                                    <a class="btn btn-sm btn-primary mbtn" href="<?=base_url("iservices/vahan-service/retry/").$value->rtps_trans_id?>" >Try Again</a>
                                                  <?php else: ?>
                                                  <?php endif ?>

                                                  <?php if ($value->status ==="S"): ?>
                                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/v-acknowledgement/").$value->vahan_app_no?>" >Acknowledgement</a>
                                                  <?php endif; ?>
                                                  <?php if ($value->status ==="F" || $value->status ==="N"): ?>
                                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/vahan/").$value->service_id?>" >Apply Again</a>
                                                  <?php endif; ?>
                                  <?php }?>

                              </td>
                              <?php else: ?>
                                <!-- this is for other service then vahan -->

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
                                            <?php if ($value->status === "S"): ?>
                                              <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/o-acknowledgement?app_ref_no=").$value->app_ref_no?>" >Acknowledgement</a>
                                            <?php endif; ?>
                                    <?php } ?>



                                </td>
                              <?php endif; ?>

                              <!-- <td><?php if(isset($value->payment)) { echo json_encode($value->payment); } ?></td> -->
                              </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>

                  <?php else: ?>
                      <p>No Transactions Found<p>
                  <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
function get_status($str){
  switch ($str) {
    case 'S':
      return "Success";
      break;
    case 'P':
      return "Pending";
      break;
    case 'Y':
      return "Success";
      break;
    case 'N':
      return "Failed";
      break;
    case 'F':
      return "Failed";
      break;
    case 'A':
      return "Aborted ";
      break;
    case 'R':
      return "Pending";
      break;

    default:
      return "";
      break;
  }
}
 ?>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
