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
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/service_category"); ?>">Service Categories</a></li>
                        <li class="breadcrumb-item active">Add Category</li>
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


        
        <?= form_open_multipart( base_url("site/admin/service_category/$action_url") ); ?>

            <div class="form-group">
                <label><span class="text-danger font-weight-bold">*</span> Category Name: </label>
                <div>
                    <input name="cen" type="text" value="<?= $cat_info->cat_name->en ?? '';?>"  placeholder="english" required>
                </div>
                <div>
                    <input name="cas" type="text" value="<?= $cat_info->cat_name->as ?? '';?>"   placeholder="assamese" required>
                </div>
                <div>
                    <input name="cbn" type="text" value="<?= $cat_info->cat_name->bn ?? '';?>"  placeholder="bengali" required>
                </div>
            </div>
            <div class="form-group">
                <label><span class="text-danger font-weight-bold">*</span> Tag: </label>
                <div>
                    <input name="ten" type="text" value="<?= $cat_info->tag->en ?? '';?>"  placeholder="english" required>
                </div>
                <div>
                    <input name="tas" type="text" value="<?= $cat_info->tag->as ?? '';?>"   placeholder="assamese" required>
                </div>
                <div>
                    <input name="tbn" type="text" value="<?= $cat_info->tag->bn ?? '';?>"  placeholder="bengali" required>
                </div>
            </div>

            
            <?php if (isset($cat_info->_id)): ?>

                <div class="form-group my-4">
                    <input type="hidden" name="object_id" value="<?=$cat_info->_id->{'$id'}?>">

                    <a class="btn btn-outline-info" role="button" href="<?= base_url($cat_info->img_path) ?>" target="_blank" rel="noopener noreferrer">
                    
                    <i class="far fa-image fa-lg mr-2"></i>View Original Image

                    </a>
    
                    
                </div>

            <?php endif; ?>


            <div class="form-group">
                <label for="upload_pic"> 
                
                <?php if (empty($cat_info)): ?>
                    <!-- only in case of insert -->
                    <span class="text-danger font-weight-bold">*</span>    
                
                <?php endif; ?>

                    Upload Image
                </label>
                
                <?php if (! empty($cat_info)): ?>
                    <span class="mx-2 font-weight-normal text-muted">(If you do, it will replace the old one.)</span>
                <?php endif; ?>

                <br>
                <input type="file" id="upload_pic" name="upload_pic"
                
                <?= empty($cat_info) ? 'required' : '' ?>  

                >
                <small id="picHelp" class="form-text text-muted">Maximum image size is 1MB.</small>
                <small  class="form-text text-muted">Maximum image dimension 400x300 px.</small>
                  
            </div>
           
            <button class="btn btn-secondary" type="submit">
            <i class="fas fa-save mr-2"></i>
            <?= $action_name ?>
            </button>

            <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/service_category') ?>" role="button">Cancel</a>
        </form>



        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>

