<?php
    $holidaysCSV = implode(',',getHolidaysArray());
?>

<!--<link rel="stylesheet" href="--><?//= base_url("assets/plugins/datepicker/datepicker3.css") ?><!--">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.standalone.min.css">
<style>
    .datepicker-days table .disabled-date.day {
        background-color: #EBEBE4 ;
        color: #000;
    }

    .datepicker table tr td.disabled,
    .datepicker table tr td.disabled:hover {
        background: #EBEBE4 ;
        color: #000;
    }
</style>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<!--<script src="--><?//= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?><!--"></script>-->

<form id="provideHearingDateForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row order-box mt-3">
        <div class="col-12">
            <label for="order_no">Proposed Date of hearing</label>
            <input type="text" class="form-control" id="order_no" value="<?=$hearingDate?>" disabled>
        </div>
    </div>
    <div class="row hearing-box mt-3">
        <div class="col-12">
            <label for="date_of_hearing">New Date of hearing <span class="text-danger">*</span></label>
            <input type="text" name="date_of_hearing" id="date_of_hearing" class="form-control datepicker" placeholder="dd-mm-YYYY" required="">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
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
    var inProgressFormRef = $('#provideHearingDateForm');
    var datepickerRef = $('#date_of_hearing');
    var processSubmitRef = $('#processSubmit');
    // var inProgressFormProcessUrl = '<?=base_url('appeal/first/process/provide-hearing-date')?>';
    var inProgressFormProcessUrl = '<?=base_url('appeal/second/process/change-hearing-date')?>';

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


        // var appealDate = '';
        // var startDate = new Date().getTime() + 86400000;
        //
        // startDate=new Date(startDate);
        var tentativeHearingDate = '<?=date('c',strtotime($hearingDate))?>';
        var startDate=new Date(tentativeHearingDate);
        var disabledDate = '<?=$holidaysCSV?>'.split(',');
        setTimeout(function(){
            datepickerRef.datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                startDate: startDate,
                datesDisabled: disabledDate
            }).on('changeDate', function(e) {
                $(this).datepicker('hide');
            });
        },1000)
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
                        }).always(function(){
                            inProgressFormRef.find(":input").prop("disabled", false);
                            swal.close();
                        });
                    }
                }
            });
        });

    });
</script>
