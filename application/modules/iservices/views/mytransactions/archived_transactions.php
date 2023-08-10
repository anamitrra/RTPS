<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
      width: 100% !important;
      margin-bottom: 3px;
    }
    .blk{
      display: block;
    }
</style>
<div class="content-wrapper">
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('message') <> '') {?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success</strong> <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>
     </div>
    <div class="row">
        <div class="col-sm-12 mx-auto">
        <div class="card my-4">
                <div class="card-body">
                  <h4>Archived Applications </h4>
                  <?php if (!empty($intermediate_ids)): ?>

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
                            <?php foreach ($intermediate_ids as $key => $value): ?>
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
                                     <a class="btn btn-success btn-sm mbtn" href="<?=base_url("iservices/application/unarchive/").$value->rtps_trans_id?>" >Unarchive</a>
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
