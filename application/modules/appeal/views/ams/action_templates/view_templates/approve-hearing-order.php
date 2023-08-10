
<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">

<!--<link type="text/css" rel="stylesheet" href="--><?//=base_url('assets/plugins/nic-d-sign/css/dsc-signer.css')?><!--">-->
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

<script src="<?=base_url('assets/plugins/nic-d-sign/js/dsc-signer.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/plugins/nic-d-sign/js/dscapi-conf.js')?>" type="text/javascript"></script>

<form id="hearingForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>
    <div class="card card-body mt-2 p-2 border border-warning shadow" style="flex: none" id="templateLinkBox">
        <p>
            Please click the below link to View the order uploaded by Dealing Assistant. 
            <a href="https://sewasetu.assam.gov.in/site/dsc" target="_blank" class="font-weight-bold text-danger">Click here for DSC installation guidelines.</a>
        </p>
        


        <div class="btn-group mb-2">
            <?php
                if($appellantHearingOrder){
                    echo '<a href="'.base_url($appellantHearingOrder).'" class="btn btn-outline-primary"  target="_blank">View Notice for hearing : Appellant</a>';
                    // print_r ($appellantHearingOrder[0]);
                    echo '<br/><a href="'.base_url('appeal/test_app/sign_doc/appellant/'. base64_encode($appellantHearingOrder[0])).'" class="btn btn-outline-primary"  target="">Digitally Sign Notice for hearing : Appellant</a>';
                }
            ?>
        </div>
        <div class="btn-group">
            <?php
                if($dpsHearingOrder){
                    echo '<a href="'.base_url($dpsHearingOrder).'" class="btn btn-outline-primary" target="_blank">View Notice for hearing : DPS</a>';
                    echo '<br/><a href="'.base_url('appeal/test_app/sign_doc/dps/'.base64_encode($dpsHearingOrder[0])).'" class="btn btn-outline-primary" target="">Digitally Sign Notice for hearing : DPS</a>';
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
<input type="hidden" id="signedPdfData">
<input type="hidden" id="lblEncryptedKey">
<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var hearingFormRef = $('#hearingForm');
    var hearingFormProcessUrl = '<?= base_url('appeal/first/process/approve-hearing-order') ?>';
    var appellantHearingOrder = '<?=isset($appellantHearingOrder)? base_url($appellantHearingOrder) : ""?>';
    var dpsHearingOrder = '<?=isset($dpsHearingOrder)? base_url($dpsHearingOrder) : ""?>';
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
                        formData.push({name : 'appellantHearingOrder', value : '<?=urlencode($appellantHearingOrder[0])?>'});
                        formData.push({name : 'dpsHearingOrder', value : '<?=urlencode($dpsHearingOrder[0])?>'});
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
                                    setTimeout(function(){
                                        window.location.reload();
                                    },4000)
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

    function signOrder(base64pdfData){
        return
        if(base64pdfData.length){

            var initConfig = {
                "preSignCallback" : function() {
                    // do something
                    // based on the return sign will be invoked
                    return true;
                },
                "postSignCallback" : function(alias, sign, key) {
                    $('#signedPdfData').val(sign);
                    $('#lblEncryptedKey').val(key);
                    // Implement signed pdf upload and pdf Download here
                    var requestData = {
                        action : "DECRYPT",
                        en_sig : sign,
                        ek : key
                    };
                    $
                        .ajax(
                            {
                                url : dscapibaseurl
                                    + "/pdfsignature",
                                type : "post",
                                dataType : "json",
                                contentType : 'application/json',
                                data : JSON
                                    .stringify(requestData),
                                async : false
                            })
                        .done(
                            function(data) {
                                if (data.status_cd == 1) {
                                    //get data.data -> decode base64 -> get json->check status == SUCCESS
                                    //get data.data.sig -> add pdf header and append to link
                                    var jsonData = JSON
                                        .parse(atob(data.data));
                                    if (jsonData.status === "SUCCESS") {
                                        $(
                                            '#verifyPdfBtn')
                                            .show();
                                        //Set Class to download link
                                        $(
                                            '#downloadDiv')
                                            .addClass(
                                                'btn btn-info');
                                        //get pdf data
                                        var pdfData = jsonData.sig;
                                        var dlnk = document
                                            .getElementById('downloadDiv');
                                        dlnk.href = 'data:application/pdf;base64,'
                                            + pdfData;
                                        $(
                                            "#downloadDiv")
                                            .text(
                                                "Download Signed PDF File");

                                    }

                                } else {
                                    if (data.error.error_cd == 1002) {
                                        alert(data.error.message);
                                        return false;
                                    } else {
                                        alert("Decryption Failed for Signed PDF File");
                                        return false;
                                    }

                                }
                            }).fail(
                        function(jqXHR, textStatus,
                                 errorThrown) {
                            alert(textStatus);
                        });
                },
                signType : 'pdf',
                mode : 'stamping'
                //"certificateSno" : 13705892,
            };
            dscSigner.configure(initConfig);
            dscSigner.sign(base64pdfData);
        }
    }
</script>