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

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">DASHBOARD<?php //$this->session->userdata("department_name")
                        ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("appeal/userarea"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
<div class="container">
    <div class="card my-2 border-0 shadow">
        <div class="card-header bg-info">
            <h4 class="text-white font-weight-bold text-center">Track Appeal Application</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-outline-primary font-weight-bold mx-1" data-toggle="modal" data-target="#appealDetailsViewModal" > View Appeal Details</button>
                <?php if(isset($appealApplication[0]->ref_appeal_id)) { ?>
                    <button class="btn btn-sm btn-outline-warning font-weight-bold mx-1" data-toggle="modal" data-target="#appealDetailsPreviousViewModal" > View Previous Appeal Details</button>
                <?php } ?>
                <?php
                    if(isset($appealApplication[0]->application_data)){
                ?>
                        <button class="btn btn-sm btn-outline-info font-weight-bold mx-1" data-toggle="modal" data-target="#rtpsApplicationViewModal" >View RTPS Application Details</button>
                <?php
                    }
                ?>

                <!-- <a href="<?=base_url('appeal/userarea')?>" class="btn btn-sm btn-outline-danger font-weight-bold mx-1" >close</a> -->
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Appeal ID</label>
                </div>
                <div class="col-3"><?=$appealApplication[0]->appeal_id?></div>
                <div class="col-3">
                    <label for="">Application Reference Number</label>
                </div>
                <div class="col-3"><?=$appealApplication[0]->appl_ref_no?></div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Contact Number</label>
                </div>
                <div class="col-3"><?=$appealApplication[0]->contact_number?></div>
                <div class="col-3">
                    <label for="">Email ID</label>
                </div>
                <div class="col-3"><?=(isset($appealApplication[0]->email_id) || $appealApplication[0]->email_id != '' )?$appealApplication[0]->email_id:'NA'?></div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="">Appeal Submission Date</label>
                </div>
                <div class="col-3"><?=format_mongo_date($appealApplication[0]->created_at)?></div>
                <div class="col-3">
                    <label for="">Appeal Description</label>
                </div>
                <div class="col-3"><?=$appealApplication[0]->appeal_description?></div>
            </div>

            <?php
            if(!in_array($appealApplication[0]->process_status ,array(null,'completed'))){
             ?>
                <div class="d-flex justify-content-end mb-2">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm font-weight-bold btn-outline-success" data-toggle="modal" data-target="#responseToReplyModal">
                        Reply to Query/ Latest Response
                    </button>
                </div>
            <?php
            }?>
            <?php
            if($this->session->userdata('success') != null){
                ?>
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <strong>Success!</strong> <?=$this->session->userdata('success')?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            ?>
            <?php
            if($this->session->userdata('fail') != null){
                ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <strong>Fail!</strong> <?=$this->session->userdata('fail')?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            ?>
            <?php
            if($this->session->userdata('error') != null){
                ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <strong>Error!</strong> <?=$this->session->userdata('error')?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            ?>
            <div id="trackMsg"></div>
            <span id="processTBody"></span>

        </div>
    </div>
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="appealDetailsViewModal" tabindex="-1" role="dialog" aria-labelledby="appealDetailsViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
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
<?php if(isset($appealApplication[0]->ref_appeal_id)){?>

    <!-- Modal -->
    <div class="modal fade" id="appealDetailsPreviousViewModal" tabindex="-1" role="dialog" aria-labelledby="appealDetailsPreviousViewModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appealDetailsPreviousViewModalLongTitle">Previous Appeal Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplication]);
                    ?>
                    <span id="processPrevTBody"></span>
                </div>
            </div>
        </div>
    </div>

    <?php }?>

<?php if(isset($appealApplication[0]->application_data)){
?>
    <!-- Modal -->
    <div class="modal fade" id="rtpsApplicationViewModal" tabindex="-1" role="dialog" aria-labelledby="rtpsApplicationViewModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rtpsApplicationViewModalLongTitle">RTPS Application Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $this->load->view("applications/view_application", array('data' => $appealApplication[0]->application_data->initiated_data, 'execution_data' => $appealApplication[0]->application_data->execution_data));
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

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
            <form id="appealCommentForm" method="post" action="<?=base_url('appeal/comment')?>">
                <input type="hidden" name="appeal_id" value="<?=$appealApplication[0]->appeal_id?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea name="comment" id="comment" class="form-control" placeholder="Write your reply here ..." required></textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="file-loading">
                                <input id="file_for_comment" name="file_for_comment[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
<!--                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>-->
                    <button type="submit" class="btn btn-outline-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="processDocModal" aria-modal="true" style="z-index: 9999!important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <div id="processDocModalTBody"></div>
<!--                <table class="table table-bordered table-hover">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th class="text-center">Sl No.</th>-->
<!--                        <th class="text-center">Action</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody id="processDocModalTBody">-->
<!--                    <tr>-->
<!--                        <td colspan="2" class="text-center">No Data Available</td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<?php
$this->session->unset_userdata("file_for_comment");
?>
<script>
    var appealCommentFormRef = $('#appealCommentForm');
    var trackMsgRef = $('#trackMsg');
    var processTBodyRef = $('#processTBody');
    var processPrevTBodyRef = $('#processPrevTBody');
    var processDocModalRef = $('#processDocModal');
    var processDocModalTBodyRef = $('#processDocModalTBody');
    var refreshProcessUrl = '<?= base_url('appeal/process/refresh/' . $appealApplication[0]->appeal_id) ?>';
    var refreshPreviousProcessUrl = '<?= isset($appealApplication[0]->ref_appeal_id) ? base_url('appeal/process/refresh/' . $appealApplication[0]->ref_appeal_id) : base_url('appeal/process/refresh/' . $appealApplication[0]->appeal_id) ?>'
    $(function () {
        appealCommentFormRef.parsley();
        refreshProcess();
        loadPreviousProcess();

        //DPS File Upload
        var $el1 = $("#file_for_comment");
        $el1.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "file_for_comment"
            },
            allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el1.fileinput("upload");
        });
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

    function refreshProcess() {
        processTBodyRef.html('<tr><td colspan="7">\
                                    <div class="d-flex justify-content-center">\
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">\
                                            <span class="sr-only">Loading...</span>\
                                        </div>\
                                    </div>\
                                    </td>\
                                    </tr>');
        $.get(refreshProcessUrl, function(response) {
            processTBodyRef.html(response);
        });
    }
    function loadPreviousProcess() {
        processPrevTBodyRef.html('<tr><td colspan="7">\
                                    <div class="d-flex justify-content-center">\
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">\
                                            <span class="sr-only">Loading...</span>\
                                        </div>\
                                    </div>\
                                    </td>\
                                    </tr>');
        $.get(refreshPreviousProcessUrl, function(response) {
            processPrevTBodyRef.html(response);
        });
    }

    function openProcessDocModal(processRef){
        $.get("<?=base_url('appeal/process/show-attachments/')?>"+processRef,function(response){
            processDocModalTBodyRef.html(response);
        });
        processDocModalRef.modal('show');
    }
    function openCommentDocModal(processRef){
        $.get("<?=base_url('appeal/process/show-attachments/')?>"+processRef,{docField:"comment_documents"})
        .done(function(response){
            processDocModalTBodyRef.html(response);
        });
        processDocModalRef.modal('show');
    }
</script>
