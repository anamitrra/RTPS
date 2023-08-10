<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>


<!-- Modal -->
<div class="modal fade" id="deleteModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open(base_url("site/admin/online/delete_service")); ?>

            <div class="modal-body">
                <h4>Are You Sure about Deleting this Service ?</h4>

                <input type="hidden" name="object_id" value="">

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
                    <h1 class="m-0 text-dark">Services</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Services</li>
                    </ol>
                </div>
            </div>

            <?php
            $role = $this->session->userdata('designation');

            if ($role === 'System Administrator') :
            ?>

                <section class="text-right">
                    <a class="btn btn-secondary mr-2" href="<?= base_url("site/admin/online/add_new_service/create"); ?>" role="button">
                        <i class="fas fa-plus-square mr-2"></i>
                        Add New Service
                    </a>
                </section>

            <?php endif; ?>

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


            <section class="mt-4">
                <table id="data-table" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#Service ID</th>
                            <th>Service Name</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th>Avaliable Online</th>
                            <th>Newly Launched</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#Service ID</th>
                            <th>Service Name</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th>Avaliable Online</th>
                            <th>Newly Launched</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </section>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>

<script>
    var designation = "<?= $role ?>";
    // console.log(designation);

    $(document).ready(function() {

        const table = $('#data-table')
            .DataTable({
                pageLength: 25,
                order: [
                    [1, 'asc']
                ],
                scrollX: true,
                processing: true,
                deferRender: true,
                ajax: {
                    url: '<?= base_url("site/admin/online/all_services_api"); ?>',
                    dataSrc: ''
                },
                columns: [{
                        data: 'service_id'
                    },
                    {
                        data: 'service_name.en'
                    },
                    {
                        data: 'department.department_name.en'
                    },
                    {
                        data: 'category.cat_name.en'
                    },
                    {
                        data: 'online',
                        render: function(data, type, row, meta) {
                            return type === 'display' ?
                                (data ? '<span class="d-inline rounded-lg p-2 text-light bg-success">Yes</span>' : '<span class="d-inline rounded-lg p-2 text-light bg-danger">No</span>') :
                                data;
                        }
                    },
                    {
                        data: 'is_new',
                        render: function(data, type, row, meta) {
                            return type === 'display' ?
                                (data ? '<span class="d-inline rounded-lg p-2 text-light bg-success">Yes</span>' : '<span class="d-inline rounded-lg p-2 text-light bg-danger">No</span>') :
                                data;
                        }
                    },
                    {
                        data: '_id',
                        render: function(data, type, row, meta) {
                            let htmlStr = `<a title="view/edit" href="<?= base_url("site/admin/online/add_new_service/") ?>${data['$id']}">
                        <i class="fas fa-edit fa-lg"></i>
                        </a>`;

                            if (designation === 'System Administrator') {
                                htmlStr += `<a title="delete" class="delete" data-ob_id="${data['$id']}" href="#">
                        <i class="far fa-trash-alt fa-lg"></i>
                        </a>`;
                            }

                            return type === 'display' ? htmlStr : data;
                        }
                    },

                ],

            });


        // Delete action handler
        $('#data-table tbody').on('click', 'a.delete', function(e) {
            e.preventDefault();

            $('[name="object_id"]').val($(this).data('ob_id'));

            $('#deleteModel').modal('show');
        });


    });
</script>