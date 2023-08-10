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
$applications = !empty($serviceplus_transactions_eodb) ? $serviceplus_transactions_eodb : array()
?>
<div class="container">
  <div class="row">
    <div class="col-sm-12 mx-auto">
      <div class="card my-4">
        <div class="card-body">
          <?php   //pre($serviceplus_transactions_eodb);
          ?>
          <h4>Business Services (Applied from Serviceplus) </h4>
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

            <table class="table table-dt">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Application Ref No</th>
                  <th width="30%">Service Name</th>
                  <!-- <th>Initiate Date</th> -->
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
                    <td><?= isset($value->form_data->appl_ref_no) ? $value->form_data->appl_ref_no : $value->form_data->appl_ref_no ?></td>
                    <td><?= isset($value->form_data->service_name) ? $value->form_data->service_name : $value->form_data->service_name  ?></td>
                    <td><?= isset($value->form_data->application_date) ? date('d/m/Y', strtotime($value->form_data->application_date)) : "N/A" ?></td>

                    <td><?php if ($value->form_data->appl_status == "S") {
                          echo "Pending";
                        } else if ($value->form_data->appl_status == "R") {
                          echo "Rejected";
                        } else if ($value->form_data->appl_status == "D") {
                          echo "Delivered";
                        } else {
                          echo "Under Process";
                        } ?></td>
                    <td>
                      <?php if ($value->form_data->appl_status == "S" || $value->form_data->appl_status == "W") { ?>
                        <!-- <a class="btn btn-primary btn-sm mbtn" onclick="showSPLoginModalEODB();" target="_blank">Complete Your Application</a> -->
                        <a class="btn btn-primary btn-sm mbtn openLinkEodb" href="https://eodb.assam.gov.in/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E" target="_blank">Complete Your Application</a>
                        
                      <?php } else { ?>
                        <!-- <button type="button" class="btn btn-info btn-sm mbtn" onclick="showSPLoginModalEODB();" target="_blank">Track Application</button> -->
                            <a class="btn btn-primary btn-sm mbtn openLinkEodb" href="https://eodb.assam.gov.in/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E" target="_blank">Complete Your Application</a>

                        <!-- <a class="btn btn-info btn-sm mbtn" onclick="showSPLoginModalEODB();" target="_blank"><i class="fa fa-unlock" aria-hidden="true"></i> Proceed</a> -->
                        <!-- <a href="<?= base_url('iservices/serviceplus/rtps_track/' . $obj_id) ?>" class="btn btn-success btn-sm mbtn" target="_blank"> Track Application</a> -->
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
<script>
  /* Login Model */
  $( ".openLinkEodb" ).on( "click", function(event) {
        event.preventDefault();
        const linkUrl = $(this).attr('href');
        // Load EODB
        const eodbWindow = window.open('https://eodb.assam.gov.in/', 'EODB', 'width=300,height=300');
        setTimeout(function() {
            eodbWindow.close();
            //console.log('Pop up closed', eodbWindow.closed);
            window.open(linkUrl, '_blank');
            //window.location.href = event.target.href;
        }, 1500);
   });

</script>