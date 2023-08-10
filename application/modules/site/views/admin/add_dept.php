<?php
// pre($dept_info);
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
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/departments"); ?>">Departments</a></li>
                        <li class="breadcrumb-item active"><?= $action_name ?></li>
                    </ol>
                </div>
            </div>


            <!-- Action Success/Fail alert messages  -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>



            <?= form_open(base_url("site/admin/departments/$action_url")); ?>

            <div class="form-group">
                <label for="department_id"><span class="text-danger font-weight-bold">*</span> Department ID: </label><br>
                <input type="text" id="department_id" name="department_id" required placeholder="Enter Department ID" value="<?= $dept_info->department_id ?? ''; ?>" />
                <input type="hidden" name="old_department_id" value="<?= $dept_info->department_id ?? ''; ?>"/>
            </div>
            <div class="form-group">
                <label><span class="text-danger font-weight-bold">*</span> Department Name: </label>
                <div>
                    <input name="en" type="text" value="<?= $dept_info->department_name->en ?? ''; ?>" placeholder="english" required>
                </div>
                <div>
                    <input name="as" type="text" value="<?= $dept_info->department_name->as ?? ''; ?>" placeholder="assamese" required>
                </div>
                <div>
                    <input name="bn" type="text" value="<?= $dept_info->department_name->bn ?? ''; ?>" placeholder="bengali" required>
                </div>
            </div>

            <div class="form-group">
                <label for="short_name"><span class="text-danger font-weight-bold">*</span> Department Short Name: </label><br>
                <input type="text" id="short_name" value="<?= $dept_info->department_short_name ?? ''; ?>" name="department_short_name" required placeholder="Enter department short name" />
                <input type="hidden" name="old_department_short" value="<?= $dept_info->department_short_name ?? ''; ?>"/>

            </div>

            <div class="form-group">
                <p class="font-weight-bold"><span class="text-danger font-weight-bold">*</span> Is this an Autonomus Council ? </p>

                <?php if (isset($dept_info->ac)) : ?>

                    <input type="radio" id="true" name="ac" value="1" <?= ($dept_info->ac == true) ? 'checked' : '' ?>>
                    <label class="font-weight-normal" for="true">Yes</label>
                    <input type="radio" id="false" name="ac" value="0" <?= ($dept_info->ac == false) ? 'checked' : '' ?>>
                    <label class="font-weight-normal" for="false">No</label>

                <?php else : ?>

                    <input type="radio" id="true" name="ac" value="1">
                    <label class="font-weight-normal" for="true">Yes</label>
                    <input type="radio" id="false" name="ac" value="0" checked>
                    <label class="font-weight-normal" for="false">No</label>

                <?php endif; ?>

            </div>

            <div class="form-group">
                <p class="font-weight-bold"><span class="text-danger font-weight-bold">*</span> Make Available Online ? </p>

                <?php if (isset($dept_info->online)) : ?>

                    <input type="radio" id="true_o" name="online" value="1" <?= ($dept_info->online == true) ? 'checked' : '' ?>>
                    <label class="font-weight-normal" for="true_o">Yes</label>
                    <input type="radio" id="false_o" name="online" value="0" <?= ($dept_info->online == false) ? 'checked' : '' ?>>
                    <label class="font-weight-normal" for="false_o">No</label>

                <?php else : ?>

                    <input type="radio" id="true_o" name="online" value="1">
                    <label class="font-weight-normal" for="true_o">Yes</label>
                    <input type="radio" id="false_o" name="online" value="0" checked>
                    <label class="font-weight-normal" for="false_o">No</label>

                <?php endif; ?>

            </div>

            <!-- Incase of update, send the ObjectID also -->
            <?php if (isset($dept_info->_id)) : ?>

                <input type="hidden" name="object_id" value="<?= $dept_info->_id->{'$id'} ?>">

            <?php endif; ?>


            <button class="btn btn-secondary" type="submit">
                <i class="fas fa-save mr-2"></i>
                <?= $action_name ?>
            </button>

            <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/departments') ?>" role="button">Cancel</a>
            </form>



        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>