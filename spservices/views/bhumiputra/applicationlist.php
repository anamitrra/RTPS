<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
        width: 100% !important;
        margin-bottom: 3px;
    }
</style>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4>Caste Certificate </h4>
                    <?php if ($this->session->flashdata('pay_message') != null) { ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong></strong> <?= $this->session->flashdata('pay_message') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($caste): ?>
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
                                foreach ($caste as $key => $value):
                                    $obj_id = $value->_id->{'$id'};
                                    $certificate = $value->certificate??'';
                                    $payment_status = $value->payment_status??'';
                                    $ppr_grn = isset($value->pfc_payment_response->GRN)?$value->pfc_payment_response->GRN:'';
                                    $ppr_status = isset($value->pfc_payment_response->STATUS)?$value->pfc_payment_response->STATUS:'';
                                    $certificatePath = (strlen($certificate)?base_url($certificate):'#')?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $value->service_data->rtps_trans_id ?></td>
                                        <td><?= isset($value->service_data->service_name) ? $value->service_data->service_name : "" ?></td>
                                        <td><?= isset($value->created_at) ? format_mongo_date($value->created_at) : "" ?></td>
                                        <td><?= getstatusname($value->service_data->appl_status)?></td>
                                        <td>
                                            <?php if ($payment_status == "PAID") { ?>
                                                <a href="<?= base_url("spservices/applications/acknowledgement/") . $obj_id ?>" class="btn btn-success btn-sm mbtn" >Acknowledgement</a>
                                            <?php } if (strtolower($value->service_data->appl_status) == "draft") { ?>
                                                <a href="<?= base_url('spservices/bhumipura/index/'.$obj_id)?>" class="btn btn-primary btn-sm mbtn" >Complete Your Application</a>
                                            <?php } elseif ($value->service_data->appl_status == "QS") { ?>
                                                <a href="<?= base_url('spservices/bhumiputra/Registration/query/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Reply to query</a>
                                            <?php } elseif ($value->service_data->appl_status == "D") { ?>
                                                <a href="<?=$certificatePath?>" target="_blank" class="btn btn-success btn-sm mbtn" >Download/View</a>
                                            <?php } elseif ($value->service_data->appl_status == "PAID") {
                                                if(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))){ ?>
                                                    <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                        <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$value->pfc_payment_response->DEPARTMENT_ID?>" />
                                                        <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$value->payment_params->OFFICE_CODE?>" />
                                                        <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$value->pfc_payment_response->AMOUNT?>" />
                                                        <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                        <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/necresponse/cin_response')?>" />
                                                        <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="Verify Payment" />
                                                    </form>
                                                    <?php } else { ?>
                                                       <a href="<?= base_url('spservices/bhumiputra/initiate/'.$obj_id)?>" class="btn btn-warning btn-sm mbtn" >Make payment</a> 
                                                    <?php }//End of if else ?>
                                                
                                            <?php } else { ?>
                                                <a href="<?=base_url('spservices/bhumiputra/Registration/track/'.$obj_id)?>" class="btn btn-secondary btn-sm mbtn" >Track status</a>
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
