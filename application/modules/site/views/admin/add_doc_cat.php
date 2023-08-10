<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumb  -->
            <div class="row mb-4">
            <div class="col-sm-8">
                    <p class="m-0 text-muted"><span class="font-weight-bold mr-2">Note: </span>Fields marked with <span class="text-danger font-weight-bold">*</span> are mandatory.</p>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/document_category"); ?>">Document Categories</a></li>
                        <li class="breadcrumb-item active"><?= $action ?></li>
                    </ol>
                </div>
            </div>

            <!-- Action Success/Fail alert messages  -->            
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <?= form_open( base_url("site/admin/document_category/add_doc_category_action") ); ?>

                <div class="form-group">
                    <label for=""><span class="text-danger font-weight-bold">*</span> Category Name: </label>
                    <div>
                        <input name="en" type="text" value="<?= $cat_info->title->en ?? '';?>" placeholder="english" required>
                    </div>
                    <div>
                        <input name="as" type="text" value="<?= $cat_info->title->as ?? '';?>" placeholder="assamese" required>
                    </div>
                    <div>
                        <input name="bn" type="text" value="<?= $cat_info->title->bn ?? '';?>" placeholder="bangla" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for=""><span class="text-danger font-weight-bold">*</span> Category Short Name: </label><br>

                    <input type="text" name="short_name" value="<?= $cat_info->short_name ?? '';?>" required placeholder="Category short name">
                    
                    <!-- text-muted -->
                    <small id="shortNameHelpBlock" class="form-text text-info"></small>
                </div>

                <!-- Incase of update, send the cat short_name also -->
            <?php if (isset($cat_info->short_name)): ?>

                <input type="hidden" name="action" value="<?=$cat_info->short_name?>">
                
            <?php endif; ?>


            <button class="btn btn-secondary" type="submit">
            <i class="fas fa-save mr-2"></i>
            <?= $action ?>
            </button>

            <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/document_category') ?>" role="button">Cancel</a>
        </form>


        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>


<script>

$(document).ready(function(){

    // Checking if DOC_CAT already exists
    $('[name="short_name"]').on('keyup', function (event) {

        const key = $(event.target).val().trim();

        if (! key.length) {
            return;
        }

        $.ajax({
            type: 'GET',
            url: '<?= base_url("site/admin/document_category/check_doc_category_api/") ?>' + key,
            dataType: 'json',
            error: function (xhr, status, error) {
                
                $('#shortNameHelpBlock').text('Error in checking category short names.');

            },
            success: function (result, status, xhr) {
                if (result.status && result.count > 0) {
                    $('#shortNameHelpBlock').text('This short name alredy exists. Please choose another one.');
                }
                else {
                    $('#shortNameHelpBlock').text('');
                }
            },

        });
    });

});


</script>