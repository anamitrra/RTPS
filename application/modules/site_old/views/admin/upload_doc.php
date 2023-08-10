<?php

// pre($action);
//pre($doc_info);
// pre($cat_list);

?>

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
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/documents"); ?>">Documents</a></li>
                        <li class="breadcrumb-item active">Upload Document</li>
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


        
        <?= form_open_multipart( base_url("site/admin/documents/upload_document_action") ); ?>

                <div class="form-group">
                    <label class=""><span class="text-danger font-weight-bold">*</span> 
                    Document Name:
                    </label>
                   
                    <div>
                        <input name="en" type="text" value="<?= $doc_info->name->en ?? '';?>" placeholder="english" required>
                    </div>
                    <div>
                        <input name="as" type="text" value="<?= $doc_info->name->as ?? '';?>" placeholder="assamese" required>
                    </div>
                    <div>
                        <input name="bn" type="text" value="<?= $doc_info->name->bn ?? '';?>" placeholder="bangla" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="cat_name"><span class="text-danger font-weight-bold">*</span> Document Category:</label>
                    </div>

                    <select name="cat_name" required>
                        <?php foreach ($cat_list as $cat) : ?>
                            <option value="<?= $cat->short_name ?>"

                            <?php 
                            
                                if (isset($doc_info->category)) {
                                    
                                    echo ($cat->short_name == $doc_info->category ? 'selected' : '');
                                }
                            
                            ?>
                        
                            ><?= $cat->title->en; ?></option>
                        <?php endforeach; ?>
                    </select>
                   
                </div>

                <?php if (isset($doc_info)): ?>
                    <div class="form-group my-4">
                        <input type="hidden" name="object_id" value="<?=$doc_info->_id->{'$id'}?>">

                        <a class="btn btn-outline-info" role="button" href="<?= base_url($doc_info->path) ?>" target="_blank" rel="noopener noreferrer">
                        
                        <i class="far fa-file-alt fa-lg mr-2"></i>View Original Document

                        </a>
        
                        
                    </div>

                <?php endif; ?>

            

                <div class="form-group">

                    <div>
                        <label for="upload_doc">
                        <?= isset($doc_info) ? '' : '<span class="text-danger font-weight-bold">*</span>' ?>
                        Please Select a Document:
                        <?php if (isset($doc_info)): ?>
                            <span class="mx-2 font-weight-normal text-muted">(If you do, it will replace the old one.)</span>
                        <?php endif; ?>

                        </label>
                    
                    </div>
                    <input type="file" id="upload_doc" name="upload_doc" <?= isset($doc_info) ? '' : 'required' ?>>        
                
                </div>

                <button class="btn btn-secondary" type="submit">
                <i class="fas fa-upload mr-2"></i>
                Upload Document
                </button>

            <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/documents') ?>" role="button">Cancel</a>
        </form>



        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>

