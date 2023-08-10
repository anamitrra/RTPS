<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumb  -->
            <div class="row mb-4">
                <div class="col-sm-8">
                    <p class="m-0 text-muted"><span class="font-weight-bold mr-2">Note: </span>Fields marked with <span
                            class="text-danger font-weight-bold">*</span> are mandatory.</p>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url("site/admin/dashboard");?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url("site/admin/service_category");?>">Service
                                Categories</a></li>
                        <li class="breadcrumb-item active">Add Category</li>
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



            <?=form_open_multipart(base_url("site/admin/service_category/$action_url"));?>

            <div class="form-group">
                <label><span class="text-danger font-weight-bold">*</span> Category Name: </label>
                <div>
                    <input name="cen" class="col-md-4 form-control" type="text"
                        value="<?=$cat_info->cat_name->en ?? '';?>" placeholder="english" required>
                </div>
                <div>
                    <input name="cas" class="col-md-4 form-control" type="text"
                        value="<?=$cat_info->cat_name->as ?? '';?>" placeholder="assamese" >
                </div>
                <div>
                    <input name="cbn" class="col-md-4 form-control" type="text"
                        value="<?=$cat_info->cat_name->bn ?? '';?>" placeholder="bengali" >
                </div>
            </div>

            <?php if (isset($cat_info->_id)): ?>

            <div class="form-group my-4">
                <input type="hidden" name="object_id" value="<?=$cat_info->_id->{'$id'}?>">

                <a class="btn btn-outline-info" role="button" href="<?=base_url($cat_info->img_path)?>"
                    target="_blank" rel="noopener noreferrer">

                    <i class="far fa-image fa-lg mr-2"></i>View Original Image

                </a>


            </div>

            <?php endif;?>


            <div class="form-group">
                <label for="upload_pic">
                    <?php if (empty($cat_info)): ?>
                    <span class="text-danger font-weight-bold">*</span>
                    <?php endif;?>
                    Upload Image
                </label>

                <?php if (!empty($cat_info)): ?>
                <span class="mx-2 font-weight-normal text-muted">(If you do, it will replace the old one.)</span>
                <?php endif;?>

                <br>
                <input type="file" id="upload_pic" name="upload_pic" class="form-control dp"
                    <?=empty($cat_info) ? 'required' : ''?>>
                <small id="picHelp" class="form-text text-muted">Maximum image size is 1MB.</small>
                <small class="form-text text-muted">Maximum image dimension 400x300 px.</small>

            </div>


            <fieldset class="border border-success" style="margin-top:40px">
                <legend class="h5">Subcategories </legend>
                <div class="row form-group">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="subcategory">
                            <thead>
                                <tr>
                                    <th>English Name<span class="text-danger">*</span></th>
                                    <th>Assamese Name <span class="text-danger"></span></th>
                                    <th>Bengali Name <span class="text-danger"></span></th>
                                    <th>Image <span class="text-danger"></span>*</th>
                                    <th style="width:65px;text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $count = (isset($cat_info->sub_categories) && is_array($cat_info->sub_categories)) ? count($cat_info->sub_categories) : 0;
                                    if ($count > 0) {
                                    foreach ($cat_info->sub_categories as $i => $cat) {
                                    if ($i == 0) {
                                        $btn = '<button class="btn btn-info" id="addlatblrow" type="button">Add </button>';
                                    } else {
                                        $btn = '<button class="btn btn-danger deletetblrow" type="button">Remove</button>';
                                    } // End of if else ?>
                                <tr>
                                    <td><input name="en[]" value="<?php echo isset($cat) ? $cat->en : set_value('en'); ?>"
                                            class="form-control" type="text" /></td>
                                    <td><input name="as[]" value="<?php echo isset($cat) ? $cat->as : set_value('as'); ?>"
                                            class="form-control" type="text" /></td>
                                    <td><input name="bn[]" value="<?php echo isset($cat) ? $cat->bn : set_value('bn'); ?>"
                                            class="form-control" type="text" /></td>
                                    <td><input name="img_pathh_<?=$i?>" value="" class="form-control dp" type="file" />
                                        <?php if (isset($cat->img_path)): ?>
                                        <a class="btn btn-outline-info btn-sm" role="button"
                                            href="<?=base_url($cat->img_path)?>" target="_blank"
                                            rel="noopener noreferrer"><i class="far fa-image fa-lg mr-2">View
                                                Image</i></a>
                                        <?php endif;?>
                                    </td>
                                    <td><?=$btn?></td>
                                </tr>
                                <?php }
                                } else {?>
                                <tr>
                                    <td><input name="en[]" class="form-control" type="text" /></td>
                                    <td><input name="as[]" class="form-control" type="text" /></td>
                                    <td><input name="bn[]" class="form-control" type="text" /></td>
                                    <td><input name="img_pathh_0" class="form-control dp" type="file" /></td>
                                    <td style="text-align:center">
                                        <button class="btn btn-info" id="addlatblrow" type="button">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php } //End of if else  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>

            <button class="btn btn-secondary" type="submit">
                <i class="fas fa-save mr-2"></i>
                <?=$action_name?>
            </button>

            <a class="btn btn-outline-secondary" href="<?=base_url('site/admin/service_category')?>"
                role="button">Cancel</a>
            </form>



        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>
<script type="text/javascript">
$(document).on("click", "#addlatblrow", function() {
    let totRows = $('#subcategory tbody tr').length;  
    // alert(totRows); 
    var trow =
        `<tr> <td><input name="en[]" class="form-control" type="text" /></td><td><input name="as[]" class="form-control" type="text"/></td><td><input name="bn[]" class="form-control" type="text"/></td><td><input type="file" name="img_pathh_${totRows}"  class="form-control" /></td><td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button">Remove</button></td></tr>`;
    $('#subcategory tr:last').after(trow);
});

$(document).on("click", ".deletetblrow", function() {
    $(this).closest("tr").remove();
    return false;
});
</script>