<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* .digilocker_fetch_doc {
            border: 1px solid gray;
            padding: 2px;
            background: lightgoldenrodyellow;
        } */
        .digilogin_btn {
            border: 1px solid gray;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                <?php $this->digilocker_api->login_btn();  ?>

                <!-- <button class="btn btn-sm  digilogin_btn pull-right" type="button" onclick="window.open('<?= base_url('digilocker/response') ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                    <img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link
                </button> -->
            </div>
            <div class="card-body">
                <form action="<?= base_url('digilocker/saveDigilockerId') ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>select pan card</label>
                        <input type="file" name="t1" class="form-control-file">
                        <input class="pan_card" type="hidden" name="file_name1">
                        <?= $this->digilocker_api->digilocker_fetch_btn('pan_card'); ?>
                    </div>
                    <div class="form-group">
                        <label>select driving </label>
                        <input type="file" name="t2" class="form-control-file">
                        <input class="dl" type="hidden" name="file_name2">
                        <?= $this->digilocker_api->digilocker_fetch_btn('dl'); ?>
                    </div>
                    <input type="submit" class="btn btn-dark btn-block" value="Submit">
                </form>
            </div>
        </div> 
        <a href="<?= base_url('digilocker/upload_to_locker/o') ?>" class="btn btn-sm btn-success">upload to digilocker</a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
</body>

</html>