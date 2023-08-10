<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<form id="hearingForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row order-box mt-3">
        <div class="col-12">
            <label for="order_no">Order No. <small>(Provide Order No. to Generate Hearing Order)</small></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">AAIDR/</span>
                </div>
                <input type="text" id="order_no" name="order_no" class="form-control" placeholder="Enter order number ..."  aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="row mt-3" id="attachmentBox">
        <div class="col-12">
            <label for="additionalContent">Any information to be added to the order (optional)</label>
            <textarea name="additionalContent" id="additionalContent" class="form-control" placeholder="Write information to be added to the order"></textarea>
        </div>
    </div>
    <div class="card card-body mt-2 p-2 border border-warning shadow" style="flex: none" id="templateLinkBox">
        <p>
            This is a system generated order.
            <br>
            You can sign and upload it for further processing as per you requirement.
            <br>
            Please click the below link to download the order.
        </p>
        <div class="btn-group">
            <!-- <a href="javascript:void(0)" id="hearingTemplateAppellant" class="btn btn-outline-info" onclick="openActionTemplateModal('appellant')">Generate Notice for hearing : Appellant</a>
            <a href="javascript:void(0)" id="hearingTemplateDPS" class="btn btn-outline-info" onclick="openActionTemplateModal('dps')">Generate Notice for hearing : DPS</a> -->
            <a href="javascript:void(0)" id="hearingTemplateAppellant" class="btn btn-outline-info" onclick="viewActionTemplateModal('appellant')">View Notice for hearing : Appellant</a>
            <a href="javascript:void(0)" id="hearingTemplateDPS" class="btn btn-outline-info" onclick="viewActionTemplateModal('dps')">View Notice for hearing : DPS</a>
        </div>
        <?php
            if(isset($hearingOrderTemplates)){
        ?>
        <!-- <div class="btn-group" style="padding-top:10px">
              <?php //if (isset($hearingOrderTemplates->appellant)): ?>
                  <a href="javascript:void(0)" id="hearingEditTemplateAppellant" class="btn btn-outline-info" onclick="openEditActionTemplateModal('appellant')">Edit Notice for hearing : Appellant</a>
              <?php //endif; ?>
              <?php //if (isset($hearingOrderTemplates->dps)): ?>
                    <a href="javascript:void(0)" id="hearingEditTemplateDPS" class="btn btn-outline-info" onclick="openEditActionTemplateModal('dps')">Edit Notice for hearing : DPS</a>
              <?php //endif; ?>


        </div> -->
            <!-- <h6 class="font-weight-bold py-2">OR Download Previously Generated Order</h6>

            <div class="btn-group">
                <?php
                    //foreach ($hearingOrderTemplates as $forUserType => $hTemplate){
                ?>
                        <a href="<?=base_url('appeal/templates/download-generated-template/'.$appealId.'/'.$forUserType.'/'.$action)?>" class="btn btn-outline-primary" >Download Notice for hearing : <?=ucfirst($forUserType)?></a>
                <?php
                    //}
                ?>
            </div> -->
        <?php
            }
        ?>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="appellant_hearing_order">Hearing Order for Appellant <span class="text-danger">*</span></label>
            <div class="file-loading">
                <input id="appellant_hearing_order" name="appellant_hearing_order[]" type="file">
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="dps_hearing_order">Hearing Order for DPS <span class="text-danger">*</span></label>
            <div class="file-loading">
                <input id="dps_hearing_order" name="dps_hearing_order[]" type="file">
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
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
    var hearingFormProcessUrl = '<?= base_url('appeal/first/process/upload-hearing-order') ?>';
    var notifiableRef = $('#notifiable');
    var templateLinkBoxRef = $('#templateLinkBox');
    var attachmentBoxRef = $('#attachmentBox');
    var hearingTemplateAppellantRef = $('#hearingTemplateAppellant');
    var hearingTemplateDPSRef = $('#hearingTemplateDPS');

    $(function() {
        appealIdRef.val(appealId);
        // FileUpload File Upload
        var $el3 = $("#appellant_hearing_order");
        $el3.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: true,
            maxFileSize: 1000,
            minFileCount: 1,
            maxFileCount: 1,
            uploadExtraData: {
                "filename": "appellant_hearing_order"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el3.fileinput("upload");
        });
        var $el4 = $("#dps_hearing_order");
        $el4.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: true,
            maxFileSize: 1000,
            minFileCount: 1,
            maxFileCount: 1,
            uploadExtraData: {
                "filename": "dps_hearing_order"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el4.fileinput("upload");
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
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm!'
            }).then((result) => {
                if (result.value) {
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
                                    $('#action').val('');
                                    $('#action').trigger('change');
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
                }
            });
        });
    });
</script>
