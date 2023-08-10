<form id="issueOrderForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <div class="row notifiable-box mt-3">
        <div class="col-12">
            <label for="notifiable">Notifiable</label>
            <select name="notifiable" id="notifiable" class="form-control" data-parsley-errors-container="#notifiableErrorContainer">
                <option value="">Please select a notifiable</option>
                <option value="appellant">Appellant/ Applicant </option>
                <option value="dps">DPS</option>
                <option value="both">Both</option>
            </select>
            <div id="notifiableErrorContainer"></div>
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
    var issueOrderFormRef = $('#issueOrderForm');
    var issueOrderFormProcessUrl = '<?= base_url('appeal/first/process/issue-order') ?>';
    var notifiableRef = $('#notifiable');
    $(function() {
        appealIdRef.val(appealId);
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
            if (issueOrderFormRef.parsley().validate()) {
                $.ajax({
                    url: issueOrderFormProcessUrl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: issueOrderFormRef.serialize(),
                    beforeSend: function() {
                        issueOrderFormRef.find(":input").prop("disabled", true);
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
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
                            issueOrderFormRef.trigger('reset');
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
                }).always(function() {
                    issueOrderFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            }
        });

    });
</script>