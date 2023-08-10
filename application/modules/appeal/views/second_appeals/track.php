<style>
    .btn-red{
        background-color: #dc3545;
    }
    [contentEditable=true]:empty:not(:focus):before{
        content:attr(data-text)
    }
    tbody{
        font-size: .9rem;
    }
</style>

<div class="container">
    <div class="card my-2 border-0 shadow">
        <div class="card-header bg-info">
            <h4 class="text-white font-weight-bold text-center">Track Appeal Application</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-outline-primary font-weight-bold mx-1" data-toggle="modal" data-target="#appealDetailsViewModal" > View Appeal Details</button>
                <button class="btn btn-sm btn-outline-info font-weight-bold mx-1" data-toggle="modal" data-target="#rtpsApplicationViewModal" >View RTPS Application Details</button>
                <a href="<?=base_url('appeal/logout')?>" class="btn btn-sm btn-outline-info font-weight-bold mx-1" >close</a>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Appeal ID</label>
                </div>
                <div class="col-3"><?=$appealApplication->appeal_id?></div>
                <div class="col-3">
                    <label for="">Application Reference Number</label>
                </div>
                <div class="col-3"><?=$appealApplication->appl_ref_no?></div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Contact Number</label>
                </div>
                <div class="col-3"><?=$appealApplication->contact_number?></div>
                <div class="col-3">
                    <label for="">Email ID</label>
                </div>
                <div class="col-3"><?=(isset($appealApplication->email_id) || $appealApplication->email_id != '' )?$appealApplication->email_id:'NA'?></div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Appeal Submission Date</label>
                </div>
                <div class="col-3"><?=$appealApplication->date_of_appeal?></div>
                <div class="col-3">
                    <label for="">Appeal Description</label>
                </div>
                <div class="col-3"><?=$appealApplication->appeal_description?></div>
            </div>

            <?php
            if(!in_array($appealApplication->process_status ,array(null,'completed'))){
             ?>
                <div class="d-flex justify-content-end">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm font-weight-bold btn-outline-success" data-toggle="modal" data-target="#responseToReplyModal">
                        Reply to Latest Response
                    </button>
                </div>
            <?php
            }?>
<!--            --><?php
//            if(!empty(validation_errors()))
//                echo validation_errors();
//            ?>
            <div id="trackMsg"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Process Name</th>
                        <th>Processed By</th>
                        <th>Message</th>
                        <th width="20%">Comment By Applicant</th>
<!--                        <th>Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $processCount = 0;
                    foreach ($appealProcessList as $appealProcess) {
                        ?>
                        <tr>
                            <td><?=++$processCount?></td>
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
                                        echo '<span class="badge badge-success">Penalized and Resolved</span>';
                                        break;
                                    case 'remark':
                                        echo '<span class="badge badge-warning">Remark</span>';
                                        break;
                                    case 'in-progress':
                                        echo '<span class="badge badge-light">In Progress</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-secondary">initiated</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                foreach ($userList as $user) {
                                    if($appealProcess->action_taken_by == $user->{'_id'}->{'$id'}){
                                        echo $user->name;
                                    }
                                }?>
                            </td>
                            <td><?=$appealProcess->message?></td>
                            <td colspan="commentByApplicant" onblur="contentUnEditable(this)" onkeyup="alert('changed')"><?php echo isset($appealProcess->comment) ? $appealProcess->comment : 'NA' ?></td>
<!--                            <td data-text="write your comment here...">-->
<!--                                <button class="btn btn-sm btn-info" data-toggle="tooltip" data-original-title="Comment" onclick="enterComment(this)">Edit</button>-->
<!--                                <button class="btn btn-sm btn-success d-none save-comment-btn" data-toggle="tooltip" data-original-title="Save Comment" onclick="submitComment(this,'--><?php //echo $appealProcess->{'_id'}->{'$id'}?><!--//')">Save</button>
//                            </td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php
                if(!$processCount){
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        No process available.
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="appealDetailsViewModal" tabindex="-1" role="dialog" aria-labelledby="appealDetailsViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appealDetailsViewModalLongTitle">Appeal Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplication]);
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rtpsApplicationViewModal" tabindex="-1" role="dialog" aria-labelledby="rtpsApplicationViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rtpsApplicationViewModalLongTitle">RTPS Application Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("applications/view_application", array('data' => $applicationData->initiated_data, 'execution_data' => $applicationData->execution_data));
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="responseToReplyModal" tabindex="-1" role="dialog" aria-labelledby="responseToReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseToReplyModalLabel">Reply to Latest Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="appealCommentForm" method="post" action="<?=base_url('appeal/preview-n-track')?>">
                <input type="hidden" name="appeal_id" value="<?=$appealApplication->appeal_id?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea name="comment" id="comment" class="form-control" placeholder="Write your reply here ..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script>
    var appealCommentFormRef = $('#appealCommentForm');
    var trackMsgRef = $('#trackMsg');
    $(function () {
        appealCommentFormRef.parsley();
    });
    function contentUnEditable(that) {
        let saveCommentBtn = $(that).parents().eq(0).children().eq(5).find('.save-comment-btn');

        if(!$(that).text().length){
            $(that).text('NA');
            saveCommentBtn.addClass('d-none')
        }else{
            saveCommentBtn.removeClass('d-none')
        }
        $(that).prop('contentEditable',false);
    }

    function enterComment(that) {
        let tdToComment = $(that).parents().eq(1).children().eq(4);
        tdToComment.prop('contentEditable',true);
        if(tdToComment.text() == 'NA')
        tdToComment.html('');

        tdToComment.focus();
    }

    function submitComment(that,appeal_ref){
        console.log('test');
        let tdToComment = $(that).parents().eq(1).children().eq(4);
        if(tdToComment.text() != 'NA' || !tdToComment.text().length){

        }else{
            trackMsgRef.html('<div class="alert alert-warning alert-dismissible fade show" role="alert">\n' +
                '  <strong>Failed!</strong> Please enter a comment.' +
                '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '    <span aria-hidden="true">&times;</span>\n' +
                '  </button>\n' +
                '</div>');
        }

    }
    $(document).ready(function() {
        $(document).on('click','#headingOne', function(){
            $(this).addClass('btn-danger');
            $('#headingTwo').removeClass('btn-danger');
            $('#headingTwo').removeClass('btn-red');
            $('#headingThree').removeClass('btn-danger');
        });
        $(document).on('click','#headingTwo', function(){
            $(this).addClass('btn-danger');
            $('#headingThre').removeClass('btn-danger');
            $('#headingOne').removeClass('btn-danger');
        });
        $(document).on('click','#headingThree', function(){
            $(this).addClass('btn-danger');
            $('#headingTwo').removeClass('btn-danger');
            $('#headingTwo').removeClass('btn-red');
            $('#headingOne').removeClass('btn-danger');
        });

    });
</script>