<!-- Modal -->
<div class="modal fade" id="deleteModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open(base_url("site/admin/banners/delete_banners")); ?>

            <div class="modal-body">
                <h4>Delete this Banner ?</h4>

                <input type="hidden" name="doc_path" value="">
                <input type="hidden" name="lang_arr" value="">

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn btn-secondary">YES</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">NO</button>
            </div>

            </form>
        </div>
    </div>
</div>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Banners</h1>
                    <p class="my-2 text-muted">
                        <span class="font-weight-bold mr-2">Note: </span>
                        Maximum image size is 200KB.
                        Maximum image dimension is 1677x370 px.
                    </p>

                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Banners</li>
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

            <div class="tab-pane" id="upload-documents" role="tabpanel" aria-labelledby="documents-tab">


                <div class="table-responsive mb-4">

                    <table id="my-table" class="table table-sm table-hover">


                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">View</th>
                                <th scope="col">Position</th>
                                <th scope="col" colspan="2" class="invisible">Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($banner as  $key => $ln) : ?>


                                <tr>

                                    <td>
                                        <ul class="list-unstyled">

                                            <?php foreach ($ln as $k => $img_path) :  ?>

                                                <li>#BANNER <?= strtoupper($key) . ($k + 1) ?></li>

                                            <?php endforeach; ?>



                                        </ul>
                                    </td>
                                    <td>

                                        <?php foreach ($ln as $img_path) :  ?>

                                            <a class="btn btn-outline-info" role="button" href="<?= base_url($img_path) ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-alt fa-lg mr-2"></i> View</a><br>

                                        <?php endforeach; ?>

                                    </td>
                                    <td>
                                        <ul class="list-unstyled">

                                            <?php foreach ($ln as $k => $img_path) :  ?>

                                                <li>
                                                    <div class="input-group input-group-sm mb-3 " style="width: 5rem">

                                                        <input type="text" value="<?= ($k + 1) ?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" readonly>
                                                    </div>

                                                </li>

                                            <?php endforeach; ?>



                                        </ul>
                                    </td>

                                    <td>
                                        <?php foreach ($ln as $img_path) : ?>

                                            <a class="btn btn-outline-danger delete" role="button" href="#" data-doc_path="<?= $img_path ?>" data-lang="<?= $key ?>">
                                                <i class="fas fa-trash-alt fa-lg mr-2"></i> Delete</a><br>

                                        <?php endforeach; ?>


                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>

                <div class="row">
                    <div class="col">
                        <p class="lead border-bottom">Upload English Banner</p>

                        <?= form_open_multipart('site/admin/banners/upload_banners'); ?>

                        <input type="hidden" name="lang_arr" value="en">

                        <div class="form-group">

                            <div>
                                <label for="banner"><span class="text-danger font-weight-bold">*</span> Please Select a Banner:</label>
                            </div>
                            <input type="file" name="banner" required>

                        </div>

                        <div class="form-group">
                            <label for="banner"><span class="text-danger font-weight-bold">*</span> Banner Position:</label>
                            <input type="number" min="1" name="position" required>
                        </div>


                        <button class="btn btn-secondary btn-sm" type="submit">
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>

                        </form>

                    </div>
                    <div class="col">
                        <p class="lead border-bottom">Upload Assamese Banner</p>

                        <?= form_open_multipart('site/admin/banners/upload_banners'); ?>

                        <input type="hidden" name="lang_arr" value="as">

                        <div class="form-group">

                            <div><label for="banner"><span class="text-danger font-weight-bold">*</span> Please Select a Banner:</label></div>
                            <input type="file" name="banner" required>

                        </div>
                        <div class="form-group">
                            <label for="banner"><span class="text-danger font-weight-bold">*</span> Banner Position:</label>
                            <input type="number" min="1" name="position" required>
                        </div>
                        <button class="btn btn-secondary btn-sm" type="submit">
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>



                        </form>
                    </div>

                    <div class="col">
                        <p class="lead border-bottom">Upload Bengali Banner</p>

                        <?= form_open_multipart('site/admin/banners/upload_banners'); ?>

                        <input type="hidden" name="lang_arr" value="bn">

                        <div class="form-group">

                            <div>
                                <label for="banner"><span class="text-danger font-weight-bold">*</span> Please Select a Banner:</label>
                            </div>
                            <input type="file" name="banner" required>

                        </div>
                        <div class="form-group">
                            <label for="banner"><span class="text-danger font-weight-bold">*</span> Banner Position:</label>
                            <input type="number" min="1" name="position" required>
                        </div>
                        <button class="btn btn-secondary btn-sm" type="submit">
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>



                        </form>
                    </div>
                </div>

            </div>


        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>

<script>
    // Delete action handler
    $('#my-table tbody').on('click', 'a.delete', function(e) {
        e.preventDefault();

        //  console.log($(this).data('doc_path'));

        $('[name="doc_path"]').val($(this).data('doc_path'));
        $('[name="lang_arr"]').val($(this).data('lang'));

        $('#deleteModel').modal('show');
    });
</script>