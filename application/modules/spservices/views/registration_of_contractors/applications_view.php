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
                    Registration of Contractors
                    <a href="<?= base_url('spservices/registration_of_contractors') ?>" class="mybtn" target="_blnk">
                        NEW APPLICATION <i class="fa fa-plus"></i>
                    </a>
                </h4>
                <?php if ($this->session->flashdata('pmt_msg') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong></strong> <?= $this->session->flashdata('pmt_msg') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } else if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong></strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <?php if ($reg_of_contractors) : ?>
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
                            foreach ($reg_of_contractors as $key => $dbrow) :
                                if(!$dbrow)
                                {
                                    continue;
                                }
                                $obj_id = $dbrow->_id->{'$id'};
                                $payment_status = $dbrow->form_data->payment_status ?? '';
                                $status = $dbrow->service_data->appl_status;
                                $certificate = $dbrow->form_data->output_certificate ?? '';
                                $certificatePath = (strlen($certificate) ? base_url($certificate) : '#');
                                $ppr_grn = isset($dbrow->form_data->pfc_payment_response->GRN) ? $dbrow->form_data->pfc_payment_response->GRN : '';
                                $ppr_status = isset($dbrow->form_data->pfc_payment_response->STATUS) ? $dbrow->form_data->pfc_payment_response->STATUS : ''; ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td class="txt"><a href="<?= base_url('spservices/registration_of_contractors/view/' . $obj_id) ?>"><?= $dbrow->service_data->appl_ref_no ?></td>
                                    <td><?= isset($dbrow->form_data->service_name) ? $dbrow->form_data->service_name : "" ?></td>
                                    <td><?= isset($dbrow->service_data->created_at) ? getDateFormat($dbrow->service_data->created_at) : "" ?></td>
                                    <td><?= getstatusname($status) ?></td>
                                    <td>
                                        <?php if ($status == "DRAFT") { ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?= base_url("spservices/registration_of_contractors/personal-details/") . $obj_id ?>">Complete Your Application</a>
                                        <?php } elseif ($status == "submitted") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/registration_of_contractors/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/registration_of_contractors/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                        <?php } elseif ($status == "QS") {
                                        ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/registration_of_contractors/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/registration_of_contractors/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                            <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/registration_of_contractors/personal-details/' . $obj_id) ?>">Reply to query</a>
                                            <?php
                                        } elseif ($status == "payment_initiated") { ?>

                                            <?php if (isset($dbrow->form_data->pfc_payment_response) && !empty($dbrow->form_data->pfc_payment_response->GRN) && ($dbrow->form_data->pfc_payment_response->STATUS === "P" || $dbrow->form_data->pfc_payment_response->STATUS === "")) {
                                            ?>
                                                <form method="post" name="getGRN" id="getGRN" action="<?= $this->config->item('egras_grn_cin_url') ?>">
                                                    <input type="hidden" id="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?= $dbrow->form_data->pfc_payment_response->DEPARTMENT_ID ?>" />
                                                    <input type="hidden" id="OFFICE_CODE" name="OFFICE_CODE" value="<?= $dbrow->form_data->payment_params->OFFICE_CODE ?>" />
                                                    <input type="hidden" id="AMOUNT" name="AMOUNT" value="<?= $dbrow->form_data->pfc_payment_response->AMOUNT ?>" />
                                                    <input type="hidden" id="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly />
                                                    <input type="hidden" id="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?= base_url('spservices/registration_of_contractors/Payment_response/cin_response') ?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id="submit" class="btn btn-sm btn-warning mbtn" name="submit" target="_BLANK" value="Verify Payment" />
                                                </form>
                                            <?php
                                            } elseif (isset($dbrow->form_data->pfc_payment_response) && !empty($dbrow->form_data->pfc_payment_response->GRN) && ($dbrow->form_data->pfc_payment_response->STATUS === "Y")) {
                                            ?>
                                                <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/registration_of_contractors/registration/finalsubmition/' . $obj_id) ?>">Verify Payment</a>
                                            <?php
                                            } else {  ?>
                                                <a class="btn btn-warning btn-sm mbtn" href="<?= base_url('spservices/registration_of_contractors/Payment/verify/' . $obj_id) ?>">Make/Verify Payment</a>
                                            <?php }
                                            ?>
                                        <?php } elseif ($status == "S" || $status == "QA" || $status == "PR" || $status == "R" || $status == "F") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/registration_of_contractors/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/registration_of_contractors/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>

                                        <?php } elseif ($status == "D") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/registration_of_contractors/acknowledgement/") . $obj_id ?>">Acknowledgement</a>
                                            <a href="<?= base_url('spservices/registration_of_contractors/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn">Track</a>
                                            <a class="btn btn-primary btn-sm mbtn" target="_blank" href="<?= base_url($dbrow->form_data->certificate??'') ?>">Download Certificate</a>
                                        <?php } ?>
                          
                                    </td>
                                </tr>
                            <?php 
                        //pre($obj_id);
                        endforeach; ?>
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