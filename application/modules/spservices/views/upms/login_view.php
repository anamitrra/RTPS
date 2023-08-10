<style type="text/css">
    .captcha-container {
        width: 100%;
        height: auto;
        text-align: justify;
        margin: 5px auto;
        display: block;
    }

    #reloadcaptcha {
        float: right;
        height: 30px;
        line-height: 20px;
    }

    .form-group {
        margin: 0;
    }

    .captcha_block {
        margin-top: 5px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $.post("<?= base_url('spservices/upms/login/createcaptcha') ?>", function(res) {
            $("#captchadiv").html(res);
        });

        $(document).on("click", "#reloadcaptcha", function() {
            $.post("<?= base_url('spservices/upms/login/createcaptcha') ?>", function(res) {
                $("#captchadiv").html(res);
            });
        }); //End of onChange #reloadcaptcha
        // 
        $(document).on("blur", "#signin_id", function() {
            var userName = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/upms/login/checkroles') ?>",
                data: {
                    "username": userName
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(res) {
                    console.log(res)
                    if (res.status) {
                        $('.role_div').removeClass('d-none');
                        $('#role').prop('required', true);
                        $.each(res.roles, function(index, item) {
                            var roleData = {
                                "role_code": item.role_code,
                                "role_name": item.role_name,
                                "level_no": item.level_no
                            }
                            $("#role").append("<option value='" + JSON.stringify(roleData) + "'>" + item.role_name + "</option>");
                        });
                    } else {
                        $('.role_div').addClass('d-none');
                        $('#role').prop('required', false);
                    }
                }
            });
        });
    });
</script>
<div class="container my-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-4"></div><!--End of .col-md-4-->
        <div class="col-md-4 mt-3 mb-3">
            <form method="POST" action="<?= base_url('spservices/upms/login/process') ?>">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark">
                        <span class="text-center text-white"><h5>User Authentication</h5></span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Username<span class="text-danger">*</span></label>
                                <input class="form-control" name="signin_id" id="signin_id" value="<?= set_value('signin_id') ?>" maxlength="100" autocomplete="off" type="text" />
                                <?= form_error("signin_id") ?>
                            </div>
                        </div>
                        <div class="row role_div d-none">
                            <div class="col-md-12 form-group">
                                <label>Select Role<span class="text-danger">*</span></label>
                                <select name="role" id="role"></select>
                                <?= form_error("role") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Password<span class="text-danger">*</span></label>
                                <input class="form-control" name="signin_pass" value="<?= set_value('signin_pass') ?>" maxlength="30" autocomplete="off" type="password" />
                                <?= form_error("signin_pass") ?>
                            </div>
                        </div>

                        <div class="row captcha_block">
                            <div class="col-md-12">
                                <span id="captchadiv" style="float:left;"></span>
                                <button id="reloadcaptcha" class="btn btn-warning" type="button">
                                    <i class="fa fa-refresh"></i> Reload
                                </button>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <input name="inputcaptcha" placeholder="Enter the captcha" maxlength="6" class="form-control" autocomplete="off" type="text" />
                                <?= form_error("inputcaptcha") ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-danger" type="reset">
                            <i class="fa fa-refresh"></i> RESET
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-angle-double-right"></i> LOGIN
                        </button>
                    </div><!--End of .card-footer-->
                </div>
            </form>
        </div><!--End of .col-md-4-->
        <div class="col-md-4"></div><!--End of .col-md-4-->
    </div><!--End of .row-->
</div>