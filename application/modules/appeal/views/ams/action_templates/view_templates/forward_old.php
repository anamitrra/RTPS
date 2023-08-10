<form id="forwardForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <div class="row forward-box mt-3">
        <div class="col-12">
            <label for="forwardTo">Forward To ( Another Appellate Authority)</label>
            <select class="select2" id="forwardTo" name="forwardTo" data-placeholder="Select an user" style="width: 100%;" data-parsley-errors-container="#forwardToErrorContainer">
                <?= $forwardAbleUserOptionList ?? '<option value="">No user available</option>' ?>
            </select>
            <div id="forwardToErrorContainer"></div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="file_for_action">Attachments</label>
            <div class="file-loading">
                <input id="file_for_action" name="file_for_action[]" type="file" multiple>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-success" id="processSubmit" type="button">
                <span id="actionSubmitProgress" class="d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                </span>
                <span id="submitBtnTxt">Submit</span>
            </button>
        </div>
    </div>
</form>

<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var forwardFormRef = $('#forwardForm');
    var forwardFormProcessUrl = '<?= base_url('appeal/first/process/forward') ?>';
    $(function() {
        appealIdRef.val(appealId);
        var select2Ref = $('.select2');
        select2Ref.select2();
        //AppeleteFileUpload File Upload
        var $el2 = $("#file_for_action");
        $el2.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "file_for_action"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
            }).on("filebatchselected", function(event, files) {
            $el2.fileinput("upload");
        });
        
        processSubmitRef.click(function() {
            if (forwardFormRef.parsley().validate()) {
                var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
                $.ajax({
                    url: forwardFormProcessUrl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: forwardFormRef.serialize(),
                    beforeSend: function(){
                        forwardFormRef.find(":input").prop("disabled", true);
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            onRender: function() {
                                // there will only ever be one sweet alert open.
                                $('.swal2-content').prepend(sweet_loader);
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            appellateProcessActionFormMsgContainerRef.html('' +
                                '<div class="alert alert-success">\n' +
                                '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                response.msg +
                                '</div>'
                            );
                            forwardFormRef.trigger('reset');
                            refreshProcess();
                            
                        } else {
                            appellateProcessActionFormMsgContainerRef.html('' +
                                '<div class="alert alert-danger">\n' +
                                '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                response.error_msg +
                                '</div>'
                            );
                            // Swal.fire('Failed', response.error_msg, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Failed', 'Unable to submit hearing!!!', 'error');
                    }
                }).always(function(){
                    forwardFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            }
        });

    });
</script>