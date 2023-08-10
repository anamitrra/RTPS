<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style type="text/css">
    .parsley-errors-list {
        color: red;
    }

    .mbtn {
        width: 100% !important;
        margin-bottom: 3px;
    }

    .blk {
        display: block;
    }

    .mybtn {
        float: right;
        font-size: 14px;
        font-style: italic;
        font-weight: bold;
        text-transform: uppercase;
        border: 2px dotted #F40080;
        border-radius: 5px;
        padding: 2px 5px;
        background: #F40080;
        color: #fff;
        text-decoration: none !important;
    }

    .mybtn1 {
        float: right;
        font-size: 14px;
        font-style: italic;
        font-weight: bold;
        text-transform: uppercase;
        border: 2px dotted #188686;
        border-radius: 5px;
        padding: 2px 5px;
        margin-left: 5px;
        background: #188686;
        color: #fff;
        text-decoration: none !important;
    }

    .txt {
        clear: both;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-sm-12 mx-auto">
        <div class="card my-4">
            <div class="card-body">
                <h4>
                    Employment Exchange Re-Registration Non-aadhaar
                    <a href="<?= base_url('spservices/employment-re-registration') ?>" class="mybtn1" target="_blnk">
                        RE-REGISTRATION <i class="fa fa-edit"></i>
                    </a>

                    <a href="<?= base_url('spservices/employment-reregistration-nonaadhaar') ?>" class="mybtn" target="_blnk">
                        NEW APPLICATION <i class="fa fa-plus"></i>
                    </a>
                </h4>
                <?php if ($this->session->flashdata('pay_message') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong></strong> <?= $this->session->flashdata('pay_message') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <?php if ($employment_exchange_renonaadhaar) : ?>
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
                            <?php
                            foreach ($employment_exchange_renonaadhaar as $key => $dbrow) :
                                $obj_id = $dbrow->_id->{'$id'};
                                $payment_status = $dbrow->form_data->payment_status ?? '';
                                $status = $dbrow->service_data->appl_status;
                                $certificate = $dbrow->form_data->output_certificate ?? '';
                                $certificatePath = (strlen($certificate) ? base_url($certificate) : '#');
                                $ppr_grn = isset($dbrow->form_data->pfc_payment_response->GRN) ? $dbrow->form_data->pfc_payment_response->GRN : '';
                                $ppr_status = isset($dbrow->form_data->pfc_payment_response->STATUS) ? $dbrow->form_data->pfc_payment_response->STATUS : ''; ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td class="txt"><a href="<?= base_url('spservices/employment-reregistration-nonaadhaar/view/' . $obj_id) ?>"><?= $dbrow->service_data->appl_ref_no ?></a></td>
                                    <td><?= isset($dbrow->form_data->service_name) ? $dbrow->form_data->service_name : "" ?></td>
                                    <td><?= isset($dbrow->service_data->created_at) ? format_mongo_date($dbrow->service_data->created_at) : "" ?></td>
                                    <td><?= getappstatus($status) ?></td>
                                    <td>
                                        <?php if ($status == "DRAFT") { ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?= base_url("spservices/employmentnonaadhaar/reregistration/search_reg_details/") . $obj_id ?>">Complete Your Application</a>
                                        <?php } elseif ($status == "submitted") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/employment-reregistration-nonaadhaar/acknowledgements/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/employment-reregistration-nonaadhaar/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                        <?php } elseif ($status == "QS") {
                                        ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/employment-reregistration-nonaadhaar/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/employment-reregistration-nonaadhaar/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                            <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/employment-reregistration-nonaadhaar/personal-details/' . $obj_id) ?>">Reply to query</a>
                                            <?php
                                        } elseif ($status == "payment_initiated") { ?>

                                            <?php if (isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "P" || $value->form_data->pfc_payment_response->STATUS === "")) {
                                            ?>
                                                <form method="post" name="getGRN" id="getGRN" action="<?= $this->config->item('egras_grn_cin_url') ?>">
                                                    <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?= $value->form_data->pfc_payment_response->DEPARTMENT_ID ?>" />
                                                    <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE" value="<?= $value->form_data->payment_params->OFFICE_CODE ?>" />
                                                    <input type="hidden" id="AMOUNT" name="AMOUNT" value="<?= $value->form_data->pfc_payment_response->AMOUNT ?>" />
                                                    <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly />
                                                    <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?= base_url('spservices/employmentnonaadhaar/PaymentResponse/cin_response') ?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id="submit" class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK" value="Verify Payment1" />
                                                </form>
                                            <?php
                                            } elseif (isset($value->form_data->pfc_payment_response) && !empty($value->form_data->pfc_payment_response->GRN) && ($value->form_data->pfc_payment_response->STATUS === "Y")) {
                                            ?>
                                                <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/employmentnonaadhaar/reregistration/finalsubmition/' . $obj_id) ?>">Verify Payment</a>
                                            <?php
                                            } else {  ?>
                                                <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/employmentnonaadhaar/Payment/verify/' . $obj_id) ?>">Make/Verify Payment</a>
                                            <?php }
                                            ?>
                                        <?php } elseif ($status == "S" || $status == "QA" || $status == "PR" || $status == "R" || $status == "F") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/employment-reregistration-nonaadhaar/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/employment-reregistration-nonaadhaar/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>

                                        <?php } elseif ($status == "D") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/employment-reregistration-nonaadhaar/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/employment-reregistration-nonaadhaar/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                            <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?= base_url($value->form_data->certificate) ?>">Download Certificate</a>
                                        <?php } ?>
                          
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No Data Found
                    <p>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>