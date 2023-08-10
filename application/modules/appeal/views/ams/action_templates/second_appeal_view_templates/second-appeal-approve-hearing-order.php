<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<form id="hearingForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <input type="hidden" name="lastHearingConfirmed" id="last_hearing_confirmed" value="<?=$lastHearingConfirmed->_id->{'$id'}??''?>">
    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>
    <div class="card card-body mt-2 p-2 border border-warning shadow" style="flex: none" id="templateLinkBox">
        <p>
            Please click the below link to View the order(s).
        </p>

        <div class="btn-group mb-2">
            <?php
                if(isset($lastHearingConfirmed) && isset($appealId)){
            ?>
                    <a href="<?=base_url('appeal/second/process/view-order/hearing-order/'.$appealId.'/appellant')?>" class="btn btn-outline-primary"  target="_blank">View Notice for hearing : Appellant</a>
            <?php
                }
            ?>
        </div>
        <div class="btn-group">
            <?php
            if(isset($lastHearingConfirmed) && isset($appealId)){
                ?>
                <a href="<?=base_url('appeal/second/process/view-order/hearing-order/'.$appealId.'/dps')?>" class="btn btn-outline-primary" target="_blank">View Notice for hearing : DPS</a>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="file_for_action">Attachments (optional)</label>
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
                <span id="submitBtnTxt"> <span class="fa fa-check"></span> Approve Hearing</span>
            </button>
        </div>
    </div>

</form>
<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var hearingFormRef = $('#hearingForm');
    var hearingFormProcessUrl = '<?= base_url('appeal/second/process/approve-hearing-order') ?>';
    var dscEnabledRef = $('.dsc-enabled');
    var enableDscCheckboxRef = $('#enable_digital_signature')
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
                        let formData = hearingFormRef.serializeArray();
                        //formData.push({name : 'appellantHearingOrder', value : '<?//=urlencode($appellantHearingOrder[0])?>//'});
                        //formData.push({name : 'dpsHearingOrder', value : '<?//=urlencode($dpsHearingOrder[0])?>//'});
                        $.ajax({
                            url: hearingFormProcessUrl,
                            type: 'POST',
                            dataType: 'JSON',
                            data: formData,
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
                                    window.location.reload();

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
