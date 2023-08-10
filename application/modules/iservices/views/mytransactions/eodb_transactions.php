<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
  .parsley-errors-list {
    color: red;
  }

  .mbtn {
    width: 100% !important;
    margin-bottom: 3px;
  }
</style>
<?php
$applications = !empty($eodb_transactions) ? $eodb_transactions : array()
?>
<div class="container">
  <div class="row">
    <div class="col-sm-12 mx-auto">
      <div class="card my-4">
        <div class="card-body">
          <h4>Service Plus Integrated Services </h4>
          <?php if ($this->session->flashdata('success') != null) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php } else if ($this->session->flashdata('error') != null) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php } ?>
          <?php if (!empty($applications)) : ?>

            <table class="table">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Application Ref No</th>
                  <th>Service Name</th>
                  <th>Initiate Date</th>
                  <th>Submission Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="">
                <?php foreach ($applications as $key => $value) :
                  $obj_id = $value->_id->{'$id'};
                ?>
                  <tr>
                    <td><?= ($key + 1) ?></td>
                    <td><?= isset($value->submitted_application->draft_ref_no) ? $value->submitted_application->draft_ref_no : $value->draft_application->draft_ref_no ?></td>
                    <td><?= isset($value->service_name) ? $value->service_name : "" ?></td>
                    <td><?= isset($value->draft_application->draft_ref_no) ? date('d/m/Y', strtotime($value->draft_application->application_date)) : "N/A" ?></td>
                    <td><?= isset($value->submitted_application->draft_ref_no) ? date('d/m/Y', strtotime($value->submitted_application->application_date)) : "N/A" ?></td>
                    <td><?php if($value->appl_status == "S"){echo "Initiated";}else if($value->appl_status == "W"){echo "Payment Pending";}else if($value->appl_status == "I"){echo "Submitted";}else if($value->appl_status == "R"){echo "Rejected";}else if($value->appl_status == "D"){echo "Delivered";} ?></td>
                    <td>
                      <?php if ($value->appl_status == "S" || $value->appl_status == "W") { ?>
                        <a class="btn btn-primary btn-sm mbtn" href="<?= base_url("iservices/eodb/incomplete_application/") . $obj_id ?>" target="_blank">Complete Your Application</a>

                      <?php } else if (($value->appl_status == "I" || $value->appl_status == "D") && !empty($value->appl_wise_tiny_url)) { ?>
                        <a href="<?= base_url('iservices/serviceplus/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn" target="_blank">Track Application</a>
                        <!-- <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?= base_url($value->certificate) ?>">Download Certificate</a> -->

                      <?php } else if (($value->appl_status == "I" || $value->appl_status == "D") && empty($value->appl_wise_tiny_url)) { ?>
                        <a href="<?= base_url("iservices/serviceplus/update_tiny_url/") . $value->submitted_application->application_id ?>" class="btn btn-secondary btn-sm mbtn"><i class="fa fa-refresh"></i> Refresh</a>
                      <?php }
                      ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          <?php else : ?>
            <p>No Application Found
            <p>
            <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>