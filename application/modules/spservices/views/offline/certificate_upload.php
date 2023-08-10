<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<style>
    .parsley-errors-list {
        color: red;
    }

    .mbtn {
        width: 100% !important;
        margin-bottom: 3px;
    }

    .blk {
        display: block;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {


        $("#certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });


    });
</script>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("spservices/office/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url("spservices/offline/acknowledgement/myapplications"); ?>">My Applications</a></li>
                <li class="breadcrumb-item active">Upload Certificate</li>
            </ol>
        </div><!-- /.container-fluid -->
    </div>

    <div class="container">

        <div class="card my-12">
            <div class="card-header">
                <h3>Upload Certificate</h3>
            </div>
            <div class="card-body">
                <!-- table -->
                <form method="post" action="<?=base_url('spservices/offline/acknowledgement/upload_certificate/'.$objId)?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="">
                                <input id="certificate" name="certificate" type="file" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn  btn-success">Upload</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>