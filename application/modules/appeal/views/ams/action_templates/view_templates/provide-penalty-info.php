<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<form id="provideHearingDateForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <div class="row  penalty-box mt-3">
        <div class="col-md-6">
            <label for="penaltyAmount">Penalty Amount <small>(in INR)</small><span class="text-danger">*</span></label> <span class="text-danger small">(applied to DPS - <?= $name ?>)</span>
            <input type="number" name="penaltyAmount" value="<?=!empty($last_penalty_process->penalty_amount) ? $last_penalty_process->penalty_amount :'250' ?>" id="penaltyAmount" class="form-control" placeholder="Enter penalty amount  per day... " data-parsley-type="number" required>
        </div>
        <div class="col-md-6">
            <label for="numberOfDaysofDelay">Number of days of delay <span class="text-danger">*</span></label>
            <input type="number" name="numberOfDaysofDelay" value="<?=!empty($last_penalty_process->number_of_days_of_delay) ? $last_penalty_process->number_of_days_of_delay :'' ?>" id="numberOfDaysofDelay" class="form-control" required placeholder="Enter number of days of delay  ..." data-parsley-type="number">
        </div>
        <div class="col-md-6">
            <label for="totalPenaltyAmount">Total Penalty Amount <small>(in INR)</small></label>
            <input id="totalPenaltyAmount"  type="number" name="totalPenaltyAmount" value="<?=!empty($last_penalty_process->total_penalty_amount) ? $last_penalty_process->total_penalty_amount :'' ?>" class="form-control" readonly>
        </div>

        <div class="col-md-6">
            <label for="penaltyShouldPayWithinDays">Penalty should be paid within <span class="text-danger">*</span></label>
            <input type="number" name="penaltyShouldPayWithinDays" value="<?=!empty($last_penalty_process->penalty_should_by_paid_within_days) ? $last_penalty_process->penalty_should_by_paid_within_days :'' ?>" id="penaltyShouldPayWithinDays" class="form-control" required placeholder="Enter number of days ..." data-parsley-type="number">
        </div>
        <div class="col-md-6">
            <label for="certificateIssuedWithinDays">Certificate should be issued within <span class="text-danger">*</span></label>
            <input name="certificateIssuedWithinDays" value="<?=!empty($last_penalty_process->certificate_to_be_issued_within_days) ? $last_penalty_process->certificate_to_be_issued_within_days :'' ?>" id="certificateIssuedWithinDays" class="form-control" required placeholder="Enter number of days ..." data-parsley-type="number">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <small class="text-danger">*</small></label>
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
    var inProgressFormRef = $('#provideHearingDateForm');
    var datepickerRef = $('#date_of_hearing');
    var processSubmitRef = $('#processSubmit');
    // var inProgressFormProcessUrl = '<?=base_url('appeal/first/process/provide-hearing-date')?>';
    var inProgressFormProcessUrl = '<?=base_url('appeal/first/process/provide-hearing-date-revert-to-da')?>';

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
                if(result.value){
                    if (inProgressFormRef.parsley().validate()) {
                        $.ajax({
                            url: inProgressFormProcessUrl,
                            type: 'POST',
                            dataType: 'JSON',
                            data: inProgressFormRef.serialize(),
                            beforeSend: function(){
                                inProgressFormRef.find(":input").prop("disabled", true);
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
                                    inProgressFormRef.trigger('reset');
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
                        }).always(function(){
                            inProgressFormRef.find(":input").prop("disabled", false);
                            swal.close();
                        });
                    }
                }
            });
        });

        $("#numberOfDaysofDelay").on('change',function(){
            // alert("ddh");
            var penalty_amount=$("#penaltyAmount").val();
            var num_of_days=$("#numberOfDaysofDelay").val();
            // var penalty_amount=$("#totalPenaltyAmount").val();
            if(num_of_days.length > 0){
                var total=parseFloat(penalty_amount)*parseFloat(num_of_days);
                if(total > 25000){
                    $("#totalPenaltyAmount").val(25000);
                }else{
                    $("#totalPenaltyAmount").val(total);
                }

            }
        })

    });
</script>
