<?php
$lang = $this->lang;

// pre($settings);

?>

<link href="<?= base_url('assets/plugins/summernote/summernote-bs4.css') ?>" rel="stylesheet">

<!-- <script src="<?= base_url("assets/"); ?>plugins/jquery/jquery.min.js"></script> -->

<script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>" defer></script>

<main>



    <!-- RTPS Dashboard  -->
    <section class="container mb-5" id="main-contenet">

        <form action="<?= base_url("site/site/add_guidelines_action") ?>" method="post">
            <input class="form-control" type="text" name="service_id" required placeholder="Enter Service Id" />
            <select class="form-control" name="language_type" required>
                <option value="">Select Language</option>
                <option value="as">Assamese</option>
                <option value="en">English</option>
                <option value="bn">Bangla</option>
            </select>
            <select class="form-control" name="type" required>
                <option value="">Select Type</option>
                <option value="guide_lines">Guidlines</option>
                <option value="requirements">Requirements</option>
                
            </select>
            <div class="row">
                <div class="col-12">
                    <label for="template_summernote_content">Content</label>
                    <textarea id="template_summernote_content" name="template_summernote_content" class="form-control" style="max-height: 500vh" required data-parsley-errors-container="#template_content_errors"></textarea>
                </div>
            </div>
            <button class="btn btn-primary" id="btn" type="button">Add Content</button>
        </form>

    </section>

</main>

<script>

    window.addEventListener('load', function (event) {
        
    const templateContentRef = $('#template_summernote_content')
    const templateSummernoteContentRef = $('#template_summernote_content')


    $(function() {
        templateContentRef.summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        })

        $("#btn").on('click', function() {
            if (templateContentRef.summernote('isEmpty')) {
                alert('All fields are required');
            } else {
                $('form').submit();
            }
        })
    });

});
</script>