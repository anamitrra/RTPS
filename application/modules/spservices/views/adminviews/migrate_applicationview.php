<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<script src="<?= base_url("assets/"); ?>plugins/select2/js/select2.min.js"></script>

<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <div class="card shadow-sm mt-2">
        <form id="migrationform">
            <div class="card-header bg-secondary">
                <span class="h5">Applications Migration</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 form-group">
                        <label>Organization<span class="text-danger">*</span> </label>
                        <select name="dept_name" id="dept_name" class="form-control" required>
                            <option value=''>Please Select</option>
                            <?php if ($depts) {
                                foreach ($depts as $dept) {
                                    echo '<option value="' . $dept->dept_code . '">' . $dept->dept_name . '</option>';
                                } //End of foreach()
                            } //End of if 
                            ?>
                        </select>
                        <?= form_error("dept_name") ?>
                    </div>
                    <div class="col-6 form-group">
                        <label>Service<span class="text-danger">*</span> </label>
                        <select name="service_id" id="service_id" class="form-control" required>
                            <option value=''>Please Select</option>
                        </select>
                        <?= form_error("service_id") ?>
                    </div>
                    <div class="col-6 form-group">
                        <label>Role<span class="text-danger">*</span> </label>
                        <select name="user_role" id="user_role" class="form-control" required>
                            <option value=''>Please Select</option>
                        </select>
                        <?= form_error("user_role") ?>
                    </div>
                    <!-- Select office user Trasnfer from -->
                    <div class="col-6 form-group">
                        <label>Transfer From<span class="text-danger">*</span><span class="text-danger"></span> </label>
                        <div id="">
                            <select name="transfer_from" id="transfer_from" class="form-control" required>
                                <option value=''>Please Select</option>
                            </select>
                        </div>
                        <?= form_error("transfer_from") ?>
                    </div>
                    <!-- Select office user Transfer to -->
                    <div class="col-6 form-group">
                        <label>Transfer To<span class="text-danger">*</span><span class="text-danger"></span> </label>
                        <div id="">
                            <select name="transfer_to" id="transfer_to" class="form-control" required>
                                <option value=''>Please Select</option>
                            </select>
                        </div>
                        <?= form_error("transfer_to") ?>
                    </div>
                </div> <!-- Row end -->
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button class="btn btn-sm btn-info">Get Applications</button>
                </div>
            </div>
        </form>
    </div><!--End of .card-->

    <div id="response_div" class="d-none">
        <div class="card">
            <div class="card-header bg-secondary">
                <span class="h5">Applications in user account</span>
            </div>
            <div class="appls_div d-none">
                <form action="<?= base_url('spservices/admin/applications/process_migration') ?>" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="frm" class="from_user" required>
                        <input type="hidden" name="to" class="to_user" required>
                        <table class="table table-bordered table-striped response_tbl"></table>
                        <div class="form-group">
                            <label for="">Remarks</label>
                            <textarea name="remarks" id="" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-sm btn-success">Migrate</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body err_msg_div d-none">
                <p>No Applications found.</p>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#service_id').select2();
        $('#user_role').select2();
        $('#transfer_from').select2();
        $('#transfer_to').select2();

    });
    $(document).on("change", "#dept_name", function() {
        var deptCode = $(this).val();
        $("#service_id").empty();
        $("#user_role").empty();
        if (deptCode.length > 0) {
            $("#service_id").append('<option value="">Please Select</option>');
            $("#user_role").append('<option value="">Please Select</option>');
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/admin/services/get_services') ?>",
                data: {
                    "dept_code": deptCode
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(res) {
                    if (res.length) {
                        $("#service_id").empty();
                        $("#service_id").append('<option value="">Please Select</option>');
                        $.each(res, function(index, item) {
                            $("#service_id").append($('<option></option>').val(item.service_obj).html(item.service_name));
                        });
                    } else {
                        alert("No records found");
                    } //End of if else
                }
            });
        } else {
            //alert("Please select a valid department");
        } //End of if else
    }); //End of onChange #dept_info

    $(document).on("change", "#service_id", function() {
        var serviceData = $(this).val();
        $("#user_role").empty();
        $("#user_role").append('<option value="">Please Select</option>');
        if (serviceData.length > 10) {
            var serviceCode = $.parseJSON(serviceData).service_code;
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/admin/levels/get_role_list') ?>",
                data: {
                    "service_code": serviceCode
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(res) {
                    if (res.length) {
                        $("#user_role").empty();
                        $("#user_role").append('<option value="">Please Select</option>');
                        $.each(res, function(index, item) {
                            $("#user_role").append($('<option></option>').val(item.role_code).html(item.role_name));
                        });
                    } else {
                        alert("No records found");
                    } //End of if else
                }
            });
        } else {
            //alert("Please select a valid department");
        } //End of if else
    }); //End of onChange #service_id

    $(document).on("change", "#user_role", function() {
        var role = $(this).val();
        var deptCode = $("#dept_name").val();
        var serviceData = $("#service_id").val();
        var serviceCode = $.parseJSON(serviceData).service_code;
        $("#transfer_from").empty();
        $("#transfer_from").append('<option value="">Please Select</option>');
        $("#transfer_to").empty();
        $("#transfer_to").append('<option value="">Please Select</option>');
        if (role.length > 0) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/admin/users/get_users') ?>",
                data: {
                    "dept_code": deptCode,
                    "service_code": serviceCode,
                    "role_code": role
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(res) {
                    $.each(res.all_users, function(index, item) {
                        $("#transfer_from").append($("<option value='" + item.objId + "'>" + item.fullname + " (" + item.login_username + ")</option>"));
                    });
                    $.each(res.active_users, function(index, item) {
                        $("#transfer_to").append($("<option value='" + item.objId + "'>" + item.fullname + " (" + item.login_username + ")</option>"));
                    });
                }
            });
        } else {
            alert("Please select a valid department");
        } //End of if else
    }); //End of onChange #user_role


    $(document).ready(function() {
        $("form#migrationform").submit(function(event) {
            event.preventDefault();
            var orgId = $('#dept_name').val();
            var serviceId = $('#service_id').val();
            var roleId = $('#user_role').val();
            var tf = $('#transfer_from').val();
            var tt = $('#transfer_to').val();
            if (orgId.length > 0 && serviceId.length > 0 && roleId.length > 0 && tf.length > 0 && tt.length > 0) {
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('spservices/admin/applications/get_applications') ?>",
                    data: formData,

                    beforeSend: function() {
                        $('#response_div').removeClass('d-none');
                        $('.err_msg_div').addClass('d-none');
                    },
                    success: function(res) {
                        if (res.status) {
                            $('.appls_div').removeClass('d-none');
                            $('.response_tbl').empty();
                            $('.from_user').val(res.transfer_frm);
                            $('.to_user').val(res.transfer_to);
                            var tr = '<tr><th>#</th><th>Ref. No.</th><th>Applicant Name</th></tr>';
                            $.each(res.applications, function(index, item) {
                                var sl = index + 1;
                                tr += '<tr><td>' + sl + '</td><td>' + item.service_data.appl_ref_no + '<input type="hidden" value="' + item.service_data.appl_ref_no + '" name="appl_nos[]"></td><td>' + item.form_data.applicant_name + '</td></tr>';
                            })
                            $('.response_tbl').append(tr);
                        } else {
                            $('.appls_div').addClass('d-none');
                            $('.err_msg_div').removeClass('d-none');
                        }
                    }
                });
            } else {
                alert('Please select all fields');
            }
        });
    });
</script>