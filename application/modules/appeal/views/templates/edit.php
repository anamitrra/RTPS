

    <link href="<?= base_url('assets/plugins/summernote/summernote-bs4.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>"></script>

               
               
              
          
                <input type="hidden" name="appeal_id" id="template_appeal_id" value="<?=$appeal_id?>">
                <input type="hidden" name="docField" id="template_docField" value="<?=$docField?>">
                <input type="hidden" name="notifiable" id="template_notifiable" value="<?=$notifiable?>">

                <!-- for penalty order -->

                <input type="hidden" name="penalty_amount" id="penalty_amount" value="<?=empty($penalty_amount)? "" : $penalty_amount ?>">
                <input type="hidden" name="number_of_days_of_delay" id="number_of_days_of_delay" value="<?=empty($number_of_days_of_delay)? "" : $number_of_days_of_delay ?>">
                <input type="hidden" name="penalty_should_by_paid_within_days" id="penalty_should_by_paid_within_days" value="<?=empty($penalty_should_by_paid_within_days)? "" : $penalty_should_by_paid_within_days ?>">
                <input type="hidden" name="certificate_to_be_issued_within_days" id="certificate_to_be_issued_within_days" value="<?=empty($certificate_to_be_issued_within_days)? "" : $certificate_to_be_issued_within_days ?>">
                <input type="hidden" name="total_penalty_amount" id="total_penalty_amount" value="<?=empty($total_penalty_amount)? "" : $total_penalty_amount ?>">
                    
                <div class="row">
                    <div class="col-12">
                        <label for="template_summernote_content">Template Content</label>
                        <textarea id="template_summernote_content" name="template_summernote_content" class="form-control" style="max-height: 50vh" required data-parsley-errors-container="#template_content_errors">
                          <?=$content?>
                        </textarea>
                    </div>
                </div>

           









<script>
 //   const templateContentRef = $('#template_summernote_content')
    // const addNewTemplateFormRef = $('#addNewTemplateForm')
   // const addNewTemplateModalRef = $('#addNewTemplateModal')
    // const select2 = $('.select2')
    // const templateDataTableRef = $('#templateDataTable')
    // const templateIdRef = $('#template_id')
    // const templateNameRef = $('#template_name')
    // const actionTypeRef = $('#action_type')
    // const templateStatus1Ref = $('#template_status_1')
    // const templateStatus0Ref = $('#template_status_0')
    // const appealTypeFirstRef = $('#appeal_type_first')
    // const appealTypeSecondRef = $('#appeal_type_second')
    // const templateSummernoteContentRef = $('#template_summernote_content')
    

    $(function() {
        $('#template_summernote_content').summernote({
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
       // select2.select2()
        $('#addNewTemplateForm').parsley()
    });

    // const openEditTemplateModal = function(templateRef = '') {
    //     if(templateRef.length){

    //         $.get(templateUrl+templateRef,function(response){
    //             if(response.success){
    //                 templateIdRef.val(response.template.ref)
    //                 templateNameRef.val(response.template.template_name)
    //                 actionTypeRef.val(response.template.action_type_id.$oid).trigger('change')
    //                 if(response.template.template_status === 'active'){
    //                     templateStatus1Ref.prop('checked',true)
    //                 }else{
    //                     templateStatus0Ref.prop('checked',true)
    //                 }
    //                 if(response.template.appeal_type === 'first'){
    //                     appealTypeFirstRef.prop('checked',true)
    //                 }else if(response.template.appeal_type === 'second'){
    //                     appealTypeSecondRef.prop('checked',true)
    //                 }
    //                 // templateSummernoteContentRef.text(response.template.template_name)
    //                 templateSummernoteContentRef.summernote('reset')
    //                 templateSummernoteContentRef.summernote('code', response.template.template_content)
    //                 addNewTemplateModalRef.find('.modal-title').text('Template')
    //                 addNewTemplateModalRef.modal('show')
    //             }else{
    //                 Swal.fire('Failed','Unable to edit info! Please try again.','error')
    //             }
    //         }).fail(function() {
    //             Swal.fire('Failed','Unable to edit info! Please try again.','error')
    //         })
    //             .always(function() {
    //                 // todo
    //             });
    //     }else{
    //         templateIdRef.val('');
    //         templateNameRef.val('');
    //         actionTypeRef.val('').trigger('change');
    //         templateStatus1Ref.prop('checked',false)
    //         templateStatus0Ref.prop('checked',false)
    //         appealTypeFirstRef.prop('checked',false)
    //         appealTypeSecondRef.prop('checked',false)
    //         templateSummernoteContentRef.summernote('reset')
    //         addNewTemplateModalRef.modal('show')
    //     }
    // }
    $("#sub_template").click(function(e) {
        //var values = templateContentRef.summernote('code');
        //console.log(values);
        //exit;
        //$("#template_content").val(values);
        $('#addNewTemplateForm').submit()
    });
</script>
