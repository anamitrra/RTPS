<link href="<?= base_url('assets/plugins/summernote/summernote-bs4.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>"></script>

<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>


<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Templates</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Templates</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Templates</h3>
                    <a href="#" onclick="openEditTemplateModal()" class="btn btn-sm btn-outline-primary float-right font-weight-bold">Add New Template</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="templateDataTable">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">#</th>
                                    <th width="20%">Template Name</th>
                                    <th>Appeal Type</th>
                                    <th>Action Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $templateRowCounter = 0;
                                if (!empty((array)$templateList)) {
                                    foreach ($templateList as $template) {
                                ?>
                                        <tr>
                                            <td><?= ++$templateRowCounter ?></td>
                                            <td><?= $template->template_name ?></td>
                                            <td class="text-center"><?= ucfirst($template->appeal_type) ?> Appeal</td>
                                            <td class="text-center">
                                                <?php
                                                if(count((array)$action_types)){
                                                    foreach ($action_types as $action_type){
                                                        if($action_type->{'_id'}->{'$id'} == $template->action_type_id){
                                                ?>
                                                    <?=$action_type->display_name?>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onclick="openEditTemplateModal('<?=$template->{'_id'}->{'$id'}?>')"><i class="fa fa-edit" ></i>Edit</a>
                                            </td>
                                        </tr>
                                    <?php

                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            No data available
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="text-center">
                                    <th width="5%">#</th>
                                    <th>Template Name</th>
                                    <th>Appeal Type</th>
                                    <th>Action Type</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addNewTemplateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="addNewTemplateForm" action="<?= base_url('appeal/templates/store') ?>">
                    <input type="hidden" name="template_id" id="template_id">
                    <div class="row">
                        <div class="col-12">
                            <label for="template_name">Template Name</label>
                            <input type="text" name="template_name" id="template_name" class="form-control" placeholder="Enter template name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex my-2">
                            <label for="action_type">Appeal Type</label>
                            <label for="appeal_type_first" class="px-4"><input type="radio" id="appeal_type_first" name="appeal_type" value="first" required> First Appeal</label>
                            <label for="appeal_type_second" class="px-4"><input type="radio" id="appeal_type_second" name="appeal_type" value="second" required> Second Appeal</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="action_type">Action Type</label>
                            <select name="action_type" id="action_type" class="form-control select2" required data-parsley-errors-container="#action_type_errors" data-placeholder="Select a action type">
                                <?php
                                if (!empty($action_types)) {
                                ?>
                                    <option value="">Please select a template type</option>
                                    <?php
                                    foreach ($action_types as $action_type) {
                                    ?>
                                        <option value="<?= $action_type->{'_id'}->{'$id'} ?>"><?= $action_type->display_name ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                ?>
                                    <option value="">No type available</option>
                                <?php
                                }

                                ?>
                            </select>
                            <div id="action_type_errors">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex my-2">
                            <label for="template_status">Template Status</label>
                            <label for="template_status_1" class="px-4">
                                <input type="radio" name="template_status" id="template_status_1" value="active" checked required>
                                 Active
                            </label>
                            <label for="template_status_0" class="px-4">
                                <input type="radio" name="template_status" id="template_status_0" value="inactive" required>
                                 Inactive
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="template_summernote_content">Template Content</label>
                            <textarea id="template_summernote_content" name="template_summernote_content" class="form-control" style="max-height: 50vh" required data-parsley-errors-container="#template_content_errors"></textarea>
                            <div id="template_content_errors">
                                <input type="hidden" name="template_content" id="template_content">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="sub_template" class="btn btn-outline-success">Save
                    changes
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    const templateContentRef = $('#template_summernote_content')
    const addNewTemplateFormRef = $('#addNewTemplateForm')
    const addNewTemplateModalRef = $('#addNewTemplateModal')
    const select2 = $('.select2')
    const templateDataTableRef = $('#templateDataTable')
    const templateIdRef = $('#template_id')
    const templateNameRef = $('#template_name')
    const actionTypeRef = $('#action_type')
    const templateStatus1Ref = $('#template_status_1')
    const templateStatus0Ref = $('#template_status_0')
    const appealTypeFirstRef = $('#appeal_type_first')
    const appealTypeSecondRef = $('#appeal_type_second')
    const templateSummernoteContentRef = $('#template_summernote_content')
    var templateUrl = '<?=base_url('appeal/templates/show/')?>'

    $(function() {
        templateContentRef.summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen','codeview', 'help']],
            ]
        })
        select2.select2()
        addNewTemplateFormRef.parsley()
    });

    const openEditTemplateModal = function(templateRef = '') {
        if(templateRef.length){

            $.get(templateUrl+templateRef,function(response){
                if(response.success){
                    templateIdRef.val(response.template.ref)
                    templateNameRef.val(response.template.template_name)
                    actionTypeRef.val(response.template.action_type_id.$oid).trigger('change')
                    if(response.template.template_status === 'active'){
                        templateStatus1Ref.prop('checked',true)
                    }else{
                        templateStatus0Ref.prop('checked',true)
                    }
                    if(response.template.appeal_type === 'first'){
                        appealTypeFirstRef.prop('checked',true)
                    }else if(response.template.appeal_type === 'second'){
                        appealTypeSecondRef.prop('checked',true)
                    }
                    // templateSummernoteContentRef.text(response.template.template_name)
                    templateSummernoteContentRef.summernote('reset')
                    templateSummernoteContentRef.summernote('code', response.template.template_content)
                    addNewTemplateModalRef.find('.modal-title').text('Template')
                    addNewTemplateModalRef.modal('show')
                }else{
                    Swal.fire('Failed','Unable to edit info! Please try again.','error')
                }
            }).fail(function() {
                Swal.fire('Failed','Unable to edit info! Please try again.','error')
            })
                .always(function() {
                    // todo
                });
        }else{
            templateIdRef.val('');
            templateNameRef.val('');
            actionTypeRef.val('').trigger('change');
            templateStatus1Ref.prop('checked',false)
            templateStatus0Ref.prop('checked',false)
            appealTypeFirstRef.prop('checked',false)
            appealTypeSecondRef.prop('checked',false)
            templateSummernoteContentRef.summernote('reset')
            addNewTemplateModalRef.modal('show')
        }
    }
    $("#sub_template").click(function(e) {
        //var values = templateContentRef.summernote('code');
        //console.log(values);
        //exit;
        //$("#template_content").val(values);
        $('#addNewTemplateForm').submit()
    });
</script>