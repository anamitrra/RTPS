<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Portals</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Portals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Details</h3>
                </div>
                <?php  if ($this->session->flashdata('status')=="error") {
                  ?>
                    <div class="alert alert-danger alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $this->session->flashdata('message'); ?>
                    </div>
                  <?php
                  }

                  if ($this->session->flashdata('status')=="success") {
                  ?>
                    <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('message'); ?>

                    </div>
                  <?php } ?>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                      <label>Portal Name </label> : <?=$detail->portal_name?>
                    </div>
                    <div class="row">
                      <label>Service Name </label> : <?=$detail->service_name?>
                    </div>
                    <div class="row">
                      <label>Department Name </label> : <?=$detail->department_name?>
                    </div>
                    <div class="row">
                      <label>Portal No </label> : <?=$detail->portal_no?>
                    </div>
                    <div class="row">
                      <label>Service Id </label> : <?=$detail->service_id?>
                    </div>
                    <div class="row">
                      <label>External Service Id </label> : <?= property_exists($detail,'external_service_id') ?  $detail->external_service_id :""?>
                    </div>  
                    <div class="row">
                      <label>Department Code </label> : <?= property_exists($detail,'dept_code') ?  $detail->dept_code :""?>
                    </div>
                    <div class="row">
                      <label>Office Code </label> : <?= property_exists($detail,'office_code') ?  $detail->office_code :""?>
                    </div>
                    <div class="row">
                      <label>Timeline Days </label> : <?=$detail->timeline_days?>
                    </div>
                    <div class="row">
                      <label>URL </label> : <?=$detail->url?>
                    </div>
                    <div class="row">
                      <label>Status URL </label> : <?=$detail->status_url?>
                    </div>
                      <div class="row">
                      <label>RTPS Service URL </label> : <?= property_exists($detail,'rtps_service_url') ?  $detail->rtps_service_url :""?>
                    </div>

                    <?php if (isset($detail->payment_required)) { ?>

                      <div class="row">
                      <label>Payment </label> : 
                      <?php  if($detail->payment_required == 1){ ?>
                      Required
                      <?php } else { ?>
                      Not Required
                      <?php }?>
                    </div>

<?php } else { ?>

<div> </div>

<?php } ?>


<?php if (isset($detail->status)) { ?>

<div class="row">
<label>Status </label> : 
<?php  if($detail->status == 1){ ?>
Active
<?php } else { ?>
Inactive
<?php }?>
</div>

<?php } else { ?>

<div> </div>

<?php } ?>
                    
                    


                    

                          <fieldset  class="border border-success"  style="margin-top:40px">
                            <legend class="h5">External Portal(EP) Payment Info :</legend>
                            <div style="padding:20px">
                            

                                <div class="form-group">
                                <label for="varchar">Account Code For EP payment:  </label>  <?= property_exists($detail,'ep_payment_account_code') ?  $detail->ep_payment_account_code :""?>
                           
                                </div>
                            </div>
                           
                           </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>
