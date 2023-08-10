<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style type="text/css">
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
        width: 100% !important;
        margin-bottom: 3px;
    }
    .blk{
        display: block;
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
                    Minority Community Certificates
                    <a href="<?=base_url('spservices/minority-certificate')?>" class="mybtn" target="_blnk">
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
                if ($minoritycertificates): ?>
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
                            foreach ($minoritycertificates as $key => $dbrow):
                                $obj_id = $dbrow->_id->{'$id'};
                                $payment_status = $dbrow->form_data->payment_status??'';
                                $status = $dbrow->service_data->appl_status;
                                $certificate = $dbrow->execution_data[0]->official_form_details->output_certificate??'';
                                $certificatePath = (strlen($certificate)?base_url($certificate):'#'); 
                                $ppr_grn = isset($dbrow->form_data->pfc_payment_response->GRN)?$dbrow->form_data->pfc_payment_response->GRN:'';
                                $ppr_status = isset($dbrow->form_data->pfc_payment_response->STATUS)?$dbrow->form_data->pfc_payment_response->STATUS:''; ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td><?= $dbrow->service_data->appl_ref_no ?></td>
                                    <td><?= isset($dbrow->service_data->service_name) ? $dbrow->service_data->service_name : "" ?></td>
                                    <td><?= isset($dbrow->service_data->created_at) ? format_mongo_date($dbrow->service_data->created_at) : "" ?></td>
                                    <td><?= getappstatus($status)?></td>
                                    <td>
                                        <?php if ($payment_status == "PAYMENT_COMPLETED") { ?>
                                            <a href="<?= base_url("spservices/minority-certificate-payment-acknowledgement/") . $obj_id ?>" class="btn btn-success btn-sm mbtn" >Acknowledgement</a>
                                            <a href="<?= base_url("spservices/minority-certificate-preview/") . $obj_id ?>" class="btn btn-info btn-sm mbtn"  target="_blank">Application Preview</a>
                                        <?php } if ($status == "DRAFT") { ?>
                                            <a href="<?= base_url('spservices/minority-certificate/'.$obj_id)?>" class="btn btn-primary btn-sm mbtn" >Complete Your Application</a>
                                        <?php } elseif ($status == "QUERY_ARISE") { ?>
                                            <a href="<?= base_url('spservices/minority-certificate-query/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Reply to query</a>
                                        <?php } elseif ($status == "QUERY_SUBMITTED") { ?>
                                            <a href="" class="btn btn-warning btn-sm mbtn" >Query replied</a>
                                        <?php } elseif ($status == "UNDER_PROCESSING") { ?>
                                            <a href="" class="btn btn-warning btn-sm mbtn" >Under processing</a>
                                        <?php } elseif ($status == "DELIVERED") { ?>
                                            <a href="<?=$certificatePath?>" target="_blank" class="btn btn-success btn-sm mbtn" >Delivered</a>
                                        <?php } elseif ($status == "REJECTED") { ?>
                                            <a href="#" class="btn btn-danger btn-sm mbtn" >Rejected</a>
                                        <?php } elseif ($payment_status !== "PAYMENT_COMPLETED") {
                                            if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                                <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$dbrow->form_data->pfc_payment_response->DEPARTMENT_ID?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$dbrow->form_data->payment_params->OFFICE_CODE?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$dbrow->form_data->pfc_payment_response->AMOUNT?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/minoritycertificates/paymentresponse/cin_response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                </form>
                                                <?php } else { ?>
                                                   <a href="<?= base_url('spservices/minority-certificate-payment/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn">Make payment</a> 
                                                <?php }//End of if else ?>
                                        <?php } else { ?>
                                            <!--<a href="" class="btn btn-secondary btn-sm mbtn" >Status mismatched</a>-->
                                        <?php } //End of if else ?>
                                        <a href="<?=base_url('spservices/minoritycertificates/track/status?id='.$dbrow->service_data->appl_ref_no)?>" class="btn btn-primary btn-sm mbtn" >Track</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>No Minority certificate(s) Found<p>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>