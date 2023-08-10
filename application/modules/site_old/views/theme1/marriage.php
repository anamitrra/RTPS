<?php
$lang = $this->lang;
// pre($settings);
?>

<main id="main-contenet">

    <div class="container">

        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-3 mb-md-0">
            <ol class="breadcrumb m-0">

                <?php foreach ($settings->nav as $key => $link) : ?>

                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                        <?php else : ?>
                            <?= $link->$lang ?>


                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>

        <div class="card shadow-sm my-3">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?= $settings->card_title->$lang ?></span>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('download_cert_error')) : ?>
                    <p class="fs-5 text-uppercase text-danger fw-bolder">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $this->session->flashdata('download_cert_error') ?>
                    </p>
                <?php endif; ?>

                <p class="bg-warning fw-bold py-2 text-center text-dark"><?= $settings->messages[5]->$lang ?></p>

                <?= form_open(base_url('site/marriage/verify_applicant'), 'autocomplete="on"'); ?>
                <div class="row form-group mb-3">
                    <div class="col-sm-6">
                        <label for="mobile_number" class="col-form-label">
                            <?= $settings->form_fields[0]->mobile_label->$lang ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="<?= $settings->form_fields[0]->placeholder->$lang ?>" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" value="<?= set_value('mobile_number'); ?>" required>
                        <small class="text-capitalize text-primary d-block">
                            <?= $settings->form_fields[0]->help_text->$lang ?>
                        </small>

                        <?= form_error('mobile_number', '<small class="text-danger text-capitalize d-block">', '</small>'); ?>

                    </div>
                </div>
                <div class="row form-group mb-3">
                    <div class="col-sm-6">
                        <label for="app_ref_no" class="col-form-label">

                            <?= $settings->form_fields[1]->ref_no_label->$lang ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="app_ref_no" id="app_ref_no" placeholder="<?= $settings->form_fields[1]->placeholder->$lang ?>" minlength="3" value="<?= set_value('app_ref_no') ?>" required>

                        <?= form_error('app_ref_no', '<small class="text-danger text-capitalize d-block">', '</small>'); ?>
                    </div>
                </div>
                <!-- 
                CAPTCH Code

                <div class="row form-group mb-5">
                    <div class="col-6 col-sm-2">
                        <span id="captchaImage"><img id="captchaImg" src="<?= base_url('storage/PORTAL/captcha/' . $captcha_img) ?>" width="170" style="border:0;" alt="Catpch Image"></span>
                    </div>
                    <div class="col-2 col-sm-1">
                        <button type="button" class="btn btn-outline-info" id="refreshCaptcha">
                            <i class="fa fa-sync"></i>
                        </button>
                    </div>
                    <div class="col-12 col-sm-3">
                        <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Enter Security Code" maxlength="6" required>
                    </div>
                </div> -->

                <div class="row form-group">
                    <div class="col-sm-4">
                        <button type="submit" class="btn rtps-btn">
                            <?= $settings->form_fields[2]->download_btn->$lang ?>
                        </button>
                    </div>
                </div>

                </form>

            </div>
        </div>


    </div>

</main>

<div class="modal fade" id="marriageModel" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="0" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($this->session->flashdata('success_otp')) : ?>

                    <!-- Display OTP Submit Form -->
                    <?= form_open(base_url('site/marriage/download_cert'), 'autocomplete="off" id="otpForm"'); ?>
                    <p class="text-success small">
                        <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success_otp') ?>
                    </p>
                    <div class="row form-group">
                        <div class="col-12 mb-3">

                            <input tabindex="0" type="text" class="form-control form-control-lg" name="otp_number" id="otp_number" placeholder="<?= $settings->form_fields[3]->otp_placeholder->$lang ?>" minlength="6" maxlength="6" pattern="^\d{6}$" required>
                            <p class="my-2 text-center fs-5"></p>

                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn rtps-btn"><?= $settings->form_fields[4]->submit_btn->$lang ?></button>
                        </div>
                    </div>
                    </form>

                <?php elseif ($this->session->flashdata('errorMsg')) : ?>

                    <h3 class="text-danger text-center"><?= $this->session->flashdata('errorMsg'); ?></h3>

                <?php endif; ?>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn rtps-btn-alt btn-sm" data-bs-dismiss="modal">
                    <?= $settings->form_fields[5]->close_btn->$lang ?>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('load', function(event) { // Execute script when DOM is ready

        const error_no_record = Boolean("<?= $this->session->flashdata('error_no_record'); ?>");
        const otp_sent = Boolean("<?= $this->session->flashdata('otp_sent'); ?>");
        const marriageModel = new bootstrap.Modal(document.getElementById('marriageModel'), {});
        const baseURL = "<?= base_url() ?>";

        // Refresh captcha
        // $('#refreshCaptcha').on('click', function(event) {
        //     $.getJSON("<?= base_url('site/marriage/create_captcha') ?>", function(data, status) {

        //         if (status == 'success') {
        //             $('#captchaImg').attr("src", data.capth_img_file);

        //         } else {
        //             alert(`Could not reload CAPTCH: ${status}`);
        //         }

        //     });
        // });

        // Display Model
        if (error_no_record || otp_sent) {
            marriageModel.show();
        }

        if (otp_sent) {
            $('#marriageModel').on('shown.bs.modal', function() { // Focus OTP form
                $('#otp_number').focus();
            });

        } else {
            $('#mobile_number').focus(); // Focus Main form
        }

        // Verify OTP
        $('#otpForm').on('submit', function(event) {
            event.preventDefault(); // block form submit event

            // Disable submit btn
            $('#otpForm button[type="submit"]').addClass('rtps-btn-disabled');

            $.post("<?= base_url('site/marriage/verify_otp') ?>", $("#otpForm").serialize(), function(data, status) {
                    if (status == 'success') {
                        $('#otp_number').next('p').html(data.msg);

                        // console.log(data);

                        if (data.status) {
                            // Download the cerfiticate
                            $('#otpForm button[type="submit"]').remove();

                            window.setTimeout(function(params) {
                                event.currentTarget.submit(); // Continue the form submit

                            }, 2000);

                        }

                    } else {
                        marriageModel.hide();
                        alert('OTP could not be verified');
                    }
                })
                .fail(function() {
                    alert("Network Error!");
                })
                .always(function() {
                    // hide loader
                    $('#otpForm button[type="submit"]').removeClass('rtps-btn-disabled');
                });
        });

        // Redload page when cert is downloaded
        $('#marriageModel').on('hidden.bs.modal', function() { // Focus OTP form
            if ($('small.text-success').length) {
                window.location.replace(baseURL + '/site/marriage/index');
            }
        });

    });
</script>