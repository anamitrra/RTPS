<form id="appellantForm" method="POST" action="<?=base_url('appeal/test_app/ds_ho_app_2/appellant/')?>" target="">
    <input type="hidden" name="base_64_pdf" value='<?=$appellantHearingOrder_base_64 ?? ''?>'>
</form>
<form id="dpsForm" method="POST" action="<?=base_url('appeal/test_app/ds_ho_dps_2/dps')?>" target="">
    <input type="hidden" name="base_64_pdf" value='<?=$dpsHearingOrder_base_64 ?? ''?>'>
</form>
<form id="issueOrderForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row mt-3">
        <div class="col-12">
        <div class="row">
    <div class="col">
    <label for="enable_digital_signature">Check to enable Digital Signature<span class="text-danger">*</span></label>
    <input type="checkbox" id="enable_digital_signature" name="enable_digital_signature">
    </div>
    <div class="col">
    <p class="text-right m-0 p-1 "><a href="https://sewasetu.assam.gov.in/site/dsc" target="_blank" class="font-weight-bold text-danger">Click here for DSC installation guidelines.</a>
    </div>
  </div>
            
        </div>

    </div>

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
                <a href="javascript:void(0)" class="btn btn-outline-primary dsc-enabled" onclick="$('#appellantForm').submit()">Digitally Sign Notice for hearing : Appellant</a>
                <?php
            }
            ?>
        </div>
        <div class="btn-group">
            <?php
            if(isset($lastHearingConfirmed) && isset($appealId)){
                ?>
                <a href="<?=base_url('appeal/second/process/view-order/hearing-order/'.$appealId.'/dps')?>" class="btn btn-outline-primary" target="_blank">View Notice for hearing : DPS</a>
                <a href="javascript:void(0)" class="btn btn-outline-primary dsc-enabled" onclick="$('#dpsForm').submit()">Digitally Sign Notice for hearing : DPS</a>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <label for="signed_order_for_appellant">Signed Order for Appellant <span class="text-danger">*</span></label>
            <div class="file-loading">
                <input id="signed_order_for_appellant" name="signed_order_for_appellant[]" type="file" multiple>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="signed_order_for_dps">Signed Order for DPS <span class="text-danger">*</span></label>
            <div class="file-loading">
                <input id="signed_order_for_dps" name="signed_order_for_dps[]" type="file" multiple>
            </div>
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
                <span id="submitBtnTxt">Submit</span>
            </button>
        </div>
    </div>
</form>


<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var issueOrderFormRef = $('#issueOrderForm');
    var issueOrderFormProcessUrl = '<?= base_url('appeal/second/process/issue-hearing-order') ?>';
    var notifiableRef = $('#notifiable');
    var hearingFormProcessUrl = '<?= base_url('appeal/second/process/approve-hearing-order') ?>';
    var dscEnabledRef = $('.dsc-enabled');
    var enableDscCheckboxRef = $('#enable_digital_signature')
    $(function() {
        if(enableDscCheckboxRef.is(':checked')){
            dscEnabledRef.show();
        }else {
            dscEnabledRef.hide();
        }
        enableDscCheckboxRef.change(function(){
            if($(this).is(':checked')){
                dscEnabledRef.show();
            }else {
                dscEnabledRef.hide();
            }
        })

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

        var $el3 = $("#signed_order_for_appellant");
        $el3.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "signed_order_for_appellant"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el3.fileinput("upload");
        });
        var $el4 = $("#signed_order_for_dps");
        $el4.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "signed_order_for_dps"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el4.fileinput("upload");
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
                    issueOrderFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            }
        });

    });
</script>
