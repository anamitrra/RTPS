<?php $dbRows = $this->logreports_model->get_rows(["log_status"=>2]); ?>
<div class="content-wrapper">
    <div class="container">
        <div class="card mt-2">
            <div class="card-header p-1">
                <span class="h5 text-dark" style="font-size:18px; text-transform: uppercase;">Log reports</span>
            </div>
            <div class="card-body table-responsive-md" style="padding:5px">
                <table class="table table-bordered" id="dtbl">
                    <thead>
                        <tr>
                            <th style="width: 120px">Log time</th>
                            <th>Log message</th>
                            <th>Application Ref. no.</th>
                            <th style="width: 84px">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($dbRows) {
                            foreach($dbRows as $row) {
                                $obj_id = $row->{'_id'}->{'$id'};
                                $log_time = $row->log_time??'';
                                $logTime = strlen($log_time)?format_mongo_date($log_time, 'Y-m-d H:i'):'';
                                $log_msg = $row->log_msg??'';
                                $appl_ref_no = $row->appl_ref_no??'';
                                if(is_array($log_msg)) {
                                    $logMsg = json_encode($log_msg, JSON_PRETTY_PRINT);
                                } else {
                                    $logMsg = $log_msg;
                                }//End of if else
                                $deleteLink = base_url('spservices/logreports/deleteme/'.$obj_id);
                                echo "<tr>"
                                . "<td>{$logTime}</td>"
                                . "<td>{$logMsg}</td>"
                                . "<td>{$appl_ref_no}</td>"
                                . "<td><a href='{$deleteLink}' class='btn btn-sm btn-danger'>Delete</a></td>"
                                . "</tr>";
                            }//End of foreach()
                        }//End of if ?>
                    </tbody>
                </table>
            </div><!--End of .card-body -->
        </div><!--End of .card -->
    </div><!--End of .container -->
</div><!--End of .content-wrapper -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $('#dtbl').DataTable({
        order: [[0, 'desc']]
    });
});
</script>
