<?php
$lang = $this->lang;
//pre($all);
?>
<link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/css/bootstrap.min.css') ?>">
<script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.6.0.min.js"); ?>" defer></script>
<script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Site Texts</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Site Texts</li>
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


            <section class="text-right">

                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                    <i class="fa fa-plus-square mr-2" aria-hidden="true"></i>
                    Add New Setting
                </button>
            </section>

            <!-- The Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Setting</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= form_open('site/admin/site_text/add_text', array('class' => 'my-3', 'enctype' => 'text/plain')) ?>
                        <div class="modal-body">
                            <textarea placeholder="Enter your JSON" name="new_setting" rows="13" style="width: 100%;" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


            <?= form_open('site/admin/site_text'); ?>
            <div class="form-group mb-4">
                <label for="index" class="mr-3">
                    <span class="font-weight-bold">*Select Document</span>
                </label>
                <select name="name" id="index" required>

                    <option value="" ?> ---Please select--- </option>


                    <?php foreach ($all as $key => $names) :  ?>

                        <option value="<?= $names->name ?>"> <?= $names->name_formatted ?> </option>

                    <?php endforeach; ?>

                </select>

            </div>
            <button class="btn btn-secondary" type="submit">Search</button>
            </form>



            <?php if (!empty($name)) { ?>
                <?= form_open('site/admin/site_text/update', array('class' => 'my-3', 'enctype' => 'text/plain')); ?>


                <textarea name="jsonbox" class="d-block" style="width: 100%;" rows="10">
                        <?php echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
                    </textarea>

                <!-- <textarea class="summernote-text" name="jsonbox" ><!?php echo json_encode($results, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></textarea> -->

                <input type="hidden" name="name" value="<?= $results->name ?>">

                <button type="submit" class="btn btn-info mt-1">Update</button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>