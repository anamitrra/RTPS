<!DOCTYPE html>
<html lang="en">

<head>
    <title>Digilocker user's document list</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .jumbotron-fluid {
            padding: 0;
            margin: 0
        }

        .tab-pane {
            margin: 0;
            padding: 0
        }

        .tab-content ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .tab-content ul li {
            margin: 0px;
            padding: 0;
            line-height: 20px
        }

        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: #eee url("http://localhost/rtps/assets/iservices/images/loader-1.gif") center no-repeat;
            background-size: 10%;
            opacity: 0.9;
            text-align: center;
        }

        .overlay p {
            position: absolute;
            top: 60%;
            left: 0;
            right: 0;
            font-size: 20px;
        }

        /* Turn off scrollbar when body element has the loading class */
        body.loading {
            overflow: hidden;
        }

        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay {
            display: block;
        }
    </style>
</head>

<body>
    <?php if ($status == false && $token_expire == false) { ?>
            <p class="m-1">Unauthorized Access.</p>
    <?php } elseif ($token_expire == true) { ?>
        <div class="container">
            <div class="text-center">
                <img src="<?= base_url("assets/iservices/images/spinner.gif") ?>" alt="" width="200">
            </div>
            <p style="text-align: justify;font-size:17px;" class="text-primary">Your digilocker session has expired. Please wait while we are redirecting to digilocker login page.</p>
        </div>
        <script>
            window.location.replace("<?= $this->digilocker_enclosure->get_auth_code() ?>");

            // window.opener.enableLogin();
            // Swal.fire({
            //     position: "center",
            //     icon: "error",
            //     title: "Oops..",
            //     text: "Your digilocker session has expired. Please login to continue.",
            //     showConfirmButton: true,
            // }).then(function(result) {
            //     window.opener.enableLogin();
            //     // $(".digilogin_btn").html("<i class='fa fa-lock'></i>  linked.");
            //     // window.close();
            // });
        </script>
    <?php } else { ?>
        <div class="jumbotron jumbotron-fluid">
            <div class="col-sm-4 top">
                <img src="<?= base_url('assets/iservices/images/digiLocker-Large.png') ?>" class="img-fluid" alt="" width="80%">
            </div>
        </div>

        <div class="container-fluid">
            <p class="mt-2 text-right">Name: <span style="font-weight: bold"><?= $this->session->userdata('token_details')['name']; ?></span></p>
            <div class="text-center">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#issued">Issued Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#uploaded">Uploaded Documents</a>
                    </li>
                </ul>
            </div>
            <hr>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="issued" class="container tab-pane active">
                    <ul>
                        <?php foreach ($issued_files->items as $item) { ?>
                            <li>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input " id="selected_file" name="document_list" value="<?= $item->uri ?>" data-name="<?= $item->name ?>"><?= $item->name ?>
                                    </label>
                                </div>
                            </li>
                            <hr>
                        <?php } ?>
                    </ul>
                </div>
                <div id="uploaded" class="container tab-pane fade">
                    <ul>
                        <?php
                        if(count($upload_files)){
                        foreach ($upload_files as $list) { ?>
                            <li>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input " id="selected_file" name="document_list" value="<?= $list->uri ?>" data-name="<?= $list->name ?>"><?= $list->name ?>
                                    </label>
                                </div>
                            </li>
                            <hr>
                        <?php
                        } }?>
                    </ul>
                    <ul>
                        <?php
                        if(count($dir_files)){
                        foreach ($dir_files as $dfile) {
                            echo '<span class="badge badge-info">Home' . $dfile->directory . '</span>';
                            foreach ($dfile->items as $list) { ?>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input " id="selected_file" name="document_list" value="<?= $list->uri ?>" data-name="<?= $list->name ?>"><?= $list->name ?>
                                        </label>
                                    </div>
                                </li>
                                <hr>
                        <?php }
                        } } ?>
                    </ul>
                </div>
            </div>
            <div class="pb-5">
                <button class="btn btn-sm btn-primary fetch_btn float-right"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</button>
            </div>
        </div>
        <div class="overlay">
            <p>Please wait while we are sharing your document.</p>
        </div>
    <?php } ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
    <script>
        var fetchUrl = "<?= base_url('digilocker/get_document') ?>";
        var fileID = "<?= isset($fileId) ? base64_decode($fileId) : '' ?>";
    </script>
</body>

</html>