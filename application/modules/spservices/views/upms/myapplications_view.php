<div class="content-wrapper p-2 pt-3">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark"><?=$card_title??'My applications'?></span>
            <span style="float: right; color:#000">
                Logged in as <strong><?=$this->session->loggedin_user_fullname?></strong>
                (Role <?=$this->session->loggedin_user_role_code?> of Level-<?=$this->session->loggedin_user_level_no?>)
            </span>
        </div>
        <div class="card-body">                
            <?php if ($myapplications): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Submitted on</th>
                            <th>Ref. no.</th>
                            <th>Applicant&apos;s name</th>
                            <th>Service name</th>
                            <th>Last action taken</th>
                            <th>Current status</th>
                            <th style="width:120px; text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($myapplications as $key => $row):
                            $obj_id = $row->_id->{'$id'};
                            $submission_date = $row->service_data->submission_date??'';                            
                            if(strlen($submission_date)) {
                                $submissionDate = date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($submission_date)));
                            } else {
                                $submissionDate = "Not available";
                            }//End of if else
                            
                            $appl_ref_no = $row->service_data->appl_ref_no;
                            $appl_status = $row->service_data->appl_status??'';
                            $applStatus = getstatusname($appl_status);
                            $processing_history = $row->processing_history??array();
                            $process_no = count($processing_history)?count($processing_history):0;//$row->process_no??0;
                            $status = $row->status??'';
                            $country = $row->form_data->country??'';
                            $addMe = strlen($country)?' ('.$country.')':'';
                            $certificatePath = (isset($row->form_data->certificate) && strlen($row->form_data->certificate))?base_url($row->form_data->certificate):'#';
                            
                            if($process_no) {
                                $actionTakenBy = $processing_history[$process_no-1]->processed_by??'';
                                $actionCode = $processing_history[$process_no-1]->action_taken??'';
                                $actionDetails = $processing_history[$process_no-1]->remarks??'';
                                $aprocessingTime = $processing_history[$process_no-1]->processing_time??'';
                                $lastAction = date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($aprocessingTime)));//$actionDetails.' @ '.date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($aprocessingTime)));
                            } else {
                                $actionCode = '';
                                $actionDetails = '';
                                $lastAction = $submissionDate;
                            }//End of if else ?>
                            <tr>
                                <td><?=sprintf("%03d", ($key+1))?></td>
                                <td><?=$submissionDate?></td>
                                <td><?=$appl_ref_no.$addMe?></td>
                                <td><?=$row->form_data->applicant_name??''?></td>
                                <td><?=$row->service_data->service_name??''?></td>
                                <td><?=$lastAction?></td>
                                <td><?=$applStatus?></td>
                                <td style="text-align:center">
                                    <a href='<?=base_url('spservices/upms/myapplications/preview/'.$obj_id)?>' class='btn btn-primary btn-sm' target='_blank' >View</a>
                                    <?php if($status === 'DELIVERED') {
                                        echo "<a href='{$certificatePath}' class='btn btn-success btn-sm' target='_blank'>View Certificate</a>";
                                    } elseif($status === 'REJECTED') {
                                        //echo "<a href='javascript:void(0)' class='btn btn-danger btn-sm' >Rejected</a>";
                                    } elseif(($status === 'QUERY_ARISE')) {
                                        //echo "<a href='javascript:void(0)' class='btn btn-info btn-sm' >Query made</a>";
                                    } elseif(($status === 'QUERY_PAYMENT_ARISE')) {
                                        //echo "<a href='javascript:void(0)' class='btn btn-success btn-sm' >Payment query made</a>";
                                    } else {
                                        $processLink = base_url('spservices/upms/myapplications/process/'.$obj_id);
                                        echo "<a href='{$processLink}' class='btn btn-warning btn-sm' >Process</a>";
                                    }//End of if else ?>                                  
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found<p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $("#dtbl").DataTable({
        "order": [[0, 'asc']],
        "lengthMenu": [[10, 20, 50, 100, 200], [10, 20, 50, 100, 200]]
    });
});
</script>