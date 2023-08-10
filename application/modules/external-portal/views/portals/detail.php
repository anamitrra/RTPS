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
                      <label>Timeline Days </label> : <?=$detail->timeline_days?>
                    </div>
                    <div class="row">
                      <label>URL </label> : <?=$detail->url?>
                    </div>
                    <div class="row">
                      <label>Status URL </label> : <?=$detail->status_url?>
                    </div>
                    <div class="row">
                      <label>Guidelines </label> :
                    </div>
                    <div class="row">
                      <?php if (is_array($detail->guidelines)): ?>
                          <ul>
                          <?php foreach ($detail->guidelines as $key => $value): ?>
                            <li><?=$value?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </div>
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
