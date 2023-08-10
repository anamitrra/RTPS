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
$applications = !empty($offline_ack_apps) ? $offline_ack_apps : array()
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h4>Offline Services</h4>
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
                              <td><?= $value->form_data->rtps_trans_id ?></td>
                              <td><?=isset($value->service_data->service_name) ? $value->service_data->service_name : ""?></td>
                              <td><?=isset($value->service_data->submission_date)? format_mongo_date($value->service_data->submission_date): ""?></td>
                              <td><?= getstatusname($value->service_data->appl_status)?></td>  
                              <td>
                                  <?php if($value->service_data->appl_status == "DRAFT") { ?>
                                    <a class="btn btn-primary btn-sm mbtn" href="<?=base_url("spservices/offline/acknowledgement/form/").$obj_id ?>" >Complete Your Application</a>
                                  <?php }
                                   elseif($value->service_data->appl_status == "submitted" || $value->service_data->appl_status == "QA" || $value->service_data->appl_status == "UP" ||  $value->service_data->appl_status == "R"){?>
                                        <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/offline/acknowledgement/download/").$obj_id ?>" >Acknowledgement</a>
                                  <?php } elseif($value->service_data->appl_status == "QS"){
                                    ?>
                                    <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/offline/acknowledgement/query/'.$obj_id)?>" >Reply to query</a>
                                    <?php 
                                  }elseif($value->service_data->appl_status == "payment_initiated"){ ?>  
                                         <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/offline/payment/verify/'.$obj_id)?>" >Make/Verify Payment</a>
                                  <?php }elseif($value->service_data->appl_status == "D"){ ?>
                                    <a class="btn btn-success btn-sm mbtn" href="<?=base_url("spservices/offline/acknowledgement/download/").$obj_id ?>" >Acknowledgement</a>
                                    <?php if(isset($value->form_data->certificate)){ ?>
                                      <a class="btn btn-primary btn-sm mbtn"  target="_blank" href="<?=base_url($value->form_data->certificate)?>" >Download Certificate</a>
                                    <?php }?>
                                  <?php }
                                  ?>
                                 
                                   <a href="<?=base_url('spservices/offline/acknowledgement/track/').$obj_id?>" class="btn btn-secondary btn-sm mbtn" >Track</a>
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
