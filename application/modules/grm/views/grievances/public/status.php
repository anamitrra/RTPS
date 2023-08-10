
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>

<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<style>
    legend{
        display: inline;
        width: auto;
    }
    body { padding-right: 0 !important }
</style>
<div class="container my-2">
    <div class="card shadow-sm">
        <div class="card-header bg-dark">
            <span class="h5 text-white">Grievance Status</span>
        </div>
        <div class="card-body">
            <div class="row form-group">
                <div class="col-6">
                    <label for="mobile_number">Mobile Number <span class="text-danger">*</span> </label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="mobile_number" id="mobile_number"  placeholder="Enter your mobile number and verify" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" data-parsley-errors-container="#mobile_number_error_container" value="<?=$old['mobile_number'] ?? ''?>" data-parsley-group="verification" required>
                        <div class="input-group-append">
                            <a href="javascript:void(0)" class="btn btn-outline-danger" id="verify">Verify</a>
                            <a href="javascript:void(0)" class="btn btn-outline-success d-none" id="verified"><i class="fa fa-check"></i></a>
                        </div>
                    </div>
                    <small class="text-info">Provide mobile number to view grievances registered with that number</small>
                    <small class="text-danger" id="mobile_number_error_container"></small>
                </div>
            </div>
            <div class="table-responsive d-none" id="grievance-table-parent">
                <table id="grievance-table" class="table table-bordered table-hover table-striped" style="width:100%">
                    <thead>
                    <tr class="table-header">
                        <th>#</th>
                        <th>Registration Number</th>
                        <th>Name</th>
                        <th>Grievance Category</th>
                        <th>Date of Receipt</th>
                        <!-- <th>Current Status</th> -->
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody class="small-text">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-grievance-details">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark" id="modal-title">Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <div id="status-box" class="d-none">
                    <div class="row form-group">
                        <div class="col-3"><label for="resCurrentStatus">Current Status :</label></div>
                        <div class="col-3"><h5><div id="resCurrentStatus"></div></h5></div>
                    </div>
                    <fieldset class="border border-success p-3 mt-2">
                        <legend class="h5">Basic Info</legend>
                        <div class="row form-group">
                            <div class="col-3"><label for="resName">Name</label></div>
                            <div class="col-3"><span id="resName"></span></div>
                            <div class="col-3"><label for="resRegistrationNumber">Registration Number</label></div>
                            <div class="col-3"><span id="resRegistrationNumber"></span></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3"><label for="resGrievanceDetails">Grievance Details</label></div>
                            <div class="col-9"><span id="resGrievanceDetails"></span></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3"><label for="resDateOfReceipt">Date Of Receipt</label></div>
                            <div class="col-3"><span id="resDateOfReceipt"></span></div>
                            <div class="col-3"><label for="resGrievanceDocument">Grievance Document</label></div>
                            <div class="col-3"><span id="resGrievanceDocument"></span></div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success p-3">
                        <legend class="h5">Process Info</legend>
                        <div class="row form-group">
                            <div class="col-3"><label for="resOfficerName">Officer Name</label></div>
                            <div class="col-3"><span id="resOfficerName"></span></div>
                            <div class="col-3"><label for="resOfficerDesignation">Officer Designation</label></div>
                            <div class="col-3"><span id="resOfficerDesignation"></span></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3"><label for="resOfficerName">Officer Address</label></div>
                            <div class="col-3"><span id="resOfficerAddress"></span></div>
                            <div class="col-3"><label for="resDateOfAction">Date of Action</label></div>
                            <div class="col-3"><span id="resDateOfAction"></span></div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>

    const mobileNumberRef         = $('#mobile_number');
    const verifyRef               = $('#verify');
    const verifiedRef             = $('#verified');
    var isMobileVerified          = "<?= $this->session->userdata('isMobileVerified') ?? false ?>";
    var sendOTPurl                = '<?= base_url('grm/public/send-otp') ?>';
    var verifyOTPurl              = '<?= base_url('grm/verify') ?>';
    var checkMobileUrl            = '<?= base_url('grm/check-user-data/mobile_number/') ?>';
    var fetchStatusUrl = '<?=base_url('grm/status/fetch')?>';
    var grievanceGetRecordsByMobileUrl = '<?=base_url('grm/get-records/by-mobile')?>';
    var reg_noRef = $('#registration_number');
    var emailOrMobileRef = $('#email_or_mobile');
    var submitRef = $('#submit');
    var statusBoxRef = $('#status-box');
    var viewStatusFormRef = $('#viewStatusForm');
    var resRegistrationNumberRef = $('#resRegistrationNumber');
    var resNameRef = $('#resName');
    var resDateOfReceiptRef = $('#resDateOfReceipt');
    var resReceivingOrgRef = $('#resReceivingOrg');
    var resGrievanceDetailsRef = $('#resGrievanceDetails');
    var resGrievanceDocumentRef = $('#resGrievanceDocument');
    var resCurrentStatusRef = $('#resCurrentStatus');
    var resDateOfActionRef = $('#resDateOfAction');
    var resReasonRef = $('#resReason');
    var resRemarkRef = $('#resRemark');
    var resReplyDocumentRef = $('#resReplyDocument');
    var resRatingRef = $('#resRating');
    var resRatingTextRef = $('#resRatingText');
    var resToOrgRef = $('#resToOrg');
    var resOfficerNameRef = $('#resOfficerName');
    var resOfficerDesignationRef = $('#resOfficerDesignation');
    var resOfficerAddressRef = $('#resOfficerAddress');

    var table = $('#grievance-table').DataTable({
        "processing": true,
        language: {
            processing: '<div class="lds-ripple"><div></div><div></div></div>',
        },
        "pagingType": "full_numbers",
        "pageLength": 10,
        "serverSide": true,
        "orderMulti": false,
        "columnDefs": [{
            "width": "5%",
            "targets": 0
        },
        {
            "width": "20%",
            "targets": 1
        },
        {
            "width": "15%",
            "targets": 3
        },
        {
            "width": "20%",
            "targets": 4
        },
        // {
        //     "width": "15%",
        //     "targets": 5
        // },
        {
            "width": "20%",
            "targets": 5
        },
            {
                "targets": 5,
                "orderable": false
            }
        ],
        "columns": [
            {
                "data": "#"
            },
            {
                "data": "RegistrationNumber"
            },
            {
                "data": "Name"
            },
            {
                "data": "grievanceCategory"
            },
            {
                "data": "DateOfReceipt"
            },
            // {
            //     "data": "CurrentStatus"
            // },
            {
                "data": "Action"
            }
        ],
        "ajax": {
            url: grievanceGetRecordsByMobileUrl,
            type: 'POST',
            data: function(d) {
                d.mobileNumber = $('#mobile_number').val();
            },
            beforeSend: function() {
                $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
            },
            complete: function() {
                $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
            }
        },
    });

    $(function(){
        verifyRef.click(sendOTP);

        mobileNumberRef.change(function(){
            let mobileNumber = $(this).val();
            if(mobileNumber.length === 10){
                $.get(checkMobileUrl+mobileNumber,function(response){
                    if(response.status){
                        verifyRef.addClass('d-none');
                        verifiedRef.removeClass('d-none');
                        isMobileVerified = true;
                        table.draw();
                        $('#grievance-table-parent').removeClass('d-none')
                    }else{
                        verifyRef.removeClass('d-none');
                        verifiedRef.addClass('d-none');
                        isMobileVerified = false;
                        $('#grievance-table-parent').addClass('d-none')
                    }
                }).fail(function(){
                    otpRef.attr('type','hidden');
                    verifyRef.removeClass('d-none');
                    submitOtpRef.addClass('d-none');
                    resendOtpRef.addClass('d-none');
                    verifiedRef.addClass('d-none');
                    $('#grievance-table-parent').parent().addClass('d-none')
                });
            }
        })

        viewStatusFormRef.parsley();
    })

    const sendOTP = function(){
        if(mobileNumberRef.val().length){
            $.ajax({
                type: 'POST',
                url: sendOTPurl,
                dataType: 'json',
                data: {mobile_number: mobileNumberRef.val()},
                beforeSend: function () {
                    swal.fire({
                        html: '<h5>Processing...</h5>',
                        showConfirmButton: false,
                        allowOutsideClick: () => !Swal.isLoading(),
                        onOpen: function () {
                            Swal.showLoading();
                        }
                    });
                },
                success:function(response){
                    swal.close();
                    if(response.status) {
                        Swal.fire({
                            title: 'Enter your OTP',
                            input: 'text',
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            showLoaderOnConfirm: true,
                            preConfirm: (otp) => {
                                if (otp.length) {
                                    $.post(verifyOTPurl, {mobile_number: mobileNumberRef.val(), otp})
                                        .done(function (response) {
                                            if (response.status) {
                                                verifyRef.addClass('d-none');
                                                verifiedRef.removeClass('d-none');
                                                isMobileVerified = true;
                                                $('#grievance-table-parent').removeClass('d-none')
                                                Swal.fire('Success', response.msg, 'success');
                                            } else {
                                                verifyRef.removeClass('d-none');
                                                verifiedRef.addClass('d-none');
                                                isMobileVerified = false;
                                                $('#grievance-table-parent').addClass('d-none')
                                                Swal.fire('Error', response.msg, 'error');
                                            }
                                        })
                                        .fail(function () {
                                            Swal.fire('Failed', "Mobile number verification failed!!! Please try again.", 'error');
                                        });
                                } else {
                                    Swal.fire('Warning', "Please enter valid OTP", 'warning');
                                }
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result) {
                                table.draw();
                            }
                        })
                    }else{
                        otpRef.attr('type','hidden')
                        verifyRef.removeClass('d-none');
                        submitOtpRef.addClass('d-none');
                        resendOtpRef.addClass('d-none');
                        Swal.fire('Error',response.msg,'error');
                    }
                },
                error:function(){
                    Swal.fire('Failed',"Unable to send OTP!!! Please try again.",'error');
                }
            });
        }else{
            Swal.fire('Warning',"Please enter valid mobile number",'warning');
        }
    }

    function showGrievanceDetails(grievanceRef,mobile){
        $.ajax({
            url: fetchStatusUrl,
            type: 'POST',
            dataType: 'JSON',
            data: {registration_number:grievanceRef, email_or_mobile:mobile},
            beforeSend: function(){
                swal.fire({
                    html: '<h5>Processing...</h5>',
                    showConfirmButton: false,
                    allowOutsideClick: () => !Swal.isLoading(),
                    onOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response){
                if(response.status){
                    resRegistrationNumberRef.text(response.data.RegistrationNumber || 'NA');
                    resNameRef.text(response.data.Name || 'NA');
                    resDateOfReceiptRef.text(response.data.DateOfReceipt || 'NA');
                    resReceivingOrgRef.text(response.data.ReceivingOrg || 'NA');
                    resGrievanceDetailsRef.text(response.data.GrievanceDetails || 'NA');
                    let grievanceDocLink = '';
                    if(response.data.grievance_doc_name){
                        grievanceDocLink = '<a href="<?=base_url('storage/uploads/grm/attachments/')?>'+response.data.grievance_doc_name+'" class="btn btn-sm btn-outline-warning" target="_blank">View</a>';
                    }
                    resGrievanceDocumentRef.html(grievanceDocLink || 'NA');
                    if(response.data.CurrentStatus){
                        if(response.data.CurrentStatus === "Grievance received"){
                             resCurrentStatusRef.html('<span class="badge bg-info">'+response.data.CurrentStatus+'</span>');
                        }else if( response.data.CurrentStatus === "Case closed"){
                            resCurrentStatusRef.html('<span class="badge bg-success">'+response.data.CurrentStatus+'</span>');
                        }else{
                            resCurrentStatusRef.html('<span class="badge bg-warning">'+response.data.CurrentStatus+'</span>');
                        }
                    }else{
                        resCurrentStatusRef.html('<span class="badge bg-warning">NA</span>');
                    }
                    
                  
                    resDateOfActionRef.text(response.data.DateOfAction || 'NA');
                    resReasonRef.text(response.data.Reason || 'NA');
                    resRemarkRef.text(response.data.Remark || 'NA');
                    replyDocLink = '';
                    if(response.data.reply_doc_name){
                        replyDocLink = '<a href="<?=base_url('storage/uploads/grievance/storage/uploads/grievance/reply_document/')?>'+response.data.reply_doc_name+'" class="btn btn-outline-warning" target="_blank">View></a>';
                    }
                    resReplyDocumentRef.html(replyDocLink || 'NA');
                    resRatingRef.text(response.data.RatingRef || 'NA');
                    resRatingTextRef.text(response.data.RatingText || 'NA');
                    resToOrgRef.text(response.data.ToOrg || 'NA');
                    resOfficerNameRef.text(response.data.OfficerName || 'NA');
                    resOfficerDesignationRef.text(response.data.OfficerDesignation || 'NA');
                    resOfficerAddressRef.text(response.data.OfficerAddress || 'NA');

                    statusBoxRef.removeClass('d-none')

                    $('#view-grievance-details').modal("show");
                   // table.draw();
                }else{
                    statusBoxRef.addClass('d-none')
                    Swal.fire('Fail',response.msg,'error');
                }
            },
            error: function(){
                statusBoxRef.addClass('d-none')
                Swal.fire('Fail','Unable to fetch data','error');
            }
        }).always(function(){
            swal.close();
        });
    }
</script>