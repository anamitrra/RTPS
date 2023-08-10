<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<form id="hearingForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <div class="row notifiable-box mt-3">
        <div class="col-12">
            <label for="notifiable">Notifiable</label>
            <select name="notifiable" id="notifiable" class="form-control" data-parsley-errors-container="#notifiableErrorContainer" required="">
                <option value="">Please select a notifiable</option>
                <option value="appellant">Appellant/ Applicant </option>
                <option value="dps">DPS</option>
                <option value="both">Both</option>
            </select>
            <div id="notifiableErrorContainer"></div>
        </div>
    </div>

    <div class="row order-box mt-3">
        <div class="col-12">
            <label for="order_no">Order No.</label>
            <input type="text" id="order_no" name="order_no" class="form-control" placeholder="Enter order number ...">
        </div>
    </div>

    <div class="row hearing-box mt-3">
        <div class="col-12">
            <label for="date_of_hearing">Date of hearing</label> <span>( Pick a date after one week as per regulation)</span>
            <input type="text" name="date_of_hearing" id="date_of_hearing" class="form-control datepicker" placeholder="dd-mm-YYYY" required="">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>
    <div class="row mt-3 d-none" id="attachmentBox">
        <div class="col-12">
            <label for="additionalContent">Any information to be added to the order (optional)</label>
            <textarea name="additionalContent" id="additionalContent" class="form-control" placeholder="Write information to be added to the order"></textarea>
        </div>
    </div>
    <div class="card card-body mt-2 p-2 border border-warning shadow d-none" style="flex: none" id="templateLinkBox">
        <p>
            This is a system generated order.
            <br>
            You can sign and upload it for further processing as per you requirement.
            <br>
            Please click the below link to download the order.
        </p>
        <div class="btn-group">
            <a href="javascript:void(0)" id="hearingTemplateAppellant" class="btn btn-outline-primary" onclick="openActionTemplateModal()">View Notice for hearing : Appellant</a>
            <a href="javascript:void(0)" id="hearingTemplateDPS" class="btn btn-outline-primary d-none" onclick="openActionTemplateModal('dps')">View Notice for hearing : DPS</a>
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
    var hearingFormRef = $('#hearingForm');
    var lastDateOfSubmission = $('#last_date_of_submission');
    var datepickerRef = $('#date_of_hearing');
    var hearingFormProcessUrl = '<?= base_url('appeal/first/process/hearing') ?>';
    var notifiableRef = $('#notifiable');
    var templateLinkBoxRef = $('#templateLinkBox');
    var attachmentBoxRef = $('#attachmentBox');
    var hearingTemplateAppellantRef = $('#hearingTemplateAppellant');
    var hearingTemplateDPSRef = $('#hearingTemplateDPS');

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

        datepickerRef.datepicker({
            format: 'dd-mm-yyyy',
            startDate: new Date()
        }).on('changeDate', function(e) {
            $(this).datepicker('hide');
        });

        // select2Ref.select2();
        // processActionFormRef.parsley();
        // appellateFormRef.parsley();
        notifiableRef.change(function() {
            let notifiable = $(this).val();
            let isTemplateLinkVisible = true;
            switch (notifiable) {
                case 'appellant':
                    hearingTemplateAppellantRef.removeClass('d-none');
                    hearingTemplateDPSRef.addClass('d-none');
                    break;
                case 'dps':
                    hearingTemplateDPSRef.removeClass('d-none');
                    hearingTemplateAppellantRef.addClass('d-none');
                    break;
                case 'both':
                    hearingTemplateAppellantRef.removeClass('d-none');
                    hearingTemplateDPSRef.removeClass('d-none');
                    break;
                default:
                    isTemplateLinkVisible = false;
                    hearingTemplateAppellantRef.addClass('d-none');
                    hearingTemplateDPSRef.addClass('d-none');
                    break;
            }
            if (isTemplateLinkVisible) {
                templateLinkBoxRef.removeClass('d-none');
                attachmentBoxRef.removeClass('d-none');
            } else {
                templateLinkBoxRef.addClass('d-none');
                attachmentBoxRef.addClass('d-none');
            }
        });

        processSubmitRef.click(function() {
            if (hearingFormRef.parsley().validate()) {
                $.ajax({
                    url: hearingFormProcessUrl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: hearingFormRef.serialize(),
                    beforeSend: function() {
                        hearingFormRef.find(":input").prop("disabled", true);
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
                            hearingFormRef.trigger('reset');
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
                    hearingFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            }
        });
    });
</script>