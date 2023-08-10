<?php

// pre($action);
// pre($video_info);
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
                        <li class="breadcrumb-item"><a href="<?=base_url("site/admin/dashboard");?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url("site/admin/videos");?>">Videos</a></li>
                        <li class="breadcrumb-item active">Upload Video</li>
                    </ol>
                </div>
            </div>


            <!-- Action Success/Fail alert messages  -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?=$this->session->flashdata('success');?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?=$this->session->flashdata('error');?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif;?>



        <?=form_open(base_url("site/admin/videos/upload_video_action"));?>

                <div class="form-group">
                    <label class=""><span class="text-danger font-weight-bold">*</span>
                    Video Name:
                    </label>

                    <div>
                        <input name="en" type="text" value="<?=$video_info->name->en ?? '';?>" placeholder="english" required>
                    </div>
                    <div>
                        <input name="as" type="text" value="<?=$video_info->name->as ?? '';?>" placeholder="assamese" required>
                    </div>
                    <div>
                        <input name="bn" type="text" value="<?=$video_info->name->bn ?? '';?>" placeholder="bangla" required>
                    </div>

                </div>

                <div class="form-group">
                    <div>
                        <label for="cat_name"><span class="text-danger font-weight-bold">*</span> Video Category:</label>
                    </div>

                    <select name="cat_name" required>
                        <?php foreach ($cat_list as $cat): ?>
                            <option value="<?=$cat->short_name?>"

                            <?php

                            if (isset($video_info->category)) {

                                echo ($cat->short_name == $video_info->category ? 'selected' : '');
                            }

                            ?>

                            ><?=$cat->title->en;?></option>
                        <?php endforeach;?>
                    </select>

                </div>

                <?php if (isset($video_info)): ?>
                    <div class="form-group my-4">

                        <input type="hidden" name="object_id" value="<?=$video_info->_id->{'$id'}?>">

                        <a class="btn btn-outline-info" role="button" href="<?= $video_info->url ?>" target="_blank" rel="noopener noreferrer">

                        <!-- <i class="far fa-video fa-lg mr-2"></i> -->
                        <i class="fas fa-external-link-alt fa-lg mr-2"></i>
                        View Original Video

                        </a>
                    </div>

                <?php endif;?>

                <div class="form-group">
                    <label class=""><span class="text-danger font-weight-bold">*</span>
                    Video URL:
                    </label>

                    <div>
                        <input name="url" type="url" value="<?=$video_info->url ?? '';?>" placeholder="video URL" required>

                        <small class="form-text text-muted">Only use YouTube embed video URLs.</small>
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                <i class="fas fa-upload mr-2"></i>
                    Upload Video
                </button>

            <a class="btn btn-outline-secondary" href="<?=base_url('site/admin/videos')?>" role="button">Cancel</a>
        </form>


        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>
