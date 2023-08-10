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

$applications = !empty($kaac_services_CCJ) ? $kaac_services_CCJ : array();



?>
<div class="container">
  <div class="row">
    <div class="col-sm-12 mx-auto">
      <div class="card my-4">
        <div class="card-body">
          <h4>Certified Copies of Jamabandi <a href="<?= base_url('spservices/kaac-services/CCM') ?>" class="btn btn-sm btn-warning" style="float: right" target="_blank">Apply for
              Certified Copies of Jamabandi</a></h4>

          <?php if (!empty($applications)) : ?>

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
              <tbody>
                <?php if (!empty($applications)) {
                  foreach ($applications as $key => $value) :
                    $obj_id = $value->_id->{'$id'};
                ?>
                    <tr>
                      <td><?= ($key + 1) ?></td>
                      <td><a href="<?= base_url("spservices/kaac/registration/applicationpreview/") . $obj_id ?>" target="_blank"><?= $value->service_data->appl_ref_no ?></a></td>
                      <td><?= isset($value->service_data->service_name) ? $value->service_data->service_name : "" ?></td>
                      <td>
                        <?php if (gettype($value->service_data->submission_date) == "string") { ?>
                          <?= $value->service_data->submission_date ?>
                        <?php } else { ?>
                          <?= (!empty($value->service_data->submission_date)) ? format_mongo_date($value->service_data->submission_date) : "" ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?=
                        ($value->service_data->appl_status == "F") ? "Forwarded" : getstatusname($value->service_data->appl_status);
                        ?>
                      </td>
                      <td>
                        <?php if ($value->service_data->appl_status == "DRAFT") { ?>
                          <a class="btn btn-primary btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/index/") . $obj_id ?>">Complete Your Application</a>
                        <?php } elseif ($value->service_data->appl_status == "submitted") { ?>
                          <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                        <?php } elseif ($value->service_data->appl_status == "QS") {
                        ?>
                          <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                          <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/queryform/' . $obj_id) ?>">Reply to query</a>
                        <?php
                        } elseif ($value->service_data->appl_status == "FRS") { ?>
                          <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/acknowledgement/") . $obj_id ?>">Acknowledgement</a>

                          <?php

                          if (isset($value->form_data->frs_request->amount)) {
                            if (isset($value->form_data->frs_request->amount) && isset($value->form_data->query_payment_response) && !empty($value->form_data->query_payment_response->GRN) && ($value->form_data->query_payment_response->STATUS === "P" || $value->form_data->query_payment_response->STATUS === "")) {
                          ?>
                              <form method="post" name="getGRN" id="getGRN" action="<?= $this->config->item('egras_grn_cin_url') ?>">
                                <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?= $value->form_data->query_payment_response->DEPARTMENT_ID ?>" />
                                <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE" value="<?= $value->form_data->query_payment_params->OFFICE_CODE ?>" />
                                <input type="hidden" id="AMOUNT" name="AMOUNT" value="<?= $value->form_data->query_payment_response->AMOUNT ?>" />
                                <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly />
                                <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?= base_url('spservices/kaac/payment_query_response/cin_response') ?>" />
                                <input type="submit" style="margin-top: 3px;color:white" id="submit" class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK" value="Verify Query Payment" />
                              </form>
                            <?php
                            } elseif (isset($value->form_data->query_payment_response) && !empty($value->form_data->query_payment_response->GRN) && ($value->form_data->query_payment_response->STATUS === "Y")) {
                            ?>
                              <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/querypaymentsubmit/' . $obj_id . '/pp') ?>">Verify Query Payment</a>
                            <?php
                            } else {  ?>

                              <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/query_payment_break_down/' . $obj_id) ?>">Make Query Payment</a>
                          <?php }
                          } ?>

                        <?php } elseif ($value->service_data->appl_status == "payment_initiated") { ?>

                          <?php if (isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "P" || $value->form_data->pfc_payment_response->STATUS === "")) {
                          ?>
                            <form method="post" name="getGRN" id="getGRN" action="<?= $this->config->item('egras_grn_cin_url') ?>">
                              <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?= $value->form_data->pfc_payment_response->DEPARTMENT_ID ?>" />
                              <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE" value="<?= $value->form_data->payment_params->OFFICE_CODE ?>" />
                              <input type="hidden" id="AMOUNT" name="AMOUNT" value="<?= $value->form_data->pfc_payment_response->AMOUNT ?>" />
                              <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly />
                              <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?= base_url('spservices/kaac/payment_response/cin_response') ?>" />
                              <input type="submit" style="margin-top: 3px;color:white" id="submit" class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK" value="Verify Payment1" />
                            </form>
                          <?php
                          } elseif (isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")) {
                          ?>
                            <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/submit_to_backend/' . $obj_id) ?>">Verify Payment</a>
                          <?php
                          } else {  ?>
                            <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/kaac/payment/verify/' . $obj_id) ?>">Make/Verify Payment</a>
                          <?php }
                          ?>
                        <?php } elseif ($value->service_data->appl_status == "QA" || $value->service_data->appl_status == "R" || $value->service_data->appl_status == "F") { ?>
                          <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/acknowledgement/") . $obj_id ?>" target="_blank">Acknowledgement</a>
                          <?php if (isset($value->form_data->query_payment_response->STATUS) && !empty(($value->form_data->query_payment_response->STATUS) == 'Y')) { ?>
                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/payment_acknowledgement/' . $obj_id) ?>">Payment Acknowledgement</a>
                          <?php } ?>
                          <?php } elseif ($value->service_data->appl_status == "D") { ?>
                          <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/kaac/registration/acknowledgement/") . $obj_id ?>">Acknowledgement</a>



                          <?php if (!empty($value->form_data->certificate)) { ?>
                            <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?= base_url($value->form_data->certificate) ?>">Download Certificate</a>
                            <?php if (isset($value->form_data->query_payment_response->STATUS) && !empty($value->form_data->query_payment_response->STATUS == 'Y')) { ?>
                              <a class="btn btn-success btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/payment_acknowledgement/' . $obj_id) ?>">Payment Acknowledgement</a>
                            <?php } ?>
                          <?php } ?>
                          <?php if ((isset($value->form_data->app_record_type)) && (!empty($value->form_data->app_record_type) != "reapply_done") && $value->service_data->appl_status == "D") { ?>
                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url('spservices/kaac/registration/cancel_form/' . $obj_id) ?>">Cancel Permit</a>
                          <?php } ?>
                        <?php } ?>

                        <a href="<?= base_url('spservices/kaac/registration/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                      </td>

                    </tr>
                <?php endforeach;
                } ?>
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