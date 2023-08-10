<?php 
    $alreadyGenerated=false;
    $last_penalty_generatedProcess=$this->appeal_process_model->find_latest_where(array('action'=>"generate-penalty-order","appeal_id"=>$appealId));
    if(!empty($last_penalty_generatedProcess)){
        $last_penalty_process=$last_penalty_generatedProcess;
        $alreadyGenerated=true;
    }else{
        $last_penalty_process=$this->appeal_process_model->find_latest_where(array('action'=>"penalize","appeal_id"=>$appealId));
    }
    
    
?>
<form id="penaltyForm">
    <input type="hidden" name="appeal_id" id="appeal_id">
    <div class="row  order-box mt-3">
        <div class="col-12">
            <label for="order_no">Order No. <span class="text-danger">*</span></label>
            <input type="text" id="order_no" name="order_no" class="form-control" placeholder="Enter order number ...">
        </div>
    </div>
    <div class="row  penalty-box mt-3">
        <div class="col-md-4">
            <label for="penaltyAmount">Penalty Amount<span class="text-danger">*</span></label> <small class="text-danger">(applied to DPS - <?= $name ?>)</small>
            <input name="penaltyAmount" value="<?=!empty($last_penalty_process->penalty_amount) ? $last_penalty_process->penalty_amount :'250' ?>" id="penaltyAmount" class="form-control" placeholder="Enter penalty amount  per day... " data-parsley-type="number" required>
        </div>
        <div class="col-md-4">
            <label for="penaltyShouldPayWithinDays">Penalty should be paid within <span class="text-danger">*</span></label>
            <input name="penaltyShouldPayWithinDays" value="<?=!empty($last_penalty_process->penalty_should_by_paid_within_days) ? $last_penalty_process->penalty_should_by_paid_within_days :'' ?>" id="penaltyShouldPayWithinDays" class="form-control" required placeholder="Enter number of days ..." data-parsley-type="number">
        </div>
        <div class="col-md-4">
            <label for="certificateIssuedWithinDays">Certificate should be issued within <span class="text-danger">*</span></label>
            <input name="certificateIssuedWithinDays" value="<?=!empty($last_penalty_process->certificate_to_be_issued_within_days) ? $last_penalty_process->certificate_to_be_issued_within_days :'' ?>" id="certificateIssuedWithinDays" class="form-control" required placeholder="Enter number of days ..." data-parsley-type="number">
        </div>
        <div class="col-md-4">
            <label for="numberOfDaysofDelay">Number of days of delay <span class="text-danger">*</span></label>
            <input name="numberOfDaysofDelay" value="<?=!empty($last_penalty_process->number_of_days_of_delay) ? $last_penalty_process->number_of_days_of_delay :'' ?>" id="numberOfDaysofDelay" class="form-control" required placeholder="Enter number of days of delay  ..." data-parsley-type="number">
        </div>
        <div class="col-md-4">
            <label for="numberOfDaysofDelay">Total Penalty Amount </label>
            <input name="totalPenaltyAmount" value="<?=!empty($last_penalty_process->total_penalty_amount) ? $last_penalty_process->total_penalty_amount :'' ?>" id="totalPenaltyAmount" class="form-control" readonly>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>
    <div class="row mt-3 " id="attachmentBox">
        <div class="col-12">
            <label for="additionalContent">Any information to be added to the order (optional)</label>
            <textarea name="additionalContent" id="additionalContent" class="form-control" placeholder="Write information to be added to the order"></textarea>
        </div>
    </div>
    <div class="card card-body mt-2 p-2 border border-warning shadow " style="flex: none" id="templateLinkBox">
        <p>
            This is a system generated order.
            <br>
            You can sign and upload it for further processing as per you requirement.
            <br>
            Please click the below link to download the order.
        </p>
        
        <div class="btn-group">
            <!-- <a href="#" id="actionTemplateLink" class="btn btn-outline-primary " onclick="openActionTemplateModal()">Generate Penalty Order</a> -->
            <a href="javascript:void(0)" id="actionTemplateLink" class="btn btn-outline-info" onclick="viewActionTemplateModal('dps')">View Penalty Order</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="file_for_action">Penalty Order <span class="text-danger">*</span></label>
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
    var penaltyFormRef = $('#penaltyForm');
    var lastDateOfSubmission = $('#last_date_of_submission');
    var penaltyFormProcessUrl = '<?= base_url('appeal/first/process/generate-penalty-order') ?>';
    var templateLinkBoxRef = $('#templateLinkBox');
    var attachmentBoxRef = $('#attachmentBox');
    $(function() {
        appealIdRef.val(appealId);

        //AppeleteFileUpload File Upload
        var $el2 = $("#file_for_action");
        $el2.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: true,
            minFileCount: 1,
            maxFileCount: 1,
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
            if (penaltyFormRef.parsley().validate()) {
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm!'
            }).then((result) => {
                var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
                $.ajax({
                    url: penaltyFormProcessUrl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: penaltyFormRef.serialize(),
                    beforeSend: function(){
                        penaltyFormRef.find(":input").prop("disabled", true);
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
                            penaltyFormRef.trigger('reset');
                            refreshProcess();
                            $('#action').val('');
                            $('#action').trigger('change');
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
                    penaltyFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            });
               
            }
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
        $("#penaltyAmount").on('change',function(){
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