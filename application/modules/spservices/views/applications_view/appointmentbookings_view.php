<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style type="text/css">
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
        width: 100% !important;
        margin-bottom: 3px;
    }
    .mybtn {
        float:right; 
        font-size: 14px; 
        font-style: italic; 
        font-weight: bold; 
        text-transform: uppercase;
        border: 2px dotted #F40080; 
        border-radius: 5px; 
        padding: 2px 5px;
        background: #F40080;
        color: #fff;
    }
</style>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4>
                        Appointment booking for Marriage or Deed registration
                        <a href="<?=base_url('spservices/appointmentbooking')?>" class="mybtn" target="_blnk">
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
                    <?php }
                    if ($appointmentbookings): ?>
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
                                foreach ($appointmentbookings as $key => $row):
                                    $obj_id = $row->_id->{'$id'};
                                    $payment_status = $row->form_data->payment_status??'';
                                    $ppr_grn = isset($row->form_data->pfc_payment_response->GRN)?$row->form_data->pfc_payment_response->GRN:'';
                                    $ppr_status = isset($row->form_data->pfc_payment_response->STATUS)?$row->form_data->pfc_payment_response->STATUS:'';
                                    $DEPARTMENT_ID = $row->form_data->pfc_payment_response->DEPARTMENT_ID??'';
                                    $appl_status = $row->service_data->appl_status; ?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $row->service_data->appl_ref_no ?></td>
                                        <td><?= isset($row->service_data->service_name) ? $row->service_data->service_name : "" ?></td>
                                        <td><?= isset($row->service_data->submission_date) ? format_mongo_date($row->service_data->submission_date) : "" ?></td>
                                        <td><?= getstatusname($row->service_data->appl_status)?></td>
                                        <td>
                                            <?php if ($payment_status == "PAID") { ?>
                                                <a href="<?= base_url("spservices/appointmentbooking/registration/acknowledgement/") . $obj_id ?>" class="btn btn-success btn-sm mbtn" target="_blank">Acknowledgement</a>
                                                <a href="<?= base_url("spservices/appointmentbooking/registration/preview/") . $obj_id ?>" class="btn btn-info btn-sm mbtn"  target="_blank">Application Preview</a>
                                            <?php } if (strtolower($appl_status) == "DRAFT") { ?>
                                                <a href="<?= base_url('spservices/appointmentbooking/registration/index/'.$obj_id)?>" class="btn btn-primary btn-sm mbtn">Complete Your Application</a>
                                            <?php } elseif ($appl_status == "D") { ?>
                                                <a href="<?=base_url('spservices/appointmentbooking/registration/delivered/'.$obj_id)?>" target="_blank" class="btn btn-success btn-sm mbtn">Application Delivered</a>
                                            <?php } elseif (($appl_status == "payment_initiated") && ($ppr_status == "Y")) { ?>
                                                <a href="<?= base_url('spservices/appointmentbooking/paymentresponse/post_data/' . $DEPARTMENT_ID) ?>" class="btn btn-warning btn-sm mbtn" >Get Acknowledgement</a>
                                            <?php } elseif ($payment_status !== "PAID") {
                                                if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                                    <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                        <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$row->form_data->pfc_payment_response->DEPARTMENT_ID??''?>" />
                                                        <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$row->form_data->payment_params->OFFICE_CODE??''?>" />
                                                        <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$row->form_data->pfc_payment_response->AMOUNT??''?>" />
                                                        <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                        <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/appointmentbooking/paymentresponse/cin_response')?>" />
                                                        <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                    </form>
                                                    <?php } else { ?>
                                                       <a href="<?= base_url('spservices/appointmentbooking/payment/initiate/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn">Make payment</a> 
                                                    <?php }//End of if else ?>
                                                
                                            <?php } else { ?>
                                                <a href="<?=base_url('spservices/appointmentbooking/registration/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn"  target="_blank">Track status</a>
                                            <?php } //End of if else ?>
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>