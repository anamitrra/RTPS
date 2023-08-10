
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Grievance Status</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Grievance Status</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">

        <div class="container my-2">
            <div class="card shadow-sm">
                <div class="card-header bg-info">
                    <span class="h5 text-white">Grievance Status</span>
                </div>
                <div class="card-body">
                    <form id="viewStatusForm" method="POST" action="<?=base_url('grm/status/fetch')?>">
                        <div class="row form-group">
                            <div class="col-6">
                                <label for="registration_number">Registration Number</label>
                                <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Enter registration number " required>
                            </div>
                            <div class="col-6">
                                <label for="email_or_mobile">Email/ Mobile</label>
                                <input type="text" name="email_or_mobile" id="email_or_mobile" class="form-control" placeholder="Enter registered email id or mobile number" pattern="/^([\w-\.]+)@((?:[A-Za-z\-]+\.)+)([A-Za-z]{2,15})|\d{10}$/" data-parsley-error-message="Enter a valid email id or mobile number" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <button type="button" id="submit" class="btn btn-outline-success btn-block">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div id="status-box" class="table-responsive d-none">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th width="25%">Registration Number</th>
                                <td width="25%"><span id="resRegistrationNumber"></span></td>
                                <th width="25%">Name</th>
                                <td><span id="resName"></span></td>
                            </tr>
                            <tr>
                                <th>Date Of Receipt</th>
                                <td><span id="resDateOfReceipt"></span></td>
                                <th>Receiving Organization</th>
                                <td><span id="resReceivingOrg"></span></td>
                            </tr>
                            <tr>
                                <th>Grievance Details</th>
                                <td><span id="resGrievanceDetails"></span></td>
                                <th>Grievance Document</th>
                                <td><span id="resGrievanceDocument"></span></td>
                            </tr>
                            <tr>
                                <th>Current Status</th>
                                <td><span id="resCurrentStatus"></span></td>
                                <th>Date Of Action</th>
                                <td><span id="resDateOfAction"></span></td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td><span id="resReason"></span></td>
                                <th>Remark</th>
                                <td><span id="resRemark"></span></td>
                            </tr>
                            <tr>
                                <th>Reply Document</th>
                                <td><span id="resReplyDocument"></span></td>
                                <th>Rating</th>
                                <td><span id="resRating"></span></td>
                            </tr>
                            <tr>
                                <th>Rating Text</th>
                                <td><span id="resRatingText"></span></td>
                                <th>To Org</th>
                                <td><span id="resToOrg"></span></td>
                            </tr>
                            <tr>
                                <th>Officer Name</th>
                                <td><span id="resOfficerName"></span></td>
                                <th>Officer Designation</th>
                                <td><span id="resOfficerDesignation"></span></td>
                            </tr>
                            <tr>
                                <th>Officer Address</th>
                                <td colspan="3"><span id="resOfficerAddress"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var fetchStatusUrl = '<?=base_url('grm/status/fetch')?>';
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
    $(function(){
        viewStatusFormRef.parsley();
        submitRef.click(function(){
            if(viewStatusFormRef.parsley().validate()){
                $.post(fetchStatusUrl,viewStatusFormRef.serialize()).done(function(response){
                    if(response.status){
                        resRegistrationNumberRef.text(response.data.RegistrationNumber || 'NA');
                        resNameRef.text(response.data.Name || 'NA');
                        resDateOfReceiptRef.text(response.data.DateOfReceipt || 'NA');
                        resReceivingOrgRef.text(response.data.ReceivingOrg || 'NA');
                        resGrievanceDetailsRef.text(response.data.GrievanceDetails || 'NA');
                        let grievanceDocLink = '';
                        if(response.data.grievance_doc_name){
                            grievanceDocLink = '<a href="<?=base_url('storage/uploads/grievance/attachments/')?>'+response.data.grievance_doc_name+'" class="btn btn-sm btn-outline-warning font-weight-bold" target="_blank">View</a>';
                        }
                        resGrievanceDocumentRef.html(grievanceDocLink || 'NA');
                        resCurrentStatusRef.text(response.data.CurrentStatus || 'NA');
                        resDateOfActionRef.text(response.data.DateOfAction || 'NA');
                        resReasonRef.text(response.data.Reason || 'NA');
                        resRemarkRef.text(response.data.Remark || 'NA');
                        replyDocLink = '';
                        if(response.data.reply_doc_name){
                            replyDocLink = '<a href="<?=base_url('storage/uploads/grievance/storage/uploads/grievance/reply_document/')?>'+response.data.reply_doc_name+'" class="btn btn-sm btn-outline-warning font-weight-bold" target="_blank">View></a>';
                        }
                        resReplyDocumentRef.html(replyDocLink || 'NA');
                        resRatingRef.text(response.data.RatingRef || 'NA');
                        resRatingTextRef.text(response.data.RatingText || 'NA');
                        resToOrgRef.text(response.data.ToOrg || 'NA');
                        resOfficerNameRef.text(response.data.OfficerName || 'NA');
                        resOfficerDesignationRef.text(response.data.OfficerDesignation || 'NA');
                        resOfficerAddressRef.text(response.data.OfficerAddress || 'NA');

                        statusBoxRef.removeClass('d-none')
                    }else{
                        statusBoxRef.addClass('d-none')
                        Swal.fire('Fail',response.msg,'error');
                    }
                }).fail(function(){
                    statusBoxRef.addClass('d-none')
                    Swal.fire('Fail','Unable to fetch data','error');
                });
            }
        })
    })
</script>