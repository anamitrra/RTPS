<style type="text/css">
legend {
    display: inline;
    width: auto;
}
ol li {
    font-size: 14px;
    font-weight: bold;
}

</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
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
                            <p style="font-size:16px ; text-align:right "><b>Application Receiving Date : <span class="text-danger"><?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))); ?></span></b></p>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm table-striped table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Sl. No.</th>
                            <th class="text-center">Application Status</th>
                            <th class="text-center">Payment Status</th>
                            <th class="text-center">Consumer No</th>
                            <th class="text-center">Bill No</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($dbrow->processing_history_raw)) {
                            $processingHistory = $dbrow->processing_history_raw;
                            foreach ($processingHistory as $key=>$rows) { ?>
                                <tr>
                                    <td class="text-center"><?=sprintf("%02d", $key+1)?></td>
                                    <!-- <td></td> -->
                                    <td><?=$rows->applStatus?></td>
                                    <td class="text-center">
                                            <?php if($rows->paymentLink != null){?><a href="<?=$rows->paymentLink?>" target="_blank">Click Here</a>
                                            <?php } ?>
                                    </td>
                                    <td class="text-center"><?=$rows->consNo?></td>
                                    <td class="text-center">
                                            <?php if($rows->document != null){?><a href="<?=$rows->billNo?>" target="_blank">Download</a>
                                            <?php } ?>
                                    </td>
                                    <td class="text-center">
                                            <?php if($rows->document != null){?><a href="<?=$rows->remarks?>" target="_blank">Download</a>
                                            <?php } ?>
                                    </td>
                                    <td class="text-center">
                                            <?php if($rows->document != null){?><a href="<?=$rows->document?>" target="_blank">Download</a>
                                            <?php } ?>
                                    </td>
                                </tr><?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No records available</td></tr>';
                        }?>
                    </tbody>
                </table>
                <?php if(isset($dbrow->processing_history_raw) && !empty($dbrow->processing_history_raw[0]->applHistory)){ ?>
                    <h5>Processing History</h5>
                    <table class="table table-sm table-striped table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Date </th>
                            <th class="text-center">Task Details</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($dbrow->processing_history_raw)) {
                            $applHistory = $dbrow->processing_history_raw[0]->applHistory;
                            foreach ($applHistory as $key=>$rows) { ?>
                                <tr>
                                    
                                    <td><?=$rows->executed_time?></td>
                                    <td><?=$rows->action?></td>
                                    <td><?=$rows->remarks?></td>
                                </tr><?php
                            }
                        } ?>
                    </tbody>
                </table>
               <?php } ?>
            </div>
        </div>
    </div>
</div>