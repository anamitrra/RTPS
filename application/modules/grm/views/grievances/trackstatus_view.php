<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<style>
    legend{
        display: inline;
        width: auto;
    }
    body { padding-right: 0 !important }
</style>
<?php $lang = $this->rtps_lang;?>
<div class="container my-2">
    <div class="card shadow-sm">
        <div class="card-header bg-dark">
            <span class="h5 text-white"><?= isset($form_label->track_status->$lang) ? $form_label->track_status->$lang : 'Track Status' ?> </span>
        </div>
        <div class="card-body">
            <div class="row form-group">
                <div class="col-6">
                    <label for="mobile_number"><?= isset($form_label->mn->$lang) ? $form_label->mn->$lang : 'Mobile Number' ?> <span class="text-danger">*</span> </label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="mobile_number" id="mobile_number"  placeholder="Enter your mobile number and verify" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" data-parsley-errors-container="#mobile_number_error_container" value="<?=$old['mobile_number'] ?? ''?>" data-parsley-group="verification" required>
                        <div class="input-group-append">
                            <a href="javascript:void(0)" class="btn btn-outline-danger" id="track">Track</a>
                        </div>
                    </div>
                    <small class="text-info">Provide mobile number to view grievances registered with that number</small>
                    <small class="text-danger" id="mobile_number_error_container"></small>
                </div>
            </div>
            <div class="table-responsive" id="grievance-table-parent"></div>
            <div class="table-responsive" id="grievance-table-parent"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="details_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark" id="modal-title">Tracking details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="details_model_body">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">   
    var getRecords = function(mobile_number){
        $.ajax({
            type: "POST",
            url: "<?= base_url('grm/trackstatus/get_records') ?>",
            data: {"mobileNumber": mobile_number},
            beforeSend: function () {
                $("#grievance-table-parent").html('<h2 class="text-center">Loading...</h2>');
            },
            success: function (res) {
                $("#grievance-table-parent").html(res);
            }
        });
    }//End of getRecords()
    
    var showGrievanceDetails = function(grievanceRef,mobile){
        $.ajax({
            type: "POST",
            url: "<?= base_url('grm/trackstatus/get_details') ?>",
            data: {registration_number:grievanceRef, email_or_mobile:mobile},
            beforeSend: function () {
                //$("#grievance-table-parent").html("Loading...");
            },
            success: function (res) {
                $('#details_model').modal('show');
                $('#details_model_body').html(res);
            }
        });
    }//End of showGrievanceDetails()


    const sendOTP = function(mobile_number){
        if(mobile_number.length){
            $.ajax({
                type: 'POST',
                url: '<?= base_url('grm/public/send-otp')?>',
                dataType: 'json',
                data: {mobile_number: mobile_number},
                beforeSend: function () {
                    swal.fire({
                        html: '<h5>Sending OTP. Please wait...</h5>',
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
                                    $.post('<?=base_url('grm/verify')?>', {mobile_number: mobile_number, otp})
                                        .done(function (response) {
                                            if (response.status) { //alert(response.status);
                                                getRecords(mobile_number);
                                            } else {
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
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: `${result.value.login}'s avatar`,
                                    imageUrl: result.value.avatar_url
                                });
                            }
                        });
                    }else{
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
    };
   
    
    $(function(){
        $(document).on("click", "#track", function(){
            let mobile_number = $('#mobile_number').val();
            sendOTP(mobile_number);
            /*$.ajax({
                type: "POST",
                url: "<?= base_url('grm/trackstatus/get_records') ?>",
                data: {"mobileNumber": mobile_number},
                beforeSend: function () {
                    $("#grievance-table-parent").html("Loading...");
                },
                success: function (res) {
                    $("#grievance-table-parent").html(res);
                }
            });*/     
        });        
    })
</script>