<?php
$lang=$this->lang;
//pre($about);
?>

<link rel="stylesheet" href="<?= base_url('assets/site/admin/plugins/summernote/summernote-lite.min.css') ?>">

<script src="<?=base_url('assets/site/admin/plugins/summernote/summernote-lite.min.js')?>"></script>
<script src="<?= base_url('assets/site/admin/js/add_service.js') ?>"></script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">About</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">About</li>
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

            <div class="tab-pane" id="add-about" role="tabpanel" aria-labelledby="about-tab">

                <?= form_open('site/admin/about/add_about_action'); ?>
                    
                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> About in English : </label>
                        <textarea class="summernote-about" name="about-en"><?= html_entity_decode(htmlspecialchars_decode($about->en)) ?></textarea>
                    
                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> About in Assamese : </label>        
                        <textarea class="summernote-about" name="about-as"><?= html_entity_decode(htmlspecialchars_decode($about->as)) ?></textarea>
                        
                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> About in Bangla : </label>
                        <textarea class="summernote-about" name="about-bn"><?= html_entity_decode(htmlspecialchars_decode($about->bn)) ?></textarea>
                    
                    </div>
                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/services') ?>" role="button">Cancel</a>
             </form>
            </div>

        </div>
    </div>
</div>