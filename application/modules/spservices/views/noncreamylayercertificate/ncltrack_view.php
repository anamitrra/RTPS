<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-header text-center" style="background:#9edad8">Application Processing history</div>
            <div class="card-body">
                <table class="table  table-sm" id="application_list_table">
                    <tr>
                        <td>
                            <p style="font-size:16px"><b>RTPS Ref. No. : <span
                                        class="text-danger"><?php echo $dbrow->service_data->rtps_trans_id; ?></span></b>
                            </p>
                        </td>
                        <td>
                            <p style="font-size:16px ; text-align:right "><b>Application Receiving Date :
                                    <?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))); ?></b>
                            </p>
                        </td>
                    </tr>
                </table>
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
                            foreach ($processingHistory as $key => $rows) { ?>
                        <tr>
                            <td><?= sprintf("%02d", $key + 1) ?></td>
                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?>
                            </td>
                            <td><?= $rows->action_taken ?></td>
                            <td><?= $rows->remarks ?></td>
                        </tr><?php
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">No records available</td></tr>';
                                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>