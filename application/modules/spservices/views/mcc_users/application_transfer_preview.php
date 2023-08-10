<style>
  body {
    overflow-x: hidden;
  }
</style>


<!-- Transfer From -->
<div class="content-wrapper">
  <main class="rtps-container">
    <div class="row">
      <div class="col">
        <div class="container my-2">
          <div class="card">
            <div class="card-body">
              <h5 class="text-center mb-4">Preview of Application Transfer</h5>
              <form id="myfrm" method="POST" action="<?= base_url('spservices/mcc_users/admindashboard/process_transfer') ?>" enctype="multipart/form-data">
                <input type="hidden" name="transfer_from" value="<?= $transferFrom; ?>" required>
                <input type="hidden" name="transfer_to" value="<?= $transferTo; ?>" required>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th width="10%">SL. No.</th>
                      <th>RTPS Ref. No.</th>
                      <th>Applicant Name</th>
                      <th>District</th>
                      <th>Circle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($applications) {
                      $i = 1;
                      foreach ($applications as $val) { ?>
                        <input type="hidden" name="appl_no[]" value="<?= $val->service_data->appl_ref_no; ?>">
                        <tr>
                          <td><?= $i; ?></td>
                          <td><?= $val->service_data->appl_ref_no; ?></td>
                          <td><?= $val->form_data->applicant_name; ?></td>
                          <td><?= $val->service_data->district; ?></td>
                          <td><?= $val->form_data->pa_circle; ?></td>
                        </tr>
                      <?php }
                      $i++; ?>
                      <tr>
                        <td colspan="5">Remarks <span class="text-danger">*</span></td>
                      </tr>
                      <tr>
                        <td colspan="5"><textarea name="remarks" cols="30" rows="2" class="form-control" required></textarea></td>
                      </tr>
                    <?php } else { ?>
                      <tr>
                        <td colspan="5" class="text-center">No pending applications found.</td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-save"></i> Confirm Transfer</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div><!--End of .container-->
      </div>
    </div>
  </main>
</div>