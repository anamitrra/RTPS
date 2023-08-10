<?php
$userdata = $this->session->userdata();
$is_citizen=isset($userdata['opt_status']) && $userdata['opt_status'] ? true:false;
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Process Name...</th>
                <th class="text-center">Processed By</th>
                <th class="text-center">Processed on</th>
                <th class="text-center">Message</th>
                <th class="text-center">Attachments</th>
                <th class="text-center">Comment By Applicant</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $processCount = 0;
            $benchCreatedCount = 0;
            foreach ($appealProcessList as $process) {

                // print_r($process->approved_files);

                if($process->action == 'second-appeal-create-bench'){
                    $benchCreatedCount++;
                }
                $skipActionForAppellant = ['generate-disposal-order','upload-disposal-order','generate-rejection-order','upload-rejection-order','generate-hearing-order','upload-hearing-order','generate-penalty-order','upload-penalty-order'];
                if((!$this->session->userdata('userId') || ($this->session->has_userdata('role') && $this->session->userdata('role')->slug === 'DPS')) && in_array($process->action,$skipActionForAppellant)){
                    continue;
                }
            ?>
                <tr class="text-center">
                    <td><?= ++$processCount ?></td>
                    <td class="text-capitalize text-center">
                        <?php
                        $generatedMsgPreText = '';
                        switch ($process->action) {
                            case 'reply':
                                echo '<span class="badge badge-info">Reply</span>';
                                break;
                            case 'forward-to-aa':
                            case 'second-appeal-forward-to-aa':
                                echo '<span class="badge badge-secondary">Forwarded to AA</span>';
                                break;
                            case 'second-appeal-forward-to-registrar':
                                echo '<span class="badge badge-secondary">Forwarded to Registrar</span>';
                                break;
                            case 'second-appeal-forward-to-chairman':
                                echo '<span class="badge badge-secondary">Forwarded to Chairman</span>';
                                break;
                            case 'second-appeal-forward-to-moc':
                                echo '<span class="badge badge-secondary">Forwarded to Member of Commission</span>';
                                break;
                            case 'revert-back-to-da':
                            case 'second-appeal-revert-back-to-da':
                                echo '<span class="badge badge-info">Reverted to DA</span>';
                                break;
                            case 'second-appeal-revert-back-to-rr':
                                echo '<span class="badge badge-info">Reverted to Registrar</span>';
                                break;
                            case 'generate-disposal-order':
                                echo '<span class="badge badge-warning">Disposal Order Generated</span>';
                                break;
                            case 'upload-disposal-order':
                            case 'second-appeal-upload-disposal-order':
                                echo '<span class="badge badge-warning">Disposal Order Uploaded</span>';
                                break;
                            case 'second-appeal-approve-disposal-order':
                                echo '<span class="badge badge-warning">Disposal Order Approved</span>';
                                break;
                            case 'resolved':
                            case 'second-appeal-issue-disposal-order':
                                echo '<span class="badge badge-success">Resolved</span>';
                                break;
                            case 'generate-rejection-order':
                                echo '<span class="badge badge-warning">Rejection Order Generated</span>';
                                break;
                            case 'upload-rejection-order':
                            case 'second-appeal-upload-rejection-order':
                                echo '<span class="badge badge-warning">Rejection Order Uploaded</span>';
                                break;
                            case 'second-appeal-approve-rejection-order':
                                echo '<span class="badge badge-warning">Rejection Order Approved</span>';
                                break;
                            case 'rejected':
                            case 'second-appeal-issue-rejection-order':
                                echo '<span class="badge badge-danger">Rejected</span>';
                                break;
                            case 'penalize':
                                echo '<span class="badge badge-danger">Penalized</span>';
                                break;
                            case 'generate-penalty-order':
                                echo '<span class="badge badge-warning">Penalty Order Generated</span>';
                                 break;
                            case 'approve-penalty-order':
                                echo '<span class="badge badge-info">Penalty Order Approved</span>';
                                break;
                            case 'upload-penalty-order':
                                echo '<span class="badge badge-warning">Penalty Order Uploaded</span>';
                                break;

                            case 'second-appeal-final-verdict':
                                echo '<span class="badge badge-success">Final Verdict</span>';
                                break;

                            case 'comment-by-bench-member':
                                echo '<span class="badge badge-info">Commented by Bench Member</span>';
                                break;

                            case 'reassign':
                                echo '<span class="badge badge-info">Reassigned</span>';
                                break;
                            case 'remark':
                                echo '<span class="badge badge-warning">Remark</span>';
                                break;
                            case 'in-progress':
                                echo '<span class="badge badge-dark">In Progress</span>';
                                break;
                            case 'provide-hearing-date':
                                echo '<span class="badge badge-info">Hearing Date Provided</span>';
                                break;
                            case 'second-appeal-change-hearing-date':
                                echo '<span class="badge badge-info">Hearing Date Changed</span>';
                                break;
                            case 'second-appeal-confirm-hearing-date':
                                echo '<span class="badge badge-info">Hearing Date Approved</span>';
                                break;
                            case 'generate-hearing-order':
                            case 'second-appeal-generate-hearing-order':
                                echo '<span class="badge badge-warning">Hearing Order Generated</span>';
                                $generatedMsgPreText = 'Hearing for ';
                                break;
                            case 'modify-hearing-order':
                            case 'second-appeal-modify-hearing-order':
                                echo '<span class="badge badge-warning">Hearing Order Modified</span>';
                                $generatedMsgPreText = 'Hearing for ';
                                break;
                            case 'upload-hearing-order':
                            case 'second-appeal-upload-hearing-order':
                                echo '<span class="badge badge-warning">Hearing Order Uploaded</span>';
                                $generatedMsgPreText = 'Hearing for ';
                                break;
                            case 'approve-hearing-order':
                            case 'second-appeal-approve-hearing-order':
                                echo '<span class="badge badge-info">Hearing Order Approved</span>';
                                break;
                            case 'second-appeal-issue-hearing-order':
                                echo '<span class="badge badge-success">Hearing Order Issued</span>';
                                break;
                            case 'seek-info':
                            case 'second-appeal-seek-info':
                                echo '<span class="badge badge-primary">Seeking Info</span>';
                                $generatedMsgPreText = 'Seeking Information from ';
                                break;
                            case 'issue-order':
                                echo '<span class="badge badge-info">Order Issued</span>';
                                $generatedMsgPreText = 'Order Issued to ';
                                break;
                            case 'second-appeal-create-bench':
                                if($benchCreatedCount > 1){
                                    echo '<span class="badge badge-info">Bench Updated</span>';
                                }else{
                                    echo '<span class="badge badge-warning">Bench Created</span>';
                                }
                                break;
                            case 'dps-reply':
                                echo '<span class="badge badge-info">DPS replied</span>';
                                break;
                            case 'appellate-reply':
                                echo '<span class="badge badge-info">Appellate Authority replied</span>';
                                break;
                                case 'second-appeal-issue-hearing-order':
                                   echo '<span class="badge badge-info">Hearing Notice Issued</span>';
                                    break;
                            case 'appellant-dsc-sign-generated':
                                echo '<span class="badge badge-info">Appellant DSC Sign</span>';
                                    break;
                            case 'dps-dsc-sign-generated':
                                echo '<span class="badge badge-info">DPS DSC Sign</span>';
                                    break;
                            case 'rejection-order-dsc-sign-generated':
                                echo '<span class="badge badge-info">Rejection-Order DSC Sign</span>';
                                    break;
                            case 'disposal-order-dsc-sign-generated':
                                echo '<span class="badge badge-info">Disposal-Order DSC Sign</span>';
                                    break;
                            default:
                                echo '<span class="badge badge-secondary">initiated</span>';
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <!-- <?= $process->user->name ?> -->
                        <?= isset($process->action_taken_by_name) ? $process->action_taken_by_name : $process->user->designation ?>
                        
                    </td>
                    <td><?= $this->mongo_db->getDateTime($process->created_at) ?></td>
                    <td><?= $process->message ?>
                        <?php
                        if(property_exists($process,'notifiable')){
                            switch ($process->notifiable){
                                case 'dps':
                                    echo '<br>'.$generatedMsgPreText.'DPS';
                                    break;
                                case 'appellant':
                                    echo '<br>'.$generatedMsgPreText.'Appellant';
                                    break;
                                case 'appellate':
                                    echo '<br>'.$generatedMsgPreText.'Appellate Authority';
                                    break;
                                default:
                                    echo '<br>'.$generatedMsgPreText.' both DPS and Appellant';
                                    break;
                            }
                        }

                        if($process->action === 'second-appeal-create-bench'){
                            $benchMemberIdArray[] = new \MongoDB\BSON\ObjectId($process->delegate_to_chairman->userId);
                            foreach ($process->bench_member as $member){
                                $benchMemberIdArray[] = new \MongoDB\BSON\ObjectId($member->userId);
                            }
                            $benchMemberList = $this->users_model->get_where_in('_id',$benchMemberIdArray);
                            $authorizedPerson = $this->users_model->get_by_doc_id($process->delegate_to_chairman->userId);
                            $unAuthBenchMember = [];
                            foreach ($benchMemberList as $benchMemberInfo){
                                if($process->delegate_to_chairman->userId == strval($benchMemberInfo->{'_id'}->{'$id'})){
                                    $authorizedPersonName = $benchMemberInfo->name;
                                    $authorizedPersonDesignation = $benchMemberInfo->designation;
                                    echo '<br>Authorized Person : '.$authorizedPersonName.' ('.$authorizedPersonDesignation.')';
                                }else{
                                    $unAuthBenchMember[] =$benchMemberInfo->name.' ('.$benchMemberInfo->designation;
                                }
                            }
                            echo '<br>Other Bench Member : '.implode(', ',$unAuthBenchMember);
                        }
                        if (property_exists($process, 'date_of_hearing')) {
                            echo '<br>Date of Hearing : '.format_mongo_date($process->date_of_hearing,'d-m-Y');
                        }
                        if(property_exists($process,'is_final_hearing') && $process->is_final_hearing){
                            echo '<br><span class="badge badge-success">Final Hearing</span>';
                        }
                        if (property_exists($process, 'last_date_of_submission')) {
                            echo '<br>Last date of submission : '.format_mongo_date($process->last_date_of_submission,'d-m-Y');
                        }
                        $penaltyContent = '';
                        if (property_exists($process, "total_penalty_amount")) {
                            $penaltyContent .= 'Penalty Amount Per Day: ' . $process->penalty_amount . ' INR';
                            $penaltyContent .= '<br>Number of days delayed: ' . $process->number_of_days_of_delay ;
                            $penaltyContent .= '<br>Total Penalty Amount: ' . $process->total_penalty_amount . ' INR';

                        }
                        if(property_exists($process,'penalty_to_user')){
                            $penaltyToUser = $this->users_model->get_by_doc_id($process->penalty_to_user);
                            $penaltyContent .= '<br/>Penalty imposed to '.$penaltyToUser->name;
                        }
                        echo '<p>'.$penaltyContent.'</p>';
                        if (isset($process->previous_user) && $process->previous_user != '' && property_exists($process, 'previous_user') && $process->action === 'forward') {
                            $forwardFrom = $this->users_model->get_by_doc_id($process->previous_user);
                            echo '<p>Forwarded from : ' . $forwardFrom->name .' </p>';
                        }
                        if (isset($process->forward_to) && $process->forward_to != '' && property_exists($process, 'forward_to')) {
                            $forwardTo = $this->users_model->get_by_doc_id($process->forward_to);
                            $this->load->model('roles_model');

                            $forwardToRole = (isset($forwardTo->roleId)) ? $this->roles_model->get_by_doc_id($forwardTo->roleId) : '';   ;

                            echo (isset($forwardTo->name) && isset($forwardToRole->role_name) ) ? '<p>Forwarded to : ' . $forwardTo->name .' ('.  $forwardToRole->role_name.')</p>' : ''; ;
                        }
                        if (isset($process->previous_user) && $process->previous_user != '' && property_exists($process, 'previous_user') && $process->action === 'reassign') {
                            $reassignFrom = $this->users_model->get_by_doc_id($process->previous_user);
                            echo '<p>Reassigned from : ' . $reassignFrom->name .' </p>';
                        }
                        if (isset($process->reassign_to) && $process->reassign_to != '' && property_exists($process, 'reassign_to')) {
                            $reassignTo = $this->users_model->get_by_doc_id($process->reassign_to);
                            $this->load->model('roles_model');
                            $reassignToRole = $this->roles_model->get_by_doc_id($reassignTo->roleId);
                            echo '<p>Reassigned to : ' . $reassignTo->name .' ('.  $reassignToRole->role_name.')</p>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        
                            if($process->action === "appellant-dsc-sign-generated" && !empty($process->documents) ){ ?>
                                <a href="<?=$is_citizen ?  base_url("appeal/ams/downloadSign/".$process->_id->{'$id'}) :  base_url("appeal/test_app/downloadSign/".$process->_id->{'$id'}) ?>"  class="btn btn-outline-primary btn-sm" >Download Signed Document : Appellant</a>
                            
                            <?php }  ?>

                            <?php 
                        
                            if($process->action === "dps-dsc-sign-generated" && !empty($process->documents) ){ ?>
                                <a href="<?=$is_citizen ? base_url("appeal/ams/downloadSign/".$process->_id->{'$id'}) :  base_url("appeal/test_app/downloadSign/".$process->_id->{'$id'}) ?>"  class="btn btn-outline-primary btn-sm" >Download Signed Document : DPS</a>
                            
                            <?php }  ?>


                            <?php 
                        
                            if($process->action === "rejection-order-dsc-sign-generated" && !empty($process->documents) ){ ?>
                                <a href="<?=$is_citizen ?  base_url("appeal/ams/downloadSign/".$process->_id->{'$id'}) :  base_url("appeal/test_app/downloadSign/".$process->_id->{'$id'}) ?>"  class="btn btn-outline-primary btn-sm" >Download Signed Document : Rejection Order</a>
                            
                            <?php }  ?>


                            <?php 
                        
                            if($process->action === "disposal-order-dsc-sign-generated" && !empty($process->documents) ){ ?>
                                <a href="<?=$is_citizen ?  base_url("appeal/ams/downloadSign/".$process->_id->{'$id'}) :  base_url("appeal/test_app/downloadSign/".$process->_id->{'$id'}) ?>"  class="btn btn-outline-primary btn-sm" >Download Signed Document : Disposal Order</a>
                            
                            <?php }  ?>


                               <?php 
                        
                              if (property_exists($process, 'disposal_order') && is_array((array)$process->disposal_order)) {
                            echo '<a href="' . base_url($process->disposal_order[0]) . '" class="btn btn-sm btn-outline-info" target="_blank">View Disposal Order</a>';
                        }?>

                            


                            


                            


                        <?php
                        
                        if($process->action === 'second-appeal-approve-hearing-order' && !isset($hearingForAppellantOrderSigned
) && !isset($hearingForDPSOrderSigned)){
                        ?>
                            <a href="<?=base_url('appeal/second/process/view-order/hearing-order/'.$process->appeal_id.'/appellant')?>" class="btn btn-outline-primary btn-sm"  target="_blank">View Hearing Order : Appellant</a>
                            <a href="<?=base_url('appeal/second/process/view-order/hearing-order/'.$process->appeal_id.'/dps')?>" class="btn btn-outline-primary btn-sm"  target="_blank">View Hearing Order : DPS</a>
                        <?php
                        }
                        if($process->action === 'second-appeal-issue-hearing-order'){
                            if(isset($hearingForAppellantOrderSigned)){
                        ?>
                            <a href="<?=base_url($hearingForAppellantOrderSigned)?>" class="btn btn-outline-danger btn-sm"  target="_blank">View Hearing Order : Appellant <small>(signed)</small></a>
                        <?php
                            }
                            if(isset($hearingForDPSOrderSigned)){
                        ?>
                                <a href="<?=base_url($hearingForDPSOrderSigned)?>" class="btn btn-outline-danger btn-sm"  target="_blank">View Hearing Order : DPS <small>(signed)</small></a>
                                <?php
                            }
                        }
                        if($process->action === 'second-appeal-approve-disposal-order' && isset($disposalOrderSigned)){
                        ?>
                            <a href="<?=base_url('appeal/second/process/view-order/disposal-order/'.$process->appeal_id.'/appellant')?>" class="btn btn-outline-primary btn-sm"  target="_blank">View Disposal Order</a>
                        <?php
                        }
                        if($process->action === 'resolved' && isset($disposalOrderSigned)){
                            ?>
                            <a href="<?=base_url($disposalOrderSigned)?>" class="btn btn-outline-success btn-sm"  target="_blank">View Disposal Order <small>(signed)</small></a>
                            <?php
                        }
                        if($process->action === 'second-appeal-approve-rejection-order'){
                        ?>
                            <a href="<?=base_url('appeal/second/process/view-order/rejection-order/'.$process->appeal_id.'/appellant')?>" class="btn btn-outline-danger btn-sm"  target="_blank">View Rejection Order</a>
                        <?php
                        }
                        if($process->action === 'rejected' && isset($rejectionOrderSigned)){
                        ?>
                            <a href="<?=base_url($rejectionOrderSigned)?>" class="btn btn-outline-danger btn-sm"  target="_blank">View Rejection Order <small>(signed)</small></a>
                        <?php
                        }
                        if (property_exists($process, 'documents') && is_array($process->documents)) {
                            $List = implode(', ', array_map(function ($v) {
                                return base_url($v);
                            }, $process->documents));
                            echo '<a href="#!" data-links="' . $List . '" class="btn btn-sm btn-outline-info attachments">Attachments</a>';
                        }
                        if (property_exists($process, 'rejection_order') && is_array($process->rejection_order)) {
                            $List = implode(', ', array_map(function ($v) {
                                return base_url($v);
                            }, $process->rejection_order));
                            echo '<a href="#!" data-links="' . $List . '" class="btn btn-sm btn-outline-info attachments">Attachments</a>';
                        }
                        if (property_exists($process, 'approved_files') && is_array((array)$process->approved_files)) {

                            if(isset($process->approved_files->appellantHearingOrder))
                                echo '<a href="'.base_url($process->approved_files->appellantHearingOrder).'" class="btn btn-sm btn-outline-info mt-1" target="_blank">View Appellant Hearing Order</a>';

                            if(isset($process->approved_files->dpsHearingOrder))
                                echo '<a href="' . base_url($process->approved_files->dpsHearingOrder) . '" class="btn btn-sm btn-outline-info mt-1" target="_blank">View DPS Hearing Order</a>';

//                            if(isset($process->approved_files->disposalOrder))
//                                echo '<a href="' . base_url($process->approved_files->disposalOrder) . '" class="btn btn-sm btn-outline-success mt-1" target="_blank">View Disposal Order</a>';
//
//                            if(isset($process->approved_files->rejectionOrder))
//                                echo '<a href="' . base_url($process->approved_files->rejectionOrder) . '" class="btn btn-sm btn-outline-danger mt-1" target="_blank">View Rejection Order</a>';

                            if(isset($process->approved_files->order))
                                echo '<a href="' . base_url($process->approved_files->order) . '" class="btn btn-sm btn-outline-info" target="_blank">View Order</a>';
                            if(isset($process->approved_files->penaltyOrder))
                                echo '<a href="' . base_url($process->approved_files->penaltyOrder) . '" class="btn btn-sm btn-outline-info" target="_blank">View Penalty Order</a>';
                        }
                        if (property_exists($process, 'appellant_hearing_order') && is_array((array)$process->appellant_hearing_order)) {
                            echo '<a href="' . base_url($process->appellant_hearing_order[0]) . '" class="btn btn-sm btn-outline-info" target="_blank">View Appellant Hearing Order</a>';
                        }
                        if (property_exists($process, 'dps_hearing_order') && is_array((array)$process->dps_hearing_order)) {
                            echo '<a href="' . base_url($process->dps_hearing_order[0]) . '" class="btn btn-sm btn-outline-info" target="_blank">View DPS Hearing Order</a>';
                        }
                        if (property_exists($process, 'penalty_order') && is_array((array)$process->penalty_order)) {
                            echo '<a href="' . base_url($process->penalty_order[0]) . '" class="btn btn-sm btn-outline-info" target="_blank">View Penalty Order</a>';
                        }
//                        if (property_exists($process, 'disposal_order') && is_array((array)$process->disposal_order)) {
//                            echo '<a href="' . base_url($process->disposal_order[0]) . '" class="btn btn-sm btn-outline-info" target="_blank">View Disposal Order</a>';
//                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if(property_exists($process, 'comment')){
                            echo '<p>'.$process->comment.'</p>';
                        }
                        if (property_exists($process, 'comment_documents') && !in_array($process->comment_documents, array('', false))) {
                            echo '<a href="javascript:void(0)" onclick="openCommentDocModal(\'' . $process->{'_id'}->{'$id'} . '\',\'comment_documents\')" class="btn btn-sm btn-outline-info">Attachments</a>';
                        } ?>
                    </td>
                </tr>

            <?php }
            if (!$processCount) {
            ?>
                <tr>
                    <td colspan="7" class="text-center">No Process Available !!!</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="processDocModal" aria-modal="true" style="z-index: 9999">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="processDocModalTBody">
                <?php
                $counter = 0;
                if (count((array)$process->documents) && is_array($process->documents)) {
                    foreach ($process->documents as $doc) {
                        echo '<a href="' . base_url($doc) . '" class="btn btn-sm btn-outline-warning" target="_blank">View Attachment ' . ($counter + 1) . '</a><br/>';
                    }
                }
                ?>
            </div>
<!--            <div class="modal-footer justify-content-between">-->
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        var processDocModalRef = $('#processDocModal');
        var processDocModalTBodyRef = $('#processDocModalTBody');
        $('.attachments').click(function() {
            var links = $(this).attr('data-links');
            var docs = links.split(',');
            console.log(docs);
            processDocModalTBodyRef.empty();
            $(docs).each(function(key, val) {
            processDocModalTBodyRef.append('<a href = "'+val+'" class = "btn btn-sm btn-outline-warning mt-3"    target = "_blank" > View Attachment '+(key+1)+' </a><br/>');
            processDocModalRef.modal('show');
        });
        });

    });
</script>