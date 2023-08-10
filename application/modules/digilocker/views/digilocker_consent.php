<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Digilocker Consent</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-white" id="">Digilocker consent for account link.</h5>
                    </div>
                    <div class="modal-body">
                        <p style="text-align:justify">Hi User! <br>
                            RTPS portal now provides the facility to link your Digilocker account with your RTPS account. Once linked, documents issued from the RTPS portal can be made available in your Digilocker. Please click on the "Proceed" button below to link your Digilocker account. You may skip the process by clicking on the "Skip" button and do it at a later time.</p>
                        <p class="text-center" style="font-weight: bold;">Do you wish to proceed ? </p>
                        <p class="text-center">
                            <a href="<?= $url ?>" class="btn btn-sm btn-info">Agree</a>
                            <a href="<?= base_url('digilocker/cancel_consent') ?>" class="btn btn-sm btn-danger">Cancel</a>
                        </p>
                        <p class="text-right">
                            <?php
                            if (!empty($this->session->userdata('redirectTo'))) {
                                $skip = $this->session->userdata('redirectTo');
                                // $this->session->unset_userdata('redirectTo');
                            } else {
                                $skip = (base_url('iservices/transactions'));
                            }
                            ?>
                            <a href="<?= $skip ?>" class="btn btn-sm btn-warning">Skip now</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#staticBackdrop").modal();
    </script>
</body>

</html>