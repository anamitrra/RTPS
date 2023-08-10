<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-header text-center" style="background:#9edad8">Application Processing history</div>
            <div class="card-body">
                <table class="table  table-sm" id="application_list_table">
                    <tr>
                        <td>
                            <p style="font-size:16px"><b>RTPS Ref. No. : <span class="text-danger"><?php echo $dbrow->service_data->appl_ref_no; ?></span></b></p>
                        </td>
                        <td>
                            <?php if(!empty($dbrow->service_data->submission_date)){ ?>
                            <p style="font-size:16px ; text-align:right "><b>Application Receiving Date : <?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))); ?></b></p>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
                <?php if (isset($dbrow->service_data->appl_status) && $dbrow->service_data->appl_status == "QS") { ?>
                    <p style="font-size:18px;"><label><i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="text-danger"> Waiting for applicant response. Please <a href="<?= base_url('spservices/change_institute_ahsec/registration/queryform/' . $dbrow->_id->{'$id'}) ?>" class="btn btn-info btn-sm">click here</a> to proceed.</span></label></p>
                <?php } elseif (isset($dbrow->service_data->appl_status) && $dbrow->service_data->appl_status == "D") { ?>
                    <a class="btn btn-success btn-sm mbtn text-center" target="_blank" href="<?= base_url('spservices/change_institute_ahsec/registration/download_certificate/' . $dbrow->_id->{'$id'}) ?>"><i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD CERTIFICATE</a>
                <?php }
                ?>
                <table class="table table-sm table-striped table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Date &AMP; time</th>
                            <th>Action taken</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($dbrow->processing_history)) {
                            $processingHistory = $dbrow->processing_history;
                            foreach ($processingHistory as $key=>$rows) { ?>
                                <tr>
                                    <td><?=sprintf("%02d", $key+1)?></td>
                                    <td><?=date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time)))?></td>
                                    <td><?=$rows->action_taken?></td>
                                    <td><?=$rows->remarks?></td>
                                </tr><?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No records available</td></tr>';
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>