<?php
// pre($notices);
?>


<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Notices</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Notices</li>
                    </ol>
                </div>
            </div>

            <?php
            $role = $this->session->userdata('designation');

            if ($role === 'System Administrator') :
            ?>

                <section class="text-right">
                    <a class="btn btn-secondary mr-2" href="<?= base_url("site/admin/notice/add_new_notice"); ?>" role="button">
                        <i class="fas fa-plus-square mr-2"></i>
                        Add New Notice
                    </a>
                </section>

            <?php endif; ?>


            <div class="mt-2">
                <!-- Action Success/Fail alert messages  -->
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <section class="mt-4">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>URL</th>
                            <th>Newly Launched</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php for ($x = 0; $x < count($notices); $x++) { ?>
                            <tr>
                                <td><?php echo $notices[$x]->title->en ?></td>
                                <td><?php echo empty($notices[$x]->desc->en) ? '<small class="rounded-pill p-2 text-dark bg-gradient-warning font-weight-bold">No description</small>' : $notices[$x]->desc->en ?></td>
                                <td><?php echo $notices[$x]->link->url ?></td>
                                <td><?php if ($notices[$x]->newly_launched  == 1) { ?>
                                        <h4> <span class="badge bg-success">Yes</span></h4>
                                    <?php } else { ?>
                                        <h4> <span class="badge bg-danger">No</span></h4>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a title="view/edit" href="<?= base_url("site/admin/notice/update/{$x}")  ?>">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>

                                    <a title="view/delete" href="<?= base_url("site/admin/notice/delete/{$x}")  ?>">
                                        <i class="far fa-trash-alt fa-lg"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>






                    </tbody>

                </table>
            </section>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable();

        // Ask before deleting a Notice
        $('a[title="view/delete"]').on('click', function(event) {
            event.preventDefault();
            if (window.confirm('Are you sure about Deleting This Notice?')) {

                // Go to deleting action
                window.location.replace($(event.currentTarget).attr('href'));
            }

        });
    });
</script>