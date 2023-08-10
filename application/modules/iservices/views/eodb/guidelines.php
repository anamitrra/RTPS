<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>

.loader_2 {
    position: static;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-bottom: 16px solid blue;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script>
    'use strict';
    $(document).ready(function() {
        // Load EODB
        const eodbWindow = window.open('https://eodb.assam.gov.in/', 'EODB', 'width=300,height=300');
        setTimeout(function() {
            eodbWindow.close();
            console.log('Pop up closed', eodbWindow.closed);
            window.location.href = event.target.href;
        }, 1000);
        //console.log(eodbWindow);
    });
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body d-flex flex-column align-items-center" style="gap: 1em;">
                    <!-- <h2 class="transport-appl-header"><?= $service_name ?></h2> -->
                    <!-- <p class="transport-appl-para">Supporting Documents:</p>
                  <?php if ($guidelines) : ?>
                    <ul class="transport-appl-list">
                      <?php foreach ($guidelines as $key => $value) : ?>
                          <li><?= $value; ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?> -->
                    <input type="hidden" name="obj_id" id="obj_id" value="<?= $obj_id ?>">
                    <input type="hidden" name="service_id" id="service_id" value="<?= $service_id ?>">
                    <input type="hidden" name="portal_no" id="portal_no" value="<?= $portal_no ?>">
                    <input type="hidden" name="service_name" id="service_name" value="<?= $service_name ?>">
                    <input type="hidden" name="url" id="url" value="<?= $url ?>">
                    <input type="hidden" name="reference_no" id="reference_no" value="<?= $reference_no ?>">
                    <input type="hidden" name="token" id="token" value="<?= $token ?>">
                    <div class="loader_2"></div>
                    <span id="procced">You are getting redirect to your application form .</span>

                </div>
            </div>
        </div>

        <div>
            <form id="process_form" action="<?= $url ?>" method="post">
                <!--<input type="hidden" name="data" id="data_to_submit" />-->
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
    $(function() {


        var submitProcced = $('#submitProcced');
        var btnProccedTxtRef = $('#btnProccedTxt');
        var procced = $('#procced');
        var service_id = $("#service_id").val();
        var portal_no = $("#portal_no").val();
        var service_name = $("#service_name").val();
        var url = $("#url").val();
        var reference_no = $("#reference_no").val();
        var token = $("#token").val();
        var obj_id = $("#obj_id").val();

        procced.click(function() {
            if (submitProcced.hasClass('d-none')) {
                submitProcced.removeClass('d-none');
                btnProccedTxtRef.addClass('d-none');
            }

            $.ajax({
                url: "<?= base_url('iservices/eodb/procced') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    service_id: service_id,
                    service_name: service_name,
                    portal_no: portal_no,
                    url: url,
                    reference_no: reference_no,
                    token: token,
                    obj_id: obj_id
                },
                success: function(response) {
                    console.log('success');
                    // alert(response.status);
                    if (response.status) {
                        if (!submitProcced.hasClass('d-none')) {
                            submitProcced.addClass('d-none');
                        }
                        if (btnProccedTxtRef.hasClass('d-none')) {
                            btnProccedTxtRef.removeClass('d-none');
                        }

                        $('#process_form').attr('action', response.url + response.encrypted_data).submit();
                        // window.location=response.url;

                        // Swal.fire(
                        //     'Success!',
                        //     response.message !== undefined ? response.message : "Operation success",
                        //     'success'
                        // );

                    } else {
                        Swal.fire(
                            'Failed!',
                            response.error_msg !== undefined ? response.error_msg : response.msg,
                            'error'
                        );
                    }
                },
                error: function(error) {
                    console.log('error');

                    Swal.fire(
                        'Failed!',
                        'Something went wrong!',
                        'error'
                    );
                }
            }).always(function() {
                // console.log('always');
                if (btnProccedTxtRef.hasClass('d-none')) {
                    submitProcced.addClass('d-none');
                    btnProccedTxtRef.removeClass('d-none');
                }
                procced.prop('disabled', false);
            });

        });

        setTimeout(function() {
            $("#procced").trigger('click');
        }, 1000);

    })
</script>