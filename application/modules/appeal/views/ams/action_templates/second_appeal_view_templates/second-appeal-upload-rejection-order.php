<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<form id="hearingForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
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
            <a href="#" id="actionTemplateLink" class="btn btn-outline-primary" onclick="viewActionTemplateModal()">View Rejection Order</a>

        </div>
<!--        --><?php
//        if(isset($rejectionOrderTemplates)){
//            ?>
<!--            <h6 class="font-weight-bold py-2">OR Download Previously Generated Order</h6>-->
<!---->
<!--            <div class="btn-group">-->
<!--                --><?php
//                if(isset($rejectionOrderTemplates->{'order'}) || isset($rejectionOrderTemplates->{'appellant'})){
//                    ?>
<!--                    <a href="--><?//=base_url('appeal/templates/download-generated-template/'.$appealId.'/order/generate-rejection-order')?><!--" class="btn btn-outline-primary" >Download Rejection Order</a>-->
<!--                    --><?php
//                }
//                ?>
<!--            </div>-->
<!--            --><?php
//        }
//        ?>
    </div>


    <?php
    if($this->session->userdata('role')->slug === 'DA'){
        ?>
    <div class="row mt-3">
        <div class="col-12">
            <label for="rejection_order">Rejection Order <span class="text-danger">*</span></label>
            <div class="file-loading">
                <input id="rejection_order" name="rejection_order[]" type="file" >
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

        <?php
    }
    ?>
</form>
<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var hearingFormRef = $('#hearingForm');
    var hearingFormProcessUrl = '<?= base_url('appeal/second/process/upload-rejection-order') ?>';

    $(function() {
        appealIdRef.val(appealId);
        // FileUpload File Upload
        var $el3 = $("#rejection_order");
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
                "filename": "rejection_order"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el3.fileinput("upload");
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
