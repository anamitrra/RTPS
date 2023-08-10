
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Process Name</th>
        <th>Processed By</th>
        <th>Processed on</th>
        <th>Message</th>
        <th>Comment By Applicant</th>
    </tr>
    </thead>
    <tbody <?=isset($tbodyIdRef)?$tbodyIdRef:''?>>
    <?php
    $processCount = 0;
    foreach ($appealProcessPreviousList as $appealProcess) {var_dump($appealProcess->action);die;
        ?>
        <tr>
            <td><?= ++$processCount ?></td>
            <td class="text-capitalize text-center">
                <?php
                switch ($appealProcess->action){
                    case 'reply':
                        echo '<span class="badge badge-info">Reply</span>';
                        break;
                    case 'resolved':
                        echo '<span class="badge badge-success">Resolved</span>';
                        break;
                    case 'rejected':
                        echo '<span class="badge badge-danger">Rejected</span>';
                        break;
                    case 'penalize':
                        echo '<span class="badge badge-success">Penalized</span>';
                        break;
                    case 'issue-penalty-order':
                            echo '<span class="badge badge-success">Penalty Order issued</span>';
                        break;
                    case 'remark':
                        echo '<span class="badge badge-warning">Remark</span>';
                        break;
                    case 'in-progress':
                        echo '<span class="badge badge-dark">In Progress</span>';
                        break;
                    default:
                        echo '<span class="badge badge-secondary">initiated</span>';
                        break;
                }
                ?>
            </td>
            <td>
                <?php
                // foreach ($appealProcess->user as $user) {
                //     if ($appealProcess->action_taken_by == $user->{'_id'}->{'$id'}) {
                //         echo $user->name;
                //     }
                // }
                if( strval($appealProcess->user->{"_id"}) === strval($appealProcess->action_taken_by)){
                  echo $appealProcess->user->name;
                }

                ?>
            </td>
            <td><?=$this->mongo_db->getDateTime($appealProcess->created_at)?></td>
            <td><?= $appealProcess->message ?></td>
            <td><?php echo isset($appealProcess->comment) ? $appealProcess->comment : 'NA' ?></td>
        </tr>
    <?php }

    if (!$processCount) {
        ?>
        <tr>
            <td colspan="6" class="text-center">No Process Available !!!</td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
