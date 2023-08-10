<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<div class="content-wrapper">
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php echo validation_errors(); ?>
                <div class="card shadow">
                    <div class="card-header text-center text-white" style="background:#1a4066">
                        Change Password
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('spservices/office/save-change-password') ?>" method="post">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="vertical-align: middle;">Old Password</td>
                                    <td><input type="password" class="form-control" name="old_password" required placeholder="Enter old password"></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" width="20%">New Password</td>
                                    <td><input type="password" class="form-control" name="new_password" id="new_password" maxlength="8" required placeholder="Enter password">
                                        <p id="info_msg" class="text-info m-0">(Password must be 8 characters and combination of Capital letters,small letters and numbers)</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Confirm Password</td>
                                    <td><input type="password" class="form-control" name="conf_password" id="conf_password" maxlength="8" required placeholder="Enter confirm password">
                                        <p id="err_msg" class="text-danger m-0"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center"><button class="btn btn-success btn-sm submit_btn">Change Password</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($this->session->flashdata('success')) : ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Success',
            text: '<?php echo $this->session->flashdata('success'); ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('warning')) : ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'Warning',
            text: '<?php echo $this->session->flashdata('warning'); ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')) : ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Error',
            text: '<?php echo $this->session->flashdata('error'); ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#conf_password").on("input", function() {
            let conf_pass = $(this).val();
            let password = $("#new_password").val();
            if (password.length != 0) {
                if (conf_pass != password) {
                    $('#err_msg').show()
                    $('#err_msg').text("Confirm password doesn't match.")
                    $('.submit_btn').prop('disabled', true);
                } else {
                    $('#err_msg').hide()
                    $('.submit_btn').removeAttr('disabled');
                }
            }
        });
    })
</script>