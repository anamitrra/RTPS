<?php
$lang = $this->lang;
?>

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet"
      type="text/css"/>

<script src="<?= base_url("assets/site/".$theme."/plugins/jquery/jquery-3.5.1.min.js"); ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>

<main>



    <!-- RTPS Dashboard  -->
    <section class="container">

        <form action="<?= base_url("site/site/upload_document") ?>" method="post">
        
           
            <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">name</label>
                            <div>
                                <input id="en" name="en" type="text" placeholder="english">
                            </div>
                            <div>
                                <input id="as" name="as" type="text" placeholder="assamese">
                            </div>
                            <div>
                                <input id="bn" name="bn" type="text" placeholder="bengali">
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Category</label>
                            <div>
                                <input id="category" name="category" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="doc_attachments">File</label>
                            <div class="file-loading">
                                <input id="doc_attachments" name="doc_attachments[]" type="file">
                            </div>
                        </div>
                    </div>
                </div>

            <button class="btn btn-primary" id="btn" type="submit">Upload Content</button>
        </form>

    </section>

</main>
<?php
$this->session->unset_userdata("doc_attachments");
?>

<script>
    const appealAttachmentsRef = $('#doc_attachments');
    $(document).ready(function($){
      //appeal attachment File Upload
      appealAttachmentsRef.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 10000,
            uploadExtraData: {
                "filename": "doc_attachments"
            },
            allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function (event, files) {
            appealAttachmentsRef.fileinput("upload");
        });

    });
</script>