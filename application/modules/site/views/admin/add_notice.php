
<link rel="stylesheet" href="<?= base_url('assets/site/admin/plugins/summernote/summernote-lite.min.css') ?>">

<script src="<?= base_url('assets/site/admin/plugins/summernote/summernote-lite.min.js') ?>"></script>
<script src="<?= base_url('assets/site/admin/js/add_service.js') ?>"></script>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumbs  -->
            <div class="row mb-4">
                <div class="col-sm-8">
                <h2>Add Notice</h2>
                    <p class="m-0 text-muted"><span class="font-weight-bold mr-2">Note: </span>Fields marked with <span class="text-danger font-weight-bold">*</span> are mandatory.</p>

         


                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/online"); ?>">Notices</a></li>
                        <li class="breadcrumb-item active">Add Notice</li>
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


            <!-- Tab Lists -->
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="service-tab" data-toggle="tab" href="#add-service" role="tab" aria-controls="add-service" aria-selected="true">NOTICE INFO</a>
                </li>

                
            </ul>


            <!-- Tab Contents -->
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="add-service" role="tabpanel" aria-labelledby="service-tab">

                    <?= form_open('site/admin/notice/add_notice_info_action'); ?>


                    <!-- Notice Title -->
                    <div class="form-group">
                        <label><span class="text-danger font-weight-bold">*</span> Enter Notice Name: </label>
                        <div>
                            <input name="notice_en" type="text" placeholder="english" value="" required>
                        </div>
                        <div>
                            <input name="notice_as" type="text" placeholder="assamese" value="" required>
                        </div>
                        <div>
                            <input name="notice_bn" type="text" placeholder="bangla" value="" required>
                        </div>
                    </div>

                     <!-- Notice Desc -->
                     <div class="form-group">
                        <label> Enter Notice Description: </label>
                        <div>
                            <input name="notice_desc_en" type="text" placeholder="english" value="" >
                        </div>
                        <div>
                            <input name="notice_desc_as" type="text" placeholder="assamese" value="" >
                        </div>
                        <div>
                            <input name="notice_desc_bn" type="text" placeholder="bangla" value="" >
                        </div>
                    </div>


                    <!-- New / Old -->
                    <div class="form-group">
                        <p class="font-weight-bold d-inline-block mr-3"><span class="text-danger font-weight-bold">*</span> Newly Launched ?</p>


                            <input type="radio" id="true" name="newly_launched" value="1">
                            <label class="font-weight-normal" for="true">Yes</label>
                            <input type="radio" id="false" name="newly_launched" value="0" checked>
                            <label class="font-weight-normal" for="false">No</label>


                    </div>



                    <!-- Notice apply URL -->
                    <div class="form-group">
                    <label><span class="text-danger font-weight-bold">*</span> Notice URL: </label><br>

                        <input type="text" id="service_url" name="notice_url" placeholder="notice url" value="" required>
                    </div>



                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        Add Notice
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/notice') ?>" role="button">Cancel</a>


                    </form>
                </div>


            </div>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>